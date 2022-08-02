<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;

use App\Models\PayrollData;
use App\Models\Payslip;

$count_net_salary = 0;
$current_date = date('Y-m-d');
$month_year = Input::get('month_year');
$count =1;

CommonHelper::companyDatabaseConnection(Input::get('company_id'));
//$payslip = Payslip::select('emp_id','net_salary')->where([['payment_mode', '=', 'Transfer'],["month","=",$explodeMonthYear[1]],["year","=",$explodeMonthYear[0]]]);

$bonus = DB::table('bonus_issue')
    ->join('employee', 'employee.emp_id', '=', 'bonus_issue.emp_id')
    ->select('employee.emp_name', 'employee.account_title', 'employee.bank_account','bonus_issue.bonus_amount')
    ->where([["bonus_issue.bonus_month","=",$explodeMonthYear[1]],["bonus_issue.bonus_year","=",$explodeMonthYear[0]],['employee.bank_account','!=',''],['employee.status','=','1']]);


$total_net = DB::table('bonus_issue')
    ->join('employee', 'employee.emp_id', '=', 'bonus_issue.emp_id')
    ->select(DB::raw('SUM(bonus_issue.bonus_amount) As total_net'))
    ->where([["bonus_issue.bonus_month","=",$explodeMonthYear[1]],["bonus_issue.bonus_year","=",$explodeMonthYear[0]],['employee.bank_account','!=',''],['employee.status','=','1']])->get();

$total_net = $total_net[0]->total_net;

?>
<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                        <label><?=date('d-m-Y')?>&nbsp;&nbsp;</label>
                    </div>
                </div>
                <div class="row">&nbsp;</div>
                <div class="row">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h4>
                            <b>The Manager <br>
                                Habib Metropolitan Bank<br>
                                Progressive Plaza Branch<br>
                                Beaumont Road<br>
                                Karachi
                            </b>
                        </h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h4>Dear Sir,</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h4>We feel pleasure to enclose herewith cheque No. <?=Input::get('cheque_no')?> dated <?=HrHelper::date_format(Input::get('cheque_date'))?> amounting to Rs. <?=number_format($total_net)?>/- (<?=CommonHelper::number_to_word($total_net)?>) and request you to please credit/transfer the said amount to our employees account as per following breakup:
                        </h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h4> <b>  ACCOUNT NO. 20311-714-132642 </b>   </h4>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-responsive table-bordered table-condensed" id="regionWisePayrollReport">
                        <thead>
                        <tr>
                            <th>S. NO.</th>
                            <th>NAME </th>
                            <th>ACCOUNT NO. </th>
                            <th class="text-center">AMOUNT RS. </th>
                        </tr>
                        </thead>
                        <tbody>



                        @foreach($bonus->get() as $value)
                            <?php
                            //  $empData = Employee::select('emp_name','account_title','bank_account')->where('emp_id',$value->emp_id);
                            ?>

                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ $value->account_title }}</td>
                                <td>{{ $value->bank_account}}</td>
                                <td>{{ number_format($value->bonus_amount,0) }}</td>
                            </tr>

                        @endforeach
                        <?php $count=1;?>
                        <tr>
                            <td colspan="3" class="text-right">
                                <strong>TOTAL : </strong>
                            </td>
                            <td class="text-center">
                                <b><?php echo number_format($bonus->sum('bonus_amount'),0);?></b>
                            </td>
                        </tr>
                        <?php CommonHelper::reconnectMasterDatabase(); ?>
                        </tbody>
                    </table>
                </div>
                <h4> Thanking you   </h4>
                <br>
                <h4> Yours truly</h4>
            </div>
        </div>
    </div>
</div>