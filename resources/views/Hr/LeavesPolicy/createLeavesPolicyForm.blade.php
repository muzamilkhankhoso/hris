<?php

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">


        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintEmployeeList">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Create Leaves Policy Form</h4>
                            </div>


                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">


                            <?php echo Form::open(array('url' => 'had/addLeavesPolicyDetail','id'=>'employeeForm'));?>
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" id="company_id" value="<?=$m?>"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                <label class="sf-label">Leaves Policy Name:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" name="leaves_policy_name" class="form-control requiredField" id="leaves_policy_name" required value="" />
                                            </div>
                                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                                <div class="row">


                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="sf-label">Policy Date from:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="date" name="PolicyDateFrom" class="form-control requiredField" id="PolicyDatefrom" required value=""/>

                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="sf-label">Policy Date till:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="date" name="PolicyDateTill" class="form-control requiredField" id="PolicyDateTill" required value=""/>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Full Day Deduction Rate:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select readonly class="form-control requiredField" name="full_day_deduction_rate" id="full_day_deduction_rate" required>
                                                    <option selected value="1">1 (Day)</option>
                                                    <!-- <option selected value="1">1/1&nbsp;&nbsp;(First Quarter)</option>
                                                     <option value="0.5">1/2&nbsp;&nbsp;(Second Quarter)</option>
                                                     <option value="0.33333333333">1/3&nbsp;&nbsp;(Third Quarter)</option>
                                                     <option value="0.25">1/4&nbsp;&nbsp;(Fourth Quarter)</option>-->
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Half Day Deduction Rate:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select readonly class="form-control requiredField" id="half_day_deduction_rate" name="half_day_deduction_rate" required>
                                                    <option selected value="0.5">0.5 (Day)</option>
                                                    <!--   <option value="0.5">1/2&nbsp;&nbsp;(Second Quarter)</option>
                                                        <option value="0.33333333333">1/3&nbsp;&nbsp;(Third Quarter)</option>
                                                        <option value="0.25">1/4&nbsp;&nbsp;(Fourth Quarter)</option>-->
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Short Leave Deduction Rate:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select readonly class="form-control requiredField" id="per_hour_deduction_rate" name="per_hour_deduction_rate" required>

                                                    <option selected value="0.25">0.25 (Day)</option>
                                                    <!--<option value="0.1">1/1&nbsp;&nbsp;(Equivalent to 1 Hour)</option>
                                                    <option value="0.2">1/2&nbsp;&nbsp;(Equivalent to 2 Hour)</option>
                                                    <option value="0.3">1/3&nbsp;&nbsp;(Equivalent to 3 Hour)</option>
                                                    <option value="0.4">1/4&nbsp;&nbsp;(Equivalent to 4 Hour)</option>
                                                    <option value="0.5">1/5&nbsp;&nbsp;(Equivalent to 5 Hour)</option>
                                                    <option value="0.6">1/6&nbsp;&nbsp;(Equivalent to 6 Hour)</option>
                                                    <option value="0.7">1/7&nbsp;&nbsp;(Equivalent to 7 Hour)</option>
                                                    <option value="0.8">1/8&nbsp;&nbsp;(Equivalent to 8 Hour)</option>-->

                                                </select>
                                            </div>

                                        </div>
                                        <br>
                                        <span class="form_area">
                                        <span class="get_data">
                                             <?php $count=count($leaves_types); ?>
                                            <input type="hidden" id="count" value="{{ $count }}"/>
                                            <div class="row">
                                             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Leaves Type:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>

                                                <select name="leaves_type_id[]" id="leaves_type_id" class="form-control requiredField test" required>
                                                    <option value="">Select</option>
                                                    @foreach($leaves_types as $value)
                                                        <option value="{{ $value->id }}">{{ $value->leave_type_name }}</option>
                                                    @endforeach
                                                </select>
                                             </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">No. of Leaves:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input onkeyup="LeavesCount()" type="number" name="no_of_leaves[]" id="no_of_leaves" value="" class="form-control requiredField getLeaves" required />
                                            </div>
                                            </div>
                                        </span>
                                    </span>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="employeeSection"></div>
                                            </div>
                                        </div>

                                        <div class="row">&nbsp;</div>
                                        <div class="text-right">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><b>Total</b></div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><input readonly id="totalLeaves" name="totalLeaves" type="text" required class="form-control requiredField"/></div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="text-right">

                                        </div>

                                    </div>
                                </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                            <input type="button" class="btn btn-sm btn-primary addMorePolicySection" value="Add More Leaves Type" />
                                            {{ Form::submit('Submit', ['class' => 'btn btn-success btn-sm']) }}
                                            <button type="reset" id="reset" class="btn btn-primary btn-sm">Clear Form</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                        </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>



@endsection

