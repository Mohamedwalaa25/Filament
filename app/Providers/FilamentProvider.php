<?php

namespace App\Providers;

use App\Filament\Resources\BlogResource\Pages\FormsBlogs;
use Illuminate\Support\ServiceProvider;

class FilamentProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        FormsBlogs::class;
    }
}
