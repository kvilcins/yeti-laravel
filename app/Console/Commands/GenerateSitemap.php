<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Item;
use App\Models\Category;
use App\Models\Page;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $sitemap = Sitemap::create();

        $sitemap->add(Url::create(route('home'))
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0));

        $staticPages = [
            route('home') => ['frequency' => Url::CHANGE_FREQUENCY_DAILY, 'priority' => 1.0],
        ];

        foreach ($staticPages as $url => $config) {
            $sitemap->add(Url::create($url)
                ->setChangeFrequency($config['frequency'])
                ->setPriority($config['priority']));
        }

        Page::all()->each(function (Page $page) use ($sitemap) {
            $sitemap->add(Url::create(url($page->slug))
                ->setLastModificationDate($page->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8));
        });

        Category::all()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(Url::create(route('category.show', $category->slug))
                ->setLastModificationDate($category->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8));
        });

        Item::with('category')->get()->each(function (Item $item) use ($sitemap) {
            $sitemap->add(Url::create(route('lot.show', [
                'category_slug' => $item->category->slug,
                'slug' => $item->slug
            ]))
                ->setLastModificationDate($item->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.6));
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
