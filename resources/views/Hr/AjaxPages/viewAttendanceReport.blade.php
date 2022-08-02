<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Attendence;
use App\Models\Holidays;
use App\Models\Attendance;
use App\Models\WorkingHoursPolicy;

$status_bg_color = array("Leave"=>"style='background-color: #adde80;'", "Present"=>"style='background-color: ;'", "Off Day"=>"style='background-color: #FFC0CB;color:black;'", "Absent"=>"style='background-color: #e76e6ed9;color:white;'");
CommonHelper::companyDatabaseConnection(Input::get('m'));


$working_hour_policy_id = Employee::select('working_hours_policy_id')->whereIn('emp_id',$dynamic_emp_id)->value('working_hours_policy_id');

CommonHelper::reconnectMasterDatabase();

// $count_leave_apllication = DB::table('leave_application')->where([['approval_status',2],['approval_status_lm',2],['status',1],['emp_id',Input::get('employee_id')]])->count();
$count_leave_apllication = DB::table('leave_application')->where([['approval_status',2],['status',1],['emp_id',Input::get('employee_id')]])
    ->orWhere(function($nest){
        $nest->where('approval_status_lm', 2)
            ->where('status', 1)
            ->where('emp_id',Input::get('employee_id'));
    })->count();


$working_hour_policy = WorkingHoursPolicy::where([['id','=',$working_hour_policy_id]])->first();
//echo "<pre>";
//echo "asd";
//print_r($dynamic_emp_id);
//echo "</pre>";

if($working_hour_policy->count() > 0 ):
    $GraceTime = strtotime("+".$working_hour_policy->value('working_hours_grace_time')." minutes", strtotime($working_hour_policy->value('start_working_hours_time')));

else:
    echo "<h3 class='text-center' style='color:red;'>Please Select Employee Working Hour policy First !</h3>";
    return true;

endif;





/*$getMonth = $month;
$startDate = $month.'-01';
$endDate = date("Y-m-t", strtotime($getMonth));
$date1 = strtotime($startDate);
$date2 = strtotime($endDate);*/
//echo "<pre>";
//print_r($attendance->toArray()); die();

