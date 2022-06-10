<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::get('/', function () {
    return redirect('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/folders', 'App\Http\Controllers\FolderController@index')->name('folders.index');
Route::get('/images/show', 'App\Http\Controllers\ImageController@show')->name('images.show');
Route::post('/images/confirm', 'App\Http\Controllers\ImageController@confirm')->name('images.confirm');
Route::post('/images/approve', 'App\Http\Controllers\ImageController@approve')->name('images.approve');
Route::post('/images/delete', 'App\Http\Controllers\ImageController@delete')->name('images.delete');
Route::get('/folders/date', 'App\Http\Controllers\FolderController@indexByDate')->name('folders.indexByDate');




require __DIR__.'/auth.php';
