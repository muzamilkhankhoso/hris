<?php

use App\Helpers\HrHelper;
use App\Models\TransferedLeaves;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$data='';
$leaves_data = '';
$btn = '';
$remainingLeaves = '';
$count = 1; $count_leaves =0; $leaves_loop =0;
$no_of_leaves = 0;
$countUsedLeavess=0;
$countRemainingLeaves=0;
$transferedleaveTotal = 0;


?>
<div class="row"></div>
<div class="row">


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center ClsHide" style="border-bottom: double;">
        <?php
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $empDesgnation = Employee::select('designation_id')->where([['emp_id','=',Auth::user()->emp_id],['status','=',1]])->value('designation_id');

        //    $hod = Employee::select('emp_hod_department')->where([['emr_no','=',Auth::user()->emr_no],['status','=',1]])->value('emp_hod_department');
        CommonHelper::reconnectMasterDatabase();
        ?>

        @if(Auth::user()->acc_type == 'client')
            @if ($leave_application_data->approval_status != 3 && $leave_application_data->approval_status != 2)
                <button class="btn btn-sm btn-success" onclick="approveAndRejectLeaveApplication('<?=Input::get('id')?>',2,'<?=$leave_day_type?>')">Approve(HR)</button>

                <button class="btn btn-sm btn-danger" onclick="approveAndRejectLeaveApplication('<?=Input::get('id')?>',3,'<?=$leave_day_type?>')">Reject(HR)</button>
            @endif
        @endif

        @if(Auth::user()->acc_type == 'user' && $leave_application_data->emp_id != Auth::user()->emp_id)
            @if ($leave_application_data->approval_status_lm != 3 && $leave_application_data->approval_status_lm != 2)
                <button class="btn btn-sm btn-success" onclick="approveAndRejectLeaveApplication2('<?=Input::get('id')?>',2,'<?=$leave_day_type?>')">Approve(LM)</button>

                <button class="btn btn-sm btn-danger" onclick="approveAndRejectLeaveApplication2('<?=Input::get('id')?>',3,'<?=$leave_day_type?>')">Reject(LM)</button>
            @endif
        @endif

        @if(Auth::user()->acc_type == 'client')
            @if ($leave_application_data->approval_status == 2 && $leave_application_data->status == 1)
                <button class="btn btn-sm btn-danger" onclick="approveAndRejectLeaveApplication('<?=Input::get('id')?>',3,'<?=$leave_day_type?>')">Reject(HR)</button>
            @endif
        @endif


        @if(Auth::user()->acc_type == 'user')
            @if ($leave_application_data->approval_status_lm == 2 && $leave_application_data->status == 1)
                <button class="btn btn-sm btn-danger" onclick="approveAndRejectLeaveApplication2('<?=Input::get('id')?>',3,'<?=$leave_day_type?>')">Reject(LM)</button>
            @endif
        @endif

        @if($empDesgnation == 6)
            @if ($leave_application_data->approval_status == 2 && $leave_application_data->status == 1)
                <button class="btn btn-sm btn-danger" onclick="approveAndRejectLeaveApplication('<?=Input::get('id')?>',3,'<?=$leave_day_type?>')">Reject</button>
            @endif
        @endif
        @if($empDesgnation == 1)
            @if ($leave_application_data->approval_status_lm == 2 && $leave_application_data->status == 1)
                <button class="btn btn-sm btn-danger" onclick="approveAndRejectLeaveApplication2('<?=Input::get('id')?>',3,'<?=$leave_day_type?>')">Reject</button>
            @endif
        @endif
        @if(Auth::user()->acc_type == 'client')
            @if ($leave_application_data->approval_status == 3 && $leave_application_data->status == 1)
                <button class="btn btn-sm btn-success" onclick="approveAndRejectLeaveApplication('<?=Input::get('id')?>',2,'<?=$leave_day_type?>')">Approve(HR)</button>
            @endif
        @endif

        @if(Auth::user()->acc_type == 'user')
            @if ($leave_application_data->approval_status_lm == 3 && $leave_application_data->status == 1)
                <button class="btn btn-sm btn-success" onclick="approveAndRejectLeaveApplication2('<?=Input::get('id')?>',2,'<?=$leave_day_type?>')">Approve(LM)</button>
            @endif
        @endif

        @if($empDesgnation == 6)
            @if ($leave_application_data->approval_status == 3 && $leave_application_data->status == 1)
                <button class="btn btn-sm btn-success" onclick="approveAndRejectLeaveApplication('<?=Input::get('id')?>',2,'<?=$leave_day_type?>')">Approve</button>
            @endif
        @endif

        @if($empDesgnation == 1)
            @if ($leave_application_data->approval_status_lm == 3 && $leave_application_data->status == 1)
                <button class="btn btn-sm btn-success" onclick="approveAndRejectLeaveApplication2('<?=Input::get('id')?>',2,'<?=$leave_day_type?>')">Approve</button>
            @endif
        @endif


        <!-- @if ($leave_application_data->status == 2)
            <button class="btn btn-sm btn-info" onclick="RepostLeaveApplicationData('<?=Input::get('m')?>','<?=Input::get('id')?>')">Repost</button>
        @else
            <button class="btn btn-sm btn-danger" onclick="deleteLeaveApplicationData('<?=Input::get('m')?>','<?=Input::get('id')?>')">Delete</button>
        @endif -->
    </div>
