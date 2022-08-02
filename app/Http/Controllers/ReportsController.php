<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use Auth;
use DB;
use Config;
use App\Models\Category;

class ReportsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function toDayActivity()
    {
        return view('Reports.toDayActivity');
    }

    public function viewBankDepositSummary(){
        return view('Reports.Finance.BankingReport.viewBankDepositSummary');
    }

    public function viewBranchPerformanceReports(){
        return view('Reports.Finance.PerformanceReports.viewBranchPerformanceReports');
    }

    public function viewBranchExpenseSummaryReports(){
        return view('Reports.Finance.PerformanceReports.viewBranchExpenseSummaryReports');
    }

    public function viewBranchExpenseSummaryDetailReports(){
        return view('Reports.Finance.PerformanceReports.viewBranchExpenseSummaryDetailReports');
    }

    public function viewInventoryPerformanceDetailReports(){
        return view('Reports.Inventory.viewInventoryPerformanceDetailReports');
    }
	
	public function viewStockIssueanceLocationWiseReports(){
		$checkPermission =  CommonHelper::checkUserPermissionForSingleOption($_GET['m'],Auth::user()->id,Auth::user()->emp_id,$_GET['pageType'],$_GET['parentCode'],'Store',Auth::user()->acc_type);
        if($checkPermission != 1) {
            return view('dontPermissionForPage');
        }
		$getRegionPrivilagesArray = CommonHelper::getRegionPrivilagesArray($_GET['m'],Auth::user()->emr_no);
		$regionsList = DB::table('regions')->where('company_id','=',$_GET['m'])->whereIn('id',$getRegionPrivilagesArray)->where('status','=','1')->get();
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $categoryList = new Category;
        $categoryList = $categoryList::where([['company_id', '=', $_GET['m']], ])->orderBy('id')->get();
		$customerLocationList = DB::table('customer_location')->whereIn('region_id',$getRegionPrivilagesArray)->where('status','=','1')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Reports.Inventory.viewStockIssueanceLocationWiseReports',compact('regionsList','categoryList','customerLocationList'));
    }
	
	public function viewPurchaseAndStockMonitoringReports(){
		$checkPermission =  CommonHelper::checkUserPermissionForSingleOption($_GET['m'],Auth::user()->id,Auth::user()->emp_id,$_GET['pageType'],$_GET['parentCode'],'Store',Auth::user()->acc_type);
        if($checkPermission != 1) {
            return view('dontPermissionForPage');
        }
		$getRegionPrivilagesArray = CommonHelper::getRegionPrivilagesArray($_GET['m'],Auth::user()->emr_no);
		$regionsList = DB::table('regions')->where('company_id','=',$_GET['m'])->whereIn('id',$getRegionPrivilagesArray)->where('status','=','1')->get();
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $categoryList = new Category;
        $categoryList = $categoryList::where([['company_id', '=', $_GET['m']], ])->orderBy('id')->get();
		CommonHelper::reconnectMasterDatabase();
        return view('Reports.Inventory.viewPurchaseAndStockMonitoringReports',compact('regionsList','categoryList'));
    }
	
	public function viewStockPurchaseAndIssuanceRangeWiseReports(){
		$checkPermission =  CommonHelper::checkUserPermissionForSingleOption($_GET['m'],Auth::user()->id,Auth::user()->emp_id,$_GET['pageType'],$_GET['parentCode'],'Store',Auth::user()->acc_type);
        if($checkPermission != 1) {
            return view('dontPermissionForPage');
        }
		$getRegionPrivilagesArray = CommonHelper::getRegionPrivilagesArray($_GET['m'],Auth::user()->emr_no);
		$regionsList = DB::table('regions')->where('company_id','=',$_GET['m'])->whereIn('id',$getRegionPrivilagesArray)->where('status','=','1')->get();
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $categoryList = new Category;
        $categoryList = $categoryList::where([['company_id', '=', $_GET['m']], ])->orderBy('id')->get();
		$customerLocationList = DB::table('customer_location')->whereIn('region_id',$getRegionPrivilagesArray)->get();
		CommonHelper::reconnectMasterDatabase();
        return view('Reports.Inventory.viewStockPurchaseAndIssuanceRangeWiseReports',compact('regionsList','categoryList','customerLocationList'));
    }
}
