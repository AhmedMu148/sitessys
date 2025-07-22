<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Site;
use App\Models\TplPage;
use App\Models\TplPageSection;
use App\Models\TplLayout;

class ShowSiteInfo extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'site:info {site_id} {--pages} {--templates} {--sections}';

    /**
     * The console command description.
     */
    protected $description = 'Show detailed information about a site';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $siteId = $this->argument('site_id');
        $site = Site::find($siteId);
        
        if (!$site) {
            $this->error("❌ Site with ID {$siteId} not found");
            return;
        }
        
        $this->info("🏢 Site Information:");
        $this->line("   ID: {$site->id}");
        $this->line("   Name: {$site->site_name}");
        $this->line("   URL: {$site->url}");
        $this->line("   Status: " . ($site->status_id ? 'Active' : 'Inactive'));
        $this->line("   Domains: " . json_encode($site->domains));
        $this->line("   Subdomains: " . json_encode($site->subdomains));
        $this->line("   Created: {$site->created_at}");
        
        if ($this->option('pages') || !$this->hasOptions()) {
            $this->showPages($site);
        }
        
        if ($this->option('templates') || !$this->hasOptions()) {
            $this->showTemplates();
        }
        
        if ($this->option('sections') || !$this->hasOptions()) {
            $this->showSections($site);
        }
    }
    
    private function hasOptions(): bool
    {
        return $this->option('pages') || $this->option('templates') || $this->option('sections');
    }
    
    private function showPages(Site $site): void
    {
        $this->line("");
        $this->info("📄 Pages:");
        
        $pages = TplPage::where('site_id', $site->id)->get();
        
        if ($pages->isEmpty()) {
            $this->line("   No pages found");
            return;
        }
        
        foreach ($pages as $page) {
            $sectionsCount = TplPageSection::where('page_id', $page->id)->count();
            $this->line("   - {$page->name} (/{$page->slug}) - {$sectionsCount} sections");
        }
    }
    
    private function showTemplates(): void
    {
        $this->line("");
        $this->info("🎨 Available Default Templates:");
        
        $templates = TplLayout::whereIn('tpl_id', [
            'default-seo-header',
            'default-seo-footer', 
            'default-seo-hero',
            'default-seo-services',
            'default-seo-about',
            'default-seo-contact'
        ])->get();
        
        foreach ($templates as $template) {
            $status = $template->status ? '✅' : '❌';
            $this->line("   {$status} {$template->name} ({$template->layout_type})");
        }
    }
    
    private function showSections(Site $site): void
    {
        $this->line("");
        $this->info("🧩 Page Sections:");
        
        $sections = TplPageSection::where('site_id', $site->id)
            ->with(['page', 'layout'])
            ->orderBy('page_id')
            ->orderBy('sort_order')
            ->get();
            
        if ($sections->isEmpty()) {
            $this->line("   No sections found");
            return;
        }
        
        $currentPageId = null;
        foreach ($sections as $section) {
            if ($currentPageId !== $section->page_id) {
                $currentPageId = $section->page_id;
                $this->line("");
                $this->line("   📄 {$section->page->name}:");
            }
            
            $status = $section->status ? '✅' : '❌';
            $this->line("      {$status} [{$section->sort_order}] {$section->layout->name}");
        }
    }
}
