<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use DB;
use Config;
use Input;
use Session;
use Redirect;
use Mail;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Payslip;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\JobType;
use App\Models\SubDepartment;
use App\Models\MaritalStatus;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\CarPolicy;
use App\Models\LeaveType;
use App\Models\LeaveApplicationData;


class HrAddDetailByAjaxController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function addLeaveApplicationDetail()
    {
        //CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $emp_id = Input::get('emp_id');
//
//echo $emp_id;
//die;
        $leaveApplicationCheck1 =  LeaveApplicationData::where([['view','=','yes'],['leave_policy_id','=',Input::get('leave_policy_id')],['from_date','=', Input::get('from_date')],['first_second_half','=',''],['emp_id','=',Input::get('emp_id')]])->count();

        $leaveApplicationCheck2 = LeaveApplicationData::where([['view','=','yes'],['leave_policy_id','=',Input::get('leave_policy_id')],['first_second_half_date','=', Input::get('first_second_half_date')],
            ['emp_id','=',Input::get('emp_id')],['first_second_half','!=','']])->count();

        if( $leaveApplicationCheck1 > 0 || $leaveApplicationCheck2 > 0):

            return "Employee Emp ID:".Input::get('emp_id')." Leave Application Already Exist on Date ".Input::get('from_date').Input::get('first_second_half_date');

        else:

            $leaveApplicationData['emp_id']          = Input::get('emp_id');
            $leaveApplicationData['leave_policy_id'] = Input::get('leave_policy_id');
            $leaveApplicationData['company_id']      = Input::get('company_id');
            $leaveApplicationData['leave_type']      = Input::get('leave_type');
            $leaveApplicationData['leave_day_type']  = Input::get('leave_day_type');
            $leaveApplicationData['reason']          = Input::get('reason');
            $leaveApplicationData['leave_address']   = Input::get('leave_address');
            $leaveApplicationData['approval_status'] = 1;
            $leaveApplicationData['status']          = 1;
            $leaveApplicationData['username']        = Auth::user()->name;
            $leaveApplicationData['date']            = date("Y-m-d");
            $leaveApplicationData['time']            = date("H:i:s");

            $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);


            /* Annual Leaves ID = 3 */
            if(Input::get('leave_type') == 1):

                $maternityLeavesData['emp_id']               = Input::get('emp_id');
                $maternityLeavesData['leave_application_id'] = $leave_application_id;
                $maternityLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                $maternityLeavesData['leave_type']           = Input::get('leave_type');
                $maternityLeavesData['leave_day_type']       = Input::get('leave_day_type');
                $maternityLeavesData['no_of_days']           = Input::get('no_of_days');
                $maternityLeavesData['from_date']            = Input::get('from_date');
                $maternityLeavesData['to_date']              = Input::get('to_date');
                $maternityLeavesData['status']               = 1;
                $maternityLeavesData['username']             = Auth::user()->name;
                $maternityLeavesData['date']                 = date("Y-m-d");
                $maternityLeavesData['time']                 = date("H:i:s");

                DB::table('leave_application_data')->insert($maternityLeavesData);

            elseif(Input::get('leave_type') == 2):

                /* Full Day Leaves */
                if(Input::get('leave_day_type') == 1):

                    $annualLeavesData['emp_id']               = Input::get('emp_id');
                    $annualLeavesData['leave_application_id'] = $leave_application_id;
                    $annualLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                    $annualLeavesData['leave_type']           = Input::get('leave_type');
                    $annualLeavesData['leave_day_type']       = Input::get('leave_day_type');
                    $annualLeavesData['no_of_days']           = Input::get('no_of_days');
                    $annualLeavesData['from_date']            = Input::get('from_date');
                    $annualLeavesData['to_date']              = Input::get('to_date');
                    $annualLeavesData['status']               = 1;
                    $annualLeavesData['username']             = Auth::user()->name;
                    $annualLeavesData['date']                 = date("Y-m-d");
                    $annualLeavesData['time']                 = date("H:i:s");

                    DB::table('leave_application_data')->insert($annualLeavesData);

                /* Half Day Leaves */
                elseif(Input::get('leave_day_type') == 2):

                    $halfdayLeavesData['emp_id']                   = Input::get('emp_id');
                    $halfdayLeavesData['leave_application_id']     = $leave_application_id;
                    $halfdayLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                    $halfdayLeavesData['leave_type']               = Input::get('leave_type');
                    $halfdayLeavesData['leave_day_type']           = Input::get('leave_day_type');
                    $halfdayLeavesData['no_of_days']               = Input::get('no_of_days');
                    $halfdayLeavesData['first_second_half']        = Input::get('first_second_half');
                    $halfdayLeavesData['first_second_half_date']   = Input::get('first_second_half_date');
                    $halfdayLeavesData['status']                   = 1;
                    $halfdayLeavesData['username']                 = Auth::user()->name;
                    $halfdayLeavesData['date']                     = date("Y-m-d");
                    $halfdayLeavesData['time']                     = date("H:i:s");

                    DB::table('leave_application_data')->insert($halfdayLeavesData);

                else:
                    /* Short Leaves */

                    $shortLeavesData['emp_id']               = Input::get('emp_id');
                    $shortLeavesData['leave_application_id'] = $leave_application_id;
                    $shortLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                    $shortLeavesData['leave_type']           = Input::get('leave_type');
                    $shortLeavesData['leave_day_type']       = Input::get('leave_day_type');
                    $shortLeavesData['no_of_days']           = Input::get('no_of_days');
                    $shortLeavesData['short_leave_time_from']= Input::get('short_leave_time_from');
                    $shortLeavesData['short_leave_time_to']  = Input::get('short_leave_time_to');
                    $shortLeavesData['short_leave_date']     = Input::get('short_leave_date');
                    $shortLeavesData['status']               = 1;
                    $shortLeavesData['username']             = Auth::user()->name;
                    $shortLeavesData['date']                 = date("Y-m-d");
                    $shortLeavesData['time']                 = date("H:i:s");


                    DB::table('leave_application_data')->insert($shortLeavesData);

                endif;

            elseif(Input::get('leave_type') == 3):


                /* Full Day Leaves */
                if(Input::get('leave_day_type') == 1):

                    $annualLeavesData['emp_id']               = Input::get('emp_id');
                    $annualLeavesData['leave_application_id'] = $leave_application_id;
                    $annualLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                    $annualLeavesData['leave_type']           = Input::get('leave_type');
                    $annualLeavesData['leave_day_type']       = Input::get('leave_day_type');
                    $annualLeavesData['no_of_days']           = Input::get('no_of_days');
                    $annualLeavesData['from_date']            = Input::get('from_date');
                    $annualLeavesData['to_date']              = Input::get('to_date');
                    $annualLeavesData['status']               = 1;
                    $annualLeavesData['username']             = Auth::user()->name;
                    $annualLeavesData['date']                 = date("Y-m-d");
                    $annualLeavesData['time']                 = date("H:i:s");

                    DB::table('leave_application_data')->insert($annualLeavesData);

                /* Half Day Leaves */
                elseif(Input::get('leave_day_type') == 2):

                    $halfdayLeavesData['emp_id']                   = Input::get('emp_id');
                    $halfdayLeavesData['leave_application_id']     = $leave_application_id;
                    $halfdayLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                    $halfdayLeavesData['leave_type']               = Input::get('leave_type');
                    $halfdayLeavesData['leave_day_type']           = Input::get('leave_day_type');
                    $halfdayLeavesData['no_of_days']               = Input::get('no_of_days');
                    $halfdayLeavesData['first_second_half']        = Input::get('first_second_half');
                    $halfdayLeavesData['first_second_half_date']   = Input::get('first_second_half_date');
                    $halfdayLeavesData['status']                   = 1;
                    $halfdayLeavesData['username']                 = Auth::user()->name;
                    $halfdayLeavesData['date']                     = date("Y-m-d");
                    $halfdayLeavesData['time']                     = date("H:i:s");

                    DB::table('leave_application_data')->insert($halfdayLeavesData);

                else:
                    /* Short Leaves */

                    $shortLeavesData['emp_id']               = Input::get('emp_id');
                    $shortLeavesData['leave_application_id'] = $leave_application_id;
                    $shortLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                    $shortLeavesData['leave_type']           = Input::get('leave_type');
                    $shortLeavesData['leave_day_type']       = Input::get('leave_day_type');
                    $shortLeavesData['no_of_days']           = Input::get('no_of_days');
                    $shortLeavesData['short_leave_time_from']= Input::get('short_leave_time_from');
                    $shortLeavesData['short_leave_time_to']  = Input::get('short_leave_time_to');
                    $shortLeavesData['short_leave_date']     = Input::get('short_leave_date');
                    $shortLeavesData['status']               = 1;
                    $shortLeavesData['username']             = Auth::user()->name;
                    $shortLeavesData['date']                 = date("Y-m-d");
                    $shortLeavesData['time']                 = date("H:i:s");


                    DB::table('leave_application_data')->insert($shortLeavesData);

                endif;

            else:


                /* Full Day Leaves */
                if(Input::get('leave_day_type') == 1):

                    $annualLeavesData['emp_id']               = Input::get('emp_id');
                    $annualLeavesData['leave_application_id'] = $leave_application_id;
                    $annualLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                    $annualLeavesData['leave_type']           = Input::get('leave_type');
                    $annualLeavesData['leave_day_type']       = Input::get('leave_day_type');
                    $annualLeavesData['no_of_days']           = Input::get('no_of_days');
                    $annualLeavesData['from_date']            = Input::get('from_date');
                    $annualLeavesData['to_date']              = Input::get('to_date');
                    $annualLeavesData['status']               = 1;
                    $annualLeavesData['username']             = Auth::user()->name;
                    $annualLeavesData['date']                 = date("Y-m-d");
                    $annualLeavesData['time']                 = date("H:i:s");

                    DB::table('leave_application_data')->insert($annualLeavesData);

                /* Half Day Leaves */
                elseif(Input::get('leave_day_type') == 2):

                    $halfdayLeavesData['emp_id']                   = Input::get('emp_id');
                    $halfdayLeavesData['leave_application_id']     = $leave_application_id;
                    $halfdayLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                    $halfdayLeavesData['leave_type']               = Input::get('leave_type');
                    $halfdayLeavesData['leave_day_type']           = Input::get('leave_day_type');
                    $halfdayLeavesData['no_of_days']               = Input::get('no_of_days');
                    $halfdayLeavesData['first_second_half']        = Input::get('first_second_half');
                    $halfdayLeavesData['first_second_half_date']   = Input::get('first_second_half_date');
                    $halfdayLeavesData['status']                   = 1;
                    $halfdayLeavesData['username']                 = Auth::user()->name;
                    $halfdayLeavesData['date']                     = date("Y-m-d");
                    $halfdayLeavesData['time']                     = date("H:i:s");

                    DB::table('leave_application_data')->insert($halfdayLeavesData);

                else:
                    /* Short Leaves */

                    $shortLeavesData['emp_id']               = Input::get('emp_id');
                    $shortLeavesData['leave_application_id'] = $leave_application_id;
                    $shortLeavesData['leave_policy_id'] = Input::get('leave_policy_id');
                    $shortLeavesData['leave_type']           = Input::get('leave_type');
                    $shortLeavesData['leave_day_type']       = Input::get('leave_day_type');
                    $shortLeavesData['no_of_days']           = Input::get('no_of_days');
                    $shortLeavesData['short_leave_time_from']= Input::get('short_leave_time_from');
                    $shortLeavesData['short_leave_time_to']  = Input::get('short_leave_time_to');
                    $shortLeavesData['short_leave_date']     = Input::get('short_leave_date');
                    $shortLeavesData['status']               = 1;
                    $shortLeavesData['username']             = Auth::user()->name;
                    $shortLeavesData['date']                 = date("Y-m-d");
                    $shortLeavesData['time']                 = date("H:i:s");

                    DB::table('leave_application_data')->insert($shortLeavesData);

                endif;

            endif;
           $leaveType =  LeaveType::where([['id', '=', Input::get('leave_type')]])->value('leave_type_name');

            if(Input::get('leave_day_type') == 1):
                $from_date = Input::get('from_date');
                $to_date   = Input::get('to_date');
                $messages =  'Please approve my leave application for '.Input::get('no_of_days').' days from '.$from_date." to ".$to_date." leave type ".$leaveType;
            elseif(Input::get('leave_day_type') == 2):

                $from_date = Input::get('first_second_half');
                $to_date = Input::get('first_second_half_date');
                $messages =  'Please approve my leave application for '.Input::get('no_of_days').' days from '.$to_date." leave type ".$leaveType;


            endif;
            if($leaveType == "Annual"):
                CommonHelper::companyDatabaseConnection('12');

                $cnic = Employee::where([['emp_id', '=', $emp_id]])->select('emp_id','emp_name','reporting_manager')->first();
                $professional_email = Employee::where([['emp_id', '=', $cnic->emp_id]])->value('professional_email');
                $details = [
                    'title' =>$cnic->emp_name,
                    'body' => $messages
                ];

                Mail::to($professional_email)->send(new \App\Mail\MyTestMail($details));

                CommonHelper::reconnectMasterDatabase();
                else:
                    CommonHelper::companyDatabaseConnection('12');

                    $cnic = Employee::where([['emp_id', '=', $emp_id]])->select('emp_id','emp_name','reporting_manager')->first();
                    $professional_email = Employee::where([['emp_id', '=', $cnic->emp_id]])->value('professional_email');
                    $details = [
                        'title' =>$cnic->emp_name,
                        'body' => $messages
                    ];

                    Mail::to($professional_email)->send(new \App\Mail\MyTestMail($details));

                    //dd("Email is Sent.");
                    CommonHelper::reconnectMasterDatabase();
            endif;

            Session::flash('emp_id',$emp_id);
            return "1";
        endif;
