<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tenant Profile - {{ $tenant->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            margin-bottom: 25px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
        }
        .logo-title-area {
            width: 100%;
        }
        .logo-title-area td {
            vertical-align: middle;
        }
        .app-name {
            font-size: 22px;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
        }
        .doc-title {
            font-size: 16px;
            color: #555;
            margin-top: 5px;
        }
        .profile-photo {
            text-align: right;
        }
        .profile-photo img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid #ccc;
            object-fit: cover;
        }
        .section-title {
            background-color: #f3f4f6;
            color: #1e3a8a;
            padding: 6px 10px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-left: 4px solid #2563eb;
        }
        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.info-table td {
            padding: 5px 8px;
            vertical-align: top;
        }
        table.info-table td.label {
            font-weight: bold;
            color: #555;
            width: 30%;
            background-color: #fafafa;
            border-bottom: 1px solid #eee;
        }
        table.info-table td.value {
            width: 70%;
            border-bottom: 1px solid #eee;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f9fafb;
            color: #374151;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            font-size: 11px;
            color: #6b7280;
            text-align: center;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <div class="header">
        <table class="logo-title-area">
            <tr>
                <td>
                    <div class="app-name">SWEET HOME</div>
                    <div class="doc-title">Tenant Information Profile</div>
                </td>
                <td class="profile-photo">
                    @if($tenant->image && file_exists(public_path($tenant->image)))
                        <img src="{{ public_path($tenant->image) }}" alt="Profile Photo">
                    @else
                        <div style="width:100px; height:100px; border-radius:50%; background-color:#e5e7eb; line-height:100px; text-align:center; color:#9ca3af; font-size:11px; display:inline-block; border:1px solid #ccc;">No Photo</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- SECTION: Tenancy Info -->
    <div class="section-title">Tenancy & Assignment Details</div>
    <table class="info-table">
        <tr>
            <td class="label">Building Name</td>
            <td class="value">{{ $building->name }} ({{ $building->location }})</td>
        </tr>
        <tr>
            <td class="label">Flat Assigned</td>
            <td class="value">{{ $flat->flat_name }} (Floor: {{ $flat->floor }})</td>
        </tr>
        <tr>
            <td class="label">Tenancy Status</td>
            <td class="value" style="font-weight: bold; color: {{ $flatTenant->status === 'active' ? '#16a34a' : '#dc2626' }}">{{ ucfirst($flatTenant->status) }}</td>
        </tr>
        <tr>
            <td class="label">Start Date</td>
            <td class="value">{{ $flatTenant->start_date ? $flatTenant->start_date->format('d M, Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">End Date</td>
            <td class="value">{{ $flatTenant->end_date ? $flatTenant->end_date->format('d M, Y') : 'Present' }}</td>
        </tr>
        <tr>
            <td class="label">Advance Amount</td>
            <td class="value">৳ {{ number_format($flatTenant->advance_amount, 2) }}</td>
        </tr>
    </table>

    <!-- SECTION: Personal Details -->
    <div class="section-title">Personal Information</div>
    <table class="info-table">
        <tr>
            <td class="label">Full Name</td>
            <td class="value" style="font-weight: bold;">{{ $tenant->name }}</td>
        </tr>
        <tr>
            <td class="label">Tenant ID</td>
            <td class="value">{{ $tenant->tenant_id }}</td>
        </tr>
        <tr>
            <td class="label">Phone Number</td>
            <td class="value">{{ $tenant->phone }}</td>
        </tr>
        <tr>
            <td class="label">Email Address</td>
            <td class="value">{{ $tenant->email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">NID Number</td>
            <td class="value">{{ $tenant->nid_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Blood Group</td>
            <td class="value">{{ $tenant->blood_group ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Father's Name</td>
            <td class="value">{{ $tenant->father_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Mother's Name</td>
            <td class="value">{{ $tenant->mother_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Gender</td>
            <td class="value">{{ ucfirst($tenant->gender ?? 'N/A') }}</td>
        </tr>
        <tr>
            <td class="label">Date of Birth</td>
            <td class="value">{{ $tenant->dob ? $tenant->dob->format('d M, Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Religion</td>
            <td class="value">{{ ucfirst($tenant->religion ?? 'N/A') }}</td>
        </tr>
        <tr>
            <td class="label">Nationality</td>
            <td class="value">{{ $tenant->nationality ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Present Address</td>
            <td class="value">{{ $tenant->present_address ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Permanent Address</td>
            <td class="value">{{ $tenant->permanent_address ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- SECTION: Emergency Contact -->
    <div class="section-title">Emergency Contact Details</div>
    <table class="info-table">
        <tr>
            <td class="label">Contact Name</td>
            <td class="value">{{ $tenant->emergency_contact_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Relation</td>
            <td class="value">{{ $tenant->emergency_contact_relation ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Phone Number</td>
            <td class="value">{{ $tenant->emergency_contact_phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Address</td>
            <td class="value">{{ $tenant->emergency_contact_address ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- SECTION: Spouse Info -->
    @if($tenant->marital_status === 'married')
        <div class="section-title">Spouse Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Spouse Name</td>
                <td class="value">{{ $tenant->spouse_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Contact Number</td>
                <td class="value">{{ $tenant->spouse_contact_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Father's Name</td>
                <td class="value">{{ $tenant->spouse_father_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Mother's Name</td>
                <td class="value">{{ $tenant->spouse_mother_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Blood Group</td>
                <td class="value">{{ $tenant->spouse_blood_group ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Date of Birth</td>
                <td class="value">{{ $tenant->spouse_date_of_birth ? $tenant->spouse_date_of_birth->format('d M, Y') : 'N/A' }}</td>
            </tr>
        </table>
    @else
        <div class="section-title">Marital Status</div>
        <table class="info-table">
            <tr>
                <td class="label">Status</td>
                <td class="value">{{ ucfirst($tenant->marital_status ?? 'single') }}</td>
            </tr>
        </table>
    @endif

    <!-- SECTION: Previous Tenancy Details -->
    <div class="section-title">Previous Tenancy Details</div>
    <table class="info-table">
        <tr>
            <td class="label">Previous Owner Name</td>
            <td class="value">{{ $tenant->prev_owner_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Owner Phone Number</td>
            <td class="value">{{ $tenant->prev_owner_phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Reason of Leaving</td>
            <td class="value">{{ $tenant->prev_leaving_reason ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Previous Flat Address</td>
            <td class="value">{{ $tenant->prev_flat_address ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- SECTION: Family Members -->
    @if(isset($tenant->members_info) && is_array($tenant->members_info) && count($tenant->members_info) > 0)
        <div class="section-title">Other Family / House Members</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Relation</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tenant->members_info as $mem)
                    <tr>
                        <td>{{ $mem['name'] ?? 'N/A' }}</td>
                        <td>{{ $mem['age'] ?? 'N/A' }}</td>
                        <td>{{ $mem['relation'] ?? 'N/A' }}</td>
                        <td>{{ $mem['phone'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- SECTION: Children Info -->
    @if(isset($tenant->children_info) && is_array($tenant->children_info) && count($tenant->children_info) > 0)
        <div class="section-title">Children Information</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tenant->children_info as $child)
                    <tr>
                        <td>{{ $child['name'] ?? 'N/A' }}</td>
                        <td>{{ ucfirst($child['gender'] ?? 'N/A') }}</td>
                        <td>{{ $child['dob'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- SECTION: Occupation Info -->
    @if(isset($tenant->occupation_info) && is_array($tenant->occupation_info) && count($tenant->occupation_info) > 0)
        <div class="section-title">Occupation Information</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Company/Business</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tenant->occupation_info as $occ)
                    <tr>
                        <td>{{ ucfirst($occ['type'] ?? 'N/A') }}</td>
                        <td>{{ ($occ['type'] ?? '') === 'job' ? ($occ['company'] ?? 'N/A') : ($occ['business_name'] ?? 'N/A') }}</td>
                        <td>{{ ($occ['type'] ?? '') === 'job' ? ($occ['address'] ?? 'N/A') : ($occ['business_address'] ?? 'N/A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- SECTION: Education Info -->
    @if(isset($tenant->education_info) && is_array($tenant->education_info) && count($tenant->education_info) > 0)
        <div class="section-title">Education Information</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Degree/Exam</th>
                    <th>Institution</th>
                    <th>Passing Year</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tenant->education_info as $edu)
                    <tr>
                        <td>{{ $edu['exam'] ?? 'N/A' }}</td>
                        <td>{{ $edu['institution'] ?? 'N/A' }}</td>
                        <td>{{ $edu['year'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- SECTION: Domestic Helpers -->
    @if(isset($tenant->help_info) && is_array($tenant->help_info) && count($tenant->help_info) > 0)
        <div class="section-title">Domestic Helpers</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>NID</th>
                    <th>Mobile</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tenant->help_info as $help)
                    <tr>
                        <td>{{ $help['name'] ?? 'N/A' }}</td>
                        <td>{{ $help['nid'] ?? 'N/A' }}</td>
                        <td>{{ $help['mobile'] ?? 'N/A' }}</td>
                        <td>{{ $help['address'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- SECTION: Drivers -->
    @if(isset($tenant->driver_info) && is_array($tenant->driver_info) && count($tenant->driver_info) > 0)
        <div class="section-title">Drivers</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>NID</th>
                    <th>Mobile</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tenant->driver_info as $driver)
                    <tr>
                        <td>{{ $driver['name'] ?? 'N/A' }}</td>
                        <td>{{ $driver['nid'] ?? 'N/A' }}</td>
                        <td>{{ $driver['mobile'] ?? 'N/A' }}</td>
                        <td>{{ $driver['address'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- SECTION: Documents Checklist -->
    <div class="section-title">Submitted Documents Checklist</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 50%;">Document Category</th>
                <th style="width: 50%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>NID Document</td>
                <td>{{ !empty($tenant->nid_document) ? 'Submitted (' . count($tenant->nid_document) . ' file/s)' : 'Not Submitted' }}</td>
            </tr>
            <tr>
                <td>Passport Document</td>
                <td>{{ !empty($tenant->passport_document) ? 'Submitted (' . count($tenant->passport_document) . ' file/s)' : 'Not Submitted' }}</td>
            </tr>
            <tr>
                <td>Driving Licence Document</td>
                <td>{{ !empty($tenant->driving_licence_document) ? 'Submitted (' . count($tenant->driving_licence_document) . ' file/s)' : 'Not Submitted' }}</td>
            </tr>
            <tr>
                <td>Advance Payment Document</td>
                <td>{{ !empty($flatTenant->advance_document) ? 'Submitted (' . count($flatTenant->advance_document) . ' file/s)' : 'Not Submitted' }}</td>
            </tr>
            <tr>
                <td>Rental Agreement Document</td>
                <td>{{ !empty($flatTenant->agreement_document) ? 'Submitted (' . count($flatTenant->agreement_document) . ' file/s)' : 'Not Submitted' }}</td>
            </tr>
            <tr>
                <td>Police Verification Form</td>
                <td>{{ !empty($flatTenant->police_form_document) ? 'Submitted (' . count($flatTenant->police_form_document) . ' file/s)' : 'Not Submitted' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- SECTION: Notes -->
    @if($tenant->notes || $flatTenant->notes)
        <div class="section-title">Remarks & Notes</div>
        @if($tenant->notes)
            <div style="margin-bottom: 10px;">
                <strong>Tenant Profile Notes:</strong><br>
                <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 8px; border-radius: 4px; font-style: italic;">
                    {{ $tenant->notes }}
                </div>
            </div>
        @endif
        @if($flatTenant->notes)
            <div>
                <strong>Assignment/Tenancy Notes:</strong><br>
                <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 8px; border-radius: 4px; font-style: italic;">
                    {{ $flatTenant->notes }}
                </div>
            </div>
        @endif
    @endif

    <div class="footer">
        Generated automatically by Sweet Home App on {{ now()->format('d M, Y h:i A') }}
    </div>

</body>
</html>
