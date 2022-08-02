<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
$approval_status_final=0;
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintLeaveApplicationList">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Leave Application List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintLeaveApplicationList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('LeaveApplicationList','','1')?>
                                @endif

                            </div>

                        </div>
                        <hr>
                        <?php $leave_type = [4 => 'Maternity Leaves',1 => 'Annual/Earned Leave',2 => 'Sick Leave',3 => 'Casual'];?>
                        <?php $leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];?>
                        <div class="row">
                            <div class="col-sm-8"></div>
                            <div class="col-sm-4 text-right">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="emp_id_search" name="emp_id1" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="<?= $m ?>">
                                </div>
                            </div>
                        </div>

                        <span id="employee-list">
                                      <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <div class="table-responsive">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LeaveApplicationList">
                                            <thead>
                                            <tr>
                                                <th>S No.</th>
                                                <th>Leave Type</th>
                                                <th>Day Type</th>
                                                <th class="text-center">From</th>
                                                <th class="text-center">To</th>
                                                <th class="text-center">No of days</th>
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
                                                $startDate = new DateTime($value->from_date);
                                                $endDate = new DateTime($value->to_date);
                                                $value->to_date;
                                                $difference = $endDate->diff($startDate);
                                                if($value->leave_day_type=='1'){
                                                    $diff=$difference->format("%a")+1;
                                                }else{
                                                    $diff=0.5;
                                                }
                                                if($value->approval_status == 2 || $value->approval_status_lm == 2){
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
                                                    <td class="text-center">{{ $value->leave_day_type=='1'? HrHelper::date_format($value->from_date) : HrHelper::date_format($value->first_second_half_date)  }}</td>
                                                    <td class="text-center">{{ $value->leave_day_type=='1'? HrHelper::date_format($value->to_date)  : HrHelper::date_format($value->first_second_half_date)  }}</td>
                                                    <td class="text-center">{{ $diff }}</td>
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
                                                        @if($approval_status_final != 2)
                                                            <button onclick="showDetailModelFourParamerter('hr/editLeaveApplicationDetailForm','<?php echo $value->id."|".$value->emp_id;?>','Edit Leave Application Detail','<?php echo $m; ?>')" class="btn btn-sm btn-info" type="button">
                                                            <span class="fas fa-edit"></span>
                                                        </button>
                                                        @endif
                                                        <button onclick="getLeavesData('<?=$value->id?>','<?=$value->leave_day_type?>','<?=$value->leave_type?>')" class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample<?=$value->id?>" aria-expanded="false" aria-controls="collapseExample">
                                                            <span class="fas fa-eye"></span>
                                                        </button>
                                                        @if($approval_status_final != 2)
                                                            @if($value->status==2)
                                                                <button onclick="RepostLeaveApplicationData('<?= $m ?>','<?=$value->id?>')" class="btn btn-sm btn-warning "  type="button"><span style="color:white;" class="fas fa-retweet" ></span></button>
                                                            @else
                                                                <button onclick="deleteLeaveApplicationData('<?= $m ?>','<?=$value->id?>')" class="btn btn-sm btn-danger " type="button"><span class="fas fa-trash"></span></button>
                                                            @endif

                                                        @endif
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
												 <td colspan="10" class="text-center text-danger">
													<strong>No Record Found</strong>
												 </td>
												</tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                </div>
                                </span>
                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right sidebar -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->
        <!-- ============================================================== -->
        <!-- End Right sidebart -->
        <!-- ============================================================== -->
    </div>










@endsection

