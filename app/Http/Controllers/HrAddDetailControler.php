<?php

namespace App\Http\Controllers;
use App\Helpers\HrHelper;
use Carbon\Carbon;
use App\Models\Arrears;
use App\Models\Designation;
use App\Models\EmployeeCategory;
use App\Models\EmployeeFuelData;
use App\Models\EmployeeLocation;
use App\Models\Grades;
use App\Models\Locations;
use App\Models\Regions;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Models\LoanRequest;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Payslip;
use App\Models\Attendence;
use App\Models\EmployeeDeposit;
use App\Models\LeavesData;
use App\Models\LeavesPolicy;
use App\Models\LeaveApplication;
use App\Models\LeaveApplicationData;
use App\Models\EmployeeDocuments;
use App\Models\EmployeeEquipments;
use App\Models\UsersImport;
use App\Models\EmployeeBankData;
use App\Models\EmployeePromotion;
use App\Models\TransferedLeaves;
use DateTime;
use App\Models\Role;
use App\Models\PayrollData;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\MaritalStatus;
use App\Models\JobType;
use App\Models\Eobi;
use App\Models\MenuPrivileges;
use App\Models\Tax;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\Payroll;
use App\Models\BonusIssue;
use App\Models\Bonus;
use App\Models\AdvanceSalary;
use App\Models\EmployeeTransfer;


use Hash;
use File;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;



