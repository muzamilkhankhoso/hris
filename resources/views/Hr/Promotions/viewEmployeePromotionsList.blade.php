<?php
//$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
//$parentCode = $_GET['parentCode'];
$m = $_GET['m'];
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;

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
                                <h4 class="card-title">View Promotion List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeePromotionList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('EmployeePromotionList','','1')?>
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

                        <span id="PrintEmployeePromotionList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                                        <div class="table-responsive LeavesData">

                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LeaveApplicationRequestList">
                        <thead>
                        <th class="text-center">S No</th>
                        <th class="text-center">Emp ID</th>
                        <th class="text-center">Emp Name</th>
                        <th class="text-center">Dept / Subdept</th>
                        <th class="text-center">Designation</th>
                        <th class="text-center">Increment</th>
                        <th class="text-center">Salary</th>
                        <th class="text-center">Date</th>

                        <th class="text-center">Status</th>
                        <th class="text-center hidden-print">Action</th>
                        </thead>
                        <tbody>
                        <?php if($employeePromotions->count() > 0):?>
                        <?php $counter = 1;?>
                        @foreach($employeePromotions->get() as $value)
                            <?php
                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                            $employeeData = App\Models\Employee::where('emp_id','=',$value->emp_id)->first();
                            
                            CommonHelper::reconnectMasterDatabase();
                            ?>
                            <tr>
                                <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                                <td class="text-center">{{$value->emp_id}}</td>
                                <td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$value['emp_id'],'emp_id') }}</td>
                                <td class="text-center">{{HrHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$employeeData->value('emp_sub_department_id') )}}</td>
                                <td class="text-center">{{HrHelper::getMasterTableValueById($m,'designation','designation_name',$value->designation_id)}}</td>
                                <td class="text-right">
                                    @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                        {{ number_format($value->increment,0) }}
                                    @else
                                        ******
                                    @endif

                                </td>
                                <td class="text-right">
                                    @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                        {{ number_format($value->salary,0) }}
                                    @else
                                        ******
                                    @endif

                                </td>
                                <td class="text-center">{{HrHelper::date_format($value->promotion_date)}}</td>

                                <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown"><i data-feather="chevron-down"
                                                                                                                                                  class="svg-icon"></i></button>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                            @if(in_array('view', $operations_rights))
                                                <li role="presentation" class="actionsLink">
                                                    <a  class="delete-modal btn" onclick="showDetailModelFourParamerter('hdc/viewPromotionLetter','<?php echo $value->id ?>','View Promotion Letter','<?php echo $m ?>')">
                                                        View
                                                    </a>
                                                </li>
                                            @endif
                                            @if(in_array('approve', $operations_rights))
                                                @if($value->approval_status != '2')
                                                    <li role="presentation" class="actionsLink">
                                                        <a class="delete-modal btn" onclick="approveAndRejectTableRecord('{{ $m }}','{{ $value->id }}','2','employee_promotion')">
                                                            Approve
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                            @if(in_array('reject', $operations_rights))
                                                @if($value->approval_status != '3')
                                                    <li role="presentation" class="actionsLink">
                                                        <a class="delete-modal btn" onclick="approveAndRejectTableRecord('{{ $m }}','{{ $value->id }}','3','employee_promotion')">
                                                            Reject
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                            @if(in_array('edit', $operations_rights))
                                                <li role="presentation" class="actionsLink">
                                                    <a class="delete-modal btn" onclick="showDetailModelFourParamerter('hr/editEmployeePromotionDetailForm','<?= $value->id ?>','View Employee Promotions Detail','<?php echo $m; ?>')">
                                                        Edit
                                                    </a>
                                                </li>
                                            @endif
                                            @if(in_array('delete', $operations_rights))
                                                <li role="presentation" class="actionsLink">
                                                    <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m; ?>','<?=$value->id?>', 'employee_promotion')">
                                                        Delete
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <?php else: ?>
                        <tr>
                            <td class="text-center" colspan="12" style="color:red;font-weight: bold;">Record Not Found !</td>
                        </tr>
                        <?php endif; ?>
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

