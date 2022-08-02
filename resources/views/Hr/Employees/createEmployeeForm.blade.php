
<?php
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
//	if($accType == 'client'){
//		$m = $_GET['m'];
//	}else{
//		$m = Auth::user()->company_id;
//	}

$m = $_GET['m'];
use App\Models\Employee;
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
CommonHelper::companyDatabaseConnection($m);
$employee_name = Employee::select('id','emp_id','emp_name')->where('status','=',1)->get();
CommonHelper::reconnectMasterDatabase();
$banks=DB::table('banks')->where('status',1)->get();

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
                        <div class="col-sm-12">
                            <h4 class="card-title">Create Employee Form</h4>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo Form::open(array('url' => 'had/addEmployeeDetail','id'=>'employeeForm',"enctype"=>"multipart/form-data"));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?=$m?>">


                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                        <div class="hr-border" style=""></div>
                                        <img id="img_file_1" class="avatar img-circle img-thumbnail" style="width:20%;" src="{{  url('storage/app/uploads/employee_images/user-dummy.png') }}">
                                    </div>
                                    <div class="form-group text-center">
                                        <label class="text-dark">
                                            <input type="file" id="file_1" name="fileToUpload_1" accept="image/*" capture style="display:none"/>
                                            <img class="avatar img-circle img-thumbnail" style="width:20%;" src="<?= url('assets/images/cam.png')?>" id="upfile1" style="cursor:pointer" />
                                            Change Image
                                        </label>
                                    </div>
                                    <div class="row">&nbsp;</div>
                                    <div class="row" style="background-color: rgb(220 213 247);";>
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
                                            <input type="number" name="emp_id" id="emp_id" class="form-control requiredField">
                                            <span style="color:red;font-weight: bold;font-size: 13px;" id="emrExistMessage"></span>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Employee Name</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="text" class="form-control requiredField" placeholder="Employee Name" name="employee_name_1" id="employee_name_1" value="" />
                                        </div>

                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Father / Husband Name</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="text" class="form-control requiredField" placeholder="Father Name" name="father_name_1" id="father_name_1" value="" />
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label pointer">Department</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select style="width:100%;" class="form-control requiredField" name="department_id_" id="department_id_">
                                                <option value="">Select Department</option>
                                                @foreach($Department as $key => $y)
                                                    <option value="<?php echo $y->id ?>"> {{ $y->department_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                    </div>
                                    <hr>
                                    <div class="row">


                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label for="exampleFormControlSelect1">Sub Department</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select style="width:100%;" class="form-control requiredField" name="sub_department_id_1" id="sub_department_id_1">
                                                <option value="">Select Department</option>
                                                @foreach($sub_department as $key => $y)
                                                    <option value="<?php echo $y->id ?>"> 				{{ $y->sub_department_name}}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 custom-div">
                                            <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Designation','designation','designation_name','designation','<?php echo $m; ?>')"><label class="text-dark pointer sf-label">Designation</label></a>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select style="width:100%;" class="form-control requiredField designation" id="designation_1" name="designation_1">
                                                <option value="">Select</option>
                                                @foreach($designation as $key5 => $value5)
                                                    <option value="{{ $value5->id}}">{{ $value5->designation_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 custom-div">
                                            <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Marital Status','marital_status','marital_status_name','marital_status','<?php echo $m; ?>')"><label class="text-dark pointer sf-label">Marital Status</label></a>
                                            <select style="width:100%;" class="form-control marital_status" name="marital_status_1" id="marital_status_1">
                                                <option value="">Select Marital</option>
                                                @foreach($marital_status as $key4 => $value2)
                                                    <option value="{{ $value2->id}}">{{ $value2->marital_status_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Joining Date</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="date" class="form-control requiredField" placeholder="Joining Date" name="joining_date_1" id="joining_date_1" value="" />
                                        </div>

                                    </div>

                                    <hr>


                                    <div class="row">

                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 custom-div">

                                            <a onclick="insertMasterTableRecord('hdc/viewMasterTableForm','Add Employment Status','job_type','job_type_name','employee_status','<?php echo $m; ?>')"><label class="text-dark pointer sf-label">Employment Status</label></a>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select style="width:100%;" class="form-control requiredField employee_status" name="employee_status_1" id="employee_status_1" onchange="employeestatus(this.value)">
                                                <option value="">Select Employment Status</option>
                                                @foreach($jobtype as $key3 => $value)
                                                    <option value="{{ $value->id}}">{{ $value->job_type_name}}</option>
                                                @endforeach
                                            </select>



                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label pointer">Probotion/Internee Expire Date</label>
                                            <span class="rflabelsteric"><strong></strong></span>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="date" name="pTimePeriod" id="pTimePeriod" class="form-control" disabled />
                                        </div>

                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">CNIC</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="text" class="form-control requiredField cnicExistMessage" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" maxlength="15" placeholder="CNIC Number" name="cnic_1" id="cnic_1" value="" />
                                            <span style="color:red;font-size:13px;font-weight: bold;" id="cnicExistMessage"></span>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">CNIC Expiry Date</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="date" class="form-control" name="cnic_expiry_date_1" id="cnic_expiry_date_1" value="" />
                                        </div>


                                    </div>


                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark">Upload CNIC Copy:</label>
                                            <input type="file" class="form-control" name="cnic_path_1" id="cnic_path_1" multiple>
                                        </div>

                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Date of Birth</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="date" class="form-control requiredField" placeholder="Date of Birth" name="date_of_birth_1" id="date_of_birth_1" value="" />
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Gender</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select style="width:100%;" class="form-control requiredField" name="gender_1" id="gender_1">
                                                <option value="">Select Gender</option>
                                                <option value="1">Male</option>
                                                <option value="2">Female</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Place of Birth</label>
                                            <input type="text" class="form-control" placeholder="Pace of Birth" name="place_of_birth_1" id="place_of_birth_1" value="" />
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">

                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Nationality</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="text" class="form-control requiredField" placeholder="Nationality" name="nationality_1" id="nationality_1" value="" />
                                        </div>

                                        <span class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Contact Number</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <div  class="input-group-prepend">
                                                <span class="input-group-text">+92</span>
                                                <div class="input-group-area" style="width:100%;">
                                                    <input type="text" id="contact_no" name="contact_no" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" onkeyup ="checkMobileNumber(this.value=this.value.replace(/^0+/, ''),'errorMobileNumberOne','1','e')" class="form-control requiredField" value="" placeholder="3152401099" maxlength="10" onkeypress="return isNumber(event)" />
                                                </div>

                                            </div>
                                        <span style="color:red;font-size:13px;font-weight: bold;" class="errorMobileNumberOne" ></span>


                                        </span>

                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Landline Number</label>
                                            <span class="input-group-prepend">
                                                <span class="input-group-text">+92</span>
                                                <span class="input-group-area" style="width:100%;">
                                                    <input type="text" id="landline_number" name="landline_number" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" onkeyup ="checkMobileNumber(this.value=this.value.replace(/^0+/, ''),'errorlandlineNumberOne','1','e')" class="form-control" value="" placeholder="3152401099" maxlength="10" onkeypress="return isNumber(event)" />
                                                </span>

                                            </span>

                                            <span style="color:red;font-size:13px;font-weight: bold;" class="errorlandlineNumberOne" ></span>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Personal Email</label>
                                            <input type="text" class="form-control" placeholder="Personal Email" name="email_1" id="email_1" value="" />
                                        </div>


                                    </div>

                                </div>
                                <hr>
                                <div class="row">

                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Official Email</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" placeholder="Professional Email" name="professional_email" id="professional_email" value="" />
                                    </div>
                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Compensation</label>

                                        <input type="number" class="form-control" placeholder="Compensation" name="compensation" id="compensation" value="" />
                                    </div>
                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Religion</label>
                                        <input type="text" class="form-control" placeholder="Religion Name" name="religion_1" id="religion_1" value="" />
                                    </div>
                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Eobi</label>
                                        <select style="width:100%;" class="form-control" name="eobi_id_1" id="eobi_id_1">
                                            <option value="0">--</option>
                                            @foreach($eobi as $value8)
                                                <option value="{{ $value8->id}}">
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
                                        <input type="text" class="form-control" placeholder="EOBI Number" name="eobi_number_1" id="eobi_number_1" value="" />
                                    </div>

                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">EOBI Upload</label>
                                        <input type="file" class="form-control" name="eobi_path_1" id="eobi_path_1" multiple>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Provident Fund</label>

                                        <select style="width:100%;" class="form-control" name="provident_fund_1" id="provident_fund_1">
                                            <option value="">-</option>
                                            @foreach($provident_fund as $value9)
                                                <option value="{{ $value9->id}}">{{ $value9->name.'=>Mode:'.$value9->pf_mode.'=>'.$value9->amount_percent}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Days Off</label>
                                        <select style="width:100%;" class="form-control requiredField"  name="days_off_1[]" id="days_off_1" multiple>
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
                                                   <input type="text" id="emergency_no_1" name="emergency_no_1"  value="" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" onkeyup="checkMobileNumber(this.value=this.value.replace(/^0+/, ''),'erroremergencyNumberOne','1','e')" class="form-control"  placeholder="3xxxxxxxxx" maxlength="10" onkeypress="return isNumber(event)" />
                                                    </span>

                                                    </span>
                                        <span style="color:red;font-size:13px;font-weight: bold;" class="erroremergencyNumberOne" ></span>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Emergency Person Name</label>

                                        <input type="text" class="form-control" placeholder="Emergency person's name" name="person_name" id="person_name" value="" />
                                    </div>
                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Emergency Person's Relation</label>
                                        <input type="text" class="form-control" placeholder="Emergency person's relation" name="persons_relation" id="persons_relation" value="" />
                                    </div>
                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Emergency Person's Address</label>
                                        <input type="text" class="form-control" placeholder="Emergency person's Address" name="persons_address" id="persons_address" value="" />
                                    </div>

                                </div>


                                <hr>
                                <div class="row">

                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Reporting Manager </label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select style="width:100%;" class="form-control requiredField" name="reporting_manager" id="reporting_manager">
                                            <option value="">Select Reporting Manager</option>
                                            @foreach($employee_name as $value)
                                                <option value="{{$value->emp_id}}">
                                                    {{ $value->emp_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Working Hours Policy</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select style="width:100%;" class="form-control requiredField" name="working_hours_policy_1" id="working_hours_policy_1">
                                            <option value="0">--</option>
                                            @foreach($working_policy as $working_policy_value)
                                                <option value="{{$working_policy_value->id}}">
                                                    {{'Office Time=='.$working_policy_value->start_working_hours_time.'--'.$working_policy_value->end_working_hours_time."| Grace Time== ".$working_policy_value->working_hours_grace_time." Mins | Half Day==".$working_policy_value->half_day_time." Hours"}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="text-dark sf-label">Leaves Policy</label>
                                            <select style="width:100%;" class="form-control" name="leaves_policy_1" id="leaves_policy_1">
                                                <option value="">Select</option>
                                                @foreach($leaves_policy as $key4 => $value3)
                                                    <option value="{{ $value3->id}}">{{ $value3->leaves_policy_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right" style="padding-top: 15px;">
                                            <button type="button" class="btn btn-sm btn-primary" id="leaves_policy_id_1">View Policy</button>
                                        </div>
                                    </div>

                                </div>

                                <hr>


                                {{--new employee details--}}
                                <div class="row">&nbsp;</div>

                                <div class="row" style="background-color: rgb(220 213 247);";>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Address</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="text-dark sf-label">Current Address</label>
                                        <textarea class="form-control" name="residential_address_1"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="text-dark sf-label">Permanent Address</label>
                                        <textarea class="form-control" name="permanent_address_1"></textarea>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: rgb(220 213 247);";>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                        <h4 style="font-weight: bold;margin-top: 10px;">Bank Account Details</h4>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <input type="checkbox" name="bank_account_check_1" id="bank_account_check_1">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="bank_account_area_1"></div>

                                <div class="row">&nbsp;</div>

                                <div class="row" style="background-color: rgb(220 213 247);";>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Educational Background</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h6 style="font-weight: bold;margin-top: 10px;">Start from Recent</h6>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="education_check_1" id="education_check_1">
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" id="education_area_1">

                                </div>
                                <div class="row">&nbsp;</div>


                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: rgb(220 213 247);";>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Work Experience</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h5 style="font-weight: bold;margin-top: 10px;">Most recent first</h5>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="work_experience_check_1" id="work_experience_check_1">
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="work_experience_area_1">
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: rgb(220 213 247);";>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Employee Document Upload</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

                                        <h5 style="font-weight: bold;margin-top: 10px;">Can Upload Multiple Files

                                        </h5>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="documents_upload_check" id="documents_upload_check" value="1">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div id="file_upload_area" class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                </div>

                                <div class="row">&nbsp;</div>


                                <div class="row" style="background-color: rgb(220 213 247);";>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4 style="font-weight: bold;margin-top: 10px;">Login Credentials</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">


                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12 text-right">
                                        <input type="checkbox" name="can_login_1" id="can_login_1" value="yes">
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" id="credential_area_1" style="display: none;">
                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Account type</label>
                                        <select style="width:100%;" class="form-control" name="account_type_1">
                                            <option value="user">User</option>
                                            <option value="client">Client</option>

                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Password</label>
                                        <input type="text" class="form-control" id="password_1" name="password_1">
                                    </div>
                                    <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <button type="button" style="margin-top:37px;color:white;" class="icon btn btn-sm btn-warning" onclick="password_generator()" >Generate</button>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="text-dark sf-label">Roles</label>
                                        <select style="width:100%;" class="form-control" name="role_1">
                                            <option value="">Select</option>
                                            @foreach($MenuPrivileges as $MenuPrivilege)
                                                <option value="{{$MenuPrivilege->id}}">{{$MenuPrivilege->role_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="employeeSection"></div>
                    <br>

                </div>
                <div class="row">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <span id="emp_warning" style="color:red;font-weight:bold;"></span>
                        {{ Form::submit('Submit', ['class' => 'btn btn-success btn-sm btn_disable','id'=>'btn_add']) }}
                        <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                    </div>
                </div>
                <?php echo Form::close();?>
            </div>
        </div>
    </div>

    <script>
        $('#education_check_1').click(function(){
            if($(this).is(":checked") == true)
            {
                $("#education_area_1").html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="">' +
                    '<input type="hidden" name="education_data[]" value="1"><table class="table table-sm -mb-0 table-bordered"><thead><th class="text-center">S.No</th>' +
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
@endsection

