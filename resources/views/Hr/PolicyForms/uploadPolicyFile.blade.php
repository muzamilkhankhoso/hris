<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
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
                                <h4 class="card-title">Upload Policies / Forms File</h4>
                            </div>


                        </div>
                        <hr>
                        <div class="row">
                            {{ Form::open(array('url' => 'had/uploadPolicyFileDetail',"enctype"=>"multipart/form-data")) }}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="m" value="{{ Input::get('m') }}">
                            <input type="hidden" name="HrSection[]" id="HrSection" value="1">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label>Category</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select name="category_id" id="category_id" class="form-control requiredField">
                                                    <option value="">Select Category</option>
                                                    <option value="1">Policy</option>
                                                    <option value="2">Forms</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label>Policies / Forms Title</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" name="title" id="title" value="" class="form-control requiredField" />
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label>File</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="file" name="policy_file[]" id="policy_file" value="" class="form-control requiredField" />
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right" style="margin-top: 30px">
                                                {{ Form::submit('Submit', ['class' => 'btn btn-sm btn-success']) }}
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
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

