<?php

use App\Support\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// header('Access-Control-Allow-Origin:*');


Route::post('stores/test','H5\TestController@index');
//  Route::post('apiRoute/{route}','ApiController@apiRoute');
//小程序
Route::post('user/login', 'MiniPro\LoginController@doLogin');
Route::post('user/wxlogin', 'MiniPro\LoginController@wxLogin');
//支付回调

Route::any('user/notify/{comId?}', 'MiniPro\NotifyController@index')->name('notify');
Route::any('user/refund-notify/{comId?}', 'MiniPro\NotifyController@refundBackNotify')->name('refundnotify');
Route::any('user/mall-refund-notify/{comId?}', 'MiniPro\NotifyController@mallRefundNotify')->name('mallrefundnotify');
Route::any('mall/mallNotify/{comId?}', 'MiniPro\NotifyController@mallNotify')->name('mallnotify');
Route::any('card/cardNotify/{comId?}', 'MiniPro\NotifyController@cardNotify')->name('cardnotify');
Route::any('user/pwNotify', 'MiniPro\NotifyController@pwNotify')->name('pwnotify'); //票务支付成功回调
Route::any('user/pwRefundNotify', 'MiniPro\NotifyController@pwRefundNotify')->name('pwrefundnotify');
Route::any('order/jufubao_order', 'MiniPro\NotifyController@jufubao_order')->name('jufubao_order_notify'); //聚福宝出票通知

Route::namespace('MiniPro')->middleware('auth.users')->group(function() {
    
    Route::get('user/info', 'UserController@index');
    Route::get('user/groupList','UserController@groupList');
    Route::get('user/fans', 'UserController@fans');
    Route::get('user/commision', 'UserController@commision');
    Route::get('user/draw_list', 'UserController@drawList');
    Route::get('user/apply-withdraw','UserController@applyWithDraw');
    Route::get('user/formid','UserController@saveFormId');    
    Route::post('user/qrcode','UserController@myCode');   
    Route::get('user/posterList','UserController@posterList');
    Route::post('user/edit','UserController@updateInfo');    //个人资料
    Route::post('user/edit_phone','UserController@updateMobile');    //手机号修改

    //订单
    Route::post('user/confirm_order', 'OrderController@confirmOrder');

    Route::post('user/create_order', 'OrderController@addOrder');
    Route::post('user/pay_order', 'OrderController@payOrder');
    Route::get('user/order_list', 'OrderController@index');
    Route::get('user/order_info', 'OrderController@info');
    Route::get('user/cancel_order', 'OrderController@cancelOrder');

    // 图片上传
    Route::post('user/upload','UserController@upload');
    //意见反馈
    Route::post('user/suggestion','UserController@suggestion');

    
    
});

Route::get('film_info','ApiController@filmInfo');
Route::get('paiqi_info','ApiController@schedulesDetail');
Route::get('paiqi_list','ApiController@schedulesList');
Route::get('search_film','ApiController@searchFilm');
Route::get('cinemas_film','ApiController@getFilmWithCinema');
Route::get('cinemas','ApiController@cinemaList');
Route::get('film_cinema_list','ApiController@getCinemaWithFilm');
Route::get('cinemas_brand','ApiController@cinemaBrand');
Route::get('current_film','ApiController@currentFilmList');
Route::get('areas','ApiController@cityAreaList');
Route::get('cityinfo','ApiController@getCityInfo');
Route::get('carousel','ApiController@carousel');
Route::get('agreement','ApiController@agreement');
Route::get('showqrcode','ApiController@showqrcode');



