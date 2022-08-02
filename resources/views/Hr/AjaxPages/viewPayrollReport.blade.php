<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Holidays;
use App\Models\PayrollData;
use App\Models\Payslip;
use App\Models\EmployeeCategory;
use App\Models\Regions;
use App\Models\EmployeePromotion;
use App\Models\EmployeeExit;
$account_no="";
$count_advance_salary = 0;
$count_m_p_fund = 0;
$count_total_vechile = 0;
$count_total_medical = 0;
$count_gross_salary = 0;
$count_bonus = 0;
$count_hr_utility = 0;

$count_total_salary = 0;
$count_ot_amount = 0;
$count_deduction_day_amount = 0;
$count_deposit = 0;
$count_loan = 0;
$count_tax = 0;
$count_eobi = 0;
$count_pf_amount = 0;
$count_allowance = 0;
$count_grossWithAllowance=0;
$count_extra_allowances = 0;
$count_deduction = 0;
$count_net_salary = 0;
$current_date = date('Y-m-d');
$count_ot_hours_amount = 0;
$count_other_deduction = 0;
$count_taxable_salary = 0;


$grand_taxable_salary = 0;
$grand_gross_salaries = 0;
$grand_advance_salary = 0;
$grand_m_p_fund= 0;
$grand_m_i_tax = 0;
$grand_vehicle_addit = 0;
$grand_vr_other_allow = 0;
$grand_medical = 0;
$grand_hr_utlilty = 0;
$grand_loan_amount_paid  = 0;
$grand_total_salary = 0;
$grand_ot_amount = 0;
$grand_deduction_day_amount = 0;
$grand_other = 0;
$grand_lwp = 0;
$grand_penalty = 0;
$grand_loan = 0;
$grand_bonus = 0;
$grand_tax = 0;
$grand_eobi = 0;
$grand_pf_amount = 0;
$grand_allowance = 0;
$grand_other_amount = 0;
$grand_extra_allowances = 0;
$grand_deduction = 0;
$grand_net_salary = 0;
$current_date = date('Y-m-d');
$grand_ot_hours_amount = 0;
$grand_cheque_amount = 0;
$grand_cash_amount = 0;
$grand_other_deduction = 0;
$grand_arrear=0;
$grand_other_allow=0;
$grandGrossWithAllowance=0;

$count_m_i_t = 0;


$month_year = Input::get('month_year');

