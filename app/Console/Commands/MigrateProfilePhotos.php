<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\User;

class MigrateProfilePhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile-photos:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate profile photos from public/uploads to storage/app/public';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Migrating profile photos from public/uploads to storage/app/public...');

        $users = User::whereNotNull('profile_photo')->get();
        $migratedCount = 0;
        $errorCount = 0;

        foreach ($users as $user) {
            $oldPath = public_path('uploads/profile-photos/' . $user->profile_photo);
            $newPath = storage_path('app/public/profile-photos/' . $user->profile_photo);

            if (File::exists($oldPath)) {
                try {
                    // Pastikan direktori tujuan ada
                    if (!File::exists(dirname($newPath))) {
                        File::makeDirectory(dirname($newPath), 0755, true);
                    }

                    // Copy file dari old ke new location
                    File::copy($oldPath, $newPath);
                    
                    $this->line("   ✅ Migrated: {$user->name} - {$user->profile_photo}");
                    $migratedCount++;

                    // Optional: Hapus file lama setelah berhasil copy
                    // File::delete($oldPath);
                    
                } catch (\Exception $e) {
                    $this->error("   ❌ Error migrating {$user->name}: " . $e->getMessage());
                    $errorCount++;
                }
            } else {
                $this->warn("   ⚠️  File not found: {$oldPath}");
            }
        }

        $this->info('');
        $this->info("📊 Migration Summary:");
        $this->info("   ✅ Successfully migrated: {$migratedCount} photos");
        $this->info("   ❌ Errors: {$errorCount} photos");
        $this->info("   📁 Total users with photos: {$users->count()}");
        
        if ($migratedCount > 0) {
            $this->info('');
            $this->info('🎉 Profile photos migration completed!');
            $this->info('💡 You can now delete old photos from public/uploads/profile-photos/ if needed');
        }
    }
}
