@extends('layouts.default')
@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <!-- *************************************************************** -->
            <!-- Start First Cards -->
            <!-- *************************************************************** -->
            <div class="card-group">
                <div class="card border-right">
                    <div class="card-body">


                        <a href="{{ url('hr/viewEmployeeList?m='.Input::get('m').'#Innovative') }}" target="_blank">
                            <div  class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="huge total_employees text-dark mb-1 font-weight-medium"></h2>
                                        <span
                                                class="badge bg-primary font-12 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none">+18.33%</span>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Employees</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted hrSection"><i data-feather="user-plus"></i></span>
                                </div>

                            </div>
                        </a>
                    </div>
                </div>
                <div class="card border-right">
                    <div class="card-body">


                        <a href="{{ url('hr/viewEmployeeList?m='.Input::get('m').'#Innovative') }}" target="_blank">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <h2 class="huge total_employees_onboard text-dark mb-1 w-100 text-truncate font-weight-medium"><sup
                                                class="set-doller"></sup></h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Employees Onboard
                                    </h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted hrSection"><i data-feather="trending-up" ></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="card border-right">
                    <div class="card-body">
                        <a href="{{ url('hr/viewDepartmentList?m='.Input::get('m').'#Innovative') }}" target="_blank">
                            <div class="d-flex d-lg-flex d-md-block align-items-center" href="{{ url('hr/viewDepartmentList?m='.Input::get('m')) }}" target="_blank">
                                <div >
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="huge total_departments text-dark mb-1 font-weight-medium"></h2>
                                        <span
                                                class="badge bg-danger font-12 text-white font-weight-medium badge-pill ml-2 d-md-none d-lg-block">-18.33%</span>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Departments</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted hrSection"><i data-feather="globe" ></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">


                        <a href="{{ url('hr/viewEmployeeExitClearanceList?m='.Input::get('m').'#Innovative') }}" target="_blank">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <h2 class="huge total_employees_exit text-dark mb-1 font-weight-medium"></h2>
                                    <h6 class=" text-muted font-weight-normal mb-0 w-100 text-truncate">Employees Exit</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted hrSection"><i data-feather="user-minus" ></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>


            <div class="row">

                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center shadow">
                            <i class="fa fa-bell fa-fw" style="color:red"></i>
                            <b style="font-size: 17px"> Notifications Panel</b>
                        </div>
                        <br>
                        <div class="panel-body">
                            <div class="list-group" id="expiry_and_upcoming_alerts">
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-lg-6">
                    <div class="hide_for_alerts"></div>
                    <div class="panel panel-default alertPendingRequest">

                        <div class="panel-heading text-center shadow">
                            <i class="fa fa-bar-chart-o fa-fw" style="color:rgb(240,173,78)"></i> <b style="font-size: 17px">Pending Requests</b>
                        </div>
                        <br>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group" id="viewPendingRequests">
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                                <div class="text-center">
                                    <a class="list-group-item">
                                        <img width="30" class="text-right" src="<?= url('assets/images/icon-loader.gif') ?>">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


           </div>
            <!-- *************************************************************** -->
            <!-- End Top Leader Table -->
            <!-- *************************************************************** -->
        </div>
    </div>





@endsection