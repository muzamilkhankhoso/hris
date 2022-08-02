<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

$countRemainingLeaves=0;
$countRemainingCasualLeaves=0;
$countUsedLeavess=0;

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\LeaveApplicationData;
use App\Models\Employee;

?>




<div class="lineHeight">
    &nbsp;
    @if($leaves_policy_validatity == 0)
        <h4><div style="padding:0px;" class="policy_expire_mesg"> Leaves Policy Expire ,Please Update Leaves Policy !</div></h4>
    @endif
</div>
<?php echo Form::open(array('url' => 'had/addTaxesDetail','id'=>'EOBIform'));?>
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <label>Emp Code:</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <input type="text" name="" id="" value="{{ $emp_data->emp_id }}" disabled class="form-control requiredField" />
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <label>Emp Name:</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <input type="text" name="" id="" value="<?= $emp_data->emp_name ?>" disabled class="form-control requiredField" />
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <label>Emp Department:</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <input type="text" name="" id="" value="{{ HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$emp_data->emp_sub_department_id)}}" disabled class="form-control requiredField" />
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <label>Emp Designation:</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <input type="text" name="" id="" value="{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$emp_data->designation_id)}}" disabled class="form-control requiredField" />
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div style="color: #fff;background-color: rgb(137, 113, 234);">
            <div style="display:block;margin-top: 15px;">&nbsp;&nbsp;<span style="color:white;">LEAVES BALANCE</span></div>
            <div class="text-right">
                <?php
                $total_leaves = $total_leaves->total_leaves;
                $taken_leaves = $taken_leaves->taken_leaves;?>
                <span class="btn btn-success btn-sm" style="cursor: default">Taken Leaves = <?= ($taken_leaves == '')? '0': $taken_leaves ?></span>
                <span class="btn btn-danger btn-sm" style="cursor: default">Remaining Leaves= <?=($total_leaves-$taken_leaves)?></span>
            </div>

        </div>

    </div>
</div>

<div class="row">

    <br>
    <div class="col-sm-12">
        <table class="table table-sm mb-0 table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>S No#</th>
                <th>Leaves Name</th>
                <th class="text-center">No of leaves</th>
                <th class="text-center">Used</th>
                <th class="text-center">Remaining</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $count =0 ;
            $count_leaves = 0;
            ?>
            @foreach($leaves_policy as $val)
                <?php
                $count_leaves+=$val->no_of_leaves ;
                $count++;
                ?>
                <tr>
                    <td style="color: green;"><b>{{ $count }}</b></td>
                    <td style="color: green;"><b>{{ HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$val->leave_type_id )}}</b></td>
                    <td class="text-center" style="color: green;"><b>
                            <?php

                            if($val->leave_type_id == 1):
                                echo $val->no_of_leaves;
                            elseif($val->leave_type_id == 3):
                                echo $val->no_of_leaves;
                            else:
                                echo $val->no_of_leaves;
                            endif;
                            ?>
                        </b>
                    </td>
                    <td class="text-center">
                        <?php

                        //CommonHelper::companyDatabaseConnection(Input::get('m'));
                        $getUsedLeaves =DB::table('leave_application_data')
                            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                            ->where([['leave_application.emp_id','=',$emp_data->emp_id],['leave_application.leave_type','=',$val->leave_type_id ],
                                ['leave_application.status', '=', '1'],
                                ['leave_application.approval_status', '=', '2']])
                            ->sum('no_of_days');
                        $countUsedLeavess +=$getUsedLeaves;
                        echo $getUsedLeaves;
                        //CommonHelper::reconnectMasterDatabase();

                        ?>
                    </td>
                    <td class="text-center">
                        <?php
                        if($val->leave_type_id == 1):
                            $remaining = $val->no_of_leaves - $getUsedLeaves;

                        elseif($val->leave_type_id == 3):
                            $remaining = $val->no_of_leaves - $getUsedLeaves;
                            $countRemainingCasualLeaves=$val->no_of_leaves -$getUsedLeaves;
                        else:
                            $remaining = $val->no_of_leaves-$getUsedLeaves;
                        endif;



                        if($remaining < 0):
                            echo "<span style='color:red;'>$remaining</span>";
                        else:
                            $countRemainingLeaves +=$remaining;
                            echo $remaining;
                        endif;
                        ?>
                    </td>
                </tr>
            @endforeach

            </tbody>
            <tfoot>
            <tr>
                <th class="text-right"  style="color: #fff;background-color: #6a75e9;" colspan="2"><b>Total</b></th>
                <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #6a75e9;"><b>{{ $count_leaves }}</b></th>
                <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #6a75e9; "><?php print_r($countUsedLeavess)?></th>
                <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #6a75e9; "><?=$countRemainingLeaves?></th>


            </tr>
            </tfoot>
        </table>
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <div style="color: #fff;background-color: rgb(137, 113, 234);">
            <b>SELECT LEAVE TYPE</b>
            &ensp;
            <span class="glyphicon glyphicon-arrow-down"></span>
        </div>
        <div class="btn-group" data-toggle="buttons" style="padding: 4px;">
            @foreach($leaves_policy as $val)
                <?php $leaveName = HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$val->leave_type_id )?>

                <label style="color:white;cursor:pointer;border:1px solid #fff;" @if($val->leave_type_id=="1") class="btn btn-sm btn-success" @else class="btn btn-sm btn-warning" @endif onclick="viewEmployeeLeavesDetail('<?=$val->id?>','{{ $val->no_of_leaves }}','<?= $val->leave_type_id ?>')">
                    <input  type="hidden"  name="leave_type" id="leave_type"  value="<?=$val->leave_type_id?>">
                    {{ $leaveName }}
                    <span class="glyphicon glyphicon-ok"></span>
                </label>
            @endforeach
        </div>
    </div>
