<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;

class CleanUnusedLotImages extends Command
{
    protected $signature = 'lots:clean-images';
    protected $description = 'Clean unused lot image files';

    public function handle()
    {
        $files = Storage::disk('public')->files('lots');
        $usedImages = Item::whereNotNull('img')
            ->pluck('img')
            ->map(fn($img) => str_replace('lots/', '', $img))
            ->toArray();

        $deletedCount = 0;
        foreach ($files as $file) {
            $filename = basename($file);
            if (!in_array($filename, $usedImages)) {
                Storage::disk('public')->delete($file);
                $deletedCount++;
                $this->line("Deleted: {$filename}");
            }
        }

        $this->info("Deleted {$deletedCount} unused lot image files.");
    }
}
