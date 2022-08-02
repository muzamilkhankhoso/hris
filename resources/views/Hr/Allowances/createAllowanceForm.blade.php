<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = Input::get('m');
?>

@extends('layouts.default')
@section('content')


    <div class="page-wrapper">

        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->

        <?php echo Form::open(array('url' => 'had/addEmployeeAllowanceDetail','id'=>'employeeForm'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" value="<?=$m?>">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">Create Allowance Form</h4>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label pointer">Department</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select required class="form-control requiredField" name="department_id" id="department_id" onchange="getEmployee()">
                                <option value="0">Select Department</option>
                                @foreach($Department  as $key => $y)
                                    <option value="<?php echo $y->id ?>">
                                        {{ $y->department_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label pointer">Sub Department</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select required class="form-control requiredField" name="sub_department_id" id="sub_department_id" onchange="getEmployee()">
                                <option value="0">Select Department</option>
                                @foreach($SubDepartment  as $key => $y)
                                    <option value="<?php echo $y->id ?>">
                                        {{ $y->sub_department_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<label class="sf-label">Employee:</label>
								<span class="rflabelsteric"><strong>*</strong></span>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
								<span style="font-weight:bold;cursor:pointer;border:1px solid black;" class="badge badge-sm badge-success avtive_btn" onclick="getEmployee()">Active</span>
								<span style="font-weight:bold;cursor:pointer;border:1px solid black;" class="badge badge-sm badge-default exit_btn" onclick="getExitEmployees()">Exit</span>
								<input type="hidden" value="" id="emp_status" />
							</div>
                        </div>    
                            <select required class="form-control requiredField" name="emp_id" id="emp_id" >
                                <option value="0">-</option>
                            </select>
                            <div id="emp_loader_1"></div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label">Allowance Type:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <select style="width: 100%;" name="allowance_type[]" id="allowance_type" required value="" class="form-control requiredField" >
                                <option value="">Select Allowance Type</option>
                                @foreach($allowanceTypes  as $key => $y1)
                                    <option value="<?php echo $y1->id ?>">
                                        {{ $y1->allowance_type}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <br>
                    <div class="form_area">
                    <div class="row get_data">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Remarks:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <textarea name="remarks[]" id="remarks" class="form-control requiredField" required ></textarea>
                        </div>
                                      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                              <label class="sf-label">Amount:</label>
                                               <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="number" name="allowance_amount[]" id="allowance_amount" value="" class="form-control requiredField" required />
                                          </div>
                                           {{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">--}}
                                              {{--<br><br>--}}
                                            {{--<label>Once ?--}}
                                              {{--<input type="checkbox" name="once[]" id="once" value="1">--}}
                                            {{--</label>--}}

                                          {{--</div>--}}
                                             <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 once_area"></div>

                    </div>
                    </div>
                    <div class="employeeSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                            <!-- <input type="button" class="btn btn-sm btn-primary addMoreAllowanceSection" value="Add More Allowance Section" /> -->
                        </div>
                    </div>
                    <?php echo Form::close();?>

                </div>
                </div>

            </div>
        </div>


        <br>






    </div>




    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Right sidebar -->
    <!-- ============================================================== -->
    <!-- .right-sidebar -->
    <!-- ============================================================== -->
    <!-- End Right sidebart -->
    <!-- ============================================================== -->



@endsection

