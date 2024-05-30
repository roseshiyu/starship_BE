<?php

use App\Enums\Domain\Status;
use App\Models\Domain;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// api-user.local.starship.com

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            $domains = Domain::where('status', Status::active)->get();

            foreach ($domains as $domain) {
                Route::middleware('api')
                    ->domain(env('APP_SUBDOMAIN_API_USER').'.'.env('APP_ENV').'.'.env('APP_DOMAIN'))
                    ->prefix('v1')
                    ->as('user.')
                    ->group(base_path('routes/'.$domain->name.'/api-user.php'));

                Route::middleware('api')
                    ->domain(env('APP_SUBDOMAIN_API_ADMIN').'.'.env('APP_ENV').'.'.env('APP_DOMAIN'))
                    ->prefix('v1')
                    ->as('admin.')
                    ->group(base_path('routes/'.$domain->name.'/api-admin.php'));
            }
        },

    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->api([
            'accept-json',
        ]);
        $middleware->alias([
            'ability' => CheckForAnyAbility::class,
            'accept-json' => \App\Http\Middleware\SetRequestAcceptJson::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Domain::get();

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('v1/*')) {
                return response()->json([
                    'message' => 'Record not found.',
                ], 404);
            }
        });
        $exceptions->render(function (QueryException $e, Request $request) {
            if ($request->is('v1/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 404);
            }
        });
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('v1/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                ], $e->status);
            }
        });
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('v1/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->is('v1/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 401);
            }
        });
    })->create();
