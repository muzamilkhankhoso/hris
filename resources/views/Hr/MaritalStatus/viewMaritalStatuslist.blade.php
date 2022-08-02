<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//	$m = $_GET['m'];
//}else{
//	$m = Auth::user()->company_id;
//}
$m = $_GET['m'];

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
                    <div class="card-body" id="PrintEmployeeList">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Marital Status List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintMaritalStatusList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('MaritalStatusList','','1')?>
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
                                <div class="table-responsive" id="PrintMaritalStatusList">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="tblMaritalStatusList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Marital Status Name</th>
                                        <th class="text-center">Created By</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center hidden-print">Action</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 1;?>
                                        @foreach($maritalStatus as $key => $y)
                                            <tr>
                                                <td class="text-center"><?php echo $counter++;?></td>
                                                <td><?php echo $y->marital_status_name;?></td>
                                                <td><?php echo $y->username;?></td>
                                                <td class="text-center">{{ HrHelper::getStatusLabel($y->status) }}</td>
                                                <td class="text-center hidden-print">
                                                    @if(in_array('edit', $operation_rights))
                                                        <button class="edit-modal btn-sm btn btn-info" onclick="showMasterTableEditModel('hr/editMaritalStatusForm','<?php echo $y->id ?>','Marital Status Edit Detail Form','<?php echo $m?>')">
                                                            <span class="fas fa-edit"></span>
                                                        </button>
                                                    @endif
                                                    @if(in_array('repost', $operation_rights))
                                                        @if($y->status == 2)
                                                            <button class="delete-modal btn btn-sm btn-primary" onclick="repostMasterTableRecords('<?php echo $y->id ?>','marital_status')">
                                                                <span class="fas fa-refresh"></span>
                                                            </button>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete', $operation_rights))
                                                        @if($y->status == 1)
                                                            <button class="delete-modal btn-sm btn btn-danger" onclick="deleteRowMasterTable('<?php echo $y->id ?>','marital_status')">
                                                                <span class="fas fa-trash"></span>
                                                            </button>
                                                        @endif
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

