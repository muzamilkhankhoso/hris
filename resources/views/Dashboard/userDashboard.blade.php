@extends('layouts.default')
@section('content')
    <?php
    $m = Input::get('m');
    use App\Helpers\HrHelper;
    ?>

    <div class="page-wrapper">

            <!-- *************************************************************** -->
            <!-- Start First Cards -->
            <!-- *************************************************************** -->
        <div class="col-12" id="dashboardUser">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="card-title">User Dashboard</h4>
                        </div>

                    </div>
                    <hr>
                    <?php HrHelper::getAuthorizedInputFields();?>

                    <div class="row" id="run_loaders">
                    </div>
                </div>
            </div>
        </div>


        <div id="userDashboard" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="showUserDashboard">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>

        $(document).ready(function(){

            $("#loaderbody").css({"display": "block"});
            $("#footer").css({"display": "none"});
            $('#emp_id2').select2();
            filterUserDashBoard($("#emp_id2").val())
            if(sessionStorage.getItem('successMsg')){
                $.notify({
                    icon: "fa fa-check-circle",
                    message: "<b>'"+sessionStorage.getItem('successMsg')+"'</b>.",
                }, {
                    type: 'success',
                    timer: 3000
                });
            }
            sessionStorage.clear();
        });

        function filterUserDashBoard(value){
            $("#userDashboard").css({"display": "none"});
            var emp_id = value;
            var m = '<?php echo $m ?>';
            var url = '<?php echo url("/") ?>/ddc/filterUserDashboard';
            $('#run_loaders').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');
            $.ajax({
                type:'GET',
                url:url,
                data:{emp_id:emp_id,m:m,'filter':'user'},
                success:function(res){
                    $("#userDashboard").css({"display": "block"});
                    $("#showUserDashboard").html(res);
                    $('#run_loaders').html('');
                }
            });

        }
    </script>

@endsection