<?php

namespace App\Services;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class BladeRenderingService
{
    /**
     * Render a Blade template string with given data
     *
     * @param string $template The Blade template content
     * @param array $data Data to pass to the template
     * @return string Rendered HTML
     */
    public function render(string $template, array $data = []): string
    {
        try {
            // Create a unique cache key for this template
            $cacheKey = 'dynamic_template_' . md5($template);
            
            // Use Blade::render for dynamic template rendering
            return Blade::render($template, $data);
        } catch (\Exception $e) {
            // Log the error and return a safe fallback
            Log::error('Blade rendering error: ' . $e->getMessage(), [
                'template' => substr($template, 0, 200),
                'data' => $data
            ]);
            
            // Return the template with basic variable replacement as fallback
            return $this->basicVariableReplacement($template, $data);
        }
    }
    
    /**
     * Basic variable replacement fallback
     *
     * @param string $template
     * @param array $data
     * @return string
     */
    private function basicVariableReplacement(string $template, array $data): string
    {
        $rendered = $template;
        
        // Remove Blade directives that can't be processed safely
        $rendered = preg_replace('/@(if|foreach|endforeach|endif|else|elseif|guest|endguest|auth|endauth|csrf|method)\b[^@]*/', '', $rendered);
        
        // Replace simple variable outputs
        $rendered = preg_replace_callback('/\{\{\s*\$([^}]+)\s*\}\}/', function ($matches) use ($data) {
            $varPath = trim($matches[1]);
            return $this->getNestedValue($data, $varPath);
        }, $rendered);
        
        return $rendered;
    }
    
    /**
     * Get nested array value by dot notation
     *
     * @param array $array
     * @param string $path
     * @return string
     */
    private function getNestedValue(array $array, string $path): string
    {
        // Handle array access like config['key']
        if (preg_match('/^config\[([\'"]?)([^\]]+)\1\](.*)/', $path, $matches)) {
            $key = $matches[2];
            $remaining = $matches[3];
            
            if (isset($array['config'][$key])) {
                $value = $array['config'][$key];
                
                // Handle nested access
                if ($remaining && preg_match('/\[([\'"]?)([^\]]+)\1\]/', $remaining, $nestedMatches)) {
                    $nestedKey = $nestedMatches[2];
                    if (is_array($value) && isset($value[$nestedKey])) {
                        return (string) $value[$nestedKey];
                    }
                } else {
                    return (string) $value;
                }
            }
        }
        
        return '';
    }
}
