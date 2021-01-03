<?php
Route::group(['prefix' => 'svc', 'namespace' => 'ZhuiTech\BootAdmin\Admin\Controllers'], function () {
    Route::get('/dashboard', 'ServiceProxyController@index')->name('admin.svc.dashboard');
    Route::any('/{wildcard?}', 'ServiceProxyController@admin')->where('wildcard', '.+');
});

Route::group(['namespace' => 'ZhuiTech\BootAdmin\Admin\Controllers'], function () {
    // 复写用户管理
    Route::resource('auth/users', 'UserController');

    // 复写菜单管理
    Route::post('auth/menu/fix', 'MenuController@fix')->name('admin.auth.menu.fix');
    Route::resource('auth/menu', 'MenuController');

    // 老版本的导出功能
    Route::get('export', 'ExportController@index')->name('admin.export.index');
    Route::get('export/downLoadFile', 'ExportController@downLoadFile')->name('admin.export.downLoadFile');

    Route::get('redirect', 'SystemController@redirectTo')->name('admin.redirect');

    // 系统设置
    Route::get('settings/system', 'SystemController@settings')->name('admin.settings.system');

    // 数据转换
    Route::get('helpers/convert', 'SystemController@convertHelper');

    // 队列管理
	Route::get('helpers/horizon', 'SystemController@horizon');
});