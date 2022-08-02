
<?php
use App\Helpers\CommonHelper;
// $accType = Auth::user()->acc_type;
//	if($accType == 'client'){
//		$m = $_GET['m'];
//	}else{
//		$m = Auth::user()->company_id;
//	}

$m = $_GET['m'];
use App\Models\Employee;
// $accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
CommonHelper::companyDatabaseConnection($m);
$employee_name = Employee::select('id','emp_id','emp_name')->where('status','=',1)->get();
CommonHelper::reconnectMasterDatabase();
$banks=DB::table('banks')->where('status',1)->get();

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="robots" content="noindex, nofollow" />
    <link rel="icon" href="{{ URL::asset('assets/images/logoTab.PNG') }}">
    <title>{{ env('APP_NAME') }}</title>
    <!-- Custom CSS -->
    <link href="{{ URL::asset('assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">


    <!-- Custom CSS -->
    <link href="{{ URL::asset('assets/dist/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/custom/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/select2.min.css') }}">


    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/animate.min.css') }}">
    <script src="{{ URL::asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dist/js/bootstrap-notify.js') }}"></script>
    {{--<link href="{{ URL::asset('assets/custom/css/print.css') }}" rel="stylesheet" />--}}
    <style>
        @media print {
            .hidden-print{
                display: none;
            }
        }
        .has-search .form-control {
            padding-left: 2.375rem;
        }
        .hscroll {
            overflow-x: auto; /* Horizontal */
        }
        @media only screen and (max-width: 414px) {
            table {
                width:95%;
            }
            table input[type=text],input[type=number], input[type=date],input[type=file],textarea {
                width:200px;
            }
        }
        @media only screen and (max-width: 736px) {
            table {
                width:95%;
            }
            table input[type=text],input[type=number], input[type=date],input[type=file],textarea {
                width:200px;
            }
        }
        @media only screen and (max-width: 768px) {
            table {
                width:95%;
            }
            table input[type=text],input[type=number], input[type=date],input[type=file],textarea {
                width:200px;
            }
        }
        .has-search .form-control-feedback {
            position: absolute;
            z-index: 2;
            display: block;
            width: 2.375rem;
            height: 2.375rem;
            line-height: 2.375rem;
            text-align: center;
            pointer-events: none;
            color: #aaa;
        }
        .card-body {

            border-top: 4px solid #5f76e8;
        }
        .table thead th, .table th{
            font-weight: 300;

        }
        .table th {
            color: black;
        }
    </style>
    </head>
