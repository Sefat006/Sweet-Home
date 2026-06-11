@extends('admin.layouts.app')

@section('content')

<style>
    .container-fluid, .container-fluid label, .container-fluid input, .container-fluid textarea, .container-fluid h4 {
        color: #000 !important;
    }
    .breadcrumb__title h2 {
        color: white;
    }
</style>

<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>Edit Building Information</h2>
                    </div>
                </div>
                <div class="breadcrumb__content__right">
                    <a href="{{ route('admin.building.index', ['admin_id' => $building->user_id]) }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="gallery__area bg-style">
                <div class="form-vertical__item bg-style">

                    <form method="POST" action="{{ route('admin.building.update', $building->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Section 1: Basic Info --}}
                        <div class="input__group mb-25">
                            <label>Building Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" placeholder="Farida Villa" value="{{ old('name', $building->name) }}" required>
                        </div>

                        <div class="input__group mb-25">
                            <label>Building Logo</label>
                            <input type="file" name="logo" class="form-control" accept="image/jpeg,image/png,image/jpg">
                            @if($building->logo)
                                <small class="text-success">Current logo: {{ basename($building->logo) }}</small>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="input__group mb-25">
                                    <label>No of Floor <span class="text-danger">*</span></label>
                                    <input type="number" name="no_of_floor" placeholder="e.g. 6" value="{{ old('no_of_floor', $building->no_of_floor) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="input__group mb-25">
                            <label>Building Address <span class="text-danger">*</span></label>
                            <textarea name="address" rows="3" placeholder="House: 15, Road: 6/A, Sector: 12, Uttara, Dhaka 1230" required>{{ old('address', $building->address) }}</textarea>
                        </div>

                        <hr class="mb-25">
                        <h4 class="mb-20">Important Documents</h4>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>Holding Tax Number</label>
                                    <input type="text" name="holding_tax_number" value="{{ old('holding_tax_number', $building->holding_tax_number) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>Holding Tax Clearance Up to</label>
                                    <input type="date" name="holding_tax_clearance_up_to" value="{{ old('holding_tax_clearance_up_to', $building->holding_tax_clearance_up_to ? $building->holding_tax_clearance_up_to->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input__group mb-25">
                                    <label>Holding Tax Document (Zip/PDF/Image)</label>
                                    <input type="file" name="holding_tax_document" class="form-control" accept=".pdf,.jpg,.png,.zip">
                                    @if($building->holding_tax_document)
                                        <small class="text-success">Current file uploaded.</small>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>Khajna Clearance Up to</label>
                                    <input type="date" name="khajna_clearance_up_to" value="{{ old('khajna_clearance_up_to', $building->khajna_clearance_up_to ? $building->khajna_clearance_up_to->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>Khajna Document (Zip/PDF/Image)</label>
                                    <input type="file" name="khajna_document" class="form-control" accept=".pdf,.jpg,.png,.zip">
                                    @if($building->khajna_document)
                                        <small class="text-success">Current file uploaded.</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="input__group mb-25">
                                    <label>Dolil Document (Zip/PDF/Image)</label>
                                    <input type="file" name="dolil_document" class="form-control" accept=".pdf,.jpg,.png,.zip">
                                    @if($building->dolil_document)
                                        <small class="text-success">Current file uploaded.</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input__group mb-25">
                                    <label>Noksha Document (Zip/PDF/Image)</label>
                                    <input type="file" name="noksha_document" class="form-control" accept=".pdf,.jpg,.png,.zip">
                                    @if($building->noksha_document)
                                        <small class="text-success">Current file uploaded.</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input__group mb-25">
                                    <label>Mutation Document (Zip/PDF/Image)</label>
                                    <input type="file" name="mutation_document" class="form-control" accept=".pdf,.jpg,.png,.zip">
                                    @if($building->mutation_document)
                                        <small class="text-success">Current file uploaded.</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="input__group mb-25">
                                    <label>Alert Notes</label>
                                    <textarea name="alert_notes" rows="2" placeholder="Any alert notes">{{ old('alert_notes', $building->alert_notes) }}</textarea>
                                </div>
                            </div>

                            {{-- Bill Slip PDF Info --}}
                            <div class="col-md-12">
                                <hr class="mb-20 mt-5">
                                <h4 class="mb-20">Bill Slip Information (for PDF Export)</h4>
                            </div>
                            <div class="col-md-12">
                                <div class="input__group mb-25">
                                    <label>Online Bank Transfer Info <small class="text-muted">(shown on bill slip — one line per Enter)</small></label>
                                    <textarea name="bank_info" rows="4" placeholder="Prime Bank _Account Name: Rubaiyat Ferdous&#10;Account Number: 2125216028410&#10;Branch Name: Uttara Branch">{{ old('bank_info', $building->bank_info) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input__group mb-25">
                                    <label>Additional Contact Note <small class="text-muted">(optional — shown under phone on bill slip)</small></label>
                                    <input type="text" name="contact_note" value="{{ old('contact_note', $building->contact_note) }}" placeholder="e.g. Email:rf.sifat@gmail.com">
                                </div>
                            </div>
                        </div>

                        <hr class="mb-25">
                        <h4 class="mb-20">Security Information</h4>

                        @php
                            $currentNoSecurity = old('no_of_security', max(1, count($building->securities)));
                        @endphp

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>No of Security</label>
                                    <input type="number" name="no_of_security" id="no_of_security" value="{{ $currentNoSecurity }}" min="1" oninput="renderSecurityCards()">
                                </div>
                            </div>
                        </div>

                        <div id="security_cards"></div>

                        {{-- Submit --}}
                        <div class="input__button mt-3">
                            <button type="submit" class="btn btn-blue">
                                Update Building Information
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    const existingSecurities = @json($building->securities);

    function escHtml(str) {
        return String(str).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function renderSecurityCards() {
        const n = parseInt(document.getElementById('no_of_security').value) || 0;
        const wrap = document.getElementById('security_cards');
        wrap.innerHTML = '';
        if (n <= 0) return;
        
        for (let i = 0; i < n; i++) {
            const sec = existingSecurities[i] || {};
            
            wrap.insertAdjacentHTML('beforeend', `
            <div class="card mb-4" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                <div class="card-header" style="background-color: #f8fafc; font-weight: 700; padding: 15px; border-bottom: 1px solid #e2e8f0; color: #000;">
                    Security Member ${i + 1}
                </div>
                <div class="card-body" style="padding: 20px; color: #000;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input__group mb-25">
                                <label style="color: #000;">Security Name <span class="text-danger">*</span></label>
                                <input type="text" name="sec_name[]" value="${escHtml(sec.name || '')}" style="color: #000;" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input__group mb-25">
                                <label style="color: #000;">Contact Number <span class="text-danger">*</span></label>
                                <input type="text" name="sec_contact[]" value="${escHtml(sec.contact || '')}" style="color: #000;" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input__group mb-25">
                                <label style="color: #000;">Security Image</label>
                                <input type="file" name="sec_image[]" class="form-control" accept="image/jpeg,image/png,image/jpg">
                                <input type="hidden" name="old_sec_image[]" value="${sec.image || ''}">
                                ${sec.image ? '<small class="text-success">Current image uploaded.</small>' : ''}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group mb-25">
                                <label style="color: #000;">Father's Name</label>
                                <input type="text" name="sec_father_name[]" value="${escHtml(sec.father_name || '')}" style="color: #000;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group mb-25">
                                <label style="color: #000;">Mother's Name</label>
                                <input type="text" name="sec_mother_name[]" value="${escHtml(sec.mother_name || '')}" style="color: #000;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group mb-25">
                                <label style="color: #000;">NID Number</label>
                                <input type="text" name="sec_nid_number[]" value="${escHtml(sec.nid_number || '')}" style="color: #000;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group mb-25">
                                <label style="color: #000;">NID Document</label>
                                <input type="file" name="sec_nid_document[]" class="form-control" accept=".pdf,.jpg,.png">
                                <input type="hidden" name="old_sec_nid_document[]" value="${sec.nid_document || ''}">
                                ${sec.nid_document ? '<small class="text-success">Current NID uploaded.</small>' : ''}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group mb-25">
                                <label style="color: #000;">Birth Certificate Number</label>
                                <input type="text" name="sec_birth_cert_number[]" value="${escHtml(sec.birth_certificate_number || '')}" style="color: #000;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group mb-25">
                                <label style="color: #000;">Birth Certificate Document</label>
                                <input type="file" name="sec_birth_cert_document[]" class="form-control" accept=".pdf,.jpg,.png">
                                <input type="hidden" name="old_sec_birth_cert_document[]" value="${sec.birth_certificate_document || ''}">
                                ${sec.birth_certificate_document ? '<small class="text-success">Current certificate uploaded.</small>' : ''}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `);
        }
    }

    // Init on page load
    document.addEventListener('DOMContentLoaded', function() {
        renderSecurityCards();
    });
</script>
@endpush
