<?php
use App\Helpers\HrHelper;

?>
<div class="col-sm-3"><!--left col-->
    <div class="text-center">
        <?php if($employee->value('img_path') != ''){ ?>
        <img src="<?php echo Storage::url($employee->value('img_path')) ?>" class="avatar img-circle img-thumbnail" alt="avatar">
        <?php } else{ ?>
        <img id="img_file_1" class="img-circle" src="<?= Storage::url('app/uploads/employee_images/user-dummy.png')?>">
        <?php } ?>
        <h3 id="username"><?php echo $employee->value('emp_name') ?></h3>
    </div>
    <hr><br>
    <ul class="list-group">
        <li class="list-group-item text-right"><span class="pull-left"><strong>Emp ID</strong></span> <?php echo $employee->value('emp_id') ?></li>
        <li class="list-group-item text-right"><span class="pull-left" ><strong>Name</strong></span><p id="name_1"><?php echo $employee->value('emp_name') ?></p></li>
        <li class="list-group-item text-right"><span class="pull-left"><strong>Department</strong></span> <?php echo HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$employee->value('emp_sub_department_id')); ?></li>
        <li class="list-group-item text-right"><span class="pull-left"><strong>Designation</strong></span> <?php echo HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$employee->value('designation_id'))?></li>
        <li class="list-group-item text-right"><span class="pull-left"><strong>Reporting Manager</strong></span> <?php echo $reporting_manager ?></li>
        <li class="list-group-item text-right"><span class="pull-left"><strong>Cell #</strong></span><p id="cell_1"><?php echo $employee->value('emp_contact_no') ?></p></li>
        <li class="list-group-item"><span class="pull-left" ><strong>Official Email</strong></span><br><p id="p_email_1"><?php echo $employee->value('professional_email') ?></p></li>
        <li class="list-group-item text-right"><span class="pull-left"><strong>DOJ #</strong></span> <?php echo  date("d-m-Y", strtotime($employee->value('emp_joining_date'))); ?></li>
        <li class="list-group-item text-right" ><span class="pull-left" id><strong>DOB</strong></span><p id="dob_1"><?php echo  date("d-m-Y", strtotime($employee->value('emp_date_of_birth'))) ?></p></li>
        @if($team_lead_exists->exists())
            <li class="list-group-item"><strong>Team Member</strong>
                <ol>
                    @foreach($team_lead_exists->get() as $value)
                        <li>{{$value->emp_name}}</li>

            @endforeach
    </ul>
    </ol>
    @endif
    </ul>
</div>