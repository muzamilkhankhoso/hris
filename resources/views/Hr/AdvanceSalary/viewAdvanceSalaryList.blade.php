<?php

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
//$parentCode = $_GET['parentCode'];

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
                    <div class="card-body" id="">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Advance Salary List</h4>
                            </div>

                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintAdvancedSalaryList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('AdvancedSalayList','','1')?>
                                @endif
                            </div>


                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-8"></div>
                            <div class="col-sm-4">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="emp_id_search" name="emp_id_search" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="<?= $m ?>">
                                </div>
                            </div>
                        </div>

                        <span id="PrintAdvancedSalaryList">


                        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m')); ?>
                            <div class="table-responsive">
                            <table class="table table-sm mb-0 table-striped table-bordered table-hover" id="AdvancedSalayList">
                                <thead>
                                <th class="text-center">S.No</th>
                                <th class="text-center">EMP ID</th>
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Amount Needed</th>
                                <th class="text-center">Salary Need On</th>
                                <th class="text-center">Deduction Month/year</th>
                                <th class="text-center">Approval Status</th>
                                <th class="text-center">Status</th>
                                <th class="text-center hidden-print">Action</th>
                                </thead>
                                <tbody>
                                <?php $counter = 1;?>
                                @foreach($advance_salary as $key => $y)
                                    <tr>
                                        <td class="text-center">{{ $counter++ }}</td>
                                        <td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_id',$y->emp_id,'emp_id') }}</td>
                                        <td>{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$y->emp_id,'emp_id') }}</td>
                                        <td class="text-right">{{ number_format($y->advance_salary_amount,0) }}</td>
                                        <td class="text-center">{{ HrHelper::date_format($y->salary_needed_on) }}</td>
                                        <td class="text-center">{{ $y->deduction_month.' / '.$y->deduction_year }}</td>
                                        <td class="text-center">{{HrHelper::getApprovalStatusLabel($y->approval_status)}}</td>
                                        <td class="text-center">{{HrHelper::getStatusLabel($y->status)}}</td>
                                        <td class="text-center hidden-print">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle btn-rounded btn-sm" type="button" id="menu1" data-toggle="dropdown"><i data-feather="chevron-down"
                                                                                                                                                                      class="svg-icon"></i></button>
                                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                    @if(in_array('approve', $operation_rights))
                                                        @if ($y->approval_status != 2)
                                                            <li role="presentation" class="actionsLink">
                                                                <a class="edit-modal btn" onclick="approveAndRejectTableRecords('<?php echo $m; ?>','<?php echo $y->id;?>', 2, 'advance_salary')">
                                                                    Approve
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                    @if(in_array('reject', $operation_rights))
                                                        @if ($y->approval_status != 3)
                                                            <li role="presentation" class="actionsLink">
                                                                <a class="edit-modal btn" onclick="approveAndRejectTableRecords('<?php echo $m; ?>','<?php echo $y->id;?>', '3', 'advance_salary')">
                                                                    Reject
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                    @if(in_array('view', $operation_rights))
                                                        <li role="presentation" class="actionsLink">
                                                            <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hdc/viewAdvanceSalaryDetail','<?php echo $y->id;?>','View Advance Salary Detail','<?php echo $m; ?>')">
                                                                View
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if(in_array('edit', $operation_rights))
                                                        <li role="presentation" class="actionsLink">
                                                            <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/editAdvanceSalaryDetailForm','<?php echo $y->id;?>','Edit Advance Salary Detail','<?php echo $m; ?>')">
                                                                Edit
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if(in_array('repost', $operation_rights))
                                                        @if ($y->status == 2)
                                                            <li role="presentation" class="actionsLink">
                                                                <a class="delete-modal btn" onclick="repostOneTableRecords('<?php echo $m ?>','<?php echo $y->id ?>','advance_salary','approval_status')">
                                                                    Repost
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete', $operation_rights))
                                                        @if ($y->status == 1)
                                                            <li role="presentation" class="actionsLink">
                                                                <a class="delete-modal btn" onclick="deleteAdvanceSalaryWithPaySlip('<?php echo $m ?>','<?php echo $y->id ?>','advance_salary')">
                                                                    Delete
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                      </span>

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

