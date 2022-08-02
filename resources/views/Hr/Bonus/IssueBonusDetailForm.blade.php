<?php

use App\Helpers\CommonHelper;
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
        <?php echo Form::open(array('url' => 'had/addEmployeeBonusDetail'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="m" value="<?= Input::get('m') ?>">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"><div class="row">
            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Issue Employees Bonus</h4>

                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintIssueBonusDetailForm','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('IssueBonusDetailForm','','1')?>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label pointer">Department</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control" name="department_id" id="department_id" onchange="getEmployee()">
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
                                <select class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
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
                                <select class="form-control" name="emp_id" id="emp_id" >
                                    <option value="0">-</option>
                                </select>
                                <div id="emp_loader_1"></div>
                            </div>



                        </div>
                        <br>
                        <div class="row">

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Bonus List:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="bonus_id" id="bonus_id" required>
                                    <option value="">Select</option>
                                    @foreach($bonus_list as $value)
                                        <option value="{{ $value->id }}">{{ $value->bonus_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Bonus Month:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" name="bonus_month_year" id="bonus_month_year" value="" class="form-control requiredField" required />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="margin-top:50px;">
                                <button type="button" class="btn btn-sm btn-primary btn_search" onclick="viewEmployeesBonus()" style="margin-right: 75px;margin-top: -20px;;" ><i id="load" class="fas fa-search fa"> </i> Search</button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>

        <span id="employeePayslipSection" style="display: none;">


        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintregionWisePayrollReport">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="run_loader"></div>

                                <div id="PrintIssueBonusDetailForm">
                                    <div class="employeePayslipSection" id="IssueBonusDetailForm"></div>
                                </div>


                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
        </span>
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

