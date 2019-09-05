<?php
Route::group(['prefix' => 'svc', 'namespace' => 'ZhuiTech\BootAdmin\Admin\Controllers'], function () {
    Route::get('/dashboard', 'ServiceController@index')->name('admin.svc.dashboard');
    Route::any('/{wildcard?}', 'ServiceController@admin')->where('wildcard', '.+');
});