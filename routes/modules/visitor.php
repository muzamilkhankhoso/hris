<?php


Route::group(['prefix' => 'visitor'], function () {
    Route::get('/d', 'VisitorController@index');
    Route::get('/careers/{id?}', 'VisitorController@careers');
    Route::get('/ViewandApplyDetail/{id}/{company_id}','VisitorController@ViewandApplyDetail');
    Route::get('/ThankyouForApply','VisitorController@ThankyouForApply');
});


Route::group(['prefix' => 'vad','before' => 'csrf'], function () {
    Route::post('/addVisitorApplyDetail', 'VisitorAddDetailController@addVisitorApplyDetail');

});
