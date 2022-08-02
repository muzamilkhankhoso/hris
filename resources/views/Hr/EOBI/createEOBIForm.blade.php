<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <?php echo Form::open(array('url' => 'had/addEOBIDetail','id'=>'EOBIform'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="EOBISection[]" class="form-control" id="sectionEOBI" value="1" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">

                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">Create EOBI Form</h4>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>EOBI Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input required type="text" name="EOBI_name[]" id="EOBI_name" value="" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>EOBI Amount:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input required  type="text" name="EOBI_amount[]" id="EOBI_amount" value="" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Month & Year:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input required type="month" name="month_year[]" id="month_year" value="" class="form-control requiredField" />
                        </div>
                    </div>
                    <div class="EOBISection"></div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            {{--<input type="button" class="btn btn-sm btn-primary addMoreEOBISection" value="Add More EOBI Section" />--}}
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

