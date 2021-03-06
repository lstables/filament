<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Asset routes...
Route::name('assets.')->group(function () {

    Route::get('filament.css', 'AssetController@css')->name('css');
    Route::get('filament.js', 'AssetController@js')->name('js');
    
});

// Authentication routes...
Route::name('auth.')->namespace('Auth')->group(function () {

    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');

    Route::prefix('password')->name('password.')->group(function () {
        Route::get('forgot', 'ForgotPasswordController@showLinkRequestForm')->name('forgot');
        Route::post('forgot', 'ForgotPasswordController@sendResetLinkEmail')->name('email');
        Route::get('reset/{token}', 'ResetPasswordController@showResetForm')->name('reset');
        Route::post('reset', 'ResetPasswordController@reset')->name('update');        
    });
    
});

// Authenticated routes...
Route::name('admin.')->middleware('auth.filament')->group(function () {
    
    Route::layout('filament::layouts.admin')->section('main')->group(function () {

        Route::livewire('/', 'filament::dashboard')->name('dashboard');

        Route::livewire('users', 'filament::users')->name('users.index');
        Route::livewire('users/{id}', 'filament::user')->name('users.edit');

        Route::livewire('roles', 'filament::roles')->name('roles.index');
        Route::livewire('roles/{id}', 'filament::role-edit')->name('roles.edit');

        Route::livewire('permissions', 'filament::permissions')->name('permissions.index');
        Route::livewire('permissions/{id}', 'filament::permission-edit')->name('permissions.edit');

    });

});

// Images
Route::get('/image/{path}', 'ImageController')->where('path', '.*')->name('image');
