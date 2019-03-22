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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/adduser','User\UserController@add');

//路由跳转
Route::redirect('/hello1','/world1',301);
Route::get('/world1','Test\TestController@world1');

Route::get('hello2','Test\TestController@hello2');
Route::get('world2','Test\TestController@world2');


//路由参数
Route::get('/user/{uid}','User\UserController@user');
Route::get('/month/{m}/date/{d}','Test\TestController@md');
Route::get('/name/{str?}','Test\TestController@showName');



// View视图路由
Route::view('/mvc','mvc');
Route::view('/error','error',['code'=>403]);


// Query Builder
Route::get('/query/get','Test\TestController@query1');
Route::get('/query/where','Test\TestController@query2');

Route::match(['get','post'],'/test','Test\TestController@test');
Route::get('/date','Test\TestController@date');
Route::get('/userList','User\UserController@userList');
Route::get('/dump','User\UserController@dump');

/** 注册*/
Route::get('/userreg','User\UserController@reg');
Route::post('/userreg','User\UserController@doReg');
/** 登录*/
Route::get('/userlogin','User\UserController@login');
Route::post('/userlogin','User\UserController@doLogin');



Route::get('/usercenter','User\UserController@center')->middleware('check.login');//

/** 退出*/
Route::get('/userquit','User\UserController@quit');
//中间件
Route::middleware(['log.click'])->group(function(){
    Route::any('/test/guzzle','Test\TestController@guzzleTest');
    Route::get('/test/cookie1','Test\TestController@cookieTest1');
    Route::get('/test/cookie2','Test\TestController@cookieTest2');
    Route::get('/test/session','Test\TestController@sessionTest');
    Route::get('/test/mid1','Test\TestController@mid1');        //中间件测试
    Route::get('/test/check_cookie','Test\TestController@checkCookie');
    //Route::get('/test/goods/{goods_id}','Goods\GoodsController@index');

});
//购物车
Route::any('/cart','Cart\CartController@index')->middleware('check.login.token');
Route::get('/cartAdd/{goods_id}','Cart\CartController@cartAdd')->middleware('check.login.token');
Route::post('/add2','Cart\CartController@add2');
Route::get('/cartDel/{goods_id}','Cart\CartController@cartDel')->middleware('check.login.token');
Route::post('/cartDel2','Cart\CartController@cartdel2')->middleware('check.login.token');
//商品列表
Route::get('/goodsList','Login\LoginController@goodsList');
//商品详情
Route::get('/goods/{goods_id}','Goods\GoodsController@index');
//订单
Route::get('/orderAdd','Order\OrderController@orderAdd');
Route::get('/orderList','Order\OrderController@orderList');
Route::get('/test0','Pay\AliPayController@test0');
Route::get('/pay','Pay\AliPayController@test');
//支付宝调回

Route::get('/orderPay/{oid}','Pay\AliPayController@pay');
Route::post('/pay/alipay/notify','Pay\AliPayController@aliNotify');//异步通知
Route::get('/pay/alipay/return','Pay\AliPayController@aliReturn');//同步通知


Route::get('/pay/alipay/orderDel','Pay\AliPayController@orderDel');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


//文件上传
Route::get('/upload','Goods\GoodsController@uploadIndex');
Route::post('/goods/upload/pdf','Goods\GoodsController@uploadDF');
//搜索
Route::get('/search','Goods\GoodsController@search');
Route::get('/movie','Movie\IndexController@index');
//微信开发
Route::get('/weixin/vaild','Weixin\IndexController@vaild');
Route::post('/weixin/vaild1','Weixin\IndexController@vaild1');
Route::post('/all','Weixin\IndexController@all');
//创建菜单
Route::get('/createMenu','Weixin\IndexController@createMenu');

//微信
Route::get('/weixin/test','Weixin\IndexController@test');//获取access_token
//Route::get('/weixin/valid','Weixin\IndexController@validToken');
Route::get('/weixin/valid1','Weixin\IndexController@validToken1');
Route::post('/weixin/valid1','Weixin\IndexController@wxEvent');        //接收微信服务器事件推送
//Route::post('/weixin/valid','Weixin\IndexController@validToken');


//创建菜单
Route::get('/createMenu','Weixin\IndexController@createMenu');
Route::get('/addMaterial','Weixin\IndexController@addMaterial');
Route::get('/formTest','Weixin\IndexController@formTest');//表单测试
Route::post('/formShow','Weixin\IndexController@formShow');//表单测试


Route::get('/weixin/materialList','Weixin\IndexController@materialList');     //获取永久素材列表
Route::get('/weixin/materialUpload','Weixin\IndexController@upMaterial');

//上传永久素材
Route::get('/weixin/talk','Weixin\IndexController@talk');
Route::post('/talk','Weixin\IndexController@weixintalk');


//微信支付
Route::get('/weixin/pay/test/{order_id}','Weixin\PayController@test');     //微信支付测试
Route::post('/weixin/pay/notice','Weixin\PayController@notice');     //微信支付通知回调
//Route::get('/weixin/pay/curl/{curl}','Weixin\PayController@curl');
Route::post('/weixin/pay/payweixn','Weixin\PayController@payweixin');
Route::get('/weixin/pay/pay111','Weixin\PayController@pay111'); //支付成功



//微信登录

Route::get('/weixin/login','Weixin\IndexController@wxlogin');     //微信登录
Route::get('/weixin/getcode','Weixin\IndexController@getCode');        //接收code
Route::get('/weixin/redirect','Weixin\IndexController@redirect');        //接收code


Route::get('/weixin/jssdk/test','Weixin\IndexController@jssdkTest');       // 测试




//curl
Route::any('/curl/test','Curl\CurlController@test');

Route::post('/curl/api','Curl\CurlController@api');
//ajax
Route::post('/ajax/api','Curl\CurlController@ajax');
Route::post('/ajax/mui','Curl\CurlController@ajaxmui');




Route::get('/api/login','Curl\CurlController@userlogin');
Route::any('/','Curl\CurlController@index');




