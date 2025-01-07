<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:users,name,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);

        try {
            // Handle photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }

                // Store new photo
                $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
                $validated['profile_photo'] = $photoPath;
            }

            // Remove photo from validated data if not provided
            if (!$request->hasFile('profile_photo')) {
                unset($validated['profile_photo']);
            }

            $user->update($validated);

            return redirect()
                ->back()
                ->with('success', 'Profile berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui profile');
        }
    }
} 