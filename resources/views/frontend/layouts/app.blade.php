<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @php
        $metaDescription = $siteConfigs->where('key', 'meta_description')->first()->value ?? "{$site->name} - {$page->name}";
    @endphp
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="author" content="{{ $site->name }}">
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    @php
        $favicon = $siteConfigs->where('key', 'favicon')->first()->value ?? 'img/favicon.ico';
    @endphp
    <link rel="shortcut icon" href="{{ asset($favicon) }}" />
    
    <title>{{ $page->name }} - {{ $site->name }}</title>
    
    <!-- Third-party CSS -->
    <link href="{{ asset('vendor/css/jsvectormap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/simplebar.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/swiper-bundle.min.css') }}" rel="stylesheet">
    
    <!-- Application assets -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    @foreach($customCss as $css)
        <style>
            {!! $css->content !!}
        </style>
    @endforeach
    
    <!-- Custom head scripts -->
    @foreach($customScripts->where('location', 'head') as $script)
        <script>
            {!! $script->content !!}
        </script>
    @endforeach
    
    @stack('head')
</head>

<body>
    <!-- Landing Page Layout -->
    <div class="landing-page">
        <!-- Navigation -->
        @php
            $navData = [
                'brand' => $site->name,
                'menu_items' => [
                    ['title' => 'Home', 'url' => '/'],
                    ['title' => 'About', 'url' => '/about'],
                    ['title' => 'Services', 'url' => '/services'],
                    ['title' => 'Contact', 'url' => '/contact']
                ],
                'cta_text' => 'Get Started',
                'cta_url' => '/contact'
            ];
        @endphp
        @include('frontend.components.nav', ['data' => $navData])

        <!-- Page Content -->
        <main>
            @foreach($designs->whereNotIn('layoutType.name', ['nav', 'footer']) as $design)
                @php
                    $componentName = $design->layout->name;
                    $data = json_decode($design->data ?? '{}', true);
                    $data['logo'] = $siteConfigs->where('key', 'logo')->first()->value ?? 'img/logo.svg';
                    $data['contactInfo'] = json_decode($siteConfigs->where('key', 'contact_info')->first()->value ?? '{}', true);
                    $data['socialLinks'] = json_decode($siteConfigs->where('key', 'social_links')->first()->value ?? '{}', true);
                @endphp
                @includeIf("frontend.components.$componentName", ['data' => $data])
            @endforeach
        </main>

        <!-- Footer -->
        @php
            $footerData = [
                'logo' => $siteConfigs->where('key', 'logo')->first()->value ?? 'img/logo.svg',
                'description' => $site->description,
                'contact_info' => json_decode($siteConfigs->where('key', 'contact_info')->first()->value ?? '{}', true),
                'social_links' => json_decode($siteConfigs->where('key', 'social_links')->first()->value ?? '{}', true),
                'menu_columns' => [
                    [
                        'title' => 'Company',
                        'links' => [
                            ['title' => 'About Us', 'url' => '/about'],
                            ['title' => 'Services', 'url' => '/services'],
                            ['title' => 'Contact', 'url' => '/contact']
                        ]
                    ],
                    [
                        'title' => 'Legal',
                        'links' => [
                            ['title' => 'Privacy Policy', 'url' => '/privacy'],
                            ['title' => 'Terms of Service', 'url' => '/terms'],
                            ['title' => 'Cookie Policy', 'url' => '/cookies']
                        ]
                    ]
                ]
            ];
        @endphp
        @include('frontend.components.footer', ['data' => $footerData])
    </div>
    
    <!-- Bootstrap & AdminKit Scripts -->
    <script src="{{ asset('build/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/simplebar.min.js') }}"></script>
    
    <!-- Initialize Feather Icons -->
    <script>
        feather.replace();
    </script>
    
    <!-- Initialize tooltips and popovers -->
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    </script>
    
    <!-- Custom body scripts -->
    @foreach($customScripts->where('location', 'body') as $script)
        <script>
            {!! $script->content !!}
        </script>
    @endforeach
    
    @stack('scripts')
</body>
</html>
