<?php

namespace Htqxd\LaravelHanetApi;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

class LaravelHanetApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_hanets_table.php.stub' => $this->getMigrationFileName('create_hanets_table.php'),
                __DIR__.'/../database/migrations/create_hanet_places_table.php.stub' => $this->getMigrationFileName('create_hanet_places_table.php'),
            ], 'hanet-migrations');
        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LaravelHanet::class);
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @return string
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }

}
