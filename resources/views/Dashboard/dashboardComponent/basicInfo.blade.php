<div class="tab-pane active" id="basic_info">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <span class="subHeadingLabelClass">Basic Info</span>
        </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <p class="bg-success text-center" id="response"></p>
        <p class="p-3 mb-2 bg-danger text-white text-center"  id="error_cnic"></p>
        <div class="row">
            <form class="form" id="basic_info_form">
                <div id="ajax_form"></div>
                <div id="without_ajax_form">
                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="name">Name</label>
                            <input type="text" class="form-control requiredField" name="name" id="name" placeholder="Name" value="<?php echo $employee->value('emp_name') ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="p_email">Personal Email</label>
                            <input type="email" class="form-control requiredField" name="p_email" id="p_email" placeholder="Personal Email" value="<?php echo $employee->value('professional_email') ?>">
                        </div>
                    </div>
                    <br><br><br><br>
                    <div class="form-group">

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="cnic">Cnic</label>
                            <input type="text" class="form-control requiredField" name="cnic" id="cnic" placeholder="Cnic" value="<?php echo $employee->value('emp_cnic') ?>" >
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="phone">Phone</label>
                            <input type="number" class="form-control requiredField" name="phone" id="phone" placeholder="enter mobile number" value="<?php echo $employee->value('emp_contact_no') ?>" >
                        </div>
                    </div>

                    <div class="form-group">

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="email">Dob</label>
                            <input type="date" class="form-control" name="dob" id="dob" placeholder="DOB" value="<?php echo $employee->value('emp_date_of_birth') ?>">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            <br>
                            <button type="submit" class="btn btn-lg btn-success" id="btn_check" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Loading...">Save</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <hr>

</div>