<?php

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
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
                                <h4 class="card-title">Create Working Hours Policy Form</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'had/addWorkingHoursPolicyDetail','id'=>'EOBIform'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="m" value="{{$m}}">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="workingHoursSection[]" class="form-control" id="workingHoursSection" value="1">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label class="sf-label">Working Hours Policy Name:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" name="working_hours_policy" class="form-control requiredField" id="working_hours_policy" required="">

                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Start Working Hours Time (Hour) <span class="rflabelsteric"><strong>*</strong></span></label>

                                                <input type="time" name="start_working_hours_time" class="form-control requiredField" id="start_working_hours_time" required="">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">End Working Hours Time (Hour) <span class="rflabelsteric"><strong>*</strong></span></label>

                                                <input type="time" name="end_working_hours_time" class="form-control requiredField" id="end_working_hours_time" required="">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Half Day Time <br> (Hour) <span class="rflabelsteric"><strong>*</strong></span></label>

                                                <input type="number" name="half_day_time" class="form-control requiredField" id="half_day_time" required="">

                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Working Hours Grace Time (Minutes) <span class="rflabelsteric"><strong>*</strong></span></label>

                                                <input type="number" name="working_hours_grace_time" class="form-control requiredField" id="working_hours_grace_time" required="" min="0">

                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                <input class="btn btn-sm btn-success" type="submit" value="Submit">
                                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                                            </div>
                                        </div>
                                    </div>
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

