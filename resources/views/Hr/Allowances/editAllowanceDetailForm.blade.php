<?php
use App\Helpers\HrHelper;
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
?>
<div class="well">
    <?php echo Form::open(array('url' => 'had/editAllowanceDetail','id'=>'employeeForm'));?>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="company_id" value="<?=$m?>">
    <input type="hidden" name="allowanceId" class="form-control" id="allowanceId" value="<?= $allowance->id;?>" />

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
                            <label class="sf-label">Allowance Type:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="allowance_type" id="allowance_type" value="" class="form-control requiredField" >
                                <option value="">Select Department</option>
                                @foreach($allowanceTypes  as $key => $y1)
                                    <option @if ($allowance->allowance_type_id == $y1->id) selected @endif value="<?php echo $y1->id ?>">
                                        {{ $y1->allowance_type}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="sf-label">Allowance Amount:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                <input type="number" name="allowance_amount" id="allowance_amount" value="<?= $allowance->allowance_amount; ?>" class="form-control requiredField" required />
                            @else
                                <input type="text" readonly name=""  value="******" class="form-control"  />
                            @endif

                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="sf-label">Remarks:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <textarea name="remarks" id="remarks" class="form-control requiredField"  ><?= $allowance->remarks; ?></textarea>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 once_area"></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <br>
                            <div style="margin-top:13px;">
                                {{ Form::submit('Update', ['class' => 'btn btn-success btn-sm']) }}
                                <button type="reset" id="reset" class="btn btn-primary btn-sm">Clear Form</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">

        </div>

    </div>
    <?php echo Form::close();?>
</div>

<script>
    $(document).ready(function(){
        if($('#allowance_type').val() == 5 || $('#allowance_type').val() == 6){
            $(".once_area").html('<label>Month-Year</label><input required type="month" class="form-control requiredField" name="month_year" value="{{ $allowance->year.'-'.$allowance->month }}"><input type="hidden" name="once" id="once" value="1"> ')
        }
    });
    $(document).on('change', '#allowance_type', function() {
        if($(this).val()==5 || $(this).val()==6){
            $(".once_area").html('<label>Month-Year</label><input required type="month" class="form-control requiredField" name="month_year" value="{{ $allowance->year.'-'.$allowance->month }}"><input type="hidden" name="once" id="once" value="1"> ')

        }
        else{
            $(".once_area").html('')
        }
    });


</script>