<?php
$currentDate = date('Y-m-d');
$accType = Auth::user()->acc_type;
$m = Input::get('m');

?>

<div class="panel">
    <div class="panel-body">
        {!! Form::open(array('url' => 'had/uploadAttendanceFile','method'=>'POST','files'=>'true')) !!}
        <input type="hidden" name="m" value="{{ Input::get('m') }}">
        <input type="hidden" name="employeeSection[]" value="1">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="sf-label">Select File to Import:</label>
                    <span class="rflabelsteric">* (.XLSX file Supported)</span>
                {!! Form::file('sample_file', array('class' => 'form-control requiredField','id'=>'sample_file','onChange'=>'getoutput()')) !!}
                <!--{!! $errors->first('sample_file', '<p class="alert alert-danger">:message</p>') !!}
                        <span class="text-success"><?php if($errors->first() == '1'){echo 'Your File Import Successfully';}?></span>
                    <span class="text-danger"><?php if($errors->first() == '2'){echo 'Please Select File To Import';}?></span>-->
                    <span id="extension_err_messg" style="color:red;"></span>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin-top: 38px">
                {!! Form::submit('Upload',['class'=>'btn btn-sm btn-success','id'=>'BtnImport']) !!}

            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>

    $(function(){
        $(".btn-success").click(function(e){
            var employee = new Array();
            var val;
            $("input[name='employeeSection[]']").each(function(){
                employee.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of employee) {
                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });
    });

    function getoutput(){
        var file_extension = sample_file.value.split('.')[1];

        if( file_extension == 'xlsx' )
        {

            $("#extension_err_messg").html('');
            $("#BtnImport").removeAttr('disabled');
        }
        else
        {

            $("#BtnImport").attr('disabled','disabled');
            $("#extension_err_messg").html('Please Select xlsx File !');
        }

    }
</script>