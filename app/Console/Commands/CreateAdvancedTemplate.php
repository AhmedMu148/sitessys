<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TplLayout;

class CreateAdvancedTemplate extends Command
{
    protected $signature = 'template:advanced 
                           {name : The template name}
                           {type : The template type (header|footer|section)}
                           {--file= : Path to HTML file}
                           {--config= : Path to JSON config file}
                           {--blade= : Create as Blade template}';

    protected $description = 'Create advanced templates with file imports and Blade support';

    public function handle()
    {
        $name = $this->argument('name');
        $type = $this->argument('type');
        
        // Validate type
        if (!in_array($type, ['header', 'footer', 'section'])) {
            $this->error('Invalid type. Use: header, footer, or section');
            return 1;
        }

        // Get HTML content
        $htmlContent = $this->getHtmlContent();
        
        // Get configuration
        $config = $this->getConfiguration();
        
        // Generate unique tpl_id
        $tplId = strtolower(str_replace(' ', '-', $name)) . '-' . time();
        
        try {
            // Create template in database
            $template = TplLayout::create([
                'tpl_id' => $tplId,
                'layout_type' => $type,
                'name' => $name,
                'description' => "Advanced {$type} template: {$name}",
                'content' => [
                    'html' => $htmlContent,
                    'css' => $config['css'] ?? '',
                    'js' => $config['js'] ?? ''
                ],
                'configurable_fields' => $config['fields'] ?? [],
                'default_config' => $config['defaults'] ?? [],
                'path' => "frontend.templates.{$type}s.{$tplId}",
                'status' => true,
                'sort_order' => TplLayout::where('layout_type', $type)->count() + 1
            ]);

            // Create Blade file if requested
            if ($this->option('blade')) {
                $this->createBladeFile($template, $htmlContent);
            }

            $this->info("✅ Advanced template '{$name}' created successfully!");
            $this->info("   ID: {$template->tpl_id}");
            $this->info("   Type: {$template->layout_type}");
            $this->info("   Database record: ✓");
            
            if ($this->option('blade')) {
                $this->info("   Blade file: ✓");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Failed to create template: " . $e->getMessage());
            return 1;
        }
    }

    private function getHtmlContent()
    {
        $filePath = $this->option('file');
        
        if ($filePath && file_exists($filePath)) {
            $this->info("📄 Loading HTML from file: {$filePath}");
            return file_get_contents($filePath);
        }

        // Prompt for HTML content
        $this->info("Enter HTML content (type 'END' on a new line to finish):");
        $content = '';
        while (true) {
            $line = $this->ask('');
            if (strtoupper(trim($line)) === 'END') {
                break;
            }
            $content .= $line . "\n";
        }

        return trim($content);
    }

    private function getConfiguration()
    {
        $configPath = $this->option('config');
        
        if ($configPath && file_exists($configPath)) {
            $this->info("⚙️ Loading configuration from: {$configPath}");
            return json_decode(file_get_contents($configPath), true);
        }

        // Default configuration
        return [
            'fields' => [
                'title' => ['type' => 'text', 'default' => 'Default Title', 'label' => 'Title'],
                'background_color' => ['type' => 'color', 'default' => '#ffffff', 'label' => 'Background Color']
            ],
            'defaults' => [
                'title' => 'Default Title',
                'background_color' => '#ffffff'
            ],
            'css' => '',
            'js' => ''
        ];
    }

    private function createBladeFile($template, $htmlContent)
    {
        $type = $template->layout_type;
        $filename = $template->tpl_id . '.blade.php';
        
        // Determine directory
        $directory = match($type) {
            'header' => 'resources/views/frontend/templates/headers/',
            'footer' => 'resources/views/frontend/templates/footers/',
            'section' => 'resources/views/frontend/templates/sections/',
            default => 'resources/views/frontend/templates/'
        };

        // Create directory if it doesn't exist
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Create Blade template content
        $bladeContent = $this->generateBladeTemplate($template, $htmlContent);
        
        // Write file
        $fullPath = $directory . $filename;
        file_put_contents($fullPath, $bladeContent);
        
        $this->info("📝 Blade file created: {$fullPath}");
    }

    private function generateBladeTemplate($template, $htmlContent)
    {
        $name = $template->name;
        $type = $template->layout_type;
        
        return <<<BLADE
{{-- {$name} Template --}}
<{$type} class="{$type}-{$template->tpl_id}" 
         style="background-color: {{ \$config['background_color'] ?? '#ffffff' }};">
    
    {$htmlContent}
    
</{$type}>

{{-- Inline Styles --}}
<style>
.{$type}-{$template->tpl_id} {
    /* Add your custom styles here */
    transition: all 0.3s ease;
}

/* Responsive Design */
@media (max-width: 768px) {
    .{$type}-{$template->tpl_id} {
        /* Mobile styles */
    }
}
</style>

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add your JavaScript functionality here
    console.log('{$name} template loaded');
});
</script>
BLADE;
    }
}
