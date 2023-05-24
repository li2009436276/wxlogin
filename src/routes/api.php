<?php

//小程序
Route::post('/applet/auth','AppletController@auth');
Route::post('/applet/phone','AppletController@phone');
Route::post('/applet/authAndPhone','AppletController@authAndPhone');
Route::post('/applet/bindPhone','AppletController@bindPhone');
Route::post('/applet/authBindUser','AppletController@authBindUser');
Route::post('/applet/login','AppletController@login');