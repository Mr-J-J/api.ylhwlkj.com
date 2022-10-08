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


use Illuminate\Support\Facades\Route;
Route::get('/',function(){
    return view('welcome');
});

Route::group(['prefix'=>'stores','middleware'=>['web','rsstore']],function(){
    Route::get('/', Stores\HomeController::class.'@index');
    Route::get('/login', Stores\LoginController::class.'@showLogin')->name('stores.login');
    Route::post('/login', Stores\LoginController::class.'@login');
    Route::match(['get','post'],'/logout', Stores\LoginController::class.'@logout')->name('stores.logout');

    Route::get('/profile',Stores\HomeController::class.'@profile');
    Route::get('/account',Stores\HomeController::class.'@account');
    Route::get('/settle',Stores\HomeController::class.'@settle');
    Route::get('/withdraw',Stores\HomeController::class.'@withdraw');
    Route::post('/dowithdraw',Stores\HomeController::class.'@dowithdraw');
    Route::get('/withdrawList',Stores\HomeController::class.'@withdrawList');
    Route::get('/card',Stores\HomeController::class.'@card');
    Route::post('/card',Stores\HomeController::class.'@card_handle');
    Route::get('/order',Stores\HomeController::class.'@order');
    Route::get('/member',Stores\HomeController::class.'@member');
    Route::get('/card-detail/{userId}',Stores\HomeController::class.'@card_detail');
});
