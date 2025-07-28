@php
    $lang = $language ?? 'en';
    $isRTL = $lang === 'ar';
    $sectionContent = $content[$lang] ?? $content['en'] ?? [];
@endphp

<div class="preview-section" @if($isRTL) dir="rtl" @endif>
    <style>
        .preview-section {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8faff 0%, #e3f2fd 100%);
            border: 1px solid rgba(34, 46, 60, 0.1);
            border-radius: 0.75rem;
            padding: 3rem 2rem;
            margin: 1rem 0;
            text-align: center;
        }
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #222e3c;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        .section-subtitle {
            font-size: 1.25rem;
            color: #64748b;
            margin-bottom: 2rem;
            line-height: 1.4;
        }
        .section-description {
            font-size: 1rem;
            color: #475569;
            margin-bottom: 2rem;
            line-height: 1.6;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .section-button {
            display: inline-block;
            background: linear-gradient(135deg, #222e3c 0%, #2b3947 100%);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .section-button:hover {
            background: linear-gradient(135deg, #1a2530 0%, #222e3c 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 46, 60, 0.25);
            color: white;
        }
        .section-image {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 2rem 0;
            box-shadow: 0 4px 20px rgba(34, 46, 60, 0.15);
        }
        .preview-info {
            background: rgba(34, 46, 60, 0.1);
            padding: 1rem;
            border-radius: 0.375rem;
            margin-top: 2rem;
            text-align: center;
            font-size: 0.9rem;
            color: #64748b;
        }
        .section-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .feature-item {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(34, 46, 60, 0.1);
            transition: transform 0.3s ease;
        }
        .feature-item:hover {
            transform: translateY(-4px);
        }
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #222e3c;
        }
        .feature-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #222e3c;
            margin-bottom: 0.5rem;
        }
        .feature-text {
            font-size: 0.9rem;
            color: #64748b;
        }
    </style>

    @if($template->preview_image)
        <img src="{{ $template->preview_image }}" alt="{{ $template->name }}" class="section-image">
    @endif

    <h1 class="section-title">
        {{ $sectionContent['title'] ?? ($lang === 'en' ? 'Sample Section Title' : 'عنوان القسم التجريبي') }}
    </h1>

    <h2 class="section-subtitle">
        {{ $sectionContent['subtitle'] ?? ($lang === 'en' ? 'Beautiful subtitle that describes this section' : 'عنوان فرعي جميل يصف هذا القسم') }}
    </h2>

    <p class="section-description">
        {{ $sectionContent['description'] ?? ($lang === 'en' ? 'This is a sample description that shows how your content will look. You can customize this text, add images, buttons, and much more through the content management system.' : 'هذا وصف تجريبي يوضح كيف سيبدو المحتوى الخاص بك. يمكنك تخصيص هذا النص وإضافة الصور والأزرار والمزيد من خلال نظام إدارة المحتوى.') }}
    </p>

    @if(in_array($template->layout_type, ['hero', 'call-to-action', 'landing']))
        <div class="section-features">
            <div class="feature-item">
                <div class="feature-icon">🚀</div>
                @if($lang === 'en')
                    <div class="feature-title">Fast Performance</div>
                    <div class="feature-text">Lightning-fast loading times</div>
                @else
                    <div class="feature-title">أداء سريع</div>
                    <div class="feature-text">أوقات تحميل سريعة جداً</div>
                @endif
            </div>
            <div class="feature-item">
                <div class="feature-icon">🔒</div>
                @if($lang === 'en')
                    <div class="feature-title">Secure</div>
                    <div class="feature-text">Enterprise-level security</div>
                @else
                    <div class="feature-title">آمن</div>
                    <div class="feature-text">أمان على مستوى المؤسسات</div>
                @endif
            </div>
            <div class="feature-item">
                <div class="feature-icon">📱</div>
                @if($lang === 'en')
                    <div class="feature-title">Responsive</div>
                    <div class="feature-text">Works on all devices</div>
                @else
                    <div class="feature-title">متجاوب</div>
                    <div class="feature-text">يعمل على جميع الأجهزة</div>
                @endif
            </div>
        </div>
    @endif

    @if($sectionContent['button_text'] ?? false)
        <a href="{{ $sectionContent['button_url'] ?? '#' }}" class="section-button">
            {{ $sectionContent['button_text'] }}
        </a>
    @else
        <a href="#" class="section-button">
            {{ $lang === 'en' ? 'Learn More' : 'اعرف أكثر' }}
        </a>
    @endif

    <div class="preview-info">
        @if($lang === 'en')
            <strong>Template Preview:</strong> {{ $template->name }}<br>
            <small>This is how your section will appear on the website. You can edit the content, images, and styling through the template editor.</small>
        @else
            <strong>معاينة القالب:</strong> {{ $template->name }}<br>
            <small>هذا هو شكل القسم كما سيظهر في الموقع. يمكنك تعديل المحتوى والصور والتصميم من خلال محرر القوالب.</small>
        @endif
    </div>
</div>
