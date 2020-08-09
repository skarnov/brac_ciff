<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_complain_filed', 'edit_complain_filed')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_complain_fileds(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid complain filed, no data found.', 'error');
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

    $ret = $this->add_edit_complain_filed($data);

    if ($ret) {
        $msg = "Complain Filed has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_event_management/manage_complain_fileds?action=add_edit_complain_filed&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_event_management/manage_complain_fileds'));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Complain Filed </h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Complain Fileds',
                'title' => 'Manage Complain Fileds',
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
                    <label for="case_id">Case ID</label>
                    <input type="text" class="form-control" id="case_id" name="case_id" value="<?php echo $pre_data['case_id']; ?>">
                </div>
                <div class="form-group">
                    <label for="inputName">Date</label>
                    <div class="input-group">
                        <input id="ComplainRegisterDate" type="text" class="form-control" name="complain_register_date" value="<?php echo $pre_data['complain_register_date'] && $pre_data['complain_register_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['complain_register_date'])) : date('d-m-Y'); ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('ComplainRegisterDate');
                        });
                    </script>
                </div>
                <div class="form-group">
                    <label for="inputBranch">Select Month</label>
                    <select class="form-control" name="month" >
                        <option value="January">January</option>
                        <option value="February">February</option>
                        <option value="March ">March</option>
                        <option value="May">May</option>
                        <option value="June">June</option>
                        <option value="July">July</option>
                        <option value="August">August</option>
                        <option value="September">September</option>
                        <option value="October">October</option>
                        <option value="November">November</option>
                        <option value="December">December</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Division (*)</label>
                    <div class="select2-primary">
                        <select class="form-control" id="permanent_division" name="division" data-selected="<?php echo $pre_data['division'] ? $pre_data['division'] : '' ?>"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label>District (*)</label>
                    <div class="select2-success">
                        <select class="form-control" id="permanent_district" name="district" data-selected="<?php echo $pre_data['district'] ? $pre_data['district'] : ''; ?>"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label input-label">Select Municipality/Upazilla</label>
                    <input class="form-control" type="text" name="upazila" value="<?php echo $pre_data['upazila'] ? $pre_data['upazila'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="police_station">Name of police station</label>
                    <input type="text" class="form-control" id="police_station" name="police_station" value="<?php echo $pre_data['police_station']; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="inputAge">Age</label>
                    <input type="text" class="form-control" id="Age" name="age" value="<?php echo $pre_data['age']; ?>">
                </div>
                <div class="form-group">
                    <label>Sex</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px oldGender" type="radio" name="gender" value="male" <?php echo $pre_data && $pre_data['gender'] == 'male' ? 'checked' : '' ?>><span class="lbl">Male</span></label>
                            <label><input class="px oldGender" type="radio" name="gender" value="female" <?php echo $pre_data && $pre_data['gender'] == 'female' ? 'checked' : '' ?>><span class="lbl">Female</span></label>
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
                <div class="form-group">
                    <label for="comments">Type of Case</label>
                    <div class="options_holder radio">
                        <label><input class="px" type="radio"  class="Oldcase" name="type_case" value="Missing" <?php echo $pre_data && $pre_data['type_case'] == 'Missing' ? 'checked' : '' ?>><span class="lbl">Missing</span></label>
                        <label><input class="px" type="radio"  class="Oldcase" name="type_case" value="Flee away with" <?php echo $pre_data && $pre_data['type_case'] == 'Flee away with' ? 'checked' : '' ?>><span class="lbl">Flee away with</span></label>
                        <label><input class="px" type="radio"  class="Oldcase" name="type_case" value="Abduction" <?php echo $pre_data && $pre_data['type_case'] == 'Abduction' ? 'checked' : '' ?>><span class="lbl">Abduction</span></label>
                        <label><input class="px" type="radio" id="newCase" name="type_case" value="Other"><span class="lbl">Other</span></label>
                    </div>                          
                </div>  
                <div id="newCaseType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control" placeholder="Please Specity" type="text" name="new_type_case" value="<?php echo $pre_data['type_case'] ? $pre_data['type_case'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="comments">Comments</label>
                    <textarea class="form-control" name="comments"><?php echo $pre_data['comments']; ?></textarea>
                </div>
                <script>
                    init.push(function () {
                        $("#newCase").on("click", function () {
                            $('#newCaseType').show();
                        });

                        $(".Oldcase").on("click", function () {
                            $('#newCaseType').hide();
                        });
                    });
                </script>                                                                                                            
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_event_management/manage_complain_fileds') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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

        new bd_new_location_selector({
            'division': $('#permanent_division'),
            'district': $('#permanent_district'),
            'sub_district': $('#permanent_sub_district'),
            'police_station': $('#permanent_police_station'),
            'post_office': $('#permanent_post_office'),
        });

        var theForm = $('#theForm');
        theForm.data('serialized', theForm.serialize());

        theForm.on('change input', function () {
            theForm.find('input:submit, button:submit').prop('disabled', theForm.serialize() == theForm.data('serialized'));
        });
        theForm.find('input:submit, button:submit').prop('disabled', true);
    });
</script>