<body>
<div class="container-fluid" id="mainSFContent">
   
       <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->


        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="card-title">Personal Data Form</h4>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo Form::open(array('url' => 'addEmployeeDetailDraft','id'=>'employeeForm',"enctype"=>"multipart/form-data"));?>
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
                                    <div class="row" style="background-color: rgb(220 213 247);font-size: 20px;color: black">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <h4  style="font-size: 20px;margin-top: 10px;">Personal Information</h4>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
                                    </div>
                                    <div class="row">&nbsp;</div>
                                    <div class="row">
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
                                            <label class="text-dark sf-label">Date of Birth</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="date" class="form-control requiredField" placeholder="Date of Birth" name="date_of_birth_1" id="date_of_birth_1" value="" />
                                        </div>
                                         <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Place of Birth</label>
                                            <input type="text" class="form-control" placeholder="Pace of Birth" name="place_of_birth_1" id="place_of_birth_1" value="" />
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
                                            <label class="text-dark sf-label">Religion</label>
                                            <input type="text" class="form-control" placeholder="Pace of Birth" name="relegion_1" id="relegion_1" value="" />
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                         <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label class="text-dark sf-label">Present Address</label>
                                            <textarea class="form-control" placeholder="Pace of Birth" name="present_address" id="present_address" rows="7"></textarea>
                                        </div>
                                         <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label class="text-dark sf-label">Permanent Address</label>
                                            <textarea class="form-control" placeholder="Pace of Birth" name="permanent_address" id="permanent_address" rows="7"></textarea>
                                        </div>
                                        
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <span class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Cell Number</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <div  class="input-group-prepend">
                                                <span class="input-group-text">+92</span>
                                                <div class="input-group-area" style="width:100%;">
                                                    <input type="text" id="emp_contact_no" name="emp_contact_no" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" onkeyup ="checkMobileNumber(this.value=this.value.replace(/^0+/, ''),'errorMobileNumberOne','1','e')" class="form-control requiredField" value="" placeholder="3152401099" maxlength="10" onkeypress="return isNumber(event)" />
                                                </div>
                                            </div>
                                        <span style="color:red;font-size:13px;font-weight: bold;" class="errorMobileNumberOne" ></span>
                                        </span>
                                        <span class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Home Number</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <div  class="input-group-prepend">
                                                <span class="input-group-text">+92</span>
                                                <div class="input-group-area" style="width:100%;">
                                                    <input type="text" id="contact_home" name="contact_home" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" onkeyup ="checkMobileNumber(this.value=this.value.replace(/^0+/, ''),'errorMobileNumberOne','1','e')" class="form-control requiredField" value="" placeholder="3152401099" maxlength="10" onkeypress="return isNumber(event)" />
                                                </div>
                                            </div>
                                        <span style="color:red;font-size:13px;font-weight: bold;" class="errorMobileNumberOne" ></span>
                                        </span>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Email</label>
                                            <input type="text" class="form-control" placeholder="Enter Email" name="email_1" id="email_1" value="" />
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Blood Group</label>
                                            <input type="text" class="form-control" placeholder="Blood Group" name="blood_group_1" id="blood_group_1" value="" />
                                        </div>
                                        
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">CNIC</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="text" class="form-control requiredField cnicExistMessage" onKeyDown="javascript: var keycode = stopKeyPressedSpace(event); if(keycode==32 || keycode==189){ return false; }" maxlength="15" placeholder="CNIC Number" name="cnic_1" id="cnic_1" value="" />
                                            <span style="color:red;font-size:13px;font-weight: bold;" id="cnicExistMessage"></span>
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Mark of Identification</label>
                                            <input type="text" class="form-control" placeholder="Mark of Identification" name="mark_of_identification_1" id="mark_of_identification_1" value="" />
                                        </div>
                                        <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="text-dark sf-label">Nationality</label>
                                            <input type="text" class="form-control" placeholder="Nationality" name="nationality_1" id="nationality_1" value="" />
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
                                            <label class="text-dark sf-label">DL Number</label>
                                            <input type="number" class="form-control" placeholder="DL Number" name="landline_number" id="landline_number" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="bank_account_area_1"></div>

                                <div class="row">&nbsp;</div>

                                <div class="row" style="background-color: rgb(220 213 247);font-size: 20px;color: black";>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4 style="font-size: 20px;margin-top: 10px;">Qualifications (please attach attested copies of all your degrees and Certificates)</h4>
                                    </div>

                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" id="education_area_1">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="hscroll">
                                        <input type="hidden" name="education_data[]" value="1"><table class="table table-sm -mb-0 table-bordered"><thead><th class="text-center">S.No</th>
                                        <th class="text-center">Education Level</th><th class="text-center">SubJect</th>
                                        <th class="text-center">Grade/GPA/DIV</th><th class="text-center">Year of Passing</th><th class="text-center">Name Of Institution</th>
                                        <th class="text-center"><button type="button" id="addMoreQualification" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></th>
                                    </thead><tbody id="insert_clone_education"><tr class="get_education_rows"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>
                                    <td class="text-center"><input type="hidden" name="qualificationSection[]">
                                        <select style="width:300px;" id="degree_type_1" class="form-control requiredField get_clone_education" name="degree_type_1"><option value="">Select</option>
                                            @foreach($DegreeType as $DegreeTypeValue)<option value="{{ $DegreeTypeValue->id }}">{{ $DegreeTypeValue->degree_type_name }}</option>@endforeach<option value="other">Other</option></select><span id="other_option_1"></span></td>
                                    <td class="text-center"><input name="subject_1" type="text" class="form-control requiredField" id="subject_1" value=""></td>
                                    <td class="text-center"><input name="grade_1" type="text" class="form-control requiredField" id="grade_1" value=""></td>
                                    <td class="text-center"><input name="year_of_passing_1" type="date" class="form-control requiredField" id="year_of_passing_1" value=""></td>
                                    <td class="text-center"><input name="institute_name_1" type="text" class="form-control requiredField" id="institute_name_1" value=""></td>
                                    <td class="text-center">-</td></tr></tbody></table></div></div>
                                </div>
                                <div class="row">&nbsp;</div>


                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: rgb(220 213 247);color: black";>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <h4 style="font-size: 20px;margin-top: 10px;color: black">Work Experience</h4>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h5 style="font-size: 20px;margin-top: 10px;color: black">Most recent first</h5>
                                    </div>

                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="work_experience_area_1">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="hscroll">
                                            <table class="table table-sm mb-0 table-bordered"><thead><th class="text-center">S.No</th>
                                                <th class="text-center">Company Name<span class="rflabelsteric"><strong>*</strong></span></th>
                                                <th class="text-center">From</th><th class="text-center">Till</th>
                                                <th class="text-center">Desgination</th><th class="text-center">Department</th>
                                                <th class="text-center">Drawn Salary</th><th class="text-center">Reason</th>
                                                <th class="text-center">File</th>
                                                <th class="text-center"><button type="button" id="addMoreWorkExperience" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"></i></button></th>
                                                </thead>
                                                <tbody id="insert_clone1">
                                                <tr class="get_rows1">
                                                    <td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>
                                                    <td id="get_clone1" class="text-center"><input type="hidden" name="work_experience_data[]" value="1">
                                                        <input type="text" name="employeer_name_1" id="employeer_name_1" class="form-control requiredField" required></td>
                                                    <td class="text-center"><input name="started_1" type="date" class="form-control" id="started_1">
                                                    </td><td class="text-center"><input name="ended_1" id="ended_1"type="date" class="form-control" ></td>
                                                    </td><td class="text-center"><input name="emp_designation_1" id="emp_designation_1"type="text" class="form-control" ></td>
                                                    </td><td class="text-center"><input name="emp_depart_1" id="emp_depart_1"type="text" class="form-control" ></td>
                                                    </td><td class="text-center"><input name="emp_last_drawn_salary_1" id="emp_last_drawn_salary_1"type="number" class="form-control" ></td>
                                                    </td><td class="text-center"><input name="reason_for_separation_1" id="reason_for_separation_1"type="text" class="form-control" ></td>
                                                    <td class="text-center"><input type="file" class="form-control" name="work_exp_path_1" id="work_exp_path_1" multiple></td>
                                                    <td class="text-center">-</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: rgb(220 213 247);color: black";>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4 style="font-size: 20px;margin-top: 10px;">Official References (2 Mandatory)</h4>
                                    </div>


                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="reference_area_1">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="hscroll">
                                            <input type="hidden" name="reference_data[]" value="1">
                                            <table class="table table-sm -mb-0 table-bordered">
                                                <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Desigination</th>
                                                <th class="text-center">Age</th>
                                                <th class="text-center">Contact</th>
                                                <th class="text-center">Relation</th>
                                                <th class="text-center"><button type="button" id="addMoreReference" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></th>
                                                </thead>
                                                <tbody id="insert_clone2">
                                                <tr class="get_rows2"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>
                                                    <td class="text-center"><input name="reference_name_1" type="text" class="form-control requiredField" id="reference_name_1" value=""></td>
                                                    <td class="text-center"><input name="reference_designation_1" type="text" class="form-control" id="reference_designation_1" value=""></td>
                                                    <td class="text-center"><input name="reference_age_1" type="text" class="form-control" id="reference_age_1" value=""></td>
                                                    <td class="text-center"><input name="reference_contact_1" type="number" class="form-control" id="reference_contact_1" value=""></td>
                                                    <td class="text-center"><input name="reference_relationship_1" type="text" class="form-control" id="reference_relationship_1" value=""></td>
                                                    <td class="text-center">-</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: rgb(220 213 247);color: black";>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4 style="font-size: 20px;margin-top: 10px;">In Case of Emergency (2 Mandatory)</h4>
                                    </div>

                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="emergency_area_1">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="hscroll">
                                            <input type="hidden" name="emergency_data[]" value="1">
                                            <table class="table table-sm -mb-0 table-bordered">
                                                <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Relation</th>
                                                <th class="text-center">Address</th>
                                                <th class="text-center">Contact</th>
                                                <th class="text-center"><button type="button" id="addMoreEmergency" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></th>
                                                </thead>
                                                <tbody id="insert_clone3">
                                                <tr class="get_rows3"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>
                                                    <td class="text-center"><input name="person_name" type="text" class="form-control requiredField" id="person_name" value=""></td>
                                                    <td class="text-center"><input name="persons_relation" type="text" class="form-control" id="persons_relation" value=""></td>
                                                    <td class="text-center"><input name="persons_address" type="text" class="form-control" id="persons_address" value=""></td>
                                                    <td class="text-center"><input name="emergency_no" type="number" class="form-control" id="emergency_no" value=""></td>
                                                    <td class="text-center">-</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" style="background-color: rgb(220 213 247);color: black;font-size: 20px">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4 style="font-size: 20px;margin-top: 10px;">Depends Information</h4>
                                    </div>


                                </div>
                                <div class="row">&nbsp;</div>
                                <div class="row" id="depends_formation_area_1">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="hscroll">
                                            <input type="hidden" name="depends_info_data[]" value="1">
                                            <table class="table table-sm -mb-0 table-bordered">
                                                <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Relationship</th>
                                                <th class="text-center">Date of birth</th>
                                                <th class="text-center">Age</th>
                                                <th class="text-center">Cnic No/B-form No.</th>
                                                <th class="text-center"><button type="button" id="addMoreDependsInfo" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></th>
                                                </thead>
                                                <tbody id="insert_clone_depends">
                                                <tr class="get_depends_rows"><td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>
                                                    <td class="text-center"><input name="depends_formation_name_1" type="text" class="form-control requiredField" id="depends_formation_name_1" value=""></td>
                                                    <td class="text-center"><input name="depends_formation_relationship_1" type="text" class="form-control" id="depends_formation_relationship_1" value=""></td>
                                                    <td class="text-center"><input name="depends_formation_date_of_birth_1" type="date" class="form-control" id="depends_formation_date_of_birth_1" value=""></td>
                                                    <td class="text-center"><input name="depends_formation_age_1" type="number" class="form-control" id="depends_formation_age_1" value=""></td>
                                                    <td class="text-center"><input name="depends_formation_cnic_1" type="text" class="form-control" id="depends_formation_cnic_1" value=""></td>
                                                    <td class="text-center">-</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">&nbsp;</div>
                                
                                <div class="row" style="background-color: rgb(220 213 247);color: black;font-size: 20px;">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h4 style="font-size: 20px;margin-top: 10px;">Nominations on behalf of employee</h4>
                                    </div>

                                </div>

                                <div class="row">&nbsp;</div>
                                <div class="row" id="nomination_behalf_area_1">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="hscroll">
                                            <input type="hidden" name="nominee_behalf_data[]" value="1">
                                            <table class="table table-sm -mb-0 table-bordered">
                                                <thead><th class="text-center">S.No</th>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Cnic No.</th>
                                                <th class="text-center">Address</th>
                                                <th class="text-center">Realtion</th>
                                                <th class="text-center">Share of Amount (in%)</th>
                                                <th class="text-center"><button type="button" id="addMoreNominationBehalf" class="icon btn btn-sm btn-primary"><i class="fas fa-plus"><i/></button></th>
                                                </thead>
                                                <tbody id="insert_clone_nominee">
                                                <tr class="get_nomination_rows">
                                                    <td class="text-center"><span class="badge badge-pill badge-secondary">1</span></td>
                                                    <td class="text-center"><input name="name_of_nominee_1" type="text" class="form-control requiredField" id="name_of_nominee_1" value=""></td>
                                                    <td class="text-center"><input name="cnic_of_nominee_1" type="number" class="form-control" id="cnic_of_nominee_1" value=""></td>
                                                    <td class="text-center"><input name="address_of_nominee_1" type="text" class="form-control" id="address_of_nominee_1" value=""></td>
                                                    <td class="text-center"><input name="relation_of_nominee_1" type="text" class="form-control" id="relation_of_nominee_1" value=""></td>
                                                    <td class="text-center"><input name="share_of_nominee_1" type="number" class="form-control" id="share_of_nominee_1" value=""></td>
                                                    <td class="text-center">-</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
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
    


