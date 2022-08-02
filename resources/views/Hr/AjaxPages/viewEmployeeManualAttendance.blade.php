<?php
use App\Models\Attendance;
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
 
$filter_value = Input::get('filter_value');
$getData['company_id'] = Input::get('m');
$from_date = Input::get('from_date');
$to_date = Input::get('to_date');
$clockInTime = '09:30';
$clockOutTime = '18:00';
 ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">

            <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="employeeAttendanceList">
                <thead>
                <th class="text-center">S.No</th>
                <th class="text-center">Emp ID</th>
                <th class="text-center">Employee Name</th>
                <th class="text-center">Attendace Date</th>
                <th class="text-center">Days</th>
                <th class="text-center">Clock In <a class="btn btn-sm" id="deafult_clockIn" onclick="setAsDefault('<?php echo $clockInTime ?>','clockIn')" data-val="09:30">Set as default</a></th>
                <th class="text-center">Clock Out <a class="btn btn-sm" id="deafult_clockOut" onclick="setAsDefault('<?php echo $clockOutTime ?>','clockOut')">Set as default</a></th>
                <th class="text-center">Late</th>
                <th class="text-center">Absent</th>
                </thead>
                <tbody id="">

                <?php foreach ($emp_data as $key => $value) {
                     $counter =1;
                    $day_off = explode("=>",$value->day_off);

                $period = new DatePeriod(new DateTime($from_date), new DateInterval('P1D'), new DateTime($to_date. '+1 day'));
                foreach ($period as $date) {
                $dates[$date->format("Y-m-d")] = $date->format("Y-m-d");
                }

                $FilterDates = '';
                $i = 0;

                foreach($dates as $date)
                {

                $LoopingDate = $date;
                $month_year = explode('-',$date);
                $year = $month_year[0];
                $month = $month_year[1];


                CommonHelper::companyDatabaseConnection(Input::get('m'));
                $clock_in = '';
                $clock_out = '';

//                $getAttendance = Attendance::select('clock_in','clock_out')->where([['emp_id','=',$value->emp_id],['attendance_date','=', $LoopingDate]]);
//                if($getAttendance->count() > 0 )
//                {
//                    $getAttendanceDetail =  $getAttendance->first();
//                    $clock_in = $getAttendanceDetail->clock_in;
//                    $clock_out = $getAttendanceDetail->clock_out;
//
//                }
//                else
//                {
//                    $clock_in = '';
//                    $clock_out = '';
//
//                }
                CommonHelper::reconnectMasterDatabase();
				$dating = date('D',strtotime($LoopingDate));
                ?>

                <tr style="<?php if(in_array($dating,$day_off)){echo "background-color: #FFC0CB";}?>">
                    <td><?php echo $counter++;?></td>
                    <td><input type="hidden" name="emp_id[]" value="<?php echo $value->emp_id;?>"><?php echo $value->emp_id ?></td>
                    <td><input type="hidden" name="emp_name[]" value="<?php echo $value->emp_id;?>"><?php echo $value->emp_name;?></td>
                    <input type="hidden" name="month[]" value="<?php echo $month;?>">
                    <input type="hidden" name="to_date" value="<?php echo Input::get('to_date');?>">
                    <input type="hidden" name="year[]" value="<?php echo $year;?>">

                    <td>
                        <?php if(in_array($dating,$day_off)){echo date('d-m-Y',strtotime($LoopingDate));}?>
                        <input name="attendance_date[]" id="attendance_date_<?php echo $i;?>" type="<?php if(in_array($dating,$day_off)){echo "hidden";}else{echo "date";}?>" value="<?php echo $LoopingDate;?>" class="form-control" readonly >
                    </td>

                    <td>
                        <input type="hidden" name="day[]" value="<?php echo date('D',strtotime($LoopingDate));?>"><?php echo date('D',strtotime($LoopingDate));?>
                    </td>


                    <td><?php if(in_array($dating,$day_off)){echo "DAY OFF";}?>
                        <input type="time"
                               class="form-control clockIn" value="20:00"
                               name="clock_in[]" id="clock_in_<?php echo $key + 1;?>">
                    </td>
                    <td><?php if(in_array($dating,$day_off)){echo "DAY OFF";}?>
                        <input type="time" class="form-control clockOut"
                               value="05:00"
                               name="clock_out[]" id="clock_out_<?php echo $key + 1;?>">
                    </td>
                    <td>--</td>
                    <td><?php if(in_array($dating,$day_off)){echo "DAY OFF";}else{?>
                        <select name="absent[]" id="absent_<?php echo $i;?>" class="form-control absentclas" onchange="AbsentStatus('<?php echo $i;?>')">
                            <option value="1">No</option>
                            <option value="2">YES</option>
                        </select>
                        <?php } ?>

                    </td>



                </tr>
                <?php }
                 ?>
                <?php
                    }
                ?>
                </tbody>
            </table>

        </div>
        <br>
        <div class="row text-right">
            <div class="col-sm-12">
                <button class="btn btn-sm btn-success">Submit</button>
            </div>

        </div>
    </div>
</div>
<script>
    function AbsentStatus(rowId) {
        var absentStatusVal  = $('#absent_'+rowId).val();
        if(absentStatusVal == 2)
        {
            $('#clock_in_' + rowId).attr('type', 'hidden');
            $('#clock_in_' + rowId).attr('value', '');
            $('#clock_out_' + rowId).attr('type', 'hidden');
            $('#clock_out_' + rowId).attr('value', '');
        }
        else
        {
            $('#clock_in_' + rowId).attr('type', 'time');
            $('#clock_in_' + rowId).attr('value', '10:00');
            $('#clock_out_' + rowId).attr('type', 'time');
            $('#clock_out_' + rowId).attr('value', '18:00');
        }

    }


    function setAsDefault(param1,param2,param3){
        var formdataa = new Array();
        var val;
        var param = param1;

        formdataa.push($(this.target).val());

        for (val in formdataa) {
            fillAllFields(param,param2,param3);
            // alert('Sucess');

        }
    }

    function fillAllFields(param,param2,param3) {

        var requiredField = document.getElementsByClassName(param2);

       // alert(requiredField.length);

        for (i = 0; i < requiredField.length; i++) {

            var rf = requiredField[i].id;
            console.log(rf);
            var checkType = requiredField[i].type;

            if(checkType !== 'hidden') {
                $('.' + rf).val(param);
            }
        }

    }



</script>