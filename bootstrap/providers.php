<?php

use App\Infrastructure\Escort\Persistence\Providers\RepositoryServiceProvider as EscortRepositoryServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    EscortRepositoryServiceProvider::class,
];
