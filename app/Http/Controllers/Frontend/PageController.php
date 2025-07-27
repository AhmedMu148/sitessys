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
                            'label' => $link['name'],
                            'url' => $link['url']
                        ];
                    }, $tplSite->nav_data['links']);
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
                            $icons = [
                                'facebook' => 'fab fa-facebook-f',
                                'twitter' => 'fab fa-twitter',
                                'instagram' => 'fab fa-instagram',
                                'linkedin' => 'fab fa-linkedin-in',
                                'youtube' => 'fab fa-youtube'
                            ];
                            $footerConfig['social_links'][] = [
                                'url' => $url,
                                'icon' => $icons[$platform] ?? 'fas fa-link'
                            ];
                        }
                    }
                    if (isset($tplSite->footer_data['newsletter'])) {
                        $footerConfig['newsletter'] = $tplSite->footer_data['newsletter'];
                    }
                    if (isset($tplSite->footer_data['links'])) {
                        $footerConfig['additional_pages'] = array_map(function($link) {
                            return [
                                'url' => $link['url'],
                                'label' => $link['name']
                            ];
                        }, $tplSite->footer_data['links']);
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
                    // Parse JSON content_data
                    try {
                        $contentData = json_decode($section->content_data, true);
                        $settings = json_decode($section->settings, true);
                        
                        $section->parsed_content = [
                            'title' => $contentData['title'] ?? $section->name,
                            'content' => $contentData['content'] ?? '',
                            'button_text' => $contentData['button_text'] ?? '',
                        ];
                        
                        $section->parsed_settings = $settings ?? [];
                        
                        // Process layout content with configurable fields
                        if ($section->layout && $section->layout->content) {
                            $layoutConfig = $section->layout->default_config ?? [];
                            // Ensure it's an array
                            if (is_string($layoutConfig)) {
                                $layoutConfig = json_decode($layoutConfig, true) ?? [];
                            }
                            $section->layout->processed_content = $this->processTemplate(
                                $section->layout->content, 
                                $layoutConfig
                            );
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
        
        // Replace simple placeholders {{field_name}}
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
}
