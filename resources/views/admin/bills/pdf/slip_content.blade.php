{{-- Shared slip content --}}
@php
    $adminUser  = $building->admin;
    $tenantName = $bill->tenant?->name ?? '—';
    $flatName   = $flat->flat_name;
    $billMonth  = date('F Y', strtotime($bill->bill_month . '-01'));
    $dueDate    = '10th ' . date('F Y', strtotime($bill->bill_month . '-01'));

    $adminName  = $adminUser?->name  ?? '';
    $adminPhone = $adminUser?->phone ?? '';
    $adminEmail = $adminUser?->email ?? '';
    $bankInfo   = $building->bank_info ?? '';
    $contactNote= $building->contact_note ?? '';

    $logoBase64 = '';
    if (!empty($building->logo)) {
        $path = public_path($building->logo);
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    }

    $houseRent  = (float)$bill->house_rent;
    $gas        = (float)$bill->gas;
    $water      = (float)$bill->wasa;
    $security   = (float)$bill->security;
    $others     = (float)$bill->utility + (float)$bill->other + (float)$bill->society_bill;
    $parking    = (float)$bill->parking;
    $advance    = 0.00;
    $dueElec    = (float)$bill->common_electricity + (float)$bill->previous_due;

    $grandTotal = $houseRent + $gas + $water + $security + $others + $parking + $advance + $dueElec;
    $paidAmount = (float)$bill->paid_amount;
    $dueAmount  = (float)$bill->remaining_amount;

    $rows = [
        ['sn'=>1, 'item'=>'House Rent',     'amount'=>$houseRent],
        ['sn'=>2, 'item'=>'Gas',             'amount'=>$gas],
        ['sn'=>3, 'item'=>'Water',           'amount'=>$water],
        ['sn'=>4, 'item'=>'Security',        'amount'=>$security],
        ['sn'=>5, 'item'=>'Others',          'amount'=>$others],
        ['sn'=>6, 'item'=>'Parking',         'amount'=>$parking],
        ['sn'=>7, 'item'=>'Advance',         'amount'=>$advance],
        ['sn'=>8, 'item'=>'Due/ Electricity','amount'=>$dueElec],
    ];
@endphp

{{-- ══ HEADER: Logo + Villa Name (Centered) ════════════════════════ --}}
<div style="text-align: center; margin-bottom: 5px;">
    @if($logoBase64)
        <img src="{{ $logoBase64 }}" alt="logo" style="height: 45px; vertical-align: middle; margin-right: 15px;">
    @endif
    <span style="font-size: 42px; font-weight: bold; vertical-align: middle;">{{ $building->name }}</span>
</div>

{{-- ══ ADDRESS (Centered, under Villa Name) ════════════════════════ --}}
<div style="text-align: center; font-size: 13px; margin-bottom: 25px;">
    {{ $building->address }}
</div>

{{-- ══ MONTH NAME + COPY LABEL ═════════════════════════════════════ --}}
<table style="width: 100%; margin-bottom: 15px; font-size: 14px;">
    <tr>
        <td style="text-align: left;"><b>Month Name:</b> {{ $billMonth }}</td>
        <td style="text-align: right; font-weight: bold;">{{ $copyLabel }}</td>
    </tr>
</table>

{{-- ══ TENANT NAME ═════════════════════════════════════════════════ --}}
<div style="font-size: 14px; margin-bottom: 12px;">
    <b>Name:</b> {{ $tenantName }}
</div>

{{-- ══ FLAT NAME ═══════════════════════════════════════════════════ --}}
<div style="font-size: 14px; margin-bottom: 20px;">
    <b>Flat Name:</b> {{ $flatName }}
</div>

