<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\TplPage;
use App\Models\SiteConfig;
use App\Models\TplPageSection;
use App\Models\TplSite;
use App\Models\TplLayout;
use App\Models\TplLang;
use Illuminate\Support\Facades\Log;
use Exception;

class PageController extends Controller
{
    public function show($slug = 'home')
    {
        try {
            $request = request();
            $domain = $request->getHost();
            
            // Try to find site by domain first
            $site = Site::findByDomain($domain);
            
            if (!$site) {
                // Fallback: check if we have a tenant from middleware
                $tenant = $request->attributes->get('tenant');
                if ($tenant) {
                    $site = $tenant;
                } else {
                    // Final fallback: get the first active site (for development)
                    $site = Site::where('status_id', true)->first();
                }
            }
            
            if (!$site) {
                // Create a simple welcome page if no site is found
                return $this->showWelcomePage();
            }
            
            // Convert slug to link format
            $link = $slug === 'home' ? '/' : '/' . $slug;
            
            // Get the page by link
            $page = TplPage::where('site_id', $site->id)
                ->where('link', $link)
                ->first();
                
            if (!$page) {
                // If specific page not found, try to get home page
                $page = TplPage::where('site_id', $site->id)
                    ->where('link', '/')
                    ->first();
                    
                if (!$page) {
                    // Create a simple page if no pages are found
                    return $this->showSimplePage($slug, $site);
                }
            }
            
            // Get site configuration
            $siteConfig = SiteConfig::where('site_id', $site->id)->first();
            $config = $siteConfig ? $siteConfig->data : [];
        
            // Get language settings
            $lang = 'en'; // Default to English
            $dir = 'ltr';  // Default to LTR
            
            // Get site template configuration
            $tplSite = TplSite::where('site_id', $site->id)->first();
            
            // Get navigation layout - prioritize active_header_id, fallback to legacy tpl_site
            $navLayout = null;
            if ($site->active_header_id) {
                $navLayout = TplLayout::where('id', $site->active_header_id)->where('status', true)->first();
            } elseif ($tplSite && $tplSite->nav) {
                $navLayout = TplLayout::where('id', $tplSite->nav)->where('status', true)->first();
            }
            
            if ($navLayout) {
                $navConfig = $navLayout->default_config ?? [];
                // Ensure it's an array
                if (is_string($navConfig)) {
                    $navConfig = json_decode($navConfig, true) ?? [];
                }
                // Add site-specific data
                $navConfig['site_name'] = $site->site_name;
                
                // Override menu_items with nav_data if available
                if ($tplSite && $tplSite->nav_data && isset($tplSite->nav_data['links'])) {
                    $navConfig['menu_items'] = array_map(function($link) {
                        return [
                            'label' => $link['title'] ?? $link['name'] ?? 'Untitled', // Support both title and name
                            'url' => $link['url'],
                            'active' => $link['active'] ?? true,
                            'external' => $link['external'] ?? false
                        ];
                    }, array_filter($tplSite->nav_data['links'], function($link) {
                        return ($link['active'] ?? true); // Only include active links
                    }));
                }
                
                // Handle content - check if it's JSON array or string
                $contentToProcess = $navLayout->content;
                if (is_array($contentToProcess) && isset($contentToProcess['html'])) {
                    $contentToProcess = $contentToProcess['html'];
                } elseif (is_string($contentToProcess)) {
                    // Try to decode JSON
                    $decoded = json_decode($contentToProcess, true);
                    if (is_array($decoded) && isset($decoded['html'])) {
                        $contentToProcess = $decoded['html'];
                    }
                }
                
                $navLayout->processed_content = $this->processTemplate($contentToProcess, $navConfig);
            }
            
            // Get footer layout - prioritize active_footer_id, fallback to legacy tpl_site  
            $footerLayout = null;
            if ($site->active_footer_id) {
                $footerLayout = TplLayout::where('id', $site->active_footer_id)->where('status', true)->first();
            } elseif ($tplSite && $tplSite->footer) {
                $footerLayout = TplLayout::where('id', $tplSite->footer)->where('status', true)->first();
            }
            
            if ($footerLayout) {
                $footerConfig = $footerLayout->default_config ?? [];
                // Ensure it's an array
                if (is_string($footerConfig)) {
                    $footerConfig = json_decode($footerConfig, true) ?? [];
                }
                // Add dynamic data
                $footerConfig['year'] = date('Y');
                $footerConfig['site_name'] = $site->site_name;
                
                // Add footer-specific data from TplSite
                if ($tplSite && $tplSite->footer_data) {
                    if (isset($tplSite->footer_data['social_media'])) {
                        $footerConfig['social_links'] = [];
                        foreach ($tplSite->footer_data['social_media'] as $platform => $url) {
                            if (!empty(trim($url))) { // Only include non-empty URLs
                                $icons = [
                                    'facebook' => 'fab fa-facebook-f',
                                    'twitter' => 'fab fa-twitter',
                                    'instagram' => 'fab fa-instagram', 
                                    'linkedin' => 'fab fa-linkedin-in',
                                    'youtube' => 'fab fa-youtube',
                                    'github' => 'fab fa-github',
                                    'discord' => 'fab fa-discord',
                                    'tiktok' => 'fab fa-tiktok',
                                    'pinterest' => 'fab fa-pinterest'
                                ];
                                $footerConfig['social_links'][] = [
                                    'url' => $url,
                                    'icon' => $icons[$platform] ?? 'fas fa-link',
                                    'platform' => $platform
                                ];
                            }
                        }
                    }
                    if (isset($tplSite->footer_data['newsletter'])) {
                        $footerConfig['newsletter'] = $tplSite->footer_data['newsletter'];
                    }
                    if (isset($tplSite->footer_data['links'])) {
                        $footerConfig['footer_links'] = array_map(function($link) {
                            return [
                                'url' => $link['url'],
                                'label' => $link['title'] ?? $link['name'] ?? 'Untitled', // Support both title and name
                                'active' => $link['active'] ?? true,
                                'external' => $link['external'] ?? false
                            ];
                        }, array_filter($tplSite->footer_data['links'], function($link) {
                            return ($link['active'] ?? true); // Only include active links
                        }));
                        
                        // Also set as additional_pages for backward compatibility
                        $footerConfig['additional_pages'] = $footerConfig['footer_links'];
                    }
                }
                
                // Handle content - check if it's JSON array or string
                $contentToProcess = $footerLayout->content;
                if (is_array($contentToProcess) && isset($contentToProcess['html'])) {
                    $contentToProcess = $contentToProcess['html'];
                } elseif (is_string($contentToProcess)) {
                    // Try to decode JSON
                    $decoded = json_decode($contentToProcess, true);
                    if (is_array($decoded) && isset($decoded['html'])) {
                        $contentToProcess = $decoded['html'];
                    }
                }
                
                $footerLayout->processed_content = $this->processTemplate($contentToProcess, $footerConfig);
            }
            
            // Get page sections with their layouts
            $sections = TplPageSection::where('page_id', $page->id)
                ->where('status', 1)
                ->with('layout')
                ->orderBy('sort_order')
                ->get()
                ->map(function ($section) use ($lang) {
                    // Parse JSON content from both content and content_data fields
                    try {
                        // Try content_data first, then fallback to content
                        $contentData = null;
                        if ($section->content_data) {
                            $contentData = json_decode($section->content_data, true);
                        }
                        
                        // If content_data is empty or invalid, use content field
                        if (!$contentData || !is_array($contentData)) {
                            $contentData = json_decode($section->content, true) ?? [];
                        }
                        
                        $settings = json_decode($section->settings, true);
                        
                        // Ensure we have valid content data
                        if (!$contentData || !is_array($contentData)) {
                            $contentData = [];
                        }
                        
                        $section->parsed_content = [
                            'title' => $contentData['title'] ?? $contentData['hero_title'] ?? $section->name,
                            'content' => $contentData['content'] ?? $contentData['hero_description'] ?? $contentData['subtitle'] ?? '',
                            'button_text' => $contentData['button_text'] ?? $contentData['cta_text'] ?? '',
                            'button_url' => $contentData['button_url'] ?? $contentData['cta_url'] ?? '#',
                            'html' => $contentData['html'] ?? '', // Add processed HTML
                        ];
                        
                        $section->parsed_settings = $settings ?? [];
                        
                        // Use pre-processed HTML if available, otherwise process layout content
                        if (!empty($contentData['html'])) {
                            // Use the pre-processed HTML from content_data
                            $processedHTML = $contentData['html'];
                            
                            // Process any remaining placeholders with actual data
                            $processedHTML = $this->processTemplatePlaceholders($processedHTML, [
                                'title' => $section->parsed_content['title'],
                                'content' => $section->parsed_content['content'],
                                'button_text' => $section->parsed_content['button_text'],
                                'button_url' => $section->parsed_content['button_url'],
                                'image' => $contentData['image'] ?? ''
                            ]);
                            
                            $section->layout->processed_content = $processedHTML;
                        } elseif ($section->layout && $section->layout->content) {
                            // Process layout content with actual data
                            $layoutContent = $section->layout->content;
                            
                            // Extract HTML from array if needed
                            if (is_array($layoutContent) && isset($layoutContent['html'])) {
                                $layoutContent = $layoutContent['html'];
                            } elseif (is_string($layoutContent)) {
                                // Try to decode JSON
                                $decoded = json_decode($layoutContent, true);
                                if (is_array($decoded) && isset($decoded['html'])) {
                                    $layoutContent = $decoded['html'];
                                }
                            }
                            
                            // Process placeholders in layout content with actual section data
                            $processedHTML = $this->processTemplatePlaceholders($layoutContent, [
                                'title' => $section->parsed_content['title'],
                                'content' => $section->parsed_content['content'],
                                'button_text' => $section->parsed_content['button_text'],
                                'button_url' => $section->parsed_content['button_url'],
                                'services' => $contentData['services'] ?? [],
                                'image' => $contentData['image'] ?? ''
                            ]);
                            
                            $section->layout->processed_content = $processedHTML;
                        }
                    } catch (Exception $e) {
                        // Fallback for parsing errors
                        $section->parsed_content = [
                            'title' => $section->name,
                            'content' => '',
                            'button_text' => '',
                        ];
                        $section->parsed_settings = [];
                    }
                    
                    return $section;
                });
            
            return view('frontend.layouts.app', compact(
                'site', 
                'page', 
                'config', 
                'lang', 
                'dir', 
                'navLayout', 
                'footerLayout', 
                'sections'
            ));
            
        } catch (Exception $e) {
            // Log the error and show a simple welcome page
            Log::error('Frontend page error: ' . $e->getMessage());
            return $this->showWelcomePage();
        }
    }
    
