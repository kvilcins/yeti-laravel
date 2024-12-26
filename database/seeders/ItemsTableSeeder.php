<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Services\ItemSyncService;

class ItemsTableSeeder extends Seeder
{
    protected $itemSyncService;
    
    // Конструктор для внедрения сервиса
    public function __construct(ItemSyncService $itemSyncService)
    {
        $this->itemSyncService = $itemSyncService;
    }
    
    public function run()
    {
        // Обновляем конфиг данными из базы данных
        $this->itemSyncService->updateConfigFromDb();
        
        // Получаем данные из конфигурационного файла (когда конфиг обновлен)
        $items = config('items');
        
        foreach ($items as $item) {
            // Найти категорию по имени и получить ее ID
            $category = DB::table('categories')->where('name', $item['category'])->first();
            
            // Если категория найдена, вставляем элемент
            if ($category) {
                Item::updateOrCreate(
                    ['title' => $item['title']], // Условие обновления
                    [
                        'description' => $item['description'],
                        'price' => $item['price'],
                        'min_bid' => $item['min_bid'],
                        'img' => $item['img'],
                        'category_id' => $category->id,
                    ]
                );
            }
        }
    }
}
