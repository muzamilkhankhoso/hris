<?php

Route::group(['prefix' => 'slal','before' => 'csrf'], function () {
    Route::get('/stateLoadDependentCountryId', 'SelectListLoadAjaxController@stateLoadDependentCountryId');
    Route::get('/cityLoadDependentStateId', 'SelectListLoadAjaxController@cityLoadDependentStateId');
    Route::get('/employeeLoadDependentDepartmentID', 'SelectListLoadAjaxController@employeeLoadDependentDepartmentID');
    Route::get('/MachineEmployeeListDeptWise', 'SelectListLoadAjaxController@MachineEmployeeListDeptWise');
    Route::get('/MachineAllEmployeeList', 'SelectListLoadAjaxController@MachineAllEmployeeList');
    Route::get('/getEmployeeProjectList', 'SelectListLoadAjaxController@getEmployeeProjectList');
    Route::get('/getEmployeeCategoriesList', 'SelectListLoadAjaxController@getEmployeeCategoriesList');
    Route::get('/getSubDepartment','SelectListLoadAjaxController@getSubDepartment');
    Route::get('/getAllSubDepartment','SelectListLoadAjaxController@getAllSubDepartment');
    Route::get('/getExitSubDepartment','SelectListLoadAjaxController@getExitSubDepartment');

    Route::get('/viewEmployeeLoansList','SelectListLoadAjaxController@viewEmployeeLoansList');


});