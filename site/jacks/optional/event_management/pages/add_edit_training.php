<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_training', 'edit_training')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_trainings(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid training, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_training($data);

    if ($ret) {
        $msg = "Training has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_event_management/manage_trainings?action=add_edit_training&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_event_management/manage_trainings'));
        }
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

doAction('render_start');

ob_start();
?>
<style type="text/css">
    .removeReadOnly {
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Training/Workshop</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Training/Workshop',
                'title' => 'Manage Training/Workshop',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
    <div class="panel" id="fullForm" style="">
        <div class="panel-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Date</label>
                    <div class="input-group">
                        <input id="Date" type="text" class="form-control" name="date" value="<?php echo $pre_data['date'] && $pre_data['date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['date'])) : date('d-m-Y'); ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('Date');
                        });
                    </script>
                </div>
                <div class="form-group">
                    <label for="inputId">Beneficiary ID</label>
                    <input type="text" class="form-control" id="BeneficiaryId" name="beneficiary_id" value="<?php echo $pre_data['beneficiary_id']; ?>">
                </div>
                <div class="form-group">
                    <label for="inputId">Participant Name</label>
                    <input type="text" class="form-control" required name="name" value="<?php echo $pre_data['name']; ?>">
                </div>
                <div class="form-group">
                    <label for="inputId">Age</label>
                    <input type="number" class="form-control" id="Age" name="age" value="<?php echo $pre_data['age']; ?>">
                </div>
                <div class="form-group">
                    <label for="inputId">Profession</label>
                    <input type="text" class="form-control" id="Profesion" name="profession" value="<?php echo $pre_data['profession']; ?>">
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px oldGender" type="radio" name="gender" value="male" <?php echo $pre_data && $pre_data['gender'] == 'male' ? 'checked' : '' ?>><span class="lbl">Men (>=18)</span></label>
                            <label><input class="px oldGender" type="radio" name="gender" value="female" <?php echo $pre_data && $pre_data['gender'] == 'female' ? 'checked' : '' ?>><span class="lbl">Women (>=18)</span></label>
                            <label><input class="px" type="radio" name="gender" id="newGender"><span class="lbl">Other</span></label>
                        </div>
                    </div>
                </div>           
                <div id="newGenderType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control" placeholder="Please Specity" type="text" id="newGenderText" name="new_gender" value="<?php echo $pre_data['gender'] ?>">
                </div>
                <script>
                    init.push(function () {
                        $("#newGender").on("click", function () {
                            $('#newGenderType').show();
                        });

                        $(".oldGender").on("click", function () {
                            $('#newGenderType').hide();
                            $('#newGenderText').val('');
                        });
                    });
                </script>   
            </div>
            <div class="col-md-6">   
                <div class="form-group">
                    <label for="inputId">Name of the training</label>
                    <input type="text" class="form-control" id="Profesion" name="training_name" value="<?php echo $pre_data['training_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="inputId">Name of the workshop</label>
                    <input type="text" class="form-control" name="workshop_name" value="<?php echo $pre_data['workshop_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="inputId">Duration of Workshop</label>
                    <input type="text" class="form-control" name="workshop_duration" value="<?php echo $pre_data['workshop_duration']; ?>">
                </div>
                <div class="form-group">
                    <label for="inputId">Duration of training</label>
                    <input type="text" class="form-control" name="training_duration" value="<?php echo $pre_data['training_duration']; ?>">
                </div>
                <div class="form-group">
                    <label for="inputId">Mobile</label>
                    <input type="number" class="form-control" id="Mobile" name="mobile" value="<?php echo $pre_data['mobile']; ?>">
                </div>
                <div class="form-group">
                    <label for="inputId">Address</label>
                    <textarea class="form-control" name="address"><?php echo $pre_data['address']; ?></textarea>
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_event_management/manage_trainings') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
            <?php
            echo submitButtonGenerator(array(
                'action' => $edit ? 'update' : 'update',
                'size' => '',
                'id' => 'submit',
                'title' => $edit ? 'Update' : 'Save',
                'icon' => $edit ? 'icon_update' : 'icon_save',
                'text' => $edit ? 'Update' : 'Save'
            ))
            ?>
        </div>
    </div>
</form>
<script type="text/javascript">
    var BD_LOCATIONS = <?php echo getBDLocationJson(); ?>;
    init.push(function () {
        theForm.find('input:submit, button:submit').prop('disabled', true);
    });
</script>