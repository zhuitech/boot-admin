<?php
Route::group(['prefix' => 'svc', 'namespace' => 'ZhuiTech\BootAdmin\Admin\Controllers'], function () {
    Route::get('/dashboard', 'ServiceProxyController@index')->name('admin.svc.dashboard');
    Route::any('/{wildcard?}', 'ServiceProxyController@admin')->where('wildcard', '.+');
});

Route::group(['namespace' => 'ZhuiTech\BootAdmin\Admin\Controllers'], function () {
    Route::resource('auth/users', 'UserController');

    Route::post('auth/menu/fix', 'MenuController@fix')->name('admin.auth.menu.fix');
    Route::resource('auth/menu', 'MenuController');
    
    Route::get('auth/login', 'AuthController@getLogin')->name('auth.admin.login');
    Route::post('auth/login', 'AuthController@postLogin')->name('auth.admin.login.post');

    Route::post('getMobile', 'AuthController@getMobile');
    Route::get('export', 'ExportController@index')->name('admin.export.index');
    Route::get('export/downLoadFile', 'ExportController@downLoadFile')->name('admin.export.downLoadFile');
});