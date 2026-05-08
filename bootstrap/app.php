<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\ModelNotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->respond(function ($response) {

            $status = $response->getStatusCode();

            if (request()->is('api/*')) {

                // 404
                if ($status === 404) {

                    return response()->json([
                        'success' => false,
                        'message' => 'Recurso não encontrado',
                        'data' => null,
                        'errors' => null,
                    ], 404);
                }

                // 500
                if ($status === 500) {

                    return response()->json([
                        'success' => false,
                        'message' => 'Erro interno do servidor',
                        'data' => null,
                        'errors' => null,
                    ], 500);
                }
            }

            return $response;
        });

        $exceptions->render(function (
            ValidationException $e,
            $request
        ) {

            if ($request->is('api/*')) {

                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'data' => null,
                    'errors' => $e->errors(),
                ], 422);
            }
        });

    })
    ->create();
