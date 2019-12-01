<?php
Route::group(['prefix' => 'admin', 'namespace' => 'ZhuiTech\BootAdmin\Admin\Controllers'], function () {
    Route::post('auth/mobile', 'AuthController@getMobile');
});