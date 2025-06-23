<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class CleanUnusedAvatars extends Command
{
    protected $signature = 'avatars:clean';
    protected $description = 'Clean unused avatar files';

    public function handle()
    {
        $files = Storage::disk('public')->files('avatars');
        $usedAvatars = User::whereNotNull('avatar')
            ->pluck('avatar')
            ->map(fn($avatar) => str_replace('avatars/', '', $avatar))
            ->toArray();

        $deletedCount = 0;
        foreach ($files as $file) {
            $filename = basename($file);
            if (!in_array($filename, $usedAvatars)) {
                Storage::disk('public')->delete($file);
                $deletedCount++;
            }
        }

        $this->info("Deleted {$deletedCount} unused avatar files.");
    }
}
