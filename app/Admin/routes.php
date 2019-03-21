<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->resource('/goods',GoodsController::class);
    $router->resource('/users',UsersController::class);
    $router->resource('/wxuser',WeixinController::class);
    $router->resource('/wxMedia',WeixinMediaController::class);
    $router->resource('/material',WeixinMaterialController::class);//永久素材
    $router->get('/sendmsg','WeixinMediaController@sendMsg');//群发消息
    $router->post('/','WeixinMediaController@all');//群发消息
    $router->post('/material','WeixinMaterialController@formShow');
    $router->get('/touser','WeixinController@openid');//私聊1
    $router->post('/touser','WeixinController@touser');//私聊2
    $router->post('/message','WeixinController@message');//刷新数据3
    $router->get('/token','WeixinController@refreshToken');//私聊




});
