<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        try {
            $publicStorage = public_path('storage');
            $storageTarget = storage_path('app/public');
            if (!file_exists($publicStorage) || (is_link($publicStorage) && readlink($publicStorage) !== $storageTarget)) {
                @unlink($publicStorage);
                @symlink($storageTarget, $publicStorage);
            }
        } catch (\Throwable $t) {
            //
        }
    }
}
