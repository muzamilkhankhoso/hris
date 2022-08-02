<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use DB;
use Input;

use App\Models\Attendance;
use Redirect;
//use Symfony\Component\Console\Input\Input;


class ExcelController extends Controller

{
    public function __construct()
    {

    }

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function importExportView(){

        return view('import_export');

    }



    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function importFile(Request $request){

        $m = Input::get('m');
        CommonHelper::companyDatabaseConnection($m);
        if($request->hasFile('sample_file')){


            $path = $request->file('sample_file')->getRealPath();

            $data = \Excel::load($path)->get()->toArray();


                foreach ($data as $value) {

                    $month_data = (explode("/",$value['date']));

                    $arr = ['acc_no' => $value['acc_no'],
                        'emp_name' => $value['name'],
                        //'ddate' => $value->ddate,
                        'attendance_date' => date('Y-m-d',strtotime($value['date'])),
                        'timetable' => $value['timetable'],
                        'day'=>  date('D',strtotime($value['date'])),
                        'month'=>  $month_data[0],
                        'year'=>  $month_data[2],
                        'clock_in' => $value['clock_in'],
                        'clock_out' => $value['clock_out'],
                        'late' => $value['late'],
                        'absent' => $value['absent'],
                        'over_time' => $value['ot_time'],
                        'att_time' => $value['att_time']

                    ];
                    DB::table('attendance')->insert($arr);

                }

                if(!empty($arr)){

                   // DB::table('attendance')->insert($arr);
                    //dd($arr);die();
                    return Redirect::back()->withErrors(['1', '']);
                    Session::flash('dataInsert','successfully saved.');

                }

            CommonHelper::reconnectMasterDatabase();
            return Redirect::back()->withErrors(['2', '']);




        /*    if($data->count()){

                foreach ($data as $key => $value) {

                    $month_data = (explode("/",$value->attendance_date));

                    $arr[] = ['acc_no' => $value->acc_no,
                        'emp_name' => $value->emp_name,
                        //'ddate' => $value->ddate,
                        'attendance_date' => date('Y-m-d',strtotime($value->attendance_date)),
                        'day'=>  date('D',strtotime($value->attendance_date)),
                        'month'=>  $month_data[0],
                        'year'=>  $month_data[2],
                        'clock_in' => $value->clock_in,
                        'clock_out' => $value->clock_out,
                        'total_in_time' => $value->total_in_time,
                        'late' => $value->late,
                        'absent' => $value->absent,
                        'over_time' => $value->over_time,
                        'must_check_in' => $value->must_check_in,
                        'must_check_out' => $value->must_check_out

                    ];


                }



            }*/

        }

        //dd('Request data does not have any files to import.');

    }



    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function exportFile($type){

        $products = Product::get()->toArray();



        return \Excel::create('hdtuto_demo', function($excel) use ($products) {

            $excel->sheet('sheet name', function($sheet) use ($products)

            {

                $sheet->fromArray($products);

            });

        })->download($type);

    }

    public function filetest()
    {
        return view("filetest");
    }

    public function tester(Request $request)
    {
echo "<pre>";
        if ($request->hasFile('sample_file')) {
            $path = $request->file('sample_file')->getRealPath();
            $data = \Excel::load($path)->get()->toArray();
            print_r($data);
            foreach ($data[0] as $value) {

           echo $value['s.no']."<br>";


            }


        }
    }

}