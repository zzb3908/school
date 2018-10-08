<?php

// Route::get('think', function () {
//     return 'hello,ThinkPHP5!';
// });

// Route::get('hello/:name', 'index/hello');

Route::domain('www.aixuejie.ajeelee.com', 'index');
Route::domain('api.aixuejie.ajeelee.com', 'api');
Route::domain('manage.aixuejie.ajeelee.com', 'manage');

Route::group([], function () {

    Route::get('date/index','date/index')->name('date.index');
    Route::get('date/message','date/message')->name('date.index');

})->middleware('Auth');

// Route::domain('api', function () {
//     // 动态注册域名的路由规则
//     Route::rule('new/:id', 'index/news/read');
//     Route::rule(':user', 'index/user/info');
// })->bind('api');

return [

];
