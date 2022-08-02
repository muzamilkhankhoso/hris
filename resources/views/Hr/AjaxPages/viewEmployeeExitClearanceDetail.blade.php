<?php

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
//if ($accType == 'client') {
//    $m = $_GET['m'];
//} else {
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');

$emp_name = $employee->emp_name;



$id = $exit_employee_data->id;
$emp_id = $exit_employee_data->emp_id;
$leaving_type = $exit_employee_data->leaving_type;
$last_working_date = $exit_employee_data->last_working_date;
$approval_status = $exit_employee_data->approval_status;
$supervisor_name = $exit_employee_data->supervisor_name;
$signed_by_supervisor = $exit_employee_data->signed_by_supervisor;

$room_key=$exit_employee_data->room_key;
$mobile_sim=$exit_employee_data->mobile_sim;
$fuel_card=$exit_employee_data->fuel_card;
$mfm_employee_card=$exit_employee_data->mfm_employee_card;
$client_access_card=$exit_employee_data->client_access_card;
$medical_insurance_card=$exit_employee_data->medical_insurance_card;
$eobi_card=$exit_employee_data->eobi_card;
$biometric_scan=$exit_employee_data->biometric_scan;
$payroll_deduction=$exit_employee_data->payroll_deduction;
$info_sent_to_client=$exit_employee_data->info_sent_to_client;
$client_exit_checklist=$exit_employee_data->client_exit_checklist;
$exit_interview=$exit_employee_data->exit_interview;

$laptop=$exit_employee_data->laptop;
$desktop_computer=$exit_employee_data->desktop_computer;
$email_account_deactivated=$exit_employee_data->email_account_deactivated;
$toolkit_ppe=$exit_employee_data->toolkit_ppe;
$uniform=$exit_employee_data->uniform;

$advance_loan=$exit_employee_data->advance_loan;
$dues_payable = $exit_employee_data->dues_payable;
$incentive=$exit_employee_data->incentive;
$cell_phone = $exit_employee_data->cell_phone;
$company_car = $exit_employee_data->company_car;
$emp_card= $exit_employee_data->emp_card;
$business_card = $exit_employee_data->business_card;
$stationary_returned = $exit_employee_data->stationary_returned;
$any_other_clearance= $exit_employee_data->any_other_clearance;
$data_document = $exit_employee_data->data_document;
$knowledge_transfer= $exit_employee_data->knowledge_transfer;
$responsiblities_handed=$exit_employee_data->responsiblities_handed;
$last_assignment = $exit_employee_data->last_assignment;
$client_acc_info=$exit_employee_data->client_acc_info;
$emp_separation=$exit_employee_data->emp_separation;
$controlling_system=$exit_employee_data->controlling_system;
$build_server = $exit_employee_data->build_server;
$project_management=$exit_employee_data->project_management;
$client_proj_manage=$exit_employee_data->client_proj_manage;
$production_env=$exit_employee_data->production_env;
$bug_tracking=$exit_employee_data->bug_tracking;
$internal_app=$exit_employee_data->internal_app;
$help_desk=$exit_employee_data->help_desk;
$info_partner_sep=$exit_employee_data->info_partner_sep;
$skype_group= $exit_employee_data->skype_group;
$hris_system = $exit_employee_data->hris_system;
$hris_portal = $exit_employee_data->hris_portal;
$insurance_card_returned = $exit_employee_data->insurance_card_returned;
$exit_questionnaire = $exit_employee_data->exit_questionnaire;
$finalize_disbursable_salry= $exit_employee_data->finalize_disbursable_salry;
$int_app_sup_port = $exit_employee_data->int_app_sup_port;
$promised_paid = $exit_employee_data->promised_paid;
$confirm_reflection = $exit_employee_data->confirm_reflection;
$notice_period = $exit_employee_data->notice_period;
$resignation_noti_received = $exit_employee_data->resignation_noti_received;
$email_acc_deactivated = $exit_employee_data->email_acc_deactivated;
$point_1_no = $exit_employee_data->point_1_no;
$hardware_received = $exit_employee_data->hardware_received;
$email_sheet = $exit_employee_data->email_sheet;
$equipment_received = $exit_employee_data->equipment_received;
$internet_device_received = $exit_employee_data->internet_device_received;
$test_phone_receiving = $exit_employee_data->test_phone_receiving;
$deactivate_attend_soft = $exit_employee_data->deactivate_attend_soft;
$extra_leaves=$exit_employee_data->extra_leaves;
$final_settlement=$exit_employee_data->final_settlement;


