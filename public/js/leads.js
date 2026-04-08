document.addEventListener('DOMContentLoaded', () => {
    const filtersForm = document.getElementById('lead-filters-form');
    const tableWrapper = document.getElementById('leads-table-wrapper');
    const searchInput = document.getElementById('search-input');
    const statusSelect = document.getElementById('status-select');
    const loadingIndicator = document.getElementById('table-loading');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const toastContainer = document.getElementById('toast-container');

    const leadModal = document.getElementById('lead-form-modal');
    const deleteModal = document.getElementById('delete-confirm-modal');
    const openCreateButton = document.getElementById('open-create-modal');
    const leadForm = document.getElementById('lead-form');
    const leadFormSubmit = document.getElementById('lead-form-submit');
    const deleteLeadName = document.getElementById('delete-lead-name');
    const confirmDeleteButton = document.getElementById('confirm-delete-btn');

    if (!filtersForm || !tableWrapper || !csrfToken) {
        return;
    }

    let currentDeleteUrl = null;

    const openModal = (modal) => {
        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');

        const panel = modal.querySelector('[data-modal-panel]');
        requestAnimationFrame(() => {
            panel?.classList.remove('scale-95', 'opacity-0');
            panel?.classList.add('scale-100', 'opacity-100');
        });
    };

    const closeModal = (modal) => {
        if (!modal) return;

        const panel = modal.querySelector('[data-modal-panel]');
        panel?.classList.remove('scale-100', 'opacity-100');
        panel?.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
        }, 180);
    };

    const showToast = (message, type = 'success') => {
        if (!toastContainer || !message) return;

        const palette = type === 'error'
            ? 'bg-red-50 border-red-200 text-red-700'
            : 'bg-emerald-50 border-emerald-200 text-emerald-700';

        const toast = document.createElement('div');
        toast.className = `rounded-lg border px-4 py-3 text-sm shadow-sm transition ${palette}`;
        toast.textContent = message;

        toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-x-2');
            setTimeout(() => toast.remove(), 250);
        }, 2600);
    };

    const setTableLoading = (isLoading) => {
        if (!loadingIndicator) return;
        loadingIndicator.classList.toggle('hidden', !isLoading);
    };

    const fetchFilteredLeads = async (url = null) => {
        const requestUrl = url ?? `${filtersForm.action}?${new URLSearchParams(new FormData(filtersForm)).toString()}`;
        setTableLoading(true);
        tableWrapper.classList.add('opacity-50');

        const response = await fetch(requestUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
        });

        if (!response.ok) {
            setTableLoading(false);
            tableWrapper.classList.remove('opacity-50');
            return;
        }

        const payload = await response.json();
        tableWrapper.innerHTML = payload.html;
        window.history.replaceState({}, '', requestUrl);

        setTableLoading(false);
        tableWrapper.classList.remove('opacity-50');
    };

    const debounce = (fn, delay = 300) => {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => fn(...args), delay);
        };
    };

    const clearErrors = () => {
        document.querySelectorAll('[data-error-for]').forEach((errorEl) => {
            errorEl.classList.add('hidden');
            errorEl.textContent = '';
        });
    };

    const setErrors = (errors = {}) => {
        clearErrors();
        Object.entries(errors).forEach(([field, messages]) => {
            const errorEl = leadForm?.querySelector(`[data-error-for="${field}"]`);
            if (!errorEl || !messages.length) return;
            errorEl.textContent = messages[0];
            errorEl.classList.remove('hidden');
        });
    };

    const resetLeadForm = () => {
        leadForm?.reset();
        clearErrors();
        document.getElementById('lead-id').value = '';
        document.getElementById('lead-form-method').value = 'POST';
        leadForm.dataset.action = leadForm.dataset.createUrl;
        leadFormSubmit.textContent = 'Create Lead';
    };

    const openCreateModal = () => {
        resetLeadForm();
        openModal(leadModal);
    };

    const openEditModal = (button) => {
        resetLeadForm();
        document.getElementById('lead-id').value = button.dataset.id || '';
        document.getElementById('lead-form-method').value = 'PUT';
        leadForm.dataset.action = button.dataset.updateUrl || '/leads';
        leadFormSubmit.textContent = 'Update Lead';

        document.getElementById('lead-name').value = button.dataset.name || '';
        document.getElementById('lead-email').value = button.dataset.email || '';
        document.getElementById('lead-phone').value = button.dataset.phone || '';
        document.getElementById('lead-company').value = button.dataset.company || '';
        document.getElementById('lead-status').value = button.dataset.status || 'new';
        document.getElementById('lead-notes').value = button.dataset.notes || '';

        openModal(leadModal);
    };

    const submitLeadForm = async (event) => {
        event.preventDefault();
        clearErrors();

        const method = document.getElementById('lead-form-method').value;
        const action = leadForm.dataset.action || '/leads';
        const formData = new FormData(leadForm);

        if (method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        leadFormSubmit.disabled = true;
        leadFormSubmit.classList.add('opacity-70', 'cursor-not-allowed');

        const response = await fetch(action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
            body: formData,
        });

        if (response.status === 422) {
            const payload = await response.json();
            setErrors(payload.errors || {});
            leadFormSubmit.disabled = false;
            leadFormSubmit.classList.remove('opacity-70', 'cursor-not-allowed');
            return;
        }

        if (!response.ok) {
            showToast('Something went wrong. Please try again.', 'error');
            leadFormSubmit.disabled = false;
            leadFormSubmit.classList.remove('opacity-70', 'cursor-not-allowed');
            return;
        }

        const payload = await response.json();
        closeModal(leadModal);
        await fetchFilteredLeads();
        showToast(payload.message || 'Lead saved successfully.');

        leadFormSubmit.disabled = false;
        leadFormSubmit.classList.remove('opacity-70', 'cursor-not-allowed');
    };

    const confirmDelete = (button) => {
        currentDeleteUrl = button.dataset.url;
        deleteLeadName.textContent = button.dataset.name || 'this lead';
        openModal(deleteModal);
    };

    const performDelete = async () => {
        if (!currentDeleteUrl) return;
        confirmDeleteButton.disabled = true;
        confirmDeleteButton.textContent = 'Deleting...';

        const response = await fetch(currentDeleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
        });

        confirmDeleteButton.disabled = false;
        confirmDeleteButton.textContent = 'Delete';

        if (!response.ok) {
            showToast('Failed to delete lead.', 'error');
            return;
        }

        closeModal(deleteModal);
        await fetchFilteredLeads();
        const payload = await response.json();
        showToast(payload.message || 'Lead deleted successfully.');
    };

    searchInput?.addEventListener('input', debounce(async () => {
        await fetchFilteredLeads();
    }));

    statusSelect?.addEventListener('change', async () => {
        await fetchFilteredLeads();
    });

    filtersForm.addEventListener('submit', (event) => {
        event.preventDefault();
    });

    openCreateButton?.addEventListener('click', openCreateModal);
    leadForm?.addEventListener('submit', submitLeadForm);
    confirmDeleteButton?.addEventListener('click', performDelete);

    document.addEventListener('click', (event) => {
        const closeButton = event.target.closest('[data-close-modal]');
        if (closeButton) {
            const modalId = closeButton.getAttribute('data-close-modal');
            closeModal(document.getElementById(modalId));
            return;
        }

        const overlay = event.target.closest('[data-modal-overlay]');
        if (overlay) {
            const modal = overlay.closest('[id]');
            closeModal(modal);
            return;
        }

        const editButton = event.target.closest('.js-edit-lead');
        if (editButton) {
            openEditModal(editButton);
            return;
        }

        const deleteButton = event.target.closest('.js-delete-lead');
        if (deleteButton) {
            confirmDelete(deleteButton);
            return;
        }

        const pageLink = event.target.closest('.js-page-link');
        if (!pageLink) {
            return;
        }

        event.preventDefault();
        fetchFilteredLeads(pageLink.getAttribute('href'));
    });

    const initialToast = document.getElementById('initial-toast');
    if (initialToast?.dataset.message) {
        showToast(initialToast.dataset.message);
    }
});