    /**
     * Show a simple welcome page when no site is configured
     */
    private function showWelcomePage()
    {
        return view('frontend.welcome');
    }
    
    /**
     * Show a simple page when site exists but no specific page is found
     */
    private function showSimplePage($slug, $site)
    {
        return view('frontend.simple', compact('slug', 'site'));
    }
    
    /**
     * Process template content by replacing placeholders with actual data
     */
    private function processTemplate($content, $data = [])
    {
        if (empty($content)) {
            return $content;
        }
        
        // Handle array content - extract HTML if it's a structured array
        if (is_array($content)) {
            if (isset($content['html'])) {
                $content = $content['html'];
            } else {
                // If it's an array but no 'html' key, convert to JSON or return empty
                return '<!-- Template content is array without html key: ' . json_encode(array_keys($content)) . ' -->';
            }
        }
        
        // Ensure content is a string
        if (!is_string($content)) {
            return '<!-- Template content is not a string: ' . gettype($content) . ' -->';
        }

        // If content contains PHP/Blade syntax, process it as Blade template
        if (strpos($content, '@') !== false || strpos($content, '$config') !== false) {
            return $this->processBladeLikeTemplate($content, $data);
        }        // Replace simple placeholders {{field_name}}
        $content = preg_replace_callback('/\{\{([^}]+)\}\}/', function ($matches) use ($data) {
            $field = trim($matches[1]);
            
            // Handle nested fields like contact_info.email
            if (strpos($field, '.') !== false) {
                $parts = explode('.', $field);
                $value = $data;
                foreach ($parts as $part) {
                    if (is_array($value) && isset($value[$part])) {
                        $value = $value[$part];
                    } else {
                        $value = '';
                        break;
                    }
                }
                return $value;
            }
            
            return $data[$field] ?? '';
        }, $content);
        
        // Handle Handlebars-style loops {{#each array}}
        $content = preg_replace_callback('/\{\{#each\s+([^}]+)\}\}(.*?)\{\{\/each\}\}/s', function ($matches) use ($data) {
            $arrayName = trim($matches[1]);
            $template = $matches[2];
            $output = '';
            
            if (isset($data[$arrayName]) && is_array($data[$arrayName])) {
                foreach ($data[$arrayName] as $item) {
                    $itemOutput = $template;
                    // Replace {{this}} with the item value for simple arrays
                    $itemOutput = str_replace('{{this}}', $item, $itemOutput);
                    
                    // Replace {{field}} with item properties for object arrays
                    if (is_array($item)) {
                        foreach ($item as $key => $value) {
                            if (is_array($value)) {
                                // Handle nested arrays (like social links)
                                $nestedOutput = '';
                                foreach ($value as $nestedItem) {
                                    if (is_array($nestedItem)) {
                                        $nestedItemOutput = $itemOutput;
                                        foreach ($nestedItem as $nestedKey => $nestedValue) {
                                            $nestedItemOutput = str_replace('{{' . $nestedKey . '}}', $nestedValue, $nestedItemOutput);
                                        }
                                        $nestedOutput .= $nestedItemOutput;
                                    }
                                }
                                $itemOutput = $nestedOutput;
                            } else {
                                $itemOutput = str_replace('{{' . $key . '}}', $value, $itemOutput);
                            }
                        }
                    }
                    $output .= $itemOutput;
                }
            }
            
            return $output;
        }, $content);
        
        // Handle conditional blocks {{#if condition}}
        $content = preg_replace_callback('/\{\{#if\s+([^}]+)\}\}(.*?)(?:\{\{else\}\}(.*?))?\{\{\/if\}\}/s', function ($matches) use ($data) {
            $condition = trim($matches[1]);
            $ifContent = $matches[2];
            $elseContent = isset($matches[3]) ? $matches[3] : '';
            
            $value = $data[$condition] ?? false;
            
            // Check if the condition is truthy
            if ($value && $value !== 'false' && $value !== '0') {
                return $ifContent;
            } else {
                return $elseContent;
            }
        }, $content);
        
        return $content;
    }
    
