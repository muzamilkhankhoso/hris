<style>
    .panel-heading {
        padding: 0px 15px;}
    .field_width {width: 120px;}

    /*fix head css*/
    .tableFixHead {
        overflow-y: auto;
        height: 100px;
    }
    .tableFixHead thead th {
        position: sticky; top: 0px;
    }

    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 8px 16px; }
    th     { background:#f9f9f9; }

    div.wrapper {
        overflow: auto;
        max-height: 630px;

    }


</style>
<?php

use App\Helpers\CommonHelper;
use Carbon\Carbon;
use App\Helpers\HrHelper;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Payroll;
use App\Models\Tax;
use App\Models\Eobi;
use App\Models\BonusIssue;
use App\Models\Bonus;
use App\Models\PayrollData;
use App\Models\LoanRequest;
use App\Models\AdvanceSalary;
use App\Models\EmployeePromotion;
use App\Models\EmployeeBankData;
use App\Models\Arrears;
use App\Models\EmployeeTransfer;

$employeeArray = [];
$recordNotFound = [];

$result=[];

?>
<div class="panel">
    <div class="panel-body">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive wrapper">
                    <table class="table table-sm mb-0 table-bordered table-striped tableFixHead" id="TaxesList">
                        <thead>
                        <tr>
                            <th colspan="7"></th>
                            <th colspan="5" class="text-center">Allowance</th>
                            <th colspan="11" class="text-center">Deductions</th>
                            <th colspan="3" class="text-center"></th>
                        </tr>
                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">EMP ID</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Gross</th>
                            <th class="text-center">Basic Salary</th>
                            <th class="text-center">H.R & Utility</th>
                            <th class="text-center">Medical</th>
                            <th class="text-center">V.R Allow</th>
                            <th class="text-center">Other Allow</th>
                            <th class="text-center">Arrears</th>
                            <th class="text-center">Vehicle Addit.@5%</th>
                            <th class="text-center">Gross with Allowance</th>
                            <th class="text-center">Taxable Salary</th>
                            <th class="text-center">Monthly I.Tax</th>
                            <th class="text-center">Monthly P.Fund</th>
                            <th class="text-center">Adv Salary</th>
                            <th class="text-center">EOBI</th>
                            <th class="text-center">Loan</th>
                            <th class="text-center">Eid Bonus</th>
                            <th class="text-center">L.W.P</th>
                            <th class="text-center">Penalty</th>
                            <th class="text-center">Others</th>
                            <th class="text-center">Total Deduction</th>
                            <th class="text-center">Net Salary</th>
                            <th class="text-center">Account No.</th>
                            <th class="text-center">Payment Mode</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 0;?>
                        <?php foreach($employees as $row1){?>
                        <?php
                        $counter++;
                        CommonHelper::companyDatabaseConnection(Input::get('m'));
                        $designation_id = $row1->designation_id;
                        $employeeCurrentPositions = EmployeePromotion::where([['emp_id','=',$row1->emp_id],['status','=',1],['approval_status', '=', 2]])->orderBy('id','desc');

                        if($employeeCurrentPositions->count() > 0):
                            $employeeCurrentPositionsDetail = $employeeCurrentPositions->first();
                            $designation_id = $employeeCurrentPositionsDetail->designation_id;
                        endif;


                        $emp_name = $row1->emp_name;
                        $father_name = $row1->emp_father_name;
                        CommonHelper::companyDatabaseConnection(Input::get('m'));
                        $payroll_deduction = PayrollData::where([['emp_id', '=', $row1->emp_id],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]]);




                        $deduction_days = $payroll_deduction->value('deduction_days');
                        $employeeArray[] = $row1->id;


                        $bonus = BonusIssue::where([['status', '=', 1],['emp_id', '=', $row1->emp_id],['bonus_year', '=', $explodeMonthYear[0]],['bonus_month', '=', $explodeMonthYear[1]]]);
                        $loanRequest = LoanRequest::where([['status','=','1'],['approval_status','=',2],['loan_status','=',0],['emp_id', '=', $row1->emp_id]]);
                        $advanceSalary = AdvanceSalary::where([['status','=','1'],['approval_status','=',2],['emp_id', '=', $row1->emp_id],['deduction_year', '=', $explodeMonthYear[0]],['deduction_month', '=', $explodeMonthYear[1]]]);


                        $loanRequest = LoanRequest::select('id','per_month_deduction')->where([['approval_status','=',2],['status', '=', 1],['loan_status','=',0],['emp_id', '=', $row1->emp_id]]);

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
//                        if($bonus->count() > 0):
//                            $bonus_issue = $bonus->first();
//                            $bonus_name = Bonus::select('bonus_name')->where([['id', '=', $bonus_issue->bonus_id]])->value('bonus_name');
//                            $bonus_amount = $bonus_issue->bonus_amount;
//                        endif;

                        $emp_month=0;
                        $pay_month=0;

                        $LWPD=0;
                        $LWP=0;
                        $LWP_once=0;
                        $penalty=0;
                        $penalty_once=0;
                        $other_once=0;
                        $allowance = Allowance::where([['emp_id', '=', $row1->emp_id],['status', '=', 1]]);
                        $LWP=Deduction::where([['emp_id', '=', $row1->emp_id],['status', '=', 1],['deduction_type','=','LWP']]);
                        $LWPD=Deduction::where([['emp_id', '=', $row1->emp_id],['status', '=', 1],['deduction_type','=','LWP'],['once','!=','1']])->sum('deduction_amount');
                        $LWP_once=Deduction::where([['emp_id', '=', $row1->emp_id],['status', '=', 1],['deduction_type','=','LWP'],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->value('deduction_amount');
                        $LWPD+=$LWP_once;
                        $LWPD+=$deduction_days;
                        $penalty_once=Deduction::where([['emp_id', '=', $row1->emp_id],['status', '=', 1],['deduction_type', '=','Penalty'],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->value('deduction_amount');
                        $other_once=Deduction::where([['emp_id', '=', $row1->emp_id],['status', '=', 1],['deduction_type', '=','Other'],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->value('deduction_amount');

                        $penalty=Deduction::where([['emp_id', '=', $row1->emp_id],['status', '=', 1],['deduction_type','=','Penalty'],['once','!=','1']])->sum('deduction_amount');
                        $penalty+=$penalty_once;
                        $Others=Deduction::where([['emp_id', '=', $row1->emp_id],['status', '=', 1],['deduction_type','=','Other'],['once','!=','1']])->sum('deduction_amount');;
                        $Others+=$other_once;
                        $deduction = Deduction::where([['emp_id', '=', $row1->emp_id],['status', '=', 1],['deduction_type','!=','LWP'],['once','!=','1']])->sum('deduction_amount');
                        $deduction+=($penalty_once+$other_once);
                        $promoted_salary = EmployeePromotion::select('salary','emp_id')->where([['emp_id','=',$row1->emp_id],['status','=',1]])->orderBy('id', 'desc');
                        if($promoted_salary->count() > 0):
                            $emp_salary = $promoted_salary->value('salary');
                        else:
                            $emp_salary = $row1->emp_salary;
                        endif;
                        //$bankAccDetail =EmployeeBankData::where([['status','=',1],['emp_id','=',$row1['emp_id']]]);

                        //if($bankAccDetail->count() > 0):
                        //    $bankAccNo =  $bankAccDetail->value('account_no');
                        //else:
                        //    $bankAccNo = '-';
                        //endif;
                        $bankAccNo = $row1->bank_account;

                        CommonHelper::reconnectMasterDatabase();

                        $eobi_deduct=0;        
                        if($row1->eobi_id != '0'):
                            $eobi = Eobi::where([['id','=',$row1->eobi_id],['company_id','=',Input::get('m')],['status','=','1']]);
                            if($eobi->count() > 0){
                                $eobi_deduct = $eobi->value('EOBI_amount');
                            }
                            else{
                                $eobideduct = 0;
                            }
                        else:
                            $eobi_deduct = 0;
                        endif;

                        /*Provident Fund start*/
                        $provident_fund_data = DB::table('provident_fund')->select('id','pf_mode','amount_percent')->where([['id','=',$row1->provident_fund_id]]);

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
                        $vr_other_allow = Allowance::where([['emp_id', '=', $row1->emp_id],['status', '=', 1],['allowance_type_id', '=', 1]])->value('allowance_amount');
                        $other_allow = Allowance::where([['emp_id', '=', $row1->emp_id],['status', '=', 1],['allowance_type_id', '=', 6],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->sum('allowance_amount');
                        $arear = Allowance::where([['emp_id', '=', $row1->emp_id],['status', '=', 1],['allowance_type_id', '=', 5],['month','=',$explodeMonthYear[1]],['year','=',$explodeMonthYear[0]]])->sum('allowance_amount');

                        $vehicle_addit = Allowance::where([['emp_id', '=', $row1->emp_id],['status', '=', 1],['allowance_type_id', '=', 4]])->value('allowance_amount');

                        //$deduction_days = PayrollData::where([['emp_id', '=', $row1['emp_id']],['year', '=', $explodeMonthYear[0]],['month', '=', $explodeMonthYear[1]]]);
                        $perDaySalary = ($emp_salary)/(30);

                        $rate_of_pay = ($perDaySalary*30);

                        $payroll_deduct_amount = ($LWPD*$perDaySalary);
                        $payroll_ot_amount = 0;
                        // $payroll_ot_amount= round(($deduction_days->total_ot_hours)*(($emp_salary/30)));
                        $count_allowance=($arear+$other_allow+$vr_other_allow+$vehicle_addit);
                        $grossSalaryWithAllownace=($grossSalary+$count_allowance);
                        $total_deduction        = ($deduction+$loan_perMonthDeduction+$payroll_deduct_amount+$count_deduction+$pf_amount+$eobi_deduct+$advanceSalaryAmount);


                        $total_deduction2        = ($deduction+$payroll_deduct_amount+$count_deduction+$pf_amount+$eobi_deduct+$advanceSalaryAmount);
                        $payable_salary         = ($rate_of_pay + $bonus_amount + $count_allowance + $payroll_ot_amount);
                        $payable_wihtoutdays   = ($emp_salary + $bonus_amount + $count_allowance + $payroll_ot_amount);
                        $payable_wihtoutdays_taxable  = ($emp_salary-$payroll_deduct_amount)+( $bonus_amount + $payroll_ot_amount);
                        CommonHelper::reconnectMasterDatabase();


                        $emp_month = Carbon::createFromFormat('Y-m-d', $row1->emp_joining_date)->month;
                        $emp_year = Carbon::createFromFormat('Y-m-d', $row1->emp_joining_date)->year;
                        $pay_month= $explodeMonthYear[1];
                        $pay_year= $explodeMonthYear[0];


                        $divided_tax = HrHelper::getIncomeTax($payable_wihtoutdays_taxable,$row1->emp_joining_date,$emp_month,$getPayslipMonth,$pay_month,$emp_year,$pay_year,$tax_slabs);


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
                        ?>
                        <tr>
                            <td class="text-center">{{$counter}}</td>
                            <td>
                                {{ $row1->emp_id }}

                            </td>
                            <td class="text-center">{{ $emp_name }}

                            </td>
                            <td class="text-center">{{ number_format($grossSalary,0) }}

                            </td>

                            <td>
                                {{ number_format($basic_salary,0) }}

                            </td>

                            <td class="text-center">
                                {{ number_format($hr_utility_allowance,0) }}
                                <input name="hr_utility_allowance_<?=$row1->id?>" type="hidden" value="<?=$hr_utility_allowance?>">
                                <input type="hidden" onkeyup="payrollCalculation('<?php echo $row1->id;?>','<?php echo $netSalary ?>','<?php echo $loan_perMonthDeduction ?>')" id="total_allowance_<?php echo $row1->id;?>" name="total_allowance_<?php echo $row1->id;?>" value="<?= round($allowance->sum('allowance_amount')); ?>" class="form-control field_width" />
                                <input type="hidden" id="hidden_allowance_<?php echo $row1->id;?>" name="hidden_allowance_<?php echo $row1->id;?>" value="<?= round($allowance->sum('allowance_amount')); ?>" class="form-control field_width" />
                            </td>

                            <td class="text-center">
                                {{ number_format($fix_medical,0) }}

                            </td>
                            <td class="text-center">
                                {{ number_format($vr_other_allow,0) }}

                            </td>

                            <td class="text-center">
                                {{ number_format($other_allow,0) }}

                            </td>

                            <td class="text-center">
                                {{ number_format($arear,0) }}

                            </td>

                            <td class="text-center">
                                {{ number_format($vehicle_addit,0) }}

                            </td>
                            <td class="text-center">
                                {{ number_format($grossSalaryWithAllownace,0) }}

                            </td>
                            <td class="text-center">
                                <?= ($tax_deduct> 0) ? number_format($payable_wihtoutdays_taxable/1.1,0) : "0"?>


                            </td>
                            <td class="text-center">
                                {{ number_format($tax_deduct,0) }}

                            </td>
                            <td class="text-center">
                                {{ number_format($pf_amount,0) }}
                            </td>

                            <td class="text-center">
                                {{ number_format($advanceSalaryAmount,0) }}

                            </td>
                            <td class="text-center">
                                {{ number_format($eobi_deduct,0) }}

                            </td>
                            <td class="text-center">
                                <input type="hidden" id="loan_id_<?php echo $row1->id;?>" name="loan_id_<?php echo $row1->id;?>" value="<?=$loan_id?>" class="form-control" />

                                <?php if($loanRequest->count() > 0):?>
                                <input onkeyup="payrollCalculation('<?php echo $row1->id;?>','<?php echo $netSalary ?>','<?php echo $loan_perMonthDeduction ?>','<?php echo round($total_deduction2); ?>')" type="number" id="loan_amount_<?php echo $row1->id;?>"
                                       name="loan_amount_<?php echo $row1->id;?>" value="<?=$loan_perMonthDeduction?>" class="form-control field_width" />
                                <?php else: ?>
                                -
                                <input type="hidden" id="loan_amount_<?php echo $row1->id;?>" name="loan_amount_<?php echo $row1->id;?>" value="<?=$loan_perMonthDeduction?>" class="form-control field_width" />
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                {{ number_format($bonus_amount,0) }}

                            </td>
                            <td>{{ number_format($payroll_deduct_amount,0) }}

                            </td>
                            <td>{{ $penalty ?? 0 }}

                            </td>

                            <td>{{ $Others ?? 0 }}

                            </td>
                            <td class="text-center">
                                <span class="total_deduction_<?php echo $row1->id;?>"><?= number_format(round($total_deduction),0)?></span>

                            </td>

                            <input type="hidden" onkeyup="payrollCalculation('<?php echo $row1->id;?>','<?php echo $netSalary ?>','<?php echo $loan_perMonthDeduction ?>')" id="other_amount_<?php echo $row1->id;?>" name="other_amount_<?php echo $row1->id;?>" value="0" class="form-control field_width">




                            <td class="text-center">
                                <span class="net_salary2_<?php echo $row1->id;?>"><?= number_format(round($netSalary),0)?></span>

                            </td>

                            <td class="text-center">{{ $bankAccNo }}

                            </td>
                            <td class="text-center">
                                <select name="payment_mode_<?php echo $row1->id;?>" id="payment_mode_<?php echo $row1->id;?>" class="form-control field_width">
                                    <option @if($bankAccNo != '') selected @endif value="Transfer">Transfer</option>
                                    <option @if($bankAccNo == '') selected @endif value="Cheque">Cheque</option>
                                </select>
                            </td>
                        </tr>

                        <?php

                        CommonHelper::reconnectMasterDatabase();

                        ?>

                        <?php } ?>
                        </tbody>
                    </table>

                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <?php
                                foreach ($recordNotFound as $value):
                                    echo $value;
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>



        </div>
        <div class="row text-right">&nbsp;
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="hidden" value="{{ serialize($tax_slabs) }}" name="slabs" />
                <input type="submit" name="submit" class="btn btn-sm btn-success" />
            </div>
        </div>

    </div>`
</div>







