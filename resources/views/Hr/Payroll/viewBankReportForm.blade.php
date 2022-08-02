<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

$banks=DB::table('banks')->where('status',1)->get();

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


        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">View Bank Report Form</h4>

                        </div>
                        <div class="col-sm-4 text-right" >
                            @if(in_array('print', $operation_rights))
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeAttendanceList','','1');?>
                            @endif
                            @if(in_array('export', $operation_rights))
                                <?php echo CommonHelper::displayExportButton('regionWisePayrollReport','','1')?>
                            @endif
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label pointer">Department</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select class="form-control requiredField" multiple name="department_id" id="department_id" onchange="getEmployee()">
                                <option value="0">Select Department</option>
                                @foreach($Department  as $key => $y)
                                    <option value="<?php echo $y->id ?>">
                                        {{ $y->department_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label pointer">Sub Department</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select class="form-control" multiple name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
                                <option value="0">Select Department</option>
                                @foreach($SubDepartment  as $key => $y)
                                    <option value="<?php echo $y->id ?>">
                                        {{ $y->sub_department_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label pointer">Select Bank</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select class="form-control requiredField" name="bank_id" id="bank_id">
                                <option value="0">Select Bank</option>
                                @foreach($banks  as $key => $y)
                                    <option value="<?php echo $y->id ?>">
                                        {{ $y->bank_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Month-Year</label>
                            <input type="month" name="month_year" id="month_year" max="" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Cheque No.</label>
                            <input type="text" name="cheque_no" id="cheque_no" max="" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Cheque Date.</label>
                            <input type="date" name="cheque_date" id="cheque_date" max="" value="<?=date('Y-m-d')?>" class="form-control requiredField" />
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <input type="checkbox" id="show_all" name="show_all" value="show_all" >
                            <label for="show_all" style="margin-right: 11px;"> Show All </label>
                            <button type="button" class="btn btn-sm btn-primary btn_search" id="showAttendenceReport" onclick="showPayrollReportBank()"  ><i id="load" class="fas fa fa-search"> </i> Search</button>
                        </div>



                    </div>



                </div>

            </div>
        </div>


        <br>


        <span style="display: none;" id="employeeAttendenceReportSection">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="loader"></div>
                            <div class="employeeAttendenceReportSection" id="PrintEmployeeAttendanceList"></div>
                        </div>
                    </div>

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




@endsection