    /**
     * Process Blade-like template syntax
     */
    private function processBladeLikeTemplate($content, $data = [])
    {
        try {
            // Create a unique temporary view name
            $viewName = 'temp_' . md5($content . serialize($data));
            $viewPath = resource_path('views/temp');
            
            // Ensure directory exists
            if (!is_dir($viewPath)) {
                mkdir($viewPath, 0755, true);
            }
            
            $fullPath = $viewPath . '/' . $viewName . '.blade.php';
            
            // Write the blade content to a temporary file
            file_put_contents($fullPath, $content);
            
            // Render the view with data
            $rendered = view('temp.' . $viewName, ['config' => $data])->render();
            
            // Clean up the temporary file
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            
            return $rendered;
            
        } catch (Exception $e) {
            // Fallback to manual replacement if Blade processing fails
            Log::warning('Blade template processing failed, using fallback: ' . $e->getMessage());
            return $this->manualTemplateReplacement($content, $data);
        }
    }
    
    /**
     * Manual template replacement as fallback
     */
    private function manualTemplateReplacement($content, $data = [])
    {
        // Replace basic variable access
        $content = preg_replace_callback('/\$config\[\'([^\']+)\'\]/', function($matches) use ($data) {
            return $data[$matches[1]] ?? '';
        }, $content);
        
        // Handle @foreach loops for menu_items
        $content = preg_replace_callback('/@foreach\(\$config\[\'menu_items\'\][^)]*\).*?@endforeach/s', function($matches) use ($data) {
            $output = '';
            $menuItems = $data['menu_items'] ?? [];
            foreach ($menuItems as $item) {
                $itemHtml = '<li class="nav-item"><a class="nav-link" href="' . ($item['url'] ?? '#') . '">' . ($item['label'] ?? 'Link') . '</a></li>';
                $output .= $itemHtml;
            }
            return $output;
        }, $content);
        
        // Handle @foreach loops for social_links
        $content = preg_replace_callback('/@foreach\(\$config\[\'social_links\'\][^)]*\).*?@endforeach/s', function($matches) use ($data) {
            $output = '';
            $socialLinks = $data['social_links'] ?? [];
            foreach ($socialLinks as $social) {
                if (!empty($social['url']) && $social['url'] !== '#') {
                    $itemHtml = '<a href="' . $social['url'] . '" class="text-light me-3"><i class="' . $social['icon'] . '"></i></a>';
                    $output .= $itemHtml;
                }
            }
            return $output;
        }, $content);
        
        // Handle @foreach loops for footer_links/additional_pages
        $content = preg_replace_callback('/@foreach\(\$config\[\'footer_links\'\][^)]*\).*?@endforeach/s', function($matches) use ($data) {
            $output = '';
            $footerLinks = $data['footer_links'] ?? $data['additional_pages'] ?? [];
            foreach ($footerLinks as $link) {
                $itemHtml = '<li><a href="' . ($link['url'] ?? '#') . '" class="text-light">' . ($link['label'] ?? 'Link') . '</a></li>';
                $output .= $itemHtml;
            }
            return $output;
        }, $content);
        
        // Remove remaining Blade directives that couldn't be processed
        $content = preg_replace('/@[a-zA-Z]+.*?@end[a-zA-Z]+/s', '', $content);
        $content = preg_replace('/@[a-zA-Z]+\([^)]*\)/', '', $content);
        
        return $content;
    }

