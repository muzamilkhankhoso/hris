<?php
use App\Helpers\CommonHelper;
use App\Models\Policies;
?>
<div class="tab-pane" id="policies">
    <?php
    CommonHelper::companyDatabaseConnection(Input::get('m'));
    $policies = Policies::where([['status', '=', 1],['category_id', '=', 1]])->get();
    $forms = Policies::where([['status', '=', 1],['category_id', '=', 2]])->get();
    CommonHelper::reconnectMasterDatabase();
    ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h2>Policies</h2>
            <div class="row">
                <ul>
                    @foreach($policies as $key => $val)
                        <li><h4><a target="_blank" href="{{ url('/').'/storage/'.$val->file_path}}">{{ $val->title }}</a></h4></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h2>Forms</h2>
            <div class="row">
                <ul>
                    @foreach($forms as $key => $val)
                        <li><h4><a target="_blank" href="{{ url('/').'/storage/'.$val->file_path}}">{{ $val->title }}</a></h4></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>