</div>

<div class="row"></div>
<div class="row">
    <table class="table table-sm mb-0 table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th style="background-color: #6a75e9;padding: 2px;">
                <div class="text-center" style="margin-top: 7px;">
                    <span style="color:white;">LEAVES BALANCE</span>
                </div>
            </th>
        </tr>
        </thead>
    </table>
</div>
<div class="row">
    <table class="table table-sm mb-0 table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th class="text-center">S No#</th>
            <th class="text-center">Leaves Name</th>
            <th class="text-center">No of leaves </th>
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
                <td class="text-center"><b>{{ $count }}</b></td>
                <td class="text-center"><b>{{ HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$val->leave_type_id )}}</b></td>
                <td class="text-center" style="color: green;">
                    <b><?php

                        $TransferedLeaves = TransferedLeaves::where([['emp_id','=',$emp_data->emp_id],['leaves_policy_id','=',$emp_data->leaves_policy_id],['status','=','1']]);

                        if($val->leave_type_id == 1):
                            $transferedleaveTotal+= $TransferedLeaves->value('annual_leaves');
                            echo $val->no_of_leaves;
                        elseif($val->leave_type_id == 3):
                            $transferedleaveTotal+= $TransferedLeaves->value('casual_leaves');
                            echo $val->no_of_leaves;
                        else:
                            echo $val->no_of_leaves;
                        endif;
                        ?>
                    </b>
                </td>
                <td class="text-center">
                    <?php
                    if($val->leave_type_id == 1):
                        $getUsedAnnualLeaves =DB::table('leave_application_data')
                            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                            ->where([['leave_application_data.leave_policy_id','=',$emp_data->leaves_policy_id],
                                ['leave_application.emp_id','=',$emp_data->emp_id],['leave_application.leave_type','=',$val->leave_type_id ],
                                ['leave_application.status', '=', '1'],
                                ['leave_application.approval_status_lm', '=', '2']])
                            ->orWhere(function ($query) use($emp_data,$val) {
                                $query->where('leave_application.emp_id', '=', $emp_data->emp_id)
                                    ->where('leave_application.status', '=', '1')
                                    ->where('leave_application.leave_type', '=', $val->leave_type_id)
                                    ->where('leave_application.approval_status', '=', '2');
                            })
                            ->sum('no_of_days');
                        echo $getUsedAnnualLeaves;
                        $countUsedLeavess+= $getUsedAnnualLeaves;

                    elseif($val->leave_type_id == 3):
                        $getUsedCasualLeaves =DB::table('leave_application_data')
                            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                            ->where([['leave_application_data.leave_policy_id','=',$emp_data->leaves_policy_id],
                                ['leave_application.emp_id','=',$emp_data->emp_id],['leave_application.leave_type','=',$val->leave_type_id ],
                                ['leave_application.status', '=', '1'],
                                ['leave_application.approval_status_lm', '=', '2']])
                            ->orWhere(function ($query) use($emp_data,$val) {
                                $query->where('leave_application.emp_id', '=', $emp_data->emp_id)
                                    ->where('leave_application.status', '=', '1')
                                    ->where('leave_application.leave_type', '=', $val->leave_type_id)
                                    ->where('leave_application.approval_status', '=', '2');
                            })
                            ->sum('no_of_days');
                        echo $getUsedCasualLeaves;
                        $countUsedLeavess+= $getUsedCasualLeaves;

                    else:
                        echo $getUsedSickLeaves =DB::table('leave_application_data')
                            ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                            ->where([['leave_application_data.leave_policy_id','=',$emp_data->leaves_policy_id],
                                ['leave_application.emp_id','=',$emp_data->emp_id],['leave_application.leave_type','=',$val->leave_type_id ],
                                ['leave_application.status', '=', '1'],
                                ['leave_application.approval_status_lm', '=', '2']])
                            ->orWhere(function ($query) use($emp_data,$val) {
                                $query->where('leave_application.emp_id', '=', $emp_data->emp_id)
                                    ->where('leave_application.status', '=', '1')
                                    ->where('leave_application.leave_type', '=', $val->leave_type_id)
                                    ->where('leave_application.approval_status', '=', '2');
                            })
                            ->sum('no_of_days');
                        $countUsedLeavess+= $getUsedSickLeaves;
                    endif;
                    CommonHelper::reconnectMasterDatabase();
                    ?>
                </td>
                <td class="text-center">
                    <?php
                    if($val->leave_type_id == 1):
                        $countRemainingLeaves+= $val->no_of_leaves -$getUsedAnnualLeaves;
                        echo $val->no_of_leaves - $getUsedAnnualLeaves;

                    elseif($val->leave_type_id == 3):
                        $countRemainingLeaves+= $val->no_of_leaves -$getUsedCasualLeaves;
                        echo $val->no_of_leaves-$getUsedCasualLeaves;
                    else:
                        $countRemainingLeaves+= $val->no_of_leaves-$getUsedSickLeaves;
                        echo $val->no_of_leaves-$getUsedSickLeaves;
                    endif;

                    ?>
                </td>
            </tr>
        @endforeach

        </tbody>
        <tfoot>
        <tr>
            <th class="text-right"  style="color: #fff;background-color: #6a75e9;" colspan="2"><b>Total</b></th>
            <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #6a75e9;"><b>{{ $count_leaves+$transferedleaveTotal }}</b></th>
            <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #6a75e9; "><?php print_r($countUsedLeavess)?></th>
            <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #6a75e9; "><?=$countRemainingLeaves?></th>


        </tr>
        </tfoot>
    </table>
