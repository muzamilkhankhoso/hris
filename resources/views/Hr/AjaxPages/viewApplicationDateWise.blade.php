<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;

?>

<style>
    hr{border-top: 1px solid cadetblue}

    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>

<?php $leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];?>

<div class="well">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="text-center"><h3>Absent Dates</h3></div>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                                    <thead>
                                    <th class="text-center">S No.</th>
                                    <th class="text-center">Emp ID</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Check In</th>
                                    <th class="text-center">Check Out</th>
                                    <th class="text-center">Late</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                   

                                    if(!empty($absent_dates)):
                                    $counter2 = 1;?>
                                    @foreach($absent_dates as $value1)
                                        
                                        <tr>
                                            <td class="text-center">{{ $counter2++ }}</td>
                                            <td class="text-center">{{ $value1['emp_id'] }}</td>
                                            <td class="text-center">{{ HrHelper::date_format($value1['attendance_date']) }}</td>
                                            <td class="text-center" style="background-color: #FFC0CB">{{ $value1['clock_in'] }}</td>
                                            <td class="text-center" style="background-color: #FFC0CB">{{ $value1['clock_out'] }}</td>
                                            <td class="text-center" style="background-color: #FFC0CB">--</td>
                                        </tr>
                                    @endforeach
                                    <?php else: ?>

                                    <tr><td colspan="6" style="color:red" class="text-center">Record Not Found !</td></tr>

                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="text-center"><h3>Leave Application Absent Dates </h3></div>
                            <div class="table-responsive">

                                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LeaveApplicationRequestList">
                                    <thead>
                                    <th class="text-center">S No.</th>
                                    <th class="text-center">Emp Name</th>
                                    <th class="text-center">Leave Type</th>
                                    <th class="text-center">Day Type</th>
                                    <th class="text-center">Application Date</th>
                                    <th class="text-center">Approval Status</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center hidden-print">Action</th>

                                    </thead>
                                    <tbody>
                                    <?php $counter = 1;
									
                                    //print_r($leave_application_request_list);
                                    if(!empty($leave_application_request_list)):?>
							
                                    @foreach($leave_application_request_list as $value) 
										<?php
										CommonHelper::companyDatabaseConnection($m);
											$emp_name = Employee::where('emp_id',$value->emp_id)->value('emp_name');
										CommonHelper::reconnectMasterDatabase();
										?>
                                        <tr>
                                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                                            <td class="text-center">{{ $emp_name }}</td>
                                            <td class="text-center" style="color:green">{{ $leave_type_name = HrHelper::getMasterTableValueById('1','leave_type','leave_type_name',$value->leave_type)}}</td>
                                            <td class="text-center" style="color:green">{{ $leave_day_type[$value->leave_day_type] }}</td>
                                            <td class="text-center" style="color:green"> 
                                                <?php
                                                if($value->leave_day_type == 1)
                                                { echo HrHelper::date_format($value->from_date); }
                                                else{ echo HrHelper::date_format($value->first_second_half_date);}
                                                ?>
                                            </td>
                                            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
                                            <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                            <td class="text-center hidden-print">
                                                <button onclick="LeaveApplicationRequestDetail('<?=$value->leave_application_id?>','<?=$value->leave_day_type?>','<?=$leave_type_name?>','<?=$value->emp_id?>')" class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample<?=$value->leave_application_id?>" aria-expanded="false" aria-controls="collapseExample">
                                                    <span class="fas fa-eye"></span>
                                                </button>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="collapseExample<?=$value->leave_application_id?>">
                                                    <div class="card card-body" id="leave_area<?=$value->leave_application_id?>"></div>
                                                </div>
                                            </td>
                                        </tr>

                                    @endforeach

                                    <?php else: ?>

                                    <tr><td colspan="9" style="color:red" class="text-center">Record Not Found !</td></tr>

                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>



    $('.btn-danger').click(function () {

    });
    function LeaveApplicationRequestDetail(id,leave_day_type,leave_type_name,user_id)
    {

        //alert(user_id);
        $('#leave_area'+id).append('<div class="row">&nbsp;</div><div class="loader"></div>');
        var m = '<?= Input::get('m'); ?>';
        var url= '<?php echo url('/')?>/hdc/viewLeaveApplicationRequestDetail';

        $.ajax({
            url: url,
            type: "GET",
            data: {m:m,id:id,leave_day_type:leave_day_type,leave_type_name:leave_type_name,user_id:user_id},
            success:function(data) {

                $('#leave_area'+id).html('<hr>' +
                        '<div class="row text-center" style="background-color: gainsboro">' +
                        '<h4><b>Leave Application Details</b></h4>' +
                        '</div>' +
                        '<div class="row">&nbsp;</div>'+data);
                $('.ClsHide').css('display','none');


            }
        });
    }



    function approveAndRejectLeaveApplication(recordId,approval_status)
    {

        var check = (approval_status == 2) ? "Approve":"Reject";
        var url= '<?php echo url('/')?>/cdOne/approveAndRejectLeaveApplication';
        var companyId = '<?= Input::get('m'); ?>';

        if(confirm('Do you want to '+check+' Leave Applicaiton ?'))
        {

            $.ajax({
                url: url,
                type: "GET",
                data: {companyId:companyId,recordId:recordId,approval_status:approval_status},
                success:function(data) {
                    location.reload();
                }
            });
        }
    }


    function RepostLeaveApplicationData(companyId,recordId)
    {
        if(confirm('Do you want to Repost Leave Applicaiton ?'))
        {
            repostOneTableRecords(companyId,recordId,'leave_application','status');

        }

    }

</script>

<script type="text/javascript" src="{{ URL::asset('assets/custom/js/customHrFunction.js') }}"></script>
