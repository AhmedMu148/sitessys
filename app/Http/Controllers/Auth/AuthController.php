<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserTemplate;
use App\Models\ApiAccessLog;
use App\Services\TemplateCloneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $templateCloneService;

    public function __construct(TemplateCloneService $templateCloneService)
    {
        $this->templateCloneService = $templateCloneService;
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
     * Handle web registration
     */
    public function webRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'subdomain' => 'nullable|string|max:50|unique:users|regex:/^[a-z0-9-]+$/',
            'domain' => 'nullable|string|max:255|unique:users',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'subdomain' => $request->subdomain,
            'domain' => $request->domain,
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Assign default role
        $user->assignRole('admin');

        // Clone default template and create site for user
        $this->templateCloneService->cloneDefaultTemplateForUser($user);

        Auth::login($user);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Registration successful! Welcome to your dashboard.');
    }

    /**
     * Handle web login
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
            
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated.',
                ]);
            }

            $user->updateLastLogin();
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    /**
     * Handle web logout
     */
    public function webLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * API Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'subdomain' => 'nullable|string|max:50|unique:users|regex:/^[a-z0-9-]+$/',
            'domain' => 'nullable|string|max:255|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'subdomain' => $request->subdomain,
            'domain' => $request->domain,
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Assign default role
        $user->assignRole('admin');

        // Clone default template and create site for user
        $this->templateCloneService->cloneDefaultTemplateForUser($user);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user->load('roles'),
                'token' => $token,
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

        if (!$user->is_active) {
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
                'user' => $user->load('roles', 'permissions'),
                'token' => $token,
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
        return response()->json([
            'status' => 'success',
            'data' => $request->user()->load('roles', 'permissions', 'activeTemplate')
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
