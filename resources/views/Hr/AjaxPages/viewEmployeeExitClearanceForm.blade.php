<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}

//$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName;

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\EmployeePromotion;
$m = $_GET['m'];
$currentDate = date('Y-m-d');

CommonHelper::companyDatabaseConnection($m);
$promoted_designation = EmployeePromotion::select('designation_id','emp_id')->where([['emp_id','=',$employee->emp_id],['status','=',1]])->orderBy('id', 'desc');
if($promoted_designation->count() > 0):
    $emp_designation_id = $promoted_designation->value('designation_id');
    $designation_name =  HrHelper::getMasterTableValueByIdAndColumn($m, 'designation', 'designation_name', $emp_designation_id, 'id');
else:
    $designation_name =  HrHelper::getMasterTableValueByIdAndColumn($m, 'designation', 'designation_name', $designation_id, 'id');
endif;
CommonHelper::reconnectMasterDatabase();





if ($count==1):

    $leaving_type=$exit_employee_data->leaving_type;
    $last_working_date=$exit_employee_data->last_working_date;
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
    $extra_leaves=$exit_employee_data->extra_leaves;
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
    $final_settlement_remarks=$exit_employee_data->final_settlement_remarks;
    $note = $exit_employee_data->note;


else:

    $leaving_type='';
    $last_working_date='';
    CommonHelper::companyDatabaseConnection($m);
    $supervisor_name = DB::table('employee')->select('emp_name')->where('emp_id',$employee->reporting_manager)->value('emp_name');
    CommonHelper::reconnectMasterDatabase();
    $signed_by_supervisor = '';

    $room_key='';
    $mobile_sim='';
    $fuel_card='';
    $mfm_employee_card='';
    $client_access_card='';
    $medical_insurance_card='';
    $eobi_card='';
    $biometric_scan='';
    $payroll_deduction='';
    $info_sent_to_client='';
    $client_exit_checklist='';
    $exit_interview='';

    $laptop='';
    $desktop_computer='';
    $email_account_deactivated='';
    $toolkit_ppe='';
    $uniform='';

    $advance_loan='';
    $incentive = '';
    $dues_payable='';
    $cell_phone='';
    $company_car='';
    $emp_card='';
    $business_card='';
    $stationary_returned='';
    $any_other_clearance='';
    $data_document='';
    $knowledge_transfer='';
    $responsiblities_handed='';
    $last_assignment='';
    $client_acc_info='';
    $emp_separation='';
    $controlling_system='';
    $build_server='';
    $project_management='';
    $production_env='';
    $client_proj_manage='';
    $bug_tracking='';
    $internal_app='';
    $extra_leaves='';
    $help_desk='';
    $info_partner_sep='';
    $skype_group='';
    $hris_system='';
    $hris_portal='';
    $insurance_card_returned='';
    $exit_questionnaire='';
    $finalize_disbursable_salry='';
    $int_app_sup_port='';
    $promised_paid = '';
    $confirm_reflection='';
    $notice_period = '';
    $resignation_noti_received='';
    $email_acc_deactivated='';
    $point_1_no = '';
    $hardware_received = '';
    $email_sheet = '';
    $equipment_received='';
    $internet_device_received= '';
    $test_phone_receiving='';
    $deactivate_attend_soft = '';
    $final_settlement='';


    $room_key_remarks='';
    $mobile_sim_remarks='';
    $fuel_card_remarks='';
    $mfm_employee_card_remarks='';
    $client_access_card_remarks='';
    $medical_insurance_card_remarks='';
    $eobi_card_remarks='';
    $biometric_scan_remarks='';
    $payroll_deduction_remarks='';
    $info_sent_to_client_remarks='';
    $client_exit_checklist_remarks='';
    $exit_interview_remarks='';

    $laptop_remarks='';
    $desktop_computer_remarks='';
    $email_account_deactivated_remarks='';
    $toolkit_ppe_remarks='';
    $uniform_remarks='';

    $advance_loan_remarks='';
    $incentive_remarks='';
    $dues_payable_remarks='';
    $cell_phone_remarks='';
    $company_car_remarks='';
    $emp_card_remarks = '';
    $business_card_remarks='';
    $stationary_returned_remarks='';
    $any_other_clearance_remarks = '';
    $data_document_remarks='';
    $knowledge_transfer_remarks='';
    $responsiblities_handed_remarks='';
    $last_assignment_remarks='';
    $client_acc_info_remarks='';
    $emp_separation_remarks='';
    $controlling_system_remarks='';
    $build_server_remarks='';
    $project_management_remarks='';
    $production_env_remarks='';
    $client_proj_manage_remarks='';
    $bug_tracking_remarks='';
    $internal_app_remarks='';
    $extra_leaves_remarks='';
    $help_desk_remarks='';
    $info_partner_sep_remarks='';
    $skype_group_remarks='';
    $hris_system_remarks='';
    $hris_portal_remarks='';
    $insurance_card_returned_remarks = '';
    $exit_questionnaire_remarks='';
    $finalize_disbursable_salry_remarks='';
    $int_app_sup_port_remarks='';
    $promised_paid_remarks = '';
    $confirm_reflection_remarks='';
    $notice_period_remarks = '';
    $resignation_noti_received_remarks = '';
    $email_acc_deactivated_remarks='';
    $point_1_no_remarks = '';
    $hardware_received_remarks= '';
    $email_sheet_remarks='';
    $equipment_received_remarks='';
    $internet_device_received_remarks='';
    $test_phone_receiving_remarks='';
    $deactivate_attend_soft_remarks='';
    $final_settlement_remarks='';
    $note='';


