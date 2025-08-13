<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TplLayout;

class CustomTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        echo "🎨 Seeding themed templates (5 nav, 5 footer, 20 sections)...\n";

        $this->seedNavs();
        $this->seedFooters();
        $this->seedSections();

        echo "✅ Done!\n";
    }

    /* =========================
     |  NAVBARS (5 THEMES)
     |========================= */

    private function seedNavs(): void
    {
        $navs = [
            // 1) Modern Gradient Navigation
            ['tpl_id' => 'nav-modern-gradient', 'name' => 'Navigation • Modern Gradient', 'variant' => 'modern'],
            // 2) Glass Blur Navigation with Glassmorphism Effect
            ['tpl_id' => 'nav-glass-blur', 'name' => 'Navigation • Glass Blur', 'variant' => 'glass'],
            // 3) Split Center Logo Navigation
            ['tpl_id' => 'nav-split-center', 'name' => 'Navigation • Split Center Logo', 'variant' => 'split'],
            // 4) Minimal Underline Animation Navigation
            ['tpl_id' => 'nav-minimal-underline', 'name' => 'Navigation • Minimal Underline', 'variant' => 'underline'],
            // 5) Rounded Pills Style Navigation
            ['tpl_id' => 'nav-rounded-pills', 'name' => 'Navigation • Rounded Pills', 'variant' => 'pills'],
        ];

        foreach ($navs as $i => $n) {
            $content = $this->getNavVariant($n['variant']);
            TplLayout::updateOrCreate(
                ['tpl_id' => $n['tpl_id']],
                [
                    'tpl_id' => $n['tpl_id'],
                    'layout_type' => 'header',
                    'name' => $n['name'],
                    'description' => 'Professional navigation theme: ' . $n['variant'],
                    'preview_image' => "/img/templates/{$n['tpl_id']}-preview.jpg",
                    'path' => "frontend.templates.headers.{$n['tpl_id']}",
                    'content' => $content['content'],
                    'configurable_fields' => $content['fields'],
                    'default_config' => $content['defaults'],
                    'status' => true,
                    'sort_order' => $i + 1
                ]
            );
        }
    }

    private function getNavVariant(string $variant): array
    {
        switch ($variant) {
            case 'glass':
                return [
                    'defaults' => [
                        'brand_text' => 'Your Brand',
                        'text_color' => '#0f172a',
                        'show_search' => false,
                        'sticky' => true
                    ],
                    'fields' => [
                        'brand_text' => ['type' => 'text','default' => 'Your Brand','label' => 'Brand'],
                        'logo_url' => ['type' => 'url','default' => '','label' => 'Logo'],
                        'text_color' => ['type' => 'color','default' => '#0f172a','label' => 'Text Color'],
                        'sticky' => ['type' => 'boolean','default' => true,'label' => 'Sticky'],
                        'menu' => ['type' => 'array','label' => 'Menu','default' => [
                            ['label' => 'Home','url' => '/'],
                            ['label' => 'Studio','url' => '/studio'],
                            ['label' => 'Work','url' => '/work'],
                            ['label' => 'Careers','url' => '/careers'],
                            ['label' => 'Contact','url' => '/contact'],
                        ]],
                    ],
                    'content' => [
                        'html' => '
<nav class="nav-glass {{ ($config[\'sticky\']??true)?\'sticky-top\':\'\' }}">
 <div class="container d-flex align-items-center justify-content-between rounded-4 p-2 mt-3"
      style="backdrop-filter: blur(14px); background: rgba(255,255,255,0.55); border:1px solid rgba(0,0,0,0.06);">
   <a class="d-flex align-items-center text-decoration-none" href="/">
      @if(!empty($config[\'logo_url\']))<img src="{{ $config[\'logo_url\'] }}" height="36" class="me-2">@endif
      <strong style="color: {{ $config[\'text_color\']??\'#0f172a\' }};">{{ $config[\'brand_text\']??\'Brand\' }}</strong>
   </a>
   <ul class="nav">
     @foreach(($config[\'menu\']??[]) as $item)
       <li class="nav-item">
         <a class="nav-link px-3 rounded-pill fw-medium hover-lift"
            href="{{ $item[\'url\'] }}"
            style="color: {{ $config[\'text_color\']??\'#0f172a\' }};">
            {{ $item[\'label\'] }}
         </a>
       </li>
     @endforeach
   </ul>
 </div>
</nav>',
                        'css' => '.nav-glass .hover-lift{transition:transform .2s,background .2s} .nav-glass .hover-lift:hover{transform:translateY(-2px);background:rgba(0,0,0,.04)}',
                        'js' => '',
                    ],
                ];
            case 'split':
                return [
                    'defaults' => [
                        'text_color' => '#111827',
                        'accent' => '#2563eb',
                        'sticky' => true,
                        'left_menu' => [
                            ['label' => 'Home','url' => '/'],
                            ['label' => 'Services','url' => '/services'],
                            ['label' => 'Pricing','url' => '/pricing'],
                        ],
                        'right_menu' => [
                            ['label' => 'Blog','url' => '/blog'],
                            ['label' => 'About','url' => '/about'],
                            ['label' => 'Contact','url' => '/contact'],
                        ],
                    ],
                    'fields' => [
                        'logo_url' => ['type'=>'url','default'=>'','label'=>'Logo'],
                        'text_color' => ['type'=>'color','default'=>'#111827','label'=>'Text'],
                        'accent' => ['type'=>'color','default'=>'#2563eb','label'=>'Accent'],
                        'sticky' => ['type'=>'boolean','default'=>true,'label'=>'Sticky'],
                        'left_menu' => ['type'=>'array','default'=>[],'label'=>'Left Menu'],
                        'right_menu' => ['type'=>'array','default'=>[],'label'=>'Right Menu'],
                    ],
                    'content' => [
                        'html' => '
<nav class="nav-split {{ ($config[\'sticky\']??true)?\'sticky-top\':\'\' }}">
 <div class="container d-flex align-items-center justify-content-between py-3">
   <ul class="nav me-auto">
     @foreach(($config[\'left_menu\']??[]) as $m)
       <li class="nav-item"><a class="nav-link px-3" href="{{ $m[\'url\'] }}">{{ $m[\'label\'] }}</a></li>
     @endforeach
   </ul>
   <a href="/" class="mx-4 text-decoration-none d-flex align-items-center">
     @if(!empty($config[\'logo_url\']))<img src="{{ $config[\'logo_url\'] }}" height="40" class="me-2">@endif
     <span class="badge rounded-pill px-3 py-2" style="background: {{ $config[\'accent\']??\'#2563eb\' }};">{{ $config[\'brand_text\']??\'Brand\' }}</span>
   </a>
   <ul class="nav ms-auto">
     @foreach(($config[\'right_menu\']??[]) as $m)
       <li class="nav-item"><a class="nav-link px-3" href="{{ $m[\'url\'] }}">{{ $m[\'label\'] }}</a></li>
     @endforeach
   </ul>
 </div>
</nav>',
                        'css' => '.nav-split .nav-link{color:#111827;position:relative} .nav-split .nav-link:after{content:"";position:absolute;left:50%;bottom:0;width:0;height:2px;background:currentColor;transition:.2s} .nav-split .nav-link:hover:after{left:0;width:100%}',
                        'js' => '',
                    ],
                ];
            case 'underline':
                return [
                    'defaults' => [
                        'bg' => '#ffffff',
                        'text' => '#0f172a',
                        'underline' => '#ef4444',
                        'search' => true,
                    ],
                    'fields' => [
                        'bg' => ['type'=>'color','default'=>'#ffffff','label'=>'Background'],
                        'text' => ['type'=>'color','default'=>'#0f172a','label'=>'Text'],
                        'underline' => ['type'=>'color','default'=>'#ef4444','label'=>'Underline'],
                        'search' => ['type'=>'boolean','default'=>true,'label'=>'Show Search'],
                        'menu' => ['type'=>'array','default'=>[
                            ['label'=>'Docs','url'=>'/docs'],
                            ['label'=>'API','url'=>'/api'],
                            ['label'=>'Community','url'=>'/community'],
                            ['label'=>'Support','url'=>'/support'],
                        ],'label'=>'Menu'],
                    ],
                    'content' => [
                        'html' => '
<nav class="nav-underline" style="background: {{ $config[\'bg\']??\'#fff\' }};">
 <div class="container d-flex align-items-center gap-3 py-3">
   <a href="/" class="fw-bold text-decoration-none" style="color: {{ $config[\'text\']??\'#0f172a\' }};">{{ $config[\'brand_text\']??\'Product\' }}</a>
   <ul class="nav flex-grow-1">
     @foreach(($config[\'menu\']??[]) as $m)
       <li class="nav-item">
         <a class="nav-link nav-underline-link" href="{{ $m[\'url\'] }}" style="--u: {{ $config[\'underline\']??\'#ef4444\' }};">{{ $m[\'label\'] }}</a>
       </li>
     @endforeach
   </ul>
   @if($config[\'search\']??true)
     <form class="ms-auto" style="min-width:220px;">
       <input class="form-control form-control-sm" placeholder="Search…">
     </form>
   @endif
 </div>
</nav>',
                        'css' => '.nav-underline-link{color:#0f172a;position:relative} .nav-underline-link:after{content:"";position:absolute;left:0;right:0;bottom:-6px;height:2px;background:var(--u,#ef4444);transform:scaleX(0);transform-origin:left;transition:.2s} .nav-underline-link:hover:after{transform:scaleX(1)}',
                        'js' => '',
                    ],
                ];
            case 'pills':
                return [
                    'defaults' => [
                        'gradient_start' => '#14b8a6',
                        'gradient_end' => '#3b82f6',
                        'text_color' => '#ffffff',
                        'cta' => ['text'=>'Start Free','url'=>'/signup','style'=>'btn-dark','show'=>true],
                    ],
                    'fields' => [
                        'brand_text' => ['type'=>'text','default'=>'Suite','label'=>'Brand'],
                        'logo_url' => ['type'=>'url','default'=>'','label'=>'Logo'],
                        'gradient_start' => ['type'=>'color','default'=>'#14b8a6','label'=>'Gradient Start'],
                        'gradient_end' => ['type'=>'color','default'=>'#3b82f6','label'=>'Gradient End'],
                        'text_color' => ['type'=>'color','default'=>'#ffffff','label'=>'Text'],
                        'menu' => ['type'=>'array','default'=>[
                            ['label'=>'Overview','url'=>'/'],
                            ['label'=>'Features','url'=>'/features'],
                            ['label'=>'Templates','url'=>'/templates'],
                            ['label'=>'Pricing','url'=>'/pricing'],
                        ],'label'=>'Menu'],
                        'cta' => ['type'=>'object','default'=>['text'=>'Start Free','url'=>'/signup','style'=>'btn-dark','show'=>true],'label'=>'CTA'],
                    ],
                    'content' => [
                        'html' => '
<nav class="nav-pills" style="background: linear-gradient(90deg, {{ $config[\'gradient_start\']??\'#14b8a6\' }}, {{ $config[\'gradient_end\']??\'#3b82f6\' }});">
 <div class="container d-flex align-items-center py-3">
   <a class="d-flex align-items-center text-decoration-none me-4" href="/">
     @if(!empty($config[\'logo_url\']))<img src="{{ $config[\'logo_url\'] }}" height="32" class="me-2">@endif
     <span class="fw-bold" style="color: {{ $config[\'text_color\']??\'#fff\' }};">{{ $config[\'brand_text\']??\'Suite\' }}</span>
   </a>
   <ul class="nav">
     @foreach(($config[\'menu\']??[]) as $m)
       <li class="nav-item"><a class="nav-link text-white px-3 py-1 rounded-pill pill-hover" href="{{ $m[\'url\'] }}">{{ $m[\'label\'] }}</a></li>
     @endforeach
   </ul>
   @if(($config[\'cta\'][\'show\']??true) && !empty($config[\'cta\'][\'text\']??null))
     <a href="{{ $config[\'cta\'][\'url\']??\'#\' }}" class="btn btn-light btn-sm ms-auto rounded-pill px-3">{{ $config[\'cta\'][\'text\'] }}</a>
   @endif
 </div>
</nav>',
                        'css' => '.nav-pills .pill-hover{transition:transform .15s,background .15s} .nav-pills .pill-hover:hover{transform:translateY(-2px);background:rgba(255,255,255,.18)}',
                        'js' => '',
                    ],
                ];
            case 'modern':
            default:
                return [
                    'defaults' => [
                        'gradient_start' => '#667eea',
                        'gradient_end' => '#764ba2',
                        'text_color' => '#ffffff',
                        'sticky' => true,
                        'show_search' => true,
                    ],
                    'fields' => [
                        'brand_text' => ['type'=>'text','default'=>'Your Brand','label'=>'Brand'],
                        'logo_url' => ['type'=>'url','default'=>'','label'=>'Logo'],
                        'gradient_start' => ['type'=>'color','default'=>'#667eea','label'=>'Gradient Start'],
                        'gradient_end' => ['type'=>'color','default'=>'#764ba2','label'=>'Gradient End'],
                        'text_color' => ['type'=>'color','default'=>'#ffffff','label'=>'Text'],
                        'sticky' => ['type'=>'boolean','default'=>true,'label'=>'Sticky'],
                        'show_search' => ['type'=>'boolean','default'=>true,'label'=>'Search'],
                        'menu' => ['type'=>'array','default'=>[
                            ['label'=>'Home','url'=>'/'],
                            ['label'=>'About','url'=>'/about'],
                            ['label'=>'Services','url'=>'/services'],
                            ['label'=>'Portfolio','url'=>'/portfolio'],
                            ['label'=>'Blog','url'=>'/blog'],
                            ['label'=>'Contact','url'=>'/contact'],
                        ],'label'=>'Menu'],
                    ],
                    'content' => [
                        'html' => '
<nav class="navbar navbar-expand-lg navbar-modern {{ ($config[\'sticky\']??true)?\'sticky-top\':\'\' }}"
     style="background: linear-gradient(135deg, {{ $config[\'gradient_start\']??\'#667eea\' }}, {{ $config[\'gradient_end\']??\'#764ba2\' }});">
 <div class="container">
   <a class="navbar-brand d-flex align-items-center" href="/">
     @if(!empty($config[\'logo_url\']))<img src="{{ $config[\'logo_url\'] }}" height="40" class="me-2">@endif
     <span class="fw-bold" style="color: {{ $config[\'text_color\']??\'#ffffff\' }};">{{ $config[\'brand_text\']??\'Your Brand\' }}</span>
   </a>
   <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navModern">
     <span class="navbar-toggler-icon"></span>
   </button>
   <div class="collapse navbar-collapse" id="navModern">
     <ul class="navbar-nav me-auto">
       @foreach(($config[\'menu\']??[]) as $item)
         <li class="nav-item"><a class="nav-link fw-medium" href="{{ $item[\'url\'] }}" style="color: {{ $config[\'text_color\']??\'#ffffff\' }};">{{ $item[\'label\'] }}</a></li>
       @endforeach
     </ul>
     @if($config[\'show_search\']??true)
       <form class="d-flex me-2"><input class="form-control form-control-sm" placeholder="Search..." style="border-radius:20px"></form>
     @endif
     <a href="/contact" class="btn btn-light btn-sm rounded-pill px-3">Get Started</a>
   </div>
 </div>
</nav>',
                        'css' => '.navbar-modern{backdrop-filter:blur(10px);border-bottom:1px solid rgba(255,255,255,.1)} .navbar-modern .nav-link:hover{transform:translateY(-1px)}',
                        'js' => '',
                    ],
                ];
        }
    }

    /* =========================
     |  FOOTERS (5 THEMES)
     |========================= */

    private function seedFooters(): void
    {
        $footers = [
            ['tpl_id'=>'footer-corporate-columns','name'=>'Footer • Corporate Columns','variant'=>'corporate'],
            ['tpl_id'=>'footer-newsletter-bar','name'=>'Footer • Newsletter Bar','variant'=>'newsletter'],
            ['tpl_id'=>'footer-minimal-center','name'=>'Footer • Minimal Centered','variant'=>'minimal'],
            ['tpl_id'=>'footer-dark-social','name'=>'Footer • Dark with Social','variant'=>'social'],
            ['tpl_id'=>'footer-mega-grid','name'=>'Footer • Mega Link Grid','variant'=>'mega'],
        ];

        foreach ($footers as $i => $f) {
            $content = $this->getFooterVariant($f['variant']);
            TplLayout::updateOrCreate(
                ['tpl_id' => $f['tpl_id']],
                [
                    'tpl_id' => $f['tpl_id'],
                    'layout_type' => 'footer',
                    'name' => $f['name'],
                    'description' => 'Professional footer theme: ' . $f['variant'],
                    'preview_image' => "/img/templates/{$f['tpl_id']}-preview.jpg",
                    'path' => "frontend.templates.footers.{$f['tpl_id']}",
                    'content' => $content['content'],
                    'configurable_fields' => $content['fields'],
                    'default_config' => $content['defaults'],
                    'status' => true,
                    'sort_order' => $i + 1
                ]
            );
        }
    }

    private function getFooterVariant(string $variant): array
    {
        switch ($variant) {
            case 'newsletter':
                return [
                    'defaults' => [
                        'bg' => '#0f172a','text' => '#e5e7eb','accent' => '#22c55e',
                        'company' => 'Your Company'
                    ],
                    'fields' => [
                        'bg'=>['type'=>'color','default'=>'#0f172a','label'=>'Background'],
                        'text'=>['type'=>'color','default'=>'#e5e7eb','label'=>'Text'],
                        'accent'=>['type'=>'color','default'=>'#22c55e','label'=>'Accent'],
                        'company'=>['type'=>'text','default'=>'Your Company','label'=>'Company'],
                    ],
                    'content' => [
                        'html' => '
<footer style="background: {{ $config[\'bg\']??\'#0f172a\' }}; color: {{ $config[\'text\']??\'#e5e7eb\' }};">
 <div class="border-bottom" style="border-color: rgba(255,255,255,.08)!important;">
   <div class="container py-4 d-flex gap-3 align-items-center">
     <strong class="me-auto">{{ $config[\'company\']??\'Your Company\' }}</strong>
     <form class="d-flex" style="max-width:380px">
       <input type="email" class="form-control me-2" placeholder="Your email">
       <button class="btn" style="background: {{ $config[\'accent\']??\'#22c55e\' }}; color:#0b0f19">Subscribe</button>
     </form>
   </div>
 </div>
 <div class="container py-4 d-flex justify-content-between small">
   <div>© {{ date(\'Y\') }} {{ $config[\'company\']??\'Your Company\' }} • All rights reserved.</div>
   <div class="d-flex gap-3"><a href="/privacy" class="link-light text-decoration-none">Privacy</a><a href="/terms" class="link-light text-decoration-none">Terms</a></div>
 </div>
</footer>',
                        'css' => '',
                        'js' => '',
                    ],
                ];
            case 'minimal':
                return [
                    'defaults' => ['bg'=>'#ffffff','text'=>'#111827'],
                    'fields' => [
                        'bg'=>['type'=>'color','default'=>'#ffffff','label'=>'Background'],
                        'text'=>['type'=>'color','default'=>'#111827','label'=>'Text'],
                        'links'=>['type'=>'array','default'=>[
                            ['label'=>'About','url'=>'/about'],
                            ['label'=>'Careers','url'=>'/careers'],
                            ['label'=>'Contact','url'=>'/contact'],
                        ],'label'=>'Links'],
                    ],
                    'content' => [
                        'html' => '
<footer style="background: {{ $config[\'bg\']??\'#fff\' }}; color: {{ $config[\'text\']??\'#111827\' }};">
 <div class="container py-5 text-center">
   <a href="/" class="h5 text-decoration-none d-block mb-3" style="color: inherit;">{{ $config[\'company\']??\'Brand\' }}</a>
   <ul class="nav justify-content-center mb-3">
     @foreach(($config[\'links\']??[]) as $l)
       <li class="nav-item"><a class="nav-link px-3" style="color: inherit;" href="{{ $l[\'url\'] }}">{{ $l[\'label\'] }}</a></li>
     @endforeach
   </ul>
   <small>© {{ date(\'Y\') }} {{ $config[\'company\']??\'Brand\' }}</small>
 </div>
</footer>',
                        'css' => '.nav-link:hover{opacity:.7}',
                        'js' => '',
                    ],
                ];
            case 'social':
                return [
                    'defaults' => ['bg'=>'#111827','text'=>'#e5e7eb','social_bg'=>'#0b1220'],
                    'fields' => [
                        'bg'=>['type'=>'color','default'=>'#111827','label'=>'Background'],
                        'text'=>['type'=>'color','default'=>'#e5e7eb','label'=>'Text'],
                        'social_bg'=>['type'=>'color','default'=>'#0b1220','label'=>'Social Bar BG'],
                        'social'=>['type'=>'array','default'=>[
                            ['icon'=>'fab fa-facebook-f','url'=>'#'],
                            ['icon'=>'fab fa-twitter','url'=>'#'],
                            ['icon'=>'fab fa-linkedin-in','url'=>'#'],
                            ['icon'=>'fab fa-instagram','url'=>'#'],
                        ], 'label'=>'Social'],
                    ],
                    'content' => [
                        'html' => '
<footer style="background: {{ $config[\'bg\']??\'#111827\' }}; color: {{ $config[\'text\']??\'#e5e7eb\' }};">
 <div style="background: {{ $config[\'social_bg\']??\'#0b1220\' }};">
   <div class="container py-3 d-flex gap-2">
     @foreach(($config[\'social\']??[]) as $s)
       <a class="d-inline-flex align-items-center justify-content-center rounded-circle"
          style="width:42px;height:42px;background:rgba(255,255,255,.08);color:#fff" href="{{ $s[\'url\'] }}"><i class="{{ $s[\'icon\'] }}"></i></a>
     @endforeach
   </div>
 </div>
 <div class="container py-5 d-grid" style="row-gap:1.25rem;">
   <div class="row">
     <div class="col-md-4"><h5>About</h5><p class="opacity-75">We craft digital products with love.</p></div>
     <div class="col-md-2"><h6>Company</h6><ul class="list-unstyled"><li><a href="/about" class="text-decoration-none link-light">About</a></li><li><a href="/careers" class="text-decoration-none link-light">Careers</a></li></ul></div>
     <div class="col-md-2"><h6>Resources</h6><ul class="list-unstyled"><li><a href="/blog" class="text-decoration-none link-light">Blog</a></li><li><a href="/help" class="text-decoration-none link-light">Help Center</a></li></ul></div>
     <div class="col-md-4"><h6>Newsletter</h6><form class="d-flex"><input class="form-control me-2" placeholder="Email"><button class="btn btn-primary">Join</button></form></div>
   </div>
   <div class="d-flex justify-content-between small pt-3 border-top border-secondary"> <span>© {{ date(\'Y\') }} All rights reserved</span> <span><a class="link-light text-decoration-none me-3" href="/privacy">Privacy</a><a class="link-light text-decoration-none" href="/terms">Terms</a></span></div>
 </div>
</footer>',
                        'css' => '',
                        'js' => '',
                    ],
                ];
            case 'mega':
                return [
                    'defaults' => ['bg'=>'#0b0f19','text'=>'#cbd5e1','grid_cols'=>4],
                    'fields' => [
                        'bg'=>['type'=>'color','default'=>'#0b0f19','label'=>'Background'],
                        'text'=>['type'=>'color','default'=>'#cbd5e1','label'=>'Text'],
                        'grid_cols'=>['type'=>'select','options'=>[3,4,5],'default'=>4,'label'=>'Grid Columns'],
                    ],
                    'content' => [
                        'html' => '
<footer style="background: {{ $config[\'bg\']??\'#0b0f19\' }}; color: {{ $config[\'text\']??\'#cbd5e1\' }};">
 <div class="container py-5">
   <div class="row row-cols-{{ $config[\'grid_cols\']??4 }} g-4">
     @for($i=0;$i<($config[\'grid_cols\']??4)*2;$i++)
       <div class="col"><a href="#" class="text-decoration-none link-light d-block py-1">Footer Link {{ $i+1 }}</a></div>
     @endfor
   </div>
   <div class="small opacity-75 pt-4">© {{ date(\'Y\') }} Grid Footer</div>
 </div>
</footer>',
                        'css' => '',
                        'js' => '',
                    ],
                ];
            case 'corporate':
            default:
                return [
                    'defaults' => ['bg'=>'#1f2937','text'=>'#ffffff', 'company'=>'Your Company'],
                    'fields' => [
                        'bg'=>['type'=>'color','default'=>'#1f2937','label'=>'Background'],
                        'text'=>['type'=>'color','default'=>'#ffffff','label'=>'Text'],
                        'company'=>['type'=>'text','default'=>'Your Company','label'=>'Company Name'],
                    ],
                    'content' => [
                        'html' => '
<footer style="background: {{ $config[\'bg\']??\'#1f2937\' }}; color: {{ $config[\'text\']??\'#ffffff\' }};">
 <div class="container py-5">
   <div class="row g-4">
     <div class="col-lg-4"><h5 class="fw-bold mb-3">{{ $config[\'company\']??\'Your Company\' }}</h5><p class="opacity-75">We build future-ready experiences.</p></div>
     <div class="col-lg-2"><h6>Company</h6><ul class="list-unstyled"><li><a class="link-light text-decoration-none" href="/about">About</a></li><li><a class="link-light text-decoration-none" href="/contact">Contact</a></li></ul></div>
     <div class="col-lg-2"><h6>Services</h6><ul class="list-unstyled"><li><a class="link-light text-decoration-none" href="/services/web">Web</a></li><li><a class="link-light text-decoration-none" href="/services/seo">SEO</a></li></ul></div>
     <div class="col-lg-4"><h6>Newsletter</h6><form class="d-flex"><input class="form-control me-2" placeholder="Email"><button class="btn btn-primary">Subscribe</button></form></div>
   </div>
   <hr class="my-4" style="border-color: rgba(255,255,255,.15)">
   <div class="d-flex justify-content-between small"><span>© {{ date(\'Y\') }} {{ $config[\'company\']??\'Your Company\' }}</span><span><a class="link-light text-decoration-none me-3" href="/privacy">Privacy</a><a class="link-light text-decoration-none" href="/terms">Terms</a></span></div>
 </div>
</footer>',
                        'css' => '',
                        'js' => '',
                    ],
                ];
        }
    }

    /* =========================
     |  SECTIONS (20 THEMES)
     |========================= */

    private function seedSections(): void
    {
        $sections = [
            ['tpl_id'=>'section-hero-video','name'=>'Hero • Video Background','builder'=>'heroVideo'],
            ['tpl_id'=>'section-hero-split','name'=>'Hero • Split Image Layout','builder'=>'heroSplit'],
            ['tpl_id'=>'section-features-grid','name'=>'Features • Modern Grid','builder'=>'featuresGrid'],
            ['tpl_id'=>'section-features-zigzag','name'=>'Features • Zigzag Layout','builder'=>'featuresZigzag'],
            ['tpl_id'=>'section-testimonials-carousel','name'=>'Testimonials • Carousel Slider','builder'=>'testimonialsCarousel'],
            ['tpl_id'=>'section-testimonials-grid','name'=>'Testimonials • Card Grid','builder'=>'testimonialsGrid'],
            ['tpl_id'=>'section-pricing-cards','name'=>'Pricing • Plan Cards','builder'=>'pricingCards'],
            ['tpl_id'=>'section-pricing-table','name'=>'Pricing • Comparison Table','builder'=>'pricingTable'],
            ['tpl_id'=>'section-cta-gradient','name'=>'CTA • Gradient Background','builder'=>'ctaGradient'],
            ['tpl_id'=>'section-cta-solid','name'=>'CTA • Solid Color','builder'=>'ctaSolid'],
            ['tpl_id'=>'section-faq-accordion','name'=>'FAQ • Accordion Style','builder'=>'faq'],
            ['tpl_id'=>'section-process-steps','name'=>'Process • Step by Step','builder'=>'steps'],
            ['tpl_id'=>'section-stats-counters','name'=>'Statistics • Counter Numbers','builder'=>'stats'],
            ['tpl_id'=>'section-gallery-masonry','name'=>'Gallery • Masonry Layout','builder'=>'gallery'],
            ['tpl_id'=>'section-team-cards','name'=>'Team • Member Cards','builder'=>'team'],
            ['tpl_id'=>'section-blog-posts','name'=>'Blog • Article List','builder'=>'blog'],
            ['tpl_id'=>'section-contact-form','name'=>'Contact • Form with Info','builder'=>'contact'],
            ['tpl_id'=>'section-video-text','name'=>'Video • With Text Content','builder'=>'videoText'],
            ['tpl_id'=>'section-timeline-events','name'=>'Timeline • Event History','builder'=>'timeline'],
            ['tpl_id'=>'section-partners-logos','name'=>'Partners • Logo Strip','builder'=>'partners'],
        ];

        foreach ($sections as $i => $s) {
            $content = $this->buildSection($s['builder']);
            TplLayout::updateOrCreate(
                ['tpl_id' => $s['tpl_id']],
                [
                    'tpl_id' => $s['tpl_id'],
                    'layout_type' => 'section',
                    'name' => $s['name'],
                    'description' => $s['name'] . ' themed section',
                    'preview_image' => "/img/templates/{$s['tpl_id']}-preview.jpg",
                    'path' => "frontend.templates.sections.{$s['tpl_id']}",
                    'content' => $content['content'],
                    'configurable_fields' => $content['fields'],
                    'default_config' => $content['defaults'],
                    'status' => true,
                    'sort_order' => $i + 1
                ]
            );
        }
    }

    private function buildSection(string $builder): array
    {
        return match ($builder) {
            'heroVideo'        => $this->secHeroVideo(),
            'heroSplit'        => $this->secHeroSplit(),
            'featuresGrid'     => $this->secFeaturesGrid(),
            'featuresZigzag'   => $this->secFeaturesZigzag(),
            'testimonialsCarousel' => $this->secTestimonialsCarousel(),
            'testimonialsGrid' => $this->secTestimonialsGrid(),
            'pricingCards'     => $this->secPricingCards(),
            'pricingTable'     => $this->secPricingTable(),
            'ctaGradient'      => $this->secCtaGradient(),
            'ctaSolid'         => $this->secCtaSolid(),
            'faq'              => $this->secFaq(),
            'steps'            => $this->secSteps(),
            'stats'            => $this->secStats(),
            'gallery'          => $this->secGallery(),
            'team'             => $this->secTeam(),
            'blog'             => $this->secBlog(),
            'contact'          => $this->secContact(),
            'videoText'        => $this->secVideoText(),
            'timeline'         => $this->secTimeline(),
            'partners'         => $this->secPartners(),
            default            => $this->secCtaGradient(),
        };
    }

    /* ========= HERO: VIDEO ========= */
    private function secHeroVideo(): array
    {
        return [
            'defaults' => [
                'video_url' => '',
                'overlay_opacity' => 0.5,
                'hero_title' => 'Welcome to Our Amazing Service',
                'hero_subtitle' => 'We provide innovative solutions to help your business grow.',
                'text_alignment' => 'center',
            ],
            'fields' => [
                'video_url'=>['type'=>'url','default'=>'','label'=>'Video URL (mp4)'],
                'overlay_opacity'=>['type'=>'range','min'=>0,'max'=>1,'step'=>0.1,'default'=>0.5,'label'=>'Overlay'],
                'hero_title'=>['type'=>'text','default'=>'Welcome...','label'=>'Title'],
                'hero_subtitle'=>['type'=>'textarea','default'=>'We provide...','label'=>'Subtitle'],
                'text_alignment'=>['type'=>'select','options'=>['left','center','right'],'default'=>'center','label'=>'Align'],
            ],
            'content' => [
                'html' => '<section class="hero-video position-relative" style="min-height: 92vh;">
 @if(!empty($config[\'video_url\']))<video autoplay muted loop class="position-absolute w-100 h-100" style="object-fit:cover"><source src="{{ $config[\'video_url\'] }}" type="video/mp4"></video>@endif
 <div class="position-absolute w-100 h-100" style="background: rgba(0,0,0,{{ $config[\'overlay_opacity\']??.5 }});"></div>
 <div class="container position-relative d-flex align-items-center" style="min-height:92vh">
   <div class="col-lg-8 mx-auto text-{{ $config[\'text_alignment\']??\'center\' }} text-white">
     <h1 class="display-2 fw-bold mb-3">{{ $config[\'hero_title\']??\'Welcome\' }}</h1>
     <p class="lead mb-4 opacity-90">{{ $config[\'hero_subtitle\']??\'Subtitle\' }}</p>
     <a href="#features" class="btn btn-light btn-lg rounded-pill px-4">Explore</a>
   </div>
 </div>
</section>',
                'css' => '.hero-video{background:linear-gradient(135deg,#667eea,#764ba2)}',
                'js' => '',
            ],
        ];
    }

    /* ========= HERO: SPLIT IMAGE ========= */
    private function secHeroSplit(): array
    {
        return [
            'defaults' => [
                'image_url' => 'https://via.placeholder.com/720x720',
                'title' => 'Design that works',
                'subtitle' => 'Crafting delightful experiences for web & mobile.',
                'bg' => '#ffffff',
            ],
            'fields' => [
                'image_url'=>['type'=>'url','default'=>'','label'=>'Right Image'],
                'title'=>['type'=>'text','default'=>'Design that works','label'=>'Title'],
                'subtitle'=>['type'=>'text','default'=>'Crafting...','label'=>'Subtitle'],
                'bg'=>['type'=>'color','default'=>'#ffffff','label'=>'Background'],
            ],
            'content' => [
                'html' => '<section class="hero-split" style="background: {{ $config[\'bg\']??\'#fff\' }};">
 <div class="container py-5">
   <div class="row align-items-center g-5">
     <div class="col-lg-6">
       <h1 class="display-4 fw-bold mb-3">{{ $config[\'title\']??\'Design that works\' }}</h1>
       <p class="lead text-muted mb-4">{{ $config[\'subtitle\']??\'Crafting...\' }}</p>
       <div class="d-flex gap-3"><a class="btn btn-primary btn-lg" href="#">Get Started</a><a class="btn btn-outline-secondary btn-lg" href="#">See Work</a></div>
     </div>
     <div class="col-lg-6">
       <div class="ratio ratio-1x1 rounded-4 shadow-sm" style="background:url({{ $config[\'image_url\']??\'https://via.placeholder.com/720x720\' }}) center/cover"></div>
     </div>
   </div>
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= FEATURES: GRID ========= */
    private function secFeaturesGrid(): array
    {
        return [
            'defaults' => [
                'section_title'=>'Our Amazing Features','section_subtitle'=>'Discover what makes us different',
                'columns'=>3,'background_color'=>'#f8f9fa','icon_background'=>'linear-gradient(135deg,#667eea,#764ba2)',
                'features'=>[
                    ['icon'=>'fas fa-bolt','title'=>'Fast','description'=>'Lightning-fast performance'],
                    ['icon'=>'fas fa-lock','title'=>'Secure','description'=>'Top-notch security'],
                    ['icon'=>'fas fa-thumbs-up','title'=>'Reliable','description'=>'99.9% uptime'],
                ]
            ],
            'fields' => [
                'section_title'=>['type'=>'text','default'=>'Our Amazing Features','label'=>'Title'],
                'section_subtitle'=>['type'=>'text','default'=>'Discover...','label'=>'Subtitle'],
                'columns'=>['type'=>'select','options'=>[2,3,4],'default'=>3,'label'=>'Columns'],
                'background_color'=>['type'=>'color','default'=>'#f8f9fa','label'=>'BG'],
                'icon_background'=>['type'=>'text','default'=>'linear-gradient(135deg,#667eea,#764ba2)','label'=>'Icon BG'],
                'features'=>['type'=>'array','default'=>[],'label'=>'Features'],
            ],
            'content' => [
                'html' => '<section class="features-grid py-5" style="background: {{ $config[\'background_color\']??\'#f8f9fa\' }};">
 <div class="container">
   <div class="text-center mb-5">
     <h2 class="display-6 fw-bold">{{ $config[\'section_title\']??\'Features\' }}</h2>
     <p class="text-muted">{{ $config[\'section_subtitle\']??\'Subtitle\' }}</p>
   </div>
   <div class="row g-4">
     @foreach(($config[\'features\']??[]) as $f)
       <div class="col-lg-{{ 12/($config[\'columns\']??3) }} col-md-6">
         <div class="p-4 bg-white rounded-4 shadow-sm h-100 text-center hover-lift">
           <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:84px;height:84px;background: {{ $config[\'icon_background\']??\'linear-gradient(135deg,#667eea,#764ba2)\' }};">
             <i class="{{ $f[\'icon\']??\'fas fa-circle\' }} text-white fs-2"></i>
           </div>
           <h5 class="fw-bold mb-2">{{ $f[\'title\']??\'Title\' }}</h5>
           <p class="text-muted mb-0">{{ $f[\'description\']??\'Description\' }}</p>
         </div>
       </div>
     @endforeach
   </div>
 </div>
</section>',
                'css' => '.hover-lift{transition:transform .2s,box-shadow .2s}.hover-lift:hover{transform:translateY(-6px);box-shadow:0 18px 40px rgba(0,0,0,.1)!important}',
                'js' => '',
            ],
        ];
    }

    /* ========= FEATURES: ZIGZAG ========= */
    private function secFeaturesZigzag(): array
    {
        return [
            'defaults' => [
                'title'=>'Why choose us','items'=>[
                    ['title'=>'UX-first','text'=>'We design from user needs','image'=>'https://via.placeholder.com/600x360'],
                    ['title'=>'Scalable','text'=>'Built to grow with you','image'=>'https://via.placeholder.com/600x360?text=2'],
                    ['title'=>'Support','text'=>'We stay with you','image'=>'https://via.placeholder.com/600x360?text=3'],
                ]
            ],
            'fields' => [
                'title'=>['type'=>'text','default'=>'Why choose us','label'=>'Title'],
                'items'=>['type'=>'array','default'=>[],'label'=>'ZigZag Items'],
            ],
            'content' => [
                'html' => '<section class="zigzag py-5">
 <div class="container">
   <h2 class="fw-bold text-center mb-5">{{ $config[\'title\']??\'Why choose us\' }}</h2>
   @foreach(($config[\'items\']??[]) as $i => $it)
     <div class="row align-items-center g-4 mb-4">
       <div class="col-lg-6 {{ $i % 2 ? \'order-lg-2\':\'\' }}">
         <div class="ratio ratio-16x9 rounded-4 shadow-sm" style="background:url({{ $it[\'image\']??\'https://via.placeholder.com/800x450\' }}) center/cover"></div>
       </div>
       <div class="col-lg-6">
         <h4 class="fw-bold">{{ $it[\'title\']??\'Title\' }}</h4>
         <p class="text-muted">{{ $it[\'text\']??\'Text\' }}</p>
       </div>
     </div>
   @endforeach
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= TESTIMONIALS: CAROUSEL ========= */
    private function secTestimonialsCarousel(): array
    {
        return [
            'defaults' => [
                'section_title'=>'What clients say',
                'testimonials'=>[
                    ['quote'=>'Amazing service!','name'=>'Jane','company'=>'Acme','rating'=>5],
                    ['quote'=>'Loved the results.','name'=>'John','company'=>'Beta','rating'=>5],
                ]
            ],
            'fields' => [
                'section_title'=>['type'=>'text','default'=>'What clients say','label'=>'Title'],
                'testimonials'=>['type'=>'array','default'=>[],'label'=>'Testimonials'],
            ],
            'content' => [
                'html' => '<section class="testi-carousel py-5" style="background:linear-gradient(135deg,#667eea,#764ba2)">
 <div class="container">
   <h2 class="text-white text-center mb-4">{{ $config[\'section_title\']??\'Testimonials\' }}</h2>
   <div id="tCarousel" class="carousel slide" data-bs-ride="carousel">
     <div class="carousel-inner">
       @foreach(($config[\'testimonials\']??[]) as $idx => $t)
         <div class="carousel-item {{ $idx===0?\'active\':\'\' }}">
           <div class="col-lg-8 mx-auto bg-white rounded-4 p-5 text-center shadow">
             <blockquote class="mb-3 fs-5">"{{ $t[\'quote\']??\'...\' }}"</blockquote>
             <div class="small text-muted">{{ $t[\'name\']??\'Name\' }} — {{ $t[\'company\']??\'Company\' }}</div>
           </div>
         </div>
       @endforeach
     </div>
     <button class="carousel-control-prev" type="button" data-bs-target="#tCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
     <button class="carousel-control-next" type="button" data-bs-target="#tCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
   </div>
 </div>
</section>',
                'css' => '',
                'js' => 'document.addEventListener("DOMContentLoaded",()=>{new bootstrap.Carousel(document.getElementById("tCarousel"),{interval:5000,wrap:true})});',
            ],
        ];
    }

    /* ========= TESTIMONIALS: GRID ========= */
    private function secTestimonialsGrid(): array
    {
        return [
            'defaults' => [
                'title'=>'Happy customers',
                'cards'=>[
                    ['name'=>'Ali','text'=>'Top notch!'],
                    ['name'=>'Sara','text'=>'Great support'],
                    ['name'=>'Omar','text'=>'Highly recommend'],
                    ['name'=>'Lina','text'=>'Amazing results'],
                ]
            ],
            'fields' => [
                'title'=>['type'=>'text','default'=>'Happy customers','label'=>'Title'],
                'cards'=>['type'=>'array','default'=>[],'label'=>'Cards'],
            ],
            'content' => [
                'html' => '<section class="testi-grid py-5 bg-light">
 <div class="container">
   <h2 class="fw-bold text-center mb-4">{{ $config[\'title\']??\'Happy customers\' }}</h2>
   <div class="row g-4">
     @foreach(($config[\'cards\']??[]) as $c)
       <div class="col-md-6 col-lg-3">
         <div class="bg-white rounded-4 p-4 h-100 shadow-sm">
           <p class="mb-3">"{{ $c[\'text\']??\'...\' }}"</p>
           <strong class="small text-muted">— {{ $c[\'name\']??\'Name\' }}</strong>
         </div>
       </div>
     @endforeach
   </div>
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= PRICING: CARDS ========= */
    private function secPricingCards(): array
    {
        return [
            'defaults' => [
                'title'=>'Choose your plan','subtitle'=>'Flexible options',
                'plans'=>[
                    ['name'=>'Starter','price'=>9,'features'=>['1 project','Email support'],'featured'=>false],
                    ['name'=>'Pro','price'=>29,'features'=>['5 projects','Priority support'],'featured'=>true],
                    ['name'=>'Business','price'=>79,'features'=>['Unlimited','Dedicated manager'],'featured'=>false],
                ]
            ],
            'fields' => [
                'title'=>['type'=>'text','default'=>'Choose your plan','label'=>'Title'],
                'subtitle'=>['type'=>'text','default'=>'Flexible options','label'=>'Subtitle'],
                'plans'=>['type'=>'array','default'=>[],'label'=>'Plans'],
            ],
            'content' => [
                'html' => '<section class="pricing-cards py-5">
 <div class="container">
   <div class="text-center mb-5"><h2 class="fw-bold">{{ $config[\'title\']??\'Pricing\' }}</h2><p class="text-muted">{{ $config[\'subtitle\']??\'Subtitle\' }}</p></div>
   <div class="row g-4 justify-content-center">
     @foreach(($config[\'plans\']??[]) as $p)
       <div class="col-md-6 col-lg-4">
         <div class="card h-100 border-0 shadow-sm rounded-4 {{ ($p[\'featured\']??false)?\'border border-primary\':\'\' }}">
           <div class="card-body text-center p-5">
             <h5 class="fw-bold">{{ $p[\'name\']??\'Plan\' }}</h5>
             <div class="display-5 fw-bold text-primary my-3">${{ $p[\'price\']??0 }}</div>
             <ul class="list-unstyled mb-4">@foreach(($p[\'features\']??[]) as $f)<li class="mb-1"><i class="fas fa-check text-success me-2"></i>{{ $f }}</li>@endforeach</ul>
             <a href="#" class="btn {{ ($p[\'featured\']??false)?\'btn-primary\':\'btn-outline-primary\' }} rounded-pill px-4">Choose</a>
           </div>
         </div>
       </div>
     @endforeach
   </div>
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= PRICING: COMPARISON TABLE ========= */
    private function secPricingTable(): array
    {
        return [
            'defaults' => [
                'title'=>'Compare plans',
                'features'=>['Projects','Support','Storage'],
                'rows'=>[
                    ['plan'=>'Starter','values'=>['1','Email','5GB']],
                    ['plan'=>'Pro','values'=>['5','Priority','25GB']],
                    ['plan'=>'Business','values'=>['Unlimited','Dedicated','1TB']],
                ]
            ],
            'fields' => [
                'title'=>['type'=>'text','default'=>'Compare plans','label'=>'Title'],
                'features'=>['type'=>'array','default'=>[],'label'=>'Features'],
                'rows'=>['type'=>'array','default'=>[],'label'=>'Rows'],
            ],
            'content' => [
                'html' => '<section class="pricing-compare py-5 bg-light">
 <div class="container">
   <h2 class="fw-bold text-center mb-4">{{ $config[\'title\']??\'Compare\' }}</h2>
   <div class="table-responsive">
     <table class="table align-middle bg-white rounded-4 overflow-hidden">
       <thead class="table-light">
         <tr><th>Plan</th>@foreach(($config[\'features\']??[]) as $f)<th>{{ $f }}</th>@endforeach</tr>
       </thead>
       <tbody>
         @foreach(($config[\'rows\']??[]) as $r)
           <tr><td class="fw-semibold">{{ $r[\'plan\']??\'Plan\' }}</td>@foreach(($r[\'values\']??[]) as $v)<td>{{ $v }}</td>@endforeach</tr>
         @endforeach
       </tbody>
     </table>
   </div>
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= CTA: GRADIENT ========= */
    private function secCtaGradient(): array
    {
        return [
            'defaults' => [
                'cta_title'=>'Ready to get started?','cta_subtitle'=>'Join thousands today.',
                'background_gradient'=>'linear-gradient(135deg,#ff6b6b 0%,#ee5a52 50%,#ff4757 100%)',
                'primary_button'=>['text'=>'Get Started','url'=>'#'],
                'secondary_button'=>['text'=>'Learn More','url'=>'#'],
            ],
            'fields' => [
                'cta_title'=>['type'=>'text','default'=>'Ready to get started?','label'=>'Title'],
                'cta_subtitle'=>['type'=>'text','default'=>'Join thousands today.','label'=>'Subtitle'],
                'background_gradient'=>['type'=>'text','default'=>'linear-gradient(135deg,#ff6b6b 0%,#ee5a52 50%,#ff4757 100%)','label'=>'Gradient'],
                'primary_button'=>['type'=>'object','default'=>['text'=>'Get Started','url'=>'#'],'label'=>'Primary'],
                'secondary_button'=>['type'=>'object','default'=>['text'=>'Learn More','url'=>'#'],'label'=>'Secondary'],
            ],
            'content' => [
                'html' => '<section class="cta-gradient py-5" style="background: {{ $config[\'background_gradient\']??\'linear-gradient(135deg,#ff6b6b,#ff4757)\' }};">
 <div class="container">
  <div class="row align-items-center">
   <div class="col-lg-8"><h2 class="display-6 text-white fw-bold mb-2">{{ $config[\'cta_title\']??\'Ready?\' }}</h2><p class="lead text-white-50 mb-0">{{ $config[\'cta_subtitle\']??\'Join us\' }}</p></div>
   <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
     <a href="{{ $config[\'primary_button\'][\'url\']??\'#\' }}" class="btn btn-light btn-lg me-2 rounded-pill px-4">{{ $config[\'primary_button\'][\'text\']??\'Start\' }}</a>
     <a href="{{ $config[\'secondary_button\'][\'url\']??\'#\' }}" class="btn btn-outline-light btn-lg rounded-pill px-4">{{ $config[\'secondary_button\'][\'text\']??\'Learn\' }}</a>
   </div>
  </div>
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= CTA: SOLID LEFT ========= */
    private function secCtaSolid(): array
    {
        return [
            'defaults' => [
                'bg'=>'#0ea5e9','title'=>'Boost your product','text'=>'Get a dedicated team today.',
                'button'=>['text'=>'Contact Sales','url'=>'#'],
            ],
            'fields' => [
                'bg'=>['type'=>'color','default'=>'#0ea5e9','label'=>'Background'],
                'title'=>['type'=>'text','default'=>'Boost your product','label'=>'Title'],
                'text'=>['type'=>'text','default'=>'Get a dedicated team today.','label'=>'Text'],
                'button'=>['type'=>'object','default'=>['text'=>'Contact Sales','url'=>'#'],'label'=>'Button'],
            ],
            'content' => [
                'html' => '<section class="cta-solid py-5" style="background: {{ $config[\'bg\']??\'#0ea5e9\' }};">
 <div class="container">
   <div class="row align-items-center text-white">
     <div class="col-lg-8"><h2 class="fw-bold mb-1">{{ $config[\'title\']??\'Title\' }}</h2><p class="lead mb-0">{{ $config[\'text\']??\'Text\' }}</p></div>
     <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
       <a href="{{ $config[\'button\'][\'url\']??\'#\' }}" class="btn btn-light btn-lg rounded-pill px-4">{{ $config[\'button\'][\'text\']??\'Contact\' }}</a>
     </div>
   </div>
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= FAQ ========= */
    private function secFaq(): array
    {
        return [
            'defaults' => [
                'title'=>'Frequently asked questions',
                'items'=>[
                    ['q'=>'How do I start?','a'=>'Create an account and pick a plan.'],
                    ['q'=>'Can I cancel?','a'=>'Yes, anytime from settings.'],
                    ['q'=>'Support hours?','a'=>'24/7 via chat/email.'],
                ]
            ],
            'fields' => [
                'title'=>['type'=>'text','default'=>'Frequently asked questions','label'=>'Title'],
                'items'=>['type'=>'array','default'=>[],'label'=>'Items'],
            ],
            'content' => [
                'html' => '<section class="faq py-5">
 <div class="container">
   <h2 class="fw-bold text-center mb-4">{{ $config[\'title\']??\'FAQ\' }}</h2>
   <div class="accordion" id="faqAcc">
     @foreach(($config[\'items\']??[]) as $i => $it)
       <div class="accordion-item">
         <h2 class="accordion-header" id="h{{ $i }}">
           <button class="accordion-button {{ $i?\'collapsed\':\'\' }}" type="button" data-bs-toggle="collapse" data-bs-target="#c{{ $i }}">{{ $it[\'q\']??\'Question\' }}</button>
         </h2>
         <div id="c{{ $i }}" class="accordion-collapse collapse {{ $i?\'\':\'show\' }}" data-bs-parent="#faqAcc">
           <div class="accordion-body">{{ $it[\'a\']??\'Answer\' }}</div>
         </div>
       </div>
     @endforeach
   </div>
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= STEPS ========= */
    private function secSteps(): array
    {
        return [
            'defaults' => [
                'title'=>'How it works',
                'steps'=>[
                    ['title'=>'Sign up','text'=>'Create your account'],
                    ['title'=>'Setup','text'=>'Connect your data'],
                    ['title'=>'Launch','text'=>'Go live confidently'],
                ]
            ],
            'fields' => [
                'title'=>['type'=>'text','default'=>'How it works','label'=>'Title'],
                'steps'=>['type'=>'array','default'=>[],'label'=>'Steps'],
            ],
            'content' => [
                'html' => '<section class="steps py-5 bg-light">
 <div class="container">
   <h2 class="fw-bold text-center mb-4">{{ $config[\'title\']??\'How it works\' }}</h2>
   <div class="row g-4">
     @foreach(($config[\'steps\']??[]) as $i => $s)
       <div class="col-md-4">
         <div class="bg-white rounded-4 p-4 h-100 shadow-sm">
           <div class="badge bg-primary rounded-pill mb-2">{{ $i+1 }}</div>
           <h5 class="fw-bold">{{ $s[\'title\']??\'Step\' }}</h5>
           <p class="text-muted mb-0">{{ $s[\'text\']??\'Text\' }}</p>
         </div>
       </div>
     @endforeach
   </div>
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= STATS ========= */
    private function secStats(): array
    {
        return [
            'defaults' => [
                'items'=>[
                    ['value'=>'12K+','label'=>'Users'],
                    ['value'=>'98%','label'=>'Satisfaction'],
                    ['value'=>'32','label'=>'Countries'],
                    ['value'=>'4.9','label'=>'Rating'],
                ],
                'bg'=>'#ffffff'
            ],
            'fields' => [
                'bg'=>['type'=>'color','default'=>'#ffffff','label'=>'BG'],
                'items'=>['type'=>'array','default'=>[],'label'=>'Items'],
            ],
            'content' => [
                'html' => '<section class="stats py-5" style="background: {{ $config[\'bg\']??\'#fff\' }};">
 <div class="container">
   <div class="row g-4 text-center">
     @foreach(($config[\'items\']??[]) as $i)
       <div class="col-6 col-md-3">
         <div class="display-5 fw-bold">{{ $i[\'value\']??\'0\' }}</div>
         <div class="text-muted">{{ $i[\'label\']??\'Label\' }}</div>
       </div>
     @endforeach
   </div>
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= GALLERY ========= */
    private function secGallery(): array
    {
        return [
            'defaults' => [
                'images'=>[
                    'https://picsum.photos/600/500?1','https://picsum.photos/600/700?2',
                    'https://picsum.photos/600/600?3','https://picsum.photos/600/800?4',
                    'https://picsum.photos/600/650?5','https://picsum.photos/600/520?6',
                ]
            ],
            'fields' => [
                'images'=>['type'=>'array','default'=>[],'label'=>'Images'],
            ],
            'content' => [
                'html' => '<section class="gallery py-5">
 <div class="container">
   <div class="masonry" style="column-count:3; column-gap:1rem;">
     @foreach(($config[\'images\']??[]) as $img)
       <div class="masonry-item mb-3" style="break-inside:avoid;">
         <img src="{{ $img }}" class="w-100 rounded-4 shadow-sm" alt="Gallery">
       </div>
     @endforeach
   </div>
 </div>
</section>',
                'css' => '@media (max-width:992px){.masonry{column-count:2}} @media (max-width:576px){.masonry{column-count:1}}',
                'js' => '',
            ],
        ];
    }

    /* ========= TEAM ========= */
    private function secTeam(): array
    {
        return [
            'defaults' => [
                'title'=>'Meet the team',
                'members'=>[
                    ['name'=>'Mona','role'=>'CEO','avatar'=>'https://i.pravatar.cc/160?img=1'],
                    ['name'=>'Omar','role'=>'CTO','avatar'=>'https://i.pravatar.cc/160?img=2'],
                    ['name'=>'Sara','role'=>'Design Lead','avatar'=>'https://i.pravatar.cc/160?img=3'],
                    ['name'=>'Ali','role'=>'PM','avatar'=>'https://i.pravatar.cc/160?img=4'],
                ]
            ],
            'fields' => [
                'title'=>['type'=>'text','default'=>'Meet the team','label'=>'Title'],
                'members'=>['type'=>'array','default'=>[],'label'=>'Members'],
            ],
            'content' => [
                'html' => '<section class="team py-5 bg-light">
 <div class="container">
   <h2 class="fw-bold text-center mb-4">{{ $config[\'title\']??\'Team\' }}</h2>
   <div class="row g-4 justify-content-center">
     @foreach(($config[\'members\']??[]) as $m)
       <div class="col-6 col-md-3">
         <div class="bg-white rounded-4 p-4 text-center shadow-sm h-100">
           <img src="{{ $m[\'avatar\']??\'https://i.pravatar.cc/160\' }}" class="rounded-circle mb-3" width="96" height="96" alt="{{ $m[\'name\']??\'Member\' }}">
           <h6 class="fw-bold mb-1">{{ $m[\'name\']??\'Name\' }}</h6>
           <small class="text-muted">{{ $m[\'role\']??\'Role\' }}</small>
         </div>
       </div>
     @endforeach
   </div>
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= BLOG ========= */
    private function secBlog(): array
    {
        return [
            'defaults' => [
                'title'=>'Latest articles',
                'posts'=>[
                    ['title'=>'Design systems 101','excerpt'=>'Build once, scale forever','image'=>'https://picsum.photos/600/360?blog1','url'=>'#'],
                    ['title'=>'Clean architecture','excerpt'=>'Maintainable codebases','image'=>'https://picsum.photos/600/360?blog2','url'=>'#'],
                    ['title'=>'Marketing analytics','excerpt'=>'Measure what matters','image'=>'https://picsum.photos/600/360?blog3','url'=>'#'],
                ]
            ],
            'fields' => [
                'title'=>['type'=>'text','default'=>'Latest articles','label'=>'Title'],
                'posts'=>['type'=>'array','default'=>[],'label'=>'Posts'],
            ],
            'content' => [
                'html' => '<section class="blog-list py-5">
 <div class="container">
   <h2 class="fw-bold text-center mb-4">{{ $config[\'title\']??\'Blog\' }}</h2>
   <div class="row g-4">
     @foreach(($config[\'posts\']??[]) as $p)
       <div class="col-md-6 col-lg-4">
         <a href="{{ $p[\'url\']??\'#\' }}" class="text-decoration-none d-block h-100">
           <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
             <div class="ratio ratio-16x9" style="background:url({{ $p[\'image\']??\'https://picsum.photos/600/360\' }}) center/cover"></div>
             <div class="card-body">
               <h5 class="fw-bold">{{ $p[\'title\']??\'Title\' }}</h5>
               <p class="text-muted mb-0">{{ $p[\'excerpt\']??\'Excerpt\' }}</p>
             </div>
           </div>
         </a>
       </div>
     @endforeach
   </div>
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= CONTACT ========= */
    private function secContact(): array
    {
        return [
            'defaults' => [
                'title'=>'Get in touch',
                'subtitle'=>'We usually reply within 24 hours',
                'bg'=>'#ffffff'
            ],
            'fields' => [
                'title'=>['type'=>'text','default'=>'Get in touch','label'=>'Title'],
                'subtitle'=>['type'=>'text','default'=>'We usually reply within 24 hours','label'=>'Subtitle'],
                'bg'=>['type'=>'color','default'=>'#ffffff','label'=>'Background'],
            ],
            'content' => [
                'html' => '<section class="contact py-5" style="background: {{ $config[\'bg\']??\'#fff\' }};">
 <div class="container">
   <div class="row g-4">
     <div class="col-lg-6">
       <h2 class="fw-bold mb-2">{{ $config[\'title\']??\'Contact\' }}</h2>
       <p class="text-muted">{{ $config[\'subtitle\']??\'Subtitle\' }}</p>
       <ul class="list-unstyled small text-muted">
         <li class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>123 Business Street</li>
         <li class="mb-1"><i class="fas fa-envelope me-2"></i>hello@example.com</li>
         <li><i class="fas fa-phone me-2"></i>+1 (555) 123-4567</li>
       </ul>
     </div>
     <div class="col-lg-6">
       <form class="card border-0 shadow-sm rounded-4 p-4">
         <div class="row g-3">
           <div class="col-md-6"><input class="form-control" placeholder="Your name"></div>
           <div class="col-md-6"><input class="form-control" type="email" placeholder="Email"></div>
           <div class="col-12"><input class="form-control" placeholder="Subject"></div>
           <div class="col-12"><textarea class="form-control" rows="5" placeholder="Message"></textarea></div>
           <div class="col-12"><button class="btn btn-primary px-4">Send</button></div>
         </div>
       </form>
     </div>
   </div>
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= VIDEO WITH TEXT ========= */
    private function secVideoText(): array
    {
        return [
            'defaults' => [
                'title'=>'Watch the demo',
                'text'=>'See how the platform fits your workflow.',
                'video_url'=>'https://www.w3schools.com/html/mov_bbb.mp4',
                'bg'=>'#0b0f19',
            ],
            'fields' => [
                'title'=>['type'=>'text','default'=>'Watch the demo','label'=>'Title'],
                'text'=>['type'=>'text','default'=>'See how the platform fits your workflow.','label'=>'Text'],
                'video_url'=>['type'=>'url','default'=>'','label'=>'Video URL (mp4)'],
                'bg'=>['type'=>'color','default'=>'#0b0f19','label'=>'Background'],
            ],
            'content' => [
                'html' => '<section class="video-text py-5" style="background: {{ $config[\'bg\']??\'#0b0f19\' }};">
 <div class="container">
   <div class="row align-items-center g-5">
     <div class="col-lg-6 text-white">
       <h2 class="fw-bold mb-2">{{ $config[\'title\']??\'Demo\' }}</h2>
       <p class="text-white-50 mb-0">{{ $config[\'text\']??\'Text\' }}</p>
     </div>
     <div class="col-lg-6">
       <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow">
         @if(!empty($config[\'video_url\']))<video controls src="{{ $config[\'video_url\'] }}"></video>
         @else
           <div class="d-flex align-items-center justify-content-center bg-dark text-white-50">Add a video URL</div>
         @endif
       </div>
     </div>
   </div>
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= TIMELINE ========= */
    private function secTimeline(): array
    {
        return [
            'defaults' => [
                'title'=>'Our journey',
                'items'=>[
                    ['year'=>'2018','text'=>'Company founded'],
                    ['year'=>'2020','text'=>'First 1k customers'],
                    ['year'=>'2023','text'=>'Global expansion'],
                ]
            ],
            'fields' => [
                'title'=>['type'=>'text','default'=>'Our journey','label'=>'Title'],
                'items'=>['type'=>'array','default'=>[],'label'=>'Items'],
            ],
            'content' => [
                'html' => '<section class="timeline py-5 bg-light">
 <div class="container">
   <h2 class="fw-bold text-center mb-5">{{ $config[\'title\']??\'Timeline\' }}</h2>
   <div class="position-relative">
     <div class="position-absolute top-0 bottom-0 start-50 translate-middle-x" style="width:2px;background:#e5e7eb;"></div>
     @foreach(($config[\'items\']??[]) as $i => $it)
       <div class="row g-4 align-items-center mb-4">
         <div class="col-md-6 {{ $i%2?\'order-md-2\':\'\' }}">
           <div class="bg-white rounded-4 shadow-sm p-4 h-100">
             <h6 class="text-primary mb-1">{{ $it[\'year\']??\'Year\' }}</h6>
             <p class="mb-0">{{ $it[\'text\']??\'Event\' }}</p>
           </div>
         </div>
         <div class="col-md-6 {{ $i%2?\'order-md-1\':\'\' }} d-flex justify-content-{{ $i%2?\'md-start\':\'md-end\' }}">
           <span class="rounded-circle d-inline-block" style="width:14px;height:14px;background:#3b82f6"></span>
         </div>
       </div>
     @endforeach
   </div>
 </div>
</section>',
                'css' => '',
                'js' => '',
            ],
        ];
    }

    /* ========= PARTNERS ========= */
    private function secPartners(): array
    {
        return [
            'defaults' => [
                'title'=>'Trusted by teams worldwide',
                'logos'=>[
                    'https://dummyimage.com/120x48/ddd/000&text=A',
                    'https://dummyimage.com/120x48/ddd/000&text=B',
                    'https://dummyimage.com/120x48/ddd/000&text=C',
                    'https://dummyimage.com/120x48/ddd/000&text=D',
                    'https://dummyimage.com/120x48/ddd/000&text=E',
                ]
            ],
            'fields' => [
                'title'=>['type'=>'text','default'=>'Trusted by teams worldwide','label'=>'Title'],
                'logos'=>['type'=>'array','default'=>[],'label'=>'Logos'],
            ],
            'content' => [
                'html' => '<section class="partners py-5">
 <div class="container">
   <h6 class="text-center text-muted mb-4">{{ $config[\'title\']??\'Trusted by\' }}</h6>
   <div class="d-flex flex-wrap justify-content-center gap-4">
     @foreach(($config[\'logos\']??[]) as $l)
       <img src="{{ $l }}" class="opacity-75" height="40" alt="Logo">
     @endforeach
   </div>
 </div>
</section>',
                'css' => '.partners img{filter:grayscale(100%);transition:opacity .2s} .partners img:hover{opacity:1}',
                'js' => '',
            ],
        ];
    }
}
