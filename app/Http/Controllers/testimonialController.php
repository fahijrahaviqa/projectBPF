<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Testimonial;
use App\Http\Requests\Testimonial\StoreTestimonialRequest;
use App\Http\Requests\Testimonial\UpdateTestimonialRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $testimonials = Testimonial::with(['order.user'])
            ->latest()
            ->paginate(10);

        return view('testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        // Inisialisasi variabel
        $order = null;
        $availableOrders = collect();

        // Jika ada order_id dari URL, validasi dulu
        if ($request->has('order_id')) {
            $order = Order::where('id', $request->order_id)
                ->where('user_id', auth()->id())
                ->where('status', 'completed')
                ->whereDoesntHave('testimonial')
                ->firstOrFail();
        }

        // Jika tidak ada order_id atau order tidak valid, ambil semua order yang available
        if (!$order) {
            $availableOrders = Order::where('user_id', auth()->id())
                ->where('status', 'completed')
                ->whereDoesntHave('testimonial')
                ->get();

            if ($availableOrders->isEmpty()) {
                return redirect()->route('orders.index')
                    ->with('error', 'Tidak ada pesanan yang dapat direview saat ini.');
            }
        }

        return view('testimonials.create', compact('order', 'availableOrders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTestimonialRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('testimonials', 'public');
        }

        $validated['is_published'] = false; // Perlu disetujui admin dulu

        Testimonial::create($validated);

        return redirect()->route('testimonial.index')
            ->with('success', 'Testimonial berhasil dikirim dan menunggu persetujuan admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial): View
    {
        abort_if(
            !$testimonial->is_published && 
            $testimonial->order->user_id !== auth()->id(), 
            403
        );
        
        return view('testimonials.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial): View
    {
        $this->authorize('update', $testimonial);
        
        return view('testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($testimonial->image) {
                Storage::disk('public')->delete($testimonial->image);
            }
            $validated['image'] = $request->file('image')->store('testimonials', 'public');
        }

        $testimonial->update($validated);

        return redirect()->route('testimonial.show', $testimonial)
            ->with('success', 'Testimonial berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        $this->authorize('delete', $testimonial);

        if ($testimonial->image) {
            Storage::disk('public')->delete($testimonial->image);
        }

        $testimonial->delete();

        return redirect()->route('testimonial.index')
            ->with('success', 'Testimonial berhasil dihapus.');
    }

    /**
     * Publish the specified testimonial.
     */
    public function publish(Testimonial $testimonial)
    {
        // Hanya admin yang bisa publish
        abort_if(!auth()->user()->isAdmin(), 403);

        $testimonial->update(['is_published' => true]);

        return response()->json([
            'message' => 'Testimonial berhasil dipublikasikan',
            'testimonial' => $testimonial
        ]);
    }

    /**
     * Display published testimonials on landing page.
     */
    public function landing(): View
    {
        $testimonials = Testimonial::with(['order.user'])
            ->where('is_published', true)
            ->latest()
            ->get();

        return view('testimonial', compact('testimonials'));
    }
}
