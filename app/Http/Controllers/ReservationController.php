<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Reservation::query()
            ->with(['user', 'table'])
            ->when($request->search, function ($query, $search) {
                $query->where('reservation_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->date, function ($query, $date) {
                $query->whereDate('reservation_date', $date);
            });

        // Jika user adalah customer, hanya tampilkan reservasi miliknya
        if (Auth::user()->role === 'customer') {
            $query->where('user_id', Auth::id());
        }

        $reservations = $query->latest()->paginate(10);

        return view('reservations.index', [
            'reservations' => $reservations,
            'statusOptions' => Reservation::getStatusOptions()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Ambil meja yang tersedia
        $availableTables = Table::where('status', 'available')
            ->orderBy('table_number')
            ->get();

        return view('reservations.create', [
            'tables' => $availableTables
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        $validated = $request->validated();
        
        // Generate kode reservasi
        $validated['reservation_code'] = 'RES' . strtoupper(Str::random(8));
        $validated['user_id'] = Auth::id();
        $validated['status'] = Reservation::STATUS_PENDING;

        $reservation = Reservation::create($validated);

        // Update status meja menjadi reserved
        $reservation->table->update(['status' => 'reserved']);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Reservasi berhasil dibuat. Silahkan tunggu konfirmasi dari admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation): View
    {
        // Pastikan user hanya bisa melihat reservasi miliknya sendiri
        if (Auth::user()->role === 'customer' && $reservation->user_id !== Auth::id()) {
            abort(403);
        }

        return view('reservations.show', [
            'reservation' => $reservation->load(['user', 'table'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $validated = $request->validated();

        // Jika status berubah menjadi cancelled atau rejected, update status meja
        if (in_array($validated['status'] ?? null, [Reservation::STATUS_CANCELLED, Reservation::STATUS_REJECTED])) {
            $reservation->table->update(['status' => 'available']);
        }
        
        // Jika status berubah menjadi completed, update status meja
        if (($validated['status'] ?? null) === Reservation::STATUS_COMPLETED) {
            $reservation->table->update(['status' => 'available']);
        }

        $reservation->update($validated);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Reservasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        // Hanya admin yang bisa menghapus reservasi
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Jika reservasi masih aktif, kembalikan status meja menjadi available
        if (!in_array($reservation->status, [Reservation::STATUS_COMPLETED, Reservation::STATUS_CANCELLED, Reservation::STATUS_REJECTED])) {
            $reservation->table->update(['status' => 'available']);
        }

        $reservation->delete();

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Reservasi berhasil dihapus.');
    }

    /**
     * Approve reservation
     */
    public function approve(Reservation $reservation)
    {
        // Hanya admin yang bisa menyetujui reservasi
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Pastikan reservasi masih pending
        if ($reservation->status !== Reservation::STATUS_PENDING) {
            return back()->with('error', 'Hanya reservasi dengan status pending yang dapat disetujui.');
        }

        $reservation->update(['status' => Reservation::STATUS_APPROVED]);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Reservasi berhasil disetujui.');
    }

    /**
     * Reject reservation
     */
    public function reject(Request $request, Reservation $reservation)
    {
        // Hanya admin yang bisa menolak reservasi
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Pastikan reservasi masih pending
        if ($reservation->status !== Reservation::STATUS_PENDING) {
            return back()->with('error', 'Hanya reservasi dengan status pending yang dapat ditolak.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500']
        ]);

        $reservation->update([
            'status' => Reservation::STATUS_REJECTED,
            'rejection_reason' => $validated['rejection_reason']
        ]);

        // Kembalikan status meja menjadi available
        $reservation->table->update(['status' => 'available']);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Reservasi berhasil ditolak.');
    }

    /**
     * Complete reservation
     */
    public function complete(Reservation $reservation)
    {
        // Hanya admin yang bisa menyelesaikan reservasi
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Pastikan reservasi sudah disetujui
        if ($reservation->status !== Reservation::STATUS_APPROVED) {
            return back()->with('error', 'Hanya reservasi yang sudah disetujui yang dapat diselesaikan.');
        }

        $reservation->update(['status' => Reservation::STATUS_COMPLETED]);

        // Kembalikan status meja menjadi available
        $reservation->table->update(['status' => 'available']);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Reservasi berhasil diselesaikan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation): View
    {
        // Pastikan user hanya bisa mengedit reservasi miliknya sendiri
        if (Auth::user()->role === 'customer' && $reservation->user_id !== Auth::id()) {
            abort(403);
        }

        // Pastikan hanya reservasi dengan status pending yang bisa diedit oleh customer
        if (Auth::user()->role === 'customer' && $reservation->status !== Reservation::STATUS_PENDING) {
            return redirect()
                ->route('reservations.show', $reservation)
                ->with('error', 'Hanya reservasi dengan status menunggu konfirmasi yang dapat diubah.');
        }

        return view('reservations.edit', [
            'reservation' => $reservation
        ]);
    }
} 