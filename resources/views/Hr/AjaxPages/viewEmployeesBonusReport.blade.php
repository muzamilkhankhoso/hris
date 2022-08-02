<?php

use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
$totalBonus = 0;


?>
<div class="panel">
    <div class="panel-body" id="PrintHrReport">
        <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="bonusReport">
                        @if($bonus->count() > 0)
                            <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">EMP ID</th>
                            <th class="text-center">Emp Name</th>
                            <th class="text-center">Bonus Amount</th>
                            </thead>
                            <tbody>

                            <?php $counter = 1;?>
                            @foreach($bonus->get() as $key => $y)
                                @php
                                    $totalBonus+=$y->bonus_amount;
                                @endphp

                                <tr>
                                    <td class="text-center">{{$counter++}}</td>
                                    <td class="text-center">{{$y->emp_id}}</td>
                                    <td class="text-center">{{$y->emp_name}}</td>
                                   <td class="text-right">
                                       @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                           {{number_format($y->bonus_amount)}}
                                       @else
                                           ******
                                       @endif

                                   </td>
                                </tr>
                            @endforeach
                            <tr>

                                <td class="text-right" colspan="3"><strong>Total</strong></td>
                                <td class="text-right"><strong>
                                        @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                            {{number_format($totalBonus)}}
                                        @else
                                            ******
                                        @endif
                                    </strong></td>
                            </tr>
                            @else
                                <tr><td class="text-center" style="color:red;font-weight: bold;" colspan="4">Record Not Found !</td></tr>
                            @endif
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>