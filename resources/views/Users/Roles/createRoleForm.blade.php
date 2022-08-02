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
                    <div class="card-body" id="PrintEmployeeList">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Add Role and Permission Detail</h4>
                            </div>
                        </div>
                        <hr>

                        <br>
                        <?php echo Form::open(array('url' => 'uad/addRoleDetail','id'=>'addRoleDetail'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" id="company_id" value="{{ Input::get('m') }}">
                        <div class="row">

                            <div class="col-lg-6 col-md-46 col-sm-6 col-xs-12">
                                <label class="sf-label pointer">Role Name</label>
                                <input type="text" name="role_name" id="role_name" class="form-control">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label pointer">Hide Confidentiality</label>
                                <br>
                                <input type="checkbox" checked name="hide_confidentiality" id="hide_confidentiality" >
                            </div>
                        </div>

                        <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 panel panel-primary">
                                        <?php
                                        $MainMenuTitles = DB::table('main_menu_title')->select(['id','main_menu_id'])->where([['menu_type','=',1],['title_id', '=', 'dashboard'],['status', '=', 1]])->groupBy('main_menu_id')->get();

                                        $counter = 1;
                                        foreach($MainMenuTitles as $row){ ?>
                                            <div class="panel-heading">
                                                <h3 class="panel-title"> Dashboard
                                                    <input style="float: right" checked type="checkbox" onclick="showPrilvigesMenu('<?php echo $row->main_menu_id;?>')" id="<?php echo $row->main_menu_id;?>" name="main_modules[]" value="<?php echo $row->id;?>">
                                                </h3>
                                                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                                            </div>
                                            <div class="panel-body" id="Prilviges_<?php echo $row->main_menu_id;?>">
                                                <?php
                                                $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['menu_type','=',1],['status', '=', 1]])->orderBy('id', 'desc')->get();

                                                foreach($MainMenuTitlesSub as $row1){ ?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-2 p-3 mb-2 bg-success text-light">
                                                        <label class=""><?php echo  $row1->title; ?> :</label>
                                                        <input type="checkbox" name="submenu_<?php echo $row->id ?>" id="submenu_<?php echo $row->id ?>">
                                                        <input type="hidden" name="menu_title_<?php echo $row->id;?>[]" value="<?php echo $row1->title_id; ?>" />
                                                    </div>
                                                </div>
                                                    <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <p><b>Pages / Screens :</b></p>
                                                        <?php
                                                        $data = DB::table('menu')->select(['name','id'])->where('m_parent_code','=',$row1->id)->get();
                                                        foreach($data as $dataValue){
                                                        $data = explode(' ',$dataValue->name);
                                                        ?>
                                                        <ul class="nav">
                                                            <li class="pagesList">
                                                                <input name="sub_menu_<?php echo $row1->title_id; ?>[]" type="checkbox" value="<?=$dataValue->id?>">
                                                                <strong> <?php echo $dataValue->name;?></strong>&nbsp;&nbsp;
                                                            </li>
                                                            
                                                        </ul>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <p><b>Default Dashboard</b></p>
                                                        <?php
                                                        $data = DB::table('menu')->select(['name','id'])->where('m_parent_code','=',$row1->id)->get();
                                                        foreach($data as $dataValue){
                                                        $data = explode(' ',$dataValue->name);
                                                        ?>
                                                        <input type="radio" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="<?=$data[0]?>" /> <strong><?php echo $dataValue->name;?></strong>
                                                        <br>
                                                        <?php } ?>
                                                    
                                                    </div>
                                                    <br>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                            </div>
                               <br>                             
                            <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php
                                        $MainMenuTitles = DB::table('main_menu_title')->select(['id','main_menu_id'])->where([['menu_type','=',1],['status', '=', 1],['title_id', '!=', 'dashboard']])->groupBy('main_menu_id')->get();

                                        $counter = 1;
                                        foreach($MainMenuTitles as $row){ ?>
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><?php echo $row->main_menu_id;?>
                                                    <input style="float: right"  type="checkbox" onclick="showPrilvigesMenu('<?php echo $row->main_menu_id;?>')" id="<?php echo $row->main_menu_id;?>" name="main_modules[]" value="<?php echo $row->id;?>">
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
                                                </div>                                                    
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <p><b>Pages / Screens :</b></p>
                                                            <ul class="nav">
                                                            <?php
                                                            $data = DB::table('menu')->select(['name','id'])->where('m_parent_code','=',$row1->id)->get();
                                                            foreach($data as $dataValue){?>
                                                            <li class="pagesList">
                                                                <input name="sub_menu_<?php echo $row1->title_id; ?>[]"  type="checkbox" value="<?=$dataValue->id?>">
                                                                <strong> <?php echo $dataValue->name;?></strong>&nbsp;&nbsp;
                                                            </li>
                                                            <?php } ?>
                                                            </ul>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <p><b>Actions :</b></p>
                                                            <ul class="privilegesList nav">
                                                                <li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="view" /> <strong>View</strong>&nbsp;&nbsp;</li>
                                                                <li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="edit" /> <strong>Edit</strong>&nbsp;&nbsp;</li>
                                                                <li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="approve" /> <strong>Approve</strong>&nbsp;&nbsp;</li>
                                                                <li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="reject" /> <strong>Reject</strong>&nbsp;&nbsp;</li>
                                                                <li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="repost" /> <strong>Repost</strong>&nbsp;&nbsp;</li>
                                                                <li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="delete" /> <strong>Delete</strong>&nbsp;&nbsp;</li>
                                                                <li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="print" /> <strong>Print</strong>&nbsp;&nbsp;</li>
                                                                <li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="export" /> <strong>Export</strong>&nbsp;&nbsp;</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                
                                                <br>
                                                <?php }?>
                                            </div>
                                            <?php }?>
                                        </div>

                                    </div>
                                    <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <?php
                                    $MainMenuTitles = DB::table('main_menu_title')->select(['id','main_menu_id', 'menu_type'])->where([['menu_type','=',2],['status', '=', 1]])->groupBy('main_menu_id')->get();

                                    $counter = 1;
                                    foreach($MainMenuTitles as $row){ ?>

                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><?php echo $row->main_menu_id;?>
                                                    <input style="float: right" checked type="checkbox" onclick="showPrilvigesMenu('<?php echo $row->main_menu_id;?>','master')" id="<?php echo $row->main_menu_id;?>_master" name="main_modules[]" value="<?php echo $row->id;?>">
                                                </h3>
                                                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                                                <div class="panel-body" id="Prilviges_master_<?php echo $row->main_menu_id;?>">
                                                    <?php
                                                    $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['menu_type','=',2],['status', '=', 1]])->orderBy('id', 'desc')->get();
                                                    foreach($MainMenuTitlesSub as $row1){ ?>

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
                                                            $data = DB::table('menu')->select(['name','id'])->where([['m_parent_code','=',$row1->id]])->get();

                                                            foreach($data as $dataValue){?>
                                                            <li>
                                                                <input name="sub_menu_<?php echo $row1->title_id; ?>[]" checked type="checkbox" value="<?=$dataValue->id?>">
                                                                <strong> <?php echo $dataValue->name;?></strong>&nbsp;&nbsp;
                                                            </li>
                                                            <?php } ?>
                                                            </ul>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <p><b>Actions :</b></p>
                                                            <ul class="privilegesList nav">
                                                                <li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="edit" /> <strong>Edit</strong>&nbsp;&nbsp;</li>
                                                                <li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="delete" /> <strong>Delete</strong>&nbsp;&nbsp;</li>
                                                                <li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="print" /> <strong>Print</strong>&nbsp;&nbsp;</li>
                                                                <li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="export" /> <strong>Export</strong>&nbsp;&nbsp;</li>
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

                            </div>
                                <br>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                </div>
                                </div>
                            

                                
                            </div>
                        </div>
                        













                        <?php echo Form::close();?>
                        <div class="text-right"><span class="regionError" style="font-size:18px;"></span></div>

                    </div>

                </div>
            </div>
        </div>

    </div>








@endsection

