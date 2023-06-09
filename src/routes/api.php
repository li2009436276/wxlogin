<?php

//小程序
Route::post('/applet/auth','AppletController@auth');
Route::post('/applet/phone','AppletController@phone');
Route::post('/applet/authAndPhone','AppletController@authAndPhone');
Route::post('/applet/authBindUser','AppletController@authBindUser');
Route::post('/applet/login','AppletController@login');

Route::middleware('user.auth')->group(function (){

    Route::post('/applet/bindPhone','AppletController@bindPhone');
});

//微信公众号
Route::post('/official/auth','OfficialController@auth');

//公众号分享
Route::post('/share/share','ShareController@share');