<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = Sitemap::create()
                          ->add(Url::create('/'))
                          ->add(Url::create('/catalog'))
                          ->add(Url::create('/catalog/boards'))
        ;
    
        // Генерация карты сайта в файл public/sitemap.xml
        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
