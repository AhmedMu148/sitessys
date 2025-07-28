@php
    $lang = $language ?? 'en';
    $isRTL = $lang === 'ar';
@endphp

<div class="preview-footer" @if($isRTL) dir="rtl" @endif>
    <style>
        .preview-footer {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .footer-section h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #3498db;
        }
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer-links li {
            margin-bottom: 0.5rem;
        }
        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .footer-links a:hover {
            color: #3498db;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 1rem;
            text-align: center;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }
        .preview-info {
            background: rgba(52, 152, 219, 0.2);
            padding: 1rem;
            border-radius: 0.375rem;
            margin-top: 1rem;
            text-align: center;
            font-size: 0.9rem;
        }
    </style>

    <div class="footer-content">
        <div class="footer-section">
            @if($lang === 'en')
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            @else
                <h3>روابط سريعة</h3>
                <ul class="footer-links">
                    <li><a href="#">الصفحة الرئيسية</a></li>
                    <li><a href="#">من نحن</a></li>
                    <li><a href="#">خدماتنا</a></li>
                    <li><a href="#">اتصل بنا</a></li>
                </ul>
            @endif
        </div>

        <div class="footer-section">
            @if($lang === 'en')
                <h3>Contact Info</h3>
                <ul class="footer-links">
                    <li>📧 info@example.com</li>
                    <li>📱 +1 (555) 123-4567</li>
                    <li>📍 123 Business Street</li>
                    <li>🌐 www.example.com</li>
                </ul>
            @else
                <h3>معلومات التواصل</h3>
                <ul class="footer-links">
                    <li>📧 info@example.com</li>
                    <li>📱 +966 50 123 4567</li>
                    <li>📍 الرياض، المملكة العربية السعودية</li>
                    <li>🌐 www.example.com</li>
                </ul>
            @endif
        </div>

        <div class="footer-section">
            @if($lang === 'en')
                <h3>Follow Us</h3>
                <ul class="footer-links">
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">LinkedIn</a></li>
                </ul>
            @else
                <h3>تابعنا</h3>
                <ul class="footer-links">
                    <li><a href="#">فيسبوك</a></li>
                    <li><a href="#">تويتر</a></li>
                    <li><a href="#">إنستغرام</a></li>
                    <li><a href="#">لينكد إن</a></li>
                </ul>
            @endif
        </div>
    </div>

    <div class="footer-bottom">
        @if($lang === 'en')
            © {{ date('Y') }} Your Company Name. All rights reserved.
        @else
            © {{ date('Y') }} اسم شركتك. جميع الحقوق محفوظة.
        @endif
    </div>

    <div class="preview-info">
        @if($lang === 'en')
            <strong>Template Preview:</strong> {{ $template->name }}<br>
            <small>This is how your footer will appear on the website</small>
        @else
            <strong>معاينة القالب:</strong> {{ $template->name }}<br>
            <small>هذا هو شكل الفوتر كما سيظهر في الموقع</small>
        @endif
    </div>
</div>
