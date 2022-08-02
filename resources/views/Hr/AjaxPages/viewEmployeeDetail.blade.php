<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
$day_off = explode("=>",$employee_detail->day_off);



use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\EmployeePromotion;

?>

<style>

    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }


    .fileContainer [type=file] {
        cursor: inherit;
        display: block;
        font-size: 999px;
        filter: alpha(opacity=0);
        min-height: 100%;
        min-width: 100%;
        opacity: 0;
        position: absolute;
        right: 0;
        text-align: right;
        top: 0;
    }


    .fileContainer [type=file] {
        cursor: pointer;
    }

    hr{border-top: 1px solid cadetblue}
    /*td{ padding: 0px !important;}*/
    /*th{ padding: 0px !important;}*/
    .img-circle {
        width: 150px;
        height: 150px;
        border: 2px solid #ccc;
        padding: 4px;
        border-radius: 50%;
        margin-bottom: 32px;
        margin-top: -78px;
        z-index: 10000000;}



</style>




<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-right">
                    <?php echo CommonHelper::displayPrintButtonInBlade('printEmployeeDetail','','1');?>
                </div>
            </div>
            <div class="row">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="company_id" value="<?=Input::get('m')?>">
                <input type="hidden" name="login_check" value="<?=$employee_detail->can_login?>">
                <input type="hidden" name="emr_no" value="<?=$employee_detail->emr_no?>">

                <?php $acc_pass = DB::table('users')->select('password')->where([['emp_id','=',$employee_detail->emp_id]])->value('password'); ?>
                <input type="hidden" name="old_password" value="<?=$acc_pass?>">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel" id="printEmployeeDetail">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <div class="hr-border" style="border: 1px solid #e5e5e5b0; margin-top: 89px;" ></div>
                                <img id="img_file_1" class="img-circle" src="{{ url($employee_detail->img_path != '' ? 'storage/'.$employee_detail->img_path  : 'storage/app/uploads/employee_images/user-dummy.png') }} ">
                            </div>

                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: #f5f3ff">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <h4  style="font-weight: bold;margin-top: 10px;">Basic Information</h4>
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row table-responsive">
                                    <table class="table table-sm mb-0 table-bordered">
                                        <thead>
                                        <th class="text-center" colspan="1"> EMP-ID </th>
                                        <td class="text-center" colspan="2"><?=$employee_detail->emp_id?></td>
                                        <th class="text-center " colspan="1">Employee Name</th>
                                        <td class="text-center" colspan="2"><?=$employee_detail->emp_name?></td>

                                        </thead>
                                        <thead>
                                        <th class="text-center" colspan="1">Faher Name</th>
                                        <td class="text-center" colspan="2"><?=$employee_detail->emp_father_name?></td>
                                        <th class="text-center" colspan="1">DOB</th>
                                        <td class="text-center" colspan="2"><?=HrHelper::date_format($employee_detail->emp_date_of_birth) ?></td>

                                        </thead>

                                        <thead>
                                        <th colspan="1" class="text-center">Dep/Sub Dep</th>
                                        <td colspan="5" class="text-center">
                                            {{ HrHelper::getMasterTableValueByIdAndColumn($m, 'department', 'department_name', $employee_detail->emp_department_id, 'id') }}
                                            /
                                            {{ HrHelper::getMasterTableValueByIdAndColumn($m, 'sub_department', 'sub_department_name', $employee_detail->emp_sub_department_id, 'id') }}
                                        </td>
                                        </thead>

                                        <thead>
                                        <th class="text-center" colspan="1">Birth Place</th>
                                        <td class="text-center" colspan="2"><?=$employee_detail->emp_place_of_birth?></td>
                                        <th class="text-center" colspan="1">Status</th>
                                        <td class="text-center" colspan="2">
                                            {{ HrHelper::getMasterTableValueByIdAndColumn($m, 'job_type', 'job_type_name', $employee_detail->emp_employementstatus_id, 'id') }}
                                        </td>
                                        </thead>

                                        <thead>

                                        <th class="text-center" colspan="1">Join Date</th>
                                        <td class="text-center" colspan="2"><?=HrHelper::date_format($employee_detail->emp_joining_date)?></td>
                                        <th class="text-center" colspan="1">Nationality</th>
                                        <td class="text-center" colspan="2"><?=$employee_detail->nationality?></td>
                                        </thead>

                                        <thead>

                                        <th class="text-center" colspan="1"> Gender </th>
                                        <td class="text-center" colspan="2">
                                            @if($employee_detail->emp_gender == 1) {{ 'Male' }} @endif
                                            @if($employee_detail->emp_gender == 2) {{ 'Female' }} @endif
                                        </td>
                                        <th class="text-center" colspan="1"> Marital Status </th>
                                        <td class="text-center" colspan="2">
                                            {{ HrHelper::getMasterTableValueByIdAndColumn($m, 'marital_status', 'marital_status_name', $employee_detail->emp_marital_status, 'id') }}
                                        </td>
                                        </thead>

                                        <thead>
                                        <th class="text-center" colspan="1">Email</th>
                                        <td class="text-center" colspan="5"><?=$employee_detail->emp_email ?></td>

                                        </thead>



                                        <thead>
                                        <th class="text-center" colspan="1">CNIC</th>
                                        <td class="text-center" colspan="2"><?=$employee_detail->emp_cnic ?></td>
                                        <th class="text-center" colspan="1">CNIC Expiry Date</th>
                                        <td class="text-center" colspan="2">
                                            @if($employee_detail->life_time_cnic == 1)
                                                Life Time Validity
                                            @else
                                                <?=HrHelper::date_format($employee_detail->emp_cnic_expiry_date) ?>
                                            @endif

                                        </td>
                                        </thead>

                                        <thead>

                                        <th class="text-center" colspan="1">Office No#</th>
                                        <td class="text-center" colspan="4"><?=$employee_detail->contact_office !='' ? $employee_detail->contact_office : "--" ?></td>
                                        </thead>


                                        <thead>
                                        <th class="text-center" colspan="1">Cell No</th>
                                        <td class="text-center" colspan="2"><?=$employee_detail->emp_contact_no?></td>
                                        <th class="text-center" colspan="1">Home No#</th>
                                        <td class="text-center" colspan="2"><?=$employee_detail->contact_home !='' ? $employee_detail->contact_home : "--" ?></td>
                                        </thead>


                                        <thead>
                                        <th class="text-center" colspan="1"> Emergency No# </th>
                                        <td class="text-center" colspan="4">
                                            <?=$employee_detail->emergency_no != '' ? $employee_detail->emergency_no : "--" ?>
                                        </td>
                                        </thead>
                                        <thead>
                                        <th class="text-center" colspan="1"> Emergency Person's Name </th>
                                        <td class="text-center" colspan="2">
                                            <?=$employee_detail->emp_emergency_relation_name != '' ? $employee_detail->emp_emergency_relation_name : "--" ?>
                                        </td>
                                        <th class="text-center" colspan="1"> Emergency Person's Relation  </th>
                                        <td class="text-center" colspan="2">
                                            <?=$employee_detail->emp_emergency_relation != '' ? $employee_detail->emp_emergency_relation : "--" ?>
                                        </td>
                                        </thead>



                                        <thead>

                                        <th class="text-center" colspan="1"> Emergency Person's Address </th>
                                        <td class="text-center" colspan="4">
                                            <?=$employee_detail->emp_emergency_relation_address != '' ? $employee_detail->emp_emergency_relation_address : "--" ?>
                                        </td>

                                        </thead>



                                        <thead>
                                        <th class="text-center" colspan="1"> Religion </th>
                                        <td class="text-center" colspan="2"><?=$employee_detail->relegion ?></td>
                                        <th class="text-center"  colspan="1"> Joining Salary </th>
                                        <td class="text-center"  colspan="2">
                                            @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                                {{ number_format($employee_detail->emp_salary,0) }}
                                            @else
                                                ******
                                            @endif

                                        </td>

                                        </thead>


                                        <thead>
                                        <th class="text-center" colspan="1"> Current Salary </th>
                                        <td class="text-center" colspan="2">
                                            <?php
                                            CommonHelper::companyDatabaseConnection($m);
                                            $promoted_salary = EmployeePromotion::select('salary','emp_id')->where([['emp_id','=',$employee_detail->emp_id],['status','=',1]])->orderBy('id', 'desc');
                                            if($promoted_salary->count() > 0):
                                                $emp_salary = $promoted_salary->value('salary');
                                            else:
                                                $emp_salary = $employee_detail->emp_salary;
                                            endif;

                                            CommonHelper::reconnectMasterDatabase();
                                            ?>
                                            @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                                {{ number_format($emp_salary,0) }}
                                            @else
                                                ******
                                            @endif
                                        </td>
                                        <th class="text-center" colspan="1"> Designation </th>
                                        <td class="text-center" colspan="2">
                                            <?php
                                            CommonHelper::companyDatabaseConnection($m);
                                            $promoted_designation = EmployeePromotion::select('designation_id','emp_id')->where([['emp_id','=',$employee_detail->emp_id],['status','=',1]])->orderBy('id', 'desc');
                                            if($promoted_designation->count() > 0):
                                                $emp_designation_id = $promoted_designation->value('designation_id');
                                                echo HrHelper::getMasterTableValueByIdAndColumn($m, 'designation', 'designation_name', $emp_designation_id, 'id');
                                            else:
                                                echo HrHelper::getMasterTableValueByIdAndColumn($m, 'designation', 'designation_name', $employee_detail->designation_id, 'id');
                                            endif;
                                            CommonHelper::reconnectMasterDatabase();
                                            ?>
                                        </td>
                                        </thead>



                                        <thead>
                                        <th class="text-center" colspan="1"> Tax </th>
                                        <td class="text-center" colspan="2">
                                            {{ $employee_detail->tax_id != '' ? HrHelper::getMasterTableValueByIdAndColumn($m, 'tax', 'tax_name', $employee_detail->tax_id, 'id') : "--" }}
                                        </td>
                                        <th class="text-center"> Eobi </th>
                                        <td class="text-center">
                                            {{ $employee_detail->eobi_id !='' ? HrHelper::getMasterTableValueByIdAndColumn($m, 'eobi', 'EOBI_name', $employee_detail->eobi_id, 'id') : "--" }}
                                        </td>

                                        </thead>

                                        <thead>
                                        <th class="text-center" colspan="1"> Day Off </th>
                                        <td class="text-center" colspan="5">
                                            <?php for($i =0; $i < count($day_off)- 1; $i++){ ?>
												<?php echo '*'.$day_off[$i]?>
											<?php } ?>
                                        </td>
                                        </thead>

                                    </table>
                                </div>
                            </div>

                            {{--new employee details--}}

                            <div class="row" style="background-color: #f5f3ff">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <h4 style="font-weight: bold;margin-top: 10px;">Address</h4>
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table style="table-layout: fixed;" class="table table-sm mb-0 table-bordered">
                                        <thead>
                                        <th> Current Address </th>
                                        <td class="text-center">
                                            {{ $employee_detail->residential_address!='' ?$employee_detail->residential_address :"--" }}
                                        </td>
                                        </thead>
                                        <thead>
                                        <th> Permanent Address </th>
                                        <td class="text-center">
                                            {{ $employee_detail->permanent_address!=''  ?$employee_detail->permanent_address :"--" }}
                                        </td>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            {{--family data--}}
                            <div class="row">&nbsp;</div>

                            <div class="row main" style="background-color: #f5f3ff">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <h4 style="font-weight: bold;margin-top: 10px;">Bank Account Details</h4>
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row" id="bank_account_area_1">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive" style="min-height: 85px;">
                                        <table style="font-weight: bold;margin-top: 10px;" class="table table-sm mb-0 table-bordered">
                                            <thead>
                                            <th class="text-center">Account No</th>
                                            <th class="text-center">Account Title</th>
                                            </thead>
                                            <tbody id="insert_clone">
                                            <tr class="get_rows_education">
                                                <td class="text-center"><?= $employee_detail->account_title!=''?$employee_detail->account_title :"--" ?></td>
                                                <td class="text-center"><?= $employee_detail->bank_account!=''?$employee_detail->bank_account :"--" ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                            </div>

                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: #f5f3ff">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <h4 style="font-weight: bold;margin-top: 10px;">Educational Background</h4>
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row" id="education_area_1">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive" style="min-height: 85px;">
                                        <table class="table table-sm mb-0 table-bordered table=striped table-hover">
                                            <thead>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Name Of Institution</th>
                                            <th class="text-center">From</th>
                                            <th class="text-center">To</th>
                                            <th class="text-center">Degree / Diploma</th><th class="text-center">Major Subjects</th>
                                            </thead>
                                            @if($employee_educational_detail->count() > 0 )
                                                <tbody id="insert_clone">
                                                <?php $counter =1; ?>
                                                @foreach($employee_educational_detail->get() as $employee_educational_value)
                                                    <tr class="get_rows_education" id="remove_area_<?=$employee_educational_value->id?>">
                                                        <td class="text-center">
                                                            <input type="hidden" name="education_data[]" value="{{$employee_educational_value->id}}">
                                                            <span class="badge badge-pill badge-secondary">{{$counter++}}</span></td>
                                                        <td class="text-center"><?=$employee_educational_value->institute_name !='' ? $employee_educational_value->institute_name : '--' ?></td>
                                                        <td class="text-center"><?=$employee_educational_value->year_of_admission !='' ? $employee_educational_value->year_of_admission : '--' ?></td>
                                                        <td class="text-center"><?=$employee_educational_value->year_of_passing !=''?$employee_educational_value->year_of_passing : "--"  ?></td>
                                                        <td class="text-center"><input type="hidden" name="qualificationSection[]">
                                                            {{ $employee_educational_value->degree_type!=''?HrHelper::getMasterTableValueByIdAndColumn($m, 'degree_type', 'degree_type_name', $employee_educational_value->degree_type, 'id') : "--" }}
                                                        </td>
                                                        <td class="text-center"><?=$employee_educational_value->major_subjects !='' ?$employee_educational_value->major_subjects :"--" ?></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            @else
                                                <tbody>
                                                <tr>
                                                    <td class="text-danger text-center" colspan="6">No record found</td>
                                                </tr>
                                                </tbody>
                                            @endif

                                        </table>
                                    </div>
                                </div>


                            </div>
                            <div class="row">&nbsp;</div>


                            <div class="row">&nbsp;</div>
                            <div class="row main" style="background-color: #f5f3ff">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <h4 style="font-weight: bold;margin-top: 10px;">Work Experience</h4>
                                </div>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="row" id="work_experience_area_1">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive" style="min-height: 85px;">
                                        <table class="table table-sm mb-0 table-bordered table=striped table-hover">
                                            <thead>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Name Of Employeer</th><th class="text-center">Position Held</th>
                                            <th class="text-center">Career Level</th><th class="text-center">Started</th><th class="text-center">Ended</th>
                                            <th class="text-center">Last Drawn Salary</th><th class="text-center">Reason For Leaving</th>
                                            </thead>
                                            @if($employee_work_experience->count() > 0)
                                                <tbody id="insert_clone1">
                                                <?php $counter4 = 1; ?>
                                                @foreach($employee_work_experience->get() as $employee_work_experience_value)
                                                    <tr class="get_rows1" id="remove_area1_<?=$employee_work_experience_value->id?>"><td class="text-center"><span class="badge badge-pill badge-secondary">{{$counter4++}}</span></td>
                                                        <td id="get_clone1" class="text-center">
                                                            <input type="hidden" name="work_experience_data[]" value="{{$employee_work_experience_value->id}}">
                                                            <?=$employee_work_experience_value->employeer_name !='' ? $employee_work_experience_value->employeer_name : '--' ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?=$employee_work_experience_value->position_held !='' ? $employee_work_experience_value->position_held : '--' ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?= $employee_work_experience_value->career_level !='' ? $employee_work_experience_value->career_level : '--' ?>
                                                        </td>
                                                        <td class="text-center"><?=$employee_work_experience_value->started !='' ? $employee_work_experience_value->started : "--" ?></td>
                                                        <td class="text-center"><?=$employee_work_experience_value->ended !='' ?$employee_work_experience_value->ended :"--" ?></td>
                                                        <td class="text-center"><?=$employee_work_experience_value->last_drawn_salary !='' ? $employee_work_experience_value->last_drawn_salary : "--" ?></td>
                                                        <td class="text-center"><?=$employee_work_experience_value->reason_leaving !='' ? $employee_work_experience_value->reason_leaving : "--" ?></td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            @else
                                                <tbody>
                                                <tr>
                                                    <td class="text-danger text-center" colspan="8">No record found</td>
                                                </tr>
                                                </tbody>
                                            @endif
                                        </table>
                                    </div>
                                </div>



                            </div>

                            <br><br>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
