<?php
namespace App\Http\Controllers;


use App\Http\Requests;
use App\Helpers\CommonHelper;
use App\Models\Arrears;
use App\Models\Role;
use App\Models\Attendance;
use App\Models\Cities;
use App\Models\Deduction;
use App\Models\Diseases;
use App\Models\EmployeeGsspDocuments;
use App\Models\FinalSettlement;
use App\Models\States;
use App\User;
use Hamcrest\Core\AllOf;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Attendence;
use App\Models\Designation;
use App\Models\HealthInsurance;
use App\Models\JobType;
use App\Models\Countries;
use App\Models\Institute;
use App\Models\Qualification;
use App\Models\LeaveType;
use App\Models\LoanType;
use App\Models\AdvanceType;
use App\Models\ShiftType;
use App\Models\MaritalStatus;
use App\Models\RequestHiring;
use App\Models\Job;
use App\Models\AdvanceSalary;
use App\Models\LeavesPolicy;
use App\Models\LeavesData;
use App\Models\VehicleType;
use App\Models\CarPolicy;
use App\Models\LoanRequest;
use App\Models\Eobi;
use App\Models\Tax;
use App\Models\Bonus;
use App\Models\LeaveApplication;
use App\Models\LeaveApplicationData;
use App\Models\DegreeType;
use App\Models\WorkingHoursPolicy;
use App\Models\Holidays;
use App\Models\EmployeeDeposit;
use App\Models\Regions;
use App\Models\Locations;
use App\Models\Grades;
use App\Models\EmployeeExit;
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
use App\Models\EmployeeCardRequest;
use App\Models\EmployeeProjects;
use App\Models\EmployeeDocuments;
use App\Models\EmployeePromotion;
use App\Models\EmployeeFuelData;
use App\Models\EmployeeEquipments;
use App\Models\Equipments;
use App\Models\EmployeeMedical;
use App\Models\EmployeeTransfer;
use App\Models\LetterFiles;
use App\Models\Trainings;
use App\Models\Gratuity;
use App\Models\TrainingCertificate;
use App\Models\TransferEmployeeProject;
use App\Models\EmployeeOfTheMonth;
use App\Models\Policies;


