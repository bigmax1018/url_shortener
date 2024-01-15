<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlshortenerController;

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
    return view('urlshortener');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//url used for url shortner
Route::get('/u/{any}', [UrlshortenerController::class, 'handle']);

//url shortner storing into database
Route::post('/url/shorten', [UrlshortenerController::class, 'store']);