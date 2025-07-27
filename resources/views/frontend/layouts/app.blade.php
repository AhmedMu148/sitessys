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
    
    @stack('head')
</head>

<body>
    <!-- Navigation -->
    @if($navLayout && $navLayout->processed_content)
        {!! $navLayout->processed_content !!}
    @elseif($navLayout)
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
        @endphp
        {!! $navContent !!}
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
                @if($section->layout && $section->layout->processed_content)
                    <div class="section" id="section-{{ $section->id }}">
                        {!! $section->layout->processed_content !!}
                    </div>
                @elseif($section->layout && $section->layout->content)
                    <div class="section" id="section-{{ $section->id }}">
                        {!! $section->layout->content !!}
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
    @if($footerLayout && $footerLayout->processed_content)
        {!! $footerLayout->processed_content !!}
    @elseif($footerLayout)
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
        @endphp
        {!! $footerContent !!}
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
    
    @stack('scripts')
</body>
</html>
