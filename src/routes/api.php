<?php

//小程序
Route::get('/applet/auth','AppletController@auth');
Route::get('/applet/phone','AppletController@phone');
Route::get('/applet/authAndPhone','AppletController@authAndPhone');