<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SetupStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:setup {--force : Force recreate symlink}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup storage directories and symlink for file uploads';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Setting up storage for file uploads...');

        // Pastikan direktori storage ada
        $this->ensureStorageDirectories();

        // Buat symlink
        $this->createSymlink();

        // Set permissions
        $this->setPermissions();

        $this->info('✅ Storage setup completed successfully!');
        $this->line('');
        $this->line('📋 Next steps:');
        $this->line('1. Ensure web server has read access to storage/ directory');
        $this->line('2. Verify APP_URL in .env is correct');
        $this->line('3. Test file upload and download functionality');
    }

    private function ensureStorageDirectories()
    {
        $this->info('📁 Creating storage directories...');

        $directories = [
            'storage/app/public',
            'storage/app/public/dokumen',
            'storage/app/public/aset-tanah-warga',
            'storage/app/public/bukti-aset',
            'storage/app/public/sk-perangkat',
            'storage/app/public/sk-kepala-desa',
            'storage/app/public/monografi',
            'storage/app/public/profile-photos',
            'public/uploads',
        ];

        foreach ($directories as $dir) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
                $this->line("   Created: {$dir}");
            }
        }
    }

    private function createSymlink()
    {
        $this->info('🔗 Creating storage symlink...');

        $publicStorage = public_path('storage');
        $storageAppPublic = storage_path('app/public');

        // Hapus symlink lama jika ada
        if (File::exists($publicStorage)) {
            if ($this->option('force')) {
                File::delete($publicStorage);
                $this->line('   Removed old symlink');
            } else {
                $this->line('   Symlink already exists');
                return;
            }
        }

        // Buat symlink baru
        if (File::exists($storageAppPublic)) {
            if (PHP_OS_FAMILY === 'Windows') {
                // Windows
                exec("mklink /D \"{$publicStorage}\" \"{$storageAppPublic}\"");
            } else {
                // Unix/Linux
                symlink($storageAppPublic, $publicStorage);
            }
            $this->line('   Created symlink: public/storage -> storage/app/public');
        } else {
            $this->error('   Error: storage/app/public directory not found');
        }
    }

    private function setPermissions()
    {
        $this->info('🔐 Setting permissions...');

        $directories = [
            'storage',
            'storage/app',
            'storage/app/public',
            'public/uploads',
        ];

        foreach ($directories as $dir) {
            if (File::exists($dir)) {
                chmod($dir, 0755);
                $this->line("   Set permissions for: {$dir}");
            }
        }
    }
}
