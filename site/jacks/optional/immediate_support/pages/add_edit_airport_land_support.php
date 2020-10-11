<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_airport_land_support', 'edit_airport_land_support')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
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
                                        <label><input class="px oldGender" type="radio" name="gender" value="male" <?php echo $pre_data && $pre_data['gender'] == 'male' ? 'checked' : '' ?>><span class="lbl">Male</span></label>
                                        <label><input class="px oldGender" type="radio" name="gender" value="female" <?php echo $pre_data && $pre_data['gender'] == 'female' ? 'checked' : '' ?>><span class="lbl">Female</span></label>
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
                                <label>Division</label>
                                <div class="select2-primary" required>
                                    <select class="form-control" id="permanent_division" name="division" data-selected="<?php echo $pre_data['division'] ? $pre_data['division'] : '' ?>"></select>
                                </div>
                            </div>       
                            <div class="form-group">
                                <label>District (*)</label>
                                <div class="select2-success" required>
                                    <select class="form-control" id="permanent_district" name="district" data-selected="<?php echo $pre_data['district'] ? $pre_data['district'] : ''; ?>"></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Upazila</label>
                                <select class="form-control" name="upazilla">
                                    <option value="">Select One</option>
                                    <option value="Jashore Sadar" <?php echo $pre_data && $pre_data['upazilla'] == 'Jashore Sadar' ? 'selected' : '' ?>>Jashore Sadar</option>
                                    <option value="Jhikargacha" <?php echo $pre_data && $pre_data['upazilla'] == 'Jhikargacha' ? 'selected' : '' ?>>Jhikargacha</option>
                                    <option value="Sharsha" <?php echo $pre_data && $pre_data['upazilla'] == 'Sharsha' ? 'selected' : '' ?>>Sharsha</option>
                                    <option value="Chougachha" <?php echo $pre_data && $pre_data['upazilla'] == 'Chougachha' ? 'selected' : '' ?>>Chougachha</option>
                                    <option value="Manirampur" <?php echo $pre_data && $pre_data['upazilla'] == 'Manirampur' ? 'selected' : '' ?>>Manirampur</option>
                                    <option value="Bagherpara" <?php echo $pre_data && $pre_data['upazilla'] == 'Bagherpara' ? 'selected' : '' ?>>Bagherpara</option>
                                    <option value="Keshabpur" <?php echo $pre_data && $pre_data['upazilla'] == 'Keshabpur' ? 'selected' : '' ?>>Keshabpur</option>
                                    <option value="Abhaynagar" <?php echo $pre_data && $pre_data['upazilla'] == 'Abhaynagar' ? 'selected' : '' ?>>Abhaynagar</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Union/Pourashava</label>
                                <input class="form-control" type="text" name="user_union" value="<?php echo $pre_data['user_union'] ? $pre_data['user_union'] : ''; ?>">
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