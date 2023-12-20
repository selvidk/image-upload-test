<?php

use App\Http\Controllers\Controller;
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

Route::get('/', [App\Http\Controllers\ImageProcessing::class, 'index']);

Route::post('/image/upload', [App\Http\Controllers\ImageProcessing::class, 'imageUpload']);
Route::post('/image/delete', [App\Http\Controllers\ImageProcessing::class, 'imageDelete']);
