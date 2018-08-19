<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        view()->composer('inc.tags', function ($view) {
            $view->with('tags', \App\Tag::withCount('posts')->orderBy('posts_count', 'desc')->get());
        });

        view()->composer('inc.popular_posts', function ($view) {
            $view->with('popularPosts', \App\Post::orderBy('views', 'desc')->limit(5)->get());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
