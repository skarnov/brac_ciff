<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_airport_land_support', 'edit_airport_land_support')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$divisions = get_division();

if (isset($_POST['division_id'])) {
    $districts = get_district($_POST['division_id']);
    echo "<option value=''>Select One</option>";
    foreach ($districts as $district) :
        echo "<option id='" . $district['id'] . "' value='" . strtolower($district['name']) . "' >" . $district['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['district_id'])) {
    $subdistricts = get_subdistrict($_POST['district_id']);
    echo "<option value=''>Select One</option>";
    foreach ($subdistricts as $subdistrict) :
        echo "<option id='" . $subdistrict['id'] . "' value='" . strtolower($subdistrict['name']) . "'>" . $subdistrict['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['subdistrict_id'])) {
    $unions = get_union($_POST['subdistrict_id']);
    echo "<option value=''>Select One</option>";
    foreach ($unions as $union) :
        echo "<option id='" . $union['id'] . "' value='" . strtolower($union['name']) . "'>" . $union['name'] . "</option>";
    endforeach;
    exit;
}

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_airport_land_supports(array('id' => $edit, 'single' => true));

    $service_received = explode(',', $pre_data['service_received']);

    if (!$pre_data) {
        add_notification('Invalid Immediate Assistance ID, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'brac_info_id' => 'BRAC Info ID',
            'return_route' => 'Return Route',
            'arrival_date' => 'Arrival Date',
            'person_type' => 'Person Type',
            'full_name' => 'Full Name',
            'destination_country' => 'Destination Country',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $msg = array();

    $ret = $this->add_edit_airport_land_support($data);

    if ($ret['success']) {
        $msg = "Immediate assistance after arrival information has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/immediate_support/manage_airport_land_support?action=add_edit_airport_land_support&edit=' . $edit));
        } else {
            header('location: ' . url('admin/immediate_support/manage_airport_land_support'));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Immediate Assistance after Arrival</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Immediate Assistance after Arrival',
                'title' => 'Manage Immediate Assistance after Arrival',
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
            <div class="tab-pane fade active in" id="personalInfo">
                <fieldset>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Project</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="project_id">
                                        <option value="">Select One</option>
                                        <?php foreach ($all_projects['data'] as $project) : ?>
                                            <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $pre_data['fk_project_id']) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>BRAC Info ID (*)</label>
                                <input class="form-control" type="text" required name="brac_info_id" value="<?php echo $pre_data['brac_info_id'] ? $pre_data['brac_info_id'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Return Route (*)</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="radio" name="return_route" value="land" <?php echo $pre_data && $pre_data['return_route'] == 'land' ? 'checked' : '' ?>><span class="lbl">Land</span></label>
                                        <label><input class="px" type="radio" name="return_route" value="air" <?php echo $pre_data && $pre_data['return_route'] == 'air' ? 'checked' : '' ?>><span class="lbl">Air</span></label>
                                        <label><input class="px" type="radio" name="return_route" value="sea" <?php echo $pre_data && $pre_data['return_route'] == 'sea' ? 'checked' : '' ?>><span class="lbl">Sea</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Arrival Date (*)</label>
                                <div class="input-group">
                                    <input id="arrival_date" required type="text" class="form-control" name="arrival_date" value="<?php echo $pre_data['arrival_date'] && $pre_data['arrival_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['arrival_date'])) : date('d-m-Y'); ?>">
                                </div>
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker('arrival_date');
                                    });
                                </script>
                            </div>
                            <div class="form-group">
                                <label>Person Type (*)</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="radio" name="person_type" value="trafficked_survivor" <?php echo $pre_data && $pre_data['person_type'] == 'trafficked_survivor' ? 'checked' : '' ?>><span class="lbl">Trafficked Survivor</span></label>
                                        <label><input class="px" type="radio" name="person_type" value="returnee_migrant_worker" <?php echo $pre_data && $pre_data['person_type'] == 'returnee_migrant_worker' ? 'checked' : '' ?>><span class="lbl">Returnee Migrant Worker</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Name (*)</label>
                                <input class="form-control" type="text" required name="full_name" value="<?php echo $pre_data['full_name'] ? $pre_data['full_name'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px oldGender" type="radio" name="gender" value="male" <?php echo $pre_data && $pre_data['gender'] == 'male' ? 'checked' : '' ?>><span class="lbl">Men (>=18)</span></label>
                                        <label><input class="px oldGender" type="radio" name="gender" value="female" <?php echo $pre_data && $pre_data['gender'] == 'female' ? 'checked' : '' ?>><span class="lbl">Women (>=18)</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Disability</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px oldGender" type="radio" name="is_disable" value="yes" <?php echo $pre_data && $pre_data['is_disable'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                        <label><input class="px oldGender" type="radio" name="is_disable" value="no" <?php echo $pre_data && $pre_data['is_disable'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Passport Number</label>
                                <input class="form-control" type="text" name="passport_number" value="<?php echo $pre_data['passport_number'] ? $pre_data['passport_number'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Travel Pass</label>
                                <input class="form-control" type="text" name="travel_pass" value="<?php echo $pre_data['travel_pass'] ? $pre_data['travel_pass'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input class="form-control" type="number" name="mobile_number" value="<?php echo $pre_data['mobile_number'] ? $pre_data['mobile_number'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Emergency Number</label>
                                <input class="form-control" type="number" name="emergency_mobile" value="<?php echo $pre_data['emergency_mobile'] ? $pre_data['emergency_mobile'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Division (*)</label>
                                <div class="select2-primary">
                                    <select class="form-control division" required name="division" style="text-transform: capitalize">
                                        <?php if ($pre_data['division']) : ?>
                                            <option value="<?php echo strtolower($pre_data['division']) ?>"><?php echo $pre_data['division'] ?></option>
                                        <?php else: ?>
                                            <option>Select One</option>
                                        <?php endif ?>
                                        <?php foreach ($divisions as $division) : ?>
                                            <option id="<?php echo $division['id'] ?>" value="<?php echo strtolower($division['name']) ?>" <?php echo $pre_data && $pre_data['division'] == $division['name'] ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">District (*)</label>
                                <div class="select2-primary">
                                    <select class="form-control district" required name="district" style="text-transform: capitalize" id="districtList">
                                        <?php if ($pre_data['district']) : ?>
                                            <option value="<?php echo $pre_data['district'] ?>"><?php echo $pre_data['district'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Upazila</label>
                                <div class="select2-primary">
                                    <select class="form-control subdistrict" name="upazilla" style="text-transform: capitalize" id="subdistrictList">
                                        <?php if ($pre_data['upazilla']) : ?>
                                            <option value="<?php echo $pre_data['upazilla'] ?>"><?php echo $pre_data['upazilla'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Union</label>
                                <div class="select2-primary">
                                    <select class="form-control union" name="user_union" style="text-transform: capitalize" id="unionList">
                                        <?php if ($pre_data['user_union']) : ?>
                                            <option value="<?php echo $pre_data['user_union'] ?>"><?php echo $pre_data['user_union'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Village</label>
                                <input class="form-control" type="text" name="village" value="<?php echo $pre_data['village'] ? $pre_data['village'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Destination Country (*)</label>
                                <input class="form-control" required type="text" name="destination_country" value="<?php echo $pre_data['destination_country'] ? $pre_data['destination_country'] : ''; ?>">
                            </div>
                            <?php
                            $service_received = $service_received ? $service_received : array($service_received);
                            ?>
                            <div class="form-group">
                                <label>Service Received (*)</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="checkbox" name="service_received[]" value="Food" <?php
                                            if (in_array('Food', $service_received)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Food</span></label>
                                        <label><input class="px" type="checkbox" name="service_received[]" value="Information" <?php
                                            if (in_array('Information', $service_received)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Information</span></label>
                                        <label><input class="px" type="checkbox" name="service_received[]" value="Medical Transport Support" <?php
                                            if (in_array('Medical Transport Support', $service_received)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Medical Transport Support</span></label>
                                        <label><input class="px" type="checkbox" name="service_received[]" value="Accommodation" <?php
                                            if (in_array('Accommodation', $service_received)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Accommodation</span></label>
                                        <label><input class="px" type="checkbox" name="service_received[]" value="Mobile Communication to family/relatives" <?php
                                            if (in_array('Mobile Communication to family/relatives', $service_received)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Mobile Communication to family/relatives</span></label>
                                        <label><input class="px" type="checkbox" name="service_received[]" value="Provide Information" <?php
                                            if (in_array('Provide Information', $service_received)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Provide Information</span></label>

                                        <label><input class="px" type="checkbox" name="service_received[]" value="Psychosocial counseling" <?php
                                            if (in_array('Psychosocial counseling', $service_received)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Psychosocial counseling</span></label>
                                        <label><input class="px" type="checkbox" id="newService" <?php echo $pre_data && $pre_data['other_service_received'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="newServiceType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" name="new_service_received" value="<?php echo $pre_data['other_service_received'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newService').is(':checked');

                                    if (isChecked == true) {
                                        $('#newServiceType').show();
                                    }

                                    $("#newService").on("click", function () {
                                        $('#newServiceType').toggle();
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="panel-footer tar">
        <a href="<?php echo url('admin/immediate_support/manage_airport_land_support') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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
</form>
<script type="text/javascript">
    init.push(function () {
        $('.division').change(function () {
            var divisionId = $(this).find('option:selected').attr('id');
            $.ajax({
                type: 'POST',
                data: {division_id: divisionId},
                success: function (result) {
                    $('#districtList').html(result);
                }}
            );
        });
        $('.district').change(function () {
            var districtId = $(this).find('option:selected').attr('id');
            $.ajax({
                type: 'POST',
                data: {district_id: districtId},
                success: function (result) {
                    $('#subdistrictList').html(result);
                }}
            );
        });
        $('.subdistrict').change(function () {
            var subdistrictId = $(this).find('option:selected').attr('id');
            $.ajax({
                type: 'POST',
                data: {subdistrict_id: subdistrictId},
                success: function (result) {
                    $('#unionList').html(result);
                }}
            );
        });
    });
</script>