
<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Bonus;
use App\Models\BonusIssue;
use App\Models\EmployeePromotion;
$counter = 1;
$data1 ='';
$bonus=0;
$bonusAmount=0;
$diff=0;
?>



<div class="lineHeight">&nbsp;</div>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-bordered table-striped">
                        <thead>
                        <th class="text-center">S.No</th>
                        <th class="text-center">EMP ID</th>
                        <th class="text-center">Emp Name</th>
                        <th class="text-center">Joining Date</th>

                        <th class="text-center">Emp Salary</th>
                        <th class="text-center">Bonus</th>
                        <th class="text-center">Bonus Month & Year</th>
                        <th class="text-center hidden-print"><input id="check_all" type="checkbox"></th>
                        </thead>
                        <tbody>
                        <?php foreach($all_employees as $key => $value):
                        CommonHelper::companyDatabaseConnection(Input::get('m'));
                        $get_bonus_data = BonusIssue::where([['status','=',1],['bonus_id','=',Input::get('bonus_id')],['emp_id','=',$value->emp_id],['bonus_month','=',$month_year[1]],['bonus_year','=',$month_year[0]]]);
                        $get_promotion_date = EmployeePromotion::where([['status','=',1],['emp_id','=',$value->emp_id]])->orderBy('id','desc');

                        CommonHelper::reconnectMasterDatabase();

                        $date1 = $value->emp_joining_date;
                        $emp_date=explode('-',$date1);
                        $date1=$emp_date[0].'-'.$emp_date[1];
                        $date2 = $monthYearDay;
                        $d1=new DateTime($date2);
                        $d2=new DateTime($date1);
                        $Months = $d2->diff($d1);

                        $ts1 = strtotime($value->emp_joining_date);
                        $ts2 = strtotime($bonus_month);

                        $empDay = date('d', $ts1);

                        if($empDay <= 15){
                            $diff =  $howeverManyMonths = (($Months->y) * 12) + ($Months->m);
                        }else{
                            $diff =  $howeverManyMonths = (($Months->y) * 12) + ($Months->m);
                            $diff--;
                            $diff;
                        }
                        ?>
                        @if($diff >= 12)
                            <?php
                            $date1 = $value->emp_joining_date;
                            $emp_date=explode('-',$date1);
                            $date1=$emp_date[0].'-'.$emp_date[1];
                            $date2 = $bonus_month."-31";
                            $d1=new DateTime($date2);
                            $d2=new DateTime($date1);
                            $Months = $d2->diff($d1);
                            if($empDay <= 15){
                                $diff =  $howeverManyMonths = (($Months->y) * 12) + ($Months->m);
                            }else{
                                $diff =  $howeverManyMonths = (($Months->y) * 12) + ($Months->m);
                                $diff--;
                                $diff;
                            }

                            ?>
                            <tr id="bonusId<?=$value->id?>">
                                <td class="text-center"><?=$counter++?></td>
                                <td class="text-center"><?=$value->emp_id?></td>
                                <td class="text-center"><?=$value->emp_name?>
                                </td>
                                <td class="text-center"><?= HrHelper::date_format($value->emp_joining_date)?></td>

                                <td class="text-right">
                                    @if($get_promotion_date->count() > 0)
                                        <?php
                                        $get_promotion = $get_promotion_date->first();
                                        ?>
                                        <?=number_format($get_promotion->salary,0)?>
                                    @else
                                        <?=number_format($value->emp_salary,0)?>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($get_promotion_date->count() > 0)
                                        @if($diff >= 12)
                                            <?php $basic_salry=($get_promotion->salary/3)*1 ?>
                                            <?=number_format($basic_salry,0)?>
                                        @else
                                            <?php $basic_salry=($get_promotion->salary/3)*1 ?>
                                            <?php $bonus=$basic_salry;
                                            $bonusAmount=($bonus/12);
                                            ?>
                                            <?=number_format($bonusAmount*$diff,0)?>
                                        @endif
                                    @else
                                        @if($diff >= 12)
                                            <?php $basic_salry=($value->emp_salary/3)*1 ?>
                                            <?=number_format($basic_salry,0)?>
                                        @else
                                            <?php $basic_salry=($value->emp_salary/3)*1 ?>
                                            <?php $bonus=$basic_salry;
                                            $bonusAmount=($bonus/12);
                                            ?>
                                            <?=number_format($bonusAmount*$diff,0)?>
                                        @endif

                                    @endif

                                </td>
                                <td class="text-center"><?=Input::get('bonus_month_year')?></td>
                                <td class="text-center hidden-print">
                                   
                                    
                                    @if($get_promotion_date->count() > 0)
                                        @if($diff >= 12)
                                            <?php $basic_salry=($get_promotion->salary/3)*1 ?>
                                            <input type="checkbox" class="ads_Checkbox " name="check_list[]" value='<?=$value->emp_id.'_'.$basic_salry?>'>
                                        @else
                                            <?php $basic_salry=($get_promotion->salary/3)*1 ?>
                                            <?php $bonus=$basic_salry;
                                            $bonusAmount=($bonus/12);
                                            ?>
                                            <input type="checkbox" class="ads_Checkbox " name="check_list[]" value='<?=$value->emp_id.'_'.(($bonusAmount*$diff)) ?>'>
                                        @endif
                                    @else
                                        @if($diff >= 12)
                                            <?php $basic_salry=($value->emp_salary/3)*1 ?>
                                            <input type="checkbox" class="ads_Checkbox " name="check_list[]" value='<?=$value->emp_id.'_'.$basic_salry?>'>
                                        @else
                                            <?php $basic_salry=($value->emp_salary/3)*1 ?>
                                            <?php $bonus=$basic_salry;
                                            $bonusAmount=($bonus/12);
                                            ?>
                                            <input type="checkbox"  class="ads_Checkbox " name="check_list[]" value='<?=$value->emp_id.'_'.(($bonusAmount*$diff)) ?>'>
                                        @endif

                                    @endif
                                    
                                </td>
                            </tr>
                        @endif
                        <?php endforeach;
                         ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden-print">
    <button class="btn btn-sm btn-success" type="submit">Submit</button>
</div>
<?php
CommonHelper::reconnectMasterDatabase(); ?>
<script>
    // $('.btn-success').prop('disabled', true);
    // $(".ads_Checkbox").change(function() {
    //     if(this.checked) {
    //         $('.btn-success').prop('disabled', false);
    //     }
    //     else{
    //         $('.btn-success').prop('disabled', true);
    //     }
    // });


    $(function(){
        $("#check_all").click(function(){

            if($("#check_all").prop("checked") == true)
            {
                $(".ads_Checkbox").prop("checked",true);
                $('.btn-success').prop('disabled', false);
            }
            else
            {
                $(".ads_Checkbox").prop("checked",false);
                $('.btn-success').prop('disabled', true);
            }


        });
    });

    function removeBonus(id){
        var id;
        var m = '<?=Input::get('m')?>';
        var _token = $("meta[name=csrf-token]").attr("content");

        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url : ""+baseUrl+"/cdOne/deleteEmployeeBonus",
                type: "GET",
                data: {id:id, m:m, _token:_token},
                success: function (data) {
                    $("#bonusId"+id).fadeOut();
                },
                error: function () {
                    console.log("error");
                }
            });
        }
    }


</script>

