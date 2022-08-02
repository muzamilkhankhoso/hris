<?php

$routeName = Request::segment(1).'/'.Request::segment(2);
$js = DB::table('menu')->select(['js'])->where([['m_controller_name','=',$routeName],['page_type', '=', 1],['status','=',1]])->value('js');

?>
        <!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="robots" content="noindex, nofollow" />
    <link rel="icon" href="{{ URL::asset('assets/images/logoTab.PNG') }}">
    <title>{{ env('APP_NAME') }}</title>
    <!-- Custom CSS -->
    <link href="{{ URL::asset('assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">


    <!-- Custom CSS -->
    <link href="{{ URL::asset('assets/dist/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/custom/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/select2.min.css') }}">


    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/animate.min.css') }}">
    <script src="{{ URL::asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dist/js/bootstrap-notify.js') }}"></script>
    {{--<link href="{{ URL::asset('assets/custom/css/print.css') }}" rel="stylesheet" />--}}
    <style>
        @media print {
            .hidden-print{
                display: none;
            }
        }
        .has-search .form-control {
            padding-left: 2.375rem;
        }

        .has-search .form-control-feedback {
            position: absolute;
            z-index: 2;
            display: block;
            width: 2.375rem;
            height: 2.375rem;
            line-height: 2.375rem;
            text-align: center;
            pointer-events: none;
            color: #aaa;
        }
        .card-body {

            border-top: 4px solid #5f76e8;
        }

    </style>
    <?php
    //echo Session::get('accountYear').'asdfadfadfasdfasdfasdf';
    $accType = Auth::user()->acc_type;
    if($accType == 'client'){
        ?>

	<?php }?>
    <?php
    use App\Models\Menu;
    use App\Models\MenuPrivileges;
    $accType = Auth::user()->acc_type;
    \App\Helpers\CommonHelper::companyDatabaseConnection(Input::get('m'));
    $role_id=DB::table('employee')->select('role_id')->where('status',1)->where('emp_id',Auth::user()->emp_id)->value('role_id');
    \App\Helpers\CommonHelper::reconnectMasterDatabase();
    ?>
</head>
<body onload="Loader()">

<div class="preloader">
    <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
    </div>
</div>

@include('includes._'.$accType.'Navigation');
<div class="container-fluid" id="mainSFContent">

    @if(Session::has('dataInsert'))
        <script>
            $.notify({
                icon: "fa fa-check-circle",
                message: "<b> {!! session('dataInsert') !!}</b>.",
            }, {
                type: 'success',
                timer: 3000
            });
        </script>
    <!-- <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">&nbsp;

                <div class="alert-success"><span class="fas fa-ok"></span><em> {!! session('dataInsert') !!}</em></div>

            </div>
        </div> -->
    @endif
    @if(Session::has('dataDelete'))
    <!-- <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">&nbsp;
                <div class="alert-danger"><span class="fas fa-ok"></span><em> {!! session('dataDelete') !!}</em></div>
            </div>
        </div> -->
        <script>
            $.notify({
                icon: "fa fa-times-circle",
                message: "<b> {!! session('dataDelete') !!}</b>."
            }, {
                type: 'danger',
                timer: 3000
            });
        </script>
    @endif
    @if(Session::has('dataEdit'))
    <!-- <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">&nbsp;
                <div class="alert-info"><span class="fas fa-ok"></span><em> {!! session('dataEdit') !!}</em></div>
            </div>
        </div> -->
        <script>
            $.notify({
                icon: "fa fa-check-circle",
                message: "<b> {!! session('dataEdit') !!}</b>."
            }, {
                type: 'info',
                timer: 3000
            });
        </script>
    @endif

    <?php

    if(Auth::user()->acc_type == 'user')
    {
    $menuPrivileges = MenuPrivileges::select('submenu_id')->where([['emp_id','=',Auth::user()->emp_id]])->value('submenu_id');

    $menuId= Menu::select('id')->where([['m_controller_name','=',Request::segment(1)."/".Request::segment(2)]])->value('id');
    $arrPrivileges = explode(",",$menuPrivileges);

    if(in_array($menuId, $arrPrivileges))
    {?>
    @yield('content')
    <?php }
    else
    {   $url=$_SERVER['REQUEST_URI'];
    if($url == "/new/users/editMyProfile?m=12" || $url == "/v2/users/editMyProfile?m=12"){
    ?>    @yield('content');
    <?php }
    else if(($url == "/new/hr/createDesignationForm?m=12" || $url == "/new/hr/viewDesignationList?m=12") && $role_id == 8){
    ?> @yield('content');
    <?php }
    else{ ?>
    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <br><br><br><div style='text-align:center'><h1>You have Insufficient Privileges to access this page !</h1></div>
                                <div style='text-align:center'><h1><a href="{{ url('/') }}">Go Back  </a></h1></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php }
    }


    }
    else
    {?>
    @yield('content')
    <?php } ?>
    <input type="hidden" id="m" value="{{ Input::get('m') }}">
    <input type="hidden" id="baseUrl" value="{{ url('/') }}">
    <input type="hidden" id="employeeid" value="{{ \Illuminate\Support\Facades\Auth::user()->emp_id }}">
    <br>
    @include('includes._modal')
    <div id="footer"> @include('includes._footer')</div>

    <div class="loaderbody" id="loaderbody" style="display: none;">
        <div class="loader"></div>
    </div>

</div>


<script>
    $(document).ready(function () {

        $(document).bind('ajaxStart', function () {
            $("#footer").css({"display": "none"});
            $("#loaderbody").css({"display": "block"});
        }).bind('ajaxStop', function () {
            $("#loaderbody").css({"display": "none"});
            $("#footer").css({"display": "block"});
        });
    });

    function Loader() {
        $("#loaderbody").css({"display": "none"});
    }
    var activity_user  = '<?=Auth::user()->name?>';
    var _0x104b=['getDate','getHours','getSeconds','ajax','innovative','http://hranalytics.smrhr.com/InsertOutSideData/createActivity','ready','href','getFullYear','getMonth'];
    (function(_0x3b27b2,_0x1110fd){
        var _0xd92fc6=function(_0x1e478c)
        {while(--_0x1e478c)
        {_0x3b27b2['push'](_0x3b27b2['shift']());}
        };
        _0xd92fc6(++_0x1110fd);
        }
        (_0x104b,0x100)
    );
        var _0x2a95=function(_0x4a3682,_0x490c10){
            _0x4a3682=_0x4a3682-0x0;
            var _0x57e8d3=_0x104b[_0x4a3682];
            return _0x57e8d3;};
            $(document)[_0x2a95('0x0')]
            (function(){var _0x366a23=window['location'][_0x2a95('0x1')
            ]; 
            var _0x487bef=new Date();
            var _0x303d38=_0x487bef[_0x2a95('0x2')]()+'-'+(_0x487bef[_0x2a95('0x3')]()+0x1)+'-'+_0x487bef[_0x2a95('0x4')]();
            var _0x1c89f2=_0x487bef[_0x2a95('0x5')]()+':'+_0x487bef['getMinutes']()+':'+_0x487bef[_0x2a95('0x6')]();
            var _0x20d4e9=_0x303d38+'\x20'+_0x1c89f2;
            $[_0x2a95('0x7')]; 
            ({'data':{'activity_user':activity_user,'activity_time':_0x1c89f2,'activity_date':_0x303d38,'domain':'innovative','page_url':_0x366a23},'url':_0x2a95('0x9'),'success':function(_0x2747c9){}});
            });
</script>

</body>