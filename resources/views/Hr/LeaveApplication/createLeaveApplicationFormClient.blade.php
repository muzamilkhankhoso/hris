<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
//$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName
?>



<?php
$currentDate = date('Y-m-d');


?>
@extends('layouts.default')
@section('content')
<div class="page-wrapper">


    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <?php echo Form::open(array('url' => 'had/createPayslipForm'));?>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="m" id="m" value="<?= Input::get('m') ?>">
    <input type="hidden" name="_token" value="{{ csrf_token() }}"><div class="row">
        <input type="hidden" name="m" value="<?= Input::get('m') ?>">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">Create Leave Application</h4>

                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label pointer">Department</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select class="form-control requiredField" name="department_id" id="department_id" onchange="getEmployee()">
                                <option value="0">Select Department</option>
                                <?php foreach($Department as $d){?>
                                <option value="<?php echo $d->id ?>">
                                    <?php echo $d->department_name ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label pointer">Sub Department</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
                                <option value="0">Select Department</option>
                                <?php foreach($SubDepartment as $sd) {?>
                                <option value="<?php echo $sd->id ?>">
                                   <?php echo $sd->sub_department_name ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Employee:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select class="form-control requiredField" name="emp_id" id="emp_id" >
                                <option value="0">-</option>
                            </select>
                            <div id="emp_loader_1"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <button type="button" class="btn btn-sm btn-success" onclick="manageEmployeeApplication()" style="margin-top: 27px;">Create Application Forms</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <span id="employeePayslipSection" style="display:none;">


        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="run_loader"></div>
                                <div class="employeePayslipSection"></div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
        </span>

    <?php echo Form::close();?>
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

