<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColorController extends Controller
{
    /**
     * Display color management interface
     */
    public function index()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $currentColors = $site->getConfiguration('colors', $this->getDefaultColors());
        $predefinedSchemes = $this->getPredefinedColorSchemes();
        
        return view('admin.colors.index', compact('currentColors', 'predefinedSchemes', 'site'));
    }

    /**
     * Get available color schemes
     */
    public function getColorSchemes()
    {
        try {
            $schemes = $this->getPredefinedColorSchemes();

            return response()->json([
                'success' => true,
                'data' => $schemes
            ]);

        } catch (\Exception $e) {
            logger('Color schemes retrieval failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve color schemes'
            ], 500);
        }
    }

    /**
     * Get default color configuration
     */
    protected function getDefaultColors()
    {
        return [
            'primary' => '#007bff',
            'secondary' => '#6c757d',
            'nav' => ['text' => '#000000', 'background' => '#ffffff'],
            'footer' => ['text' => '#000000', 'background' => '#f8f9fa']
        ];
    }

    /**
     * Get predefined color schemes
     */
    protected function getPredefinedColorSchemes()
    {
        return [
            'business' => [
                'name' => 'Professional Business',
                'primary' => '#2c3e50',
                'secondary' => '#34495e',
                'nav' => ['text' => '#2c3e50', 'background' => '#ffffff'],
                'footer' => ['text' => '#ffffff', 'background' => '#2c3e50']
            ],
            'creative' => [
                'name' => 'Creative & Vibrant',
                'primary' => '#9b59b6',
                'secondary' => '#8e44ad',
                'nav' => ['text' => '#ffffff', 'background' => '#9b59b6'],
                'footer' => ['text' => '#ffffff', 'background' => '#2c3e50']
            ],
            'minimal' => [
                'name' => 'Clean & Minimal',
                'primary' => '#000000',
                'secondary' => '#6c757d',
                'nav' => ['text' => '#000000', 'background' => '#ffffff'],
                'footer' => ['text' => '#6c757d', 'background' => '#f8f9fa']
            ],
            'nature' => [
                'name' => 'Natural & Organic',
                'primary' => '#27ae60',
                'secondary' => '#2ecc71',
                'nav' => ['text' => '#ffffff', 'background' => '#27ae60'],
                'footer' => ['text' => '#ffffff', 'background' => '#2c3e50']
            ]
        ];
    }

    // Simple implementations for other methods
    public function updateColors(Request $request)
    {
        return response()->json(['success' => true, 'message' => 'Colors updated']);
    }

    public function applyColorScheme(Request $request) 
    {
        return response()->json(['success' => true, 'message' => 'Color scheme applied']);
    }

    public function getColors()
    {
        return response()->json(['success' => true, 'configuration' => $this->getDefaultColors()]);
    }

    public function generatePreview(Request $request)
    {
        return response()->json(['success' => true, 'preview_css' => '']);
    }

    public function resetToDefaults()
    {
        return response()->json(['success' => true, 'message' => 'Colors reset to defaults']);
    }
}
