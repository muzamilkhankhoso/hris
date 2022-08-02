<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/createEmployeeFormDraft', 'HrController@createEmployeeFormDraft');

Route::post('/addEmployeeDetailDraft','HrAddDetailControler@addEmployeeDetailDraft');
Route::get('wwwwee', function () {
    return view('index');
});


Route::auth();

Route::get('logout', 'Auth\LoginController@logout', function () {
    return abort(404);
});
Route::get('login',array('as'=>'login',function () {
    if (!Auth::check()) {

        return Redirect::to('/');
    }
}));
Route::get('/error', function () {
    return 'Not Authorized';
});
Route::get('/d', 'VisitorController@index');
Route::get('/', function () {
    if (Auth::check())
    {
        if(Auth::user()->acc_type == 'client'):
            return Redirect::to('/dc/hrDashboard?m=12#Innovative');
        elseif(Auth::user()->acc_type == 'user'):
            return Redirect::to('/dc/userDashboard?m='.Auth::user()->company_id.'#Innovative');
        endif;
    }
    else
    {
        return view('auth.login');
    }
});


/* Visitor Module Starts Here*/
Route::get('/testingEmail', 'EmailController@testingEmail');
Route::group(['prefix' => 'softwareSetting','before' => 'csrf'], function () {
	Route::get('/createMaterialRequestSettingForm', 'SoftwareSettingController@createMaterialRequestSettingForm');
	
});

Route::group(['prefix' => 'ssad','before' => 'csrf'], function () {
	Route::post('/addMaterialRequestSettingDetail','SoftwareSettingAddDetailController@addMaterialRequestSettingDetail');
});


	
Route::group(['prefix' => 'emailNotification','before' => 'csrf'], function () {

});


Route::group(['prefix' => 'cCSF','before' => 'csrf'], function () {
    Route::get('/checkDulplicateValuesForCompanies', 'CommonCustomizeStandardFuctionController@checkDulplicateValuesForCompanies');
});

/* Visitor Module Ends Here*/

Route::get('/dMaster', 'MasterController@index');
Route::get('/dClient', 'ClientController@index');
Route::get('/dCompany', 'CompanyController@index');
Route::get('/d', 'HomeController@index');

Route::get('/insufficientPrivileges', function () {
    return view('insufficientPrivileges');
})->name('insufficientPrivileges');

Route::group(['prefix' => 'cdTwo','before' => 'csrf'], function () {
    //Route::get('/deleteRowCompanyHRRecords', 'DeleteCompanyTableRecordController@deleteRowCompanyHRRecords');
});
Route::group(['prefix' => 'cdThree','before' => 'csrf'], function () {
    //Route::get('/deleteRowCompanyHRRecords', 'DeleteCompanyTableRecordController@deleteRowCompanyHRRecords');
});

Route::group(['prefix' => 'fd','before' => 'csrf'], function () {
    Route::get('/deleteCompanyFinanceTwoTableRecords', 'FinanceDeleteController@deleteCompanyFinanceTwoTableRecords');
	Route::get('/repostCompanyFinanceTwoTableRecords', 'FinanceDeleteController@repostCompanyFinanceTwoTableRecords');
	Route::get('/approveCompanyFinanceTwoTableRecords', 'FinanceDeleteController@approveCompanyFinanceTwoTableRecords');
	
	Route::get('/deleteCompanyFinanceTwoTableRecordsPayments', 'FinanceDeleteController@deleteCompanyFinanceTwoTableRecordsPayments');
	Route::get('/repostCompanyFinanceTwoTableRecordsPayments', 'FinanceDeleteController@repostCompanyFinanceTwoTableRecordsPayments');


    Route::get('/deleteCompanyFinanceThreeTableRecords', 'FinanceDeleteController@deleteCompanyFinanceThreeTableRecords');
	Route::get('/repostCompanyFinanceThreeTableRecords', 'FinanceDeleteController@repostCompanyFinanceThreeTableRecords');
	
	Route::get('/deleteCompanyFinanceThreeTableRecordsPayments', 'FinanceDeleteController@deleteCompanyFinanceThreeTableRecordsPayments');
	Route::get('/repostCompanyFinanceThreeTableRecordsPayments', 'FinanceDeleteController@repostCompanyFinanceThreeTableRecordsPayments');
	
    Route::get('/deleteAccount','FinanceDeleteController@deleteAccount');
    Route::get('/repostAccount','FinanceDeleteController@repostAccount');


});

