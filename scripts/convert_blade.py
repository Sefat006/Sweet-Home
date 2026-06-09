import re

with open('d:/Project/Laravel/sweet-home/resources/views/admin/tenants/create.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace Form Action
content = content.replace(
    "route('admin.tenants.store', [$building->id, $flat->id])", 
    "route('admin.tenants.update', [$building->id, $flat->id, $tenant->id])"
)

# Add method PUT
content = content.replace(
    "@csrf",
    "@csrf\n                        @method('PUT')"
)

# Replace Title
content = content.replace(
    "Enroll New Tenant",
    "Edit Tenant - {{ $tenant->name }}"
)

# Replace Enroll button
content = content.replace(
    "<button type=\"submit\" class=\"btn btn-primary\"><i class=\"fa-solid fa-save\"></i> Enroll Tenant</button>",
    "<button type=\"submit\" class=\"btn btn-success\"><i class=\"fa-solid fa-save\"></i> Update Details</button>"
)

# Fix old('field') without $tenant fallback
fields_to_fix = [
    'name', 'phone', 'email', 'blood_group', 'dob', 'nid_number', 'start_date', 'advance_amount'
]
for field in fields_to_fix:
    content = re.sub(
        r"old\('" + field + r"'\)", 
        r"old('" + field + r"', $tenant->" + field + r" ?? '')", 
        content
    )
    
# Wait, for advance_amount, it's in flatTenant
content = content.replace(
    "$tenant->advance_amount",
    "$flatTenant->advance_amount"
)
content = content.replace(
    "$tenant->start_date",
    "$flatTenant->start_date"
)

with open('d:/Project/Laravel/sweet-home/resources/views/admin/tenants/edit.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Edit blade updated")
