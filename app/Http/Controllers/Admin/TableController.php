<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Table\StoreTableRequest;
use App\Http\Requests\Table\UpdateTableRequest;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tables = Table::query()
            ->when($request->search, function ($query, $search) {
                $query->where('table_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->location, function ($query, $location) {
                $query->where('location', $location);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->capacity, function ($query, $capacity) {
                $query->where('capacity', '>=', $capacity);
            })
            ->latest()
            ->paginate(10)
            ->appends(request()->query());

        return view('admin.tables.index', [
            'tables' => $tables,
            'statusOptions' => Table::getStatusOptions(),
            'locationOptions' => Table::getLocationOptions()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.tables.create', [
            'statusOptions' => Table::getStatusOptions(),
            'locationOptions' => Table::getLocationOptions()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTableRequest $request)
    {
        $table = Table::create($request->validated());

        return redirect()
            ->route('admin.tables.index')
            ->with('success', "Meja {$table->table_number} berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(Table $table): View
    {
        return view('admin.tables.show', compact('table'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Table $table): View
    {
        return view('admin.tables.edit', [
            'table' => $table,
            'statusOptions' => Table::getStatusOptions(),
            'locationOptions' => Table::getLocationOptions()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTableRequest $request, Table $table)
    {
        $table->update($request->validated());

        return redirect()
            ->route('admin.tables.index')
            ->with('success', "Meja {$table->table_number} berhasil diperbarui");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        // Cek apakah meja sedang digunakan
        if ($table->status !== Table::STATUS_AVAILABLE) {
            return back()->with('error', "Meja {$table->table_number} tidak dapat dihapus karena sedang digunakan");
        }

        $tableNumber = $table->table_number;
        $table->delete();

        return redirect()
            ->route('admin.tables.index')
            ->with('success', "Meja {$tableNumber} berhasil dihapus");
    }

    /**
     * Update table status.
     */
    public function updateStatus(Request $request, Table $table)
    {
        $request->validate([
            'status' => ['required', 'string', Rule::in([
                Table::STATUS_AVAILABLE,
                Table::STATUS_OCCUPIED,
                Table::STATUS_RESERVED,
                Table::STATUS_MAINTENANCE
            ])]
        ]);

        $table->update(['status' => $request->status]);

        return redirect()
            ->route('admin.tables.index')
            ->with('success', "Status meja {$table->table_number} berhasil diperbarui");
    }

    /**
     * Display the specified resource.
     */
    public function view(Table $table): View
    {
        return view('admin.tables.view', compact('table'));
    }

    /**
     * Get reservations for the specified table.
     */
    public function getReservations(Request $request, Table $table)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $reservations = $table->reservations()
            ->with('user')
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('reservation_date', [$start, $end]);
            })
            ->get();

        return response()->json($reservations);
    }

    /**
     * Get reservations for specific date.
     */
    public function getReservationsByDate(Table $table, string $date)
    {
        $reservations = $table->reservations()
            ->with('user')
            ->whereDate('reservation_date', $date)
            ->get();

        return response()->json($reservations);
    }
} 
