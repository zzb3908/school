<?php

// Route::get('think', function () {
//     return 'hello,ThinkPHP5!';
// });

// Route::get('hello/:name', 'index/hello');

Route::domain('www.aixuejie.ajeelee.com', 'index');
Route::domain('api.aixuejie.ajeelee.com', 'api');
Route::domain('manage.aixuejie.ajeelee.com', 'manage');

Route::group([], function () {

    //约吧
    Route::get('invite/index','invite/index')->name('invite.index');
    Route::get('invite/messages','invite/messages')->name('invite.messages');
    Route::post('invite/destroy','invite/destroy')->name('invite.destroy');
    Route::get('invite/show/:II_Id','invite/show')->model('II_Id', '\app\manage\model\InviteModel')->name('invite.show');

    //聚吧
    Route::get('topic/index','topic/index')->name('topic.index');
    Route::post('topic/update','topic/update')->name('topic.update');
    Route::post('topic/destroy','topic/destroy')->name('topic.destroy');
    //聚吧小组
    Route::get('topic/group','topic/group')->name('topic.group');
    Route::post('topic/groupupdate','topic/groupupdate')->name('topic.groupupdate');
    Route::post('topic/groupdestroy','topic/groupdestroy')->name('topic.groupdestroy');

    //赛吧
    Route::get('competition/index','competition/index')->name('competition.index');
    Route::get('competition/category/:pid','competition/category')->name('competition.category');
    Route::get('competition/categorylist/:pid','competition/categorylist')->name('competition.categorylist');

})->middleware('Auth');

// Route::domain('api', function () {
//     // 动态注册域名的路由规则
//     Route::rule('new/:id', 'index/news/read');
//     Route::rule(':user', 'index/user/info');
// })->bind('api');

return [

];
