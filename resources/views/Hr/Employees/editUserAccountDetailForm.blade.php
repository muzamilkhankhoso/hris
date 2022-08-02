<?php

use App\Models\Employee;
use App\User;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Storage;
$accType = Auth::user()->acc_type;
//	if($accType == 'client'){
//		$m = $_GET['m'];
//	}else{
//		$m = Auth::user()->company_id;
//	}

$m = $_GET['m'];
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
CommonHelper::companyDatabaseConnection($m);
//$employee_name = Employee::select('id','emp_id','emp_name')->where('status','=',1)->get();
CommonHelper::reconnectMasterDatabase();





?>






        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">

            <div class="col-12">

                <div class="row">
                    <div class="col-sm-12">
                        <?php echo Form::open(array('url' => 'had/editUserAccountDetail','id'=>'userAccountDetailForm'));?>
                            <input type="hidden" name="emp_id" value="{{ $user_account_detail['emp_id'] }}">
                            <input type="hidden" name="id" value="{{ $user_account_detail['id'] }}">
                            <input type="hidden" name="company_id" value="<?=Input::get('m')?>">
                            <div class="row" id="credential_area_1">
                                <input type="hidden" name="sub_department_id_1" value="{{ $emp_role['emp_sub_department_id'] }}">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Account type</label>
                                    <select class="form-control" name="account_type_1">
                                        <?php if($user_account_detail['acc_type'] == 'user') { ?>
                                            <option selected value="user">User</option>
                                            <?php } else{ ?>
                                            <option selected value="client">Client</option>
                                            <?php } ?>

                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Roles</label>
                                    <select class="form-control" name="role_type">
                                        @foreach($roles as $role);
                                        <?php if($emp_role['role_id'] == $role['id']) { ?>
                                        <option selected value="{{ $role['id'] }}">{{ $role['role_name'] }}</option>
                                        <?php } else{ ?>
                                        <option value="{{ $role['id'] }}">{{ $role['role_name'] }}</option>
                                        <?php } ?>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="sf-label">Password</label>
                                    <input type="text" class="requiredField form-control" id="password_1" name="password_1">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <button style="margin-top:35px;color:white" class="btn btn-sm btn-warning" type="button" onclick="password_generator()" >Generate</button>
                                </div>

                            </div>
                            <br>
                            <div class="row">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <br>
                                    <div style="margin-top: 15px;">
                                    {{ Form::submit('Update', ['class' => 'btn btn-sm btn-success btn-md btn_disable']) }}
                                    <button type="reset" id="reset" class="btn btn-sm btn-primary btn-md">Clear Form</button>
                                    <?php echo Form::close();?>
                                    </div>
                                </div>
                            </div>


                    </div>
                </div>



            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right sidebar -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->
        <!-- ============================================================== -->
        <!-- End Right sidebar -->
        <!-- ============================================================== -->

    </div>
    <script>
    var animation = 'rubberBand';



/*
 * Icon
 * --------
 * This code toggle classes for
 * our icon.
 *
 */



$('.icon').on('click', function(){

    $(this).toggleClass('icon--active');
})

/* If you want to disable animation just comment out the code bellow */

$('.icon').on('click', function(){

    $(this).addClass('animated ' + animation).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
        $(this).removeClass('animated ' + animation);
    })
});
</script>



