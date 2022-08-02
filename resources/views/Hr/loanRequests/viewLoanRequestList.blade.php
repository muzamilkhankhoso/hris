<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
//$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
//echo Auth::user()->company_id;
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
                    <div class="card-body" id="PrintLoanRequestList">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Loan Request List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintLoanRequestList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('LoanRequestList','','1')?>
                                @endif
                            </div>


                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-8"></div>
                            <div class="col-sm-4 text-right">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="emp_id_search" name="emp_id_search" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="<?= $m ?>">
                                </div>
                            </div>
                        </div>
                         <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LoanRequestList">
                                <thead>
                                <th class="text-center">S.No</th>
                                <th class="text-center">EMP ID</th>
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Department</th>
                                <th class="text-center">Month</th>
                                <th class="text-center">Year</th>
                                <th class="text-center">Loan Amount</th>
                                <th class="text-center">Remarks</th>
                                <th class="text-center">Approval Status</th>
                                <th class="text-center">Status</th>
                                <th class="text-center hidden-print">Action</th>
                                </thead>
                                <tbody>
                                <?php $counter=1;
                                foreach($loanRequest as $key => $y):
                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                $emp_data = Employee::select('emp_name','emp_sub_department_id')->where([['emp_id','=',$y->emp_id],['status', '!=', 2]])->first();

                                CommonHelper::reconnectMasterDatabase();
                                ?>
                                <?php
                                $monthName="";
                                if($y->month > 0){
                                    $monthName = date('M', mktime(0, 0, 0, $y->month=(int)$y->month, 10));
                                }
                                ?>
                                <tr>
                                    <td class="text-center">{{ $counter++ }}</td>
                                    <td class="text-center">{{ $y->emp_id }}</td>
                                    <td>{{ $emp_data->emp_name }}</td>
                                    <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$emp_data->emp_sub_department_id) }}</td>
                                    <td class="text-center">{{ $monthName }}</td>
                                    <td class="text-center">{{ $y->year }}</td>
                                    <td class="text-right">
                                        @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                            {{ number_format($y->loan_amount,0) }}
                                        @else
                                            ******
                                        @endif

                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if($y->loan_status == 1):
                                            echo "<span class='badge badge-pill badge-success text-white'>Paid</span>";
                                        else:
                                            echo "<span class='badge badge-pill badge-warning text-white'>Not Paid</span>";
                                        endif;
                                        ?>
                                    </td>
                                    <td class="text-center">{{ HrHelper::getApprovalStatusLabel($y->approval_status) }}</td>
                                    <td class="text-center">{{ HrHelper::getStatusLabel($y->status) }}</td>
                                    <td class="text-center hidden-print">
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle btn-sm btn-rounded" type="button" id="menu1" data-toggle="dropdown">
                                                <i data-feather="chevron-down"
                                                   class="svg-icon"></i></button>
                                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                @if(in_array('view', $operation_rights))
                                                    <li role="presentation" class="actionsLink">
                                                        <a class="delete-modal btn" onclick="showDetailModelFourParamerter('hdc/viewLoanRequestDetail','<?php echo $y->id;?>','View Loan Request Detail','<?php echo $m; ?>','hr/viewLoanRequestList')">
                                                            View
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(in_array('approve', $operation_rights))
                                                    @if ($y->approval_status != 2)
                                                        <li role="presentation" class="actionsLink">
                                                            <a class="edit-modal btn" onclick="approveAndRejectTableRecords('<?php echo $m; ?>','<?php echo $y->id;?>', '2', 'loan_request')">
                                                                Approve
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                                @if(in_array('reject', $operation_rights))
                                                    @if ($y->approval_status != 3)
                                                        <li role="presentation" class="actionsLink">
                                                            <a class="edit-modal btn" onclick="approveAndRejectTableRecords('<?php echo $m; ?>','<?php echo $y->id;?>', '3', 'loan_request')">
                                                                Reject
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                                @if(in_array('edit', $operation_rights))
                                                    <li role="presentation" class="actionsLink">
                                                        <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/editLoanRequestDetailForm','<?php echo $y->id;?>','Edit Loan Request Detail','<?php echo $m; ?>')">
                                                            Edit
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(in_array('repost', $operation_rights))
                                                    @if($y->status == 2)
                                                        <li role="presentation" class="actionsLink">
                                                            <a class="delete-modal btn"onclick="repostCompanyTableRecord('<?php echo $m ?>','<?php echo $y->id;?>','loan_request')">
                                                                Repost
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                                @if(in_array('delete', $operation_rights))
                                                    @if($y->status == 1)
                                                        <li role="presentation" class="actionsLink">
                                                            <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $y->id;?>','loan_request')">
                                                                Delete
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <?php endforeach;  ?>

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

