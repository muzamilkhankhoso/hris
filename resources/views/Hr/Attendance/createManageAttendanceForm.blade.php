<?php
$currentDate = date('Y-m-d');
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');

?>



@extends('layouts.default')
@section('content')

<div class="page-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="well">
                                        <div class="row">
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                <span class="subHeadingLabelClass">Manage Employees Attendence</span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">
                                                <button class="btn btn-sm btn-primary" id="viewUploadAttendanceFileForm" >Upload Attendance File</button>
                                                &nbsp;&nbsp;
                                                <button class="btn btn-sm btn-primary" id="viewManualAttendanceForm" >Add Manual Attendance</button>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    <span class="attendance-area" style="display: none;">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="m" value="{{ Input::get('m') }}">
                        <input type="hidden" name="employeeSection[]" value="">
                        <br>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div id="attendance-area"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</span>





</div>


@endsection