<script src="{{ URL::asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('assets/dist/js/app-style-switcher.js') }}"></script>
<script src="{{ URL::asset('assets/dist/js/feather.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ URL::asset('assets/dist/js/sidebarmenu.js') }}"></script>
<script src="{{ URL::asset('assets/dist/js/custom.min.js') }}"></script>
<script src="{{ URL::asset('assets/extra-libs/c3/d3.min.js') }}"></script>
<script src="{{ URL::asset('assets/extra-libs/c3/c3.min.js') }}"></script>
<script src="{{ URL::asset('assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ URL::asset('assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ URL::asset('assets/dist/js/pages/dashboards/dashboard1.min.js') }}"></script>
<script src="{{ url('assets//dist/js/dataTables/jquery.dataTables.js') }}"> </script>
<script src="{{ URL::asset('assets/custom/js/modal.js') }}"></script>
<script src="{{ URL::asset('assets/dist/js/select2.min.js') }}"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script src="{{ URL::asset('assets/custom/js/custom.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/dist/js/summernote-bs4.js') }}"></script>
<script src="{{ URL::asset('assets/dist/js/popper.js') }}"></script>
<script src="{{ URL::asset('assets/custom/js/customHrFunction.js') }}"></script>

<footer class="text-center text-muted">
	All Rights Reserved by <strong>Innovative Network</strong>.
