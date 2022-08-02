<?php
namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\DegreeType;
use App\Models\EmployeeWorkExperience;
use App\Models\EmployeeOfTheMonth;
use App\Helpers\CommonHelper;
use App\Http\Requests;
use Hash;
use Input;
use Auth;
use DB;
use Config;

use Illuminate\Pagination\LengthAwarePaginator;
class DashboardDataCallController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function basicInfo(){
        $employeeResponse = [];
        $name = Input::get('name');
        $p_email = Input::get('p_email');
        $cnic = Input::get('cnic');
        $phone = Input::get('phone');
        $dob = Input::get('dob');
        $emp_id = Input::get('emp_id');

        $convertDob = date("Y-m-d", strtotime($dob));

        $data['emp_name'] = $name;
        $data['professional_email'] = $p_email;
        $data['emp_cnic'] = $cnic;
        $data['emp_contact_no'] = $phone;
        $data['emp_date_of_birth'] = $convertDob;

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $check_cnic = Employee::where([['emp_cnic','=',$cnic],['emp_id','!=',$emp_id]]);
        if($check_cnic->count() == 0){
            Employee::where('emp_id','=',$emp_id)->update($data);
            $employeeData = Employee::where('emp_id','=',$emp_id);
            return $employeeResponse = [$employeeData->value('emp_name'),$employeeData->value('professional_email'),$employeeData->value('emp_cnic'),$employeeData->value('emp_contact_no'),$employeeData->value('emp_date_of_birth')];

        }
        else{
            echo '1';
            return;
        }
        CommonHelper::reconnectMasterDatabase();


