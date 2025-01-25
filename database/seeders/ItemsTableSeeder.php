<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Services\ItemSyncService;
use Cocur\Slugify\Slugify;
use Illuminate\Support\Str;

class ItemsTableSeeder extends Seeder
{
    protected $itemSyncService;
    
    public function __construct(ItemSyncService $itemSyncService)
    {
        $this->itemSyncService = $itemSyncService;
    }
    
    public function run()
    {
        $this->itemSyncService->updateConfigFromDb();
        $items = config('items');
        $slugify = new Slugify();
    
        foreach ($items as $item) {
            $category = DB::table('categories')->where('name', $item['category'])->first();
        
            if ($category) {
                $item['slug'] = $item['slug'] ?? Str::slug($item['title']);
            
                Item::updateOrCreate(
                    ['title' => $item['title']],
                    [
                        'description' => $item['description'],
                        'price' => $item['price'],
                        'min_bid' => $item['min_bid'],
                        'img' => $item['img'],
                        'category_id' => $category->id,
                        'slug' => $item['slug'],
                    ]
                );
            }
        }
    }
}
