<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\File;

class FileController extends Controller
{
    /**
     * Halaman file manager.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $category = $request->input('category', 'all');
        $folderId = $request->input('folder', null);
        $search = $request->input('search', '');
        
        $query = File::where('user_id', $userId)
            ->whereNull('deleted_at') // exclude trash
            ->orderBy('is_folder', 'desc') // folder first
            ->orderBy('created_at', 'desc');
        
        if ($folderId) {
            $query->where('parent_id', $folderId);
        } else {
            $query->whereNull('parent_id');
        }

        // Search filter (kalau ada)
        if (!empty($search)) {
            $query->where('original_name', 'LIKE', '%' . $search . '%');
        }
        
        if ($category !== 'all' && $category !== 'folder' && empty($search)) {
            $query->where('is_folder', false)
                  ->where(function ($q) use ($category) {
                      $mimes = match ($category) {
                          'image' => ['image/%'],
                          'video' => ['video/%'],
                          'audio' => ['audio/%'],
                          'document' => [
                              'application/pdf',
                              'application/msword',
                              'application/vnd.openxmlformats-officedocument.%',
                              'application/vnd.ms-%',
                              'text/%',
                              'application/rtf',
                          ],
                          default => [],
                      };
                      foreach ($mimes as $m) {
                          $q->orWhere('mime_type', 'like', $m);
                      }
                  });
        } elseif ($category === 'folder') {
            $query->where('is_folder', true);
        }
        
        $files = $query->get();
        
        // Breadcrumb kalau di dalam folder
        $breadcrumb = [];
        if ($folderId) {
            $current = File::where('id', $folderId)->where('user_id', $userId)->first();
            while ($current) {
                array_unshift($breadcrumb, ['id' => $current->id, 'name' => $current->original_name]);
                $current = $current->parent_id ? File::where('id', $current->parent_id)->where('user_id', $userId)->first() : null;
            }
        }
        
        $totalFiles = File::where('user_id', $userId)->whereNull('deleted_at')->where('is_folder', false)->count();
        $totalSize = File::where('user_id', $userId)->whereNull('deleted_at')->where('is_folder', false)->sum('size');
        
        return view('files.index', compact('files', 'category', 'totalFiles', 'totalSize', 'folderId', 'breadcrumb'));
    }

    /**
     * Handle upload.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:2048000', // 100MB max
                'parent_id' => 'nullable|exists:files,id',
            ]);
            
            $userId = Auth::id();
            $uploadedFile = $request->file('file');
            
            // Cek quota
            $currentUsage = File::where('user_id', $userId)->whereNull('deleted_at')->where('is_folder', false)->sum('size');
            $quota = Auth::user()->storage_quota;
            $fileSize = $uploadedFile->getSize();
            
            if ($currentUsage + $fileSize > $quota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kuota penyimpanan tidak cukup. Hapus beberapa file dulu.',
                ], 400);
            }
            
            // Generate stored name
            $extension = $uploadedFile->getClientOriginalExtension();
            $storedName = Str::uuid() . ($extension ? '.' . $extension : '');
            
            // Simpan di storage/app/private/files/{user_id}/
            $path = 'files/' . $userId;
            $uploadedFile->storeAs($path, $storedName, 'local');
            
            // Simpan metadata di DB
            $file = File::create([
                'user_id' => $userId,
                'parent_id' => $request->parent_id,
                'original_name' => $uploadedFile->getClientOriginalName(),
                'stored_name' => $storedName,
                'mime_type' => $uploadedFile->getMimeType(),
                'size' => $fileSize,
                'extension' => $extension,
                'is_folder' => false,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'File berhasil diupload.',
                'file' => [
                    'id' => $file->id,
                    'name' => $file->original_name,
                    'size' => $file->human_size,
                    'icon' => $file->icon,
                    'icon_color' => $file->icon_color,
                ],
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak valid atau melebihi 100MB.',
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('File upload error', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload file. Coba lagi.',
            ], 500);
        }
    }

    /**
     * Handle chunked upload for large files.
     */
    public function uploadChunk(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file',
                'chunk_index' => 'required|integer',
                'total_chunks' => 'required|integer',
                'file_name' => 'required|string',
                'upload_id' => 'required|string',
                'parent_id' => 'nullable|exists:files,id',
            ]);

            $chunk = $request->file('file');
            $chunkIndex = $request->chunk_index;
            $totalChunks = $request->total_chunks;
            $uploadId = $request->upload_id;
            $fileName = $request->file_name;
            
            // Folder sementara untuk menyimpan potongan
            $chunkDir = storage_path('app/chunks/' . $uploadId);
            if (!file_exists($chunkDir)) {
                mkdir($chunkDir, 0775, true);
            }

            // Simpan potongan file
            $chunk->move($chunkDir, $chunkIndex . '.part');

            // Jika ini adalah potongan terakhir, gabungkan semuanya
            if ($chunkIndex == $totalChunks - 1) {
                $finalFilePath = $chunkDir . '/' . $fileName;
                $out = fopen($finalFilePath, 'wb');

                if ($out === false) {
                    throw new \Exception('Gagal membuka file untuk digabungkan.');
                }

                for ($i = 0; $i < $totalChunks; $i++) {
                    $chunkPath = $chunkDir . '/' . $i . '.part';
                    $in = fopen($chunkPath, 'rb');
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    fclose($in);
                    unlink($chunkPath); // Hapus file part setelah digabung
                }
                fclose($out);

                // Gunakan Fileinfo untuk deteksi MIME yang lebih akurat
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($finalFilePath);

                $userId = Auth::id();

                $thumbnailName = null;
                $thumbPath = null;
                if ($request->filled('thumbnail_base64') && Str::startsWith($mimeType, 'image/')) {
                    $thumbnailName = 'thumb_' . Str::uuid() . '.jpg';
                    $userDir = storage_path('app/private/files/' . $userId);
                    if (!file_exists($userDir)) {
                        mkdir($userDir, 0775, true);
                    }
                    $thumbPath = $userDir . '/' . $thumbnailName;
                    // Decode base64 dan simpan
                    $imageParts = explode(";base64,", $request->thumbnail_base64);
                    if (count($imageParts) == 2) {
                        file_put_contents($thumbPath, base64_decode($imageParts[1]));
                    }
                }

                $mergedFile = new \Illuminate\Http\UploadedFile(
                    $finalFilePath,
                    $fileName,
                    $mimeType ?: 'application/octet-stream',
                    null,
                    true
                );

                // Panggil logic store bawaan (atau pindahkan logic store ke method helper)
                // Untuk simplifikasi, kita duplikasi logic validasi & penyimpanan di sini:
                $currentUsage = File::where('user_id', $userId)->whereNull('deleted_at')->where('is_folder', false)->sum('size');
                $quota = Auth::user()->storage_quota;
                $fileSize = $mergedFile->getSize();

                if ($currentUsage + $fileSize > $quota) {
                    \Illuminate\Support\Facades\File::deleteDirectory($chunkDir);
                    if ($thumbPath && file_exists($thumbPath)) {
                        unlink($thumbPath);
                    }
                    return response()->json(['success' => false, 'message' => 'Kuota penyimpanan tidak cukup.'], 400);
                }

                $extension = $mergedFile->getClientOriginalExtension();
                $storedName = Str::uuid() . ($extension ? '.' . $extension : '');
                $path = 'files/' . $userId;
                
                // Pindahkan file gabungan ke folder private storage
                $mergedFile->storeAs($path, $storedName, 'local');

                $file = File::create([
                    'user_id' => $userId,
                    'parent_id' => $request->parent_id,
                    'original_name' => $fileName,
                    'stored_name' => $storedName,
                    'thumbnail_name' => $thumbnailName,
                    'mime_type' => $mergedFile->getMimeType(),
                    'size' => $fileSize,
                    'extension' => $extension,
                    'is_folder' => false,
                ]);

                // Bersihkan folder chunk
                \Illuminate\Support\Facades\File::deleteDirectory($chunkDir);

                return response()->json([
                    'success' => true,
                    'message' => 'File berhasil diupload.',
                    'file' => [
                        'id' => $file->id,
                        'name' => $file->original_name,
                        'size' => $file->human_size,
                        'icon' => $file->icon,
                        'icon_color' => $file->icon_color,
                    ],
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Chunk uploaded']);

        } catch (\Exception $e) {
            Log::error('Chunk upload error', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Gagal upload chunk: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Buat folder baru.
     */
    public function createFolder(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:files,id',
            ]);
            
            $userId = Auth::id();
            
            $folder = File::create([
                'user_id' => $userId,
                'parent_id' => $request->parent_id,
                'original_name' => $request->name,
                'stored_name' => '',
                'size' => 0,
                'is_folder' => true,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Folder berhasil dibuat.',
                'folder' => ['id' => $folder->id, 'name' => $folder->original_name],
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid.'], 422);
        } catch (\Exception $e) {
            Log::error('Create folder error', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Gagal membuat folder.'], 500);
        }
    }

    /**
     * Toggle status star / favorit.
     */
    public function toggleStar($id)
    {
        try {
            $file = File::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $file->is_starred = !$file->is_starred;
            $file->save();
            
            return response()->json([
                'success' => true,
                'is_starred' => $file->is_starred,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah favorit.'], 500);
        }
    }

    /**
     * Halaman file favorit.
     */
    public function starredIndex()
    {
        $userId = Auth::id();
        $files = File::where('user_id', $userId)
            ->where('is_starred', true)
            ->whereNull('deleted_at')
            ->orderBy('is_folder', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalFiles = File::where('user_id', $userId)->whereNull('deleted_at')->where('is_folder', false)->count();
        $totalSize = File::where('user_id', $userId)->whereNull('deleted_at')->where('is_folder', false)->sum('size');
        $category = 'starred';
        $folderId = null;
        $breadcrumb = [];
        
        return view('files.index', compact('files', 'category', 'totalFiles', 'totalSize', 'folderId', 'breadcrumb'));
    }

    /**
     * Generate / get link share.
     */
    public function share(Request $request, $id)
    {
        try {
            $file = File::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

            if (!$file->share_token) {
                $file->share_token = Str::random(32);
            }

            $expiry = $request->input('expiry', 'never');
            $file->share_expires_at = match ($expiry) {
                '1h'    => now()->addHour(),
                '1d'    => now()->addDay(),
                '7d'    => now()->addDays(7),
                '30d'   => now()->addDays(30),
                default => null,
            };

            $file->save();

            return response()->json([
                'success' => true,
                'url' => $file->share_url,
                'expires_at' => $file->share_expires_at?->toIso8601String(),
                'expires_human' => $file->share_expires_at?->diffForHumans(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal membuat link.'], 500);
        }
    }

    /**
     * Cabut link share.
     */
    public function unshare($id)
    {
        try {
            $file = File::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $file->share_token = null;
            $file->share_expires_at = null;
            $file->is_public = false;
            $file->save();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mencabut link.'], 500);
        }
    }

    /**
     * Tampilan publik untuk file yang di-share (tanpa login).
     */
    public function showShared($token)
    {
        $file = File::where('share_token', $token)->whereNull('deleted_at')->firstOrFail();

        if ($file->isShareExpired()) {
            return response()->view('files.shared', compact('file'), 410);
        }

        if (!$file->existsOnDisk()) {
            abort(404, 'File tidak tersedia.');
        }

        return view('files.shared', compact('file'));
    }

    /**
     * Download publik untuk file yang di-share (tanpa login).
     */
    public function downloadShared($token)
    {
        $file = File::where('share_token', $token)->whereNull('deleted_at')->firstOrFail();

        if ($file->isShareExpired()) {
            abort(410, 'Link sudah kadaluarsa.');
        }

        if (!$file->existsOnDisk()) {
            abort(404, 'File tidak tersedia.');
        }

        return Storage::disk('local')->download($file->storage_path, $file->original_name);
    }

    /**
     * Download file (khusus user yang login).
     */
    public function download($id)
    {
        $file = File::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if (!$file->existsOnDisk()) {
            abort(404, 'File tidak ditemukan di server.');
        }
        
        return Storage::disk('local')->download(
            $file->storage_path,
            $file->original_name
        );
    }

    /**
     * Pindahkan file / folder ke sampah (soft delete).
     */
    public function destroy($id)
    {
        try {
            $file = File::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            
            // Kalau folder, soft delete juga anak-anaknya
            if ($file->is_folder) {
                $this->softDeleteRecursive($file);
            }
            
            $file->delete(); // soft delete (deleted_at diisi)
            
            return response()->json([
                'success' => true,
                'message' => 'File dipindah ke sampah.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus.'], 500);
        }
    }
    
    private function softDeleteRecursive($folder)
    {
        foreach ($folder->children as $child) {
            if ($child->is_folder) {
                $this->softDeleteRecursive($child);
            }
            $child->delete();
        }
    }

    /**
     * Halaman tempat sampah.
     */
    public function trashIndex()
    {
        $userId = Auth::id();
        $files = File::onlyTrashed()
            ->where('user_id', $userId)
            ->orderBy('deleted_at', 'desc')
            ->get();
        
        $totalFiles = File::where('user_id', $userId)->whereNull('deleted_at')->where('is_folder', false)->count();
        $totalSize = File::where('user_id', $userId)->whereNull('deleted_at')->where('is_folder', false)->sum('size');
        $category = 'trash';
        $folderId = null;
        $breadcrumb = [];
        
        return view('files.index', compact('files', 'category', 'totalFiles', 'totalSize', 'folderId', 'breadcrumb'));
    }

    /**
     * Pulihkan file dari sampah.
     */
    public function restore($id)
    {
        try {
            $file = File::onlyTrashed()->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $file->restore();
            
            return response()->json(['success' => true, 'message' => 'File dipulihkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memulihkan.'], 500);
        }
    }

    /**
     * Hapus permanen file dari sampah.
     */
    public function forceDelete($id)
    {
        try {
            $file = File::onlyTrashed()->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            
            // Hapus file fisik
            if (!$file->is_folder && $file->existsOnDisk()) {
                Storage::disk('local')->delete($file->storage_path);
            }
            
            $file->forceDelete();
            
            return response()->json(['success' => true, 'message' => 'File dihapus permanen.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus permanen.'], 500);
        }
    }

    /**
     * Kosongkan seluruh sampah user.
     */
    public function emptyTrash()
    {
        try {
            $userId = Auth::id();
            $files = File::onlyTrashed()->where('user_id', $userId)->get();
            
            foreach ($files as $file) {
                if (!$file->is_folder && $file->existsOnDisk()) {
                    Storage::disk('local')->delete($file->storage_path);
                }
                $file->forceDelete();
            }
            
            return response()->json(['success' => true, 'message' => 'Sampah dikosongkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengosongkan sampah.'], 500);
        }
    }

    /**
     * Preview file inline (image, video, audio, pdf, text).
     */
    public function preview($id)
    {
        $file = File::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($file->is_folder || !$file->existsOnDisk()) {
            abort(404, 'File tidak ditemukan.');
        }

        $path = Storage::disk('local')->path($file->storage_path);
        $mime = $file->mime_type ?: 'application/octet-stream';

        return response()->file($path, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . addslashes($file->original_name) . '"',
            'X-Frame-Options' => 'ALLOWALL', // Izinkan render di iframe
        ]);
    }

    /**
     * Serve thumbnail gambar.
     */
    public function thumbnail($id)
    {
        $file = File::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if (!$file->thumbnail_name || !file_exists(storage_path('app/private/files/' . $file->user_id . '/' . $file->thumbnail_name))) {
            abort(404);
        }
        return response()->file(storage_path('app/private/files/' . $file->user_id . '/' . $file->thumbnail_name));
    }

    /**
     * Preview file publik via share token.
     */
    public function previewShared($token)
    {
        $file = File::where('share_token', $token)->whereNull('deleted_at')->firstOrFail();

        if ($file->isShareExpired()) {
            abort(410, 'Link sudah kadaluarsa.');
        }

        if ($file->is_folder || !$file->existsOnDisk()) {
            abort(404, 'File tidak ditemukan.');
        }

        $path = Storage::disk('local')->path($file->storage_path);
        $mime = $file->mime_type ?: 'application/octet-stream';

        return response()->file($path, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . addslashes($file->original_name) . '"',
            'X-Frame-Options' => 'ALLOWALL', // Izinkan render di iframe
        ]);
    }

    /**
     * List file selevel untuk navigasi prev/next di modal preview.
     */
    public function siblings($id)
    {
        try {
            $currentFile = File::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

            $query = File::where('user_id', Auth::id())
                ->where('is_folder', false)
                ->whereNull('deleted_at');

            if ($currentFile->parent_id) {
                $query->where('parent_id', $currentFile->parent_id);
            } else {
                $query->whereNull('parent_id');
            }

            $files = $query->orderBy('created_at', 'desc')->get()->map(function ($f) {
                return [
                    'id' => $f->id,
                    'name' => $f->original_name,
                    'preview_type' => $f->preview_type,
                    'is_previewable' => $f->is_previewable,
                ];
            });

            return response()->json([
                'success' => true,
                'files' => $files,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'files' => [],
            ], 500);
        }
    }
}
