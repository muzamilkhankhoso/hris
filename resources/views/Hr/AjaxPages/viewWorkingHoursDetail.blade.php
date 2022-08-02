<?php
use App\Helpers\HrHelper;
?>
<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       
        <div class="table-responsive">
            <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="LeaveApplicationRequestList">
                <thead>
                    <th class="text-center">S No.</th>
                    <th class="text-center">Attendance Date</th>
                    <th class="text-center">Day</th>
                    <th class="text-center">Check In</th>
                    <th class="text-center">Check Out</th>
                    <th class="text-center">Working Hours</th>
                </thead>
                <tbody>
                <?php $counter = 1; ?>
                @foreach($total_working_hours as $value)
                    <?php
                    $duration='';
                        if($value->clock_in != NULL && $value->clock_out != NULL) {
                            $start = strtotime($value->clock_in);
                            $end = strtotime($value->clock_out);

                            $elapsed2 = $end - $start;
                            //echo '<b>'.date("H:i", $elapsed).'</b>'.'<br>';
                            $duration = date("H", $elapsed2)."</b>";
                        ?>

                        <tr>
                            <td class="text-center"><span class="badge badge-pill badge-secondary">{{ $counter++ }}</span></td>
                            <td class="text-center"><?php echo HrHelper::date_format($value->attendance_date);?></td>
                            <td class="text-center">{{$value->day}}</td>
                            <td class="text-center">{{date('h:i: a', strtotime($value->clock_in))}}</td>
                            <td class="text-center">{{date('h:i: a', strtotime($value->clock_out))}}</td>
                            <td class="text-center" style="background-color: @if($duration >=8) rgba(101, 124, 237, 0.43); @elseif($duration >=6 && $duration < 8) rgba(253, 193, 106, 0.43); @elseif($duration < 6) rgba(255, 79, 112, 0.43); @endif color:white;">
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


                        <?php 
                        }
                        
                    ?>
                        
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>