
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
$count_extra_allowances = 0;
$count_deduction = 0;
$count_net_salary = 0;
$current_date = date('Y-m-d');
$count_ot_hours_amount = 0;
$count_other_deduction = 0;
$count_other_deduct=0;
$count_penalty=0;
$count_taxable_salary=0;

$grand_taxable_salary = 0;
$grand_gross_salaries = 0;
$grand_advance_salary = 0;
$grand_m_p_fund= 0;
$grand_m_i_tax = 0;
$grand_vehicle_addit = 0;
$grand_vr_other_allow = 0;
$grand_others = 0;
$grand_arrears = 0;
$grand_medical = 0;
$grand_hr_utlilty = 0;
$grand_loan_amount_paid  = 0;
$grand_total_salary = 0;
$grand_ot_amount = 0;
$grand_deduction_day_amount = 0;
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
$grand_other_deduct=0;
$grand_penalty=0;
$grand_gross_with_allowance=0;

$count_m_i_t = 0;

$month_year = Input::get('month_year');

?>
<div class="panel">
 
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
             
                <div class="table-responsive" >
                    <?php $count =1;?>
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="regionWisePayrollReport">
                        <thead>
                      
                        <tr>
                            <th class="text-center">S No.</th>
                            <th class="text-center"> ID </th>
                            <th class="text-center" style="width: 50px;">Month</th>
                            <th class="text-center"> Name </th>
                            <th class="text-center">Dept</th>
                            <th class="text-center">Sub-Dpt</th>

                            <th class="text-center">Gross</th>
                            <th class="text-center">Basic</th>
                            <th class="text-center">HR & Utility</th>
                            <th class="text-center">Medical</th>
                            <th class="text-center">VR</th>
                            <th class="text-center">Others</th>
                            <th class="text-center">Arrear</th>
                            <th class="text-center">Vehicle Addit.@5%</th>
                            <th class="text-center">Gross With Allowance</th>
                            <th class="text-center">Taxable Salary</th>
                            <th class="text-center">Monthly I Tax</th>
                            <th class="text-center">Monthly P.F</th>
                            <th class="text-center">Adv Salary</th>
                            <th class="text-center">EOBI </th>
                            <th class="text-center">Loan</th>
                            <th class="text-center"> Bonus</th>
                            <th class="text-center">LWP </th>
                            <th class="text-center">Penalty</th>
                            <th class="text-center">Other</th>
                            <th class="text-center">Total Deduct</th>
                            <th class="text-center">Net Salary </th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($payslip->get() as $value)
                            <?php
                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                            
                          
                            //  $empCategory = HrHelper::getMasterTableValueById($companyData->id,'employee_category','e
                            $department_name = HrHelper::getMasterTableValueById(Input::get('m'),'department','department_name',$value->emp_department_id);
                            $sub_department_name = HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$value->emp_sub_department_id);


                            $emp_name=$value->emp_name;

                            $count_total_vechile+=$value->vehicle_addit_;
                            $count_total_salary+=$value->total_salary;
                            $count_total_medical+=$value->fix_medical;
                            $count_gross_salary+=$value->gross_salaries;
                            $count_taxable_salary+=$value->taxable_salary;

                            $count_deduction_day_amount+=($value->deduction_days_amount+$value->lwp_deduction);
                            $count_other_deduction+=$value->other_deduction;
                            $count_other_deduct+=$value->other_deduct;
                            $count_penalty+=$value->penalty;
                            $count_loan+=$value->loan_amount_paid;
                            $count_tax+=$value->tax_amount;
                            $count_eobi+=$value->eobi_amount;
                            $count_pf_amount+=$value->pf_amount;
                            $count_allowance+=$value->total_allowance;
                            $count_extra_allowances +=$value->extra_allowance;
                            $count_deduction+=$value->total_deduction;
                            $count_net_salary+=$value->net_salary;
                            $count_hr_utility +=$value->net_salary;
                            $count_m_i_t +=$value->tax_amount;
                            $count_m_p_fund +=$value->pf_amount;
                            $count_advance_salary +=$value->advance_salary_amount;


                            $grand_total_salary+=$value->total_salary;
                            $grand_ot_amount+=$value->ot_amount;
                            $grand_deduction_day_amount+=($value->deduction_days_amount+$value->lwp_deduction);
                            $grand_other_deduct+=$value->other_deduct;
                            $grand_penalty+=$value->penalty;
                            $grand_other_deduction+=$value->other_deduction;
                            $grand_taxable_salary+=$value->taxable_salary;
                            $grand_loan+=$value->loan_amount_paid;
                            $grand_bonus+=$value->bonus_amount;
                            $grand_tax+=$value->tax_amount;
                            $grand_eobi+=$value->eobi_amount;
                            $grand_pf_amount+=$value->pf_amount;
                            $grand_allowance+=$value->total_allowance;
                            $grand_other_amount+=$value->other_amount;
                            $grand_extra_allowances+=$value->extra_allowance;
                            $grand_deduction+=$value->total_deduction;
                            $grand_net_salary+=$value->net_salary;
                            $grand_ot_hours_amount+=$value->total_ot_hours_amount;
                            $grand_hr_utlilty += $value->hr_utility_allowance;
                            $grand_medical += $value->fix_medical;
                            $grand_vr_other_allow += $value->vr_other_allow;
                            $grand_others += $value->other_allowance;
                            $grand_arrears += $value->Arrears;
                            $grand_gross_salaries +=$value->gross_salaries;
                            $grand_vehicle_addit +=$value->vehicle_addit_;
                            $grand_m_i_tax += $value->tax_amount;
                            $grand_m_p_fund += $value->pf_amount;
                            $grand_advance_salary +=$value->advance_salary_amount;
                            $count_bonus += $value->bonus_amount;
                            $grand_loan_amount_paid += $value->loan_amount_paid;
                            $grand_gross_with_allowance+=($value->gross_salaries+$value->vr_other_allow+$value->other_allowance+$value->Arrears+$value->vehicle_addit_);

                        
                            ?>
                            <tr class="text-center">
                                <td>{{ $count++ }}</td>
                                <td>{{ $value->emp_id }}</td>
                                <td>{{date("M-Y",strtotime($value->year.'-'.$value->month))}}</td>
                                <td>{{ $value->emp_name}}</td>
                                <td>{{ $department_name }}</td>
                                <td>{{ $sub_department_name }}</td>

                                <td>{{ $value->gross_salaries }}</td>
                                <td>{{ number_format($value->total_salary,0) }}</td>
                                <td>{{ number_format($value->hr_utility_allowance,0) }}</td>
                                <td>{{ number_format($value->fix_medical,0) }}</td>
                                <td>{{ number_format($value->vr_other_allow,0) }}</td>
                                <td>{{ number_format($value->other_allowance,0) }}</td>
                                <td>{{ number_format($value->Arrears,0)  }}</td>
                                <td>{{ number_format($value->vehicle_addit_,0) }} </td>
                                <td>{{ ($value->gross_salaries+$value->vr_other_allow+$value->other_allowance+$value->Arrears+$value->vehicle_addit_) }}</td>
                                <td>{{ number_format($value->taxable_salary,0) }} </td>
                                <td>{{ number_format($value->tax_amount,0) }}</td>


                                <td>{{ number_format($value->pf_amount,0) }}</td>
                                <td>{{ number_format($value->advance_salary_amount,0) }}</td>
                                <td>{{ number_format($value->eobi_amount,0) }}</td>
                                <td>{{ number_format($value->loan_amount_paid,0)}}</td>
                                <td>{{ number_format($value->bonus_amount,0) }}</td>
                                <td>{{ number_format($value->deduction_days_amount+$value->lwp_deduction ,0) }}</td>
                                <td>{{ number_format($value->penalty,0) }}</td>
                                <td>{{ number_format($value->other_deduct,0) }}</td>
                                <td>{{ number_format($value->total_deduction,0) }}</td>
                                <td>{{ number_format($value->net_salary,0) }}</td>
                            </tr>
                          

                            <?php CommonHelper::reconnectMasterDatabase(); ?>
                        </tbody>

                        @endforeach
                    </table>
                </div>

                <div class="table-responsive">


                <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                    <thead>
                    <th class="text-center" colspan="21"><b style="font-size:30px;text-decoration: underline">Grand Total</b></th>
                    </thead>
                    <thead>
                    <th class="text-center">Total Gross Salary </th>
                    <th class="text-center">Total Basic Salary</th>
                    <th class="text-center">Total Hr Utility</th>
                    <th class="text-center">Total Medical</th>
                    <th class="text-center">Total VR</th>
                    <th class="text-center">Total Other</th>
                    <th class="text-center">Total Arrear</th>
                    <th class="text-center">Total Vechile Addition @5 %</th>
                    <th class="text-center">Total Gross With Allowance</th>
                    <th class="text-center">Total Taxable Salary </th>
                    <th class="text-center">Total Monthly Income Tax </th>
                    <th class="text-center">Total Monthly.P.Fund </th>
                    <th class="text-center">Total Adv Salary </th>
                    <th class="text-center">Total EOBI </th>
                    <th class="text-center">Total Loan </th>
                    <th class="text-center">Total Bonus </th>
                    <th class="text-center">Total LWP</th>
                    <th class="text-center">Total Penalty</th>
                    <th class="text-center">Total Other</th>
                    <th class="text-center">Total Deduction </th>
                    <th class="text-center">Net Salary</th>
                    </thead>
                   
                    <tfoot>
                    <th class="text-center">
                        <b>{{ number_format($grand_gross_salaries,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_total_salary,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_hr_utlilty,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_medical,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_vr_other_allow,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_others,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_arrears,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_vehicle_addit,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_gross_with_allowance,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_taxable_salary,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_m_i_tax,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_m_p_fund,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_advance_salary,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_eobi,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_loan_amount_paid,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_bonus,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_deduction_day_amount,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_penalty,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_other_deduct,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_deduction,0) }}</b>
                    </th>
                    <th class="text-center">
                        <b>{{ number_format($grand_net_salary,0) }}</b>
                    </th>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>
    </div>

</div>