$room_key_remarks=$exit_employee_data->room_key_remarks;
$mobile_sim_remarks=$exit_employee_data->mobile_sim_remarks;
$fuel_card_remarks=$exit_employee_data->fuel_card_remarks;
$mfm_employee_card_remarks=$exit_employee_data->mfm_employee_card_remarks;
$client_access_card_remarks=$exit_employee_data->client_access_card_remarks;
$medical_insurance_card_remarks=$exit_employee_data->medical_insurance_card_remarks;
$eobi_card_remarks=$exit_employee_data->eobi_card_remarks;
$biometric_scan_remarks=$exit_employee_data->biometric_scan_remarks;
$payroll_deduction_remarks=$exit_employee_data->payroll_deduction_remarks;
$info_sent_to_client_remarks=$exit_employee_data->info_sent_to_client_remarks;
$client_exit_checklist_remarks=$exit_employee_data->client_exit_checklist_remarks;
$exit_interview_remarks=$exit_employee_data->exit_interview_remarks;

$laptop_remarks=$exit_employee_data->laptop_remarks;
$desktop_computer_remarks=$exit_employee_data->desktop_computer_remarks;
$email_account_deactivated_remarks=$exit_employee_data->email_account_deactivated_remarks;
$toolkit_ppe_remarks=$exit_employee_data->toolkit_ppe_remarks;
$uniform_remarks=$exit_employee_data->uniform_remarks;

$advance_loan_remarks=$exit_employee_data->advance_loan_remarks;
$incentive_remarks=$exit_employee_data->incentive_remarks;
$dues_payable_remarks = $exit_employee_data->dues_payable_remarks;
$cell_phone_remarks = $exit_employee_data->cell_phone_remarks;
$company_car_remarks = $exit_employee_data->company_car_remarks;
$emp_card_remarks = $exit_employee_data->emp_card_remarks;
$business_card_remarks = $exit_employee_data->business_card_remarks;
$stationary_returned_remarks = $exit_employee_data->stationary_returned_remarks;
$any_other_clearance_remarks = $exit_employee_data->any_other_clearance_remarks;
$data_document_remarks = $exit_employee_data->data_document_remarks;
$knowledge_transfer_remarks = $exit_employee_data->knowledge_transfer_remarks;
$responsiblities_handed_remarks=$exit_employee_data->responsiblities_handed_remarks;
$last_assignment_remarks=$exit_employee_data->last_assignment_remarks;
$client_acc_info_remarks=$exit_employee_data->client_acc_info_remarks;
$emp_separation_remarks=$exit_employee_data->emp_separation_remarks;
$controlling_system_remarks=$exit_employee_data->controlling_system_remarks;
$build_server_remarks = $exit_employee_data->build_server_remarks;
$project_management_remarks=$exit_employee_data->project_management_remarks;
$client_proj_manage_remarks=$exit_employee_data->client_proj_manage_remarks;
$production_env_remarks=$exit_employee_data->production_env_remarks;
$bug_tracking_remarks = $exit_employee_data->bug_tracking_remarks;
$internal_app_remarks = $exit_employee_data->internal_app_remarks;
$help_desk_remarks = $exit_employee_data->help_desk_remarks;
$extra_leaves_remarks=$exit_employee_data->extra_leaves_remarks;
$info_partner_sep_remarks = $exit_employee_data->info_partner_sep_remarks;
$skype_group_remarks = $exit_employee_data->skype_group_remarks;
$hris_system_remarks = $exit_employee_data->hris_system_remarks;
$hris_portal_remarks = $exit_employee_data->hris_portal_remarks;
$insurance_card_returned_remarks = $exit_employee_data->insurance_card_returned_remarks;
$exit_questionnaire_remarks = $exit_employee_data->exit_questionnaire_remarks;
$finalize_disbursable_salry_remarks= $exit_employee_data->finalize_disbursable_salry_remarks;
$int_app_sup_port_remarks = $exit_employee_data->int_app_sup_port_remarks;
$promised_paid_remarks = $exit_employee_data->promised_paid_remarks;
$confirm_reflection_remarks = $exit_employee_data->confirm_reflection_remarks;
$notice_period_remarks = $exit_employee_data->notice_period_remarks;
$resignation_noti_received_remarks = $exit_employee_data->resignation_noti_received_remarks;
$email_acc_deactivated_remarks = $exit_employee_data->email_acc_deactivated_remarks;
$point_1_no_remarks = $exit_employee_data->point_1_no_remarks;
$hardware_received_remarks = $exit_employee_data->hardware_received_remarks;
$email_sheet_remarks = $exit_employee_data->email_sheet_remarks;
$equipment_received_remarks = $exit_employee_data->equipment_received_remarks;
$internet_device_received_remarks = $exit_employee_data->internet_device_received_remarks;
$test_phone_receiving_remarks = $exit_employee_data->test_phone_receiving_remarks;
$deactivate_attend_soft_remarks = $exit_employee_data->deactivate_attend_soft_remarks;
$extra_leaves_remarks=$exit_employee_data->extra_leaves_remarks;
$final_settlement_remarks=$exit_employee_data->final_settlement_remarks;

