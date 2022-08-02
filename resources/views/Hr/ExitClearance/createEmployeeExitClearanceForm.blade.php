<?php

$accType = Auth::user()->acc_type;
//if ($accType == 'client') {
//    $m = $_GET['m'];
//} else {
//    $m = Auth::user()->company_id;
//}

$m = $_GET['m'];

use App\Helpers\CommonHelper;
$currentDate = date('Y-m-d');
?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">
        <div class="row" style="margin-bottom: 10px;">

        </div>

        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <form method="post" action="{{url('had/addEmployeeExitClearanceDetail')}}">
            <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
            <input type="hidden" name="company_id" id="company_id" value="12">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Employee Exit Clearance Form</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeExitClearanceForm','','1');?>
                                <?php echo CommonHelper::displayExportButton('EmployeeExitClearanceForm','','1')?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                            </div>
                        </div>
                        <form method="post" action="{{url('had/addEmployeeExitClearanceDetail')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                            <input type="hidden" name="company_id" id="company_id" value="<?php echo $m ?>">
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
                                        @foreach($sub_department  as $key => $y)
                                            <option value="<?php echo $y->id ?>">
                                                {{ $y->sub_department_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label class="sf-label">Employee:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select class="form-control requiredField" name="emp_id" id="emp_id" required>
                                        <option value="">-</option>
                                    </select>
                                    <div id="emp_loader_1"></div>
                                </div>


                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right" style="margin-top: 31px">
                                    <a class=" btn btn-sm btn-primary btn_search" onclick="viewEmployeeExitClearance()" style="cursor: pointer;color: white;"><i id="load" class="fas fa-search fa"> Search</i></a>
                                </div>
                            </div>

                            <div>&nbsp;</div>




                        
                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>
            <span id="exitSectiondiv" style="display: none;">

                <div class="col-12">
                    <div class="card">
                        <div class="card-body" id="PrintEmployeeExitClearanceForm">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="exitSection"></div>
                                </div>
                            </div>
                        </div>
`                   </div>
                </div>

        </span>

        </form>
    </div>



    <script>
        $(document).ready(function () {

            $(document).bind('ajaxStart', function () {

            }).bind('ajaxStop', function () {

                $("select[name='emp_id'] option[value='all']").remove();
            });
        });

    </script>



@endsection