    /**
     * Process template placeholders with actual data
     */
    private function processTemplatePlaceholders($content, $data)
    {
        if (empty($content) || !is_string($content)) {
            return $content;
        }
        
        // Ensure data is an array
        if (!is_array($data)) {
            $data = [];
        }
        
        // Handle both old Handlebars syntax and new placeholder syntax
        $replacements = [
            // New placeholder syntax (recommended)
            '{TITLE_PLACEHOLDER}' => $data['title'] ?? '',
            '{CONTENT_PLACEHOLDER}' => !empty($data['content']) ? nl2br(e($data['content'])) : '',
            '{BUTTON_TEXT_PLACEHOLDER}' => $data['button_text'] ?? '',
            '{BUTTON_URL_PLACEHOLDER}' => $data['button_url'] ?? '#',
            '{IMAGE_PLACEHOLDER}' => $data['image'] ?? '',
            
            // Old Handlebars syntax (for backward compatibility)
            '{{title}}' => $data['title'] ?? '',
            '{{content}}' => !empty($data['content']) ? nl2br(e($data['content'])) : '',
            '{{button_text}}' => $data['button_text'] ?? '',
            '{{button_url}}' => $data['button_url'] ?? '#',
            '{{image}}' => $data['image'] ?? '',
            
            // Additional common placeholders
            '{TITLE}' => $data['title'] ?? '',
            '{CONTENT}' => !empty($data['content']) ? nl2br(e($data['content'])) : '',
            '{BUTTON_TEXT}' => $data['button_text'] ?? '',
            '{BUTTON_URL}' => $data['button_url'] ?? '#',
            '{IMAGE}' => $data['image'] ?? '',
        ];
        
        // Apply replacements
        $processedContent = str_replace(array_keys($replacements), array_values($replacements), $content);
        
        // Handle conditional statements for buttons
        if (!empty($data['button_text'])) {
            $processedContent = str_replace(['{{#if button_text}}', '{{/if}}'], '', $processedContent);
        } else {
            $processedContent = preg_replace('/\{\{#if button_text\}\}.*?\{\{\/if\}\}/s', '', $processedContent);
        }
        
        // Handle conditional statements for images
        if (!empty($data['image'])) {
            $processedContent = str_replace(['{{#if image}}', '{{/if}}'], '', $processedContent);
        } else {
            $processedContent = preg_replace('/\{\{#if image\}\}.*?\{\{\/if\}\}/s', '', $processedContent);
        }
        
        return $processedContent;
    }
}
