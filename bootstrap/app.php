<?php

use App\Http\Middleware\BAAKMiddleware;
use App\Http\Middleware\DekanMiddleware;
use App\Http\Middleware\KaprodiMiddleware;
use App\Http\Middleware\MahasiswaMiddleware;
use App\Http\Middleware\PMBMiddleware;
use App\Http\Middleware\RektorMiddleware;
use App\Http\Middleware\WR1Middleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'pmb' => PMBMiddleware::class,
            'kaprodi' => KaprodiMiddleware::class,
            'baak' => BAAKMiddleware::class,
            'mahasiswa' => MahasiswaMiddleware::class,
            'dekan' => DekanMiddleware::class,
            'wr1' => WR1Middleware::class,
            'rektor' => RektorMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
