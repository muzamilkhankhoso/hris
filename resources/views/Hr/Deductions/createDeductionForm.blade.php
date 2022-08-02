<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];

$currentDate = date('Y-m-d');
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
                                <h4 class="card-title">Create Deduction Form</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo Form::open(array('url' => 'had/addEmployeeDeductionDetail','id'=>'employeeForm'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?=$m?>">

                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label pointer">Department</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width:100%;" class="form-control" name="department_id" id="department_id" onchange="getEmployee()">
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
                                        <span class="form_area">
                                                <span class="get_data">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label class="sf-label">Remarks:</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <textarea name="remarks[]" id="remarks" value="" class="form-control requiredField" required> </textarea>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                        <label class="sf-label">Select Deduction Type:</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <select name="deduction_type[]" id="deduction_type" class="form-control requiredField" required>
                                                            <option value="">Select Deduction Type</option>
                                                            <option value="LWP">LWP</option>
                                                            <option value="Penalty">Penalty</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                        {{--<input type="text" name="deduction_type[]" id="deduction_type" value="" class="form-control requiredField" required />--}}

                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="margin-top: 15px;">


                                                        <br>
                                                        <label>Once ?
                                                        <input type="checkbox" name="once[]" id="once" value="1">
                                                        </label>



                                                        </div>


                                                </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="lblDeduct">Deduction Amount:</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="number" name="deduction_amount[]" id="deduction_amount" value="" class="form-control requiredField" required/>

                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 once_area"></div>
                                                    </div>
                                                </span>
                                            </span>
                                        <div class="employeeSection"></div>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                                <input type="button" class="btn btn-sm btn-primary addMoreDeductionSection" value="Add More Deduction Section" />
                            </div>
                        </div>
                        <?php echo Form::close();?>
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

