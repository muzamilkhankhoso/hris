<?php
    use App\Helpers\HrHelper;
    use App\Helpers\CommonHelper;
    use App\Models\Employee;
    use Illuminate\Support\Facades\Route;
    
    $accType = Auth::user()->acc_type;
    //if($accType == 'client'){
    //    $m = $_GET['m'];
    //}else{
    //    $m = Auth::user()->company_id;
    //}
    $m = Input::get('m');
    
    
    
?>

 <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                            <div class="table-responsive">
                                                    <table class="table table-sm mb-0 table-bordered table-striped dataTable no-footer" id="DeductionList">
                                                    <thead>
                                                    <th class="text-center">S.No</th>
                                                    <th class="text-center">EMP ID</th>
                                                    <th class="text-center">Employee Name</th>
                                                    <th class="text-center">Department</th>
													<th class="text-center">Sub Department</th>
                                                    <th class="text-center">Deduction Type</th>
                                                    <th class="text-center">Deduction</th>
                                                    <th class="text-center">Month-Year</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center hidden-print">Action</th>
                                                    </thead>
                                                    <tbody id="deduction_list">
                                                    <?php $counter = 1;?>
                                                    @foreach($deduction as $key => $value)
                                                        <?php
                                                        CommonHelper::companyDatabaseConnection(Input::get('m'));
                                                        $emp_detail = Employee::select('emp_sub_department_id','emp_department_id')->where([['emp_id', '=', $value->emp_id]])->first();
                                                        CommonHelper::reconnectMasterDatabase();
                                                        $monthName="";
                                                        if($value->month > 0){
                                                            $monthName = date('M', mktime(0, 0, 0, $value->month=(int)$value->month, 10));
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td class="text-center">{{ $counter++ }}</td>
                                                            <td class="text-center">{{ $value->emp_id }}</td>
                                                            <td>{{$value->emp_name }}</td>
													    	<td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'department','department_name', $emp_detail->emp_department_id) }}</td>
                                                            <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name', $emp_detail->emp_sub_department_id) }}</td>
                                                           
                                                            <td>{{ $value->deduction_type }}</td>
                                                            <td class="text-right">
                                                                @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                                                    {{ $value->deduction_type!="LWP"?number_format( $value->deduction_amount,0):$value->deduction_amount." Day" }}
                                                                @else
                                                                    ******
                                                                @endif
                                                            </td>
                                                            <td class="text-right">{{  $monthName."-".$value->year }}</td>
                                                            <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                                            <td class="text-center hidden-print">
                                                                <div class="dropdown">
                                                                    <button class="btn btn-rounded btn-primary dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown"><i class="fa fa-chevron-down"></i></button>
                                                                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                        @if(in_array('view', $operation_rights))
                                                                            <li role="presentation" class="actionsLink">
                                                                                <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hdc/viewDeductionDetail','<?php echo $value->id;?>','Edit Deduction Detail Form','<?php echo $m; ?>')">
                                                                                    View
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                        @if(in_array('edit', $operation_rights))
                                                                            <li role="presentation" class="actionsLink">
                                                                                <a class="edit-modal btn" onclick="showDetailModelFourParamerter('hr/editDeductionDetailForm','<?php echo $value->id;?>','Edit Deduction Detail Form','<?php echo $m; ?>')">
                                                                                    Edit
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                        @if(in_array('delete', $operation_rights))
                                                                            @if($value->status == 1)
                                                                                <li role="presentation" class="actionsLink">
                                                                                    <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $value->id ?>','deduction')">
                                                                                        Delete
                                                                                    </a>
                                                                                </li>
                                                                            @endif
                                                                        @endif
                                                                        @if(in_array('repost', $operation_rights))
                                                                            @if($value->status == 2)
                                                                                <li role="presentation" class="actionsLink">
                                                                                    <a class="delete-modal btn" onclick="repostOneTableRecords('<?php echo $m ?>','<?php echo $value->id ?>','deduction')">
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
    var table = $('#DeductionList').DataTable({
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