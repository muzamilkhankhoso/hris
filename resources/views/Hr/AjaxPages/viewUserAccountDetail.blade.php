<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
//$day_off = explode("=>",$user_account_detail->day_off);



use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;

?>





<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-right">
                    <?php echo CommonHelper::displayPrintButtonInBlade('printEmployeeDetail','','1');?>

                </div>
            </div>
            <br>
            <div class="row">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="company_id" value="<?=Input::get('m')?>">



                <input type="hidden" name="old_password" value="<?=$user_account_detail->password?>">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel" id="printEmployeeDetail">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="UserAccountsList">
                                        <tbody>

                                        <?php if($user_account_detail){ ?>
                                        <tr>
                                            <td  class="text-center">1</td>
                                            <th class="text-center">Employee ID </th>
                                            <td class="text-center"><?php echo $user_account_detail['emp_id'] ?></td>
                                        </tr>
                                        <tr>
                                            <td  class="text-center">2</td>
                                            <th class="text-center">Employee Name </th>
                                            <td class="text-center"><?php echo $user_account_detail['name'] ?></td>
                                        </tr>
                                        <tr>
                                            <td  class="text-center">3</td>
                                            <th class="text-center">Username</th>
                                            <td class="text-center"><?php echo $user_account_detail['username'] ?></td>
                                        </tr>
                                        <tr>
                                            <td  class="text-center">4</td>
                                            <th class="text-center">Email</th>
                                            <td class="text-center"><?php echo $user_account_detail['email'] ?></td>
                                        </tr>
                                        <tr>
                                            <td  class="text-center">5</td>
                                            <th class="text-center">Role</th>
                                            <td class="text-center"><?php echo $user_account_detail['acc_type'] ?></td>
                                        </tr>


                                        <?php } else{ ?>
                                        <tr>
                                            <td colspan="13" class="text-danger text-center"><h3 class="text-danger"><strong>No Record Found</strong></h3></td>
                                        </tr>
                                        <?php } ?>
                                        </tbody>
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
