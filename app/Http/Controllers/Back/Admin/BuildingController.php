<?php

namespace App\Http\Controllers\Back\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\BuildingSecurity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuildingController extends Controller
{
    private function getBuilding($id, $with = []) {
        $query = Building::query();
        if (!empty($with)) {
            $query->with($with);
        }
        $building = $query->findOrFail($id);
        
        if (Auth::user()->role !== 'super_admin' && $building->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return $building;
    }

    public function index(Request $request)
    {
        $userId = Auth::id();
        $owner = null;

        if (Auth::user()->role === 'super_admin' && $request->has('admin_id')) {
            $userId = $request->admin_id;
            $owner = \App\Models\User::find($userId);
        }

        $buildings = Building::where('user_id', $userId)->latest()->get();
        return view('admin.buildings.index', compact('buildings', 'owner'));
    }

    public function create()
    {
        return view('admin.buildings.create');
    }



    public function store(Request $request)
    {
        // Full Validation based on Image fields and Model fillables
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg',
            'no_of_floor' => 'required|integer',
            'address' => 'required|string',
            'holding_tax_number' => 'nullable|string',
            'holding_tax_clearance_up_to' => 'nullable|date',
            'holding_tax_document' => 'nullable|file|mimes:pdf,jpg,png,zip',
            'dolil_document' => 'nullable|file|mimes:pdf,jpg,png,zip',
            'noksha_document' => 'nullable|file|mimes:pdf,jpg,png,zip',
            'mutation_document' => 'nullable|file|mimes:pdf,jpg,png,zip',
            'khajna_document' => 'nullable|file|mimes:pdf,jpg,png,zip',
            'khajna_clearance_up_to' => 'nullable|date',
            'alert_notes'                => 'nullable|string',
            'bank_info'                  => 'nullable|string',
            'contact_note'               => 'nullable|string|max:300',
            // Security Info Validation
            'sec_name' => 'nullable|array',
            'sec_name.*' => 'required|string|max:255',
            'sec_father_name' => 'nullable|array',
            'sec_father_name.*' => 'nullable|string|max:255',
            'sec_mother_name' => 'nullable|array',
            'sec_mother_name.*' => 'nullable|string|max:255',
            'sec_nid_number' => 'nullable|array',
            'sec_nid_number.*' => 'nullable|string|max:50',
            'sec_nid_document.*' => 'nullable|file|mimes:pdf,jpg,png',
            'sec_birth_cert_number' => 'nullable|array',
            'sec_birth_cert_number.*' => 'nullable|string|max:50',
            'sec_birth_cert_document' => 'nullable|array',
            'sec_birth_cert_document.*' => 'nullable|file|mimes:pdf,jpg,png',
            'sec_contact' => 'nullable|array',
            'sec_contact.*' => 'required|string|max:20',
            'sec_image' => 'nullable|array',
            'sec_image.*' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        try {
            DB::beginTransaction();

            // Handle Building File Uploads
            $logo = uploadFileDirect($request->file('logo'), 'admin/assets/images/buildings/logos');
            $taxDoc = uploadFileDirect($request->file('holding_tax_document'), 'admin/assets/documents/buildings/tax-docs');
            $dolilDoc = uploadFileDirect($request->file('dolil_document'), 'admin/assets/documents/buildings/dolil-docs');
            $nokshaDoc = uploadFileDirect($request->file('noksha_document'), 'admin/assets/documents/buildings/noksha-docs');
            $mutationDoc = uploadFileDirect($request->file('mutation_document'), 'admin/assets/documents/buildings/mutation-docs');
            $khajnaDoc = uploadFileDirect($request->file('khajna_document'), 'admin/assets/documents/buildings/khajna-docs');

            // 1. Store Building
            $building = Building::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'logo' => $logo,
                'no_of_floor' => $request->no_of_floor,
                'address' => $request->address,
                'holding_tax_number' => $request->holding_tax_number,
                'holding_tax_clearance_up_to' => $request->holding_tax_clearance_up_to,
                'holding_tax_document' => $taxDoc,
                'dolil_document' => $dolilDoc,
                'noksha_document' => $nokshaDoc,
                'mutation_document' => $mutationDoc,
                'khajna_document' => $khajnaDoc,
                'khajna_clearance_up_to' => $request->khajna_clearance_up_to,
                'alert_notes'      => $request->alert_notes,
                'bank_info'        => $request->bank_info,
                'contact_note'     => $request->contact_note,
            ]);

            // Handle Security File Uploads and Store Security Info
            if ($request->has('sec_name') && is_array($request->sec_name)) {
                foreach ($request->sec_name as $index => $name) {
                    $secNidDoc = uploadFileDirect($request->file("sec_nid_document.$index"), 'admin/assets/documents/buildings/security/nid-docs');
                    $secBirthDoc = uploadFileDirect($request->file("sec_birth_cert_document.$index"), 'admin/assets/documents/buildings/security/birth-certificate');
                    $secImg = uploadFileDirect($request->file("sec_image.$index"), 'admin/assets/images/buildings/security');

                    BuildingSecurity::create([
                        'building_id' => $building->id,
                        'name' => $name,
                        'father_name' => $request->sec_father_name[$index] ?? null,
                        'mother_name' => $request->sec_mother_name[$index] ?? null,
                        'nid_number' => $request->sec_nid_number[$index] ?? null,
                        'nid_document' => $secNidDoc,
                        'birth_certificate_number' => $request->sec_birth_cert_number[$index] ?? null,
                        'birth_certificate_document' => $secBirthDoc,
                        'contact' => $request->sec_contact[$index] ?? null,
                        'image' => $secImg,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.building.index')->with('success', 'Building and Security Information saved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $building = $this->getBuilding($id, ['securities']);
        return view('admin.buildings.show', compact('building'));
    }

    public function edit($id)
    {
        $building = $this->getBuilding($id, ['securities']);
        return view('admin.buildings.edit', compact('building'));
    }

    public function update(Request $request, $id)
    {
        $building = $this->getBuilding($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg',
            'no_of_floor' => 'required|integer',
            'address' => 'required|string',
            'holding_tax_number' => 'nullable|string',
            'holding_tax_clearance_up_to' => 'nullable|date',
            'holding_tax_document' => 'nullable|file|mimes:pdf,jpg,png,zip',
            'dolil_document' => 'nullable|file|mimes:pdf,jpg,png,zip',
            'noksha_document' => 'nullable|file|mimes:pdf,jpg,png,zip',
            'mutation_document' => 'nullable|file|mimes:pdf,jpg,png,zip',
            'khajna_document' => 'nullable|file|mimes:pdf,jpg,png,zip',
            'khajna_clearance_up_to' => 'nullable|date',
            'alert_notes'                => 'nullable|string',
            'bank_info'                  => 'nullable|string',
            'contact_note'               => 'nullable|string|max:300',
            // Security Info Validation
            'sec_name' => 'nullable|array',
            'sec_name.*' => 'required|string|max:255',
            'sec_father_name' => 'nullable|array',
            'sec_father_name.*' => 'nullable|string|max:255',
            'sec_mother_name' => 'nullable|array',
            'sec_mother_name.*' => 'nullable|string|max:255',
            'sec_nid_number' => 'nullable|array',
            'sec_nid_number.*' => 'nullable|string|max:50',
            'sec_nid_document.*' => 'nullable|file|mimes:pdf,jpg,png',
            'sec_birth_cert_number' => 'nullable|array',
            'sec_birth_cert_number.*' => 'nullable|string|max:50',
            'sec_birth_cert_document' => 'nullable|array',
            'sec_birth_cert_document.*' => 'nullable|file|mimes:pdf,jpg,png',
            'sec_contact' => 'nullable|array',
            'sec_contact.*' => 'required|string|max:20',
            'sec_image' => 'nullable|array',
            'sec_image.*' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->except(['_token', '_method', 'sec_name', 'sec_father_name', 'sec_mother_name', 'sec_nid_number', 'sec_nid_document', 'sec_birth_cert_number', 'sec_birth_cert_document', 'sec_contact', 'sec_image']);

            if ($request->hasFile('logo')) $data['logo'] = uploadFileDirect($request->file('logo'), 'admin/assets/images/buildings/logos');
            if ($request->hasFile('holding_tax_document')) $data['holding_tax_document'] = uploadFileDirect($request->file('holding_tax_document'), 'admin/assets/documents/buildings/tax-docs');
            if ($request->hasFile('dolil_document')) $data['dolil_document'] = uploadFileDirect($request->file('dolil_document'), 'admin/assets/documents/buildings/dolil-docs');
            if ($request->hasFile('noksha_document')) $data['noksha_document'] = uploadFileDirect($request->file('noksha_document'), 'admin/assets/documents/buildings/noksha-docs');
            if ($request->hasFile('mutation_document')) $data['mutation_document'] = uploadFileDirect($request->file('mutation_document'), 'admin/assets/documents/buildings/mutation-docs');
            if ($request->hasFile('khajna_document')) $data['khajna_document'] = uploadFileDirect($request->file('khajna_document'), 'admin/assets/documents/buildings/khajna-docs');

            $building->update($data);

            // Update Security Info (Re-create for simplicity or sync)
            BuildingSecurity::where('building_id', $building->id)->delete(); // In a real scenario we should delete old files as well, but this is a quick update.

            if ($request->has('sec_name') && is_array($request->sec_name)) {
                foreach ($request->sec_name as $index => $name) {
                    $secNidDoc = $request->hasFile("sec_nid_document.$index") ? uploadFileDirect($request->file("sec_nid_document.$index"), 'admin/assets/documents/buildings/security/nid-docs') : ($request->old_sec_nid_document[$index] ?? null);
                    $secBirthDoc = $request->hasFile("sec_birth_cert_document.$index") ? uploadFileDirect($request->file("sec_birth_cert_document.$index"), 'admin/assets/documents/buildings/security/birth-certificate') : ($request->old_sec_birth_cert_document[$index] ?? null);
                    $secImg = $request->hasFile("sec_image.$index") ? uploadFileDirect($request->file("sec_image.$index"), 'admin/assets/images/buildings/security') : ($request->old_sec_image[$index] ?? null);

                    BuildingSecurity::create([
                        'building_id' => $building->id,
                        'name' => $name,
                        'father_name' => $request->sec_father_name[$index] ?? null,
                        'mother_name' => $request->sec_mother_name[$index] ?? null,
                        'nid_number' => $request->sec_nid_number[$index] ?? null,
                        'nid_document' => $secNidDoc,
                        'birth_certificate_number' => $request->sec_birth_cert_number[$index] ?? null,
                        'birth_certificate_document' => $secBirthDoc,
                        'contact' => $request->sec_contact[$index] ?? null,
                        'image' => $secImg,
                    ]);
                }
            }

            DB::commit();
            
            if (Auth::user()->role === 'super_admin') {
                return redirect()->route('admin.building.index', ['admin_id' => $building->user_id])->with('success', 'Building updated successfully.');
            }
            return redirect()->route('admin.building.index')->with('success', 'Building updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $building = $this->getBuilding($id);
        $userId = $building->user_id;
        $building->securities()->delete();
        $building->delete();

        if (Auth::user()->role === 'super_admin') {
            return redirect()->route('admin.building.index', ['admin_id' => $userId])->with('success', 'Building deleted successfully.');
        }
        return redirect()->route('admin.building.index')->with('success', 'Building deleted successfully.');
    }
}
