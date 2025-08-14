// public/js/admin/edit-page-section.js
// ===================================
//  Admin Page Sections (No Edit Modal)
// ===================================

// -------- Bootstrapping / Globals --------
const pageData = window.PAGE || { id: null, name: '', slug: '', sections: [] };
const availableLayouts = window.AVAILABLE_LAYOUTS || [];
const defaultLayoutId = availableLayouts.length > 0 ? availableLayouts[0].id : 1;

const URLS = window.URLS || {
  addSection: `/admin/pages/${pageData.id}/sections`,
  deleteSection: (id) => `/admin/pages/${pageData.id}/sections/${id}`,
  toggleStatus: (id) => `/admin/pages/${pageData.id}/sections/${id}/toggle-status`,
  reorder: `/admin/pages/${pageData.id}/sections/reorder`,
};
const I18N = window.I18N || {
  pleaseSelectTemplate: 'Please select a template first',
  addingSection: 'Adding section to page...',
  sectionAdded: 'Section added successfully!',
  failedAdd: 'Failed to add section',
  errorAdd: 'An error occurred while adding section',
  confirmRemove: 'Are you sure you want to remove this section from the page?',
  removing: 'Removing section from page...',
  removedOk: 'Section removed from page successfully',
  failedRemove: 'Failed to remove section from page',
  errorRemove: 'An error occurred while removing section from page',
  confirmToggle: 'Are you sure you want to toggle this section status?',
  updatingStatus: 'Updating section status...',
  statusNow: 'Section is now',
  active: 'active',
  inactive: 'inactive',
  orderUpdated: 'Sections order updated successfully',
  confirmSaveAll: 'Are you sure you want to save all changes?',
  changesSaved: 'Changes saved successfully',
};
const CSRF = window.CSRF || (document.querySelector('meta[name="csrf-token"]')?.content || '');

// -------- Helpers --------
function api(url, options = {}) {
  const headers = options.headers || {};
  return fetch(url, {
    credentials: 'same-origin',
    ...options,
    headers: {
      'X-CSRF-TOKEN': CSRF,
      ...headers,
    },
  });
}

