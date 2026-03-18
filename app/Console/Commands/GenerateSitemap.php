<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;
use DB;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Auto-generate sitemap for ecommerce website';

    public function handle()
    {
        $baseUrl = config('app.url');

        // Create Sitemap Index (main container)
        $index = SitemapIndex::create();

        /* --------------------------
            1. STATIC PAGES
        ---------------------------*/
        $staticSitemapPath = public_path('sitemaps/static.xml');
        $static = Sitemap::create();

        $staticPages = [
            '/',
            'page/about-us',
            'contact',
            'page/privacy-policy',
            'page/order-terms',
        ];

        foreach ($staticPages as $page) {
            $static->add(
                Url::create($baseUrl . $page)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.7)
            );
        }

        $static->writeToFile($staticSitemapPath);
        $index->add('/public/sitemaps/static.xml');

        /* --------------------------
            2. PRODUCT SITEMAP
        ---------------------------*/
        $productSitemapPath = public_path('sitemaps/products.xml');
        $products = Sitemap::create();

        $productRows = DB::table('products')
            ->select('id', 'slug', 'updated_at')
            ->where('status', 1)
            ->get();

        foreach ($productRows as $p) {
            $products->add(
                Url::create($baseUrl . 'product/' . $p->slug . '/' . urlencode(base64_encode($p->id)))
                    ->setLastModificationDate(Carbon::parse($p->updated_at))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(1.0)
            );
        }

        $products->writeToFile($productSitemapPath);
        $index->add('/public/sitemaps/products.xml');

        /* --------------------------
            3. CATEGORY SITEMAP
        ---------------------------*/
        $categorySitemapPath = public_path('sitemaps/categories.xml');
        $categories = Sitemap::create();

        $categoryRows = DB::table('categories')
            ->select('slug', 'parent_id')
            ->where('status', 1)
            ->get();

        foreach ($categoryRows as $c) {
            if($c->parent_id <= 0){
                $categories->add(
                    Url::create($baseUrl . 'products/' . $c->slug)
                        ->setPriority(0.8)
                );
            } else {
                $getParentCat = DB::table('categories')->select('slug', 'parent_id')->where('id', $c->parent_id)->first();
                $categories->add(
                    Url::create($baseUrl . 'products/' . $getParentCat->slug . '/' . $c->slug)
                        ->setPriority(0.8)
                );
            }
        }

        $categories->writeToFile($categorySitemapPath);
        $index->add('/public/sitemaps/categories.xml');

        /* --------------------------
            4. BLOG POSTS (Optional)
        ---------------------------*/
        // if (DB::schema()->hasTable('blogs')) {

        //     $blogSitemapPath = public_path('sitemaps/blogs.xml');
        //     $blogs = Sitemap::create();

        //     $blogRows = DB::table('blogs')
        //         ->select('slug', 'updated_at')
        //         ->where('status', 1)
        //         ->get();

        //     foreach ($blogRows as $b) {
        //         $blogs->add(
        //             Url::create($baseUrl . '/blog/' . $b->slug)
        //                 ->setLastModificationDate(Carbon::parse($b->updated_at))
        //                 ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
        //                 ->setPriority(0.7)
        //         );
        //     }

        //     $blogs->writeToFile($blogSitemapPath);
        //     $index->add('/sitemaps/blogs.xml');
        // }

        /* --------------------------
            5. GENERATE MASTER SITEMAP INDEX
        ---------------------------*/
        $index->writeToFile(base_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
