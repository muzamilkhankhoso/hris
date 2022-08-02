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
        <?php echo Form::open(array('url' => 'had/addDepartmentDetail?m='.$m, 'id'=>'departmentForm'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <input type="hidden" name="pageType" value="<?php echo $_GET['pageType'] ?? ""?>">
        <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode'] ?? ""?>">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="departmentSection[]" class="form-control" id="departmentSection" value="1" />
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">Create Department Form</h4>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Department Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="department_name_1" id="department_name_1" value="" class="form-control requiredField" />
                        </div>
                    </div>

                    <div class="departmentSection"></div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            <input type="button" class="btn btn-sm btn-primary addMoreDepartmentSection" value="Add More Department's Section" />
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

