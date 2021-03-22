<?php

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

Route::get('cache_clear', function () {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    dd("cache:clear");
});
Route::get('config_clear', function () {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    dd("config:clear");
});
Route::get('storage', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    dd("storage");
});
Route::get('migrate', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate');
    dd("migrate");
});
Route::get('seed', function () {
    \Illuminate\Support\Facades\Artisan::call('db:seed');
    dd("seed");
});
Route::get('up', function () {
    \Illuminate\Support\Facades\Artisan::call('up');
    dd("live");
});
Route::get('down', function () {
    \Illuminate\Support\Facades\Artisan::call('down');
    dd("maintenance");
});
Route::get('php_info', function () {
    phpinfo();
    return "true";
});

Route::get('/', function () {
    return redirect('/admin');
});
