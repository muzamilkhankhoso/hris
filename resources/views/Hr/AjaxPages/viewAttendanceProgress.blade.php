<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Holidays;
use App\Models\PayrollData;
use App\Models\LeaveApplication;
use App\Models\LeaveApplicationData;
use App\Models\WorkingHoursPolicy;
$current_date = date('Y-m-d');
$m = Input::get('m');
$total_absent_holidays1 = '';
$monthly_holidays_absents = [];

?>


<div class="panel">
    <div class="panel-body">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                {{ Form::open(array('url' => 'had/addEmployeeDeductionDays','id'=>'')) }}
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                    <input type="hidden" name="company_id" class="form-control" id="employeeSection" value="<?=Input::get('m')?>" />
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>S.no</th>
                            <th class="text-center">Emp Id</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Month-Year</th>
                            <th class="text-center">Total Days</th>
                            <th class="text-center">Total Present</th>
                            <th class="text-center">Total Absent</th>
                            <!-- <th class="text-center">Total Holidays</th> -->
                            <th class="text-center">Leaves</th>
                            <th class="text-center">Lates</th>
                            <th class="text-center">Half Days</th>
                            {{--<th class="text-center">Overtime</th>--}}
                            <th class="text-center">Working Hours</th>
                            
                            <th class="text-center">LWP <small>(days)</small></th>
                            <th class="text-center">Reason/Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($attendance->count()>0)
                            <?php
                            
                            $counter=1;
                            $leaves_no_days = array();
                            $monthly_holidays = array();
                            $totalOffDates = array();
                            ?>
                            @foreach($attendance->get() as $value)
                                <?php
                                CommonHelper::companyDatabaseConnection(Input::get('m'));

                                $get_deduction = PayrollData::where([['emp_id','=',$value->emp_id],['month','=',$month],['year','=',$year],['status','=',1]]);

                                $approvedPayrollData = PayrollData::where([['emp_id','=',$value->emp_id],['month','=',$month],['year','=',$year],['approval_status_m','=',2],['status','=',1]]);
                                $emp_data = Employee::select('day_off','working_hours_policy_id')->where([['emp_id','=',$value->emp_id]]);
                                $day_off_emp = $emp_data->value('day_off');
                                CommonHelper::reconnectMasterDatabase();
                                $working_policy = WorkingHoursPolicy::where([['id','=',$emp_data->value('working_hours_policy_id')]]);
                                $working_policy_data = $working_policy->get()->toArray();
                                $countPresent = cal_days_in_month(CAL_GREGORIAN,$month,$year);
                                ?>
                                <tr>
                                    <td class="text-center counterId" id="<?php echo $counter;?>">
                                        <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
                                    </td>
                                    <td>{{ $value->emp_id }}</td>
                                    <td>{{ $value->emp_name }}</td>
                                    <td>
                                        <?php
                                            $dateObj   = DateTime::createFromFormat('!m', $month);
                                            $monthName = $dateObj->format('F');
                                            echo $monthName."-".$year
                                        ?>
                                    </td>
                                    <td>{{ $countPresent }}</td>
                                    <td>
                                        <?php
                                        CommonHelper::companyDatabaseConnection(Input::get('m'));
                                        $days_array = [];
                                        $day_off_emp = Employee::select('day_off')->where([['emp_id','=',$value->emp_id]])->value('day_off');
                                        $day_off_emp =  explode('=>',$day_off_emp);
                                        foreach($day_off_emp as $value2){
                                            if($value2 != ''){
                                                $days_array[] = $value2;
                                            }
                                        }

                                        $total_days_off = Attendance::select('attendance_date')
                                            ->whereBetween('attendance_date',[$from_date,$to_date])
                                            ->whereIn('day',$days_array)
                                            ->where('emp_id','=',$value->emp_id)

                                            ->get()
                                            ->toArray();

                                        $get_holidays = Holidays::select('holiday_date')->whereBetween('holiday_date',[$from_date,$to_date])->where([['status','=',1]]);

                                        $totalHolidays = $get_holidays->get()->toArray();
                                        $monthly_holidays = array_merge($totalHolidays,$total_days_off);

                                        $total_LateAbsent = Attendance::where([['month','=',$value->month],['year','=',$value->year],['emp_id','=',$value->emp_id]])
                                            ->whereNotIn('attendance_date', $monthly_holidays)
                                            ->count();

                                        $employee_id =  $value->emp_id;
                                        $total_present = Attendance::select('attendance_date')->whereBetween('attendance_date', [$from_date, $to_date])
                                            ->where(function ($q) use ($employee_id) {
                                                $q->where([['emp_id','=',$employee_id],['clock_in','!=','']])->orWhere([['emp_id','=',$employee_id],['clock_out','!=','']]);
                                            })
                                            ->whereNotIn('attendance_date', $monthly_holidays)
                                            ->count();
                                        echo ($total_present);

                                        ?>
                                    </td>
                                    <td>
                                        <?php

                                        $attendance2 = DB::table('attendance')
                                            ->select('emp_id','month','year','attendance_date','duty_time','clock_in','clock_out')
                                            ->where([['attendance.emp_id','=',$employee_id]])
                                            ->whereBetween('attendance_date',[$from_date,$to_date])
                                            ->get();
                                        
                                        $dates = array();
                                        
                                        
                                        CommonHelper::reconnectMasterDatabase();
                                        foreach($attendance2 as $value2):

                                            $LikeDate = "'".'%'.$value2->year."-".$value2->month.'%'."'";

                                            $leave_application_request_list = DB::select('select leave_application.* ,leave_application_data.from_date,leave_application_data.to_date,leave_application_data.first_second_half_date,leave_application_data.no_of_days from leave_application INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id 
                                            WHERE leave_application_data.from_date LIKE '.$LikeDate.' AND leave_application_data.emp_id = '.$value2->emp_id.' AND leave_application.status = 1 AND (leave_application.approval_status = 2 OR leave_application.approval_status_lm = 2) AND leave_application.view = "yes" 
                                            OR leave_application_data.to_date LIKE '.$LikeDate.' AND leave_application_data.emp_id = '.$value2->emp_id.' AND leave_application.status = 1  AND (leave_application.approval_status = 2 OR leave_application.approval_status_lm = 2) AND leave_application.view = "yes" 
                                            OR leave_application_data.first_second_half_date LIKE '.$LikeDate.' and leave_application_data.emp_id = '.$value2->emp_id.'');

                                            

                                            $leaves_from_dates2 = [];

                                            if(!empty($leave_application_request_list)):
                                                foreach($leave_application_request_list as $value3):
                                                    $leaves_from_dates = $value3->from_date;
                                                    $leaves_to_dates = $value3->to_date;
                                                    $leaves_from_dates2[] = $value3->from_date;
                                                    $leaves_no_days[] = $value3->no_of_days;

                                                    $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates. '+1 day'));

                                                    foreach ($period as $date) {
                                                        $dates[] = $date->format("Y-m-d");

                                                    }
                                                endforeach;

                                            endif;

                                          
                                           
                                            $monthly_holidays_absents = array_merge($monthly_holidays,$dates);

                                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                                            $total_absent_holidays1 = Attendance::select('attendance_date')->where([['emp_id','=',$value2->emp_id],['clock_in','=',Null],['clock_out','=',Null]])->whereBetween('attendance_date',[$from_date,$to_date])
                                                ->whereNotIn('attendance_date', $monthly_holidays_absents)
                                                ->get()->toArray();
                                            CommonHelper::reconnectMasterDatabase();

                                        endforeach;

                                        ?>
                                        @if(count($total_absent_holidays1) > 0)
                                            <button  type="button" class="btn btn-sm btn-danger" onclick="showDetailModelFourParamerter('hdc/viewApplicationDateWise','<?php echo $value->emp_id.','.$from_date.','.$to_date;?>','View Application Detail','<?= Input::get('m');?>')"><?= count($total_absent_holidays1) ?></button>
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <!-- <td>
                                        <?php
                                        CommonHelper::companyDatabaseConnection(Input::get('m'));
                                        $attendance222 = DB::table('attendance')
                                            ->where([['attendance.emp_id','=',$employee_id]])
                                            ->whereBetween('attendance_date',[$from_date,$to_date]);
                                        CommonHelper::reconnectMasterDatabase();
                                        ?>
                                        {{--@if($attendance222->value('clock_in') != '' && $attendance222->value('clock_out') != '')--}}
                                            @if(count($totalHolidays) > 0)
                                                <button type="button" style="color:white;" class="btn btn-sm btn-warning" onclick="showDetailModelFourParamerter('hdc/viewHolidayDetails','<?php echo $value->emp_id."_".$from_date."_".$to_date ?>','View Holiday Details','<?= Input::get('m');?>')"><?= count($totalHolidays) ?></button>
                                            @else
                                                0
                                            @endif
                                        {{--@else--}}
                                            {{--0--}}
                                        {{--@endif--}}
                                    </td> -->
                                    <td class="text-center">
                                        <?php

                                        $leaves = array();

                                        foreach($attendance2 as $value2):
                                            $LikeDate = "'".'%'.$value2->year."-".$value2->month.'%'."'";

                                            $leave_application_request_list = DB::select('select leave_application.* ,leave_application_data.from_date,leave_application_data.to_date,leave_application_data.first_second_half_date,leave_application_data.no_of_days from leave_application
                                            INNER JOIN leave_application_data on leave_application_data.leave_application_id = leave_application.id
                                            WHERE leave_application_data.from_date LIKE '.$LikeDate.' AND leave_application_data.emp_id = '.$value2->emp_id.' AND leave_application.status = 1  AND
                                            leave_application.view = "yes"
                                            OR leave_application_data.to_date LIKE '.$LikeDate.' AND leave_application_data.emp_id = '.$value2->emp_id.' AND leave_application.status = 1  AND
                                            leave_application.view = "yes"
                                            OR leave_application_data.first_second_half_date LIKE '.$LikeDate.' and leave_application_data.emp_id = '.$value2->emp_id.'');
                                            $dates2 = array();


                                            $leaves_from_dates2 = [];

                                            if(!empty($leave_application_request_list)):
                                                foreach($leave_application_request_list as $value3):
                                                    $leaves_from_dates = $value3->from_date;
                                                    $leaves_to_dates = $value3->to_date;
                                                    $leaves_from_dates2[] = $value3->from_date;
                                                    $leaves_no_days[] = $value3->no_of_days;

                                                    $period = new DatePeriod(new DateTime($leaves_from_dates), new DateInterval('P1D'), new DateTime($leaves_to_dates. '+1 day'));

                                                    foreach ($period as $date2) {
                                                        $dates2[] = $date2->format("Y-m-d");

                                                    }
                                                endforeach;

                                            endif;


                                            CommonHelper::reconnectMasterDatabase();
                                            $monthly_holidays_absents = array_merge($monthly_holidays,$dates2);

                                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                                            $total_absent_holidays = Attendance::select('attendance_date')->whereBetween('attendance_date',[$from_date,$to_date])->where([['emp_id','=',$value->emp_id],['clock_in','=',NULL],['clock_out','=',NULL]])
                                                ->whereNotIn('attendance_date', $monthly_holidays_absents)
                                                ->get()->toArray();
                                            CommonHelper::reconnectMasterDatabase();

                                            if(in_array($value2->attendance_date,$dates2)):
                                                $leaves[] = $value2->attendance_date;
                                            endif;
                                        endforeach;

                                        ?>
                                        @if(count($leaves) > 0 || count($leave_application_request_list) > 0)

                                            <button type="button" class="btn btn-sm btn-success" onclick="showDetailModelFourParamerter('hdc/viewLeaveApplicationDateWise','<?php echo $value->emp_id.','.$from_date.','.$to_date;?>','View Application Detail','<?= Input::get('m');?>')">
                                                <?= count($leave_application_request_list) ?>
                                            </button>

                                        @else
                                            0
                                        @endif
                                    </td>

                                    <td class="text-center">

                                        <?php
                                        $lates=0;
                                        $late_deduct=0;
                                        $half_days=0;
                                        $half_days_count=0;
                                        $grace_time='';


                                        CommonHelper::companyDatabaseConnection($m);
                                        $emp=Employee::where('emp_id',$employee_id)->where('status',1)->first();
                                        $emp_working_hours_policy_id=$emp->working_hours_policy_id;
                                        CommonHelper::reconnectMasterDatabase();
                                        $working_hours_policy=WorkingHoursPolicy::where('id',$emp_working_hours_policy_id)->where('status',1)->first();
                                        ?>
                                        @foreach($attendance2 as $value2)
                                            <?php
                                            $startTime = $working_hours_policy->start_working_hours_time;
                                            $endTime = strtotime("+".$working_hours_policy->working_hours_grace_time."minutes", strtotime($startTime));
                                            
                                            $half_day_time=strtotime("+".($working_hours_policy->half_day_time*60)."minutes", strtotime($value2->clock_in));
                                            if(($value2->clock_out!=NULL && $value2->clock_in !=NULL) && ($value2->duty_time < $working_hours_policy->half_day_time)){
                                                $half_days++;
                                            }
                                            
                                            
                                            if($value2->clock_in > date('h:i', $endTime)){
                                                $lates++;
                                            }
                                            ?>
                                        @endforeach
                                        <button type="button" class="btn btn-sm btn-danger" onclick="showDetailModelFourParamerter('hdc/viewLatesDetail','<?php echo $value->emp_id.','.$from_date.','.$to_date;?>','View Application Detail','<?= Input::get('m');?>')"><?= $lates ?></button>
                                        <?php

                                        $deduction=($late_deduct-$half_days_count)*$working_hours_policy->deduction_amount_late_day;
                                        ?>


                                    </td>
                                   <td class="text-center">
                                        @if($half_days > 0)
                                            <button type="button" class="btn btn-sm btn-warning" style="color: white;" onclick="showDetailModelFourParamerter('hdc/viewHalfDaysDetail','<?php echo $value->emp_id.','.$from_date.','.$to_date;?>','View Half Day Detail','<?= Input::get('m');?>')"><?= $half_days ?></button>
                                        @else
                                            0
                                        @endif

                                    </td>
                                    <td>
                                        <?php 
                                            $working_hours=0;
                                            CommonHelper::companyDatabaseConnection($m);
                                            $working_hours=DB::table('attendance')
                                            ->where([['attendance.emp_id','=',$employee_id]])
                                            ->whereBetween('attendance_date',[$from_date,$to_date])
                                            ->sum('attendance.duty_time');
                                            
                                            CommonHelper::reconnectMasterDatabase();
                                                
                                        ?>
                                    
                                        @if($working_hours > 0)
                                        <div style="cursor:pointer;height:25px;" class="@if($working_hours < 150 && $working_hours >= 100) progress-warning @elseif($working_hours < 100 && $working_hours > 0) progress-danger @else progress @endif" onclick="showDetailModelFourParamerter('hdc/viewWorkingHoursDetail','<?php echo $value->emp_id.','.$from_date.','.$to_date;?>','View Working Hours Detail','<?= Input::get('m');?>')">
                                        <div class="progress-bar progress-bar-animated @if($working_hours < 150 && $working_hours >= 100) progress-bar-warning @elseif($working_hours < 100 && $working_hours > 0) progress-bar-danger @endif" role="progressbar" aria-valuenow="{{$working_hours}}"
                                        aria-valuemin="0" aria-valuemax="100" style="width:{{($working_hours/2)}}%">
                                            {{$working_hours}}
                                        </div>
                                        </div>    
                                        @else
                                            0
                                        @endif
                                    </td>            
                                   <td class="text-center">
                                        <?php
                                            $deduction_days=count($total_absent_holidays1);
                                        ?>
                                        <input type="number" class="form-control " name="deduction_days_<?=$value->emp_id?>" id="deduction_days_<?=$value->emp_id?>" value="">

                                        <input type="hidden" name="emp_id[]" value="{{ $value->emp_id }}">
                                        <input type="hidden" name="month_{{$value->emp_id}}" value="{{ $value->month }}">
                                        <input type="hidden" name="to_date" value="<?= Input::get('to_date') ?>">
                                        <input type="hidden" name="from_date" value="<?= Input::get('from_date') ?>">
                                        <input type="hidden" name="filter" value="<?= Input::get('filter_value') ?>">
                                        <input type="hidden" name="month_year" value="<?= Input::get('month_year') ?>">
                                        <input type="hidden" name="year_{{$value->emp_id}}" value="{{ $value->year }}">
                                        <input type="hidden" name="total_days_{{$value->emp_id}}" value="{{  $countPresent }}">
                                        <input type="hidden" name="total_present_{{$value->emp_id}}" value="{{ $total_present }}">
                                        <input type="hidden" name="total_absent_{{$value->emp_id}}" value="{{count($total_absent_holidays1)}}">
                                        <input type="hidden" name="total_holidays_{{$value->emp_id}}" value="{{ count($monthly_holidays) }}">
                                        <input type="hidden" name="total_late_arrivals_{{$value->emp_id}}" value="{{ 0 }}">

                                            <input type="hidden" name="total_leaves_count_{{$value->emp_id}}" value="{{ count($leave_application_request_list) }}">
                                        <input type="hidden" name="total_ot_count_np_{{$value->emp_id}}" value="">
                                        <input type="hidden" name="total_late_hours_count_{{$value->emp_id}}" value="{{ $lates }}">


                                    </td>
                                    <td>
                                        <?php
                                        if($get_deduction->count() > 0): $PayrollRemarks = $get_deduction->value('remarks'); else: $PayrollRemarks = ''; endif  ?>
                                        <input type="text" <?php if($approvedPayrollData->count() > 0): echo 'disabled'; else: echo ''; endif;?>   name="remarks_{{$value->emp_id}}" id="remarks_{{$value->emp_id}}" class="form-control" value="{{$PayrollRemarks}}">

                                    </td>

                                </tr>
                            @endforeach

                        @else
                            <tr>
                                <td class="text-center text-danger" colspan="14">No record found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <br>
                <div class=" text-right">
                    {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                </div>
                {{ Form::close() }}

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Wait for the DOM to be ready
        $(".btn-success").click(function(e){
            var employee = new Array();
            var val;
            $("input[name='employeeSection[]']").each(function(){
                employee.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val in employee) {
                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });
    });

</script>
