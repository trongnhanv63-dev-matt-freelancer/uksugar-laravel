<?php

namespace App\Infrastructure\Escort\Persistence\Providers;

use Illuminate\Support\ServiceProvider;

// Escort Repositories
use App\Domain\Escort\Repositories\EscortRepositoryInterface;
use App\Infrastructure\Escort\Persistence\Repositories\EloquentEscortRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Escort bindings
        $this->app->bind(EscortRepositoryInterface::class, EloquentEscortRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
