<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');

use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\PayrollData;
use App\Models\Employee;

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
                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h4 class="card-title">View Employee Attendance Progress List</h4>
                                    </div>
                                    <div class="col-sm-4 text-right">
                                        <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeAttendanceList','','1');?>
                                        <?php echo CommonHelper::displayExportButton('PrintEmployeeAttendanceList','','1')?>
                                    </div>

                                </div>
                                <hr>
                                <br>
                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="row">
                                            <input type="hidden" name="accType" id="accType" value="<?php echo $accType ?>"	>
                                            <input type="hidden" name="acc_emp_id" id="acc_emp_id" value="<?php echo Auth()->user()->emp_id ?>"	>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12" style="">
                                                <!-- <label class="sf-label">Search By Month-Year:</label> -->
                                                <input type="month" value="2021-12" class="form-control requiredField" name="month_year" id="month_year">
                                                <label style="font-size:12px;" >Search by month/year</label>
                                            </div>
                                            <input type="hidden" id="company_id" value="<?= Input::get('m')?>">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="
margin-top: 5px;">
                                                <button class="btn btn-sm btn-primary btn_search" onclick="attendanceProgressFilteredList()"><i id="load" class="fas fa fa-search"> </i> Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>        
                        </div>

                    </div>
                </div>

                <div class="row" id="attendance_progress_list" style="display:none;">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span id="employee-list"></span>
                        </div>
                    </div>
                            </div>
                
                        </div>
                    </div>
                </div>


    </div>




@endsection