</div>

<?php if($leave_day_type == 1): ?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="table-responsive" style="min-height:170px;">
            <table class="table table-sm mb-0 table-bordered table-hover">
                <thead>
                <th>Employee Name</th>
                <td><?=Hrhelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name','emp_id',$leave_application_data->emp_id)?> </td>
                </thead>
                <thead>
                <th>Leave Type</th>
                <td><?=$leave_type_name?></td>
                </thead>
                <thead>
                <th>Day Type</th>
                <td><?=$leave_day_type_label?></td>
                </thead>
                <thead>
                <th>No.of Days</th>
                <td><?=$leave_application_data->no_of_days?></td>
                </thead>
            </table>
        </div>
        <?php 
        
        ?>
        <div class="table-responsive" style="min-height:170px;">
            <table class="table table-sm mb-0 table-bordered table-hover">
                <thead>
                <th>Employee Designation</th>
                <td><?=$designation_name?></td>
                </thead>
                <thead>
                <th>Leave From </th>
                <td><?= date("d-M-Y", strtotime($leave_application_data->from_date)); ?></td>
                </thead>
                <thead>
                <th>Leave Till </th>
                <td><?=  date("d-M-Y", strtotime($leave_application_data->to_date)); ?></td>
                </thead>
                <thead>
                <th>Created On</th>
                <td><?= date("d-M-Y", strtotime($leave_application_data->date)); ?></td>
                </thead>
                <thead>
                <th>Approval Status(HR)</th>
                <td><?=$approval_status?></td>
                </thead>
                <thead>
                <th>Approval Status(LM)</th>
                <td><?=$approval_status_lm?></td>
                </thead>
                <thead>
            </table>
        </div>
        <br>
        <label class="text-dark">Reason :</label><br>
        <?=$leave_application_data->reason?>
        <br><label class="text-dark">Address While On Leave :</label><br>
        <?=$leave_application_data->leave_address?>
    </div>
