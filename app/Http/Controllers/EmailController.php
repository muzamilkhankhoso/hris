<?php
	namespace App\Http\Controllers;
	use Illuminate\Http\Request;
	use Redirect;
	use Response;
	use DB;
	use Config;
	use Mail;
	class EmailController extends Controller {
	    
	    
	    public function __construct()
	    {
	      //  $this->middleware('auth');
	    }
		public function testingEmail(){
		    $value =1; $cnic=2;
		     Mail::send('test', ['emp_name' => 'Muzamil', 'month' =>'09', 'year' => 2020], function ($message) use ($value, $cnic) {
                      
                        $name ="test";
                        $address = 'epay@hr-innovative.com';
                        $subject = 'Payslip for the month of ';
                        //$message->to('firebaseapplications10@gmail.com', 'Test');
                        $message->to('muzammil@innovative-net.com', 'Test');
                        $message->subject('This is test email');
                        $message->from($address, $name);
                        $message->cc($address, $name);
                        $message->bcc($address, $name);
                        $message->replyTo($address, $name);
                        $message->subject($subject);
                        // $message->attachData($pdf->output(), "Payslip_" . $value->month . "_$value->year .pdf");

                    });
                    echo 111;
		}
	}
?>