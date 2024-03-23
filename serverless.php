<?php

require __DIR__.'/vendor/autoload.php';

use Bref\Http\SymfonyAdapter;

return function ($event) {
    // Capture the request from the event
    $psrRequest = SymfonyAdapter::apiGateway($event);

    // Bootstrap Laravel
    $app = require_once __DIR__.'/bootstrap/app.php';

    // Handle the request
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $psrResponse = $kernel->handle(
        $symfonyRequest = SymfonyAdapter::laravel($psrRequest)
    );

    // Send the response back to API Gateway
    return SymfonyAdapter::apiGateway($psrResponse);
};