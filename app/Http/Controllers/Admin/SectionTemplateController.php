<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\TplLayout;
use App\Models\TplPageSection;
use App\Services\ConfigurationService;
use App\Services\ContentRenderingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SectionTemplateController extends Controller
{
    protected ConfigurationService $configService;
    protected ContentRenderingService $renderingService;

    public function __construct(
        ConfigurationService $configService,
        ContentRenderingService $renderingService
    ) {
        $this->configService = $configService;
        $this->renderingService = $renderingService;
    }

    /**
     * Display a listing of section templates
     */
    public function index()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        // Get all section templates
        $sectionTemplates = TplLayout::where('layout_type', 'section')
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        // Get current site's active sections configuration
        $activeSections = $site->getConfiguration('sections', [
            'active_sections' => [],
            'section_content' => []
        ]);

        return view('admin.sections.index', compact('site', 'sectionTemplates', 'activeSections'));
    }

    /**
     * Show the form for creating a new section template
     */
    public function create()
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        return view('admin.sections.create', compact('site'));
    }

    /**
     * Store a newly created section template
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'template_content' => 'required|string',
            'configurable_fields' => 'nullable|array',
            'default_config' => 'nullable|array',
            'preview_image' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        // Create new section template
        $sectionTemplate = TplLayout::create([
            'tpl_id' => 'section_' . strtolower(str_replace(' ', '_', $request->name)) . '_' . time(),
            'layout_type' => 'section',
            'name' => $request->name,
            'description' => $request->description,
            'path' => '/sections/' . strtolower(str_replace(' ', '-', $request->name)),
            'content' => [
                'html' => $request->template_content,
                'css' => $request->custom_css ?? '',
                'js' => $request->custom_js ?? ''
            ],
            'configurable_fields' => $request->configurable_fields ?? [],
            'default_config' => $request->default_config ?? [],
            'preview_image' => $request->preview_image,
            'status' => true,
            'sort_order' => TplLayout::where('layout_type', 'section')->count() + 1,
        ]);

        return response()->json([
            'message' => 'Section template created successfully.',
            'section' => $sectionTemplate
        ]);
    }

    /**
     * Display the specified section template
     */
    public function show(TplLayout $sectionTemplate)
    {
        if ($sectionTemplate->layout_type !== 'section') {
            return response()->json(['error' => 'Invalid section template.'], 404);
        }

        return response()->json(['section' => $sectionTemplate]);
    }

    /**
     * Show the form for editing the specified section template
     */
    public function edit(TplLayout $sectionTemplate)
    {
        if ($sectionTemplate->layout_type !== 'section') {
            return redirect()->route('admin.sections.index')
                ->with('error', 'Invalid section template.');
        }

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        return view('admin.sections.edit', compact('site', 'sectionTemplate'));
    }

    /**
     * Update the specified section template
     */
    public function update(Request $request, TplLayout $sectionTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'template_content' => 'required|string',
            'configurable_fields' => 'nullable|array',
            'default_config' => 'nullable|array',
            'preview_image' => 'nullable|string|max:255',
        ]);

        if ($sectionTemplate->layout_type !== 'section') {
            return response()->json(['error' => 'Invalid section template.'], 404);
        }

        $sectionTemplate->update([
            'name' => $request->name,
            'description' => $request->description,
            'content' => [
                'html' => $request->template_content,
                'css' => $request->custom_css ?? '',
                'js' => $request->custom_js ?? ''
            ],
            'configurable_fields' => $request->configurable_fields ?? [],
            'default_config' => $request->default_config ?? [],
            'preview_image' => $request->preview_image,
        ]);

        return response()->json([
            'message' => 'Section template updated successfully.',
            'section' => $sectionTemplate
        ]);
    }

    /**
     * Remove the specified section template
     */
    public function destroy(TplLayout $sectionTemplate)
    {
        if ($sectionTemplate->layout_type !== 'section') {
            return response()->json(['error' => 'Invalid section template.'], 404);
        }

        $sectionTemplate->delete();

        return response()->json(['message' => 'Section template deleted successfully.']);
    }

    /**
     * Toggle section status (active/inactive)
     */
    public function toggleStatus(Request $request)
    {
        $request->validate([
            'section_id' => 'required|string|max:50',
            'is_active' => 'required|boolean',
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        // Get current sections configuration
        $sectionsConfig = $site->getConfiguration('sections', [
            'active_sections' => [],
            'section_content' => []
        ]);

        // Update or add section status
        $found = false;
        foreach ($sectionsConfig['active_sections'] as &$section) {
            if ($section['section_id'] === $request->section_id) {
                $section['is_active'] = $request->is_active;
                $found = true;
                break;
            }
        }

        // If section not found, add it
        if (!$found) {
            $sectionsConfig['active_sections'][] = [
                'section_id' => $request->section_id,
                'is_active' => $request->is_active,
                'sort_order' => count($sectionsConfig['active_sections']) + 1
            ];
        }

        // Save configuration
        $success = $site->setConfiguration('sections', $sectionsConfig);

        if ($success) {
            return response()->json([
                'message' => 'Section status updated successfully.',
                'status' => $request->is_active ? 'active' : 'inactive'
            ]);
        }

        return response()->json(['error' => 'Failed to update section status.'], 500);
    }

    /**
     * Update section order
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.section_id' => 'required|string|max:50',
            'sections.*.sort_order' => 'required|integer|min:0',
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        // Get current sections configuration
        $sectionsConfig = $site->getConfiguration('sections', [
            'active_sections' => [],
            'section_content' => []
        ]);

        // Update sort orders
        foreach ($request->sections as $sectionData) {
            foreach ($sectionsConfig['active_sections'] as &$section) {
                if ($section['section_id'] === $sectionData['section_id']) {
                    $section['sort_order'] = $sectionData['sort_order'];
                    break;
                }
            }
        }

        // Sort sections by sort_order
        usort($sectionsConfig['active_sections'], function ($a, $b) {
            return ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0);
        });

        // Save configuration
        $success = $site->setConfiguration('sections', $sectionsConfig);

        if ($success) {
            return response()->json(['message' => 'Section order updated successfully.']);
        }

        return response()->json(['error' => 'Failed to update section order.'], 500);
    }

    /**
     * Update section content
     */
    public function updateContent(Request $request)
    {
        $request->validate([
            'section_id' => 'required|string|max:50',
            'content' => 'required|array',
            'language' => 'nullable|string|size:2',
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $language = $request->language ?? 'en';

        // Get current sections configuration
        $sectionsConfig = $site->getConfiguration('sections', [
            'active_sections' => [],
            'section_content' => []
        ]);

        // Update section content
        if (!isset($sectionsConfig['section_content'][$request->section_id])) {
            $sectionsConfig['section_content'][$request->section_id] = [];
        }

        $sectionsConfig['section_content'][$request->section_id][$language] = $request->content;

        // Save configuration
        $success = $site->setConfiguration('sections', $sectionsConfig);

        if ($success) {
            return response()->json(['message' => 'Section content updated successfully.']);
        }

        return response()->json(['error' => 'Failed to update section content.'], 500);
    }

    /**
     * Get section content for editing
     */
    public function getContent(Request $request, string $sectionId)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $language = $request->query('language', 'en');

        // Get current sections configuration
        $sectionsConfig = $site->getConfiguration('sections', [
            'active_sections' => [],
            'section_content' => []
        ]);

        $content = $sectionsConfig['section_content'][$sectionId][$language] ?? [];

        return response()->json(['content' => $content]);
    }

    /**
     * Render section preview
     */
    public function preview(Request $request, TplLayout $sectionTemplate)
    {
        if ($sectionTemplate->layout_type !== 'section') {
            return response()->json(['error' => 'Invalid section template.'], 404);
        }

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();

        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $language = $request->query('language', 'en');
        $mockContent = $request->query('content', []);

        // Render section with mock content
        $renderedHtml = $this->renderingService->renderSection(
            $sectionTemplate,
            $mockContent,
            $language,
            $site
        );

        return response()->json(['html' => $renderedHtml]);
    }

    /**
     * Get all available section templates
     */
    public function getAvailableTemplates()
    {
        $templates = TplLayout::where('layout_type', 'section')
            ->where('status', true)
            ->orderBy('sort_order')
            ->select('id', 'tpl_id', 'name', 'description', 'preview_image', 'configurable_fields', 'default_config')
            ->get();

        return response()->json(['templates' => $templates]);
    }

    /**
     * Duplicate section template
     */
    public function duplicate(TplLayout $sectionTemplate)
    {
        if ($sectionTemplate->layout_type !== 'section') {
            return response()->json(['error' => 'Invalid section template.'], 404);
        }

        $newTemplate = $sectionTemplate->replicate();
        $newTemplate->tpl_id = $sectionTemplate->tpl_id . '_copy_' . time();
        $newTemplate->name = $sectionTemplate->name . ' (Copy)';
        $newTemplate->sort_order = TplLayout::where('layout_type', 'section')->count() + 1;
        $newTemplate->save();

        return response()->json([
            'message' => 'Section template duplicated successfully.',
            'section' => $newTemplate
        ]);
    }
}
