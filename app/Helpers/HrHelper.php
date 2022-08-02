<?php
namespace App\Helpers;
use DB;
use Carbon\Carbon;
use Config;
use Input;
use Session;
use App\Helpers\CommonHelper;
use App\Models\Attendance;
use App\Models\EmployeeBankData;
use App\Models\Employee_projects;
use App\Models\Employee;
use App\Models\Role;
use App\Models\TransferEmployeeProject;
use Illuminate\Support\Facades\Auth;

class HrHelper{


                                        /*company_id*/ /*table name*/ /*column name*/ /*column_id*/

    public static function takenLeavesLeaveTypeWise(){
        return 'abc';
    }

    public static function hideConfidentiality($param1){

        $hide_confidentiality='yes';
        CommonHelper::companyDatabaseConnection($param1);
        $role_id=Employee::where('emp_id',Auth::user()->emp_id)->value('role_id');
        CommonHelper::reconnectMasterDatabase();
        if($role_id != 0){
            $hide_confidentiality=Role::where('id',$role_id)->value('hide_confidentiality');
        }
        if(Auth::user()->acc_type == 'client'){
            $hide_confidentiality='no';
        }
        return $hide_confidentiality;
    }

    public static function totalLateForThisRange($param1,$param2,$param3,$param4){
        $totalLateForThisRange = 0;
        CommonHelper::companyDatabaseConnection($param1);
        $fromDateOne = date_create($param2);
        $toDateOne = date_create($param3);
        $fromDate = date_format($fromDateOne,'n/j/yyyy');
        $toDate = date_format($toDateOne,'n/j/yyyy');
        $countTotalLateForThisRange = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$param4)->where('late','!=','')->get();
        CommonHelper::reconnectMasterDatabase();
        $totalLateForThisRange = count($countTotalLateForThisRange);

