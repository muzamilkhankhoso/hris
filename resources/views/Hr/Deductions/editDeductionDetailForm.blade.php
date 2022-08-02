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
    <?php echo Form::open(array('url' => 'had/editDeductionDetail','id'=>'employeeForm'));?>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="company_id" value="<?=$m?>">
    <input type="hidden" name="deductionId" class="form-control" id="deductionId" value="<?= $deduction->id;?>" />

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
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <label class="sf-label">Deduction Type:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="deduction_type" id="deduction_type" class="form-control requiredField" required>
                                <option value="">Select Deduction Type</option>
                                @if($deduction->deduction_type=="LWP")
                                    <option selected value="LWP">LWP</option>
                                    <option value="Penalty">Penalty</option>
                                    <option value="Other">Other</option>
                                @elseif($deduction->deduction_type=="Penalty")
                                    <option value="LWP">LWP</option>
                                    <option selected value="Penalty">Penalty</option>
                                    <option value="Other">Other</option>
                                @elseif($deduction->deduction_type=="Other")
                                    <option value="LWP">LWP</option>
                                    <option value="Penalty">Penalty</option>
                                    <option selected value="Other">Other</option>
                                @else
                                    <option value="LWP">LWP</option>
                                    <option value="Penalty">Penalty</option>
                                    <option value="Other">Other</option>
                                @endif
                            </select>

                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <label class="sf-label">Deduction Amount:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                <input type="number" name="deduction_amount" id="deduction_amount" value="<?= $deduction->deduction_amount; ?>" class="form-control requiredField" required />
                            @else
                                <input type="text" readonly name=""  value="******" class="form-control"  />
                            @endif

                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 15px;">
                            <br>
                            <label>Once ?
                                @if($deduction->once == '1')
                                    <input checked type="checkbox" name="once" id="once" value="1">
                                    <script>
                                        if($("#once").is(':checked')){
                                            $(".once_area").html('<label>Month-Year</label><input selected type="month" class="requiredField form-control" name="month_year" value="{{ $deduction->year.'-'.$deduction->month }}" >')
                                        }
                                    </script>
                                @else
                                    <input type="checkbox" name="once" id="once" value="1">
                                @endif

                            </label>



                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <label class="sf-label">Remarks:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <textarea type="text" name="remarks" id="remarks" class="form-control requiredField"><?= $deduction->remarks; ?></textarea>
                        </div>

                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 once_area"></div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                <div>{{ Form::submit('Update', ['class' => 'btn btn-sm btn-success']) }}</div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
    <?php echo Form::close();?>
</div>
<script>
    $('#once').click(function (e){

        if($("#once").is(':checked')){

            $(".once_area").html('<label>Month-Year</label><input selected type="month" class="requiredField form-control" name="month_year" value="{{ $deduction->year.'-'.$deduction->month }}" >')
        }

        else{
            $(".once_area").html('')
        }

    });
    $(document).on('change', '#deduction_type', function() {

        if($(this).val()=="LWP"){
            $('.lblDeduct').html("Deduction Days:");
        }
        else{
            $('.lblDeduct').html("Deduction Amount:");
        }
    });
</script>