<?php

namespace App\Http\Controllers\Back\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillCollection;
use App\Models\Building;
use App\Models\Flat;
use App\Models\MonthlyBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RentOverviewController extends Controller
{
    /**
     * Display a rent overview of all flats belonging to this admin.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // ── 1. Fetch buildings that belong to this admin ──────────────────
        $buildingQuery = Building::with([
            'flats.activeTenant.tenant',
            'flats.monthlyBills' => function ($q) {
                $q->orderByDesc('bill_year')->orderByDesc('bill_month_number');
            }
        ]);
        if ($user->role !== 'super_admin') {
            $buildingQuery->where('user_id', $user->id);
        }

        // Address suggestions for the filter datalist (using a clone of query without eager loads)
        $suggestQuery = Building::query();
        if ($user->role !== 'super_admin') {
            $suggestQuery->where('user_id', $user->id);
        }
        $addressSuggestions = $suggestQuery->pluck('address')->unique()->filter()->values();

        // ── 2. Apply filters ──────────────────────────────────────────────

        // Filter by address (building address)
        if ($request->filled('address')) {
            $buildingQuery->where('address', 'like', '%' . $request->address . '%');
        }

        $paymentFilter = $request->payment_status; // 'paid' | 'pending' | null

        $buildings = $buildingQuery->get();

        // ── 3. Build overview rows grouped by Building ────────────────────
        $rows = $buildings->map(function (Building $building) use ($request) {
            $flats = $building->flats;

            // Filter by occupancy within building
            if ($request->filled('occupancy') && in_array($request->occupancy, ['occupied', 'vacant', 'booked_by_owner'])) {
                $flats = $flats->filter(fn($f) => $f->status === $request->occupancy);
            }

            // Map each flat to its detailed overview
            $flatRows = $flats->map(function (Flat $flat) {
                $bills      = $flat->monthlyBills; // already sorted desc
                $latestBill = $bills->first();

                // Count unpaid bills (due or partial)
                $unpaidBills = $bills->filter(fn($b) => $b->collection_status !== 'paid');
                $overdueCount   = $unpaidBills->count();
                $totalOutstanding = $unpaidBills->sum('remaining_amount');

                // Determine display status
                if ($flat->status === 'booked_by_owner') {
                    $displayStatus = 'booked_by_owner';
                } elseif (!$latestBill) {
                    $displayStatus = 'no_bill';
                } else {
                    $displayStatus = $latestBill->collection_status;
                }

                $activeTenant = $flat->activeTenant;
                $tenantName   = $activeTenant?->tenant?->name ?? null;
                $tenantPhone  = $activeTenant?->tenant?->phone ?? null;

                return [
                    'flat'             => $flat,
                    'tenant_name'      => $tenantName,
                    'tenant_phone'     => $tenantPhone,
                    'total_rent'       => $flat->total_rent,
                    'latest_bill'      => $latestBill,
                    'display_status'   => $displayStatus,
                    'overdue_count'    => $overdueCount,
                    'total_outstanding'=> (float) $totalOutstanding,
                ];
            })->values();

            // Determine building level rent status:
            // "if all the rents of flats of that building is paid, then it will show paid, else it will show due"
            // A building is 'due' if any of its flats is 'due' or 'partial'.
            $hasDue = $flatRows->contains(fn($fr) => in_array($fr['display_status'], ['due', 'partial']));
            $buildingStatus = $hasDue ? 'due' : 'paid';

            return [
                'building'          => $building,
                'flats'             => $flatRows,
                'status'            => $buildingStatus,
                'total_outstanding' => (float) $flatRows->sum('total_outstanding'),
                'total_rent'        => (float) $flatRows->sum('total_rent'),
                'occupied_count'    => $flatRows->filter(fn($fr) => $fr['flat']->status === 'occupied')->count(),
                'vacant_count'      => $flatRows->filter(fn($fr) => $fr['flat']->status === 'vacant')->count(),
                'booked_count'      => $flatRows->filter(fn($fr) => $fr['flat']->status === 'booked_by_owner')->count(),
            ];
        });

        // ── 4. Apply Occupancy filter pruning ─────────────────────────────
        // If occupancy filter was set, we prune out buildings that have no matching flats
        if ($request->filled('occupancy')) {
            $rows = $rows->filter(fn($r) => $r['flats']->isNotEmpty());
        }

        // ── 5. Apply payment status filter (post-fetch) ───────────────────
        if ($paymentFilter === 'paid') {
            $rows = $rows->filter(fn($r) => $r['status'] === 'paid');
        } elseif ($paymentFilter === 'pending') {
            $rows = $rows->filter(fn($r) => $r['status'] === 'due');
        }

        // ── 6. Sort: due status first, paid last ──────────────────────────
        $rows = $rows->map(function ($r) {
            $r['sort_weight'] = ($r['status'] === 'due') ? 0 : 1;
            return $r;
        });
        $rows = $rows->sortBy('sort_weight')->values();

        return view('admin.rent_overview.index', compact(
            'rows',
            'addressSuggestions',
            'paymentFilter'
        ));
    }

    /**
     * Toggle a bill to fully paid via a BillCollection entry.
     */
    public function togglePaid(Request $request, $billId)
    {
        $user = Auth::user();

        $bill = MonthlyBill::with('flat.building')->findOrFail($billId);

        // Authorization
        if ($user->role !== 'super_admin' && $bill->flat->building->user_id !== $user->id) {
            abort(403);
        }

        if ($bill->collection_status === 'paid') {
            return redirect()->route('admin.rent.overview')
                ->with('error', 'Bill is already marked as paid.');
        }

        $remaining = (float) $bill->remaining_amount;
        if ($remaining <= 0) {
            return redirect()->route('admin.rent.overview')
                ->with('error', 'No outstanding amount to settle.');
        }

        try {
            DB::beginTransaction();

            BillCollection::create([
                'monthly_bill_id'       => $bill->id,
                'flat_id'               => $bill->flat_id,
                'tenant_id'             => $bill->tenant_id,
                'amount'                => $remaining,
                'collection_date'       => now()->toDateString(),
                'payment_method'        => 'cash',
                'transaction_reference' => null,
                'notes'                 => 'Marked as paid from Rent Overview.',
                'collected_by'          => $user->id,
            ]);

            // BillCollection::booted() triggers recalculate() automatically
            $bill->refresh();
            if ($bill->collection_status === 'paid') {
                $bill->update([
                    'payment_date' => now()->toDateString(),
                    'collected_by' => $user->id,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.rent.overview')
                ->with('success', 'Bill marked as paid successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.rent.overview')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
