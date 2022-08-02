<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Employee;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
//$crud_permission = CommonHelper::crudRights();?>
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
                                <h4 class="card-title">View Role and Permission List</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('EmployeeList','','1')?>
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

                        <span id="PrintAllownceList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
                                        <div class="table-responsive">
										<table class="table table-sm mb-0 table-bordered table-striped table-hover" id="AllownceList">
											<thead>
											<th class="text-center">S.No</th>
											<th class="">Role Name</th>
											<th class="text-center hidden-print">Action</th>
											</thead>
											<tbody>
                                            <?php $counter=1; ?>

                                            @foreach($MenuPrivileges as $value)
                                                <tr>
													<td class="text-center">{{ $counter++ }}</td>
													<td class="">{{$value->role_name}}</td>
													<td class="text-center">
                                                         <div class="dropdown">
															<button class="btn btn-primary btn-rounded dropdown-toggle btn-sm" type="button" id="menu1" data-toggle="dropdown">
																<i data-feather="chevron-down"
                                                                   class="svg-icon"></i></button>
															<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">

                                                                    <li role="presentation" class="actionsLink" >
																		<a href="<?= url("/users/viewEmployeePrivileges/{$value->id}?pageType=viewlist&&parentCode=27&&m={$m}#Innovative")?>" class="delete-modal btn">
																			View
																		</a>
																	</li>


                                                                    <li role="presentation" class="actionsLink" onclick="deleteRowMasterTable('<?php echo $value->id ?>','roles')">
																		<a  class="delete-modal btn" >
																			Delete
																		</a>
																	</li>
                                                            </ul>
														</div>

													</td>
												</tr>
                                            @endforeach
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

