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
        $this->backupItems();
        $this->backupCategories();
        $this->backupUsers();
        $this->backupPages();
        $this->backupBids();
    }

    private function backupItems()
    {
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
                'user_id' => $item['user_id'] ?? null,
                'status' => $item['status'] ?? 'active',
                'winner_id' => $item['winner_id'] ?? null,
            ];
        }, $items);

        $configPath = config_path('items.php');
        $existingItems = file_exists($configPath) ? include $configPath : [];

        if (!is_array($existingItems)) {
            $existingItems = [];
        }

        $mergedItems = array_merge($existingItems, $formattedItems);
        $mergedItems = $this->removeDuplicates($mergedItems, 'id');

        file_put_contents($configPath, '<?php return ' . var_export($mergedItems, true) . ';');

        echo "Items backup completed: " . count($mergedItems) . " items\n";
    }

    private function backupCategories()
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

        $configPath = config_path('categories.php');
        $existingCategories = file_exists($configPath) ? include $configPath : [];

        if (!is_array($existingCategories)) {
            $existingCategories = [];
        }

        $mergedCategories = array_merge($existingCategories, $formattedCategories);
        $mergedCategories = $this->removeDuplicates($mergedCategories, 'id');

        file_put_contents($configPath, '<?php return ' . var_export($mergedCategories, true) . ';');

        echo "Categories backup completed: " . count($mergedCategories) . " categories\n";
    }

    private function backupUsers()
    {
        $users = User::all();

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
                'avatar' => $user->avatar ?? null,
                'role' => $user->role ?? 'user',
            ];
        })->toArray();

        $configPath = config_path('userdata.php');
        $existingUsers = file_exists($configPath) ? include $configPath : [];

        if (!is_array($existingUsers)) {
            $existingUsers = [];
        }

        $mergedUsers = array_merge($existingUsers, $formattedUsers);
        $mergedUsers = $this->removeDuplicates($mergedUsers, 'id');

        file_put_contents($configPath, '<?php return ' . var_export($mergedUsers, true) . ';');

        echo "Users backup completed: " . count($mergedUsers) . " users\n";
    }

    private function backupPages()
    {
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

        $configPath = config_path('pages.php');
        $existingPages = file_exists($configPath) ? include $configPath : [];

        if (!is_array($existingPages)) {
            $existingPages = [];
        }

        $mergedPages = array_merge($existingPages, $formattedPages);
        $mergedPages = $this->removeDuplicates($mergedPages, 'id');

        file_put_contents($configPath, '<?php return ' . var_export($mergedPages, true) . ';');

        echo "Pages backup completed: " . count($mergedPages) . " pages\n";
    }

    private function backupBids()
    {
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

        $configPath = config_path('bids.php');
        $existingBids = file_exists($configPath) ? include $configPath : [];

        if (!is_array($existingBids)) {
            $existingBids = [];
        }

        $mergedBids = array_merge($existingBids, $formattedBids);
        $mergedBids = $this->removeDuplicates($mergedBids, 'id');

        file_put_contents($configPath, '<?php return ' . var_export($mergedBids, true) . ';');

        echo "Bids backup completed: " . count($mergedBids) . " bids\n";
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
