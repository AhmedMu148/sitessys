<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ $site->name }} - {{ $page->name }}">
    <meta name="author" content="{{ $site->name }}">
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="{{ asset('img/icons/icon-48x48.png') }}" />
    
    <title>{{ $page->name }} - {{ $site->name }}</title>
    
    <!-- AdminKit CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
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
        @foreach($navDesigns as $navDesign)
            @php
                $data = $navDesign->data ?? [];
            @endphp
            @include('frontend.components.nav', ['data' => $data])
        @endforeach

        <!-- Main Content -->
        <main class="main-content">
            @foreach($designs->where('layoutType.name', 'section') as $design)
                @php
                    $data = $design->data ?? [];
                @endphp
                @include('frontend.components.section', ['data' => $data, 'layoutName' => $design->layout->name])
            @endforeach
        </main>

        <!-- Footer -->
        @foreach($footerDesigns as $footerDesign)
            @php
                $data = $footerDesign->data ?? [];
            @endphp
            @include('frontend.components.footer', ['data' => $data])
        @endforeach
    </div>

    <!-- AdminKit JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Custom footer scripts -->
    @foreach($customScripts->where('location', 'footer') as $script)
        <script>
            {!! $script->content !!}
        </script>
    @endforeach
    
    @stack('footer-scripts')
</body>
</html>
