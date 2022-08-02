
<?php

use Illuminate\Http\Request;
use App\Models\TransferedLeaves;
use App\Models\Employee;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/tttt', function (Request $request) {

echo 1;
  
});

Route::post('/userLogin', function (Request $request) {


    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        return response()->json(['status'=>'true','emp_id'=>Auth::user()->emp_id]);
    }
    else{
        return response()->json(['status'=>'false','message'=>'Incorrect Username/Password']);
    }
});

Route::get('/viewUpcomingBirthdays', function (Request $request) {
    \App\Helpers\CommonHelper::companyDatabaseConnection(12);
    $upcoming_birthdays_detail = DB::select( DB::raw("SELECT DAY(date) as date,MONTHNAME(emp_date_of_birth) AS bday_month,emp_gender,emp_name,YEAR(CURDATE()) - YEAR(emp_date_of_birth) AS age FROM employee where status = 1 and DATE_FORMAT(emp_date_of_birth, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') and DATE_FORMAT(emp_date_of_birth, '%m-%d') <= DATE_FORMAT((NOW() + INTERVAL + 10 day), '%m-%d') ORDER BY MONTH(emp_date_of_birth), DAYOFMONTH(emp_date_of_birth)"));
    \App\Helpers\CommonHelper::reconnectMasterDatabase();
    return response()->json($upcoming_birthdays_detail);

});


Route::get('/viewEmpLeavesList', function (Request $request) {
   
	
	$leave_application_data = DB::table('leave_application')
            ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
			->join('leave_type', 'leave_type.id', '=', 'leave_application.leave_type')
            ->select('leave_type.leave_type_name','leave_application.leave_day_type',
                'leave_application.date','leave_application.id','leave_application.approval_status','leave_application.approval_status_lm','leave_application_data.no_of_days','leave_application.reason','leave_application_data.no_of_days','leave_application_data.from_date','leave_application_data.first_second_half','leave_application_data.first_second_half_date',
            'leave_application_data.to_date')
			->where('leave_application.emp_id','=',$request->userId)
            ->where('leave_application.view','=','yes')
			->orderBy('leave_application.approval_status')
            ->get();
   
    return response()->json($leave_application_data);

});


Route::get('/viewEmpLeaveByMonth', function (Request $request) {
   
   $monthYear = explode("-",$request->monthYear);

	
	$leave_application_data = DB::table('leave_application')
            ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
			->join('leave_type', 'leave_type.id', '=', 'leave_application.leave_type')
            ->select('leave_type.leave_type_name','leave_application.leave_day_type',
                'leave_application.date', 'leave_application.id','leave_application.approval_status','leave_application.approval_status_lm','leave_application_data.no_of_days','leave_application.reason','leave_application_data.no_of_days','leave_application_data.from_date','leave_application_data.first_second_half','leave_application_data.first_second_half_date',
            'leave_application_data.to_date')
			->where('leave_application.emp_id','=',$request->userId)
            ->where('leave_application.view','=','yes')
            ->whereMonth('leave_application.date','=',$monthYear[1])
            ->whereYear('leave_application.date', '=', $monthYear[0])
			->orderBy('leave_application.approval_status')
            ->get();
   
    return response()->json($leave_application_data);

});



Route::get('/deleteLeaveApplicationById', function (Request $request) {
   

	

	DB::table('leave_application')->where('id',$request->id)->delete();
	DB::table('leave_application_data')->where('leave_application_id',$request->id)->delete();
   
    return response()->json(true);

});


Route::get('/viewLeaveBalances', function (Request $request) {
   

	$value = array('emp_id'=>27);
	 \App\Helpers\CommonHelper::companyDatabaseConnection(12);
      $leave_policy_id = Employee::select('leaves_policy_id')->where([['emp_id','=',$value["emp_id"]],['status','=',1]])->get(); 
    \App\Helpers\CommonHelper::reconnectMasterDatabase();
    
  
	 $TransferedCasualLeaves = TransferedLeaves::where([['emp_id','=',$value["emp_id"]],['leaves_policy_id','=',$leave_policy_id[0]->leaves_policy_id],['status','=','1']])->value('casual_leaves');
     $total_casual_leaves = DB::table("leaves_data")
        ->select('no_of_leaves')
        ->where([['leave_type_id','=',3],['leaves_policy_id', '=', $leave_policy_id[0]->leaves_policy_id]]);

     $taken_casual_leaves = DB::table("leave_application_data")
        ->select(DB::raw("SUM(no_of_days) as taken_casual_leaves"))
        ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
        ->where([['leave_application.emp_id', '=', $value["emp_id"]], ['leave_application.status', '=', '1'],
            ['leave_application.approval_status', '=', '2'],['leave_application.leave_type','=','3'],['leave_application.leave_policy_id','=',$leave_policy_id[0]->leaves_policy_id]])
        ->first();

     $causal_sick =  $total_casual_leaves->value('no_of_leaves')+$TransferedCasualLeaves-$taken_casual_leaves->taken_casual_leaves;
     
     
       $TransferedAnnualLeaves = TransferedLeaves::where([['emp_id','=',$value["emp_id"]],['leaves_policy_id','=',$leave_policy_id[0]->leaves_policy_id],['status','=','1']])->value('annual_leaves');
        $total_annual_leaves = DB::table("leaves_data")
            ->select('no_of_leaves')
            ->where([['leave_type_id','=',1],['leaves_policy_id', '=',$leave_policy_id[0]->leaves_policy_id]]);

        $taken_annual_leaves = DB::table("leave_application_data")
            ->select(DB::raw("SUM(no_of_days) as taken_annual_leaves"))
            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
            ->where([['leave_application.emp_id', '=', $value["emp_id"]], ['leave_application.status', '=', '1'],
                ['leave_application.approval_status', '=', '2'],['leave_application.leave_type','=','1'],['leave_application.leave_policy_id','=',$leave_policy_id[0]->leaves_policy_id]])
            ->first();
        $annual =  $total_annual_leaves->value('no_of_leaves')+$TransferedAnnualLeaves-$taken_annual_leaves->taken_annual_leaves;
                                           

    return response()->json(array($annual));

});







