<?php

use App\Http\Middleware\AuthPassword;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (){
            Route::middleware('api')->prefix('mobile')->group(__DIR__.'/../routes/mobile.php');
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth:password' => AuthPassword::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request){
            if($request->is('api/*')){
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage() ?: 'Forbidden Access',
                    'data' => null,
                    'statusCode' => $e->getCode() ?: 403       
                ]);
            }
        });
    })->create();
