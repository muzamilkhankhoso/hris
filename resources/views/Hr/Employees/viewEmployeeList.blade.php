
<?php

$accType = Auth::user()->acc_type;
/*if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}*/
//$parentCode = $_GET['parentCode'];
$m = Input::get('m');

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use App\Models\EmployeePromotion;


$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')
@section('content')


    <div class="page-wrapper">

        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Employee List</h4>
                            </div>
                            <div class="col-sm-4 text-right">

                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('EmployeeList','','1')?>
                                @endif
                                <div class="row" style="margin-top: 10px;">

                                    <div class="col-sm-6"></div>
                                    <div class="col-sm-6 text-right">
                                        <li class="nav-item d-none d-md-block">
                                            <a class="nav-link" href="javascript:void(0)">
                                                <div class="customize-input">
                                                    {{--onchange="getEmployeesList(this.value)"--}}
                                                    <select id="activeEmployees"
                                                            class="custom-select form-control bg-white custom-radius custom-shadow border-0 text-center" style="cursor: pointer">
                                                        <option value="e" selected >All  ({{ count($employees) }})</option>
                                                        <option value="&nbsp;Active&nbsp;">Active   ({{ count($employees->where('status',1)) }}) </option>
                                                        <option value="InActive">InActive ({{ count($employees->where('status',4)) }})  </option>
                                                        <option value="Exit">Exit  ({{ count($employees->where('status',3)) }})</option>
                                                    </select>
                                                </div>
                                            </a>
                                        </li>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-6"></div>

                            <div class="col-sm-4 text-right">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="emp_id_search" name="emp_id1" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="<?= $m ?>">
                                </div>
                            </div>
                        </div>

                        <span id="PrintEmployeeList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                            <div class="table-responsive">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="EmployeeList">
                                        <thead>
                                        <tr class="text-center">
                                            <th scope="col">S.No</th>
                                            <th scope="col">ID</th>
                                            <th scope="col">Emp Name</th>
                                            <th scope="col">Dep/Sub Dep</th>
                                            <th scope="col">Designation</th>
                                            <th scope="col">Manager</th>
                                            <th scope="col">DOB</th>
                                            <th scope="col">Join Date</th>
                                            <th scope="col">C.No</th>
                                            <th scope="col">CNIC</th>
                                            <th scope="col">Salary</th>
                                            <th scope="col">Status</th>
                                            <th id="hide-table-row" class="hide-table hidden-print" scope="col" >Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php if(count($employees) != '0'){ ?>
                                        <?php $counter = 1;?>
                                        @foreach($employees as $key => $y)
                                            <tr>
													<td class="text-center counterId" id="<?php echo $counter;?>">
                                                        <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
													</td>
													<td class="text-center">{{ $y->emp_id}}</td>
													<td class="text-center">{{ $y->emp_name }}</td>
													<td class="text-center">
                                                       <?php
                                                        if($y->emp_department_id != ''){
                                                            echo  HrHelper::getMasterTableValueById(Input::get('m'),' department','department_name',$y->emp_department_id);
                                                        } else{
                                                            echo '--';
                                                        }
                                                        ?>
                                                        /
                                                        <small>{{ HrHelper::getMasterTableValueById(Input::get('m'),'sub_department','sub_department_name',$y->emp_sub_department_id)}}</small>
                                                    </td>
                                                <td class="text-center">
                                                    <?php
                                                    CommonHelper::companyDatabaseConnection($m);
                                                    $promoted_designation = EmployeePromotion::select('designation_id','emp_id')->where([['emp_id','=',$y->emp_id],['status','=',1]])->orderBy('id', 'desc');
                                                    if($promoted_designation->count() > 0):
                                                        $emp_designation_id = $promoted_designation->value('designation_id');
                                                        echo HrHelper::getMasterTableValueByIdAndColumn($m, 'designation', 'designation_name', $emp_designation_id, 'id');
                                                    else:
                                                        echo HrHelper::getMasterTableValueByIdAndColumn($m, 'designation', 'designation_name', $y->designation_id, 'id');
                                                    endif;
                                                    CommonHelper::reconnectMasterDatabase();
                                                    ?>
                                                </td>
                                                    <?php CommonHelper::companyDatabaseConnection($m); ?>
                                                    <td class="text-center">{{ DB::table('employee')->select('emp_name')->where('status',1)->where('emp_id',$y->reporting_manager)->value('emp_name') ?? '--' }}</td>
													<?php CommonHelper::reconnectMasterDatabase(); ?>
                                                    <td class="text-center">{{ HrHelper::date_format($y->emp_date_of_birth) }}</td>
													<td class="text-center">{{ HrHelper::date_format($y->emp_joining_date) }}</td>
													<td class="text-center">{{ $y->emp_contact_no }}</td>
													<td class="text-center">{{ $y->emp_cnic}}</td>
                                                <?php
                                                CommonHelper::companyDatabaseConnection($m);
                                                $promoted_salary = EmployeePromotion::select('salary','emp_id')->where([['emp_id','=',$y->emp_id],['status','=',1]])->orderBy('id', 'desc');
                                                if($promoted_salary->count() > 0):
                                                    $emp_salary = $promoted_salary->value('salary');
                                                else:
                                                    $emp_salary = $y->emp_salary;
                                                endif;

                                                CommonHelper::reconnectMasterDatabase();
                                                ?>
                                                <td class="text-right">
                                                         @if(HrHelper::hideConfidentiality(Input::get('m')) == 'no')
                                                        <?php echo number_format($emp_salary,0);?>
                                                    @else
                                                        ******
                                                    @endif

                                                    </td>


                                                <td class="text-center">
                                                    <?php
                                                    CommonHelper::companyDatabaseConnection($m);
                                                        $employee_exit = DB::table('employee_exit')
                                                       ->where([['status','=', 1],['emp_id','=', $y->emp_id]])
                                                        ->count();
                                                    CommonHelper::reconnectMasterDatabase();    
                                                    ?>
                                                    @if($y->status==1 && $employee_exit > 0)
                                                    {{ HrHelper::getStatusLabel(6)}}
                                                    @else
                                                    {{ HrHelper::getStatusLabel($y->status)}}
                                                    @endif
                                                </td>
													<td id="hide-table-row" class="hide-table text-center hidden-print">
														 <div class="dropdown">
															<button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
																<i data-feather="chevron-down"
                                                                   class="svg-icon"></i></button>
															<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
																@if(in_array('view', $operation_rights))
                                                                    <li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hdc/viewEmployeeDetail','<?php echo $y->id;?>','View Employee Detail','<?php echo $m; ?>')">
																		<a class="delete-modal btn">
																			View
																		</a>
																	</li>
                                                                @endif
                                                                @if(in_array('edit', $operation_rights))
                                                                    <li role="presentation" class="actionsLink">
																		<a  class="delete-modal btn" href="<?= url("/hr/editEmployeeDetailForm/{$y->id}/{$m}?pageType=viewlist&&parentCode=27&m={$m}#Innovative")?>">
																			Edit
																		</a>
																	</li>
                                                                @endif
                                                                @if(in_array('repost', $operation_rights))
                                                                    @if($y->status == 2)
                                                                        <li role="presentation" class="actionsLink" onclick="repostCompanyTableRecord('<?php echo $m ?>','<?php echo $y->id ?>','employee')">
																			<a class="delete-modal btn">
																				Repost
																			</a>
																		</li>
                                                                    @endif
                                                                @endif
                                                                @if(in_array('delete', $operation_rights))
                                                                    @if($y->status == 1)
                                                                        <li role="presentation" class="actionsLink" onclick="deleteEmployee('<?php echo $m ?>','<?php echo $y->id ?>','employee','<?php echo $y->emp_id ?>')">
																			<a class="delete-modal btn" >
																				Delete
																			</a>
																		</li>
                                                                    @endif
                                                                @endif
                                                                @if($y->status == 4 || $y->status == 3)
                                                                    <li role="presentation" class="actionsLink" onclick="restoreEmployee('<?php echo $m ?>','<?php echo $y->id ?>','employee')">
																	   <a class="delete-modal btn" >
																		   Active
																		</a>
																	</li>
                                                                @else
                                                                    <li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hmfal/makeFormEmployeeInActive','<?php echo $y->id;?>','View Employee Detail','<?php echo $m; ?>')">
																		<a class="delete-modal btn" >
																			InActive
																		</a>
																	</li>
                                                                @endif

															</ul>
														</div>
													</td>
												</tr>

                                        @endforeach
                                        <?php } else{ ?>
                                        <tr>
											 <td colspan="13" class="text-danger text-center"><h3 class="text-danger"><strong>No Record Found</strong></h3></td>
											</tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>

                                </div>
                                </span>
                        <div class="text-center ajax-loader"></div>
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
        <!-- End Right sidebart -->
        <!-- ============================================================== -->
    </div>










@endsection

