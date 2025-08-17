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
            $domain  = $request->getHost();

            // 1) حدد الـ Site
            $site = Site::findByDomain($domain);
            if (!$site) {
                $tenant = $request->attributes->get('tenant');
                $site   = $tenant ?: Site::where('status_id', true)->first();
            }
            if (!$site) {
                return $this->showWelcomePage();
            }

            // 2) هات الـ Page
            $link = $slug === 'home' ? '/' : '/' . $slug;
            $page = TplPage::where('site_id', $site->id)->where('link', $link)->first();
            if (!$page) {
                $page = TplPage::where('site_id', $site->id)->where('link', '/')->first();
                if (!$page) {
                    return $this->showSimplePage($slug, $site);
                }
            }

            // 3) Config عام
            $siteConfig = SiteConfig::where('site_id', $site->id)->first();
            $config     = $siteConfig ? $siteConfig->data : [];
            $lang = 'en';
            $dir  = 'ltr';

            // 4) بيانات tpl_site
            $tplSite = TplSite::where('site_id', $site->id)->first();

            // ====== HEADER (NAV) ======
            $navLayout = null;
            if ($site->active_header_id) {
                $navLayout = TplLayout::where('id', $site->active_header_id)->where('status', true)->first();
            } elseif ($tplSite && $tplSite->nav) {
                $navLayout = TplLayout::where('id', $tplSite->nav)->where('status', true)->first();
            }

            if ($navLayout) {
                // ابدأ من content (المحفوظ بالتعديلات)
                $navConfig  = [];
                $contentArr = $navLayout->content;
                if (is_string($contentArr)) $contentArr = json_decode($contentArr, true) ?: [];
                if (is_array($contentArr))  $navConfig  = $contentArr;

                // ادمج مع default_config كـ fallback
                $default = $navLayout->default_config ?? [];
                if (is_string($default)) $default = json_decode($default, true) ?: [];
                if (is_array($default))  $navConfig = array_replace($default, $navConfig);

                // قيم ديناميكية
                $navConfig['site_name'] = $navConfig['site_name'] ?? $site->site_name;

                // روابط المنيو من tpl_site.nav_data
                if ($tplSite && is_array($tplSite->nav_data ?? null) && isset($tplSite->nav_data['links'])) {
                    $navConfig['menu_items'] = array_map(function ($link) {
                        return [
                            'label'    => $link['title'] ?? $link['name'] ?? 'Untitled',
                            'url'      => $link['url'] ?? '#',
                            'active'   => $link['active'] ?? true,
                            'external' => $link['external'] ?? false,
                        ];
                    }, array_values(array_filter($tplSite->nav_data['links'], function ($link) {
                        return ($link['active'] ?? true);
                    })));
                }

                // CTA تطبيع
                if (!isset($navConfig['cta_button'])) {
                    $navConfig['cta_button'] = [
                        'text' => $navConfig['cta_button_text'] ?? ($navConfig['cta_button']['text'] ?? null),
                        'url'  => $navConfig['cta_button_url']  ?? ($navConfig['cta_button']['url']  ?? '#'),
                    ];
                }

                // HTML
                $navHtml = $navLayout->content;
                if (is_array($navHtml) && isset($navHtml['html'])) {
                    $navHtml = $navHtml['html'];
                } elseif (is_string($navHtml)) {
                    $decoded = json_decode($navHtml, true);
                    if (is_array($decoded) && isset($decoded['html'])) $navHtml = $decoded['html'];
                }
                $navLayout->processed_content = $this->processTemplate($navHtml, $navConfig);
            }

            // ====== FOOTER ======
            $footerLayout = null;
            if ($site->active_footer_id) {
                $footerLayout = TplLayout::where('id', $site->active_footer_id)->where('status', true)->first();
            } elseif ($tplSite && $tplSite->footer) {
                $footerLayout = TplLayout::where('id', $tplSite->footer)->where('status', true)->first();
            }

            if ($footerLayout) {
                // ابدأ من content (المحفوظ بالتعديلات)
                $footerConfig = [];
                $footerArr = $footerLayout->content;
                if (is_string($footerArr)) $footerArr = json_decode($footerArr, true) ?: [];
                if (is_array($footerArr))  $footerConfig = $footerArr;

                // ادمج مع default_config
                $defaultFooter = $footerLayout->default_config ?? [];
                if (is_string($defaultFooter)) $defaultFooter = json_decode($defaultFooter, true) ?: [];
                if (is_array($defaultFooter))  $footerConfig = array_replace($defaultFooter, $footerConfig);

                // ديناميكي
                $footerConfig['year'] = date('Y');
                $footerConfig['site_name'] = $footerConfig['site_name'] ?? $site->site_name;

                // Social & Links من tpl_site.footer_data
                if ($tplSite && is_array($tplSite->footer_data ?? null)) {
                    if (isset($tplSite->footer_data['social_media'])) {
                        $footerConfig['social_links'] = [];
                        foreach ($tplSite->footer_data['social_media'] as $platform => $url) {
                            if (!empty(trim($url ?? ''))) {
                                $icons = [
                                    'facebook' => 'fab fa-facebook-f', 'twitter' => 'fab fa-twitter',
                                    'instagram'=> 'fab fa-instagram',  'linkedin'=> 'fab fa-linkedin-in',
                                    'youtube'  => 'fab fa-youtube',    'github'  => 'fab fa-github',
                                    'discord'  => 'fab fa-discord',    'tiktok'  => 'fab fa-tiktok',
                                    'pinterest'=> 'fab fa-pinterest',
                                ];
                                $footerConfig['social_links'][] = [
                                    'url' => $url,
                                    'icon' => $icons[$platform] ?? 'fas fa-link',
                                    'platform' => $platform,
                                ];
                            }
                        }
                    }

                    if (isset($tplSite->footer_data['newsletter'])) {
                        $footerConfig['newsletter'] = $tplSite->footer_data['newsletter'];
                    }

                    if (isset($tplSite->footer_data['links'])) {
                        $footerConfig['footer_links'] = array_map(function ($link) {
                            return [
                                'url'      => $link['url'] ?? '#',
                                'label'    => $link['title'] ?? $link['name'] ?? 'Untitled',
                                'active'   => $link['active'] ?? true,
                                'external' => $link['external'] ?? false,
                            ];
                        }, array_values(array_filter($tplSite->footer_data['links'], function ($link) {
                            return ($link['active'] ?? true);
                        })));
                        // توافق خلفي
                        $footerConfig['additional_pages'] = $footerConfig['footer_links'];
                    }

                    // Merge simple stored footer_data keys (company_name, description, contact fields, etc.)
                    // so values edited in the admin persist to the frontend template.
                    $fd_simple = $tplSite->footer_data;
                    if (is_array($fd_simple)) {
                        // Exclude complex keys already handled above
                        foreach (['links', 'social_media', 'newsletter', 'show_auth'] as $k) {
                            if (array_key_exists($k, $fd_simple)) unset($fd_simple[$k]);
                        }

                        // Map flat contact fields into contact_info structure expected by templates
                        $contactInfo = $footerConfig['contact_info'] ?? [];
                        if (isset($fd_simple['contact_email']) || isset($fd_simple['email'])) {
                            $contactInfo['email'] = $fd_simple['contact_email'] ?? $fd_simple['email'];
                        }
                        if (isset($fd_simple['contact_phone']) || isset($fd_simple['phone'])) {
                            $contactInfo['phone'] = $fd_simple['contact_phone'] ?? $fd_simple['phone'];
                        }
                        if (isset($fd_simple['address']) || isset($fd_simple['contact_address'])) {
                            $contactInfo['address'] = $fd_simple['address'] ?? $fd_simple['contact_address'];
                        }
                        if (!empty($contactInfo)) {
                            $footerConfig['contact_info'] = array_replace($footerConfig['contact_info'] ?? [], $contactInfo);
                        }

                        // Merge remaining simple keys directly (company_name, description, copyright_text, etc.)
                        $footerConfig = array_replace($footerConfig, $fd_simple);
                    }
                }

                // HTML
                $footerHtml = $footerLayout->content;
                if (is_array($footerHtml) && isset($footerHtml['html'])) {
                    $footerHtml = $footerHtml['html'];
                } elseif (is_string($footerHtml)) {
                    $decoded = json_decode($footerHtml, true);
                    if (is_array($decoded) && isset($decoded['html'])) $footerHtml = $decoded['html'];
                }
                $footerLayout->processed_content = $this->processTemplate($footerHtml, $footerConfig);
            }

            // ====== Sections ======
            $sections = TplPageSection::where('page_id', $page->id)
                ->where('status', 1)
                ->with('layout')
                ->orderBy('sort_order')
                ->get()
                ->map(function ($section) use ($lang) {
                    try {
                        // content_data أولاً
                        $contentData = null;
                        if ($section->content_data) {
                            $contentData = is_string($section->content_data)
                                ? (json_decode($section->content_data, true) ?: null)
                                : (is_array($section->content_data) ? $section->content_data : null);
                        }
                        if (!$contentData) {
                            if (is_string($section->content)) {
                                $decoded = json_decode($section->content, true);
                                $contentData = (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
                                    ? $decoded
                                    : ['content' => $section->content];
                            } elseif (is_array($section->content)) {
                                $contentData = $section->content;
                            } else {
                                $contentData = [];
                            }
                        }
                        if (isset($contentData['en']) && is_array($contentData['en'])) {
                            $contentData = array_merge($contentData, $contentData['en']);
                        }

                        $section->parsed_content = [
                            'title'       => $contentData['title'] ?? $contentData['hero_title'] ?? $section->name,
                            'content'     => $contentData['content'] ?? $contentData['hero_description'] ?? $contentData['subtitle'] ?? $contentData['description'] ?? '',
                            'button_text' => $contentData['button_text'] ?? $contentData['cta_text'] ?? '',
                            'button_url'  => $contentData['button_url'] ?? $contentData['cta_url'] ?? '#',
                            'html'        => $contentData['html'] ?? '',
                        ];

                        $settings = null;
                        if ($section->settings) {
                            $settings = is_string($section->settings)
                                ? (json_decode($section->settings, true) ?: null)
                                : (is_array($section->settings) ? $section->settings : null);
                        }
                        $section->parsed_settings = $settings ?? [];

                        // HTML النهائي
                        if (!empty($contentData['html'])) {
                            $processed = $this->processTemplatePlaceholders($contentData['html'], [
                                'title'       => $section->parsed_content['title'],
                                'content'     => $section->parsed_content['content'],
                                'button_text' => $section->parsed_content['button_text'],
                                'button_url'  => $section->parsed_content['button_url'],
                                'image'       => $contentData['image'] ?? '',
                            ]);
                            $section->layout->processed_content = $processed;
                        } elseif ($section->layout && $section->layout->content) {
                            $layoutContent = $section->layout->content;
                            if (is_array($layoutContent) && isset($layoutContent['html'])) {
                                $layoutContent = $layoutContent['html'];
                            } elseif (is_string($layoutContent)) {
                                $decoded = json_decode($layoutContent, true);
                                if (is_array($decoded) && isset($decoded['html'])) {
                                    $layoutContent = $decoded['html'];
                                }
                            }
                            $processed = $this->processTemplatePlaceholders($layoutContent, [
                                'title'       => $section->parsed_content['title'],
                                'content'     => $section->parsed_content['content'],
                                'button_text' => $section->parsed_content['button_text'],
                                'button_url'  => $section->parsed_content['button_url'],
                                'services'    => $contentData['services'] ?? [],
                                'image'       => $contentData['image'] ?? '',
                            ]);
                            $section->layout->processed_content = $processed;
                        }

                    } catch (Exception $e) {
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
            $template  = $matches[2];
            $output = '';

            if (isset($data[$arrayName]) && is_array($data[$arrayName])) {
                foreach ($data[$arrayName] as $item) {
                    $itemOutput = $template;
                    $itemOutput = str_replace('{{this}}', $item, $itemOutput);

                    if (is_array($item)) {
                        foreach ($item as $key => $value) {
                            if (is_array($value)) {
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
            $condition  = trim($matches[1]);
            $ifContent  = $matches[2];
            $elseContent = isset($matches[3]) ? $matches[3] : '';

            $value = $data[$condition] ?? false;

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
        $content = preg_replace_callback('/\$config\[\'([^\']+)\'\]/', function ($matches) use ($data) {
            return $data[$matches[1]] ?? '';
        }, $content);

        // Handle @foreach loops for menu_items
        $content = preg_replace_callback('/@foreach\(\$config\[\'menu_items\'\][^)]*\).*?@endforeach/s', function ($matches) use ($data) {
            $output = '';
            $menuItems = $data['menu_items'] ?? [];
            foreach ($menuItems as $item) {
                $itemHtml = '<li class="nav-item"><a class="nav-link" href="' . ($item['url'] ?? '#') . '">' . ($item['label'] ?? 'Link') . '</a></li>';
                $output .= $itemHtml;
            }
            return $output;
        }, $content);

        // Handle @foreach loops for social_links
        $content = preg_replace_callback('/@foreach\(\$config\[\'social_links\'\][^)]*\).*?@endforeach/s', function ($matches) use ($data) {
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
        $content = preg_replace_callback('/@foreach\(\$config\[\'footer_links\'\][^)]*\).*?@endforeach/s', function ($matches) use ($data) {
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

        if (!is_array($data)) {
            $data = [];
        }

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
            '{HERO_TITLE}' => $data['title'] ?? '',
            '{HERO_DESCRIPTION}' => !empty($data['content']) ? nl2br(e($data['content'])) : '',
            '{CTA_TEXT}' => $data['button_text'] ?? '',
            '{CTA_URL}' => $data['button_url'] ?? '#',
        ];

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
