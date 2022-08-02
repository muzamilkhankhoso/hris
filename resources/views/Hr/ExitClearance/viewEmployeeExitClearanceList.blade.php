<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');
//$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\FinalSettlement;
use App\Models\Employee;
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Employee Exit Clearance List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeExitClearanceList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('EmployeeExitClearanceList','','1')?>
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

                        <div class="table-responsive">
                            <span id="PrintEmployeeExitClearanceList">
                            <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="EmployExitCleareanceList">
                                <thead>
                                <th class="text-center">S.No</th>
                                <th class="text-center">Emp No.</th>
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Supervisor Name</th>
                                <th class="text-center">Signed By Supervisor</th>
                                <th class="text-center">Last Working Date</th>
                                <th class="text-center">Approval Status</th>
                                <th class="text-center">Action</th>

                                </thead>
                                <tbody>
                                <?php $counter = 1;?>
                                @foreach($employee_exit as  $row)
                                    <?php
                                    CommonHelper::companyDatabaseConnection(Input::get('m'));
                                    $emp_name = Employee::select('emp_name')->where([['emp_id', '=', $row->emp_id],['status', '!=', 2]])->first();
                                    $final_settlement = FinalSettlement::where([['emp_id','=',$row->emp_id]])->count();
                                    CommonHelper::reconnectMasterDatabase();
                                    ?>
                                    <tr>
                                        <td class="text-center">{{ $counter++ }}</td>
                                        <td class="text-center">{{ $row->emp_id }}</td>
                                        <td>{{ $emp_name->emp_name }} </td>
                                        <td>{{ $row->supervisor_name }}</td>
                                        <td class="text-center">{{ $row->signed_by_supervisor }}</td>
                                        <td class="text-center">{{HrHelper::date_format($row->last_working_date) }}</td>
                                        <td class="text-center">{{ HrHelper::getApprovalStatusLabel($row->approval_status) }}</td>

                                        <td class="text-center hidden-print">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-rounded btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">
                                                    <i data-feather="chevron-down"
                                                       class="svg-icon"></i></button>
                                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">

                                                        <li role="presentation" class="actionsLink">
                                                            <a href="{{url('hr/viewEmployeeExitClearanceData?m='.$m.'&id='.$row->id.'#Innovative')}}">View</a>
                                                        </li>

                                                    @if(in_array('edit', $operation_rights))
                                                        <li role="presentation" class="actionsLink">
                                                            <a  class="delete-modal btn" onclick="showDetailModelFourParamerter('hr/editEmployeeExitClearanceDetailForm','<?php echo $row->id; ?>','Edit Employee Exit CLearance Detail Form','<?php echo $m;?>')">
                                                                Edit
                                                            </a>
                                                        </li>
                                                    @endif

                                                    @if(in_array('repost', $operation_rights))
                                                        @if($row->status == 2)
                                                            <li role="presentation" class="actionsLink">
                                                                <a class="delete-modal btn" onclick="repostCompanyTableRecord('<?php echo $m ?>','<?php echo $row->id; ?>','employee_exit')">
                                                                    Repost
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                    @if(in_array('delete', $operation_rights))
                                                        @if($row->status == 1)
                                                            <li role="presentation" class="actionsLink">
                                                                <a class="delete-modal btn" onclick="deleteEmployeeExitClearance('<?php echo $m ?>','<?php echo $row->id; ?>', '<?php echo $row->emp_id; ?>','employee_exit')">
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
                                </span>
                        </div>
                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

