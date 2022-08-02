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
        <?php echo Form::open(array('url' => 'had/addSubDepartmentDetail?m='.$m,'id'=>'subDepartmentForm'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="subDepartmentSection[]" class="form-control" id="subDepartmentSection" value="1" />
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">Create Sub Department Form</h4>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Select Department:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select class="form-control requiredField" name="department_id_1" id="department_id_1">
                                <option value="">Select Department</option>
                                @foreach($departments as $key => $y)
                                    <option value="{{ $y->id}}">{{ $y->department_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Sub Department Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="sub_department_name_1" id="sub_department_name_1" value="" class="form-control requiredField" />
                        </div>
                    </div>

                    <div class="subDepartmentSection"></div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            <input type="button" class="btn btn-sm btn-primary addMoreSubDepartmentSection" value="Add More Sub Department's Section" />
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

