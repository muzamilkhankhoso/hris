<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

?>

@extends('layouts.default')
@section('content')


    <div class="page-wrapper">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-12 text-right">

            </div>
        </div>

        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintEmployeeList">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Leaves Policy List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintLeavesPolicyList','','1');?>
                                <?php echo CommonHelper::displayExportButton('LeavesPolicyList','','1')?>

                            </div>

                        </div>
                        <hr>

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

                        <div class="table-responsive" id="PrintLeavesPolicyList">
                            <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LeavesPolicyList">
                                <thead>
                                <th class="text-center">Id</th>
                                <th class="text-center">Leave Policy Name</th>
                                <th class="text-center">Policy Month/Year From</th>
                                <th class="text-center">Policy Month/Year Till</th>
                                <th class="text-center">Status</th>
                                <th class="text-center hidden-print">Action</th>
                                </thead>
                                <tbody>
                                <?php $counter = 1;?>
                                @foreach($leavesPolicy as $key => $value)
                                    <tr>
                                        <td class="text-center"><?php echo $value->id;?></td>
                                        <td class="text-center"><?php echo $value->leaves_policy_name;?></td>
                                        <td class="text-center"><?php echo HrHelper::date_format($value->policy_date_from);?></td>
                                        <td class="text-center"><?php echo HrHelper::date_format($value->policy_date_till);?></td>
                                        <td class="text-center"><?php echo HrHelper::getStatusLabel($value->status); ?></td>
                                        <td class="text-center hidden-print">
                                            <div class="dropdown">
                                                <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                                                    <i data-feather="chevron-down"
                                                       class="svg-icon"></i></button>
                                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                    <li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hdc/viewLeavePolicyDetail','<?php echo $value->id; ?>','View Leaves Policy Detail ','<?php echo $m;  ?>')">
                                                        <a class="delete-modal btn">
                                                            View
                                                        </a>
                                                    </li>
                                                    <li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hr/editLeavesPolicyDetailForm','<?php echo $value->id ?>','Leaves Policy Edit Detail Form','<?php echo $m?>')">
                                                        <a  class="delete-modal btn">
                                                            Edit
                                                        </a>
                                                    </li>
                                                    @if($value->status == 2)
                                                    <li role="presentation" class="actionsLink" onclick="repostCompanyTableRecord('<?php echo $m ?>','<?php echo $value->id ?>','leaves_policy')">
                                                        <a class="delete-modal btn">
                                                            Refresh
                                                        </a>
                                                    </li>
                                                    @else
                                                        <li role="presentation" class="actionsLink" onclick="deleteLeavesDataPolicyRows('/hdc/deleteLeavesDataPolicyRows','<?php echo $m; ?>','<?php echo $value->id ?>')">
                                                            <a class="delete-modal btn">
                                                                Delete
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
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

