<?php

namespace App\Providers;

use App\Compression\CompressionInterface;
use App\Compression\to7zipCompression;
use App\Compression\toTarGzCompression;
use App\Compression\toZipCompression;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $format = $this->app->request->format ?? config('compress.default_format');

        $handler = match ($format) {
            'zip'    => toZipCompression::class,
            '7z'     => to7zipCompression::class,
            'tar.gz' => toTarGzCompression::class,
            default  => throw new \Exception('Invalid compression format.')
        };
        $this->app->bind(CompressionInterface::class, $handler);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
