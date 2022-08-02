<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
$m = $_GET['m'];
$counter = 1;
$remaining_amount = $loanRequest->loan_amount-$paid_amount->paid_amount;
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
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LoanRequestList">
                                        <thead>
                                        <th class="text-center">Emp.Name</th>
                                        <td class="text-center">{{ $employee->emp_name }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Month-Year</th>
                                        <td class="text-center">{{ $loanRequest->month.'-'.$loanRequest->year }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Time</th>
                                        <td class="text-center ">{{ $loanRequest->time }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center ">Created By</th>
                                        <td class="text-center ">{{ $loanRequest->username }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center ">Date</th>
                                        <td class="text-center ">{{ $loanRequest->date }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center">Description</th>
                                        <td class="text-center">{{ $loanRequest->description }}</td>
                                        </thead>
                                        <thead>
                                        <th class="text-center ">Per Month Deduction</th>
                                        <td class="text-center ">
                                            @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                                {{ number_format($loanRequest->per_month_deduction,0) }}
                                            @else
                                                ******
                                            @endif

                                        </td>
                                        </thead>
                                        <thead>
                                        <th class="text-center ">Loan Amount</th>
                                        <td class="text-center ">
                                            @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                                {{ number_format($loanRequest->loan_amount,0) }}
                                            @else
                                                ******
                                            @endif

                                        </td>
                                        </thead>
                                        <thead>
                                        <th class="text-center ">Paid loan Amount</th>
                                        <td class="text-center " style="color: red;">
                                            @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                                {{ number_format($paid_amount->paid_amount,0)}}
                                            @else
                                                ******
                                            @endif

                                        </td>
                                        </thead>
                                        <thead>
                                        <th class="text-center ">Remaining Amount</th>
                                        <td class="text-center " style="color: red;">
                                            @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                                {{number_format($remaining_amount,0)}}
                                            @else
                                                ******
                                            @endif

                                        </td>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br>
                    <?php if(count($loan_Detail) != '0'){ ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color: #f5f3ff">
                            <h4 style="font-weight: bold;text-align: center;padding:5px;">Loan Detail</h4>
                    </div>
                <div>&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                                <thead>
                                <th class="text-center">S.No</th>
                                <th class="text-center">Deduction Month & Date</th>
                                <th class="text-center">Deduction Amount</th>
                                </thead>
                                <tbody>
                                <?php
                                    foreach($loan_Detail as $value){
                                    $monthNum  = $value->month;
                                    $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                                    $monthName = $dateObj->format('F'); // March
                                     ?>
                                      <tr>
                                      <td class="text-center">{{ $counter++ }}</td>
                                      <td class="text-center"><?php echo $monthName.' '.$value->year ?></td>
                                      <td class="text-center">
                                          @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                              {{ number_format($value->loan_amount_paid,0) }}
                                          @else
                                              ******
                                          @endif

                                      </td>
                                      </tr>
                                        <?php
                                    }
                                ?>
                                <tr>
                                    <th colspan="2" class="text-right">Total</th>
                                    <th class="text-center">
                                        @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                            {{ number_format($paid_amount->paid_amount,0)}}
                                        @else
                                            ******
                                        @endif

                                    </th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>
               </div>
                </div>
            </div>
        </div>
    </div>
</div>


