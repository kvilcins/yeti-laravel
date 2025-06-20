<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use App\Models\Page;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(BackupDataSeeder::class);

        Artisan::call('migrate:fresh');

        $this->call(RestoreDataSeeder::class);

        $this->call(CategoriesTableSeeder::class);
        $this->call(UsersTableSeeder::class);

        $this->restorePages();
    }

    private function restorePages()
    {
        $pages = Config::get('pages', []);

        foreach ($pages as $page) {
            $existingPage = Page::where('slug', $page['slug'])->first();
            if (!$existingPage) {
                Page::create($page);
                echo "Page {$page['name']} successfully restored.\n";
            } else {
                $existingPage->update($page);
                echo "Page with slug {$page['slug']} updated.\n";
            }
        }
    }
}
