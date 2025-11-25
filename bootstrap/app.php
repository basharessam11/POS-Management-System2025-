<?php

use App\Http\Middleware\Customer;
use App\Http\Middleware\language;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',


        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append:[language::class]);
        $middleware->api(prepend: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Session\Middleware\StartSession::class,


        ]);
        $middleware->alias([

            'teacher' => \App\Http\Middleware\TeacherMiddleware::class,
            'employee' => \App\Http\Middleware\EmployeeMiddleware::class,
                'role' =>  RoleMiddleware::class,
    'permission' =>  PermissionMiddleware::class,
    'role_or_permission' =>  RoleOrPermissionMiddleware::class,
        ]);

    })

//     ->withMiddleware(function (Middleware $middleware) {
//         $middleware->append(Customer::class);
//    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
