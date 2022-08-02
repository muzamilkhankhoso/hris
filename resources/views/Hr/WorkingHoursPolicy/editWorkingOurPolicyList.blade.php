<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <?php echo Form::open(array('url' => 'had/editWorkingHoursPolicyDetail','id'=>'EOBIform'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="w_id" value="{{$editWorkingPolicyDetail->id}}">
                        <input type="hidden" name="m" value="{{Input::get('m')}}">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="hidden" name="workingHoursSection[]" class="form-control" id="workingHoursSection" value="1">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="sf-label">Working Hours Policy Name <span class="rflabelsteric"><strong>*</strong></span></label>

                                            <input type="text" name="working_hours_policy" class="form-control requiredField" id="working_hours_policy" required="" value="{{$editWorkingPolicyDetail->working_hours_policy}}" >

                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Start Working Hours Time (Hour) <span class="rflabelsteric"><strong>*</strong></span></label>

                                            <input type="time" name="start_working_hours_time" class="form-control requiredField" id="start_working_hours_time" required="" value="{{$editWorkingPolicyDetail->start_working_hours_time}}" >
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">End Working Hours Time (Hour) <span class="rflabelsteric"><strong>*</strong></span></label>

                                            <input type="time" name="end_working_hours_time" class="form-control requiredField" id="end_working_hours_time" required="" value="{{$editWorkingPolicyDetail->end_working_hours_time}}" >
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Half Day Time <br> (Hour) <span class="rflabelsteric"><strong>*</strong></span></label>

                                            <input type="number" name="half_day_time" class="form-control requiredField" id="half_day_time" required="" value="{{$editWorkingPolicyDetail->half_day_time}}" >

                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label class="sf-label">Working Hours Grace Time (Minutes)  <span class="rflabelsteric"><strong>*</strong></span></label>

                                            <input type="number" name="working_hours_grace_time" class="form-control requiredField" id="working_hours_grace_time" required="" min="0" value="{{$editWorkingPolicyDetail->working_hours_grace_time}}">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <input class="btn btn-sm btn-success" type="submit" value="Submit">
                                    <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                                </div>
                            </div>

                        </div>
                        <?php echo Form::close();?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>



