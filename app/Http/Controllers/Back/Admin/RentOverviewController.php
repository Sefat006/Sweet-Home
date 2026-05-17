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
        $buildingQuery = Building::query();
        if ($user->role !== 'super_admin') {
            $buildingQuery->where('user_id', $user->id);
        }

        // Address suggestions for the filter datalist
        $addressSuggestions = (clone $buildingQuery)->pluck('address')->unique()->filter()->values();

        // ── 2. Load flats with all needed relations in one go ─────────────
        $flatsQuery = Flat::with([
            'building',
            'activeTenant.tenant',
            'monthlyBills' => function ($q) {
                $q->orderByDesc('bill_year')->orderByDesc('bill_month_number');
            },
        ])->whereHas('building', function ($q) use ($user) {
            if ($user->role !== 'super_admin') {
                $q->where('user_id', $user->id);
            }
        });

        // ── 3. Apply filters ──────────────────────────────────────────────

        // Filter by address (building address)
        if ($request->filled('address')) {
            $flatsQuery->whereHas('building', function ($q) use ($request) {
                $q->where('address', 'like', '%' . $request->address . '%');
            });
        }

        // Filter by occupancy: occupied | vacant
        if ($request->filled('occupancy') && in_array($request->occupancy, ['occupied', 'vacant'])) {
            $flatsQuery->where('status', $request->occupancy);
        }

        // Filter by payment status: paid | pending (due + partial)
        // We resolve this after fetching (it's computed) but we can pre-filter via sub-query
        $paymentFilter = $request->payment_status; // 'paid' | 'pending' | null

        $flats = $flatsQuery->get();

        // ── 4. Build overview rows ────────────────────────────────────────
        $rows = $flats->map(function (Flat $flat) {
            $bills      = $flat->monthlyBills; // already sorted desc
            $latestBill = $bills->first();

            // Count unpaid bills (due or partial)
            $unpaidBills = $bills->filter(fn($b) => $b->collection_status !== 'paid');
            $overdueCount   = $unpaidBills->count();
            $totalOutstanding = $unpaidBills->sum('remaining_amount');

            // Determine display status
            if (!$latestBill) {
                $displayStatus = 'no_bill';
            } else {
                $displayStatus = $latestBill->collection_status; // paid | partial | due
            }

            // Sort weight: due=0, partial=1, no_bill=2, paid=3
            $sortWeight = match ($displayStatus) {
                'due'     => 0,
                'partial' => 1,
                'no_bill' => 2,
                'paid'    => 3,
                default   => 4,
            };

            $activeTenant = $flat->activeTenant;
            $tenantName   = $activeTenant?->tenant?->name ?? null;
            $tenantPhone  = $activeTenant?->tenant?->phone ?? null;

            return [
                'flat'             => $flat,
                'building'         => $flat->building,
                'tenant_name'      => $tenantName,
                'tenant_phone'     => $tenantPhone,
                'total_rent'       => $flat->total_rent,
                'latest_bill'      => $latestBill,
                'display_status'   => $displayStatus,
                'overdue_count'    => $overdueCount,
                'total_outstanding'=> (float) $totalOutstanding,
                'sort_weight'      => $sortWeight,
            ];
        });

        // ── 5. Apply payment status filter (post-fetch) ───────────────────
        if ($paymentFilter === 'paid') {
            $rows = $rows->filter(fn($r) => $r['display_status'] === 'paid');
        } elseif ($paymentFilter === 'pending') {
            $rows = $rows->filter(fn($r) => in_array($r['display_status'], ['due', 'partial', 'no_bill']));
        }

        // ── 6. Sort: pending first, paid last ─────────────────────────────
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
