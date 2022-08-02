<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
$currentDate = date('Y-m-d');
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Employee Allowance List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintAllownceList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('AllowanceList','','1')?>
                                @endif
                            </div>

                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-8">
                            <div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span style="font-weight:bold;cursor:pointer;border:1px solid black;" class="badge badge-sm badge-default all_btn" onclick="getAllEmployeesData()">All Employees</span>
                                    <span style="font-weight:bold;cursor:pointer;border:1px solid black;" class="badge badge-sm badge-success avtive_btn" onclick="getActiveEmployeesData()">Active</span>
									<span style="font-weight:bold;cursor:pointer;border:1px solid black;" class="badge badge-sm badge-default exit_btn" onclick="getExitEmployeesData()">Exit</span>
									<input type="hidden" value="" id="emp_status" />
								</div>
							</div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group has-search text-right">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="emp_id_search" name="emp_id_search" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="<?= $m ?>">
                                </div>
                            </div>

                        </div>
                        <span id="PrintAllownceList">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0 table-bordered table-striped" id="AllowanceList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">EMP ID</th>
                                        <th class="text-center">Emp Name</th>
                                        <th class="text-center">Department</th>
                                        <th class="text-center">Sub Department</th>
                                        <th class="text-center">Allowance Type</th>
                                        <th class="text-center">Month-Year</th>
                                        <th class="text-center">Amount</th>

                                        <th class="text-center">Status</th>

                                        <th class="text-center hidden-print">Actions</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 1;?>
                                        @foreach($allowance as $key => $value)
                                            <?php
                                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                                            $sub_department = Employee::select('emp_sub_department_id','emp_department_id')->where([['emp_id', '=', $value->emp_id]])->first();
                                            CommonHelper::reconnectMasterDatabase();
                                            $monthName="";
                                            if($value->month > 0){
                                                $monthName = date('M', mktime(0, 0, 0, $value->month=(int)$value->month, 10));
                                            }
                                            ?>

                                            <tr>
                                                <td class="text-center">{{ $counter++ }}</td>
                                                <td class="text-center">{{ $value->emp_id }}</td>
                                                <td>{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$value->emp_id,'emp_id') }}</td>
                                                <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'department','department_name', $sub_department->emp_department_id) }}</td>
                                                <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name', $sub_department->emp_sub_department_id) }}</td>
                                                <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'allowance_types','allowance_type', $value->allowance_type_id) }}</td>
                                                <td class="text-right">{{ $monthName."-".$value->year }}</td>
                                                <td class="text-right">
                                                    @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                                        {{ number_format($value->allowance_amount,0) }}
                                                    @else
                                                        ******
                                                    @endif

                                                </td>

                                                <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                                <td class="text-center hidden-print">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle  btn-rounded btn-sm" type="button" id="menu1" data-toggle="dropdown"><i data-feather="chevron-down" class="svg-icon" ></i></button>
                                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                            @if(in_array('view', $operation_rights))
                                                                <li role="presentation" class="actionsLink">
                                                                    <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hdc/viewAllowanceDetail','<?php echo $value->id;?>','View Allowance Detail','<?php echo $m; ?>')">
                                                                        View
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if(in_array('edit', $operation_rights))
                                                                <li role="presentation" class="actionsLink">
                                                                    <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/editAllowanceDetailForm','<?php echo $value->id;?>','Edit Allowance Detail','<?php echo $m; ?>')">
                                                                        Edit
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if(in_array('delete', $operation_rights))
                                                                @if($value->status == 1)
                                                                    <li role="presentation" class="actionsLink">
                                                                        <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $value->id ?>','allowance')">
                                                                            Delete
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endif
                                                            @if(in_array('repost', $operation_rights))
                                                                @if($value->status == 2)
                                                                    <li role="presentation" class="actionsLink">
                                                                        <a class="delete-modal btn" onclick="repostOneTableRecords('<?php echo $m ?>','<?php echo $value->id ?>','allowance')">
                                                                            Repost
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
                            </div>
                        </div>
                            </span>
                        <div class="text-center ajax-loader"></div>

                    </div>


                </div>

            </div>
        </div>

    </div>



    </div>


@endsection