endif;
?>
<style>

    input[type="radio"],[type="checkbox"]{ width:22px;
        height:16px;
    }

</style>


<div class="row">&nbsp;</div>
<div class="row" style="background-color: gainsboro">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <h4 style="text-decoration: underline;font-weight: bold;">Exit Clearance Form</h4>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-left">

    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
    </div>
</div>
<div class="row">&nbsp;</div>
<form method="post" action="{{url('had/addEmployeeExitClearanceDetail')}}">
    <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
    <input type="hidden" name="company_id" id="company_id" value="<?php echo $m ?>">

    <input type="hidden" name="save_update" value="<?php echo $count; ?>">


    <input type="hidden" name="emp_id" value="<?php echo $emp_id; ?>">

<div class="row">

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label>Designation:</label>
            <input readonly name="designation" id="designation" type="text" value="{{ $designation_name }}" class="form-control">
        </div>
    </div>



    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label>Supervisor Name:</label>
            <input name="supervisor_name" id="supervisor_name" name="supervisor_name" type="text" value="{{ $supervisor_name }}" class="form-control">
        </div>
    </div>



</div>

<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="form-group">
            <label>Leaving Reason :</label>
            <select id="leaving_type" name="leaving_type" class="form-control">
                <option value="6" @if($leaving_type==6)selected @endif>Contract End:</option>
                <option value="1" @if($leaving_type==1)selected @endif>Resignation</option>
                <option value="2" @if($leaving_type==2)selected @endif>Retirement</option>
                <option value="3" @if($leaving_type==3)selected @endif>Termination</option>
                <option value="4" @if($leaving_type==4)selected @endif>Dismissal</option>
                <option value="5" @if($leaving_type==5)selected @endif>Demise</option>
            </select>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="form-group">
            <label>Last Working Data:</label>
            <input name="last_working_date" id="last_working_date" type="date" value="{{ $last_working_date }}" class="form-control requiredField"/>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <label>Signed by supervisor:</label>
        <br>
        <b><input @if($signed_by_supervisor=='yes')checked @endif  value="yes" type="radio" name="signed_by_supervisor"> Yes</b> &nbsp &nbsp
        <b><input @if($signed_by_supervisor=='no')checked @endif  value="no" type="radio" name="signed_by_supervisor"> No</b>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-sm mb-0 table-bordered table-striped table-hover">
        <thead class="table-row-heading">
        <tr>
            <th scope="col" class="text-center"><h4>DEPARTMENT</h4></th>
            <th scope="col" class="text-center"><h4>VERIFICATION</h4></th>
            <th scope="col" class="text-center"><h4>STATUS</h4></th>
            <th scope="col" class="text-center"><h4>REMARKS</h4></th>
        </tr>
        </thead>
        {{--finance department start--}}
        <tbody>
        <tr>
            <td rowspan="5" class="text-center">
                <h3>FINANCE <br/> DEPARTMENT</h3>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Loans/Advances or any dues receiveable from the employee
            </td>
            <td class="text-center">
                <b><input @if($advance_loan==1) checked @endif type="radio" name="advance_loan" value="1"> Yes </b>
                <b><input @if($advance_loan==2) checked @endif type="radio" name="advance_loan" value="2"> No </b><br>
                <b><input @if($advance_loan==3) checked @endif type="radio" name="advance_loan" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="advance_loan_remarks"> @if($advance_loan_remarks!='') {{trim($advance_loan_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Incentive/Commission to be paid to the employee
            </td>
            <td class="text-center">
                <b><input @if($incentive==1) checked @endif type="radio" name="incentive" value="1"> Yes </b>
                <b><input @if($incentive==2) checked @endif type="radio" name="incentive" value="2"> No </b><br>
                <b><input @if($incentive==3) checked @endif type="radio" name="incentive" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="incentive_remarks"> @if($incentive_remarks!='') {{trim($incentive_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Dues payable by Innovative network
            </td>
            <td class="text-center">
                <b><input @if($dues_payable==1) checked @endif type="radio" name="dues_payable" value="1"> Yes </b>
                <b><input @if($dues_payable==2) checked @endif type="radio" name="dues_payable" value="2"> No </b><br>
                <b><input @if($dues_payable==3) checked @endif type="radio" name="dues_payable" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="dues_payable_remarks"> @if($dues_payable_remarks!='') {{trim($dues_payable_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Any other dues receivable from employee
            </td>
            <td class="text-center">
                <b><input @if($final_settlement==1) checked @endif type="radio" name="final_settlement" value="1"> Yes </b>
                <b><input @if($final_settlement==2) checked @endif type="radio" name="final_settlement" value="2"> No </b><br>
                <b><input @if($final_settlement==3) checked @endif type="radio" name="final_settlement" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="final_settlement_remarks"> @if($final_settlement_remarks!='') {{trim($final_settlement_remarks)}} @endif </textarea>
            </td>
        </tr>
        </tbody>
        {{--finance department end--}}
        {{-- admin department start--}}
        <tbody>
        <tr>
            <td rowspan="13" class="text-center">
                <h4>Administration</h4>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Mobile Sim received

            </td>
            <td class="text-center">
                <input @if($mobile_sim==1) checked @endif type="radio" name="mobile_sim" value="1"> Yes
                <input @if($mobile_sim==2) checked @endif type="radio" name="mobile_sim" value="2"> No <br>
                <input @if($mobile_sim==3) checked @endif type="radio" name="mobile_sim" value="3"> N/A
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="mobile_sim_remarks"> @if($mobile_sim_remarks!='') {{trim($mobile_sim_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Cellphone/Smartphone received
            </td>
            <td class="text-center">
                <b><input @if($cell_phone==1) checked @endif type="radio" name="cell_phone" value="1"> Yes </b>
                <b><input @if($cell_phone==2) checked @endif type="radio" name="cell_phone" value="2"> No </b><br>
                <b><input @if($cell_phone==3) checked @endif type="radio" name="cell_phone" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="cell_phone_remarks"> @if($cell_phone_remarks!='') {{trim($cell_phone_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Company Car handed-over (Must do before FnF settlement to assess damage/loss if any)
            </td>
            <td class="text-center">
                <b><input @if($company_car==1) checked @endif type="radio" name="company_car" value="1"> Yes </b>
                <b><input @if($company_car==2) checked @endif type="radio" name="company_car" value="2"> No </b><br>
                <b><input @if($company_car==3) checked @endif type="radio" name="company_car" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="company_car_remarks"> @if($company_car_remarks!='') {{trim($company_car_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Employment Cards received
            </td>
            <td class="text-center">
                <b><input @if($emp_card==1) checked @endif type="radio" name="emp_card" value="1"> Yes </b>
                <b><input @if($emp_card==2) checked @endif type="radio" name="emp_card" value="2"> No </b><br>
                <b><input @if($emp_card==3) checked @endif type="radio" name="emp_card" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="emp_card_remarks"> @if($emp_card_remarks!='') {{trim($emp_card_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Business Cards received
            </td>
            <td class="text-center">
                <b><input @if($business_card==1) checked @endif type="radio" name="business_card" value="1"> Yes </b>
                <b><input @if($business_card==2) checked @endif type="radio" name="business_card" value="2"> No </b><br>
                <b><input @if($business_card==3) checked @endif type="radio" name="business_card" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="business_card_remarks"> @if($business_card_remarks!='') {{trim($business_card_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Stationary returned
            </td>
            <td class="text-center">
                <b><input @if($stationary_returned==1) checked @endif type="radio" name="stationary_returned" value="1"> Yes </b>
                <b><input @if($stationary_returned==2) checked @endif type="radio" name="stationary_returned" value="2"> No </b><br>
                <b><input @if($stationary_returned==3) checked @endif type="radio" name="stationary_returned" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="stationary_returned_remarks"> @if($stationary_returned_remarks!='') {{trim($stationary_returned_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Any other clearance
            </td>
            <td class="text-center">
                <b><input @if($any_other_clearance==1) checked @endif type="radio" name="any_other_clearance" value="1"> Yes </b>
                <b><input @if($any_other_clearance==2) checked @endif type="radio" name="any_other_clearance" value="2"> No </b><br>
                <b><input @if($any_other_clearance==3) checked @endif type="radio" name="any_other_clearance" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="any_other_clearance_remarks"> @if($any_other_clearance_remarks!='') {{trim($any_other_clearance_remarks)}} @endif </textarea>
            </td>
        </tr>
        </tbody>
        {{--admin department end --}}

        {{--Reporting manager teamlead start--}}
        <tbody>
        <tr>
            <td rowspan="7" class="text-center">
                <h4>Reporting Manager/TeamLead </h4>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Data and documentation backed up and received
            </td>
            <td class="text-center">
                <b><input @if($data_document==1) checked @endif type="radio" name="data_document" value="1"> Yes </b>
                <b><input @if($data_document==2) checked @endif type="radio" name="data_document" value="2"> No </b><br>
                <b><input @if($data_document==3) checked @endif type="radio" name="data_document" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="data_document_remarks"> @if($data_document_remarks!='') {{trim($data_document_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Knowledge transferred to team members
            </td>
            <td class="text-center">
                <b><input @if($knowledge_transfer==1) checked @endif type="radio" name="knowledge_transfer" value="1"> Yes </b>
                <b><input @if($knowledge_transfer==2) checked @endif type="radio" name="knowledge_transfer" value="2"> No </b><br>
                <b><input @if($knowledge_transfer==3) checked @endif type="radio" name="knowledge_transfer" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="knowledge_transfer_remarks"> @if($knowledge_transfer_remarks!='') {{trim($knowledge_transfer_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Responsiblities handed over to
            </td>
            <td class="text-center">
                <b><input class="form-control"  type="text" name="responsiblities_handed" value="{{$responsiblities_handed}}">  </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="responsiblities_handed_remarks"> @if($responsiblities_handed_remarks!='') {{trim($responsiblities_handed_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Last assignment completed duly
            </td>
            <td class="text-center">
                <b><input @if($last_assignment==1) checked @endif type="radio" name="last_assignment" value="1"> Yes </b>
                <b><input @if($last_assignment==2) checked @endif type="radio" name="last_assignment" value="2"> No </b><br>
                <b><input @if($last_assignment==3) checked @endif type="radio" name="last_assignment" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="last_assignment_remarks"> @if($last_assignment_remarks!='') {{trim($last_assignment_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Client accounts information backed up
            </td>
            <td class="text-center">
                <b><input @if($client_acc_info==1) checked @endif type="radio" name="client_acc_info" value="1"> Yes </b>
                <b><input @if($client_acc_info==2) checked @endif type="radio" name="client_acc_info" value="2"> No </b><br>
                <b><input @if($client_acc_info==3) checked @endif type="radio" name="client_acc_info" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="client_acc_info_remarks"> @if($client_acc_info_remarks!='') {{trim($client_acc_info_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Informed client of employee's separation
            </td>
            <td class="text-center">
                <b><input @if($emp_separation==1) checked @endif type="radio" name="emp_separation" value="1"> Yes </b>
                <b><input @if($emp_separation==2) checked @endif type="radio" name="emp_separation" value="2"> No </b><br>
                <b><input @if($emp_separation==3) checked @endif type="radio" name="emp_separation" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="emp_separation_remarks"> @if($emp_separation_remarks!='') {{trim($emp_separation_remarks)}} @endif </textarea>
            </td>
        </tr>
        </tbody>
        {{--reporting manager teamlead end--}}

        {{--Project related lead start--}}
        <tbody>
        <tr>
            <td rowspan="10" class="text-center">
                <h4>Project related (PMO/Lead) </h4>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Revoked access from client version controlling systems
            </td>
            <td class="text-center">
                <b><input @if($controlling_system==1) checked @endif type="radio" name="controlling_system" value="1"> Yes </b>
                <b><input @if($controlling_system==2) checked @endif type="radio" name="controlling_system" value="2"> No </b><br>
                <b><input @if($controlling_system==3) checked @endif type="radio" name="controlling_system" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="controlling_system_remarks"> @if($controlling_system_remarks!='') {{trim($controlling_system_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Revoked access from the build server
            </td>
            <td class="text-center">
                <b><input @if($build_server==1) checked @endif type="radio" name="build_server" value="1"> Yes </b>
                <b><input @if($build_server==2) checked @endif type="radio" name="build_server" value="2"> No </b><br>
                <b><input @if($build_server==3) checked @endif type="radio" name="build_server" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="build_server_remarks"> @if($build_server_remarks!='') {{trim($build_server_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Revoked access from internal project management tools
            </td>
            <td class="text-center">
                <b><input @if($project_management==1) checked @endif type="radio" name="project_management" value="1"> Yes </b>
                <b><input @if($project_management==2) checked @endif type="radio" name="project_management" value="2"> No </b><br>
                <b><input @if($project_management==3) checked @endif type="radio" name="project_management" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="project_management_remarks"> @if($project_management_remarks!='') {{trim($project_management_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Revoked access from client project management tools
            </td>
            <td class="text-center">
                <b><input @if($client_proj_manage==1) checked @endif type="radio" name="client_proj_manage" value="1"> Yes </b>
                <b><input @if($client_proj_manage==2) checked @endif type="radio" name="client_proj_manage" value="2"> No </b><br>
                <b><input @if($client_proj_manage==3) checked @endif type="radio" name="client_proj_manage" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="client_proj_manage_remarks"> @if($client_proj_manage_remarks!='') {{trim($client_proj_manage_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Revoked access from all staging and production environments
            </td>
            <td class="text-center">
                <b><input @if($production_env==1) checked @endif type="radio" name="production_env" value="1"> Yes </b>
                <b><input @if($production_env==2) checked @endif type="radio" name="production_env" value="2"> No </b><br>
                <b><input @if($production_env==3) checked @endif type="radio" name="production_env" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="production_env_remarks"> @if($production_env_remarks!='') {{trim($production_env_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Revoked access from bug tracking tools
            </td>
            <td class="text-center">
                <b><input @if($bug_tracking==1) checked @endif type="radio" name="bug_tracking" value="1"> Yes </b>
                <b><input @if($bug_tracking==2) checked @endif type="radio" name="bug_tracking" value="2"> No </b><br>
                <b><input @if($bug_tracking==3) checked @endif type="radio" name="bug_tracking" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="bug_tracking_remarks"> @if($bug_tracking_remarks!='') {{trim($bug_tracking_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Revoked access from internal application support portal
            </td>
            <td class="text-center">
                <b><input @if($internal_app==1) checked @endif type="radio" name="internal_app" value="1"> Yes </b>
                <b><input @if($internal_app==2) checked @endif type="radio" name="internal_app" value="2"> No </b><br>
                <b><input @if($internal_app==3) checked @endif type="radio" name="internal_app" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="internal_app_remarks"> @if($internal_app_remarks!='') {{trim($internal_app_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Revoked access from client help desk systems
            </td>
            <td class="text-center">
                <b><input @if($help_desk==1) checked @endif type="radio" name="help_desk" value="1"> Yes </b>
                <b><input @if($help_desk==2) checked @endif type="radio" name="help_desk" value="2"> No </b><br>
                <b><input @if($help_desk==3) checked @endif type="radio" name="help_desk" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="help_desk_remarks"> @if($help_desk_remarks!='') {{trim($help_desk_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Informed partners of employee's separation
            </td>
            <td class="text-center">
                <b><input @if($info_partner_sep==1) checked @endif type="radio" name="info_partner_sep" value="1"> Yes </b>
                <b><input @if($info_partner_sep==2) checked @endif type="radio" name="info_partner_sep" value="2"> No </b><br>
                <b><input @if($info_partner_sep==3) checked @endif type="radio" name="info_partner_sep" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="info_partner_sep_remarks"> @if($info_partner_sep_remarks!='') {{trim($info_partner_sep_remarks)}} @endif </textarea>
            </td>
        </tr>
        </tbody>
        {{-- Project realated lead end--}}

        {{--Hr  start--}}
        <tbody>
        <tr>
            <td rowspan="12" class="text-center">
                <h4>Human Resource (HR) </h4>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Remove from all Skype groups
            </td>
            <td class="text-center">
                <b><input @if($skype_group==1) checked @endif type="radio" name="skype_group" value="1"> Yes </b>
                <b><input @if($skype_group==2) checked @endif type="radio" name="skype_group" value="2"> No </b><br>
                <b><input @if($skype_group==3) checked @endif type="radio" name="skype_group" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="skype_group_remarks"> @if($skype_group_remarks!='') {{trim($skype_group_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Deactivate from HRIS System
            </td>
            <td class="text-center">
                <b><input @if($hris_system==1) checked @endif type="radio" name="hris_system" value="1"> Yes </b>
                <b><input @if($hris_system==2) checked @endif type="radio" name="hris_system" value="2"> No </b><br>
                <b><input @if($hris_system==3) checked @endif type="radio" name="hris_system" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="hris_system_remarks"> @if($hris_system_remarks!='') {{trim($hris_system_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Fill Exit interview form on HRIS portal
            </td>
            <td class="text-center">
                <b><input @if($hris_portal==1) checked @endif type="radio" name="hris_portal" value="1"> Yes </b>
                <b><input @if($hris_portal==2) checked @endif type="radio" name="hris_portal" value="2"> No </b><br>
                <b><input @if($hris_portal==3) checked @endif type="radio" name="hris_portal" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="hris_portal_remarks"> @if($hris_portal_remarks!='') {{trim($hris_portal_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Insurance Card returned/Discontinued
            </td>
            <td class="text-center">
                <b><input @if($insurance_card_returned==1) checked @endif type="radio" name="insurance_card_returned" value="1"> Yes </b>
                <b><input @if($insurance_card_returned==2) checked @endif type="radio" name="insurance_card_returned" value="2"> No </b><br>
                <b><input @if($insurance_card_returned==3) checked @endif type="radio" name="insurance_card_returned" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="insurance_card_returned_remarks"> @if($insurance_card_returned_remarks!='') {{trim($insurance_card_returned_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Exit Questionnaire/interview conducted
            </td>
            <td class="text-center">
                <b><input @if($exit_questionnaire==1) checked @endif type="radio" name="exit_questionnaire" value="1"> Yes </b>
                <b><input @if($exit_questionnaire==2) checked @endif type="radio" name="exit_questionnaire" value="2"> No </b><br>
                <b><input @if($exit_questionnaire==3) checked @endif type="radio" name="exit_questionnaire" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="exit_questionnaire_remarks"> @if($exit_questionnaire_remarks!='') {{trim($exit_questionnaire_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Finalize disbursable salary related information
            </td>
            <td class="text-center">
                <b><input @if($finalize_disbursable_salry==1) checked @endif type="radio" name="finalize_disbursable_salry" value="1"> Yes </b>
                <b><input @if($finalize_disbursable_salry==2) checked @endif type="radio" name="finalize_disbursable_salry" value="2"> No </b><br>
                <b><input @if($finalize_disbursable_salry==3) checked @endif type="radio" name="finalize_disbursable_salry" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="finalize_disbursable_salry_remarks"> @if($finalize_disbursable_salry_remarks!='') {{trim($finalize_disbursable_salry_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Revoked access from internal application support portal
            </td>
            <td class="text-center">
                <b><input @if($int_app_sup_port==1) checked @endif type="radio" name="int_app_sup_port" value="1"> Yes </b>
                <b><input @if($int_app_sup_port==2) checked @endif type="radio" name="int_app_sup_port" value="2"> No </b><br>
                <b><input @if($int_app_sup_port==3) checked @endif type="radio" name="int_app_sup_port" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="int_app_sup_port_remarks"> @if($int_app_sup_port_remarks!='') {{trim($int_app_sup_port_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
               Any sererance pay, and other amounts promised to be paid
            </td>
            <td class="text-center">
                <b><input @if($promised_paid==1) checked @endif type="radio" name="promised_paid" value="1"> Yes </b>
                <b><input @if($promised_paid==2) checked @endif type="radio" name="promised_paid" value="2"> No </b><br>
                <b><input @if($promised_paid==3) checked @endif type="radio" name="promised_paid" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="promised_paid_remarks"> @if($promised_paid_remarks!='') {{trim($promised_paid_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Confirm reflection in Payroll Fluctuations indicated by HR
            </td>
            <td class="text-center">
                <b><input @if($confirm_reflection==1) checked @endif type="radio" name="confirm_reflection" value="1"> Yes </b>
                <b><input @if($confirm_reflection==2) checked @endif type="radio" name="confirm_reflection" value="2"> No </b><br>
                <b><input @if($confirm_reflection==3) checked @endif type="radio" name="confirm_reflection" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="confirm_reflection_remarks"> @if($confirm_reflection_remarks!='') {{trim($confirm_reflection_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Notice Period
            </td>
            <td class="text-center">
                <b><input @if($notice_period==1) checked @endif type="radio" name="notice_period" value="1"> Served in Full </b>
                <b><input @if($notice_period==2) checked @endif type="radio" name="notice_period" value="2"> Waived off </b><br>

            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="notice_period_remarks"> @if($notice_period_remarks!='') {{trim($notice_period_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Resignation Notification Received?
            </td>
            <td class="text-center">
                <b><input @if($resignation_noti_received==1) checked @endif type="radio" name="resignation_noti_received" value="1"> Yes </b>
                <b><input @if($resignation_noti_received==2) checked @endif type="radio" name="resignation_noti_received" value="2"> No </b><br>
                <b><input @if($resignation_noti_received==3) checked @endif type="radio" name="resignation_noti_received" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="resignation_noti_received_remarks"> @if($resignation_noti_received_remarks!='') {{trim($resignation_noti_received_remarks)}} @endif </textarea>
            </td>
        </tr>
        </tbody>
        {{-- Hr end--}}
        {{--Network  start--}}
        <tbody>
        <tr>
            <td rowspan="9" class="text-center">
                <h4>Network </h4>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Email Account deactivated
            </td>
            <td class="text-center">
                <b><input @if($email_acc_deactivated==1) checked @endif type="radio" name="email_acc_deactivated" value="1"> Yes </b>
                <b><input @if($email_acc_deactivated==2) checked @endif type="radio" name="email_acc_deactivated" value="2"> No </b><br>
                <b><input @if($email_acc_deactivated==3) checked @endif type="radio" name="email_acc_deactivated" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="email_acc_deactivated_remarks"> @if($email_acc_deactivated_remarks!='') {{trim($email_acc_deactivated_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                If point 1 is No,forwarder put in place to:
            </td>
            <td class="text-center">
                <b><input  class="form-control" type="text" name="point_1_no" value="{{$point_1_no}}">  </b>

            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="point_1_no_remarks"> @if($point_1_no_remarks!='') {{trim($point_1_no_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Hardware received
            </td>
            <td class="text-center">
                <b><input @if($hardware_received==1) checked @endif type="radio" name="hardware_received" value="1"> Yes </b>
                <b><input @if($hardware_received==2) checked @endif type="radio" name="hardware_received" value="2"> No </b><br>
                <b><input @if($hardware_received==3) checked @endif type="radio" name="hardware_received" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="hardware_received_remarks"> @if($hardware_received_remarks!='') {{trim($hardware_received_remarks)}} @endif </textarea>
            </td>
        </tr>

        <tr>
            <td class="text-center">
                Update Exit Employee Email Sheet
            </td>
            <td class="text-center">
                <b><input @if($email_sheet==1) checked @endif type="radio" name="email_sheet" value="1"> Yes </b>
                <b><input @if($email_sheet==2) checked @endif type="radio" name="email_sheet" value="2"> No </b><br>
                <b><input @if($email_sheet==3) checked @endif type="radio" name="email_sheet" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="email_sheet_remarks"> @if($email_sheet_remarks!='') {{trim($email_sheet_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                If Equipment's received (if any)
            </td>
            <td class="text-center">
                <b><input @if($equipment_received==1) checked @endif type="radio" name="equipment_received" value="1"> Yes </b>
                <b><input @if($equipment_received==2) checked @endif type="radio" name="equipment_received" value="2"> No </b><br>
                <b><input @if($equipment_received==3) checked @endif type="radio" name="equipment_received" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="equipment_received_remarks"> @if($equipment_received_remarks!='') {{trim($equipment_received_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Internet Device received
            </td>
            <td class="text-center">
                <b><input @if($internet_device_received==1) checked @endif type="radio" name="internet_device_received" value="1"> Yes </b>
                <b><input @if($internet_device_received==2) checked @endif type="radio" name="internet_device_received" value="2"> No </b><br>
                <b><input @if($internet_device_received==3) checked @endif type="radio" name="internet_device_received" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="internet_device_received_remarks"> @if($internet_device_received_remarks!='') {{trim($internet_device_received_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Testing phone receiving
            </td>
            <td class="text-center">
                <b><input @if($test_phone_receiving==1) checked @endif type="radio" name="test_phone_receiving" value="1"> Yes </b>
                <b><input @if($test_phone_receiving==2) checked @endif type="radio" name="test_phone_receiving" value="2"> No </b><br>
                <b><input @if($test_phone_receiving==3) checked @endif type="radio" name="test_phone_receiving" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="test_phone_receiving_remarks"> @if($test_phone_receiving_remarks!='') {{trim($test_phone_receiving_remarks)}} @endif </textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                Deactivate from attendance software
            </td>
            <td class="text-center">
                <b><input @if($deactivate_attend_soft==1) checked @endif type="radio" name="deactivate_attend_soft" value="1"> Yes </b>
                <b><input @if($deactivate_attend_soft==2) checked @endif type="radio" name="deactivate_attend_soft" value="2"> No </b><br>
                <b><input @if($deactivate_attend_soft==3) checked @endif type="radio" name="deactivate_attend_soft" value="3"> N/A </b>
            </td>
            <td class="text-center">
                <textarea class="form-control" rows="2" name="deactivate_attend_soft_remarks"> @if($deactivate_attend_soft_remarks!='') {{trim($deactivate_attend_soft_remarks)}} @endif </textarea>
            </td>
        </tr>


        </tbody>
        {{-- Network end--}}
    </table>

    <div>
        <label for=""> Note :</label><br>
        <textarea style="max-width: 100%" class="form-control" name="note" id="note" >@if($note!='') {{trim($note)}} @endif</textarea>
    </div>
</div>

<br>
<div style="float: right;">
    <button style="text-align: center" class="btn btn-sm btn-success" type="submit" value="Submit">Submit</button>
</div>
</form>