        //return view('Dashboard.ajax_pages.basicInfo',compact('employeeData'));


    }
    public function checkingPassword(){
        $emp_id = Input::get('emp_id');
        $p_user = Input::get('p_user');
        $bycrpt_password = bcrypt($p_user);

        $users = DB::table('users')->where('emp_id','=',$emp_id);

        if (Hash::check($p_user, $users->value('password'))) {
            //The passwords match...
            echo 'matched';
        }
        else{
            echo 'not matched';
        }
        return;
    }

    public function filterUserDashboard(){



        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection($m);
        $team_members = Employee::select('emp_id','emp_name')->where('reporting_manager',Auth::user()->emp_id);
        CommonHelper::reconnectMasterDatabase();
        if(Auth::user()->acc_type == 'user' && Auth::user()->emp_id != Input::get('emp_id') && $team_members->count() == 0)
        { return '<span style="color:red"><b>Something went wrong !</b></span>'; }

        $DegreeType = DegreeType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection($m);

        //$upcoming_birthdays_detail = DB::select( DB::raw("SELECT * FROM  employee WHERE status = 1 and DATE_ADD(emp_date_of_birth,INTERVAL YEAR(CURDATE())-YEAR(emp_date_of_birth) + IF(DAYOFYEAR(CURDATE()) >= DAYOFYEAR(emp_date_of_birth),1,0) YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 10 DAY) ORDER BY MONTH(emp_date_of_birth), DAYOFMONTH(emp_date_of_birth)"));
        //$empWorkAnvs = DB::select( DB::raw("SELECT * FROM  employee WHERE status = 1 and DATE_ADD(emp_joining_date,INTERVAL YEAR(CURDATE())-YEAR(emp_joining_date) + IF(DAYOFYEAR(CURDATE()) >= DAYOFYEAR(emp_joining_date),1,0) YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 10 DAY) ORDER BY MONTH(emp_joining_date), DAYOFMONTH(emp_joining_date)"));

        $upcoming_birthdays_detail = DB::select( DB::raw("SELECT id,emp_name,emp_date_of_birth,img_path FROM employee where status = 1 and DATE_FORMAT(emp_date_of_birth, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') and DATE_FORMAT(emp_date_of_birth, '%m-%d') <= DATE_FORMAT((NOW() + INTERVAL + 10 day), '%m-%d') ORDER BY MONTH(emp_date_of_birth), DAYOFMONTH(emp_date_of_birth)"));
        $EmployeeOfTheMonth = EmployeeOfTheMonth::where([['status','=', '1'],['month','=',date('m')],['year','=',date('Y')]])->orderBy('id');

        $empWorkAnvs = DB::select( DB::raw("SELECT id,emp_id,emp_name,emp_joining_date FROM employee where status = 1 and DATE_FORMAT(emp_joining_date, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') and DATE_FORMAT(emp_joining_date, '%m-%d') <= DATE_FORMAT((NOW() + INTERVAL +10 day), '%m-%d') ORDER BY MONTH(emp_joining_date), DAYOFMONTH(emp_joining_date)"));
        $employee_time_period = Employee::select('emp_id','probation_expire_date','emp_name','emp_sub_department_id','date')->where('status','=',1)->get();

        $emp = Employee::select('id','emp_id','leaves_policy_id')->where([['emp_id', '=', Input::get('emp_id')]])->first();

        $employee_work_experience = EmployeeWorkExperience::where([['emp_id','=',Input::get('emp_id')]])->get();

        $employee_work_experience_doc = EmployeeWorkExperience::where([['emp_id','=',Input::get('emp_id')],['status','=',1],['work_exp_path', '!=', null]]);


        $attendance_machine_id = $emp->emp_id;
        CommonHelper::reconnectMasterDatabase();

        if($emp->leaves_policy_id != '0'){

            $leaves_policy = DB::table('leaves_policy')
                //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
                ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
                ->select('leaves_policy.*', 'leaves_data.*')
                ->where([['leaves_policy.id', '=', $emp->leaves_policy_id]])
                ->get();


            $leaves_policy_validatity = DB::table('leaves_policy')
                //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
                ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
                ->select('leaves_policy.id', 'leaves_data.id')
                ->where([['leaves_policy.id', '=', $emp->leaves_policy_id], ['leaves_policy.policy_date_till', '>', date("Y-m-d")]])
                ->count();

            //echo Auth::user()->emp_id; die();
            $total_leaves = DB::table("leaves_data")
                ->select(DB::raw("SUM(no_of_leaves) as total_leaves"))
                ->where([['leaves_policy_id', '=', $leaves_policy[0]->leaves_policy_id]])
                ->first();

            $taken_leaves = DB::table("leave_application_data")
                ->select(DB::raw("SUM(no_of_days) as taken_leaves"))
                ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->where([['leave_application.emp_id', '=', $emp->emp_id], ['leave_application.status', '=', '1'],
                    ['leave_application.approval_status', '=', '2']])
                ->orWhere(function ($query) use($emp) {
                    $query->where('leave_application.emp_id', '=', $emp->emp_id)
                        ->where('leave_application.status', '=', '1')
                        ->where('leave_application.approval_status_lm', '=', '2');
                })
                ->first();

            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $emp_data = Employee::where([['emp_id', '=', Input::get('emp_id')]])->orderBy('id')->first();

            CommonHelper::reconnectMasterDatabase();
            $getCurrentLeavePolicyYear = date('Y',strtotime($leaves_policy[0]->policy_date_from));
            $date = strtotime($getCurrentLeavePolicyYear.' -1 year');
            $getPreviousLeavePolicyYear = date('Y', $date);
            $getPreviousLeavePolicy = LeavesPolicy::select('id')->where('policy_date_from', 'like', $getPreviousLeavePolicyYear.'%');
            $getPreviousUsedAnnualLeavesBalance = 0;
            $getPreviousUsedCasualLeavesBalance = 0;
            if($getPreviousLeavePolicy->count() > 0 ):
                // print_r($getPreviousLeavePolicyId->first()->id);
                $getPreviousLeavePolicyId=$getPreviousLeavePolicy->first();

                $getPreviousAnnualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',1],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
                $getPreviousCasualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',3],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
                $getPreviousUsedAnnualLeaves = DB::table("leave_application_data")
                    ->select(DB::raw("SUM(no_of_days) as no_of_days"))
                    ->where([['emp_id','=',Input::get('emp_id')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','1']])
                    ->first();
                $getPreviousUsedCasualLeaves = DB::table("leave_application_data")
                    ->select(DB::raw("SUM(no_of_days) as no_of_days"))
                    ->where([['emp_id','=',Input::get('emp_id')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','3']])
                    ->first();

                $getPreviousUsedAnnualLeavesBalance =  $getPreviousAnnualLeaves-$getPreviousUsedAnnualLeaves->no_of_days;
                $getPreviousUsedCasualLeavesBalance =$getPreviousCasualLeaves-$getPreviousUsedCasualLeaves->no_of_days;

            endif;
            $WithoutLeavePolicy = [];
            $WithoutLeavePolicy[] = 'have';

            return view('Dashboard.ajax_pages.filterUserDashboard',compact('employee_work_experience_doc','employee_work_experience','EmployeeOfTheMonth','upcoming_birthdays_detail','empWorkAnvs','getPreviousUsedCasualLeavesBalance','getPreviousUsedAnnualLeavesBalance','attendance_machine_id', 'leaves_policy_validatity', 'leaves_policy', 'emp_data', 'total_leaves', 'taken_leaves','WithoutLeavePolicy','employee_time_period','DegreeType'));

        }
        else{


            $WithoutLeavePolicy = [];
            $WithoutLeavePolicy[] = 'Select Leave Policy';
            $leaves_policy_validatity = '0';
            $leaves_policy  = '0';
            return view('Dashboard.ajax_pages.filterUserDashboard',compact('employee_work_experience_doc','employee_work_experience','EmployeeOfTheMonth','upcoming_birthdays_detail','empWorkAnvs','getPreviousUsedCasualLeavesBalance','getPreviousUsedAnnualLeavesBalance','attendance_machine_id', 'leaves_policy_validatity', 'leaves_policy', 'emp_data', 'total_leaves', 'taken_leaves','WithoutLeavePolicy','employee_time_period','DegreeType'));
        }

    }


    function addEducationDetails(){
        $emp_id = Input::get('check_emp_id');

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if (!empty(Input::get('education_data'))):
            foreach (Input::get('education_data') as $educationalRow):
                $educationalData['emp_id'] = $emp_id;
                $educationalData['institute_name'] = Input::get('institute_name_' . $educationalRow . '');
                $educationalData['year_of_admission'] = Input::get('year_of_admission_' . $educationalRow . '');
                $educationalData['year_of_passing'] = Input::get('year_of_passing_' . $educationalRow . '');
                $educationalData['degree_type'] = Input::get('degree_type_' . $educationalRow . '');
                $educationalData['status'] = 1;
                $educationalData['username'] = Auth::user()->name;
                $educationalData['date'] = date("Y-m-d");
                $educationalData['time'] = date("H:i:s");
                DB::table('employee_educational_data')->insert($educationalData);

            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();

    }

    public function filterTeamVise(){

        $emp_id = Input::get('emp_id');
        $m = Input::get('m');
        $DegreeType = DegreeType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection($m);

        $upcoming_birthdays_detail = DB::select( DB::raw("SELECT id,emp_name,emp_date_of_birth,emp_sub_department_id FROM employee where DATE_FORMAT(emp_date_of_birth, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') and DATE_FORMAT(emp_date_of_birth, '%m-%d') <= DATE_FORMAT((NOW() + INTERVAL +1 month), '%m-%d') and status = 1 ORDER BY MONTH(emp_date_of_birth), DAYOFMONTH(emp_date_of_birth)"));
        $EmployeeOfTheMonth = EmployeeOfTheMonth::where([['status','=', '1'],['month','=',date('m')],['year','=',date('Y')]])->orderBy('id');
        $empWorkAnvs = DB::select( DB::raw("SELECT id,emp_name,emp_joining_date FROM employee where status = 1 and DATE_FORMAT(emp_joining_date, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') and DATE_FORMAT(emp_joining_date, '%m-%d') <= DATE_FORMAT((NOW() + INTERVAL +10 day), '%m-%d') ORDER BY MONTH(emp_joining_date), DAYOFMONTH(emp_joining_date)"));
        $emp = Employee::select('id','emp_id','leaves_policy_id')->where([['emp_id', '=', Input::get('emp_id')]])->first();
        $attendance_machine_id = $emp->emp_id;
        CommonHelper::reconnectMasterDatabase();
        if($emp->leaves_policy_id != '0'){

            $leaves_policy = DB::table('leaves_policy')
                //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
                ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
                ->select('leaves_policy.*', 'leaves_data.*')
                ->where([['leaves_policy.id', '=', $emp->leaves_policy_id]])
                ->get();

            $leaves_policy_validatity = DB::table('leaves_policy')
                //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
                ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
                ->select('leaves_policy.id', 'leaves_data.id')
                ->where([['leaves_policy.id', '=', $emp->leaves_policy_id], ['leaves_policy.policy_date_till', '>', date("Y-m-d")]])
                ->count();

            //echo Auth::user()->emp_id; die();
            $total_leaves = DB::table("leaves_data")
                ->select(DB::raw("SUM(no_of_leaves) as total_leaves"))
                ->where([['leaves_policy_id', '=', $leaves_policy[0]->leaves_policy_id]])
                ->first();

            $taken_leaves = DB::table("leave_application_data")
                ->select(DB::raw("SUM(no_of_days) as taken_leaves"))
                ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->where([['leave_application.emp_id', '=', $emp->emp_id], ['leave_application.status', '=', '1'],
                    ['leave_application.approval_status', '=', '2']])
                ->first();

            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $emp_data = Employee::where([['emp_id', '=', Auth::user()->emp_id]])->orderBy('id')->first();
            CommonHelper::reconnectMasterDatabase();
            $getCurrentLeavePolicyYear = date('Y',strtotime($leaves_policy[0]->policy_date_from));
            $date = strtotime($getCurrentLeavePolicyYear.' -1 year');
            $getPreviousLeavePolicyYear = date('Y', $date);
            $getPreviousLeavePolicy = LeavesPolicy::select('id')->where('policy_date_from', 'like', $getPreviousLeavePolicyYear.'%');
            $getPreviousUsedAnnualLeavesBalance = 0;
            $getPreviousUsedCasualLeavesBalance = 0;
            if($getPreviousLeavePolicy->count() > 0 ):
                // print_r($getPreviousLeavePolicyId->first()->id);
                $getPreviousLeavePolicyId=$getPreviousLeavePolicy->first();

                $getPreviousAnnualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',1],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
                $getPreviousCasualLeaves = LeavesData::select('no_of_leaves')->where([['leave_type_id','=',3],['leaves_policy_id','=',$getPreviousLeavePolicyId->id]])->value('no_of_leaves');
                $getPreviousUsedAnnualLeaves = DB::table("leave_application_data")
                    ->select(DB::raw("SUM(no_of_days) as no_of_days"))
                    ->where([['emp_id','=',Input::get('emp_id')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','1']])
                    ->first();
                $getPreviousUsedCasualLeaves = DB::table("leave_application_data")
                    ->select(DB::raw("SUM(no_of_days) as no_of_days"))
                    ->where([['emp_id','=',Input::get('emp_id')],['leave_policy_id','=',$getPreviousLeavePolicyId->id],['leave_type','=','3']])
                    ->first();

                $getPreviousUsedAnnualLeavesBalance =  $getPreviousAnnualLeaves-$getPreviousUsedAnnualLeaves->no_of_days;
                $getPreviousUsedCasualLeavesBalance =$getPreviousCasualLeaves-$getPreviousUsedCasualLeaves->no_of_days;

            endif;
            $WithoutLeavePolicy = [];
            $WithoutLeavePolicy[] = 'have';
            return view('Dashboard.ajax_pages.filterTeamVise',compact('EmployeeOfTheMonth','upcoming_birthdays_detail','empWorkAnvs','getPreviousUsedCasualLeavesBalance','getPreviousUsedAnnualLeavesBalance','attendance_machine_id', 'leaves_policy_validatity', 'leaves_policy', 'emp_data', 'total_leaves', 'taken_leaves','WithoutLeavePolicy','DegreeType'));
        }
        else{
            $WithoutLeavePolicy = [];
            $WithoutLeavePolicy[] = 'Select Leave Policy';
            $leaves_policy_validatity = '0';
            $leaves_policy  = '0';
            return view('Dashboard.ajax_pages.filterTeamVise',compact('EmployeeOfTheMonth','upcoming_birthdays_detail','empWorkAnvs','WithoutLeavePolicy','DegreeType'));
        }
        return view('Dashboard.ajax_pages.filterTeamVise',compact('emp_id','m','EmployeeOfTheMonth','upcoming_birthdays_detail','empWorkAnvs','WithoutLeavePolicy','DegreeType'));
    }
}