</div>


<div class="" id="leavesDatas">

</div>
<div class="leave_days_area" id="">

</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label>Reason For Leave</label>
        <textarea id="reason" class="form-control requiredField">-</textarea>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label>Address While on Leaave</label>
        <textarea id="leave_address" class="form-control requiredField">-</textarea>
    </div>
</div>

<br>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4 style="text-decoration: underline;"> <b>Terms & Condtions</b></h4>
        <?php print_r($leaves_policy[0]->terms_conditions); ?>
    </div>

</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <span id="errorMesg" style="color:red"></span>
        <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
        <button type="button" id="submitBtn" onclick="check_days()" class="btn btn-sm btn-success">Update</button>
    </div>
</div>
<?php echo Form::close();?>




<script type="text/javascript">

    @if($leaves_policy_validatity == 0)
setInterval(function () {
        $(".policy_expire_mesg").css("color","#fff");
        $(".policy_expire_mesg").css("background-color","#a94442");
        $(".policy_expire_mesg").css("border-color","#a94442");
        setTimeout(function () {
            $(".policy_expire_mesg").removeAttr("style");

        },500)
    },900);
    @endif

    function leaves_day_type(type)
    {

        var current_date  = '<?= date("Y-m-d") ?>';
        var leave_type = $("#leave_type").val();

        if(leave_type == 2)
        {
            if(type == 'full_day_leave')
            {
                $(".leave_days_area").html('');
                $(".leave_days_area").html('<div class="row">'+
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Leave from </label><input type="date" class="form-control requiredField" name="from_date" id="from_date"> </div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> To </label><input type="date" class="form-control requiredField" name="to_date" id="to_date"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> No. of Days</label><input type="number" readonly class="form-control requiredField" id="no_of_days" name="no_of_days">' +
                    '<span id="warning_message" style="color:red"></span></div>' +
                    '</div>');


            }
            else if(type == 'half_day_leave')
            {
                $(".leave_days_area").html('');
                $(".leave_days_area").html('<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input checked type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
                    '<label> (01:00 A.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;2nd Half&nbsp;:&nbsp;<input type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
                    '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> Date </label><input type="date" class="form-control requiredField" id="first_second_half_date" name="first_second_half_date"> </div></div>');
            }
            else if(type == 'short_leave')
            {
                $(".leave_days_area").html('');
                $(".leave_days_area").html('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> From (Time) </label><input type="time" class="form-control requiredField" id="short_leave_time_from" name="short_leave_time_from"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label> To (Time) </label><input class="form-control requiredField" type="time"  id="short_leave_time_to" name="short_leave_time_to"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Date </label><input type="date" class="form-control requiredField" id="short_leave_date" name="short_leave_date"></div></div>');

            }
        }
        else if(leave_type == 3)
        {
            if(type == 'full_day_leave')
            {
                $(".leave_days_area").html('');

                $(".leave_days_area").html('<div class="row">'+
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Leave from </label><input type="date" class="form-control requiredField" name="from_date" id="from_date"> </div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> To </label><input type="date" class="form-control requiredField" onchange="checkCasualLeavesDifference(<?php echo $countRemainingCasualLeaves ?>)" name="to_date" id="to_date"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> No. of Days</label><input type="number" readonly onclick="checkCasualLeave()" onchange="checkCasualLeave()" class="form-control requiredField" id="no_of_days" name="no_of_days">' +
                    '<span id="warning_message" style="color:red"></span></div>' +
                    '</div> <br>');

            }
            else if(type == 'half_day_leave')
            {
                $(".leave_days_area").html('');
                $(".leave_days_area").html('<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input checked type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
                    '<label> (01:00 A.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;2nd Half&nbsp;:&nbsp;<input type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
                    '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> Date </label><input type="date" class="form-control requiredField" id="first_second_half_date" name="first_second_half_date"> </div></div>');
            }
            else if(type == 'short_leave')
            {

                $(".leave_days_area").html('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> From (Time) </label><input type="time" class="form-control requiredField" id="short_leave_time_from" name="short_leave_time_from"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label> To (Time) </label><input class="form-control requiredField" type="time"  id="short_leave_time_to" name="short_leave_time_to"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Date </label><input type="date" class="form-control requiredField" id="short_leave_date" name="short_leave_date"></div></div>');

            }
        }


    }

    function calcBusinessDays(start, end) {

        // Copy date objects so don't modify originals
        var s = new Date(+start);
        var e = new Date(+end);

        // Set time to midday to avoid dalight saving and browser quirks
        s.setHours(12,0,0,0);
        e.setHours(12,0,0,0);

        // Get the difference in whole days
        var totalDays = Math.round((e - s) / 8.64e7);

        // Get the difference in whole weeks
        var wholeWeeks = totalDays / 7 | 0;

        // Estimate business days as number of whole weeks * 5
        var days = wholeWeeks * 5;

        // If not even number of weeks, calc remaining weekend days
        if (totalDays % 7) {
            s.setDate(s.getDate() + wholeWeeks * 7);

            while (s < e) {
                s.setDate(s.getDate() + 1);

                // If day isn't a Sunday or Saturday, add to business days
                if (s.getDay() != 0 && s.getDay() != 6) {
                    ++days;
                }
            }
        }
        return days;
    }

    function check_days()
    {

        var leave_type = $("#leave_type").val();
        var leaves_day_type = $("input[id='leave_day_type']:checked").val();
        var leave_policy_id = '<?=$leaves_policy[0]->leaves_policy_id?>';
        var leave_application_recordId = '<?=Input::get('id')?>';
        var leave_application_id = leave_application_recordId.split('|')

        //  jqueryValidationCustom();
        if(true){

            if(leave_type == 4)
            {
                var emp_id = '<?= $emp_data->emp_id ?>';
                var company_id = '<?= Input::get('m') ?>';
                var no_of_days = $("#no_of_days").val();
                var from_date =  $("#from_date").val();
                var to_date   =  $("#to_date").val();
                var leave_type = $("#leave_type").val();
                var leave_day_type = 1;
                var reason   = $("#reason").val();
                var leave_address =  $("#leave_address").val();
                var data = {
                    leave_application_id:leave_application_id[0],
                    emp_id:emp_id,
                    leave_policy_id:leave_policy_id,
                    company_id:company_id,
                    leave_type:leave_type,
                    leave_day_type:leave_day_type,
                    no_of_days:no_of_days,
                    from_date:from_date,
                    to_date:to_date,
                    reason:reason,
                    leave_address:leave_address,
                };

                var from_date = $('#from_date').val();
                var to_date   = $("#to_date").val();
                var date1 = new Date(from_date);
                var date2 = new Date(to_date);
                var no_of_days = $("#no_of_days").val();
                var diffDays=calcBusinessDays(date1, date2);

                //  if(diffDays != 90 )
                // {
                //     $("#maternity_date_error").html("Please Correct Date Difference !");
                //      return false;
                //  }
                //  else
                // {
                //     $("#maternity_date_error").html("");
                // }



            }

            else if(leave_type == 1)
            {

                if(leaves_day_type == 'full_day_leave'){


                    var inform_days_two = '29';
                    var from_date = $('#from_date').val();
                    var to_date   = $("#to_date").val();
                    var no_of_days = $('#no_of_days').val();
                    var current_date = '<?= date("Y-m-d"); ?>';
                    var date1 = current_date;
                    var date2 = from_date;
                    date1 = date1.split('-');
                    date2 = date2.split('-');
                    date1 = new Date(date1[0], date1[1], date1[2]);
                    date2 = new Date(date2[0], date2[1], date2[2]);
                    date1_unixtime = parseInt(date1.getTime() / 1000);
                    date2_unixtime = parseInt(date2.getTime() / 1000);
                    var timeDifference = date2_unixtime - date1_unixtime;
                    var timeDifferenceInHours = timeDifference / 60 / 60;
                    var timeDifferenceInDays = timeDifferenceInHours  / 24;

//                        var check1= checkLeavesDifference();
//                        var check2= checkAnnualLeaveDays();
//
//                        if((inform_days_two-1) > timeDifferenceInDays)
//                        {
//
//                            $('#warning_message').html('For Anuual Leaves , Inform Administration Before '+inform_days_two+' days !');
//                            var check = false;
//
//                        }
//                        else
//                        {
//                            $('#warning_message').html('');
//                            var check = true;
//                        }


                    // if(check == true && check1 == true && check2 == true){

                    var emp_id = '<?= $emp_data->emp_id ?>';
                    var company_id = '<?= Input::get('m') ?>';
                    var full_day_deduction_rate  = '<?=$leaves_policy[0]->fullday_deduction_rate ?>';
                    var no_of_days = ($("#no_of_days").val()*full_day_deduction_rate);
                    var from_date =  $("#from_date").val();
                    var to_date   = $("#to_date").val();
                    var leave_type = $("#leave_type").val();
                    var leave_day_type = 1
                    var reason   = $("#reason").val();
                    var leave_address =  $("#leave_address").val();
                    var data = {
                        leave_application_id:leave_application_id[0],
                        emp_id:emp_id,
                        leave_policy_id:leave_policy_id,
                        company_id:company_id,
                        full_day_deduction_rate:full_day_deduction_rate,
                        leave_type:leave_type,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        from_date:from_date,
                        to_date:to_date,
                        reason:reason,
                        leave_address:leave_address,
                    };

//                        }
//                        else
//                        {
//                            return false;
//                        }


                }

            }
            else if(leave_type == 2)
            {

                if(leaves_day_type == 'full_day_leave'){


                    var from_date = $('#from_date').val();
                    var no_of_days = $('#no_of_days').val();
                    var emp_id = '<?= $emp_data->emp_id ?>';
                    var company_id = '<?= Input::get('m') ?>';
                    var full_day_deduction_rate  = '<?=$leaves_policy[0]->fullday_deduction_rate ?>';
                    var no_of_days = ($("#no_of_days").val()*full_day_deduction_rate);
                    var from_date =  $("#from_date").val();
                    var to_date   = $("#to_date").val();
                    var leave_type = $("#leave_type").val();
                    var leave_day_type = 1
                    var reason   = $("#reason").val();
                    var leave_address =  $("#leave_address").val();
                    var data = {
                        leave_application_id:leave_application_id[0],
                        leave_type:leave_type,
                        emp_id:emp_id,
                        leave_policy_id:leave_policy_id,
                        company_id:company_id,
                        full_day_deduction_rate:full_day_deduction_rate,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        from_date:from_date,
                        to_date:to_date,
                        reason:reason,
                        leave_address:leave_address,
                    };



                }
                else if(leaves_day_type == 'half_day_leave')
                {

                    var emp_id = '<?= $emp_data->emp_id ?>';
                    var company_id = '<?= Input::get('m') ?>';
                    var reason   = $("#reason").val();
                    var leave_address =  $("#leave_address").val();
                    var half_day_deduction_rate  = '<?=$leaves_policy[0]->halfday_deduction_rate ?>';
                    var first_second_half = $("input[id='first_second_half']:checked").val();
                    var no_of_days = (1*half_day_deduction_rate);
                    var first_second_half_date =  $("#first_second_half_date").val();
                    var leave_day_type = 2
                    var leave_type = $("#leave_type").val();
                    var data = {
                        leave_application_id:leave_application_id[0],
                        leave_type:leave_type,
                        company_id:company_id,
                        emp_id:emp_id,
                        leave_policy_id:leave_policy_id,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        first_second_half:first_second_half,
                        first_second_half_date:first_second_half_date,
                        leave_address:leave_address,
                        reason:reason,
                        first_second_half_date:first_second_half_date,
                    };



                }
                else if(leaves_day_type == 'short_leave')
                {

                    var emp_id = '<?= $emp_data->emp_id ?>';
                    var company_id = '<?= Input::get('m') ?>';
                    var reason   = $("#reason").val();
                    var leave_address =  $("#leave_address").val();
                    var per_hour_deduction_rate  = '<?=$leaves_policy[0]->per_hour_deduction_rate ?>';
                    var short_leave_time_from = $("#short_leave_time_from").val();
                    var short_leave_time_to = $("#short_leave_time_to").val();
                    var short_leave_date = $("#short_leave_date").val();
                    var no_of_days = (1*per_hour_deduction_rate);
                    var first_second_half_date =  $("#first_second_half_date").val();
                    var leave_day_type = 3;
                    var leave_type = $("#leave_type").val();

                    var data = {
                        leave_application_id:leave_application_id[0],
                        leave_type:leave_type,
                        company_id:company_id,
                        emp_id:emp_id,
                        leave_policy_id:leave_policy_id,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        short_leave_time_from:short_leave_time_from,
                        short_leave_time_to:short_leave_time_to,
                        short_leave_date:short_leave_date,
                        leave_address:leave_address,
                        reason:reason
                    };

                }
                else
                {
                    alert('Error ! Select Full/Half/Short Leave Type !');
                    return false;
                }
            }
            else if(leave_type == 3)
            {
                if(leaves_day_type == 'full_day_leave'){



                    var from_date = $('#from_date').val();
                    var no_of_days = $('#no_of_days').val();
                    var emp_id = '<?= $emp_data->emp_id ?>';
                    var company_id = '<?= Input::get('m') ?>';
                    var full_day_deduction_rate  = '<?=$leaves_policy[0]->fullday_deduction_rate ?>';
                    var no_of_days = ($("#no_of_days").val()*full_day_deduction_rate);
                    var from_date =  $("#from_date").val();
                    var to_date   = $("#to_date").val();
                    var leave_type = $("#leave_type").val();
                    var leave_day_type = 1
                    var reason   = $("#reason").val();
                    var leave_address =  $("#leave_address").val();
                    var data = {
                        leave_application_id:leave_application_id[0],
                        leave_type:leave_type,
                        emp_id:emp_id,
                        leave_policy_id:leave_policy_id,
                        company_id:company_id,
                        full_day_deduction_rate:full_day_deduction_rate,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        from_date:from_date,
                        to_date:to_date,
                        reason:reason,
                        leave_address:leave_address,
                    };


                }
                else if(leaves_day_type == 'half_day_leave')
                {

                    var emp_id = '<?= $emp_data->emp_id ?>';
                    var company_id = '<?= Input::get('m') ?>';
                    var reason   = $("#reason").val();
                    var leave_address =  $("#leave_address").val();
                    var half_day_deduction_rate  = '<?=$leaves_policy[0]->halfday_deduction_rate ?>';
                    var first_second_half = $("input[id='first_second_half']:checked").val();
                    var no_of_days = (1*half_day_deduction_rate);
                    var first_second_half_date =  $("#first_second_half_date").val();
                    var leave_day_type = 2
                    var leave_type = $("#leave_type").val();
                    var data = {
                        leave_application_id:leave_application_id[0],
                        leave_type:leave_type,
                        company_id:company_id,
                        emp_id:emp_id,
                        leave_policy_id:leave_policy_id,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        first_second_half:first_second_half,
                        first_second_half_date:first_second_half_date,
                        leave_address:leave_address,
                        reason:reason,
                        first_second_half_date:first_second_half_date,
                    };

                }
                else if(leaves_day_type == 'short_leave')
                {

                    var emp_id = '<?= $emp_data->emp_id ?>';
                    var company_id = '<?= Input::get('m') ?>';
                    var reason   = $("#reason").val();
                    var leave_address =  $("#leave_address").val();
                    var per_hour_deduction_rate  = '<?=$leaves_policy[0]->per_hour_deduction_rate ?>';
                    var short_leave_time_from = $("#short_leave_time_from").val();
                    var short_leave_time_to = $("#short_leave_time_to").val();
                    var short_leave_date = $("#short_leave_date").val();
                    var no_of_days = (1*per_hour_deduction_rate);
                    var first_second_half_date =  $("#first_second_half_date").val();
                    var leave_day_type = 3;
                    var leave_type = $("#leave_type").val();

                    var data = {
                        leave_application_id:leave_application_id[0],
                        leave_type:leave_type,
                        company_id:company_id,
                        emp_id:emp_id,
                        leave_policy_id:leave_policy_id,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        short_leave_time_from:short_leave_time_from,
                        short_leave_time_to:short_leave_time_to,
                        short_leave_date:short_leave_date,
                        leave_address:leave_address,
                        reason:reason
                    };

                }
                else
                {
                    alert('Error ! Select Full/Half/Short Leave Type !');
                    return false;
                }
            }
            else
            {
                alert('Please Select Leaves Type !')
            }
            var company_id = '<?= Input::get('m') ?>';

            $.ajax({
                url: '<?php echo url('/')?>/hedbac/editLeaveApplicationDetail',
                type: "GET",
                data :data,
                success:function(data) {
                    sessionStorage.setItem('successMsg','Application Updated');
                    localStorage.setItem('mineLeaves','1');
                    location.reload();
                    //var baseUrl = $('#baseUrl').val();
                    // var url = $('#baseUrl').val()+'/hr/viewLeaveApplicationList?pageType=viewlist&&parentCode=21&&m='+company_id+'';
                    //window.location.href =url;
                }
            });

        }

        else
        {
            //alert(jqueryValidationCustom());
        }
    }

    function viewEmployeeLeavesDetail(id,leavesCount,leaveType)
    {

        //alert(leaveType); return false;
        var current_date  = '<?= date("Y-m-d") ?>';
        $('#leavesDatas').append('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $(".leave_days_area").html('');
        $('#leave_type').val(leaveType);

        $('#leave_type').val(leaveType);

        if (leaveType == 4)
        {
            var url = '<?php echo url('/')?>/hdc/viewEmployeeLeaveDetail';
            var data =  {
                company_id :'<?= Input::get('m') ?>' ,
                emp_id:'<?=$attendance_machine_id?>',
                leave_id:id,
                leavesCount:leavesCount,
                leaveType:leaveType,
            };
        }
        else if(leaveType == 1)
        {

            var url = '<?php echo url('/')?>/hdc/viewEmployeeLeaveDetail';
            var data =  {
                company_id :'<?= Input::get('m') ?>' ,
                emp_id:'<?=$attendance_machine_id?>',
                leave_id:id,
                leavesCount:leavesCount,
                leaveType:leaveType,
            };

        }
        else if(leaveType == 2)
        {

            var url = '<?php echo url('/')?>/hdc/viewEmployeeLeaveDetail';
            var data =  {
                company_id :'<?= Input::get('m') ?>' ,
                emp_id:'<?=$attendance_machine_id?>',
                leave_id:id,
                leavesCount:leavesCount,
                leaveType:leaveType,
            };
        }
        else if(leaveType == 3)
        {

            var url = '<?php echo url('/')?>/hdc/viewEmployeeLeaveDetail';
            var data =  {
                company_id :'<?= Input::get('m') ?>' ,
                emp_id:'<?=$attendance_machine_id?>',
                leave_id:id,
                leavesCount:leavesCount,
                leaveType:leaveType,
            };
        }

        else
        {
            $(".leave_days_area").html('');
        }

        $.ajax({
            type:'GET',
            url:url,
            data:data,
            success:function(res){
                $('#leavesDatas').html(res);
            }
        });
    }

    function checkAllowedLeaveDays(remainingLeaves,check)
    {
        var no_of_days = $("#no_of_days").val();

        if(check == 1)
        {

            if(no_of_days > remainingLeaves)
            {
                $("#no_of_days").val('');
                $("#warning_message").html('You cannot carry Leaves , More Then '+remainingLeaves+' In 2nd segment !');

            }
            else if(no_of_days < remainingLeaves)
            {
                $("#no_of_days").val('');
                $("#warning_message").html('You cannot carry Less Leaves , You Have to Carry '+remainingLeaves+' Leaves In 2nd segment !');

            }
            else
            {
                $("#warning_message").html('');
            }
        }
        else if(check == 0)
        {
            if(no_of_days > remainingLeaves)
            {
                $("#no_of_days").val('');
                $("#warning_message").html('You cannot carry Leaves , More Then '+remainingLeaves+' In 1st segment !');

            }
            else
            {
                $("#warning_message").html('');
            }
        }
    }

    function checkCasualLeave()
    {
        var no_of_days =  $("#no_of_days").val();

        if(no_of_days > 3)
        {
            $("#no_of_days").val('');
            $("#warning_message").html('You cannot carry More then 3 Casual Leaves !');
            $("#submitBtn").attr('disabled','disabled');
            $("#errorMesg").html('Please Remove All Errors First !');




        }
        else
        {
            $("#warning_message").html('');
            $("#errorMesg").html('');
            $("#submitBtn").removeAttr('disabled');
        }

    }

    function checkCasualLeavesDifference(leavesCount)
    {

        var from_date = $('#from_date').val();
        var to_date   = $("#to_date").val();
        var date1 = new Date(from_date);
        var date2 = new Date(to_date);

        var diffDays=calcBusinessDays(date1, date2);

        $("#no_of_days").val(diffDays+1);
        var no_of_days = $("#no_of_days").val();

        if(to_date < from_date){
            $("#warning_message").html('Please Correct Date !');
            $("#submitBtn").attr('disabled','disabled');
            $("#errorMesg").html('Please Remove All Errors First !');
            return false;
        }

        if(no_of_days <= leavesCount)
        {
            $("#warning_message").html('');
            $("#errorMesg").html('');
            $("#submitBtn").removeAttr('disabled');

            return true;
        }
        else
        {
            $("#warning_message").html('Please Correct Date Difference !');
            $("#submitBtn").attr('disabled','disabled');
            $("#errorMesg").html('Please Remove All Errors First !');

        }


    }
    function checkAnnualLeaveDays(leavesCount)
    {


        var no_of_days =  $("#no_of_days").val();

        if(no_of_days < 4  )
        {

            $("#warning_message").html('You cannot take less then 4 and More Then '+leavesCount+' Annual Leaves !');
            $("#submitBtn").attr('disabled','disabled');
            $("#errorMesg").html('Please Remove All Errors First !');

        }
        else if(no_of_days > leavesCount)
        {


            $("#warning_message").html('You cannot take less then 4 and More Then '+leavesCount+' Annual Leaves !');
            $("#submitBtn").attr('disabled','disabled');
            $("#errorMesg").html('Please Remove All Errors First !');
        }
        else
        {

            $("#warning_message").html('');
            $("#errorMesg").html('');
            $("#submitBtn").removeAttr('disabled');
            return true;
        }


    }


    function checkLeavesDifference(leavesCount)
    {

        var from_date = $('#from_date').val();
        var to_date   = $("#to_date").val();
        var date1 = new Date(from_date);
        var date2 = new Date(to_date);

        var diffDays=calcBusinessDays(date1, date2);

        $("#no_of_days").val(diffDays+1);
        var no_of_days = diffDays+1;

        if(to_date < from_date){
            $("#submitBtn").attr('disabled','disabled');
            $("#errorMesg").html('Please Remove All Errors First !');
            $("#warning_message").append('Please Enter Correct Date !');
            return false;
        }


        if(no_of_days > leavesCount)
        {
            $("#submitBtn").attr('disabled','disabled');
            $("#errorMesg").html('Please Remove All Errors First !');
            $("#warning_message").html('You cannot take More Than '+leavesCount+' Annual Leaves !');


        }
        else
        {

            $("#warning_message").html('');
            $("#errorMesg").html('');
            $("#submitBtn").removeAttr('disabled');
            return true;
        }


    }

</script>
