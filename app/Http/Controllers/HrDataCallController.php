<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Designation;
use App\Models\Diseases;
use App\Models\EmployeeGsspDocuments;
use App\Models\EmployeeMedical;
use App\Models\HrWarningLetter;
use Illuminate\Http\Request;
use Auth;
use DB;
use Config;
use Input;
use Hash;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Payroll;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\JobType;
use App\Models\SubDepartment;
use App\Models\MaritalStatus;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\CarPolicy;
use App\Models\Bonus;
use App\Models\LoanRequest;
use App\Models\Tax;
use App\Models\Eobi;
use App\User;
use App\Models\RequestHiring;
use App\Models\Qualification;
use App\Models\ShiftType;
use App\Models\Attendance;
use App\Models\WorkingHoursPolicy;
use App\Models\Holidays;
use App\Models\EmployeeDeposit;
use App\Models\LeaveApplicationData;
use App\Models\EmployeeExit;
use App\Models\Locations;
use App\Models\EmployeeCategory;
use App\Models\EmployeeCardRequest;
use App\Models\DegreeType;
use App\Models\Regions;
use App\Models\Grades;
use App\Models\EmployeeFamilyData;
use App\Models\EmployeeBankData;
use App\Models\EmployeeEducationalData;
use App\Models\EmployeeLanguageProficiency;
use App\Models\EmployeeHealthData;
use App\Models\EmployeeActivityData;
use App\Models\EmployeeWorkExperience;
use App\Models\EmployeeReferenceData;
use App\Models\EmployeeKinsData;
use App\Models\EmployeeRelativesData;
use App\Models\EmployeeOtherDetails;
use App\Models\EmployeePromotion;
use App\Models\EmployeeDocuments;
use App\Models\EmployeeTransfer;
use App\Models\EmployeeFuelData;
use App\Models\HrTerminationFormat1Letter;
use App\Models\HrTerminationFormat2Letter;
use App\Models\HrContractConclusionLetter;
use App\Models\HrMfmSouthWithoutIncrementLetter;
use App\Models\HrMfmSouthIncrementLetter;
use App\Models\EmployeeHrAudit;
use App\Models\EmployeeEquipments;
use App\Models\Equipments;
use App\Models\LeaveApplication;
use App\Models\LetterFiles;
use App\Models\EmployeeMedicalDocuments;
use App\Models\Trainings;
use App\Models\FinalSettlement;
use App\Models\HrTransferLetter;
use App\Models\Gratuity;
use App\Models\AdvanceSalary;
use App\Models\TrainingCertificate;
use App\Models\TransferLetter;
use App\Models\PromotionLetter;
use App\Models\TransferEmployeeProject;
use App\Models\projectTransferLetter;
use App\Models\EmployeeProjects;
use App\Models\Policies;
use App\Models\Payslip;

use DatePeriod;
use DateTime;
use DateInterval;
Use DATE_SUB;

class HrDataCallController extends Controller
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


    public function filterEmployeeList(){
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $m = $_GET['m'];

        $selectEmployeeGradingStatus = $_GET['selectEmployeeGradingStatus'];
        $selectSubDepartment = $_GET['selectSubDepartment'];
        $selectSubDepartmentId = $_GET['selectSubDepartmentId'];

        CommonHelper::companyDatabaseConnection($m);
        if(empty($selectEmployeeGradingStatus) && empty($selectSubDepartmentId)){
            $employeeList = Employee::get();
        }else if(empty($selectEmployeeGradingStatus) && !empty($selectSubDepartmentId)){
            $employeeList = Employee::whereBetween('date',[$fromDate,$toDate])->whereIn('status', array(1, 2))->where('emp_sub_department_id','=',$selectSubDepartmentId)->get();
        }else if(!empty($selectEmployeeGradingStatus) && !empty($selectSubDepartmentId)){
            $employeeList = Employee::whereBetween('date',[$fromDate,$toDate])->whereIn('status', array(1, 2))->where('emp_sub_department_id','=',$selectSubDepartmentId)->where('grading_system','=',$selectEmployeeGradingStatus)->get();
        }else if(!empty($selectEmployeeGradingStatus) && empty($selectSubDepartmentId)){
            $employeeList = Employee::whereBetween('date',[$fromDate,$toDate])->whereIn('status', array(1, 2))->where('grading_system','=',$selectEmployeeGradingStatus)->get();
        }
        /*else if($selectVoucherStatus == '2' && !empty($selectSubDepartmentId)){
            $employeeDetail = Employee::whereBetween('date',[$fromDate,$toDate])->where('status','=','1')->where('emp_sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '3' && !empty($selectSubDepartmentId)){
            $employeeDetail = Employee::whereBetween('date',[$fromDate,$toDate])->where('status','=','2')->where('emp_sub_department_id','=',$selectSubDepartmentId)->get();
        }else if($selectVoucherStatus == '1' && empty($selectSubDepartmentId)){
            $employeeDetail = Employee::whereBetween('date',[$fromDate,$toDate])->where('status','=','1')->get();
        }else if($selectVoucherStatus == '2' && empty($selectSubDepartmentId)){
            $employeeDetail = Employee::whereBetween('date',[$fromDate,$toDate])->where('status','=','1')->get();
        }else if($selectVoucherStatus == '3' && empty($selectSubDepartmentId)){
            $employeeDetail = Employee::whereBetween('date',[$fromDate,$toDate])->where('status','=','2')->get();
        }*/
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.filterEmployeeList',compact('employeeList'));
    }
