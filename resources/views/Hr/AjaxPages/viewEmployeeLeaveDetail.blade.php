<?php
$array[1] ='<span class="label label-warning">Pending</span>';
$array[2] ='<span class="label label-success">Approved</span>';
$array[3] ='<span class="label label-danger">Rejected</span>';

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\LeaveApplicationData;
$current_data = date('Y-m-d');
$data = '';

CommonHelper::companyDatabaseConnection(Input::get('company_id'));
//$countAnnualLeaves= LeaveApplicationData::select('*')->where([['emp_id','=',Input::get('emp_id')],['leave_type','=',Input::get('leaveType')]]);
$leave_policy_id = \App\Models\Employee::select('leaves_policy_id')->where([['emp_id','=',Input::get('emp_id')]])->value('leaves_policy_id');


CommonHelper::reconnectMasterDatabase();
$countAnnualLeaves = DB::table('leave_application_data')
    ->where([['leave_application.leave_policy_id','=',$leave_policy_id],['leave_application.emp_id','=',Input::get('emp_id')],['leave_application.leave_type','=',Input::get('leaveType')],['leave_application.view','=','yes']])
    ->join('leave_application', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
    ->select('leave_application_data.*', 'leave_application.approval_status');
	
	

//CommonHelper::reconnectMasterDatabase();

if(Input::get('leaveType') == 4):

?>
            <div class="row" style="background-color: gainsboro">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <h4>Maternity Leaves </h4>
                </div>
             </div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="row">
			<input type="hidden" class="form-control" value='<?php echo date("Y-m-d") ?>'>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<label> from </label>
				<input type="date" class="form-control requiredField" name="from_date" id="from_date"  value='<?php echo date("Y-m-d") ?>'>
				<span style="color:red;" id="maternity_date_error"></span>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<label> To </label>
				<input type="date" class="form-control requiredField" name="to_date" id="to_date">
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<label> No. of Days</label><input readonly type="number" disabled class="form-control requiredField" value='<?php echo Input::get("leavesCount") ?>' id="no_of_days" name="no_of_days">
				<span id="warning_message" style="color:red"></span>
			</div>
		</div>

	</div>
</div>

<div class="lineHeight">&nbsp;</div>
<?php

elseif(Input::get('leaveType') == 1):
?>
     <div class="row" style="background-color: gainsboro">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<h4 style="margin-top: 10px;">Full Day Leave  :
				<input checked type="radio" name="leave_day_type" id="leave_day_type" value="full_day_leave" onclick="leaves_day_type(this.value)">
			</h4>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<h4 style="margin-top: 10px;">Half Day Leave :
				<input disabled type="radio" name="leave_day_type" id="leave_day_type" value="half_day_leave" onclick="leaves_day_type(this.value)">
			</h4>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<!--<h4>Short Leave :
				<input disabled type="radio" name="leave_day_type" id="leave_day_type" value="short_leave" onclick="leaves_day_type(this.value)">
			</h4>-->
		</div>
	</div>

<?php


    ?>
<br>
<div class="row">
<!--<div class="alert-danger">&nbsp;&nbsp;You have to take Annual Leaves Maximum in 2 Segments ( Minimum Leaves 4 Day )</div>-->


		<input disabled type="hidden" class="form-control" value='<?php echo date("Y-m-d") ?>' >

	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<label> from </label><input type="date" class="form-control requiredField" name="from_date" id="from_date" value='<?php echo date("Y-m-d", strtotime("+29 days")) ?>'>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<label> To </label><input type="date" class="form-control requiredField" name="to_date" id="to_date" onchange="checkLeavesDifference('<?php echo Input::get("leavesCount") ?>')">
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<label> No. of Days</label><input type="number" disabled id="no_of_days" onchange="checkAnnualLeaveDays('<?php echo Input::get("leavesCount") ?>')" class="form-control requiredField"   name="no_of_days">
		<span id="warning_message" style="color:red"></span>
	</div>
</div>
<br>
<?php
elseif(Input::get('leaveType') == 2):
?>
   <div class="row" style="background-color: gainsboro">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<h4 style="margin-top: 10px;">Full Day Leave  :
				<input class="requiredField" type="radio" name="leave_day_type" id="leave_day_type" value="full_day_leave" onclick="leaves_day_type(this.value)">
			</h4>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<h4 style="margin-top: 10px;">Half Day Leave :
				<input class="requiredField" type="radio" name="leave_day_type" id="leave_day_type" value="half_day_leave" onclick="leaves_day_type(this.value)">
			</h4>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<!--<h4>Short Leave :
				<input class="requiredField" type="radio" name="leave_day_type" id="leave_day_type" value="short_leave" onclick="leaves_day_type(this.value)">
			</h4>-->
		</div>
	</div>
<div class="lineHeight">&nbsp;</div>
<?php 
elseif(Input::get('leaveType') == 3):
?>
	<div class="row" style="background-color: gainsboro">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<h4 style="margin-top: 10px;">Full Day Leave  :
				<input class="requiredField" type="radio" name="leave_day_type" id="leave_day_type" value="full_day_leave" onclick="leaves_day_type(this.value)">
			</h4>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<h4 style="margin-top: 10px;">Half Day Leave :
				<input class="requiredField" type="radio" name="leave_day_type" id="leave_day_type" value="half_day_leave" onclick="leaves_day_type(this.value)">
			</h4>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<!--<h4>Short Leave :
				<input class="requiredField" type="radio" name="leave_day_type" id="leave_day_type" value="short_leave" onclick="leaves_day_type(this.value)">
			</h4>-->
		</div>
</div>
<div class="lineHeight">&nbsp;</div>
<?php
endif;


?>