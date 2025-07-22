<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Services\ConfigurationService;
use Illuminate\Support\Facades\Auth;

class ConfigurationTestController extends Controller
{
    protected ConfigurationService $configService;

    public function __construct(ConfigurationService $configService)
    {
        $this->configService = $configService;
    }

    /**
     * Display configuration test dashboard
     */
    public function test()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        // Get all configurations to display
        $configurations = $site->getAllConfigurations();
        
        return view('admin.configurations.test', compact('site', 'configurations'));
    }
}
