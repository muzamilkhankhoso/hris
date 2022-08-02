<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}


use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-12 text-right">

            </div>
        </div>

        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body" >
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">View Working Hours Policy List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintWorkingHoursPolicList','','1');?>
                                <?php echo CommonHelper::displayExportButton('workingHoursPolicList','','1')?>

                            </div>

                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-8"></div>
                            <div class="col-sm-4 text-right">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="emp_id_search" name="emp_id1" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="<?= $m ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <span id="PrintWorkingHoursPolicList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="workingHoursPolicList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <!--<th class="text-center">Emp. No.</th>-->
                                        <th class="text-center">Policy Name</th>
                                        <th class="text-center">Start Time</th>
                                        <th class="text-center">End Time</th>
                                        <th class="text-center">Grace Time</th>
                                        <th class="text-center">Half Day Time</th>
                                        <th class="text-center">created By</th>
                                        <th class="text-center  hidden-print">Status</th>
                                        <th class="text-center hidden-print">Action</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 0; ?>
                                        @foreach($workingHoursPolicyList as $value)
                                            <?php $counter++; ?>
                                            <tr>
                                                <td class="text-center">{{$counter}}</td>
                                                <td class="text-center">{{$value->working_hours_policy}}</td>
                                                <td class="text-center">{{$value->start_working_hours_time}}</td>
                                                <td class="text-center">{{$value->end_working_hours_time}}</td>
                                                <td class="text-center">{{$value->working_hours_grace_time}}</td>
                                                <td class="text-center">{{$value->half_day_time}}</td>
                                                <td class="text-center">{{$value->username}}</td>
                                                <td class="text-center">{{HrHelper::getStatusLabel($value->status)}}</td>
                                                <td class="text-center hidden-print">

                                                        <div class="dropdown">
                                                            <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                                                                <i data-feather="chevron-down"
                                                                   class="svg-icon"></i></button>
                                                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                @if($value->status == 1)
                                                                    <li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hr/editWorkingOurPolicyList','{{$value->id}}','Edit working Policy Detail','{{$value->company_id}}')">
                                                                        <a class="delete-modal btn">
                                                                            Edit
                                                                        </a>
                                                                    </li>
                                                                    <li role="presentation" class="actionsLink" onclick="change_status('{{$value->company_id}}','{{$value->id}}','2','working_hours_policy')" >
                                                                        <a class="delete-modal btn">
                                                                            Remove
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                    @if($value->status == 2)
                                                                        <li role="presentation" class="actionsLink" onclick="change_status('{{$value->company_id}}','{{$value->id}}','1','working_hours_policy')">
                                                                            <a class="delete-modal btn">
                                                                                Refresh
                                                                            </a>
                                                                        </li>

                                                                    @endif

                                                            </ul>
                                                        </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                    </span>
                            </div>
                        </div>
                        <div class="text-center ajax-loader"></div>
                    </div>

                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv" style="display:none;">
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Employee List'))!!} ">
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

