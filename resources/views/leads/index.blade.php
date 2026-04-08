@extends('layouts.app')

@section('title', 'Leads Dashboard')
@section('heading', 'Leads Management')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row gap-2 justify-content-between mb-3">
                <div class="d-flex gap-2 flex-wrap">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by name, email, company">
                    <select id="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary" id="addLeadBtn">Add Lead</button>
            </div>

            <div id="alertBox"></div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Source</th>
                            <th width="140">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="leadsTableBody"></tbody>
                </table>
            </div>
            <div id="paginationWrapper" class="d-flex justify-content-center"></div>
        </div>
    </div>

    <div class="modal fade" id="leadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="leadForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leadModalTitle">Add Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="leadId">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                        <div class="text-danger small" data-error="name"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                        <div class="text-danger small" data-error="email"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                        <div class="text-danger small" data-error="phone"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company</label>
                        <input type="text" class="form-control" id="company" name="company">
                        <div class="text-danger small" data-error="company"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="status_id" name="status_id">
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger small" data-error="status_id"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Source</label>
                        <select class="form-select" id="source_id" name="source_id">
                            <option value="">Select source</option>
                            @foreach ($sources as $source)
                                <option value="{{ $source->id }}">{{ $source->name }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger small" data-error="source_id"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="mb-3">Delete this lead?</p>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBtn">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            const leadModal = new bootstrap.Modal('#leadModal');
            const deleteModal = new bootstrap.Modal('#deleteModal');
            let deleteLeadId = null;
            let currentPage = 1;
            let searchTimer = null;

            function showAlert(message, type = 'success') {
                $('#alertBox').html(`<div class="alert alert-${type} alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`);
            }

            function clearErrors() {
                $('[data-error]').text('');
            }

            function renderTable(items) {
                if (!items.length) {
                    $('#leadsTableBody').html('<tr><td colspan="7" class="text-center text-muted py-4">No leads found.</td></tr>');
                    return;
                }

                const rows = items.map((lead) => `
                    <tr>
                        <td>${lead.name}</td>
                        <td>${lead.email}</td>
                        <td>${lead.phone ?? '-'}</td>
                        <td>${lead.company ?? '-'}</td>
                        <td>${lead.status?.name ?? '-'}</td>
                        <td>${lead.source?.name ?? '-'}</td>
                        <td>
                            <button class="btn btn-sm btn-warning editBtn" data-id="${lead.id}">Edit</button>
                            <button class="btn btn-sm btn-danger deleteBtn" data-id="${lead.id}">Delete</button>
                        </td>
                    </tr>
                `).join('');

                $('#leadsTableBody').html(rows);
            }

            function renderPagination(meta) {
                if (!meta || meta.last_page <= 1) {
                    $('#paginationWrapper').html('');
                    return;
                }

                let html = '<nav><ul class="pagination mb-0">';
                const prevDisabled = meta.current_page === 1 ? ' disabled' : '';
                const nextDisabled = meta.current_page === meta.last_page ? ' disabled' : '';

                html += `<li class="page-item${prevDisabled}"><a class="page-link" href="#" data-page="${meta.current_page - 1}">Previous</a></li>`;

                for (let i = 1; i <= meta.last_page; i++) {
                    const active = i === meta.current_page ? ' active' : '';
                    html += `<li class="page-item${active}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }

                html += `<li class="page-item${nextDisabled}"><a class="page-link" href="#" data-page="${meta.current_page + 1}">Next</a></li>`;
                html += '</ul></nav>';

                $('#paginationWrapper').html(html);
            }

            function loadLeads(page = 1) {
                currentPage = page;
                $.get('{{ route('leads.index') }}', {
                    page,
                    search: $('#searchInput').val(),
                    status_id: $('#statusFilter').val()
                }, function (response) {
                    const paginated = response.leads;
                    renderTable(paginated.data);
                    renderPagination(paginated);
                });
            }

            $('#addLeadBtn').on('click', function () {
                clearErrors();
                $('#leadForm')[0].reset();
                $('#leadId').val('');
                $('#leadModalTitle').text('Add Lead');
                leadModal.show();
            });

            $('#searchInput').on('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    loadLeads(1);
                }, 300);
            });

            $('#statusFilter').on('change', function () {
                loadLeads(1);
            });

            $(document).on('click', '.editBtn', function () {
                const id = $(this).data('id');
                clearErrors();
                $.get(`/leads/${id}/edit`, function (response) {
                    const lead = response.lead;
                    $('#leadId').val(lead.id);
                    $('#name').val(lead.name);
                    $('#email').val(lead.email);
                    $('#phone').val(lead.phone);
                    $('#company').val(lead.company);
                    $('#status_id').val(lead.status_id);
                    $('#source_id').val(lead.source_id);
                    $('#leadModalTitle').text('Edit Lead');
                    leadModal.show();
                });
            });

            $('#leadForm').on('submit', function (e) {
                e.preventDefault();
                clearErrors();

                const id = $('#leadId').val();
                const url = id ? `/leads/${id}` : '/leads';
                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url,
                    method,
                    data: $(this).serialize(),
                    success: function (response) {
                        leadModal.hide();
                        showAlert(response.message, 'success');
                        loadLeads(currentPage);
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(function (field) {
                                $(`[data-error="${field}"]`).text(errors[field][0]);
                            });
                        } else {
                            showAlert('Something went wrong.', 'danger');
                        }
                    }
                });
            });

            $(document).on('click', '.deleteBtn', function () {
                deleteLeadId = $(this).data('id');
                deleteModal.show();
            });

            $('#confirmDeleteBtn').on('click', function () {
                if (!deleteLeadId) return;
                $.ajax({
                    url: `/leads/${deleteLeadId}`,
                    method: 'DELETE',
                    success: function (response) {
                        deleteModal.hide();
                        showAlert(response.message, 'success');
                        loadLeads(currentPage);
                        deleteLeadId = null;
                    }
                });
            });

            $(document).on('click', '.page-link', function (e) {
                e.preventDefault();
                const page = Number($(this).data('page'));
                if (!page || $(this).closest('.page-item').hasClass('disabled') || $(this).closest('.page-item').hasClass('active')) {
                    return;
                }

                loadLeads(page);
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            loadLeads(1);
        });
    </script>
@endsection