function showAlert(type, message, duration = 3000) {
  const cls =
    type === 'success'
      ? 'alert-success'
      : type === 'error'
      ? 'alert-danger'
      : type === 'warning'
      ? 'alert-warning'
      : 'alert-info';
  const icon =
    type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'warning' ? '⚠️' : 'ℹ️';

  const target = document.querySelector('.container-fluid') || document.body;
  // Remove any existing
  target.querySelectorAll('.alert').forEach((a) => a.remove());

  const div = document.createElement('div');
  div.className = `alert ${cls} alert-dismissible fade show`;
  div.setAttribute('role', 'alert');
  div.innerHTML = `${icon} ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
  target.insertAdjacentElement('afterbegin', div);

  setTimeout(() => {
    div.classList.remove('show');
    setTimeout(() => div.remove(), 150);
  }, duration);
}

// ===================== Template Selection =====================
let selectedTemplateId = null;
let selectedTemplateName = null;

function addSection() {
  resetModalState();
  const modalEl = document.getElementById('addSectionModal');
  if (!modalEl) return showAlert('error', 'Add Section modal not found');
  new bootstrap.Modal(modalEl).show();
}

// NOTE: يجب استدعاؤها من الـBlade كالتالي:
// onclick="selectSectionTemplate(event, {{ $template->id }}, '{{ addslashes($template->name) }}')"
function selectSectionTemplate(event, templateId, templateName) {
  document.querySelectorAll('.template-selection-card').forEach((c) => c.classList.remove('selected'));
  if (event && event.currentTarget) event.currentTarget.classList.add('selected');

  selectedTemplateId = templateId;
  selectedTemplateName = templateName;

  const info = document.getElementById('selectedTemplateInfo');
  const name = document.getElementById('selectedTemplateName');
  const btn = document.getElementById('addSectionButton');
  const input = document.getElementById('customSectionName');

  if (name) name.innerHTML = `<strong>${templateName}</strong> - Template ID: ${templateId}`;
  if (info) info.style.display = 'block';
  if (btn) btn.style.display = 'inline-block';
  if (input) input.value = templateName;
}

function addSelectedSection() {
  if (!selectedTemplateId)
    return showAlert('error', I18N.pleaseSelectTemplate);

  const customName = (document.getElementById('customSectionName')?.value || '').trim();
  const sectionName = customName || selectedTemplateName;

  const addButton = document.getElementById('addSectionButton');
  const spinner = document.getElementById('addSectionSpinner');
  if (addButton && spinner) {
    addButton.disabled = true;
    spinner.classList.remove('d-none');
  }

  showAlert('info', I18N.addingSection);

  const payload = {
    name: sectionName,
    tpl_layouts_id: selectedTemplateId,
    status: true,
    content: {},
    custom_styles: '',
    custom_scripts: '',
  };

  api(URLS.addSection, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload),
  })
    .then((r) => r.json())
    .then((data) => {
      if (addButton && spinner) {
        addButton.disabled = false;
        spinner.classList.add('d-none');
      }
      if (data.success) {
        showAlert('success', I18N.sectionAdded);
        const modalEl = document.getElementById('addSectionModal');
        const instance =
          modalEl ? bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl) : null;
        if (instance) instance.hide();
        resetModalState();
        setTimeout(() => window.location.reload(), 800);
      } else {
        showAlert('error', data.message || I18N.failedAdd);
      }
    })
    .catch(() => {
      if (addButton && spinner) {
        addButton.disabled = false;
        spinner.classList.add('d-none');
      }
      showAlert('error', I18N.errorAdd);
    });
}

function resetModalState() {
  selectedTemplateId = null;
  selectedTemplateName = null;
  const info = document.getElementById('selectedTemplateInfo');
  const btn = document.getElementById('addSectionButton');
  const input = document.getElementById('customSectionName');
  if (info) info.style.display = 'none';
  if (btn) btn.style.display = 'none';
  if (input) input.value = '';
  document.querySelectorAll('.template-selection-card').forEach((c) => c.classList.remove('selected'));
}

function resetTemplateSelection() { resetModalState(); }

// Search / Filter in add modal
function initializeTemplateSearch() {
  const searchInput = document.getElementById('sectionTemplateSearch');
  const filterSelect = document.getElementById('sectionTemplateFilter');
  if (searchInput) searchInput.addEventListener('input', filterTemplates);
  if (filterSelect) filterSelect.addEventListener('change', filterTemplates);
}
function filterTemplates() {
  const searchTerm = (document.getElementById('sectionTemplateSearch')?.value || '').toLowerCase();
  const selectedType = (document.getElementById('sectionTemplateFilter')?.value || '').toLowerCase();
  document.querySelectorAll('.section-template-item').forEach((item) => {
    const templateName = (item.dataset.templateName || '').toLowerCase();
    const templateType = (item.dataset.templateType || '').toLowerCase();
    const show =
      (!searchTerm || templateName.includes(searchTerm)) &&
      (!selectedType || templateType.includes(selectedType));
    item.style.display = show ? 'block' : 'none';
  });
}

// ===================== Actions: Delete / Toggle =====================
function deleteSection(id) {
  if (!confirm(I18N.confirmRemove)) return;
  showAlert('info', I18N.removing);

  api(URLS.deleteSection(id), { method: 'DELETE', headers: { 'Content-Type': 'application/json' } })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        pageData.sections = (pageData.sections || []).filter((s) => s.id !== id);
        showAlert('success', I18N.removedOk);
        setTimeout(() => window.location.reload(), 800);
      } else {
        showAlert('error', data.message || I18N.failedRemove);
      }
    })
    .catch(() => showAlert('error', I18N.errorRemove));
}

function toggleActive(id) {
  if (!confirm(I18N.confirmToggle)) return;
  showAlert('info', I18N.updatingStatus);

  api(URLS.toggleStatus(id), { method: 'POST', headers: { 'Content-Type': 'application/json' } })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        const s = (pageData.sections || []).find((x) => x.id === id);
        if (s) { s.status = data.status; s.is_active = data.status; }
        const statusText = data.status ? I18N.active : I18N.inactive;
        showAlert('success', `${I18N.statusNow} ${statusText}`);
        setTimeout(() => window.location.reload(), 800);
      } else {
        showAlert('error', data.message || I18N.failedUpdate || 'Failed to update section status');
      }
    })
    .catch(() => showAlert('error', I18N.errorUpdate || 'An error occurred while updating status'));
}

// ===================== Sortable (Drag & Drop) =====================
function initializeSortable() {
  const sortableContainer = document.getElementById('sortable-sections');
  if (!sortableContainer || typeof Sortable === 'undefined') return;

  new Sortable(sortableContainer, {
    animation: 200,
    easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
    ghostClass: 'sortable-ghost',
    chosenClass: 'sortable-chosen',
    dragClass: 'sortable-drag',
    forceFallback: false,
    fallbackTolerance: 5,
    onStart() { document.body.style.cursor = 'grabbing'; },
    onEnd(evt) {
      document.body.style.cursor = '';
      document.querySelectorAll('.alert-danger').forEach((a) => a.remove());
      if (evt.newIndex === evt.oldIndex) return;

      const items = Array.from(sortableContainer.children);
      const sectionOrders = items.map((item, idx) => ({
        id: parseInt(item.getAttribute('data-section-id'), 10),
        order: idx + 1,
      }));
      updateMultipleSectionsOrder(sectionOrders);
    },
  });
}

function updateMultipleSectionsOrder(sectionOrders) {
  updateOrderIndicators();
  api(URLS.reorder, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ section_orders: sectionOrders }),
  })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        showAlert('success', I18N.orderUpdated, 2000);
        sectionOrders.forEach((u) => {
          const s = (pageData.sections || []).find((x) => x.id === u.id);
          if (s) s.sort_order = u.order;
        });
      }
    })
    .catch(() => {
      // silent
      console.log('Order update sent (silent).');
    });
}

function updateOrderIndicators() {
  document.querySelectorAll('.section-item').forEach((item, index) => {
    const badge = item.querySelector('.order-indicator');
    if (badge) badge.textContent = index + 1;
    item.setAttribute('data-sort-order', index + 1);
  });
}

// ===================== Misc =====================
function savePage() {
  if (confirm(I18N.confirmSaveAll)) {
    showAlert('success', I18N.changesSaved);
    // لا يوجد API مخصص للحفظ العام هنا؛ العودة للقائمة
    setTimeout(() => { window.location.href = '/admin/pages'; }, 1200);
  }
}

// ===================== Init =====================
document.addEventListener('DOMContentLoaded', () => {
  console.log('Admin Page Sections (no edit modal) loaded');

  // نظف أي Alerts خطأ قديمة
  document.querySelectorAll('.alert-danger').forEach((a) => a.remove());

  // Sortable
  initializeSortable();

  // Bootstrap dropdowns
  setTimeout(() => {
    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach((el) => {
      new bootstrap.Dropdown(el, {
        popperConfig: {
          strategy: 'fixed',
          modifiers: [{ name: 'preventOverflow', options: { boundary: document.body } }],
        },
      });
    });
  }, 300);

  // تصحيح تموضع المينيو
  document.addEventListener('show.bs.dropdown', function (e) {
    const menu = e.target.querySelector('.dropdown-menu');
    if (!menu) return;
    menu.classList.remove('dropdown-menu-up', 'dropdown-menu-end');
    setTimeout(() => {
      const btnRect = e.target.querySelector('[data-bs-toggle="dropdown"]').getBoundingClientRect();
      const menuRect = menu.getBoundingClientRect();
      const cardRect = e.target.closest('.component-card')?.getBoundingClientRect();
      if (btnRect.bottom + menuRect.height > window.innerHeight - 20) menu.classList.add('dropdown-menu-up');
      if (cardRect && btnRect.left + menuRect.width > cardRect.right) menu.classList.add('dropdown-menu-end');
      if (document.dir === 'rtl' || document.documentElement.dir === 'rtl') {
        if (cardRect && btnRect.right - menuRect.width < cardRect.left) menu.classList.remove('dropdown-menu-end');
      }
    }, 10);
  });
  document.addEventListener('hide.bs.dropdown', (e) => {
    const menu = e.target.querySelector('.dropdown-menu');
    if (menu) menu.classList.remove('dropdown-menu-up');
  });

  // صور المعاينة أعلى الكارد
  const imgs = document.querySelectorAll('.card-top-image img');
  imgs.forEach((img) => {
    img.addEventListener('error', function () {
      this.style.display = 'none';
      const fb = this.parentElement.querySelector('.card-top-fallback');
      if (fb) fb.style.display = 'flex';
    });
    img.addEventListener('load', function () {
      const fb = this.parentElement.querySelector('.card-top-fallback');
      if (fb) fb.style.display = 'none';
    });
  });
  setTimeout(() => {
    imgs.forEach((img) => {
      if (!img.src || img.src === '' || img.src === window.location.href) {
        img.dispatchEvent(new Event('error'));
      }
    });
  }, 100);

  // بحث/فلترة القوالب
  initializeTemplateSearch();

  // عند إغلاق مودال إضافة سكشن، نرجّع الحالة الافتراضية
  const addSectionModal = document.getElementById('addSectionModal');
  if (addSectionModal) {
    addSectionModal.addEventListener('hidden.bs.modal', () => resetModalState());
  }
});

// --------- Expose to window (لو محتاج تستخدمها Inline) ---------
window.addSection = addSection;
window.selectSectionTemplate = selectSectionTemplate;
window.addSelectedSection = addSelectedSection;
window.resetTemplateSelection = resetTemplateSelection;
window.deleteSection = deleteSection;
window.toggleActive = toggleActive;
window.savePage = savePage;
