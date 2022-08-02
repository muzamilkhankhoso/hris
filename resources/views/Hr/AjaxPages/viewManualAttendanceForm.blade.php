<?php
$currentDate = date('Y-m-d');
$accType = Auth::user()->acc_type;
use App\Helpers\CommonHelper;
$m = Input::get('m');
use App\Models\SubDepartment;
use App\Models\EmployeeProjects;
CommonHelper::companyDatabaseConnection(Input::get('m'));
$employee_data = DB::table('employee')->where('emp_id',Auth::user()->emp_id)->first();
CommonHelper::reconnectMasterDatabase();
$emp_SubDepartment = SubDepartment::where([['department_id',$employee_data->emp_department_id],['status','1']])->first();



?>

<div class="panel">
    <div class="panel-body">
        {{ Form::open(array('url' => 'had/addManualyAttendances?m='.$m.'','id'=>'manualyAttendaceForm')) }}
        <div class="row">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="m" value="{{ Input::get('m') }}">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel">
                    <div class="panel-body">
                        <?php if($accType != 'user'){ ?>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label class="sf-label pointer">Department</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="requiredField form-control" name="department_id" id="department_id" onchange="getEmployee()">
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
                                <select class="requiredField form-control" name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
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
                                <select class="requiredField form-control" name="emp_id" id="emp_id" >
                                    <option value="0">-</option>
                                </select>
                                <div id="emp_loader_1"></div>
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="datesArea">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control requiredField">
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="requiredField form-control">
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label">Remarks:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="remarks" id="remarks" value="" class="requiredField form-control" required />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <button id="btn_search" type="button" class="btn_search btn btn-sm btn-primary" onclick="viewEmployeeManualAttendance()" style="margin-top: 32px;" ><i class="fa fa-search"></i>  Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }else{ ?>
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
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="datesArea">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control requiredField">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control requiredField">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Remarks:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="text" name="remarks" id="remarks" value="" class="form-control" required />
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <input type="button" class="btn btn-sm btn-primary" onclick="viewEmployeeManualAttendance()" value="Manage Employee Attendence" style="margin-top: 32px;" />
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div id="loader"></div>
                </div>
            </div>
            

        </div>
        <div class="employeeAttendenceSection"></div>
        {{ Form::close() }}
    </div>
</div>


<script>
//    function jqueryValidationCustom() {
//        var requiredField = document.getElementsByClassName('requiredField');
//        for (i = 0; i < requiredField.length; i++) {
//            var rf = requiredField[i].id;
//            var checkType = requiredField[i].type;
//            /*if(checkType == 'text'){
//             alert('Please type text');
//             }else if(checkType == 'select-one'){
//             alert('Please select one option');
//             }else if(checkType == 'number'){
//             alert('Please type number');
//             }else if(checkType == 'date'){
//             alert('Please type date');
//             }*/
//            if ($('#' + rf).val() == '') {
//                $('#' + rf).css('border-color', 'red');
//                $('#' + rf).focus();
//                validate = 1;
//                return false;
//            } else {
//                $('#' + rf).css('border-color', '#ccc');
//                validate = 0;
//            }
//        }
//
//
//        /*var requiredField1 = document.getElementsByClassName('requiredField');
//         for (i = 0; i < requiredField1.length; i++){
//         var rf1 = requiredField[i].id;
//         if($('#'+rf1+'').val() == ''){
//         validate = 1;
//         }else{
//         validate = 0;
//         }
//         }*/
//        return validate;
//    }



    function viewEmployeeManualAttendance(){

        var department_id = $('#department_id').val();
        var sub_department_id = $('#sub_department_id').val();
        var month_year = $('#month_year').val();
        var emp_id = $('#emp_id').val();
        var filter_value = $('#filter_value').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        var m = '<?php echo $m;?>';
        if(department_id != 0 && month_year != '' && emp_id !='')


        jqueryValidationCustom();
        jqueryValidationCustom();

        if(validate == 0){
            $('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: '<?php echo url('/')?>/hdc/viewEmployeeManualAttendance',
                type: "GET",
                data: {department_id:department_id,sub_department_id:sub_department_id,month_year:month_year,m:m,emp_id:emp_id,filter_value:filter_value,from_date:from_date,to_date:to_date},
                success:function(data) {

                    $('#loader').html('');
                    $('.employeeAttendenceSection').empty();
                    $('.employeeAttendenceSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="sectionEmployeeAttendense_'+department_id+'"><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
                }
            });
        }else{
            return false;
        }
    }

    function Filter(val){

        var datesArea = $('#datesArea');

        if(val == ''){
            $(datesArea).html();
        } else if(val == 1){
            if($('#employee_id').val() == 'All'){
                $('select[name="employee_id"]').find('option').get(0).remove();
                $("#employee_id").prop('disabled', false);
            }
            $(datesArea).html();
            $(datesArea).html('');
        }else if(val == 3){
            if($('#employee_id').val() == 'All'){
                $('select[name="employee_id"]').find('option').get(0).remove();
                $("#employee_id").prop('disabled', false);
            }
            $(datesArea).html();
            $(datesArea).html('<div class="row">'+
                '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">'+
                '<label>Current Month</label>'+
                '<input type="month" name="month_year" id="month_year" value="<?php echo $currentDate?>" class="form-control requiredField" required />'+
                '</div>');
        }

    }

    function getEmployee(){
        var m = '<?php echo $m ?>';
        var department = $("#department_id").val();
        var sub_department = $("#sub_department_id").val();

        if(department == '0'){
            $("#department_id_").val('0');
            $("#sub_department").val('0');
            $('select[name="emp_id"]').empty();
            $("#emp_id").prepend("<option value='0'>-</option>");
            return false;
        }
        if(department != '0' && sub_department == ''){
            data = {department:department,sub_department:'0',m:m}
        }
        else if(department != '' && sub_department != ''){
            data = {department:department,sub_department:sub_department,m:m}
        }
        if(department != ''){
            $('#emp_loader_1').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                type:'GET',
                url:'<?php echo url('/') ?>/slal/getSubDepartment',
                data:data,
                success:function(res){
                    $('#emp_loader_1').html('');
                    $('select[name="emp_id"]').empty();
                    $('select[name="emp_id"]').html(res);

                }
            })
        }
        else{
            $("#sub_department_id_1").prepend("<option value='' selected='selected'>Select Sub Department</option>");
            $('select[name="sub_department_id_1"]').empty();
            $('select[name="emr_no"]').empty();
        }
    }


    $(document).ready(function(){
        $('#department_id').select2();
        $('#sub_department_id').select2();
        $('#emp_id').select2();
        $('#filter_value').select2();
    });

</script>