</div>

<?php elseif($leave_day_type == 2): ?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="table-responsive">
            <table class="table table-sm mb-0 table-bordered table-hover">
                <thead>
                <th>Employee Name</th>
                <td><?=Hrhelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name','emp_id',$leave_application_data->emp_id)?></td>
                </thead>
                <thead>
                <th>Leave Type</th>
                <td><?=$leave_type_name?></td>
                </thead>
                <thead>
                <th>Day Type</th>
                <td><?=$leave_day_type_label?></td>
                </thead>
                <thead>
                <th>First / Second Half</th>
                <td style="color:green;"><?=ucfirst($leave_application_data->first_second_half)?></td>
                </thead>
            </table>

        </div>

        <div class="table-responsive">
            <table class="table table-sm mb-0 table-bordered  table-hover">
                <thead>
                <th>Employee Designation</th>
                <td><?=$designation_name?></td>
                </thead>
                <thead>
                <th>Leave Date </th>
                <td><?=$leave_application_data->first_second_half_date?></td>
                </thead>
                <thead>
                <th>Created On</th>
                <td><?=$leave_application_data->date?></td>
                </thead>
                <thead>
                <th>Approval Status(HR)</th>
                <td><?=$approval_status?></td>
                </thead>
                <thead>
                <th>Approval Status(LM)</th>
                <td><?=$approval_status_lm?></td>
                </thead>
                <thead>
                <th>-</th>
                <td>-</td>
                </thead>
            </table>
        </div>

        <label>Reason :</label><br>
        <?=$leave_application_data->reason?>
        <br><label>Address While On Leave :</label><br>
        <?=$leave_application_data->leave_address?>
    </div>
</div>

<?php else:  ?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="table-responsive">
            <table class="table table-sm mb-0 table-bordered  table-hover">
                <thead>
                <th>Employee Name</th>
                <td><?=Hrhelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name','emp_id',$leave_application_data->emp_id)?></td>
                </thead>
                <thead>
                <th>Leave Type</th>
                <td><?=$leave_type_name?></td>
                </thead>
                <thead>
                <th>Day Type</th>
                <td><?=$leave_day_type_label?></td>
                </thead>
                <thead>
                <th>Created On</th>
                <td><?=$leave_application_data->date?></td>
                </thead>

            </table>
        </div>


        <div class="table-responsive">
            <table class="table table-sm mb-0 table-bordered  table-hover">
                <thead>
                <th>Employee Designation</th>
                <td><?=$designation_name?></td>
                </thead>
                <thead>
                <th>Leave Time From </th>
                <td><?=date('H : A', strtotime($leave_application_data->short_leave_time_from))?></td>
                </thead>
                <thead>
                <th>Leave Time Till </th>
                <td><?=date('H : A', strtotime($leave_application_data->short_leave_time_to))?></td>
                </thead>
                <thead>
                <th>Leave Date</th>
                <td><?=$leave_application_data->short_leave_date?></td>
                </thead>
                <thead>
                <th>Approval Status(HR)</th>
                <td><?=$approval_status?></td>
                <th>Approval Status(LM)</th>
                <td><?=$approval_status_lm?></td>
                </thead>
            </table>
        </div>

        <label>Reason :</label><br>
        <?=$leave_application_data->reason?>
        <br><label>Address While On Leave :</label><br>
        <?=$leave_application_data->leave_address?>
    </div>
</div>

<?php endif; ?>
