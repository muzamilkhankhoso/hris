<?php
use App\Helpers\HrHelper;
?>
<div class="tab-pane" id="JobHistory">
    <br>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <span class="subHeadingLabelClass">Education</span>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right" style="margin-top: 19px;">
            <button type="button" class="btn btn-default btn-md" id="salaryPrivacy" style="margin-right: -35px;">
                <span class="glyphicon glyphicon-eye-open"></span>
            </button>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hidden text-right" id="pass_eye" style="margin-top: 19px">
            <input type="password" class="form-control" name="p_user" id="p_user" placeholder="password" style="margin-left: 20px;" >
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 hidden checkSubmit text-right">
            <button type="button" class="btn btn-default btn-md" id="check_pass" style="margin-top: 20px;margin-right: -3px;">
                <span class="">Ok</span>
            </button>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="bg-danger text-center" id="error_password" style="width: 276px;margin-left: 525px;" ></p>
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <ul class="timeline">
                <?php if($EmployeePromotion->count() > 0){

                ?>

                <?php foreach($EmployeePromotion->get() as $value){
                $salarys[] = number_format($value->salary,0);

                ?>
                <li>
                    <a target="_blank" href="#"><?php echo HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$value->designation_id) ?></a>
                    <a href="#" class="float-right"><?php echo date("F d, yy", strtotime($value->date)) ?></a>
                    <h5>Salary: <span class="salary_hidden demi" id="check_<?= $counting++ ?>" ><?php echo number_format($value->salary) ?></span></h5>
                </li>
                <?php } ?>
                <?php } ?>
                <li>
                    <a target="_blank" href="#"><?php echo HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$employee->value('designation_id')) ?></a>
                    <a href="#" class="float-right"><?php echo date("F d, yy", strtotime($employee->value('date'))) ?></a>
                    <h5>Salary: <span class="salary_hidden demi" id="check_<?php echo $counting++ ?>" ><?php echo number_format($employee->value('emp_salary'));    ?></span></h5>
                    <?php $salarys[] = number_format($employee->value('emp_salary'),0); ?>
                </li>
            </ul>
        </div>
    </div>
</div>