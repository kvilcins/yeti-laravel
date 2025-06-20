<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Models\Page;
use App\Models\Bid;
use Illuminate\Support\Facades\Config;

class BackupDataSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all()->toArray();

        $formattedCategories = array_map(function($category) {
            return [
                'id' => $category['id'],
                'name' => $category['name'],
                'class' => $category['class'],
                'slug' => $category['slug'],
            ];
        }, $categories);

        $existingCategories = Config::get('categories', []);
        $mergedCategories = array_merge($existingCategories, $formattedCategories);
        $mergedCategories = $this->removeDuplicates($mergedCategories, 'id');

        Config::set('categories', $mergedCategories);
        file_put_contents(config_path('categories.php'), '<?php return ' . var_export($mergedCategories, true) . ';');

        $items = Item::all()->toArray();

        $formattedItems = array_map(function($item) {
            return [
                'id' => $item['id'],
                'title' => $item['title'],
                'slug' => $item['slug'],
                'description' => $item['description'],
                'price' => $item['price'],
                'min_bid' => $item['min_bid'],
                'img' => $item['img'],
                'category_id' => $item['category_id'],
                'timer' => $item['timer'] ?? null,
            ];
        }, $items);

        $existingItems = Config::get('items', []);
        $mergedItems = array_merge($existingItems, $formattedItems);
        $mergedItems = $this->removeDuplicates($mergedItems, 'id');

        Config::set('items', $mergedItems);
        file_put_contents(config_path('items.php'), '<?php return ' . var_export($mergedItems, true) . ';');

        $users = User::all();

        $existingUsers = Config::get('userdata', []);

        $formattedUsers = $users->map(function($user) {
            return [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'password' => $user->password ?? null,
                'remember_token' => $user->remember_token ?? null,
                'email_verified_at' => $user->email_verified_at ?? null,
                'created_at' => $user->created_at ? $user->created_at->toISOString() : null,
                'updated_at' => $user->updated_at ? $user->updated_at->toISOString() : null,
                'contact_details' => $user->contact_details ?? null,
                'avatar' => $user->avatar ?? null, // Avatar field included
            ];
        })->toArray();

        $mergedUsers = array_merge($existingUsers, $formattedUsers);
        $mergedUsers = $this->removeDuplicates($mergedUsers, 'id');

        Config::set('userdata', $mergedUsers);
        file_put_contents(config_path('userdata.php'), '<?php return ' . var_export($mergedUsers, true) . ';');

        $bids = Bid::all()->toArray();

        $formattedBids = array_map(function ($bid) {
            return [
                'id' => $bid['id'],
                'lot_id' => $bid['lot_id'],
                'user_id' => $bid['user_id'],
                'bid_amount' => $bid['bid_amount'],
                'bid_time' => $bid['bid_time'],
                'created_at' => $bid['created_at'] ?? null,
                'updated_at' => $bid['updated_at'] ?? null,
            ];
        }, $bids);

        $existingBids = Config::get('bids', []);

        $mergedBids = array_merge($existingBids, $formattedBids);
        $mergedBids = $this->removeDuplicates($mergedBids, 'id');

        Config::set('bids', $mergedBids);
        file_put_contents(config_path('bids.php'), '<?php return ' . var_export($mergedBids, true) . ';');

        $pages = Page::all()->toArray();

        $formattedPages = array_map(function($page) {
            return [
                'id' => $page['id'],
                'slug' => $page['slug'],
                'name' => $page['name'],
                'title' => $page['title'] ?? null,
                'content' => $page['content'] ?? null,
                'type' => $page['type'] ?? 'default_value',
                'route' => $page['route'] ?? 'default_value',
            ];
        }, $pages);

        $existingPages = Config::get('pages', []);

        if (!is_array($existingPages)) {
            $existingPages = [];
        }

        $mergedPages = array_merge($existingPages, $formattedPages);
        $mergedPages = $this->removeDuplicates($mergedPages, 'id');

        Config::set('pages', $mergedPages);
        file_put_contents(config_path('pages.php'), '<?php return ' . var_export($mergedPages, true) . ';');
    }

    private function removeDuplicates($array, $key)
    {
        $unique = [];
        foreach ($array as $item) {
            if (isset($item[$key])) {
                $unique[$item[$key]] = $item;
            }
        }
        return array_values($unique);
    }
}