Route::group(['prefix' => 'pd','before' => 'csrf'], function () {
    Route::get('/deleteCompanyPurchaseTwoTableRecords', 'PurchaseDeleteController@deleteCompanyPurchaseTwoTableRecords');
    Route::get('/cancelSinglePurchaseOrderItemRow', 'PurchaseDeleteController@cancelSinglePurchaseOrderItemRow');
    Route::get('/repostCompanyPurchaseTwoTableRecords', 'PurchaseDeleteController@repostCompanyPurchaseTwoTableRecords');
    Route::get('/cancelCompletePurchaseOrderDetail', 'PurchaseDeleteController@cancelCompletePurchaseOrderDetail');
	Route::get('/cancelSingleGoodsReceiptNoteItemRow', 'PurchaseDeleteController@cancelSingleGoodsReceiptNoteItemRow');
    Route::get('/approveCompanyPurchaseTwoTableRecords', 'PurchaseDeleteController@approveCompanyPurchaseTwoTableRecords');
	Route::get('/reverseGoodsReceiptNoteDetailAfterApproval', 'PurchaseDeleteController@reverseGoodsReceiptNoteDetailAfterApproval');
	Route::get('/reverseGoodsReceiptNoteDetailBeforeApproval', 'PurchaseDeleteController@reverseGoodsReceiptNoteDetailBeforeApproval');
    Route::get('/deleteCompanyPurchaseThreeTableRecords', 'PurchaseDeleteController@deleteCompanyPurchaseThreeTableRecords');
    Route::get('/repostCompanyPurchaseThreeTableRecords', 'PurchaseDeleteController@repostCompanyPurchaseThreeTableRecords');
    Route::get('/approveCompanyPurchaseGoodsReceiptNote', 'PurchaseDeleteController@approveCompanyPurchaseGoodsReceiptNote');
	Route::get('/repostCompanyPurchaseGoodsReceiptNoteDetail', 'PurchaseDeleteController@repostCompanyPurchaseGoodsReceiptNoteDetail');
	Route::get('/deleteCompanyPurchaseGoodsReceiptNoteDetail', 'PurchaseDeleteController@deleteCompanyPurchaseGoodsReceiptNoteDetail');
	
	Route::get('/reversePurchaseRequestDetailAfterApproval', 'PurchaseDeleteController@reversePurchaseRequestDetailAfterApproval');
	Route::get('/reversePurchaseRequestDetailAndMaterialRequestAfterApproval', 'PurchaseDeleteController@reversePurchaseRequestDetailAndMaterialRequestAfterApproval');
	
	
});

Route::group(['prefix' => 'sd','before' => 'csrf'], function () {
	Route::get('/deleteCustomerRecord', 'SalesDeleteController@deleteCustomerRecord');
	Route::get('/repostCustomerRecord', 'SalesDeleteController@repostCustomerRecord');
	
	Route::get('/deleteCustomerLocationRecord', 'SalesDeleteController@deleteCustomerLocationRecord');
	Route::get('/repostCustomerLocationRecord', 'SalesDeleteController@repostCustomerLocationRecord');
	
	
});
Route::group(['prefix' => 'std','before' => 'csrf'], function () {
    Route::get('/deleteCompanyStoreThreeTableRecords', 'StoreDeleteController@deleteCompanyStoreThreeTableRecords');
    Route::get('/repostCompanyStoreThreeTableRecords', 'StoreDeleteController@repostCompanyStoreThreeTableRecords');
    Route::get('/approvePurchaseRequest', 'StoreDeleteController@approvePurchaseRequest');
    Route::get('/approvePurchaseRequestSale', 'StoreDeleteController@approvePurchaseRequestSale');
    Route::get('/deleteStockDemandRequestDetail', 'StoreDeleteController@deleteStockDemandRequestDetail');
    Route::get('/repostStockDemandRequestDetail', 'StoreDeleteController@repostStockDemandRequestDetail');
    Route::get('/approveStockDemandRequestDetail', 'StoreDeleteController@approveStockDemandRequestDetail');
	Route::get('/approveDeliveryChallanReturnVoucherDetail', 'StoreDeleteController@approveDeliveryChallanReturnVoucherDetail');
	
	
	Route::get('/reversePurchaseOrderDetailAfterApproval', 'StoreDeleteController@reversePurchaseOrderDetailAfterApproval');
	Route::get('/reversePurchaseOrderDetailBeforeApproval', 'StoreDeleteController@reversePurchaseOrderDetailBeforeApproval');
	
	
	Route::get('/deleteMaterialRequestTemplateDetail', 'StoreDeleteController@deleteMaterialRequestTemplateDetail');
    Route::get('/repostMaterialRequestTemplateDetail', 'StoreDeleteController@repostMaterialRequestTemplateDetail');
    Route::get('/approveMaterialRequestTemplateDetail', 'StoreDeleteController@approveMaterialRequestTemplateDetail');
    
    Route::get('/deleteStoreChallanVoucherDetail', 'StoreDeleteController@deleteStoreChallanVoucherDetail');
    Route::get('/repostStoreChallanVoucherDetail', 'StoreDeleteController@repostStoreChallanVoucherDetail');
    Route::get('/approveStoreChallanVoucherDetail', 'StoreDeleteController@approveStoreChallanVoucherDetail');
	

    Route::get('/approvePurchaseOrder', 'StoreDeleteController@approvePurchaseOrder');
    Route::get('/deleteCompanyStoreTwoTableRecords', 'StoreDeleteController@deleteCompanyStoreTwoTableRecords');
    Route::get('/repostCompanyStoreTwoTableRecords', 'StoreDeleteController@repostCompanyStoreTwoTableRecords');
    
	Route::get('/approveStockTransferVoucherDetail', 'StoreDeleteController@approveStockTransferVoucherDetail');
	


});
//End Company Database Record Delete


