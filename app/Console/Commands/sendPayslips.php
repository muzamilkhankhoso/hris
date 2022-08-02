<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Redirect;
use Response;
use DB;
use Config;
use Dompdf\Dompdf;
use Dompdf\Options;
use PDF;
use Mail;
use DateTime;
use Auth;
use Hash;






use App\Helpers\CommonHelper;
use App\Models\LoanRequest;
use App\Models\Payroll;
use App\Models\EmailQueue;
use App\Models\Employee;


class sendPayslips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:payslips';

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

        CommonHelper::companyDatabaseConnection('12');
        $email_queue = DB::Table('email_queue')->where([['status', '=', 1]])->orderBy('emp_id');
        CommonHelper::reconnectMasterDatabase();

        if ($email_queue->count() > 0):

            foreach ($email_queue->get() as $key => $value):

                CommonHelper::companyDatabaseConnection('12');
                $cnic = Employee::where([['emp_id', '=', $value->emp_id]])->select('emp_cnic', 'professional_email');


                CommonHelper::reconnectMasterDatabase();

                if($cnic->value('professional_email') == '' || $cnic->value('professional_email') == '-' || $cnic->value('professional_email') == null):

                else:

                    CommonHelper::companyDatabaseConnection('12');
                    $emp_name = Employee::select('emp_name')->where([['emp_id', '=', $value->emp_id]])->value('emp_name');
                    CommonHelper::reconnectMasterDatabase();

                    Mail::send('Hr.test', ['emp_name' => $emp_name, 'month' => $value->month , 'year' => $value->year], function ($message) use ($value, $cnic) {
                        $m='12';
                        CommonHelper::companyDatabaseConnection('12');


                        //$payslip_data = DB::Table('payslip')->where([['month', '=', $value->month], ['year', '=', $value->year], ['emp_id', '=', $value->emp_id]])->first();

                        $payslip_data = DB::table('payslip')
                            ->where([['payslip.month','=',$value->month],['payslip.year','=',$value->year],['payslip.emp_id','=',$value->emp_id]])
                            ->join('employee', 'employee.emp_id', '=', 'payslip.emp_id')
                            ->select('payslip.*','employee.bank_account','employee.emp_father_name','employee.emp_department_id', 'employee.emp_name', 'employee.emp_cnic','employee.emp_joining_date','employee.professional_email', 'employee.designation_id')
                            ->get()->toArray();

                        CommonHelper::reconnectMasterDatabase();

                        $payslip_data =$payslip_data[0];

                        $monthNum  = $value->month;
                        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                        $monthName = $dateObj->format('F');


                        $pdf = PDF::loadView('Hr.pdf', compact('payslip_data','m'));
                        $pdf->setEncryption(str_replace(["-", "–"], '', $cnic->value('emp_cnic')));

                        $address = 'epay@hr-innovative.com';
                        $subject = 'Payslip for the month of '.$monthName." ".$value->year;
                        $name = 'Innovative Network';
                        //$message->to('firebaseapplications10@gmail.com', 'Test');
                        $message->to($cnic->value('professional_email'), 'Payslip');
//                        $message->to('khizarshafi05@gmail.com', 'Payslip');
                        $message->subject('Payslip for the month of '.$monthName." ".$value->year);
                        $message->setBody('Enter Your CNIC as password to view your Payslip');
                        $message->from($address, $name);
                        $message->cc($address, $name);
                        $message->bcc($address, $name);
                        $message->replyTo($address, $name);
                        $message->subject($subject);
                        $message->attachData($pdf->output(), "Payslip_" . $value->month . "_$value->year .pdf");

                    });

                endif;
                CommonHelper::companyDatabaseConnection('12');
                DB::table('email_queue')->where([['emp_id', '=', $value->emp_id]])->delete();
                CommonHelper::reconnectMasterDatabase();


            endforeach;

        else:



        endif;

    }

}