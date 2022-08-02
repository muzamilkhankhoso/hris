<?php
use App\Models\Menu;
use App\Models\SubDepartment;
use App\Models\Department;
use App\Models\EmployeeProjects;
$main_modules = explode(",",$MenuPrivileges[0]['main_modules']);
$submenu_ids  = explode(",",$MenuPrivileges[0]['submenu_id']);

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
                                <h4 class="card-title">Edit Role and Permission Detail</h4>
                            </div>
                        </div>
                        <hr>

                        <?php echo Form::open(array('url' => 'uad/editUserRoleDetail','id'=>'addRoleDetail'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" value="{{ Input::get('m') }}">
                        <input type="hidden" name="id" value="<?php echo $MenuPrivileges[0]['id']; ?>">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label pointer">Role Name</label>
                                        <input type="text" name="role_name" id="role_name" class="form-control" value="{{$MenuPrivileges[0]['role_name']}}">
                                    </div>

                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php
                                        $MainMenuTitles = DB::table('main_menu_title')->select(['id','main_menu_id'])->where([['menu_type','=',1],['title_id', '=', 'dashboard'],['status', '=', 1]])->groupBy('main_menu_id')->get();

                                        $counter = 1;
                                        foreach($MainMenuTitles as $row){ ?>

                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"> Dashboard
                                                    <input @if(in_array($row->id,$main_modules)) checked @endif style="float: right" type="checkbox" id="{{ $row->main_menu_id }}" onclick="showPrilvigesMenu('<?php echo $row->main_menu_id;?>')" id="<?php echo $row->main_menu_id;?>" name="main_modules[]" value="<?php echo $row->id;?>">
                                                </h3>
                                                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                                            </div>
                                            <div class="panel-body" id="Prilviges_<?php echo $row->main_menu_id;?>">
                                                <?php
                                                $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['menu_type','=',1],['status', '=', 1]])->orderBy('id', 'desc')->get();
                                                foreach($MainMenuTitlesSub as $row1){ ?>

                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label style="text-decoration: underline;color:royalblue;"><?php echo $row1->title; ?> :</label>
                                                        <input type="hidden" name="menu_title_<?php echo $row->id;?>[]" value="<?php echo $row1->title_id; ?>" />
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <p><b>Pages / Screens :</b></p>
                                                        <?php
                                                        $data = DB::table('menu')->select(['name','id'])->where('m_parent_code','=',$row1->id)->get();
                                                        foreach($data as $dataValue){
                                                        $data = explode(' ',$dataValue->name);
                                                        ?>
                                                        <ul class="nav">
                                                            <li class="pagesList">
                                                                <input @if(in_array($dataValue->id,$submenu_ids)) checked @endif class="{{ $row->main_menu_id.'_child' }}" name="sub_menu_<?php echo $row1->title_id; ?>[]" type="checkbox" value="<?=$dataValue->id?>">
                                                                <strong> <?php echo $dataValue->name;?></strong>&nbsp;&nbsp;
                                                            </li>
                                                            <br>
                                                        </ul>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <p><b>Default Dashboard</b></p>
                                                        <?php

                                                        $crud_permission[]='';
                                                        $crud_rights  = explode(",",$MenuPrivileges[0]['crud_rights']);


                                                        if(in_array('HR_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "HR";
                                                        endif;
                                                        if(in_array('User_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "User";
                                                        endif;
                                                        if(in_array('Finance_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "Finance";
                                                        endif;

                                                        $data = DB::table('menu')->select(['name','id'])->where('m_parent_code','=',$row1->id)->get();
                                                        foreach($data as $dataValue){
                                                        $data = explode(' ',$dataValue->name);
                                                        ?>
                                                        <input type="radio" @if(in_array($data[0],$crud_permission))   checked @endif class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="<?=$data[0]?>" /> <strong><?php echo $dataValue->name;?></strong>
                                                        <br>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="lineHeight">&nbsp;</div>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                                </div>

                                <div class="row">
                                    <?php
                                    $MainMenuTitles = DB::table('main_menu_title')->select(['id','main_menu_id'])->where([['menu_type','=',1],['status', '=', 1],['title_id', '!=', 'dashboard']])->groupBy('main_menu_id')->get();

                                    $counter = 1;
                                    foreach($MainMenuTitles as $row){
                                    ?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><?php echo $row->main_menu_id;?>
                                                    <input @if(in_array($row->id,$main_modules)) checked @endif style="float: right"  type="checkbox" onclick="showPrilvigesMenu('<?php echo $row->main_menu_id;?>')" id="<?php echo $row->main_menu_id;?>" name="main_modules[]" value="<?php echo $row->id;?>">
                                                </h3>
                                                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                                            </div>
                                            <div class="panel-body" id="Prilviges_<?php echo $row->main_menu_id;?>">
                                                <?php
                                                $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['menu_type','=',1],['status', '=', 1]])->orderBy('id', 'desc')->get();
                                                foreach($MainMenuTitlesSub as $row1){
                                                ?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label style="text-decoration: underline;color:royalblue;"><?php echo $row1->title; ?> :</label>
                                                        <input type="hidden" name="menu_title_<?php echo $row->id;?>[]" value="<?php echo $row1->title_id; ?>" />
                                                    </div>
                                                </div>
                                                <div class="row">    
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <p><b>Pages / Screens :</b></p>
                                                        <ul class="nav"> 
                                                        <?php
                                                        $data = DB::table('menu')->select(['name','id'])->where('m_parent_code','=',$row1->id)->get();
                                                        foreach($data as $dataValue){?>
                                                        <li class="pagesList">
                                                            <input @if(in_array($dataValue->id,$submenu_ids)) checked @endif class="{{$row->main_menu_id.'_child'}}" name="sub_menu_<?php echo $row1->title_id; ?>[]"  type="checkbox" value="<?=$dataValue->id?>">
                                                            <strong> <?php echo $dataValue->name;?></strong>&nbsp;&nbsp;
                                                        </li>
                                                        <?php } ?>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <?php
                                                        $crud_permission[]='';
                                                        $crud_rights  = explode(",",$MenuPrivileges[0]['crud_rights']);


                                                        if(in_array('view_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "view";
                                                        endif;
                                                        if(in_array('edit_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "edit";
                                                        endif;
                                                        if(in_array('repost_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "repost";
                                                        endif;
                                                        if(in_array('delete_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "delete";
                                                        endif;
                                                        if(in_array('print_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "print";
                                                        endif;
                                                        if(in_array('export_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "export";
                                                        endif;
                                                        if(in_array('approve_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "approve";
                                                        endif;
                                                        if(in_array('reject_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "reject";
                                                        endif;
                                                        ?>
                                                        <p><b>Actions :</b></p>
                                                        <ul class="privilegesList nav">
                                                            <li class="pagesList"><input @if(in_array('view',$crud_permission))   checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="view" /> <strong>View</strong>&nbsp;&nbsp;</li>
                                                            <li class="pagesList"><input @if(in_array('edit',$crud_permission))   checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="edit" /> <strong>Edit</strong>&nbsp;&nbsp;</li>
                                                            <li class="pagesList"><input @if(in_array('approve',$crud_permission))checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="approve" /> <strong>Approve</strong>&nbsp;&nbsp;</li>
                                                            <li class="pagesList"><input @if(in_array('reject',$crud_permission)) checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="reject" /> <strong>Reject</strong>&nbsp;&nbsp;</li>
                                                            <li class="pagesList"><input @if(in_array('repost',$crud_permission)) checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="repost" /> <strong>Repost</strong>&nbsp;&nbsp;</li>
                                                            <li class="pagesList"><input @if(in_array('delete',$crud_permission)) checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="delete" /> <strong>Delete</strong>&nbsp;&nbsp;</li>
                                                            <li class="pagesList"><input @if(in_array('print',$crud_permission))  checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="print" /> <strong>Print</strong>&nbsp;&nbsp;</li>
                                                            <li class="pagesList"><input @if(in_array('export',$crud_permission)) checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="export" /> <strong>Export</strong>&nbsp;&nbsp;</li>

                                                        </ul>
                                                        <?php $crud_permission[]=''; ?>
                                                    </div>
                                                </div>
                                                <div class="lineHeight">&nbsp;</div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                                </div>
                                <div class="row">
                                    <?php
                                    $MainMenuTitles = DB::table('main_menu_title')->select(['id','main_menu_id'])->where([['menu_type','=',2],['status', '=', 1]])->groupBy('main_menu_id')->get();


                                    $counter = 1;
                                    foreach($MainMenuTitles as $row){
                                    ?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><?php echo $row->main_menu_id." Master" ;?>
                                                    <input @if(in_array($row->id,$main_modules)) checked @endif style="float: right" type="checkbox" onclick="showPrilvigesMenu('<?php echo $row->main_menu_id;?>','master')" id="<?php echo $row->main_menu_id;?>_master" name="main_modules[]" value="<?php echo $row->id;?>">
                                                </h3>
                                                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                                            </div>
                                            <div class="panel-body" id="Prilviges_master_<?php echo $row->main_menu_id;?>">
                                                <?php
                                                $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['status', '=', 1],['menu_type','=',2]])->orderBy('id', 'desc')->get();
                                                foreach($MainMenuTitlesSub as $row1){
                                                ?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label style="text-decoration: underline;color:royalblue;"><?php echo $row1->title; ?> :</label>
                                                        <input type="hidden" name="menu_title_<?php echo $row->id;?>[]" value="<?php echo $row1->title_id; ?>" />
                                                    </div>
                                                </div>  
                                                <div class="row">  
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <p><b>Pages / Screens :</b></p>
                                                        <ul class="nav">
                                                        <?php
                                                        $data = DB::table('menu')->select(['name','id'])->where('m_parent_code','=',$row1->id)->get();
                                                        foreach($data as $dataValue){?>
                                                        <li>
                                                            <input @if(in_array($dataValue->id,$submenu_ids)) checked @endif class="{{$row->main_menu_id.'_child'}}" name="sub_menu_<?php echo $row1->title_id; ?>[]" type="checkbox" value="<?=$dataValue->id?>">
                                                            <strong> <?php echo $dataValue->name;?></strong>&nbsp;&nbsp;
                                                        </li>
                                                        <?php } ?>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <?php
                                                        $crud_permission[]='';
                                                        $crud_rights  = explode(",",$MenuPrivileges[0]['crud_rights']);

                                                        if(in_array('edit_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "edit";
                                                        endif;
                                                        if(in_array('repost_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "repost";
                                                        endif;
                                                        if(in_array('delete_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "delete";
                                                        endif;
                                                        if(in_array('print_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "print";
                                                        endif;
                                                        if(in_array('export_'.$row1->title_id,$crud_rights)):
                                                            $crud_permission[] = "export";
                                                        endif;

                                                        ?>
                                                        <p><b>Actions :</b></p>
                                                        <ul class="privilegesList nav">
                                                            <li class="pagesList"><input type="checkbox" @if(in_array('edit',$crud_permission)) checked @endif class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="edit" /> <strong>Edit</strong>&nbsp;&nbsp;</li>
                                                            <li class="pagesList"><input type="checkbox" @if(in_array('delete',$crud_permission)) checked @endif class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="delete" /> <strong>Delete</strong>&nbsp;&nbsp;</li>
                                                            <li class="pagesList"><input type="checkbox" @if(in_array('print',$crud_permission)) checked @endif class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="print" /> <strong>Print</strong>&nbsp;&nbsp;</li>
                                                            <li class="pagesList"><input type="checkbox" @if(in_array('export',$crud_permission)) checked @endif class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="export" /> <strong>Export</strong>&nbsp;&nbsp;</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="lineHeight">&nbsp;</div>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                </div>
                            </div>
                        </div>

                        <div class="text-right"><span class="regionError" style="font-size:18px;"></span></div>
                        <?php echo Form::close();?>
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

