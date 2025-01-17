<?php

use Convoy\Http\Controllers\Admin\IndexController;
use Convoy\Http\Controllers\Admin\Nodes\Addresses\AddressController;
use Convoy\Http\Controllers\Admin\Nodes\NodeController;
use Convoy\Http\Controllers\Admin\Nodes\SettingsController;
use Convoy\Http\Controllers\Admin\Servers\ServerController;
use Convoy\Http\Controllers\Admin\Servers\Settings;
use Convoy\Http\Controllers\Admin\Templates\TemplateController;
use Convoy\Http\Controllers\Admin\Users;
use Convoy\Http\Controllers\Admin\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| Node Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /api/application/nodes
|
*/

Route::prefix('/nodes')->group(function () {
    Route::get('/', [NodeController::class, 'index'])->name('admin.nodes');
    Route::post('/', [NodeController::class, 'store']);
    Route::get('/create', [NodeController::class, 'create'])->name('admin.nodes.create');
    Route::get('/search', [NodeController::class, 'search'])->name('admin.nodes.search');

    Route::prefix('/{node}')->group(function () {
        Route::get('/', [NodeController::class, 'show'])->name('admin.nodes.show');
        Route::delete('/', [NodeController::class, 'destroy']);

        Route::prefix('/templates')->group(function () {
            Route::get('/', [TemplateController::class, 'index'])->name('admin.nodes.show.templates');
            Route::get('/{template}', [TemplateController::class, 'show'])->name('admin.nodes.show.templates.show');
        });

        Route::prefix('/addresses')->group(function () {
            Route::get('/', [AddressController::class, 'index'])->name('admin.nodes.show.addresses');
            Route::post('/', [AddressController::class, 'store']);
            Route::get('/search', [AddressController::class, 'search'])->name('admin.nodes.show.addresses.search');

            Route::prefix('/{address}')->group(function () {
                Route::put('/', [AddressController::class, 'update'])->name('admin.nodes.show.addresses.show');
                Route::delete('/', [AddressController::class, 'destroy']);
            });
        });

        Route::prefix('/settings')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('admin.nodes.show.settings');
            Route::put('/basic-settings', [NodeController::class, 'update'])->name('admin.nodes.show.settings.basic-info');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Server Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /api/application/servers
|
*/

Route::prefix('/servers')->group(function () {
    Route::get('/', [ServerController::class, 'index'])->name('admin.servers');
    Route::post('/', [ServerController::class, 'store']);
    Route::get('/create', [ServerController::class, 'create'])->name('admin.servers.create');
    Route::get('/search', [ServerController::class, 'search'])->name('admin.servers.search');

    Route::prefix('/{server}')->group(function () {
        Route::get('/', [ServerController::class, 'show'])->name('admin.servers.show');
        Route::delete('/', [ServerController::class, 'destroy']);

        Route::prefix('/settings')->group(function () {
            Route::get('/', [Settings\SettingsController::class, 'index'])->name('admin.servers.show.settings');
            Route::put('/basic-info', [Settings\SettingsController::class, 'updateBasicInfo'])->name('admin.servers.show.settings.basic-info');
            Route::put('/details', [Settings\SettingsController::class, 'updateDetails'])->name('admin.servers.show.settings.details');
        });
    });
});

/*
|--------------------------------------------------------------------------
| User Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /api/application/users
|
*/

Route::prefix('/users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('admin.users');
    Route::post('/', [UserController::class, 'store']);
    Route::get('/search', [UserController::class, 'search'])->name('admin.users.search');

    Route::prefix('/{user}')->group(function () {
        Route::get('/', [UserController::class, 'show'])->name('admin.users.show');
        Route::delete('/', [UserController::class, 'destroy']);

        Route::prefix('/settings')->group(function () {
            Route::get('/', [Users\Settings\SettingsController::class, 'index'])->name('admin.users.show.settings');
            Route::put('/', [Users\Settings\SettingsController::class, 'update']);
        });
    });
});
