<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TplLayout;

class CreateTemplate extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'template:create {name} {type=section} {--id=} {--html=} {--fields=}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new template layout';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $type = $this->argument('type');
        $id = $this->option('id') ?: 'custom-' . strtolower(str_replace(' ', '-', $name));
        
        // Get HTML content
        $html = $this->option('html');
        if (!$html) {
            $html = $this->getDefaultHtml($type);
        }
        
        // Get configurable fields
        $fields = $this->option('fields');
        if (!$fields) {
            $fields = $this->getDefaultFields();
        } else {
            $fields = json_decode($fields, true);
        }
        
        try {
            $template = TplLayout::create([
                'tpl_id' => $id,
                'layout_type' => $type,
                'name' => $name,
                'content' => $html,
                'configurable_fields' => json_encode($fields),
                'default_config' => json_encode($this->getDefaultConfig($fields)),
                'path' => 'templates/' . $type . 's/' . $id,
                'status' => true,
                'sort_order' => 0
            ]);
            
            $this->info("✅ Template '{$name}' created successfully!");
            $this->info("   ID: {$template->tpl_id}");
            $this->info("   Type: {$template->layout_type}");
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to create template: " . $e->getMessage());
        }
    }
    
    private function getDefaultHtml($type)
    {
        switch ($type) {
            case 'header':
                return '<header class="header-section">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">{{site_name}}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    {{#each menu_items}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{url}}">{{label}}</a>
                    </li>
                    {{/each}}
                </ul>
            </div>
        </div>
    </nav>
</header>';
                
            case 'footer':
                return '<footer class="footer-section bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <h5>{{site_name}}</h5>
                <p>{{description}}</p>
            </div>
            <div class="col-lg-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    {{#each footer_links}}
                    <li><a href="{{url}}" class="text-white-50">{{label}}</a></li>
                    {{/each}}
                </ul>
            </div>
            <div class="col-lg-4">
                <h5>Contact Info</h5>
                <p>{{contact_info}}</p>
            </div>
        </div>
        <hr class="my-4">
        <div class="text-center">
            <p>&copy; {{year}} {{site_name}}. All rights reserved.</p>
        </div>
    </div>
</footer>';
                
            default: // section
                return '<section class="custom-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-4 fw-bold">{{title}}</h2>
                <p class="lead">{{subtitle}}</p>
                <div class="mt-4">
                    {{content}}
                </div>
            </div>
        </div>
    </div>
</section>';
        }
    }
    
    private function getDefaultFields()
    {
        return [
            'title' => ['type' => 'text', 'default' => 'Section Title'],
            'subtitle' => ['type' => 'textarea', 'default' => 'Section subtitle'],
            'content' => ['type' => 'richtext', 'default' => 'Section content goes here'],
        ];
    }
    
    private function getDefaultConfig($fields)
    {
        $config = [];
        foreach ($fields as $key => $field) {
            $config[$key] = $field['default'] ?? '';
        }
        return $config;
    }
}
