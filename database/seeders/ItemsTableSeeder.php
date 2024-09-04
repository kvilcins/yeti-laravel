<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        // Получаем данные из конфигурационного файла
        $items = config('items');
        
        foreach ($items as $item) {
            // Найти категорию по имени и получить ее ID
            $category = DB::table('categories')->where('name', $item['category'])->first();
            
            // Если категория найдена, вставляем элемент
            if ($category) {
                Item::create([
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'min_bid' => $item['min_bid'],
                    'img' => $item['img'],
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
