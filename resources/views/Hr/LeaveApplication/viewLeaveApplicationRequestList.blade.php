<?php

$m = $_GET['m'];

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

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
                                <h4 class="card-title">View Leave Application Request Lists</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintLeaveApplicationRequestList','','1');?>
                                <?php echo CommonHelper::displayExportButton('LeaveApplicationRequestList','','1')?>

                            </div>

                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
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
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
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
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Employee:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="emp_id" id="emp_id" >
                                    <option value="0">-</option>
                                </select>
                                <div id="emp_loader_1"></div>
                            </div>

                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label pointer">Date From</label>
                                <input type="date" class="form-control requiredField" name="from_date" id="from_date" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label pointer">Date To</label>
                                <input type="date" class="form-control requiredField" name="to_date" id="to_date" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <br/><br/>
                                <label class="sf-label">Pending:</label>
                                <input type="checkbox" checked value="1" name="pending" id="pending" />
                                <label class="sf-label">Approved:</label>
                                <input type="checkbox" checked value="2" name="approved" id="approved" />
                                <label class="sf-label">Rejected:</label>
                                <input type="checkbox" checked value="3" name="rejected" id="rejected" />
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                <button type="button"  class="btn btn-sm btn-primary btn_search" onclick="viewRangeWiseLeaveApplicationsRequests();" style="margin-top: 25px;" ><i id="load" class="fas fa-search fa"> </i> Search</button>
                            </div>
                        </div>
                        <div id="leavesLoader"></div>

                        <?php $leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];?>

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
        <br>
        <span id="employeeAttendenceReportSection" style="display:none;">
				<div class="row">

                    <div class="col-12">
                        <div class="card" id="LeavesPolicyList">
                            <div class="card-body"  id="PrintLeaveApplicationRequestList">
							<div id="loader"></div>
                        
						<div class="employeeAttendenceReportSection" id="PrintLeaveApplicationRequestList"></div>

                			</div>
						</div>
					</div>
				</div>
		</span>



    </div>









@endsection

