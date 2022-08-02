<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//	$m = $_GET['m'];
//}else{
//	$m = Auth::user()->company_id;
//}
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = $_GET['m'];

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
                    <div class="card-body" id="PrintEmployeeList">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Loan Type List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintLeaveTypeList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('LeaveTypeList','','1')?>
                                @endif
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

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                <div class="table-responsive" id="PrintLeaveTypeList">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LeaveTypeList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Loan Type Name</th>
                                        <th class="text-center">Created By</th>
                                        <th class="text-center">Action</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 1;?>
                                        @foreach($LoanTypes as $key => $y)
                                            <tr>
                                                <td class="text-center"><?php echo $counter++;?></td>
                                                <td><?php echo $y->loan_type_name;?></td>
                                                <td><?php echo $y->username;?></td>
                                                <td class="text-center">
                                                    @if(in_array('edit', $operation_rights))
                                                        <button class="edit-modal btn btn-sm btn-info" onclick="showMasterTableEditModel('hr/editLoanTypeForm','<?php echo $y->id ?>','Loan Type Edit Detail Form','<?php echo $m?>')">
                                                            <span class="fas fa-edit"></span>
                                                        </button>
                                                    @endif
                                                    @if(in_array('delete', $operation_rights))
                                                        <button class="delete-modal btn btn-sm btn-danger" onclick="deleteRowMasterTable('<?php echo $y->id ?>','loan_type')">
                                                            <span class="fas fa-trash"></span>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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