?>
{{--<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">--}}
<style>



</style>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 subHeadings">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Report for Period: {{CommonHelper::changeDateFormat($from_date)}} - {{CommonHelper::changeDateFormat($to_date)}}</b></div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Employee Code: {{ $employee['emp_id']}}</b></div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Employee Name: {{ $employee['emp_name']}}</b></div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right subHeadings">
                <?php $end_time =CommonHelper::getMasterTableValueById($_GET['m'],'working_hours_policy','end_working_hours_time',$employee['working_hours_policy_id']);
                $start_time = CommonHelper::getMasterTableValueById($_GET['m'],'working_hours_policy','start_working_hours_time',$employee['working_hours_policy_id']);
                ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Duty Timing: {{date('h:i: a', strtotime($start_time))}} - {{date('h:i: a', strtotime($end_time))}}</b> <b>Duty Hours: </b> <?php
                        $start = strtotime($start_time);
                        $end = strtotime($end_time);
                        $elapsed1 = $end - $start;
                        echo '<b>'.$employeeDutyHours = date("H:i", $elapsed1).'</b>';
                        ?></div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Department: {{CommonHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$employee['emp_sub_department_id'])}}</b></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hidden-print" id="highlights">
                <span class="label" style="background-color:#FFC0CB;">&nbsp;&nbsp;&nbsp;</span> = Holidays
                <span class="label" style="background-color:#adde80;">&nbsp;&nbsp;&nbsp;</span> = Leaves
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="example">
                        <thead>
                        <th class="text-center">Sno#</th>
                        <th class="text-center">Day</th>
                        <th class="text-center">Shift</th>
                        <th class="text-center">Att- Status</th>
                        <th class="text-center">Attendance Date</th>
                        <th class="text-center">Clock Inn Time</th>
                        <th class="text-center">Clock Out Time</th>
                        <th class="text-center">Working Hours</th>
                        </thead>
                        <tbody>
                        <?php $count =1;
                        $total_ot_days = 0;
                        $leave_application_request_list =[];
                        $totalHoursWorked4=0;
                        $dates = [];
                        $total_absent_holidays=array();
                        $absentDays=0;
                        $leaves= array();
                        $totalLateHoursCount=0;
                        $totalLateMintsCount=0;
                        $diff2=0;
                        ?>
                        @if($attendance->count()>0)
                            @foreach($attendance as $value)
                                <?php

                                CommonHelper::reconnectMasterDatabase();

                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                $day_off_emp =Employee::select('day_off')->where([['emp_id','=',$employee_id]])->value('day_off');
                                $dayoff = explode("=>",$day_off_emp);

                                $total_days_off = Attendance::select('attendance_date')->whereBetween('attendance_date',[$from_date,$to_date])->whereIn('day',[$dayoff[0],$dayoff[1]])->where('emp_id','=',$employee_id);


                                if($total_days_off->count() > 0):

                                    foreach($total_days_off->get()->toArray() as $offDates):
                                        $totalOffDates[] = $offDates['attendance_date'];
                                    endforeach;

                                else:
                                    $totalOffDates =array();
                                endif;
                                $get_holidays = Holidays::select('holiday_date')->where([['status','=',1],['month','=',$value->month],['year','=',$value->year]]);
                                if($get_holidays->count() > 0):
                                    foreach($get_holidays->get() as $value2):

                                        $monthly_holidays[]=$value2['holiday_date'];
                                        $public_holidays[]=$value2['holiday_date'];
                                    endforeach;

                                else:
                                    $monthly_holidays =array();
                                    $public_holidays = array();
                                endif;

                                $monthly_holidays = array_merge($monthly_holidays,$totalOffDates);

                                CommonHelper::reconnectMasterDatabase();
                                $duty_time =  CommonHelper::getMasterTableValueById(Input::get('m'),'working_hours_policy','start_working_hours_time',$employee['working_hours_policy_id']);

                                $duty_end_time =  CommonHelper::getMasterTableValueById($_GET['m'],'working_hours_policy','end_working_hours_time',$employee['working_hours_policy_id']);


                                $LikeDate = "'".'%'.$value->year."-".$value->month.'%'."'";


                                $leave_application_request_list = DB::select('select leave_application.* ,leave_application_data.from_date,leave_application_data.to_date,leave_application_data.first_second_half_date from leave_application
                            INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id
                            WHERE leave_application_data.from_date LIKE '.$LikeDate.' AND leave_application_data.emp_id = '.$value->emp_id.' AND leave_application.status = 1 AND (leave_application.approval_status = 2 OR leave_application.approval_status_lm = 2) AND
                                leave_application.view = "yes"
                            OR leave_application_data.first_second_half_date LIKE '.$LikeDate.' and leave_application_data.emp_id = '.$value->emp_id.'');



                                $leaves_from_dates2 = [];
                                if(!empty($leave_application_request_list)):
                                    foreach($leave_application_request_list as $value3):
                                        $leaves_from_dates = $value3->from_date;
                                        $leaves_to_dates = $value3->to_date;
                                        $leaves_type=$value3->leave_type;
                                        $leaves_from_dates2[] = $value3->from_date;

                                        $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates. '+1 day'));

                                        foreach ($period as $date) {
                                            $dates[] = $date->format("Y-m-d");
                                        }
                                        //$leave_type_name = HrHelper::getMasterTableValueById('1','leave_type','leave_type_name',$value3->leave_type);
                                    endforeach;

                                endif;
                                $monthly_holidays_absents = array_merge($monthly_holidays,$dates);
                                //print_r($monthly_holidays_absents);
                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                $total_absent_holidays = Attendance::select('attendance_date')->whereBetween('attendance_date',[$from_date,$to_date])->where([['emp_id','=',$value->emp_id],['clock_in','=',''],['clock_out','=','']])
                                    ->whereNotIn('attendance_date', $monthly_holidays_absents)
                                    ->get()->toArray();



                                if(in_array($value->attendance_date,$dates)):
                                    $leaves[] = $value->attendance_date;
                                endif;
                                $attendance_status = '';

                                if(in_array($value->attendance_date,$totalOffDates)){
                                    $attendance_status = 'Off Day';
                                }
                                else if(in_array($value->attendance_date,$public_holidays)){
                                    $attendance_status = 'Off Day';
                                }
                                elseif(in_array($value->attendance_date,$leaves_from_dates2)){
                                    $attendance_status = 'Leave';
                                }
                                elseif($value->clock_in != '' || $value->clock_out != ''){
                                    $attendance_status = 'Present';
                                }
                                elseif(in_array($value->attendance_date,$dates)){
                                    $attendance_status = 'Leave';
                                }
                                else{
                                    $attendance_status = 'Absent';
                                }

                                ?>


                                <style>
                                    @media screen, print {
                                        .jholeLal<?php echo $count?>  {
                                            background-color: <?php echo HrHelper::checkTRColor($value->attendance_date,$monthly_holidays,$dates)?>  !important;
                                            -webkit-print-color-adjust: exact;
                                        }
                                    }
                                </style>

                                <tr class="text-center" <?php echo $status_bg_color[$attendance_status]; ?>>

                                    <td>{{$count++}}</td>
                                    <td>{{$value->day}}</td>
                                    <td>@if(in_array($value->attendance_date,$totalOffDates)) Off Day @elseif(in_array($value->attendance_date,$public_holidays))Holiday @elseif(in_array($value->attendance_date,$leaves_from_dates2))Leave @else Routine @endif</td>
                                    <td>@if(in_array($value->attendance_date,$totalOffDates)) Off Day @elseif(in_array($value->attendance_date,$public_holidays))Off Day @elseif(in_array($value->attendance_date,$leaves_from_dates2))Leave @elseif($value->clock_in != '' || $value->clock_out != '') Present @elseif(in_array($value->attendance_date,$dates)) Leave @else Absent @endif</td>
                                    <td class="text-center">{{HrHelper::date_format($value->attendance_date)}}</td>
                                    <td class="text-center">
                                        @if($value->clock_in != '')
                                            {{date('h:i: a', strtotime($value->clock_in))}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($value->clock_out != '')
                                            {{date('h:i: a', strtotime($value->clock_out))}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        //Duration
                                        if($value->clock_in == '' || $value->clock_out == ''):
                                            echo '';
                                            $elapsed2 = 0;
                                        else:
                                            $start = strtotime($value->clock_in);
                                            $end = strtotime($value->clock_out);

                                            $elapsed2 = $end - $start;
                                            //echo '<b>'.date("H:i", $elapsed).'</b>'.'<br>';
                                            echo "<b>".$duration = date("H:i", $elapsed2)."</b>";
                                        endif;
                                        ?>
                                    </td>


                                </tr>
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="11" style="color:red;">
                                    Record Not Found
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if($attendance->count()>0)
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Total Present: {{$total_present}}</b></div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Total Absent: <?= count($total_absent_holidays) ?></b></div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Total Holidays: <?= count($monthly_holidays2) ?></b></div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subHeadings"><b>Total Leave(s): {{$count_leave_apllication}}</b></div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-right">
                    <b>Total Late Hours <?php
                        echo $totalLateHoursCount ?>
                    </b>
                </div>
            </div>
        @endif
    </div>
    <div class="lineHeight">&nbsp;</div>
{{-- <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>--}}
{{--<script>--}}
{{--	$(document).ready(function() {--}}
{{--    $('#example').DataTable();--}}
{{--	});--}}
{{--</script>	--}}
