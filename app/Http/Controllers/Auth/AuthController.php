<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserTemplate;
use App\Models\ApiAccessLog;
use App\Services\TemplateCloneService;
use App\Services\DefaultTemplateAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $templateCloneService;
    protected $defaultTemplateService;

    public function __construct(
        TemplateCloneService $templateCloneService = null,
        DefaultTemplateAssignmentService $defaultTemplateService = null
    ) {
        $this->templateCloneService = $templateCloneService;
        $this->defaultTemplateService = $defaultTemplateService;
    }
    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Show admin login form
     */
    public function showAdminLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle web registration
     * In single-site architecture, regular users register as 'user' role
     * Only super-admin can create admin/team-member accounts
     */
    public function webRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // In single-site architecture, new registrations are regular users
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'subdomain' => null, // No subdomain for regular users
            'domain' => null,    // No custom domain for regular users
            'role' => 'user',    // Default role for public registration
            'is_active' => true,
        ]);

        // Assign user role
        $user->assignRole('user');

        // No template cloning for regular users in single-site architecture
        // They access the main site content

        Auth::login($user);

        // Redirect based on role
        if ($user->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Registration successful! Welcome to your dashboard.');
        } else {
            return redirect()->route('welcome')
                ->with('success', 'Registration successful! Welcome to the site.');
        }
    }

    /**
     * Handle web login (regular users only)
     */
    public function webLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Check if user is an admin - redirect them to admin login
            if ($user->hasAnyRole(['super-admin', 'admin', 'team-member'])) {
                Auth::logout();
                return redirect()->route('admin.login')->withErrors([
                    'email' => 'Admin users must login through the admin portal.',
                ])->withInput($request->except('password'));
            }
            
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated.',
                ]);
            }

            $user->updateLastLogin();
            $request->session()->regenerate();

            // Regular users go to user dashboard
            return redirect()->intended('/dashboard')
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    /**
     * Handle admin login
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Check if user is actually an admin/team member
            if (!$user->hasAnyRole(['super-admin', 'admin', 'team-member'])) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Access denied. This login is for administrators only.',
                ])->withInput($request->except('password'));
            }
            
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your admin account has been deactivated.',
                ]);
            }

            $user->updateLastLogin();
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our admin records.',
        ])->withInput($request->except('password'));
    }

    /**
     * Handle web logout
     */
    public function webLogout(Request $request)
    {
        $user = Auth::user();
        $wasAdmin = $user && $user->hasAnyRole(['super-admin', 'admin', 'team-member']);
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect based on where they came from
        if ($wasAdmin) {
            return redirect()->route('admin.login')
                ->with('success', 'You have been successfully logged out from admin panel.');
        } else {
            return redirect()->route('login')
                ->with('success', 'You have been successfully logged out.');
        }
    }

    /**
     * API Register a new admin user (Site Owner)
     * Creates a new site with default templates and configurations
     */
    public function registerAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'site_name' => 'required|string|max:255',
            'domains' => 'required|array|min:1',
            'domains.*' => 'required|string|max:255',
            'subdomains' => 'nullable|array',
            'subdomains.*' => 'string|max:100',
            'language' => 'nullable|string|size:2|in:en,ar,es,fr',
            'business_type' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create the admin user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'status_id' => true,
                'preferred_language' => $request->language ?? 'en',
            ]);

            // Use DefaultTemplateAssignmentService to create site with templates
            $templateService = app(\App\Services\DefaultTemplateAssignmentService::class);
            $domains = $request->domains ?? ['localhost', '127.0.0.1:8000', 'phplaravel-1399496-5687062.cloudwaysapps.com'];
            $subdomains = $request->subdomains ?? [];
            
            $site = $templateService->assignDefaultTemplates($user, $domains, $subdomains);

            // Set additional site configuration data
            $config = $site->config;
            if ($config) {
                $data = is_string($config->data) ? json_decode($config->data, true) : ($config->data ?? []);
                $data['business_type'] = $request->business_type ?? 'seo';
                $data['language'] = $request->language ?? 'en';
                $data['site_name'] = $request->site_name;
                $config->data = $data;
                $config->save();
            }

            DB::commit();

            $token = $user->createToken('admin-auth-token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Admin registered successfully with default SEO templates',
                'data' => [
                    'user' => $user,
                    'site' => [
                        'id' => $site->id,
                        'site_name' => $site->site_name,
                        'domains' => $site->domains,
                        'subdomains' => $site->subdomains,
                        'business_type' => $request->business_type ?? 'seo',
                        'language' => $request->language ?? 'en',
                        'templates_assigned' => true,
                        'pages_created' => 5,
                        'default_sections' => ['header', 'hero', 'services', 'about', 'contact', 'footer']
                    ],
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'admin_panel_url' => url("/admin?site_id=" . $site->id),
                    'site_url' => $site->url,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create admin account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API Register a new user
     * In single-site architecture, API registration creates regular users
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // API registration creates regular users in single-site architecture
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status_id' => true,
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 201);
    }

    /**
     * API Login user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->isActive()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Account is inactive. Please contact administrator.'
            ], 403);
        }

        // Update last login
        $user->updateLastLogin();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }

    /**
     * API Logout user
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get current user
     */
    public function user(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status_id' => $user->status_id,
                'preferred_language' => $user->preferred_language,
                'is_active' => $user->isActive(),
                'is_admin' => $user->isAdmin(),
                'is_super_admin' => $user->isSuperAdmin(),
                'display_name' => $user->getDisplayName(),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ]);
    }

    /**
     * Revoke all tokens for user
     */
    public function revokeAllTokens(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'All tokens revoked successfully'
        ]);
    }
}