        return $totalLateForThisRange;
    }

    public static function totalAbsentForThisRange($param1,$param2,$param3,$param4){
        $totalAbsentForThisRange = 0;
        CommonHelper::companyDatabaseConnection($param1);
        $fromDateOne = date_create($param2);
        $toDateOne = date_create($param3);
        $fromDate = date_format($fromDateOne,'n/j/yyyy');
        $toDate = date_format($toDateOne,'n/j/yyyy');
        $countTotalAbsentForThisRange = Attendance::whereBetween('ddate',[$fromDate,$toDate])->where('acc_no','=',$param4)->where('absent','!=','')->get();
        CommonHelper::reconnectMasterDatabase();
        $totalAbsentForThisRange = count($countTotalAbsentForThisRange);

        return $totalAbsentForThisRange;
    }

    public static function totalLateForThisAccountingYear(){
        return '0';
    }

    public static function totalAbsentForThisAccountingYear(){
        return '0';
    }

    /*company_id*/ /*table name*/ /*column name*/ /*column_id*/
    public static function getMasterTableValueById($param1,$param2,$param3,$param4){
       

        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and `company_id` = '.$param1.' and id = '.$param4.'' );

        if($detailName):
            return $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and `company_id` = '.$param1.' and id = '.$param4.'' )->$param3;
        else:
            return ;
        endif;
    }


    public static function getMasterTableValueByIdAndColumn($param1,$param2,$param3,$param4,$param5){
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and `company_id` = '.$param1.' and id = '.$param4.'' );

        if($detailName):
            return $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and `company_id` = '.$param1.' and '.$param5.' = '.$param4.'' )->$param3;
        else:
            return ;
        endif;
    }
    public static function getCompanyTableValueByIdAndColumn($param1,$param2,$param3,$param4,$param5){

        $d = DB::selectOne('select `dbName` from `company` where `id` = '.$param1.'')->dbName;
        Config::set(['database.connections.tenant.database' => $d]);
        Config::set('database.default', 'tenant');
        DB::reconnect('tenant');

        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where  '.$param5.' = '.$param4.'' );

        if($detailName):
            $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where '.$param5.' = '.$param4.'' )->$param3;
        else:
            $detailName = '';
        endif;
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
        return $detailName;

    }


    public static function getCompanyTableValueById($param1,$param2,$param3,$param4){

        $d = DB::selectOne('select `dbName` from `company` where `id` = '.$param1.'')->dbName;
        Config::set(['database.connections.tenant.database' => $d]);
        Config::set('database.default', 'tenant');
        DB::reconnect('tenant');

        $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and id = '.$param4.'' );

        if($detailName):
            $detailName = DB::selectOne('select  '.$param3.' from ' .$param2.' where `status` = 1 and id = '.$param4.'' )->$param3;
        else:
            $detailName = '<span style="color:red">Deleted</span>';
        endif;
        Config::set('database.default', 'mysql');
        DB::reconnect('mysql');
        return $detailName;

    }

    public static function getStatusLabel($param)
    {
        $array[1] ="<span class='badge badge-pill badge-success'>&nbsp;Active&nbsp;</span>";
        $array[2] ="<span class='badge badge-pill badge-danger'>Deleted</span>";
        $array[3] ="<span class='badge badge-pill badge-danger'>Exit</span>";
        $array[4] ="<span class='badge badge-pill badge-danger'>InActive</span>";
        $array[5] ="<span class='badge badge-pill badge-danger'>Expired</span>";
        $array[6] ="<span class='badge badge-pill badge-warning' style='color:white;'>Rehired</span>";

        echo $array[$param];
    }

    public static function getApprovalStatusLabel($param)
    {
        $array[1] ='<span style="color: white;" class="badge badge-pill badge-warning">Pending</span>';
        $array[2] ='<span style="color: white;" class="badge badge-pill badge-success">Approved</span>';
        $array[3] ='<span style="color: white;" class="badge badge-pill badge-danger">Rejected</span>';
        echo $array[$param];
    }

    public static function date_format($str)
    {
         return date("d-M-Y", strtotime($str));
    }

    public static function date_format2($str)
    {
        return date("d/M/Y", strtotime($str));
    }

    public static function hr_date_format($str)
    {
        $myDateTime = date_create_from_format('Y-m-d',$str);
        $new_date = $myDateTime->format('F d, Y');
        return $new_date;
    }

    public static function getIdCardStatus($param)
    {
        $array[1] ='<span class="label label-warning">Pending</span>';
        $array[2] ='<span class="label label-info">Printed</span>';
        $array[3] ='<span class="label label-success">Delivered</span>';
        echo $array[$param];
    }

    public static function getEmployeeBankData($param1,$param2,$param3)
    {
        CommonHelper::companyDatabaseConnection($param1);
           $EmployeeBankData =  EmployeeBankData::where([['status','=',$param2],['emr_no','=',$param3]])->value('account_no');
        CommonHelper::reconnectMasterDatabase();
            return $EmployeeBankData;
    }

    public static function getAllEmployeeId($param1,$param2,$param3){
        CommonHelper::companyDatabaseConnection($param1);
           $employeeEmrnoArray = [];
            if($param2 != '' && $param3 != ''){
               $employee_emrno = Employee::select('emp_id')->where([['emp_department_id','=',$param2],['emp_sub_department_id','=',$param3],['status','!=',2]])
                   ->get();
            }
            else if($param2 != '' && $param3 == ''){
              $employee_emrno = Employee::select('emp_id')->where([['emp_department_id','=',$param2],['status','!=',2]])
			  ->get();
              }
           else{
              $employee_emrno = Employee::select('emp_id')->where('status','!=',2)
                                  ->get();
           }

         CommonHelper::reconnectMasterDatabase();
         foreach ($employee_emrno as $value){
             $employeeEmrnoArray[] = $value->emp_id;
         }
         return $employeeEmrnoArray;
    }

    public static function getEmployeeData($param1,$param2,$param3,$param4){
        CommonHelper::companyDatabaseConnection($param1);
            $employee_data = Employee::select($param2)->where($param3,'=',$param4)->get();
            foreach ($employee_data as $value){
                 return $value->$param2;
            }
    }

    public static function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if (($number %100) >= 11 && ($number%100) <= 13)
            return $abbreviation = $number. 'th';
        else
            return $abbreviation = $number. $ends[$number % 10];
    }

   public static function getActiveProjectId($param1,$param2){
     $projectdataArray = [];
       CommonHelper::companyDatabaseConnection($param1);
         $TransferEmployeeProject = TransferEmployeeProject::where([['emr_no','=',$param2],['status','=',1],['active','=',1]]);
         $employee = Employee::where([['emr_no','=',$param2],['status','=',1],['active','=',1]]);
         CommonHelper::reconnectMasterDatabase();

         if($TransferEmployeeProject->count() > 0){
              $projectData = $TransferEmployeeProject->get();
          }
         else{
              if($employee->count() > 0){
               $projectData = $employee->get();
              }
         }

         foreach($projectData as $value){
              $projectdataArray[] = $value->employee_project_id;

          }
          return $projectdataArray;
   }

   public static function getProjectName($param1,$param2,$param3){

        $projectName = DB::table($param1)->select($param2)->whereIn('id',$param3)->get();

        foreach($projectName as $value){
            echo $value->$param2;
            return;
        }

   }
   
   public static function checkTRColor($param1,$param2,$param3){
        //return $param1.' - '.print_r($param2).' - '.print_r($param3);
        if(in_array($param1,$param2)){
            return '#FFC0CB';
        }else if(in_array($param1,$param3)) {
            return '#adde80';
        }else{
            return 'white';
        }
    }
  public static function get_menu_title($param1){
	  print_r($param1);
	  return;
  }
  
  public static function getAllEmpId($param1,$param2,$param3){
	  CommonHelper::companyDatabaseConnection($param3);

	 $all_emp_ids = '';
	 if($param1 != 0 && $param2 != 0){
		 $all_emp_ids = DB::table('employee')->select('emp_id')->where([['emp_department_id',$param1],['emp_sub_department_id',$param2]]);
	 }
	 else if($param1 != 0 && $param2 == 0){
		 $all_emp_ids = DB::table('employee')->select('emp_id')->where([['emp_department_id',$param1]]);
	 }



	 if($all_emp_ids == ''){
		 return 'null';
	 }else{
	 CommonHelper::reconnectMasterDatabase();
	 $emp_ids = [];
	  foreach($all_emp_ids->get() as $value){
		  $emp_ids[] = $value->emp_id;
	  }
	  return $emp_ids;
	 }
  }
  public static function getAllEmpIds($param1,$param2,$param3){
    CommonHelper::companyDatabaseConnection($param3);

   $all_emp_ids = '';
   if($param1 != 0 && $param2 != 0){
       $all_emp_ids = DB::table('employee')->select('emp_id')->where([['emp_department_id',$param1],['emp_sub_department_id',$param2]]);
   }
   else if($param1 != 0 && $param2 == 0){
       $all_emp_ids = DB::table('employee')->select('emp_id')->where([['emp_department_id',$param1]]);
   }



   if($all_emp_ids == ''){
       return 'null';
   }else{
   CommonHelper::reconnectMasterDatabase();
   $emp_ids = [];
    foreach($all_emp_ids->get() as $value){
        $emp_ids[] = $value->emp_id;
    }
    return $emp_ids;
   }
}
      
  public static function getIncomeTax($payable_wihtoutdays_taxable,$emp_join_date,$emp_month,$payslip_month,$pay_month,$emp_year,$pay_year,$tax_slabs){
     
      
      // http://innovative-apis.smrhr.com/calculateTax.php
      //$tax_slabs = DB::table('tax_slabs')->where([['status','=','1'],['tax_id', '=',1 ]])->get();
      $payable_salary_taxable=0;
      $month=0;
      

      $to = Carbon::createFromFormat('Y-m-d', $emp_join_date);
      $from = Carbon::createFromFormat('Y-m', $payslip_month);
      $diff_in_months = $to->diffInMonths($from);

      if($diff_in_months >= 12){
          $payable_salary_taxable = ($payable_wihtoutdays_taxable*12)/1.1;
          $month=12;
      }
      else{
          if(($pay_month >= 7 && $emp_month <= 6) || ($pay_month >= 7 && $emp_month > $pay_month) ){
                $payable_salary_taxable = ($payable_wihtoutdays_taxable*12)/1.1;
                $month=12;
          }
          elseif($emp_month <=6 && $pay_year > $emp_year){
                $payable_salary_taxable = ($payable_wihtoutdays_taxable*12)/1.1;
                $month=12;
          }
          else{
            switch ($emp_month) {
                  case 1:
                      $month=6;
                      break;
                  case 2:
                      $month=5;
                      break;
                  case 3:
                      $month=4;
                      break;
                  case 4:
                      $month=3;
                      break;
                  case 5:
                      $month=2;
                      break;
                  case 6:
                      $month=1;
                      break;
                  case 7:
                      $month=12;
                      break;
                  case 8:
                      $month=11;
                      break;
                  case 9:
                      $month=10;
                      break;
                  case 10:
                      $month=9;
                      break;
                  case 11:
                      $month=8;
                      break;
                  case 12:
                      $month=7;
                      break;
                  default:
                      $month=12;
                      break;
            }
          $payable_salary_taxable = ($payable_wihtoutdays_taxable*$month)/1.1;
          }
      }

      
      $divided_tax = 0;
      $tax=0;
      foreach($tax_slabs as $value):
          if($payable_salary_taxable > $value['salary_range_from'] && $payable_salary_taxable <= $value['salary_range_to']):

              $payable_salary_taxable = ($payable_wihtoutdays_taxable*12)/1.1;
              $tax_percent = $value['tax_percent'];
              $tax_amount = $value['tax_amount'];

              $income_tax = round((($payable_salary_taxable - $value['salary_range_from']) / 100) * $tax_percent) + $tax_amount;

              $divided_tax = round($income_tax/12);
              $tax= ($divided_tax/12)*$month;
          endif;
      endforeach;

      return $tax;
  }

    public static function getAuthorizedInputFields()
    {
        if(Auth::user()->acc_type == 'client'):
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $employee = Employee::select('emp_id','emp_name')->get();
            CommonHelper::reconnectMasterDatabase();
            ?>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <p class="emp_search">Search Employee : <span class="rflabelsteric">&nbsp;</span></p>
                    <select style="width:100%;" class="form-control" id="emp_id2" onchange="filterUserDashBoard(this.value)" >
                        <?php foreach($employee as $value): ?>
                            <option <?php if(Session::has('emp_id') && $value->emp_id == session('emp_id')){ echo "selected"; }  ?> value="<?php echo $value->emp_id ?>"><?php echo 'EMP-ID: ' . $value->emp_id . '---' . $value->emp_name; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>


        <?php elseif(Auth::user()->acc_type == 'user'):
            CommonHelper::companyDatabaseConnection(Input::get('m'));
            $team_members = Employee::select('emp_id','emp_name')
                ->where('reporting_manager',Auth::user()->emp_id)
                ->where('status',1);
            CommonHelper::reconnectMasterDatabase();
            if( $team_members->count() > 0): ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="emp_search">Team Members :</label>
                                <span class="rflabelsteric">&nbsp;</span>
                                <select style="width: 100%;" class="form-control" id="emp_id2" onchange="filterUserDashBoard(this.value)" >
                                    <option value="<?=Auth::user()->emp_id?>"><?=Auth::user()->name?></option>
                                    <?php foreach($team_members->get() as $value): ?>
                                        <option <?php if(Session::has('emp_id') && $value->emp_id == session('emp_id')){ echo "selected"; }  ?> value="<?php echo $value->emp_id ?>"><?php echo 'EMP-ID: ' . $value->emp_id . '---' . $value->emp_name; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>


                    </div>
                </div>


            <?php else: ?>
                <script>
                    $('#dashboardUser').hide();
                </script>
                <div style="display: none;">
                    <select style="display: none;" class="form-control" id="emp_id2" onchange="filterUserDashBoard(this.value)" >
                        <option value="<?=Auth::user()->emp_id?>"><?=Auth::user()->name?></option>
                    </select>
                </div>


            <?php endif;

        endif;
    }

}
?>