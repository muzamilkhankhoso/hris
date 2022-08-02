<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//	$m = $_GET['m'];
//}else{
//	$m = Auth::user()->company_id;
//}
//$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName
$m = $_GET['m'];
$currentDate = date('Y-m-d');
?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <?php echo Form::open(array('url' => 'had/addLeaveTypeDetail?m='.$m.'','id'=>'leaveTypeForm'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="leaveTypeSection[]" class="form-control" id="leaveTypeSection" value="1" />
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">Create Leave Type Form</h4>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Leave Type Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="leave_type_name_1" id="leave_type_name_1" value="" class="form-control requiredField" />
                        </div>
                    </div>
                    <div class="leaveTypeSection"></div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            <input type="button" class="btn btn-sm btn-primary addMoreLeaveTypeSection" value="Add More Leave Type Section" />
                        </div>
                    </div>
                </div>

            </div>


        </div>

    </div>


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

