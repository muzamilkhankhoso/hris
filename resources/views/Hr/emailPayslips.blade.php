<?php

$accType = Auth::user()->acc_type;
use App\Helpers\CommonHelper;
?>



@extends('layouts.default')
@section('content')
    <style>
        input[type="radio"], input[type="checkbox"]{ width:30px;
            height:20px;
        }
    </style>

    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        {{ Form::open(array('url' => 'had/addEmailPayslipDetail','id'=>'addEmailPayslipDetail')) }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Payslip Email</h4>

                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintregionWisePayrollReport','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('regionWisePayrollReport','','1')?>
                                @endif
                            </div>
                        </div>
                        <hr>


                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="m" value="{{ Input::get('m') }}">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Employee</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select style="width:100%;" class="form-control requiredField" name="emp_id" id="emp_id" required>
                                                    <option value="All">All</option>
                                                    @foreach($employee as $y)
                                                        <option value="{{ $y->emp_id }}">Emp Code: {{ $y->emp_id.' -- '.$y->emp_name.' '.$y->emp_father_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Month-Year</label><span class="rflabelsteric"><strong>*</strong></span>
                                                <input class="form-control requiredField" type="month" id="month_year" name="month_year">
                                            </div>

                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                <label class="sf-label"></label>
                                                <button class="btn btn-sm btn-primary" onclick="viewEmployeePayslips()" type="button">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>

                </div>
            </div>

        </div>

        <span id="employeeAttendenceReportSection" style="display: none;">


        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintregionWisePayrollReport">
                        <div class="row">
                            <div class="col-sm-12">
                                 <div id="emp_loader_1"></div>
                                <div class="employeeSection" id="PrintEmployeeAttendanceList"></div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
        </span>
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
        {{ Form::close() }}
    </div>



    <script>

        $(document).ready(function() {

            // Wait for the DOM to be ready
            $(".btn-primary").click(function(e){
                var employee = new Array();
                var val;
                $("input[name='employeeSection[]']").each(function(){
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val in employee) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }
                    else{
                        return false;
                    }
                }
            });
            $('#emp_id').select2();

        });

        function viewEmployeePayslips() {
            $("#employeeAttendenceReportSection").css({"display": "none"});
            var month_year = $('#month_year').val();
            var emp_id = $('#emp_id').val();

            jqueryValidationCustom();
            if (validate == 0) {
                $('#emp_loader_1').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                $.ajax({
                    url: '<?php echo url('/')?>/hdc/viewEmployeePayslips',
                    type: "GET",
                    data: {month_year: month_year, emp_id:emp_id, m: <?=Input::get('m')?>},
                    success: function (data) {
                        $("#employeeAttendenceReportSection").css({"display": "block"});
                        $('#emp_loader_1').html('');
                        $('.employeeSection').html('');
                        $('.employeeSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' + data + '</div>');
                    }
                });
            } else {
                return false;
            }
        }


    </script>

@endsection