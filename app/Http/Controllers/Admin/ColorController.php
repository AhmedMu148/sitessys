<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'success' => '#198754',
            'info' => '#0dcaf0',
            'warning' => '#ffc107',
            'danger' => '#dc3545',
            'nav' => [
                'text' => '#000000',
                'background' => '#ffffff',
                'link' => null,
                'link_hover' => null,
                'button_bg' => null,
                'button_text' => null,
            ],
            'footer' => [
                'text' => '#000000',
                'background' => '#f8f9fa',
                'link' => null,
                'link_hover' => null,
            ],
            'body' => ['background' => '#ffffff', 'text' => '#212529'],
            'link' => ['color' => null, 'hover' => null],
            'section' => [
                'background' => null,
                'text' => null,
                'heading' => null,
                'link' => null,
                'link_hover' => null,
                'button_bg' => null,
                'button_text' => null,
            ],
            'buttons' => [
                'primary_text' => null,
                'secondary_text' => null,
                'success_text' => null,
                'info_text' => null,
                'warning_text' => null,
                'danger_text' => null,
            ],
        ];
    }

    /**
     * Get predefined color schemes (curated & consistent)
     */
    protected function getPredefinedColorSchemes()
    {
        return [
            // Balanced, accessible sets
            'royal_blue' => [
                'name' => 'Royal Blue',
                'primary' => '#2B6CB0', 'secondary' => '#4A5568', 'success' => '#22C55E', 'info' => '#0284C7', 'warning' => '#F59E0B', 'danger' => '#DC2626',
                'body' => ['background' => '#FFFFFF', 'text' => '#111827'],
                'link' => ['color' => '#2B6CB0', 'hover' => '#1D4ED8'],
                'nav' => ['background' => '#FFFFFF', 'text' => '#1F2937', 'link' => '#1F2937', 'link_hover' => '#2B6CB0', 'button_bg' => '#2B6CB0', 'button_text' => '#FFFFFF'],
                'footer' => ['background' => '#111827', 'text' => '#E5E7EB', 'link' => '#93C5FD', 'link_hover' => '#BFDBFE'],
                'section' => ['background' => '#F9FAFB', 'text' => '#111827', 'heading' => '#0F172A', 'link' => '#2B6CB0', 'link_hover' => '#1D4ED8', 'button_bg' => '#2B6CB0', 'button_text' => '#FFFFFF'],
                'buttons' => ['primary_text'=>'#FFFFFF','secondary_text'=>'#FFFFFF','success_text'=>'#FFFFFF','info_text'=>'#FFFFFF','warning_text'=>'#111827','danger_text'=>'#FFFFFF']
            ],
            'emerald' => [
                'name' => 'Emerald',
                'primary' => '#059669', 'secondary' => '#065F46', 'success' => '#10B981', 'info' => '#14B8A6', 'warning' => '#D97706', 'danger' => '#B91C1C',
                'body' => ['background' => '#F0FDF4', 'text' => '#064E3B'],
                'link' => ['color' => '#059669', 'hover' => '#047857'],
                'nav' => ['background' => '#065F46', 'text' => '#ECFDF5', 'link' => '#D1FAE5', 'link_hover' => '#FFFFFF', 'button_bg' => '#059669', 'button_text' => '#FFFFFF'],
                'footer' => ['background' => '#052E2B', 'text' => '#D1FAE5', 'link' => '#A7F3D0', 'link_hover' => '#ECFDF5'],
                'section' => ['background' => '#ECFDF5', 'text' => '#064E3B', 'heading' => '#065F46', 'link' => '#059669', 'link_hover' => '#047857', 'button_bg' => '#10B981', 'button_text' => '#FFFFFF'],
                'buttons' => ['primary_text'=>'#FFFFFF','secondary_text'=>'#FFFFFF','success_text'=>'#FFFFFF','info_text'=>'#FFFFFF','warning_text'=>'#111827','danger_text'=>'#FFFFFF']
            ],
            'sunset' => [
                'name' => 'Sunset',
                'primary' => '#F97316', 'secondary' => '#C2410C', 'success' => '#22C55E', 'info' => '#06B6D4', 'warning' => '#F59E0B', 'danger' => '#DC2626',
                'body' => ['background' => '#FFF7ED', 'text' => '#1F2937'],
                'link' => ['color' => '#F97316', 'hover' => '#EA580C'],
                'nav' => ['background' => '#7C2D12', 'text' => '#FFF7ED', 'link' => '#FFDAB5', 'link_hover' => '#FFFFFF', 'button_bg' => '#F97316', 'button_text' => '#FFFFFF'],
                'footer' => ['background' => '#4A1D0A', 'text' => '#FDE68A', 'link' => '#FDBA74', 'link_hover' => '#FED7AA'],
                'section' => ['background' => '#FFFBEB', 'text' => '#1F2937', 'heading' => '#7C2D12', 'link' => '#F97316', 'link_hover' => '#EA580C', 'button_bg' => '#F97316', 'button_text' => '#FFFFFF'],
                'buttons' => ['primary_text'=>'#FFFFFF','secondary_text'=>'#FFFFFF','success_text'=>'#FFFFFF','info_text'=>'#FFFFFF','warning_text'=>'#111827','danger_text'=>'#FFFFFF']
            ],
            'midnight' => [
                'name' => 'Midnight',
                'primary' => '#60A5FA', 'secondary' => '#94A3B8', 'success' => '#34D399', 'info' => '#38BDF8', 'warning' => '#FBBF24', 'danger' => '#F87171',
                'body' => ['background' => '#0B1220', 'text' => '#E5E7EB'],
                'link' => ['color' => '#93C5FD', 'hover' => '#FFFFFF'],
                'nav' => ['background' => '#0F172A', 'text' => '#E5E7EB', 'link' => '#E5E7EB', 'link_hover' => '#FFFFFF', 'button_bg' => '#60A5FA', 'button_text' => '#0B1220'],
                'footer' => ['background' => '#111827', 'text' => '#E5E7EB', 'link' => '#93C5FD', 'link_hover' => '#BFDBFE'],
                'section' => ['background' => '#0B1220', 'text' => '#E5E7EB', 'heading' => '#FFFFFF', 'link' => '#93C5FD', 'link_hover' => '#FFFFFF', 'button_bg' => '#60A5FA', 'button_text' => '#0B1220'],
                'buttons' => ['primary_text'=>'#0B1220','secondary_text'=>'#0B1220','success_text'=>'#0B1220','info_text'=>'#0B1220','warning_text'=>'#0B1220','danger_text'=>'#0B1220']
            ],
            'pastel_bloom' => [
                'name' => 'Pastel Bloom',
                'primary' => '#F472B6', 'secondary' => '#93C5FD', 'success' => '#86EFAC', 'info' => '#A5F3FC', 'warning' => '#FDE68A', 'danger' => '#FCA5A5',
                'body' => ['background' => '#FFFFFF', 'text' => '#374151'],
                'link' => ['color' => '#F472B6', 'hover' => '#EC4899'],
                'nav' => ['background' => '#FFFFFF', 'text' => '#374151', 'link' => '#374151', 'link_hover' => '#4B5563', 'button_bg' => '#F472B6', 'button_text' => '#FFFFFF'],
                'footer' => ['background' => '#F9FAFB', 'text' => '#4B5563', 'link' => '#F472B6', 'link_hover' => '#EC4899'],
                'section' => ['background' => '#FFFFFF', 'text' => '#374151', 'heading' => '#111827', 'link' => '#F472B6', 'link_hover' => '#EC4899', 'button_bg' => '#F472B6', 'button_text' => '#FFFFFF'],
                'buttons' => ['primary_text'=>'#FFFFFF','secondary_text'=>'#FFFFFF','success_text'=>'#111827','info_text'=>'#111827','warning_text'=>'#111827','danger_text'=>'#111827']
            ],
            'slate_pro' => [
                'name' => 'Slate Pro',
                'primary' => '#334155', 'secondary' => '#64748B', 'success' => '#22C55E', 'info' => '#38BDF8', 'warning' => '#F59E0B', 'danger' => '#EF4444',
                'body' => ['background' => '#F1F5F9', 'text' => '#0F172A'],
                'link' => ['color' => '#334155', 'hover' => '#1E293B'],
                'nav' => ['background' => '#E2E8F0', 'text' => '#0F172A', 'link' => '#0F172A', 'link_hover' => '#1E293B', 'button_bg' => '#334155', 'button_text' => '#FFFFFF'],
                'footer' => ['background' => '#0F172A', 'text' => '#E2E8F0', 'link' => '#94A3B8', 'link_hover' => '#CBD5E1'],
                'section' => ['background' => '#FFFFFF', 'text' => '#0F172A', 'heading' => '#111827', 'link' => '#334155', 'link_hover' => '#1E293B', 'button_bg' => '#334155', 'button_text' => '#FFFFFF'],
                'buttons' => ['primary_text'=>'#FFFFFF','secondary_text'=>'#FFFFFF','success_text'=>'#FFFFFF','info_text'=>'#FFFFFF','warning_text'=>'#111827','danger_text'=>'#FFFFFF']
            ],
            'mint_fresh' => [
                'name' => 'Mint Fresh',
                'primary' => '#14B8A6', 'secondary' => '#0D9488', 'success' => '#22C55E', 'info' => '#06B6D4', 'warning' => '#F59E0B', 'danger' => '#EF4444',
                'body' => ['background' => '#F0FDFA', 'text' => '#0F172A'],
                'link' => ['color' => '#14B8A6', 'hover' => '#0F766E'],
                'nav' => ['background' => '#ECFEFF', 'text' => '#0F172A', 'link' => '#0F172A', 'link_hover' => '#115E59', 'button_bg' => '#14B8A6', 'button_text' => '#FFFFFF'],
                'footer' => ['background' => '#134E4A', 'text' => '#E6FFFA', 'link' => '#99F6E4', 'link_hover' => '#CCFBF1'],
                'section' => ['background' => '#FFFFFF', 'text' => '#0F172A', 'heading' => '#115E59', 'link' => '#14B8A6', 'link_hover' => '#0F766E', 'button_bg' => '#14B8A6', 'button_text' => '#FFFFFF'],
                'buttons' => ['primary_text'=>'#FFFFFF','secondary_text'=>'#FFFFFF','success_text'=>'#FFFFFF','info_text'=>'#FFFFFF','warning_text'=>'#111827','danger_text'=>'#FFFFFF']
            ],
            'crimson_edge' => [
                'name' => 'Crimson Edge',
                'primary' => '#DC2626', 'secondary' => '#4B5563', 'success' => '#16A34A', 'info' => '#0EA5E9', 'warning' => '#F59E0B', 'danger' => '#B91C1C',
                'body' => ['background' => '#FFFFFF', 'text' => '#111827'],
                'link' => ['color' => '#DC2626', 'hover' => '#991B1B'],
                'nav' => ['background' => '#111827', 'text' => '#E5E7EB', 'link' => '#E5E7EB', 'link_hover' => '#FFFFFF', 'button_bg' => '#DC2626', 'button_text' => '#FFFFFF'],
                'footer' => ['background' => '#0B0F19', 'text' => '#CBD5E1', 'link' => '#FCA5A5', 'link_hover' => '#F87171'],
                'section' => ['background' => '#FFF1F2', 'text' => '#111827', 'heading' => '#7F1D1D', 'link' => '#DC2626', 'link_hover' => '#991B1B', 'button_bg' => '#DC2626', 'button_text' => '#FFFFFF'],
                'buttons' => ['primary_text'=>'#FFFFFF','secondary_text'=>'#FFFFFF','success_text'=>'#FFFFFF','info_text'=>'#FFFFFF','warning_text'=>'#111827','danger_text'=>'#FFFFFF']
            ],

            // New curated additions
            'ocean_wave' => [
                'name' => 'Ocean Wave',
                'primary' => '#0EA5E9', 'secondary' => '#155E75', 'success' => '#10B981', 'info' => '#22D3EE', 'warning' => '#F59E0B', 'danger' => '#EF4444',
                'body' => ['background' => '#F8FAFC', 'text' => '#0F172A'],
                'link' => ['color' => '#0EA5E9', 'hover' => '#0369A1'],
                'nav' => ['background' => '#0C4A6E', 'text' => '#E2E8F0', 'link' => '#E2E8F0', 'link_hover' => '#FFFFFF', 'button_bg' => '#0EA5E9', 'button_text' => '#0B1220'],
                'footer' => ['background' => '#082F49', 'text' => '#E2E8F0', 'link' => '#7DD3FC', 'link_hover' => '#BAE6FD'],
                'section' => ['background' => '#ECFEFF', 'text' => '#0F172A', 'heading' => '#0C4A6E', 'link' => '#0EA5E9', 'link_hover' => '#0369A1', 'button_bg' => '#0EA5E9', 'button_text' => '#0B1220'],
                'buttons' => ['primary_text'=>'#0B1220','secondary_text'=>'#FFFFFF','success_text'=>'#0B1220','info_text'=>'#0B1220','warning_text'=>'#0B1220','danger_text'=>'#FFFFFF']
            ],
            'orchid_blush' => [
                'name' => 'Orchid Blush',
                'primary' => '#A855F7', 'secondary' => '#7C3AED', 'success' => '#22C55E', 'info' => '#06B6D4', 'warning' => '#F59E0B', 'danger' => '#EF4444',
                'body' => ['background' => '#FCF7FF', 'text' => '#3F3D56'],
                'link' => ['color' => '#A855F7', 'hover' => '#7C3AED'],
                'nav' => ['background' => '#FFFFFF', 'text' => '#3F3D56', 'link' => '#3F3D56', 'link_hover' => '#7C3AED', 'button_bg' => '#A855F7', 'button_text' => '#FFFFFF'],
                'footer' => ['background' => '#3F3D56', 'text' => '#EDE9FE', 'link' => '#C4B5FD', 'link_hover' => '#DDD6FE'],
                'section' => ['background' => '#FFFFFF', 'text' => '#3F3D56', 'heading' => '#2E1065', 'link' => '#A855F7', 'link_hover' => '#7C3AED', 'button_bg' => '#A855F7', 'button_text' => '#FFFFFF'],
                'buttons' => ['primary_text'=>'#FFFFFF','secondary_text'=>'#FFFFFF','success_text'=>'#FFFFFF','info_text'=>'#FFFFFF','warning_text'=>'#111827','danger_text'=>'#FFFFFF']
            ],
            'sandstone' => [
                'name' => 'Sandstone',
                'primary' => '#D97706', 'secondary' => '#7C3F00', 'success' => '#16A34A', 'info' => '#0EA5E9', 'warning' => '#F59E0B', 'danger' => '#B91C1C',
                'body' => ['background' => '#FFFBEB', 'text' => '#3F2D1C'],
                'link' => ['color' => '#B45309', 'hover' => '#92400E'],
                'nav' => ['background' => '#3F2D1C', 'text' => '#FDE68A', 'link' => '#FDE68A', 'link_hover' => '#FFFFFF', 'button_bg' => '#D97706', 'button_text' => '#1F2937'],
                'footer' => ['background' => '#2A2016', 'text' => '#F3F4F6', 'link' => '#FCD34D', 'link_hover' => '#FBBF24'],
                'section' => ['background' => '#FFF7ED', 'text' => '#3F2D1C', 'heading' => '#7C3F00', 'link' => '#B45309', 'link_hover' => '#92400E', 'button_bg' => '#D97706', 'button_text' => '#1F2937'],
                'buttons' => ['primary_text'=>'#1F2937','secondary_text'=>'#FFFFFF','success_text'=>'#FFFFFF','info_text'=>'#FFFFFF','warning_text'=>'#1F2937','danger_text'=>'#FFFFFF']
            ],
            'forest_night' => [
                'name' => 'Forest Night',
                'primary' => '#16A34A', 'secondary' => '#166534', 'success' => '#22C55E', 'info' => '#10B981', 'warning' => '#EAB308', 'danger' => '#DC2626',
                'body' => ['background' => '#0B1220', 'text' => '#E2E8F0'],
                'link' => ['color' => '#86EFAC', 'hover' => '#BBF7D0'],
                'nav' => ['background' => '#052E2B', 'text' => '#ECFDF5', 'link' => '#D1FAE5', 'link_hover' => '#FFFFFF', 'button_bg' => '#16A34A', 'button_text' => '#052E2B'],
                'footer' => ['background' => '#041F1C', 'text' => '#C7F9CC', 'link' => '#86EFAC', 'link_hover' => '#BBF7D0'],
                'section' => ['background' => '#052E2B', 'text' => '#E2E8F0', 'heading' => '#C7F9CC', 'link' => '#86EFAC', 'link_hover' => '#BBF7D0', 'button_bg' => '#16A34A', 'button_text' => '#052E2B'],
                'buttons' => ['primary_text'=>'#052E2B','secondary_text'=>'#0B1220','success_text'=>'#052E2B','info_text'=>'#052E2B','warning_text'=>'#052E2B','danger_text'=>'#0B1220']
            ],
            'mono_light' => [
                'name' => 'Mono Light',
                'primary' => '#111827', 'secondary' => '#6B7280', 'success' => '#16A34A', 'info' => '#0EA5E9', 'warning' => '#F59E0B', 'danger' => '#EF4444',
                'body' => ['background' => '#FFFFFF', 'text' => '#111827'],
                'link' => ['color' => '#111827', 'hover' => '#374151'],
                'nav' => ['background' => '#FFFFFF', 'text' => '#111827', 'link' => '#111827', 'link_hover' => '#374151', 'button_bg' => '#111827', 'button_text' => '#FFFFFF'],
                'footer' => ['background' => '#111827', 'text' => '#E5E7EB', 'link' => '#E5E7EB', 'link_hover' => '#FFFFFF'],
                'section' => ['background' => '#F9FAFB', 'text' => '#111827', 'heading' => '#0F172A', 'link' => '#111827', 'link_hover' => '#374151', 'button_bg' => '#111827', 'button_text' => '#FFFFFF'],
                'buttons' => ['primary_text'=>'#FFFFFF','secondary_text'=>'#FFFFFF','success_text'=>'#FFFFFF','info_text'=>'#FFFFFF','warning_text'=>'#111827','danger_text'=>'#FFFFFF']
            ],
            'mono_dark' => [
                'name' => 'Mono Dark',
                'primary' => '#F3F4F6', 'secondary' => '#9CA3AF', 'success' => '#86EFAC', 'info' => '#BAE6FD', 'warning' => '#FDE68A', 'danger' => '#FCA5A5',
                'body' => ['background' => '#0B1220', 'text' => '#E5E7EB'],
                'link' => ['color' => '#F3F4F6', 'hover' => '#FFFFFF'],
                'nav' => ['background' => '#111827', 'text' => '#E5E7EB', 'link' => '#E5E7EB', 'link_hover' => '#FFFFFF', 'button_bg' => '#F3F4F6', 'button_text' => '#111827'],
                'footer' => ['background' => '#0F172A', 'text' => '#E5E7EB', 'link' => '#D1D5DB', 'link_hover' => '#FFFFFF'],
                'section' => ['background' => '#0B1220', 'text' => '#E5E7EB', 'heading' => '#FFFFFF', 'link' => '#F3F4F6', 'link_hover' => '#FFFFFF', 'button_bg' => '#F3F4F6', 'button_text' => '#111827'],
                'buttons' => ['primary_text'=>'#111827','secondary_text'=>'#111827','success_text'=>'#111827','info_text'=>'#111827','warning_text'=>'#111827','danger_text'=>'#111827']
            ],
        ];
    }

    // Update colors configuration and persist site-wide
    public function updateColors(Request $request)
    {
        $request->validate([
            'colors' => 'required|array',
            'colors.primary' => ['required','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.secondary' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.success' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.info' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.warning' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.danger' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],

            'colors.nav.background' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.nav.text' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.nav.link' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.nav.link_hover' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.nav.button_bg' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.nav.button_text' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],

            'colors.footer.background' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.footer.text' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.footer.link' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.footer.link_hover' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],

            'colors.body.background' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.body.text' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],

            'colors.link.color' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.link.hover' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],

            'colors.section.background' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.section.text' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.section.heading' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.section.link' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.section.link_hover' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.section.button_bg' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.section.button_text' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],

            'colors.buttons.primary_text' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.buttons.secondary_text' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.buttons.success_text' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.buttons.info_text' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.buttons.warning_text' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],
            'colors.buttons.danger_text' => ['nullable','regex:/^#[a-fA-F0-9]{6}$/'],

            'colors.raw_css' => 'nullable|string'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        if (!$site) {
            return response()->json(['success' => false, 'message' => 'No active site found'], 404);
        }

        $colors = $request->input('colors', []);

        // Optional: parse pasted Bootstrap.build CSS and merge
        if (!empty($colors['raw_css'])) {
            $parsed = $this->parseBootstrapBuildCss($colors['raw_css']);
            $colors = array_replace_recursive($colors, $parsed);
        }

        $saved = $site->setConfiguration('colors', $colors);
        if ($saved) {
            // clear any rendering cache if used
            $site->clearConfigurationCache('colors');
            return response()->json(['success' => true, 'message' => 'Colors updated', 'colors' => $colors]);
        }

        return response()->json(['success' => false, 'message' => 'Failed to update colors'], 500);
    }

    // Apply a predefined color scheme site-wide or a custom scheme key set
    public function applyColorScheme(Request $request) 
    {
        $request->validate([
            'scheme' => 'required|string',
            'raw_css' => 'nullable|string'
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        if (!$site) {
            return response()->json(['success' => false, 'message' => 'No active site found'], 404);
        }

        $schemeKey = $request->input('scheme');
        $schemes = $this->getPredefinedColorSchemes();
        $colors = $schemes[$schemeKey] ?? null;

        if (!$colors) {
            return response()->json(['success' => false, 'message' => 'Unknown scheme'], 422);
        }

        // Merge any pasted Bootstrap.build CSS
        if ($request->filled('raw_css')) {
            $parsed = $this->parseBootstrapBuildCss($request->input('raw_css'));
            $colors = array_replace_recursive($colors, $parsed);
        }

        $saved = $site->setConfiguration('colors', $colors);
        if ($saved) {
            $site->clearConfigurationCache('colors');
            return response()->json(['success' => true, 'message' => 'Color scheme applied', 'colors' => $colors]);
        }

        return response()->json(['success' => false, 'message' => 'Failed to apply scheme'], 500);
    }

    // Return current colors
    public function getColors()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        if (!$site) {
            return response()->json(['success' => false, 'message' => 'No active site found'], 404);
        }
        $colors = $site->getConfiguration('colors', $this->getDefaultColors());
        return response()->json(['success' => true, 'configuration' => $colors]);
    }

    // Build CSS preview from colors or raw CSS
    public function generatePreview(Request $request)
    {
        $request->validate([
            'colors' => 'nullable|array',
            'raw_css' => 'nullable|string'
        ]);

        $colors = $request->input('colors', $this->getDefaultColors());
        if ($request->filled('raw_css')) {
            $parsed = $this->parseBootstrapBuildCss($request->input('raw_css'));
            $colors = array_replace_recursive($colors, $parsed);
        }

        $css = $this->buildCssFromColors($colors);
        return response()->json(['success' => true, 'preview_css' => $css]);
    }

    public function resetToDefaults()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        if (!$site) {
            return response()->json(['success' => false, 'message' => 'No active site found'], 404);
        }

        $defaults = $this->getDefaultColors();
        $saved = $site->setConfiguration('colors', $defaults);
        if ($saved) {
            $site->clearConfigurationCache('colors');
            return response()->json(['success' => true, 'message' => 'Colors reset to defaults']);
        }
        return response()->json(['success' => false, 'message' => 'Failed to reset']);
    }

    /**
     * Parse CSS exported from bootstrap.build and map to our config structure
     */
    protected function parseBootstrapBuildCss(string $rawCss): array
    {
        $result = [];
        if (preg_match_all('/--bs-([a-z0-9\-]+)\s*:\s*([^;]+);/i', $rawCss, $m, PREG_SET_ORDER)) {
            foreach ($m as $match) {
                $name = strtolower(trim($match[1]));
                $value = $this->normalizeColor(trim($match[2]));
                if (!$value) continue;
                switch ($name) {
                    case 'primary':
                    case 'secondary':
                    case 'success':
                    case 'info':
                    case 'warning':
                    case 'danger':
                        $result[$name] = $value; break;
                    case 'link-color':
                        $result['link']['color'] = $value; break;
                    case 'link-hover-color':
                        $result['link']['hover'] = $value; break;
                    case 'body-bg':
                        $result['body']['background'] = $value; break;
                    case 'body-color':
                        $result['body']['text'] = $value; break;
                }
            }
        }
        return $result;
    }

    /**
     * Build CSS string from colors configuration
     */
    protected function buildCssFromColors(array $colors): string
    {
        $c = array_replace_recursive($this->getDefaultColors(), $colors);

        $css = ":root{\n" .
            "  --bs-primary: {$c['primary']};\n" .
            "  --bs-secondary: {$c['secondary']};\n" .
            "  --bs-success: {$c['success']};\n" .
            "  --bs-info: {$c['info']};\n" .
            "  --bs-warning: {$c['warning']};\n" .
            "  --bs-danger: {$c['danger']};\n" .
            "  --sps-body-bg: {$c['body']['background']};\n" .
            "  --sps-body-text: {$c['body']['text']};\n" .
            "  --sps-link-color: " . ($c['link']['color'] ?? $c['primary']) . ";\n" .
            "  --sps-link-hover: " . ($c['link']['hover'] ?? $c['secondary']) . ";\n" .
            "  --sps-nav-bg: " . ($c['nav']['background'] ?? '#ffffff') . ";\n" .
            "  --sps-nav-text: " . ($c['nav']['text'] ?? '#000000') . ";\n" .
            "  --sps-nav-link: " . ($c['nav']['link'] ?? ($c['nav']['text'] ?? '#000000')) . ";\n" .
            "  --sps-nav-link-hover: " . ($c['nav']['link_hover'] ?? $c['primary']) . ";\n" .
            "  --sps-nav-btn-bg: " . ($c['nav']['button_bg'] ?? $c['primary']) . ";\n" .
            "  --sps-nav-btn-text: " . ($c['nav']['button_text'] ?? '#ffffff') . ";\n" .
            "  --sps-footer-bg: " . ($c['footer']['background'] ?? '#f8f9fa') . ";\n" .
            "  --sps-footer-text: " . ($c['footer']['text'] ?? '#000000') . ";\n" .
            "  --sps-footer-link: " . ($c['footer']['link'] ?? ($c['primary'] ?? '#007bff')) . ";\n" .
            "  --sps-footer-link-hover: " . ($c['footer']['link_hover'] ?? ($c['secondary'] ?? '#6c757d')) . ";\n" .
            "  --sps-section-bg: " . ($c['section']['background'] ?? 'transparent') . ";\n" .
            "  --sps-section-text: " . ($c['section']['text'] ?? 'inherit') . ";\n" .
            "  --sps-section-heading: " . ($c['section']['heading'] ?? 'inherit') . ";\n" .
            "  --sps-section-link: " . ($c['section']['link'] ?? ($c['link']['color'] ?? $c['primary'])) . ";\n" .
            "  --sps-section-link-hover: " . ($c['section']['link_hover'] ?? ($c['link']['hover'] ?? $c['secondary'])) . ";\n" .
            "  --sps-section-btn-bg: " . ($c['section']['button_bg'] ?? $c['primary']) . ";\n" .
            "  --sps-section-btn-text: " . ($c['section']['button_text'] ?? '#ffffff') . ";\n" .
            "  --sps-btn-primary-text: " . ($c['buttons']['primary_text'] ?? '#ffffff') . ";\n" .
            "  --sps-btn-secondary-text: " . ($c['buttons']['secondary_text'] ?? '#ffffff') . ";\n" .
            "  --sps-btn-success-text: " . ($c['buttons']['success_text'] ?? '#ffffff') . ";\n" .
            "  --sps-btn-info-text: " . ($c['buttons']['info_text'] ?? '#ffffff') . ";\n" .
            "  --sps-btn-warning-text: " . ($c['buttons']['warning_text'] ?? '#000000') . ";\n" .
            "  --sps-btn-danger-text: " . ($c['buttons']['danger_text'] ?? '#ffffff') . ";\n" .
            "}\n";

        $css .= <<<CSS
body{background-color:var(--sps-body-bg)!important;color:var(--sps-body-text)!important;}
a{color:var(--sps-link-color);} a:hover{color:var(--sps-link-hover);} 
.navbar,.navbar-wrapper,header.navbar,.navbar.navbar-expand{background-color:var(--sps-nav-bg)!important;color:var(--sps-nav-text)!important;}
.navbar .nav-link{color:var(--sps-nav-link)!important;} .navbar .nav-link:hover{color:var(--sps-nav-link-hover)!important;}
.navbar .btn,.navbar .btn-primary{background-color:var(--sps-nav-btn-bg)!important;border-color:var(--sps-nav-btn-bg)!important;color:var(--sps-nav-btn-text)!important;}
footer,.footer{background-color:var(--sps-footer-bg)!important;color:var(--sps-footer-text)!important;}
footer a,.footer a{color:var(--sps-footer-link)!important;} footer a:hover,.footer a:hover{color:var(--sps-footer-link-hover)!important;}
section,.section,.tpl-section{background-color:var(--sps-section-bg);color:var(--sps-section-text);} 
section h1,section h2,section h3,section h4,section h5,section h6,.section h1,.section h2,.section h3,.section h4,.section h5,.section h6{color:var(--sps-section-heading);} 
section a,.section a{color:var(--sps-section-link);} section a:hover,.section a:hover{color:var(--sps-section-link-hover);} 
section .btn-primary,.section .btn{background-color:var(--sps-section-btn-bg)!important;border-color:var(--sps-section-btn-bg)!important;color:var(--sps-section-btn-text)!important;}
.btn-primary{color:var(--sps-btn-primary-text)!important;} 
.btn-secondary{color:var(--sps-btn-secondary-text)!important;} 
.btn-success{color:var(--sps-btn-success-text)!important;} 
.btn-info{color:var(--sps-btn-info-text)!important;} 
.btn-warning{color:var(--sps-btn-warning-text)!important;} 
.btn-danger{color:var(--sps-btn-danger-text)!important;}
CSS;

        return $css;
    }

    /** Normalize a CSS color value */
    protected function normalizeColor(?string $value): ?string
    {
        if (!$value) return null;
        $value = trim($value);
        if (preg_match('/^#([0-9a-fA-F]{6})$/', $value, $m)) {
            return '#' . strtoupper($m[1]);
        }
        if (preg_match('/^#([0-9a-fA-F]{3})$/', $value, $m)) {
            $h = strtoupper($m[1]);
            return '#' . $h[0] . $h[0] . $h[1] . $h[1] . $h[2] . $h[2];
        }
        if (preg_match('/rgba?\((\s*\d+\s*),(\s*\d+\s*),(\s*\d+\s*)(?:,\s*[0-9\.]+\s*)?\)/i', $value, $m)) {
            $r = max(0, min(255, (int)trim($m[1])));
            $g = max(0, min(255, (int)trim($m[2])));
            $b = max(0, min(255, (int)trim($m[3])));
            return sprintf('#%02X%02X%02X', $r, $g, $b);
        }
        if (preg_match('/^[a-zA-Z]+$/', $value)) {
            return strtolower($value);
        }
        return null;
    }
}
