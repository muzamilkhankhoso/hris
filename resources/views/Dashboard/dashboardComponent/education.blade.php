<?php
use App\Helpers\HrHelper;
?>
<div class="tab-pane" id="Education">
    <br>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <span class="subHeadingLabelClass">Education</span>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
        <input type="button" name="add_edu" id="add_edu" value="+" class="btn btn-success"	/>
    </div>
    <br>

    <table class="table table-hover table-fixed">
        <!--Table head-->
        <thead>
        <tr>
            <th class="text-center">S.no</th>
            <th class="text-center">Institute Name</th>
            <th class="text-center">Degree Type</th>
            <th class="text-center">Year Of Admission</th>
            <th class="text-center">Year Of Passing</th>
        </tr>
        </thead>
        <!--Table head-->

        <!--Table body-->
        <tbody>
        <?php if(count($employee_education) != 0){ ?>

        <?php foreach($employee_education as $value){ ?>
        <tr>
            <td class="text-center"><?php echo $counter++ ?></td>
            <td class="text-center"><?php echo $value->institute_name ?></td>
            <td class="text-center"><?php echo HrHelper::getMasterTableValueById(Input::get('m'),'degree_type','degree_type_name',$value->degree_type) ?></td>
            <td class="text-center"><?php echo date("d-m-Y", strtotime($value->year_of_admission)); ?></td>
            <td class="text-center"><?php echo date("d-m-Y", strtotime($value->year_of_passing)); ?></td>
        </tr>
        <?php } ?>

        <?php } else{ ?>
        <tr>
            <td colspan="4" class="text-danger text-center"><h2>No Record Found</h2></td>
        </tr>
        <?php } ?>
        </tbody>
        <!--Table body-->

    </table>
    <!--Table-->
</div>