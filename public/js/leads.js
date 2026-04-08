document.addEventListener('DOMContentLoaded', () => {
    const filtersForm = document.getElementById('lead-filters-form');
    const tableWrapper = document.getElementById('leads-table-wrapper');
    const searchInput = document.getElementById('search-input');

    if (!filtersForm || !tableWrapper) {
        return;
    }

    const fetchFilteredLeads = async (url = null) => {
        const requestUrl = url ?? `${filtersForm.action}?${new URLSearchParams(new FormData(filtersForm)).toString()}`;

        const response = await fetch(requestUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            return;
        }

        const payload = await response.json();
        tableWrapper.innerHTML = payload.html;
        window.history.replaceState({}, '', requestUrl);
    };

    const debounce = (fn, delay = 350) => {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => fn(...args), delay);
        };
    };

    filtersForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        await fetchFilteredLeads();
    });

    filtersForm.addEventListener('change', async (event) => {
        if (event.target.matches('#status-select')) {
            await fetchFilteredLeads();
        }
    });

    searchInput?.addEventListener('input', debounce(async () => {
        await fetchFilteredLeads();
    }));

    document.addEventListener('click', async (event) => {
        const deleteButton = event.target.closest('.js-delete-lead');
        if (!deleteButton) {
            return;
        }

        const confirmed = window.confirm('Are you sure you want to delete this lead?');
        if (!confirmed) {
            return;
        }

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!token) {
            return;
        }

        const response = await fetch(deleteButton.dataset.url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        });

        if (!response.ok) {
            window.alert('Failed to delete the lead.');
            return;
        }

        const row = deleteButton.closest('tr');
        row?.remove();

        if (!tableWrapper.querySelector('tbody tr')) {
            await fetchFilteredLeads();
        }
    });

    tableWrapper.addEventListener('click', async (event) => {
        const pageLink = event.target.closest('.pagination a');
        if (!pageLink) {
            return;
        }

        event.preventDefault();
        await fetchFilteredLeads(pageLink.getAttribute('href'));
    });
});
