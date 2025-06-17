<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class ItemSyncService
{
    public static function updateConfig(array $newItem): void
    {
        $configItems = Config::get('items', []);

        $exists = collect($configItems)->contains('title', $newItem['title']);

        if (!$exists) {
            $configItems[] = $newItem;

            Config::set('items', $configItems);
        }
    }
}
