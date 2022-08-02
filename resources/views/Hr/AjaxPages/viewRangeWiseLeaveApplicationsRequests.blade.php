<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];
$m = Input::get('m');
?>


<div class="row">

            <div class="col-12">
     
                    <div class="row">
                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-6"></div>

                            <div class="col-sm-4 text-right">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="emp_id_search" name="emp_id1" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="<?= $m ?>">
                                </div>
                            </div>
                        </div> 


                        
                        <div class="row" id="PrintLeaveApplicationRequestList">
                        
                            <div class="col-sm-12">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                <div class="table-responsive LeavesData">
                                <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="EmployeeList">
    <thead>
    <th class="text-center">S No.</th>
    <th class="text-center">Emp Name</th>
    <th class="text-center">Emp ID</th>
    <th class="text-center">Leave Type</th>
    <th class="text-center">Day Type</th>
    <th class="text-center">From</th>
    <th class="text-center">To</th>
    <th class="text-center">No of days</th>
    <th class="text-center">Approval Status(HR)</th>
    <th class="text-center">Approval Status(LM)</th>
    <th class="text-center">Status</th>
    <th class="text-center hidden-print">Action</th>

    </thead>
    <tbody>
    <?php $counter = 1;
   
    if(count($leave_application_request_list) != '0'){
        $array=[$approved,$pending,$rejected];
    ?>
    
    @foreach($leave_application_request_list as $value)
        <?php
        
        CommonHelper::companyDatabaseConnection($m);
        $emp_name =  Employee::where([['emp_id','=',$value->emp_id]]);
        CommonHelper::reconnectMasterDatabase();
        
        if($emp_name->first() != ''){
        $startDate = new DateTime($value->from_date);
        $endDate = new DateTime($value->to_date);
        $value->to_date;
        $difference = $endDate->diff($startDate);
        if($value->leave_day_type=='1'){
            $diff=$difference->format("%a")+1;
        }else{
            $diff=0.5;
        }
        
        ?>
        
        @if(in_array($value->approval_status_lm,$array) || in_array($value->approval_status,$array))
        <tr>
            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
            <td class="text-center">{{$emp_name->value('emp_name')}}</td>
            <td class="text-center">{{ $emp_name->value('emp_id') }}</td>
            <td class="text-center" style="color:green">
                {{ $leave_type_name = HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$value->leave_type)}}</td>
            <td class="text-center" style="color:green">{{ $leave_day_type[$value->leave_day_type] }}</td>
            <td class="text-center">{{ $value->leave_day_type=='1'? HrHelper::date_format($value->from_date) : HrHelper::date_format($value->first_second_half_date)  }}</td>
            <td class="text-center">{{ $value->leave_day_type=='1'? HrHelper::date_format($value->to_date)  : HrHelper::date_format($value->first_second_half_date)  }}</td>
            <td class="text-center">{{ $value->no_of_days }}</td>
            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status) }}</td>
            <td class="text-center">{{ HrHelper::getApprovalStatusLabel($value->approval_status_lm) }}</td>
            <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
            <td class="text-center hidden-print">
                <div class="dropdown">
                    <button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
                        <i data-feather="chevron-down"
                           class="fa fa-chevron-down"></i></button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        <li role="presentation" class="actionsLink">
                            <a  class="delete-modal btn" onclick="LeaveApplicationRequestDetail('<?=$value->id?>','<?=$value->leave_day_type?>','<?=$leave_type_name?>','<?=$value->emp_id?>','<?=$m?>')">
                                View
                            </a>
                        </li>
                        <li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hr/editLeaveApplicationDetailForm','<?php echo $value->id."|".$value->emp_id;?>','Edit Leave Application Detail','<?=$m?>')">
                            <a class="delete-modal btn">
                                Edit
                            </a>
                        </li>
                        @if ($value->status == 2)
                            <li role="presentation" class="actionsLink" onclick="RepostLeaveApplicationData('<?= $m ?>','<?=$value->id?>')">
                                <a class="delete-modal btn">
                                    Repost
                                </a>
                            </li>
                        @else
                            <li role="presentation" class="actionsLink" onclick="deleteLeaveApplicationData('<?= $m ?>','<?=$value->id?>')">
                                <a class="delete-modal btn">
                                    Delete
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </td>
        </tr>
          @endif
            <!-- <tr>

            <td colspan="12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="collapseExample<?=$value->id?>">
                    <div class="card card-body" id="leave_area<?=$value->id?>"></div>
                </div>
            </td>
            </tr> -->
      
        <?php } ?>
    @endforeach
    <?php } else { ?>
    <tr>
        <td colspan="12" class="text-danger text-center">No Record Found</td>
    </tr>
    <?php } ?>
    </tbody>
</table>
                                  
                                </div>


                            </div>
                        </div>

                    
            </div>

        </div>

        <script>
        $(document).ready(function() {
    
            var table = $('#EmployeeList').DataTable({
                "dom": "t",
                "bPaginate" : false,
                "bLengthChange" : true,
                "bSort" : false,
                "bInfo" : false,
                "bAutoWidth" : false

            });

        $('#emp_id_search').keyup( function() {
            table.search(this.value).draw();
        });



});

</script>