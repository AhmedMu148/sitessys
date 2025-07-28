<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ $config['description'] ?? $site->site_name }}">
    <meta name="keywords" content="{{ $config['keyword'] ?? 'website' }}">
    <meta name="author" content="{{ $site->site_name }}">
    
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
    
    <!-- Dynamic Section CSS -->
    @if(isset($sections) && $sections)
        @foreach($sections as $section)
            @if($section->layout && $section->layout->content && is_array($section->layout->content) && isset($section->layout->content['css']))
                <style>
                    {!! $section->layout->content['css'] !!}
                </style>
            @endif
        @endforeach
    @endif
    
    <!-- Dynamic Navigation CSS -->
    @if($navLayout && $navLayout->content && is_array($navLayout->content) && isset($navLayout->content['css']))
        <style>
            {!! $navLayout->content['css'] !!}
        </style>
    @endif
    
    <!-- Dynamic Footer CSS -->
    @if($footerLayout && $footerLayout->content && is_array($footerLayout->content) && isset($footerLayout->content['css']))
        <style>
            {!! $footerLayout->content['css'] !!}
        </style>
    @endif
    
    <!-- Dynamic Section CSS -->
    @if(isset($sections) && $sections)
        @foreach($sections as $section)
            @if($section->layout && $section->layout->content && is_array($section->layout->content) && isset($section->layout->content['css']))
                <style>
                    {!! $section->layout->content['css'] !!}
                </style>
            @endif
        @endforeach
    @endif
    
    @stack('head')
</head>

<body>
    <!-- Navigation -->
    @if($navLayout)
        @php
            $navContent = $navLayout->content;
            if (is_array($navContent) && isset($navContent['html'])) {
                $navContent = $navContent['html'];
            } elseif (is_string($navContent)) {
                $decoded = json_decode($navContent, true);
                if (is_array($decoded) && isset($decoded['html'])) {
                    $navContent = $decoded['html'];
                }
            }
            
            // Final validation - if still array, convert to string or use fallback
            if (is_array($navContent)) {
                $navContent = '<!-- Navigation content is array, cannot display -->';
            } elseif (!is_string($navContent)) {
                $navContent = '<!-- Navigation content invalid -->';
            }
            
            // Get navigation configuration
            $navConfig = $navLayout->default_config ?? [];
            if (is_string($navConfig)) {
                $navConfig = json_decode($navConfig, true) ?: [];
            }
            
            // Merge with nav_data if available
            if (isset($navData) && is_array($navData)) {
                $navConfig = array_merge($navConfig, $navData);
            }
            
            // Use Blade rendering service
            $bladeService = new \App\Services\BladeRenderingService();
            $renderedNav = $bladeService->render($navContent, [
                'config' => $navConfig,
                'navData' => $navData ?? []
            ]);
        @endphp
        {!! $renderedNav !!}
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
                            $variant = 1; // Default variant
                            
                            // Use Blade rendering service for dynamic templates
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
    @if($footerLayout)
        @php
            $footerContent = $footerLayout->content;
            if (is_array($footerContent) && isset($footerContent['html'])) {
                $footerContent = $footerContent['html'];
            } elseif (is_string($footerContent)) {
                $decoded = json_decode($footerContent, true);
                if (is_array($decoded) && isset($decoded['html'])) {
                    $footerContent = $decoded['html'];
                }
            }
            
            // Final validation - if still array, convert to string or use fallback
            if (is_array($footerContent)) {
                $footerContent = '<!-- Footer content is array, cannot display -->';
            } elseif (!is_string($footerContent)) {
                $footerContent = '<!-- Footer content invalid -->';
            }
            
            // Get footer configuration
            $footerConfig = $footerLayout->default_config ?? [];
            if (is_string($footerConfig)) {
                $footerConfig = json_decode($footerConfig, true) ?: [];
            }
            
            // Merge with footer_data if available
            if (isset($footerData) && is_array($footerData)) {
                $footerConfig = array_merge($footerConfig, $footerData);
            }
            
            // Use Blade rendering service
            $bladeService = new \App\Services\BladeRenderingService();
            $renderedFooter = $bladeService->render($footerContent, [
                'config' => $footerConfig,
                'footerData' => $footerData ?? []
            ]);
        @endphp
        {!! $renderedFooter !!}
    @else
        <footer class="bg-dark text-white py-4 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h5>{{ $site->site_name ?? 'My Site' }}</h5>
                        <p>{{ $config['description'] ?? 'Welcome to our website' }}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p>&copy; 2025 {{ $site->site_name ?? 'My Site' }}. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>
    @endif
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS (Animate On Scroll) -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    </script>
    
    <!-- Dynamic Section JavaScript -->
    @if(isset($sections) && $sections)
        @foreach($sections as $section)
            @if($section->layout && $section->layout->content && is_array($section->layout->content) && isset($section->layout->content['js']))
                <script>
                    {!! $section->layout->content['js'] !!}
                </script>
            @endif
        @endforeach
    @endif
    
    <!-- Dynamic Navigation JavaScript -->
    @if($navLayout && $navLayout->content && is_array($navLayout->content) && isset($navLayout->content['js']))
        <script>
            {!! $navLayout->content['js'] !!}
        </script>
    @endif
    
    <!-- Dynamic Footer JavaScript -->
    @if($footerLayout && $footerLayout->content && is_array($footerLayout->content) && isset($footerLayout->content['js']))
        <script>
            {!! $footerLayout->content['js'] !!}
        </script>
    @endif
    
    <!-- Dynamic Section JavaScript -->
    @if(isset($sections) && $sections)
        @foreach($sections as $section)
            @if($section->layout && $section->layout->content && is_array($section->layout->content) && isset($section->layout->content['js']))
                <script>
                    {!! $section->layout->content['js'] !!}
                </script>
            @endif
        @endforeach
    @endif
    
    @stack('scripts')
</body>
</html>
