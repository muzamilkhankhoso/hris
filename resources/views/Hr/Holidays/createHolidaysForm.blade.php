<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}

$m = $_GET['m'];
use App\Helpers\HrHelper;
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
                                <h4 class="card-title">Create Holidays Form</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo Form::open(array('url' => 'had/addHolidaysDetail','id'=>'Holidays'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
                            <input type="hidden" name="holidaySection[]">

                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label>Holiday Name:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" name="holiday_name[]" id="holiday_name" value="" class="form-control requiredField" required />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label>Holiday Date:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" name="holiday_date[]" id="holiday_date" value="" class="form-control requiredField" required />
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                                <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <?php echo Form::close();?>
                            </div>
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