class HrAddDetailControler extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function addDepartmentDetail()
    {
        $departmentSection = Input::get('departmentSection');
        foreach ($departmentSection as $row) {
            $department_name = Input::get('department_name_' . $row . '');
            $data1['department_name'] = trim($department_name,' ');
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('department')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewDepartmentList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }

    public function addSubDepartmentDetail()
    {
        $subDepartmentSection = Input::get('subDepartmentSection');
        foreach ($subDepartmentSection as $row) {
            $department_id = Input::get('department_id_' . $row . '');
            $sub_department_name = Input::get('sub_department_name_' . $row . '');
            $data1['department_id'] = strip_tags($department_id);
            $data1['sub_department_name'] = strip_tags($sub_department_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('sub_department')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewSubDepartmentList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }

    function addEmergencyDetail(Request $request)
    {
        $m=Input::get('m');
        CommonHelper::companyDatabaseConnection($m);

        foreach ($request->input('emp_id') as $key => $val){


            if($request->input('designation')[$key] != ''){


                $data1['emp_id'] = $val;
                $data1['designation_id'] = $request->input('designation')[$key];
                $data1['emp_cnic'] = $request->input('cnic')[$key];
                $data1['emp_contact_no'] = $request->input('emp_contact_no')[$key];
                $data1['emergency_no'] = $request->input('emp_emergency_contact_no')[$key];
                $data1['emp_emergency_relation_name'] = $request->input('emp_emergency_relation_name')[$key];
                $data1['emp_emergency_relation'] = $request->input('emp_emergency_relation')[$key];
                $data1['emp_emergency_relation_address'] = $request->input('emp_emergency_relation_address')[$key];

                DB::table('employee')->where('emp_id', $val)->update($data1);
            }

        }
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeList?m=' . $m . '#Innovative');
    }


    public function addDesignationDetail()
    {
        $designationSection = Input::get('designationSection');
        foreach ($designationSection as $row) {
            $designation_name = Input::get('designation_name_' . $row . '');
            $data1['designation_name'] = strip_tags($designation_name);
            $data1['designation_name'] = strip_tags($designation_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('designation')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewDesignationList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }

    public function addHealthInsuranceDetail()
    {
        $healthInsuranceSection = Input::get('healthInsuranceSection');
        foreach ($healthInsuranceSection as $row) {
            $healthInsurance_name = Input::get('healthInsurance_name_' . $row . '');
            $data1['health_insurance_name'] = strip_tags($healthInsurance_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('health_insurance')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewHealthInsuranceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }

    public function addLifeInsuranceDetail()
    {
        $lifeInsuranceSection = Input::get('lifeInsuranceSection');
        foreach ($lifeInsuranceSection as $row) {
            $lifeInsurance_name = Input::get('lifeInsurance_name_' . $row . '');
            $data1['life_insurance_name'] = strip_tags($lifeInsurance_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('life_insurance')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewLifeInsuranceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }

    public function addJobTypeDetail()
    {
        $jobTypeSection = Input::get('jobTypeSection');
        foreach ($jobTypeSection as $row) {
            $job_type_name = Input::get('job_type_name_' . $row . '');
            $data1['job_type_name'] = strip_tags($job_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('job_type')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewJobTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }

    public function addQualificationDetail()
    {
        $qualificationSection = Input::get('qualificationSection');
        foreach ($qualificationSection as $row) {
            $qualification_name = Input::get('qualification_name_' . $row . '');
            $institute_name = Input::get('institute_name_' . $row . '');
            $country = Input::get('country_' . $row . '');
            $state = Input::get('state_' . $row . '');
            $city = Input::get('city_' . $row . '');
            $institute = Input::get('institute_name_' . $row . '');
            $data2['qualification_name'] = strip_tags($qualification_name);
            $data2['institute_id'] = strip_tags($institute);
            $data2['country_id'] = strip_tags($country);
            $data2['state_id'] = strip_tags($state);
            $data2['city_id'] = strip_tags($city);
            $data2['username'] = Auth::user()->name;
            $data2['status'] = 1;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");
            $data2['company_id'] = $_GET['m'];
            DB::table('qualification')->insert($data2);


        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewQualificationList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }

    public function addLeaveTypeDetail()
    {
        $leaveTypeSection = Input::get('leaveTypeSection');
        foreach ($leaveTypeSection as $row) {
            $leave_type_name = Input::get('leave_type_name_' . $row . '');
            $data1['leave_type_name'] = strip_tags($leave_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('leave_type')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewLeaveTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }

    public function addLoanTypeDetail()
    {
        $loanTypeSection = Input::get('loanTypeSection');
        foreach ($loanTypeSection as $row) {
            $loan_type_name = Input::get('loan_type_name_' . $row . '');
            $data1['loan_type_name'] = strip_tags($loan_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('loan_type')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewLoanTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }

    public function addAdvanceTypeDetail()
    {
        $advanceTypeSection = Input::get('advanceTypeSection');
        foreach ($advanceTypeSection as $row) {
            $advance_type_name = Input::get('advance_type_name_' . $row . '');
            $data1['advance_type_name'] = strip_tags($advance_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('advance_type')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewAdvanceTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }

    public function addShiftTypeDetail()
    {
        $shiftTypeSection = Input::get('shiftTypeSection');
        foreach ($shiftTypeSection as $row) {
            $shift_type_name = Input::get('shift_type_name_' . $row . '');
            $data1['shift_type_name'] = strip_tags($shift_type_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('shift_type')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewShiftTypeList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }


    public function addHiringRequestDetail()
    {
        $d = Input::get('dbName');
        $companyId = Input::get('company_id');
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $jobTitle = Input::get('job_title');
        $jobTypeId = Input::get('job_type_id');
        $subDepartmentId = Input::get('sub_department_id');
        $designationId = Input::get('designation_id');
        $qualificationId = Input::get('qualification_id');
        $shiftTypeId = Input::get('shift_type_id');
        $gender = Input::get('gender');
        $salaryStart = Input::get('salary_start');
        $salaryEnd = Input::get('salary_end');
        $age = Input::get('age');
        $jobDescription = Input::get('job_description');
        $location = Input::get('location');
        $experience = Input::get('experience');
        $career_level = Input::get('career_level');
        $apply_before_date = Input::get('apply_before_date');

        $str = DB::selectOne("select max(convert(substr(`RequestHiringNo`,4,length(substr(`RequestHiringNo`,4))-4),signed integer))
        reg from `requesthiring` where substr(`RequestHiringNo`,-4,2) = " . date('m') . "
        and substr(`RequestHiringNo`,-2,2) = " . date('y') . "")->reg;
        $RequestHiringNo = 'rhn' . ($str + 1) . date('my');

        $data1['RequestHiringNo'] = strip_tags($RequestHiringNo);
        $data1['RequestHiringTitle'] = strip_tags($jobTitle);
        $data1['sub_department_id'] = strip_tags($subDepartmentId);
        $data1['job_type_id'] = strip_tags($jobTypeId);
        $data1['designation_id'] = strip_tags($designationId);
        $data1['qualification_id'] = strip_tags($qualificationId);
        $data1['shift_type_id'] = strip_tags($shiftTypeId);
        $data1['location'] = strip_tags($location);
        $data1['experience'] = strip_tags($experience);
        $data1['career_level'] = strip_tags($career_level);
        $data1['apply_before_date'] = strip_tags($apply_before_date);
        $data1['RequestHiringGender'] = strip_tags($gender);
        $data1['RequestHiringSalaryStart'] = strip_tags($salaryStart);
        $data1['RequestHiringSalaryEnd'] = strip_tags($salaryEnd);
        $data1['RequestHiringAge'] = strip_tags($age);
        $data1['RequestHiringDescription'] = $jobDescription;
        $data1['ApprovalStatus'] = 1;
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('requesthiring')->insert($data1);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewHiringRequestList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $companyId . '#Innovative');
    }

    function addEmployeeDetailDraft(Request $request)
    {

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $employeeSection = Input::get('employeeSection');
//        $days_off_1 = Input::get('days_off_1');
//        $days = '';


        //emergency area
        $emergency_no = Input::get('emergency_no_1');
        $emergency_address = Input::get('persons_address');
        $emergency_person_name = Input::get('person_name');
        $emergency_person_relation = Input::get('persons_relation');



        foreach ($employeeSection as $row) {


            /*Image uploading start*/
            if ($request->file('fileToUpload_' . $row . '')):
                $file_name = Input::get('employee_name_' . $row . '') . '_' . time() . '.' . $request->file('fileToUpload_' . $row . '')->getClientOriginalExtension();
                $path = 'app/' . $request->file('fileToUpload_' . $row . '')->storeAs('uploads/employee_images', $file_name);
            else:
                $path = 'app/uploads/employee_images/user-dummy.png';
            endif;
            /*Image uploading end*/

//          cnic upload start
            if ($request->file('cnic_path_' . $row . '')):
                $file_name1 = Input::get('employee_name_' . $row . '') . '_' . time() . '.' . $request->file('cnic_path_' . $row . '')->getClientOriginalExtension();
                $path1 = 'app/' . $request->file('cnic_path_' . $row . '')->storeAs('uploads/employee_cnic_copy', $file_name1);
                $data1['cnic_path'] = $path1;
                $data1['cnic_name'] = $file_name1;
                $data1['cnic_type'] = $request->file('cnic_path_' . $row . '')->getClientOriginalExtension();
            else:
                $data1['cnic_path'] = null;
                $data1['cnic_name'] = null;
                $data1['cnic_type'] = null;
            endif;

//            eobi upload
            if ($request->file('eobi_path_' . $row . '')):
                $file_name1 = Input::get('employee_name_' . $row . '') . '_' . time() . '.' . $request->file('eobi_path_' . $row . '')->getClientOriginalExtension();
                $path1 = 'app/' . $request->file('eobi_path_' . $row . '')->storeAs('uploads/employee_eobi_copy', $file_name1);
                $data1['eobi_path'] = $path1;
                $data1['eobi_type'] = $request->file('eobi_path_' . $row . '')->getClientOriginalExtension();
            else:
                $data1['eobi_path'] = null;
                $data1['eobi_type'] = null;
            endif;


            /*Basic info start*/
            $employee_name = Input::get('employee_name_' . $row . '');
            $emp_id = '104';
            //$emp_id = Input::get('emp_id');
            $father_name = Input::get('father_name_' . $row . '');
            $sub_department_id = Input::get('sub_department_id_' . $row . '');
            $department_id = Input::get('department_id_');
            $date_of_birth = Input::get('date_of_birth_' . $row . '');
            $gender = Input::get('gender_' . $row . '');
            $cnic = Input::get('cnic_' . $row . '');
            $cnic_expiry_date = Input::get('cnic_expiry_date_' . $row . '');
            $contact_no = Input::get('emp_contact_no');

            $landline_number = Input::get('landline_number');
            $contact_home = Input::get('contact_home');
            $blood_group = Input::get('blood_group_'.$row.'');
            $mark_of_identification = Input::get('mark_of_identification_'.$row.'');
            $employee_status = Input::get('employee_status_' . $row . '');
            $salary = Input::get('compensation');
            $email = Input::get('email_1');
            $marital_status = Input::get('marital_status_' . $row . '');
            $leaves_policy = Input::get('leaves_policy_' . $row . '');
            $designation = Input::get('designation_' . $row . '');
            $tax_id = Input::get('tax_id_' . $row . '');
            $eobi_id = Input::get('eobi_id_' . $row . '');
            $place_of_birth = Input::get('place_of_birth_' . $row . '');
            $labour_law = Input::get('labour_law_' . $row . '');
            $eobi_number = Input::get('eobi_number_' . $row . '');
            $life_time_cnic = Input::get('life_time_cnic_' . $row . '');
            $joining_date = Input::get('joining_date_' . $row . '');

            $relegion = Input::get('relegion_' . $row . '');
            $nationality = Input::get('nationality_' . $row . '');
            $working_hours_policy_id = Input::get('working_hours_policy_1');
            $residential_address = Input::get('residential_address_' . $row . '');
            $permanent_address = Input::get('permanent_address');
            $present_address = Input::get('present_address');

            $provident_fund_id = Input::get('provident_fund_1');
            $role_id = Input::get('role_' . $row . '');
            $bank_id = Input::get('bank_id');
            $account_title = Input::get('account_title');
            $bank_account = Input::get('account_no');

            $str = DB::selectOne("select max(convert(substr(`emp_no`,4,length(substr(`emp_no`,4))-4),signed integer)) reg from `employee` where substr(`emp_no`,-4,2) = " . date('m') . " and substr(`emp_no`,-2,2) = " . date('y') . "")->reg;
            $employee_no = 'Emp' . ($str + 1) . date('my');


            $data1['relegion'] = strip_tags($relegion);
            $data1['nationality'] = strip_tags($nationality);
            $data1['designation_id'] = strip_tags($designation);
            $data1['tax_id'] = strip_tags($tax_id);
            $data1['eobi_id'] = strip_tags($eobi_id);
            $data1['emp_id'] = $emp_id;
            $data1['leaves_policy_id'] = strip_tags($leaves_policy);
            $data1['emp_no'] = strip_tags($employee_no);
            $data1['emp_name'] = strip_tags($employee_name);
            $data1['emp_father_name'] = strip_tags($father_name);
            $data1['emp_department_id'] = strip_tags($department_id);
            $data1['emp_sub_department_id'] = strip_tags($sub_department_id);
            $data1['emp_date_of_birth'] = strip_tags($date_of_birth);
            $data1['emp_place_of_birth'] = strip_tags($place_of_birth);
            $data1['labour_law'] = strip_tags($labour_law);
            $data1['emp_joining_date'] = strip_tags($joining_date);
            $data1['emp_gender'] = strip_tags($gender);
            $data1['emp_cnic'] = strip_tags($cnic);
            $data1['emp_cnic_expiry_date'] = strip_tags($cnic_expiry_date);
            $data1['emp_contact_no'] = strip_tags($contact_no);
            $data1['contact_home'] = strip_tags($contact_home);
            $data1['landline_number'] = strip_tags($landline_number);

            $data1['present_address'] = strip_tags($present_address);
            $data1['mark_of_identification'] = strip_tags($mark_of_identification);
            $data1['emp_employementstatus_id'] = strip_tags($employee_status);
            $data1['emp_salary'] = strip_tags($salary);
            $data1['working_hours_policy_id'] = strip_tags($working_hours_policy_id);
            $data1['emp_email'] = strip_tags($email);
            $data1['residential_address'] = strip_tags($residential_address);
            $data1['permanent_address'] = strip_tags($permanent_address);
            $data1['blood_group'] = strip_tags($blood_group);
            $data1['emp_marital_status'] = strip_tags($marital_status);
            $data1['eobi_number'] = strip_tags($eobi_number);
            $data1['life_time_cnic'] = strip_tags($life_time_cnic);
            $data1['account_title'] = strip_tags($account_title);
            $data1['bank_account'] = strip_tags($bank_account);
            $data1['bank_id'] = strip_tags($bank_id);

            $data1['can_login'] = (Input::get('can_login_' . $row . '') ? 'yes' : 'no');
            $data1['img_path'] = $path;
            $data1['professional_email'] = Input::get('professional_email');
            $data1['employee_project_id'] = Input::get('employee_project_id');
            $data1['reporting_manager'] = Input::get('reporting_manager');
            if($employee_status == 7){
                $data1['probation_expire_date'] = null;
            }
            else{
                $data1['probation_expire_date'] = Input::get('pTimePeriod');
            }
            $data1['provident_fund_id'] = $provident_fund_id;
            $data1['role_id']           = strip_tags($role_id);

            $data1['emergency_no']=$emergency_no;
            $data1['emp_emergency_relation_address']=$emergency_address;
            $data1['emp_emergency_relation_name']=$emergency_person_name;
            $data1['emp_emergency_relation']=$emergency_person_relation;



            $data1['status'] = 5;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

//            foreach ($days_off_1 as $value) {
//                $days .= $value . '=>';
//            }

            //$data1['day_off'] = $days;
            $last_user_id = DB::table('employee')->insertGetId($data1);
            CommonHelper::reconnectMasterDatabase();

            /*Basic info end*/

            if (Input::get('can_login_' . $row . '')):
                CommonHelper::reconnectMasterDatabase();
                $email = Input::get('email_' . $row . '');
                $employee_password = Input::get('password_' . $row . '');
                $employee_name = Input::get('employee_name_' . $row . '');
                $employee_account_type = Input::get('account_type_' . $row . '');


                $dataCredentials['name'] = $employee_name;
                $dataCredentials['username'] = Input::get('professional_email');
                $dataCredentials['email'] = Input::get('professional_email');
                $dataCredentials['password'] = Hash::make($employee_password);
                $dataCredentials['acc_type'] = $employee_account_type;
                $dataCredentials['emp_id'] = $emp_id;
                $dataCredentials['company_id'] = Input::get('company_id');
                $dataCredentials['updated_at'] = date("Y-m-d");
                $dataCredentials['created_at'] = date("Y-m-d");
                $dataCredentials['company_id'] = Input::get('company_id');

                DB::table('users')->insert($dataCredentials);

                $MenuPrivilegess = Role::where([['id','=',Input::get('role_' . $row . '')]]);

                if($MenuPrivilegess->count() > 0){

                    MenuPrivileges::where('emp_id', Input::get('emp_id'))->delete();


                    $MenuPrivileges                         = new MenuPrivileges();
                    $MenuPrivileges->emp_id                 = Input::get('emp_id');
                    $MenuPrivileges->employee_project_id    = Input::get('employee_project_id');
                    $MenuPrivileges->emp_sub_department_id  = Input::get('sub_department_id_1');
                    $MenuPrivileges->main_modules           = $MenuPrivilegess->value('main_modules');
                    $MenuPrivileges->submenu_id             = $MenuPrivilegess->value('submenu_id');
                    $MenuPrivileges->menu_titles            = $MenuPrivilegess->value('menu_titles');
                    $MenuPrivileges->crud_rights            = $MenuPrivilegess->value('crud_rights');
                    $MenuPrivileges->company_list           = $MenuPrivilegess->value('company_list');
                    $MenuPrivileges->status                 = 1;
                    $MenuPrivileges->username               = Auth::user()->name;
                    $MenuPrivileges->save();
                }
            endif;
        }

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        /*family data start*/
        if (!empty(Input::get('family_data'))):
            foreach (Input::get('family_data') as $familyRow):
                $familyData['emp_id'] = $emp_id;
                $familyData['family_name'] = Input::get('family_name_' . $familyRow . '');
                $familyData['family_relation'] = Input::get('family_relation_' . $familyRow . '');
                $familyData['family_emergency_contact'] = Input::get('family_relation_' . $familyRow . '');
                $familyData['status'] = 1;
                $familyData['username'] = Auth::user()->name;
                $familyData['date'] = date("Y-m-d");
                $familyData['time'] = date("H:i:s");
                DB::table('employee_family_data')->insert($familyData);
            endforeach;
        endif;
        /*family data end*/

        /*Bank Account data start*/
        if (Input::get('bank_account_check_1') == 'on'):
            $bankData['emp_id'] = $emp_id;
            $bankData['account_no'] = Input::get('account_no');
            $bankData['account_title'] = Input::get('account_title');
            $bankData['status'] = 1;
            $bankData['username'] = Auth::user()->name;
            $bankData['date'] = date("Y-m-d");
            $bankData['time'] = date("H:i:s");
            DB::table('employee_bank_data')->insert($bankData);


        endif;
        /*Bank Account data end*/
        /*Educational data start*/
        if (!empty(Input::get('education_data'))):
            foreach (Input::get('education_data') as $educationalRow):
                $educationalData['emp_id'] = $emp_id;
                $educationalData['institute_name'] = Input::get('institute_name_' . $educationalRow . '');
                $educationalData['subject'] = Input::get('subject_' . $educationalRow . '');
                $educationalData['grade'] = Input::get('grade_' . $educationalRow . '');
                $educationalData['year_of_passing'] = Input::get('year_of_passing_' . $educationalRow . '');
                $educationalData['degree_type'] = Input::get('degree_type_' . $educationalRow . '');
                $educationalData['status'] = 1;
                $educationalData['username'] = Auth::user()->name;
                $educationalData['date'] = date("Y-m-d");
                $educationalData['time'] = date("H:i:s");
                DB::table('employee_educational_data')->insert($educationalData);

            endforeach;
        endif;
        /*Educational data end*/

        /*Educational data start*/
        if (!empty(Input::get('nominee_behalf_data'))):
            foreach (Input::get('nominee_behalf_data') as $nomineeRow):
                $nomineeBehalfData['emp_id'] = $emp_id;
                $nomineeBehalfData['name_of_nominee'] = Input::get('name_of_nominee_' . $nomineeRow . '');
                $nomineeBehalfData['cnic_of_nominee'] = Input::get('cnic_of_nominee_' . $nomineeRow . '');
                $nomineeBehalfData['address_of_nominee'] = Input::get('address_of_nominee_' . $nomineeRow . '');
                $nomineeBehalfData['relation_of_nominee'] = Input::get('relation_of_nominee_' . $nomineeRow . '');
                $nomineeBehalfData['share_of_nominee'] = Input::get('share_of_nominee_' . $nomineeRow . '');
                $nomineeBehalfData['status'] = 1;
                $nomineeBehalfData['username'] = Auth::user()->name;
                $nomineeBehalfData['date'] = date("Y-m-d");
                $nomineeBehalfData['time'] = date("H:i:s");
                DB::table('nominee_behalf_data')->insert($nomineeBehalfData);

            endforeach;
        endif;
        /*Educational data end*/

        /*Language data end*/
        if (!empty(Input::get('language_data'))):
            foreach (Input::get('language_data') as $languageRow):
                $languageData['emp_id'] = $emp_id;
                $languageData['language_name'] = Input::get('language_name_' . $languageRow . '');
                $languageData['reading_skills'] = Input::get('reading_skills_' . $languageRow . '');
                $languageData['writing_skills'] = Input::get('writing_skills_' . $languageRow . '');
                $languageData['speaking_skills'] = Input::get('speaking_skills_' . $languageRow . '');
                $languageData['status'] = 1;
                $languageData['username'] = Auth::user()->name;
                $languageData['date'] = date("Y-m-d");
                $languageData['time'] = date("H:i:s");

                DB::table('employee_language_proficiency')->insert($languageData);
            endforeach;
        endif;
        /*Language data end*/

        /*Health data end*/
        if (!empty(Input::get('health_data'))):
            foreach (Input::get('health_data') as $healthRow):
                $healthData['emp_id'] = $emp_id;
                $healthData['health_type'] = Input::get('health_type_' . $healthRow . '');
                $healthData['health_check'] = Input::get('health_check_' . $healthRow . '');
                $healthData['physical_handicap'] = Input::get('physical_handicap');
                $healthData['height'] = Input::get('height');
                $healthData['weight'] = Input::get('weight');
                $healthData['blood_group'] = Input::get('blood_group');
                $healthData['status'] = 1;
                $healthData['username'] = Auth::user()->name;
                $healthData['date'] = date("Y-m-d");
                $healthData['time'] = date("H:i:s");

                DB::table('employee_health_data')->insert($healthData);
            endforeach;
        endif;
        /*Activity data end*/
        if (!empty(Input::get('activity_data'))):
            foreach (Input::get('activity_data') as $activityRow):
                $activityData['emp_id'] = $emp_id;
                $activityData['institution_name'] = Input::get('institution_name_' . $activityRow . '');
                $activityData['position_held'] = Input::get('position_held_' . $activityRow . '');
                $activityData['status'] = 1;
                $activityData['username'] = Auth::user()->name;
                $activityData['date'] = date("Y-m-d");
                $activityData['time'] = date("H:i:s");
                DB::table('employee_activity_data')->insert($activityData);
            endforeach;
        endif;
        /*Activity data end*/

        /*work experience data start*/
        $counter = 1;
        if (!empty(Input::get('work_experience_data'))):
            foreach (Input::get('work_experience_data') as $workExperienceRow):

                if ($request->hasFile('work_exp_path_1')):

                    $extension = $request->file('work_exp_path_' . $workExperienceRow . '')->getClientOriginalExtension();
                    $file_name3 = $emp_id . '_' . $counter . '_' . time() . '.' . $request->file('work_exp_path_' . $workExperienceRow . '')->getClientOriginalExtension();
                    $path3 = 'app/' . $request->file('work_exp_path_' . $workExperienceRow . '')->storeAs('uploads/employee_experience_documents', $file_name3);

                    $workExperienceData['work_exp_path'] = $path3;
                    $workExperienceData['work_exp_name'] = $file_name3;
                    $workExperienceData['work_exp_type'] = $extension;
                else:
                    $workExperienceData['work_exp_path'] = null;
                    $workExperienceData['work_exp_name'] = null;
                    $workExperienceData['work_exp_type'] = null;
                endif;

                $counter++;
                $workExperienceData['emp_id'] = $emp_id;
                $workExperienceData['employeer_name'] = Input::get('employeer_name_' . $workExperienceRow . '');
                $workExperienceData['started'] = Input::get('started_' . $workExperienceRow . '');
                $workExperienceData['ended'] = Input::get('ended_' . $workExperienceRow . '');

                $workExperienceData['emp_designation'] = Input::get('emp_designation_' . $workExperienceRow . '');
                $workExperienceData['emp_depart'] = Input::get('emp_depart_' . $workExperienceRow . '');
                $workExperienceData['emp_last_drawn_salary'] = Input::get('emp_last_drawn_salary_' . $workExperienceRow . '');
                $workExperienceData['reason_for_separation'] = Input::get('reason_for_separation_' . $workExperienceRow . '');

                $workExperienceData['suspend_check'] = Input::get('suspend_check_1');
                $workExperienceData['suspend_reason'] = Input::get('suspend_reason_1');

                $workExperienceData['status'] = 1;
                $workExperienceData['username'] = Auth::user()->name;
                $workExperienceData['date'] = date("Y-m-d");
                $workExperienceData['time'] = date("H:i:s");
                DB::table('employee_work_experience')->insert($workExperienceData);

            endforeach;
        endif;
        /*work experience data end*/

        /*Reference data start*/
        if (!empty(Input::get('reference_data'))):
            foreach (Input::get('reference_data') as $referenceRow):
                $referenceData['emp_id'] = $emp_id;
                $referenceData['reference_name'] = Input::get('reference_name_' . $referenceRow . '');
                $referenceData['reference_designation'] = Input::get('reference_designation_' . $referenceRow . '');
                $referenceData['reference_age'] = Input::get('reference_age_' . $referenceRow . '');
                $referenceData['reference_contact'] = Input::get('reference_contact_' . $referenceRow . '');
                $referenceData['reference_relationship'] = Input::get('reference_relationship_' . $referenceRow . '');
                $referenceData['status'] = 1;
                $referenceData['username'] = Auth::user()->name;
                $referenceData['date'] = date("Y-m-d");
                $referenceData['time'] = date("H:i:s");
                DB::table('employee_reference_data')->insert($referenceData);
            endforeach;
        endif;
        /*Reference data end*/

        /*Emergency data start*/
        if (!empty(Input::get('emergency_data'))):
            foreach (Input::get('emergency_data') as $emergencyRow):
                $emergencyData['emp_id'] = $emp_id;
                $emergencyData['person_name'] = Input::get('person_name_' . $emergencyRow . '');
                $emergencyData['persons_relation'] = Input::get('persons_relation_' . $emergencyRow . '');
                $emergencyData['persons_address'] = Input::get('persons_address_' . $emergencyRow . '');
                $emergencyData['emergency_no'] = Input::get('emergency_no_' . $emergencyRow . '');
                $emergencyData['status'] = 1;
                $emergencyData['username'] = Auth::user()->name;
                $emergencyData['date'] = date("Y-m-d");
                $emergencyData['time'] = date("H:i:s");
                DB::table('employee_emergency_data')->insert($emergencyData);
            endforeach;
        endif;
        /*Emergency data end*/

        /*Reference data start*/
        if (!empty(Input::get('depends_info_data'))):
            foreach (Input::get('depends_info_data') as $dependRow):
                $dependsInfoData['emp_id'] = $emp_id;
                $dependsInfoData['depends_formation_name'] = Input::get('depends_formation_name_' . $dependRow . '');
                $dependsInfoData['depends_formation_relationship'] = Input::get('depends_formation_relationship_' . $dependRow . '');
                $dependsInfoData['depends_formation_date_of_birth'] = Input::get('depends_formation_date_of_birth_' . $dependRow . '');
                $dependsInfoData['depends_formation_age'] = Input::get('depends_formation_age_' . $dependRow . '');
                $dependsInfoData['depends_formation_cnic'] = Input::get('depends_formation_cnic_' . $dependRow . '');
                $dependsInfoData['status'] = 1;
                $dependsInfoData['username'] = Auth::user()->name;
                $dependsInfoData['date'] = date("Y-m-d");
                $dependsInfoData['time'] = date("H:i:s");
                DB::table('employee_depends_info_data')->insert($dependsInfoData);
            endforeach;
        endif;
        /*Reference data end*/



        /*relatives data start*/
        if (!empty(Input::get('relatives_data'))):
            foreach (Input::get('relatives_data') as $relativesRow):
                $relativesData['emp_id'] = $emp_id;
                $relativesData['relative_name'] = Input::get('relative_name_' . $relativesRow . '');
                $relativesData['relative_position'] = Input::get('relative_position_' . $relativesRow . '');
                $relativesData['status'] = 1;
                $relativesData['username'] = Auth::user()->name;
                $relativesData['date'] = date("Y-m-d");
                $relativesData['time'] = date("H:i:s");
                DB::table('employee_relatives')->insert($relativesData);
            endforeach;
        endif;

        /*relatives data end*/

        /*other detail start*/

        $otherDetails['emp_id'] = $emp_id;
        $otherDetails['crime_check'] = Input::get('crime_check_1');
        $otherDetails['crime_detail'] = Input::get('crime_detail_1');
        $otherDetails['additional_info_check'] = Input::get('additional_info_check_1');
        $otherDetails['additional_info_detail'] = Input::get('additional_info_detail_1');
        $otherDetails['status'] = 1;
        $otherDetails['username'] = Auth::user()->name;
        $otherDetails['date'] = date("Y-m-d");
        $otherDetails['time'] = date("H:i:s");
        DB::table('employee_other_details')->insert($otherDetails);

        /*other detail end*/

        /*FileUploding Documents detail start*/
        $counter = 0;
        if ($request->file('media')) {
            foreach ($request->file('media') as $media) {
                if (!empty($media)) {
                    $counter++;
                    $file_name = 'emp_id' . $emp_id . '_mima_' . $counter . '.' . $media->getClientOriginalExtension();
                    $path = $media->storeAs('uploads/employee_documents', $file_name);

                    $fileUploadData['emp_id'] = $emp_id;
                    $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                    $fileUploadData['file_name'] = $file_name;
                    $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                    $fileUploadData['file_path'] = 'app/' . $path;
                    $fileUploadData['status'] = 1;
                    $fileUploadData['counter'] = $counter;
                    $fileUploadData['username'] = Auth::user()->name;
                    $fileUploadData['date'] = date("Y-m-d");
                    $fileUploadData['time'] = date("H:i:s");
                    DB::table('employee_documents')->insert($fileUploadData);
                }
            }
        }

        /*FileUploding Documents  detail end*/

        $log['table_name'] = 'employee';
        $log['activity_id'] = $last_user_id;
        $log['deleted_emr_no'] = null;
        $log['activity'] = 'Insert';
        $log['module'] = 'hr';
        $log['username'] = Auth::user()->name;
        $log['date'] = date("Y-m-d");
        $log['time'] = date("H:i:s");
        DB::table('log')->insert($log);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeList?m=' . Input::get('company_id') . '#Innovative');


    }
    function addEmployeeDetail(Request $request)
    {

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $employeeSection = Input::get('employeeSection');
        $days_off_1 = Input::get('days_off_1');
        $days = '';

        $emergency_no = Input::get('emergency_no_1');
        $emergency_address = Input::get('persons_address');
        $emergency_person_name = Input::get('person_name');
        $emergency_person_relation = Input::get('persons_relation');

        foreach ($employeeSection as $row) {


            /*Image uploading start*/
            if ($request->file('fileToUpload_' . $row . '')):
                $file_name = Input::get('employee_name_' . $row . '') . '_' . time() . '.' . $request->file('fileToUpload_' . $row . '')->getClientOriginalExtension();
                $path = 'app/' . $request->file('fileToUpload_' . $row . '')->storeAs('uploads/employee_images', $file_name);
            else:
                $path = 'app/uploads/employee_images/user-dummy.png';
            endif;
            /*Image uploading end*/

//          cnic upload start
            if ($request->file('cnic_path_' . $row . '')):
                $file_name1 = Input::get('employee_name_' . $row . '') . '_' . time() . '.' . $request->file('cnic_path_' . $row . '')->getClientOriginalExtension();
                $path1 = 'app/' . $request->file('cnic_path_' . $row . '')->storeAs('uploads/employee_cnic_copy', $file_name1);
                $data1['cnic_path'] = $path1;
                $data1['cnic_name'] = $file_name1;
                $data1['cnic_type'] = $request->file('cnic_path_' . $row . '')->getClientOriginalExtension();
            else:
                $data1['cnic_path'] = null;
                $data1['cnic_name'] = null;
                $data1['cnic_type'] = null;
            endif;

//            eobi upload
            if ($request->file('eobi_path_' . $row . '')):
                $file_name1 = Input::get('employee_name_' . $row . '') . '_' . time() . '.' . $request->file('eobi_path_' . $row . '')->getClientOriginalExtension();
                $path1 = 'app/' . $request->file('eobi_path_' . $row . '')->storeAs('uploads/employee_eobi_copy', $file_name1);
                $data1['eobi_path'] = $path1;
                $data1['eobi_type'] = $request->file('eobi_path_' . $row . '')->getClientOriginalExtension();
            else:
                $data1['eobi_path'] = null;
                $data1['eobi_type'] = null;
            endif;


            /*Basic info start*/
            $employee_name = Input::get('employee_name_' . $row . '');
            $emp_id = Input::get('emp_id');
            $father_name = Input::get('father_name_' . $row . '');
            $sub_department_id = Input::get('sub_department_id_' . $row . '');
            $department_id = Input::get('department_id_');
            $date_of_birth = Input::get('date_of_birth_' . $row . '');
            $gender = Input::get('gender_' . $row . '');
            $cnic = Input::get('cnic_' . $row . '');
            $cnic_expiry_date = Input::get('cnic_expiry_date_' . $row . '');
            $contact_no = Input::get('contact_no');
            $landline_number = Input::get('landline_number');

            $employee_status = Input::get('employee_status_' . $row . '');
            $salary = Input::get('compensation');
            $email = Input::get('email_1');
            $marital_status = Input::get('marital_status_' . $row . '');
            $leaves_policy = Input::get('leaves_policy_' . $row . '');
            $designation = Input::get('designation_' . $row . '');
            $tax_id = Input::get('tax_id_' . $row . '');
            $eobi_id = Input::get('eobi_id_' . $row . '');
            $place_of_birth = Input::get('place_of_birth_' . $row . '');
            $labour_law = Input::get('labour_law_' . $row . '');
            $eobi_number = Input::get('eobi_number_' . $row . '');
            $life_time_cnic = Input::get('life_time_cnic_' . $row . '');
            $joining_date = Input::get('joining_date_' . $row . '');

            $relegion = Input::get('relegion_' . $row . '');
            $nationality = Input::get('nationality_' . $row . '');
            $working_hours_policy_id = Input::get('working_hours_policy_1');
            $residential_address = Input::get('residential_address_' . $row . '');
            $permanent_address = Input::get('permanent_address_' . $row . '');
            $provident_fund_id = Input::get('provident_fund_1');
            $role_id = Input::get('role_' . $row . '');
            $bank_id = Input::get('bank_id');
            $account_title = Input::get('account_title');
            $bank_account = Input::get('account_no');

            $str = DB::selectOne("select max(convert(substr(`emp_no`,4,length(substr(`emp_no`,4))-4),signed integer)) reg from `employee` where substr(`emp_no`,-4,2) = " . date('m') . " and substr(`emp_no`,-2,2) = " . date('y') . "")->reg;
            $employee_no = 'Emp' . ($str + 1) . date('my');


            $data1['relegion'] = strip_tags($relegion);
            $data1['nationality'] = strip_tags($nationality);
            $data1['designation_id'] = strip_tags($designation);
            $data1['tax_id'] = strip_tags($tax_id);
            $data1['eobi_id'] = strip_tags($eobi_id);
            $data1['emp_id'] = $emp_id;
            $data1['leaves_policy_id'] = strip_tags($leaves_policy);
            $data1['emp_no'] = strip_tags($employee_no);
            $data1['emp_name'] = strip_tags($employee_name);
            $data1['emp_father_name'] = strip_tags($father_name);
            $data1['emp_department_id'] = strip_tags($department_id);
            $data1['emp_sub_department_id'] = strip_tags($sub_department_id);
            $data1['emp_date_of_birth'] = strip_tags($date_of_birth);
            $data1['emp_place_of_birth'] = strip_tags($place_of_birth);
            $data1['labour_law'] = strip_tags($labour_law);
            $data1['emp_joining_date'] = strip_tags($joining_date);
            $data1['emp_gender'] = strip_tags($gender);
            $data1['emp_cnic'] = strip_tags($cnic);
            $data1['emp_cnic_expiry_date'] = strip_tags($cnic_expiry_date);
            $data1['emp_contact_no'] = strip_tags($contact_no);
            $data1['contact_home'] = strip_tags($landline_number);

            $data1['emp_employementstatus_id'] = strip_tags($employee_status);
            $data1['emp_salary'] = strip_tags($salary);
            $data1['working_hours_policy_id'] = strip_tags($working_hours_policy_id);
            $data1['emp_email'] = strip_tags($email);
            $data1['residential_address'] = strip_tags($residential_address);
            $data1['permanent_address'] = strip_tags($permanent_address);
            $data1['emp_marital_status'] = strip_tags($marital_status);
            $data1['eobi_number'] = strip_tags($eobi_number);
            $data1['life_time_cnic'] = strip_tags($life_time_cnic);
            $data1['account_title'] = strip_tags($account_title);
            $data1['bank_account'] = strip_tags($bank_account);
            $data1['bank_id'] = strip_tags($bank_id);

            $data1['can_login'] = (Input::get('can_login_' . $row . '') ? 'yes' : 'no');
            $data1['img_path'] = $path;
            $data1['professional_email'] = Input::get('professional_email');
            $data1['employee_project_id'] = Input::get('employee_project_id');
            $data1['reporting_manager'] = Input::get('reporting_manager');
            if($employee_status == 7){
                $data1['probation_expire_date'] = null;
            }
            else{
                $data1['probation_expire_date'] = Input::get('pTimePeriod');
            }
            $data1['provident_fund_id'] = $provident_fund_id;
            $data1['role_id']           = strip_tags($role_id);

            $data1['emergency_no']=$emergency_no;
            $data1['emp_emergency_relation_address']=$emergency_address;
            $data1['emp_emergency_relation_name']=$emergency_person_name;
            $data1['emp_emergency_relation']=$emergency_person_relation;




            $data1['status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            foreach ($days_off_1 as $value) {
                $days .= $value . '=>';
            }

           // $data1['day_off'] = $days;
            $last_user_id = DB::table('employee')->insertGetId($data1);
            CommonHelper::reconnectMasterDatabase();

            /*Basic info end*/

            if (Input::get('can_login_' . $row . '')):
                CommonHelper::reconnectMasterDatabase();
                $email = Input::get('email_' . $row . '');
                $employee_password = Input::get('password_' . $row . '');
                $employee_name = Input::get('employee_name_' . $row . '');
                $employee_account_type = Input::get('account_type_' . $row . '');


                $dataCredentials['name'] = $employee_name;
                $dataCredentials['username'] = Input::get('professional_email');
                $dataCredentials['email'] = Input::get('professional_email');
                $dataCredentials['password'] = Hash::make($employee_password);
                $dataCredentials['acc_type'] = $employee_account_type;
                $dataCredentials['emp_id'] = $emp_id;
                $dataCredentials['company_id'] = Input::get('company_id');
                $dataCredentials['updated_at'] = date("Y-m-d");
                $dataCredentials['created_at'] = date("Y-m-d");
                $dataCredentials['company_id'] = Input::get('company_id');

                DB::table('users')->insert($dataCredentials);

                $MenuPrivilegess = Role::where([['id','=',Input::get('role_' . $row . '')]]);

                if($MenuPrivilegess->count() > 0){

                    MenuPrivileges::where('emp_id', Input::get('emp_id'))->delete();


                    $MenuPrivileges                         = new MenuPrivileges();
                    $MenuPrivileges->emp_id                 = Input::get('emp_id');
                    $MenuPrivileges->employee_project_id    = Input::get('employee_project_id');
                    $MenuPrivileges->emp_sub_department_id  = Input::get('sub_department_id_1');
                    $MenuPrivileges->main_modules           = $MenuPrivilegess->value('main_modules');
                    $MenuPrivileges->submenu_id             = $MenuPrivilegess->value('submenu_id');
                    $MenuPrivileges->menu_titles            = $MenuPrivilegess->value('menu_titles');
                    $MenuPrivileges->crud_rights            = $MenuPrivilegess->value('crud_rights');
                    $MenuPrivileges->company_list           = $MenuPrivilegess->value('company_list');
                    $MenuPrivileges->status                 = 1;
                    $MenuPrivileges->username               = Auth::user()->name;
                    $MenuPrivileges->save();
                }
            endif;
        }

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        /*family data start*/
        if (!empty(Input::get('family_data'))):
            foreach (Input::get('family_data') as $familyRow):
                $familyData['emp_id'] = $emp_id;
                $familyData['family_name'] = Input::get('family_name_' . $familyRow . '');
                $familyData['family_relation'] = Input::get('family_relation_' . $familyRow . '');
                $familyData['family_emergency_contact'] = Input::get('family_relation_' . $familyRow . '');
                $familyData['status'] = 1;
                $familyData['username'] = Auth::user()->name;
                $familyData['date'] = date("Y-m-d");
                $familyData['time'] = date("H:i:s");
                DB::table('employee_family_data')->insert($familyData);
            endforeach;
        endif;
        /*family data end*/

        /*Bank Account data start*/
        if (Input::get('bank_account_check_1') == 'on'):
            $bankData['emp_id'] = $emp_id;
            $bankData['account_no'] = Input::get('account_no');
            $bankData['account_title'] = Input::get('account_title');
            $bankData['status'] = 1;
            $bankData['username'] = Auth::user()->name;
            $bankData['date'] = date("Y-m-d");
            $bankData['time'] = date("H:i:s");
            DB::table('employee_bank_data')->insert($bankData);


        endif;
        /*Bank Account data end*/
        /*Educational data start*/
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
        /*Educational data end*/

        /*Language data end*/
        if (!empty(Input::get('language_data'))):
            foreach (Input::get('language_data') as $languageRow):
                $languageData['emp_id'] = $emp_id;
                $languageData['language_name'] = Input::get('language_name_' . $languageRow . '');
                $languageData['reading_skills'] = Input::get('reading_skills_' . $languageRow . '');
                $languageData['writing_skills'] = Input::get('writing_skills_' . $languageRow . '');
                $languageData['speaking_skills'] = Input::get('speaking_skills_' . $languageRow . '');
                $languageData['status'] = 1;
                $languageData['username'] = Auth::user()->name;
                $languageData['date'] = date("Y-m-d");
                $languageData['time'] = date("H:i:s");

                DB::table('employee_language_proficiency')->insert($languageData);
            endforeach;
        endif;
        /*Language data end*/

        /*Health data end*/
        if (!empty(Input::get('health_data'))):
            foreach (Input::get('health_data') as $healthRow):
                $healthData['emp_id'] = $emp_id;
                $healthData['health_type'] = Input::get('health_type_' . $healthRow . '');
                $healthData['health_check'] = Input::get('health_check_' . $healthRow . '');
                $healthData['physical_handicap'] = Input::get('physical_handicap');
                $healthData['height'] = Input::get('height');
                $healthData['weight'] = Input::get('weight');
                $healthData['blood_group'] = Input::get('blood_group');
                $healthData['status'] = 1;
                $healthData['username'] = Auth::user()->name;
                $healthData['date'] = date("Y-m-d");
                $healthData['time'] = date("H:i:s");

                DB::table('employee_health_data')->insert($healthData);
            endforeach;
        endif;
        /*Activity data end*/
        if (!empty(Input::get('activity_data'))):
            foreach (Input::get('activity_data') as $activityRow):
                $activityData['emp_id'] = $emp_id;
                $activityData['institution_name'] = Input::get('institution_name_' . $activityRow . '');
                $activityData['position_held'] = Input::get('position_held_' . $activityRow . '');
                $activityData['status'] = 1;
                $activityData['username'] = Auth::user()->name;
                $activityData['date'] = date("Y-m-d");
                $activityData['time'] = date("H:i:s");
                DB::table('employee_activity_data')->insert($activityData);
            endforeach;
        endif;
        /*Activity data end*/

        /*work experience data start*/
        $counter = 1;
        if (!empty(Input::get('work_experience_data'))):
            foreach (Input::get('work_experience_data') as $workExperienceRow):

                if ($request->hasFile('work_exp_path_1')):

                    $extension = $request->file('work_exp_path_' . $workExperienceRow . '')->getClientOriginalExtension();
                    $file_name3 = $emp_id . '_' . $counter . '_' . time() . '.' . $request->file('work_exp_path_' . $workExperienceRow . '')->getClientOriginalExtension();
                    $path3 = 'app/' . $request->file('work_exp_path_' . $workExperienceRow . '')->storeAs('uploads/employee_experience_documents', $file_name3);

                    $workExperienceData['work_exp_path'] = $path3;
                    $workExperienceData['work_exp_name'] = $file_name3;
                    $workExperienceData['work_exp_type'] = $extension;
                else:
                    $workExperienceData['work_exp_path'] = null;
                    $workExperienceData['work_exp_name'] = null;
                    $workExperienceData['work_exp_type'] = null;
                endif;

                $counter++;
                $workExperienceData['emp_id'] = $emp_id;
                $workExperienceData['employeer_name'] = Input::get('employeer_name_' . $workExperienceRow . '');
                $workExperienceData['started'] = Input::get('started_' . $workExperienceRow . '');
                $workExperienceData['ended'] = Input::get('ended_' . $workExperienceRow . '');
                $workExperienceData['suspend_check'] = Input::get('suspend_check_1');
                $workExperienceData['suspend_reason'] = Input::get('suspend_reason_1');

                $workExperienceData['status'] = 1;
                $workExperienceData['username'] = Auth::user()->name;
                $workExperienceData['date'] = date("Y-m-d");
                $workExperienceData['time'] = date("H:i:s");
                DB::table('employee_work_experience')->insert($workExperienceData);

            endforeach;
        endif;
        /*work experience data end*/

        /*Reference data start*/
        if (!empty(Input::get('reference_data'))):
            foreach (Input::get('reference_data') as $referenceRow):
                $referenceData['emp_id'] = $emp_id;
                $referenceData['reference_name'] = Input::get('reference_name_' . $referenceRow . '');
                $referenceData['reference_designation'] = Input::get('reference_designation_' . $referenceRow . '');
                $referenceData['reference_age'] = Input::get('reference_age_' . $referenceRow . '');
                $referenceData['reference_contact'] = Input::get('reference_contact_' . $referenceRow . '');
                $referenceData['reference_relationship'] = Input::get('reference_relationship_' . $referenceRow . '');
                $referenceData['status'] = 1;
                $referenceData['username'] = Auth::user()->name;
                $referenceData['date'] = date("Y-m-d");
                $referenceData['time'] = date("H:i:s");
                DB::table('employee_reference_data')->insert($referenceData);
            endforeach;
        endif;
        /*Reference data end*/

        /*kins data start*/
        if (!empty(Input::get('kins_data'))):
            foreach (Input::get('kins_data') as $kinsRow):
                $kinsData['emp_id'] = $emp_id;
                $kinsData['next_kin_name'] = Input::get('next_kin_name_' . $kinsRow . '');
                $kinsData['next_kin_relation'] = Input::get('next_kin_relation_' . $kinsRow . '');
                $kinsData['status'] = 1;
                $kinsData['username'] = Auth::user()->name;
                $kinsData['date'] = date("Y-m-d");
                $kinsData['time'] = date("H:i:s");
                DB::table('employee_kins_data')->insert($kinsData);
            endforeach;
        endif;

        /*kins data end*/

        /*relatives data start*/
        if (!empty(Input::get('relatives_data'))):
            foreach (Input::get('relatives_data') as $relativesRow):
                $relativesData['emp_id'] = $emp_id;
                $relativesData['relative_name'] = Input::get('relative_name_' . $relativesRow . '');
                $relativesData['relative_position'] = Input::get('relative_position_' . $relativesRow . '');
                $relativesData['status'] = 1;
                $relativesData['username'] = Auth::user()->name;
                $relativesData['date'] = date("Y-m-d");
                $relativesData['time'] = date("H:i:s");
                DB::table('employee_relatives')->insert($relativesData);
            endforeach;
        endif;

        /*relatives data end*/

        /*other detail start*/

        $otherDetails['emp_id'] = $emp_id;
        $otherDetails['crime_check'] = Input::get('crime_check_1');
        $otherDetails['crime_detail'] = Input::get('crime_detail_1');
        $otherDetails['additional_info_check'] = Input::get('additional_info_check_1');
        $otherDetails['additional_info_detail'] = Input::get('additional_info_detail_1');
        $otherDetails['status'] = 1;
        $otherDetails['username'] = Auth::user()->name;
        $otherDetails['date'] = date("Y-m-d");
        $otherDetails['time'] = date("H:i:s");
        DB::table('employee_other_details')->insert($otherDetails);

        /*other detail end*/

        /*FileUploding Documents detail start*/
        $counter = 0;
        if ($request->file('media')) {
            foreach ($request->file('media') as $media) {
                if (!empty($media)) {
                    $counter++;
                    $file_name = 'emp_id' . $emp_id . '_mima_' . $counter . '.' . $media->getClientOriginalExtension();
                    $path = $media->storeAs('uploads/employee_documents', $file_name);

                    $fileUploadData['emp_id'] = $emp_id;
                    $fileUploadData['documents_upload_check'] = Input::get('documents_upload_check');
                    $fileUploadData['file_name'] = $file_name;
                    $fileUploadData['file_type'] = $media->getClientOriginalExtension();
                    $fileUploadData['file_path'] = 'app/' . $path;
                    $fileUploadData['status'] = 1;
                    $fileUploadData['counter'] = $counter;
                    $fileUploadData['username'] = Auth::user()->name;
                    $fileUploadData['date'] = date("Y-m-d");
                    $fileUploadData['time'] = date("H:i:s");
                    DB::table('employee_documents')->insert($fileUploadData);
                }
            }
        }

        /*FileUploding Documents  detail end*/

        $log['table_name'] = 'employee';
        $log['activity_id'] = $last_user_id;
        $log['deleted_emr_no'] = null;
        $log['activity'] = 'Insert';
        $log['module'] = 'hr';
        $log['username'] = Auth::user()->name;
        $log['date'] = date("Y-m-d");
        $log['time'] = date("H:i:s");
        DB::table('log')->insert($log);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeList?m=' . Input::get('company_id') . '#Innovative');


    }

    public function uploadEmployeeFileDetail(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $data = Excel::toArray(new UsersImport, request()->file('employeeFile'));
        $counters = 1;


        if (trim(trim($data[0][1][0]) == 'Employee ID' && trim($data[0][1][1] == 'Employee Name') &&
            trim($data[0][1][2]) == 'Father / Husband Name' && trim($data[0][1][3]) == 'Department' && trim($data[0][1][4]) == 'Sub Department' &&
            trim($data[0][1][5]) == 'Designation' && trim($data[0][1][6]) == 'Marital Status' && trim($data[0][1][7]) == 'Employment Status' &&
            trim($data[0][1][8]) == 'Gender' && trim($data[0][1][9]) == 'CNIC' &&
            trim($data[0][1][10]) == 'Date of Birth' && trim($data[0][1][11]) == 'Place of Birth' && trim($data[0][1][12]) == 'Nationality' && trim($data[0][1][13]) == 'Joining Date' && trim($data[0][1][14]) == 'Contact Number' && trim($data[0][1][15]) == 'Landline Number' && trim($data[0][1][16]) == 'Official Email' && trim($data[0][1][17]) == 'Compensation' && trim($data[0][1][18]) == 'Religion' && trim($data[0][1][19]) == 'Eobi' && trim($data[0][1][20]) == 'Leaves Policy')):




            foreach ($data[0] as $value) {

                if ($counters++ == 2) continue;;

                if ($value[0] == '' || $value[0] == 'Employee ID' || $value[0] == 'Employee ID') continue;

                if ($value[0] != '') {

                    $designation = Designation::select('id')->where([['status', '=', 1], ['designation_name', '=', $value[5]]]);
                    if ($designation->count() > 0) {
                        $designation_id = $designation->value('id');
                    } else {
                        Session::flash('errorMsg', $value[5] . ' designation not found. Please create and try again.');
                        return Redirect::to('hr/uploadEmployeeFileForm?pageType=viewlist&&parentCode=8&&m=' . Input::get('company_id') . '#Innovative');
                    }

                    $department = Department::select('id')->where([['status', '=', 1], ['department_name', '=', trim($value[3],' ')]]);


                    if ($department->count() > 0) {

                        $department_id = $department->value('id');
                    } else {
                        Session::flash('errorMsg', $value[3] . ' department not found. Please create and try again.');
                        return Redirect::to('hr/uploadEmployeeFileForm?pageType=viewlist&&parentCode=8&&m=' . Input::get('company_id') . '#Innovative');
                    }

                    $sub_department = SubDepartment::select('id')->where([['status', '=', 1], ['sub_department_name', '=', $value[4]]]);
                    if ($sub_department->count() > 0) {
                        $sub_department_id = $sub_department->value('id');
                    } else {
                        Session::flash('errorMsg', $value[4] . ' Sub Department not found. Please create and try again.');
                        return Redirect::to('hr/uploadEmployeeFileForm?pageType=viewlist&&parentCode=8&&m=' . Input::get('company_id') . '#Innovative');
                    }

                    $MaritalStatus = MaritalStatus::select('id')->where([['status', '=', 1], ['marital_status_name', '=', $value[6]], ['company_id', '=', $company_id]]);
                    if ($MaritalStatus->count() > 0) {
                        $MaritalStatus_id = $MaritalStatus->value('id');
                    } else {
                        Session::flash('errorMsg', $value[6] . ' Martial Status not found. Please create and try again.');
                        return Redirect::to('hr/uploadEmployeeFileForm?pageType=viewlist&&parentCode=8&&m=' . Input::get('company_id') . '#Innovative');
                    }

                    $JobType = JobType::select('id')->where([['status', '=', 1], ['job_type_name', '=', $value[7]], ['company_id', '=', $company_id]]);
                    if ($JobType->count() > 0) {
                        $JobType_id = $JobType->value('id');
                    } else {
                        Session::flash('errorMsg', $value[7] . ' Employee Status not found. Please create and try again.');
                        return Redirect::to('hr/uploadEmployeeFileForm?pageType=viewlist&&parentCode=8&&m=' . Input::get('company_id') . '#Innovative');
                    }

                    $excelDate = $value[10]; //2018-11-03
                    $dob = date("Y-m-d", strtotime($excelDate));

                    $excelDate2 = $value[13]; //2018-11-03
                    $doj = date("Y-m-d", strtotime($excelDate2));

                    $data2['emp_name'] = ($value[1] == '' ? '-' : $value[1]);
                    $data2['emp_father_name'] = ($value[2] == '' ? '-' : $value[2]);
                    $data2['emp_salary'] = ($value[17] == '' ? '-' : $value[17]);
                    $data2['emp_contact_no'] = ($value[15] == '' ? '-' : $value[15]);
                    $data2['emp_id'] = ($value[0] == '' ? '-' : $value[0]);
                    $data2['emp_cnic'] = ($value[9] == '' ? '-' : $value[9]);
                    $data2['emp_date_of_birth'] = ($value[10] == '' ? '-' : $dob);
                    $data2['emp_joining_date'] = ($value[13] == '' ? '-' : $doj);
                    $data2['emp_department_id'] = $department_id;
                    $data2['emp_sub_department_id'] = $sub_department_id;
                    $data2['designation_id'] = $designation_id;
                    $data2['emp_marital_status'] = $MaritalStatus_id;
                    $data2['emp_employementstatus_id'] = $JobType_id;
                    $data2['emp_gender'] = ($value[8] == '' ? '-' : $value[8]);
                    $data2['emp_place_of_birth'] = ($value[11] == '' ? '-' : $value[11]);
                    $data2['nationality'] = ($value[12] == '' ? '-' : $value[12]);
                    $data2['contact_home'] = ($value[15] == '' ? '-' : $value[15]);
                    $data2['emp_email'] = ($value[16] == '' ? '-' : $value[16]);
                    $data2['relegion'] = ($value[18] == '' ? '-' : $value[18]);
                    $data2['eobi_id'] = ($value[19] == '' ? '-' : $value[19]);
                    $data2['leaves_policy_id'] = ($value[20] == '' ?: $value[20]);
                    $data2['working_hours_policy_id'] = ($value[21] == '' ? '-': $value[21]);
                    $data2['day_off'] = ($value[22] == '' ? '-': $value[22]);
                    $data2['bank_account'] = ($value[23] == '' ? '-': $value[23]);
                    $data2['username'] = Auth::user()->name;
                    $data2['date'] = date("Y-m-d");
                    $data2['time'] = date("H:i:s");
                    $data2['emp_gender'] = ($value[8] == '' ? '-' : $value[8]);
                    CommonHelper::companyDatabaseConnection(Input::get('company_id'));
                    //echo '<pre>';
                    // print_r($data2);
                    // DB::table('employee')->where([['emp_id','=', $value[0]]])->delete();
                    //DB::table('employee')->insert($data2);


                    $employeeCount = Employee::where('emp_id','=',$value[0])->count();
                    if ($employeeCount > 0) {
                        DB::table('employee')->where([['emp_id','=', $value[0]]])->update($data2);
                    } else {
                        DB::table('employee')->insert($data2);
                    }

                    CommonHelper::reconnectMasterDatabase();

                }
            }

            CommonHelper::companyDatabaseConnection(Input::get('company_id'));
            $log['table_name'] = 'employee';
            $log['activity_id'] = null;
            $log['deleted_emr_no'] = null;
            $log['activity'] = 'Upload';
            $log['module'] = 'hr';
            $log['username'] = Auth::user()->name;
            $log['date'] = date("Y-m-d");
            $log['time'] = date("H:i:s");
            DB::table('log')->insert($log);
            CommonHelper::reconnectMasterDatabase();

        else:
            Session::flash('errorMsg', 'Please upload file with the given format.');
            return Redirect::to('hr/uploadEmployeeFileForm?m=' . Input::get('company_id') . '#Innovative');
        endif;

        Session::flash('dataInsert', 'Successfully saved.');
        return Redirect::to('hr/uploadEmployeeFileForm?m=' . Input::get('company_id') . '#Innovative');
    }

    function addManageAttendenceDetail()
    {

        FinanceHelper::companyDatabaseConnection(Input::get('m'));
        $sub_department_id = Input::get('sub_department_id_1');
        $attendence_date = Input::get('attendence_date');
        $emp_id = Input::get('emp_id_');
        foreach ($emp_id as $row1) {
            $attendence_type = Input::get('attendence_status_' . $row1 . '');
            $attendence_remarks = Input::get('attendence_remarks_' . $row1 . '');

            $data1['emp_id'] = strip_tags($row1);
            $data1['sub_department_id'] = strip_tags($sub_department_id);
            $data1['attendence_date'] = strip_tags($attendence_date);
            $data1['attendence_type'] = strip_tags($attendence_type);
            $data1['remarks'] = strip_tags($attendence_remarks);
            $data1['username'] = Auth::user()->name;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $attendance = Attendence::where([['attendence_date', '=', Input::get('attendence_date')], ['emp_id', '=', $row1]]);

            if ($attendance->count() > 0):
                DB::table('attendence')->where([['attendence_date', '=', Input::get('attendence_date')], ['emp_id', '=', $row1]])->update($data1);
            else:
                DB::table('attendence')->insert($data1);
            endif;
        }

        FinanceHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeAttendanceList?pageType=viewlist&&parentCode=8&&m=' . Input::get('m') . '#Innovative');
    }


    function addPayrollDetail()
    {
        $employeeArray = [];
        $recordNotFound = [];

        $sub_department = Input::get('sub_department_id');
        $department_id = Input::get("department_id");
        $getEmployee = Input::get('emp_id');
        $payslip_month = Input::get('payslip_month');
        $explodeMonthYear = explode('-', $payslip_month);
        $monthYearDay=$explodeMonthYear[0].'-'.$explodeMonthYear[1].'-01';
        $all_emp_id = HrHelper::getAllEmpId($department_id,$sub_department,Input::get('m'));
        $tax_slabs = unserialize(Input::get('slabs'));
        
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        if(Input::get('show_all') == 'show_all'){

            $employees= DB::select(DB::raw("SELECT * FROM employee WHERE status = 1 AND DATE_FORMAT(emp_joining_date,'%Y-%m') <= DATE_SUB('$monthYearDay', INTERVAL 1 MONTH) ORDER by emp_id ASC") );
            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'emp_joining_date','tax_id','provident_fund_id','eobi_id','bank_account','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->whereRaw("status = 1 AND DATE_FORMAT(emp_joining_date,'%Y-%m') <='$monthYearDay' ")
                ->orderBy('emp_id')->get();

        }
        elseif($getEmployee == 'all' && $sub_department == '0'){


            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'emp_joining_date','bank_account','tax_id','provident_fund_id','eobi_id','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->whereIn('emp_id',$all_emp_id)
                ->whereRaw("status = 1 AND DATE_FORMAT(emp_joining_date,'%Y-%m') <= '$monthYearDay'")
                ->orderBy('emp_id')
                ->get();
        }
        elseif($getEmployee == 'all' &&  $sub_department != '0'){

            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'emp_joining_date','bank_account','tax_id','provident_fund_id','eobi_id','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->whereIn('emp_id',$all_emp_id)
                ->whereRaw("status = 1 AND DATE_FORMAT(emp_joining_date,'%Y-%m') <= '$monthYearDay'")
                ->orderBy('emp_id')
                ->get();
//
        }
        else{

            $employees = Employee::select('id','designation_id','emp_cnic','emp_sub_department_id','working_hours_policy_id',
                'emp_joining_date','bank_account','tax_id','provident_fund_id','eobi_id','emp_name','emp_father_name','emp_salary','emp_id','employee_project_id')
                ->where([['emp_id','=',$getEmployee],['status', '=', '1']])->orderBy('emp_id')->get();
        }



        foreach($employees as $row1){


            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $designation_id = $row1['designation_id'];
            $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',$row1['emp_id']],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');

            if($employeeCurrentPositions->count() > 0):
                $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
                $designation_id = $employeeCurrentPositionsDetail->designation_id;
            endif;


            $emp_name = $row1['emp_name'];
            $father_name = $row1['emp_father_name'];
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $payroll_deduction = PayrollData::where([['emp_id', '=', $row1['emp_id']],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]]);




            $deduction_days = $payroll_deduction->value('deduction_days');
            $employeeArray[] = $row1['id'];


            $bonus = BonusIssue::where([['status', '=', 1],['emp_id', '=', $row1['emp_id']],['bonus_year', '=', $explodeMonthYear[0]],['bonus_month', '=', $explodeMonthYear[1]]]);
            $loanRequest = LoanRequest::where([['status','=','1'],['approval_status','=',2],['loan_status','=',0],['emp_id', '=', $row1['emp_id']]]);
            $advanceSalary = AdvanceSalary::where([['status','=','1'],['approval_status','=',2],['emp_id', '=', $row1['emp_id']],['deduction_year', '=', $explodeMonthYear[0]],['deduction_month', '=', $explodeMonthYear[1]]]);


            $loanRequest = LoanRequest::select('id','per_month_deduction')->where([['approval_status','=',2],['status', '=', 1],['loan_status','=',0],['emp_id', '=', $row1['emp_id']]]);

            $loan_perMonthDeduction=0;
            $loan_id=0;
            if($loanRequest->count() > 0):
                $loan_perMonthDeduction = $loanRequest->value('per_month_deduction');
                $loan_id = $loanRequest->value('id');
            endif;
            $advanceSalaryAmount = 0;
            if($advanceSalary->count() > 0):
                $advanceSalaryAmount=$advanceSalary->value("advance_salary_amount");
            endif;
            $loanRequestDetail = $loanRequest->get()->toArray();
            $employee_deposit_amount = 0;
            $employee_deposit_name = "-";

            $bonus_amount = 0;
//            if($bonus->count() > 0):
//                $bonus_issue = $bonus->first();
//                $bonus_name = Bonus::select('bonus_name')->where([['id', '=', $bonus_issue->bonus_id]])->value('bonus_name');
//                $bonus_amount = $bonus_issue->bonus_amount;
//            endif;
            $LWPD=0;
            $LWP=0;
            $LWP_once=0;
            $penalty=0;
            $penalty_once=0;
            $other_once=0;
            $allowance = Allowance::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1]]);
            $LWP=Deduction::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1],['deduction_type','=','LWP']]);
            $LWPD=Deduction::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1],['deduction_type','=','LWP'],['once','!=','1']])->sum('deduction_amount');
            $LWP_once=Deduction::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1],['deduction_type','=','LWP'],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->value('deduction_amount');
            $LWPD+=$LWP_once;
            $LWPD+=$deduction_days;
            $penalty_once=Deduction::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1],['deduction_type', '=','Penalty'],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->value('deduction_amount');
            $other_once=Deduction::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1],['deduction_type', '=','Other'],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->value('deduction_amount');

            $penalty=Deduction::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1],['deduction_type','=','Penalty'],['once','!=','1']])->sum('deduction_amount');
            $penalty+=$penalty_once;
            $Others=Deduction::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1],['deduction_type','=','Other'],['once','!=','1']])->sum('deduction_amount');;
            $Others+=$other_once;
            $deduction = Deduction::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1],['deduction_type','!=','LWP'],['once','!=','1']])->sum('deduction_amount');
            $deduction+=($penalty_once+$other_once);
            $promoted_salary = EmployeePromotion::select('salary','emp_id')->where([['emp_id','=',$row1['emp_id']],['status','=',1]])->orderBy('id', 'desc');
            if($promoted_salary->count() > 0):
                $emp_salary = $promoted_salary->value('salary');
            else:
                $emp_salary = $row1['emp_salary'];
            endif;

            $bankAccNo = $row1['bank_account'];

            CommonHelper::reconnectMasterDatabase();

            $eobi_deduct = 0;    
            if($row1['eobi_id'] != '0'):
                $eobi = Eobi::where([['id','=',$row1['eobi_id']],['company_id','=',Input::get('m')],['status','=','1']]);
                if($eobi->count() > 0){
                    $eobi_deduct = $eobi->value('EOBI_amount');
                }
                else{
                    $eobi_deduct = 0;
                }
            else:
                $eobi_deduct = 0;
            endif;

            /*Provident Fund start*/
            $provident_fund_data = DB::table('provident_fund')->select('id','pf_mode','amount_percent')->where([['id','=',$row1['provident_fund_id']]]);

            /*Provident Fund end*/
            $pf_amount=0;
            $provident_fund_check = false;
            $pf_company_fund=0;
            $pf_employee_fund=0;
            $pf_id = 0;
            if($provident_fund_data->count() > 0):
                $provident_fund_check = true;
                $provident_fund = $provident_fund_data->first();
                if($provident_fund->id == 0){
                    $pf_id = 0;
                }
                else{
                    $pf_id = $provident_fund->id;
                }

                if($provident_fund->pf_mode == 'percentage'):

                    $pf_company_fund = round(($provident_fund->amount_percent/100)*($emp_salary/3*2));
                    $pf_employee_fund = round(($provident_fund->amount_percent/100)*($emp_salary/3*2));
                    $pf_amount = $pf_employee_fund;
                else:
                    $pf_amount = $provident_fund->amount_percent;
                endif;
            endif;

            $count_deduction =0;
            $other_allow = 0;
            $arear = 0;
            $netSalary=0;
            $count_allowance = 0;
            $payroll_deduct_amount=0;


            $grossSalary = $emp_salary;


            CommonHelper::companyDatabaseConnection(Input::get('m'));

            //Allowance Names
            $vr_other_allow = Allowance::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1],['allowance_type_id', '=', 1]])->value('allowance_amount');
            $other_allow = Allowance::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1],['allowance_type_id', '=', 6],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->sum('allowance_amount');
            $arear = Allowance::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1],['allowance_type_id', '=', 5],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->sum('allowance_amount');

            $vehicle_addit = Allowance::where([['emp_id', '=', $row1['emp_id']],['status', '=', 1],['allowance_type_id', '=', 4]])->value('allowance_amount');

            //$deduction_days = PayrollData::where([['emp_id', '=', $row1['emp_id']],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]]);
            $perDaySalary = ($emp_salary)/(30);

            $rate_of_pay = ($perDaySalary*30);

            $payroll_deduct_amount = ($LWPD*$perDaySalary);
            $payroll_ot_amount = 0;
            // $payroll_ot_amount= round(($deduction_days->total_ot_hours)*(($emp_salary/30)));
            $count_allowance=($arear+$other_allow+$vr_other_allow+$vehicle_addit);
            $grossSalaryWithAllownace=($grossSalary+$count_allowance);
            $loan_amount = Input::get('loan_amount_' . $row1['id'] . '');
            $total_deduction        = ($deduction+$loan_amount+$payroll_deduct_amount+$count_deduction+$pf_amount+$eobi_deduct+$advanceSalaryAmount);


            $total_deduction2        = ($deduction+$payroll_deduct_amount+$count_deduction+$pf_amount+$eobi_deduct+$advanceSalaryAmount);
            $payable_salary         = ($rate_of_pay + $bonus_amount + $count_allowance + $payroll_ot_amount);
            $payable_wihtoutdays   = ($emp_salary + $bonus_amount + $count_allowance + $payroll_ot_amount);
            $payable_wihtoutdays_taxable  = ($emp_salary-$payroll_deduct_amount)+(  $bonus_amount  + $payroll_ot_amount);
            CommonHelper::reconnectMasterDatabase();

            $emp_month = Carbon::createFromFormat('Y-m-d', $row1['emp_joining_date'])->month;
            $emp_year = Carbon::createFromFormat('Y-m-d', $row1->emp_joining_date)->year;
            $pay_month= $explodeMonthYear[1];
            $pay_year= $explodeMonthYear[0];

            $divided_tax = HrHelper::getIncomeTax($payable_wihtoutdays_taxable,$row1->emp_joining_date,$emp_month,$payslip_month,$pay_month,$emp_year,$pay_year,$tax_slabs);


            CommonHelper::companyDatabaseConnection(Input::get('m'));

            $tax_deduct = $divided_tax;
            $total_deduction +=$divided_tax;
            $total_deduction2 += $divided_tax;

            $netSalary  = ($payable_wihtoutdays - $total_deduction);


            $employee_salary = $emp_salary;
            $basic_salary = round($employee_salary / 3 * 2);
            $fix_medical = round($basic_salary / 100 * 10);
            $add_basic_add_medical = $basic_salary + $fix_medical;
            $hr_utility_allowance = round($employee_salary - $add_basic_add_medical);
            //$result =  $employees->get()->toArray();
            $counter = 1;

            $str = DB::selectOne("select max(convert(substr(`slipno`,4,length(substr(`slipno`,4))-4),signed integer)) reg from `payslip` where substr(`slipno`,-4,2) = " . date('m') . " and substr(`slipno`,-2,2) = " . date('y') . "")->reg;
            $slipno = 'slipno' . ($str + 1) . date('my');
            $payslip_month = Input::get('payslip_month');
            $emp_id = $row1['emp_id'];

            //$pf_amount = Input::get('pf_amount_' . $row1['id'] . '');

            $loan_id = Input::get('loan_id_' . $row1['id'] . '');
            $payment_mode = Input::get('payment_mode_' . $row1['id'] . '');
            $explodeMonthYear = explode('-', $payslip_month);
            $pf_id = Input::get('pf_id_' . $row1['id'] . '');
            //$pf_amount = Input::get('pf_amount_' . $row1['id'] . '');
