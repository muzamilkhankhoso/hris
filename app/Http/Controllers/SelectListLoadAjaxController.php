<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Config;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use App\Models\Employee;
use App\Models\TransferEmployeeProject;
use App\Models\LoanRequest;

class SelectListLoadAjaxController extends Controller
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

    public function stateLoadDependentCountryId(){
        $country_id = Input::get('id');
        $priviousStateId = Input::get('priviousStateId');
        $states = States::where('status', '=', 1)
            ->where('country_id','=',$country_id)->get();
        foreach($states as $row){
            if($priviousStateId == '0'){
                $selectedStatus = '';
            }else if($priviousStateId == $row->id){
                $selectedStatus = 'selected';
            }else{
                $selectedStatus = '';
            }
            ?>
            <option value="<?php echo $row['id']?>" <?php echo $selectedStatus?>><?php echo $row['name'];?></option>
            <?php
        }
    }

    public function cityLoadDependentStateId(){
        $state_id = Input::get('id');
        $priviousCityId = Input::get('priviousCityId');
        $cities = Cities::where('status', '=', 1)
            ->where('state_id','=',$state_id)->get();
        foreach($cities as $row){
            if($priviousCityId == '0'){
                $selectedStatus = '';
            }else if($priviousCityId == $row->id){
                $selectedStatus = 'selected';
            }else{
                $selectedStatus = '';
            }
            ?>
            <option value="<?php echo $row['id']?>" <?php echo $selectedStatus?>><?php echo $row['name'];?></option>
            <?php
        }
    }

    public function employeeLoadDependentDepartmentID(){
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::select('emp_id','emp_name')->where('status', '=', 1)
            ->where('emp_sub_department_id','=',Input::get('sub_department_id'))->get();
        ?>
        <option value="All">All Employees</option>
        <?php
        foreach($employees as $row){
            ?>
            <option value="<?php echo $row['emp_id']?>"><?php echo $row['emp_id'].'--'.$row['emp_name'];?></option>
            <?php
        }
        CommonHelper::reconnectMasterDatabase();
    }


    public function MachineAllEmployeeList()
    {

        $companies =  DB::table('company')->select('id', 'name')->where([['status','=',1]])->get()->toArray();
        $unique_emp = '';
        $emp_arr =array();
        foreach ($companies as $value2):

            CommonHelper::companyDatabaseConnection($value2->id);

            $emp_name =  Employee::select('emp_name','acc_no')->where([['status','=',1]]);
            if($emp_name->count() > 0 ):
                foreach($emp_name->get() as $value):

                    if(!in_array($value->acc_no,$emp_arr)):
                        $unique_emp[]=$value2->id."|".$value->acc_no."|".$value->emp_name;
                    endif;
                    $emp_arr[]=$value->acc_no;
                endforeach;
            endif;
            CommonHelper::reconnectMasterDatabase();
        endforeach;

        foreach ($unique_emp as $EmpValue):
            $emp_values_explode = explode("|",$EmpValue);
            ?>
            <option value="<?php echo $emp_values_explode[0]."|".$emp_values_explode[1]  ?>"><?= $emp_values_explode[2]?></option>

        <?php  endforeach;
    }
    public function MachineEmployeeListDeptWise()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $employees = Employee::select('emr_no','emp_name')->where('status', '=', 1)->where('emp_sub_department_id','=',Input::get('department_id'));

        if($employees->count() > 0):
            echo "<option value='All'>All</option>";
            foreach($employees->get() as $row){ ?>
                <option value="<?php echo $row['emr_no']?>"><?php echo 'EMR-No: '.$row['emr_no'].'---'.$row['emp_name'];?></option>
                <?php
            }
        else:
            echo "<option value=''>No Record Found</option>";
        endif;
        CommonHelper::reconnectMasterDatabase();
    }

    public function getEmployeeProjectList()
    {
        $regions =  CommonHelper::regionRights(Input::get('m'));
        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $emr_no = array();
        $transfer = '';
        $TransferEmployeeProject = TransferEmployeeProject::where([['emp_region_id', '=', Input::get('region_id')], ['status', '=', '1'],['emp_categoery_id', '=', Input::get('emp_category_id')], ['employee_project_id', '=', Input::get('employee_project_id')],['active','=',1]]);
        echo "<option value='All'>All</option>";
        if($TransferEmployeeProject->count() > 0){
            $transfer = DB::table('transfer_employee_project')
                ->join('employee','transfer_employee_project.emr_no','=','employee.emr_no')
                ->select('transfer_employee_project.emr_no','employee.emp_name')
                ->whereIn('region_id', $regions)
                ->where([['transfer_employee_project.emp_region_id', '=', Input::get('region_id')], ['transfer_employee_project.emp_categoery_id', '=', Input::get('emp_category_id')], ['transfer_employee_project.employee_project_id', '=', Input::get('employee_project_id')],['transfer_employee_project.status','=',1],['transfer_employee_project.active','=',1]])
                ->get();

            foreach($transfer as $value){
                $emr_no[] = $value->emr_no;
            }
            if(count($transfer) != '0'){
                foreach ($transfer as $row1) { ?>
                    <option value="<?php echo $row1->emr_no ?>"><?php echo 'EMR-No: ' . $row1->emr_no . '---' . $row1->emp_name; ?></option>
                    <?php
                }
            }
        }

        $employees = Employee::select('emr_no', 'emp_name')
            ->whereIn('region_id', $regions)
            ->whereNotIn('emr_no', $emr_no)
            ->where([['region_id','=', Input::get('region_id')], ['status', '=', '1'], ['employee_category_id', '=', Input::get('emp_category_id')],['employee_project_id', '=', Input::get('employee_project_id')],['active','=',1]]);

        if ($employees->count() >0){
            foreach ($employees->get() as $row) { ?>
                <option value="<?php echo $row['emr_no'] ?>"><?php echo 'EMR-No: ' . $row['emr_no'] . '---' . $row['emp_name']; ?></option>
                <?php
            }
        }
        else{
            echo "<option value=''>No Record Found</option>";
        }

    }

    public function getEmployeeCategoriesList()
    {
        CommonHelper::companyDatabaseConnection(Input::get('m'));

        $employees = Employee::select('emp_id', 'emp_name')
            ->where([['emp_department_id','=', Input::get('department_id_')], ['status', '=', '1'], ['emp_sub_department_id', '=', Input::get('sub_department_id_1')]]);
        if ($employees->count() > 0):
            echo "<option value='All'>All</option>";
            foreach ($employees->get() as $row) { ?>
                <option value="<?php echo $row['emp_id'] ?>"><?php echo 'EMP-ID: ' . $row['emp_id'] . '---' . $row['emp_name']; ?></option>
                <?php
            }
        else:
            echo "<option value=''>No Record Found</option>";
        endif;

        CommonHelper::reconnectMasterDatabase();
    }

    public function getSubDepartment(){


        $department = Input::get('department');
        $sub_department = Input::get('sub_department');
        $line_manager = Input::get('line_managers');
        $m = Input::get('m');

        CommonHelper::companyDatabaseConnection($m);

        if($line_manager == 'line_managers' && $sub_department == '0'){
            $employee = Employee::where([['emp_sub_department_id','=',$department],['status','=',1],['reporting_manager','=',Auth::user()->emp_id]]);
        }
        else if($line_manager == 'line_managers' && $sub_department != '0'){
            $employee = Employee::where([['emp_sub_department_id','=',$department],['employee_project_id','=',$sub_department],['status','=',1],['reporting_manager','=',Auth::user()->emp_id]]);
        }
        else if($sub_department == '0'){
            $employee = Employee::where([['emp_department_id','=',$department],['status','=',1]]);
        }
        else{
            $employee = Employee::where([['emp_sub_department_id','=',$sub_department],['emp_department_id','=',$department],['status','=',1]]);
        }

        CommonHelper::reconnectMasterDatabase();
        if($employee->count() > 0){
            ?>
            <option value="all">All</option>
            <?php
            foreach($employee->get() as $value){
                ?>
                <option value="<?php echo $value->emp_id ?>"><?php echo  $value->emp_id . '-' . $value->emp_name; ?></option>
                <?php
            }
        }
        else{
            ?><option value="emptyvalue">No Record fOUND</option><?php
        }

    }

    public function getAllSubDepartment(){


        $department = Input::get('department');
        $sub_department = Input::get('sub_department');
        $line_manager = Input::get('line_managers');
        $m = Input::get('m');

        CommonHelper::companyDatabaseConnection($m);

        if($line_manager == 'line_managers' && $sub_department == '0'){
            $employee = Employee::where([['emp_sub_department_id','=',$department],['status','=',1],['reporting_manager','=',Auth::user()->emp_id]]);
        }
        else if($line_manager == 'line_managers' && $sub_department != '0'){
            $employee = Employee::where([['emp_sub_department_id','=',$department],['employee_project_id','=',$sub_department],['status','=',1],['reporting_manager','=',Auth::user()->emp_id]]);
        }
        else if($sub_department == '0'){
            $employee = Employee::where([['emp_department_id','=',$department],['status','!=',2]]);
        }
        else{
            $employee = Employee::where([['emp_sub_department_id','=',$sub_department],['emp_department_id','=',$department],['status','!=',2]]);
        }

        CommonHelper::reconnectMasterDatabase();
        if($employee->count() > 0){
            ?>
            <option value="all">All</option>
            <?php
            foreach($employee->get() as $value){
                ?>
                <option value="<?php echo $value->emp_id ?>"><?php echo  $value->emp_id . '-' . $value->emp_name; ?></option>
                <?php
            }
        }
        else{
            ?><option value="emptyvalue">No Record fOUND</option><?php
        }

    }

    public function getExitSubDepartment(){


        $department = Input::get('department');
        $sub_department = Input::get('sub_department');
        $line_manager = Input::get('line_managers');
        $m = Input::get('m');

        CommonHelper::companyDatabaseConnection($m);

        if($line_manager == 'line_managers' && $sub_department == '0'){
            $employee = Employee::where([['emp_sub_department_id','=',$department],['status','=',3],['reporting_manager','=',Auth::user()->emp_id]]);
        }
        else if($line_manager == 'line_managers' && $sub_department != '0'){
            $employee = Employee::where([['emp_sub_department_id','=',$department],['employee_project_id','=',$sub_department],['status','=',3],['reporting_manager','=',Auth::user()->emp_id]]);
        }
        else if($sub_department == '0'){
            $employee = Employee::where([['emp_department_id','=',$department],['status','=',3]]);
        }
        else{
            $employee = Employee::where([['emp_sub_department_id','=',$sub_department],['emp_department_id','=',$department],['status','=',3]]);
        }

        CommonHelper::reconnectMasterDatabase();
        if($employee->count() > 0){
            ?>
            <option value="all">All</option>
            <?php
            foreach($employee->get() as $value){
                ?>
                <option value="<?php echo $value->emp_id ?>"><?php echo  $value->emp_id . '-' . $value->emp_name; ?></option>
                <?php
            }
        }
        else{
            ?><option value="emptyvalue">No Record Found</option><?php
        }

    }


    public function viewEmployeeLoansList()
    {

        CommonHelper::companyDatabaseConnection(Input::get('m'));
        $loansList = LoanRequest::where('emp_id',Input::get('emp_id'));

        echo "<option value=''>Select</option>";
        if($loansList->count() > 0){
            foreach($loansList->get() as $value){
                $loan_amount=HrHelper::hideConfidentiality(Input::get('m')) == 'no' ? $value->loan_amount : '******' ?>
                <option value="<?php echo $value->id ?>"><?php echo 'Amount: ' . $loan_amount ?></option>
                <?php
            }
        }
        else{
            ?><option value="emptyvalue">No Record fOUND</option><?php
        }
        CommonHelper::reconnectMasterDatabase();
    }
}
