<?php

namespace App\Http\Controllers\Back\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\UtilityBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UtilityBillController extends Controller
{
    private function getBuilding($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        if (Auth::user()->role !== 'super_admin' && $building->user_id !== Auth::id()) {
            abort(403);
        }
        return $building;
    }

    private function getBill($buildingId, $billId)
    {
        $building = $this->getBuilding($buildingId);
        return UtilityBill::where('building_id', $building->id)->findOrFail($billId);
    }

    private function validationRules(bool $isUpdate = false): array
    {
        return [
            'bill_type'             => 'required|in:wasa,titas_gas,holding_tax,electricity,other',
            'billing_name'          => 'nullable|string|max:200',
            'bill_month'            => 'nullable|date_format:Y-m',
            'bill_year'             => 'nullable|digits:4',
            'invoice_number'        => 'nullable|string|max:100',
            'due_date'              => 'nullable|date',
            'total_amount'          => 'required|numeric|min:0',
            'paid_amount'           => 'nullable|numeric|min:0',
            'payment_method'        => 'nullable|in:cash,bank,bkash,nagad,rocket,other',
            'payment_date'          => 'nullable|date',
            'transaction_reference' => 'nullable|string|max:200',
            'document'              => 'nullable|file|mimes:pdf,jpg,png|max:3072',
            'notes'                 => 'nullable|string',
        ];
    }

    // ─── 1. Index ─────────────────────────────────────────────────────

    public function index(Request $request, $buildingId)
    {
        $building = $this->getBuilding($buildingId);

        $query = UtilityBill::where('building_id', $building->id)->latest();

        if ($request->filled('type')) {
            $query->where('bill_type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        $bills = $query->get();

        $summary = [
            'total_due'     => $bills->where('payment_status', 'due')->sum('total_amount'),
            'total_partial' => $bills->where('payment_status', 'partial')->sum('remaining_amount'),
            'total_paid'    => $bills->where('payment_status', 'paid')->count(),
        ];

        return view('admin.utility.index', compact('building', 'bills', 'summary'));
    }

    // ─── 2. Create ────────────────────────────────────────────────────

    public function create($buildingId)
    {
        $building = $this->getBuilding($buildingId);
        return view('admin.utility.create', compact('building'));
    }

    // ─── 3. Store ─────────────────────────────────────────────────────

    public function store(Request $request, $buildingId)
    {
        $building = $this->getBuilding($buildingId);
        $request->validate($this->validationRules());

        try {
            DB::beginTransaction();

            $paidAmount      = (float) ($request->paid_amount ?? 0);
            $totalAmount     = (float) $request->total_amount;
            $remainingAmount = max(0, $totalAmount - $paidAmount);

            UtilityBill::create([
                'building_id'           => $building->id,
                'bill_type'             => $request->bill_type,
                'billing_name'          => $request->billing_name,
                'bill_month'            => $request->bill_month,
                'bill_year'             => $request->bill_year,
                'invoice_number'        => $request->invoice_number,
                'due_date'              => $request->due_date,
                'total_amount'          => $totalAmount,
                'paid_amount'           => $paidAmount,
                'remaining_amount'      => $remainingAmount,
                'payment_status'        => billCollectionStatus($totalAmount, $paidAmount),
                'payment_date'          => $request->payment_date,
                'payment_method'        => $request->payment_method,
                'transaction_reference' => $request->transaction_reference,
                'document'              => $request->hasFile('document')
                    ? $request->file('document')->store('admin/assets/documents/utility-bills', 'public')
                    : null,
                'notes'                 => $request->notes,
                'created_by'            => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('admin.utility.index', $building->id)
                ->with('success', 'Utility bill added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ─── 4. Edit ──────────────────────────────────────────────────────

    public function edit($buildingId, $billId)
    {
        $building = $this->getBuilding($buildingId);
        $bill     = $this->getBill($buildingId, $billId);
        return view('admin.utility.edit', compact('building', 'bill'));
    }

    // ─── 5. Update ────────────────────────────────────────────────────

    public function update(Request $request, $buildingId, $billId)
    {
        $building = $this->getBuilding($buildingId);
        $bill     = $this->getBill($buildingId, $billId);
        $request->validate($this->validationRules(true));

        try {
            DB::beginTransaction();

            $paidAmount      = (float) ($request->paid_amount ?? 0);
            $totalAmount     = (float) $request->total_amount;
            $remainingAmount = max(0, $totalAmount - $paidAmount);

            $data = [
                'bill_type'             => $request->bill_type,
                'billing_name'          => $request->billing_name,
                'bill_month'            => $request->bill_month,
                'bill_year'             => $request->bill_year,
                'invoice_number'        => $request->invoice_number,
                'due_date'              => $request->due_date,
                'total_amount'          => $totalAmount,
                'paid_amount'           => $paidAmount,
                'remaining_amount'      => $remainingAmount,
                'payment_status'        => billCollectionStatus($totalAmount, $paidAmount),
                'payment_date'          => $request->payment_date,
                'payment_method'        => $request->payment_method,
                'transaction_reference' => $request->transaction_reference,
                'notes'                 => $request->notes,
            ];

            if ($request->hasFile('document')) {
                $data['document'] = $request->file('document')->store('admin/assets/documents/utility-bills', 'public');
            }

            $bill->update($data);

            DB::commit();
            return redirect()->route('admin.utility.index', $building->id)
                ->with('success', 'Utility bill updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ─── 6. Quick pay (mark fully paid via AJAX-friendly POST) ────────

    public function markPaid(Request $request, $buildingId, $billId)
    {
        $bill = $this->getBill($buildingId, $billId);

        $request->validate([
            'payment_date'   => 'required|date',
            'payment_method' => 'required|in:cash,bank,bkash,nagad,rocket,other',
            'transaction_reference' => 'nullable|string|max:200',
        ]);

        $bill->update([
            'paid_amount'           => $bill->total_amount,
            'remaining_amount'      => 0,
            'payment_status'        => 'paid',
            'payment_date'          => $request->payment_date,
            'payment_method'        => $request->payment_method,
            'transaction_reference' => $request->transaction_reference,
        ]);

        return redirect()->route('admin.utility.index', $buildingId)
            ->with('success', 'Bill marked as paid.');
    }

    // ─── 7. Destroy ───────────────────────────────────────────────────

    public function destroy($buildingId, $billId)
    {
        $bill = $this->getBill($buildingId, $billId);
        $bill->delete();
        return redirect()->route('admin.utility.index', $buildingId)
            ->with('success', 'Utility bill deleted.');
    }
}