//            $pf_employee_fund = Input::get('pf_employee_fund_' . $row1['id'] . '');
//            $pf_company_fund = Input::get('pf_company_fund_' . $row1['id'] . '');

            if(Input::get('provident_fund_check_' . $row1['id'] . '') == true):

                DB::table('provident_fund_data')->where([['emp_id', '=', $emp_id],['month', '=', $explodeMonthYear[1]],['year', '=', $explodeMonthYear[0]]])->delete();

                $PfData['provident_fund_id']    =$pf_id;
                $PfData['emp_id']               =$emp_id;
                $PfData['pf_amount']            =$pf_amount;
                $PfData['pf_employee_fund']     =$pf_employee_fund;
                $PfData['pf_company_fund']      =$pf_company_fund;
                $PfData['amount_type']          ='plus';
                $PfData['month']                = $explodeMonthYear[1];
                $PfData['year']                 = $explodeMonthYear[0];
                $PfData['username']             = Auth::user()->name;
                $PfData['status']               = 1;
                $PfData['date']                 = date("Y-m-d");
                $PfData['time']                 = date("H:i:s");
                DB::table('provident_fund_data')->insert($PfData);
            endif;



            //$data3['counter'] = strip_tags($counter++);
            $data3['emp_id'] = $emp_id;
            $data3['account_no'] = $bankAccNo;
            $data3['Arrears'] = $arear ?? 0;
            $data3['other_allowance'] = $other_allow ?? 0;
            $data3['vr_other_allow'] = $vr_other_allow ?? 0;
            $data3['vehicle_addit_'] = $vehicle_addit ?? 0;
            $data3['gross_salaries'] = $grossSalary ?? 0;
            $data3['fix_medical'] = $fix_medical ?? 0;
            $data3['hr_utility_allowance'] = $hr_utility_allowance ?? 0;
            $data3['total_deduction'] = round($total_deduction ?? 0);
            $data3['slipno'] = $slipno;

            $data3['month'] = strip_tags($explodeMonthYear[1]);
            $data3['year'] = strip_tags($explodeMonthYear[0]);
            $data3['basic_salary'] =$basic_salary ?? 0;
            $data3['payable_salary'] = $payable_salary ?? 0;
            $data3['payable_days_amount'] = strip_tags(0);
            $data3['salary_status'] = strip_tags(0);
            $data3['payment_mode'] = $payment_mode;
            $data3['loan_amount_paid'] = strip_tags($loan_amount);
            $data3['loan_id'] = strip_tags($loan_id);

            $data3['lwp_deduction'] = $payroll_deduct_amount ?? 0;
            $data3['other_deduct'] = $Others ?? 0;
            $data3['penalty'] = $penalty ?? 0;
            $data3['advance_salary_amount'] = $advanceSalaryAmount ?? 0;
            $data3['net_salary'] = round($netSalary);
            $data3['eobi_amount'] = $eobi_deduct ?? 0;
            $data3['taxable_salary'] = ($tax_deduct> 0) ? ($payable_wihtoutdays_taxable/1.1) : "0";
            $data3['tax_amount'] = $tax_deduct ?? 0;
            $data3['pf_amount'] = $pf_amount;
            $data3['pf_employee_fund']=$pf_employee_fund;
            $data3['pf_company_fund']=$pf_company_fund;
            $data3['total_allowance'] = round($allowance->sum('allowance_amount') ?? 0);
            $data3['bonus_amount'] = $bonus_amount ?? 0;
            $data3['total_salary'] = $basic_salary ?? 0;
            $data3['username'] = Auth::user()->name;
            $data3['status'] = 1;
            $data3['date'] = date("Y-m-d");
            $data3['time'] = date("H:i:s");
            Payslip::where([['emp_id', '=', $emp_id], ['year', '=', $explodeMonthYear[0]], ['month', '=', $explodeMonthYear[1]]])->delete();
            DB::table('payslip')->insert($data3);
        }



        $log['table_name'] = 'payslip';
        $log['activity_id'] = null;
        $log['deleted_emr_no'] = null;
        $log['activity'] = 'Insert';
        $log['module'] = 'hr';
        $log['username'] = Auth::user()->name;
        $log['date'] = date("Y-m-d");
        $log['time'] = date("H:i:s");
        DB::table('log')->insert($log);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createPayrollForm?m=' . Input::get('m') . '#Innovative');

    }



    public function addEmailPayslipDetail()
    {


        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_year = explode('-', Input::get('month_year'));
        if (Input::get('check_list')):
            foreach (Input::get('check_list') as $key => $value):
                $empId_and_Email = (explode("<>", $value));
                $data1['emp_id'] = $empId_and_Email[1];
                $data1['month'] = $month_year[1];
                $data1['year'] = $month_year[0];
                $data1['username'] = Auth::user()->name;
                $data1['status'] = 1;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");
                DB::table('email_queue')->insert($data1);

            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved.');
        return Redirect::to('hr/emailPayslips?m=' . Input::get('m') . '#Innovative');
    }



    public function addMaritalStatusDetail()
    {
        $martitalStatus = Input::get('martitalStatusSection');
        foreach ($martitalStatus as $row) {
            $martitalStatus_name = Input::get('marital_status_name_' . $row . '');
            $data1['marital_status_name'] = strip_tags($martitalStatus_name);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('marital_status')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewMaritalStatuslist?m=' . $_GET['m'] . '');
    }

    // public function addEmployeeAllowanceDetail()
    // {
    //     CommonHelper::companyDatabaseConnection(Input::get('company_id'));
    //     foreach (Input::get('allowance_type') as $key => $val):

    //         $data1['emp_id'] = strip_tags(Input::get('emp_id'));
    //         $data1['allowance_type_id'] = strip_tags($val);
    //         $data1['allowance_amount'] = strip_tags(Input::get('allowance_amount')[$key]);
    //         $data1['username'] = Auth::user()->name;
    //         $data1['status'] = 1;
    //         $data1['date'] = date("Y-m-d");
    //         $data1['time'] = date("H:i:s");
    //         DB::table('allowance')->insert($data1);
    //     endforeach;
    //     CommonHelper::reconnectMasterDatabase();
    //     Session::flash('dataInsert', 'successfully saved.');
    //     return Redirect::to('hr/viewAllowanceList?m=' . Input::get('company_id') . '#Innovative');


    // }

    public function addEmployeeAllowanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        foreach (Input::get('allowance_type') as $key => $val):
            $month_year = explode('-', Input::get('month_year')[$key]);
            $data1['emp_id'] = strip_tags(Input::get('emp_id'));
            $data1['allowance_type_id'] = strip_tags($val);
            $data1['allowance_amount'] = strip_tags(Input::get('allowance_amount')[$key]);
            $data1['remarks'] = strip_tags(Input::get('remarks')[$key]);
            $data1['once'] = strip_tags(Input::get('once')[$key]);
            if(Input::get('month_year') != ''){
                $data1['month'] = $month_year[1];
                $data1['year'] = $month_year[0];
            };
            $data1['username'] = Auth::user()->name;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('allowance')->insert($data1);
        endforeach;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Data has been successfully saved.');
        Session::flash('alert-class', 'alert-info');
        return Redirect::to('hr/viewAllowanceList?m=' . Input::get('company_id') . '#Innovative');

    }


    public function addEmployeeDeductionDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        foreach (Input::get('deduction_type') as $key => $val):
            $month_year = explode('-', Input::get('month_year')[$key]);
            $data1['emp_id'] = strip_tags(Input::get('emp_id'));
            $data1['deduction_type'] = strip_tags($val);
            $data1['remarks'] = strip_tags(Input::get('remarks')[$key]);
            $data1['deduction_amount'] = strip_tags(Input::get('deduction_amount')[$key]);
            $data1['once'] = strip_tags(Input::get('once')[$key]);
            if(Input::get('month_year')[$key] != ''){
                $data1['month'] = $month_year[1];
                $data1['year'] = $month_year[0];
            };
            $data1['username'] = Auth::user()->name;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('deduction')->insert($data1);
        endforeach;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewDeductionList?m=' . Input::get('company_id') . '#Innovative');


    }

    public function addAdvanceSalaryDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $implode_date = explode("-", Input::get('deduction_month_year'));

        $data1['emp_id'] = Input::get('emp_id');
        $data1['advance_salary_amount'] = Input::get('advance_salary_amount');
        $data1['salary_needed_on'] = Input::get('salary_needed_on');
        $data1['deduction_year'] = $implode_date[0];
        $data1['deduction_month'] = $implode_date[1];
        $data1['detail'] = Input::get('advance_salary_detail');
        $data1['username'] = Auth::user()->name;
        $data1['approval_status'] = 1;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");


        DB::table('advance_salary')->insert($data1);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewAdvanceSalaryList?m=' . Input::get('company_id') . '#Innovative');

    }


    public function addWorkingHoursPolicyDetail()
    {

        $data['working_hours_policy'] = strip_tags(Input::get('working_hours_policy'));
        $data['start_working_hours_time'] = strip_tags(Input::get('start_working_hours_time'));
        $data['end_working_hours_time'] = strip_tags(Input::get('end_working_hours_time'));
        $data['working_hours_grace_time'] = strip_tags(Input::get('working_hours_grace_time'));
        $data['half_day_time'] = strip_tags(Input::get('half_day_time'));
        $data['username'] = Auth::user()->name;
        $data['status'] = 1;
        $data['company_id'] = Input::get('m');
        $data['time'] = date("H:i:s");
        $data['date'] = date("Y-m-d");
        DB::table('working_hours_policy')->insert($data);

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewWorkingHoursPolicyList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . Input::get('m') . '');
    }

    public function addLeavesPolicyDetail()
    {
        $data1['leaves_policy_name'] = strip_tags(Input::get('leaves_policy_name'));
        $data1['policy_date_from'] = Input::get('PolicyDateFrom');
        $data1['policy_date_till'] = Input::get('PolicyDateTill');
        $data1['total_leaves'] = Input::get('totalLeaves');
        $data1['terms_conditions'] = Input::get('terms_conditions');
        $data1['fullday_deduction_rate'] = Input::get('full_day_deduction_rate');
        $data1['halfday_deduction_rate'] = Input::get('half_day_deduction_rate');
        $data1['per_hour_deduction_rate'] = Input::get('per_hour_deduction_rate');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['time'] = date("H:i:s");
        $data1['date'] = date("Y-m-d");

        $last_id = DB::table('leaves_policy')->insertGetId($data1);

        foreach (Input::get('leaves_type_id') as $key => $val):

            $data2['leaves_policy_id'] = $last_id;
            $data2['leave_type_id'] = $val;
            $data2['no_of_leaves'] = Input::get('no_of_leaves')[$key];
            $data2['username'] = Auth::user()->name;;
            $data2['status'] = 1;
            $data2['time'] = date("H:i:s");
            $data2['date'] = date("Y-m-d");
            DB::table('leaves_data')->insert($data2);

        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewLeavesPolicyList?m=' . Input::get('company_id') . '#Innovative');

    }

    public function addManuallyLeaves()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $leave_policy = Employee::where([['emp_id', '=', Input::get('emr_no')], ['leaves_policy_id', '!=', 0]]);
        if ($leave_policy->count() == 0) {
            Session::flash('dataDelete', 'Please Select Leaves Policy For User !');
            return Redirect::to('hr/createManualLeaves?m=' . Input::get('m') . '');
        }

        $emp_leave_policy = $leave_policy->first();
        CommonHelper::reconnectMasterDatabase();
        $policy_date_from = LeavesPolicy::where([['id', '=', $emp_leave_policy->leaves_policy_id]])->value('policy_date_from');

        $casual_leaves = LeavesData::where([['leaves_policy_id', '=', $emp_leave_policy->leaves_policy_id], ['leave_type_id', '=', '3']])->value('no_of_leaves');
        $annual_leaves = LeavesData::where([['leaves_policy_id', '=', $emp_leave_policy->leaves_policy_id], ['leave_type_id', '=', '1']])->value('no_of_leaves');
        $sick_leaves = LeavesData::where([['leaves_policy_id', '=', $emp_leave_policy->leaves_policy_id], ['leave_type_id', '=', '2']])->value('no_of_leaves');

        $leaves [1] = $annual_leaves - Input::get('annual_leaves');
        $leaves [3] = $casual_leaves - Input::get('casual_leaves');
        $leaves [2] = $sick_leaves - Input::get('sick_leaves');

        TransferedLeaves::where([['emr_no', '=', Input::get('emr_no')], ['leaves_policy_id', '=', $emp_leave_policy->leaves_policy_id]])->delete();
        LeaveApplication::where([['view', '=', 'no'], ['emr_no', '=', Input::get('emr_no')], ['leave_policy_id', '=', $emp_leave_policy->leaves_policy_id]])->delete();
        LeaveApplicationData::where([['view', '=', 'no'], ['emr_no', '=', Input::get('emr_no')], ['leave_policy_id', '=', $emp_leave_policy->leaves_policy_id]])->delete();

        foreach ($leaves as $key => $value) {
            if ($key == 1) {

                $leaveApplicationData['emp_id'] = Input::get('emr_no');
                $leaveApplicationData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $leaveApplicationData['leave_type'] = $key;
                $leaveApplicationData['leave_day_type'] = 1;
                $leaveApplicationData['reason'] = "-";
                $leaveApplicationData['leave_address'] = "-";
                $leaveApplicationData['approval_status'] = 2;
                $leaveApplicationData['view'] = "no";
                $leaveApplicationData['status'] = 1;
                $leaveApplicationData['username'] = Auth::user()->name;
                $leaveApplicationData['date'] = date("Y-m-d");
                $leaveApplicationData['time'] = date("H:i:s");

                $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);

                $annualLeavesData['emp_id'] = Input::get('emr_no');
                $annualLeavesData['leave_application_id'] = $leave_application_id;
                $annualLeavesData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $annualLeavesData['leave_type'] = $key;
                $annualLeavesData['view'] = "no";
                $annualLeavesData['leave_day_type'] = 1;
                $annualLeavesData['no_of_days'] = $value;
                $annualLeavesData['from_date'] = date("Y-m-d");
                $annualLeavesData['to_date'] = date("Y-m-d");
                $annualLeavesData['status'] = 1;
                $annualLeavesData['username'] = Auth::user()->name;
                $annualLeavesData['date'] = date("Y-m-d");
                $annualLeavesData['time'] = date("H:i:s");
                DB::table('leave_application_data')->insert($annualLeavesData);

            } elseif ($key == 2) {
                $leaveApplicationData['emp_id'] = Input::get('emr_no');
                $leaveApplicationData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $leaveApplicationData['leave_type'] = $key;
                $leaveApplicationData['leave_day_type'] = 1;
                $leaveApplicationData['reason'] = "-";
                $leaveApplicationData['leave_address'] = "-";
                $leaveApplicationData['approval_status'] = 2;
                $leaveApplicationData['view'] = "no";
                $leaveApplicationData['status'] = 1;
                $leaveApplicationData['username'] = Auth::user()->name;
                $leaveApplicationData['date'] = date("Y-m-d");
                $leaveApplicationData['time'] = date("H:i:s");

                $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);

                $annualLeavesData['emp_id'] = Input::get('emr_no');
                $annualLeavesData['leave_application_id'] = $leave_application_id;
                $annualLeavesData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $annualLeavesData['leave_type'] = $key;
                $annualLeavesData['leave_day_type'] = 1;
                $annualLeavesData['view'] = "no";
                $annualLeavesData['no_of_days'] = $value;
                $annualLeavesData['from_date'] = date("Y-m-d");
                $annualLeavesData['to_date'] = date("Y-m-d");
                $annualLeavesData['status'] = 1;
                $annualLeavesData['username'] = Auth::user()->name;
                $annualLeavesData['date'] = date("Y-m-d");
                $annualLeavesData['time'] = date("H:i:s");
                DB::table('leave_application_data')->insert($annualLeavesData);
            } elseif ($key == 3) {
                $leaveApplicationData['emp_id'] = Input::get('emr_no');
                $leaveApplicationData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $leaveApplicationData['leave_type'] = $key;
                $leaveApplicationData['leave_day_type'] = 1;
                $leaveApplicationData['reason'] = "-";
                $leaveApplicationData['leave_address'] = "-";
                $leaveApplicationData['approval_status'] = 2;
                $leaveApplicationData['view'] = "no";
                $leaveApplicationData['status'] = 1;
                $leaveApplicationData['username'] = Auth::user()->name;
                $leaveApplicationData['date'] = date("Y-m-d");
                $leaveApplicationData['time'] = date("H:i:s");

                $leave_application_id = DB::table('leave_application')->insertGetId($leaveApplicationData);
                $annualLeavesData['emp_id'] = Input::get('emr_no');
                $annualLeavesData['leave_application_id'] = $leave_application_id;
                $annualLeavesData['leave_policy_id'] = $emp_leave_policy->leaves_policy_id;
                $annualLeavesData['leave_type'] = $key;
                $annualLeavesData['leave_day_type'] = 1;
                $annualLeavesData['view'] = "no";
                $annualLeavesData['no_of_days'] = $value;
                $annualLeavesData['from_date'] = date("Y-m-d");
                $annualLeavesData['to_date'] = date("Y-m-d");
                $annualLeavesData['status'] = 1;
                $annualLeavesData['username'] = Auth::user()->name;
                $annualLeavesData['date'] = date("Y-m-d");
                $annualLeavesData['time'] = date("H:i:s");
                DB::table('leave_application_data')->insert($annualLeavesData);
            }


        }

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createManualLeaves?m=' . Input::get('m') . '');


    }


    public function addVehicleTypeDetail()
    {

        foreach (Input::get('vehicle_type') as $key => $val):

            $data1['vehicle_type_name'] = strip_tags($val);
            $data1['vehicle_type_cc'] = strip_tags(Input::get('vehicle_cc')[$key]);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = Input::get('company_id');
            $data1['username'] = Auth::user()->name;;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('vehicle_type')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewVehicleTypeList?m=' . Input::get('company_id') . '#Innovative');


    }

    public function addCarPolicyDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        foreach (Input::get('designation_id') as $key => $val):

            $data1['designation_id'] = $val;
            $data1['vehicle_type_id'] = Input::get('vehicle_type_id')[$key];
            $data1['policy_name'] = Input::get('policy_name')[$key];
            $data1['start_salary_range'] = Input::get('start_salary_range')[$key];
            $data1['end_salary_range'] = Input::get('end_salary_range')[$key];
            $data1['status'] = 1;
            $data1['username'] = Auth::user()->name;;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('car_policy')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewCarPolicyList?m=' . Input::get('company_id') . '#Innovative');

    }

    public function addLoanRequestDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));


        $month_data = (explode("-", Input::get('needed_on_date')));

        $data1['emp_id'] = Input::get('emp_id');
        $data1['year'] = $month_data[0];
        $data1['month'] = $month_data[1];
        $data1['loan_type_id'] = Input::get('loan_type_id');
        $data1['loan_amount'] = Input::get('loan_amount');
        $data1['per_month_deduction'] = Input::get('per_month_deduction');
        $data1['description'] = Input::get('loan_description');
        $data1['status'] = 1;
        $data1['username'] = Auth::user()->name;;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        DB::table('loan_request')->insert($data1);


        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewLoanRequestList?m=' . Input::get('company_id') . '#Innovative');


    }

    public function addEOBIDetail()
    {


        foreach (Input::get('EOBI_name') as $key => $val):

            $data1['EOBI_name'] = $val;
            $data1['EOBI_amount'] = Input::get('EOBI_amount')[$key];
            $data1['month_year'] = Input::get('month_year')[$key];
            $data1['company_id'] = Input::get('company_id');
            $data1['username'] = Auth::user()->name;;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('eobi')->insert($data1);

        endforeach;
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEOBIList?m=' . Input::get('company_id') . '#Innovative');

    }


