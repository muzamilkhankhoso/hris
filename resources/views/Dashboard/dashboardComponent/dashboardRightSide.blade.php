<div id="notify_area1" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><br>

    @if(count($upcoming_birthdays_detail) > 0)
        <p class="notify">Upcoming Birthday's</p>
        <hr style="border-top:1px solid green"/>
        @foreach($upcoming_birthdays_detail as $upcoming_birthday)
            <p class="birthday_block">
                <span class="glyphicon glyphicon-gift" aria-hidden="true" style="color:red"></span>
                <b> {{$upcoming_birthday->emp_name}}
                    ( {{date("M-d", strtotime($upcoming_birthday->emp_date_of_birth))}} )</b>
            </p>
        @endforeach
        <br>
    @endif

    @if(count($empWorkAnvs) > 0)
        <p class="notify">Work Anniversary's</p>
        <hr style="border-top:1px solid green"/>
        @foreach($empWorkAnvs as $empWorkAnv)
            <p class="birthday_block">
                <span class="glyphicon glyphicon-heart" aria-hidden="true" style="color:red"></span>
                <b>{{$empWorkAnv->emp_name}} ( {{date("M-d", strtotime($empWorkAnv->emp_joining_date))}}
                    )</b>
            </p>
        @endforeach
    @endif
    <br>

    @if($EmployeeOfTheMonth->count() > 0)
        <h4><b>Employees Of The Month</b></h4>
        @foreach($EmployeeOfTheMonth->get() as $Eom)
            <p style="background-color: white;border-radius: 15px;padding: 12px;">
                Emp Name:
                <b>{{HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee','emp_name','emp_id',$Eom->emp_id) }}</b>
                Remarks : <b>{{$Eom->remarks}}</b>
            </p>
        @endforeach
    @endif

</div>