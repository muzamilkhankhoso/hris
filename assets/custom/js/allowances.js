var m=$('#m').val();
var baseUrl=$('#baseUrl').val();

function getAllEmployeesData(){
    $('.all_btn').removeClass('badge-default');
    $('.all_btn').addClass('badge-primary');
    $('.avtive_btn').removeClass('badge-success');
    $('.avtive_btn').addClass('badge-default');
    $('.exit_btn').removeClass('badge-danger');
    $('.exit_btn').addClass('badge-default');
    $('#emp_status').val('all');
    viewAllowancesList();
}    

function getActiveEmployeesData(){
    $('.all_btn').removeClass('badge-primary');
    $('.all_btn').addClass('badge-default');
    $('.avtive_btn').removeClass('badge-default');
    $('.avtive_btn').addClass('badge-success ');
    $('.exit_btn').removeClass('badge-danger');
    $('.exit_btn').addClass('badge-default');
    $('#emp_status').val('active');
    viewAllowancesList();
}

function getExitEmployeesData(){
    $('.all_btn').removeClass('badge-primary');
    $('.all_btn').addClass('badge-default');
    $('.avtive_btn').removeClass('badge-success');
    $('.avtive_btn').addClass('badge-default');
    $('.exit_btn').removeClass('badge-default');
    $('.exit_btn').addClass('badge-danger');
    $('#emp_status').val('exit');
    viewAllowancesList();
}   

function viewAllowancesList(){
$('#loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
var emp_status = $('#emp_status').val();

//$("#employeeAttendenceReportSection").css({"display": "block"});
    
    var data = {emp_status:emp_status,m:m}
   

    $.ajax({
        url: baseUrl+'/hdc/viewAllowanceListData',
        type: "GET",
        data:data,
        success:function(data) {
            $('#loader').html('');
            $('#PrintAllownceList').html('');
            $('#PrintAllownceList').append(data);
        }
    });
}



$(document).ready(function() {
    $('#emp_id').select2();

    $('#sub_department_id').select2();
    $("#department_id").select2();
    $("#allowance_type").select2();

    var table = $('#AllowanceList').DataTable({
        "dom": "t",
        "bPaginate" : false,
        "bLengthChange" : true,
        "bSort" : false,
        "bInfo" : false,
        "bAutoWidth" : false

    });

    $('#emp_id_search').keyup( function() {
        table.search(this.value).draw();
    });



});
$(document).ready(function() {
    // Wait for the DOM to be ready



    $('.addMoreAllowanceSection').click(function (e){
        var form_rows_count = $(".get_data").length;
        var data = $('.form_area').html();
        $('.employeeSection').append('<div class="row" id="remove_area_'+form_rows_count+'"><div class="row"><button onclick="removeEmployeeSection('+form_rows_count+')" type="button" class="btn btn-sm btn-danger">Remove</button></div>'+data+'</div>');

        // Wait for the DOM to be ready


    });


    $(document).on('change', '#allowance_type', function() {
        if($(this).val()==5 || $(this).val()==6){
            $(".once_area").html('<label>Month-Year</label><input required type="month" class="form-control requiredField" name="month_year[]"><input type="hidden" name="once[]" id="once" value="1"> ')

        }
        else{
            $(".once_area").html('')
        }
    });


    // $('#once').click(function (e){
    //
    //     if($("#once").is(':checked')){
    //
    //         $(".once_area").html('<label>Month-Year</label><input type="month" class="form-control" name="month_year[]">')
    //     }
    //
    //     else{
    //         $(".once_area").html('')
    //     }
    //
    // });



});