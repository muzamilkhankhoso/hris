<?php
$accType = Auth::user()->acc_type;
//	if($accType == 'client'){
//		$m = $_GET['m'];
//	}else{
//		$m = Auth::user()->company_id;
//	}
$m = Input::get('m')
?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <?php echo Form::open(array('url' => 'had/addEmployeeGradesDetail?m='.$m.'','id'=>'Grade'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="companyId" value="{{ $m }}">
        <input type="hidden" name="EmployeeGrageSection[]" value="1">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="EOBISection[]" class="form-control" id="sectionEOBI" value="1" />
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">Create Employee Grade Form</h4>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Category:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select class="form-control requiredField" name="category[]" id="category">
                                <option value="">Select Category</option>
                                <option value="Executives">Executives</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Clearing">Clearing</option>
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Grade Type:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="employee_grade_type[]" id="employee_grade_type" value="" class="form-control requiredField" required/>
                        </div>
                    </div>
                    <div class="EmployeeGradeSection"></div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            <input type="button" class="btn btn-sm btn-primary addMoreEmployeeGradesSection" value="Add More Grade Section" />
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

