<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $fileCount = File::where('user_id', $user->id)
            ->where('is_folder', false)
            ->whereNull('deleted_at')
            ->count();
        $storageUsed = File::where('user_id', $user->id)
            ->where('is_folder', false)
            ->whereNull('deleted_at')
            ->sum('size');

        return view('profile.edit', [
            'user' => $user,
            'fileCount' => $fileCount,
            'storageUsed' => $storageUsed,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();

        if ($request->has('avatar_base64')) {
            $user->avatar = $request->input('avatar_base64');
            $user->save();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Foto profil berhasil diperbarui.',
                    'avatar' => $user->avatar,
                ]);
            }
        }

        if ($request->filled('name')) {
            $user->name = $request->input('name');
        }

        if ($request->filled('email') && $request->input('email') !== $user->email) {
            $user->email = $request->input('email');
            $user->email_verified_at = null;
        }

        $user->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui.',
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Hapus avatar user.
     */
    public function deleteAvatar(Request $request)
    {
        $user = $request->user();
        $user->avatar = null;
        $user->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil dihapus.',
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'avatar-deleted');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Hapus file fisik dari disk
        $files = File::withTrashed()->where('user_id', $user->id)->get();
        foreach ($files as $file) {
            if (!$file->is_folder && $file->existsOnDisk()) {
                Storage::disk('local')->delete($file->storage_path);
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