//    public function addTaxesDetail()
//    {
//
//        foreach (Input::get('tax_name') as $key => $val):
//
//            $data1['tax_name'] = $val;
//            $data1['salary_range_from'] = Input::get('salary_range_from')[$key];
//            $data1['salary_range_to'] = Input::get('salary_range_to')[$key];
//            $data1['tax_mode'] = Input::get('tax_mode')[$key];
//            $data1['tax_percent'] = Input::get('tax_percent')[$key];
//            $data1['tax_month_year'] = Input::get('tax_month_year')[$key];
//            $data1['status'] = 1;
//            $data1['company_id'] = Input::get('company_id');
//            $data1['username'] = Auth::user()->name;;
//            $data1['date'] = date("Y-m-d");
//            $data1['time'] = date("H:i:s");
//
//            DB::table('tax')->insert($data1);
//
//        endforeach;
//        Session::flash('dataInsert', 'successfully saved.');
//        return Redirect::to('hr/viewTaxesList?m=' . Input::get('company_id') . '#Innovative');
//
//    }

    public function addTaxesDetail()
    {
        $data['tax_name'] = Input::get('tax_name');
        $data['tax_month_year'] = Input::get('tax_month_year');
        $data['status'] = 1;
        $data['username'] = Auth::user()->name;;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");
        $data['company_id'] = Input::get('company_id');
        $last_id = DB::table('tax')->insertGetId($data);

        foreach (Input::get('salary_range_from') as $key => $val):

            $data1['slab_name'] = Input::get('tax_name');
            $data1['tax_id'] = $last_id;
            $data1['salary_range_from'] = $val;
            $data1['salary_range_to'] = Input::get('salary_range_to')[$key];
            $data1['tax_mode'] = Input::get('tax_mode')[$key];
            $data1['tax_percent'] = Input::get('tax_percent')[$key];
            $data1['tax_amount'] = Input::get('tax_amount')[$key];
            $data1['status'] = 1;
            $data1['username'] = Auth::user()->name;;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('tax_slabs')->insert($data1);

        endforeach;
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewTaxesList?pageType=viewlist&&parentCode=53&&m=' . Input::get('company_id') . '#Innovative');
    }

    public function addBonusDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        foreach (Input::get('Bonus_name') as $key => $val):
            $data1['bonus_name'] = $val;
            $data1['percent_of_salary'] = Input::get('percent_of_salary')[$key];
            $data1['status'] = 1;
            $data1['username'] = Auth::user()->name;;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('bonus')->insert($data1);

        endforeach;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewBonusList?m='.Input::get('company_id').'#Innovative');

    }

    function addEmployeeBonusDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_year = explode('-', Input::get('bonus_month_year'));

        if (Input::get('check_list')):

            foreach (Input::get('check_list') as $key => $value):
                $emp_and_bonus = (explode("_", $value));
                $data1['emp_id'] = $emp_and_bonus[0];
                $data1['bonus_id'] = Input::get('bonus_id');
                $data1['bonus_amount'] = $emp_and_bonus[1];
                $data1['bonus_month'] = $month_year[1];
                $data1['bonus_year'] = $month_year[0];
                $data1['username'] = Auth::user()->name;
                $data1['bonus_status'] = 1;
                $data1['status'] = 1;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");
                DB::table('bonus_issue')->insert($data1);
            endforeach;

        endif;

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/IssueBonusDetailForm?m=' . Input::get('m') . '');


    }

    function addHolidaysDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        foreach (Input::get('holiday_name') as $key => $value):
            $month_year = explode('-', Input::get('holiday_date')[$key]);
            $data1['holiday_name'] = $value;
            $data1['holiday_date'] = date("Y-m-d", strtotime(Input::get('holiday_date')[$key]));;
            $data1['year'] = $month_year[0];
            $data1['month'] = $month_year[1];
            $data1['username'] = Auth::user()->name;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('holidays')->insert($data1);

        endforeach;

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewHolidaysList?m=' . Input::get('company_id') . '#Innovative');
    }

    public function addAttendanceProgressDetail()
    {

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        foreach (Input::get('emr_no') as $value):

            DB::table('payroll_data')->where([['emr_no', '=', $value], ['month', '=', Input::get('month_' . $value)], ['year', '=', Input::get('year_' . $value)]])->delete();
            DB::table('payslip')->where([['emr_no', '=', $value], ['month', '=', Input::get('month_' . $value)], ['year', '=', Input::get('year_' . $value)]])->delete();

            $attendanceProgress["emr_no"] = $value;
            $attendanceProgress["attendance_type"] = Input::get('attendance_type_' . $value);;
            $attendanceProgress["attendance_from"] = Input::get('attendance_from_' . $value);
            $attendanceProgress["attendance_to"] = Input::get('attendance_to_' . $value);
            $attendanceProgress["present_days"] = Input::get('present_days_' . $value);
            $attendanceProgress["absent_days"] = Input::get('absent_days_' . $value);
            $attendanceProgress["overtime"] = Input::get('overtime_' . $value);
            $attendanceProgress["gez_overtime"] = Input::get('gez_overtime_' . $value);
            $attendanceProgress["deduction_days"] = Input::get('deduction_days_' . $value);
            $attendanceProgress["month"] = Input::get('month_' . $value);
            $attendanceProgress["year"] = Input::get('year_' . $value);
            $attendanceProgress['username'] = Auth::user()->name;
            $attendanceProgress['status'] = 1;
            $attendanceProgress['date'] = date("Y-m-d");
            $attendanceProgress['time'] = date("H:i:s");
            DB::table('payroll_data')->insert($attendanceProgress);

        endforeach;


        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/ViewAttendanceProgress?m=' . Input::get('company_id') . '#Innovative');

    }

    public function addEmployeeDepositDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $employeeDeposit = new EmployeeDeposit;

        $month_and_year = explode('-', $request->to_be_deduct_on_date);
        $employeeDeposit->sub_department_id = $request->sub_department_id;
        $employeeDeposit->acc_no = $request->employee_id;
        $employeeDeposit->deposit_name = $request->deposit_name;
        $employeeDeposit->deposit_amount = $request->deposit_amount;
        $employeeDeposit->deduction_month = $month_and_year[1];
        $employeeDeposit->deduction_year = $month_and_year[0];
        $employeeDeposit->username = Auth::user()->name;
        $employeeDeposit->status = 1;
        $employeeDeposit->date = date("Y-m-d");
        $employeeDeposit->time = date("H:i:s");

        $employeeDeposit->save();
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeDepositList?m=' . Input::get('company_id') . '#Innovative');
    }

    public function addManualyAttendance()
    {
        //echo "<pre>";
        //print_r(Input::get('attendance_date')); die;

        $name_array = $_POST['attendance_date'];

        for ($i = 0; $i < count($name_array); $i++) {

            $manualyAttData['acc_no'] = Input::get('acc_no')[$i];
            $manualyAttData['emp_name'] = Input::get('emp_name')[$i];
            $manualyAttData['day'] = Input::get('day')[$i];
            $manualyAttData['month'] = Input::get('month')[$i];
            $manualyAttData['year'] = Input::get('year')[$i];
            $manualyAttData['attendance_date'] = Input::get('attendance_date')[$i];
            $manualyAttData['clock_in'] = Input::get('clock_in')[$i];
            $manualyAttData['clock_out'] = Input::get('clock_out')[$i];
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            DB::table('attendance')->insert($manualyAttData);
            CommonHelper::reconnectMasterDatabase();

        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createManageAttendanceForm?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }

    public function addEmployeeAttendanceFileDetail()
    {
        $data = Excel::toArray(true, request()->file('employeeAttendanceFile'));
        echo "<pre>";

        $counter1 = 1;
        $counter2 = 1;

        if (trim($data[0][3][0]) == 'S. No.' && trim($data[0][3][1]) == 'Employee Name' && trim($data[0][3][2]) == 'Designation' &&
            trim($data[0][3][3]) == 'Location/Site' && trim($data[0][3][4]) == 'EMR' && trim($data[0][3][5]) == 'Present Days' &&
            trim($data[0][3][6]) == 'Absent Days' && trim($data[0][3][7]) == 'Leaves (Sick, Casual, Annual)' &&
            trim($data[0][3][8]) == 'Total Over Time' && trim($data[0][3][9]) == 'Gez Overtime' && trim($data[0][3][10]) == 'Bank Account Number' &&
            trim($data[0][3][11]) == 'Remarks'
        ):

            CommonHelper::companyDatabaseConnection(Input::get('m'));
            foreach ($data as $value):
                if ($counter1 == 1 || $counter1 == 2 || $counter1 == 3):
                    foreach ($value as $value2):
                        if ($value2[4] == '' || $value2[4] == 'EMR' || $value2[4] == 'EMR ') continue;
                        DB::table('attendance')->where([['attendance_type', '=', 1], ['month', '=', date('m', strtotime(Input::get('date_to')))], ['year', '=', date('Y', strtotime(Input::get('date_to')))], ['emr_no', '=', $value2[4]]])->delete();
                        DB::table('payroll_data')->where([['month', '=', date('m', strtotime(Input::get('date_to')))], ['year', '=', date('Y', strtotime(Input::get('date_to')))], ['emr_no', '=', $value2[4]]])->delete();

                        $data1['emr_no'] = $value2[4];
                        $data1['present_days'] = $value2[5];
                        $data1['absent_days'] = $value2[6];
                        $data1['overtime'] = $value2[8];
                        $data1['gez_overtime'] = $value2[9];
                        $data1['attendance_from'] = Input::get('date_from');
                        $data1['attendance_to'] = Input::get('date_to');
                        $data1['month'] = date('m', strtotime(Input::get('date_to')));
                        $data1['year'] = date('Y', strtotime(Input::get('date_to')));
                        $data1['username'] = Auth::user()->name;
                        $data1['attendance_type'] = 1;
                        $data1['status'] = 1;
                        $data1['date'] = date("Y-m-d");
                        $data1['time'] = date("H:i:s");

                        DB::table('attendance')->insert($data1);

                        $account_no = EmployeeBankData::where([['emr_no', '=', $value2[4]], ['status', '=', 1]]);
                        if ($account_no->count() > 0):
                            $accno['account_no'] = $value2[10];
                            EmployeeBankData::where([['emr_no', '=', $value2[4]]])->update($accno);

                        else:

                            $data2['emr_no'] = $value2[4];
                            $data2['account_title'] = "-";
                            $data2['bank_name'] = "-";
                            $data2['account_no'] = $value2[10];
                            $data2['username'] = Auth::user()->name;
                            $data2['status'] = 1;
                            $data2['date'] = date("Y-m-d");
                            $data2['time'] = date("H:i:s");
                            DB::table('employee_bank_data')->insert($data2);
                        endif;

                    endforeach;
                endif;
                $counter1++;
            endforeach;

            $log['table_name'] = 'attendance';
            $log['activity_id'] = null;
            $log['deleted_emr_no'] = null;
            $log['activity'] = 'Upload';
            $log['module'] = 'hr';
            $log['username'] = Auth::user()->name;
            $log['date'] = date("Y-m-d");
            $log['time'] = date("H:i:s");
            DB::table('log')->insert($log);
            CommonHelper::reconnectMasterDatabase();

        else:
            Session::flash('errorMsg', 'Please upload file with the given format.');
            return Redirect::to('hr/createManageAttendanceForm?pageType=viewlist&&parentCode=8&&m=' . Input::get('m') . '#Innovative');
        endif;


        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createManageAttendanceForm?pageType=viewlist&&parentCode=8&&m=' . Input::get('m') . '#Innovative');

    }

    public function addEmployeeGradesDetail()
    {
        foreach (Input::get('employee_grade_type') as $key => $val):
            $data1['employee_grade_type'] = strip_tags($val);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('grades')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeGradesList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }

    public function addEmployeeLocationsDetail()
    {
        foreach (Input::get('employee_location') as $key => $val):
            $data1['employee_location'] = strip_tags($val);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('locations')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeLocationsList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }


    public function addEmployeeDegreeTypeDetail()
    {
        foreach (Input::get('degree_type_name') as $key => $val):
            $data1['degree_type_name'] = $val;
            $data1['company_id'] = Input::get('company_id');
            $data1['username'] = Auth::user()->name;;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            DB::table('degree_type')->insert($data1);
        endforeach;
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeDegreeTypeList?m=' . Input::get('company_id') . '#Innovative');
    }

    public function addEmployeeExitClearanceDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));


        $emp_id = Input::get('emp_id');
        $data1['emp_id'] = $emp_id;
        $data1['leaving_type'] = Input::get('leaving_type');
        $data1['supervisor_name'] = Input::get('supervisor_name');
        $data1['signed_by_supervisor'] = Input::get('signed_by_supervisor');
        $data1['last_working_date'] = Input::get('last_working_date');

        $data1['room_key'] = Input::get('room_key');
        $data1['room_key_remarks'] = Input::get('room_key_remarks');
        $data1['mobile_sim'] = Input::get('mobile_sim');
        $data1['mobile_sim_remarks'] = Input::get('mobile_sim_remarks');
