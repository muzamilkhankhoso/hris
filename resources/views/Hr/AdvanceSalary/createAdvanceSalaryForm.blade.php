<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
$currentDate = date('Y-m-d');
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\SubDepartment;

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
                                <h4 class="card-title">Create Advance Salary Form</h4>
                            </div>


                        </div>
                        <hr>

                            <?php echo Form::open(array('url' => 'had/addAdvanceSalaryDetail','id'=>'employeeForm'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?=$m?>">

                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label pointer">Department</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width: 100%;" class="form-control" name="department_id" id="department_id" onchange="getEmployee()">
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
                                                <select style="width: 100%;" class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
                                                    <option value="0">Select Department</option>
                                                    @foreach($SubDepartment  as $key => $y)
                                                        <option value="<?php echo $y->id ?>">
                                                            {{ $y->sub_department_name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Employee:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width: 100%;" class="form-control" name="emp_id" id="emp_id" >
                                                    <option value="0">-</option>
                                                </select>
                                                <div id="emp_loader_1"></div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Amount:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="number" name="advance_salary_amount" id="advance_salary_amount" value="" class="form-control requiredField" required />
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Advance Salary to be Needed On</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" class="form-control requiredField" name="salary_needed_on" id="salary_needed_on" value="" />
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Deduction Month & Year</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="month" class="form-control requiredField" name="deduction_month_year" id="deduction_month_year" value="" />
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Reason (Detail)</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <textarea name="advance_salary_detail" class="form-control requiredField"></textarea>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>

                            <div class="employeeSection"></div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                    <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
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

