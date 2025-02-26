<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckIfBlocked;
use App\Http\Controllers\{
    LoginController,
    ValidateController,
    CreateAccountController,
    ChangeStatusController,
    importController,
    roleFunctionaryController,
    registerUsersController,
    rolesController
};

// Establecer el idioma en español
App::setLocale('es');

// Registrar singleton para middleware
app()->singleton('checkIfBlocked', CheckIfBlocked::class);



Route::get('/', function () {
    return view('auth.login');
});

Route::post('/login_controller', [LoginController::class, 'login'])->name('login-controller');

Route::middleware(['auth', 'checkIfBlocked'])->group(function () {
    
    Route::controller(ValidateController::class)->group(function () {
        Route::get('/validate-account', 'index')->name('validate.account');
        Route::get('/show-validate-account', 'show')->name('show-validate.accounts');
        Route::post('/activation', 'store')->name('activation.store');
    });

    Route::controller(CreateAccountController::class)->group(function () {
        Route::get('/create-account', 'index')->name('create.account');
        Route::get('/show-account', 'show')->name('show.account');
        Route::post('/create-account', 'store')->name('create-account.store');
    });

    Route::post('/change', [ChangeStatusController::class, 'store'])->name('change.store');

    Route::controller(importController::class)->group(function () {
        Route::get('/show-import', 'store')->name('show-import');
        Route::post('/import-files', 'importFiles')->name('import-files');
    });

    Route::controller(roleFunctionaryController::class)->group(function () {
        Route::get('/show_role_functionary', 'show')->name('show_role_functionary');
        Route::post('/assign-role-functionary', 'assignRoleFuncionary')->name('assign-role-functionary');
    });

    Route::controller(registerUsersController::class)->group(function () {
        Route::get('/show_user_authorization', 'index')->name('show_user_authorization');
        Route::post('/registerStore', 'store')->name('registerStore');
    });

    Route::middleware(['auth:web', config('jetstream.auth_session'), 'verified'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });

    Route::middleware(CheckRole::class . ':admin_users')->group(function () {
        Route::controller(rolesController::class)->group(function () {
            Route::get('/Roles', 'showRolView')->name('show-rol-view');
            Route::post('/registerRoles', 'store')->name('roles.store');
        });
    });

});
