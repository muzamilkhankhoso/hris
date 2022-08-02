<?php

use App\Models\Employee;
use App\User;
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use Illuminate\Support\Facades\Storage;
use App\Models\EmployeePromotion;
$accType = Auth::user()->acc_type;
//	if($accType == 'client'){
//		$m = $_GET['m'];
//	}else{
//		$m = Auth::user()->company_id;
//	}

$m = $_GET['m'];
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
CommonHelper::companyDatabaseConnection($m);
$employee_name = Employee::select('id','emp_id','emp_name')->where('status','=',1)->get();
CommonHelper::reconnectMasterDatabase();
$userData = User::where('emp_id',$employee_detail->emp_id);
$banks=DB::table('banks')->where('status',1)->get();



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
                            <div class="col-sm-12">
                                <h4 class="card-title">Edit Employee Form</h4>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo Form::open(array('url' => 'had/editEmployeeDetail','id'=>'employeeForm',"enctype"=>"multipart/form-data", "files" => true));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?=Input::get('m')?>">
                            <input type="hidden" name="login_check" value="<?=$userData->count() ?>">
                            <input type="hidden" name="emp_id" value="<?=$employee_detail->emp_id?>">
                            <input type="hidden" name="id" value="<?=$employee_detail->id?>">


                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                            <div class="hr-border"></div>
                                            <img id="img_file_1" class="avatar img-circle img-thumbnail" style="width:20%;" src="<?= url($employee_detail->img_path != '' ? 'storage/'.$employee_detail->img_path  : 'storage/app/uploads/employee_images/user-dummy.png')?> ">
                                        </div>
                                        <div class="form-group text-center">
                                            <label class="text-dark">
                                                <input type="file" id="file_1" name="fileToUpload_1" accept="image/*" capture style="display:none"/>
                                                <img class="avatar img-circle img-thumbnail" style="width:20%;cursor:pointer" src="<?= url('assets/images/cam.png')?>" id="upfile1"/>
                                                Change Image
                                            </label>
                                        </div>
                                        <div class="row">&nbsp;</div>
                                        <div class="row" style="background-color: rgb(220 213 247);">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <h4  style="font-weight: bold;margin-top: 10px;">Basic Information</h4>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                                        </div>
                                        <div class="row">&nbsp;</div>
                                        <div class="row">
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Employee ID</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="number" name="emp_id" id="emp_id" value="<?=$employee_detail->emp_id ?>" class="form-control requiredField">
                                                <span style="color:red;font-weight: bold;font-size: 13px;" id="emrExistMessage"></span>
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Employee Name</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" class="form-control requiredField" placeholder="Employee Name" name="employee_name_1" id="employee_name_1" value="<?=$employee_detail->emp_name?>" />
                                            </div>

                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Father / Husband Name</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" class="form-control requiredField" placeholder="Father Name" name="father_name_1" id="father_name_1" value="<?=$employee_detail->emp_father_name?>" />
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label pointer">Department</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width: 100%;" class="form-control" name="department_id_" id="department_id_">
                                                    <option value="">Select Department</option>
                                                    @foreach($Department as $key => $y)
                                                        <option value="<?php echo $y->id ?>" <?php if($employee_detail->emp_department_id == $y->id){
                                                            echo 'selected';
                                                        } ?> >  	{{ $y->department_name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                        </div>
                                        <hr>
                                        <div class="row">

                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label pointer">Sub Department</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width: 100%;" class="form-control requiredField" name="sub_department_id_1" id="sub_department_id_1">
                                                    <option value="">Select Department</option>
                                                    @foreach($departments  as $key => $y)
                                                        <option value="<?php echo $y->id ?>" <?php if($employee_detail->emp_sub_department_id == $y->id ){ echo 'selected'; } ?>> 				{{ $y->sub_department_name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 custom-div">
                                                <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Designation','designation','designation_name','designation','<?php echo $m; ?>')"><label class="text-dark pointer sf-label">Designation</label></a>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width: 100%;" class="form-control requiredField" id="designation_1" name="designation_1">
                                                    <option value="">Select</option>
                                                    @foreach($designation as $value5)
                                                        <option @if($employee_detail->designation_id == $value5->id) selected @endif value="{{ $value5->id}}">{{ $value5->designation_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 custom-div">
                                                <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Marital Status','marital_status','marital_status_name','marital_status','<?php echo $m; ?>')"><label class="text-dark pointer sf-label">Marital Status</label></a>
                                                <select style="width: 100%;" class="form-control" name="marital_status_1" id="marital_status_1">
                                                    <option value="">Select Marital</option>
                                                    @foreach($marital_status as $value2)
                                                        <option @if($employee_detail->emp_marital_status == $value2->id) selected @endif value="{{ $value2->id}}">{{ $value2->marital_status_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Joining Date</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField" placeholder="Joining Date" name="joining_date_1" id="joining_date_1" value="<?=$employee_detail->emp_joining_date ?>" />
                                            </div>


                                        </div>
                                        <hr>



                                        <div class="row">

                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 custom-div">
                                                <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Employment Status','job_type','job_type_name','employee_status','<?php echo $m; ?>')"><label class="text-dark pointer sf-label">Employment Status</label></a>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width: 100%;" class="form-control requiredField" name="employee_status_1" id="employee_status_1" onchange="employeestatus(this.value)">
                                                    <option value="">Select Employment Status</option>
                                                    @foreach($jobtype as $value)
                                                        <option @if($employee_detail->emp_employementstatus_id == $value->id) selected @endif value="{{ $value->id}}">{{ $value->job_type_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label pointer">Probotion/Internee Expire Date</label>
                                                <span class="rflabelsteric"><strong></strong></span>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" name="pTimePeriod" id="pTimePeriod" value="{{ $employee_detail->probation_expire_date }}" class="form-control" disabled />
                                            </div>

                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">CNIC</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" class="form-control requiredField cnicExistMessage" onkeydown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" maxlength="15" placeholder="CNIC Number" name="cnic_1" id="cnic_1" value="<?=$employee_detail->emp_cnic ?>" />
                                                <span style="color:red;font-weight: bold;" id="cnicExistMessage"></span>
                                            </div>
                                            {{--<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">--}}
                                            {{--<label class="text-dark sf-label">CNIC Expiry Date</label>--}}
                                            {{--<span class="rflabelsteric"><strong>*</strong></span>--}}
                                            {{--<input type="date" class="form-control" name="cnic_expiry_date_1" id="cnic_expiry_date_1" value="" />--}}
                                            {{--</div>--}}
                                            @if($employee_cnic_copy->count() > 0)
                                                <div id="cnic_div_hide" class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="text-dark pointer" id="cnic_check" style="color: green;">Change / Edit CNIC copy:</label><br>
                                                    <a onclick="showMasterTableEditModel('hdc/viewEmployeeCnicCopy','<?php echo $employee_detail->id.'|'.$employee_detail->emp_id;?>','View Employee CNIC Copy','<?php echo $m; ?>')" class=" btn btn-info btn-sm" style="cursor:pointer;color:white;">View</a>

                                                </div>
                                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" style="display: none" id="cnic_div_show">
                                                    <label class="text-dark">Upload CNIC Copy:</label>
                                                    <input type="file" class="form-control" name="cnic_path_1" id="cnic_path_1" multiple>
                                                </div>
                                            @else
                                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="text-dark">Upload CNIC Copy:</label>
                                                    <input type="file" class="form-control" name="cnic_path_1" id="cnic_path_1" multiple>
                                                </div>
                                            @endif


                                        </div>
                                        <hr>


                                        <div class="row">

                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">CNIC Expiry Date</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control" name="cnic_expiry_date_1" id="cnic_expiry_date_1" value="{{ $employee_detail->emp_cnic_expiry_date }}" />
                                            </div>

                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Date of Birth</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField" placeholder="Date of Birth" name="date_of_birth_1" id="date_of_birth_1" value="<?=$employee_detail->emp_date_of_birth ?>" />
                                            </div>

                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Gender</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width: 100%;" class="form-control requiredField" name="gender_1" id="gender_1">
                                                    <option value="">Select Gender</option>
                                                    <option @if($employee_detail->emp_gender == 1) selected @endif value="1">Male</option>
                                                    <option @if($employee_detail->emp_gender == 2) selected @endif value="2">Female</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Nationality</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" class="form-control requiredField" placeholder="Nationality" name="nationality_1" id="nationality_1" value="<?=$employee_detail->nationality ?>" />
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row">



                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Contact Number</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <span class="input-group-prepend">
                                                <span class="input-group-text">+92</span>
                                                <span class="input-group-area" style="width:100%;">
                                                    <input type="text" id="contact_no_1" name="contact_no_1"  value="<?=$employee_detail->emp_contact_no?>" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" onkeyup ="checkMobileNumber(this.value=this.value.replace(/^0+/, ''),'errorMobileNumberOne','1','e')" class="form-control requiredField" value="{{ $employee_detail->emp_contact_no }}" placeholder="3xxxxxxxxx" maxlength="10" onkeypress="return isNumber(event)" />
                                                </span>

                                                </span>
                                                <span style="color:red;font-size:13px;font-weight: bold;" class="errorMobileNumberOne" ></span>
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Landline Number</label>
                                                <span class="input-group-prepend">
                                                    <span class="input-group-text">+92</span>
                                                    <span class="input-group-area" style="width:100%;">
                                                        <input type="text" id="contact_home_1" value="<?=$employee_detail->contact_home?>" name="contact_home_1" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" onkeyup ="checkMobileNumber(this.value=this.value.replace(/^0+/, ''),'errorlandlineNumberOne','1','e')" class="form-control" value="{{ $employee_detail->contact_home }}" placeholder="3xxxxxxxxx" maxlength="10" onkeypress="return isNumber(event)" />
                                                    </span>
                                                </span>

                                                <span style="color:red;font-size:13px;font-weight: bold;" class="errorlandlineNumberOne" ></span>
                                            </div>

                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Place of Birth</label>
                                                <input type="text" class="form-control" placeholder="Pace of Birth" name="place_of_birth_1" id="place_of_birth_1" value="<?=$employee_detail->emp_place_of_birth ?>" />
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Personal Email</label>
                                                <input type="text" class="form-control" placeholder="Email Address" name="email_1" id="email_1" value="<?=$employee_detail->emp_email?>" />
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row">

                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Official Email</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" class="requiredField form-control" placeholder="Professional Email" name="professional_email" id="professional_email" value="<?=$employee_detail->professional_email ?>" />
                                            </div>

                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <?php 
                                               CommonHelper::companyDatabaseConnection($m);
                                               $promotion = EmployeePromotion::select('id','salary','emp_id','increment')->where([['emp_id','=',$employee_detail->emp_id],['status','=',1]])->orderBy('id', 'desc'); 
                                               CommonHelper::reconnectMasterDatabase();
                                            ?>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                    <label class="text-dark sf-label">Compensation</label>    
                                                    </div>
                                                    @if(HrHelper::hideConfidentiality(Input::get('m')) =='no')
                                                    @if($promotion->count()>0) 
                                                        <div class="col-sm-6 text-right">
                                                            <span onclick="showDetailModelFourParamerter('hr/editSalaryForm','<?= $promotion->value('id') ?>','Edit Employee Salary Detail','<?php echo $m; ?>')" style="cursor:pointer;" class="badge badge-sm badge-info">Edit</span>
                                                        </div>
                                                    @endif 
                                                    @endif        
                                                </div>
                                                @if(HrHelper::hideConfidentiality(Input::get('m')) =='no')
                                                    @if($promotion->count()>0) 
                                                        <input type="number" readonly class="form-control " placeholder="Current Salary" name="salary_1" id="salary_1" value="<?=$promotion->value('salary')-$promotion->value('increment')?>" />
                                                    @else
                                                        <input type="number" class="form-control " placeholder="Current Salary" name="salary_1" id="salary_1" value="<?=$employee_detail->emp_salary?>" />
                                                    @endif
                                                @else
                                                    <input type="hidden" class="form-control" placeholder="Current Salary" name="salary_1" id="salary_1" value="<?=$employee_detail->emp_salary?>" />
                                                    <input type="text" readonly class="form-control" placeholder="Current Salary" name="" id="" value="******" />
                                                @endif

                                            </div>
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Religion</label>
                                                <input type="text" class="form-control" placeholder="Religion Name" name="religion_1" id="religion_1" value="<?=$employee_detail->relegion?>" />
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Eobi</label>
                                                <select style="width: 100%;" class="form-control" name="eobi_id_1" id="eobi_id_1">
                                                    <option value="0">--</option>
                                                    @foreach($eobi as $value8)
                                                        <option @if($employee_detail->eobi_id == $value8->id) selected @endif value="{{ $value8->id}}">
                                                            {{ $value8->EOBI_name}}
                                                            ({{ $value8->month_year}})
                                                            Amount=({{ $value8->EOBI_amount}})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row">
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">EOBI Number</label>
                                                <input type="text" class="form-control" name="eobi_number_1" id="eobi_number_1" value="<?=$employee_detail->eobi_number?>" />
                                            </div>
                                            @if($employee_eobi_copy->count() > 0)
                                                <div id="eobi_div_hide" class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="pointer" id="eobi_check" style="color: green">Change / Edit EOBI copy:</label>
                                                    <div class="form-group col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                                        <a onclick="showMasterTableEditModel('hdc/viewEmployeeEobiCopy','<?php echo $employee_detail->id.'|'.$employee_detail->emp_id;?>','View Employee EOBI Copy','<?php echo $m; ?>')" class=" btn btn-info btn-sm" style="cursor:pointer;color:white;">View</a>
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" style="display: none" id="eobi_div_show">
                                                    <label class="text-dark sf-label">EOBI Upload</label>
                                                    <input type="file" class="form-control" name="eobi_path_1" id="eobi_path_1" multiple>
                                                </div>
                                            @else
                                                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="text-dark sf-label">EOBI Upload</label>
                                                    <input type="file" class="form-control" name="eobi_path_1" id="eobi_path_1" multiple>
                                                </div>
                                            @endif
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Provident Fund</label>

                                                <select style="width: 100%;" class="form-control" name="provident_fund_1" id="provident_fund_1">
                                                    <option value="">-</option>
                                                    @foreach($provident_fund as $value9)
                                                        <option <?php if($employee_detail->provident_fund_id == $value9->id ) echo "selected"; ?> value="{{ $value9->id}}">
                                                            {{ $value9->name.'=>Mode:'.$value9->pf_mode.'=>'.$value9->amount_percent}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Days Off</label>
                                                <select style="width: 100%;" class="form-control requiredField"  name="days_off_1[]" id="days_off_1" multiple>
                                                    <option value="">None</option>
                                                    <option value="Mon">Monday</option>
                                                    <option value="Tue">Tuesday</option>
                                                    <option value="Wed">Wednesday</option>
                                                    <option value="Thu">Thursday</option>
                                                    <option value="Fri">Friday</option>
                                                    <option value="Sat" selected="selected">Saturday</option>
                                                    <option value="Sun" selected="selected">Sunday</option>
                                                </select>
                                            </div>

                                        </div>
                                        <hr>

                                        <div class="row">
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Emergency Contact</label>

                                                <span class="input-group-prepend">
                                                    <span class="input-group-text">+92</span>
                                                    <span class="input-group-area" style="width:100%;">
                                                    <?php substr($employee_detail->emergency_no, 0, 1);
                                                        $contactNo=ltrim($employee_detail->emergency_no, '0');
                                                        ?>
                                                        <input type="text" id="emergency_no_1" name="emergency_no_1"  value="<?=str_replace("-","",$contactNo)?>" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" onkeyup="checkMobileNumber(this.value=this.value.replace(/^0+/, ''),'erroremergencyNumberOne','1','e')" class="form-control"  placeholder="3xxxxxxxxx" maxlength="10" onkeypress="return isNumber(event)" />
                                                    </span>

                                                    </span>
                                                <span style="color:red;font-size:13px;font-weight: bold;" class="erroremergencyNumberOne" ></span>
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Emergency Person Name</label>

                                                <input type="text" class="form-control" placeholder="Emergency person's name" name="person_name" id="person_name" value="<?=$employee_detail->emp_emergency_relation_name?>" />
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Emergency Person's Relation</label>
                                                <input type="text" class="form-control" placeholder="Emergency person's relation" name="persons_relation" id="persons_relation" value="<?=$employee_detail->emp_emergency_relation?>" />
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Emergency Person's Address</label>
                                                <input type="text" class="form-control" placeholder="Emergency person's Address" name="persons_address" id="persons_address" value="<?=$employee_detail->emp_emergency_relation_address?>" />
                                            </div>

                                        </div>

                                        <hr>
                                        <div class="row">

                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Reporting Manager </label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width: 100%;" class="form-control requiredField" name="reporting_manager" id="reporting_manager">
                                                    <option value="">Select Reporting Manager</option>
                                                    @foreach($employee_name as $value)
                                                        <option value="{{$value->emp_id}}" <?php if($employee_detail->reporting_manager == $value->emp_id){ echo 'selected'; } ?> >
                                                            {{ $value->emp_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="text-dark sf-label">Working Hours Policy</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width: 100%;" class="form-control requiredField" name="working_hours_policy_1" id="working_hours_policy_1">
                                                    <option value="0">--</option>
                                                    @foreach($working_policy as $working_policy_value)
                                                        <option value="{{$working_policy_value->id}}" <?php if($employee_detail->working_hours_policy_id== $working_policy_value->id){ echo 'selected'; } ?>>
                                                            {{'Office Time=='.$working_policy_value->start_working_hours_time.'--'.$working_policy_value->end_working_hours_time."| Grace Time== ".$working_policy_value->working_hours_grace_time." Mins | Half Day==".$working_policy_value->half_day_time." Hours"}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label class="text-dark sf-label">Leaves Policy</label>
                                                    <select style="width: 100%;" class="form-control" name="leaves_policy_1" id="leaves_policy_1">
                                                        <option value="">Select</option>
                                                        @foreach($leaves_policy as $value3)
                                                            <option @if($employee_detail->leaves_policy_id == $value3->id) selected @endif  value="{{ $value3->id}}">{{ $value3->leaves_policy_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;text-align:right;">
                                                    <button type="button" class="btn btn-sm btn-primary" id="leaves_policy_id_1">View Policy</button>
                                                </div>
                                            </div>

                                        </div>

                                        <hr>

                                        {{--new employee details--}}
                                        <div class="row">&nbsp;</div>
                                        <div class="row" style="background-color: rgb(220 213 247)">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <h4 style="font-weight: bold;margin-top: 10px;">Address</h4>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                                        </div>
                                        <div class="row">&nbsp;</div>
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="text-dark sf-label">Current Address</label>
                                                <textarea class="form-control" name="residential_address_1"><?=$employee_detail->residential_address?></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="text-dark sf-label">Permanent Address</label>
                                                <textarea class="form-control" name="permanent_address_1"><?=$employee_detail->permanent_address?></textarea>
                                            </div>
                                        </div>

                                        <div class="row">&nbsp;</div>
                                        <div class="row" style="background-color: rgb(220 213 247)">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <h4 style="font-weight: bold;margin-top: 10px;">Bank Account Details</h4>
                                            </div>
                                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                                @if($employee_detail->account_title != "" || $employee_detail->bank_account != "")
                                                    <input type="checkbox" checked name="bank_account_check_1" id="bank_account_check_1">
                                                @else
                                                    <input type="checkbox" name="bank_account_check_1" id="bank_account_check_1">
                                                @endif

                                            </div>
                                        </div>
                                        <div class="row">&nbsp;</div>
                                        <div class="row" id="bank_account_area_1">
                                            @if($employee_detail->account_title != "" || $employee_detail->bank_account != "" || $employee_detail->bank_id != "")
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Bank Name</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select  class="form-control requiredField" name="bank_id" id="bank_id">
                                                        @foreach($banks as $bank)
                                                        <option @if($employee_detail->bank_id == $bank->id) selected @endif value="{{ $bank->id }}" >  {{ $bank->bank_name }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Account No</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" class="form-control requiredField" placeholder="Account No" name="account_no" id="account_no" value="<?=$employee_detail->bank_account?>" />
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <input type="hidden" name="bank_account_data[]" value="1">
                                                    <label class="sf-label">Account Title</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" class="form-control requiredField" placeholder="Title Of Account" name="account_title" id="account_title" value="<?=$employee_detail->account_title?>" />
                                                </div>



                                            @endif
                                        </div>

                                        <div class="row">&nbsp;</div>
                                        <div class="row" style="background-color: rgb(220 213 247)">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <h4 style="font-weight: bold;margin-top: 10px;">Educational Background</h4>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <h5 style="font-weight: bold;margin-top: 10px;">Start from Recent</h5>
                                            </div>
                                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                                @if($employee_educational_detail->count() > 0 )

                                                @else
                                                    <input type="checkbox" name="education_check_1" id="education_check_1">
                                                @endif

                                            </div>
                                        </div>

                                        <div class="row">&nbsp;</div>
                                        <div class="row" id="education_area_1">
                                            @if($employee_educational_detail->count() > 0 )
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div >
                                                        <table class="table table-sm mb-0 table-bordered table-striped">
                                                            <thead>
                                                            <th class="text-center">S.No</th>
                                                            <th class="text-center">Name Of Institution</th><th class="text-center">From</th><th class="text-center">To</th>
                                                            <th class="text-center">Degree / Diploma</th>
                                                            <th class="text-center"><button type="button" id="addMoreQualification" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"></i></button></th>
                                                            </thead>
                                                            <tbody id="insert_clone">
                                                            <?php $counter =1; ?>
                                                            @foreach($employee_educational_detail->get() as $employee_educational_value)
                                                                <tr class="get_rows_education" id="remove_area_<?=$employee_educational_value->id?>">
                                                                    <td class="text-center">
                                                                        <input type="hidden" name="education_data[]" value="{{$employee_educational_value->id}}">
                                                                        <span class="badge badge-pill badge-secondary">{{$counter++}}</span></td>
                                                                    <td class="text-center"><input name="institute_name_<?=$employee_educational_value->id?>" type="text" class="form-control requiredField" id="institute_name_<?=$employee_educational_value->id?>" value="<?=$employee_educational_value->institute_name?>"></td>
                                                                    <td class="text-center"><input name="year_of_admission_<?=$employee_educational_value->id?>" type="date" class="form-control requiredField" id="year_of_admission_<?=$employee_educational_value->id?>" value="<?=$employee_educational_value->year_of_admission?>"></td>
                                                                    <td class="text-center"><input name="year_of_passing_<?=$employee_educational_value->id?>" type="date" class="form-control requiredField" id="year_of_passing_<?=$employee_educational_value->id?>" value="<?=$employee_educational_value->year_of_passing?>"></td>
                                                                    <td><input type="hidden" name="qualificationSection[]">
                                                                        <select style="width:300px;" id="degree_type_<?=$employee_educational_value->id?>" class="form-control text-center requiredField get_clone_1" name="degree_type_<?=$employee_educational_value->id?>"><option value="">Select</option>
                                                                            @foreach($DegreeType as $DegreeTypeValue)
                                                                                <option @if($employee_educational_value->degree_type == $DegreeTypeValue->id) selected @endif value="{{ $DegreeTypeValue->id }}">
                                                                                    {{ $DegreeTypeValue->degree_type_name }}
                                                                                </option>
                                                                            @endforeach
                                                                            <option value="other">Other</option>
                                                                        </select>
                                                                        <span id="other_option_1"></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button onclick='removeQualificationSection("<?=$employee_educational_value->id?>")' type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button>
                                                                        <script>
                                                                            $(document).ready(function () {
                                                                                $('#degree_type_<?=$employee_educational_value->id?>').select2();
                                                                            });
                                                                        </script>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <script>

                                                    $("#addMoreQualification").click(function(e){
                                                        var clone = $(".get_clone_1").html();

                                                        var form_rows_count = $(".get_rows_education").length;
                                                        form_rows_count++;
                                                        $("#insert_clone").append("<tr class='get_rows_education' id='remove_area_"+form_rows_count+"' ><td class='text-center'>" +
                                                            "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td>" +
                                                            "<td class='text-center'><input name='institute_name_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='institute_name_"+form_rows_count+"'></td>" +
                                                            "<td class='text-center'><input name='year_of_admission_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_admission_"+form_rows_count+"'></td>" +
                                                            "<td class='text-center'><input name='year_of_passing_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_passing_"+form_rows_count+"'></td>" +
                                                            "<td><input type='hidden' name='education_data[]' value="+form_rows_count+">" +
                                                            "<select style='width:300px;' id='degree_type_"+form_rows_count+"' class='form-control requiredField' name='degree_type_"+form_rows_count+"'>"+clone+"</select>" +
                                                            "<span id='other_option_"+form_rows_count+"'></span></td>"+
                                                            "<td class='text-center'><button onclick='removeQualificationSection("+form_rows_count+")'  type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'><i/></button>" +
                                                            "</td>" +
                                                            "</tr>");

                                                        $(document).ready(function () {
                                                            $('#degree_type_'+form_rows_count+'').select2();
                                                        });


                                                    });

                                                </script>
                                            @endif
                                        </div>

                                        <div class="row">&nbsp;</div>

                                        <div class="row" style="background-color: rgb(220 213 247)">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <h4 style="font-weight: bold;margin-top: 10px;">Work Experience</h4>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <h5 style="font-weight: bold;margin-top: 10px;">Most recent first</h5>
                                            </div>
                                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                                @if($employee_work_experience->count() > 0)

                                                @else
                                                    <input type="checkbox" name="work_experience_check_1" id="work_experience_check_1">
                                                @endif

                                            </div>
                                        </div>
                                        <div class="row">&nbsp;</div>
                                        <div class="row" id="work_experience_area_1">

                                            @if($employee_work_experience->count() > 0)
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="">
                                                        <table class="table table-sm mb-0 table-bordered table-striped">
                                                            <thead>
                                                            <th class="text-center">S.No</th>
                                                            <th class="text-center">Emp Name</th>
                                                            <th class="text-center">Started</th>
                                                            <th class="text-center">Ended</th>
                                                            <th class="text-center">File</th>
                                                            <th class="text-center"><button type="button" id="addMoreWorkExperience" class="icon btn btn-sm btn-primary"><i class="fas fa fa-plus"></i></button></th>
                                                            </thead>
                                                            <tbody id="insert_clone1">
                                                            <?php $counter4 = 1; ?>
                                                            @foreach($employee_work_experience->get() as $employee_work_experience_value)
                                                                <tr class="get_rows1" id="remove_area1_<?=$employee_work_experience_value->id?>"><td class="text-center"><span class="badge badge-pill badge-secondary">{{$counter4++}}</span></td>
                                                                    <td id="get_clone1" class="text-center">
                                                                        <input type="hidden" name="work_experience_data[]" value="{{$employee_work_experience_value->id}}">
                                                                        <input type="hidden" name="recordType_<?=$employee_work_experience_value->id?>" value="update">
                                                                        <input type="text" name="employeer_name_<?=$employee_work_experience_value->id?>" id="employeer_name_<?=$employee_work_experience_value->id?>" value="<?=$employee_work_experience_value->employeer_name?>" class="form-control requiredField" required>
                                                                    </td>
                                                                    <td class="text-center"><input name="started_<?=$employee_work_experience_value->id?>" type="date" class="form-control" id="started_<?=$employee_work_experience_value->id?>" value="<?=$employee_work_experience_value->started?>"></td>
                                                                    <td class="text-center"><input name="ended_<?=$employee_work_experience_value->id?>" id="ended_<?=$employee_work_experience_value->id?>"type="date" class="form-control"  value="<?=$employee_work_experience_value->ended?>"></td>

                                                                    <td class="text-center">
                                                                        @if($employee_work_experience_doc->count() > 0)
                                                                            <p class="form-control pointer workExpFile_<?=$employee_work_experience_value->id?>" onclick="workExpFile('<?=$employee_work_experience_value->id?>')" style="color: green;">Change / Edit </p><input style="display: none" type="file" class="form-control" name="work_exp_path_<?=$employee_work_experience_value->id?>" id="work_exp_path_<?=$employee_work_experience_value->id?>" >
                                                                        @else
                                                                            <input type="file" class="form-control" name="work_exp_path_<?=$employee_work_experience_value->id?>" id="work_exp_path_<?=$employee_work_experience_value->id?>" >
                                                                        @endif
                                                                    </td>
                                                                    <td class='text-center'>
                                                                        <script>
                                                                            $(document).ready(function () {
                                                                                $('#career_level_<?=$employee_work_experience_value->id?>').select2();
                                                                                $('#position_held_<?=$employee_work_experience_value->id?>').select2();

                                                                            });
                                                                        </script>
                                                                        <button onclick='removeWorkExperienceSection("<?=$employee_work_experience_value->id?>")' type='button'class='btn btn-sm btn-danger'><i class="fas fa fa-trash"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                        @if($employee_work_experience_doc->count() > 0)
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                                                    <a style="float: right;color:white;cursor:pointer;" onclick="showMasterTableEditModel('hdc/viewEmployeeExperienceDocuments','<?php echo $employee_detail->id.'|'.$employee_detail->emp_id;?>','View Employee Work Experience Documents','<?php echo $m; ?>')" class=" btn btn-info btn-sm">View</a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                </div>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12" id="suspend_detail_1">
                                                        @if($employee_work_experience_value->suspend_check == 'yes')
                                                            <label class="sf-label">Detail</label><span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="text" class="form-control requiredField" value="<?= $employee_work_experience_value->suspend_reason ?>" name="suspend_reason_1" id="suspend_reason_1" value="" />
                                                            <script>

                                                                $("input[name='suspend_check_1']").click(function() {
                                                                    if($(this).val() == 'yes')
                                                                    {
                                                                        $("#suspend_detail_1").html('<label class="sf-label">Detail</label><span class="rflabelsteric"><strong>*</strong></span>' +
                                                                            '<input type="text" class="form-control requiredField" placeholder="Suspend Reason" name="suspend_reason_1" id="suspend_reason_1" value="" />');
                                                                    }
                                                                    else
                                                                    {
                                                                        $("#suspend_detail_1").html('');
                                                                    }
                                                                })
                                                            </script>
                                                        @endif
                                                    </div>
                                                </div>
                                                <script>
                                                    $("#addMoreWorkExperience").click(function(e){
                                                        var form_rows_count = $(".get_rows1").length;
                                                        form_rows_count++;
                                                        $("#insert_clone1").append("<tr class='get_rows1' id='remove_area1_"+form_rows_count+"' ><td class='text-center'>" +
                                                            "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td><td>" +
                                                            '<input type="hidden" name="work_experience_data[]" value="'+form_rows_count+'">' +
                                                            '<input type="hidden" name="recordType_'+form_rows_count+'" value="insert">' +
                                                            "<input type='text' name='employeer_name_"+form_rows_count+"' class='form-control requiredField' required></td>" +
                                                            "<td class='text-center'><input name='started_"+form_rows_count+"' id='started_"+form_rows_count+"'  type='date' class='form-control' value='' ></td>" +
                                                            "<td class='text-center'><input name='ended_"+form_rows_count+"' id='ended_"+form_rows_count+"' type='date' class='form-control' value='' ></td>" +
                                                            "<td class='text-center'><input type='file' class='form-control' name='work_exp_path_"+form_rows_count+"' id='work_exp_path_"+form_rows_count+"' ></td>" +
                                                            "<td class='text-center'><button onclick='removeWorkExperienceSection("+form_rows_count+")' type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button>" +
                                                            "</td></tr>");
                                                        $("#career_level_"+form_rows_count+"").select2();

                                                    });

                                                </script>
                                            @endif
                                        </div>

                                        <div class="row">&nbsp;</div>

                                        <div class="row" style="background-color: rgb(220 213 247)">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <h4 style="font-weight: bold;margin-top: 10px;">Employee Document Upload</h4>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <h5 style="font-weight: bold;margin-top: 10px;">Can Upload Multiple Files

                                                </h5>
                                            </div>
                                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                                <input @if($employee_documents->value('documents_upload_check') == 1) checked @endif type="checkbox" name="documents_upload_check" id="documents_upload_check" class="documents_upload_check" value="1" />
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">

                                            <div id="file_upload_area" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

                                                @if($employee_documents->value('documents_upload_check') == 1)
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <a style="margin-top: 10px;cursor: pointer;color: white;" onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','<?php echo $employee_detail->id;?>','View Employee Documents','<?php echo $m; ?>')" class=" btn btn-info btn-md">View</a>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                        </div>

                                        <div class="row">&nbsp;</div>

                                        <div class="row" style="background-color: rgb(220 213 247)">
                                            <br>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <h4 style="font-weight: bold;margin-top: 10px;">Login Credentials</h4>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 ">

                                            </div>
                                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">

                                                <input @if($userData->count() > 0){{ 'checked' }} @endif type="checkbox" name="can_login_1" id="can_login_1" value="yes">

                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                        </div>
                                        @if($userData->count() > 0)
                                            <br>
                                            <div class="row" id="credential_area_1">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Account type</label>
                                                    <select class="form-control" name="account_type_1">
                                                        <option @if($userData->value('acc_type') == 'user'){{ 'selected' }} @endif value="user">User</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Password</label>
                                                    <input type="text" class="form-control" id="password_1" name="password_1">
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <button style="margin-top:37px;color:white;" class="icon btn btn-sm btn-warning" type="button" onclick="password_generator()" >Generate</button>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Roles</label>
                                                    <select class="form-control" name="role_1" id="role_1">
                                                        <option value="">Select</option>
                                                        @foreach($MenuPrivileges as $MenuPrivilege)
                                                            <option value="{{$MenuPrivilege->id}}" @if($employee_detail->role_id == $MenuPrivilege->id) selected @endif>{{$MenuPrivilege->role_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @else
                                            <br>
                                            <div class="row" id="credential_area_1" style="display: none;">

                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Account type</label>
                                                    <select  class="form-control" name="account_type_1">
                                                        <option value="user">User</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Password</label>
                                                    <input type="text" class="form-control" id="password_1" name="password_1">
                                                </div>

                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <button style="margin-top:37px;color:white;" class="icon btn btn-sm btn-warning" type="button" onclick="password_generator()" >Generate
                                                    </button>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Roles</label>
                                                    <select class="form-control" name="role_1" id="role_1">
                                                        <option value="0">Select</option>
                                                        @foreach($MenuPrivileges as $MenuPrivilege)
                                                            <option value="{{$MenuPrivilege->id}}" @if($employee_detail->role_id == $MenuPrivilege->id) selected @endif>{{$MenuPrivilege->role_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>

                            <div class="employeeSection"></div>
                            <br>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <span id="emp_warning" style="color:red;font-weight:bold;"></span>
                                    {{ Form::submit('Update', ['class' => 'btn btn-success btn-sm','id' => 'btn_disable']) }}
                                    <button type="reset" id="reset" class="btn btn-primary btn-sm">Clear Form</button>

                                </div>
                            </div>
                            <?php echo Form::close();?>
                          </div>
                        </div>


                        <div class="text-center ajax-loader"></div>
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
        <!-- End Right sidebar -->
        <!-- ============================================================== -->

    </div>

    <script>

        //        var readOnlyLength = $('#contact_no_1').val().length;
        //
        //        $('#output').text(readOnlyLength);
        //
        //        $('#contact_no_1,#contact_home_1').on('keypress, keydown', function(event) {
        //
        //
        //            var charCode = (event.which) ? event.which : event.keyCode
        //            console.log(charCode);
        //            if((charCode == 8 || charCode == 48) && $('#contact_no_1').val()== "+92"){
        //                return false;
        //            }
        //            if((charCode <= 47 || charCode >= 58) && (charCode <=96 || charCode >=105) && charCode != 8){
        //                return false;
        //            }
        //            return true;
        //
        //        });

        $('#education_check_1').click(function(){

            if($(this).is(":checked") == true)
            {

                $("#education_area_1").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="">' +
                    '<input type="hidden" name="education_data[]" value="1"><table class="table table-sm mb-0 table-bordered table-striped"><thead><th class="text-center">S.No</th>' +
                    '<th class="text-center">Name Of Institution</th><th class="text-center">From</th><th class="text-center">To</th>' +
                    '<th class="text-center">Degree / Diploma</th>' +
                    '<th class="text-center"><button type="button" id="addMoreQualification" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></th>' +
                    '</thead><tbody id="insert_clone"><tr class="get_rows"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>' +
                    '<td class="text-center"><input name="institute_name_1" type="text" class="form-control requiredField" id="institute_name_1" value=""></td>' +
                    '<td class="text-center"><input name="year_of_admission_1" type="date" class="form-control requiredField" id="year_of_admission_1" value=""></td>' +
                    '<td class="text-center"><input name="year_of_passing_1" type="date" class="form-control requiredField" id="year_of_passing_1" value=""></td>' +
                    '<td class="text-center"><input type="hidden" name="qualificationSection[]">' +
                    '<select style="width:300px;" id="degree_type_1" class="form-control requiredField get_clone_1" name="degree_type_1"><option value="">Select</option>'+
                    '@foreach($DegreeType as $DegreeTypeValue)<option value="{{ $DegreeTypeValue->id }}">{{ $DegreeTypeValue->degree_type_name }}</option>@endforeach<option value="other">Other</option></select><span id="other_option_1"></span></td>'+
                    '<td class="text-center">-</td></tr></tbody></table></div></div>');


                $("#addMoreQualification").click(function(e){
                    var clone = $(".get_clone_1").html();

                    var form_rows_count = $(".get_rows").length;
                    form_rows_count++;
                    $("#insert_clone").append("<tr class='get_rows' id='remove_area_"+form_rows_count+"' ><td class='text-center'>" +
                        "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td>" +
                        "<td class='text-center'><input name='institute_name_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='institute_name_"+form_rows_count+"'></td>" +
                        "<td class='text-center'><input name='year_of_admission_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_admission_"+form_rows_count+"'></td>" +
                        "<td class='text-center'><input name='year_of_passing_"+form_rows_count+"' type='date' class='form-control requiredField' value='' id='year_of_passing_"+form_rows_count+"'></td>" +
                        "<td><input type='hidden' name='education_data[]' value="+form_rows_count+">" +
                        "<select style='width:300px;' id='degree_type_"+form_rows_count+"' class='form-control requiredField' name='degree_type_"+form_rows_count+"'>"+clone+"</select>" +
                        "<span id='other_option_"+form_rows_count+"'></span></td>" +
                        "<td class='text-center'><button onclick='removeQualificationSection("+form_rows_count+")'  type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button>" +
                        "</td>" +
                        "</tr>");
                    $('#degree_type_'+form_rows_count+'').select2();

                });
                $('#degree_type_1').select2();
            }
            else
            {
                $("#education_area_1").html('');
            }

        });

        $('#bank_account_check_1').click(function(){

            if($(this).is(":checked") == true)
            {
                $("#bank_account_area_1").html('<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Bank Name</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<select  class="form-control requiredField" name="bank_id" id="bank_id">' +
                    '@foreach($banks as $bank)'+
                    '<option value="{{ $bank->id }}" >  {{ $bank->bank_name }} </option>' +
                    '@endforeach'+
                    '</select>' +
                    '</div>'+
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Account No</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input type="text" class="form-control requiredField" placeholder="Account No" name="account_no" id="account_no" value="" />' +
                    '</div>'+
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label class="sf-label">Account Title</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input type="text" class="form-control requiredField" placeholder="Account Title" name="account_title" id="account_title" value="" />' +
                    '</div>');
            }
            else
            {
                $("#bank_account_area_1").html('');
            }

        });


    </script>





    <script src="{{ URL::asset('assets/custom/js/employees.js') }}"></script>
@endsection

