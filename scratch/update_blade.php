<?php

function process($file) {
    $content = file_get_contents($file);
    
    // Add Religion and Nationality
    $extraHtml = '
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Religion</label>
                                <input type="text" name="religion" class="form-control" value="{{ old(\'religion\', $tenant->religion ?? \'\') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nationality</label>
                                <input type="text" name="nationality" class="form-control" value="{{ old(\'nationality\', $tenant->nationality ?? \'\') }}">
                            </div>
    ';
    
    $content = preg_replace('/(<div class="col-md-4 mb-3">\s*<label class="form-label">Blood Group<\/label>.*?<\/div>)/is', "$1$extraHtml", $content);

    // Replace marital status to include JS trigger
    $content = str_replace('<select name="marital_status" class="form-control">', '<select name="marital_status" class="form-control" id="marital_status" onchange="toggleSpouse()">', $content);

    // Spouse HTML
    $spouseHtml = '
                            <!-- Spouse Section -->
                            <div class="col-12" id="spouse_section" style="display: none;">
                                <h5 class="mt-3 mb-2 text-white border-bottom pb-1">Spouse Information</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Spouse Name</label>
                                        <input type="text" name="spouse_name" class="form-control" value="{{ old(\'spouse_name\', $tenant->spouse_name ?? \'\') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Contact Number</label>
                                        <input type="text" name="spouse_contact_number" class="form-control" value="{{ old(\'spouse_contact_number\', $tenant->spouse_contact_number ?? \'\') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Father Name</label>
                                        <input type="text" name="spouse_father_name" class="form-control" value="{{ old(\'spouse_father_name\', $tenant->spouse_father_name ?? \'\') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Mother Name</label>
                                        <input type="text" name="spouse_mother_name" class="form-control" value="{{ old(\'spouse_mother_name\', $tenant->spouse_mother_name ?? \'\') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Blood Group</label>
                                        <input type="text" name="spouse_blood_group" class="form-control" value="{{ old(\'spouse_blood_group\', $tenant->spouse_blood_group ?? \'\') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" name="spouse_date_of_birth" class="form-control" value="{{ old(\'spouse_date_of_birth\', $tenant->spouse_date_of_birth ?? \'\') }}">
                                    </div>
                                </div>
                            </div>
    ';
    
    // Insert spouse section after marital status block
    $content = preg_replace('/(<div class="col-md-4 mb-3">\s*<label class="form-label">Marital Status<\/label>.*?<\/div>)/is', "$1$spouseHtml", $content);
    
    // Passport & Driving Licence & Occupation details
    $docsHtml = '
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Passport Number</label>
                                <input type="text" name="passport_number" class="form-control" value="{{ old(\'passport_number\', $tenant->passport_number ?? \'\') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Passport Expiry</label>
                                <input type="date" name="passport_expiry" class="form-control" value="{{ old(\'passport_expiry\', $tenant->passport_expiry ?? \'\') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Passport Document</label>
                                @if(isset($tenant) && $tenant->passport_document)
                                    <a href="{{ asset(\'storage/\'.$tenant->passport_document) }}" target="_blank" class="d-block mb-1"><i class="fa-solid fa-file"></i> View Current</a>
                                @endif
                                <input type="file" name="passport_document" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Driving Licence No</label>
                                <input type="text" name="driving_licence_number" class="form-control" value="{{ old(\'driving_licence_number\', $tenant->driving_licence_number ?? \'\') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Licence Expiry</label>
                                <input type="date" name="driving_licence_expiry" class="form-control" value="{{ old(\'driving_licence_expiry\', $tenant->driving_licence_expiry ?? \'\') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Licence Document</label>
                                @if(isset($tenant) && $tenant->driving_licence_document)
                                    <a href="{{ asset(\'storage/\'.$tenant->driving_licence_document) }}" target="_blank" class="d-block mb-1"><i class="fa-solid fa-file"></i> View Current</a>
                                @endif
                                <input type="file" name="driving_licence_document" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Occupation Company</label>
                                <input type="text" name="occupation_company" class="form-control" value="{{ old(\'occupation_company\', $tenant->occupation_company ?? \'\') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Occupation Address</label>
                                <textarea name="occupation_address" class="form-control" rows="1">{{ old(\'occupation_address\', $tenant->occupation_address ?? \'\') }}</textarea>
                            </div>
    ';
    
    // Insert before "2. Addresses & Contact" or "2. Assignment Docs"
    $content = preg_replace('/(<h4 class="mb-3 text-white border-bottom pb-2 mt-4">2\.)/', $docsHtml . "\n                        $1", $content);

    // JS to toggle spouse section
    $scriptHtml = "
    @push('scripts')
    <script>
        function toggleSpouse() {
            var ms = document.getElementById('marital_status');
            if(ms && ms.value === 'married') {
                document.getElementById('spouse_section').style.display = 'block';
            } else {
                var el = document.getElementById('spouse_section');
                if(el) el.style.display = 'none';
            }
        }
        document.addEventListener('DOMContentLoaded', toggleSpouse);
    </script>
    ";
    
    // image drag drop
    $dragHtml = '
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Profile Image (Drag & Drop)</label>
                                <div class="upload-container text-center border rounded p-3" style="border: 2px dashed #ccc !important; cursor: pointer; background: rgba(255,255,255,0.05);" onclick="document.getElementById(\'tenant_image\').click()">
                                    @if(isset($tenant) && $tenant->image)
                                        <img src="{{ asset(\'storage/\'.$tenant->image) }}" id="image_preview" style="max-height: 100px; max-width: 100%; border-radius: 8px;">
                                        <div id="upload_icon" style="display:none;"><i class="fa-solid fa-cloud-arrow-up fa-2x text-secondary"></i><p class="mt-2 mb-0 text-white">Click or drag image here</p></div>
                                    @else
                                        <div id="upload_icon"><i class="fa-solid fa-cloud-arrow-up fa-2x text-secondary"></i><p class="mt-2 mb-0 text-white">Click or drag image here</p></div>
                                        <img src="" id="image_preview" style="max-height: 100px; max-width: 100%; border-radius: 8px; display: none;">
                                    @endif
                                </div>
                                <input type="file" name="image" id="tenant_image" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </div>
    ';
    
    $content = preg_replace('/<div class="col-md-6 mb-3">\s*<label class="form-label">Profile Image<\/label>.*?<\/div>/is', $dragHtml, $content);
    
    $scriptHtml .= "
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById('image_preview');
                    var icon = document.getElementById('upload_icon');
                    if (icon) icon.style.display = 'none';
                    preview.src = e.target.result;
                    preview.style.display = 'inline-block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Drag and drop events
        var container = document.querySelector('.upload-container');
        if (container) {
            container.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                container.style.borderColor = '#007bff !important';
            });
            container.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                container.style.borderColor = '#ccc !important';
            });
            container.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                container.style.borderColor = '#ccc !important';
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    var fileInput = document.getElementById('tenant_image');
                    fileInput.files = e.dataTransfer.files;
                    previewImage(fileInput);
                }
            });
        }
    </script>
    @endpush
    ";
    
    $content = str_replace('@endsection', $scriptHtml . "\n@endsection", $content);
    
    file_put_contents($file, $content);
}

process('d:/Project/Laravel/sweet-home/resources/views/admin/tenants/create.blade.php');
process('d:/Project/Laravel/sweet-home/resources/views/admin/tenants/edit.blade.php');

