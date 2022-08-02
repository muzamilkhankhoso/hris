<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
$currentDate = date('Y-m-d');
?>

<div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0 table-bordered table-striped" id="AllowanceList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">EMP ID</th>
                                        <th class="text-center">Emp Name</th>
                                        <th class="text-center">Department</th>
                                        <th class="text-center">Sub Department</th>
                                        <th class="text-center">Allowance Type</th>
                                        <th class="text-center">Month-Year</th>
                                        <th class="text-center">Amount</th>

                                        <th class="text-center">Status</th>

                                        <th class="text-center hidden-print">Actions</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 1;?>
                                        @foreach($allowance as $key => $value)
                                            <?php
                                            CommonHelper::companyDatabaseConnection(Input::get('m'));
                                            $sub_department = Employee::select('emp_sub_department_id','emp_department_id')->where([['emp_id', '=', $value->emp_id]])->first();
                                            CommonHelper::reconnectMasterDatabase();
                                            $monthName="";
                                            if($value->month > 0){
                                                $monthName = date('M', mktime(0, 0, 0, $value->month=(int)$value->month, 10));
                                            }
                                            ?>

                                            <tr>
                                                <td class="text-center">{{ $counter++ }}</td>
                                                <td class="text-center">{{ $value->emp_id }}</td>
                                                <td>{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$value->emp_id,'emp_id') }}</td>
                                                <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'department','department_name', $sub_department->emp_department_id) }}</td>
                                                <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name', $sub_department->emp_sub_department_id) }}</td>
                                                <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'allowance_types','allowance_type', $value->allowance_type_id) }}</td>
                                                <td class="text-right">{{ $monthName."-".$value->year }}</td>
                                                <td class="text-right">
                                                    @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                                        {{ number_format($value->allowance_amount,0) }}
                                                    @else
                                                        ******
                                                    @endif

                                                </td>

                                                <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                                <td class="text-center hidden-print">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle  btn-rounded btn-sm" type="button" id="menu1" data-toggle="dropdown"><i class="fa fa-chevron-down" ></i></button>
                                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                            @if(in_array('view', $operation_rights))
                                                                <li role="presentation" class="actionsLink">
                                                                    <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hdc/viewAllowanceDetail','<?php echo $value->id;?>','View Allowance Detail','<?php echo $m; ?>')">
                                                                        View
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if(in_array('edit', $operation_rights))
                                                                <li role="presentation" class="actionsLink">
                                                                    <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/editAllowanceDetailForm','<?php echo $value->id;?>','Edit Allowance Detail','<?php echo $m; ?>')">
                                                                        Edit
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if(in_array('delete', $operation_rights))
                                                                @if($value->status == 1)
                                                                    <li role="presentation" class="actionsLink">
                                                                        <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $value->id ?>','allowance')">
                                                                            Delete
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endif
                                                            @if(in_array('repost', $operation_rights))
                                                                @if($value->status == 2)
                                                                    <li role="presentation" class="actionsLink">
                                                                        <a class="delete-modal btn" onclick="repostOneTableRecords('<?php echo $m ?>','<?php echo $value->id ?>','allowance')">
                                                                            Repost
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


<script>

$(document).ready(function(){
    var table = $('#AllowanceList').DataTable({
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
})

</script>    