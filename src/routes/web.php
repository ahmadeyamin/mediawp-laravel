<?php





Route::group(['namespace' => 'Eyamin\Mediawp\Http\Controllers','middleware'=> config('mediawp.middleware'),'prefix' => config('mediawp.prefix') ], function () {
    Route::get('/media', 'MediaUploadController@index')->name('admin.media');


    Route::get('/media/upload', 'MediaUploadController@upload')->name('admin.media.upload');


    Route::post('/media/edit', 'MediaUploadController@editImage')->name('admin.media.editImage');

    Route::get('/media/ajex-request', 'MediaAjaxRequest@query_menager')->name('admin.ajex.query_menager');


    Route::post('/media/ajex-request', 'MediaAjaxRequest@query_menager')->name('admin.ajex.query_menager');


    Route::post('/media/upload', 'MediaUploadController@store')->name('admin.media.upload');

    Route::post('/media/upload/delete', 'MediaUploadController@delete')->name('admin.media.upload.delete');

    
    Route::post('/media/upload/update', 'MediaUploadController@update')->name('admin.media.upload.update');
});
