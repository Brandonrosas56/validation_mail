<?php

use Laravel\Jetstream\Rules\Role;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckIfBlocked;
use App\Http\Controllers\roleFunctionary;
use App\Http\Controllers\rolesController;
use App\Http\Controllers\importController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ValidateController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ChangeStatusController;
use App\Http\Controllers\CreateAccountController;
use App\Http\Controllers\registerUsersController;
use App\Http\Controllers\roleFunctionaryController;

App::setLocale('es');

//app()->singleton('checkIfBlocked', CheckIfBlocked::class);

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/login_controller', [LoginController::class,'login'])->name('login-controller');

Route::get('/show-validate-account', [ValidateController::class, 'show'])->name('show-validate.accounts');

Route::get('/show-account', [CreateAccountController::class, 'show'])->name('show.account');

Route::post('/create-account', [CreateAccountController::class, 'store'])->name('create-account.store');

Route::get('/create-account', [CreateAccountController::class, 'index'])->name('create.account');

Route::get('/validate-account', [ValidateController::class, 'index'])->name('validate.account');

Route::post('/activation', [ValidateController::class, 'store'])->name('activation.store');

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
    Route::post('registerStore', 'store')->name('registerStore');
});



