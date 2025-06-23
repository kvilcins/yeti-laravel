<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class MigrateLotImages extends Command
{
    protected $signature = 'lots:migrate-images';
    protected $description = 'Migrate lot images from public/img to storage/lots';

    public function handle()
    {
        try {
            $this->info('Testing database connection...');

            $count = DB::table('items')->count();
            $this->info("Database connected. Found {$count} items.");

            $itemsWithImages = DB::table('items')
                ->whereNotNull('img')
                ->get();

            $this->info("Items with images: " . $itemsWithImages->count());

            $imagesToMigrate = $itemsWithImages->filter(function($item) {
                return !empty($item->img) && str_starts_with($item->img, 'img/');
            });

            $this->info("Images to migrate: " . $imagesToMigrate->count());

            if ($imagesToMigrate->count() === 0) {
                $this->info('No images to migrate.');
                return 0;
            }

            $migratedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            foreach ($imagesToMigrate as $item) {
                $oldPath = public_path($item->img);

                if (!File::exists($oldPath)) {
                    $this->warn("File not found: {$item->img} for item ID {$item->id}");
                    $skippedCount++;
                    continue;
                }

                try {
                    $filename = basename($item->img);
                    $newPath = 'lots/' . $filename;

                    if (Storage::disk('public')->exists($newPath)) {
                        $pathInfo = pathinfo($filename);
                        $newFilename = $pathInfo['filename'] . '_' . time() . '.' . $pathInfo['extension'];
                        $newPath = 'lots/' . $newFilename;
                    }

                    $fileContent = File::get($oldPath);
                    Storage::disk('public')->put($newPath, $fileContent);

                    DB::table('items')
                        ->where('id', $item->id)
                        ->update(['img' => $newPath]);

                    File::delete($oldPath);

                    $this->line("Migrated: {$item->title} -> {$newPath}");
                    $migratedCount++;

                } catch (\Exception $e) {
                    $this->error("Error migrating item ID {$item->id}: " . $e->getMessage());
                    $errorCount++;
                }
            }

            $this->info("Migration completed!");
            $this->info("Migrated: {$migratedCount} images");
            $this->info("Skipped: {$skippedCount} images");
            $this->info("Errors: {$errorCount} images");

            return 0;

        } catch (\Exception $e) {
            $this->error("Command failed: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }
    }
}
