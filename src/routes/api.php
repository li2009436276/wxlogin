<?php

//小程序
Route::post('/applet/auth','AppletController@auth');
Route::post('/applet/phone','AppletController@phone');
Route::post('/applet/authAndPhone','AppletController@authAndPhone');
Route::post('/applet/authBindUser','AppletController@authBindUser');
Route::post('/applet/login','AppletController@login');

//公众号
Route::post('/official/auth','OfficialController@auth');