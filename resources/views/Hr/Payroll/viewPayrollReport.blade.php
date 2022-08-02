<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];


use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->

        <input type="hidden" name="_token" value="{{ csrf_token() }}"><div class="row">
            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Payroll Report</h4>

                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintregionWisePayrollReport','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('regionWisePayrollReport','','1')?>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label pointer">Department</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control" name="department_id" id="department_id" onchange="getEmployee()">
                                    <option value="0">Select Department</option>
                                    @foreach($Department  as $key => $y)
                                        <option value="<?php echo $y->id ?>">
                                            {{ $y->department_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label pointer">Sub Department</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
                                    <option value="0">Select Department</option>
                                    @foreach($SubDepartment  as $key => $y)
                                        <option value="<?php echo $y->id ?>">
                                            {{ $y->sub_department_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<label class="sf-label">Employee:</label>
											<span class="rflabelsteric"><strong>*</strong></span>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
											<span style="font-weight:bold;cursor:pointer;border:1px solid black;" class="badge badge-sm badge-success avtive_btn" onclick="getEmployee()">Active</span>
											<span style="font-weight:bold;cursor:pointer;border:1px solid black;" class="badge badge-sm badge-default exit_btn" onclick="getExitEmployees()">Exit</span>
											<input type="hidden" value="" id="emp_status" />
										</div>
									</div>
                                <select class="form-control" name="emp_id" id="emp_id" >
                                    <option value="0">-</option>
                                </select>
                                <div id="emp_loader_1"></div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Payslip Month:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" value="2021-12" name="payslip_month" id="payslip_month" value="" class="form-control requiredField" required />
                            </div>


                        </div>
                        <br>
                        <div class="row">
                            <br>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                <input type="checkbox" id="show_all" name="show_all" value="show_all">
                                <label for="show_all" style="margin-right: 11px;"> Show All </label>
                                <button type="button" class="btn btn-sm btn-primary btn_search" id="viewPayrollReport" onclick="viewPayrollReport()"><i id="load" class="fas fa-search fa"> </i> Search</button>
                            </div>
                        </div>
                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>

        </div>

        <span id="employeeAttendenceReportSection" style="display: none;">


        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintregionWisePayrollReport">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="loader"></div>
                                <div class="employeeAttendenceReportSection" id="regionWisePayrollReport"></div>


                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
        </span>
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

