<?php

use App\Http\Controllers\Index;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
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
});
Route::get('/login',[Index::class,'login_page']);
Route::post('/register',[Index::class,'register']);
Route::post('/user_login',[Index::class,'user_login']);
Route::get('/task_2',[Index::class,'task_2']);
Route::post('/get_state',[Index::class,'get_state']);
Route::post('/get_city',[Index::class,'get_city']);

Route::group(['middleware'=> 'auth'],function(){
    Route::get('/user_profile',[UserController::class,'user_proile']);
});

Route::get('/logout',function(){
    session()->flush();
    Cookie::forget('user_email');
    Cookie::forget('user_password');
    return redirect('/');

});