{{-- ══ BILL TABLE ══════════════════════════════════════════════════ --}}
<table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">
    <thead>
        <tr>
            <th style="border: 1px solid #000; background-color: #B4C6E7; padding: 6px; font-size: 16px; font-weight: bold; text-align: center; width: 45px;">S/N</th>
            <th style="border: 1px solid #000; background-color: #B4C6E7; padding: 6px; font-size: 16px; font-weight: bold; text-align: center;">Item</th>
            <th style="border: 1px solid #000; background-color: #B4C6E7; padding: 6px; font-size: 16px; font-weight: bold; text-align: center; width: 110px;">Amount</th>
            <th style="border: 1px solid #000; background-color: #B4C6E7; padding: 6px; font-size: 16px; font-weight: bold; text-align: center; width: 110px;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $i => $row)
        <tr>
            <td style="border: 1px solid #000; padding: 6px; font-size: 16px; text-align: center;">{{ $row['sn'] }}</td>
            <td style="border: 1px solid #000; padding: 6px; font-size: 16px;">{{ $row['item'] }}</td>
            <td style="border: 1px solid #000; padding: 6px; font-size: 16px; text-align: right;">
                @if($row['amount'] > 0)
                    {{ number_format($row['amount'], 2) }}
                @endif
            </td>
            {{-- Mimic image design where Total column has no inner borders for rows 1 to 8 --}}
            @if($i == 0)
            <td style="border: 1px solid #000; padding: 6px;" rowspan="8"></td>
            @endif
        </tr>
        @endforeach
        
        {{-- Total --}}
        <tr>
            <td colspan="2" style="border: 1px solid #000; padding: 6px; font-size: 16px; font-weight: bold; text-align: center;">Total</td>
            <td style="border: 1px solid #000; padding: 6px; font-size: 16px; border-bottom: none;"></td>
            <td style="border: 1px solid #000; padding: 6px; font-size: 16px; font-weight: bold; text-align: right;">{{ number_format($grandTotal, 2) }}</td>
        </tr>
        {{-- Paid --}}
        <tr>
            <td colspan="2" style="border: 1px solid #000; padding: 6px; font-size: 16px; font-weight: bold; text-align: center;">Paid</td>
            <td style="border: 1px solid #000; padding: 6px; font-size: 16px; border-top: none; border-bottom: none;"></td>
            <td style="border: 1px solid #000; padding: 6px; font-size: 16px; font-weight: bold; text-align: right;">{{ number_format($paidAmount, 2) }}</td>
        </tr>
        {{-- Due --}}
        <tr>
            <td colspan="2" style="border: 1px solid #000; padding: 6px; font-size: 16px; font-weight: bold; text-align: center; color: #d9534f;">Due</td>
            <td style="border: 1px solid #000; padding: 6px; font-size: 16px; border-top: none;"></td>
            <td style="border: 1px solid #000; padding: 6px; font-size: 16px; font-weight: bold; text-align: right; color: #d9534f;">{{ number_format($dueAmount, 2) }}</td>
        </tr>
    </tbody>
</table>

{{-- ══ NOTE SECTION ════════════════════════════════════════════════ --}}
<div style="font-size: 13px; margin-bottom: 20px;">
    <div style="font-weight: bold; text-decoration: underline; margin-bottom: 5px;">Note:</div>
    <p style="margin-bottom: 4px;">*Please provide the monthly rent before <b>{{ $dueDate }}</b>.</p>
    @if($adminPhone || $adminName)
    <p style="margin-bottom: 4px;">* If required any information or assistance can call
        @if($adminName) {{ $adminName }}-@endif
        <b>{{ $adminPhone }}</b>.</p>
    @endif
    @if($contactNote)
    <p style="margin-bottom: 4px;">{{ $contactNote }}</p>
    @endif
    @if($adminEmail)
    <p style="margin-bottom: 0;">Email:{{ $adminEmail }}</p>
    @endif
</div>

{{-- ══ ONLINE TRANSFER ═════════════════════════════════════════════ --}}
@if($bankInfo)
<div style="font-size: 13px;">
    <div style="font-weight: bold; text-decoration: underline; margin-bottom: 5px;">For online Transfer-</div>
    @foreach(array_filter(explode("\n", str_replace("\r", "", $bankInfo))) as $line)
    <p style="margin-bottom: 4px;">{{ trim($line) }}</p>
    @endforeach
</div>
@endif

{{-- ══ SIGNATURE ═══════════════════════════════════════════════════ --}}
<div style="text-align: right; font-size: 14px; text-decoration: underline; margin-top: 30px; margin-right: 20px;">
    Signature
</div>
