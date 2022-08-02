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
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered sf-table-list table-stripeded" id="LoanRequestList">
                                        <thead>
                                        <th class="text-center">EMP ID</th>
                                        <td class="text-center">{{ $advance_salary->emp_id }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Employee Name</th>
                                        <td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee', 'emp_name', $advance_salary->emp_id, 'emp_id') }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Deduction Month-Year</th>
                                        <td class="text-center">{{ $advance_salary->deduction_month."-".$advance_salary->deduction_year }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Amount</th>
                                        <td class="text-center">
                                            @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                                {{ $advance_salary->advance_salary_amount }}
                                            @else
                                                ******
                                            @endif

                                        </td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Advance Salary to be Needed on</th>
                                        <td class="text-center" style="color: red;">{{ HrHelper::date_format($advance_salary->salary_needed_on) }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Reason (Detail)</th>
                                        <td class="text-center">

                                            {{ $advance_salary->detail }}
                                        </td>
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


