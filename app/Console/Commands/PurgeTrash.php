<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

class PurgeTrash extends Command
{
    protected $signature = 'cloud:purge-trash';
    protected $description = 'Hapus permanen file di sampah yang sudah > 30 hari';

    public function handle()
    {
        $files = File::onlyTrashed()
            ->where('deleted_at', '<', now()->subDays(30))
            ->get();
        
        $count = 0;
        foreach ($files as $file) {
            if (!$file->is_folder && $file->existsOnDisk()) {
                Storage::disk('local')->delete($file->storage_path);
            }
            $file->forceDelete();
            $count++;
        }
        
        $this->info("Purged {$count} file dari sampah.");
        return 0;
    }
}
