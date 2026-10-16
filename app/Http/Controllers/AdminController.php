<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\File;

class AdminController extends Controller
{
    /**
     * Dashboard: statistik + list user.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                $user->file_count = File::where('user_id', $user->id)
                    ->where('is_folder', false)
                    ->whereNull('deleted_at')
                    ->count();
                $user->storage_used = File::where('user_id', $user->id)
                    ->where('is_folder', false)
                    ->whereNull('deleted_at')
                    ->sum('size');
                return $user;
            });

        $stats = [
            'total_users' => User::count(),
            'total_files' => File::where('is_folder', false)->whereNull('deleted_at')->count(),
            'total_size' => File::where('is_folder', false)->whereNull('deleted_at')->sum('size'),
            'total_shared' => File::whereNotNull('share_token')->count(),
            'total_trash' => File::onlyTrashed()->count(),
        ];

        return view('admin.dashboard', compact('users', 'stats'));
    }

    /**
     * Update quota user.
     */
    public function updateQuota(Request $request, $id)
    {
        try {
            $request->validate([
                'quota_gb' => 'required|numeric|min:0.1|max:1000',
            ]);

            $user = User::findOrFail($id);

            // Convert GB ke bytes
            $bytes = (int) round($request->quota_gb * 1024 * 1024 * 1024);
            $user->quota = $bytes;
            $user->save();

            Log::info('Admin update quota', [
                'admin_id' => Auth::id(),
                'target_user_id' => $user->id,
                'new_quota_bytes' => $bytes,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kuota berhasil diperbarui.',
                'quota_gb' => $request->quota_gb,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Nilai kuota tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Admin update quota error', [
                'message' => $e->getMessage(),
                'user_id' => $id,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kuota.',
            ], 500);
        }
    }

    /**
     * Toggle disable/enable user.
     */
    public function toggleDisable($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa menonaktifkan akun sendiri.',
                ], 400);
            }

            $user->is_disabled = !$user->is_disabled;
            $user->save();

            return response()->json([
                'success' => true,
                'is_disabled' => $user->is_disabled,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status user.',
            ], 500);
        }
    }

    /**
     * Hapus user (cascade ke files).
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa menghapus akun sendiri.',
                ], 400);
            }

            // Hapus file fisik dulu
            $files = File::withTrashed()->where('user_id', $user->id)->get();
            foreach ($files as $file) {
                if (!$file->is_folder && $file->existsOnDisk()) {
                    Storage::disk('local')->delete($file->storage_path);
                }
            }

            Log::warning('Admin delete user', [
                'admin_id' => Auth::id(),
                'target_email' => $user->email,
                'files_deleted' => $files->count(),
            ]);

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User dan semua file-nya berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            Log::error('Admin delete user error', [
                'message' => $e->getMessage(),
                'user_id' => $id,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user.',
            ], 500);
        }
    }

    /**
     * Reset all files of user (keep account).
     */
    public function resetFiles($id)
    {
        try {
            $user = User::findOrFail($id);
            $files = File::withTrashed()->where('user_id', $user->id)->get();

            foreach ($files as $file) {
                if (!$file->is_folder && $file->existsOnDisk()) {
                    Storage::disk('local')->delete($file->storage_path);
                }
                $file->forceDelete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Semua file user berhasil direset.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset file user.',
            ], 500);
        }
    }
}
