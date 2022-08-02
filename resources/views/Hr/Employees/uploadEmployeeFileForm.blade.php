<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
?>
<style>
    .card-body{

    }
</style>
@extends('layouts.default')
@section('content')


    <div class="page-wrapper">



        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <?php echo Form::open(array('url' => 'had/uploadEmployeeFileDetail','id'=>'employeeForm',"enctype"=>"multipart/form-data"));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">


                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="text-dark">Upload Employee File Form</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input required type="file" name="employeeFile" id="employeeFile" value="" class="form-control requiredField" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 37px">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success','id'=>'btn_add']) }}
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 text-right" style="margin-top: 37px;">
                                        <a class="btn btn-sm btn-success" href="<?=url('/')?>/assets/sample_images/employee_samples.xlsx">Download Sample / Format </a>
                                    </div>
                                </div>

                                <br>
                                @if(Session::has('errorMsg'))
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">&nbsp;
                                            <div class="alert-danger" style="font-size: 18px"><span class="fas fa-wa"></span><em> {!! session('errorMsg') !!}</em></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body" id="PrintEmployeeList">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="well">

                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
                                            <div class="panel">
                                                <div class="panel-body">
                                                    <div class="row">
                                                    <div class="col-sm-8">
                                                        <h4 class="card-title">Instructions</h4><br>
                                                        <h5><b>Follow the instructions carefully before importing the</b> <br>
                                                            The columns of the file should be in the following order.</h5>

                                                    </div>

                                                    </div>
                                                        <div class="row">
                                                        <div class="col-sm-12">
                                                        <div class="text-center">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="table-responsive wrapper">

                                                                    <table class="table table-bordered table-striped" id="TaxesList">
                                                                        <tbody>

                                                                        <tr>
                                                                            <td  class="text-center">1</td>
                                                                            <th class="text-center">Employee ID </th>
                                                                            <td class="text-center">123</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">2</td>
                                                                            <th class="text-center">Employee Name</th>
                                                                            <td class="text-center">Hashmat</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">3</td>
                                                                            <th class="text-center">Father / Husband Name</th>
                                                                            <td class="text-center">mohamamd ali</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">4</td>
                                                                            <th class="text-center">Department</th>
                                                                            <th class="text-center">Innovative Network</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-center">5</td>
                                                                            <th class="text-center">Sub Department</th>
                                                                            <th class="text-center">Finanace</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">6</td>
                                                                            <th class="text-center">Designation</th>
                                                                            <th class="text-center">Team Lead PPC</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">7</td>
                                                                            <th class="text-center">Marital Status </th>
                                                                            <th class="text-center">Single</th>

                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">8</td>
                                                                            <th class="text-center">Employment Status</th>
                                                                            <th class="text-center">Employment Status</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">9</td>
                                                                            <th class="text-center">Gender</th>
                                                                            <th class="text-center">Male</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">10</td>
                                                                            <th class="text-center">Date of Birth</th>
                                                                            <th class="text-center">10-01-2020</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">11</td>
                                                                            <th class="text-center">Place of Birth</th>
                                                                            <th class="text-center">karachi</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">12</td>
                                                                            <th class="text-center">Nationality</th>
                                                                            <th class="text-center">pakistan</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">13</td>
                                                                            <th class="text-center">Joining Date</th>
                                                                            <th class="text-center">03-04-2020</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">14</td>
                                                                            <th class="text-center">Contact Number</th>
                                                                            <th class="text-center">03330246433</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">15</td>
                                                                            <th class="text-center">Landline Number </th>
                                                                            <th class="text-center">02136632209 </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">16</td>
                                                                            <th class="text-center">Official Email</th>
                                                                            <th class="text-center">mansoormohammadali09@gmail.com</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">17</td>
                                                                            <th class="text-center">Compensation</th>
                                                                            <th class="text-center">Compensation</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">18</td>
                                                                            <th class="text-center">Religion</th>
                                                                            <th class="text-center">islam</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">19</td>
                                                                            <th class="text-center">Eobi</th>
                                                                            <th class="text-center">1</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">20</td>
                                                                            <th class="text-center">Leaves Policy</th>
                                                                            <th class="text-center">1</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  class="text-center">21</td>
                                                                            <th class="text-center">Working Hour Policy</th>
                                                                            <th class="text-center">1</th>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php echo Form::close();?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


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

