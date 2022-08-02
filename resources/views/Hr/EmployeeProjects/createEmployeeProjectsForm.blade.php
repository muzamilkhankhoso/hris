<?php
$accType = Auth::user()->acc_type;
//	if($accType == 'client'){
//		$m = $_GET['m'];
//	}else{
//		$m = Auth::user()->company_id;
//	}
$m = $_GET['m'];

?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <?php echo Form::open(array('url' => 'had/addEmployeeProjectsDetail?m='.$m.'','id'=>'EmployeeProject'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="companyId" value="<?php echo $m ?>">
        <input type="hidden" name="EmployeeProjectSection[]" value="1">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="departmentSection[]" class="form-control" id="departmentSection" value="1" />
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">Create Employee Projects Form</h4>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Employee Project:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="project_name[]" id="project_name" value="" class="form-control requiredField" required/>
                        </div>
                    </div>
                    <div class="EmployeeProjectSection"></div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            <input type="button" class="btn btn-sm btn-primary addMoreEmployeeProjectSection" value="Add More Employee Project Section" />
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

