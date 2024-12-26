<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class ItemSyncService
{
    public static function updateConfig(array $newItem): void
    {
        $configItems = Config::get('items', []);
        
        // Проверяем, существует ли уже элемент с таким названием
        $exists = collect($configItems)->contains('title', $newItem['title']);
        
        if (!$exists) {
            // Добавляем новый элемент в конфигурацию
            $configItems[] = $newItem;
            
            // Обновляем конфигурацию
            Config::set('items', $configItems);
        }
    }
}