<?php

namespace App\Http\Controllers\AdminDesa;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil admin desa
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
        return view('admin-desa.profile.edit', compact('user'));
    }

    /**
     * Update profil admin desa
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists('profile-photos/' . $user->profile_photo)) {
                Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('profile-photos', $filename, 'public');
            $updateData['profile_photo'] = $filename;
        }

        User::where('id', $user->id)->update($updateData);

        return redirect()->route('admin-desa.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Menampilkan form reset password
     *
     * @return \Illuminate\View\View
     */
    public function showResetPasswordForm()
    {
        return view('admin-desa.profile.reset-password');
    }

    /**
     * Reset password admin desa
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Password saat ini tidak sesuai.');
                }
            }],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin-desa.profile.reset-password')
            ->with('success', 'Password berhasil diubah.');
    }

    /**
     * Remove profile photo
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeProfilePhoto()
    {
        $user = Auth::user();
        
        if ($user->profile_photo && Storage::disk('public')->exists('profile-photos/' . $user->profile_photo)) {
            Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
        }

        User::where('id', $user->id)->update(['profile_photo' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus.'
        ]);
    }
}