</footer>

</div>

</div>







    <div class="loaderbody" id="loaderbody" style="display: none;">
        <div class="loader"></div>
    </div>

</div>

 

    <script src="{{ URL::asset('assets/custom/js/employeeDraft.js') }}"></script>
    <script>

        $(document).ready(function() {
            qualification();
            nomination();
            dependsInfo();
            workExperience();
            referenceCheck();
            emergencyCheck();
        });
        $('#documents_upload_check').click(function(){

            if($(this).is(":checked") == true)
            {
                $("#file_upload_area").html('<label for="media">Upload File:</label>' +
                    '<input type="file" class="form-control" name="media[]" id="media" multiple>');
            }
            else
            {
                $("#file_upload_area").html('');
            }
        })
        $('#reference_check_1').click(function(){

            if($(this).is(":checked") == true)
            {
                referenceCheck();

            }
            else
            {
                $("#reference_area_1").html('');
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
<script>
    $(document).ready(function () {
        $('select').select2();
        $(document).bind('ajaxStart', function () {
            $("#footer").css({"display": "none"});
            $("#loaderbody").css({"display": "block"});
        }).bind('ajaxStop', function () {
            $("#loaderbody").css({"display": "none"});
            $("#footer").css({"display": "block"});
        });
    });
    function referenceCheck(){


        $("#addMoreReference").click(function(e){

            var form_rows_count = $(".get_rows2").length;
            form_rows_count++;
            $("#insert_clone2").append("<tr class='get_rows2' id='remove_area_reference_"+form_rows_count+"' ><td class='text-center'>" +
                "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td>" +
                "<td class='text-center'><input name='reference_name_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='reference_name_"+form_rows_count+"'></td>" +
                "<td class='text-center'><input name='reference_designation_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='reference_designation_"+form_rows_count+"'></td>" +
                "<td class='text-center'><input name='reference_age_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='reference_age_"+form_rows_count+"'></td>" +
                "<td class='text-center'><input name='reference_contact_"+form_rows_count+"' type='number' class='form-control requiredField' value='' id='reference_contact_"+form_rows_count+"'></td>" +
                "<td class='text-center'><input name='reference_relationship_"+form_rows_count+"' type='text' class='form-control requiredField' value='' id='reference_relationship_"+form_rows_count+"'></td>" +
                "<td class='text-center'><button onclick='removeReferenceSection("+form_rows_count+")'  type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button></td>" +
                "</tr>");
            $('#degree_type_'+form_rows_count+'').select2();
        });

    }
    function removeReferenceSection(id) {
        $("#remove_area_reference_"+id).remove();
    }
    function removeEmergencySection(id) {
        $("#remove_area_emeregency_"+id).remove();
    }
    function emergencyCheck(){


        $("#addMoreEmergency").click(function(e){

            var form_rows_emergency_count = $(".get_rows3").length;
            form_rows_emergency_count++;
            $("#insert_clone3").append("<tr class='get_rows3' id='remove_area_emeregency_"+form_rows_emergency_count+"' ><td class='text-center'>" +
                "<span class='badge badge-pill badge-secondary'>"+form_rows_emergency_count+"<span></td>" +
                "<td class='text-center'><input name='person_name_"+form_rows_emergency_count+"' type='text' class='form-control requiredField' value='' id='person_name_"+form_rows_emergency_count+"'></td>" +
                "<td class='text-center'><input name='persons_relation_"+form_rows_emergency_count+"' type='text' class='form-control requiredField' value='' id='persons_relation_"+form_rows_emergency_count+"'></td>" +
                "<td class='text-center'><input name='persons_address_"+form_rows_emergency_count+"' type='text' class='form-control requiredField' value='' id='persons_address_"+form_rows_emergency_count+"'></td>" +
                "<td class='text-center'><input name='emergency_no_"+form_rows_emergency_count+"' type='number' class='form-control requiredField' value='' id='emergency_no_"+form_rows_emergency_count+"'></td>" +
                "<td class='text-center'><button onclick='removeEmergencySection("+form_rows_emergency_count+")'  type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button></td>" +
                "</tr>");
            $('#degree_type_'+form_rows_emergency_count+'').select2();
        });

    }
function  workExperience(){


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

    $("#addMoreWorkExperience").click(function(e){
        var form_rows_count = $(".get_rows1").length;
        form_rows_count++;
        $("#insert_clone1").append("<tr class='get_rows1' id='remove_area1_"+form_rows_count+"' ><td class='text-center'>" +
            "<span class='badge badge-pill badge-secondary'>"+form_rows_count+"<span></td><td>" +
            '<input type="hidden" name="work_experience_data[]" value="'+form_rows_count+'">' +
            "<input type='text' name='employeer_name_"+form_rows_count+"' class='form-control requiredField' required></td>" +
            "<td class='text-center'><input name='started_"+form_rows_count+"' id='started_"+form_rows_count+"'  type='date' class='form-control' value=''></td>" +
            "<td class='text-center'><input name='ended_"+form_rows_count+"' id='ended_"+form_rows_count+"' type='date' class='form-control' value=''></td>" +
            "<td class='text-center'><input name='emp_designation_"+form_rows_count+"' id='emp_designation_"+form_rows_count+"'  type='text' class='form-control' value=''></td>" +
            "<td class='text-center'><input name='emp_depart_"+form_rows_count+"' id='emp_depart_"+form_rows_count+"' type='text' class='form-control' value=''></td>" +
            "<td class='text-center'><input name='last_drawn_salary_"+form_rows_count+"' id='last_drawn_salary_"+form_rows_count+"'  type='number' class='form-control' value=''></td>" +
            "<td class='text-center'><input name='reason_for_separation_"+form_rows_count+"' id='reason_for_separation_"+form_rows_count+"' type='text' class='form-control' value=''></td>" +
            "<td class='text-center'><input type='file' class='form-control' name='work_exp_path_"+form_rows_count+"' id='work_exp_path_"+form_rows_count+"' multiple></td>" +
            "<td class='text-center'><button onclick='removeWorkExperienceSection("+form_rows_count+")' type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button>" +
            "</td>" +
            "</tr>");
        $("#career_level_"+form_rows_count+"").select2();

    });
}
function  qualification(){

    $("#addMoreQualification").click(function(e){
        var clone = $(".get_clone_education").html();

        var form_rows_qualification_count = $(".get_education_rows").length;
        form_rows_qualification_count++;
        $("#insert_clone_education").append("<tr class='get_education_rows' id='remove_area_"+form_rows_qualification_count+"' ><td class='text-center'>" +
            "<span class='badge badge-pill badge-secondary'>"+form_rows_qualification_count+"<span></td>" +
            "<td><input type='hidden' name='education_data[]' value="+form_rows_qualification_count+">" +
            "<select style='width:300px;' id='degree_type_"+form_rows_qualification_count+"' class='form-control requiredField' name='degree_type_"+form_rows_qualification_count+"'>"+clone+"</select>" +
            "<span id='other_option_"+form_rows_qualification_count+"'></span></td>" +
            "<td class='text-center'><input name='subject_"+form_rows_qualification_count+"' type='text' class='form-control requiredField' value='' id='subject_"+form_rows_qualification_count+"'></td>" +
            "<td class='text-center'><input name='grade_"+form_rows_qualification_count+"' type='text' class='form-control requiredField' value='' id='grade_"+form_rows_qualification_count+"'></td>" +
            "<td class='text-center'><input name='year_of_passing_"+form_rows_qualification_count+"' type='date' class='form-control requiredField' value='' id='year_of_admission_"+form_rows_qualification_count+"'></td>" +
            "<td class='text-center'><input name='institute_name_"+form_rows_qualification_count+"' type='text' class='form-control requiredField' value='' id='year_of_passing_"+form_rows_qualification_count+"'></td>" +
            "<td class='text-center'><button onclick='removeQualificationSection("+form_rows_qualification_count+")'  type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button></td>" +
            "</tr>");
        $('#degree_type_'+form_rows_qualification_count+'').select2();

    });
    $('#degree_type_1').select2();
}
   function  nomination(){

       $("#addMoreNominationBehalf").click(function(e){
           var clone = $(".get_clone_nomination").html();

           var form_rows_nomination_count = $(".get_nomination_rows").length;
           form_rows_nomination_count++;
           $("#insert_clone_nominee").append("<tr class='get_nomination_rows' id='remove_area_"+form_rows_nomination_count+"' ><td class='text-center'>" +
               "<span class='badge badge-pill badge-secondary'>"+form_rows_nomination_count+"<span></td>" +
               "<td class='text-center'><input name='name_of_nominee_"+form_rows_nomination_count+"' type='text' class='form-control requiredField' value='' id='name_of_nominee_"+form_rows_nomination_count+"'></td>" +
               "<td class='text-center'><input name='cnic_of_nominee_"+form_rows_nomination_count+"' type='number' class='form-control requiredField' value='' id='cnic_of_nominee_"+form_rows_nomination_count+"'></td>" +
               "<td class='text-center'><input name='address_of_nominee_"+form_rows_nomination_count+"' type='text' class='form-control requiredField' value='' id='address_of_nominee_"+form_rows_nomination_count+"'></td>" +
               "<td class='text-center'><input name='relation_of_nominee_"+form_rows_nomination_count+"' type='text' class='form-control requiredField' value='' id='relation_of_nominee_"+form_rows_nomination_count+"'></td>" +
               "<td class='text-center'><input name='share_of_nominee_"+form_rows_nomination_count+"' type='number' class='form-control requiredField' value='' id='share_of_nominee_"+form_rows_nomination_count+"'></td>" +
               "<td class='text-center'><button onclick='removeNomineeBehalfSection("+form_rows_nomination_count+")'  type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button></td>" +
               "</tr>");
           $('#degree_type_'+form_rows_nomination_count+'').select2();

       });
       $('#degree_type_1').select2();
   }

   function  dependsInfo(){

       $("#addMoreDependsInfo").click(function(e){
           var clone = $(".get_clone_depends_info").html();

           var form_rows_depends_count = $(".get_depends_rows").length;
           form_rows_depends_count++;

           $("#insert_clone_depends").append("<tr class='get_depends_rows' id='remove_area_"+form_rows_depends_count+"' ><td class='text-center'>" +
               "<span class='badge badge-pill badge-secondary'>"+form_rows_depends_count+"<span></td>" +
               "<td class='text-center'><input name='depends_formation_name_"+form_rows_depends_count+"' type='text' class='form-control requiredField' value='' id='depends_formation_name_"+form_rows_depends_count+"'></td>" +
               "<td class='text-center'><input name='depends_formation_relationship_"+form_rows_depends_count+"' type='text' class='form-control requiredField' value='' id='depends_formation_relationship_"+form_rows_depends_count+"'></td>" +
               "<td class='text-center'><input name='depends_formation_date_of_birth_"+form_rows_depends_count+"' type='date' class='form-control requiredField' value='' id='depends_formation_date_of_birth_"+form_rows_depends_count+"'></td>" +
               "<td class='text-center'><input name='depends_formation_age_"+form_rows_depends_count+"' type='text' class='form-control requiredField' value='' id='depends_formation_age_"+form_rows_depends_count+"'></td>" +
               "<td class='text-center'><input name='depends_formation_cnic_"+form_rows_depends_count+"' type='text' class='form-control requiredField' value='' id='depends_formation_cnic_"+form_rows_depends_count+"'></td>" +
               "<td class='text-center'><button onclick='removeDependsSection("+form_rows_depends_count+")'  type='button'class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></button></td>" +
               "</tr>");
           $('#degree_type_'+form_rows_depends_count+'').select2();

       });
       $('#degree_type_1').select2();
   }
</script>

</body>
</html>
