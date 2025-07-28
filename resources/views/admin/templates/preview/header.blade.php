@php
    $lang = $language ?? 'en';
    $isRTL = $lang === 'ar';
@endphp

<div class="preview-header" @if($isRTL) dir="rtl" @endif>
    <style>
        .preview-header {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }
        .preview-header h1 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        .preview-nav {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }
        .preview-nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: background-color 0.3s ease;
        }
        .preview-nav a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .preview-info {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 0.375rem;
            margin-top: 1rem;
            text-align: center;
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            .preview-nav {
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
            }
        }
    </style>

    <h1>{{ $template->name }}</h1>
    
    @if($template->description)
        <p style="text-align: center; margin-bottom: 1.5rem; opacity: 0.9;">
            {{ $template->description }}
        </p>
    @endif

    <nav class="preview-nav">
        @if($lang === 'en')
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Services</a>
            <a href="#">Portfolio</a>
            <a href="#">Contact</a>
        @else
            <a href="#">الصفحة الرئيسية</a>
            <a href="#">من نحن</a>
            <a href="#">خدماتنا</a>
            <a href="#">أعمالنا</a>
            <a href="#">اتصل بنا</a>
        @endif
    </nav>

    <div class="preview-info">
        @if($lang === 'en')
            <strong>Template Preview:</strong> {{ $template->name }}<br>
            <small>This is how your header will appear on the website</small>
        @else
            <strong>معاينة القالب:</strong> {{ $template->name }}<br>
            <small>هذا هو شكل الناف كما سيظهر في الموقع</small>
        @endif
    </div>
</div>
