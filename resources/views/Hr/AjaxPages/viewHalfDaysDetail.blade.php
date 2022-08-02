<?php
use App\Helpers\HrHelper;
?>
<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       
        <div class="table-responsive">
            <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LeaveApplicationRequestList">
                <thead>
                    <th class="text-center">S No.</th>
                    <th class="text-center">HalfDay Date</th>
                    <th class="text-center">Day</th>
                    <th class="text-center">Check In</th>
                    <th class="text-center">Check Out</th>
                    <th class="text-center">Working Hours</th>
                </thead>
                <tbody>
                <?php $counter = 1; ?>
                @foreach($total_halfDay as $value)
                    <?php
                        if($value->clock_in != NULL && $value->clock_out != NULL) {
                            $half_day_time=strtotime("+".($working_hours_policy->half_day_time*60)."minutes", strtotime($value->clock_in));     
                           
                            if(($value->duty_time < $working_hours_policy->half_day_time)){  
                        ?>

                        <tr>
                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                            <td class="text-center"><?php echo HrHelper::date_format($value->attendance_date);?></td>
                            <td class="text-center">{{$value->day}}</td>
                            <td class="text-center">{{date('h:i: a', strtotime($value->clock_in))}}</td>
                            <td class="text-center" style="background-color: #FFC0CB;">{{date('h:i: a', strtotime($value->clock_out))}}</td>
                            <td class="text-center">
                            <?php
                                        //Duration
                                        if($value->clock_in == '' || $value->clock_out == ''):
                                            echo '';
                                            $elapsed2 = 0;
                                        else:
                                            $start = strtotime($value->clock_in);
                                            $end = strtotime($value->clock_out);

                                            $elapsed2 = $end - $start;
                                            //echo '<b>'.date("H:i", $elapsed).'</b>'.'<br>';
                                            echo "<b>".$duration = date("H:i", $elapsed2)."</b>";
                                        endif;
                                        ?>

                            </td>
                        </tr>


                        <?php }
                        }
                        
                    ?>
                        
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>