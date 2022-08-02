
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
use App\Models\Role;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')
@section('content')


    <div class="page-wrapper">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-12 text-right">

            </div>
        </div>

        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">User Accounts List</h4>
                            </div>
                            <div class="col-sm-4 text-right">

                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintUserAccountsList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('UserAccountsList','','1')?>
                                @endif

                            </div>

                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-8"></div>
                            <div class="col-sm-4 text-right">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="emp_id_search" name="emp_id1" class="form-control" placeholder="Search..." />
                                    <input type="hidden" id="company_id" value="<?= $m ?>">
                                </div>
                            </div>
                        </div>

                        <span id="PrintUserAccountsList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                            <div class="table-responsive">
                                    <table class="table table-sm mb-0 table-bordered table-striped table-hover" id="UserAccountsList">
                                        <thead>
                                        <tr>
                                            <th scope="col">S.No</th>
                                            <th scope="col">Emp Id</th>
                                            <th scope="col">Emp Name</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Role</th>

                                            <th class="hidden-print" scope="col" >Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php if(count($users) != '0'){

                                            ?>
                                        <?php $counter = 1;?>
                                        @foreach($users as $key => $y)

                                            <?php
                                                CommonHelper::companyDatabaseConnection($m);
                                                $emp_role = Employee::where([['emp_id','=',$y->emp_id],['status','=', '1']])->first();
                                                CommonHelper::reconnectMasterDatabase()
                                            ?>

                                            <tr>
													<td class="text-center counterId" id="<?php echo $counter;?>">
                                                        <span style="color: white;" class="badge badge-pill badge-secondary"><?php echo $counter++;?></span>
													</td>
													<td class="text-center">{{ $y->emp_id}}</td>
													<td>{{ $y->name}}</td>
													<td>{{ $y->username}}</td>
													<td>{{ $y->email}}</td>

                                                    <?php $role_name=Role::where([['id','=',$emp_role['role_id']],['status','=', '1']])->first(); ?>
                                                    <td>{{ $role_name->role_name ?? "--"}}</td>



													<td class="text-center hidden-print">
														 <div class="dropdown">
															<button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
																<i data-feather="chevron-down"
                                                                   class="svg-icon"></i></button>
															<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
																@if(in_array('view', $operation_rights))
                                                                    <li role="presentation" class="actionsLink" onclick="showDetailModelFourParamerter('hdc/viewUserAccountDetail','<?php echo $y->id;?>','View User Account Detail','<?php echo $m; ?>')">
																		<a class="delete-modal btn">
																			View
																		</a>
																	</li>
                                                                @endif
                                                                @if(in_array('edit', $operation_rights))
                                                                    <li role="presentation" class="actionsLink">
																		<a  class="delete-modal btn" onclick="showDetailModelFourParamerter('hr/editUserAccountDetailForm','<?php echo $y->id;?>','Edit User Account Detail','<?php echo $m; ?>')">
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
                                                                        <li role="presentation" class="actionsLink" onclick="deleteCompanyMasterTableRecord('/deleteUserAccount','<?php echo $y->id ?>','users','<?php echo $m ?>','')">
																			<a class="delete-modal btn" >
																				Delete
																			</a>
																		</li>
                                                                    @endif
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

