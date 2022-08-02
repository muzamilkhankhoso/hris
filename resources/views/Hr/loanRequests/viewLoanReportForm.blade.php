<?php
$accType = Auth::user()->acc_type;
$m = $_GET['m'];
$current_date = date('Y-m-d');
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
                    <div class="card-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="card-title">View Loan Report Form</h4>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintLoanReport','','1');?>
                                    <?php echo CommonHelper::displayExportButton('LoanReport','','1')?>
                                </div>
                            </div>

                            <hr>
                            <div class="panel">
                                <div class="panel-body">
                                    <?php echo Form::open(array('url' => 'had/addEmployeeOfTheMonthDetail','id'=>'employeeForm'));?>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="m" value="<?php echo Input::get('m')?>">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Department</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select class="form-control requiredField" name="department_id" id="department_id" onchange="getEmployee()">
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
                                            <select class="form-control requiredField" name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
                                                <option value="0">Select Department</option>
                                                @foreach($SubDepartment  as $key => $y)
                                                    <option value="<?php echo $y->id ?>">
                                                        {{ $y->sub_department_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Employee:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select class="form-control requiredField" name="emp_id" id="emp_id" onchange="getEmpLoans()" >
                                                <option value="0">-</option>
                                            </select>
                                            <div id="emp_loader_1"></div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Loans:</label>
                                            <select class="form-control" id="loan_id">
                                            </select>
                                            <div id="emp_loader_2"></div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                            <button type="button" class="btn btn-sm btn-primary btn_search" onclick="viewLoanReport()" ><i id="load" class="fas fa-search fa"> Search</i></button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <span id="employeeAttendenceReportSection" style="display:none;">


        <div class="row">
            <div class="col-12">
                <div id="loader"></div>
                <div class="card">
                    <div class="card-body">
                        <div class="employeeAttendenceReportSection" id="PrintLoanReport"></div>
                    </div>


                    <div class="text-center ajax-loader"></div>
                </div>

            </div>
        </div>
        </span>
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

