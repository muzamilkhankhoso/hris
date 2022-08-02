<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
//if ($accType == 'client') {
//    $m = $_GET['m'];
//} else {
//    $m = Auth::user()->company_id;
//}

$m = $_GET['m'];

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');

$id = Input::get('id');

$emp_name = HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee', 'emp_name', $employee_promotion->emp_id, 'emp_id')

?>

<style>
    hr{border-top: 1px solid cadetblue}

    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }
</style>

<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="lineHeight">&nbsp;</div>
                <div class="panel">
                    <div class="panel-body">
                        <?php echo Form::open(array('url' => 'had/editSalaryDetail'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token()}}"/>
                        <input type="hidden" name="company_id" id="company_id" value="<?php echo $m ?>">
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <div class="gudia-gap">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">EMP ID:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input readonly name="emp_id" id="emp_id" type="text" value="{{ $employee_promotion->emp_id }}" class="form-control requiredField">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Employee Name:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input readonly name="emp_name" id="emp_name" type="text" value="{{$emp_name}}" class="form-control requiredField">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 div_salary"">
                                        <label class="sf-label">Increment <span class="rflabelsteric"><strong>*</strong></span></label>
                                        @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                            <input type="text" name="increment" id="increment" value="0" class="form-control requiredField" onkeyup="changeSalary()" required/>
                                        @else
                                            <input type="text" value="******" class="form-control" readonly/>
                                        @endif

                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 div_salary">

                                        <label>Salary <span class="rflabelsteric"><strong>*</strong></span></label>
                                        @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                            <input name="salary" id="salary" type="number" value="{{ $employee_promotion->salary - $employee_promotion->increment }}" class="form-control requiredField" required readonly>
                                        @else
                                            <input type="text" value="******" class="form-control" readonly/>
                                        @endif


                                    </div>

                            </div>
                            </div>
                            

                        <br>
                        <div style="float: right;">
                            <button style="text-align: center" class="btn btn-sm btn-success" type="submit" value="Submit">Update</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var previousSalary = $('#salary').val();
    function changeSalary() {
        console.log(previousSalary)
        $('#salary').val(previousSalary);
        var salary = parseFloat($('#salary').val());
        var increment = parseFloat($('#increment').val());
        console.log(salary)
        $('#salary').val(salary + increment);

        if ($('#increment').val() == '')
            $('#salary').val(previousSalary);

    }
</script>