//            $data1['fuel_card'] = Input::get('fuel_card');
//            $data1['fuel_card_remarks'] = Input::get('fuel_card_remarks');
        $data1['mfm_employee_card'] = Input::get('mfm_employee_card');
        $data1['mfm_employee_card_remarks'] = Input::get('mfm_employee_card_remarks');
        $data1['client_access_card'] = Input::get('client_access_card');
        $data1['client_access_card_remarks'] = Input::get('client_access_card_remarks');
        $data1['medical_insurance_card'] = Input::get('medical_insurance_card');
        $data1['medical_insurance_card_remarks'] = Input::get('medical_insurance_card_remarks');
        $data1['eobi_card'] = Input::get('eobi_card');
        $data1['eobi_card_remarks'] = Input::get('eobi_card_remarks');
        $data1['biometric_scan'] = Input::get('biometric_scan');
        $data1['biometric_scan_remarks'] = Input::get('biometric_scan_remarks');
        //$data1['payroll_deduction'] = Input::get('payroll_deduction');
        //$data1['payroll_deduction_remarks'] = Input::get('payroll_deduction_remarks');
        //$data1['info_sent_to_client'] = Input::get('info_sent_to_client');
        //$data1['info_sent_to_client_remarks'] = Input::get('info_sent_to_client_remarks');
        //$data1['client_exit_checklist'] = Input::get('client_exit_checklist');
        //$data1['client_exit_checklist_remarks'] = Input::get('client_exit_checklist_remarks');
        $data1['exit_interview'] = Input::get('exit_interview');
        $data1['exit_interview_remarks'] = Input::get('exit_interview_remarks');
        $data1['laptop'] = Input::get('laptop');
        $data1['laptop_remarks'] = Input::get('laptop_remarks');
        $data1['desktop_computer'] = Input::get('desktop_computer');
        $data1['desktop_computer_remarks'] = Input::get('desktop_computer_remarks');
        $data1['email_account_deactivated'] = Input::get('email_account_deactivated');
        $data1['email_account_deactivated_remarks'] = Input::get('email_account_deactivated_remarks');
        //$data1['toolkit_ppe'] = Input::get('toolkit_ppe');
        //$data1['toolkit_ppe_remarks'] = Input::get('toolkit_ppe_remarks');
        //$data1['uniform'] = Input::get('uniform');
        //$data1['uniform_remarks'] = Input::get('uniform_remarks');
