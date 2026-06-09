<div class="pf-file" onclick="document.getElementById('{{ $fieldId }}').click()" 
     ondragover="pfZoneDragOver(event,this)" ondragleave="pfZoneDragLeave(event,this)" 
     ondrop="pfZoneDrop(event,this,'{{ $fieldId }}')">
    <div class="pf-file__icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
    </div>
    <div class="pf-file__text">
        <div class="pf-file__cta">Drag & Drop or Click</div>
        <div class="pf-file__hint">Images, PDFs, docs — any size</div>
        @if(isset($existing) && $existing)
            <div class="pf-file__existing-list mt-2" style="font-size: 0.75rem; color: #16a34a;">
                @php
                    $files = is_array($existing) ? $existing : json_decode($existing, true);
                    if (!is_array($files)) {
                        $files = [$existing];
                    }
                @endphp
                @foreach($files as $index => $file)
                    @if($file)
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fa-solid fa-paperclip"></i>
                            <a href="{{ asset($file) }}" target="_blank" class="text-success text-decoration-none" onclick="event.stopPropagation();">
                                File {{ $index + 1 }} ({{ basename($file) }})
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
        <div class="pf-file__names" id="{{ $fieldId }}_names"></div>
    </div>
</div>
<input type="file" id="{{ $fieldId }}" name="{{ $fieldName }}" class="d-none" multiple onchange="pfZoneChange(this,'{{ $fieldId }}')">
