<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use Input;
use PDF;
use Mail;
use Auth;

use App\Helpers\CommonHelper;
use App\Models\LoanRequest;
use App\Models\EmployeePromotion;
use App\Models\IncrementLettersQueue;
use App\Models\Employee;
use App\Models\ApiEmployeeShifts;
use App\Models\FinancialYear;
use App\Models\LoanAdjustment;
use App\Models\Payslip;
use App\Models\FinalSettlement;

class loanCronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:loan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $loanDetails = LoanRequest::select('id', 'emp_code', 'loan_amount')->where([['loan_status', '=', 0],['status','=', 1]]);
        $financial_year = FinancialYear::where([['status', '=', 1]])->select('from_year', 'to_year')->get();
        $update['loan_status'] = 1;
        if($loanDetails->count() > 0):
            foreach ($loanDetails->get() as $key1 => $val1):
                $loan_request_id = $val1->id;
                $emp_code = $val1->emp_code;
                $paid_amount = 0;
                $loan_adjusted_amount = LoanAdjustment::where([['loan_id','=',$loan_request_id]])->sum('amount');
                foreach($financial_year as $key2 => $val2):
                    $year = $val2->from_year.'_'.$val2->to_year;
                    CommonHelper::DatabaseConnectionForTax($year);
                    $payslip = Payslip::where([['emp_code' ,'=', $emp_code],['loan_id' ,'=', $loan_request_id]]);
                    if($payslip->count() > 0):
                        $paid_amount += Payslip::where([['emp_code' ,'=', $emp_code],['loan_id' ,'=', $loan_request_id]])
                            ->sum('pf_loan_amount_paid');
                    endif;
                endforeach;
                CommonHelper::reconnectMasterDatabase();

                $final_settlement = FinalSettlement::where([['emp_code', '=', $emp_code],['status', '=', 1]]);
                if($final_settlement->count() > 0):
                    $final_settlement = FinalSettlement::where([['emp_code', '=', $emp_code],['status', '=', 1]])->orderBy('id', 'desc')->first();
                    if($final_settlement->loan_id != ''):
                        $settlement_loan_id = explode(',',$final_settlement->loan_id);
                        if(in_array($loan_request_id,$settlement_loan_id)):
                            LoanRequest::where([['id', '=', $loan_request_id]])->update($update);
                        endif;
                    endif;
                endif;

                $paid_amount += $loan_adjusted_amount;
                if($paid_amount == $val1->loan_amount):
                    LoanRequest::where([['id', '=', $loan_request_id]])->update($update);
                endif;
            endforeach;
        endif;
        CommonHelper::reconnectMasterDatabase();
    }

}