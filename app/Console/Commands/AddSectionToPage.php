<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TplPage;
use App\Models\TplPageSection;
use App\Models\TplLayout;
use App\Models\Site;

class AddSectionToPage extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'page:add-section {page_slug} {template_id} {--site_id=} {--position=}';

    /**
     * The console command description.
     */
    protected $description = 'Add a template section to a page';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pageSlug = $this->argument('page_slug');
        $templateId = $this->argument('template_id');
        $siteId = $this->option('site_id') ?: 12; // Default to SEO Store site
        $position = $this->option('position');
        
        // Find the page
        $page = TplPage::where('slug', $pageSlug)
            ->where('site_id', $siteId)
            ->first();
            
        if (!$page) {
            $this->error("❌ Page '{$pageSlug}' not found for site {$siteId}");
            return;
        }
        
        // Find the template
        $template = TplLayout::where('tpl_id', $templateId)->first();
        
        if (!$template) {
            $this->error("❌ Template '{$templateId}' not found");
            return;
        }
        
        // Determine sort order
        if ($position) {
            $sortOrder = (int) $position;
        } else {
            $maxOrder = TplPageSection::where('page_id', $page->id)->max('sort_order') ?? 0;
            $sortOrder = $maxOrder + 1;
        }
        
        try {
            $pageSection = TplPageSection::create([
                'page_id' => $page->id,
                'tpl_layouts_id' => $template->id,
                'site_id' => $siteId,
                'name' => $template->name . ' on ' . $page->title,
                'content' => $template->default_config ?: '{}',
                'status' => 1,
                'sort_order' => $sortOrder
            ]);
            
            $this->info("✅ Section '{$template->name}' added to page '{$page->title}'!");
            $this->info("   Position: {$sortOrder}");
            $this->info("   Section ID: {$pageSection->id}");
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to add section: " . $e->getMessage());
        }
    }
}
