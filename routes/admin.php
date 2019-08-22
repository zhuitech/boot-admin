<?php
Route::group(['prefix' => 'svc', 'namespace' => 'ZhuiTech\BootAdmin\Admin\Controllers'], function () {
    Route::get('/', 'ServiceController@index');
    Route::any('/{wildcard?}', 'ServiceController@admin')->where('wildcard', '.+');
});