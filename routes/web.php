<?php

use App\Http\Controllers\listRepo;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckIfBlocked;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\rolesController;
use App\Http\Controllers\UnzipController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\MetadataController;
use App\Http\Controllers\moveFileController;
use App\Http\Controllers\zipReportController;
use App\Http\Controllers\SolicitanteController;
use App\Http\Controllers\registerUsersController;
use App\Http\Controllers\VersionControlController;
use Symfony\Component\HttpFoundation\JsonResponse;


App::setLocale('es');

app()->singleton('checkIfBlocked', CheckIfBlocked::class);

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/enviar-correo', [EmailController::class, 'enviarCorreo']);
Route::get('/test-correo', [UserController::class, 'testGenerarCorreo']);


Route::middleware(['auth', 'checkIfBlocked'])->group(function(){
    Route::middleware([
        'auth:web',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::middleware(['auth'])->group(function () {
            Route::middleware(CheckRole::class . ':admin_users')->group(function () {
                Route::controller(registerUsersController::class)->group(function () {
                    Route::get('registerUsers', 'index')->name('registerUsers');
                    Route::post('registerStore', 'store')->name('registerStore');
                    Route::put('editUser/{id?}', 'restore')->name('editUser');
                    Route::put('blockUser', 'blockUser')->name('blockUser');
                });
            });
        });
        

        Route::middleware(['auth'])->group(function () {
            Route::middleware(CheckRole::class . ':admin_users')->group(function () {
                Route::controller(rolesController::class)->group(function () {
                    Route::get('Roles', 'showRolView')->name('show-rol-view');
                    Route::post('/registerRoles', 'store')->name('roles.store');
                    Route::put('/updateRoles/{id}', 'restore')->name('roles.restore');
                    Route::get('/generar-correo/{id}', [UserController::class, 'generarCorreo']);
                   // 
                });
            });
        });
    });
});
