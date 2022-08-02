<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}


use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

?>

@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Leaves Balances Report</h4>

                            </div>
                            <div class="col-sm-4 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeAttendanceList','','1');?>
                                <?php echo CommonHelper::displayExportButton('LeavesPolicyList','','1')?>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label">Companies:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" name="company_id" id="company_id">
                                    <option value="All">All Companies</option>
                                    @foreach($companies as $companyData)
                                        <option value="{{ $companyData->id}}">{{ $companyData->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <button type="button" class="btn btn-sm btn-primary btn_search" id="showAttendenceReport" onclick="viewLeavesBalances()" style="margin-top: 38px;" ><i id="load" class="fas fa-search fa"> </i> Search</button>
                            </div>

                        </div>


                    </div>

                </div>
            </div>


        <br>
        <span id="employeeAttendenceReportSection" style="display: none;">



                <div class="card">
                    <div class="card-body" id="PrintregionWisePayrollReport">

                                <div id="loader"></div>

                                <div class="employeeAttendenceReportSection" id="PrintEmployeeAttendanceList"></div>




                    </div>

                </div>

        </span>

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



<script>
    
        function viewLeavesBalances(){
            $("#employeeAttendenceReportSection").css({"display": "none"});
            var company_id = $('#company_id').val();
            var leaves_policy_id = $("#leaves_policy_id").val();
            jqueryValidationCustom();
            if(validate == 0){
                $("#employeeAttendenceReportSection").css({"display": "block"});
                $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: baseUrl+'/hdc/viewLeavesBalances',
                    type: "GET",
                    data: {company_id:company_id,leaves_policy_id:leaves_policy_id},
                    success:function(data) {
                        $('#loader').html('');
                        $('.employeeAttendenceReportSection').empty();
                        $('.employeeAttendenceReportSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+data+'</div>');
                    }
                });
            }else{
                return false;
            }
        }

</script>
@endsection

