<?php

use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('home');
Route::resource('google', GoogleController::class)->names([
    'index'   => 'google.index',
    'create'  => 'google.create',
    'store'   => 'google.store',
    'show'    => 'google.show',
    'edit'    => 'google.edit',
    'update'  => 'google.update',
    'destroy' => 'google.destroy',
]);
Route::post('/analyze', [GoogleController::class, 'analyzeFile'])->name('google.analyze');
Route::get('/result/{id}', [GoogleController::class, 'results'])->name('google.result');