die;
        Session::flash('dataInsert','successfully saved.');
        CommonHelper::reconnectMasterDatabase();

    }

    public function addEmployeeSixthMonthAuditDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_and_year               = explode("-",Input::get('date'));
        $data['emp_id']               = Input::get('emr_no');
        $data['month']                = $month_and_year[0];
        $data['year']                 = $month_and_year[1];
        $data['status']               = 1;
        $data['username']             = Auth::user()->name;
        $data['date']                 = date("Y-m-d");
        $data['time']                 = date("H:i:s");
        DB::table('employee_hr_audit')->insert($data);
        CommonHelper::reconnectMasterDatabase();

    }

    public function addEmployeeTwelfthMonthAuditDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_and_year               = explode("-",Input::get('date'));
        $data['emp_id']               = Input::get('emr_no');
        $data['month']                = $month_and_year[0];
        $data['year']                 = $month_and_year[1];
        $data['status']               = 1;
        $data['username']             = Auth::user()->name;
        $data['date']                 = date("Y-m-d");
        $data['time']                 = date("H:i:s");
        DB::table('employee_hr_audit')->insert($data);
        CommonHelper::reconnectMasterDatabase();
    }

    public function addMasterTableDetail()
    {
        $tableName = Input::get('tableName');
        $columnName = Input::get('columnName');
        $name = Input::get('name');
        $department_id = Input::get('department_id');

        if($department_id != ''):
            $data1['department_id'] = $department_id;
        endif;

        $data1[$columnName] = $name;
        $data1['company_id'] = Input::get('m');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        $lastInsertId = DB::table($tableName)->insertGetId($data1);
        return response()->json(['id'=> $lastInsertId, 'name' => $name]);

    }


    public function addManualyAttendance()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emr_no = Input::get('emr_no');
        $attendance_type = Input::get('attendance_type');

        if($attendance_type == 2):

            $date = Input::get('attendance_date');
            if(DB::table('attendance')->where([['emr_no','=',$emr_no],['attendance_date', '=', $date], ['attendance_type', '=', 2]])->exists()):
                DB::table('attendance')->where([['emr_no','=',$emr_no],['attendance_date', '=', $date]])->delete();
            endif;

            $day = strtotime($date);
            $day = date('D', $day);

            $month = strtotime($date);
            $month = date('m', $month);

            $year = strtotime($date);
            $year = date('Y', $year);

            $data1['emr_no'] = $emr_no;
            $data1['attendance_date'] = $date;
            $data1['attendance_status'] = Input::get('attendance_status');
            $data1['day'] = $day;
            $data1['month'] = $month;
            $data1['year'] = $year;
        endif;

        if ($attendance_type == 1):

            $date = Input::get('date_to');

            $month = date('m', strtotime($date));
            $year = date('Y',  strtotime($date));

            if(DB::table('attendance')->where([['emr_no','=',$emr_no],['month', '=', $month],['year', '=',$year ],['attendance_type', '=', 1]])->exists()):
                DB::table('attendance')->where([['emr_no','=',$emr_no],['month', '=', $month],['year', '=',$year ]])->delete();
            endif;

            $data1['emr_no']                = $emr_no;
            $data1['attendance_from']       = Input::get('date_from');
            $data1['attendance_to']         = Input::get('date_to');
            $data1['month']                 = date('m',strtotime(Input::get('date_to')));
            $data1['year']                  = date('Y',strtotime(Input::get('date_to')));
            $data1['present_days']          = Input::get('present_days');
            $data1['absent_days']           = Input::get('absent_days');
            $data1['overtime']              = Input::get('overtime');
        endif;

        $data1['attendance_type'] = $attendance_type;
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        DB::table('attendance')->insert($data1);

        $data3['table_name']         = 'attendance';
        $data3['activity_id']        = null;
        $data3['deleted_emr_no']     = null;
        $data3['activity']           = 'Insert';
        $data3['module']             = 'hr';
        $data3['username']           = Auth::user()->name;
        $data3['date']               = date("Y-m-d");
        $data3['time']               = date("H:i:s");
        DB::table('log')->insert($data3);

        CommonHelper::reconnectMasterDatabase();
        return response()->json(['success'=> 'Attendance Submitted']);
    }

    public function addUserQueryDetails()
    {

        $month_year = explode('-', Input::get('date'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $data1['emp_id'] = strip_tags(Input::get('emp_id'));
        $data1['queryType'] = strip_tags(Input::get('queryType'));
        $data1['query_date'] = strip_tags(Input::get('date'));
        $data1['month'] = $month_year[1];
        $data1['year'] = $month_year[0];
        $data1['remarks'] = strip_tags(Input::get('remarks'));
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        DB::table('user_query')->insert($data1);

        CommonHelper::reconnectMasterDatabase();

    }





}
?>