?>

<div class="container">
    <div class="row text-right">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			@if($type != 'log')
				@if(in_array('approve', $operation_rights2))
					@if ($approval_status != 2)
						<button type="button" class="btn btn-primary btn-sm" onclick="approveAndRejectEmployeeExit('<?php echo $m ?>','<?php echo $id;?>', '2', 'employee_exit', '<?php echo $emp_id ?>', '3')"> Approve </button>
					@endif
				@endif
				@if(in_array('reject', $operation_rights2))
					@if ($approval_status != 3)
						<button type="button" class="btn btn-danger btn-sm" onclick="approveAndRejectEmployeeExit('<?php echo $m ?>','<?php echo $id;?>', '3', 'employee_exit', '<?php echo $emp_id ?>', '1')"> Reject </button>
					@endif
				@endif

				@if(in_array('print', $operation_rights2))
					@if ($approval_status == 2)
						<?php echo CommonHelper::displayPrintButtonInBlade('PrintExitClearenceDetail','','1');?>
					@endif
				@endif
			@endif
        </div>
    </div>
</div>
<br>
<div class="container" id="PrintExitClearenceDetail">
    <div class="print-font2">
		<!--<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
				<img src="../assets/img/mima_logo1.png" alt="" class="mima-logo">
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
				<h4 class="text-bold print-font-size2 text-center">EMPLOYEE EXIT CHECKLIST <br>MIMA FACILITY MANAGEMENT</h4>
			</div>
		</div>-->
		<div class="row">&nbsp</div>
		<div class="row war-margin1">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<h5 class="text-bold print-font-size">Name: {{ $emp_name }}</h5>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<h5 class="text-bold print-font-size">Job Tille: {{ HrHelper::getMasterTableValueById($m, 'designation', 'designation_name', $designation_id ) }}</h5>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<h5 class="text-bold print-font-size">Date: {{ date('d-m-Y') }} </h5>
			</div>
		</div>
		<hr>
		<div class="row war-margin1">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<h5 class="text-bold print-font-size">Emp Id: {{ $emp_id }}</h5>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<h5 class="text-bold print-font-size">Sub Department: {{  HrHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$emp_sub_department_id) }}</h5>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 print-font-size">
				<h5 class="text-bold print-font-size">Last day of employment: {{ HrHelper::date_format($last_working_date) }}</h5>
			</div>
		</div>
		<hr>

			<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<table class="table table-sm mb-0 table-bordered table-striped table-hover warning-mar2" >
				<tbody>
				<tr>
					<td></td>
					<td class="text-center text-bold print-sett">Items</td>
					<td colspan="3" class="text-center text-bold print-sett">Status</td>
					<td class="text-center text-bold print-sett">Remarks (If any)</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td class="text-center text-bold print-sett">Yes</td>
					<td class="text-center text-bold print-sett">No</td>
					<td class="text-center text-bold print-sett">N/A</td>
					<td></td>
				</tr>
				<tr>
					<td class="text-center print-black text-bold print-sett">1</td>
					<td class="print-black text-bold print-sett text-center" colspan="6">FINANCE DEPARTMENT</td>
{{--					<td class="print-black print-sett"></td>--}}
{{--					<td class="print-black print-sett"></td>--}}
{{--					<td class="print-black print-sett"></td>--}}
{{--					<td class="print-black print-sett"></td>--}}
				</tr>
				{{--<tr>--}}
					{{--<td class="text-center print-sett">a</td>--}}
					{{--<td class="print-sett">--}}
						{{--Room and drawer key(s) returned--}}
					{{--</td>--}}
					{{--<td class="text-center print-sett">@if($room_key == 1) &#10003; @endif</td>--}}
					{{--<td class="text-center print-sett">@if($room_key == 2) &#10003; @endif</td>--}}
					{{--<td class="text-center print-sett">@if($room_key == 3) &#10003; @endif</td>--}}
					{{--<td class="print-sett">{{ $room_key_remarks }}</td>--}}
				{{--</tr>--}}
				<tr>
					<td class="text-center print-sett">a</td>
					<td class="print-sett">
						Loans/Advances or any dues receiveable from the employee
					</td>
					<td class="text-center print-sett">@if($advance_loan == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($advance_loan == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($advance_loan == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $advance_loan_remarks }}</td>
				</tr>
				{{--<tr>--}}
					{{--<td class="text-center print-sett">c</td>--}}
					{{--<td class="print-sett">--}}
						{{--Fuel Card recovered--}}
					{{--</td>--}}
					{{--<td class="text-center print-sett">@if($fuel_card == 1) &#10003; @endif</td>--}}
					{{--<td class="text-center print-sett">@if($fuel_card == 2) &#10003; @endif</td>--}}
					{{--<td class="text-center print-sett">@if($fuel_card == 3) &#10003; @endif</td>--}}
					{{--<td class="print-sett">{{ $fuel_card_remarks }}</td>--}}
				{{--</tr>--}}
				<tr>
					<td class="text-center print-sett">b</td>
					<td class="print-sett">
						Incentive/Commission to be paid to the employee
					</td>
					<td class="text-center print-sett">@if($incentive == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($incentive == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($incentive == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $incentive_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">c</td>
					<td class="print-sett">
						Dues payable by Innovative network
					</td>
					<td class="text-center print-sett">@if($dues_payable == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($dues_payable == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($dues_payable == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $dues_payable_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">d</td>
					<td class="print-sett">
						Any other dues receivable from employee
					</td>
					<td class="text-center print-sett">@if($final_settlement == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($final_settlement == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($final_settlement == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $final_settlement_remarks }}</td>
				</tr>
{{--				<tr>--}}
{{--					<td class="text-center print-sett">e</td>--}}
{{--					<td class="print-sett">--}}
{{--						EOBI Card recovered and Database updated--}}
{{--					</td>--}}
{{--					<td class="text-center print-sett">@if($eobi_card == 1) &#10003; @endif</td>--}}
{{--					<td class="text-center print-sett">@if($eobi_card == 2) &#10003; @endif</td>--}}
{{--					<td class="text-center print-sett">@if($eobi_card == 3) &#10003; @endif</td>--}}
{{--					<td class="print-sett">{{ $eobi_card_remarks }}</td>--}}
{{--				</tr>--}}
{{--				<tr>--}}
{{--					<td class="text-center print-sett">h</td>--}}
{{--					<td class="print-sett">--}}
{{--						Biometric Scan entry detection--}}
{{--					</td>--}}
{{--					<td class="text-center print-sett">@if($biometric_scan == 1) &#10003; @endif</td>--}}
{{--					<td class="text-center print-sett">@if($biometric_scan == 2) &#10003; @endif</td>--}}
{{--					<td class="text-center print-sett">@if($biometric_scan == 3) &#10003; @endif</td>--}}
{{--					<td class="print-sett">{{ $biometric_scan_remarks }}</td>--}}
{{--				</tr>--}}
				{{--<tr>--}}
					{{--<td class="text-center print-sett">i</td>--}}
					{{--<td class="print-sett">--}}
						{{--Payroll Deduction information sent to Accounts--}}
					{{--</td>--}}
					{{--<td class="text-center print-sett">@if($payroll_deduction == 1) &#10003; @endif</td>--}}
					{{--<td class="text-center print-sett">@if($payroll_deduction == 2) &#10003; @endif</td>--}}
					{{--<td class="text-center print-sett">@if($payroll_deduction == 3) &#10003; @endif</td>--}}
					{{--<td class="print-sett">{{ $payroll_deduction_remarks }}</td>--}}
				{{--</tr>--}}
				{{--<tr>--}}
					{{--<td class="text-center print-sett">j</td>--}}
					{{--<td class="print-sett">--}}
						{{--Information sent to Client if emloyee was posted at Client's site--}}
					{{--</td>--}}
					{{--<td class="text-center print-sett">@if($info_sent_to_client == 1) &#10003; @endif</td>--}}
					{{--<td class="text-center print-sett">@if($info_sent_to_client == 2) &#10003; @endif</td>--}}
					{{--<td class="text-center print-sett">@if($info_sent_to_client == 3) &#10003; @endif</td>--}}
					{{--<td class="print-sett">{{ $info_sent_to_client_remarks }}</td>--}}
				{{--</tr>--}}
				{{--<tr>--}}
					{{--<td class="text-center print-sett">k</td>--}}
					{{--<td class="print-sett">--}}
						{{--Client's Exit Checklist formalities completed--}}
					{{--</td>--}}
					{{--<td class="text-center print-sett">@if($client_exit_checklist == 1) &#10003; @endif</td>--}}
					{{--<td class="text-center print-sett">@if($client_exit_checklist == 2) &#10003; @endif</td>--}}
					{{--<td class="text-center print-sett">@if($client_exit_checklist == 3) &#10003; @endif</td>--}}
					{{--<td class="print-sett">{{ $client_exit_checklist_remarks }}</td>--}}
				{{--</tr>--}}
{{--				<tr>--}}
{{--					<td class="text-center print-sett">l</td>--}}
{{--					<td class="print-sett">--}}
{{--						Exit Interview with HR Manager (for Category lead & above only)--}}
{{--					</td>--}}
{{--					<td class="text-center print-sett">@if($exit_interview == 1) &#10003; @endif</td>--}}
{{--					<td class="text-center print-sett">@if($exit_interview == 2) &#10003; @endif</td>--}}
{{--					<td class="text-center print-sett">@if($exit_interview == 3) &#10003; @endif</td>--}}
{{--					<td class="print-sett">{{ $exit_interview_remarks }}</td>--}}
{{--				</tr>--}}
				<tr>
					<td class="text-center print-black text-bold print-sett">2</td>
					<td class="print-black text-bold print-sett text-center" colspan="6">Administration</td>
{{--					<td class="print-black print-sett"></td>--}}
{{--					<td class="print-black print-sett"></td>--}}
{{--					<td class="print-black print-sett"></td>--}}
{{--					<td class="print-black print-sett"></td>--}}
				</tr>
				<tr>
					<td class="text-center print-sett">a</td>
					<td class="print-sett">Mobile Sim received</td>
					<td class="text-center print-sett">@if($mobile_sim == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($mobile_sim == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($mobile_sim == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $mobile_sim_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">b</td>
					<td class="print-sett">
						Cellphone/Smartphone received
					</td>
					<td class="text-center print-sett">@if($cell_phone == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($cell_phone == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($cell_phone == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $cell_phone_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">c</td>
					<td class="print-sett">
						Company Car handed-over (Must do before FnF settlement to assess damage/loss if any)
					</td>
					<td class="text-center print-sett">@if($company_car == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($company_car == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($company_car == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $company_car_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">d</td>
					<td class="print-sett">
						Employment Cards received
					</td>
					<td class="text-center print-sett">@if($emp_card == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($emp_card == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($emp_card == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $emp_card_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">e</td>
					<td class="print-sett">
						Business Cards received
					</td>
					<td class="text-center print-sett">@if($business_card == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($business_card == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($business_card == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $business_card_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">f</td>
					<td class="print-sett">
						Stationary returned
					</td>
					<td class="text-center print-sett">@if($stationary_returned == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($stationary_returned == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($stationary_returned == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $stationary_returned_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">g</td>
					<td class="print-sett">
						Any other clearance
					</td>
					<td class="text-center print-sett">@if($any_other_clearance == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($any_other_clearance == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($any_other_clearance == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $any_other_clearance_remarks }}</td>
				</tr>
				{{--<tr>--}}
					{{--<td class="text-center print-sett">d</td>--}}
					{{--<td class="print-sett">--}}
						{{--Tool Kits and PPE's recovered--}}
					{{--</td>--}}
					{{--<td class="text-center print-sett">@if($toolkit_ppe == 1) &#10003; @endif</td>--}}
					{{--<td class="text-center print-sett">@if($toolkit_ppe == 2) &#10003; @endif</td>--}}
					{{--<td class="text-center print-sett">@if($toolkit_ppe == 3) &#10003; @endif</td>--}}
					{{--<td class="print-sett">{{ $toolkit_ppe_remarks }}</td>--}}
				{{--</tr>--}}
				{{--<tr>--}}
					{{--<td class="text-center print-sett">e</td>--}}
					{{--<td class="print-sett">Complete Uniform and Shoes recovered</td>--}}
					{{--<td class="text-center print-sett">@if($uniform == 1) &#10003; @endif</td>--}}
					{{--<td class="text-center print-sett">@if($uniform == 2) &#10003; @endif</td>--}}
					{{--<td class="text-center print-sett">@if($uniform == 3) &#10003; @endif</td>--}}
					{{--<td class="print-sett">{{ $uniform_remarks }}</td>--}}
				{{--</tr>--}}
				<tr>
					<td class="text-center print-black text-bold print-sett">3</td>
					<td class="print-black text-bold print-sett text-center" colspan="6">Reporting Manager/TeamLead</td>
{{--					<td class="print-black text-center print-sett"></td>--}}
{{--					<td class="print-black text-center print-sett"></td>--}}
{{--					<td class="print-black text-center print-sett"></td>--}}
{{--					<td class="print-black print-sett"></td>--}}
				</tr>
				<tr>
					<td class="text-center print-sett">a</td>
					<td class="print-sett">
						Data and documentation backed up and received
					</td>
					<td class="text-center print-sett">@if($data_document == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($data_document == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($data_document == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $data_document_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">b</td>
					<td class="print-sett">
						Knowledge transferred to team members
					</td>
					<td class="text-center print-sett">@if($knowledge_transfer == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($knowledge_transfer == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($knowledge_transfer == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $knowledge_transfer_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">c</td>
					<td class="print-sett">
						Responsiblities handed over to
					</td>
					<td class="text-center print-sett" colspan="3">@if($responsiblities_handed)  {{$responsiblities_handed}} @endif</td>

					<td class="print-sett">{{ $responsiblities_handed_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">d</td>
					<td class="print-sett">
						Last assignment completed duly
					</td>
					<td class="text-center print-sett">@if($last_assignment == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($last_assignment == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($last_assignment == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $last_assignment_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">e</td>
					<td class="print-sett">
						Client accounts information backed up
					</td>
					<td class="text-center print-sett">@if($client_acc_info == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($client_acc_info == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($client_acc_info == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $client_acc_info_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">f</td>
					<td class="print-sett">
						Informed client of employee's separation
					</td>
					<td class="text-center print-sett">@if($emp_separation == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($emp_separation == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($emp_separation == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $emp_separation_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-black text-bold print-sett">4</td>
					<td class="print-black text-bold print-sett text-center" colspan="8">Project related (PMO/Lead)</td>
{{--					<td class="print-black text-center print-sett"></td>--}}
{{--					<td class="print-black text-center print-sett"></td>--}}
{{--					<td class="print-black text-center print-sett"></td>--}}
{{--					<td class="print-black print-sett"></td>--}}
				</tr>
				<tr>
					<td class="text-center print-sett">a</td>
					<td class="print-sett">
						Revoked access from client version controlling systems
					</td>
					<td class="text-center print-sett">@if($controlling_system == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($controlling_system == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($controlling_system == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $controlling_system_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">b</td>
					<td class="print-sett">
						Revoked access from the build server
					</td>
					<td class="text-center print-sett">@if($build_server == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($build_server == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($build_server == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $build_server_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">c</td>
					<td class="print-sett">
						Revoked access from internal project management tools
					</td>
					<td class="text-center print-sett">@if($project_management == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($project_management == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($project_management == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $project_management_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">d</td>
					<td class="print-sett">
						Revoked access from client project management tools
					</td>
					<td class="text-center print-sett">@if($client_proj_manage == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($client_proj_manage == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($client_proj_manage == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $client_proj_manage_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">e</td>
					<td class="print-sett">
						Revoked access from all staging and production environments
					</td>
					<td class="text-center print-sett">@if($production_env == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($production_env == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($production_env == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $production_env_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">f</td>
					<td class="print-sett">
						Revoked access from bug tracking tools
					</td>
					<td class="text-center print-sett">@if($bug_tracking == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($bug_tracking == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($bug_tracking == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $bug_tracking_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">g</td>
					<td class="print-sett">
						Revoked access from internal application support portal
					</td>
					<td class="text-center print-sett">@if($internal_app == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($internal_app == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($internal_app == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $internal_app_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">h</td>
					<td class="print-sett">
						Revoked access from client help desk systems
					</td>
					<td class="text-center print-sett">@if($help_desk == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($help_desk == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($help_desk == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $help_desk_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">i</td>
					<td class="print-sett">
						Informed partners of employee's separation
					</td>
					<td class="text-center print-sett">@if($info_partner_sep == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($info_partner_sep == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($info_partner_sep == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $info_partner_sep_remarks }}</td>
				</tr>

				<tr>
					<td class="text-center print-black text-bold print-sett">5</td>
					<td class="print-black text-bold print-sett text-center" colspan="6">Human Resource (HR)</td>
{{--					<td class="print-black text-center print-sett"></td>--}}
{{--					<td class="print-black text-center print-sett"></td>--}}
{{--					<td class="print-black text-center print-sett"></td>--}}
{{--					<td class="print-black print-sett"></td>--}}
				</tr>
				<tr>
					<td class="text-center print-sett">a</td>
					<td class="print-sett">
						Remove from all Skype groups
					</td>
					<td class="text-center print-sett">@if($skype_group == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($skype_group == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($skype_group == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $skype_group_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">b</td>
					<td class="print-sett">
						Deactivate from HRIS System
					</td>
					<td class="text-center print-sett">@if($hris_system == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($hris_system == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($hris_system == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $hris_system_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">c</td>
					<td class="print-sett">
						Fill Exit interview form on HRIS portal
					</td>
					<td class="text-center print-sett">@if($hris_portal == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($hris_portal == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($hris_portal == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $hris_portal_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">d</td>
					<td class="print-sett">
						Insurance Card returned/Discontinued
					</td>
					<td class="text-center print-sett">@if($insurance_card_returned == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($insurance_card_returned == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($insurance_card_returned == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $insurance_card_returned_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">e</td>
					<td class="print-sett">
						Exit Questionnaire/interview conducted
					</td>
					<td class="text-center print-sett">@if($exit_questionnaire == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($exit_questionnaire == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($exit_questionnaire == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $exit_questionnaire_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">f</td>
					<td class="print-sett">
						Finalize disbursable salary related information
					</td>
					<td class="text-center print-sett">@if($finalize_disbursable_salry == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($finalize_disbursable_salry == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($finalize_disbursable_salry == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $finalize_disbursable_salry_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">g</td>
					<td class="print-sett">
						Revoked access from internal application support portal
					</td>
					<td class="text-center print-sett">@if($int_app_sup_port == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($int_app_sup_port == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($int_app_sup_port == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $int_app_sup_port_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">h</td>
					<td class="print-sett">
						Any sererance pay, and other amounts promised to be paid
					</td>
					<td class="text-center print-sett">@if($promised_paid == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($promised_paid == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($promised_paid == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $promised_paid_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">i</td>
					<td class="print-sett">
						Confirm reflection in Payroll Fluctuations indicated by HR
					</td>
					<td class="text-center print-sett">@if($confirm_reflection == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($confirm_reflection == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($confirm_reflection == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $confirm_reflection_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">j</td>
					<td class="print-sett">
						Notice Period
					</td>
					<td class="text-center print-sett" >@if($notice_period == 1) &#10003; @endif</td>
					<td class="text-center print-sett" >@if($notice_period == 2) &#10003; @endif</td>
					<td class="text-center print-sett" >@if($notice_period == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $notice_period_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">i</td>
					<td class="print-sett">
						Resignation Notification Received?
					</td>
					<td class="text-center print-sett">@if($resignation_noti_received == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($resignation_noti_received == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($resignation_noti_received == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $resignation_noti_received_remarks }}</td>
				</tr>

				<tr>
					<td class="text-center print-black text-bold print-sett">6</td>
					<td class="print-black text-bold print-sett text-center" colspan="6">Network</td>
{{--					<td class="print-black text-center print-sett"></td>--}}
{{--					<td class="print-black text-center print-sett"></td>--}}
{{--					<td class="print-black text-center print-sett"></td>--}}
{{--					<td class="print-black print-sett"></td>--}}
				</tr>
				<tr>
					<td class="text-center print-sett">a</td>
					<td class="print-sett">
						If point 1 is No,forwarder put in place to:
					</td>
					<td class="text-center print-sett" colspan="3">@if($point_1_no) {{$point_1_no}} @endif</td>

					<td class="print-sett">{{ $point_1_no_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">b</td>
					<td class="print-sett">
						Hardware received
					</td>
					<td class="text-center print-sett">@if($hardware_received == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($hardware_received == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($hardware_received == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $hardware_received_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">c</td>
					<td class="print-sett">
						Update Exit Employee Email Sheet
					</td>
					<td class="text-center print-sett">@if($email_sheet == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($email_sheet == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($email_sheet == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $email_sheet_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">d</td>
					<td class="print-sett">
						If Equipment's received (if any)
					</td>
					<td class="text-center print-sett">@if($equipment_received == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($equipment_received == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($equipment_received == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $equipment_received_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">e</td>
					<td class="print-sett">
						Internet Device received
					</td>
					<td class="text-center print-sett">@if($internet_device_received == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($internet_device_received == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($internet_device_received == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $internet_device_received_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">f</td>
					<td class="print-sett">
						Testing phone receiving
					</td>
					<td class="text-center print-sett">@if($test_phone_receiving == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($test_phone_receiving == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($test_phone_receiving == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $test_phone_receiving_remarks }}</td>
				</tr>
				<tr>
					<td class="text-center print-sett">g</td>
					<td class="print-sett">
						Deactivate from attendance software
					</td>
					<td class="text-center print-sett">@if($deactivate_attend_soft == 1) &#10003; @endif</td>
					<td class="text-center print-sett">@if($deactivate_attend_soft == 2) &#10003; @endif</td>
					<td class="text-center print-sett">@if($deactivate_attend_soft == 3) &#10003; @endif</td>
					<td class="print-sett">{{ $deactivate_attend_soft_remarks }}</td>
				</tr>


				</tbody>
            </table>
			</div>
		</div>

		<div class="row">&nbsp</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="row">


				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><p class="print-font-size"><b>For Supervisor:</b></p></div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><p class="print-font-size"><b>For HR Department:</b></p></div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><p class="print-font-size"><b>For IT and Store:</b></p></div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><p class="print-font-size"><b>For Finance:</b></p></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Sign: __________</b></p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Sign: __________</b></p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Sign: __________</b></p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<p class="print-font-size"><b>Sign: __________</b></p>
				</div>
				</div>
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
						<p class="print-font-size"><b>Name: __________</b></p>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
						<p class="print-font-size"><b>Name: __________</b></p>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
						<p class="print-font-size"><b>Name: __________</b></p>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
						<p class="print-font-size"><b>Name: __________</b></p>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<script>
    $(':radio:not(:checked)').attr('disabled', true);
</script>