use Input;
use Auth;
use DB;
use Config;
use Hash;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Pagination\LengthAwarePaginator;
class HrController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'createEmployeeFormDraft'
        ]]);

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function toDayActivity(){
        return view('Hr.toDayActivity');
    }

    public  function  viewEmployeeExitClearanceData(){
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
        return view('Hr.ExitClearance.viewEmployeeExitClearanceData',compact('exit_employee_data','employee','emp_department_id','emp_sub_department_id','designation_id','exit_employee_data','designation','location','operation_rights2','type'));
    }

    public function departmentAddNView(){
        return view('Hr.departmentAddNView');
    }

    public function createDepartmentForm(){
        return view('Hr.Departments.createDepartmentForm');
    }

    public function viewDepartmentList(){

        $departments = Department::where([['company_id','=',Input::get('m')],['status', '=', 1]])->get();
        return view('Hr.Departments.viewDepartmentList',compact('departments'));
    }

    public function editDepartmentForm(){
        return view('Hr.Departments.editDepartmentForm');
    }

    public function createSubDepartmentForm(){

        $departments = Department::where('company_id','=',$_GET['m'])->where('status','=','1')->orderBy('id')->get();
        return view('Hr.SubDepartments.createSubDepartmentForm',compact('departments'));
    }

    public function viewSubDepartmentList(){

        $SubDepartments = SubDepartment::where([['company_id','=',Input::get('m')],['status','=', 1]])->orderBy('id')->get();
        return view('Hr.SubDepartments.viewSubDepartmentList', compact('SubDepartments'));
    }

    public function editSubDepartmentForm(){

        $departments = Department::where([['company_id','=',Input::get('m')],['status','=', 1]])->orderBy('id')->get();
        return view('Hr.SubDepartments.editSubDepartmentForm',compact('departments'));
    }

    public function createDesignationForm(){
        return view('Hr.Designations.createDesignationForm');
    }

    public function viewDesignationList(){

        $designations = Designation::where([['company_id','=',Input::get('m')],['status','=', 1]])->get();
        return view('Hr.Designations.viewDesignationList', compact('designations'));
    }

    public function editDesignationForm(){
        return view('Hr.Designations.editDesignationForm');
    }

    public function createHealthInsuranceForm(){
        return view('Hr.createHealthInsuranceForm');
    }

    public function viewHealthInsuranceList(){

        $HealthInsurances = HealthInsurance::where([['company_id','=',Input::get('m')]])->get();
        return view('Hr.viewHealthInsuranceList', compact('HealthInsurances'));
    }

    public function editHealthInsuranceForm(){
        return view('Hr.editHealthInsuranceForm');
    }


    public function editEmployeeCategoryDetailForm(){
        return view('Hr.editEmployeeCategoryDetailForm');
    }

    public function createJobTypeForm(){
        return view('Hr.JobType.createJobTypeForm');
    }

    public function viewJobTypeList()
    {
        $JobTypes = JobType::where([['company_id','=',Input::get('m')],['status','=', 1]])->get();
        return view('Hr.JobType.viewJobTypeList',compact('JobTypes'));
    }

    public function editJobTypeForm(){
        return view('Hr.JobType.editJobTypeForm');
    }

    public function createQualificationForm(){

        $countries = Countries::where('status', '=', 1)->get();
        $institutes = Institute::where('status', '=', 1)->get();

        return view('Hr.Qualifications.createQualificationForm',compact('countries','institutes'));
    }

    public function viewQualificationList()
    {
        $Qualifications = Qualification::where([['company_id','=',Input::get('m')],['status','=', 1]])->get();
        return view('Hr.Qualifications.viewQualificationList',compact('Qualifications'));
    }

    public function editQualificationForm(){
        $qualificationDetail = DB::selectOne('select * from `qualification` where `id` = '.Input::get('id').'');
        $countries = Countries::where('status', '=', 1)->get();
        $states = States::where([['status', '=', 1],['country_id', '=', $qualificationDetail->country_id]])->get();
        $cities = Cities::where([['status', '=', 1],['state_id', '=', $qualificationDetail->state_id]])->get();
        $institutes = Institute::where('status', '=', 1)->get();
        return view('Hr.Qualifications.editQualificationForm',compact('states','cities','qualificationDetail','countries','institutes'));
    }

    public function createLeaveTypeForm(){
        return view('Hr.LeaveTypes.createLeaveTypeForm');
    }

    public function viewLeaveTypeList()
    {
        $LeaveTypes = LeaveType::where([['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.LeaveTypes.viewLeaveTypeList', compact('LeaveTypes'));
    }

    public function editLeaveTypeForm(){
        return view('Hr.LeaveTypes.editLeaveTypeForm');
    }

    public function createLoanTypeForm(){

        return view('Hr.LoanTypes.createLoanTypeForm');
    }

    public function viewLoanTypeList()
    {
        $LoanTypes = LoanType::where([['status', '=', '1'],['company_id', '=', Input::get('m')]])->orderBy('id')->get();
        return view('Hr.LoanTypes.viewLoanTypeList', compact('LoanTypes'));
    }

    public function editLoanTypeForm(){
        return view('Hr.LoanTypes.editLoanTypeForm');
    }

    public function createAdvanceTypeForm(){
        return view('Hr.createAdvanceTypeForm');
    }

    public function viewAdvanceTypeList(){

        $AdvanceTypes = AdvanceType::where([['status','=', 1],['company_id', '=', Input::get('m')]])->get();
        return view('Hr.viewAdvanceTypeList', compact('AdvanceTypes'));
    }

    public function editAdvanceTypeForm(){
        return view('Hr.editAdvanceTypeForm');
    }

    public function createShiftTypeForm(){
        return view('Hr.createShiftTypeForm');
    }

    public function editShiftTypeForm(){
        return view('Hr.editShiftTypeForm');
    }

    public function createHiringRequestAddForm(){

        $departments = Department::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
        $JobTypes = JobType::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
        $Designations = Designation::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
        $Qualifications = Qualification::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
        $ShiftTypes = ShiftType::where('status','=','1')->where('company_id','=',$_GET['m'])->orderBy('id')->get();
        return view('Hr.createHiringRequestAddForm',compact('departments','JobTypes','Designations','Qualifications','ShiftTypes'));
    }

    public function viewHiringRequestList(){

        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection($m);
        $RequestHiring = RequestHiring::all()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewHiringRequestList', ['RequestHiring' => $RequestHiring]);
    }

    public function editHiringRequestForm(){

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $hiringRequestDetail = RequestHiring::where([['id','=',Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $departments = Department::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $JobTypes = JobType::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $Designations = Designation::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $Qualifications = Qualification::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $ShiftTypes = ShiftType::where([['status','=','1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();

        return view('Hr.editHiringRequestForm',compact('hiringRequestDetail','departments','JobTypes','Designations','Qualifications','ShiftTypes'));
    }

    public function createEmployeeFormDraft(){


        $leaves_policy = LeavesPolicy::where([['status','=','1']])->get();
        $jobtype = JobType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $marital_status = MaritalStatus::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $qualification = Qualification::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $eobi = Eobi::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $tax= Tax::select('id','tax_name')->where([['status', '=', '1']])->orderBy('id')->get();
        $DegreeType = DegreeType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $working_policy = WorkingHoursPolicy::where([['status', '=', '1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $employee_projects = EmployeeProjects::where([['status', '=', '1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $sub_department = SubDepartment::where([['status', '=', '1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $provident_fund = DB::table('provident_fund')->where([['status','=','1'],['company_id', '=', Input::get('m')]])->get();
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();

        $MenuPrivileges = Role::where([['status','=',1]])->orderBy('id')->get();

        return view('Hr.Employees.createEmployeeFormDraft',compact('DegreeType','tax','eobi','designation','qualification','leaves_policy','departments','subdepartments','jobtype','marital_status', 'employee_regions', 'employee_grades', 'employee_locations', 'employee_category', 'employee_projects','working_policy','sub_department','provident_fund','Department','MenuPrivileges'));

    }
    public function createEmployeeForm(){

      
        $leaves_policy = LeavesPolicy::where([['status','=','1']])->get();
        $jobtype = JobType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $marital_status = MaritalStatus::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $qualification = Qualification::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $eobi = Eobi::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $tax= Tax::select('id','tax_name')->where([['status', '=', '1']])->orderBy('id')->get();
        $DegreeType = DegreeType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $working_policy = WorkingHoursPolicy::where([['status', '=', '1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $employee_projects = EmployeeProjects::where([['status', '=', '1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $sub_department = SubDepartment::where([['status', '=', '1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $provident_fund = DB::table('provident_fund')->where([['status','=','1'],['company_id', '=', Input::get('m')]])->get();
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();

        $MenuPrivileges = Role::where([['status','=',1]])->orderBy('id')->get();

        return view('Hr.Employees.createEmployeeForm',compact('DegreeType','tax','eobi','designation','qualification','leaves_policy','departments','subdepartments','jobtype','marital_status', 'employee_regions', 'employee_grades', 'employee_locations', 'employee_category', 'employee_projects','working_policy','sub_department','provident_fund','Department','MenuPrivileges'));

    }
    public function editUserAccountDetailForm(){
        $id=Input::get('id');
        $user_account_detail = User::where([['id','=',$id],['status','!=', '2']])->first();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_role = Employee::where([['emp_id','=',$user_account_detail['emp_id']],['status','=', '1']])->first();
        CommonHelper::reconnectMasterDatabase(Input::get('m'));
        $roles=Role::where('status','=','1')->get();

        $MenuPrivileges = Role::where([['status','=',1]])->orderBy('id')->get();
        return view('Hr.Employees.editUserAccountDetailForm',compact('user_account_detail','roles','emp_role','MenuPrivileges'));
    }

    public function editEmployeeDetailForm($id, $CompanyId)
    {
 

        
        $login_credentials ='';
        $subdepartments = new SubDepartment;
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $leaves_policy = LeavesPolicy::where([['status','=','1']])->get();
        $jobtype = JobType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employee_projects = EmployeeProjects::where([['status', '=', '1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $departments  = SubDepartment::where([['status', '=', '1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $provident_fund = DB::table('provident_fund')->where([['status','=','1'],['company_id', '=', Input::get('m')]])->get();

        $marital_status = MaritalStatus::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $qualification = Qualification::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $eobi = Eobi::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $tax= Tax::select('id','tax_name')->where([['status', '=', '1']])->orderBy('id')->get();
        $DegreeType = DegreeType::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $working_policy = WorkingHoursPolicy::where([['status', '=', '1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();

        CommonHelper::companyDatabaseConnection($CompanyId);
        $employee_detail = Employee::where([['id','=',$id]])->first();
       
        $emp_id = $employee_detail->emp_id;
        $employee_family_detail = EmployeeFamilyData::where([['emp_id','=',$emp_id]]);
        $employee_bank_detail = EmployeeBankData::where([['emp_id','=',$emp_id]]);
        $employee_educational_detail = EmployeeEducationalData::where([['emp_id','=',$emp_id]]);
        $employee_language_proficiency = EmployeeLanguageProficiency::where([['emp_id','=',$emp_id]]);
        $employee_health_data = EmployeeHealthData::where([['emp_id','=',$emp_id]]);
        $employee_activity_data = EmployeeActivityData::where([['emp_id','=',$emp_id]]);
        $employee_work_experience = EmployeeWorkExperience::where([['emp_id','=',$emp_id]]);
        $employee_reference_data = EmployeeReferenceData::where([['emp_id','=',$emp_id]]);
        $employee_kins_data = EmployeeKinsData::where([['emp_id','=',$emp_id]]);
        $employee_relatives_data = EmployeeRelativesData::where([['emp_id','=',$emp_id]]);
        $employee_other_details = EmployeeOtherDetails::where([['emp_id','=',$emp_id]]);
        $employee_documents = EmployeeDocuments::where([['emp_id', '=', $emp_id], ['status','=', 1]]);
        $employee_gssp_documents = EmployeeGsspDocuments::where([['emp_id', '=', $emp_id], ['status','=', 1]]);
        $employee_cnic_copy = Employee::where([['emp_id','=',$emp_id],['status','=',1],['cnic_path', '!=', null]]);
        $employee_eobi_copy = Employee::where([['emp_id','=',$emp_id],['status','=',1],['eobi_path', '!=', null]]);
        $employee_insurance_copy = Employee::where([['emp_id','=',$emp_id],['status','=',1],['insurance_path', '!=', null]]);
        $employee_work_experience_doc = EmployeeWorkExperience::where([['emp_id','=',$emp_id],['status','=',1],['work_exp_path', '!=', null]]);



        CommonHelper::reconnectMasterDatabase();

        if($employee_detail->can_login == 'yes'):

            $login_credentials = DB::Table('users')->select('acc_type')->where([['company_id', '=', Input::get('m')],['emp_id', '=', $employee_detail->emp_id]])->first();

            $MenuPrivilegeId = Role::where([['id','=',$employee_detail->role_id],['status','=',1]])->value('id');
        endif;

        $MenuPrivileges = Role::where([['status','=',1]])->orderBy('id')->get();


        return view('Hr.Employees.editEmployeeDetailForm'
            ,compact('login_credentials','employee_other_details', 'employee_eobi_copy', 'employee_insurance_copy','employee_relatives_data','employee_kins_data','employee_reference_data','employee_work_experience','employee_activity_data','employee_health_data','employee_language_proficiency','employee_educational_detail','employee_bank_detail','employee_family_detail','leaves_policy','employee_detail','DegreeType','tax','eobi','designation','qualification','
            leaves_policy','Department','departments','subdepartments','jobtype','marital_status',
                'employee_regions', 'employee_grades', 'employee_locations',
                'employee_category', 'employee_projects', 'employee_documents', 'employee_gssp_documents', 'employee_cnic_copy', 'employee_work_experience_doc','working_policy','departments','provident_fund','MenuPrivileges','MenuPrivilegeId'));
    }

    public function viewEmployeeList(){


     //   if(Auth::user()->acc_type == 'user') {die('Nice Trick @xx ');}
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::where([['status', '!=', '2']])
            ->select('id','emp_department_id','emp_sub_department_id','emp_id','emp_name','emp_salary','designation_id','emp_contact_no', 'emp_joining_date', 'emp_cnic','emp_date_of_birth','status','employee_project_id','reporting_manager')
            ->orderBy('emp_id','asc')->get();
        CommonHelper::reconnectMasterDatabase();
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.Employees.viewEmployeeList',compact('employees', 'SubDepartment','Department'));
    }

    public function AddEmpEmergencyDetail(){

        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();

        return view('Hr.Employees.addEmpEmergencyDetail', compact('SubDepartment','Department'));


    }

    public function viewEmployeeBonusReportForm(){

        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();

        return view('Hr.Bonus.viewEmployeeBonusReportForm',compact('Department','SubDepartment'));
    }
    public function viewBonusBankReportForm(){

       return view('Hr.Bonus.viewBonusBankReportForm');
    }

    public function viewUseAccountsList(){

        CommonHelper::reconnectMasterDatabase(Input::get('m'));
        $users = User::where([['status', '!=', '2']])
            ->select('*')
            ->orderBy('id','asc')->get();

        return view('Hr.Employees.viewUserAccountsList',compact('users'));
    }
    public function uploadEmployeeFileForm()
    {
        return view('Hr.Employees.uploadEmployeeFileForm');

    }

    public function createManageAttendanceForm(){
      return view('Hr.Attendance.createManageAttendanceForm',compact('departments','subdepartments'));
    }

    public function ViewAttendanceProgress()
    {
	    $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.Attendance.ViewAttendanceProgress', compact('SubDepartment','Department'));
    }

    public function viewEmployeeAttendanceList()
	{
       CommonHelper::companyDatabaseConnection(Input::get('m'));
	   
		$employees = Employee::where([['status', '!=', '2']])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        $department_id = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $sub_department_id = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
		$employeeProjects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
	    return view('Hr.Attendance.viewEmployeeAttendanceList',compact('sub_department_id','employees','department_id'));
	}

    public function createPayrollForm()
    {

		$Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.Payroll.createPayrollForm', compact('SubDepartment','Department'));
    }

    public function viewPayrollList(){

       
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();

        return view('Hr.Payroll.viewPayrollList', compact('SubDepartment','Department'));
    }

    public function viewConcileReport()
    {

        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.Payroll.viewConcileReport', compact('Department','SubDepartment'));

    }


    public function viewPayrollReport()
    {

     
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.Payroll.viewPayrollReport', compact('Department','SubDepartment','Employee_projects'));

    }
    
     public function viewBankReportForm()
    {
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.Payroll.viewBankReportForm',compact('Department','SubDepartment'));
    }
    public function companyWisePayrollReport()
    {
        $companies =  DB::table('company')->select('id', 'name')->where([['status','=',1]])->orderBy('order_by_no', 'asc')->get()->toArray();
        return view('Hr.Payroll.companyWisePayrollReport',compact('companies'));
    }

    public function emailPayslips()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::select('emp_id','emp_name','emp_father_name')->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.emailPayslips', compact('employee'));
    }


    public function viewPayslipPdf($m,$month,$year,$emp_id)
    {
        CommonHelper::companyDatabaseConnection($m);
        $payslip_data = DB::table('payslip')
            ->where([['payslip.month','=',$month],['payslip.year','=',$year],['payslip.emp_id','=',$emp_id]])
            ->join('employee', 'employee.emp_id', '=', 'payslip.emp_id')
            ->select('payslip.*','employee.bank_account','employee.emp_father_name','employee.emp_department_id', 'employee.emp_name', 'employee.emp_cnic','employee.emp_joining_date','employee.professional_email', 'employee.designation_id')
            ->get()->toArray();
        CommonHelper::reconnectMasterDatabase();
        $payslip_data =$payslip_data[0];

        return view('Hr.pdf',compact('payslip_data'));
    }


    public function generatePayslip($m,$month,$year,$emp_id)
    {
        CommonHelper::companyDatabaseConnection($m);
        $payslip_data = DB::table('payslip')
            ->where([['payslip.month','=',$month],['payslip.year','=',$year],['payslip.emp_id','=',$emp_id]])
            ->join('employee', 'employee.emp_id', '=', 'payslip.emp_id')
            ->select('payslip.*','employee.bank_account','employee.emp_father_name','employee.emp_department_id', 'employee.emp_name', 'employee.emp_cnic','employee.emp_joining_date','employee.professional_email', 'employee.designation_id')
            ->get()->toArray();
        CommonHelper::reconnectMasterDatabase();
        $payslip_data =$payslip_data[0];

        return view('Hr.pdf',compact('payslip_data'));
    }

    public function downloadPayslipPdf($m,$month,$year,$emp_code)
    {

        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($this->generatePayslip($m,$month,$year,$emp_code));


        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream();
//        $pdf = PDF::loadView('pdf',compact('payslip_data'));
//        return $pdf->download($emp_code."_".$month."_".$year.".pdf");
//
//        $pdf = PDF::loadView('pdf', compact('payslip_data'));
//        return $pdf->download($emp_code."-".$month."-".$year.".pdf");
    }



    public function createMaritalStatusForm()
    {
        return view('Hr.MaritalStatus.createMaritalStatusForm');

    }
    public function editMaritalStatusForm()
    {
        return view('Hr.MaritalStatus.editMaritalStatusForm');

    }

    public function viewMaritalStatuslist()
    {
        $maritalStatus = MaritalStatus::where([['status','=', 1],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        return view('Hr.MaritalStatus.viewMaritalStatuslist', compact('maritalStatus'));
    }

    public function createAllowanceForm()
    {
        
       
        $allowanceTypes = DB::table('allowance_types')->where([['status','=','1']])->orderBy('id')->get();
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.Allowances.createAllowanceForm',compact('Department','SubDepartment','allowanceTypes'));
    }

    public function viewAllowanceList()
    {
     
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $allowance = DB::table('employee')
            ->join('allowance', 'employee.emp_id', '=', 'allowance.emp_id')
            ->select('allowance.*','employee.employee_project_id')
            ->where([['employee.status','=', 1],['allowance.status', '=', 1]])
            ->orderBy('allowance.id')
            ->get();

        CommonHelper::reconnectMasterDatabase();
		$Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.Allowances.viewAllowanceList',compact('allowance','Department','SubDepartment'));
    }

    public function editAllowanceDetailForm()
    {
        $subdepartments = new SubDepartment;
        $departments = Department::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $allowance = Allowance::where([['id','=',Input::get('id')]])->orderBy('id')->first();
        $employees = Employee::where('status', '=', 1)
            ->where('emp_sub_department_id','=',$allowance->emp_sub_department_id)->get();
        CommonHelper::reconnectMasterDatabase();
        $allowanceTypes = DB::table('allowance_types')->where([['status','=','1']])->orderBy('id')->get();
        return view('Hr.Allowances.editAllowanceDetailForm',compact('employees','allowance','departments','subdepartments','allowanceTypes'));
    }

    public function createDeductionForm()
    {
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.Deductions.createDeductionForm',compact('Department','SubDepartment'));
    }

    public function viewDeductionList()
    {
     //  // $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
       // $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
     //   $employeeProjects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $deduction = DB::table('employee')
            ->join('deduction', 'employee.emp_id', '=', 'deduction.emp_id')
            ->select('deduction.*','employee.employee_project_id','employee.emp_name')
            ->where([['employee.status','=', 1],['deduction.status', '=', 1]])
            ->orderBy('deduction.id','desc')
            ->take(50)
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Deductions.viewDeductionList',compact('deduction','Department','SubDepartment'));
    }

    public function editDeductionDetailForm()
    {
        $subdepartments = new SubDepartment;
        $departments = Department::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $deduction = Deduction::where([['id','=',Input::get('id')]])->orderBy('id')->first();
        $employees = Employee::where('status', '=', 1)
            ->where('emp_sub_department_id','=',$deduction->emp_sub_department_id)->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Deductions.editDeductionDetailForm',compact('employees','deduction','departments','subdepartments'));
    }


    public function createAdvanceSalaryForm()
    {
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();

        return view('Hr.AdvanceSalary.createAdvanceSalaryForm',compact('Department','SubDepartment'));
    }
    public function viewAdvanceSalaryList()
    {
        
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $advance_salary = DB::table('employee')
            ->join('advance_salary', 'employee.emp_id', '=', 'advance_salary.emp_id')
            ->select('advance_salary.*')
            ->where([['employee.status','=', 1],['advance_salary.status', '=', 1]])
            ->orderBy('advance_salary.id')
            ->get();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.AdvanceSalary.viewAdvanceSalaryList',compact('advance_salary'));
    }
    public function editAdvanceSalaryDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $advance_salary = AdvanceSalary::select('*')->where([['id', '=', Input::get('id')]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AdvanceSalary.editAdvanceSalaryDetailForm',compact('advance_salary'));
    }

    public function createLeavesPolicyForm()
    {
        $leaves_types  = LeaveType::select('id','leave_type_name')->where([['company_id', '=',0]])->orderBy('id')->get();
        return view('Hr.LeavesPolicy.createLeavesPolicyForm',compact('leaves_types'));
    }

    public function createManualLeaves()
    {
        $department_id = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $sub_department_id = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();

        return view('Hr.LeavesPolicy.createManualLeaves',compact('department_id','sub_department_id'));
    }

    public function viewLeavesPolicyList()
    {
        $leavesPolicy = LeavesPolicy::where([['status', '=', 1]])->orderBy('id', 'desc')->get();;
        return view('Hr.LeavesPolicy.viewLeavesPolicyList',compact('leavesPolicy'));
    }

    public function editLeavesPolicyDetailForm()
    {
        $leavesType =   LeaveType::where([['company_id','=',0]])->get();
        $leavesPolicy = LeavesPolicy::where([['id','=',Input::get('id')]])->first();
        $leavesData =   LeavesData::where([['leaves_policy_id','=',Input::get('id')]])->get();
        return view('Hr.LeavesPolicy.editLeavesPolicyDetailForm',compact('leavesPolicy','leavesData','leavesType'));
    }

    public function createVehicleTypeForm()
    {
        return view('Hr.createVehicleTypeForm');
    }

    public function viewVehicleTypeList()
    {
        $vehicleType = VehicleType::where([['company_id','=',Input::get('m')]])->get();
        return view('Hr.viewVehicleTypeList',compact('vehicleType'));
    }

    public function editVehicleTypeDetailForm()
    {
        $vehicleType = VehicleType::where([['id','=',Input::get('id')]])->get(['vehicle_type_name','vehicle_type_cc'])->first();
        return view('Hr.editVehicleTypeDetailForm',compact('vehicleType'));
    }

    public function createWorkingHoursPolicyDetailForm()
    {
        return view('Hr.WorkingHoursPolicy.createWorkingHoursPolicyDetailForm');
    }

    public function createHolidaysForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_list = Employee::select('emp_name','emp_id','emp_sub_department_id')->where([['status','=',1]])
            ->orderBy('emp_sub_department_id')
            ->get()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Holidays.createHolidaysForm',compact('employee_list'));
    }

    public function viewHolidaysList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $holidays = Holidays::orderBy('holiday_date')->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1'],])->orderBy('id')->get();
        return view('Hr.Holidays.viewHolidaysList',compact('holidays','departments'));
    }

    public function editHolidaysDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $holidaysDetail = Holidays::find(Input::get('id'))->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Holidays.editHolidaysDetailForm',compact('holidaysDetail'));
    }

    public function viewWorkingHoursPolicyList()
    {
	    $workingHoursPolicyList = DB::table('working_hours_policy')->get();
        return view('Hr.WorkingHoursPolicy.viewWorkingHoursPolicyList',compact('workingHoursPolicyList'));
    }

    public function createCarPolicyForm()
    {
        $vehicleType = VehicleType::where([['company_id','=',Input::get('m')]])->get();
        $designation = Designation::where([['company_id','=',Input::get('m')]])->get();
        return view('Hr.createCarPolicyForm',compact('vehicleType','designation'));
    }

    public function viewCarPolicyList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $carPolicy = CarPolicy::all()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewCarPolicyList',compact('carPolicy'));
    }

    public function viewCarPolicyCriteria()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $carPolicy = CarPolicy::all()->toArray();
        CommonHelper::reconnectMasterDatabase();
        $departments = Department::where('company_id','=',$_GET['m'])->where('status','=','1')->orderBy('id')->get();
        return view('Hr.viewCarPolicyCriteria',compact('departments','carPolicy'));
    }

    public function editCarPolicyDetailForm()
    {   $vehicleType = VehicleType::where([['company_id','=',Input::get('m')]])->get();
        $designation = Designation::where([['company_id','=',Input::get('m')]])->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $carPolicy = CarPolicy::where([['id','=',Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.editCarPolicyDetailForm',compact('carPolicy','vehicleType','designation'));
    }


    public function createLoanRequestForm()
    {
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();

        $loanTypes = LoanType::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.loanRequests.createLoanRequestForm',compact('Department','loanTypes','employeeProjects','SubDepartment'));
    }

    public function viewLoanRequestList()
    {
        
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loanRequest = DB::table('employee')
            ->join('loan_request', 'employee.emp_id', '=', 'loan_request.emp_id')
            ->select('loan_request.*')
            ->where([['employee.status','=', 1],['loan_request.status', '=', 1]])
            ->orderBy('loan_request.id')
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.loanRequests.viewLoanRequestList',compact('loanRequest'));
    }

    public function editLoanRequestDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loanRequest = LoanRequest::where([['id','=',Input::get('id')]])->first();
        $employee = Employee::select('emp_name','emp_sub_department_id')->where([['emp_id','=',$loanRequest->emp_id]])->first();
        CommonHelper::reconnectMasterDatabase();
        $loanTypes = LoanType::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.loanRequests.editLoanRequestDetailForm',compact('loanTypes','loanRequest','employee'));
    }

    public function viewLoanReportForm()
    {

        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.loanRequests.viewLoanReportForm', compact('SubDepartment','Department'));

    }

    public function createEOBIForm()
    {
        return view('Hr.EOBI.createEOBIForm');
    }

    public function viewEOBIList()
    {
        $eobi = Eobi::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.EOBI.viewEOBIList',compact('eobi'));
    }

    public function editEOBIDetailForm()
    {
        $eobi = Eobi::select('id','EOBI_name','EOBI_amount','month_year')->where([['id','=',Input::get('id')],['company_id','=',Input::get('m')]])->first();
        return view('Hr.EOBI.editEOBIDetailForm',compact('eobi'));
    }

    public function createTaxesForm()
    {
        return view('Hr.Taxes.createTaxesForm');
    }

    public function viewTaxesList()
    {
        $tax = Tax::where([['status', '=', 1]])->get();
        return view('Hr.Taxes.viewTaxesList',compact('tax'));

    }
    /*  public function editTaxesDetailForm()
      {
          $tax = Tax::where([['id','=',Input::get('id')],['company_id','=',Input::get('m')]])->first();
          return view('Hr.editTaxesDetailForm',compact('tax'));
      }
  */
    public function editTaxesDetailForm()
    {
        $tax_id = Input::get('id');
        $tax_slabs = DB::table('tax_slabs')->where([['tax_id','=',$tax_id]])->orderBy('salary_range_from','asc');
        $tax = Tax::where([['id','=',$tax_id]])->first();
        return view('Hr.Taxes.editTaxesDetailForm',compact('tax','tax_slabs','tax_id'));
    }

    public function viewTaxCriteria()
    {
        $departments = Department::where('company_id','=',$_GET['m'])->where('status','=','1')->orderBy('id')->get();
        $taxes = Tax::where('company_id','=',$_GET['m'])->where('status','=','1')->orderBy('id')->get();
        return view('Hr.Taxes.viewTaxCriteria',compact('departments','taxes'));
    }

    public function createBonusForm()
    {
        return view('Hr.Bonus.createBonusForm');
    }

    public function viewBonusList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $bonus = Bonus::where([['status','=','1']])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Bonus.viewBonusList',compact('bonus'));
    }

    public function editBonusDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $bonus = Bonus::where([['id','=',Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Bonus.editBonusDetailForm',compact('bonus'));
    }

    public function IssueBonusDetailForm()
    {
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $bonus_list = Bonus::where([['status','=','1']])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Bonus.IssueBonusDetailForm',compact('bonus_list','Department','SubDepartment'));
    }

    public function createLeaveApplicationForm()
    {
        $accType = Auth::user()->acc_type;
        if($accType == 'client')
        {
			  $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
			  $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
			  $employeeProjects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
              return view('Hr.LeaveApplication.createLeaveApplicationFormClient', compact('SubDepartment','Department'));
        }
        else
        {
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $emp = Employee::select('id','emp_id','leaves_policy_id')->where('emp_id','=',Auth::user()->emp_id)->first();
			
            $attendance_machine_id = $emp->emp_id;
            CommonHelper::reconnectMasterDatabase();
            if($emp->emp_id == 201 || $emp->emp_id == 208)
            {
				 $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
				 $employeeProjects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();

                $companies =  DB::table('company')->select('id', 'name')->where([['status','=',1]])->get()->toArray();
                $unique_emp = '';
                $emp_arr =array();
                foreach ($companies as $value2):

                    CommonHelper::companyDatabaseConnection($value2->id);
                    $emp_name =  Employee::select('emp_name','emp_id')->where([['status','=',1]]);
                    if($emp_name->count() > 0 ):
                        foreach($emp_name->get() as $value):

                            if(!in_array($value->emp_id,$emp_arr)):
                                $unique_emp[]=$value2->id."|".$value->emp_id."|".$value->emp_name;
                            endif;
                            $emp_arr[]=$value->emp_id;
                        endforeach;
                    endif;
                    CommonHelper::reconnectMasterDatabase();
                endforeach;

                //return view('Hr.createPayslipForm',compact('departments','subdepartments'));
                return view('Hr.LeaveApplication.createLeaveApplicationFormClient', compact('SubDepartment ','employeeProjects ','unique_emp'));
            }
			else if($emp->leaves_policy_id == '0'){
				return view('Hr.LeaveApplication.createLeaveApplicationFormWithoutLeavePolicy');
			}
			else{
				echo $emp->leaves_policy_id;
				die;
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

            //    return view('Hr.AjaxPages.viewLeaveApplicationClientForm', compact('getPreviousUsedAnnualLeavesBalance','getPreviousUsedCasualLeavesBalance','attendance_machine_id', 'leaves_policy_validatity', 'leaves_policy', 'emp_data', 'total_leaves', 'taken_leaves'));

            return view('Hr.LeaveApplication.createLeaveApplicationForm', compact('getPreviousUsedCasualLeavesBalance','getPreviousUsedAnnualLeavesBalance','attendance_machine_id', 'leaves_policy_validatity', 'leaves_policy', 'emp_data', 'total_leaves', 'taken_leaves'));
        }		
    }
}


    public function viewLeaveApplicationRequestList()
    {
        $line_manager_employees = [];
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if(Auth::user()->acc_type == 'client'){
            $line_manager_emp = Employee::select('emp_id')->where('status','=',1)->get();
        }
        else{
            $line_manager_emp = Employee::select('emp_id')->where([['reporting_manager','=',Auth::user()->emp_id],['status','=',1]])->get();
        }
        foreach($line_manager_emp as $value){
            $line_manager_employees[] = $value->emp_id;
        }
        CommonHelper::reconnectMasterDatabase();

        $leave_application_request_list = DB::table('leave_application')
            ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
            ->select('leave_application.*','leave_application_data.from_date','leave_application_data.to_date','leave_application_data.first_second_half_date')
            ->whereIn('leave_application.emp_id',$line_manager_employees)
            ->where('leave_application.view','=','yes')
            ->orderBy('leave_application.approval_status')
            ->get();
        $m = Input::get('m');
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();

        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $companies = DB::table('company')->where('status',1)->get();
        return view('Hr.LeaveApplication.viewLeaveApplicationRequestList', compact('leave_application_request_list','SubDepartment','employeeProjects','companies','m','Department'));
    }
    public function viewLeaveBalances()
    {
        $companies =  DB::table('company')->select('id', 'name')->where([['status','=',1]])->get()->toArray();
        $leavesPolicy = LeavesPolicy::all()->sortByDesc("id");
        return view('Hr.LeaveApplication.viewLeaveBalances', compact('companies','leavesPolicy'));
    }

    public function viewLeaveApplicationList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attendance_machine_id = Employee::select('emp_id')->where([['emp_id','=',Auth::user()->emp_id]])->value('emp_id');
        CommonHelper::reconnectMasterDatabase();
        $leave_application_list = DB::table('leave_application')
            ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
            ->select('leave_application.*','leave_application_data.from_date','leave_application_data.to_date','leave_application_data.first_second_half_date')
            ->where([['leave_application.emp_id', '=',$attendance_machine_id]])
            ->get();
        return view('Hr.LeaveApplication.viewLeaveApplicationList', compact('leave_application_list'));
    }

    public function editLeaveApplicationDetailForm()
    {
        $id_and_leaveId = explode('|', Input::get('id')); 
		
        $leaveApplicationData  = LeaveApplicationData::where([['leave_application_id','=',$id_and_leaveId[0]]])->first()->toArray();
		
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        //$attendance_machine_id = Employee::select('acc_no')->where([['id', '=', Auth::user()->emp_id]])->value('acc_no');
        $emp = Employee::select('id','emp_id','leaves_policy_id')->where('emp_id', '=',$id_and_leaveId[1])->first();
		
        $attendance_machine_id = $id_and_leaveId[1];
        CommonHelper::reconnectMasterDatabase();
		
        $leaves_policy = DB::table('leaves_policy')
            //->join('leaves_policy', 'leaves_policy.id', '=', 'employee.leaves_policy_id')
            ->join('leaves_data', 'leaves_data.leaves_policy_id', '=', 'leaves_policy.id')
            ->select('leaves_policy.*', 'leaves_data.*')
            ->where('leaves_policy.id', '=',$emp->leaves_policy_id)
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
            ->where([['leave_application.emp_id', '=', $id_and_leaveId[1]], ['leave_application.status', '=', '1'],
                ['leave_application.approval_status', '=', '2']])
            ->first();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_data = Employee::select('emp_name', 'emp_sub_department_id', 'designation_id', 'emp_id','emp_department_id')->where('emp_id','=',$id_and_leaveId[1])->orderBy('id')->first();
		
        CommonHelper::reconnectMasterDatabase();


        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emp_data = Employee::select('leaves_policy_id','emp_name', 'emp_sub_department_id', 'designation_id', 'emp_id','emp_department_id')->where('emp_id', '=', $id_and_leaveId[1])->orderBy('id')->first();
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

        return view('Hr.LeaveApplication.editLeaveApplicationDetailForm', compact('leaveApplicationData','getPreviousUsedCasualLeavesBalance','getPreviousUsedAnnualLeavesBalance','attendance_machine_id', 'leaves_policy_validatity', 'leaves_policy', 'emp_data', 'total_leaves', 'taken_leaves'));
    }

    public function createEmployeeDepositForm()
    {
        $subdepartments = new SubDepartment;
        $departments = Department::where('company_id','=',$_GET['m'])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.createEmployeeDepositForm',compact('departments','subdepartments'));
    }

    public function editEmployeeDepositDetail()
    {
        $empDepositId = Input::get('id');
        $subdepartments = new SubDepartment;
        $departments = Department::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $EmployeeDepositData = EmployeeDeposit::where('id','=',$empDepositId)->first();
        $employee = Employee::all();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.AjaxPages.editEmployeeDepositDetail',compact('subdepartments','departments','EmployeeDepositData','employee'));
    }

    public function viewEmployeeDepositList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeDeposit = EmployeeDeposit::all();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewEmployeeDepositList',compact('employeeDeposit'));
    }

    public function createEmployeeGradesForm(){
        return view('Hr.EmployeeGrades.createEmployeeGradesForm');
    }

    public function editEmployeeGradesDetailForm(){
        $employeeGradesDetail = Grades::where([['id','=', Input::get('id')]])->first();
        return view('Hr.EmployeeGrades.editEmployeeGradesDetailForm', compact('employeeGradesDetail'));
    }

    public function viewEmployeeGradesList()
    {
        $employee_grades = Grades::where([['status','=',1],['company_id','=',Input::get('m')]])->orderBy('employee_grade_type')->get();
        return view('Hr.EmployeeGrades.viewEmployeeGradesList', ['EmployeeGrades' => $employee_grades]);
    }

    public function createEmployeeLocationsForm()
    {
        return view('Hr.createEmployeeLocationsForm');
    }

    public function editEmployeeLocationsDetailForm()
    {
        $employeeLocationsDetail = Locations::where([['id','=', Input::get('id')]])->first();
        return view('Hr.editEmployeeLocationsDetailForm', compact('employeeLocationsDetail'));
    }

    public function viewEmployeeLocationsList()
    {
        $employee_locations = Locations::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.viewEmployeeLocationsList', ['EmployeeLocations' => $employee_locations]);
    }
	
    public function editEmployeeRegionsDetailForm()
    {
        $employeeRegionsDetail = Regions::where([['id','=', Input::get('id')]])->first();
        return view('Hr.editEmployeeRegionsDetailForm', compact('employeeRegionsDetail'));
    }
	
    public function createEmployeeDegreeTypeForm(){
        return view('Hr.EmployeeDegreeType.createEmployeeDegreeTypeForm');
    }

    public function viewEmployeeDegreeTypeList(){
        $employee_degree_type = DegreeType::all();
        return view('Hr.EmployeeDegreeType.viewEmployeeDegreeTypeList', ['EmployeeDegreeType' => $employee_degree_type]);
    }

    public function editEmployeeDegreeTypeDetailForm(){
        $employeeDegreeTypeDetail = DegreeType::where([['id','=', Input::get('id')]])->first();
        return view('Hr.EmployeeDegreeType.editEmployeeDegreeTypeDetailForm', compact('employeeDegreeTypeDetail'));
    }

    public function createEmployeeExitClearanceForm()
    {
        
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::where('status', 1)->get(['emp_name', 'id']);
        CommonHelper::reconnectMasterDatabase();
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $sub_department = SubDepartment::where([['status', '=', '1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        $employee_projects = EmployeeProjects::where([['status', '=', '1'],['company_id','=',Input::get('m')]])->orderBy('id')->get();
        return view('Hr.ExitClearance.createEmployeeExitClearanceForm', compact('employee','employee_projects','sub_department','Department'));
    }

    public function viewEmployeeExitClearanceList()
    {
        
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_exit = DB::table('employee')
            ->join('employee_exit', 'employee.emp_id', '=', 'employee_exit.emp_id')
            ->select('employee_exit.*')
            ->where([['employee.status','!=', 2],['employee_exit.status', '=', 1]])
            ->orderBy('employee_exit.id')
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.ExitClearance.viewEmployeeExitClearanceList', compact('employee_exit', 'employee'));
    }

    public function editEmployeeExitClearanceDetailForm()
    {
        $id = Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $exit_employee_data = EmployeeExit::where([['id','=',$id]])->first();
        $employee = Employee::select('emp_name', 'designation_id', 'emp_joining_date', 'emp_sub_department_id')->where([['emp_id','=', $exit_employee_data->emp_id]])->first();
        $designation_id = $employee->designation_id;
        $emp_sub_department_id = $employee->emp_sub_department_id;
        $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',$exit_employee_data->emp_id],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail->designation_id;
        endif;
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.ExitClearance.editEmployeeExitClearanceDetailForm', compact('exit_employee_data', 'designation_id', 'employee', 'emp_sub_department_id'));
    }

    public function editFinalSettlementDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $array = explode('|',Input::get('id'));
        $id = $array[0];
        $gratuityAmount = '';
        $emr_no = $array[1];
        $final_settlement = FinalSettlement::where([['status', '=', 1], ['emr_no', '=', $emr_no]])->first();
        $employee = Employee::where([['status', '!=', 2], ['emr_no', '=', $emr_no]])->select('emr_no','emp_name','emp_sub_department_id','designation_id', 'emp_joining_date', 'emp_salary','employee_category_id','region_id')->first();
        $designation_id = $employee['designation_id'];
        $salary = $employee['emp_salary'];
        $emp_sub_department_id = $employee['emp_sub_department_id'];

        $gratuity = Gratuity::where([['emr_no',$emr_no]])->orderBy('id','desc');
        if($gratuity->exists()):
            $gratuityDetails = $gratuity->first();
            $gratuityAmount = $gratuityDetails->gratuity;
        endif;

        $employeeCurrentPositions = EmployeePromotion::where([['emr_no','=',$emr_no],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
        if($employeeCurrentPositions->count() > 0):
            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
            $designation_id = $employeeCurrentPositionsDetail['designation_id'];
            $salary = $employeeCurrentPositionsDetail['salary'];
        endif;

        $exit_data = EmployeeExit::where([['status', '=', 1], ['emr_no', '=', $emr_no]])->select('leaving_type', 'last_working_date')->first();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.editFinalSettlementDetailForm', compact('exit_data','gratuityAmount', 'final_settlement','salary','designation_id', 'employee', 'emp_sub_department_id'));
    }


    public function createEmployeeIdCardRequest()
    {
        
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.createEmployeeIdCardRequest', compact('employee_category', 'employee_regions','Employee_projects'));
    }

    public function viewEmployeeIdCardRequestList()
    {
        
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_card_request = DB::table('employee')
            ->join('employee_card_request', 'employee.emr_no', '=', 'employee_card_request.emr_no')
            ->select('employee_card_request.*')
            ->where([['employee.status','!=', 2],['employee_card_request.status', '=', 1]])
            ->whereIn('employee.region_id',$regions)
            ->orderBy('employee_card_request.id')
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewEmployeeIdCardRequestList', compact('employee_card_request'));
    }

    public function editEmployeeIdCardRequestDetailForm()
    {
        $id = $_GET['id'];
        $m 	= $_GET['m'];

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_card_request=EmployeeCardRequest::where([['status', '=',1],['id', '=', $id]])->first();
        $employee=Employee::where([['emr_no', '=', $employee_card_request->emr_no],['status', '=',1]])->select('img_path','emp_name', 'designation_id', 'emp_sub_department_id', 'emp_joining_date', 'emp_cnic')->first();
        CommonHelper::reconnectMasterDatabase();

        $designation=Designation::where([['status', '=', 1],['id',$employee->designation_id]])->select('designation_name')->first();
        $sub_department=SubDepartment::where([['status', '=', 1],['id',$employee->emp_sub_department_id]])->select('sub_department_name')->first();

        return view('Hr.editEmployeeIdCardRequestDetailForm', compact('designation', 'employee_card_request', 'employee', 'sub_department'));
    }

    public function createEmployeePromotionForm()
    {
        $department_id = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $sub_department_id = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.Promotions.createEmployeePromotionForm',compact('designation', 'sub_department_id','department_id'));
    }

    public function viewEmployeePromotionsList()
    {	
        $operations_rights = CommonHelper::operations_rights();
         $m = Input::get('m');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeePromotions = EmployeePromotion::where('status','=',1)->orderBy('id','desc');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Promotions.viewEmployeePromotionsList', compact('employeePromotions','operation_rights2','m','operations_rights'));
    }

    public function editEmployeePromotionDetailForm()
    {
        $id = Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_promotion = EmployeePromotion::where([['id','=', $id]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.Promotions.editEmployeePromotionDetailForm', compact('designation', 'employee_promotion'));
    }
    
    public function editSalaryForm()
    {
        $id = Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_promotion = EmployeePromotion::where([['id','=', $id]])->orderBy('id')->first();
        CommonHelper::reconnectMasterDatabase();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.Employees.editSalaryForm', compact('designation', 'employee_promotion'));
    }
    
    public function viewEmployeeProjectsList()
    {

        $employee_projects = EmployeeProjects::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.EmployeeProjects..viewEmployeeProjectsList', ['EmployeeProjects' => $employee_projects]);
    }

    public function editEmployeeProjectsDetailForm()
    {
        $employee_projects = EmployeeProjects::where([['id','=', Input::get('id')]])->first();
        return view('Hr.EmployeeProjects.editEmployeeProjectsDetailForm',compact('employee_projects'));
    }

    public function editEmployeeTransferDetailForm()
    {
        $id = Input::get('id');
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_location = EmployeeTransfer::where([['id','=', $id]])->orderBy('id')->first();
        $employee_promotion_id = $employee_location->promotion_id;
        $employee_transfer_project_id = $employee_location->transfer_project_id;
        $count = 0;
        $promotionCount = 0;

        if($employee_promotion_id != 0)
        {
            $promotionCount = 1;
            $employee_promotion = EmployeePromotion::where([['id','=', $employee_promotion_id]])->orderBy('id')->first();
        }

        if($employee_transfer_project_id != 0)
        {
            $count = 2;
            $TransferEmployeeProject = TransferEmployeeProject::where([['id','=', $employee_transfer_project_id]])->orderBy('id')->first();
        }
        CommonHelper::reconnectMasterDatabase();
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $location = Locations::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employee_grades = Grades::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.editEmployeeTransferDetailForm', compact('designation', 'employee_promotion','promotionCount','count', 'employee_location', 'location', 'employee_grades','Employee_projects','TransferEmployeeProject'));
    }

    public function createEmployeeFuelDetailForm()
    {
        $subdepartments = new SubDepartment;
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $location = Locations::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.createEmployeeFuelDetailForm',compact('departments','subdepartments','designation','location'));
    }

    public function viewEmployeeFuel()
    {
        $subdepartments = new SubDepartment;
        $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.viewEmployeeFuel',compact('departments','subdepartments','designation'));
    }

    public function editEmployeeFuelDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeFuelData = EmployeeFuelData::where([['status', '=', 1],['id', '=', Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();

        return view('Hr.editEmployeeFuelDetailForm',compact('employeeFuelData'));
    }

    public function createHrLetters()
    {
        
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $department_id = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $sub_department_id = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.HrLetters.createHrLetters', compact('department_id', 'sub_department_id','Employee_projects'));
    }

    public function viewHrLetters()
    {
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $Department = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.HrLetters.viewHrLetters', compact('Department', 'SubDepartment'));
    }

    public function uploadLettersFile()
    {
        
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $department_id = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $sub_department_id = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $uploaded_letters_list = LetterFiles::where([['status', '=', 1]])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.HrLetters.uploadLettersFile', compact('department_id', 'sub_department_id', 'uploaded_letters_list','Employee_projects'));
    }

    public function createEquipmentsForm()
    {

        return view('Hr.Equipments.createEquipmentsForm');
    }

    public function viewEquipmentsList()
    {
//        CommonHelper::companyDatabaseConnection(Input::get('m'));
        CommonHelper::reconnectMasterDatabase();
        $EmployeeEquipment = DB::table('employee_equipments')
            ->where([['status','=', 1]])
            ->get();

        return view('Hr.Equipments.viewEquipmentsList', compact('EmployeeEquipment'));
    }

    public function editEquipmentDetailForm()
    {
        $employeeEquipment = Equipments::where([['id','=', Input::get('id')]])->first();
        return view('Hr.Equipments.editEquipmentDetailForm', compact('employeeEquipment'));
    }

    public function createEmployeeEquipmentsForm()
    {
        $departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $sub_department = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employee_projects  = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        return view('Hr.Equipments.createEmployeeEquipmentsForm', compact('departments','sub_department', 'employeeEquipment'));
    }

    public function viewEmployeeEquipmentsList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeEquipment = EmployeeEquipments::where([['status', '=', 1]])->orderBy('id')->get();

        CommonHelper::reconnectMasterDatabase();

        return view('Hr.Equipments.viewEmployeeEquipmentsList', compact('employeeEquipment'));
    }

    public function editEmployeeEquipmentsDetailForm()
    {
        $equipment_detail = null;
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee_emr_no = EmployeeEquipments::where([['id','=', Input::get('id')]])->first();
        $emr_no = $employee_emr_no->emp_id;
        $employee = Employee::select('id','emp_id', 'eobi_number', 'eobi_path')->where([['emp_id','=',$emr_no],['status','!=',2]])->first();
        $employeeEquipment  = EmployeeEquipments::where([['emp_id','=', $emr_no]])->pluck('equipment_id')->toArray();

        if(EmployeeEquipments::select('mobile_number', 'model_number', 'sim_number')->where([['emp_id','=',$emr_no],['status','=',1],['equipment_id', '=', 11]])->exists()):
            $equipment_detail = EmployeeEquipments::select('mobile_number', 'model_number', 'sim_number')->where([['emp_id','=',$emr_no],['status','=',1],['equipment_id', '=', 11]])->first();
        endif;

        $employee_eobi_copy = Employee::where([['emp_id','=',$emr_no],['status','!=',2],['eobi_path', '!=', null]]);
        $employee_insurance_copy = Employee::where([['emp_id','=',$emr_no],['status','!=',2]]);

        CommonHelper::reconnectMasterDatabase();
        $equipment = Equipments::where([['status','=', 1]])->orderBy('id')->get();

        return view('Hr.Equipments.editEmployeeEquipmentsDetailForm', compact('employeeEquipment', 'emr_no', 'equipment', 'employee', 'equipment_detail', 'employee_insurance_copy', 'employee_eobi_copy'));
    }

    public function createDiseasesForm()
    {
        return view('Hr.Diseases.createDiseasesForm');
    }

    public function viewDiseasesList()
    {
        $disease = Diseases::where([['company_id','=',Input::get('m')],['status', '=', 1]])->orderBy('id')->get();
        return view('Hr.Diseases.viewDiseasesList', compact('disease'));
    }

    public function editDiseasesDetailForm()
    {
        $disease = Diseases::where([['id','=', Input::get('id')]])->first();
        return view('Hr.Diseases.editDiseasesDetailForm', compact('disease'));
    }

    public function viewEmployeeMedicalList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeMedical = EmployeeMedical::where([['status', '=', 1]])->orderBy('id');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewEmployeeMedicalList', compact('employeeMedical'));
    }

    public function createEmployeeMedicalForm()
    {
        $disease = Diseases::where('status', '=', 1)->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $Employeeprojects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        return view('Hr.createEmployeeMedicalForm', compact('disease', 'employee_regions', 'employee_category','Employeeprojects'));
    }

    public function editEmployeeMedicalDetailForm(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeMedical = EmployeeMedical::where([['id', '=', Input::get('id')]])->first();
        CommonHelper::reconnectMasterDatabase();
        $disease = Diseases::where('status', '=', 1)->get();
        return view('Hr.editEmployeeMedicalDetailForm', compact('employeeMedical', 'disease'));
    }

    public function viewHrReports()
    {
        return view('Hr.Reports.viewHrReports');
    }

    public function editEmployeeAttendanceDetailForm()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $attendanceDetail = Attendance::where([['id','=', Input::get('id')]])->get()->toArray();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.editEmployeeAttendanceDetailForm', compact('attendanceDetail'));
    }

    public function createTrainingForm()
    {
        
        $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $employee_locations = Locations::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        return view('Hr.createTrainingForm', compact('employee_regions','employee_category','employee_locations','Employee_projects'));
    }

    public function viewTrainingList()
    {
        
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $trainingsData = Trainings::where([['status','=',1]])->orderBy('training_date','desc')->get();

        CommonHelper::reconnectMasterDatabase();
        return view('Hr.viewTrainingList', compact('trainingsData'));
    }

    public function editTrainingDetailForm()
    {
        $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        $employee_locations = Locations::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employee = Employee::where([['status', '=', 1]])->select('emr_no', 'emp_name')->get();
        $trainingsData = Trainings::where([['status','=',1],['id', Input::get('id')]])->first();
        $TrainingCertificate = TrainingCertificate::where([['status','=',1],['training_id', Input::get('id')]])->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.editTrainingDetailForm', compact('employee_regions','employee_category','employee_locations', 'trainingsData','employee','TrainingCertificate'));

    }


   

    public function employeeTransferLeaves()
    {
        $companies =  DB::table('company')->select('id', 'name')->where([['status','=',1]])->get()->toArray();
        $leavesPolicy = LeavesPolicy::all()->sortByDesc("id");
        return view('Hr.LeavesPolicy.employeeTransferLeaves', compact('companies','leavesPolicy'));
    }
	
   public function editEmployeeTransferProject()
   {
       $id = Input::get('id');
       CommonHelper::companyDatabaseConnection(Input::get('m'));
       $TransferEmployeeProject = TransferEmployeeProject::where([['id','=', $id]])->first();
       $employee = Employee::where('emr_no','=',$TransferEmployeeProject->emr_no)->first();
       CommonHelper::reconnectMasterDatabase();
       
       $Employee_projects = EmployeeProjects::where([['company_id',$_GET['m']], ['status','=','1']])->orderBy('id')->get();
       $employee_regions = Regions::where([['status','=',1],['company_id','=',Input::get('m')]])->whereIn('id',$regions)->get();
       $employee_category = EmployeeCategory::where([['status','=',1],['company_id','=',Input::get('m')]])->get();
       $designation = Designation::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
       $location = Locations::where('company_id','=',Input::get('m'))->orderBy('id')->get();
       $employee_grades = Grades::where('company_id','=',Input::get('m'))->orderBy('id')->get();
       return view('Hr.editEmployeeTransferProject',compact('employee_regions','employee_category','designation','location', 'employee_grades','Employee_projects','TransferEmployeeProject','employee'));
   }
   
   public function ViewAttendanceProgressList()
	{
		$departments = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
		CommonHelper::companyDatabaseConnection(Input::get('m'));
		$accType = Auth()->user()->acc_type;
		$acc_emp_id = Auth()->user()->emp_id;
		if($accType == 'user'){
		$payrollData = DB::table('payroll_data')
			->join('employee', 'employee.emp_id', '=', 'payroll_data.emp_id')
			->select('employee.emp_sub_department_id', 'payroll_data.*')
			->where([['payroll_data.status', '=', '1'],['employee.emp_id',$acc_emp_id]])
			->orderBy('year', 'desc')
			->orderBy('month', 'desc')
			->orderBy('emp_sub_department_id', 'asc')
			->orderBy('emp_id', 'asc');
		}
		else{
			$payrollData = DB::table('payroll_data')
			->join('employee', 'employee.emp_id', '=', 'payroll_data.emp_id')
			->select('employee.emp_sub_department_id', 'payroll_data.*')
			->where([['payroll_data.status', '=', '1']])
			->orderBy('year', 'desc')
			->orderBy('month', 'desc')
			->orderBy('emp_sub_department_id', 'asc')
			->orderBy('emp_id', 'asc');
		}
		CommonHelper::reconnectMasterDatabase();
		return view('Hr.Attendance.ViewAttendanceProgressList', compact('departments','payrollData'));
	}
	
	public function viewEmployeeQueries()
	{
	    $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
		$employeeProjects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
		CommonHelper::companyDatabaseConnection(Input::get('m'));
		$LateArrivalsReason = DB::table('user_query')->orderBy('id','desc')->get();
		CommonHelper::reconnectMasterDatabase();

		return view('Hr.viewEmployeeQueries',compact('SubDepartment','LateArrivalsReason','employeeProjects'));

	}
	
	 public function createEmployeeProjectsForm()
    {
        return view('Hr.EmployeeProjects.createEmployeeProjectsForm');
    }

    public function viewEmployeeOfTheMonth()
    {
        $department_id = Department::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $sub_department_id = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        $employeeProjects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employeeOfTheMonth = EmployeeOfTheMonth::where([['status', '=', '1']])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.Employees.viewEmployeeOfTheMonth', compact('department_id','employeeProjects','employeeOfTheMonth','sub_department_id'));
    }
    
    public function uploadPolicyFile()
    {
        return view('Hr.PolicyForms.uploadPolicyFile');
    }

    public function viewPolicyList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $policies = Policies::where([['status', '=', 1]])->orderBy('category_id');
        CommonHelper::reconnectMasterDatabase();
        return view('Hr.PolicyForms.viewPolicyList',compact('policies'));
    }

    public function createProvidentFundForm()
    {
        return view('Hr.ProvidentFund.createProvidentFundForm');
    }
    public function editProvidentFundDetail()
    {
        $provident_fund_detail =  DB::table('provident_fund')->where([['company_id','=',Input::get('m')],['id','=',Input::get('id')]])->first();

        return view('Hr.ProvidentFund.editProvidentFundDetail',compact('provident_fund_detail'));
    }
    public function viewProvidentFundList()
    {
        $provident_fund = DB::table('provident_fund')->where([['company_id','=',Input::get('m')]])->get();
        return view('Hr.ProvidentFund.viewProvidentFundList',compact('provident_fund'));
    }

    public function providentFundReport()
    {
        $companies =  DB::table('company')->select('id', 'name')->where([['status','=',1]])->orderBy('order_by_no', 'asc')->get()->toArray();
        $subdepartments = new SubDepartment;
        $departments = Department::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        return view('Hr.ProvidentFund.providentFundReport',compact('departments','subdepartments','companies'));

    }
    public function createPfOpeningBalance()
    {
        $departments = Department::where('company_id','=',Input::get('m'))->orderBy('id')->get();
        return view('Hr.ProvidentFund.createPfFundOpeningBalance',compact('departments'));
    }
	
	public function editWorkingOurPolicyList(){
		$id = Input::get('id');
		$m =  Input::get('m');
		
		$editWorkingPolicyDetail = DB::table('working_hours_policy')->where([['id',$id],['company_id',$m]])->first();
		return view('Hr.WorkingHoursPolicy.editWorkingOurPolicyList',compact('editWorkingPolicyDetail'));
	}
	
	 public function viewMySalarySheet()
    {
       
        
        return view('Hr.SalarySheet.viewMySalarySheet');
       
    }


}