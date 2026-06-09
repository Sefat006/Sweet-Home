<?php

namespace App\Http\Controllers\Back\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Flat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FlatController extends Controller
{
    private function getBuilding($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        if (Auth::user()->role !== 'super_admin' && $building->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return $building;
    }

    private function getFlat($flatId, $building)
    {
        $flat = Flat::where('building_id', $building->id)->findOrFail($flatId);
        return $flat;
    }

    public function index($buildingId)
    {
        $building = $this->getBuilding($buildingId);
        $flats = Flat::where('building_id', $buildingId)->latest()->get();
        return view('admin.flats.index', compact('building', 'flats'));
    }

    public function create($buildingId)
    {
        $building = $this->getBuilding($buildingId);
        return view('admin.flats.create', compact('building'));
    }

    public function store(Request $request, $buildingId)
    {
        $building = $this->getBuilding($buildingId);

        $request->validate([
            'flat_name'          => 'required|string|max:100',
            'intercom_number'    => 'nullable|string|max:50',
            'floor'              => 'nullable|string|max:50',
            'status'             => 'required|in:vacant,occupied',
            'available_for'      => 'nullable|string|max:100',
            'flat_size'          => 'nullable|string|max:100',
            'flat_details'       => 'nullable|string',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bill_status'        => 'required|in:active,inactive',
            'house_rent'         => 'nullable|numeric|min:0',
            'wasa'               => 'nullable|numeric|min:0',
            'common_electricity' => 'nullable|numeric|min:0',
            'gas'                => 'nullable|numeric|min:0',
            'utility'            => 'nullable|numeric|min:0',
            'parking'            => 'nullable|numeric|min:0',
            'society_bill'       => 'nullable|numeric|min:0',
            'security'           => 'nullable|numeric|min:0',
            'other'              => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $image = uploadFileDirect($request->file('image'), 'admin/assets/images/flats');

            Flat::create([
                'building_id'        => $building->id,
                'flat_name'          => $request->flat_name,
                'intercom_number'    => $request->intercom_number,
                'floor'              => $request->floor,
                'status'             => $request->status,
                'available_for'      => $request->available_for,
                'flat_size'          => $request->flat_size,
                'flat_details'       => $request->flat_details,
                'image'              => $image,
                'bill_status'        => $request->bill_status,
                'house_rent'         => $request->house_rent         ?? 0,
                'wasa'               => $request->wasa               ?? 0,
                'common_electricity' => $request->common_electricity ?? 0,
                'gas'                => $request->gas                ?? 0,
                'utility'            => $request->utility            ?? 0,
                'parking'            => $request->parking            ?? 0,
                'society_bill'       => $request->society_bill       ?? 0,
                'security'           => $request->security           ?? 0,
                'other'              => $request->other              ?? 0,
            ]);

            DB::commit();
            return redirect()->route('admin.flats.index', $building->id)
                ->with('success', 'Flat created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($buildingId, $flatId)
    {
        $building = $this->getBuilding($buildingId);
        $flat = $this->getFlat($flatId, $building);
        return view('admin.flats.show', compact('building', 'flat'));
    }

    public function edit($buildingId, $flatId)
    {
        $building = $this->getBuilding($buildingId);
        $flat = $this->getFlat($flatId, $building);
        return view('admin.flats.edit', compact('building', 'flat'));
    }

    public function update(Request $request, $buildingId, $flatId)
    {
        $building = $this->getBuilding($buildingId);
        $flat = $this->getFlat($flatId, $building);

        $request->validate([
            'flat_name'          => 'required|string|max:100',
            'intercom_number'    => 'nullable|string|max:50',
            'floor'              => 'nullable|string|max:50',
            'status'             => 'required|in:vacant,occupied',
            'available_for'      => 'nullable|string|max:100',
            'flat_size'          => 'nullable|string|max:100',
            'flat_details'       => 'nullable|string',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bill_status'        => 'required|in:active,inactive',
            'house_rent'         => 'nullable|numeric|min:0',
            'wasa'               => 'nullable|numeric|min:0',
            'common_electricity' => 'nullable|numeric|min:0',
            'gas'                => 'nullable|numeric|min:0',
            'utility'            => 'nullable|numeric|min:0',
            'parking'            => 'nullable|numeric|min:0',
            'society_bill'       => 'nullable|numeric|min:0',
            'security'           => 'nullable|numeric|min:0',
            'other'              => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->except(['_token', '_method']);

            if ($request->hasFile('image')) {
                $data['image'] = uploadFileDirect($request->file('image'), 'admin/assets/images/flats');
            } else {
                unset($data['image']);
            }

            // fill nulls with 0
            foreach (['house_rent', 'wasa', 'common_electricity', 'gas', 'utility', 'parking', 'society_bill', 'security', 'other'] as $f) {
                $data[$f] = $data[$f] ?? 0;
            }

            $flat->update($data);

            DB::commit();
            return redirect()->route('admin.flats.index', $building->id)
                ->with('success', 'Flat updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($buildingId, $flatId)
    {
        $building = $this->getBuilding($buildingId);
        $flat = $this->getFlat($flatId, $building);
        $flat->delete();
        return redirect()->route('admin.flats.index', $building->id)
            ->with('success', 'Flat deleted successfully.');
    }
}
