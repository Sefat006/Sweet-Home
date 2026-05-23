<?php
function process($file, $isEdit) {
    $content = file_get_contents($file);

    // 1. Inject Styles for pf-file
    $styles = '
@push(\'styles\')
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
        align-items: center;
        gap: 12px;
        transition: border-color .15s, background .15s;
        background: rgba(255,255,255,0.05);
        min-height: 56px;
    }
    .pf-file:hover {
        border-color: var(--pf-accent);
        background: rgba(37, 99, 235, .1);
    }
    .pf-file__icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(37, 99, 235, .2);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background .15s;
    }
    .pf-file:hover .pf-file__icon {
        background: rgba(37, 99, 235, .3);
    }
    .pf-file__icon svg {
        width: 17px;
        height: 17px;
        stroke: var(--pf-accent);
    }
    .pf-file__text {
        flex: 1;
        min-width: 0;
    }
    .pf-file__cta {
        font-size: .8rem;
        font-weight: 600;
        color: var(--pf-accent);
        line-height: 1.3;
    }
    .pf-file__hint {
        font-size: .73rem;
        color: #999;
        line-height: 1.3;
    }
    .pf-file__existing {
        font-size: .73rem;
        color: #16a34a;
        font-weight: 500;
        margin-top: 2px;
    }
    .pf-file__name {
        font-size: .78rem;
        color: #fff;
        font-weight: 500;
        margin-top: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: none;
    }
    .pf-file__name.show {
        display: block;
    }
    
    /* Children rows */
    .pf-edu-row {
        display: grid;
        grid-template-columns: 2fr 2fr 2fr 32px;
        gap: 8px;
        margin-bottom: 8px;
        align-items: center;
    }
    @media(max-width:768px) {
        .pf-edu-row {
            grid-template-columns: 1fr 1fr;
        }
        .pf-edu-row .btn-pf-del {
            grid-column: span 2;
            justify-self: start;
        }
    }
    .btn-pf-del {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        background: #fee2e2;
        border: none;
        color: var(--pf-danger);
        font-size: .85rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>
@endpush
';
    // Insert styles at the top after @extends
    $content = preg_replace('/(@extends\(\'admin\.layouts\.app\'\))/', "$1\n$styles", $content);
    
    // 2. Add children inputs inside the spouse_section block
    $childrenHtml = '
                                    <div class="col-12 mt-4">
                                        <h5 class="mt-3 mb-2 text-white border-bottom pb-1">Children Information</h5>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 mb-3">
                                                <label class="form-label">Number of Children</label>
                                                <input type="number" name="no_of_children" id="no_of_children" class="form-control" min="0" value="{{ old(\'no_of_children\', $tenant->no_of_children ?? 0) }}">
                                            </div>
                                        </div>
                                        <div id="children_rows">
                                            <!-- JS will populate rows -->
                                        </div>
                                    </div>
    ';
    
    $content = str_replace('<!-- Spouse Section -->', '<!-- Spouse & Children Section -->', $content);
    // insert childrenHtml right before the closing div of spouse_section
    $content = preg_replace('/(<input type="date" name="spouse_date_of_birth".*?<\/div>\s*<\/div>)/is', "$1\n$childrenHtml", $content);

    // 3. Process all <input type="file"> to become drag-and-drop boxes, EXCEPT the one for Profile Image which we already custom-designed.
    
    $fileFields = [
        'nid_document' => 'NID Document',
        'occupation_document' => 'Occupation Document',
        'passport_document' => 'Passport Document',
        'driving_licence_document' => 'Licence Document',
        'advance_document' => 'Advance Document',
        'agreement_document' => 'Agreement Document',
        'police_form_document' => 'Police Form Document',
        'notice_document' => 'Notice Document',
        'house_rent_copy' => 'House Rent Copy',
    ];
    
    foreach ($fileFields as $name => $label) {
        $blockRegex = '/<label class="form-label">'.$label.'<\/label>\s*(?:@if\(.*?\)\s*<a href=.*?<\/a>\s*@endif\s*)?<input type="file" name="'.$name.'" class="form-control">/is';
        
        $docProp = $name;
        if (in_array($name, ['advance_document','agreement_document','police_form_document','notice_document','house_rent_copy'])) {
            $modelPrefix = '$flatTenant';
        } else {
            $modelPrefix = '$tenant';
        }
        
        $newBlock = '
        <label class="form-label">'.$label.'</label>
        <div class="pf-file" onclick="document.getElementById(\'f_'.$name.'\').click()" ondragover="pfDragOver(event, this)" ondragleave="pfDragLeave(event, this)" ondrop="pfDrop(event, this, \'f_'.$name.'\')">
            <div class="pf-file__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13" /></svg>
            </div>
            <div class="pf-file__text">
                <div class="pf-file__cta">Drag & Drop or Click to Attach</div>
                @if(isset('.$modelPrefix.') && '.$modelPrefix.'->'.$name.')
                <div class="pf-file__existing">&#10003; Current file uploaded</div>
                @endif
                <div class="pf-file__name" id="f_'.$name.'_name"></div>
            </div>
        </div>
        <input type="file" id="f_'.$name.'" name="'.$name.'" class="d-none" onchange="pfFile(this,\'f_'.$name.'_name\')">
        ';
        
        $content = preg_replace($blockRegex, trim($newBlock), $content);
    }
    
    // 4. JS for children rows and pfFile
    $js = '
    <script>
        function pfFile(input, nameId) {
            const el = document.getElementById(nameId);
            if(input.files && input.files[0]) {
                el.textContent = input.files[0].name;
                el.classList.add("show");
            } else {
                el.textContent = "";
                el.classList.remove("show");
            }
        }
        function pfDragOver(e, el) {
            e.preventDefault(); e.stopPropagation();
            el.style.borderColor = "#2563eb";
        }
        function pfDragLeave(e, el) {
            e.preventDefault(); e.stopPropagation();
            el.style.borderColor = "var(--pf-border)";
        }
        function pfDrop(e, el, inputId) {
            e.preventDefault(); e.stopPropagation();
            el.style.borderColor = "var(--pf-border)";
            if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                let input = document.getElementById(inputId);
                input.files = e.dataTransfer.files;
                pfFile(input, inputId + "_name");
            }
        }
        
        // Children Logic
        const existingChildren = {!! isset($tenant) && $tenant->children_info ? json_encode($tenant->children_info) : \'[]\' !!};
        const noChildInput = document.getElementById("no_of_children");
        const childrenRows = document.getElementById("children_rows");
        
        function renderChildren() {
            if(!noChildInput || !childrenRows) return;
            const num = parseInt(noChildInput.value) || 0;
            let html = "";
            for(let i=0; i<num; i++) {
                let ch = existingChildren[i] || {};
                html += `
                <div class="pf-edu-row">
                    <input type="text" name="child_name[]" class="form-control" placeholder="Child Name" value="${ch.name || \'\'}">
                    <select name="child_gender[]" class="form-control">
                        <option value="">Select Gender</option>
                        <option value="male" ${ch.gender === \'male\' ? \'selected\' : \'\'}>Male</option>
                        <option value="female" ${ch.gender === \'female\' ? \'selected\' : \'\'}>Female</option>
                    </select>
                    <input type="date" name="child_dob[]" class="form-control" value="${ch.dob || \'\'}">
                    <button type="button" class="btn-pf-del" onclick="removeBtn(this)" title="Remove">✕</button>
                </div>`;
            }
            if(num === 0) {
                html = `<p class="pf-empty-msg" style="color:#aaa;">Set number of children to enter details.</p>`;
            }
            childrenRows.innerHTML = html;
        }
        
        function removeBtn(btn) {
            if(noChildInput.value > 0) {
                noChildInput.value = parseInt(noChildInput.value) - 1;
                renderChildren();
            }
        }
        
        if(noChildInput) {
            noChildInput.addEventListener("input", renderChildren);
        }
        
        document.addEventListener("DOMContentLoaded", function() {
            if(typeof toggleSpouse !== "undefined") toggleSpouse();
            renderChildren();
        });
    </script>
    ';
    $content = str_replace('@endpush', $js . "\n@endpush", $content);
    
    // Convert generic col-md-* to col-sm-* where appropriate to make form responsive on small screens.
    $content = str_replace('col-md-6 mb-3', 'col-md-6 col-sm-12 mb-3', $content);
    $content = str_replace('col-md-4 mb-3', 'col-md-4 col-sm-12 mb-3', $content);

    file_put_contents($file, $content);
}

process('d:/Project/Laravel/sweet-home/resources/views/admin/tenants/create.blade.php', false);
process('d:/Project/Laravel/sweet-home/resources/views/admin/tenants/edit.blade.php', true);
?>
