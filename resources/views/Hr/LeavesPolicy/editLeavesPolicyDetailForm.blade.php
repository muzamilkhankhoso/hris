<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
use App\Helpers\HrHelper;
?>



<div class="well">
    <div class="row">
        <?php echo Form::open(array('url' => 'had/editLeavesPolicyDetail','id'=>''));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" value="<?=$m?>">
        <input type="hidden" name="record_id" value="<?=Input::get('id')?>">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Leaves Policy Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input name="leaves_policy_name" type="text" value="<?=$leavesPolicy->leaves_policy_name;?>" class="form-control">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Policy Date from:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="date" name="PolicyDateFrom" value="<?=$leavesPolicy->policy_date_from?>"  class="form-control" disabled>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Policy Date till:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="date" name="PolicyDateTill" value="<?= $leavesPolicy->policy_date_till?>" class="form-control" disabled>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Full Day Deduction Rate:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="full_day_deduction_rate" class="form-control requiredField" name="full_day_deduction_rate" required disabled>
                                <option value="">select</option>
                                <option @if($leavesPolicy->fullday_deduction_rate == '1') {{ 'selected' }}@endif value="1">1 (Day)</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Half Day Deduction Rate:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="half_day_deduction_rate" class="form-control requiredField" name="half_day_deduction_rate" required disabled>
                                <option value="">select</option>
                                <option @if($leavesPolicy->halfday_deduction_rate == '0.5') {{ 'selected' }}@endif value="0.5">0.5 (Day)</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Per Hour Deduction Rate:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select name="per_hour_deduction_rate" class="form-control requiredField" name="per_hour_deduction_rate" required disabled>
                                <option value="">select</option>
                                <option @if($leavesPolicy->per_hour_deduction_rate == '0.25') {{ 'selected' }}@endif value="0.25"> 0.25 (Days)</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <table class="table table-sm mb-0 table-bordered table-striped table-hover">
                        <thead>
                        <th>Leaves Type</th>
                        <th>No. of Leaves</th>
                        <th><input type="button" class="btn-sm btn-primary" onclick="addMorePolicySection()"  value="Add More" /></th>
                        </thead>
                        <tbody id="append_area">
                        <?php $count=0; ?>
                        <?php $c=count($leavesData); ?>
                        @foreach($leavesData as $value)
                            <?php $count++; ?>
                            <tr class="get_rows" id=remove_area_<?=$count?>>
                                <td id="get_data" >
                                    <select name="leaves_type_id[]" class="form-control requiredField" required>
                                        <option value="">Select</option>
                                        @foreach($leavesType as $value2)
                                            <option @if($value->leave_type_id ==$value2->id) {{ 'selected' }}@endif value="{{ $value2->id }}">{{ HrHelper::getMasterTableValueById(0,'leave_type','leave_type_name',$value2->id) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="no_of_leaves[]" id="no_of_leaves[]" value="{{ $value->no_of_leaves }}" class="form-control requiredField" required />
                                </td>
                                <td><button onclick="removeEmployeeSection('<?= $count ?>')" type="button" class="btn btn-sm btn-danger"><i class="fas fa-trash fa"></i></button></td>
                            </tr>
                        @endforeach
                        <input type="hidden" id="count" value="<?php echo $c; ?>">
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
            <br>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                {{ Form::submit('Update', ['class' => 'btn btn-sm btn-success']) }}
            </div>
        </div>
        <?php echo Form::close();?>
    </div>
</div>