//        $data1['advance_loan'] = Input::get('advance_loan');
//        $data1['advance_loan_remarks'] = Input::get('advance_loan_remarks');

        //FINANCE
        $data1['advance_loan'] = Input::get('advance_loan');
        $data1['advance_loan_remarks'] = Input::get('advance_loan_remarks');
        $data1['incentive'] = Input::get('incentive');
        $data1['incentive_remarks'] = Input::get('incentive_remarks');
        $data1['dues_payable'] = Input::get('dues_payable');
        $data1['dues_payable_remarks'] = Input::get('dues_payable_remarks');
        $data1['final_settlement'] = Input::get('final_settlement');
        $data1['final_settlement_remarks'] = Input::get('final_settlement_remarks');

        // admin department start
        $data1['mobile_sim'] = Input::get('mobile_sim');
        $data1['mobile_sim_remarks'] = Input::get('mobile_sim_remarks');
        $data1['cell_phone'] = Input::get('cell_phone');
        $data1['cell_phone_remarks'] = Input::get('cell_phone_remarks');
        $data1['company_car'] = Input::get('company_car');
        $data1['company_car_remarks'] = Input::get('company_car_remarks');
        $data1['emp_card'] = Input::get('emp_card');
        $data1['emp_card_remarks'] = Input::get('emp_card_remarks');
        $data1['business_card'] = Input::get('business_card');
        $data1['business_card_remarks'] = Input::get('business_card_remarks');
        $data1['stationary_returned'] = Input::get('stationary_returned');
        $data1['stationary_returned_remarks'] = Input::get('stationary_returned_remarks');
        $data1['any_other_clearance'] = Input::get('any_other_clearance');
        $data1['any_other_clearance_remarks'] = Input::get('any_other_clearance_remarks');

        //admin department end

        // Reporting manager teamlead start
        $data1['data_document'] = Input::get('data_document');
        $data1['data_document_remarks'] = Input::get('data_document_remarks');
        $data1['knowledge_transfer'] = Input::get('knowledge_transfer');
        $data1['knowledge_transfer_remarks'] = Input::get('knowledge_transfer_remarks');
        $data1['responsiblities_handed'] = Input::get('responsiblities_handed');
        $data1['responsiblities_handed_remarks'] = Input::get('responsiblities_handed_remarks');
        $data1['last_assignment'] = Input::get('last_assignment');
        $data1['last_assignment_remarks'] = Input::get('last_assignment_remarks');
        $data1['client_acc_info'] = Input::get('client_acc_info');
        $data1['client_acc_info_remarks'] = Input::get('client_acc_info_remarks');
        $data1['emp_separation'] = Input::get('emp_separation');
        $data1['emp_separation_remarks'] = Input::get('emp_separation_remarks');


        //Reporting manager teamlead  end

        //Project related lead start
        $data1['controlling_system'] = Input::get('controlling_system');
        $data1['controlling_system_remarks'] = Input::get('controlling_system_remarks');
        $data1['build_server'] = Input::get('build_server');
        $data1['build_server_remarks'] = Input::get('build_server_remarks');
        $data1['project_management'] = Input::get('project_management');
        $data1['project_management_remarks'] = Input::get('project_management_remarks');
        $data1['client_proj_manage'] = Input::get('client_proj_manage');
        $data1['client_proj_manage_remarks'] = Input::get('client_proj_manage_remarks');
        $data1['production_env'] = Input::get('production_env');
        $data1['production_env_remarks'] = Input::get('production_env_remarks');
        $data1['bug_tracking'] = Input::get('bug_tracking');
        $data1['bug_tracking_remarks'] = Input::get('bug_tracking_remarks');
        $data1['internal_app'] = Input::get('internal_app');
        $data1['internal_app_remarks'] = Input::get('internal_app_remarks');
        $data1['help_desk'] = Input::get('help_desk');
        $data1['help_desk_remarks'] = Input::get('help_desk_remarks');
        $data1['info_partner_sep'] = Input::get('info_partner_sep');
        $data1['info_partner_sep_remarks'] = Input::get('info_partner_sep_remarks');


        //Project related lead  end

        //Hr start
        $data1['skype_group'] = Input::get('skype_group');
        $data1['skype_group_remarks'] = Input::get('skype_group_remarks');
        $data1['hris_system'] = Input::get('hris_system');
        $data1['hris_system_remarks'] = Input::get('hris_system_remarks');
        $data1['hris_portal'] = Input::get('hris_portal');
        $data1['hris_portal_remarks'] = Input::get('hris_portal_remarks');
        $data1['insurance_card_returned'] = Input::get('insurance_card_returned');
        $data1['insurance_card_returned_remarks'] = Input::get('insurance_card_returned_remarks');
        $data1['exit_questionnaire'] = Input::get('exit_questionnaire');
        $data1['exit_questionnaire_remarks'] = Input::get('exit_questionnaire_remarks');
        $data1['finalize_disbursable_salry'] = Input::get('finalize_disbursable_salry');
        $data1['finalize_disbursable_salry_remarks'] = Input::get('finalize_disbursable_salry_remarks');
        $data1['int_app_sup_port'] = Input::get('int_app_sup_port');
        $data1['int_app_sup_port_remarks'] = Input::get('int_app_sup_port_remarks');
        $data1['promised_paid'] = Input::get('promised_paid');
        $data1['promised_paid_remarks'] = Input::get('promised_paid_remarks');
        $data1['confirm_reflection'] = Input::get('confirm_reflection');
        $data1['confirm_reflection_remarks'] = Input::get('confirm_reflection_remarks');
        $data1['notice_period'] = Input::get('notice_period');
        $data1['notice_period_remarks'] = Input::get('notice_period_remarks');
        $data1['resignation_noti_received'] = Input::get('resignation_noti_received');
        $data1['resignation_noti_received_remarks'] = Input::get('resignation_noti_received_remarks');


        //Hr end
        //Network start
        $data1['email_acc_deactivated'] = Input::get('email_acc_deactivated');
        $data1['email_acc_deactivated_remarks'] = Input::get('email_acc_deactivated_remarks');
        $data1['point_1_no'] = Input::get('point_1_no');
        $data1['point_1_no_remarks'] = Input::get('point_1_no_remarks');
        $data1['hardware_received'] = Input::get('hardware_received');
        $data1['hardware_received_remarks'] = Input::get('hardware_received_remarks');
        $data1['email_sheet'] = Input::get('email_sheet');
        $data1['email_sheet_remarks'] = Input::get('email_sheet_remarks');
        $data1['equipment_received'] = Input::get('equipment_received');
        $data1['equipment_received_remarks'] = Input::get('equipment_received_remarks');
        $data1['internet_device_received'] = Input::get('internet_device_received');
        $data1['internet_device_received_remarks'] = Input::get('internet_device_received_remarks');
        $data1['test_phone_receiving'] = Input::get('test_phone_receiving');
        $data1['test_phone_receiving_remarks'] = Input::get('test_phone_receiving_remarks');
        $data1['deactivate_attend_soft'] = Input::get('deactivate_attend_soft');
        $data1['deactivate_attend_soft_remarks'] = Input::get('deactivate_attend_soft_remarks');

        //Network end
        //end finance
        $data1['extra_leaves'] = Input::get('extra_leaves');
        $data1['extra_leaves_remarks'] = Input::get('extra_leaves_remarks');
        $data1['final_settlement'] = Input::get('final_settlement');
        $data1['final_settlement_remarks'] = Input::get('final_settlement_remarks');
        $data1['note'] = Input::get('note');


        $data1['status'] = 1;
        $data1['approval_status'] = 2;
        $data1['company_id'] = Input::get('company_id');
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");



        DB::table('employee_exit')->where([['emp_id', $emp_id]])->delete();
        DB::table('employee_exit')->insert($data1);

        $employee = Employee::where([['emp_id','=',$emp_id],['status','!=','2']])->first();
        $employee->status='3';
        $employee->update();

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeExitClearanceList?m=' . Input::get('company_id') . '#Innovative');
    }


    public function addEmployeeIdCardRequestDetail(Request $request)
    {
        $counter = 0;
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        if ($request->hasFile('fir_copy')):
            $counter++;
            $extension = $request->file('fir_copy')->getClientOriginalExtension();
            $file_name = Input::get('emr_no') . '_' . time() . '.' . $request->file('fir_copy')->getClientOriginalExtension();
            $path = $request->file('fir_copy')->storeAs('uploads/employee_id_card_fir_copy', $file_name);
            $data1['fir_copy_path'] = 'app/' . $path;
            $data1['fir_copy_extension'] = $extension;
        endif;

        if ($request->hasFile('card_image')):
            $counter++;
            $extension = $request->file('card_image')->getClientOriginalExtension();
            $file_name = Input::get('emr_no') . '_' . time() . '.' . $request->file('card_image')->getClientOriginalExtension();
            $path = $request->file('card_image')->storeAs('uploads/employee_id_card_images', $file_name);
            $data1['card_image_path'] = 'app/' . $path;
            $data1['card_image_extension'] = $extension;
        endif;

        if (Input::get('card_replacement') == 0) {
            $data1['fir_copy_path'] = null;
            $data1['fir_copy_extension'] = null;
        }

        $emr_no = Input::get('employee_id');

        $data1['emr_no'] = Input::get('emr_no');
        $data1['posted_at'] = Input::get('posted_at');
        $data1['card_replacement'] = Input::get('card_replacement');
        $data1['replacement_type'] = Input::get('replacement_type');
        $data1['payment'] = Input::get('payment');
        $data1['username'] = Auth::user()->name;
        $data1['approval_status'] = 1;
        $data1['status'] = 1;
        $data1['card_status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('employee_card_request')->where([['emr_no', $emr_no]])->delete();

        DB::table('employee_card_request')->insert($data1);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeIdCardRequestList?m=' . Input::get('company_id') . '#Innovative');
    }

    public function addEmployeePromotionDetail(Request $request)
    {
        $emp_id = Input::get('emp_id');
        $addAllowancesCheck = Input::get('addAllowancesCheck');

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $data['emp_id'] = $emp_id;
        $data['designation_id'] = Input::get('designation_id');
        $data['increment'] = Input::get('increment');
        $data['salary'] = Input::get('salary');
        $data['promotion_date'] = Input::get('promotion_date');
        $data['status'] = 1;
        $data['approval_status'] = 1;
        $data['username'] = Auth::user()->name;
        $data['date'] = date("Y-m-d");
        $data['time'] = date("H:i:s");

        $id = DB::table('employee_promotion')->insertGetId($data);
        $check_letter_uploading = $_FILES['letter_uploading']['name'][0];
        if ($check_letter_uploading != '') {
            $letter_uploading = $request->file('letter_uploading');
            $extention = [];
            foreach ($letter_uploading as $key => $value) {
                $file_name = time() . '_' . $emp_id . '_' . $key . '_' . $value->getClientOriginalExtension();
                $paths = 'app/' . $value->storeAs('uploads/promotions_letter', $file_name);
                $path = $_FILES['letter_uploading']['name'][$key];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $extention['file_type'] = $ext;
                $extention['letter_uploading'] = $paths;
                $extention['promotion_id'] = $id;
                $extention['date'] = date("Y-m-d");
                $extention['time'] = date("H:i:s");

                DB::table('promotion_letter')->insert($extention);
            }
        }

        if ($addAllowancesCheck == 1) {
            DB::table('allowance')->where([['emp_id', $emp_id]])->delete();

            foreach (Input::get('allowance_type') as $key => $val):
                $data1['emp_id'] = $emp_id;
                $data1['allowance_type'] = strip_tags($val);
                $data1['allowance_amount'] = strip_tags(Input::get('allowance_amount')[$key]);
                $data1['username'] = Auth::user()->name;
                $data1['status'] = 1;
                $data1['date'] = date("Y-m-d");
                $data1['time'] = date("H:i:s");

                DB::table('allowance')->insert($data1);
            endforeach;
        }
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeePromotionsList?m=' . Input::get('company_id') . '#Innovative');
    }

    public function addEmployeeTransferDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $promotion_id = '';
        $transfer_id = '';
        $location_check = Input::get('location_check');
        $transfer_project_check = Input::get('transfer_project_check');
        if ($location_check == 1) {
            $data['emr_no'] = Input::get('emr_no');
            $data['designation_id'] = Input::get('designation_id');
            $data['grade_id'] = Input::get('grade_id');
            $data['increment'] = Input::get('increment');
            $data['salary'] = Input::get('salary');
            $data['promotion_date'] = Input::get('promotion_date');
            $data['username'] = Auth::user()->name;
            $data['approval_status'] = 1;
            $data['status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");

            $promotion_id = DB::table('employee_promotion')->insertGetId($data);
        }

        if ($transfer_project_check == 1) {
            $region_id = Input::get('region_id');
            $emp_category_id = Input::get('emp_category_id');
            $transfer_project_id = Input::get('transfer_project_id');
            $emr_no = Input::get('emr_no');
            $m = Input::get('company_id');
            CommonHelper::companyDatabaseConnection($m);
            $data2['emr_no'] = $emr_no;
            $data2['employee_project_id'] = $transfer_project_id;
            $data2['emp_region_id'] = $region_id;
            $data2['emp_categoery_id'] = $emp_category_id;
            $data2['username'] = Auth::user()->name;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");
            $transfer_id = DB::table('transfer_employee_project')->insertGetId($data2);
            $data5['active'] = 2;
            Employee::where('emr_no', '=', $emr_no)->update($data5);
            $previous = DB::table('transfer_employee_project')->where([['emr_no', '=', $emr_no], ['id', '<', $transfer_id]])->max('id');
            if (count($previous) != '0') {
                $data4['active'] = 2;
                DB::table('transfer_employee_project')->where('id', '=', $previous)->delete();
            }
        }

        $data1['emr_no'] = Input::get('emr_no');
        $data1['location_id'] = Input::get('location_id');
        $data1['promotion_id'] = $promotion_id;
        $data1['transfer_project_id'] = $transfer_id;
        $data1['approval_status'] = 1;
        $data1['status'] = 1;
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        $id = DB::table('employee_location')->insertGetId($data1);

        $check_letter_uploading = $_FILES['letter_uploading']['name'][0];
        if ($check_letter_uploading != '') {
            $letter_uploading = $request->file('letter_uploading');
            $extention = [];
            foreach ($letter_uploading as $key => $value) {
                $file_name = time() . '_' . Input::get('emr_no') . '_' . $key . '_' . $value->getClientOriginalExtension();
                $paths = 'app/' . $value->storeAs('uploads/transfer_letter', $file_name);
                $path = $_FILES['letter_uploading']['name'][$key];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $extention['file_type'] = $ext;
                $extention['letter_uploading'] = $paths;
                $extention['emp_location_id'] = $id;
                $extention['date'] = date("Y-m-d");
                $extention['time'] = date("H:i:s");

                DB::table('transfer_letter')->insert($extention);
            }
        }
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeTransferList?m=' . Input::get('company_id') . '#Innovative');
    }

    public function addEmployeeFuelDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        if (!empty(Input::get('fuel_data'))):
            foreach (Input::get('fuel_data') as $fuel_rows):

                $fuel_date = Input::get('fuel_date_' . $fuel_rows . '');

                if (EmployeeFuelData::where([['emr_no', '=', Input::get('emr_no')], ['fuel_date', '=', $fuel_date], ['status', '=', 1]])->exists()) {
                    DB::table('employee_fuel_data')->where([['emr_no', '=', Input::get('emr_no')], ['fuel_date', '=', $fuel_date], ['status', '=', 1]])->delete();
                }
                $data['emr_no'] = Input::get('emr_no');
                $data['fuel_date'] = $fuel_date;
                $data['from'] = Input::get('from_' . $fuel_rows . '');
                $data['to'] = Input::get('to_' . $fuel_rows . '');
                $data['km'] = Input::get('km_' . $fuel_rows . '');
                $data['fuel_month'] = date('m', strtotime($fuel_date));
                $data['fuel_year'] = date('Y', strtotime($fuel_date));

                $data['approval_status'] = 1;
                $data['status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");

                DB::table('employee_fuel_data')->insert($data);
            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeFuel?m=' . Input::get('company_id') . '#Innovative');
    }

    public function addHrLetters()
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        $letter_id = Input::get('letter_id');

        if ($letter_id == 1) {
            $data1['emp_id'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_warning_letter')->insertGetId($data1);
            return Redirect::to('hdc/viewHrWarningLetter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#Innovative');

        }

        if ($letter_id == 2) {
            $data1['emp_id'] = Input::get('emr_no');
            $data1['confirmation_from'] = Input::get('confirmation_from');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_mfm_south_increment_letter')->insertGetId($data1);
            return Redirect::to('hdc/viewHrMfmSouthIncrementLetter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#Innovative');

        }

        if ($letter_id == 3) {
            $data1['emp_id'] = Input::get('emr_no');
            $data1['performance_from'] = Input::get('performance_from');
            $data1['performance_to'] = Input::get('performance_to');
            $data1['confirmation_from'] = Input::get('confirmation_from');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_mfm_south_without_increment_letter')->insertGetId($data1);
            return Redirect::to('hdc/viewHrMfmSouthWithoutIncrementLetter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#Innovative');

        }
        if ($letter_id == 4) {
            $data1['emp_id'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['conclude_date'] = Input::get('conclude_date');
            $data1['settlement_date'] = Input::get('settlement_date');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_contract_conclusion_letter')->insertGetId($data1);
            return Redirect::to('hdc/viewHrContractConclusionLetter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#Innovative');

        }
        if ($letter_id == 5) {
            $data1['emp_id'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['settlement_date'] = Input::get('settlement_date');
            $data1['note'] = Input::get('note');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_termination_format1_letter')->insertGetId($data1);
            return Redirect::to('hdc/viewHrTerminationFormat1Letter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#Innovative');


        }
        if ($letter_id == 6) {
            $data1['emp_id'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['settlement_date'] = Input::get('settlement_date');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_termination_format2_letter')->insertGetId($data1);
            return Redirect::to('hdc/viewHrTerminationFormat2Letter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#Innovative');

        }

        if ($letter_id == 7) {
            $data1['emr_no'] = Input::get('emr_no');
            $data1['letter_content1'] = Input::get('letter_content1');
            $data1['letter_content2'] = Input::get('letter_content2');
            $data1['note'] = Input::get('note');
            $data1['transfer_date'] = Input::get('transfer_date');
            $data1['status'] = 1;
            $data1['approval_status'] = 1;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            $last_id = DB::table('hr_transfer_letter')->insertGetId($data1);
            return Redirect::to('hdc/viewHrTransferLetter/' . $last_id . '/' . Input::get('company_id') . '?m=' . Input::get('company_id') . '#Innovative');

        }

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
    }


    public function AddLettersFile(Request $request)
    {


        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        /*Image uploading start*/

        $extension = $request->file('letter_file')->getClientOriginalExtension();
        $file_name = Input::get('emr_no') . '_' . time() . '.' . $request->file('letter_file')->getClientOriginalExtension();
        $path = 'app/' . $request->file('letter_file')->storeAs('uploads/employee_hr_letters', $file_name);

        /*Image uploading end*/

        $data1['emr_no'] = Input::get('emr_no');
        $data1['letter_type'] = Input::get('letter_type');
        $data1['letter_path'] = $path;
        $data1['file_type'] = $extension;
        $data1['status'] = 1;
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");


        DB::table('letter_files')->insert($data1);
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/uploadLettersFile?&&m=' . Input::get('company_id') . '#Innovative');


    }

    public function addEquipmentDetail()
    {
        foreach (Input::get('equipment_name') as $key => $val):
            $data1['equipment_name'] = strip_tags($val);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = $_GET['m'];
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('employee_equipments')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEquipmentsList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Innovative');
    }

    public function addEmployeeEquipmentDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));
        DB::table('employee_equipments')->where('emp_id', Input::get('emp_id'))->delete();
        foreach (Input::get('equipment_id') as $key => $val):

            if (strip_tags($val) == 11):
                $data['model_number'] = Input::get('model_number');
                $data['mobile_number'] = Input::get('mobile_number');
                $data['sim_number'] = Input::get('sim_number');
            endif;

            $data['equipment_id'] = strip_tags($val);
            $data['emp_id'] = Input::get('emp_id');
            $data['username'] = Auth::user()->name;
            $data['status'] = 1;
            $data['approval_status'] = 1;
            $data['date'] = date("Y-m-d");
            $data['time'] = date("H:i:s");

            $last_insert_id = DB::table('employee_equipments')->insertGetId($data);

            if (strip_tags($val) == 9):

                if ($request->file('insurance_path')):
                    $file_name1 = Input::get('emp_id') . '_' . time() . '.' . $request->file('insurance_path')->getClientOriginalExtension();
                    $path1 = 'app/' . $request->file('insurance_path')->storeAs('uploads/employee_insurance_copy', $file_name1);
                    //   $data1['insurance_path'] = $path1;
                    //   $data1['insurance_type'] = $request->file('insurance_path')->getClientOriginalExtension();
                endif;

                $data1['insurance_number'] = Input::get('insurance_number');

                //  DB::table('employee')->where('emp_id', Input::get('emp_id'))->update($data1);
            endif;

            if (strip_tags($val) == 10):

                if ($request->file('eobi_path')):
                    $file_name1 = Input::get('emp_id') . '_' . time() . '.' . $request->file('eobi_path')->getClientOriginalExtension();
                    $path1 = 'app/' . $request->file('eobi_path')->storeAs('uploads/employee_eobi_copy', $file_name1);
                    $data2['eobi_path'] = $path1;
                    $data2['eobi_type'] = $request->file('eobi_path')->getClientOriginalExtension();
                endif;

                //  $data2['eobi_number'] = Input::get('eobi_number');

                //   DB::table('employee')->where('emp_id', Input::get('emp_id'))->update($data2);
            endif;

        endforeach;

        $log['table_name'] = 'employee_equipments';
        $log['activity_id'] = $last_insert_id;
        $log['deleted_emr_no'] = null;
        $log['activity'] = 'Insert';
        $log['module'] = 'hr';
        $log['username'] = Auth::user()->name;
        $log['date'] = date("Y-m-d");
        $log['time'] = date("H:i:s");
        DB::table('log')->insert($log);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeEquipmentsList?m=' . Input::get('company_id') . '#Innovative');
    }

    public function addDiseaseDetail()
    {
        foreach (Input::get('disease_type') as $key => $val):
            $data1['disease_type'] = strip_tags($val);
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = Input::get('company_id');
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('diseases')->insert($data1);
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewDiseasesList?m=' . Input::get('company_id') . '#Innovative');
    }

    public function addEmployeeMedicalDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $emr_no = Input::get('emr_no');

        $counter = 0;
        if ($request->file('medical_file_path')) {
            foreach ($request->file('medical_file_path') as $media) {
                if (!empty($media)) {
                    $counter++;
                    $file_name = 'EmrNo_' . $emr_no . '_employee_medical_file_' . time() . '_' . $counter . '.' . $media->getClientOriginalExtension();
                    $path = $media->storeAs('uploads/employee_medical_documents', $file_name);

                    $fileUploadData['emr_no'] = $emr_no;
                    $fileUploadData['medical_file_name'] = $file_name;
                    $fileUploadData['medical_file_type'] = $media->getClientOriginalExtension();
                    $fileUploadData['medical_file_path'] = 'app/' . $path;
                    $fileUploadData['status'] = 1;
                    $fileUploadData['counter'] = $counter;
                    $fileUploadData['username'] = Auth::user()->name;
                    $fileUploadData['date'] = date("Y-m-d");
                    $fileUploadData['time'] = date("H:i:s");
                    DB::table('employee_medical_documents')->insert($fileUploadData);
                }
            }
        }

        $data1['emr_no'] = $emr_no;
        $data1['disease_type_id'] = Input::get('disease_type_id');
        $data1['disease_date'] = Input::get('disease_date');
        $data1['amount'] = Input::get('amount');
        $data1['cheque_number'] = Input::get('cheque_number');
        $data1['username'] = Auth::user()->name;
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('employee_medical')->insert($data1);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeMedicalList?m=' . Input::get('company_id') . '#Innovative');
    }

    public function addTrainingDetail(Request $request)
    {

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        if (Input::get('participant_type') == '1'):
            $participants = implode(Input::get('participants_name'), ',');
        else:
            $participants = Input::get('participants_name');
        endif;

        $data1['region_id'] = Input::get('region_id');
        $data1['participant_type'] = Input::get('participant_type');
        $data1['employee_category_id'] = Input::get('emp_category_id');
        $data1['participants'] = $participants;
        $data1['location_id'] = Input::get('location_id');
        $data1['training_date'] = Input::get('training_date');
        $data1['topic_name'] = Input::get('topic_name');
        $data1['username'] = Auth::user()->name;
        $data1['trainer_name'] = Input::get('trainer_name');
        $data1['certificate_number'] = Input::get('certificate_number');
        $data1['status'] = 1;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        $id = DB::table('trainings')->insertGetId($data1);
        $certificate_uploading = $request->file('certificate_uploading');
        $extention = [];
        foreach ($certificate_uploading as $key => $value) {
            $file_name = Input::get('certificate_number') . time() . '.' . $value->getClientOriginalExtension();
            $paths = 'app/' . $value->storeAs('uploads/training_certificate', $file_name);
            $path = $_FILES['certificate_uploading']['name'][$key];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $extention['file_type'] = $ext;
            $extention['certificate_uploading'] = $paths;
            $extention['training_id'] = $id;
            $extention['date'] = date("Y-m-d");
            $extention['time'] = date("H:i:s");

            DB::table('training_certificate')->insert($extention);
        }
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createTrainingForm?m=' . Input::get('m') . '');


    }

    public function addEmployeeGratuityDetail()
    {

        $acc_no = (unserialize(base64_decode(Input::get('emr_no'))));

        foreach ($acc_no as $value):


            $data1['emr_no'] = $value;
            $data1['from_date'] = Input::get('from_date_' . $value);
            $data1['to_date'] = Input::get('till_date_' . $value);
            $data1['year_month'] = Input::get('year_month_' . $value);
            $data1['gratuity'] = Input::get('gratuity_' . $value);
            $data1['employee_category_id'] = Input::get('emp_category_id_' . $value);
            $data1['region_id'] = Input::get('region_id_' . $value);
            $data1['username'] = Auth::user()->name;
            $data1['status'] = 1;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            CommonHelper::companyDatabaseConnection(Input::get('m'));
            DB::table('gratuity')->where('emr_no', $value)->delete();
            DB::table('gratuity')->insert($data1);
            CommonHelper::reconnectMasterDatabase();
        endforeach;

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createEmployeeGratuityForm?m=' . Input::get('m') . '');

    }

    public function uploadOvertimeAndFuelFile()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month = date('m', strtotime(Input::get('month_year')));
        $year = date('Y', strtotime(Input::get('month_year')));
        echo "<pre>";
        $data = Excel::toArray(true, request()->file('overtimeAndFuelFile'));


        $counter1 = 1;
        foreach ($data as $value):
            foreach ($value as $value2):
                if ($counter1 == 1):

                    if ($value2[4] == '' || $value2[4] == 'EMR #' || $value2[4] == 'EMR # ') continue;

                    DB::table('overtime')->where([['month', '=', $month], ['year', '=', $year], ['emr_no', '=', $value2[4]]])->delete();

                    $overtime['employee_category_id'] = Input::get('emp_category_id');
                    $overtime['region_id'] = Input::get('region_id');
                    $overtime['emr_no'] = $value2[4];
                    $overtime['month'] = $month;
                    $overtime['year'] = $year;
                    $overtime['gross_salary'] = round($value2[5]);
                    $overtime['ot_claimed_hours'] = $value2[6];
                    $overtime['ot_verified_hours'] = $value2[7];
                    $overtime['per_hour_ot_rate'] = round($value2[8]);
                    $overtime['ot_for_month'] = round($value2[9]);
                    $overtime['bank_account_no'] = $value2[10];
                    $overtime['username'] = Auth::user()->name;
                    $overtime['status'] = 1;
                    $overtime['date'] = date("Y-m-d");
                    $overtime['time'] = date("H:i:s");

                    DB::table('overtime')->insert($overtime);

                    $account_no = EmployeeBankData::where([['emr_no', '=', $value2[4]], ['status', '=', 1]]);
                    if ($account_no->count() > 0):
                        $accno['account_no'] = $value2[10];
                        EmployeeBankData::where([['emr_no', '=', $value2[4]]])->update($accno);

                    else:
                        $data2['emr_no'] = $value2[4];
                        $data2['account_title'] = "-";
                        $data2['bank_name'] = "-";
                        $data2['account_no'] = $value2[10];
                        $data2['username'] = Auth::user()->name;
                        $data2['status'] = 1;
                        $data2['date'] = date("Y-m-d");
                        $data2['time'] = date("H:i:s");
                        DB::table('employee_bank_data')->insert($data2);
                    endif;

                elseif ($counter1 == 2):

                    if ($value2[5] == '' || $value2[5] == 'EMR ' || $value2[5] == 'EMR') continue;

                    DB::table('fuel')->where([['month', '=', $month], ['year', '=', $year], ['emr_no', '=', $value2[5]]])->delete();

                    $fuel['employee_category_id'] = Input::get('emp_category_id');
                    $fuel['region_id'] = Input::get('region_id');
                    $fuel['emr_no'] = $value2[5];
                    $fuel['month'] = $month;
                    $fuel['year'] = $year;
                    $fuel['monthly_salary'] = round($value2[6]);
                    $fuel['km'] = round($value2[7]);
                    $fuel['rate'] = round($value2[8]);
                    $fuel['amount'] = round($value2[9]);
                    $fuel['bank_account_no'] = $value2[10];
                    $fuel['username'] = Auth::user()->name;
                    $fuel['status'] = 1;
                    $fuel['date'] = date("Y-m-d");
                    $fuel['time'] = date("H:i:s");

                    DB::table('fuel')->insert($fuel);

                    $account_no = EmployeeBankData::where([['emr_no', '=', $value2[5]], ['status', '=', 1]]);
                    if ($account_no->count() > 0):
                        $accno['account_no'] = $value2[10];
                        EmployeeBankData::where([['emr_no', '=', $value2[5]]])->update($accno);

                    else:
                        $data2['emr_no'] = $value2[5];
                        $data2['account_title'] = "-";
                        $data2['bank_name'] = "-";
                        $data2['account_no'] = $value2[10];
                        $data2['username'] = Auth::user()->name;
                        $data2['status'] = 1;
                        $data2['date'] = date("Y-m-d");
                        $data2['time'] = date("H:i:s");
                        DB::table('employee_bank_data')->insert($data2);
                    endif;

                elseif ($counter1 == 3):

                    if ($value2[1] == '' || $value2[1] == 'Name ' || $value2[1] == 'Name') continue;

                    DB::table('drivers_allowance')->where([['month', '=', $month], ['year', '=', $year], ['emp_name', '=', $value2[1]]])->delete();

                    $driver['employee_category_id'] = Input::get('emp_category_id');
                    $driver['region_id'] = Input::get('region_id');
                    $driver['month'] = $month;
                    $driver['year'] = $year;
                    $driver['emp_name'] = $value2[1];
                    $driver['designation'] = $value2[2];
                    $driver['location'] = $value2[3];
                    $driver['cost_center'] = $value2[4];
                    $driver['psgl'] = $value2[5];
                    $driver['hours'] = round($value2[6]);
                    $driver['salary'] = round($value2[7]);
                    $driver['rate'] = round($value2[8]);
                    $driver['ot_labour_law'] = round($value2[10]);
                    $driver['allowance_on_holiday'] = round($value2[11]);
                    $driver['allowance_on_workingday'] = round($value2[12]);
                    $driver['parking_charges'] = round($value2[13]);
                    $driver['out_of_city'] = round($value2[14]);
                    $driver['puncture'] = round($value2[15]);
                    $driver['mobile_charges'] = $value2[16];
                    $driver['total_allowance'] = round($value2[17]);
                    $driver['bank_account_no'] = $value2[18];
                    $driver['username'] = Auth::user()->name;
                    $driver['status'] = 1;
                    $driver['date'] = date("Y-m-d");
                    $driver['time'] = date("H:i:s");

                    DB::table('drivers_allowance')->insert($driver);

                endif;
            endforeach;
            $counter1++;
        endforeach;

        $log1['table_name'] = 'overtime';
        $log1['activity_id'] = null;
        $log1['deleted_emr_no'] = null;
        $log1['activity'] = 'Upload';
        $log1['module'] = 'hr';
        $log1['username'] = Auth::user()->name;
        $log1['date'] = date("Y-m-d");
        $log1['time'] = date("H:i:s");
        DB::table('log')->insert($log1);

        $log2['table_name'] = 'fuel';
        $log2['activity_id'] = null;
        $log2['deleted_emr_no'] = null;
        $log2['activity'] = 'Upload';
        $log2['module'] = 'hr';
        $log2['username'] = Auth::user()->name;
        $log2['date'] = date("Y-m-d");
        $log2['time'] = date("H:i:s");
        DB::table('log')->insert($log2);

        $log3['table_name'] = 'drivers_allowance';
        $log3['activity_id'] = null;
        $log3['deleted_emr_no'] = null;
        $log3['activity'] = 'Upload';
        $log3['module'] = 'hr';
        $log3['username'] = Auth::user()->name;
        $log3['date'] = date("Y-m-d");
        $log3['time'] = date("H:i:s");
        DB::table('log')->insert($log3);

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/uploadOtAndFuelFile?pageType=viewlist&&parentCode=8&&m=' . Input::get('m') . '#Innovative');

    }

    public function addEmployeeTransferLeave()
    {
        $duplicate_leave_policy = DB::table('transfered_leaves')->where([['leaves_policy_id', '=', Input::get('leaves_policy_id')], ['status', '=', 1]])->first();
        if (Input::get('assign_all_emp') != '' && count($duplicate_leave_policy) == '0') {
            $empCode = unserialize(base64_decode(Input::get('empCode')));

            foreach ($empCode as $value):
                DB::table('transfered_leaves')->where([['leaves_policy_id', '=', Input::get('leaves_policy_id')], ['emr_no', '=', $value]])->delete();
                $data['emr_no'] = $value;
                $data['leaves_policy_id'] = Input::get('leaves_policy_id');
                $data['casual_leaves'] = (Input::get('casualLeaves_' . $value) < 1 ? 0 : Input::get('casualLeaves_' . $value));
                $data['sick_leaves'] = 0;
                $data['annual_leaves'] = (Input::get('annualLeaves_' . $value) < 1 ? true : Input::get('annualLeaves_' . $value));
                $data['status'] = 1;
                $data['username'] = Auth::user()->name;
                $data['date'] = date("Y-m-d");
                $data['time'] = date("H:i:s");
                DB::table('transfered_leaves')->insert($data);
            endforeach;

            $companiesList = DB::Table('company')->select('id', 'name')->get()->toArray();
            foreach ($companiesList as $companyData):
                CommonHelper::companyDatabaseConnection($companyData->id);
                $employees = Employee::select('emr_no')->where([['status', '=', 1]])->get()->toArray();
                foreach ($employees as $employeesValue):

                    if (in_array($employeesValue['emr_no'], $empCode)):
                        DB::Table('employee')->where([['emr_no', '=', $employeesValue['emr_no']]])->update(array('leaves_policy_id' => Input::get('leaves_policy_id')));
                    endif;

                endforeach;
                CommonHelper::reconnectMasterDatabase();
            endforeach;
            Session::flash('dataInsert', 'successfully saved.');
            return Redirect::to('hr/employeeTransferLeaves?m=' . Input::get('company_id') . '#Innovative');
        } else {
            return Redirect::to('hr/employeeTransferLeaves?m=' . Input::get('company_id') . '#Innovative');
        }
    }
    public function addManualyAttendances()
    {


        $to_date = explode('-', Input::get('to_date'));
        $month = $to_date[1];
        $year = $to_date[0];
        $name_array = $_POST['attendance_date'];

        for ($i = 0; $i < count($name_array); $i++) {
            // echo Input::get('clock_in')[$i];
            // echo "<br>";
            // echo Input::get('clock_out')[$i];

            if (Input::get('clock_in')[$i] == ''):
                $check_in_time_24 = '';
            else:
                $check_in_time_24 = date("H:i", strtotime(Input::get('clock_in')[$i]));
            endif;

            if (Input::get('clock_out')[$i] == ''):
                $check_out_time_24 = '';
            else:
                $check_out_time_24 = date("H:i", strtotime(Input::get('clock_out')[$i]));
            endif;

            $manualyAttData['emp_id'] = Input::get('emp_id')[$i];
            // $manualyAttData['emp_name'] = Input::get('emp_name')[$i];
            $manualyAttData['day'] = Input::get('day')[$i];
            $manualyAttData['month'] = $month;
            $manualyAttData['year'] = $year;
            $manualyAttData['attendance_date'] = Input::get('attendance_date')[$i];
            $manualyAttData['clock_in'] = $check_in_time_24;
            $manualyAttData['clock_out'] = $check_out_time_24;
            $manualyAttData['status'] = 1;
            $manualyAttData['remarks'] = Input::get('remarks');
            $manualyAttData['username'] = Auth::user()->name;
            $manualyAttData['date'] = date("Y-m-d");
            $manualyAttData['time'] = date("H:i:s");

            CommonHelper::companyDatabaseConnection(Input::get('m'));

            //DB::table('attendance')->insert($manualyAttData);


            $attendance = DB::table('attendance')->where([['attendance_date', '=', Input::get('attendance_date')[$i]], ['emp_id', '=', Input::get('emp_id')[$i]]]);

            if ($attendance->count() > 0):
                DB::table('attendance')->where([['attendance_date', '=', Input::get('attendance_date')[$i]], ['emp_id', '=', Input::get('emp_id')[$i]]])->update($manualyAttData);
            else:
                DB::table('attendance')->insert($manualyAttData);
            endif;

            //print_r($manualyAttData);
            CommonHelper::reconnectMasterDatabase();
        }

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createManageAttendanceForm?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . Input::get('m') . '#Innovative');
    }

     public function uploadAttendanceFile(Request $request)
    {
//		$var = "20/04/2012";
//        echo date("Y-m-d", strtotime($var) );
//        echo "<pre>";

        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection($m);
        if ($request->hasFile('sample_file')):

            $counter = 1;
            $file = Excel::toArray(new UsersImport, request()->file('sample_file'));
            $month_year_attendance = explode('-', Input::get('month_year_attendance'));

            if($file[0][1][0]== trim('Staff ID') && $file[0][1][1]== trim('Name') && $file[0][1][2]== trim('Department')
                && $file[0][1][3]== trim('Date') && $file[0][1][4]== trim('Week') && $file[0][1][5]== trim('Duty|On')
                && $file[0][1][6]== trim('Duty|Off') && $file[0][1][7]== trim('Duty|Time')){

                foreach ($file[0] as $key => $value):

                    if ($counter++ <= 2) continue;

                    $excel_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[3]));
                    $excel_attendance_date = Carbon::parse($excel_date);
                    $excel_date=explode(' ',$excel_attendance_date);
                    $excel_attendance_date=$excel_date[0];
                    $excel_month = date('m', strtotime(str_replace('/', '-',$excel_attendance_date)));
                    $excel_year = date('Y', strtotime(str_replace('/', '-',$excel_attendance_date)));

                    DB::Table('attendance')->where([
                        ['emp_id', '=', $value[0]],
                        ['attendance_date', '=',$excel_attendance_date]])
                        ->delete();

                    $data['emp_id'] = $value[0];
                    $data['attendance_date'] = $excel_attendance_date;
                    $data['day'] = $value[4];
                    $data['month'] = $excel_month;
                    $data['year'] = $excel_year;
                    $data['duty_time'] = ($value[7]);
                    $data['clock_in'] = ($value[5]);
                    $data['clock_out'] = ($value[6]);
                    $data['status'] = 1;
                    $data['username'] = Auth::user()->name;
                    $data['date'] = date("Y-m-d");
                    $data['time'] = date("H:i:s");
                    DB::Table('attendance')->insert($data);
                    $counter++;
                endforeach;

            }else{
                Session::flash('dataDelete', 'Please Upload with the given format!');
                return Redirect::to('hr/createManageAttendanceForm?m=' . Input::get('m') . '');
            }

            CommonHelper::reconnectMasterDatabase();

            Session::flash('dataInsert', 'Attendance Imported Successfully !');
            return Redirect::to('hr/createManageAttendanceForm?m=' . Input::get('m') . '');

        endif;

    }

    public function addEmployeeDeductionDays()
    {

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        session(['fromDate' => Input::get('from_date')]);
        session(['toDate' => Input::get('to_date')]);

        $to_date = explode('-', Input::get('to_date'));
        foreach (Input::get('emp_id') as $value):
            $month = $to_date[1];
            $year = $to_date[0];


            $payrollDeduction["emp_id"] = $value;
            $payrollDeduction["total_days"] = Input::get('total_days_' . $value);
            $payrollDeduction["total_present"] = Input::get('total_present_' . $value);
            $payrollDeduction["total_absent"] = Input::get('total_absent_' . $value);
            $payrollDeduction["total_holidays"] = Input::get('total_holidays_' . $value);
            $payrollDeduction["total_ot"] = 0;
            $payrollDeduction["total_ot_hours"] = Input::get('total_ot_hours_' . $value);
            $payrollDeduction["total_late_arrivals"] = Input::get('total_late_arrivals_' . $value);
            $payrollDeduction["deduction_days"] = Input::get('deduction_days_' . $value);
            $payrollDeduction["total_halfday_count"] = Input::get('total_halfday_count_' . $value);
            $payrollDeduction["total_leaves_count"] = Input::get('total_leaves_count_' . $value);
            $payrollDeduction["total_ot_count_np"] = Input::get('total_ot_count_np_' . $value);
            $payrollDeduction["total_late_hours_count"] = Input::get('total_late_hours_count_' . $value);
            $payrollDeduction["total_early_hours_count"] = Input::get('total_early_hours_count_' . $value);
            $payrollDeduction["total_halfdays"] = Input::get('total_halfdays_' . $value);
            $payrollDeduction["remarks"] = Input::get('remarks_' . $value);
            $payrollDeduction["month"] = $month;
            $payrollDeduction["year"] = $year;
            $payrollDeduction['username'] = Auth::user()->name;
            $payrollDeduction['status'] = 1;
            $payrollDeduction['date'] = date("Y-m-d");
            $payrollDeduction['time'] = date("H:i:s");

            $already = DB::table('payroll_data')->where([['emp_id', '=', $value], ['month', '=', $month], ['year', '=', $year], ['approval_status_m', '=', 2]]);

            if ($already->count() < 1):
                DB::table('payroll_data')->where([['emp_id', '=', $value], ['month', '=', $month], ['year', '=', $year]])->delete();
                DB::table('payslip')->where([['emp_id', '=', $value], ['month', '=', $month], ['year', '=', $year]])->delete();

                DB::table('payroll_data')->insert($payrollDeduction);
            endif;
        endforeach;

        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/ViewAttendanceProgress?m=' . Input::get('company_id') . '#Innovative');

    }

    public function addEmployeeProjectsDetail()
    {

        $employeeProjectSection = Input::get('project_name');
        foreach ($employeeProjectSection as $row) {

            $data1['project_name'] = $row;
            $data1['username'] = Auth::user()->name;
            $data1['company_id'] = Input::get('m');
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('employee_projects')->insert($data1);
        }
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeProjectsList?m=' . Input::get('m') . '#Innovative');
    }

    public function addEmployeeOfTheMonthDetail()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $month_year = explode('-', Input::get('month_year'));

        $data1['emp_id'] = Input::get('emp_id');
        $data1['month'] = $month_year[1];
        $data1['year'] = $month_year[0];
        $data1['remarks'] = Input::get('remarks');
        $data1['status'] = 1;
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('employee_of_the_month')->insert($data1);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewEmployeeOfTheMonth?m=' . Input::get('m') . '');
    }

    public function uploadPolicyFileDetail(Request $request)
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $counter = 0;
        $title = Input::get('title');
        $category_id = Input::get('category_id');
        if ($request->file('policy_file')):
            foreach ($request->file('policy_file') as $media):
                if (!empty($media)):
                    $counter++;
                    $file_name = $title . ' ' . $counter . '.' . $media->getClientOriginalExtension();
                    $path = $media->storeAs('uploads/policies', $file_name);

                    $data['category_id'] = $category_id;
                    $data['title'] = $title;
                    $data['file_name'] = $file_name;
                    $data['file_type'] = $media->getClientOriginalExtension();
                    $data['file_path'] = 'app/' . $path;
                    $data['status'] = 1;
                    $data['counter'] = $counter;
                    $data['username'] = Auth::user()->name;
                    $data['date'] = date("Y-m-d");
                    $data['time'] = date("H:i:s");
                    DB::table('policies')->insert($data);
                    print_r($data);
                endif;
            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataInsert', 'Successfully Saved.');
        return Redirect::to('hr/uploadPolicyFile?m=' . Input::get('m') . '');
    }

    public function addProvidentFundDetail()
    {

        foreach (Input::get('pf_name') as $key => $val):

            $data1['name'] = $val;
            $data1['pf_mode'] = Input::get('pf_mode')[$key];
            $data1['amount_percent'] = Input::get('amount_percent')[$key];
            $data1['status'] = 1;
            $data1['company_id'] = Input::get('company_id');
            $data1['username'] = Auth::user()->name;;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");

            DB::table('provident_fund')->insert($data1);

        endforeach;
        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/viewProvidentFundList?m=' . Input::get('company_id') . '#Innovative');

    }

    public function addProvidentFundDisburse()
    {

        $month_year = Input::get('month_year');
        $month_data_2nd = (explode("-", $month_year));

        $year = $month_data_2nd[0];
        $month = $month_data_2nd[1];

        CommonHelper::companyDatabaseConnection(Input::get('company_id'));

        $providentFundData['provident_fund_id'] = Input::get('provident_fund_id');
        $providentFundData['emp_id'] = Input::get('emp_id');
        $providentFundData['pf_amount'] = Input::get('disburse_amount');
        $providentFundData['pf_employee_fund'] = round(Input::get('disburse_amount') / 2);
        $providentFundData['pf_company_fund'] = round(Input::get('disburse_amount') / 2);
        $providentFundData['amount_type'] = 'minus';
        $providentFundData['month'] = $month;
        $providentFundData['year'] = $year;
        $providentFundData['username'] = Auth::user()->name;
        $providentFundData['status'] = 1;
        $providentFundData['date'] = date("Y-m-d");
        $providentFundData['time'] = date("H:i:s");
        DB::table('provident_fund_data')->insert($providentFundData);

        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/providentFundReport?m=' . Input::get('company_id') . '#Innovative');


    }

    public function addPfOpeningBalanceDetail()
    {

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $provident_fund = Employee::where([['emp_id', '=', Input::get('emp_id')]]);

        if ($provident_fund->value('provident_fund_id') == 0) {
            return "Please Select Provident Fund Policy For User !";
        } else {
            $provident_fund_data = $provident_fund->first();

        }

        ProvidentFundData::where([['emp_id', '=', Input::get('emp_id')], ['view', '=', 'no'], ['provident_fund_id', '=', $provident_fund_data->provident_fund_id]])->delete();
        $providentFundData['provident_fund_id'] = $provident_fund_data->provident_fund_id;
        $providentFundData['emp_id'] = Input::get('emp_id');
        $providentFundData['pf_amount'] = Input::get('opening_balance');
        $providentFundData['pf_employee_fund'] = round(Input::get('opening_balance') / 2);
        $providentFundData['pf_company_fund'] = round(Input::get('opening_balance') / 2);
        $providentFundData['amount_type'] = 'plus';
        $providentFundData['view'] = 'no';
        $providentFundData['username'] = Auth::user()->name;
        $providentFundData['status'] = 1;
        $providentFundData['date'] = date("Y-m-d");
        $providentFundData['time'] = date("H:i:s");
        DB::table('provident_fund_data')->insert($providentFundData);
        CommonHelper::reconnectMasterDatabase();

        Session::flash('dataInsert', 'successfully saved.');
        return Redirect::to('hr/createPfOpeningBalance?m=' . Input::get('m') . '');
    }
}