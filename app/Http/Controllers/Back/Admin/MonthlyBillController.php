<?php

namespace App\Http\Controllers\Back\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\BillCollection;
use App\Models\Flat;
use App\Models\FlatTenant;
use App\Models\MonthlyBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonthlyBillController extends Controller
{
    private function getFlat($buildingId, $flatId)
    {
        $building = Building::findOrFail($buildingId);
        if (Auth::user()->role !== 'super_admin' && $building->user_id !== Auth::id()) {
            abort(403);
        }
        return Flat::where('building_id', $building->id)->findOrFail($flatId);
    }

    // ─── 1. Bill list for a flat ──────────────────────────────────────

    public function index($buildingId, $flatId)
    {
        $flat     = $this->getFlat($buildingId, $flatId);
        $building = $flat->building;

        $bills = MonthlyBill::where('flat_id', $flat->id)
            ->with('tenant')
            ->orderByDesc('bill_year')
            ->orderByDesc('bill_month_number')
            ->get();

        // Summary
        $totalDue     = $bills->where('collection_status', 'due')->sum('total_amount');
        $totalPartial = $bills->where('collection_status', 'partial')->sum('remaining_amount');
        $totalPaid    = $bills->where('collection_status', 'paid')->count();

        return view('admin.bills.index', compact('building', 'flat', 'bills', 'totalDue', 'totalPartial', 'totalPaid'));
    }

    // ─── 2. Generate bill form ────────────────────────────────────────

    public function create($buildingId, $flatId)
    {
        $flat     = $this->getFlat($buildingId, $flatId);
        $building = $flat->building;

        if ($flat->bill_status !== 'active') {
            return redirect()->route('admin.bills.index', [$buildingId, $flatId])
                ->with('error', 'Bill is inactive for this flat.');
        }

        $activeTenant = FlatTenant::with('tenant')
            ->where('flat_id', $flat->id)
            ->where('status', 'active')
            ->first();

        // Suggest current month
        $suggestedMonth = now()->format('Y-m');

        // Previous due: last bill's remaining
        $lastBill    = MonthlyBill::where('flat_id', $flat->id)->latest('id')->first();
        $previousDue = $lastBill ? (float) $lastBill->remaining_amount : 0;

        return view('admin.bills.create', compact(
            'building', 'flat', 'activeTenant', 'suggestedMonth', 'previousDue'
        ));
    }

    // ─── 3. Store / Generate bill ─────────────────────────────────────

    public function store(Request $request, $buildingId, $flatId)
    {
        $flat = $this->getFlat($buildingId, $flatId);

        $request->validate([
            'bill_month'         => 'required|date_format:Y-m',
            'house_rent'         => 'nullable|numeric|min:0',
            'wasa'               => 'nullable|numeric|min:0',
            'common_electricity' => 'nullable|numeric|min:0',
            'gas'                => 'nullable|numeric|min:0',
            'utility'            => 'nullable|numeric|min:0',
            'parking'            => 'nullable|numeric|min:0',
            'society_bill'       => 'nullable|numeric|min:0',
            'security'           => 'nullable|numeric|min:0',
            'other'              => 'nullable|numeric|min:0',
            'previous_due'       => 'nullable|numeric|min:0',
            'notes'              => 'nullable|string',
        ]);

        // Block duplicate
        $exists = MonthlyBill::where('flat_id', $flat->id)
            ->where('bill_month', $request->bill_month)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bill for this month already exists.');
        }

        $activeFlatTenant = FlatTenant::where('flat_id', $flat->id)->where('status', 'active')->first();

        $rentFields = ['house_rent','wasa','common_electricity','gas','utility','parking','society_bill','security','other'];
        $breakdown  = [];
        $total      = 0;
        foreach ($rentFields as $f) {
            $breakdown[$f] = (float) ($request->$f ?? $flat->$f ?? 0);
            $total        += $breakdown[$f];
        }

        $previousDue = (float) ($request->previous_due ?? 0);
        [$year, $month] = explode('-', $request->bill_month);

        try {
            DB::beginTransaction();

            MonthlyBill::create(array_merge($breakdown, [
                'flat_id'          => $flat->id,
                'building_id'      => $flat->building_id,
                'tenant_id'        => $activeFlatTenant?->tenant_id,
                'flat_tenant_id'   => $activeFlatTenant?->id,
                'bill_month'       => $request->bill_month,
                'bill_year'        => (int) $year,
                'bill_month_number'=> (int) $month,
                'total_amount'     => $total,
                'paid_amount'      => 0,
                'remaining_amount' => $total + $previousDue,
                'previous_due'     => $previousDue,
                'collection_status'=> 'due',
                'notes'            => $request->notes,
                'generated_by'     => Auth::id(),
            ]));

            DB::commit();
            return redirect()->route('admin.bills.index', [$buildingId, $flatId])
                ->with('success', 'Bill generated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ─── 4. View bill detail + collection history ─────────────────────

    public function show($buildingId, $flatId, $billId)
    {
        $flat       = $this->getFlat($buildingId, $flatId);
        $building   = $flat->building;
        $bill       = MonthlyBill::with(['tenant', 'collections.collectedBy'])
            ->where('flat_id', $flat->id)
            ->findOrFail($billId);

        return view('admin.bills.show', compact('building', 'flat', 'bill'));
    }

    // ─── 5. Collect payment form ──────────────────────────────────────

    public function collectForm($buildingId, $flatId, $billId)
    {
        $flat     = $this->getFlat($buildingId, $flatId);
        $building = $flat->building;
        $bill     = MonthlyBill::with('tenant', 'collections')
            ->where('flat_id', $flat->id)
            ->findOrFail($billId);

        if ($bill->collection_status === 'paid') {
            return redirect()->route('admin.bills.show', [$buildingId, $flatId, $billId])
                ->with('error', 'This bill is already fully paid.');
        }

        return view('admin.bills.collect', compact('building', 'flat', 'bill'));
    }

    // ─── 6. Store collection ──────────────────────────────────────────

    public function collectStore(Request $request, $buildingId, $flatId, $billId)
    {
        $flat = $this->getFlat($buildingId, $flatId);
        $bill = MonthlyBill::where('flat_id', $flat->id)->findOrFail($billId);

        $request->validate([
            'amount'                => 'required|numeric|min:1|max:' . $bill->remaining_amount,
            'collection_date'       => 'required|date',
            'payment_method'        => 'required|in:cash,bank,bkash,nagad,rocket,other',
            'transaction_reference' => 'nullable|string|max:200',
            'notes'                 => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            BillCollection::create([
                'monthly_bill_id'       => $bill->id,
                'flat_id'               => $flat->id,
                'tenant_id'             => $bill->tenant_id,
                'amount'                => $request->amount,
                'collection_date'       => $request->collection_date,
                'payment_method'        => $request->payment_method,
                'transaction_reference' => $request->transaction_reference,
                'notes'                 => $request->notes,
                'collected_by'          => Auth::id(),
            ]);

            // recalculate() is auto-called via BillCollection::booted()
            // also stamp collected_by and payment_date on bill if now paid
            $bill->refresh();
            if ($bill->collection_status === 'paid') {
                $bill->update([
                    'payment_date' => $request->collection_date,
                    'collected_by' => Auth::id(),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.bills.show', [$buildingId, $flatId, $billId])
                ->with('success', 'Payment collected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ─── 7. Delete collection entry (correction) ──────────────────────

    public function deleteCollection($buildingId, $flatId, $billId, $collectionId)
    {
        $flat       = $this->getFlat($buildingId, $flatId);
        $bill       = MonthlyBill::where('flat_id', $flat->id)->findOrFail($billId);
        $collection = BillCollection::where('monthly_bill_id', $bill->id)->findOrFail($collectionId);
        $collection->delete(); // booted() recalculates automatically

        return redirect()->route('admin.bills.show', [$buildingId, $flatId, $billId])
            ->with('success', 'Collection entry removed.');
    }

    // ─── 8. Delete entire bill ────────────────────────────────────────

    public function destroy($buildingId, $flatId, $billId)
    {
        $flat = $this->getFlat($buildingId, $flatId);
        $bill = MonthlyBill::where('flat_id', $flat->id)->findOrFail($billId);
        $bill->collections()->delete();
        $bill->delete();

        return redirect()->route('admin.bills.index', [$buildingId, $flatId])
            ->with('success', 'Bill deleted.');
    }
}