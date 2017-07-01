<?php

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['middleware' => 'wechat.auth','namespace' => 'App\Http\Controllers\Wechat','prefix' => 'wechat'], function ($app) {
    $app->get('/user',function (){
        return view('index',['users'=>[]]);
    });
});

$app->group(['middleware' => 'routerRole'], function ($app) {
    $app->get('/user2',[
        'as' => 'admin.authCheck',
        'uses' => 'AdminAuthController@check',
    ]);
});