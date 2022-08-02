<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');
//$parentCode = $_GET['parentCode'];

use App\Helpers\CommonHelper;
use App\Models\SubDepartment;
use App\Models\EmployeeProjects;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
 CommonHelper::companyDatabaseConnection(Input::get('m'));
$employee_data = DB::table('employee')->where('emp_id',Auth::user()->emp_id)->first();
CommonHelper::reconnectMasterDatabase();
$emp_SubDepartment = SubDepartment::where([['department_id',$employee_data->emp_department_id],['status','1']])->first();
$EmployeeProjects = EmployeeProjects::where([['id',$employee_data->employee_project_id],['status','1']])->first();

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
                                        <h4 class="card-title">View Employee Attendance Progress</h4>
                                    </div>
									<div class="col-sm-4 text-right">
                                        <?php echo CommonHelper::displayPrintButtonInBlade('PrintLeavesPolicyList','','1');?>
                                        <?php echo CommonHelper::displayExportButton('LeavesPolicyList','','1')?>
									</div>
                                </div>

								<hr>

                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
						      <?php if($accType != 'user'){ ?>
                                    <div class="row">
										 <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label pointer">Department</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select style="width:100%;" class="requiredField form-control" name="department_id" id="department_id" onchange="getEmployee()">
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
                                            <select style="width:100%;" class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
                                                <option value="0">Select Department</option>
                                                @foreach($SubDepartment  as $key => $y)
                                                    <option value="<?php echo $y->id ?>">
                                                        {{ $y->sub_department_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
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
                                            <select style="width:100%;" class="form-control" name="emp_id" id="emp_id" >
                                                <option value="0">-</option>
                                            </select>
                                            <div id="emp_loader_1"></div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											<label>From Date</label>
											<input type="date" name="from_date" id="from_date" class="form-control requiredField" value="2021-12-01">
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											<label>To Date</label>
											<input type="date" name="to_date" id="to_date" class="form-control requiredField" value="2021-12-31">
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="margin-top: 8px;">
											<br>
											<input style="width: 24px;height: 20px;margin-left:15px;" type="checkbox" id="show_all" name="show_all" value="show_all">
											<label for="show_all"> Show All </label>&nbsp;&nbsp;
											<button type="button" class="btn btn-sm btn-primary btn_search" id="showAttendenceReport" onclick="viewAttendanceProgress()"><i id="load" class="fas fa-search fa"> Search</i></button>
										</div>
									</div>
									<br>
									<div class="row">
										{{--<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">--}}
											{{--<label>Month-Year</label>--}}
											{{--<input type="month" name="month_year" id="month_year" max="{{ $current_date }}" class="form-control requiredField" />--}}
										{{--</div>--}}

										<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <!-- <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"></div> -->

                                            
                                            
										</div>
									</div>
							  <?php }else{ ?>	
								   <div class="row">
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										   <label class="sf-label pointer">Department</label>
											<span class="rflabelsteric"><strong>*</strong></span>
											<select class="form-control requiredField" name="department_id_" id="department_id_" onchange="getEmployee()" disabled>
												<option value="<?php echo $employee_data->emp_department_id; ?>"><?php echo $emp_SubDepartment->sub_department_name;  ?></option>
											</select>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										   <label class="sf-label pointer">Projects</label>
											<span class="rflabelsteric"><strong></strong></span>
											<select class="form-control" name="employee_project_id" id="employee_project_id" onchange="getEmployee()" disabled >
											   <?php if($employee_data->employee_project_id != ''){ ?>
												<option value="<?php echo $employee_data->employee_project_id; ?>"><?php echo $EmployeeProjects->project_name; ?></option>
											   <?php } ?>	
											</select>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											<label class="sf-label">Employee:</label>
											<span class="rflabelsteric"><strong>*</strong></span>
											<select class="form-control requiredField" name="emp_id" id="emp_id" required disabled>
												<option value="<?php echo $employee_data->emp_id; ?>"><?php echo $employee_data->emp_name ?></option>
											</select>
											<div id="emp_loader_1"></div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<label>From Date</label>
											<input type="date" name="from_date" id="from_date" class="form-control requiredField" value="{{Session::get('fromDate')}}">
										</div>
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
											<label>To Date</label>
											<input type="date" name="to_date" id="to_date" class="form-control requiredField" value="{{Session::get('toDate')}}">
										</div>
									</div>
									<br>
									<div class="row">
										{{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">--}}
											{{--<label>Month-Year</label>--}}
											{{--<input type="month" name="month_year" id="month_year" max="{{ $current_date }}" class="form-control requiredField" />--}}
										{{--</div>--}}

										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
											<input type="button" class="btn btn-sm btn-primary" id="showAttendenceReport" onclick="viewAttendanceProgress()" value="Show Attendance Progress" style="margin-top: 32px;" />
										</div>
									</div>
							  <?php } ?>		
								</div>
							</div>

						</div>
                        
						
				 	</div>
                                
                                <div class="text-center ajax-loader"></div>
                            </div>

                        </div>
                    </div>

                </div>

				<span id="employeeAttendenceReportSection" style="display:none;">
				<div class="row">

                    <div class="col-12">
                        <div class="card" id="LeavesPolicyList">
                            <div class="card-body"  id="PrintLeavesPolicyList">
							<div id="loader"></div>
                        
						<div class="employeeAttendenceReportSection" id="PrintEmployeeAttendanceList"></div>

                			</div>
						</div>
					</div>
				</div>
				</span>
            </div>





@endsection

