<?php
use App\Helpers\HrHelper;
?>
<div class="tab-pane" id="Leaves">
    <br>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <span class="subHeadingLabelClass">Leaves</span>
    </div>
    <br>
    <div class="row">
        <?php
        if($WithoutLeavePolicy[0] == 'Select Leave Policy'){
        ?>
        <div class="col-sm-12 col-md-12">
            <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                </button>
                <span class="glyphicon glyphicon-record"></span> <strong>Warning Message</strong>
                <hr class="message-inner-separator">
                <p>
                    Please Select Leave Policy.</p>
            </div>
        </div>
        <?php

        }
        else{
        ?>

        <div class="lineHeight">&nbsp;</div>
        <div class="panel">
            <div class="panel-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <table class="table table-bordered sf-table-list">
                        <thead>
                        <tr>
                            <th style="background-color: rgb(42, 110, 207);padding: 2px;">
                                <div style="">
                                    <div style="display:block;float:left;width:50%; margin-top: 7px;">&nbsp;&nbsp;<span style="color:white;">LEAVES BALANCE</span></div>
                                    <div class="text-right">
                                        <?php
                                        $total_leaves = $total_leaves->total_leaves;
                                        $taken_leaves = $taken_leaves->taken_leaves;?>
                                        <span class="btn btn-success btn-sm" style="cursor: default">Taken Leaves = <?= ($taken_leaves == '')? '0': $taken_leaves ?></span>
                                        <span class="btn btn-danger btn-sm" style="cursor: default">Remaining Leaves= <?=($total_leaves-$taken_leaves)?></span>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                    <table class="table table-bordered sf-table-list">
                        <thead>
                        <tr>
                            <th>S No#</th>
                            <th>Leaves Name</th>
                            <th class="text-center">No of leaves</th>
                            <th class="text-center">Used</th>
                            <th class="text-center">Remaining</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count =0 ;
                        $count_leaves = '0';
                        ?>
                        @foreach($leaves_policy as $val)
                            <?php
                            $count_leaves+=$val->no_of_leaves ;
                            $count++;
                            ?>
                            <tr>
                                <td style="color: green;"><b>{{ $count }}</b></td>
                                <td style="color: green;"><b>{{ HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$val->leave_type_id )}}</b></td>
                                <td class="text-center" style="color: green;"><b>
                                        <?php

                                        if($val->leave_type_id == 1):
                                            echo $val->no_of_leaves;
                                        elseif($val->leave_type_id == 3):
                                            echo $val->no_of_leaves;
                                        else:
                                            echo $val->no_of_leaves;
                                        endif;
                                        ?>
                                    </b>
                                </td>
                                <td class="text-center">
                                    <?php

                                    //CommonHelper::companyDatabaseConnection(Input::get('m'));
                                    $getUsedLeaves =DB::table('leave_application_data')
                                        ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                        ->where([['leave_application.emp_id','=',$emp_data->emp_id],['leave_application.leave_type','=',$val->leave_type_id ],
                                            ['leave_application.status', '=', '1'],
                                            ['leave_application.approval_status_lm', '=', '2'],
                                            ['leave_application.approval_status', '=', '2']])
                                        ->sum('no_of_days');
                                    $countUsedLeavess +=$getUsedLeaves;
                                    echo $getUsedLeaves;
                                    //CommonHelper::reconnectMasterDatabase();

                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    if($val->leave_type_id == 1):
                                        $remaining = $val->no_of_leaves-$getUsedLeaves;

                                    elseif($val->leave_type_id == 3):
                                        $remaining = $val->no_of_leaves-$getUsedLeaves;
                                    else:
                                        $remaining = $val->no_of_leaves-$getUsedLeaves;
                                    endif;



                                    if($remaining < 0):
                                        echo "<span style='color:red;'>$remaining</span>";
                                    else:
                                        $countRemainingLeaves +=$remaining;
                                        echo $remaining;
                                    endif;
                                    ?>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="text-right"  style="color: #fff;background-color: #2a6ecf;" colspan="2"><b>Total</b></th>
                            <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #2a6ecf;"><b>{{ $count_leaves }}</b></th>
                            <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #2a6ecf; "><?php print_r($countUsedLeavess)?></th>
                            <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #2a6ecf; "><?=$countRemainingLeaves?></th>


                        </tr>
                        </tfoot>

                    </table>
                    <table class="table table-bordered sf-table-list">
                        <tr class="text-center" style="color: #fff;background-color: #2a6ecf;">
                            <td colspan="5">
                                <b>SELECT LEAVE TYPE</b>
                                &ensp;
                                <span class="glyphicon glyphicon-arrow-down"></span>

                            </td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="5">
                                <div class="btn-group" data-toggle="buttons" style="padding: 4px;">
                                    @foreach($leaves_policy as $val)
                                        <?php $leaveName = HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$val->leave_type_id )?>

                                        <label style="border:1px solid #fff;" class="btn btn-success" onclick="viewEmployeeLeavesDetail('<?=$val->id?>','{{ $val->no_of_leaves }}','<?php echo $val->leave_type_id ?>')">
                                            <input required="required" autocomplete="off" type="radio" name="leave_type" id="leave_type" class="requiredField" value="<?=$val->leave_type_id?>">
                                            {{ $leaveName }}
                                            <span class="glyphicon glyphicon-ok"></span>
                                        </label>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="leavesData"></div>
                    <div class="" id="leave_days_area"></div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Reason For Leave</label>
                    <textarea id="reason" class="form-control requiredField">-</textarea>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Address While on Leaave</label>
                    <textarea id="leave_address" class="form-control requiredField">-</textarea>
                </div>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <span id="errorMesg" style="color:red"></span>
                <button type="button" id="submitBtn" onclick="check_days()" class="btn btn-success">Submit</button>
                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
            </div>
        </div>
        <?php echo Form::close();?>


        <?php } ?>
    </div>
</div>