<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = Input::get('m');


?>

<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="lineHeight">&nbsp;</div>
                <div class="panel">
                    <div class="panel-body" id="PrintLoanRequestList">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0 table-bordered table-striped" id="LoanRequestList">
                                        <thead>
                                        <th class="text-center">EMP ID</th>
                                        <td class="text-center"><?php echo $deduction->emp_id ?></td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Employee Name</th>
                                        <td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee', 'emp_name', $deduction->emp_id, 'emp_id') }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Deduction Type</th>
                                        <td class="text-center">{{ $deduction->deduction_type }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Amount</th>
                                        <td class="text-center">
                                            @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                                {{ $deduction->deduction_amount }}
                                            @else
                                                ******
                                            @endif

                                        </td>
                                        </thead>
                                        <thead>
                                        <thead>
                                        <th class="text-center">Remarks</th>
                                        <td class="text-center">{{ $deduction->remarks }}</td>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


