<?php

namespace App\Providers;

use App\Contracts\EntregaRepositoryInterface;
use App\Repositories\EntregaRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(EntregaRepositoryInterface::class, EntregaRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 