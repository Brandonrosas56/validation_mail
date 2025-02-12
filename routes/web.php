<?php

use App\Http\Controllers\CreateAccountController;
use App\Http\Controllers\GlpiController;
use App\Http\Controllers\rolesController;
use App\Http\Controllers\ValidateController;
use App\Http\Controllers\regionalController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckIfBlocked;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

App::setLocale('es');

// Configuración de inicio de sesión
Route::get('/', function () {
    return view('auth.login');
});

// Rutas públicas
Route::get('/show-validate-account', [ValidateController::class, 'show'])->name('show-validate.accounts');
Route::get('/show-account', [CreateAccountController::class, 'show'])->name('show.account');
Route::post('/create-account', [CreateAccountController::class, 'store'])->name('create-account.store');
Route::get('/create-account', [CreateAccountController::class, 'index'])->name('create.account');
Route::get('/validate-account', [ValidateController::class, 'index'])->name('validate.account');
Route::post('/activation', [ValidateController::class, 'store'])->name('activation.store');

Route::controller(regionalController::class)->group(function(){
    Route::get('/show-regional', 'store')->name('show-regional');
    Route::post('/import-regional', 'importRegional')->name('import-regional');
});


// Rutas protegidas
Route::middleware(['auth', 'checkIfBlocked'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rutas de roles
    Route::middleware(CheckRole::class . ':admin_users')->group(function () {
        Route::controller(rolesController::class)->group(function () {
            Route::get('Roles', 'showRolView')->name('show-rol-view');
            Route::post('/registerRoles', 'store')->name('roles.store');
        });
    });

    // Rutas de GLPI
    Route::prefix('glpi')->group(function () {
        Route::get('/init-session', [GlpiController::class, 'initSession']);
        Route::get('/ticket/{id}', [GlpiController::class, 'getTicket']);
        Route::post('/ticket', [GlpiController::class, 'createTicket']);

    });

    Route::get('/test-glpi', function (GLPIService $glpiService) {
        try {
            // Intenta conectarte y obtener la información de sesión
            $response = $glpiService->client->get('/initSession');
            $data = json_decode($response->getBody()->getContents(), true);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });
});
