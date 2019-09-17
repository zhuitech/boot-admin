<?php
Route::group(['namespace' => 'ZhuiTech\BootAdmin\Http\Controllers'], function ($router) {
    Route::post('staff/auth', 'StaffController@auth');
});