//Start Select List Ajax Load

//End Select List Ajax Load

//Start Companies
Route::group(['prefix' => 'companies','before' => 'csrf'], function () {
    Route::get('/c', 'ClientCompaniesController@toDayActivity');
    Route::post('/addCompanyDetail','ClientCompaniesController@addCompanyDetail');
    Route::post('/editCompanyDetail','ClientCompaniesController@editCompanyDetail');

});

Route::group(['prefix' => 'ccd','before' => 'csrf'], function () {
    $companiesList = DB::table('company')->select(['name','id','dbName'])->where('status','=','1')->get();
    foreach($companiesList as $routeRow1){
        Route::get('/'.$routeRow1->dbName.'', 'ClientController@clientCompanyMenu');
    }
});

//End Companies




Route::post('import-file', 'ExcelController@importFile')->name('import.file');
Route::post('import-employees', 'ExcelController@importEmployees')->name('import.employees');
Route::post('tester', 'ExcelController@tester')->name('test.file');

Route::get('/filetest', 'ExcelController@filetest');

Route::get('/mytest', 'HomeController@mytest');
Route::post('/createmytest', 'HomeController@createmytest');

//Test
Route::group(['prefix' => 'test','before' => 'csrf'], function () {
    Route::get('/createUser','TestMainController@createUser');
    Route::post('/addUserDetail','TestAddController@addUserDetail');
});

Route::group(['prefix' => 'HrReports','before' => 'csrf'], function () {

    Route::get('/viewTurnoverReportForm','HrReportsController@viewTurnoverReportForm');
    Route::get('/viewOnboardReportForm','HrReportsController@viewOnboardReportForm');
    Route::get('/viewIncrementReportForm','HrReportsController@viewIncrementReportForm');
    Route::get('/viewWarningReportForm','HrReportsController@viewWarningReportForm');
    Route::get('/viewEmployeeReportForm','HrReportsController@viewEmployeeReportForm');
    Route::get('/viewTransferReportForm','HrReportsController@viewTransferReportForm');
    Route::get('/viewMedicalReportForm','HrReportsController@viewMedicalReportForm');
    Route::get('/viewTrainingReportForm','HrReportsController@viewTrainingReportForm');
    Route::get('/viewGratuityReportForm','HrReportsController@viewGratuityReportForm');
    Route::get('/viewEmployeeExpReportForm','HrReportsController@viewEmployeeExpReportForm');


    Route::get('/viewTurnoverReport','HrReportsController@viewTurnoverReport');
    Route::get('/viewOnboardReport','HrReportsController@viewOnboardReport');
    Route::get('/viewIncrementReport','HrReportsController@viewIncrementReport');
    Route::get('/viewWarningReport','HrReportsController@viewWarningReport');
    Route::get('/viewEmployeeReport','HrReportsController@viewEmployeeReport');
    Route::get('/viewTransferReport','HrReportsController@viewTransferReport');
    Route::get('/viewMedicalReport','HrReportsController@viewMedicalReport');
    Route::get('/viewTrainingReport','HrReportsController@viewTrainingReport');
    Route::get('/viewGratuityReport','HrReportsController@viewGratuityReport');
    Route::get('/viewEmployeeExpReport','HrReportsController@viewEmployeeExpReport');

});

Route::group(['prefix' => 'dc','before' => 'csrf'], function () {

    Route::get('/hrDashboard','DashboardController@hrDashboard');
    Route::get('/userDashboard','DashboardController@userDashboard');
    

});

Route::group(['prefix' => 'ddc','before' => 'csrf'], function () {
      Route::get('/basicInfo','DashboardDataCallController@basicInfo');
	  Route::get('/checkingPassword','DashboardDataCallController@checkingPassword');
	  Route::get('/filterUserDashboard','DashboardDataCallController@filterUserDashboard');
	  Route::get('/addEducationDetails','DashboardDataCallController@addEducationDetails');
	  Route::get('/filterTeamVise','DashboardDataCallController@filterTeamVise'); 
});



Route::group(['prefix' => 'cj'], function () {

    Route::get('/loanCronJob','CronJobController@loanCronJob');
    Route::get('/arrearCronJob','CronJobController@arrearCronJob');
    Route::get('/abnormalitiesCheck','CronJobController@abnormalitiesCheck');

});

/*Log Routes*/
Route::group(['prefix' => 'log','before' => 'csrf'], function () {
    Route::get('/viewLog','LogController@viewLog');
});

Route::group(['prefix' => 'ldc','before' => 'csrf'], function () {
    Route::get('/viewLogDetail','LogDataCallController@viewLogDetail');
    Route::get('/viewActivityDetail','LogDataCallController@viewActivityDetail');

});



require('modules/users.php');
require('modules/selectlist.php');
require('modules/visitor.php');
require('modules/hr.php');
