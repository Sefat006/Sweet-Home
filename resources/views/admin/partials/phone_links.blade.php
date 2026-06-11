@if(!empty($phone))
    @php
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleanPhone, '0')) {
            $whatsappPhone = '88' . $cleanPhone;
        } elseif (str_starts_with($cleanPhone, '1')) {
            $whatsappPhone = '880' . $cleanPhone;
        } else {
            $whatsappPhone = $cleanPhone;
        }
    @endphp
    <a href="tel:{{ $phone }}" class="text-decoration-none" style="color: inherit !important;">{{ $phone }}</a>
    <a href="https://wa.me/{{ $whatsappPhone }}" target="_blank" rel="noopener noreferrer" class="ms-1" title="WhatsApp" style="color: #25D366 !important; text-decoration: none;">
        <i class="fab fa-whatsapp" style="color: #25D366 !important; font-weight: 900;"></i>
    </a>
@else
    N/A
@endif