public  function  abced(){
        return "sdfs";
}
    public function viewDepartmentList(){
        Config::set(['database.connections.tenant.database' => Auth::user()->dbName]);
        Config::set(['database.connections.tenant.username' => 'root']);
        Config::set('database.default', 'tenant');
        DB::reconnect('tenant');
        $departments = new Department;
        $departments = $departments::where('status', '=', '1')->get();
        $counter = 1;
        foreach($departments as $row){
            ?>
            <tr>
                <td class="text-center"><?php echo $counter++;?></td>
                <td><?php echo $row['department_name'];?></td>
                <td></td>
            </tr>
            <?php
        }
    }

    public function viewAttendanceReport(){
        $day_off_array = [];
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $explodeMonthYear = explode("-",Input::get('month_year'));
        $employee_id = Input::get('employee_id');



        $employee_project_id = Input::get('employee_project_id');

        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $all_emp_id = HrHelper::getAllEmpId($department_id,$sub_department_id,Input::get('m'));
        $dynamic_emp_id = [];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($employee_id == 'all'):
            $attendance = Attendance::whereBetween('attendance_date',[$from_date,$to_date])->whereIn('emp_id',$all_emp_id)->orderBy('attendance_date')->get();
            $dynamic_emp_id[] = $all_emp_id;
        else:
            //$attendance = Attendance::where([['acc_no','=',$employee_id],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->orderBy('attendance_date')->get();
            $attendance = Attendance::whereBetween('attendance_date',[$from_date,$to_date])->where([['emp_id','=',$employee_id]])->orderBy('attendance_date')->get();
            $dynamic_emp_id[] = $employee_id;

            $day_off_emp =Employee::select('day_off')->where([['emp_id','=',$employee_id]])->value('day_off');
            $dayoff = explode("=>",$day_off_emp);
            foreach($dayoff as $value){
                $day_off_array[] = $value;
            }

            $total_days_off = Attendance::select('attendance_date')->whereBetween('attendance_date',[$from_date,$to_date])->whereIn('day',$day_off_array)->where('emp_id','=',$employee_id);

            if($total_days_off->count() > 0):

                foreach($total_days_off->get()->toArray() as $offDates):
                    $totalOffDates[] = $offDates['attendance_date'];
                endforeach;

            else:
                $totalOffDates =array();
            endif;

            $monthly_holidays2 = array();
            $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$from_date,$to_date])->where([['status','=',1]]);
            if($get_holidays->count() > 0):
                foreach($get_holidays->get() as $value2):

                    $monthly_holidays2[]=$value2['holiday_date'];
                endforeach;

            else:
                $monthly_holidays2 =array();
            endif;

            $monthly_holidays2 = array_merge($monthly_holidays2,$totalOffDates);


            //$total_present = Attendance::whereBetween('attendance_date',[$from_date,$to_date])->where([['acc_no','=',$employee_id],['clock_in','!=','']])
            //->whereNotIn('attendance_date', $monthly_holidays2)
            //  ->count();

            $total_present = Attendance::select('attendance_date')->whereBetween('attendance_date', [$from_date, $to_date])
                ->where(function ($q) use ($employee_id) {
                    $q->where([['emp_id','=',$employee_id],['clock_in','!=','']])->orWhere([['emp_id','=',$employee_id],['clock_out','!=','']]);
                })
                ->whereNotIn('attendance_date', $monthly_holidays2)
                ->count();



            // Total Absent Count

            $total_absent = Attendance::whereBetween('attendance_date',[$from_date,$to_date])->where([['emp_id','=',$employee_id],['clock_in','=',''],['clock_out','=','']])
                ->whereNotIn('attendance_date', $monthly_holidays2)
                ->count();


        endif;
        $employee = Employee::select('emp_name','emp_sub_department_id','emp_id','working_hours_policy_id')->where([['emp_id','=',$employee_id]])->first();
        CommonHelper::reconnectMasterDatabase();

        $period = new DatePeriod(new DateTime($from_date), new DateInterval('P1D'), new DateTime($to_date. '+1 day'));
        $dates = array();
        foreach ($period as $date) {
            $dates[] = $date->format("Y-m-d");
        }
        return view('Hr.AjaxPages.viewAttendanceReport',compact('attendance','employee','from_date','to_date','monthly_holidays2','total_present','total_absent','employee_id','dynamic_emp_id'));
    }


    public function viewAllowanceListData()
    {
     
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $status=[1];
        $emp_status = Input::get('emp_status');
        
        if($emp_status=="all"){
            $status=[1,3,4];
        }elseif($emp_status=="active"){
            $status=[1];
        }elseif($emp_status=="exit"){
            $status=[3];
        }
        $allowance = DB::table('employee')
            ->join('allowance', 'employee.emp_id', '=', 'allowance.emp_id')
            ->select('allowance.*','employee.employee_project_id')
            ->where([['allowance.status', '=', 1]])
            ->whereIn('employee.status', $status)
            ->orderBy('allowance.id')
            ->get();

        CommonHelper::reconnectMasterDatabase();
		$Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewAllowanceListData',compact('allowance','Department','SubDepartment'));
    }


     public function viewAttendanceProgress()
    {

        $month_year   = explode("-",Input::get('month_year'));

        $employee_id = Input::get('employee_id');
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $from_date = Input::get('from_date');
        $to_date = Input::get('to_date');
        $status=1;
        $emp_status = Input::get('emp_status');
        
        $emp_status == "exit" ? $status=3 : $status=1;
            
        $month_year = explode('-',$to_date);

        $year = $month_year[0];
        $month = $month_year[1];
        $dynamic_emp_id = [];
        $all_emp_id = HrHelper::getAllEmpId($department_id,$sub_department_id,Input::get('m'));

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if(Input::get('show_all') == 'show_all'):
            $attendance = DB::table('attendance')
                ->join('employee', 'employee.emp_id','=', 'attendance.emp_id')
                ->select('employee.emp_name', 'employee.emp_id','attendance.month','attendance.year','attendance.attendance_date')
                ->whereBetween('attendance_date',[$from_date,$to_date])
                ->Where('employee.status',$status)
                ->groupBy('attendance.emp_id');

        elseif($employee_id == 'all'):
            $attendance = DB::table('attendance')
                ->join('employee', 'employee.emp_id','=', 'attendance.emp_id')
                ->select('employee.emp_name', 'employee.emp_id','attendance.month','attendance.year','attendance.attendance_date')
                ->whereIn('attendance.emp_id',$all_emp_id)
                ->whereBetween('attendance_date',[$from_date,$to_date])
                ->Where('employee.status',$status)
                ->groupBy('attendance.emp_id');
        else:
            $attendance = DB::table('attendance')
                ->join('employee', 'employee.emp_id', '=', 'attendance.emp_id')
                ->select('employee.emp_name', 'employee.emp_id','attendance.month','attendance.year','attendance.attendance_date')
                ->where('attendance.emp_id','=',$employee_id)
                ->whereBetween('attendance_date',[$from_date,$to_date])
                ->groupBy('attendance.emp_id');

        endif;

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewAttendanceProgress',compact('attendance','from_date','to_date','year','month'));

    }
    public function viewComparisonReport(){

        $month_from = Input::get('month_from');
        $month_to = Input::get('month_to');

        $explodeMonthYearFrom = explode('-',$month_from);
        $explodeMonthYearTo = explode('-',$month_to);

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeesFrom= Employee::whereYear('emp_joining_date', '=', $explodeMonthYearFrom[0])
            ->whereMonth('emp_joining_date', '=', $explodeMonthYearFrom[1])
            ->get();
        $employeesTo= Employee::whereYear('emp_joining_date', '=', $explodeMonthYearTo[0])
            ->whereMonth('emp_joining_date', '=', $explodeMonthYearTo[1])
            ->get();

        $allowance_from=Allowance::where([["month","=",$explodeMonthYearFrom[1]],['year','=',$explodeMonthYearFrom[0]],["status","=",'1']])->get();
        $allowance_to=Allowance::where([["month","=",$explodeMonthYearTo[1]],['year','=',$explodeMonthYearTo[0]],["status","=",'1']])->get();

        $deduction_from=Deduction::where([["month","=",$explodeMonthYearFrom[1]],['year','=',$explodeMonthYearFrom[0]],["status","=",'1']])->get();
        $deduction_to=Deduction::where([["month","=",$explodeMonthYearTo[1]],['year','=',$explodeMonthYearTo[0]],["status","=",'1']])->get();

        $loan_from=LoanRequest::where([["month","=",$explodeMonthYearFrom[1]],['year','=',$explodeMonthYearFrom[0]],["status","=",'1'],['approval_status','=','2'],['loan_status','=','0']])->get();
        $loan_to=LoanRequest::where([["month","=",$explodeMonthYearTo[1]],['year','=',$explodeMonthYearTo[0]],["status","=",'1'],['approval_status','=','2'],['loan_status','=','0']])->get();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewComparisonReport',compact('employeesFrom','employeesTo','month_from','month_to','allowance_from','allowance_to','deduction_from','deduction_to','loan_from','loan_to','explodeMonthYearFrom','explodeMonthYearTo'));
    }

    public function viewConcileReport(){

        $month_from = Input::get('month_from');
        $month_to = Input::get('month_to');

        $department_id=Input::get('department_id');
        $sub_department_id=Input::get('sub_department_id');
        $show_all=Input::get('show_all');

        if($show_all=="0"){
            $all_emp_id = HrHelper::getAllEmpId($department_id,$sub_department_id,Input::get('m'));

        }
        else{
            $all_emp_id[]='';
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $all_emp_id_array = DB::table('employee')->select('emp_id')->where([['status',1]])->get()->toArray();
            foreach ($all_emp_id_array as $e){
                $all_emp_id[]=$e->emp_id;
            }

            CommonHelper::reconnectMasterDatabase();
        }



        $explodeMonthYearFrom = explode('-',$month_from);
        $explodeMonthYearTo = explode('-',$month_to);

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $payslipFrom = Payslip::where([["month","=",$explodeMonthYearFrom[1]],["year","=",$explodeMonthYearFrom[0]]])
            ->whereIn('emp_id',$all_emp_id)->get();
        $payslipTo = Payslip::where([["month","=",$explodeMonthYearTo[1]],["year","=",$explodeMonthYearTo[0]]])
            ->whereIn('emp_id',$all_emp_id)->get();

        $employeesFrom= Employee::whereYear('emp_joining_date', '=', $explodeMonthYearFrom[0])
            ->whereMonth('emp_joining_date', '=', $explodeMonthYearFrom[1])
            ->where('status','=','1')
            ->whereIn('emp_id',$all_emp_id)
            ->get();
        $employeesTo= Employee::whereYear('emp_joining_date', '=', $explodeMonthYearTo[0])
            ->whereMonth('emp_joining_date', '=', $explodeMonthYearTo[1])
            ->where('status','=','1')
            ->whereIn('emp_id',$all_emp_id)
            ->get();
        $employeesExitTo=EmployeeExit::whereYear('last_working_date', '=', $explodeMonthYearTo[0])
            ->whereMonth('last_working_date', '=', $explodeMonthYearTo[1])
            ->where('status','=','1')
            ->whereIn('emp_id',$all_emp_id)
            ->get();
        $employeesExitFrom=EmployeeExit::whereYear('last_working_date', '=', $explodeMonthYearFrom[0])
            ->whereMonth('last_working_date', '=', $explodeMonthYearFrom[1])
            ->where('status','=','1')
            ->whereIn('emp_id',$all_emp_id)
            ->get();
        $allowance_from=Allowance::where([["month","=",$explodeMonthYearFrom[1]],['year','=',$explodeMonthYearFrom[0]],["status","=",'1']])->whereIn('emp_id',$all_emp_id)->get();
        $allowance_to=Allowance::where([["month","=",$explodeMonthYearTo[1]],['year','=',$explodeMonthYearTo[0]],["status","=",'1']])->whereIn('emp_id',$all_emp_id)->get();

        $deduction_from=Deduction::where([["month","=",$explodeMonthYearFrom[1]],['year','=',$explodeMonthYearFrom[0]],["status","=",'1']])->whereIn('emp_id',$all_emp_id)->get();
        $deduction_to=Deduction::where([["month","=",$explodeMonthYearTo[1]],['year','=',$explodeMonthYearTo[0]],["status","=",'1']])->whereIn('emp_id',$all_emp_id)->get();

        $loan_from=LoanRequest::where([["month","=",$explodeMonthYearFrom[1]],['year','=',$explodeMonthYearFrom[0]],["status","=",'1'],['approval_status','=','2'],['loan_status','=','0']])->whereIn('emp_id',$all_emp_id)->get();
        $loan_to=LoanRequest::where([["month","=",$explodeMonthYearTo[1]],['year','=',$explodeMonthYearTo[0]],["status","=",'1'],['approval_status','=','2'],['loan_status','=','0']])->whereIn('emp_id',$all_emp_id)->get();

        $loan_paid_from=Payslip::where([["month","=",$explodeMonthYearFrom[1]],['year','=',$explodeMonthYearFrom[0]],["status","=",'1'],['loan_amount_paid','!=',0]])->whereIn('emp_id',$all_emp_id)->get();
        $loan_paid_to=Payslip::where([["month","=",$explodeMonthYearTo[1]],['year','=',$explodeMonthYearTo[0]],["status","=",'1'],['loan_amount_paid','!=',0]])->whereIn('emp_id',$all_emp_id)->get();

        $increment_from=EmployeePromotion::whereYear('promotion_date', '=', $explodeMonthYearFrom[0])
            ->whereMonth('promotion_date', '=', $explodeMonthYearFrom[1])
            ->where([['status','=','1'],['approval_status','=','1']])
            ->whereIn('emp_id',$all_emp_id)
            ->get();
        $increment_to=EmployeePromotion::whereYear('promotion_date', '=', $explodeMonthYearTo[0])
            ->whereMonth('promotion_date', '=', $explodeMonthYearTo[1])
            ->where([['status','=','1'],['approval_status','=','1']])
            ->whereIn('emp_id',$all_emp_id)
            ->get();


        $employees = Employee::select("designation_id", "emp_cnic", "emp_id", "emp_salary", "eobi_id", "tax_id", "emp_name", "emp_date_of_birth","employee_project_id","emp_father_name","emp_department_id","emp_sub_department_id")
            ->whereIn('emp_id',$all_emp_id)
            ->where('status','=','1')
            ->orderBy('emp_id')
            ->get()->toArray();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewConcileReport',compact('increment_from','increment_to','employeesExitFrom','employeesExitTo','loan_paid_to','loan_paid_from','loan_to','loan_from','deduction_to','deduction_from','allowance_to','allowance_from','employeesTo','employeesFrom','payslipFrom','payslipTo','explodeMonthYearFrom','explodeMonthYearTo','employees'));
    }

    public function viewPayrollReport()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');

        $getPayslipMonth = Input::get('payslip_month');
        $explodeMonthYear = explode('-',$getPayslipMonth);
        $monthYearDay=$explodeMonthYear[0].'-'.$explodeMonthYear[1].'-01';
        $all_emp_id = HrHelper::getAllEmpId($department_id,$sub_department_id,Input::get('m'));

        $status=1;
        $emp_status = Input::get('emp_status');
        
        $emp_status == "exit" ? $status=3 : $status=1;

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        
        if(Input::get('show_all') == 'show_all'){
            $employees = Employee::select("designation_id", "emp_cnic", "emp_id", "emp_salary", "eobi_id", "tax_id", "emp_name", "emp_date_of_birth","employee_project_id","emp_father_name","emp_department_id","emp_sub_department_id")
                ->whereRaw("status = '$status' AND DATE_FORMAT(emp_joining_date,'%Y-%m') <= '$monthYearDay'")
                ->orderBy('emp_id')
                ->get()->toArray();
        }
        elseif(Input::get('emp_id') == 'all'){
            $employees = Employee::select("designation_id", "emp_cnic", "emp_id", "emp_salary", "eobi_id", "tax_id", "emp_name", "emp_date_of_birth","employee_project_id","emp_father_name","emp_department_id","emp_sub_department_id")
                ->whereIn('emp_id',$all_emp_id)
                ->whereRaw("status = '$status' AND DATE_FORMAT(emp_joining_date,'%Y-%m') <= '$monthYearDay'")
                ->orderBy('emp_id')
                ->get()->toArray();
        }
        else{
            $employees = Employee::select("designation_id", "emp_cnic", "emp_id", "emp_salary", "eobi_id", "tax_id", "emp_name", "emp_date_of_birth","employee_project_id","emp_father_name","emp_department_id","emp_sub_department_id")
                ->where('emp_id',Input::get('emp_id'))
                ->whereRaw("DATE_FORMAT(emp_joining_date,'%Y-%m') <= '$monthYearDay'")
                ->orderBy('emp_id')
                ->get()->toArray();
        }
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewPayrollReport',compact('employees','explodeMonthYear','departments'));
    }


    public function companyWisePayrollReport()
    {
        if(Auth::user()->acc_type == 'user') {die('Nice Trick @xx ');}
        $month_year = Input::get('month_year');
        $explodeMonthYear = explode('-',$month_year);
        if(Input::get('company_id') == 'All'):
            $companiesList = DB::Table('company')->select('id','name')->where([['status','=',1]])->orderBy('order_by_no','asc')->get()->toArray();
        else:
            $companiesList = DB::Table('company')->select('id','name')->where([['status','=',1],['id','=',Input::get('company_id')]])->get()->toArray();
        endif;

        return view('Hr.AjaxPages.companyWisePayrollReport',compact('companiesList','explodeMonthYear', 'month_year'));
    }




    public function viewBankReportDetail()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');
        $bank_id = Input::get('bank_id');

        $month_year = Input::get('month_year');
        $explodeMonthYear = explode('-',$month_year);
        $show_all = Input::get('show_all');

        return view('Hr.AjaxPages.viewBankReportDetail',compact('explodeMonthYear', 'show_all','bank_id','month_year','department_id','sub_department_id'));
    }
    public function viewBonusBankReportDetail()
    {
        $month_year = Input::get('month_year');
        $explodeMonthYear = explode('-',$month_year);

        return view('Hr.AjaxPages.viewBonusBankReportDetail',compact('explodeMonthYear', 'month_year'));
    }

    public function viewEmployeeEmergencyForm(){

        $getEmployee = Input::get('emp_id');
        $sub_department = Input::get('sub_department');
        $department_id = Input::get('department_id');
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $all_emp_id = HrHelper::getAllEmpId($department_id,$sub_department,Input::get('m'));

        CommonHelper::companyDatabaseConnection(Input::get('m'));


        if(Input::get('show_all') == 'show_all'){

            $employees= DB::select(DB::raw("SELECT * FROM employee WHERE status = 1 ORDER by emp_id ASC") );


        }
        elseif($getEmployee == 'all' && $sub_department == '0'){


            $employees = Employee::select('*')
                ->whereIn('emp_id',$all_emp_id)
                ->orderBy('emp_id')
                ->get();
        }
        elseif($getEmployee == 'all' &&  $sub_department != '0'){

            $employees = Employee::select('*')
                ->whereIn('emp_id',$all_emp_id)
                ->orderBy('emp_id')
                ->get();

        }
        else{

            $employees = Employee::select('*')
                ->where([['emp_id','=',$getEmployee],['status', '=', '1']])->orderBy('emp_id')->get();
        }

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeEmergencyForm',compact('employees','subdepartment','departments',
            'department_id','getEmployee','designation'));

    }

    public function viewEmployeePayrollForm()
    {

        $getEmployee = Input::get('emp_id');
        $getPayslipMonth = Input::get('payslip_month');
        $explodeMonthYear = explode('-',$getPayslipMonth);
        $monthYearDay=$explodeMonthYear[0].'-'.$explodeMonthYear[1].'-01';
        $startDate = Input::get('payslip_month').'-1';
        $endDate = date("Y-m-t", strtotime($startDate));
        $sub_department = Input::get('sub_department');
        $department_id = Input::get('department_id');
        $tax_slabs = Input::get('slabs');
        
        $status=1;
        $emp_status = Input::get('emp_status');
        
        $emp_status == "exit" ? $status=3 : $status=1;    

        $all_emp_id = HrHelper::getAllEmpId($department_id,$sub_department,Input::get('m'));

        CommonHelper::companyDatabaseConnection(Input::get('m'));


        if(Input::get('show_all') == 'show_all'){

            $employees= DB::select(DB::raw("SELECT * FROM employee WHERE status = '$status' AND DATE_FORMAT(emp_joining_date,'%Y-%m') <='$monthYearDay' ORDER by emp_id ASC") );
//            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
//			'emp_joining_date','tax_id','provident_fund_id','eobi_id','bank_account','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
//            ->whereMonth('emp_joining_date', '<', $explodeMonthYear[1])
//                ->whereYear('emp_joining_date', '<=', $explodeMonthYear[0])
//			->where([['status', '=', '1']])->orderBy('emp_id')->get();

        }
        elseif($getEmployee == 'all' && $sub_department == '0'){


            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'emp_joining_date','bank_account','tax_id','provident_fund_id','eobi_id','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->whereIn('emp_id',$all_emp_id)
                ->whereRaw("status = '$status' AND DATE_FORMAT(emp_joining_date,'%Y-%m') <= '$monthYearDay'")
                ->orderBy('emp_id')
                ->get();
        }
        elseif($getEmployee == 'all' &&  $sub_department != '0'){

            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'emp_joining_date','bank_account','tax_id','provident_fund_id','eobi_id','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->whereIn('emp_id',$all_emp_id)
                ->whereRaw("status = '$status' AND DATE_FORMAT(emp_joining_date,'%Y-%m') <= '$monthYearDay'")
                ->orderBy('emp_id')
                ->get();

        }
        else{

            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'emp_joining_date','bank_account','tax_id','provident_fund_id','eobi_id','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->where([['emp_id','=',$getEmployee]])->orderBy('emp_id')->get();
        }

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeePayrollForm',compact('employees','explodeMonthYear','subdepartment','departments',
            'department_id','getPayslipMonth','getEmployee','startDate','endDate'.'explodeMonthYear','tax_slabs'));
    }

    public function viewEmployeePayrollList(){

        if(Auth::user()->acc_type == 'user') {die('Nice Trick @xx ');}
        $department_id = Input::get('department_id');
        $getPayslipMonth = Input::get('payslip_month');
        $explodeMonthYear = explode('-',$getPayslipMonth);
        $getEmployee = Input::get('emp_id');
        $explodePaysilpMonth = explode('-',$getPayslipMonth);
        $sub_department_id = Input::get('sub_department_id');

        $all_emp_id = HrHelper::getAllEmpId($department_id,$sub_department_id,Input::get('m'));

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if(Input::get('show_all') == 'show_all'){

            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'emp_joining_date','tax_id','provident_fund_id','eobi_id','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->where([['status', '=', '1']])->orderBy('emp_id')->get();
        }
        elseif($getEmployee == 'all' && $sub_department_id == '0'){

            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'emp_joining_date','tax_id','provident_fund_id','eobi_id','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->whereIn('emp_id',$all_emp_id)
                ->where([['status', '=', '1']])
                ->orderBy('emp_id')
                ->get();
        }
        elseif($getEmployee == 'all' &&  $sub_department_id != '0'){

            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'emp_joining_date','tax_id','provident_fund_id','eobi_id','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->whereIn('emp_id',$all_emp_id)
                ->where([['status', '=', '1']])
                ->orderBy('emp_id')
                ->get();

        }
        else{

            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'emp_joining_date','tax_id','provident_fund_id','eobi_id','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->where([['emp_id','=',$getEmployee],['status', '=', '1']])->orderBy('emp_id')->get();
        }
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeePayrollList',compact('employees','explodeMonthYear', 'operation_rights2','subdepartment','departments','department_id','getPayslipMonth','getEmployee','explodePaysilpMonth'));
    }

    public function viewMySalarySheetDetail(Request $request)

    {


        if (Hash::check(Input::get('passwordSecret'), Auth::user()->password)) {

            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $acc_emp_id = Auth()->user()->emp_id;

            $payslip  = DB::table('payslip')
                ->join('employee', 'employee.emp_id', '=', 'payslip.emp_id')
                ->select('payslip.*', 'employee.emp_name', 'employee.emp_sub_department_id',
                    'employee.emp_department_id')
                ->where('payslip.emp_id',  $acc_emp_id );

            CommonHelper::reconnectMasterDatabase();


            return view('Hr.AjaxPages.viewMySalarySheetDetail',compact('payslip'));


        }
        else{
            echo 'err';

        }

    }

    public function viewEmployeePayslips()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_year  = explode("-",Input::get('month_year'));
        $emp_id = Input::get('emp_id');
        if($emp_id == 'All'):
            $payslipData = DB::table('payslip')->where([['month','=',$month_year[1]],['year','=',$month_year[0]],['status','=',1]]);
        else:
            $payslipData = DB::table('payslip')->where([['emp_id', '=', $emp_id],['month','=',$month_year[1]],['year','=',$month_year[0]],['status','=',1]]);
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeePayslips',compact('payslipData','month_year'));
    }


    public function viewUserAccountDetail(){
        $currentDate = date('Y-m-d');
        $id = $_GET['id'];
        $CompanyId 	= $_GET['m'];

        CommonHelper::reconnectMasterDatabase($CompanyId);

        $user_account_detail = User::where([['id','=',$id]])->first();

        return view('Hr.AjaxPages.viewUserAccountDetail',compact('user_account_detail'));
    }
    public function viewEmployeeDetail(){


        $currentDate = date('Y-m-d');
        $id = $_GET['id'];
        $CompanyId 	= $_GET['m'];
        $location_id = '';

        CommonHelper::companyDatabaseConnection($CompanyId);
        $employee_detail = Employee::where([['id','=',$id]])->first();
        $employee_family_detail = EmployeeFamilyData::where([['emp_id','=',$employee_detail->emp_id]]);
        $employee_bank_detail = EmployeeBankData::where([['emp_id','=',$employee_detail->emp_id]]);
        $employee_educational_detail = EmployeeEducationalData::where([['emp_id','=',$employee_detail->emp_id]]);
        $employee_language_proficiency = EmployeeLanguageProficiency::where([['emp_id','=',$employee_detail->emp_id]]);
        $employee_health_data = EmployeeHealthData::where([['emp_id','=',$employee_detail->emp_id]]);
        $employee_activity_data = EmployeeActivityData::where([['emp_id','=',$employee_detail->emp_id]]);
        $employee_work_experience = EmployeeWorkExperience::where([['emp_id','=',$employee_detail->emp_id]]);
        $employee_reference_data = EmployeeReferenceData::where([['emp_id','=',$employee_detail->emp_id]]);
        $employee_kins_data = EmployeeKinsData::where([['emp_id','=',$employee_detail->emp_id]]);
        $employee_relatives_data = EmployeeRelativesData::where([['emp_id','=',$employee_detail->emp_id]]);
        $employee_other_details = EmployeeOtherDetails::where([['emp_id','=',$employee_detail->emp_id]]);


        if($employee_detail->can_login == 'yes'):
            $login_credentials = DB::Table('users')->select('acc_type')->where([['company_id', '=', Input::get('m')],['emp_id', '=', $employee_detail->id]]);
        endif;

        CommonHelper::reconnectMasterDatabase();


        $subdepartments = new SubDepartment;
        $leaves_policy = LeavesPolicy::where([['status','=','1']])->get();
        $jobtype = JobType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $marital_status = MaritalStatus::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $qualification = Qualification::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $eobi = Eobi::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $tax= Tax::select('id','tax_name')->where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $DegreeType = DegreeType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();

        return view('Hr.AjaxPages.viewEmployeeDetail'
            ,compact('login_credentials','employee_other_details','employee_relatives_data','employee_kins_data','employee_reference_data','employee_work_experience','employee_activity_data','employee_health_data','employee_language_proficiency','employee_educational_detail','employee_bank_detail','employee_family_detail','leaves_policy','employee_detail','DegreeType','tax','eobi','designation','qualification','
            leaves_policy','departments','subdepartments','jobtype','marital_status'));
    }

    public function viewHiringRequestDetail(){

        $array[1] ='<span class="label label-warning">Pending</span>';
        $array[2] ='<span class="label label-success">Approved</span>';
        $array[3] ='<span class="label label-danger">Rejected</span>';
        $array1[1] ="<span class='label label-success'>Active</span>";
        $array1[2] ="<span class='label label-danger'>Deleted</span>";

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hiringRequestDetail = RequestHiring::where([['id','=',Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $data['hiringRequestDetail'] = $hiringRequestDetail;
        $data['status'] 		 = $array1[$hiringRequestDetail->status];
        $data['approval_status'] = $array[$hiringRequestDetail->ApprovalStatus];
        return view('Hr.AjaxPages.viewHiringRequestDetail',$data);

        /*<a href="https://www.facebook.com/sharer/sharer.php?u=http://www.innovative-net.com/&display=popup" target="_blank"> share this facebook </a>*/

    }

    public function viewLeavePolicyDetail()
    {

        //CommonHelper::companyDatabaseConnection(Input::get('m'));
        $leavesPolicy = LeavesPolicy::where([['id','=',Input::get('id')]])->first();
        $leavesData =   LeavesData::where([['leaves_policy_id','=',Input::get('id')]])->get();

        return view('Hr.AjaxPages.viewLeavePolicyDetail',compact('leavesPolicy','leavesData'));

    }


    public function viewCarPolicyCriteria()
    {
        if(Input::get('sub_department_id') == 'all'):

            $allsubDeparments = SubDepartment::select('id','sub_department_name','department_id')->where([['status','=','1'],['company_id','=',Input::get('m')]])->get();
        else:
            $allsubDeparments = SubDepartment::select('id','sub_department_name','department_id')->where([['id','=',Input::get('sub_department_id')],['status','=','1'],['company_id','=',Input::get('m')]])->get();
        endif;

        return view('Hr.AjaxPages.viewCarPolicyCriteria',compact('allsubDeparments'));
    }


    public function  viewCarPolicy()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $carPolicyData = CarPolicy::where([['id','=',Input::get('id')],['status','=','1']])->first();
        return view('Hr.AjaxPages.viewCarPolicy',compact('carPolicyData'));
    }

    public function viewLoanRequestDetail()
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        //echo Input::get('id'); die();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loanRequest   = LoanRequest::where([['id','=',Input::get('id')],['status','=',1]])->first();
        $paid_amount = DB::table("payslip")
            ->select(DB::raw("SUM(loan_amount_paid) as paid_amount"))
            ->where([['emp_id','=', $loanRequest->emp_id],['loan_id' ,'=', $loanRequest->id]])
            ->first();
        $loan_Detail = DB::table('payslip')
            ->select('loan_amount_paid','date','month','year')
            ->where([['loan_id','=',$loanRequest->id],['emp_id','=',$loanRequest->emp_id]])
            ->get();


        $employee = Employee::select('emp_name','emp_sub_department_id')->where([['emp_id','=',$loanRequest->emp_id]])->first();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewLoanRequestDetail',compact('loanRequest','employee','paid_amount', 'operation_rights2','loan_Detail'));
    }


    public function viewLoanReportDetail()
    {


        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loanAmount = LoanRequest::select('loan_amount')
            ->where('id',Input::get('loan_id'))
            ->where('emp_id',Input::get('emp_id'))
            ->value('loan_amount');

        $LoanData = DB::table('loan_request')
            ->join('payslip', 'payslip.loan_id', '=', 'loan_request.id')
            ->join('employee', 'payslip.emp_id', '=', 'employee.emp_id')
            ->select('employee.emp_name','payslip.emp_id','loan_request.loan_amount','loan_request.id','payslip.month', 'payslip.year', 'payslip.loan_amount_paid')
            ->where('payslip.emp_id',Input::get('emp_id'))
            ->where('payslip.loan_id',Input::get('loan_id'))
            ->get()
            ->toArray();


        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewLoanReportDetail',compact('LoanData','loanAmount'));

    }

    public function viewTaxCriteria()
    {
        if(Input::get('sub_department_id') == 'all'):

            $allsubDeparments = SubDepartment::select('id','sub_department_name','department_id')->where([['status','=','1'],['company_id','=',Input::get('m')]])->get();
        else:
            $allsubDeparments = SubDepartment::select('id','sub_department_name','department_id')->where([['id','=',Input::get('sub_department_id')],['status','=','1'],['company_id','=',Input::get('m')]])->get();
        endif;
        return view('Hr.AjaxPages.viewTaxCriteria',compact('allsubDeparments'));

    }

    public function viewTaxesDetail()
    {
        $tax_slabs = DB::table('tax_slabs')->where([['tax_id', '=', Input::get('id')]])->orderBy('salary_range_from','asc')->get();
        return view('Hr.AjaxPages.viewTaxesDetail',compact('tax_slabs'));
    }

    public function viewTax()
    {
        $tax = Tax::where([['id','=',Input::get('id')],['company_id','=',Input::get('m')]])->first();
        return view('Hr.AjaxPages.viewTax',compact('tax'));
    }

    public function viewEmployeesBonus()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $bonus_month=Input::get('bonus_month_year');
        $monthYear=explode('-',Input::get('bonus_month_year'));
        $monthYearDay=$monthYear[0].'-'.$monthYear[1].'-15';

        if(Input::get('emp_id') == 'all'){
            //$all_employees = Employee::select('id','emp_salary','emp_id','emp_name','emp_joining_date','emp_id')->where([['emp_department_id', '=', Input::get('department_id')],['status','=',1]])->get();
            $all_employees = DB::select( DB::raw("SELECT * FROM employee WHERE emp_department_id = '".Input::get('department_id')."' AND  status = 1 AND DATE_FORMAT(emp_joining_date,'%Y-%m') <= DATE_SUB('$monthYearDay', INTERVAL 6 MONTH)") );
        }
        else {
            $all_employees = Employee::select('id', 'emp_id','emp_joining_date', 'emp_name', 'emp_salary','employee_project_id')->where([['emp_id', '=', Input::get('emp_id')], ['status', '=', 1]])->get();
        }


        $get_percent = Bonus::select('percent_of_salary')->where([['id','=',Input::get('bonus_id')]])->first();
        $month_year = explode('-',Input::get('bonus_month_year'));
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeesBonus',compact('all_employees','get_percent','month_year','monthYearDay','bonus_month'));
    }

    public function viewLeaveApplicationDetail()
    {
        $leave_day_type = Input::get('leave_day_type');

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp = Employee::select('id','leaves_policy_id','designation_id')->where('emp_id','=',Auth::user()->emp_id)->first();
        CommonHelper::reconnectMasterDatabase();

        if(Input::get('leave_day_type') == 1):

            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.leave_address','leave_application.emp_id','leave_application.approval_status','leave_application.reason','leave_application_data.no_of_days','leave_application_data.date','leave_application_data.from_date','leave_application_data.to_date')
                ->where([['leave_application_data.leave_application_id','=',Input::get('id')],['leave_application_data.leave_day_type','=',Input::get('leave_day_type')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];


        elseif(Input::get('leave_day_type') == 2):

            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.leave_address','leave_application.emp_id','leave_application.approval_status','leave_application.reason','leave_application_data.first_second_half','leave_application_data.date','leave_application_data.first_second_half_date')
                ->where([['leave_application_data.leave_application_id','=',Input::get('id')],['leave_application_data.leave_day_type','=',Input::get('leave_day_type')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        else:
            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.leave_address','leave_application.emp_id','leave_application.approval_status','leave_application.reason','leave_application_data.short_leave_time_from','leave_application_data.short_leave_time_to','leave_application_data.date','leave_application_data.short_leave_date')
                ->where([['leave_application_data.leave_application_id','=',Input::get('id')],['leave_application_data.leave_day_type','=',Input::get('leave_day_type')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        endif;


        $approval_array[1] = '<span class="label label-warning">Pending</span>';
        $approval_array[2] = '<span class="label label-success">Approved</span>';
        $approval_array[3] = '<span class="label label-danger">Rejected</span>';

        $approval_status = $approval_array[$leave_application_data->approval_status];

        $leaves_policy = DB::table('leaves_policy')
            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*','leaves_data.*')
            ->where([['leaves_policy.id','=',$emp->leaves_policy_id]])
            ->get();

        $total_leaves = DB::table("leaves_data")
            ->select(DB::raw("SUM(no_of_leaves) as total_leaves"))
            ->where([['leaves_policy_id' ,'=', $leaves_policy[0]->leaves_policy_id]])
            ->first();

        $employee_id=Input::get('user_id');
        $taken_leaves = DB::table("leave_application_data")
            ->select(DB::raw("SUM(no_of_days) as taken_leaves"))
            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
            ->where([['leave_application.emp_id', '=', Input::get('user_id')],['leave_application.status', '=', '1'],
                ['leave_application.approval_status', '=', '2']])
            ->orWhere(function ($query) use($employee_id) {
                $query->where('leave_application.emp_id', '=', $employee_id)
                    ->where('leave_application.status', '=', '1')
                    ->where('leave_application.approval_status_lm', '=', '2');
            })
            ->first();

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_data = Employee::select('emp_name', 'emp_sub_department_id', 'designation_id', 'emp_id')->where([['id', '=', $emp->id]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();

        $designation_name = Designation::where([['id','=',$emp->designation_id]])->value('designation_name');
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

        $data['getPreviousUsedAnnualLeavesBalance']=$getPreviousUsedAnnualLeavesBalance;
        $data['getPreviousUsedCasualLeavesBalance']=$getPreviousUsedCasualLeavesBalance;
        $data['total_leaves']=   		$total_leaves;
        $data['taken_leaves']= 		    $taken_leaves;
        $data['designation_name']=		$designation_name;
        $data['leave_day_type']=	    $leave_day_type;
        $data['leave_application_data']=$leave_application_data;
        $data['approval_status'] = 		$approval_status;

        $data['leave_type_name']        = Input::get('leave_type_name');
        $data['leave_day_type_label'] = $leave_day_type_label;
        $data['leaves_policy'] =        $leaves_policy;
        return view('Hr.AjaxPages.viewLeaveApplicationDetail')->with($data);
    }

   public function viewLeaveApplicationRequestDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp = Employee::select('id','emp_id','leaves_policy_id','designation_id')->where([['emp_id', '=',Input::get('user_id')]])->first();
        
        CommonHelper::reconnectMasterDatabase();

        $leave_day_type = Input::get('leave_day_type');

        if(Input::get('leave_day_type') == 1):

            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                //->join('employee', 'leave_application.emp_id', '=', 'employee.acc_no')
                ->select('leave_application.emp_id','leave_application.leave_address','leave_application.approval_status','leave_application.approval_status_lm','leave_application.reason','leave_application.status','leave_application_data.no_of_days','leave_application_data.date','leave_application_data.from_date','leave_application_data.to_date')
                ->where([['leave_application_data.leave_application_id','=',Input::get('id')],['leave_application_data.leave_day_type','=',Input::get('leave_day_type')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];
        elseif(Input::get('leave_day_type') == 2):

            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                //->join('employee', 'leave_application.emp_id', '=', 'employee.acc_no')
                ->select('leave_application.emp_id','leave_application.leave_address','leave_application.approval_status','leave_application.approval_status_lm','leave_application.reason','leave_application.status','leave_application_data.first_second_half','leave_application_data.date','leave_application_data.first_second_half_date')
                ->where([['leave_application_data.leave_application_id','=',Input::get('id')],['leave_application_data.leave_day_type','=',Input::get('leave_day_type')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        else:
            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                //->join('employee', 'leave_application.emp_id', '=', 'employee.acc_no')
                ->select('leave_application.emp_id','leave_application.approval_status','leave_application.approval_status_lm','leave_application.leave_address','leave_application.reason',
                    'leave_application.status','leave_application_data.short_leave_time_from','leave_application_data.short_leave_time_to',
                    'leave_application_data.date','leave_application_data.short_leave_date')
                ->where([['leave_application_data.leave_application_id','=',Input::get('id')],['leave_application_data.leave_day_type','=',Input::get('leave_day_type')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        endif;
        
        $approval_array[1] = '<span class="label label-warning">Pending</span>';
        $approval_array[2] = '<span class="label label-success">Approved</span>';
        $approval_array[3] = '<span class="label label-danger">Rejected</span>';

        $approval_status = $approval_array[$leave_application_data->approval_status];
        $approval_status_lm = $approval_array[$leave_application_data->approval_status_lm];
        CommonHelper::reconnectMasterDatabase();

        $leaves_policy = DB::table('leaves_policy')
            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
            ->join('leaves_data','leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*','leaves_data.*')
            ->where([['leaves_policy.id','=',$emp->leaves_policy_id]])
            ->get();


        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_data = Employee::select('emp_name', 'emp_sub_department_id', 'designation_id', 'emp_id','leaves_policy_id')->where([['id', '=', $emp->id]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();

        $designation_name = Designation::where([['id','=',$emp->designation_id]])->value('designation_name');
        $leave_type_name = Input::get('leave_type_name');

        $data['designation_name']       = $designation_name;
        $data['leave_day_type']         = $leave_day_type;
        $data['leave_application_data'] = $leave_application_data;
        $data['approval_status']        = $approval_status;
        $data['approval_status_lm']      = $approval_status_lm;
        $data['emp_data']               = $emp_data;
        $data['leave_type_name']        = Input::get('leave_type_name');
        $data['leave_day_type_label']   = $leave_day_type_label;
        $data['leaves_policy']          = $leaves_policy;

        return view('Hr.AjaxPages.viewLeaveApplicationRequestDetail',compact('designation_name','leave_day_type','leave_application_data','approval_status','approval_status_lm','emp_data','leave_type_name','leave_day_type_label','leaves_policy'));
    }


    public function filterWorkingHoursPolicList(){
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $selectVoucherStatus = $_GET['selectVoucherStatus'];
        $m = $_GET['m'];


        if($selectVoucherStatus == '0'){
            $workingHoursPolicyDetail = WorkingHoursPolicy::whereBetween('date',[$fromDate,$toDate])->get();
        }if($selectVoucherStatus == '1'){
            $workingHoursPolicyDetail = WorkingHoursPolicy::whereBetween('date',[$fromDate,$toDate])->where('status','=','1')->get();
        }if($selectVoucherStatus == '2'){
            $workingHoursPolicyDetail = WorkingHoursPolicy::whereBetween('date',[$fromDate,$toDate])->where('status','=','2')->get();
        }else {
            $workingHoursPolicyDetail = WorkingHoursPolicy::whereBetween('date',[$fromDate,$toDate])->get();
        }
        return view('Hr.AjaxPages.filterWorkingHoursPolicList',compact('workingHoursPolicyDetail'));
    }

    public function viewLeavesBalances()
    {
        if(Input::get('company_id') == 'All'):

            $companiesList = DB::Table('company')->select('id','name')->get()->toArray();
        else:
            $companiesList = DB::Table('company')->select('id','name')->where([['id','=',Input::get('company_id')]])->get()->toArray();

        endif;

        return view('Hr.AjaxPages.viewLeavesBalances',compact('companiesList'));


    }

    public function filterEmployeeAttendanceList(){
        $fromDateOne = date_create($_GET['fromDate']);
        $toDateOne = date_create($_GET['toDate']);

        $fromDate = date_format($fromDateOne,'n/j/yyyy');
        $toDate = date_format($toDateOne,'n/j/yyyy');

        //return $fromDate .' ---- '. $toDate;

        $m = $_GET['m'];

        $selectEmployee = $_GET['selectEmployee'];
        $selectEmployeeId = $_GET['selectEmployeeId'];
        $attendanceStatus = $_GET['attendanceStatus'];

        CommonHelper::companyDatabaseConnection($m);
        if(empty($selectEmployeeId) && empty($attendanceStatus)){
            $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->get();
        }else if(!empty($selectEmployeeId) && empty($attendanceStatus)){
            $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$selectEmployeeId)->get();
        }else if(empty($selectEmployeeId) && $attendanceStatus == '1'){
            $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('absent','=','')->get();
        }else if(empty($selectEmployeeId) && $attendanceStatus == '2'){
            $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('absent','!=','')->get();
        }else if(empty($selectEmployeeId) && $attendanceStatus == '3'){
            $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('late','!=','')->get();
        }else if(empty($selectEmployeeId) && $attendanceStatus == '4'){
            $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('clock_in','=',NULL)->get();
        }else if(empty($selectEmployeeId) && $attendanceStatus == '5'){
            $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('clock_out','=',NULL)->get();
        }else if(!empty($selectEmployeeId) && $attendanceStatus == '1'){
            $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$selectEmployeeId)->where('absent','=','')->get();
        }else if(!empty($selectEmployeeId) && $attendanceStatus == '2'){
            $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$selectEmployeeId)->where('absent','!=','')->get();
        }else if(!empty($selectEmployeeId) && $attendanceStatus == '3'){
            $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$selectEmployeeId)->where('late','!=','')->get();
        }else if(!empty($selectEmployeeId) && $attendanceStatus == '4'){
            $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$selectEmployeeId)->where('clock_in','=',NULL)->get();
        }else if(!empty($selectEmployeeId) && $attendanceStatus == '5'){
            $employeeAttendanceDetail = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$selectEmployeeId)->where('clock_out','=',NULL)->get();
        }
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.filterEmployeeAttendanceList',compact('employeeAttendanceDetail'));
    }

    public function viewEmployeeLeaveDetail()
    {
        return view('Hr.AjaxPages.viewEmployeeLeaveDetail');
    }

    public  function viewApplicationDateWise()
    {
        $data = Input::get('id');
        $dataFilter = explode(',',$data);

        $emp_id = $dataFilter[0];
        $from_date = $dataFilter[1];
        $to_date =  $dataFilter[2];
        

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attendance2 = DB::table('attendance')->where([['attendance.emp_id','=',$emp_id]])
            ->whereBetween('attendance_date',[$from_date,$to_date])
            ->get();
        
        $totalOffDates[] = '';
        
        $day_off_emp = Employee::select('day_off')->where([['emp_id','=',$emp_id]])->value('day_off');
        $day_off_emp =  explode('=>',$day_off_emp);

        $total_days_off = Attendance::select('attendance_date')
            ->whereBetween('attendance_date',[$from_date,$to_date])
            ->where([['emp_id','=',$emp_id]])
            ->wherein('day',[$day_off_emp[1],$day_off_emp[0]]);



        if($total_days_off->count() > 0):

            foreach($total_days_off->get()->toArray() as $offDates):
                $totalOffDates[] = $offDates['attendance_date'];
            endforeach;

        else:
            $totalOffDates =array();
        endif;

        $monthly_holidays[] = '';
        $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$from_date,$to_date])->where([['status','=',1]]);
        if($get_holidays->count() > 0):
            foreach($get_holidays->get() as $value2):

                $monthly_holidays[]=$value2['holiday_date'];
            endforeach;

        else:
            $monthly_holidays =array();
        endif;
        $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);
        $dates = array();
       
        foreach ($attendance2 as $value) {
            $LikeDate = "'".'%'.$value->year."-".$value->month.'%'."'";
            CommonHelper::reconnectMasterDatabase();
            $leave_application_request_list = DB::select('select leave_application.*,leave_application_data.* from leave_application
                            INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id
                            WHERE leave_application_data.from_date LIKE '.$LikeDate.' AND leave_application_data.emp_id = '.$value->emp_id.' AND leave_application.status = 1 AND (leave_application.approval_status = 2 OR leave_application.approval_status_lm = 2) AND leave_application.view = "yes"
                            OR leave_application_data.first_second_half_date LIKE '.$LikeDate.' AND leave_application_data.emp_id = '.$value->emp_id.'');

            
            
            $leaves_from_dates2 = [];
            if(!empty($leave_application_request_list)):
                foreach($leave_application_request_list as $value3):
                    $leaves_from_dates = $value3->from_date;
                    $leaves_to_dates = $value3->to_date;
                    $leaves_type=$value3->leave_type;
                    $leaves_from_dates2[] = $value3->from_date;

                    $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates. '+1 day'));


                    foreach ($period as $date) {
                        $dates[] = $date->format("Y-m-d");
                    }

                endforeach;

            endif;
        }

        $monthly_holidays = array_merge($monthly_holidays,$dates);

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $absent_dates = Attendance::select("emp_id","attendance_date","clock_in","clock_out")->whereBetween('attendance_date',[$from_date,$to_date])->where([['emp_id','=',$emp_id],['clock_in','=',Null],['clock_out','=',Null]])
            ->whereNotIn('attendance_date', $monthly_holidays)
            ->get()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewApplicationDateWise',compact('leave_application_request_list','absent_dates'));
    }

    public function viewHolidayDate()
    {
        $data = Input::get('id');
        $dataFilter = explode(',',$data);
        $acc_no = $dataFilter[0];
        $month_data = $dataFilter[1];
        $monthDataFilter = explode('-',$month_data);
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $HolidayData = Holidays::where([['status','=',1],['month','=',$monthDataFilter[0]],['year','=',$monthDataFilter[1]]])->get();
        $day_off_emp =Employee::select('day_off')->where([['acc_no','=',$acc_no]])->value('day_off');
        $total_days_off = Attendance::select('attendance_date','day','month','year')->where([['day','=',$day_off_emp],['acc_no','=',$acc_no],
            ['month','=',$monthDataFilter[0]],['year','=',$monthDataFilter[1]]])->get();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHolidayDate',compact('HolidayData','total_days_off'));

    }


    public function viewOverTimeDetail()
    {
        $data = Input::get('id');
        $dataFilter = explode(',',$data);
        $acc_no = $dataFilter[0];
        $month_data = $dataFilter[1];
        $monthDataFilter = explode('-',$month_data);
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $totalOffDates = array();
        $day_off_emp =Employee::select('day_off')->where([['acc_no','=',$acc_no]])->value('day_off');
        $total_days_off = Attendance::select('attendance_date')->where([['day','=',$day_off_emp],['acc_no','=',$acc_no]]);

        if($total_days_off->count() > 0):

            foreach($total_days_off->get()->toArray() as $offDates):
                $totalOffDates[] = $offDates['attendance_date'];
            endforeach;

        else:
            $totalOffDates =array();
        endif;

        $monthly_holidays = array();
        $get_holidays = Holidays::select('holiday_date')->where([['status','=',1],['month','=',$monthDataFilter[0]],['year','=',$monthDataFilter[1]]]);
        if($get_holidays->count() > 0) {
            foreach ($get_holidays->get() as $value2) {

                $monthly_holidays[] = $value2['holiday_date'];
            }
        }
        else{
            $monthly_holidays =array();
        }

        $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);
        $attData = Attendance::where([['month','=',$monthDataFilter[0]],['year','=',$monthDataFilter[1]],['acc_no','=',$acc_no],['clock_in','!=','']])
            ->whereIn('attendance_date', $monthly_holidays)
            ->orwhere([['month','=',$monthDataFilter[0]],['year','=',$monthDataFilter[1]],['acc_no','=',$acc_no],['clock_out','!=','']])
            ->whereIn('attendance_date', $monthly_holidays)
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewOverTimeDetail',compact('attData'));

    }

    public function  viewLateArrivalDetail()
    {

        $id = Input::get('id');

        $main_explode = explode(",",$id);
        $emp_id = $main_explode[0];

        $id_explode = explode("/",$main_explode[1]);


        $late_arrival_date = [];
        foreach ($id_explode as $key => $value) {
            $late_arrival_date[] = $value;
        }

        CommonHelper::companyDatabaseConnection(Input::get('m'));


        $emp_data =Employee::select('day_off','working_hours_policy_id')->where([['emp_id','=',$emp_id]]);

        CommonHelper::reconnectMasterDatabase();
        $working_policy_data = WorkingHoursPolicy::where([['id','=',$emp_data->value('working_hours_policy_id')]])->get()->toArray();
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $startTime =  strtotime($working_policy_data[0]['start_working_hours_time']);


        $halfdayTime = strtotime("+".$working_policy_data[0]['half_day_time']." hours", strtotime($working_policy_data[0]['start_working_hours_time']));

        $startTime2 = strtotime("+".$working_policy_data[0]['working_hours_grace_time']." minutes", strtotime($working_policy_data[0]['start_working_hours_time']));


        $lateArrivalData = Attendance::whereIn('attendance_date',$late_arrival_date)
            ->where('emp_id','=',$emp_id)
            ->get();

        $graceTime = date('H:i', $startTime);
        $graceTime2 = date('H:i', $startTime2);

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewLateArrivalDetail',compact('lateArrivalData','graceTime','graceTime2','halfDayData','halfdayTime'));
    }
    public function  viewLatesDetail()
    {
        $atendance=[];
        $data = Input::get('id');
        $dataFilter = explode(',',$data);

        $emp_id = $dataFilter[0];
        $from_date = $dataFilter[1];
        $to_date =  $dataFilter[2];

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp=Employee::where('emp_id',$emp_id)->where('status',1)->first();
        $emp_working_hours_policy_id=$emp->working_hours_policy_id;
        CommonHelper::reconnectMasterDatabase();
        $working_hours_policy=WorkingHoursPolicy::where('id',$emp_working_hours_policy_id)->where('status',1)->first();

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attendace=DB::table('attendance')->where([['attendance.emp_id','=',$emp_id]])
            ->whereBetween('attendance_date',[$from_date,$to_date])
            ->get();
        foreach($attendace as $value2){
            $startTime = $working_hours_policy->start_working_hours_time;
            $endTime = strtotime("+".$working_hours_policy->working_hours_grace_time."minutes", strtotime($startTime));
            if($value2->clock_in > date('h:i', $endTime)){
                $atendance[]=array('emp_name'=>$emp->emp_name,'attendance_date'=>$value2->attendance_date,'clock_in'=>$value2->clock_in,'clock_out'=>$value2->clock_out);
            }



        }
        return view('Hr.AjaxPages.viewLatesDetail',compact('atendance'));
    }
    
    public function viewHalfDaysDetail()
    {
        $data = Input::get('id');
        $dataFilter = explode(',',$data);
        $emp_id = $dataFilter[0];
        $month_data = $dataFilter[1];

        $monthDataFilter = explode('/',$month_data);
        
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_working_hours_policy_id=Employee::select('working_hours_policy_id')->where('emp_id',$emp_id)->value('working_hours_policy_id');
        CommonHelper::reconnectMasterDatabase();
        $working_hours_policy=WorkingHoursPolicy::where('id',$emp_working_hours_policy_id)->where('status',1)->first();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        

        $total_halfDay = Attendance::where([['emp_id','=',$emp_id]])
            ->whereBetween('attendance_date',[$dataFilter[1],$dataFilter[2]])
            ->get();

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewHalfDaysDetail',compact('total_halfDay','working_hours_policy'));

    }
    
    public function viewWorkingHoursDetail()
    {
        $data = Input::get('id');
        $dataFilter = explode(',',$data);
        $emp_id = $dataFilter[0];
        $month_data = $dataFilter[1];

        $monthDataFilter = explode('/',$month_data);
        
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_working_hours_policy_id=Employee::select('working_hours_policy_id')->where('emp_id',$emp_id)->value('working_hours_policy_id');
        CommonHelper::reconnectMasterDatabase();
        $working_hours_policy=WorkingHoursPolicy::where('id',$emp_working_hours_policy_id)->where('status',1)->first();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        

        $total_working_hours = Attendance::where([['emp_id','=',$emp_id]])
            ->whereBetween('attendance_date',[$dataFilter[1],$dataFilter[2]])
            ->get();

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewWorkingHoursDetail',compact('total_working_hours','working_hours_policy'));

    }
    

    public function viewOvertimeHoursDetail()
    {
        $data = Input::get('id');
        $dataFilter = explode(',',$data);
        $emp_id = $dataFilter[0];
        $month_data = $dataFilter[1];

        $from_date = $dataFilter[1];
        $to_date =  $dataFilter[2];

        $monthDataFilter = explode('-',$month_data);
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $totalOffDates[] = '';
        $day_off_emp = Employee::select('day_off')->where([['emp_id','=',$emp_id]])->first();
        $total_days_off = Attendance::select('attendance_date')->where([['day','=',$day_off_emp->day_off],['emp_id','=',$emp_id]]);

        if($total_days_off->count() > 0):
            foreach($total_days_off->get()->toArray() as $offDates):
                $totalOffDates[] = $offDates['attendance_date'];
            endforeach;
        else:
            $totalOffDates = array();
        endif;

        $monthly_holidays[] ='';
        $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$from_date,$to_date])->where([['status','=',1]]);

        if($get_holidays->count() > 0):
            foreach ($get_holidays->get() as $value2):
                $monthly_holidays[] = $value2['holiday_date'];
            endforeach;
        else:
            $monthly_holidays = array();
        endif;

        $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);

        $emp_data =Employee::select('working_hours_policy_id')->where([['emp_id','=',$emp_id]]);

        CommonHelper::reconnectMasterDatabase();

        $working_policy_data = WorkingHoursPolicy::where([['id','=',$emp_data->value('working_hours_policy_id')]])->first();

        $dutyEndTime = $working_policy_data->end_working_hours_time;
        $dutyStartTime =  $working_policy_data->start_working_hours_time;

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        // $time="10:09";
        $time = date('H:i', strtotime($dutyEndTime.'+40 minutes'));
        $earlyTime = date('H:i', strtotime($dutyStartTime.'-40 minutes'));

        $total_ot_hours_count = DB::table('attendance')->select('clock_in','clock_out','attendance_date','day','emp_id')->whereBetween('attendance_date',[$from_date,$to_date])->where([['emp_id','=',$emp_id],['clock_in','!=',''],['clock_out','!=',''],['clock_out','>',$time]])
            ->get()->toArray();

        $total_ot_hours_count_early = DB::table('attendance')->select('clock_in','clock_out','attendance_date','day','emp_id')->whereBetween('attendance_date',[$from_date,$to_date])->where([['emp_id','=',$emp_id],['clock_in','!=',''],['clock_in','<',$earlyTime]])
            ->get()->toArray();

        $total_ot_hours_count = array_merge($total_ot_hours_count,$total_ot_hours_count_early);

        $total_ot_hours_holidays_count = DB::table('attendance')->select('clock_in','clock_out','attendance_date','day','emp_id')->whereBetween('attendance_date',[$from_date,$to_date])->where([['emp_id','=',$emp_id],['clock_in','!=',''],['clock_out','!=','']])
            ->get();
        $attendanceOTHolidays = DB::table('attendance')
            ->select('attendance_date')
            ->where([['emp_id','=',$emp_id]])
            ->whereBetween('attendance_date',[$from_date,$to_date])
            ->whereIn('attendance_date',$monthly_holidays)
            ->get()->toArray();

        $id_array = array_column($attendanceOTHolidays, 'attendance_date');


        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewOvertimeHoursDetail',compact('total_ot_hours_count','dutyEndTime','total_ot_hours','attendanceOTHolidays','id_array','total_ot_hours_holidays_count','dutyStartTime'));
    }

    public function viewLeaveApplicationClientForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_id = Employee::select('id','leaves_policy_id')->where([['emp_id', '=',Input::get('emp_id') ]])->first();
        $leaves_policy_id = $emp_id->leaves_policy_id;
        if($leaves_policy_id == 0) { return "<span style='color:red;'>Please Select Leaves Policy !</span>";}
        $attendance_machine_id = Input::get('acc_no');
        //Employee::select('acc_no')->where([['id', '=', Auth::user()->emp_id]])->value('acc_no');
        CommonHelper::reconnectMasterDatabase();

        //Break
        $leaves_policy = DB::table('leaves_policy')
            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*', 'leaves_data.*')
            ->where([['leaves_policy.id', '=', $leaves_policy_id]])
            ->get();


        $leaves_policy_validatity = DB::table('leaves_policy')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.id', 'leaves_data.id')
            ->where([['leaves_policy.id', '=', $leaves_policy_id], ['leaves_policy.policy_date_till', '>', date("Y-m-d")]])
            ->count();

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_data = Employee::select('emp_name', 'emp_department_id', 'designation_id', 'emp_id','leaves_policy_id')->where([['id', '=', $emp_id->id]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewLeaveApplicationClientForm', compact( 'leaves_policy_validatity', 'leaves_policy', 'emp_data'));
    }

    public  function viewHolidaysMonthWise()
    {
        $monthData = explode('-',Input::get('monthYear'));
        $year = $monthData[0];
        $month = $monthData[1];
        $m  = Input::get('m');

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $holidays = Holidays::where('month','=',$month)->where('year','=',$year)->where('status','=',1)->orderBy('holiday_date')->get();
        CommonHelper::reconnectMasterDatabase();
//            return view('Hr.viewHolidaysList',compact('holidays'));
        return view('Hr.AjaxPages.viewHolidaysMonthWise',compact('holidays','m'));
    }

    public function viewEmployeeDepositDetail()
    {

    }

    public function viewEmployeeListManageAttendence(){

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $getData['emr_no'] = Input::get('emr_no');
        $emp_data = Employee::select('emp_name','day_off')->where([['emr_no', '=', Input::get('emr_no')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $getData['emp_name'] = $emp_data['emp_name'];
        $getData['day_off'] = $emp_data['day_off'];
        $getData['sub_department_id'] = Input::get('sub_department_id');
        $monthYearDataFilter = explode('-',Input::get('month_year'));
        $getData['month'] = $monthYearDataFilter[1];
        $getData['year'] = $monthYearDataFilter[0];
        $getData['company_id'] = Input::get('m');

        return view('Hr.AjaxPages.createManuallyAttendanceForm',compact('getData'));

    }


    function  viewEmployeeExitClearanceForm($id='')
    {

        $emp_id = Input::get('emp_id');

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        //$employee=Employee::where([['status', '!=', 2],['emp_id', '=', $emp_id]])->select('emp_name','designation_id');
        $employee=Employee::where([['status', '!=', '2'],['emp_id', '=', $emp_id]])->first();
        if($employee->count() != 0 ) {
            $designation_id = $employee->designation_id;

            $employeeCurrentPositions = EmployeePromotion::where([['emp_id', '=', Input::get('emp_id')], ['status', '=', 1], ['approval_status', '=', 2]])->orderBy('id', 'desc');
            if ($employeeCurrentPositions->count() > 0):
                $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
                $designation_id = $employeeCurrentPositionsDetail->designation_id;
            endif;

            $exit_data = EmployeeExit::where([['status', '=', 1], ['emp_id', '=', $emp_id]]);

            CommonHelper::reconnectMasterDatabase();

            $count = $exit_data->count();
            $exit_employee_data = $exit_data->first();


            return view('Hr.AjaxPages.viewEmployeeExitClearanceForm', compact('employee', 'count', 'exit_employee_data', 'designation_id','emp_id'));
        }
        else{
            ?><h3 class="text-center">No Record</h3><?php
        }

    }

    public function viewEmployeeExitClearanceDetail()
    {
        $id = Input::get('id');
        $m 	= Input::get('m');
        $type = Input::get('type');

        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $exit_employee_data = EmployeeExit::where([['id', '=', $id]])->first();
        $employee=Employee::where([['status', '!=', 2],['emp_id', '=', $exit_employee_data->emp_id]])->select('emp_name','designation_id', 'emp_sub_department_id')->first();
        $designation_id = $employee->designation_id;
        $emp_sub_department_id = $employee->emp_sub_department_id;
        $emp_department_id = $employee->emp_department_id;

        $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',$exit_employee_data->emp_id],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail->designation_id;
        endif;

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeExitClearanceDetail',compact('exit_employee_data','employee','emp_department_id','emp_sub_department_id','designation_id','exit_employee_data','designation','location','operation_rights2','type'));
    }

    public function checkEmrNoExist()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_count = Employee::where([['emp_id','=',Input::get('emp_id')],['status','!=',2]])->count();
        CommonHelper::reconnectMasterDatabase();

        if($employee_count > 0 ):
            echo "EMP ID. ".Input::get('emp_id')." Already Exist !";
        else:
            echo "success";
        endif;
    }

    function  viewEmployeeIdCardRequest($id='')
    {
        $emr_no = Input::get('emr_no');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee=Employee::where([['status','=',1], ['emr_no', '=', $emr_no]])->select('img_path','designation_id','emp_joining_date', 'emp_cnic')->first();
        CommonHelper::reconnectMasterDatabase();

        $designation=Designation::where([['status', '=' ,1],['id', '=', $employee['designation_id']]])->select('designation_name')->first();
        return view('Hr.AjaxPages.viewEmployeeIdCardRequest', compact('designation', 'employee', 'employee_card_request'));

    }

    function  viewEmployeeIdCardRequestDetail($id='')
    {
        $id = Input::get('id');
        $m 	= Input::get('m');
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_card_request = EmployeeCardRequest::where([['id', '=', $id]])->select('id','fir_copy_path','fir_copy_extension',
            'emr_no','posted_at','card_status', 'status', 'approval_status',
            'card_image_extension','card_image_path')->first();
        $employee = Employee::where([['emr_no',$employee_card_request->emr_no],['status', '=', 1]])->select('emp_name', 'img_path', 'designation_id', 'emp_sub_department_id', 'emp_joining_date', 'emp_cnic')->first();
        CommonHelper::reconnectMasterDatabase();

        $designation = Designation::where([['status', '=', 1],['id', '=', $employee->designation_id]])->select('designation_name')->first();
        $sub_department = SubDepartment::where([['status', '=', 1],['id', '=', $employee->emp_sub_department_id]])->select('sub_department_name')->first();
        return view('Hr.AjaxPages.viewEmployeeIdCardRequestDetail', compact('designation', 'employee_card_request', 'employee', 'sub_department', 'operation_rights2'));

    }

    public function viewEmployeePreviousPromotionsDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $get_user_data = EmployeePromotion::where([['emp_id','=',Input::get('emp_id')],['status','=',1]])->get();
        if(count($get_user_data) != '0'){
            $employee = DB::table('employee_promotion')
                ->where([['emp_id','=',Input::get('emp_id')],['status','=',1]])
                ->orderBy('id','desc')
                ->first();
            $salary = $employee->salary;
            $date = $employee->promotion_date;

        }
        else{
            $employee = Employee::where([['emp_id','=',Input::get('emp_id')],['status','=',1]])->first();
            $salary = $employee->emp_salary;
            $date = $employee->date;
        }


        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeePreviousPromotionsDetail', compact('employee','salary','date'));
    }

    public function viewEmployeePromotionsDetail()
    {

    }

    public function viewEmployeeDocuments()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('emp_id','emp_name','cnic_path')->where([['id','=',Input::get('id')],['status','=',1]])->first();
        $employeeDocuments = EmployeeDocuments::where([['emp_id','=',$employee->emp_id],['status','=',1]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeDocuments', compact('employee','employeeDocuments'));
    }

    function  viewEmployeeFilteredList()
    {
        $sub_department_id = Input::get('sub_department_id');
        $emp_name = Input::get('emp_name');
        $emp_id = Input::get('emp_id');
        $department_id = Input::get('department_id');

        $query_string_second_part = [];

        if(!empty($department_id))
            $query_string_second_part[] = " AND emp_department_id = '$department_id'";
        if(!empty($sub_department_id))
            $query_string_second_part[] = " AND emp_sub_department_id = '$sub_department_id'";
        if(!empty($emp_name))
            $query_string_second_part[] = " AND emp_name LIKE '%' '$emp_name' '%' ";
        if(!empty($emp_id))
            $query_string_second_part[] = " AND emp_id = '$emp_id'";

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $query_string_First_Part= "SELECT id, emp_sub_department_id, emp_id, emp_name, emp_salary,
        emp_contact_no,emp_joining_date, emp_cnic,employee_project_id, emp_date_of_birth, status FROM employee WHERE";
        $query_string_third_part = ' ORDER BY emp_id';
        $query_string_second_part= implode(" ", $query_string_second_part);
        $query_string_second_part=  preg_replace("/AND/", " ", $query_string_second_part, 1);
        $query_string=$query_string_First_Part.$query_string_second_part.$query_string_third_part;

        $employees = DB::select(DB::raw($query_string));
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeFilteredList',compact('employees'));
    }

    public function viewExpiryAndUpcomingAlerts()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $expireDateOne=date('Y-m-d',strtotime(now() .'+1 month'));
        $settlementDate =date('Y-m-d',strtotime(now() .'+2 days'));
        $cnic_expiry_date_count = Employee::where([['status','=',1],['emp_cnic_expiry_date','<',$expireDateOne],['emp_cnic_expiry_date','>',date('Y-m-d')],['emp_cnic_expiry_date','!=','']])->count();
        $upcoming_birthday_count = DB::select( DB::raw("SELECT count('emp_date_of_birth') as upcoming_birthday_count  FROM employee where DATE_FORMAT(emp_date_of_birth, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') and DATE_FORMAT(emp_date_of_birth, '%m-%d') <= DATE_FORMAT((NOW() + INTERVAL +1 month), '%m-%d') "));
        $permanent_employee = Employee::select('emp_employementstatus_id')->where([['status','=',1],['emp_employementstatus_id','=',7]])->get();
        $over_age_employee_count = DB::select( DB::raw('SELECT count("emp_date_of_birth") as over_age_employee_count FROM employee WHERE status=1 and DATEDIFF(NOW(), emp_date_of_birth) / 365.25 >= 60'));
        $employee_missing_images= Employee::where([['status','=',1],['img_path','=','app/uploads/employee_images/user-dummy.png']])->count();
        $nadra = EmployeeGsspDocuments::select('emp_id')->where([['document_type','=','Nadra']]);
        $settlementTermination1= HrTerminationFormat1Letter::where([['status','=',1],['settlement_date','<',$settlementDate],['settlement_date','>',date('Y-m-d')],['settlement_date','!=','']])->count();
        $settlementTermination2= HrTerminationFormat2Letter::where([['status','=',1],['settlement_date','<',$settlementDate],['settlement_date','>',date('Y-m-d')],['settlement_date','!=','']])->count();
        $settlementContract= HrContractConclusionLetter::where([['status','=',1],['settlement_date','<',$settlementDate],['settlement_date','>',date('Y-m-d')],['settlement_date','!=','']])->count();
        $date =date("Y-m-d");
        $employeesProbationExpires=Employee::select('id','emp_id','emp_joining_date','emp_name','probation_expire_date','emp_employementstatus_id')->where([['probation_expire_date','<=',$date],['status','=',1],['emp_employementstatus_id','!=',7]])->count();


        $employee_settlement = ($settlementTermination1+$settlementTermination2+$settlementContract);
        if($nadra->count()):
            $nonVerfiedNadraEmp = $nadra->get()->toArray();
            $non_verified_nadra_count = Employee::select('emp_id','emp_name')->whereNotIn('emp_id', $nonVerfiedNadraEmp)->count();
        else:
            $non_verified_nadra_count =0;
        endif;

        $police = EmployeeGsspDocuments::select('emp_id')->where([['document_type','=','Police']]);
        if($police->count()):
            $nonVerfiedNadraEmp = $police->get()->toArray();
            $non_verified_police_count = Employee::select('emp_id','emp_name')->whereNotIn('emp_id', $nonVerfiedNadraEmp)->count();
        else:
            $non_verified_police_count =0;
        endif;
        $warning_letter = HrWarningLetter::all()->count();
        $demiseEmployees = EmployeeExit::where([['leaving_type','=',5]])->count();
        $employeeProbationPeriodOverDetail =DB::select( DB::raw("SELECT count('emp_id') as totalOverProbationEmp FROM employee WHERE emp_employementstatus_id = '8' AND status = '1' AND emp_joining_date <= DATE_ADD('".date("Y-m-d")."',INTERVAL -6 MONTH)"));

        $date1 = date('6-Y');
        $month_and_year1 = explode('-',$date1);
        $sixthMonthAudit = EmployeeHrAudit::select('emp_id')->where([['month','=',$month_and_year1[0]],['year','=',$month_and_year1[1]]]);
        if($sixthMonthAudit->count()):
            $sixthMonthAuditEmp = $sixthMonthAudit->get()->toArray();
            $sixthMonthAuditEmpCount = Employee::select('emp_id','emp_name')->whereNotIn('emp_id', $sixthMonthAuditEmp)->count();
        else:
            $sixthMonthAuditEmpCount =0;
        endif;

        $date2 = date('12-Y');
        $month_and_year2 = explode('-',$date2);

        $twelfthMonthAudit = EmployeeHrAudit::select('emp_id')->where([['month','=',$month_and_year2[0]],['year','=',$month_and_year2[1]]]);
        if($twelfthMonthAudit->count()):
            $twelfthMonthAuditEmp = $twelfthMonthAudit->get()->toArray();
            $twelfthMonthAuditEmpCount = Employee::select('emp_id','emp_name')->whereNotIn('emp_id', $twelfthMonthAuditEmp)->count();
        else:
            $twelfthMonthAuditEmpCount =0;
        endif;
        $totalAuditHrEmp = $twelfthMonthAuditEmpCount+$sixthMonthAuditEmpCount;
        $employee_missing_eobi = Employee::where([['status','=',1],['eobi_path','=',null]])->count();
        $employee_missing_insurance = Employee::where('status','=',1)->count();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewExpiryAndUpcomingAlerts',compact('totalAuditHrEmp', 'employee_settlement','employee_missing_eobi','employeesProbationExpires', 'employee_missing_insurance', 'employeeProbationPeriodOverDetail','demiseEmployees','warning_letter','employee_missing_images','non_verified_police_count','non_verified_nadra_count','cnic_expiry_date_count','upcoming_birthday_count','over_age_employee_count','permanent_employee'));
    }

    public function viewEmployeeFuelDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeData = Employee::select('emr_no', 'branch_id', 'designation_id')->where([['emr_no','=',Input::get('emr_no')],['status','=',1]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeFuelDetailForm', compact('employeeData'));
    }

    public function viewEmployeeFuelDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emr_no = Input::get('emr_no');
        $employeeFuelData = EmployeeFuelData::where([['emr_no','=',Input::get('emr_no')],['status','=',1]])->orderBy('fuel_date');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeFuelDetail', compact('employeeFuelData', 'emr_no'));
    }

    public function viewEmployeeFilteredFuelDetail()
    {
        $emr_no = Input::get('emr_no');
        $fuel_month = Input::get('fuel_month');
        $fuel_year = Input::get('fuel_year');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeFuelData = EmployeeFuelData::where([['emr_no','=',Input::get('emr_no')],['status','=',1],['fuel_month', '=', Input::get('fuel_month')],['fuel_year', '=', Input::get('fuel_year')]])->orderBy('fuel_date');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeFilteredFuelDetail', compact('employeeFuelData', 'emr_no', 'fuel_month', 'fuel_year'));
    }


    public function viewUpcomingBirthdaysDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $upcoming_birthdays_detail = DB::select( DB::raw("SELECT id,emp_id,emp_name,emp_date_of_birth FROM employee where DATE_FORMAT(emp_date_of_birth, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d') and DATE_FORMAT(emp_date_of_birth, '%m-%d') <= DATE_FORMAT((NOW() + INTERVAL +1 month), '%m-%d') ORDER BY MONTH(emp_date_of_birth), DAYOFMONTH(emp_date_of_birth)"));
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewUpcomingBirthdaysDetail',compact('upcoming_birthdays_detail'));
    }

    public function viewPermanentEmployee()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $permanent_employee = Employee::where([['status','=','1'],['emp_employementstatus_id','=',7]])->get();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewPermanentEmployee',compact('permanent_employee'));
    }

    public function viewEmployeeCnicExpireDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $expireDateOne=date('Y-m-d',strtotime(now() .'+1 month'));
        $cnic_expiry_date_detail = Employee::select('id','emp_name','emp_cnic','emp_id','emp_cnic_expiry_date')
            ->where([['status','=',1],['emp_cnic_expiry_date','<',$expireDateOne],['emp_cnic_expiry_date','>',date('Y-m-d')],['emp_cnic_expiry_date','!=','']]);
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeCnicExpireDetail',compact('cnic_expiry_date_detail'));
    }
    public function viewEmployeeOverAgeDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $over_age_employee_detail = DB::select( DB::raw("SELECT id,emp_id,emp_date_of_birth,emp_name FROM employee WHERE status=1 and DATEDIFF(NOW(), emp_date_of_birth) / 365.25 >= 60"));
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeOverAgeDetail',compact('over_age_employee_detail'));
    }

    public function viewNonVerifiedNadraEmployeeDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $nadra = EmployeeGsspDocuments::select('emp_id')->where([['document_type','=','Nadra']]);
        if($nadra->count()):
            $nonVerfiedNadraEmp = $nadra->get()->toArray();
            $nonVerfiedNadraEmpDetail = Employee::select('emp_id','emp_name')->whereNotIn('emp_id', $nonVerfiedNadraEmp)->get()->toArray();
        else:
            $nonVerfiedNadraEmpDetail =array();
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewNonVerifiedNadraEmployeeDetail',compact('nonVerfiedNadraEmpDetail'));
    }
    public function viewNonVerifiedPoliceEmployeeDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $police = EmployeeGsspDocuments::select('emp_id')->where([['document_type','=','Police']]);
        if($police->count()):
            $nonVerfiedPoliceEmp = $police->get()->toArray();
            $nonVerfiedPoliceEmpDetail = Employee::select('emp_id','emp_name')->whereNotIn('emp_id', $nonVerfiedPoliceEmp)->get()->toArray();
        else:
            $nonVerfiedPoliceEmpDetail =array();
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewNonVerifiedPoliceEmployeeDetail',compact('nonVerfiedPoliceEmpDetail'));
    }

    public function viewEmployeeMissingImageDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_missing_images= Employee::select('id','emp_name','emp_id')->where([['status','=',1],['img_path','=','app/uploads/employee_images/user-dummy.png']]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeMissingImageDetail',compact('employee_missing_images'));
    }

    public function viewEmployeeGsspVeriDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('emr_no','emp_name')->where([['id','=',Input::get('id')],['status','=',1]])->first();
        $viewEmployeeGsspVeriDetail = EmployeeGsspDocuments::where([['emr_no','=',$employee->emr_no]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeGsspVeriDetail',compact('viewEmployeeGsspVeriDetail'));
    }

    public function viewEmployeeWarningLetterDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $warningLetters = HrWarningLetter::all()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeWarningLetterDetail',compact('warningLetters'));
    }

    public function viewDemiseEmployeeDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $DemiseEmployee = EmployeeExit::all()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewDemiseEmployeeDetail',compact('DemiseEmployee'));
    }

    public function viewEmployeeProbationPeriodOverDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $probationEmployees =DB::select( DB::raw("SELECT emp_id,emp_joining_date,emp_name FROM employee
         WHERE emp_employementstatus_id = '8' AND status = '1'
         AND emp_joining_date <= DATE_ADD('".date("Y-m-d")."',INTERVAL -6 MONTH)"));
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeProbationPeriodOverDetail',compact('probationEmployees'));
    }

    public function viewHrEmployeeAuditDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeAuditDetail = Employee::select('emp_id','emp_name')->get()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrEmployeeAuditDetail',compact('employeeAuditDetail'));

    }

    public function viewHrLetters()
    {

        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        $emr_no = Input::get('emp_id');
        $m = Input::get('m');
        $emp_category_id = Input::get('emp_category_id');
        $employee_project_id = Input::get('employee_project_id');
        $department_id = Input::get('department_id');
        $sub_department_id_1 = Input::get('sub_department_id');
        $region_id = Input::get('region_id');
        $letter_id = Input::get('letter_id');
        $show_all = Input::get('show_all');

        if($show_all == 1){
            $employee_all_emrno = HrHelper::getAllEmployeeId(Input::get('m'),$department_id,$region_id,'show_all');
        }
        else{
            $employee_all_emrno = HrHelper::getAllEmployeeId(Input::get('m'),$emp_category_id,$region_id,$employee_project_id);
        }


        if($letter_id == 1)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            if($show_all == 1){
                $hr_warning_letter =  DB::table('hr_warning_letter')
                    ->join('employee','hr_warning_letter.emp_id','=','employee.emp_id')
                    ->select('hr_warning_letter.id','employee.emp_name','hr_warning_letter.emp_id','hr_warning_letter.note','hr_warning_letter.date')
                    ->whereIn('hr_warning_letter.emp_id',$employee_all_emrno)
                    ->where('hr_warning_letter.status','=',1)
                    ->orderBy('hr_warning_letter.id', 'desc');
            }
            else if($emr_no  == 'All'){
                $hr_warning_letter =  DB::table('hr_warning_letter')
                    ->join('employee','hr_warning_letter.emp_id','=','employee.emp_id')
                    ->select('hr_warning_letter.id','employee.emp_name','hr_warning_letter.emp_id','hr_warning_letter.note','hr_warning_letter.date')
                    ->whereIn('hr_warning_letter.emp_id',$employee_all_emrno)
                    ->where('hr_warning_letter.status','=',1)
                    ->orderBy('hr_warning_letter.id', 'desc');
            }
            else{
                $hr_warning_letter =  DB::table('hr_warning_letter')
                    ->join('employee','hr_warning_letter.emp_id','=','employee.emp_id')
                    ->select('hr_warning_letter.id','employee.emp_name','hr_warning_letter.emp_id','hr_warning_letter.note','hr_warning_letter.date')
                    ->where([['hr_warning_letter.emp_id','=',$emr_no],['hr_warning_letter.status','=',1]])
                    ->orderBy('hr_warning_letter.id', 'desc');
            }
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrWarningLetterList', compact('hr_warning_letter','operation_rights2'));
        }
        elseif ($letter_id == 2)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));

            $hr_mfm_south_increment_letter = HrMfmSouthIncrementLetter::where([['emp_id','=',Input::get('emp_id')],['status', '=', 1]])->orderBy('id', 'desc');

            $employeeCurrentPositions = Employee::select('designation_id','emp_salary', 'emp_joining_date')->where([['emp_id','=',Input::get('emp_id')],['status','!=',2]])->first();
            $designation_id = $employeeCurrentPositions->designation_id;
            $current_salary = $employeeCurrentPositions->emp_salary;
            $performance_from_date = $employeeCurrentPositions->emp_joining_date;

            $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',Input::get('emp_id')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');

            $employeeAllowances = Allowance::where([['emp_id','=',$emr_no],['status','=',1]]);
            if($employeeAllowances->count() > 0):
                $employeeAllowances = $employeeAllowances->get();
            endif;

            if($employeeCurrentPositions->count() > 0):
                $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
                $designation_id = $employeeCurrentPositionsDetail->designation_id;
                $new_salary = $employeeCurrentPositionsDetail->salary;
                $performance_to_date = $employeeCurrentPositionsDetail->promotion_date;

            else:
                return '<div class="text-center" style="color: red"><table class="table table-bordered"><tr><td>Record not found!!</td></tr></table></div>';
            endif;

            if($employeeCurrentPositions->count() > 1):
                $employeeLastPositions = EmployeePromotion::select('designation_id','salary', 'promotion_date')->where([['emp_id','=',Input::get('emp_id')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc')->skip(1)->take(1);
                $employeeLastPositionDetails = $employeeLastPositions->first();
                $designation_id = $employeeLastPositionDetails->designation_id;
                $current_salary = $employeeLastPositionDetails->salary;
                $performance_from_date = $employeeLastPositionDetails->promotion_date;
            endif;

            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrMfmSouthIncrementLetterList', compact( 'hr_mfm_south_increment_letter','operation_rights2','performance_from_date','performance_to_date', 'employeeAllowances', 'current_salary','new_salary', 'designation_id' ));
        }
        elseif ($letter_id == 3)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_mfm_south_without_increment_letter = HrMfmSouthWithoutIncrementLetter::where([['emp_id','=',Input::get('emp_id_no')],['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrMfmSouthWithoutIncrementLetterList', compact('hr_mfm_south_without_increment_letter','operation_rights2' ));
        }
        elseif ($letter_id == 4)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_contract_conclusion_letter = HrContractConclusionLetter::where([['emp_id','=',Input::get('emp_id')],['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrContractConclusionLetterList', compact('hr_contract_conclusion_letter','operation_rights2' ));
        }
        elseif ($letter_id == 5)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_termination_format1_letter = HrTerminationFormat1Letter::where([['emp_id','=',Input::get('emp_id')],['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrTerminationFormat1LetterList', compact('hr_termination_format1_letter','operation_rights2' ));
        }
        elseif ($letter_id == 6)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_termination_format2_letter = HrTerminationFormat2Letter::where([['emp_id','=',Input::get('emp_id')],['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrTerminationFormat2LetterList', compact('hr_termination_format2_letter','operation_rights2' ));
        }
        elseif ($letter_id == 7)
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $hr_transfer_letter = HrTransferLetter::where([['emp_id','=',Input::get('emp_id')],['status', '=', 1]])->orderBy('id', 'desc');
            CommonHelper::reconnectMasterDatabase();
            return view('Hr.AjaxPages.viewHrTransferLetterList', compact('hr_transfer_letter','operation_rights2' ));
        }
        else
        {
            return;
        }

    }

    public function getEmployeeDateOfJoining()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $date_of_joining = Employee::select('emp_joining_date')->where([['emp_id','=',Input::get('emr_no')]])->value('emp_joining_date');
        CommonHelper::reconnectMasterDatabase();
        $data[] = date('F d, Y', strtotime(Input::get('settlement_date')));
        $data[] = date('F d, Y', strtotime($date_of_joining));
        return $data;
    }

    public function getConclusionLettersDate()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $date_of_joining = Employee::select('emp_joining_date')->where([['emp_id','=',Input::get('emr_no')]])->value('emp_joining_date');
        CommonHelper::reconnectMasterDatabase();
        $data[] = date('F d, Y', strtotime($date_of_joining));
        $data[] = date('F d, Y', strtotime(Input::get('conclude_date')));
        $data[] = date('F d, Y', strtotime(Input::get('settlement_date')));
        return $data;
    }

    public function getIncrementLettersDetails()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hr_mfm_south_increment_letter = HrMfmSouthIncrementLetter::where([['emp_id','=',Input::get('emr_no')],['status', '=', 1]])->orderBy('id', 'desc');
        $employeeCurrentPositions = Employee::select('designation_id','emp_salary', 'emp_joining_date')->where([['emp_id','=',Input::get('emr_no')],['status','!=',2]])->first();
        $designation_id = $employeeCurrentPositions->designation_id;
        $current_salary = $employeeCurrentPositions->emp_salary;
        $performance_from_date = $employeeCurrentPositions->emp_joining_date;


        $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',Input::get('emr_no')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');

        $employeeAllowances = Allowance::where([['emp_id','=',Input::get('emr_no')],['status','=',1]]);
        if($employeeAllowances->count() > 0):
            $employeeAllowances = $employeeAllowances->get();
        endif;

        if($employeeCurrentPositions->count() > 1):
            $employeeLastPositions = EmployeePromotion::select('designation_id','salary', 'promotion_date')->where([['emp_id','=',Input::get('emr_no')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc')->skip(1)->take(1);
            $employeeLastPositionDetails = $employeeLastPositions->first();
            $designation_id = $employeeLastPositionDetails->designation_id;
            $current_salary = $employeeLastPositionDetails->salary;
            $performance_from_date = $employeeLastPositionDetails->promotion_date;
        endif;

        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail->designation_id;
            $new_salary = $employeeCurrentPositionsDetail->salary;
            $performance_to_date = $employeeCurrentPositionsDetail->promotion_date;

        else:
            return '1';
        endif;

        CommonHelper::reconnectMasterDatabase();
        $designation_name = Designation::where([['id', '=', $designation_id], ['status', '=', '1']])->select('designation_name')->first();

        $data[] = date('F d, Y', strtotime($performance_from_date));
        $data[] = date('F d, Y', strtotime($performance_to_date));
        $data[] = date('F d, Y', strtotime(Input::get('confirmation_from')));
        $data[] = $designation_name->designation_name;
        $data[] = $new_salary - $current_salary;
//        $data[] = $current_salary;
//        $data[] = $new_salary;
//        $data[] = $employeeAllowances;
        return $data;

    }

    public function getWithoutIncrementLettersDate()
    {
        $data[] = date('F d, Y', strtotime(Input::get('performance_from')));
        $data[] = date('F d, Y', strtotime(Input::get('performance_to')));
        $data[] = date('F d, Y', strtotime(Input::get('confirmation_from')));
        return $data;
    }

    public function getTransferLettersDetails()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hr_transfer_letter = HrTransferLetter::where([['emp_id','=',Input::get('emr_no')],['status', '=', 1]])->orderBy('id', 'desc');
        $employeeCurrentPositions = Employee::select('designation_id','branch_id')->where([['emp_id','=',Input::get('emr_no')],['status','!=',2]])->first();
        $designation_id = $employeeCurrentPositions->designation_id;
        $transfer_from = $employeeCurrentPositions->branch_id;

        $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',Input::get('emr_no')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
        $employeeCurrentLocations = EmployeeTransfer::where([['emp_id','=',Input::get('emr_no')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');

        $employeeAllowances = Allowance::where([['emp_id','=',Input::get('emr_no')],['status','=',1]]);
        if($employeeAllowances->count() > 0):
            $employeeAllowances = $employeeAllowances->get();
        endif;

        if($employeeCurrentPositions->count() > 1):
            $employeeLastPositions = EmployeePromotion::select('designation_id','salary', 'promotion_date')->where([['emp_id','=',Input::get('emr_no')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc')->skip(1)->take(1);
            $employeeLastPositionDetails = $employeeLastPositions->first();
            $designation_id = $employeeLastPositionDetails->designation_id;
        endif;

        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail->designation_id;
        endif;

        if($employeeCurrentLocations->count() > 1):
            $employeeLastLocation = EmployeeTransfer::select('location_id')->where([['emp_id','=',Input::get('emr_no')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc')->skip(1)->take(1);
            $employeeLastLocationDetails = $employeeLastLocation->first();
            $transfer_from = $employeeLastLocationDetails->location_id;
        endif;

        if($employeeCurrentLocations->count() > 0):
            $employeeCurrentLocationsDetail = $employeeCurrentLocations->first();
            $transfer_to = $employeeCurrentLocationsDetail->location_id;
        else:
            return '1';
        endif;

        CommonHelper::reconnectMasterDatabase();
        $designation_name = Designation::where([['id', '=', $designation_id], ['status', '=', '1']])->select('designation_name')->first();
        $transfer_from = Locations::where([['id', '=', $transfer_from], ['status', '=', '1']])->select('employee_location')->first();
        $transfer_to = Locations::where([['id', '=', $transfer_to], ['status', '=', '1']])->select('employee_location')->first();


        $data[] = $transfer_from->employee_location;
        $data[] = $transfer_to->employee_location;
        $data[] = date('F d, Y', strtotime(Input::get('transfer_date')));
        $data[] = $designation_name->designation_name;

        return $data;

    }

    public function viewHrLetterFiles()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hrLettersFile = LetterFiles::where([['id','=',Input::get('id')],['status','=',1]])->get()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrLetterFiles', compact('hrLettersFile'));
    }

    public function viewEmployeeEquipmentsForm()
    {
        $equipment = null;
        $employeeEquipment = Equipments::where([['company_id','=',Input::get('m')],['status','=',1]])->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('id','emp_id', 'eobi_number', 'eobi_path')->where([['emp_id','=',Input::get('emp_id')],['status','=',1]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeEquipmentsForm', compact('employee','equipment', 'employeeEquipment'));
    }

    public function viewEmployeeEquipmentsDetail()
    {
        $equipment_detail = null;
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_emr_no = EmployeeEquipments::where([['id','=', Input::get('id')]])->first();

        $emr_no = $employee_emr_no->emp_id;
        $employee = Employee::select('id','eobi_number', 'eobi_path')->where([['emp_id','=',$emr_no],['status','!=',2]])->first();
        $employeeEquipment  = EmployeeEquipments::where([['emp_id','=', $emr_no]])->pluck('equipment_id')->toArray();

        if(EmployeeEquipments::select('mobile_number', 'model_number', 'sim_number')->where([['emp_id','=',$emr_no],['status','=',1],['equipment_id', '=', 11]])->exists()):
            $equipment_detail = EmployeeEquipments::select('mobile_number', 'model_number', 'sim_number')->where([['emp_id','=',$emr_no],['status','=',1],['equipment_id', '=', 11]])->first();
        endif;

        $employee_eobi_copy = Employee::where([['emp_id','=',$emr_no],['status','!=',2],['eobi_path', '!=', null]]);
        $employee_insurance_copy = Employee::where([['emp_id','=',$emr_no],['status','!=',2]]);

        CommonHelper::reconnectMasterDatabase();
        $equipment = Equipments::where([['status','=', 1]])->orderBy('id')->get();

        return view('Hr.AjaxPages.viewEmployeeEquipmentsDetail', compact('employeeEquipment', 'emr_no', 'equipment', 'employee', 'equipment_detail', 'employee_insurance_copy', 'employee_eobi_copy'));

    }

    public function viewEmployeePreviousAllowancesDetail()
    {
        $emr_no = Input::get('emr_no');
        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $allowances = Allowance::where([['emp_id', '=', $emr_no]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeePreviousAllowancesDetail',compact('allowances'));
    }

    public function viewHrWarningLetter($id, $m )
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_warning_letter = HrWarningLetter::where([['id','=',$id]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrWarningLetter', compact('hr_warning_letter', 'operation_rights2'));
    }

    public function viewHrMfmSouthIncrementLetter($id, $m )
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hr_mfm_south_increment_letter = HrMfmSouthIncrementLetter::where([['id','=',$id]])->orderBy('id', 'desc')->first();

        $emr_no = $hr_mfm_south_increment_letter['emr_no'];
        $employeeCurrentPositions = Employee::select('designation_id','emp_salary', 'emp_joining_date')->where([['emr_no','=',$emr_no],['status','=',1]])->first();
        $designation_id = $employeeCurrentPositions['designation_id'];
        $current_salary = $employeeCurrentPositions['emp_salary'];

        $employeeAllowances = Allowance::where([['emp_id','=',$emr_no],['status','=',1]]);
        if($employeeAllowances->count() > 0):
            $employeeAllowances = $employeeAllowances->get();
        endif;

        $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',$emr_no],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');

        if($employeeCurrentPositions->count() > 1):
            $employeeLastPositions = EmployeePromotion::select('designation_id','salary', 'promotion_date')->where([['emp_id','=',$emr_no],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc')->skip(1)->take(1);
            $employeeLastPositionDetails = $employeeLastPositions->first()->toArray();
            $designation_id = $employeeLastPositionDetails['designation_id'];
            $current_salary = $employeeLastPositionDetails['salary'];
        endif;

        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail['designation_id'];
            $new_salary = $employeeCurrentPositionsDetail['salary'];

        else:
            return '<div class="text-center" style="color: red"><table class="table table-bordered"><tr><td>Record not found!!</td></tr></table></div>';
        endif;
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewHrMfmSouthIncrementLetter', compact('hr_mfm_south_increment_letter','designation_id', 'current_salary', 'new_salary', 'employeeAllowances', 'operation_rights2' ));
    }

    public function viewHrMfmSouthWithoutIncrementLetter($id, $m )
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_mfm_south_without_increment_letter = HrMfmSouthWithoutIncrementLetter::where([['id','=',$id]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrMfmSouthWithoutIncrementLetter', compact('hr_mfm_south_without_increment_letter', 'operation_rights2' ));
    }

    public function viewHrContractConclusionLetter($id, $m )
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_contract_conclusion_letter = HrContractConclusionLetter::where([['id','=',$id]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrContractConclusionLetter', compact('hr_contract_conclusion_letter', 'operation_rights2' ));
    }

    public function viewHrTerminationFormat1Letter($id, $m )
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_termination_format1_letter = HrTerminationFormat1Letter::where([['id','=',$id]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrTerminationFormat1Letter', compact('hr_termination_format1_letter', 'operation_rights2' ));
    }

    public function viewHrTerminationFormat2Letter($id, $m )
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_termination_format2_letter = HrTerminationFormat2Letter::where([['id','=',$id]])->orderBy('id', 'desc')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrTerminationFormat2Letter', compact('hr_termination_format2_letter','operation_rights2' ));
    }

    public function viewHrTransferLetter($id, $m )
    {
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages('hr/viewHrLetters');
        CommonHelper::companyDatabaseConnection($m);
        $hr_transfer_letter = HrTransferLetter::where([['id','=',$id]])->orderBy('id', 'desc')->first();
        $emr_no = $hr_transfer_letter['emr_no'];

        $employeeCurrentPositions = Employee::select('designation_id')->where([['emp_id','=',$emr_no],['status','=',1]])->first();
        $designation_id = $employeeCurrentPositions['designation_id'];

        $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',$emr_no],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');

        if($employeeCurrentPositions->count() > 1):
            $employeeLastPositions = EmployeePromotion::select('designation_id')->where([['emp_id','=', $emr_no],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc')->skip(1)->take(1);
            $employeeLastPositionDetails = $employeeLastPositions->first();
            $designation_id = $employeeLastPositionDetails['designation_id'];
        endif;

        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail['designation_id'];
        endif;

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHrTransferLetter', compact('hr_transfer_letter','operation_rights2', 'designation_id' ));
    }

    public function viewEmployeesBonusReport(Request $request){

        $month_year = explode('-',$request->bonus_month_year);
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($request->show_all != 'checked'){
            $bonus = DB::Table('bonus_issue')
                ->join('employee','bonus_issue.emp_id','=','employee.emp_id')
                ->select('bonus_issue.bonus_amount','employee.emp_name','employee.emp_id')
                ->where('bonus_issue.bonus_year',$month_year[0])
                ->where('bonus_issue.bonus_month',$month_year[1])
                ->where('bonus_issue.bonus_status',1)
                ->where('bonus_issue.status',1)

                ->where('employee.emp_department_id',$request->department_id);
            if($request->sub_department_id):
                $bonus->where('employee.emp_sub_department_id',$request->sub_department_id);
            endif;
        }
        else{
            $bonus = DB::Table('bonus_issue')
                ->join('employee','bonus_issue.emp_id','=','employee.emp_id')
                ->select('bonus_issue.bonus_amount','employee.emp_name','employee.emp_id')
                ->where('bonus_issue.bonus_year',$month_year[0])
                ->where('bonus_issue.bonus_month',$month_year[1])
                ->where('bonus_issue.bonus_status',1)
                ->where('bonus_issue.status',1);
        }
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeesBonusReport',compact('bonus'));
    }

    public function viewEmployeeCnicCopy()
    {
        $array = explode('|', Input::get('id'));
        $emr_no = $array[1];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('id','emp_id','emp_name','cnic_path', 'cnic_name', 'cnic_type')->where([['emp_id','=',$emr_no],['status','=',1],['cnic_path', '!=', null]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeCnicCopy', compact('employee'));
    }

    public function viewEmployeeExperienceDocuments()
    {
        $array = explode('|', Input::get('id'));
        $emr_no = $array[1];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_experience = EmployeeWorkExperience::select('id','emp_id','work_exp_path','work_exp_name', 'work_exp_type')->where([['emp_id','=',$emr_no],['status','=',1],['work_exp_path', '!=', null]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeExperienceDocuments', compact('employee_experience'));
    }
    public function viewEmployeeExperienceDocument()
    {
        $array = explode('|', Input::get('id'));
        $emr_no = $array[1];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_experience = EmployeeWorkExperience::select('id','emp_id','work_exp_path','work_exp_name', 'work_exp_type')->where([['emp_id','=',$emr_no],['id','=',$array[0]],['status','=',1],['work_exp_path', '!=', null]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeExperienceDocuments', compact('employee_experience'));
    }

    public function checkCnicNoExist()
    {

        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $employee_count = Employee::where([['emp_cnic','=',Input::get('emp_cnic')],['status','=','1']])->count();

        CommonHelper::reconnectMasterDatabase();

//        if($employee_count > 0 ):
//            echo "CNIC No. ".Input::get('emp_cnic')." Already Exist !";
//        else:
//            echo "success";
//        endif;
        echo "success";

    }

    public function viewMasterTableForm()
    {
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewMasterTableForm', compact('departments'));
    }

    public function viewDayWiseAttendence()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::where([['status','=', 1]])->select('id', 'emp_id', 'emp_name')
            ->whereIn('region_id',$regions)
            ->orderBy('id','desc')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewDayWiseAttendence', compact('employees'));
    }

    public function viewMonthWiseAttendence()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])
            ->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.AjaxPages.viewMonthWiseAttendence', compact('employee_category', 'employee_regions'));
    }

    public function viewUploadFileAttendance()
    {
        return view('Hr.AjaxPages.viewUploadFileAttendance', compact('employees'));
    }

    public function viewEmployeeEobiCopy()
    {
        $array = explode('|', Input::get('id'));
        $emr_no = $array[1];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('id','emp_id','emp_name','eobi_path', 'eobi_type')->where([['emp_id','=',$emr_no],['status','!=', 2],['eobi_path', '!=', null]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeEobiCopy', compact('employee'));
    }

    public function viewEmployeeInsuranceCopy()
    {
        $array = explode('|', Input::get('id'));
        $emr_no = $array[1];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('id','emp_id','emp_name','insurance_path', 'insurance_type')->where([['emp_id','=',$emr_no],['status','!=', 2],['insurance_path', '!=', null]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeInsuranceCopy', compact('employee'));
    }

    public function viewEmployeeEobiDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_missing_eobi= Employee::select('emp_name','emp_id','eobi_path')->where([['status','=',1],['eobi_path','=',null]]);
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeEobiDetail',compact('employee_missing_eobi'));
    }

    public function viewEmployeeInsuranceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_missing_insurance= Employee::select('emp_name','emp_id','insurance_path')->where([['status','=',1],['insurance_path','=',null]]);
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeInsuranceDetail',compact('employee_missing_insurance'));
    }
    public function viewEmployeeProbationExpireDetail(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $date =date("Y-m-d");
        $employeesProbationExpires=Employee::select('id','emp_id','emp_joining_date','emp_name','probation_expire_date','emp_employementstatus_id')->where([['probation_expire_date','<=',$date],['status','=',1],['emp_employementstatus_id','!=',7]])->get();


        return view('Hr.AjaxPages.viewEmployeeProbationExpireDetail',compact('employeesProbationExpires'));
    }
    public function viewEmployeeSettlementDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $settlementDate =date('Y-m-d',strtotime(now() .'+2 days'));
        $settlementTermination1= HrTerminationFormat1Letter::where([['status','=',1],['settlement_date','<',$settlementDate],['settlement_date','>',date('Y-m-d')],['settlement_date','!=','']]);
        $settlementTermination2= HrTerminationFormat2Letter::where([['status','=',1],['settlement_date','<',$settlementDate],['settlement_date','>',date('Y-m-d')],['settlement_date','!=','']]);
        $settlementContract= HrContractConclusionLetter::where([['status','=',1],['settlement_date','<',$settlementDate],['settlement_date','>',date('Y-m-d')],['settlement_date','!=','']]);

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewEmployeeSettlementDetail',compact('settlementTermination1','settlementTermination2','settlementContract'));
    }

    public function viewEmployeeMedicalDocuments()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_medical = EmployeeMedical::select('emp_id')->where([['id','=',Input::get('id')],['status','=',1]])->first();
        $employeeMedicalDocuments = EmployeeMedicalDocuments::where([['emp_id','=',$employee_medical->emr_no],['status','=',1]]);
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeMedicalDocuments', compact('employee_medical','employeeMedicalDocuments'));
    }

    public function getMoreEmployeesDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::where([['status', '!=', '2'],['status','=',1],['emp_id','>',Input::get('lastId')]])
            ->select('id','emp_sub_department_id','emp_id','emp_name','emp_salary','emp_contact_no','emp_joining_date', 'emp_cnic','emp_date_of_birth','status','emp_department_id')
            ->offset(0)
            ->limit(50)
            ->orderBy('emp_id','asc')->get();

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.getMoreEmployeesDetail', compact('employees'));

    }

    public function viewTrainingDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $trainingsData = Trainings::where([['status','=',1],['id', Input::get('id')]])->first();
        $TrainingCertificate = TrainingCertificate::where([['status','=',1],['training_id', Input::get('id')]])->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewTrainingDetail', compact('employee_regions','employee_category','employee_locations', 'trainingsData','employee','TrainingCertificate'));

    }

    public function viewFinalSettlement()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loan_amount = '';
        $gratuityAmount = '';
        $allowance_amount = '';
        $previous_loan_amount = '';
        $emp_id = Input::get('emp_id');

        if(EmployeeExit::where([['emp_id', '=', $emp_id]])->exists()):

            $employee = Employee::where([['status', '!=', 2], ['emp_id', '=', $emp_id]])->select('designation_id','emp_joining_date', 'emp_salary','employee_project_id')->first();
            $designation_id = $employee->designation_id;
            $salary = $employee->emp_salary;

            $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',Input::get('emp_id')],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
            if($employeeCurrentPositions->count() > 0):
                $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
                $designation_id = $employeeCurrentPositionsDetail->designation_id;
                $salary = $employeeCurrentPositionsDetail->salary;
            endif;

            //multiple loan
            $loan = LoanRequest::where([['emp_id', '=', $emp_id],['approval_status', '=', 2],['loan_status', '=', 0],['status', '=', 1]])->orderBy('id', 'desc');
            if($loan->count() > 0):
                foreach($loan->get() as $val):
                    if(Payroll::where([['emp_id', '=', $emp_id],['loan_id', '=', $val->id]])->exists()):
                        $payroll_deducted_amount = Payroll::where([['emp_id', '=', $emp_id],['loan_id', '=', $val->id]])->sum('loan_amount_paid');
                        if($payroll_deducted_amount < $val->loan_amount):
                            $loan_amount += $val->loan_amount - $payroll_deducted_amount;
                        endif;
                    else:
                        $loan_data = LoanRequest::select('loan_amount')->where([['id', '=', $val->id]])->first();
                        $loan_amount += $loan_data->loan_amount;
                    endif;
                endforeach;
            endif;

            $gratuity = Gratuity::where([['emp_id', Input::get('emp_id')]])->orderBy('id','desc');
            if($gratuity->exists()):
                $gratuityDetails = $gratuity->first();
                $gratuityAmount = $gratuityDetails->gratuity;
            endif;
            $exit_data = EmployeeExit::where([['status', '=', 1], ['emp_id', '=', $emp_id]])->select('leaving_type', 'last_working_date')->first();
            $final_settlement = FinalSettlement::where([['status', '=', 1], ['emp_id', '=', $emp_id]]);
            CommonHelper::reconnectMasterDatabase();
            $count = $final_settlement->count();
            $final_settlement_data = $final_settlement->first();
            return view('Hr.AjaxPages.viewFinalSettlement', compact('employee','gratuityAmount', 'allowance_amount','exit_data', 'final_settlement_data', 'count', 'salary','loan_amount','previous_loan_amount', 'designation_id'));
        else:
            return "<div class='row'>&nbsp</div><div class='text-center' style='color: red; font-size: 18px;'>Create Exit Clearance Form First</div>";
        endif;

    }

    public function viewFinalSettlementDetail()
    {
        $type = Input::get('type');
        $operation_rights2 = CommonHelper::operations_rights_ajax_pages(Input::get('rights_url'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $array = explode('|',Input::get('id'));
        $id = $array[0];
        $emr_no = $array[1];
        $final_settlement = FinalSettlement::where([['status', '=', 1], ['emp_id', '=', $emr_no]])->first();
        $gratuity = Gratuity::where([['emp_id', $emr_no]])->orderBy('id')->first();

        $employee = Employee::where([['status', '!=', 2], ['emp_id', '=', $emr_no]])->select('emp_id','emp_name','emp_sub_department_id','designation_id', 'emp_joining_date', 'emp_salary','employee_category_id','region_id')->first();
        $designation_id = $employee['designation_id'];
        $salary = $employee['emp_salary'];
        $emp_sub_department_id = $employee['emp_sub_department_id'];

        $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=', $emr_no],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail['designation_id'];
            $salary = $employeeCurrentPositionsDetail['salary'];
        endif;

        $exit_data = EmployeeExit::where([['status', '=', 1], ['emp_id', '=', $emr_no]])->select('leaving_type', 'last_working_date')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewFinalSettlementDetail', compact('type','operation_rights2','gratuity','exit_data', 'final_settlement','salary','designation_id', 'employee', 'emp_sub_department_id'));
    }

    public function viewEmployeeGratuityForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if(Input::get('show_All') == "yes"):
            $employee = Employee::where([['status', '!=', 2]])
                ->select('emp_joining_date', 'emp_salary','emp_id','emp_name','region_id','employee_category_id','employee_project_id')->orderBy("emp_id")
                ->get()->toArray();
//            if(Input::get('emr_no') == 'All'):
//                $employee = Employee::where([['region_id','=',Input::get('region_id')],['employee_category_id','=',Input::get('emp_category_id')],
//                    ['status', '!=', 2]])
//                    ->select('emp_joining_date', 'emp_salary','emr_no','emp_name','region_id','employee_category_id')->orderBy("emr_no")
//                    ->get()->toArray();
//            else:
//
//                $employee = Employee::where([['status', '!=', 2], ['emr_no', '=',  Input::get('emr_no')]])
//                    ->select('emp_joining_date', 'emp_salary','emr_no','emp_name','region_id','employee_category_id')->orderBy("emr_no")
//                    ->get()->toArray();
//            endif;

        elseif(Input::get('employee_project_id') !== '0'):
            $employee = Employee::where([['region_id','=',Input::get('region_id')],['employee_category_id','=',Input::get('emp_category_id')],['status', '!=', 2],['employee_project_id','=', Input::get('employee_projest_id')]])->select('emp_joining_date', 'emp_salary','emp_id','emp_name','region_id','employee_category_id','employee_project_id')->orderBy("emp_id")->get()->toArray();
        else:
            $employee = Employee::where([['region_id','=',Input::get('region_id')],['employee_category_id','=',Input::get('emp_category_id')],['status', '!=', 2]])->select('emp_joining_date', 'emp_salary','emp_id','emp_name','region_id','employee_category_id','employee_project_id')->orderBy("emp_id")->get()->toArray();
        endif;
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeGratuityForm', compact('employee'));

    }


    public function viewDashboardDetails()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::where([['status', '!=', '2']])->count();
        $employees_onboard = Employee::where([['status', '=', '1']])->count();
        $employees_exit = EmployeeExit::where([['status', '=', '1'],['approval_status', '=', 2]])->count();

        CommonHelper::reconnectMasterDatabase();
        $projects  = EmployeeProjects::where([['status', '=', '1']])->count();
        $departments =  Department::where([['status', '=', '1']])->count();
        $subDepartments = SubDepartment::where([['status', '=', '1']])->count();
        return compact('employees','employees_onboard','employees_exit','projects','departments','subDepartments');
    }

    public function viewAdvanceSalaryDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $advance_salary = AdvanceSalary::select('*')->where([['id', '=', Input::get('id')]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewAdvanceSalaryDetail',compact('advance_salary'));
    }

    public function viewAllowanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $allowance = Allowance::where([['id','=',Input::get('id')]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewAllowanceDetail',compact('allowance'));
    }

    public function viewDeductionListData()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $status=[1];
        $emp_status = Input::get('emp_status');
        
        if($emp_status=="all"){
            $status=[1,3,4];
        }elseif($emp_status=="active"){
            $status=[1];
        }elseif($emp_status=="exit"){
            $status=[3];
        }
       
        $deduction = DB::table('employee')
            ->join('deduction', 'employee.emp_id', '=', 'deduction.emp_id')
            ->select('deduction.*','employee.employee_project_id','employee.emp_name')
            ->where([['deduction.status', '=', 1]])
            ->whereIn('employee.status',$status)
            ->orderBy('deduction.id','desc')
            ->take(50)
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewDeductionListData',compact('deduction'));
    }

    public function viewDeductionDetail()
    {
        $id=Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $deduction = Deduction::where([['id','=',$id]])->orderBy('id')->first();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewDeductionDetail',compact('deduction'));
    }

    public function viewHolidaysDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $holidays = Holidays::where([['id','=',Input::get('id')]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewHolidaysDetail',compact('holidays'));
    }

    public function viewEmployeePromotionDetailForLog()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeePromotions = EmployeePromotion::where([['id','=',Input::get('id')],['status','=',1]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeePromotionDetailForLog', compact('employeeData','employeePromotions'));

    }

    public function viewEmployeeTransferDetailForLog()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeTransfers = EmployeeTransfer::where([['id','=',Input::get('id')],['status','=',1]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeTransferDetailForLog', compact('employeeData','employeeTransfers'));
    }

    public function viewLeaveApplicationRequestDetailForLog()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $leaveApplication = LeaveApplication::where([['id','=',Input::get('id')]])->first();
        $leaveApplicationData = LeaveApplicationData::where([['leave_application_id','=',Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewLeaveApplicationRequestDetailForLog', compact('leaveApplication','leaveApplicationData'));
    }

    public function employeeGetLeavesBalances()
    {

        if(Input::get('company_id') == 'All'):

            $companiesList = DB::Table('company')->select('id','name')->get()->toArray();
        else:
            $companiesList = DB::Table('company')->select('id','name')->where([['id','=',Input::get('company_id')]])->get()->toArray();

        endif;
        $LeavePolicy = LeavesPolicy::where([['status','=',1]])->get();
        return view('Hr.AjaxPages.employeeGetLeavesBalances',compact('companiesList','LeavePolicy'));
    }

    public function viewRangeWiseLeaveApplicationsRequests()
    {
        $m = Input::get('m');

        $department_id = Input::get('department_id');
        $sub_department_id_1 = Input::get('sub_department_id_1');
        $all_employee =  HrHelper::getAllEmployeeId($m,$department_id,$sub_department_id_1);
        $from_date=Input::get('from_date');
        $to_date=Input::get('to_date');
        $approved=Input::get('approved');
        $rejected=Input::get('rejected');
        $pending=Input::get('pending');
        


        if(Input::get('employee_id') == 'all'):
            $leave_application_request_list = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                ->select('leave_application.*','leave_application_data.from_date','leave_application_data.to_date','leave_application_data.first_second_half_date','leave_application_data.no_of_days')
                ->whereIn('leave_application.emp_id', $all_employee)
                ->where([['leave_application.status','=',1]])
                ->whereBetween('leave_application_data.from_date',[$from_date,$to_date])
                ->orWhere(function($nest) use($all_employee,$from_date,$to_date) {
                    $nest->whereIn('leave_application.emp_id', $all_employee)
                    ->where([['leave_application.status','=',1]])
                    ->whereBetween('leave_application_data.first_second_half_date',[$from_date,$to_date]);
                })
                ->orderBy('leave_application_data.date','asc')
                ->get();

                 
        elseif(Input::get('employee_id') != 'all'):

            $leave_application_request_list = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                ->select('leave_application.*','leave_application_data.from_date','leave_application_data.to_date','leave_application_data.first_second_half_date')
                ->where([['leave_application.emp_id','=',Input::get('employee_id')],['leave_application.status','=',1]])
                ->whereBetween('leave_application_data.from_date',[$from_date,$to_date])
                ->orWhere(function($nest) use($from_date,$to_date) {
                    $nest->where('leave_application.emp_id', Input::get('employee_id'))
                    ->where([['leave_application.status','=',1]])
                    ->whereBetween('leave_application_data.first_second_half_date',[$from_date,$to_date]);
                })
                ->orderBy('leave_application_data.date','asc')
                ->get();

        else:
            $leave_application_request_list = DB::table('leave_application')
                ->select('leave_application.*','leave_application_data.from_date','leave_application_data.to_date','leave_application_data.first_second_half_date')
                ->where([['leave_application.emp_id','=',Input::get('employee_id')],['leave_application.status','=',1]])
                ->whereBetween('leave_application_data.from_date',[$from_date,$to_date])
                ->orWhere(function($nest) use($from_date,$to_date) {
                    $nest->where('leave_application.emp_id', Input::get('employee_id'))
                    ->where([['leave_application.status','=',1]])
                    ->whereBetween('leave_application_data.first_second_half_date',[$from_date,$to_date]);
                })
                ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
                ->orderBy('leave_application_data.date','asc')
                ->get();
        endif;

        return view('Hr.AjaxPages.viewRangeWiseLeaveApplicationsRequests', compact('leave_application_request_list','approved','rejected','pending'));
    }

    public function viewTransferLetter(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $id = Input::get('id');
        $transfer_letter = transferLetter::where('emp_location_id','=',$id)->get();
        $EmployeeTransfer = EmployeeTransfer::where('id','=',$id)->first();
        $employee = Employee::where('emp_id','=',$EmployeeTransfer->emr_no)->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewTransferLetter', compact('transfer_letter','employee'));

    }

    public function viewPromotionLetter(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $id = Input::get('id');
        $promotion_letter = PromotionLetter::where('promotion_id','=',$id)->get();
        $EmployeePromotion = EmployeePromotion::where('id','=',$id)->first();
        $employee = Employee::where('emp_id','=',$EmployeePromotion->emp_id)->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewPromotionLetter', compact('promotion_letter','employee'));
    }

    public function viewPreviousEmployeeProject(){
        $emr_no =Input::get('emr_no');
        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection($m);
        $transferEmployeeProject = transferEmployeeProject::where([['status','=',1],['emr_no','=',$emr_no]])->first();
        $check_employee_salary = EmployeePromotion::where([['emr_no','=',Input::get('emr_no')],['status','=',1]])->first();

        if(count($transferEmployeeProject) != '0' && count($check_employee_salary) == '0' ){
            $employee = DB::table('transfer_employee_project')
                ->join('employee','transfer_employee_project.emr_no','=','employee.emr_no')
                ->select('employee.emp_salary','employee.designation_id','employee.grade_id','transfer_employee_project.employee_project_id','transfer_employee_project.date')
                ->where([['transfer_employee_project.emr_no','=',$emr_no],['transfer_employee_project.status','=',1],['employee.status','=',1]])
                ->orderBy('transfer_employee_project.id','desc')
                ->first();
            $salary = $employee->emp_salary;
            $designation_id = $employee->designation_id;
            $grade_id = $employee->grade_id;
            $employee_project = $employee->employee_project_id;
            $date = $employee->date;
        }
        else if(count($transferEmployeeProject) == '0' && count($check_employee_salary) != '0' ){
            $employee = DB::table('employee_promotion')
                ->join('employee','employee_promotion.emr_no','=','employee.emr_no')
                ->select('employee_promotion.salary','employee_promotion.designation_id','employee_promotion.grade_id','employee.employee_project_id','employee_promotion.date')
                ->where('employee.emr_no','=',$emr_no)
                ->where('employee_promotion.status','=',1)
                ->where('employee.status','=',1)
                ->orderBy('employee_promotion.id','desc')
                ->first();
            $salary = $employee->salary;
            $designation_id = $employee->designation_id;
            $grade_id = $employee->grade_id;
            $employee_project = $employee->employee_project_id;
            $date = $employee->date;
        }
        else if(count($transferEmployeeProject) != '0' && count($check_employee_salary) != '0' ){
            $employee = DB::table('employee_promotion')
                ->join('transfer_employee_project','employee_promotion.emr_no','=','transfer_employee_project.emr_no')
                ->select('employee_promotion.salary','employee_promotion.designation_id','employee_promotion.grade_id','transfer_employee_project.employee_project_id','transfer_employee_project.date')
                ->where('transfer_employee_project.emr_no','=',$emr_no)
                ->where('employee_promotion.status','=',1)
                ->where('transfer_employee_project.status','=',1)
                ->orderBy('transfer_employee_project.id','desc')
                ->first();
            $salary = $employee->salary;
            $designation_id = $employee->designation_id;
            $grade_id = $employee->grade_id;
            $employee_project = $employee->employee_project_id;
            $date = $employee->date;
        }
        else{
            $employee = Employee::where([['emr_no','=',$emr_no],['status','=',1]])->first();
            $salary = $employee->emp_salary;
            $designation_id = $employee->designation_id;
            $grade_id = $employee->grade_id;
            $employee_project = $employee->employee_project_id;
            $date = $employee->date;
        }

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewPreviousEmployeeProject', compact('salary','designation_id','grade_id','employee_project','date'));
    }

    public function checkManualLeaves(){
        $value = Input::get('value');
        $leave_type = Input::get('leave_type');
        $m = Input::get('m');
        $emr_no = Input::get('emr_no');
        $error_status = Input::get('error_status');
        CommonHelper::companyDatabaseConnection($m);
        $emp_leave_policy = Employee::where([['emp_id','=',$emr_no],['leaves_policy_id','!=',0]])->first();
        CommonHelper::reconnectMasterDatabase();
        $LeavesData = LeavesData::where([['leaves_policy_id','=',$emp_leave_policy->leaves_policy_id],['leave_type_id','=',$leave_type]])->value('no_of_leaves');
        if($value > $LeavesData){
            echo 'Your'.' '.$error_status.' '.'is greater than your leave policy';
        }
        else{
            echo 'done';
        }

    }

    public function getPendingLeaveApplicationDetail()
    {
        $getPendingLeaveApp = LeaveApplication::select('emp_id','id')->where([[Input::get('type'),'=',1]])->orderBy('id','desc')->offset(0)->limit(1);

        if($getPendingLeaveApp->count() == 0):
            return 0;
        endif;

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp = Employee::select('id','leaves_policy_id','designation_id')->where([['emp_id', '=',$getPendingLeaveApp->value('emp_id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $leave_day_type = Input::get('leave_day_type');

        if(Input::get('leave_day_type') == 1):

            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.emp_id','leave_application.leave_address','leave_application.approval_status','leave_application.approval_status_lm','leave_application.reason','leave_application.status','leave_application_data.no_of_days','leave_application_data.date','leave_application_data.from_date','leave_application_data.to_date')
                ->where([['leave_application.id','=',$getPendingLeaveApp->value('id')]])->first();

            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        elseif(Input::get('leave_day_type') == 2):

            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.emp_id','leave_application.leave_address','leave_application.approval_status','leave_application.approval_status_lm','leave_application.reason','leave_application.status','leave_application_data.first_second_half','leave_application_data.date','leave_application_data.first_second_half_date')
                ->where([['leave_application.id','=',$getPendingLeaveApp->value('id')]])
                ->first();

            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        else:
            $leave_application_data = DB::table('leave_application')
                ->join('leave_application_data', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                ->select('leave_application.emp_id','leave_application.approval_status','leave_application.approval_status_lm','leave_application.leave_address','leave_application.reason','leave_application.status','leave_application_data.short_leave_time_from','leave_application_data.short_leave_time_to',
                    'leave_application_data.date','leave_application_data.short_leave_date')
                ->where([['leave_application.id','=',$getPendingLeaveApp->value('id')]])->first();

            $leave_day_type_arr = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
            $leave_day_type_label = $leave_day_type_arr[Input::get('leave_day_type')];

        endif;

        $approval_array[1] = '<span class="label label-warning">Pending</span>';
        $approval_array[2] = '<span class="label label-success">Approved</span>';
        $approval_array[3] = '<span class="label label-danger">Rejected</span>';

        $approval_status = $approval_array[$leave_application_data->approval_status];
        $approval_status_lm = $approval_array[$leave_application_data->approval_status_lm];
        CommonHelper::reconnectMasterDatabase();
        $leaves_policy = DB::table('leaves_policy')
            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*','leaves_data.*')
            ->where([['leaves_policy.id','=',$emp->leaves_policy_id]])
            ->get();


        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $emp_data = Employee::select('emp_name', 'emp_sub_department_id', 'designation_id', 'emp_id','leaves_policy_id')->where([['id', '=', $emp->id]])->orderBy('id')->first();

        CommonHelper::reconnectMasterDatabase();

        $designation_name = Designation::where([['id','=',$emp->designation_id]])->value('designation_name');
        $data['designation_name']       = $designation_name;
        $data['leave_day_type']         = $leave_day_type;
        $data['leave_application_data'] = $leave_application_data;
        $data['approval_status']        = $approval_status;
        $data['approval_status_lm']      = $approval_status_lm;
        $data['emp_data']               = $emp_data;
        $data['leave_type_name']        = Input::get('leave_type_name');
        $data['leave_day_type_label']   = $leave_day_type_label;
        $data['leaves_policy']          = $leaves_policy;
        $data['leave_application_id']   =  $getPendingLeaveApp->value('id');

        return view('Hr.AjaxPages.getPendingLeaveApplicationDetail')->with($data);
    }

    public function viewProjectLetter(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $id = Input::get('id');
        $project_letter = projectTransferLetter::where([['emp_project_id','=',$id],['status','=',1]])->get();
        $TransferEmployeeProject = TransferEmployeeProject::where('id','=',$id)->first();
        $employee = Employee::where('emr_no','=',$TransferEmployeeProject->emr_no)->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewProjectLetter', compact('project_letter','employee'));
    }
    public function viewManualAttendanceForm()
    {
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.AjaxPages.viewManualAttendanceForm',compact('SubDepartment', 'Department'));
    }
    public function viewEmployeeManualAttendance()
    {
        $department_id = Input::get('department_id');
        $sub_department_id = Input::get('sub_department_id');

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_id = Input::get('emp_id');

        $all_emp_id = HrHelper::getAllEmpId($department_id,$sub_department_id,Input::get('m'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if($emp_id == 'all'){
            $emp_data = Employee::select('emp_id','day_off','emp_name')->whereIn('emp_id',$all_emp_id)->where('working_hours_policy_id','!=',0)->get();
        }
        else{
            $emp_data = Employee::select('emp_id','day_off','emp_name')->where([['emp_id', '=', Input::get('emp_id')],['working_hours_policy_id','!=',0]])->get();
        }
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEmployeeManualAttendance',compact('getData','from_date','to_date','filter_value','emp_data','filter_value'));

    }

    public function viewUploadAttendanceFileForm()
    {
        return view('Hr.AjaxPages.viewUploadAttendanceFileForm');
    }

    public  function viewLeaveApplicationDateWise()
    {

        $data = Input::get('id');
        $dataFilter = explode(',',$data);

        $emp_id = $dataFilter[0];
        $from_date = $dataFilter[1];
        $to_date =  $dataFilter[2];
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $attendance2 = DB::table('attendance')->where([['attendance.emp_id','=',$emp_id]])
            ->whereBetween('attendance_date',[$from_date,$to_date])
            ->get();
        //print_r($attendance2);
        $totalOffDates[] = '';

        $day_off_emp = Employee::select('day_off')->where([['emp_id','=',$emp_id]])->value('day_off');
        // $total_days_off = Attendance::select('attendance_date')->where([['day','=',$day_off_emp],['emp_id','=',$emp_id]]);
        $day_off_emp =  explode('=>',$day_off_emp);

        $total_days_off = Attendance::select('attendance_date')
            ->whereBetween('attendance_date',[$from_date,$to_date])
            ->where([['emp_id','=',$emp_id]])
            ->wherein('day',[$day_off_emp[1],$day_off_emp[0]]);

        if($total_days_off->count() > 0):

            foreach($total_days_off->get()->toArray() as $offDates):
                $totalOffDates[] = $offDates['attendance_date'];
            endforeach;

        else:
            $totalOffDates =array();
        endif;

        $monthly_holidays[] = '';
        $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$from_date,$to_date])->where([['status','=',1]]);
        if($get_holidays->count() > 0):
            foreach($get_holidays->get() as $value2):

                $monthly_holidays[]=$value2['holiday_date'];
            endforeach;

        else:
            $monthly_holidays =array();
        endif;
        $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);
        $dates = array();
        foreach ($attendance2 as $value) {
            CommonHelper::reconnectMasterDatabase();

            $LikeDate = "'".'%'.$value->year."-".$value->month.'%'."'";

            $leave_application_request_list = DB::select('select leave_application.*,leave_application_data.* from leave_application
                            INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id
                            WHERE leave_application_data.from_date LIKE '.$LikeDate.' AND leave_application_data.emp_id = '.$value->emp_id.' AND leave_application.status = 1 AND leave_application.view = "yes"
                            OR leave_application_data.first_second_half_date LIKE '.$LikeDate.' and leave_application_data.emp_id = '.$value->emp_id.'');

            //   CommonHelper::reconnectMasterDatabase();

            $leaves_from_dates2 = [];
            if(!empty($leave_application_request_list)):
                foreach($leave_application_request_list as $value3):
                    $leaves_from_dates = $value3->from_date;
                    $leaves_to_dates = $value3->to_date;
                    $leaves_type=$value3->leave_type;
                    $leaves_from_dates2[] = $value3->from_date;

                    $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates. '+1 day'));


                    foreach ($period as $date) {
                        $dates[] = $date->format("Y-m-d");
                    }

                endforeach;

            endif;
        }

        $monthly_holidays = array_merge($monthly_holidays,$dates);
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewLeaveApplicationDateWise',compact('leave_application_request_list'));
    }

    public function  viewEarlyGoingDetail()
    {
        $data = Input::get('id');
        $dataFilter = explode(',',$data);
        $emp_id = $dataFilter[0];
        $month_data = $dataFilter[1];

        $monthDataFilter = explode('/',$month_data);
        // print_r($monthDataFilter);
        //     die;
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $totalOffDates[] = '';
        $emp_data =Employee::select('day_off','working_hours_policy_id')->where([['emp_id','=',$emp_id]]);
        CommonHelper::reconnectMasterDatabase();
        $working_policy_data = WorkingHoursPolicy::where([['id','=',$emp_data->value('working_hours_policy_id')]])->get()->toArray();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $day_off_emp = $emp_data->value('day_off');
        $total_days_off = Attendance::select('attendance_date')->where([['day','=',$day_off_emp],['emp_id','=',$emp_id]]);

        if($total_days_off->count() > 0):

            foreach($total_days_off->get()->toArray() as $offDates):
                $totalOffDates[] = $offDates['attendance_date'];
            endforeach;

        else:
            $totalOffDates =array();
        endif;
        $monthly_holidays[] ='';
        $get_holidays = Holidays::select('holiday_date')->where([['status','=',1]])->whereBetween('holiday_date',[$monthDataFilter[1],$monthDataFilter[2]]);
        if($get_holidays->count() > 0) {
            foreach ($get_holidays->get() as $value2) {
                $monthly_holidays[] = $value2['holiday_date'];
            }
        }
        else{
            $monthly_holidays =array();
        }
        $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);
        $endWorkTime = strtotime($working_policy_data[0]['end_working_hours_time']);
        $dutyEndTime2 = date('H:i', $endWorkTime);

        $ealryGoingData = Attendance::where([['emp_id','=',$emp_id],['clock_out','<', $dutyEndTime2],['clock_in','!=',''],['clock_out','!=','']])
            ->whereNotIn('attendance_date', $monthly_holidays)
            ->whereBetween('attendance_date',[$monthDataFilter[1],$monthDataFilter[2]])
            ->get();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewEarlyGoingDetail',compact('ealryGoingData','dutyEndTime2'));
    }

    function  attendanceProgressFilteredList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_year = explode('-',Input::get('month_year'));
        $accType = Input::get('accType');
        $acc_emp_id = Input::get('acc_emp_id');
        if($accType == 'user'){
            $attendanceProgress = DB::table('payroll_data')
                ->join('employee', 'employee.emp_id', '=', 'payroll_data.emp_id')
                ->select('employee.emp_sub_department_id', 'payroll_data.*')
                ->where([['payroll_data.status', '=', '1'],['payroll_data.month', '=', $month_year[1]],['employee.emp_id',$acc_emp_id],
                    ['payroll_data.year', '=', $month_year[0]]])
                ->orderBy('emp_sub_department_id', 'asc')
                ->orderBy('emp_id', 'asc')
                ->orderBy('month', 'desc')
                ->orderBy('year', 'desc');

        }
        else{
            $attendanceProgress = DB::table('payroll_data')
                ->join('employee', 'employee.emp_id', '=', 'payroll_data.emp_id')
                ->select('employee.emp_sub_department_id', 'payroll_data.*')
                ->where([['payroll_data.status', '=', '1'],['payroll_data.month', '=', $month_year[1]],
                    ['payroll_data.year', '=', $month_year[0]]])
                ->orderBy('emp_sub_department_id', 'asc')
                ->orderBy('emp_id', 'asc')
                ->orderBy('month', 'desc')
                ->orderBy('year', 'desc');
        }


        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.viewAttendanceProgressFilteredList',compact('attendanceProgress','month_year'));

    }

    public function viewPendingRequests()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $pomotion = EmployeePromotion::where([['status', '=', '1'],['approval_status', '=', 1]])->count();
        CommonHelper::reconnectMasterDatabase();
        $leaves = LeaveApplication::where([['status', '=', '1'],['approval_status', '=', 1]])->count();
        return view('Hr.AjaxPages.viewPendingRequests',compact('loan', 'advance_salary','pomotion', 'transfer', 'exit_clearance','id_card','leaves'));
    }

    public function viewPoliciesDetail()
    {
        $policies = Policies::where([['id', '=', Input::get('id')]]);
        return view('Hr.AjaxPages.viewPoliciesDetail',compact('policies'));
    }

    public function viewProvidentFundReport()
    {
        if(Input::get('company_id') == 'All'):

            $companiesList = DB::Table('company')->select('id','name')->where([['status','=',1]])->orderBy('order_by_no','asc')->get()->toArray();
        else:
            $companiesList = DB::Table('company')->select('id','name')->where([['status','=',1],['id','=',Input::get('company_id')]])->get()->toArray();

        endif;

        return view('Hr.AjaxPages.viewProvidentFundReport',compact('companiesList'));


    }

    public function viewEmployeeProvidentFundReport()
    {

        $companiesList = DB::Table('company')->select('id','name')->where([['status','=',1],['id','=',Input::get('m')]])->get()->toArray();
        $SubDepartment = DB::Table('sub_department')->select('id','sub_department_name')->where([['id','=', Input::get('sub_department_id')]])->first();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $Employees = Employee::select('emp_name','emp_id','provident_fund_id')->where([['acc_no','=',Input::get('employee_id')]])->first();
        CommonHelper::reconnectMasterDatabase();

//        print_r($Employees);
//        die();
        return view('Hr.AjaxPages.viewEmployeeProvidentFundReport',compact('companiesList','SubDepartment','Employees'));

    }

    public function disburseProvidentFundForm()
    {

        $acc_no_and_name = explode('|',Input::get('id'));

        CommonHelper::companyDatabaseConnection($acc_no_and_name[3]);
        $total_pf_amount = DB::table("provident_fund_data")
            ->select(DB::raw("SUM(pf_amount) as pf_amount"))
            ->where([['emp_id','=',$acc_no_and_name[0]],['amount_type','=','plus']])
            ->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.disburseProvidentFundForm',compact('total_pf_amount','acc_no_and_name'));

    }

    public function approveAndRejectLeaveApplication2(){
        $data1['approval_status_lm'] = Input::get('approval_status_lm');
        $update_approval = DB::table('leave_application')->where('id', Input::get('recordId'))->update($data1);

    }

    public function approveAndRejectLeaveApplication(){

        $data1['approval_status'] = Input::get('approval_status');
        $update_approval = DB::table('leave_application')->where('id', Input::get('recordId'))->update($data1);

    }

    public function deleteLeavesDataPolicyRows(){

        $updateDetails=array(
            'status' => 2,
            'username' => Auth::user()->name
        );
        DB::table('leaves_policy')
            ->where('id', Input::get('recordId'))
            ->update($updateDetails);

        DB::table('leaves_data')
            ->where('leaves_policy_id', Input::get('recordId'))
            ->update($updateDetails);
    }

    public function viewHolidayDetails(){
        $id = Input::get('id');
        $explode_data = explode("_",$id);
        $emp_id = $explode_data[0];
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $day_off_emp = Employee::select('day_off')->where([['emp_id','=',$explode_data[0]]])->value('day_off');


        $day_off_emp =  explode('=>',$day_off_emp);

        $total_days_off = Attendance::select('attendance_date')
            ->whereBetween('attendance_date',[$explode_data[1],$explode_data[2]])
            ->where('emp_id','=',$explode_data[0])
            ->whereIn('day',[$day_off_emp[1],$day_off_emp[0]])
            ->orderBy('attendance_date','asc')
            ->get()
            ->toArray();

        $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$explode_data[1],$explode_data[2]])->where([['status','=',1]]);

        $totalHolidays = $get_holidays->get()->toArray();
        $monthly_holidays = array_merge($totalHolidays,$total_days_off);
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AjaxPages.viewHolidayDetails',compact('monthly_holidays','emp_id'));
    }
}

?>