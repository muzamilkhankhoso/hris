<style>
    .panel-heading {
        padding: 0px 15px;}
    .field_width {width: 120px;}

    /*fix head css*/
    .tableFixHead {
        overflow-y: auto;
        height: 100px;
    }
    .tableFixHead thead th {
        position: sticky; top: 0px;
    }

    table  {  }
    th, td { padding: 8px 16px; }
    th     { background:#f9f9f9; }

    div.wrapper {
        overflow: scroll;
        max-height: 630px;

    }



</style>
<?php

use App\Helpers\CommonHelper;

$employeeArray = [];
$recordNotFound = [];

$result=[];

?>
<div class="panel">
    <div class="panel-body">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive wrapper">
                    <table class="table table-sm mb-0 table-bordered table-striped tableFixHead" id="TaxesList">
                        <thead>

                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center hidden-print">EMP ID</th>
                            <th class="text-center">Employee Name</th>
                            <th colspan="2" class="text-center">Designation</th>
                            <th colspan="2" class="text-center">Emp Contact</th>
                            <th colspan="2" class="text-center">CNIC</th>
                            <th colspan="2" class="text-center">Emergency Contact</th>
                            <th colspan="2" class="text-center">Name</th>
                            <th colspan="2" class="text-center">Relation</th>
                            <th colspan="2" class="text-center">Address</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 0;?>
                        <?php foreach($employees as $row1){?>
                        <?php
                        $counter++;
                        CommonHelper::companyDatabaseConnection(Input::get('m'));

                        ?>
                        <tr>
                            <td class="text-center ">{{$counter}}</td>
                            <td class="hidden-print">
                                {{ $row1->emp_id }}
                                <input type="hidden" value="{{ $row1->emp_id }}" name="emp_id[]">
                            </td>
                            <td class="text-center">{{ $row1->emp_name }}

                            </td>
                            <td colspan="2" class="text-center">
                                <select style="width: 100%;" class="form-control requiredField" id="designation[]" name="designation[]">
                                    <option value="">Select</option>
                                    @foreach($designation as $value5)
                                        <option @if($row1->designation_id == $value5->id) selected @endif value="{{ $value5->id}}">{{ $value5->designation_name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td colspan="2" class="text-center">
                                <input type="text"  class="form-control" value="{{ $row1->emp_contact_no }}" name="emp_contact_no[]">

                            </td>
                            <td colspan="2" class="text-center">
                                <input type="text" class="form-control"  maxlength="15" placeholder="CNIC Number" name="cnic[]" id="cnic[]" value="<?=$row1->emp_cnic ?>" />
                            </td>

                            <td colspan="2" class="text-center">
                                <input type="number"  class="form-control" value="{{ $row1->emergency_no }}" name="emp_emergency_contact_no[]">
                            </td>
                            <td colspan="2" class="text-center">
                                <input type="text"  class="form-control" value="{{ $row1->emp_emergency_relation_name }}" name="emp_emergency_relation_name[]">
                            </td>
                            <td colspan="2" class="text-center">
                                <input type="text"  class="form-control" value="{{ $row1->emp_emergency_relation }}" name="emp_emergency_relation[]">
                            </td>
                            <td colspan="2" class="text-center">
                                <textarea  class="form-control" name="emp_emergency_relation_address[]"> {{ $row1->emp_emergency_relation_address }}</textarea>
                            </td>

                        </tr>

                        <?php

                        CommonHelper::reconnectMasterDatabase();

                        ?>

                        <?php } ?>
                        </tbody>
                    </table>

                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <?php
                                foreach ($recordNotFound as $value):
                                    echo $value;
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>



        </div>
        <div class="row text-right">&nbsp;
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="submit" name="submit" class="btn btn-sm btn-success" />
            </div>
        </div>

    </div>`
</div>





