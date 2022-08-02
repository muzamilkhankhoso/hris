<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
$currentDate = date('Y-m-d');
//$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName
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
                                <h4 class="card-title">Create Loan Request Form</h4>
                            </div>


                        </div>
                        <hr>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'had/addLoanRequestDetail'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?= Input::get('m') ?>">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                                            </div>
                                        </div>

                                        <div class="get_clone">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label pointer">Department</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control" name="department_id" id="department_id" onchange="getEmployee()">
                                                        <option value="0">Select Department</option>
                                                        @foreach($Department  as $key => $y)
                                                            <option value="<?php echo $y->id ?>">
                                                                {{ $y->department_name}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label pointer">Sub Department</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
                                                        <option value="0">Select Department</option>
                                                        @foreach($SubDepartment  as $key => $y)
                                                            <option value="<?php echo $y->id ?>">
                                                                {{ $y->sub_department_name}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <label class="sf-label">Employee:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control" name="emp_id" id="emp_id" >
                                                        <option value="0">-</option>
                                                    </select>
                                                    <div id="emp_loader_1"></div>
                                                </div>
                                            </div>
                                            <div class="row">&nbsp;</div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Needed on Month & Year:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="month" name="needed_on_date" id="needed_on_date" value="" class="form-control requiredField count_rows" required />
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Loan Type</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>

                                                    <select name="loan_type_id" class="form-control requiredField" id="loan_type_id">
                                                        <option value="">Select</option>
                                                        @foreach($loanTypes as $laonTypeValue)
                                                            <option value="{{ $laonTypeValue->id}}">{{ $laonTypeValue->loan_type_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Loan Amount</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="number" name="loan_amount" id="loan_amount" value="" class="form-control requiredField count_rows" required />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="sf-label">Per Month Deduction</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="number" name="per_month_deduction" id="per_month_deduction" value="" class="form-control requiredField count_rows" required />
                                                </div>
                                            </div>
                                            <div class="row">&nbsp;</div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label class="sf-label">Loan Description</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <textarea required name="loan_description" class="form-control" id="contents"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="insert_clone"></div>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                            </div>
                            <br>
                             <div class="row">
                                 <div class="employeeSection"></div>
                             </div>

                                <div class="row">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                    <input type="button" class="btn btn-sm btn-primary addMoreLoanRequestSection" value="Add More Loan Request Section" />
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

