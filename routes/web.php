<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\listRepo;
use App\Http\Controllers\UnzipController;
use App\Http\Controllers\VersionControlController;
use App\Http\Controllers\registerUsersController;
use App\Http\Controllers\rolesController;
use App\Http\Controllers\MetadataController;
use App\Http\Controllers\zipReportController;
use App\Http\Controllers\moveFileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckIfBlocked;

App::setLocale('es');

app()->singleton('checkIfBlocked', CheckIfBlocked::class);

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'checkIfBlocked'])->group(function(){
    Route::middleware([
        'auth:web',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::controller(listRepo::class)->group(function () {
            Route::get('/list', 'index')->name('list');
        });

        Route::controller(VersionControlController::class)->group(function () {
            Route::get('versioncontrol', 'index')->name('versioncontrol');
            Route::post('uploadfile', 'store')->name('uploadfile');
            Route::get('restore/{id?}', 'restore')->name('restorefile');
        });

        Route::controller(FolderController::class)->group(function () {
            Route::post('new_directory', 'store')->name('new_directory');
        });

        Route::controller(moveFileController::class)->group(function() {
            Route::post('select_file', 'selectFile')->name('select_file');
            Route::get('list_folder', 'listFolders')->name('list_folder');
            Route::post('move_file', 'moveFile')->name('move_file');
        });

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

        Route::post('unzip', [UnzipController::class, 'index'])->name('unzip');


        Route::middleware(['auth'])->group(function () {
            Route::middleware(CheckRole::class . ':admin_users')->group(function () {
                Route::controller(rolesController::class)->group(function () {
                    Route::get('Roles', 'showRolView')->name('show-rol-view');
                    Route::post('/registerRoles', 'store')->name('roles.store');
                    Route::put('/updateRoles/{id}', 'restore')->name('roles.restore');
                });
            });
        });

    Route::post('/Metadatos', [MetadataController::class, 'show'])->name('metadata');
    Route::post('/store/{type}', [MetadataController::class, 'store'])->name('store');

        Route::middleware(['auth'])->group(function () {
            Route::middleware(CheckRole::class . ':admin_audit')->group((function () {
                Route::group(['prefix' => 'audit'], function () {
                    Route::get('/', [AuditController::class, 'show'])->name('audit');
                    Route::get('/audit/search', [AuditController::class, 'search'])->name('audit.search');
                });
            }));
        });
    });

    Route::controller(zipReportController::class)->group(function (){
        Route::get('/zipReport', 'index')->name('zipReport');
    });
  
});
