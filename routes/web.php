<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chatroom', [App\Http\Controllers\ChatController::class,'chat']);
Route::post('/send', [App\Http\Controllers\ChatController::class,'send']);
Route::post('/save', [App\Http\Controllers\ChatController::class,'save']);
Route::post('/getold',[App\Http\Controllers\ChatController::class,'getOldMessages']);
Route::post('/delete',[App\Http\Controllers\ChatController::class,'delete']);
Route::get('check',function(Request $request){
    return $request->session()->get('chat');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
