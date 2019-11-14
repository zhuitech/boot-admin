<?php
Route::group(['prefix' => 'admin', 'namespace' => 'ZhuiTech\BootAdmin\Admin\Controllers'], function () {
    Route::get('auth/login', 'AuthController@getLogin')->name('auth.admin.login');
    Route::post('auth/login', 'AuthController@postLogin')->name('auth.admin.login.post');
    Route::post('auth/mobile', 'AuthController@getMobile');
});