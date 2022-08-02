<?php

namespace App\Http\Controllers;
//namespace App\Http\Controllers\Auth
//use Auth;
//use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\SubDepartment;
use App\Models\Department;
use App\Models\LeaveApplication;
use App\Models\LeaveApplicationData;
use Input;
use Auth;
use DB;
use Redirect;

class HomeController extends Controller
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
    public function index()
    {
		//return view('pages.home');
        return view('dUser.userDashboard');
    }


    public function mytest()
    {
      ///  return view('Hr.mytest');
        $subdepartments = new SubDepartment;
        $departments = Department::where('company_id','=',$_GET['m'])->orderBy('id')->get();
        return view('Hr.mytest',compact('departments','subdepartments'));
    }
    public function createmytest()
    {

        $annual_leaves = 28;
        $casual_leaves = 12;
        $sick_leaves = 6;

        $leaves[1] = $annual_leaves-Input::get('annual_leaves');
        $leaves [3]= $casual_leaves-Input::get('casual_leaves');
        $leaves [2]= $sick_leaves-Input::get('sick_leaves');

       foreach ($leaves as $key=>$value)
       {
            if($key == 1 )
            {


                $leaveApplicationData['emp_id']          = Input::get('employee_id');
                $leaveApplicationData['leave_policy_id'] = 1;
                $leaveApplicationData['leave_type']      = $key;
                $leaveApplicationData['leave_day_type']  = 1;
                $leaveApplicationData['reason']          = "-";
                $leaveApplicationData['leave_address']   = "-";
                $leaveApplicationData['approval_status'] = 2;
                $leaveApplicationData['status']          = 1;
                $leaveApplicationData['username']        = Auth::user()->name;
                $leaveApplicationData['date']            = date("Y-m-d");
                $leaveApplicationData['time']            = date("H:i:s");

                $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);
                $annualLeavesData['emp_id']               = Input::get('employee_id');
                $annualLeavesData['leave_application_id'] = $leave_application_id;
                $annualLeavesData['leave_policy_id'] =1;
                $annualLeavesData['leave_type']           = $key;
                $annualLeavesData['leave_day_type']       = 1;
                $annualLeavesData['no_of_days']           = $value;
                $annualLeavesData['from_date']            = date("Y-m-d");
                $annualLeavesData['to_date']              = date("Y-m-d");
                $annualLeavesData['status']               = 1;
                $annualLeavesData['username']             = Auth::user()->name;
                $annualLeavesData['date']                 = date("Y-m-d");
                $annualLeavesData['time']                 = date("H:i:s");
                DB::table('leave_application_data')->insert($annualLeavesData);

            }
            elseif($key == 2 )
            {
                $leaveApplicationData['emp_id']          = Input::get('employee_id');
                $leaveApplicationData['leave_policy_id'] = 1;
                $leaveApplicationData['leave_type']      = $key;
                $leaveApplicationData['leave_day_type']  = 1;
                $leaveApplicationData['reason']          = "-";
                $leaveApplicationData['leave_address']   = "-";
                $leaveApplicationData['approval_status'] = 2;
                $leaveApplicationData['status']          = 1;
                $leaveApplicationData['username']        = Auth::user()->name;
                $leaveApplicationData['date']            = date("Y-m-d");
                $leaveApplicationData['time']            = date("H:i:s");

                $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);
                $annualLeavesData['emp_id']               = Input::get('employee_id');
                $annualLeavesData['leave_application_id'] = $leave_application_id;
                $annualLeavesData['leave_policy_id'] =1;
                $annualLeavesData['leave_type']           = $key;
                $annualLeavesData['leave_day_type']       = 1;
                $annualLeavesData['no_of_days']           = $value;
                $annualLeavesData['from_date']            = date("Y-m-d");
                $annualLeavesData['to_date']              = date("Y-m-d");
                $annualLeavesData['status']               = 1;
                $annualLeavesData['username']             = Auth::user()->name;
                $annualLeavesData['date']                 = date("Y-m-d");
                $annualLeavesData['time']                 = date("H:i:s");
                DB::table('leave_application_data')->insert($annualLeavesData);
            }
            elseif($key == 3 )
            {
                $leaveApplicationData['emp_id']          = Input::get('employee_id');
                $leaveApplicationData['leave_policy_id'] = 1;
                $leaveApplicationData['leave_type']      = $key;
                $leaveApplicationData['leave_day_type']  = 1;
                $leaveApplicationData['reason']          = "-";
                $leaveApplicationData['leave_address']   = "-";
                $leaveApplicationData['approval_status'] = 2;
                $leaveApplicationData['status']          = 1;
                $leaveApplicationData['username']        = Auth::user()->name;
                $leaveApplicationData['date']            = date("Y-m-d");
                $leaveApplicationData['time']            = date("H:i:s");

                $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);
                $annualLeavesData['emp_id']               = Input::get('employee_id');
                $annualLeavesData['leave_application_id'] = $leave_application_id;
                $annualLeavesData['leave_policy_id'] =1;
                $annualLeavesData['leave_type']           = $key;
                $annualLeavesData['leave_day_type']       = 1;
                $annualLeavesData['no_of_days']           = $value;
                $annualLeavesData['from_date']            = date("Y-m-d");
                $annualLeavesData['to_date']              = date("Y-m-d");
                $annualLeavesData['status']               = 1;
                $annualLeavesData['username']             = Auth::user()->name;
                $annualLeavesData['date']                 = date("Y-m-d");
                $annualLeavesData['time']                 = date("H:i:s");
                DB::table('leave_application_data')->insert($annualLeavesData);
            }


       }


        return Redirect::back();
    }
}



