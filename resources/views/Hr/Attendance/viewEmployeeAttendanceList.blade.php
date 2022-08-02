<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
	$m = $_GET['m'];
}else{
	$m = Auth::user()->company_id;
}
//$parentCode = $_GET['parentCode'];

use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\LeaveApplicationData;
use App\Models\Employee;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
CommonHelper::companyDatabaseConnection(Input::get('m'));
$user_data = Employee::where([['emp_id','=',Auth::user()->emp_id],['status','=',1]])->first();

CommonHelper::reconnectMasterDatabase();

?>

@extends('layouts.default')
@section('content')


	<div class="page-wrapper">


		<div class="row">

			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-8">
								<h4 class="card-title">View Employee Attendance List</h4>
							</div>
							<div class="col-sm-4 text-right">
								@if(in_array('print', $operation_rights))
									<?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeAttendanceList','','1');?>
								@endif
								@if(in_array('export', $operation_rights))
									<?php echo CommonHelper::displayExportButton('PrintEmployeeAttendanceList','','1')?>
								@endif
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								@if(Auth::user()->acc_type == 'client' || $user_data->role_id==8)

									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											<label class="sf-label pointer">Department</label>
											<span class="rflabelsteric"><strong>*</strong></span>
											<select style="width:100%;" class="form-control" name="department_id" id="department_id" onchange="getEmployee()">
												<option value="0">Select Department</option>
												@foreach($department_id  as $key => $y)
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
												@foreach($sub_department_id  as $key => $y)
													<option value="<?php echo $y->id ?>">
														{{ $y->sub_department_name}}
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
										<div style="margin-top: 10px;" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											<label>From Date</label>
											<input type="date" value="2021-12-01" name="from_date" id="from_date" class="form-control requiredField" required>
										</div>
										<div style="margin-top: 10px;" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											<label>To Date</label>
											<input type="date" value="2021-12-31" name="to_date" id="to_date" class="form-control requiredField" required>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-left" style="margin-top: 10px;">
											<button type="button" class="btn btn-sm btn-primary btn_search" id="showAttendenceReport" onclick="showAttendanceReport()" style="margin-top: 38px;" ><i class="fas fa-search"> </i> Search</button>
										</div>
									</div>

									<br>

								@else
									<div class="row">
										@if(Auth::user()->acc_type == 'user')
											<?php CommonHelper::companyDatabaseConnection(Input::get('m'));
											$team_members = Employee::select('emp_id','emp_name')
													->where('reporting_manager',Auth::user()->emp_id)
													->where('status',1);
											CommonHelper::reconnectMasterDatabase(); ?>

											@if( $team_members->count() > 0)


												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
													<label class="emp_search">Team Members :</label>
													<span class="rflabelsteric">&nbsp;</span>
													<select style="width: 100%;" class="form-control" name="emp_id" id="emp_id">
														<option value="<?=Auth::user()->emp_id?>"><?=Auth::user()->name?></option>
														<?php foreach($team_members->get() as $value): ?>
														<option value="<?php echo $value->emp_id ?>"><?php echo 'EMP-ID: ' . $value->emp_id . '---' . $value->emp_name; ?></option>
														<?php endforeach;?>
													</select>
												</div>

											@else

												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hidden">
													<label class="sf-label">Employee:</label>
													<span class="rflabelsteric"><strong>*</strong></span>
													<select style="width:100%;" class="form-control requiredField" name="emp_id" id="emp_id" required disabled>
														<option value="<?php echo $user_data->emp_id?>"><?php echo 'EMP-ID: ' . $user_data->emp_id . '---' . $user_data->emp_name;?></option>
													</select>
												</div>

											@endif
										@endif





										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											<label>From Date</label>
											<input type="date" name="from_date" id="from_date" class="form-control requiredField" >
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											<label>To Date</label>
											<input type="date" name="to_date" id="to_date" class="form-control requiredField">
										</div>

										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-left">
											<button type="button" class="btn btn-sm btn-primary btn_search" id="showAttendenceReport" onclick="showAttendanceReport()" style="margin-top: 37px;" ><i class="fas fa-search"> </i> Search</button>
										</div>


										@endif
									</div>
							</div>



						</div>






					</div>

				</div>


			</div>



			@if($accType == 'client')
				<span id="employeeAttendenceReportSection" style="display: none;">

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


			@else
				<div class="col-12">
					<span id="employeeAttendenceReportSection" style="display: none;">


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


					</span>
				</div>


			@endif
		</div>
	</div>
	<script>
		$(document).ready(function () {

			$(document).bind('ajaxStart', function () {

			}).bind('ajaxStop', function () {

				$("select[name='emp_id'] option[value='all']").remove();
			});
		});




	</script>
@endsection

