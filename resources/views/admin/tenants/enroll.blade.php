@extends('admin.layouts.app')

@push('styles')
<style>
    :root {
        --pf-accent: #2563eb;
        --pf-accent-lt: #eff6ff;
        --pf-danger: #dc2626;
        --pf-border: #e2e8f0;
        --pf-label: #374151;
        --pf-muted: #6b7280;
        --pf-bg: #f8fafc;
        --pf-card: #ffffff;
        --pf-radius: 10px;
        --pf-shadow: 0 1px 4px rgba(0, 0, 0, .07);
    }
    .pf-file {
        border: 2px dashed var(--pf-border);
        border-radius: 8px;
        padding: 13px 16px;
        cursor: pointer;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        transition: border-color .15s, background .15s;
        background: rgba(255,255,255,0.05);
        min-height: 56px;
    }
    .pf-file:hover { border-color: var(--pf-accent); background: rgba(37,99,235,.1); }
    .pf-file.dragover { border-color: var(--pf-accent); background: rgba(37,99,235,.15); }
    .pf-file__icon {
        width: 36px; height: 36px; border-radius: 8px;
        background: rgba(37,99,235,.2);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: background .15s; margin-top: 2px;
    }
    .pf-file:hover .pf-file__icon { background: rgba(37,99,235,.3); }
    .pf-file__icon svg { width: 17px; height: 17px; stroke: var(--pf-accent); }
    .pf-file__text { flex: 1; min-width: 0; }
    .pf-file__cta { font-size: .8rem; font-weight: 600; color: var(--pf-accent); line-height: 1.4; }
    .pf-file__hint { font-size: .72rem; color: #999; line-height: 1.3; margin-top: 2px; }
    .pf-file__existing { font-size: .73rem; color: #16a34a; font-weight: 500; margin-top: 2px; }
    .pf-file__names { margin-top: 6px; display: flex; flex-wrap: wrap; gap: 5px; }
    .pf-file__tag {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(37,99,235,.18); border-radius: 5px;
        padding: 2px 8px; font-size: .72rem; color: #93c5fd; max-width: 200px;
    }
    .pf-file__tag span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .pf-file__tag button {
        background: none; border: none; color: #f87171; font-size: .75rem;
        cursor: pointer; padding: 0; line-height: 1; flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2 style="color:white !important;">Enroll Tenant - {{ $flat->flat_name }}</h2>
                        <p style="color:white !important;" class="mb-0">Building: {{ $building->name }}</p>
                    </div>
                </div>
                <div class="breadcrumb__content__right d-flex gap-2">
                    <a href="{{ route('admin.tenants.index', [$building->id, $flat->id]) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card bg-style mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Step 1: Search Existing Tenant or Create New</h5>
                    <a href="{{ route('admin.tenants.create', [$building->id, $flat->id]) }}" class="btn btn-blue">
                        <i class="fa-solid fa-user-plus"></i> Create New Tenant
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tenants.search', [$building->id, $flat->id]) }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="query" class="form-control" placeholder="Search by name, phone, or NID..." value="{{ $query ?? '' }}" required>
                            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-search"></i> Search</button>
                            <a href="{{ route('admin.tenants.enroll', [$building->id, $flat->id]) }}" class="btn btn-secondary">Clear</a>
                        </div>
                    </form>

                    @if(isset($query))
                        <h6 class="mb-3">Search Results for "{{ $query }}"</h6>
                    @else
                        <h6 class="mb-3">All Tenants (Unassigned First)</h6>
                    @endif

                    @if($tenants && $tenants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered text-white">
                                <thead>
                                    <tr class="text-black">
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>NID</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tenants as $tenant)
                                        @php
                                            $isAssigned = isset($tenant->is_assigned) ? ($tenant->is_assigned > 0) : $tenant->flatTenants()->where('status', 'active')->exists();
                                        @endphp
                                        <tr class="text-black">
                                            <td>{{ $tenant->name }}</td>
                                            <td>{{ $tenant->phone }}</td>
                                            <td>{{ $tenant->nid_number ?? 'N/A' }}</td>
                                            <td>
                                                @if($isAssigned)
                                                    <span class="badge bg-secondary">Assigned</span>
                                                @else
                                                    <span class="badge bg-success">Available</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#assignModal{{ $tenant->id }}">
                                                    Assign to {{ $flat->flat_name }}
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Assign Modal -->
                                        <div class="modal fade" id="assignModal{{ $tenant->id }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $tenant->id }}" aria-hidden="true">
                                          <div class="modal-dialog modal-lg">
                                            <div class="modal-content bg-style">
                                              <form action="{{ route('admin.tenants.assign', [$building->id, $flat->id]) }}" method="POST" enctype="multipart/form-data">
                                                  @csrf
                                                  <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="assignModalLabel{{ $tenant->id }}">Assign Tenant: {{ $tenant->name }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                      <div class="row">
                                                          <div class="col-md-6 mb-3">
                                                              <label class="form-label">Start Date *</label>
                                                              <input type="date" name="start_date" class="form-control" required>
                                                          </div>
                                                          <div class="col-md-6 mb-3">
                                                              <label class="form-label">Advance Amount</label>
                                                              <input type="number" step="0.01" name="advance_amount" class="form-control" placeholder="0.00">
                                                          </div>
                                                          <div class="col-md-4 col-sm-12 mb-3">
                                                              <label class="form-label">Advance Document</label>
                                                              @include('admin.tenants.partials._multifile', ['fieldId' => 'f_advance_document_'.$tenant->id, 'fieldName' => 'advance_document[]', 'existing' => null])
                                                          </div>
                                                          <div class="col-md-4 col-sm-12 mb-3">
                                                              <label class="form-label">Agreement Document</label>
                                                              @include('admin.tenants.partials._multifile', ['fieldId' => 'f_agreement_document_'.$tenant->id, 'fieldName' => 'agreement_document[]', 'existing' => null])
                                                          </div>
                                                          <div class="col-md-4 col-sm-12 mb-3">
                                                              <label class="form-label">Police Form Document</label>
                                                              @include('admin.tenants.partials._multifile', ['fieldId' => 'f_police_form_document_'.$tenant->id, 'fieldName' => 'police_form_document[]', 'existing' => null])
                                                          </div>
                                                          <div class="col-md-4 col-sm-12 mb-3">
                                                              <label class="form-label">Notice Document</label>
                                                              @include('admin.tenants.partials._multifile', ['fieldId' => 'f_notice_document_'.$tenant->id, 'fieldName' => 'notice_document[]', 'existing' => null])
                                                          </div>
                                                          <div class="col-md-4 col-sm-12 mb-3">
                                                              <label class="form-label">House Rent Copy</label>
                                                              @include('admin.tenants.partials._multifile', ['fieldId' => 'f_house_rent_copy_'.$tenant->id, 'fieldName' => 'house_rent_copy[]', 'existing' => null])
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Confirm Assignment</button>
                                                  </div>
                                              </form>
                                            </div>
                                          </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @if(isset($query))
                            <div class="alert alert-warning">No existing tenants found matching your query.</div>
                        @else
                            <div class="alert alert-warning">No tenants found.</div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var pfStores = {}; // fieldId → DataTransfer

function pfInitStore(fieldId) {
    if (!pfStores[fieldId]) pfStores[fieldId] = new DataTransfer();
}

function pfAddFiles(fieldId, newFiles) {
    pfInitStore(fieldId);
    var dt = pfStores[fieldId];
    for (var i = 0; i < newFiles.length; i++) dt.items.add(newFiles[i]);
    pfSync(fieldId);
}

function pfRemoveFile(fieldId, index) {
    pfInitStore(fieldId);
    var old = pfStores[fieldId];
    var fresh = new DataTransfer();
    for (var i = 0; i < old.files.length; i++) {
        if (i !== index) fresh.items.add(old.files[i]);
    }
    pfStores[fieldId] = fresh;
    pfSync(fieldId);
}

function pfSync(fieldId) {
    var input   = document.getElementById(fieldId);
    var nameBox = document.getElementById(fieldId + '_names');
    if (!input || !nameBox) return;
    var dt = pfStores[fieldId];
    input.files = dt.files;
    // Render tags
    nameBox.innerHTML = '';
    for (var i = 0; i < dt.files.length; i++) {
        (function(idx, fname){
            var tag = document.createElement('span');
            tag.className = 'pf-file__tag';
            tag.innerHTML = '<span title="'+fname+'">'+fname+'</span>';
            var btn = document.createElement('button');
            btn.type = 'button'; btn.textContent = '✕';
            btn.onclick = function(){ pfRemoveFile(fieldId, idx); };
            tag.appendChild(btn);
            nameBox.appendChild(tag);
        })(i, dt.files[i].name);
    }
}

function pfZoneDragOver(e, el) {
    e.preventDefault(); e.stopPropagation();
    el.classList.add('dragover');
}
function pfZoneDragLeave(e, el) {
    e.preventDefault(); e.stopPropagation();
    el.classList.remove('dragover');
}
function pfZoneDrop(e, el, fieldId) {
    e.preventDefault(); e.stopPropagation();
    el.classList.remove('dragover');
    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
        pfAddFiles(fieldId, e.dataTransfer.files);
    }
}
function pfZoneChange(input, fieldId) {
    if (input.files && input.files.length > 0) {
        pfAddFiles(fieldId, input.files);
    }
}
</script>
@endpush
