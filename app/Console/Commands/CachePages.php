<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class CachePages extends Command
{
    protected $signature = 'cache:pages {--clear : Clear cached pages}';

    protected $description = 'Cache frequently accessed pages for improved performance';

    public function handle()
    {
        if ($this->option('clear')) {
            $this->clearCachedPages();
            return 0;
        }

        $this->cachePages();
        return 0;
    }

    protected function cachePages()
    {
        $this->info('Caching frequently accessed pages...');

        $pages = [
            'home' => route('home'),
            'shop' => route('products.index'),
            'about' => route('about'),
            'contact' => route('contact'),
            'privacy' => route('privacy'),
            'terms' => route('terms'),
        ];

        foreach ($pages as $name => $url) {
            try {
                $content = file_get_contents($url);
                Cache::put("page:{$name}", $content, 3600);
                $this->info("✓ Cached: {$name}");
            } catch (\Exception $e) {
                $this->warn("✗ Failed to cache: {$name} - {$e->getMessage()}");
            }
        }

        $this->info('Page caching complete!');
    }

    protected function clearCachedPages()
    {
        $this->info('Clearing cached pages...');

        $pages = ['home', 'shop', 'about', 'contact', 'privacy', 'terms'];

        foreach ($pages as $name) {
            Cache::forget("page:{$name}");
            $this->info("✓ Cleared: {$name}");
        }

        $this->info('Page cache cleared!');
    }
}
