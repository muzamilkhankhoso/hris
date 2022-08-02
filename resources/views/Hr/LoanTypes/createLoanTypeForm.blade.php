<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$currentDate = date('Y-m-d');
?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <?php echo Form::open(array('url' => 'had/addLoanTypeDetail?m='.$m.'&&d=','id'=>'loanTypeForm'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="loanTypeSection[]" class="form-control" id="loanTypeSection" value="1" />
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">Create Loan Type Form</h4>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Loan Type Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="loan_type_name_1" id="loan_type_name_1" value="" class="form-control requiredField" />
                        </div>
                    </div>
                    <div class="loanTypeSection"></div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            <input type="button" class="btn btn-sm btn-primary addMoreLoanTypeSection" value="Add More Loan Type Section" />
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