?>
<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
    div.wrapper {
        overflow: auto;
        max-height: 630px;

    }
    /*fix head css*/
    .tableFixHead {
        overflow-y: auto;
        height: 100px;
    }
    .tableFixHead thead td {
        position: sticky; top: 0px;
    }

    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 8px 16px; }
    th     { background:#f9f9f9; }

</style>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                <div class="table-responsive wrapper" >
                    <?php
                    $count =1;
                    ?>
                    <table class="table table-sm mb-0 table-bordered table-striped tableFixHead" id="regionWisePayrollReport">
                        <thead>
                        <tr id="hide-table-row">
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'colspan="14"></td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'colspan="2" class="text-center">Overtime</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'colspan="8" class="text-center">Deductions</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'colspan="4"></td>
                        </tr>
                        <tr>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">S No.</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Emp ID </td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Emp Name </td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Department</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Sub Department</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Gross </td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Basic Salary</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">HR & Utility</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Medical</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">V.R</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Others</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Arrears</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Vehicle Addit.@5%</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Gross With Allowance</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Taxable Salary</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Monthly I Tax</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Monthly.P.Fund</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Adv Salary</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">EOBI </td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Loan</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Eid Bonus</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">l.w/p</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Penalty</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Others</td>

                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Deduction</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Net Salary </td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Account No</td>
                            <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Payment Mode</td>
                        </tr>
                        </thead>

                        <tbody>
                        
                        @foreach($employees as $value)
                            <?php
                            CommonHelper::companyDatabaseConnection($_GET['m']);
                            $designation_id = $value['designation_id'];
                            $employeeCurrentPositions = EmployeePromotion::select('designation_id')->where([['emp_id','=',$value['emp_id']],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');
                            $payslip = Payslip::where([['emp_id','=',$value["emp_id"]],["month","=",$explodeMonthYear[1]],["year","=",$explodeMonthYear[0]]]);
                            $payroll = PayrollData::where([['emp_id','=',$value["emp_id"]],["month","=",$explodeMonthYear[1]],["year","=",$explodeMonthYear[0]]]);

                            if($employeeCurrentPositions->count() > 0):
                                $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
                                $designation_id = $employeeCurrentPositionsDetail->designation_id;
                            endif;
                            //  $empCategory = HrHelper::getMasterTableValueById($companyData->id,'employee_category','employee_category_name',$value["employee_category_id"]);
                            $emp_designation =  HrHelper::getMasterTableValueById($_GET['m'],'designation','designation_name', $designation_id);
                            $department_name = HrHelper::getMasterTableValueById($_GET['m'],'department','department_name',$value['emp_department_id']);
                            $sub_department_name = HrHelper::getMasterTableValueById($_GET['m'],'sub_department','sub_department_name',$value['emp_sub_department_id']);


                            $emp_name=$value["emp_name"];

                            if($payslip->count() > 0){
                            
                            if($payslip):
                            $payroll_data =$payroll->first();
                            $payslip =$payslip->first();
                            CommonHelper::companyDatabaseConnection($_GET['m']);
                            // $emp_exit = EmployeeExit::select('last_working_date')->where('emp_id', $payslip['emp_id'])->where('status', '1');
                            // $payslip_date=$payslip['year']."-".$payslip['month']."-01"; 
                            // if($emp_exit->count()>0){
                            //   if($payslip_date > $emp_exit->value('last_working_date')){
                            //       continue;  
                            //   }   
                            // }
                            $account_no =Employee::select('emp_id','bank_account')->where('emp_id', $payslip['emp_id'])->where('status', '1')->first();
                            CommonHelper::reconnectMasterDatabase();
                            $count_total_vechile+=$payslip['vehicle_addit_'];
                            $count_total_salary+=$payslip['total_salary'];
                            $count_total_medical+=$payslip['fix_medical'];
                            $count_gross_salary+=$payslip['gross_salaries'];
                            $count_grossWithAllowance=($payslip['gross_salaries']+$payslip['vr_other_allow']+$payslip['other_allowance']+$payslip['Arrears']+$payslip['vehicle_addit_']);
                            $count_taxable_salary+=$payslip['taxable_salary'];

                            $count_deduction_day_amount+=$payslip['deduction_days_amount'];
                            $count_other_deduction+=$payslip['other_deduction'];
                            $count_loan+=$payslip['loan_amount_paid'];
                            $count_tax+=$payslip['tax_amount'];
                            $count_eobi+=$payslip['eobi_amount'];
                            $count_pf_amount+=$payslip['pf_amount'];
                            $count_allowance+=$payslip['total_allowance'];
                            $count_extra_allowances +=$payslip['extra_allowance'];
                            $count_deduction+=$payslip['total_deduction'];
                            $count_net_salary+=$payslip['net_salary'];
                            $count_hr_utility +=$payslip['net_salary'];
                            $count_m_i_t +=$payslip['tax_amount'];
                            $count_m_p_fund +=$payslip['pf_amount'];
                            $count_advance_salary +=$payslip['advance_salary_amount'];


                            $grand_total_salary+=$payslip['total_salary'];
                            $grand_ot_amount+=$payslip['>ot_amount'];
                            $grand_deduction_day_amount+=$payslip['deduction_days_amount'];
                            $grand_other+=$payslip['other_deduct'];
                            $grand_lwp+=($payslip['lwp_deduction']+$payslip['deduction_days_amount']);
                            $grand_penalty+=$payslip['penalty'];
                            $grand_other_deduction+=$payslip['other_deduction'];
                            $grand_loan+=$payslip['loan_amount_paid'];
                            $grand_bonus+=$payslip['bonus_amount'];
                            $grand_tax+=$payslip['tax_amount'];
                            $grand_eobi+=$payslip['eobi_amount'];
                            $grand_pf_amount+=$payslip['pf_amount'];
                            $grand_allowance+=$payslip['total_allowance'];
                            $grand_other_amount+=$payslip['other_amount'];
                            $grand_extra_allowances+=$payslip['extra_allowance'];
                            $grand_deduction+=$payslip['total_deduction'];
                            $grand_net_salary+=$payslip['net_salary'];
                            $grand_ot_hours_amount+=$payslip['total_ot_hours_amount'];
                            $grand_hr_utlilty += $payslip['hr_utility_allowance'];
                            $grand_medical += $payslip['fix_medical'];
                            $grand_vr_other_allow += $payslip['vr_other_allow'];
                            $grand_other_allow+=$payslip['other_allowance'];
                            $grand_arrear+=$payslip['Arrears'];
                            $grandGrossWithAllowance+=$count_grossWithAllowance;
                            $grand_gross_salaries +=$payslip['gross_salaries'];
                            $grand_vehicle_addit +=$payslip['vehicle_addit_'];
                            $grand_m_i_tax += $payslip['tax_amount'];
                            $grand_m_p_fund += $payslip['pf_amount'];
                            $grand_advance_salary +=$payslip['advance_salary_amount'];
                            $count_bonus += $payslip['bonus_amount'];
                            $grand_loan_amount_paid += $payslip['loan_amount_paid'];
                            $grand_taxable_salary+=$payslip['taxable_salary'];
                            if($payslip['payment_mode'] == 'Transfer'):
                                $grand_cash_amount+=$payslip['net_salary'];
                            else:
                                $grand_cheque_amount+=$payslip['net_salary'];
                            endif;
                            ?>
                            <tr class="text-center">
                                <td>{{ $count++ }}</td>
                                <td>

                                    <?php
                                        if($payslip["emp_id"] == ''){
                                            echo '-';
                                        }else{
                                            echo $payslip["emp_id"];
                                        }

                                    ?>
                                </td>
                                <td>{{ $value["emp_name"] }}</td>
                                <td>{{ $department_name }}</td>
                                <td>{{ $sub_department_name }}</td>
                                <td>{{ $payslip["gross_salaries"] }}</td>
                                <td>{{ number_format($payslip["total_salary"],0) }}</td>
                                <td>{{ number_format($payslip["hr_utility_allowance"],0) }}</td>
                                <td>{{ number_format($payslip["fix_medical"],0) }}</td>
                                <td>{{ number_format($payslip["vr_other_allow"],0) }}</td>
                                <td>{{ number_format($payslip["other_allowance"],0) }}</td>
                                <td>{{ number_format($payslip["Arrears"],0)  }}</td>
                                <td>{{ number_format($payslip["vehicle_addit_"],0) }}</td>
                                <td>{{ number_format($count_grossWithAllowance,0) }}</td>
                                <td>{{ number_format($payslip["taxable_salary"],0) }}</td>
                                <td>{{ number_format($payslip["tax_amount"],0) }}</td>


                                <td>{{ number_format($payslip["pf_amount"],0) }}</td>
                                <td>{{ number_format($payslip["advance_salary_amount"],0) }}</td>
                                <td>{{ number_format($payslip["eobi_amount"],0) }}</td>
                                <td>{{ number_format($payslip["loan_amount_paid"],0)}}</td>
                                <td>{{ number_format($payslip["bonus_amount"],0) }}</td>
                                <td>{{ number_format(($payslip["lwp_deduction"]+$payslip['deduction_days_amount']),0) }}</td>
                                <td>{{ number_format($payslip["penalty"],0) }}</td>
                                <td>{{ number_format($payslip["other_deduct"],0) }}</td>
                                <td>{{ number_format($payslip["total_deduction"],0) }}</td>
                                <td>{{ number_format($payslip["net_salary"],0) }}</td>
                                <td>{{ $account_no['bank_account'] ?? '--' }}</td>
                                <td>{{ $payslip["payment_mode"] }}</td>
                            </tr>
                            <?php else:
                                $recordNotFound[] = "<tr class='text-center'><td colspan='27'><b style='color:red;'> $emp_name Payroll Not Found !</b></td></tr>";
                            endif;
                            }
                            ?>

                            <?php CommonHelper::reconnectMasterDatabase(); ?>
                            
                        </tbody>

                        @endforeach
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-bordered table-striped">
                        <thead>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center" colspan="21"><b style="font-size:30px;text-decoration: underline">Grand Total</b></td>
                        </thead>
                        <thead>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Gross Salary </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Basic Salary</td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Hr Utility</td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Medical</td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total VR</td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Arrear</td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Other</td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Vechile Addition @5 %</td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Gross With Allowance</td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Taxable Salary </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Monthly Income Tax </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Monthly.P.Fund </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Adv Salary </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total EOBI </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Loan </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Bonus </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Other </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total LWP</td>

                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Penalty </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Total Deduction </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">Net Salary</td>
                        </thead>
                        <tfoot>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-right" colspan="4">Total Transfer Amount &nbsp;&nbsp;</td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-right" colspan="2">&nbsp;{{ number_format($grand_cash_amount,0) }}</td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-right" colspan="3">Total Cheque Amount</td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-right" colspan="2">&nbsp;{{ number_format($grand_cheque_amount,0) }}</td>
                        <td></td>
                        </tfoot>
                        <tfoot>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_gross_salaries,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_total_salary,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_hr_utlilty,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_medical,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_vr_other_allow,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_arrear,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_other_allow,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_vehicle_addit,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grandGrossWithAllowance,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            {{ number_format($grand_taxable_salary,0) }}
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_m_i_tax,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_m_p_fund,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_advance_salary,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_eobi,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_loan_amount_paid,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_bonus,0) }}</b>
                        </td>

                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_other,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_lwp,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_penalty,0) }}</b>
                        </td>

                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_deduction,0) }}</b>
                        </td>
                        <td style='color:black;font-weight:bold;background:#f9f9f9;'class="text-center">
                            <b>{{ number_format($grand_net_salary,0) }}</b>
                        </td>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

