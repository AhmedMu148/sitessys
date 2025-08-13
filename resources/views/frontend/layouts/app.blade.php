<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ $config['description'] ?? $site->site_name }}">
    <meta name="keywords" content="{{ $config['keyword'] ?? 'website' }}">
    <meta name="author" content="{{ $site->site_name }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="{{ asset($config['favicon'] ?? 'favicon.ico') }}" />

    <title>{{ $config['title'] ?? $site->site_name }} - {{ $page->name }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- Site-wide Colors (from configuration) --}}
    @php
        $colorsCfgDefault = [
            'primary' => '#0d6efd',
            'secondary' => '#6c757d',
            'success' => '#198754',
            'info' => '#0dcaf0',
            'warning' => '#ffc107',
            'danger' => '#dc3545',
            'nav' => ['background' => '#ffffff', 'text' => '#000000', 'link' => '#000000', 'link_hover' => '#0d6efd', 'button_bg' => '#0d6efd', 'button_text' => '#ffffff'],
            'footer' => ['background' => '#f8f9fa', 'text' => '#000000', 'link' => '#0d6efd', 'link_hover' => '#6c757d'],
            'body' => ['background' => '#ffffff', 'text' => '#212529'],
            'link' => ['color' => null, 'hover' => null],
            'section' => ['background' => null, 'text' => null, 'heading' => null, 'link' => null, 'link_hover' => null, 'button_bg' => null, 'button_text' => null],
            'buttons' => ['primary_text'=>'#ffffff','secondary_text'=>'#ffffff','success_text'=>'#ffffff','info_text'=>'#ffffff','warning_text'=>'#000000','danger_text'=>'#ffffff'],
        ];
        $colorsCfg = is_callable([$site, 'getConfiguration'])
            ? ($site->getConfiguration('colors', $colorsCfgDefault) ?: $colorsCfgDefault)
            : $colorsCfgDefault;

        $cPrimary = $colorsCfg['primary'] ?? '#0d6efd';
        $cSecondary = $colorsCfg['secondary'] ?? '#6c757d';
        $cSuccess = $colorsCfg['success'] ?? '#198754';
        $cInfo = $colorsCfg['info'] ?? '#0dcaf0';
        $cWarning = $colorsCfg['warning'] ?? '#ffc107';
        $cDanger = $colorsCfg['danger'] ?? '#dc3545';

        $cNavBg = $colorsCfg['nav']['background'] ?? '#ffffff';
        $cNavText = $colorsCfg['nav']['text'] ?? '#000000';
        $cNavLink = $colorsCfg['nav']['link'] ?? $cNavText;
        $cNavLinkHover = $colorsCfg['nav']['link_hover'] ?? $cPrimary;
        $cNavBtnBg = $colorsCfg['nav']['button_bg'] ?? $cPrimary;
        $cNavBtnText = $colorsCfg['nav']['button_text'] ?? '#ffffff';

        $cFooterBg = $colorsCfg['footer']['background'] ?? '#f8f9fa';
        $cFooterText = $colorsCfg['footer']['text'] ?? '#000000';
        $cFooterLink = $colorsCfg['footer']['link'] ?? $cPrimary;
        $cFooterLinkHover = $colorsCfg['footer']['link_hover'] ?? $cSecondary;

        $cBodyBg = $colorsCfg['body']['background'] ?? '#ffffff';
        $cBodyText = $colorsCfg['body']['text'] ?? '#212529';

        $cLink = $colorsCfg['link']['color'] ?? $cPrimary;
        $cLinkHover = $colorsCfg['link']['hover'] ?? $cSecondary;

        $cSectionBg = $colorsCfg['section']['background'] ?? null;
        $cSectionText = $colorsCfg['section']['text'] ?? null;
        $cSectionHeading = $colorsCfg['section']['heading'] ?? null;
        $cSectionLink = $colorsCfg['section']['link'] ?? null;
        $cSectionLinkHover = $colorsCfg['section']['link_hover'] ?? null;
        $cSectionBtnBg = $colorsCfg['section']['button_bg'] ?? null;
        $cSectionBtnText = $colorsCfg['section']['button_text'] ?? null;

        $btnTxt = $colorsCfg['buttons'] ?? [];
        $btnTextPrimary = $btnTxt['primary_text'] ?? '#ffffff';
        $btnTextSecondary = $btnTxt['secondary_text'] ?? '#ffffff';
        $btnTextSuccess = $btnTxt['success_text'] ?? '#ffffff';
        $btnTextInfo = $btnTxt['info_text'] ?? '#ffffff';
        $btnTextWarning = $btnTxt['warning_text'] ?? '#000000';
        $btnTextDanger = $btnTxt['danger_text'] ?? '#ffffff';
    @endphp

    <style>
        :root{
            --color-primary: {{ $cPrimary }};
            --color-secondary: {{ $cSecondary }};

            --bs-primary: {{ $cPrimary }};
            --bs-secondary: {{ $cSecondary }};
            --bs-success: {{ $cSuccess }};
            --bs-info: {{ $cInfo }};
            --bs-warning: {{ $cWarning }};
            --bs-danger: {{ $cDanger }};

            --bs-link-color: {{ $cLink }};
            --bs-link-hover-color: {{ $cLinkHover }};
            --bs-body-bg: {{ $cBodyBg }};
            --bs-body-color: {{ $cBodyText }};

            --nav-bg: {{ $cNavBg }};
            --nav-text: {{ $cNavText }};
            --nav-link: {{ $cNavLink }};
            --nav-link-hover: {{ $cNavLinkHover }};
            --nav-btn-bg: {{ $cNavBtnBg }};
            --nav-btn-text: {{ $cNavBtnText }};

            --footer-bg: {{ $cFooterBg }};
            --footer-text: {{ $cFooterText }};
            --footer-link: {{ $cFooterLink }};
            --footer-link-hover: {{ $cFooterLinkHover }};

            @if($cSectionBg) --section-bg: {{ $cSectionBg }}; @endif
            @if($cSectionText) --section-text: {{ $cSectionText }}; @endif
            @if($cSectionHeading) --section-heading: {{ $cSectionHeading }}; @endif
            @if($cSectionLink) --section-link: {{ $cSectionLink }}; @endif
            @if($cSectionLinkHover) --section-link-hover: {{ $cSectionLinkHover }}; @endif
            @if($cSectionBtnBg) --section-btn-bg: {{ $cSectionBtnBg }}; @endif
            @if($cSectionBtnText) --section-btn-text: {{ $cSectionBtnText }}; @endif

            /* Button text color overrides */
            --btn-text-primary: {{ $btnTextPrimary }};
            --btn-text-secondary: {{ $btnTextSecondary }};
            --btn-text-success: {{ $btnTextSuccess }};
            --btn-text-info: {{ $btnTextInfo }};
            --btn-text-warning: {{ $btnTextWarning }};
            --btn-text-danger: {{ $btnTextDanger }};
        }

        body{background-color: var(--bs-body-bg); color: var(--bs-body-color);}
        .navbar{background-color: var(--nav-bg) !important;color: var(--nav-text) !important;}
        .navbar .nav-link{color: var(--nav-link) !important;}
        .navbar .nav-link:hover{color: var(--nav-link-hover) !important;}
        .navbar .navbar-brand{color: var(--nav-text) !important;}
        .navbar .btn, .navbar .btn-primary{--bs-btn-bg: var(--nav-btn-bg); --bs-btn-border-color: var(--nav-btn-bg); --bs-btn-color: var(--nav-btn-text);}

        a{color: var(--bs-link-color);} a:hover{color: var(--bs-link-hover-color);}
        footer{background-color: var(--footer-bg) !important;color: var(--footer-text) !important;}
        footer a{color: var(--footer-link);} footer a:hover{color: var(--footer-link-hover);}

        .section{background-color: var(--section-bg, initial); color: var(--section-text, inherit);}
        .section h1, .section h2, .section h3, .section h4, .section h5, .section h6{color: var(--section-heading, inherit);}
        .section a{color: var(--section-link, var(--bs-link-color));}
        .section a:hover{color: var(--section-link-hover, var(--bs-link-hover-color));}
        .section .btn, .section .btn-primary{
            --bs-btn-bg: var(--section-btn-bg, var(--bs-primary));
            --bs-btn-border-color: var(--section-btn-bg, var(--bs-primary));
            --bs-btn-color: var(--section-btn-text, #fff);
        }

        /* Ensure readable text colors for standard buttons (explicit color in addition to --bs-btn-color) */
        .btn-primary{ --bs-btn-color: var(--btn-text-primary); color: var(--btn-text-primary) !important; }
        .btn-secondary{ --bs-btn-color: var(--btn-text-secondary); color: var(--btn-text-secondary) !important; }
        .btn-success{ --bs-btn-color: var(--btn-text-success); color: var(--btn-text-success) !important; }
        .btn-info{ --bs-btn-color: var(--btn-text-info); color: var(--btn-text-info) !important; }
        .btn-warning{ --bs-btn-color: var(--btn-text-warning); color: var(--btn-text-warning) !important; }
        .btn-danger{ --bs-btn-color: var(--btn-text-danger); color: var(--btn-text-danger) !important; }

        /* Force theme BG on sections even if templates use bg-* helpers */
        .section, .tpl-section, [data-sps-section]{ background-color: var(--section-bg, initial) !important; color: var(--section-text, inherit) !important; }
        .section [class^="bg-"], .section [class*=" bg-"], .tpl-section [class^="bg-"], .tpl-section [class*=" bg-"], [data-sps-section] [class^="bg-"], [data-sps-section] [class*=" bg-"]{ background-color: var(--section-bg, initial) !important; }

        /* Make headings/links inside sections follow variables */
        .section h1, .section h2, .section h3, .section h4, .section h5, .section h6,
        .tpl-section h1, .tpl-section h2, .tpl-section h3, .tpl-section h4, .tpl-section h5, .tpl-section h6,
        [data-sps-section] h1, [data-sps-section] h2, [data-sps-section] h3, [data-sps-section] h4, [data-sps-section] h5, [data-sps-section] h6 { color: var(--section-heading, inherit) !important; }
        .section a, .tpl-section a, [data-sps-section] a{ color: var(--section-link, var(--bs-link-color)) !important; }
        .section a:hover, .tpl-section a:hover, [data-sps-section] a:hover{ color: var(--section-link-hover, var(--bs-link-hover-color)) !important; }
        .section .btn, .tpl-section .btn, [data-sps-section] .btn{ --bs-btn-bg: var(--section-btn-bg, var(--bs-primary)); --bs-btn-border-color: var(--section-btn-bg, var(--bs-primary)); --bs-btn-color: var(--section-btn-text, #fff); color: var(--section-btn-text, #fff) !important; }
    </style>

    <!-- Dynamic Section CSS (once) -->
    @if(isset($sections) && $sections)
        @foreach($sections as $section)
            @if($section->layout && $section->layout->content && is_array($section->layout->content) && isset($section->layout->content['css']))
                <style>{!! $section->layout->content['css'] !!}</style>
            @endif
        @endforeach
    @endif

    <!-- Dynamic Navigation CSS -->
    @if($navLayout && $navLayout->content && is_array($navLayout->content) && isset($navLayout->content['css']))
        <style>{!! $navLayout->content['css'] !!}</style>
    @endif

    <!-- Dynamic Footer CSS -->
    @if($footerLayout && $footerLayout->content && is_array($footerLayout->content) && isset($footerLayout->content['css']))
        <style>{!! $footerLayout->content['css'] !!}</style>
    @endif

    <!-- Site Colors Overrides (placed last in head to win specificity/order) -->
    <style id="site-color-overrides">
        nav.navbar, .navbar, header.site-header, .main-header { background-color: var(--nav-bg) !important; color: var(--nav-text) !important; }
        #site-navbar [class^="bg-"], #site-navbar [class*=" bg-"]{ background-color: var(--nav-bg) !important; }
        .navbar .navbar-brand{ color: var(--nav-text) !important; }
        .navbar .nav-link{ color: var(--nav-link) !important; }
        .navbar .nav-link:hover{ color: var(--nav-link-hover) !important; }
        .navbar .btn, .navbar .btn-primary{ --bs-btn-bg: var(--nav-btn-bg); --bs-btn-border-color: var(--nav-btn-bg); --bs-btn-color: var(--nav-btn-text); color: var(--nav-btn-text) !important; }

        #site-footer, #site-footer footer, .footer, .site-footer { background-color: var(--footer-bg) !important; color: var(--footer-text) !important; }
        #site-footer [class^="bg-"], #site-footer [class*=" bg-"], .site-footer [class^="bg-"], .site-footer [class*=" bg-"], .footer [class^="bg-"], .footer [class*=" bg-"]{ background-color: var(--footer-bg) !important; }
        #site-footer a, .footer a, .site-footer a{ color: var(--footer-link) !important; }
        #site-footer a:hover, .footer a:hover, .site-footer a:hover{ color: var(--footer-link-hover) !important; }

        .section, .tpl-section, [data-sps-section]{ background-color: var(--section-bg, initial) !important; color: var(--section-text, inherit) !important; }
        .section [class^="bg-"], .section [class*=" bg-"], .tpl-section [class^="bg-"], .tpl-section [class*=" bg-"], [data-sps-section] [class^="bg-"], [data-sps-section] [class*=" bg-"]{ background-color: var(--section-bg, initial) !important; }
        .section h1, .section h2, .section h3, .section h4, .section h5, .section h6,
        .tpl-section h1, .tpl-section h2, .tpl-section h3, .tpl-section h4, .tpl-section h5, .tpl-section h6,
        [data-sps-section] h1, [data-sps-section] h2, [data-sps-section] h3, [data-sps-section] h4, [data-sps-section] h5, [data-sps-section] h6 { color: var(--section-heading, inherit) !important; }
        .section a, .tpl-section a, [data-sps-section] a{ color: var(--section-link, var(--bs-link-color)) !important; }
        .section a:hover, .tpl-section a:hover, [data-sps-section] a:hover{ color: var(--section-link-hover, var(--bs-link-hover-color)) !important; }
        .section .btn, .tpl-section .btn, [data-sps-section] .btn{ --bs-btn-bg: var(--section-btn-bg, var(--bs-primary)); --bs-btn-border-color: var(--section-btn-bg, var(--bs-primary)); --bs-btn-color: var(--section-btn-text, #fff); color: var(--section-btn-text, #fff) !important; }
    </style>

    @stack('head')
</head>

<body>
    <!-- Navigation -->
    <div id="site-navbar">
    @if($navLayout)
        @if(isset($navLayout->processed_content))
            {!! $navLayout->processed_content !!}
        @else
            @php
                $navContent = $navLayout->content;
                if (is_array($navContent) && isset($navContent['html'])) {
                    $navContentHtml = $navContent['html'];
                    // Use all updated config from content, fallback to default_config
                    $navConfig = array_merge(
                        json_decode($navLayout->default_config ?? '{}', true) ?: [],
                        $navContent  // This contains the updated values from Edit Content
                    );
                    // Remove html key from config to avoid conflicts
                    unset($navConfig['html']);
                } elseif (is_string($navContent)) {
                    $decoded = json_decode($navContent, true);
                    if (is_array($decoded) && isset($decoded['html'])) {
                        $navContentHtml = $decoded['html'];
                        $navConfig = array_merge(
                            json_decode($navLayout->default_config ?? '{}', true) ?: [],
                            $decoded
                        );
                        unset($navConfig['html']);
                    } else {
                        $navContentHtml = $navContent;
                        $navConfig = json_decode($navLayout->default_config ?? '{}', true) ?: [];
                    }
                } else {
                    $navContentHtml = '<!-- Navigation content invalid -->';
                    $navConfig = json_decode($navLayout->default_config ?? '{}', true) ?: [];
                }
                
                if (is_array($navContentHtml)) {
                    $navContentHtml = '<!-- Navigation content is array, cannot display -->';
                } elseif (!is_string($navContentHtml)) {
                    $navContentHtml = '<!-- Navigation content invalid -->';
                }
                
                $bladeService = new \App\Services\BladeRenderingService();
                $renderedNav = $bladeService->render($navContentHtml, [ 'config' => $navConfig ]);
            @endphp
            {!! $renderedNav !!}
        @endif
    @else
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="/">{{ $site->site_name ?? 'My Site' }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    @endif
    </div>

    <!-- Main Content -->
    <main>
        @if($sections && $sections->count() > 0)
            @foreach($sections as $section)
                @if($section->layout && $section->layout->content)
                    <div class="section" id="section-{{ $section->id }}">
                        @php
                            // Get section configuration
                            $sectionConfig = [];
                            if (is_string($section->content)) {
                                $sectionConfig = json_decode($section->content, true) ?: [];
                            } elseif (is_array($section->content)) {
                                $sectionConfig = $section->content;
                            }

                            // Get layout content
                            $layoutContent = $section->layout->content;
                            if (is_array($layoutContent) && isset($layoutContent['html'])) {
                                $htmlContent = $layoutContent['html'];
                            } elseif (is_string($layoutContent)) {
                                $decoded = json_decode($layoutContent, true);
                                if (is_array($decoded) && isset($decoded['html'])) {
                                    $htmlContent = $decoded['html'];
                                } else {
                                    $htmlContent = $layoutContent;
                                }
                            } else {
                                $htmlContent = '<!-- Layout content invalid -->';
                            }

                            // Make config available for template
                            $defaultConfig = $section->layout->default_config;
                            if (is_string($defaultConfig)) {
                                $defaultConfig = json_decode($defaultConfig, true) ?: [];
                            } elseif (!is_array($defaultConfig)) {
                                $defaultConfig = [];
                            }
                            $config = array_merge($defaultConfig, $sectionConfig);
                            $variant = 1;

                            $bladeService = new \App\Services\BladeRenderingService();
                            $renderedContent = $bladeService->render($htmlContent, [
                                'config' => $config,
                                'variant' => $variant,
                                'section' => $section
                            ]);
                        @endphp
                        {!! $renderedContent !!}
                    </div>
                @else
                    <div class="section py-5" id="section-{{ $section->id }}">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <h2>{{ $section->parsed_content['title'] ?? $section->name }}</h2>
                                    <div class="content">
                                        {!! nl2br(e($section->parsed_content['content'] ?? 'Section content goes here.')) !!}
                                    </div>
                                    @if(!empty($section->parsed_content['button_text']))
                                        <div class="mt-3">
                                            <button class="btn btn-primary">
                                                {{ $section->parsed_content['button_text'] }}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <!-- Default content if no sections -->
            <div class="container my-5">
                <div class="row">
                    <div class="col-12">
                        <h1>{{ $page->name }}</h1>
                        <p>Welcome to {{ $site->site_name }}</p>
                        <p class="text-muted">This page has no sections assigned. Please contact the administrator to add content.</p>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <div id="site-footer">
    @if($footerLayout)
        @if(isset($footerLayout->processed_content))
            {!! $footerLayout->processed_content !!}
        @else
            @php
                $footerContent = $footerLayout->content;
                if (is_array($footerContent) && isset($footerContent['html'])) {
                    $footerContentHtml = $footerContent['html'];
                    // Use all updated config from content, fallback to default_config
                    $footerConfig = array_merge(
                        json_decode($footerLayout->default_config ?? '{}', true) ?: [],
                        $footerContent  // This contains the updated values from Edit Content
                    );
                    // Remove html key from config to avoid conflicts
                    unset($footerConfig['html']);
                } elseif (is_string($footerContent)) {
                    $decoded = json_decode($footerContent, true);
                    if (is_array($decoded) && isset($decoded['html'])) {
                        $footerContentHtml = $decoded['html'];
                        $footerConfig = array_merge(
                            json_decode($footerLayout->default_config ?? '{}', true) ?: [],
                            $decoded
                        );
                        unset($footerConfig['html']);
                    } else {
                        $footerContentHtml = $footerContent;
                        $footerConfig = json_decode($footerLayout->default_config ?? '{}', true) ?: [];
                    }
                } else {
                    $footerContentHtml = '<!-- Footer content invalid -->';
                    $footerConfig = json_decode($footerLayout->default_config ?? '{}', true) ?: [];
                }
                
                if (is_array($footerContentHtml)) {
                    $footerContentHtml = '<!-- Footer content is array, cannot display -->';
                } elseif (!is_string($footerContentHtml)) {
                    $footerContentHtml = '<!-- Footer content invalid -->';
                }
                
                $bladeService = new \App\Services\BladeRenderingService();
                $renderedFooter = $bladeService->render($footerContentHtml, [ 'config' => $footerConfig ]);
            @endphp
            {!! $renderedFooter !!}
        @endif
    @else
        <footer class="bg-dark text-white py-4 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h5>{{ $site->site_name ?? 'My Site' }}</h5>
                        <p>{{ $config['description'] ?? 'Welcome to our website' }}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p>&copy; {{ date('Y') }} {{ $site->site_name ?? 'My Site' }}. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>
    @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS (Animate On Scroll) -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: true, offset: 100 });
    </script>

    <!-- Dynamic Section JavaScript (once) -->
    @if(isset($sections) && $sections)
        @foreach($sections as $section)
            @if($section->layout && $section->layout->content && is_array($section->layout->content) && isset($section->layout->content['js']))
                <script>{!! $section->layout->content['js'] !!}</script>
            @endif
        @endforeach
    @endif

    <!-- Dynamic Navigation JavaScript -->
    @if($navLayout && $navLayout->content && is_array($navLayout->content) && isset($navLayout->content['js']))
        <script>{!! $navLayout->content['js'] !!}</script>
    @endif

    <!-- Dynamic Footer JavaScript -->
    @if($footerLayout && $footerLayout->content && is_array($footerLayout->content) && isset($footerLayout->content['js']))
        <script>{!! $footerLayout->content['js'] !!}</script>
    @endif

    <!-- Final Site Color Overrides (after all body content) -->
    <style id="site-color-overrides-bottom">
        /* Navigation */
        nav.navbar, .navbar, header.site-header, .main-header {
            background: var(--nav-bg) !important;
            background-color: var(--nav-bg) !important;
            background-image: none !important;
            color: var(--nav-text) !important;
        }
        #site-navbar [class^="bg-"], #site-navbar [class*=" bg-"],
        #site-navbar .bg-gradient, #site-navbar .gradient-bg, #site-navbar .bg-linear,
        #site-navbar [style*="gradient"] {
            background: var(--nav-bg) !important;
            background-image: none !important;
        }
        .navbar .navbar-brand{ color: var(--nav-text) !important; }
        .navbar .nav-link{ color: var(--nav-link) !important; }
        .navbar .nav-link:hover{ color: var(--nav-link-hover) !important; }
        .navbar .btn, .navbar .btn-primary{ --bs-btn-bg: var(--nav-btn-bg); --bs-btn-border-color: var(--nav-btn-bg); --bs-btn-color: var(--nav-btn-text); color: var(--nav-btn-text) !important; }

        /* Footer */
        #site-footer, #site-footer footer, .footer, .site-footer {
            background: var(--footer-bg) !important;
            background-color: var(--footer-bg) !important;
            background-image: none !important;
            color: var(--footer-text) !important;
        }
        #site-footer [class^="bg-"], #site-footer [class*=" bg-"], .site-footer [class^="bg-"], .site-footer [class*=" bg-"], .footer [class^="bg-"], .footer [class*=" bg-"],
        #site-footer .bg-gradient, .site-footer .bg-gradient, .footer .bg-gradient,
        #site-footer .gradient-bg, .site-footer .gradient-bg, .footer .gradient-bg,
        #site-footer [style*="gradient"], .site-footer [style*="gradient"], .footer [style*="gradient"] {
            background: var(--footer-bg) !important;
            background-image: none !important;
        }
        #site-footer a, .footer a, .site-footer a{ color: var(--footer-link) !important; }
        #site-footer a:hover, .footer a:hover, .site-footer a:hover{ color: var(--footer-link-hover) !important; }

        /* Sections */
        main section, .section, .tpl-section, [data-sps-section]{
            background: var(--section-bg, initial) !important;
            background-image: none !important;
            color: var(--section-text, inherit) !important;
        }
        .section [class^="bg-"], .section [class*=" bg-"], .tpl-section [class^="bg-"], .tpl-section [class*=" bg-"], [data-sps-section] [class^="bg-"], [data-sps-section] [class*=" bg-"],
        .section .bg-gradient, .tpl-section .bg-gradient, [data-sps-section] .bg-gradient,
        .section .gradient-bg, .tpl-section .gradient-bg, [data-sps-section] .gradient-bg,
        .section [style*="gradient"], .tpl-section [style*="gradient"], [data-sps-section] [style*="gradient"],
        main section .bg-gradient, main section .gradient-bg, main section [style*="gradient"]{
            background: var(--section-bg, initial) !important;
            background-image: none !important;
        }
        .section h1, .section h2, .section h3, .section h4, .section h5, .section h6,
        .tpl-section h1, .tpl-section h2, .tpl-section h3, .tpl-section h4, .tpl-section h5, .tpl-section h6,
        [data-sps-section] h1, [data-sps-section] h2, [data-sps-section] h3, [data-sps-section] h4, [data-sps-section] h5, [data-sps-section] h6 { color: var(--section-heading, inherit) !important; }
        .section a, .tpl-section a, [data-sps-section] a{ color: var(--section-link, var(--bs-link-color)) !important; }
        .section a:hover, .tpl-section a:hover, [data-sps-section] a:hover{ color: var(--section-link-hover, var(--bs-link-hover-color)) !important; }
        .section .btn, .tpl-section .btn, [data-sps-section] .btn{ --bs-btn-bg: var(--section-btn-bg, var(--bs-primary)); --bs-btn-border-color: var(--section-btn-bg, var(--bs-primary)); --bs-btn-color: var(--section-btn-text, #fff); color: var(--section-btn-text, #fff) !important; }
    </style>

    @stack('scripts')
</body>
</html>
