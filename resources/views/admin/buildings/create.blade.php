@extends('admin.layouts.app')

@section('content')

<style>
    .container-fluid, .container-fluid label, .container-fluid input, .container-fluid textarea, .container-fluid h4 {
        color: #000 !important;
    }
    .breadcrumb__title h2 {
        color: #000 !important;
    }
</style>

<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb__content">
                <div class="breadcrumb__content__left">
                    <div class="breadcrumb__title">
                        <h2>Add Building Information</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="gallery__area bg-style">
                <div class="form-vertical__item bg-style">

                    <form method="POST" action="{{ route('admin.building.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Section 1: Basic Info --}}
                        <div class="input__group mb-25">
                            <label>Building Name <span class="text-danger">*</span></label>
                            <input class="text-black" type="text" name="name" placeholder="Farida Villa" value="{{ old('name') }}" required>
                        </div>

                        <div class="input__group mb-25">
                            <label>Building Logo</label>
                            <input type="file" name="logo" class="form-control" accept="image/jpeg,image/png,image/jpg">
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="input__group mb-25">
                                    <label>No of Floor <span class="text-danger">*</span></label>
                                    <input class="text-black" type="number" name="no_of_floor" placeholder="e.g. 6" value="{{ old('no_of_floor') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="input__group mb-25">
                            <label>Building Address <span class="text-danger">*</span></label>
                            <textarea class="text-black" name="address" rows="3" placeholder="House: 15, Road: 6/A, Sector: 12, Uttara, Dhaka 1230" required>{{ old('address') }}</textarea>
                        </div>

                        <hr class="mb-25">
                        <h4 class="mb-20">Important Documents</h4>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>Holding Tax Number</label>
                                    <input class="text-black" type="text" name="holding_tax_number" value="{{ old('holding_tax_number') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>Holding Tax Clearance Up to</label>
                                    <input type="date" name="holding_tax_clearance_up_to" value="{{ old('holding_tax_clearance_up_to') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input__group mb-25">
                                    <label>Holding Tax Document (Zip/PDF/Image)</label>
                                    <input type="file" name="holding_tax_document" class="form-control" accept=".pdf,.jpg,.png,.zip">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>Khajna Clearance Up to</label>
                                    <input type="date" name="khajna_clearance_up_to" value="{{ old('khajna_clearance_up_to') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>Khajna Document (Zip/PDF/Image)</label>
                                    <input type="file" name="khajna_document" class="form-control" accept=".pdf,.jpg,.png,.zip">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="input__group mb-25">
                                    <label>Dolil Document (Zip/PDF/Image)</label>
                                    <input type="file" name="dolil_document" class="form-control" accept=".pdf,.jpg,.png,.zip">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input__group mb-25">
                                    <label>Noksha Document (Zip/PDF/Image)</label>
                                    <input type="file" name="noksha_document" class="form-control" accept=".pdf,.jpg,.png,.zip">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input__group mb-25">
                                    <label>Mutation Document (Zip/PDF/Image)</label>
                                    <input type="file" name="mutation_document" class="form-control" accept=".pdf,.jpg,.png,.zip">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="input__group mb-25">
                                    <label>Alert Notes</label>
                                    <textarea name="alert_notes" rows="2" placeholder="Any alert notes">{{ old('alert_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="mb-25">
                        <h4 class="mb-20">Security Information</h4>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input__group mb-25">
                                    <label>No of Security</label>
                                    <input type="number" name="no_of_security" id="no_of_security" value="{{ old('no_of_security', 1) }}" min="1" oninput="renderSecurityCards()">
                                </div>
                            </div>
                        </div>

                        <div id="security_cards"></div>

                        {{-- Submit --}}
                        <div class="input__button mt-3">
                            <button type="submit" class="btn btn-blue">
                                Save Building Information
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
    function renderSecurityCards() {
        const n = parseInt(document.getElementById('no_of_security').value) || 0;
        const wrap = document.getElementById('security_cards');
        wrap.innerHTML = '';
        if (n <= 0) return;
        
        for (let i = 0; i < n; i++) {
            wrap.insertAdjacentHTML('beforeend', `
            <div class="card mb-4" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                <div class="card-header" style="background-color: #f8fafc; font-weight: 700; padding: 15px; border-bottom: 1px solid #e2e8f0;">
                    Security Member ${i + 1}
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input__group mb-25">
                                <label>Security Name <span class="text-danger">*</span></label>
                                <input class="text-black" type="text" name="sec_name[]" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input__group mb-25">
                                <label>Contact Number <span class="text-danger">*</span></label>
                                <input class="text-black" type="text" name="sec_contact[]" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input__group mb-25">
                                <label>Security Image</label>
                                <input type="file" name="sec_image[]" class="form-control" accept="image/jpeg,image/png,image/jpg">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group mb-25">
                                <label>Father's Name</label>
                                <input class="text-black" type="text" name="sec_father_name[]">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group mb-25">
                                <label>Mother's Name</label>
                                <input class="text-black" type="text" name="sec_mother_name[]">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group mb-25">
                                <label>NID Number</label>
                                <input class="text-black" type="text" name="sec_nid_number[]">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group mb-25">
                                <label>NID Document</label>
                                <input type="file" name="sec_nid_document[]" class="form-control" accept=".pdf,.jpg,.png">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group mb-25">
                                <label>Birth Certificate Number</label>
                                <input class="text-black" type="text" name="sec_birth_cert_number[]">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input__group mb-25">
                                <label>Birth Certificate Document</label>
                                <input type="file" name="sec_birth_cert_document[]" class="form-control" accept=".pdf,.jpg,.png">
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