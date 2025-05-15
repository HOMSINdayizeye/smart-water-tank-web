<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // ... other code ...

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // ... other middleware ...
        'role' => \App\Http\Middleware\CheckRole::class,
    ];

    // ... other code ...
}