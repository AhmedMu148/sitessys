@extends('admin.layouts.master')

@section('title', 'Color palette')

@section('content')
<div class="container-fluid p-0 settings-colors">
    <h1 class="h3 mb-3">Color palette</h1>

    <!-- Page theme (يتحدّث من الـ JS) -->
    <style id="page-theme-css">
    /* قيم افتراضية قبل ما الـ JS يحدّثها */
    .settings-colors{
        --page-accent: {{ $currentColors['primary'] ?? '#0d6efd' }};
        --page-bg: {{ $currentColors['body']['background'] ?? '#ffffff' }};
        --page-text: {{ $currentColors['body']['text'] ?? '#212529' }};
        --page-accent-darker: #0b5ed7; /* بديل سريع لحد ما الـ JS يحدّث بدرجة أدكن */
        --page-accent-border: rgba(13,110,253,.25);
    }

    /* تبويب زي الصورة مع ألوان متناسقة */
    .settings-colors .nav-tabs{
        border-color: var(--page-accent-border);
    }
    .settings-colors .nav-tabs .nav-link{
        color: var(--page-accent);
        border-color: transparent;
    }
    .settings-colors .nav-tabs .nav-link:hover{
        color: var(--page-accent-darker);
        border-color: transparent;
    }
    .settings-colors .nav-tabs .nav-link.active{
        color: #fff;
        background-color: var(--page-accent);
        border-color: var(--page-accent);
    }

    /* كروت أنضف بلمسة لون في الهيدر والحواف */
    .settings-colors .card{
        border-color: var(--page-accent-border);
    }
    .settings-colors .card-header{
        border-bottom-color: var(--page-accent-border);
        background-image: linear-gradient(to bottom, rgba(0,0,0,0.02), rgba(0,0,0,0));
    }

    /* أزرار ومفاتيح */
    .settings-colors .btn-primary{
        background-color: var(--page-accent);
        border-color: var(--page-accent);
    }
    .settings-colors .btn-primary:hover{
        background-color: var(--page-accent-darker);
        border-color: var(--page-accent-darker);
    }
    .settings-colors .form-check-input:checked{
        background-color: var(--page-accent);
        border-color: var(--page-accent);
    }

    /* ملاحظات التباين والبادجز */
    .settings-colors #contrast-note{
        border-color: var(--page-accent-border);
    }

    /* بريفيو بانل */
    .settings-colors #preview-css{
        border: 1px dashed var(--page-accent-border);
        padding: .5rem;
        border-radius: .375rem;
        background-color: #fff;
    }
    </style>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-simple" type="button" role="tab">
                Simple
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-advanced" type="button" role="tab">
                Advanced
            </button>
        </li>
    </ul>

    <div class="row">
        <!-- Left column: forms -->
        <div class="col-lg-6">
            <div class="tab-content">

                {{-- ===================== SIMPLE ===================== --}}
                <div class="tab-pane fade show active" id="tab-simple" role="tabpanel">
                    <div class="card mb-3">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Quick presets</h5>
                        </div>
                        <div class="card-body d-flex flex-wrap gap-2">
                            <button class="btn btn-outline-secondary btn-sm preset" data-preset="royal">Royal Blue</button>
                            <button class="btn btn-outline-secondary btn-sm preset" data-preset="mint">Mint Fresh</button>
                            <button class="btn btn-outline-secondary btn-sm preset" data-preset="sunset">Sunset</button>
                            <button class="btn btn-outline-secondary btn-sm preset" data-preset="midnight">Midnight</button>
                            <button class="btn btn-outline-secondary btn-sm preset" data-preset="ocean">Ocean Wave</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Base colors</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auto-derive" checked>
                                <label class="form-check-label" for="auto-derive">Auto-derive</label>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="simple-form" onsubmit="return false;">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Primary</label>
                                        <input type="color" class="form-control form-control-color w-100" id="simple-primary" value="{{ $currentColors['primary'] ?? '#0d6efd' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Secondary</label>
                                        <input type="color" class="form-control form-control-color w-100" id="simple-secondary" value="{{ $currentColors['secondary'] ?? '#6c757d' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Body Background</label>
                                        <input type="color" class="form-control form-control-color w-100" id="simple-body-bg" value="{{ $currentColors['body']['background'] ?? '#ffffff' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Body Text</label>
                                        <input type="color" class="form-control form-control-color w-100" id="simple-body-text" value="{{ $currentColors['body']['text'] ?? '#212529' }}">
                                    </div>
                                    <div class="col-12">
                                        <div class="alert alert-light border d-flex align-items-center gap-2 m-0" id="contrast-note">
                                            <span class="badge rounded-pill bg-success" id="contrast-badge">OK</span>
                                            <span class="small">Contrast check for primary on body background.</span>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-primary" id="btn-save-simple">Save</button>
                                    <button type="button" class="btn btn-outline-secondary" id="btn-preview-simple">Preview CSS</button>
                                    <button type="button" class="btn btn-outline-danger" id="btn-reset-simple">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ===================== ADVANCED ===================== --}}
                <div class="tab-pane fade" id="tab-advanced" role="tabpanel">
                    <div class="alert alert-warning small">
                        This section is for advanced users. Any incorrect modification may affect readability.
                    </div>

                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">All variables</h5>
                        </div>
                        <div class="card-body">
                            <form id="colors-form" onsubmit="return false;">
                                @csrf
                                <!-- ===== base palette ===== -->
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Primary</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-primary" value="{{ $currentColors['primary'] ?? '#0d6efd' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Secondary</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-secondary" value="{{ $currentColors['secondary'] ?? '#6c757d' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Success</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-success" value="{{ $currentColors['success'] ?? '#198754' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Info</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-info" value="{{ $currentColors['info'] ?? '#0dcaf0' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Warning</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-warning" value="{{ $currentColors['warning'] ?? '#ffc107' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Danger</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-danger" value="{{ $currentColors['danger'] ?? '#dc3545' }}">
                                    </div>
                                </div>

                                <hr>
                                <!-- ===== nav/footer ===== -->
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Nav Background</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-nav-bg" value="{{ $currentColors['nav']['background'] ?? '#ffffff' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Nav Text</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-nav-text" value="{{ $currentColors['nav']['text'] ?? '#000000' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Nav Link</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-nav-link" value="{{ $currentColors['nav']['link'] ?? ($currentColors['nav']['text'] ?? '#000000') }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Nav Link Hover</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-nav-link-hover" value="{{ $currentColors['nav']['link_hover'] ?? ($currentColors['primary'] ?? '#0d6efd') }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Nav Button BG</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-nav-btn-bg" value="{{ $currentColors['nav']['button_bg'] ?? ($currentColors['primary'] ?? '#0d6efd') }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Nav Button Text</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-nav-btn-text" value="{{ $currentColors['nav']['button_text'] ?? '#ffffff' }}">
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Footer Background</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-footer-bg" value="{{ $currentColors['footer']['background'] ?? '#f8f9fa' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Footer Text</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-footer-text" value="{{ $currentColors['footer']['text'] ?? '#000000' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Footer Link</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-footer-link" value="{{ $currentColors['footer']['link'] ?? ($currentColors['primary'] ?? '#0d6efd') }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Footer Link Hover</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-footer-link-hover" value="{{ $currentColors['footer']['link_hover'] ?? ($currentColors['secondary'] ?? '#6c757d') }}">
                                    </div>
                                </div>

                                <hr>
                                <!-- ===== body / links ===== -->
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Body Background</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-body-bg" value="{{ $currentColors['body']['background'] ?? '#ffffff' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Body Text</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-body-text" value="{{ $currentColors['body']['text'] ?? '#212529' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Link</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-link" value="{{ $currentColors['link']['color'] ?? ($currentColors['primary'] ?? '#0d6efd') }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Link Hover</label>
                                        <input type="color" class="form-control form-control-color w-100" id="color-link-hover" value="{{ $currentColors['link']['hover'] ?? ($currentColors['secondary'] ?? '#6c757d') }}">
                                    </div>
                                </div>

                                <hr>
                                <!-- ===== section overrides ===== -->
                               <!-- ===== section overrides (nullable inputs) ===== -->
<div class="row g-3">
  <div class="col-6">
    <label class="form-label">Section Background</label>
    <input type="color" class="form-control form-control-color w-100"
           id="color-section-bg"
           value="{{ $currentColors['section']['background'] ?? '#000000' }}"
           data-empty="{{ empty($currentColors['section']['background']) ? '1' : '0' }}">
  </div>
  <div class="col-6">
    <label class="form-label">Section Text</label>
    <input type="color" class="form-control form-control-color w-100"
           id="color-section-text"
           value="{{ $currentColors['section']['text'] ?? '#000000' }}"
           data-empty="{{ empty($currentColors['section']['text']) ? '1' : '0' }}">
  </div>
  <div class="col-6">
    <label class="form-label">Section Heading</label>
    <input type="color" class="form-control form-control-color w-100"
           id="color-section-heading"
           value="{{ $currentColors['section']['heading'] ?? '#000000' }}"
           data-empty="{{ empty($currentColors['section']['heading']) ? '1' : '0' }}">
  </div>
  <div class="col-6">
    <label class="form-label">Section Link</label>
    <input type="color" class="form-control form-control-color w-100"
           id="color-section-link"
           value="{{ $currentColors['section']['link'] ?? '#000000' }}"
           data-empty="{{ empty($currentColors['section']['link']) ? '1' : '0' }}">
  </div>
  <div class="col-6">
    <label class="form-label">Section Link Hover</label>
    <input type="color" class="form-control form-control-color w-100"
           id="color-section-link-hover"
           value="{{ $currentColors['section']['link_hover'] ?? '#000000' }}"
           data-empty="{{ empty($currentColors['section']['link_hover']) ? '1' : '0' }}">
  </div>
  <div class="col-6">
    <label class="form-label">Section Button BG</label>
    <input type="color" class="form-control form-control-color w-100"
           id="color-section-btn-bg"
           value="{{ $currentColors['section']['button_bg'] ?? '#000000' }}"
           data-empty="{{ empty($currentColors['section']['button_bg']) ? '1' : '0' }}">
  </div>
  <div class="col-6">
    <label class="form-label">Section Button Text</label>
    <input type="color" class="form-control form-control-color w-100"
           id="color-section-btn-text"
           value="{{ $currentColors['section']['button_text'] ?? '#000000' }}"
           data-empty="{{ empty($currentColors['section']['button_text']) ? '1' : '0' }}">
  </div>
</div>

                                <hr>
                                <!-- ===== button text colors ===== -->
                                <div class="row g-3">
  <div class="col-6">
    <label class="form-label">Btn Primary Text</label>
    <input type="color" class="form-control form-control-color w-100"
           id="btn-primary-text"
           value="{{ $currentColors['buttons']['primary_text'] ?? '#000000' }}"
           data-empty="{{ empty($currentColors['buttons']['primary_text']) ? '1' : '0' }}">
  </div>
  <div class="col-6">
    <label class="form-label">Btn Secondary Text</label>
    <input type="color" class="form-control form-control-color w-100"
           id="btn-secondary-text"
           value="{{ $currentColors['buttons']['secondary_text'] ?? '#000000' }}"
           data-empty="{{ empty($currentColors['buttons']['secondary_text']) ? '1' : '0' }}">
  </div>
  <div class="col-6">
    <label class="form-label">Btn Success Text</label>
    <input type="color" class="form-control form-control-color w-100"
           id="btn-success-text"
           value="{{ $currentColors['buttons']['success_text'] ?? '#000000' }}"
           data-empty="{{ empty($currentColors['buttons']['success_text']) ? '1' : '0' }}">
  </div>
  <div class="col-6">
    <label class="form-label">Btn Info Text</label>
    <input type="color" class="form-control form-control-color w-100"
           id="btn-info-text"
           value="{{ $currentColors['buttons']['info_text'] ?? '#000000' }}"
           data-empty="{{ empty($currentColors['buttons']['info_text']) ? '1' : '0' }}">
  </div>
  <div class="col-6">
    <label class="form-label">Btn Warning Text</label>
    <input type="color" class="form-control form-control-color w-100"
           id="btn-warning-text"
           value="{{ $currentColors['buttons']['warning_text'] ?? '#000000' }}"
           data-empty="{{ empty($currentColors['buttons']['warning_text']) ? '1' : '0' }}">
  </div>
  <div class="col-6">
    <label class="form-label">Btn Danger Text</label>
    <input type="color" class="form-control form-control-color w-100"
           id="btn-danger-text"
           value="{{ $currentColors['buttons']['danger_text'] ?? '#000000' }}"
           data-empty="{{ empty($currentColors['buttons']['danger_text']) ? '1' : '0' }}">
  </div>
</div>


                                <div class="mt-3 d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-primary" id="btn-save">Save</button>
                                    <button type="button" class="btn btn-outline-secondary" id="btn-preview">Preview CSS</button>
                                    <button type="button" class="btn btn-outline-secondary" id="btn-copy">Copy CSS</button>
                                    <button type="button" class="btn btn-outline-secondary" id="btn-download">Download CSS</button>
                                    <button type="button" class="btn btn-outline-danger" id="btn-reset">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Bootstrap.build import</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">Paste variables CSS exported from https://bootstrap.build/themes to import a palette.</p>
                            <textarea id="raw-css" class="form-control" rows="8" placeholder=":root{ --bs-primary:#0d6efd; }"></textarea>
                            <div class="mt-2 d-flex gap-2">
                                <button type="button" class="btn btn-secondary" id="btn-apply-raw">Merge & Save</button>
                                <button type="button" class="btn btn-outline-secondary" id="btn-preview-raw">Preview</button>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">Schemes</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2" id="schemes"></div>
                        </div>
                    </div>
                </div>
                {{-- =================== /ADVANCED =================== --}}
            </div>
        </div>

        <!-- Right column: Preview -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Preview CSS</h5></div>
                <div class="card-body">
                    <pre class="small" id="preview-css" style="white-space: pre-wrap;"></pre>
                    <hr>
                    <div class="p-3 border rounded" id="live-sample">
                        <div class="navbar navbar-expand rounded px-3 mb-3">Navbar</div>
                        <div class="section p-3 rounded mb-2">
                            <h5>Section Title</h5>
                            <p>Paragraph text with a <a href="#">link</a>.</p>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-primary btn-sm">Primary</button>
                                <button class="btn btn-secondary btn-sm">Secondary</button>
                                <button class="btn btn-success btn-sm">Success</button>
                                <button class="btn btn-info btn-sm">Info</button>
                                <button class="btn btn-warning btn-sm">Warning</button>
                                <button class="btn btn-danger btn-sm">Danger</button>
                            </div>
                        </div>
                        <footer class="p-2 rounded">Footer area</footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
// ========= Nullable helpers =========

// أي color input يتغيّر يبقى مش فاضي
document.querySelectorAll('input[type="color"]').forEach(el=>{
  el.addEventListener('input', ()=>{ el.dataset.empty = '0'; });
});

// حقول اختيارية (هنتعامل معاها كـ nullable)
const NULLABLE_SECTION_IDS = [
  'color-section-bg','color-section-text','color-section-heading',
  'color-section-link','color-section-link-hover',
  'color-section-btn-bg','color-section-btn-text'
];
const NULLABLE_BTN_TEXT_IDS = [
  'btn-primary-text','btn-secondary-text','btn-success-text',
  'btn-info-text','btn-warning-text','btn-danger-text'
];

// علّم الحقل إنه فاضي (nullable) وخلي قيمته #000000 لأن <input type="color"> لازم قيمة
function markEmpty(id){
  const el = document.getElementById(id);
  if(!el) return;
  el.value = '#000000';
  el.dataset.empty = '1';
}
// في أول تحميل: لو السيرفر مرجع null لكن المتصفح عيّن #000000، نعلّمها empty مرة واحدة
function initNullableFlags(){
  [...NULLABLE_SECTION_IDS, ...NULLABLE_BTN_TEXT_IDS].forEach(id=>{
    const el = document.getElementById(id);
    if(!el) return;
    if(!('empty' in el.dataset)) {
      // نعتبرها فاضية مالم يلمسها المستخدم (هيبقى el.dataset.empty='0' بعد أي input)
      el.dataset.empty = (el.value && el.value.toUpperCase() !== '#000000') ? '0' : '1';
    }
  });
}

// ========= CSRF =========
const tokenMeta = document.querySelector('meta[name="csrf-token"]');
const token = tokenMeta ? tokenMeta.getAttribute('content') : '{{ csrf_token() }}';

// ========= Helpers =========
async function post(url, body){
  const res = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
    body: JSON.stringify(body)
  });
  return await res.json();
}
async function get(url){ const res = await fetch(url); return await res.json(); }
function debounce(fn, ms=250){ let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a),ms); }; }

const hex2rgb = h => ({ r:parseInt(h.slice(1,3),16), g:parseInt(h.slice(3,5),16), b:parseInt(h.slice(5,7),16) });
const luminance = ({r,g,b})=>{
  const a=[r,g,b].map(v=>{v/=255; return v<=0.03928? v/12.92 : Math.pow((v+0.055)/1.055,2.4)});
  return 0.2126*a[0]+0.7152*a[1]+0.0722*a[2];
};
const contrast = (c1,c2)=>{
  const L1=luminance(hex2rgb(c1)), L2=luminance(hex2rgb(c2));
  return (Math.max(L1,L2)+0.05)/(Math.min(L1,L2)+0.05);
};
const shade = (hex, pct)=>{ // -30..30
  const {r,g,b}=hex2rgb(hex); const f=pct/100;
  const to = v=>Math.max(0,Math.min(255, Math.round(v + (f>0?(255-v)*f:v*f))));
  return '#'+[to(r),to(g),to(b)].map(v=>v.toString(16).padStart(2,'0')).join('');
};

// ========= Export / Preview CSS =========
function cssFromColors(colors){
return `
:root{
  --color-primary:${colors.primary};
  --color-secondary:${colors.secondary};
  --bs-primary:${colors.primary};
  --bs-secondary:${colors.secondary};
  --bs-success:${colors.success};
  --bs-info:${colors.info};
  --bs-warning:${colors.warning};
  --bs-danger:${colors.danger};
  --bs-link-color:${colors.link.color || colors.primary};
  --bs-link-hover-color:${colors.link.hover || colors.secondary};
  --bs-body-bg:${colors.body.background};
  --bs-body-color:${colors.body.text};
  --nav-bg:${colors.nav.background};
  --nav-text:${colors.nav.text};
  --nav-link:${colors.nav.link};
  --nav-link-hover:${colors.nav.link_hover};
  --nav-btn-bg:${colors.nav.button_bg};
  --nav-btn-text:${colors.nav.button_text};
  --footer-bg:${colors.footer.background};
  --footer-text:${colors.footer.text};
  --footer-link:${colors.footer.link};
  --footer-link-hover:${colors.footer.link_hover};
  ${colors.section.background ? `--section-bg:${colors.section.background};` : ''}
  ${colors.section.text ? `--section-text:${colors.section.text};` : ''}
  ${colors.section.heading ? `--section-heading:${colors.section.heading};` : ''}
  ${colors.section.link ? `--section-link:${colors.section.link};` : ''}
  ${colors.section.link_hover ? `--section-link-hover:${colors.section.link_hover};` : ''}
  ${colors.section.button_bg ? `--section-btn-bg:${colors.section.button_bg};` : ''}
  ${colors.section.button_text ? `--section-btn-text:${colors.section.button_text};` : ''}
}
body{background-color:var(--bs-body-bg); color:var(--bs-body-color);}
.navbar{background-color:var(--nav-bg)!important;color:var(--nav-text)!important;}
.navbar .nav-link{color:var(--nav-link)!important;}
.navbar .nav-link:hover{color:var(--nav-link-hover)!important;}
.navbar .navbar-brand{color:var(--nav-text)!important;}
.navbar .btn,.navbar .btn-primary{--bs-btn-bg:var(--nav-btn-bg);--bs-btn-border-color:var(--nav-btn-bg);--bs-btn-color:var(--nav-btn-text);}
a{color:var(--bs-link-color);} a:hover{color:var(--bs-link-hover-color);}
footer{background-color:var(--footer-bg)!important;color:var(--footer-text)!important;}
footer a{color:var(--footer-link);} footer a:hover{color:var(--footer-link-hover);}
.section{background-color:var(--section-bg,initial); color:var(--section-text,inherit);}
.section h1,.section h2,.section h3,.section h4,.section h5,.section h6{color:var(--section-heading,inherit);}
.section a{color:var(--section-link,var(--bs-link-color));}
.section a:hover{color:var(--section-link-hover,var(--bs-link-hover-color));}
.section .btn,.section .btn-primary{--bs-btn-bg:var(--section-btn-bg,var(--bs-primary));--bs-btn-border-color:var(--section-btn-bg,var(--bs-primary));--bs-btn-color:var(--section-btn-text,#fff);}
`.trim();
}
function scopeCss(css){
  let out = css;
  out = out.replace(/\nbody\s*\{/g, '\n#live-sample{');
  out = out.replace(/\n\.navbar/g, '\n#live-sample .navbar');
  out = out.replace(/\nfooter/g, '\n#live-sample footer');
  out = out.replace(/\nsection\b/g, '\n#live-sample section');
  out = out.replace(/\n\.section/g, '\n#live-sample .section');
  out = out.replace(/\na\s*\{/g, '\n#live-sample a{');
  out = out.replace(/\n\.btn/g, '\n#live-sample .btn');
  return out;
}
function applyPreviewCss(css){
  const scoped = scopeCss(css);
  let el = document.getElementById('__preview_theme');
  if(!el){ el = document.createElement('style'); el.id='__preview_theme'; document.body.appendChild(el); }
  el.textContent = scoped;
  const pre = document.getElementById('preview-css');
  if(pre) pre.textContent = css;
}

// ========= Gather colors (with nullable support) =========
function readColor(id, nullable=false){
  const el = document.getElementById(id);
  if(!el) return null;
  const v = (el.value || '').toUpperCase();
  if (nullable && el.dataset.empty === '1' && (v === '#000000' || v === '')) return null;
  return v || null;
}
function gatherColors(){
  return {
    primary:   readColor('color-primary'),
    secondary: readColor('color-secondary'),
    success:   readColor('color-success'),
    info:      readColor('color-info'),
    warning:   readColor('color-warning'),
    danger:    readColor('color-danger'),

    nav: {
      background: readColor('color-nav-bg'),
      text:       readColor('color-nav-text'),
      link:       readColor('color-nav-link'),
      link_hover: readColor('color-nav-link-hover'),
      button_bg:  readColor('color-nav-btn-bg'),
      button_text:readColor('color-nav-btn-text'),
    },
    footer: {
      background: readColor('color-footer-bg'),
      text:       readColor('color-footer-text'),
      link:       readColor('color-footer-link'),
      link_hover: readColor('color-footer-link-hover'),
    },
    body: {
      background: readColor('color-body-bg'),
      text:       readColor('color-body-text'),
    },
    link: {
      color: readColor('color-link'),
      hover: readColor('color-link-hover'),
    },

    // اختيارية — null يفعل fallback
    section: {
      background: readColor('color-section-bg', true),
      text:       readColor('color-section-text', true),
      heading:    readColor('color-section-heading', true),
      link:       readColor('color-section-link', true),
      link_hover: readColor('color-section-link-hover', true),
      button_bg:  readColor('color-section-btn-bg', true),
      button_text:readColor('color-section-btn-text', true),
    },
    buttons: {
      primary_text:   readColor('btn-primary-text', true),
      secondary_text: readColor('btn-secondary-text', true),
      success_text:   readColor('btn-success-text', true),
      info_text:      readColor('btn-info-text', true),
      warning_text:   readColor('btn-warning-text', true),
      danger_text:    readColor('btn-danger-text', true),
    },

    raw_css: document.getElementById('raw-css')?.value || ''
  };
}

// ========= Page theme tint =========
function updatePageTheme(accent, bodyBg, bodyText){
  const darker = shade(accent, -15);
  const css = `
  .settings-colors{
    --page-accent:${accent};
    --page-bg:${bodyBg};
    --page-text:${bodyText};
    --page-accent-darker:${darker};
    --page-accent-border: rgba(0,0,0,.12);
  }`;
  const el = document.getElementById('page-theme-css');
  el.textContent = el.textContent.replace(/\.settings-colors\{[\s\S]*?\}/,'') + css;
}

// ========= Simple ⇄ Advanced & derive =========
function applySimpleToAdvanced(){
  const p = document.getElementById('simple-primary').value;
  const s = document.getElementById('simple-secondary').value;
  const bg = document.getElementById('simple-body-bg').value;
  const txt = document.getElementById('simple-body-text').value;

  // حدّث الأساسيات
  document.getElementById('color-primary').value = p;
  document.getElementById('color-secondary').value = s;
  document.getElementById('color-body-bg').value = bg;
  document.getElementById('color-body-text').value = txt;
  document.getElementById('color-link').value = p;
  document.getElementById('color-link-hover').value = s;

  if(document.getElementById('auto-derive').checked){
    // Nav
    document.getElementById('color-nav-bg').value = p;
    const navText = contrast(p, '#ffffff') >= 4.5 ? '#ffffff' : '#0f172a';
    document.getElementById('color-nav-text').value = navText;
    document.getElementById('color-nav-link').value = navText;
    document.getElementById('color-nav-link-hover').value = shade(navText, -25);
    document.getElementById('color-nav-btn-bg').value = shade(p, -10);
    document.getElementById('color-nav-btn-text').value = contrast(shade(p,-10), '#ffffff')>=4.5 ? '#ffffff' : '#0f172a';

    // Footer
    document.getElementById('color-footer-bg').value = shade(bg, -6);
    document.getElementById('color-footer-text').value = txt;
    document.getElementById('color-footer-link').value = p;
    document.getElementById('color-footer-link-hover').value = s;

    // Section defaults → خليها nullable (ما تتبعتش)
    NULLABLE_SECTION_IDS.forEach(id => markEmpty(id));

    // Buttons text (لو سايبها للذكاء التلقائي)
    NULLABLE_BTN_TEXT_IDS.forEach(id => {
      const el = document.getElementById(id);
      if(!el) return;
      // هنقترح قيم مناسبة، لكن لو عايز تسيبها للباك إند احذف السطور الجاية:
      switch(id){
        case 'btn-primary-text':   el.value = contrast(p, '#ffffff')>=4.5 ? '#FFFFFF' : '#0F172A'; el.dataset.empty='0'; break;
        case 'btn-secondary-text': el.value = contrast(s, '#ffffff')>=4.5 ? '#FFFFFF' : '#0F172A'; el.dataset.empty='0'; break;
        case 'btn-success-text':   el.value = '#FFFFFF'; el.dataset.empty='0'; break;
        case 'btn-info-text':      el.value = '#FFFFFF'; el.dataset.empty='0'; break;
        case 'btn-warning-text':   el.value = '#0F172A'; el.dataset.empty='0'; break;
        case 'btn-danger-text':    el.value = '#FFFFFF'; el.dataset.empty='0'; break;
      }
    });
  }

  updatePageTheme(p, bg, txt);
  livePreview();
}

function livePreview(){
  const css = cssFromColors(gatherColors());
  applyPreviewCss(css);
}

function updateContrastBadge(){
  const p = document.getElementById('simple-primary').value;
  const bg = document.getElementById('simple-body-bg').value;
  const ratio = contrast(p, bg);
  const badge = document.getElementById('contrast-badge');
  if(ratio >= 4.5){ badge.className='badge rounded-pill bg-success'; badge.textContent = `OK (${ratio.toFixed(2)}:1)`; }
  else{ badge.className='badge rounded-pill bg-warning text-dark'; badge.textContent = `Low (${ratio.toFixed(2)}:1)`; }
}

// ========= Simple events =========
['simple-primary','simple-secondary','simple-body-bg','simple-body-text','auto-derive']
  .forEach(id => document.getElementById(id).addEventListener('input', debounce(()=>{ applySimpleToAdvanced(); updateContrastBadge(); },150)));

document.getElementById('btn-save-simple').onclick = async ()=>{
  applySimpleToAdvanced();
  const resp = await post('{{ route('admin.colors.update') }}', { colors: gatherColors() });
  if(resp.success){ alert('Saved'); }
};
document.getElementById('btn-preview-simple').onclick = ()=>{ applySimpleToAdvanced(); };
document.getElementById('btn-reset-simple').onclick = async ()=>{ const r=await post('{{ route('admin.colors.reset') }}',{}); if(r.success){ location.reload(); } };

// ========= Presets =========
const PRESETS = {
  royal:   { primary:'#0d6efd', secondary:'#495867', bg:'#f8fafc', text:'#0f172a' },
  mint:    { primary:'#10b981', secondary:'#2d4a3f', bg:'#f6fffb', text:'#0f172a' },
  sunset:  { primary:'#f97316', secondary:'#6b4f4f', bg:'#fff8f3', text:'#111827' },
  midnight:{ primary:'#0ea5e9', secondary:'#1f2937', bg:'#0b1220', text:'#e5e7eb' },
  ocean:   { primary:'#2563eb', secondary:'#0f172a', bg:'#f3f6ff', text:'#0f172a' },
};
document.querySelectorAll('.preset').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    const p = PRESETS[btn.dataset.preset];
    document.getElementById('simple-primary').value = p.primary;
    document.getElementById('simple-secondary').value = p.secondary;
    document.getElementById('simple-body-bg').value = p.bg;
    document.getElementById('simple-body-text').value = p.text;
    // لما نختار preset: اعتبر السكاشن nullable لحد ما المستخدم يغيرها
    NULLABLE_SECTION_IDS.forEach(id => markEmpty(id));
    applySimpleToAdvanced();
    updateContrastBadge();
  });
});

// ========= Advanced actions =========
document.getElementById('btn-save').onclick = async () => {
  const resp = await post('{{ route('admin.colors.update') }}', { colors: gatherColors() });
  if(resp.success){ alert('Saved'); }
};
document.getElementById('btn-preview').onclick = async () => {
  const colors = gatherColors();
  try{
    const resp = await post('{{ route('admin.colors.preview') }}', { colors });
    if(resp.success){ applyPreviewCss(resp.preview_css); }
    else{ applyPreviewCss(cssFromColors(colors)); }
  }catch(e){ applyPreviewCss(cssFromColors(colors)); }
};
document.getElementById('btn-reset').onclick = async () => {
  const resp = await post('{{ route('admin.colors.reset') }}', {});
  if(resp.success){ location.reload(); }
};
document.getElementById('btn-apply-raw').onclick = async () => {
  const resp = await post('{{ route('admin.colors.update') }}', { colors: gatherColors() });
  if(resp.success){ location.reload(); }
};
document.getElementById('btn-preview-raw').onclick = async () => {
  const colors = gatherColors();
  try{
    const resp = await post('{{ route('admin.colors.preview') }}', { colors });
    if(resp.success){ applyPreviewCss(resp.preview_css); }
    else{ applyPreviewCss(cssFromColors(colors)); }
  }catch(e){ applyPreviewCss(cssFromColors(colors)); }
};
Array.from(document.querySelectorAll('#colors-form input[type="color"]')).forEach(el=>{
  el.addEventListener('input', debounce(()=>{
    const css = cssFromColors(gatherColors());
    applyPreviewCss(css);
    updatePageTheme(
      document.getElementById('color-primary').value,
      document.getElementById('color-body-bg').value,
      document.getElementById('color-body-text').value
    );
  }, 200));
});
document.getElementById('btn-copy').onclick = async ()=>{
  const css = document.getElementById('preview-css').textContent || cssFromColors(gatherColors());
  await navigator.clipboard.writeText(css); alert('CSS copied');
};
document.getElementById('btn-download').onclick = ()=>{
  const css = document.getElementById('preview-css').textContent || cssFromColors(gatherColors());
  const blob = new Blob([css], {type:'text/css'});
  const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = 'site-colors.css'; a.click(); URL.revokeObjectURL(a.href);
};

// Schemes
async function loadSchemes(){
  const data = await get('{{ route('admin.colors.schemes') }}');
  const wrap = document.getElementById('schemes'); if(!wrap) return; wrap.innerHTML='';
  if(data.success){
    Object.entries(data.data).forEach(([key, s])=>{
      const btn=document.createElement('button');
      btn.type='button';
      btn.className='btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-2';
      btn.innerHTML=`<span class="d-inline-block rounded-circle" style="width:14px;height:14px;background:${s.preview_primary || s.colors?.primary || '#0d6efd'}"></span>${s.name || key}`;
      btn.onclick=async()=>{ const resp=await post('{{ route('admin.colors.apply-scheme') }}',{scheme:key}); if(resp.success){ location.reload(); } };
      wrap.appendChild(btn);
    });
  }
}
loadSchemes();

// أول تشغيل
initNullableFlags();
applySimpleToAdvanced();
updateContrastBadge();
</script>

@endsection
