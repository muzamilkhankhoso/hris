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
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LoanRequestList">
                                        <thead>
                                        <th class="text-center">EMP ID</th>
                                        <td class="text-center">{{ $allowance->emp_id }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Employee Name</th>
                                        <td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee', 'emp_name', $allowance->emp_id, 'emp_id') }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Allowance Type</th>
                                        <td class="text-center">{{ $allowance->allowance_type_id }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Amount</th>
                                        <td class="text-center">
                                            @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                                {{ number_format($allowance->allowance_amount,0) }}
                                            @else
                                                ******
                                            @endif

                                        </td>
                                        </thead>
                                        <th class="text-center">Remarks</th>
                                        <td class="text-center">{{ $allowance->remarks }}</td>
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


