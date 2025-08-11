@extends('admin.layouts.master')

@section('title', 'Color palette')

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Color palette</h1>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Current colors</h5>
                </div>
                <div class="card-body">
                    <form id="colors-form">
                        @csrf
                        <div class="row g-3">
                            <!-- base palette -->
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
                        <!-- nav/footer -->
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
                        <!-- body / links -->
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
                        <!-- section overrides -->
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">Section Background</label>
                                <input type="color" class="form-control form-control-color w-100" id="color-section-bg" value="{{ $currentColors['section']['background'] ?? '' }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Section Text</label>
                                <input type="color" class="form-control form-control-color w-100" id="color-section-text" value="{{ $currentColors['section']['text'] ?? '' }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Section Heading</label>
                                <input type="color" class="form-control form-control-color w-100" id="color-section-heading" value="{{ $currentColors['section']['heading'] ?? '' }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Section Link</label>
                                <input type="color" class="form-control form-control-color w-100" id="color-section-link" value="{{ $currentColors['section']['link'] ?? '' }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Section Link Hover</label>
                                <input type="color" class="form-control form-control-color w-100" id="color-section-link-hover" value="{{ $currentColors['section']['link_hover'] ?? '' }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Section Button BG</label>
                                <input type="color" class="form-control form-control-color w-100" id="color-section-btn-bg" value="{{ $currentColors['section']['button_bg'] ?? '' }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Section Button Text</label>
                                <input type="color" class="form-control form-control-color w-100" id="color-section-btn-text" value="{{ $currentColors['section']['button_text'] ?? '' }}">
                            </div>
                        </div>

                        <hr>
                        <!-- button text colors -->
                        <div class="row g-3">
                            <div class="col-6"><label class="form-label">Btn Primary Text</label><input type="color" class="form-control form-control-color w-100" id="btn-primary-text" value="{{ $currentColors['buttons']['primary_text'] ?? '#ffffff' }}"></div>
                            <div class="col-6"><label class="form-label">Btn Secondary Text</label><input type="color" class="form-control form-control-color w-100" id="btn-secondary-text" value="{{ $currentColors['buttons']['secondary_text'] ?? '#ffffff' }}"></div>
                            <div class="col-6"><label class="form-label">Btn Success Text</label><input type="color" class="form-control form-control-color w-100" id="btn-success-text" value="{{ $currentColors['buttons']['success_text'] ?? '#ffffff' }}"></div>
                            <div class="col-6"><label class="form-label">Btn Info Text</label><input type="color" class="form-control form-control-color w-100" id="btn-info-text" value="{{ $currentColors['buttons']['info_text'] ?? '#ffffff' }}"></div>
                            <div class="col-6"><label class="form-label">Btn Warning Text</label><input type="color" class="form-control form-control-color w-100" id="btn-warning-text" value="{{ $currentColors['buttons']['warning_text'] ?? '#000000' }}"></div>
                            <div class="col-6"><label class="form-label">Btn Danger Text</label><input type="color" class="form-control form-control-color w-100" id="btn-danger-text" value="{{ $currentColors['buttons']['danger_text'] ?? '#ffffff' }}"></div>
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
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Bootstrap.build import</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Paste variables CSS exported from https://bootstrap.build/themes to import a palette.</p>
                    <textarea id="raw-css" class="form-control" rows="10" placeholder=":root{ --bs-primary:#0d6efd; }"></textarea>
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

            <div class="card mt-3">
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
const tokenMeta = document.querySelector('meta[name="csrf-token"]');
const token = tokenMeta ? tokenMeta.getAttribute('content') : '{{ csrf_token() }}';

function gatherColors(){
    return {
        primary: document.getElementById('color-primary').value,
        secondary: document.getElementById('color-secondary').value,
        success: document.getElementById('color-success').value,
        info: document.getElementById('color-info').value,
        warning: document.getElementById('color-warning').value,
        danger: document.getElementById('color-danger').value,
        nav: {
            background: document.getElementById('color-nav-bg').value,
            text: document.getElementById('color-nav-text').value,
            link: document.getElementById('color-nav-link').value,
            link_hover: document.getElementById('color-nav-link-hover').value,
            button_bg: document.getElementById('color-nav-btn-bg').value,
            button_text: document.getElementById('color-nav-btn-text').value,
        },
        footer: {
            background: document.getElementById('color-footer-bg').value,
            text: document.getElementById('color-footer-text').value,
            link: document.getElementById('color-footer-link').value,
            link_hover: document.getElementById('color-footer-link-hover').value,
        },
        body: {
            background: document.getElementById('color-body-bg').value,
            text: document.getElementById('color-body-text').value,
        },
        link: {
            color: document.getElementById('color-link').value,
            hover: document.getElementById('color-link-hover').value,
        },
        section: {
            background: document.getElementById('color-section-bg').value,
            text: document.getElementById('color-section-text').value,
            heading: document.getElementById('color-section-heading').value,
            link: document.getElementById('color-section-link').value,
            link_hover: document.getElementById('color-section-link-hover').value,
            button_bg: document.getElementById('color-section-btn-bg').value,
            button_text: document.getElementById('color-section-btn-text').value,
        },
        buttons: {
            primary_text: document.getElementById('btn-primary-text').value,
            secondary_text: document.getElementById('btn-secondary-text').value,
            success_text: document.getElementById('btn-success-text').value,
            info_text: document.getElementById('btn-info-text').value,
            warning_text: document.getElementById('btn-warning-text').value,
            danger_text: document.getElementById('btn-danger-text').value,
        },
        raw_css: document.getElementById('raw-css').value
    };
}

