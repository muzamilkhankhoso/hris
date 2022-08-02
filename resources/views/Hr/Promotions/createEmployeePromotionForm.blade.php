<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
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
                    <div class="card-body" id="PrintEmployeeList">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Create Employee Promotion Form</h4>
                            </div>


                        </div>
                        <hr>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'had/addEmployeePromotionDetail','id'=>'employeePromotionForm',"enctype"=>"multipart/form-data"));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" id="company_id" value="<?=$m?>">
                            <input type="hidden" name="employeeSection[]">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label pointer">Department</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control" name="department_id" id="department_id" onchange="getEmployee()">
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
                                                <select class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
                                                    <option value="0">Select Department</option>
                                                    @foreach($sub_department_id  as $key => $y)
                                                        <option value="<?php echo $y->id ?>">
                                                            {{ $y->sub_department_name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Employee:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="emp_id" id="emp_id" required></select>
                                                <div id="emp_loader_1"></div>
                                            </div>

                                        </div>

                                        <div id="emp_data_loader">&nbsp;</div>
                                       <div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="emp_data"></div></div>

                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Designation</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" id="designation_id" name="designation_id" required>
                                                    <option value="">Select</option>
                                                    @foreach($designation as $key5 => $value5)
                                                        <option value="{{ $value5->id}}">{{ $value5->designation_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div id="emp_data_loader">&nbsp;</div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label>Letter Upload</label>
                                                <span class="rflabelsteric"><strong></strong></span>
                                                <input type="file" name="letter_uploading[]" id="letter_uploading[]" class="form-control" multiple>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Increment :</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" name="increment" id="increment" onkeyup="changeSalary()" value="" class="form-control requiredField" required/>
                                            </div>

                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Salary :</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" name="salary" id="salary" value="" class="form-control requiredField" required readonly/>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Promotion Date :</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" name="promotion_date" id="promotion_date" value="" class="form-control requiredField" required/>
                                            </div>
                                            {{--<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">--}}
                                                {{--<label class="sf-label">Add Allowances :</label><br>--}}
                                                {{--<input type="checkbox" name="addAllowancesCheck" id="addAllowancesCheck" value="1"/>--}}
                                            {{--</div>--}}
                                        </div>
                                        {{--<div class="row">--}}
                                            {{--<div class="form_area" id="addAllowancesArea" style="display: none;"></div>--}}
                                        {{--</div>--}}
                                        {{--<div class="allowanceLoader"></div>--}}
                                        {{--<div class="allowanceData"></div>--}}
                                        <br>
                                    </div>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <input type="button" class="btn btn-sm btn-primary addMoreAllowanceSection" value="Add More Allowance Section" id="addMoreAllowancesBtn" style="display: none" />
                                        {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                                    </div>
                                </div>
                                <?php echo Form::close();?>
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
        <!-- End Right sidebart -->
        <!-- ============================================================== -->
    </div>









@endsection

