<?php
Route::group(['prefix' => 'svc', 'namespace' => 'ZhuiTech\BootAdmin\Admin\Controllers'], function () {
    Route::get('/dashboard', 'ServiceController@index')->name('admin.svc.dashboard');
    Route::any('/{wildcard?}', 'ServiceController@admin')->where('wildcard', '.+');
});

Route::group(['namespace' => 'ZhuiTech\BootAdmin\Admin\Controllers'], function () {
    Route::resource('auth/users', 'UserController');
    
    Route::get('auth/login', 'AuthController@getLogin')->name('auth.admin.login');
    Route::post('auth/login', 'AuthController@postLogin')->name('auth.admin.login.post');
    Route::post('getMobile', 'AuthController@getMobile');
});