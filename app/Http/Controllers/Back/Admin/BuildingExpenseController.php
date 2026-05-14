<?php

namespace App\Http\Controllers\Back\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\BuildingExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
 
class BuildingExpenseController extends Controller
{
    private function getBuilding($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        if (Auth::user()->role !== 'super_admin' && $building->user_id !== Auth::id()) {
            abort(403);
        }
        return $building;
    }
 
    private function validationRules(): array
    {
        return [
            'expense_month'        => 'required|date_format:Y-m',
            'security_bill'        => 'nullable|numeric|min:0',
            'cleaning_bill'        => 'nullable|numeric|min:0',
            'cleaning_material'    => 'nullable|numeric|min:0',
            'maintenance'          => 'nullable|numeric|min:0',
            'eid_bonus'            => 'nullable|numeric|min:0',
            'material_replacement' => 'nullable|numeric|min:0',
            'flat_cleaning'        => 'nullable|numeric|min:0',
            'society_cost'         => 'nullable|numeric|min:0',
            'driver_cost'          => 'nullable|numeric|min:0',
            'other'                => 'nullable|numeric|min:0',
            'notes'                => 'nullable|string',
        ];
    }
 
    private function calcTotal(Request $request): float
    {
        $total = 0;
        foreach (array_keys(BuildingExpense::$expenseFields) as $field) {
            $total += (float) ($request->$field ?? 0);
        }
        return $total;
    }
 
    // ─── 1. Index ─────────────────────────────────────────────────────
 
    public function index(Request $request, $buildingId)
    {
        $building = $this->getBuilding($buildingId);
 
        $query = BuildingExpense::where('building_id', $building->id)
            ->orderByDesc('expense_year')
            ->orderByDesc('expense_month_number');
 
        if ($request->filled('year')) {
            $query->where('expense_year', $request->year);
        }
 
        $expenses = $query->get();
 
        $yearlyTotal = $expenses->sum('total_expense');
        $years       = BuildingExpense::where('building_id', $building->id)
            ->distinct()->pluck('expense_year')->sortDesc();
 
        return view('admin.expenses.index', compact('building', 'expenses', 'yearlyTotal', 'years'));
    }
 
    // ─── 2. Create ────────────────────────────────────────────────────
 
    public function create($buildingId)
    {
        $building      = $this->getBuilding($buildingId);
        $suggestedMonth = now()->format('Y-m');
        $expenseFields  = BuildingExpense::$expenseFields;
        return view('admin.expenses.create', compact('building', 'suggestedMonth', 'expenseFields'));
    }
 
    // ─── 3. Store ─────────────────────────────────────────────────────
 
    public function store(Request $request, $buildingId)
    {
        $building = $this->getBuilding($buildingId);
        $request->validate($this->validationRules());
 
        $exists = BuildingExpense::where('building_id', $building->id)
            ->where('expense_month', $request->expense_month)
            ->exists();
 
        if ($exists) {
            return back()->with('error', 'Expense for this month already exists. Please edit it.');
        }
 
        [$year, $month] = explode('-', $request->expense_month);
 
        try {
            DB::beginTransaction();
 
            $data = ['building_id' => $building->id, 'expense_month' => $request->expense_month,
                     'expense_year' => (int)$year, 'expense_month_number' => (int)$month,
                     'total_expense' => $this->calcTotal($request),
                     'notes' => $request->notes, 'created_by' => Auth::id()];
 
            foreach (array_keys(BuildingExpense::$expenseFields) as $field) {
                $data[$field] = (float)($request->$field ?? 0);
            }
 
            BuildingExpense::create($data);
 
            DB::commit();
            return redirect()->route('admin.expenses.index', $building->id)
                ->with('success', 'Expense record saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }
 
    // ─── 4. Edit ──────────────────────────────────────────────────────
 
    public function edit($buildingId, $expenseId)
    {
        $building     = $this->getBuilding($buildingId);
        $expense      = BuildingExpense::where('building_id', $building->id)->findOrFail($expenseId);
        $expenseFields = BuildingExpense::$expenseFields;
        return view('admin.expenses.edit', compact('building', 'expense', 'expenseFields'));
    }
 
    // ─── 5. Update ────────────────────────────────────────────────────
 
    public function update(Request $request, $buildingId, $expenseId)
    {
        $building = $this->getBuilding($buildingId);
        $expense  = BuildingExpense::where('building_id', $building->id)->findOrFail($expenseId);
        $request->validate($this->validationRules());
 
        [$year, $month] = explode('-', $request->expense_month);
 
        try {
            DB::beginTransaction();
 
            $data = ['expense_month' => $request->expense_month,
                     'expense_year' => (int)$year, 'expense_month_number' => (int)$month,
                     'total_expense' => $this->calcTotal($request), 'notes' => $request->notes];
 
            foreach (array_keys(BuildingExpense::$expenseFields) as $field) {
                $data[$field] = (float)($request->$field ?? 0);
            }
 
            $expense->update($data);
 
            DB::commit();
            return redirect()->route('admin.expenses.index', $building->id)
                ->with('success', 'Expense record updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }
 
    // ─── 6. Destroy ───────────────────────────────────────────────────
 
    public function destroy($buildingId, $expenseId)
    {
        $building = $this->getBuilding($buildingId);
        $expense  = BuildingExpense::where('building_id', $building->id)->findOrFail($expenseId);
        $expense->delete();
        return redirect()->route('admin.expenses.index', $building->id)
            ->with('success', 'Expense record deleted.');
    }
}