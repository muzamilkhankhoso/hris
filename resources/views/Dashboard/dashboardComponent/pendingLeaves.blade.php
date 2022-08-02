<?php

use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\Employee;
use App\Models\SubDepartment;
use App\Models\EmployeeProjects;
?>
<div class="tab-pane" id="pendingLeaves">
    <?php
    $line_manager_employees = [];
    CommonHelper::companyDatabaseConnection(Input::get('m'));
    $line_manager_emp = Employee::select('emp_id')->where([['reporting_manager','=',$emp_id],['status','=',1]])->get();
    foreach($line_manager_emp as $value){
        $line_manager_employees[] = $value->emp_id;
    }
    CommonHelper::reconnectMasterDatabase();

    $leave_application_request_list = DB::table('leave_application')
        ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
        ->select('leave_application.*')
        ->whereIn('leave_application.emp_id',$line_manager_employees)
        ->where('leave_application.view','=','yes')
        ->orderBy('leave_application.approval_status')
        ->get();
    $leave_application_list = DB::table('leave_application')
        ->join('leave_application_data', 'leave_application_data.leave_application_id', '=', 'leave_application.id')
        ->select('leave_application.*')
        ->where([['leave_application.emp_id', '=',Auth::user()->emp_id]])
        ->get();
    $m = Input::get('m');
    $SubDepartment = SubDepartment::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
    $employeeProjects = EmployeeProjects::where([['company_id', '=', Input::get('m')], ['status', '=', '1']])->orderBy('id')->get();
    $companies = DB::table('company')->where('status',1)->get();
    ?>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <span class="subHeadingLabelClass">View Leave Application Request Lists</span>
                                        </div>
                                    </div>
                                </div>
                                <?php $leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];?>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="panel">
                                    <div class="panel-body" id="PrintLeaveApplicationRequestList">

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive LeavesData">

                                                    <table class="table table-bordered sf-table-list" id="LeaveApplicationRequestList">
                                                        <thead>
                                                        <th class="text-center">S No.</th>
                                                        <th class="text-center">Emp ID</th>
                                                        <th class="text-center">Emp Name</th>
                                                        <th class="text-center">Leave Type</th>
                                                        <th class="text-center">Day Type</th>
                                                        <th class="text-center">Approval Status(HR)</th>
                                                        <th class="text-center">Approval Status(GM)</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center hidden-print">Action</th>

                                                        </thead>
                                                        <tbody>
                                                        <?php if(count($leave_application_request_list) != '0'){ ?>
                                                        @foreach($leave_application_request_list as $value)
                                                            <?php
                                                            CommonHelper::companyDatabaseConnection($m);
                                                            $emp_data =  Employee::where([['emp_id','=',$value->emp_id],['status','=',1]]);
                                                            CommonHelper::reconnectMasterDatabase();
                                                            ?>
                                                            <tr>
                                                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $countersss++ }}</span></td>
                                                                <td class="text-center"><?php echo $emp_data->value('emp_id') ?></td>
                                                                <td class="text-center"><?php echo $emp_data->value('emp_name') ?></td>
                                                                <td class="text-center" style="color:green">{{ $leave_type_name = HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$value->leave_type)}}</td>
                                                                <td class="text-center" style="color:green">{{ $leave_day_type[$value->leave_day_type] }}</td>
                                                                <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
                                                                <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status_lm) }}</td>
                                                                <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                                                <td class="text-center hidden-print">
                                                                    <button class="btn-info btn-xs" onclick="showDetailModelTwoParamerter('hr/editLeaveApplicationDetailForm','<?php echo $value->id."|".$value->emp_id;?>','Edit Leave Application Detail','<?=$m?>')">
                                                                        <span class="glyphicon glyphicon-edit"></span>
                                                                    </button>
                                                                    <button onclick="LeaveApplicationRequestDetail('<?=$value->id?>','<?=$value->leave_day_type?>','<?=$leave_type_name?>','<?=$value->emp_id?>','<?=$m?>')" class="btn btn-xs btn-primary" type="button">
                                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                                    </button>
                                                                    @if ($value->status == 2)
                                                                        <button data-toggle="tooltip" data-placement="right" title="Repost" onclick="RepostLeaveApplicationData('<?= $m ?>','<?=$value->id?>')" class="btn btn-xs btn-info" type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                                                    @else
                                                                        <button data-toggle="tooltip" data-placement="right" title="Delete" onclick="deleteLeaveApplicationData('<?= $m ?>','<?=$value->id?>')" class="btn btn-xs btn-danger" type="button"><span class="glyphicon glyphicon-remove"></span></button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="10">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="collapseExample<?=$value->id?>">
                                                                        <div class="card card-body" id="leave_area<?=$value->id?>"></div>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                        @endforeach
                                                        <?php } else { ?>
                                                        <tr>
                                                            <td colspan="9" class="text-center text-danger"><strong>No Reord Found</strong></td>
                                                        </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <span class="subHeadingLabelClass">View User Leave Application List</span>
                                </div>

                            </div>
                        </div>
                        <?php $leave_type = [4 => 'Maternity Leaves',1 => 'Annual/Earned Leave',2 => 'Sick Leave',3 => 'Casual'];?>
                        <?php $leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];?>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="panel">
                            <div class="panel-body" id="PrintLeaveApplicationList">

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <tr class="table-responsive">
                                            <table class="table table-bordered sf-table-list" id="LeaveApplicationList">
                                                <thead>
                                                <tr>
                                                    <th>S No.</th>
                                                    <th>Leave Type</th>
                                                    <th>Day Type</th>
                                                    <th class="text-center">Approval Status</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Created on</th>
                                                    <th class="text-center hidden-print">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $counter = 1;?>
                                                <?php if(count($leave_application_list) != '0'){ ?>
                                                @foreach($leave_application_list as $value)
                                                    <?php
                                                    if($value->approval_status == 2 && $value->approval_status_lm == 2){
                                                        $approval_status_final = 2;
                                                    }
                                                    else if($value->approval_status == 3 && $value->approval_status_lm == 3){
                                                        $approval_status_final = 3;
                                                    }
                                                    else if($value->approval_status == 1 && $value->approval_status_lm == 1){
                                                        $approval_status_final = 1;
                                                    }
                                                    else{
                                                        $approval_status_final = 1;
                                                    }

                                                    ?>
                                                    <tr>
                                                        <td class="">
                                                            <span class="badge badge-pill badge-secondary">{{ $counter++ }}</span>
                                                        </td>
                                                        <td class="">
                                                            <span style="color:green">{{ $leave_type[$value->leave_type] }}</span>
                                                        </td>
                                                        <td class="">
                                                            <span style="color:green">{{ $leave_day_type[$value->leave_day_type] }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            {{ HrHelper::getApprovalStatusLabel($approval_status_final) }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ HrHelper::getStatusLabel($value->status) }}
                                                        </td>
                                                        <td class="text-center" style="text-decoration: underline">
                                                            {{ HrHelper::date_format($value->date) }}
                                                        </td>
                                                        <td class="text-center hidden-print">
                                                            <button onclick="showDetailModelTwoParamerter('hr/editLeaveApplicationDetailForm','<?php echo $value->id."|".$value->emp_id;?>','Edit Leave Application Detail','<?php echo $m; ?>')" class="btn btn-xs btn-info" type="button">
                                                                <span class="glyphicon glyphicon-pencil"></span>
                                                            </button>
                                                            <button onclick="getLeavesData('<?=$value->id?>','<?=$value->leave_day_type?>','<?=$value->leave_type?>')" class="btn btn-xs btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample<?=$value->id?>" aria-expanded="false" aria-controls="collapseExample">
                                                                <span class="glyphicon glyphicon-eye-open"></span>
                                                            </button>
                                                            <button onclick="deleteLeaveApplicationData('<?= $m ?>','<?=$value->id?>')" class="btn btn-xs btn-danger " type="button"><span class="glyphicon glyphicon-remove"></span></button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="7">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="collapseExample<?=$value->id?>">
                                                                <div class="card card-body" id="leave_area<?=$value->id?>"></div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                @endforeach
                                                <?php }else { ?>
                                                <tr>
                                                    <td colspan="7" class="text-center text-danger">
                                                        <strong>No Record Found</strong>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>