// Helpers
async function post(url, body){
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
        body: JSON.stringify(body)
    });
    return await res.json();
}
async function get(url){
    const res = await fetch(url);
    return await res.json();
}
function debounce(fn, ms=250){
    let t; return (...args)=>{ clearTimeout(t); t=setTimeout(()=>fn(...args), ms); };
}

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
    // Keep :root as-is; scope visual selectors to #live-sample
    let out = css;
    out = out.replace(/\nbody\s*\{/g, '\n#live-sample{');
    out = out.replace(/\n\.navbar/g, '\n#live-sample .navbar');
    out = out.replace(/\nfooter/g, '\n#live-sample footer');
    out = out.replace(/\nsection\b/g, '\n#live-sample section');
    out = out.replace(/\n\.section/g, '\n#live-sample .section');
    out = out.replace(/\na\s*\{/g, '\n#live-sample a{');
    out = out.replace(/\n\.btn/g, '\n#live-sample .btn');
    out = out.replace(/\n\.container/g, '\n#live-sample .container');
    return out;
}

function applyPreviewCss(css){
    const scoped = scopeCss(css);
    let el = document.getElementById('__preview_theme');
    if(!el){
        el = document.createElement('style');
        el.id = '__preview_theme';
        document.body.appendChild(el);
    }
    el.textContent = scoped;
    document.getElementById('preview-css').textContent = css;
}

async function loadSchemes(){
    const data = await get('{{ route('admin.colors.schemes') }}');
    const wrap = document.getElementById('schemes');
    wrap.innerHTML = '';
    if(data.success){
        Object.entries(data.data).forEach(([key, s]) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-2';
            btn.innerHTML = `
                <span class="d-inline-block rounded-circle" style="width:14px;height:14px;background:${s.preview_primary || s.colors?.primary || '#0d6efd'}"></span>
                ${s.name || key}
            `;
            btn.onclick = async () => {
                const resp = await post('{{ route('admin.colors.apply-scheme') }}', { scheme: key });
                if(resp.success){ location.reload(); }
            };
            wrap.appendChild(btn);
        });
    }
}

// Actions
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
    }catch(e){
        applyPreviewCss(cssFromColors(colors));
    }
};

document.getElementById('btn-reset').onclick = async () => {
    const resp = await post('{{ route('admin.colors.reset') }}', {});
    if(resp.success){ location.reload(); }
};

document.getElementById('btn-apply-raw').onclick = async () => {
    // Merge raw CSS server-side (kept same route)
    const resp = await post('{{ route('admin.colors.update') }}', { colors: gatherColors() });
    if(resp.success){ location.reload(); }
};

document.getElementById('btn-preview-raw').onclick = async () => {
    const colors = gatherColors();
    try{
        const resp = await post('{{ route('admin.colors.preview') }}', { colors });
        if(resp.success){ applyPreviewCss(resp.preview_css); }
        else{ applyPreviewCss(cssFromColors(colors)); }
    }catch(e){
        applyPreviewCss(cssFromColors(colors));
    }
};

// Live preview on input change (debounced)
Array.from(document.querySelectorAll('#colors-form input[type="color"]')).forEach(el=>{
    el.addEventListener('input', debounce(()=>{
        const css = cssFromColors(gatherColors());
        applyPreviewCss(css);
    }, 200));
});

// Copy & Download
document.getElementById('btn-copy').onclick = async ()=>{
    const css = document.getElementById('preview-css').textContent || cssFromColors(gatherColors());
    await navigator.clipboard.writeText(css);
    alert('CSS copied');
};
document.getElementById('btn-download').onclick = ()=>{
    const css = document.getElementById('preview-css').textContent || cssFromColors(gatherColors());
    const blob = new Blob([css], {type:'text/css'});
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'site-colors.css';
    a.click();
    URL.revokeObjectURL(a.href);
};

loadSchemes();
</script>
@endsection
