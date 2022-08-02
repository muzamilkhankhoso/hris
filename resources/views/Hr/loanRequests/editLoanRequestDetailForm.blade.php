<?php
use App\Helpers\HrHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>

<div class="lineHeight"></div>
<div class="well">

        <?php echo Form::open(array('url' => 'had/editLoanRequestDetail'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" value="<?= Input::get('m') ?>">
        <input type="hidden" name="loanRequestId" value="<?= Input::get('id') ?>">

            <div class="panel">
                <div class="panel-body">
                    <div class="get_clone">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Needed on Month & Year:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" name="needed_on_date" id="needed_on_date" value="{{ $loanRequest->year.'-'.$loanRequest->month }}" class="form-control requiredField count_rows" required />
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Loan Type</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select name="loan_type_id" class="form-control requiredField" id="loan_type_id">
                                    <option value="">Select</option>
                                    @foreach($loanTypes as $laonTypeValue)
                                        <option @if($loanRequest->loan_type_id == $laonTypeValue->id) selected @endif value="{{ $laonTypeValue->id}}">{{ $laonTypeValue->loan_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">


                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Loan Amount</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                    <input type="number" name="loan_amount" id="loan_amount" value="{{ $loanRequest->loan_amount }}" class="form-control requiredField count_rows" required />
                                @else
                                    <input type="text" readonly value="******" class="form-control count_rows" />
                                @endif

                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Per Month Deduction</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                    <input type="number" name="per_month_deduction" id="per_month_deduction" value="{{ $loanRequest->per_month_deduction }}" class="form-control requiredField count_rows" required />
                                @else
                                    <input type="text" readonly value="******" class="form-control count_rows" />
                                @endif

                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label class="sf-label">Loan Description</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <textarea required name="loan_description" class="form-control" id="contents">{{ $loanRequest->description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                {{ Form::submit('Submit', ['class' => 'btn btn-success btn-sm']) }}
            </div>


</div>

