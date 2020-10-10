<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_access_to_pp', 'edit_access_to_pp')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_access_to_pp(array('id' => $edit, 'single' => true));

    $service_types = explode(',', $pre_data['service_type']);
    $rescue_reason = explode(',', $pre_data['rescue_reason']);
    $complain_to = explode(',', $pre_data['complain_to']);

    if (!$pre_data) {
        add_notification('Invalid Access To PP, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'brac_info_id' => 'BRAC Info ID',
            'full_name' => 'Full Name',
            'division' => 'Division',
            'district' => 'District',
            'destination_country' => 'Destination Country',
            'support_date' => 'Complain/Support Date',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $msg = array();

    $ret = $this->add_edit_access_to_pp($data);
    
    if ($ret['success']) {
        $msg = "Access To Public And Private Support information has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/immediate_support/manage_access_to_pp?action=add_edit_access_to_pp&edit=' . $edit));
        } else {
            header('location: ' . url('admin/immediate_support/manage_access_to_pp'));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Access To Public & Private</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Access To Public & Private',
                'title' => 'Manage Access To Public & Private',
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
                                    <select class="form-control" name="project_id" required>
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
                                        <label><input class="px oldGender" type="radio" name="disability" value="yes" <?php echo $pre_data && $pre_data['disability'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                        <label><input class="px oldGender" type="radio" name="disability" value="no" <?php echo $pre_data && $pre_data['disability'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input class="form-control" type="number" name="mobile_number" value="<?php echo $pre_data['mobile'] ? $pre_data['mobile'] : ''; ?>">
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
                                <input class="form-control" type="text" name="union" value="<?php echo $pre_data['user_union'] ? $pre_data['user_union'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Village</label>
                                <input class="form-control" type="text" name="village" value="<?php echo $pre_data['village'] ? $pre_data['village'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?php
                            $service_types = $service_types ? $service_types : array($service_types);
                            ?>
                            <div class="form-group">
                                <label>Service Type (*)</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="checkbox" name="service_type[]" value="Death body" <?php
                                            if (in_array('Death body', $service_types)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Death body</span></label>
                                        <label><input class="px" type="checkbox" name="service_type[]" value="Rescue" <?php
                                            if (in_array('Rescue', $service_types)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Rescue</span></label>
                                        <label><input class="px" type="checkbox" name="service_type[]" value="Compensation" <?php
                                            if (in_array('Compensation', $service_types)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Compensation</span></label>
                                        <label><input class="px" type="checkbox" name="service_type[]" value="Treatment Support" <?php
                                            if (in_array('Treatment Support', $service_types)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Treatment Support</span></label>
                                        <label><input class="px" type="checkbox" name="service_type[]" value="Student Stipend" <?php
                                            if (in_array('Student Stipend', $service_types)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Student Stipend</span></label>
                                        <label><input class="px" type="checkbox" name="service_type[]" value="Referral Service" <?php
                                            if (in_array('Referral Service', $service_types)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Referral Service</span></label>
                                        <label><input class="px" type="checkbox" id="newService" <?php echo $pre_data && $pre_data['other_service_type'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="newServiceType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" name="new_service_type" value="<?php echo $pre_data['other_service_type'] ?>">
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
                            <div class="form-group">
                                <label>Rescue Reason</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px educations" type="radio" name="rescue_reason" value="physical_torture" <?php echo $pre_data && $pre_data['rescue_reason'] == 'physical_torture' ? 'checked' : '' ?>><span class="lbl">Physical Torture</span></label>
                                        <label><input class="px educations" type="radio" name="rescue_reason" value="sexual_abuse" <?php echo $pre_data && $pre_data['rescue_reason'] == 'sexual_abuse' ? 'checked' : '' ?>><span class="lbl">Sexual Abuse</span></label>
                                        <label><input class="px educations" type="radio" name="rescue_reason" value="deportation" <?php echo $pre_data && $pre_data['rescue_reason'] == 'deportation' ? 'checked' : '' ?>><span class="lbl">Deportation</span></label>
                                        <label><input class="px educations" type="radio" name="rescue_reason" value="undocumented" <?php echo $pre_data && $pre_data['rescue_reason'] == 'undocumented' ? 'checked' : '' ?>><span class="lbl">Undocumented</span></label>
                                        <label><input class="px" type="radio" name="rescue_reason" id="newRescue"><span class="lbl">Others, Please specify…</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="newRescueType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" id="newRescueText" name="new_rescue" value="<?php echo $pre_data['rescue_reason'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    $("#newRescue").on("click", function () {
                                        $('#newRescueType').show();
                                    });

                                    $(".educations").on("click", function () {
                                        $('#newRescueType').hide();
                                        $('#newRescueText').val('');
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label>Destination Country (*)</label>
                                <input class="form-control" required type="text" name="destination_country" value="<?php echo $pre_data['destination_country'] ? $pre_data['destination_country'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Complain/Support Date (*)</label>
                                <div class="input-group">
                                    <input id="collection_date" required type="text" class="form-control" name="support_date" value="<?php echo $pre_data['support_date'] && $pre_data['support_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['support_date'])) : date('d-m-Y'); ?>">
                                </div>
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker('collection_date');
                                    });
                                </script>
                            </div>
                            <?php
                            $complain_to = $complain_to ? $complain_to : array($complain_to);
                            ?>
                            <div class="form-group">
                                <label>Complain To (*)</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="checkbox" name="complain_to[]" value="Travel document" <?php
                                            if (in_array('Travel document', $complain_to)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Travel document</span></label>
                                        <label><input class="px" type="checkbox" name="complain_to[]" value="NID" <?php
                                            if (in_array('NID', $complain_to)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">NID</span></label>
                                        <label><input class="px" type="checkbox" name="complain_to[]" value="Birth Registration" <?php
                                            if (in_array('Birth Registration', $complain_to)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Birth Registration</span></label>
                                        <label><input class="px" type="checkbox" name="complain_to[]" value="Passport" <?php
                                            if (in_array('Passport', $complain_to)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Passport</span></label>
                                        <label><input class="px" type="checkbox" name="complain_to[]" value="Smart Card" <?php
                                            if (in_array('Smart Card', $complain_to)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Smart Card</span></label>
                                        <label><input class="px" type="checkbox" id="newComplainTo" <?php echo $pre_data && $pre_data['other_complain_to'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="newComplainToType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" id="newComplainToTypeText" name="new_complain_to" value="<?php echo $pre_data['other_complain_to'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newComplainTo').is(':checked');

                                    if (isChecked == true) {
                                        $('#newComplainToType').show();
                                    }

                                    $("#newComplainTo").on("click", function () {
                                        $('#newComplainToType').toggle();
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label class="control-label input-label">Service Result</label>
                                <select class="form-control" name="service_result">
                                    <option value="">Select One</option>
                                    <option value="received" <?php echo $pre_data && $pre_data['service_result'] == 'received' ? 'selected' : '' ?>>Received</option>
                                    <option value="not-received" <?php echo $pre_data && $pre_data['service_result'] == 'not-received' ? 'selected' : '' ?>>Not-Received</option>
                                    <option value="under-process" <?php echo $pre_data && $pre_data['service_result'] == 'under-process' ? 'selected' : '' ?>>Under-Process</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Return Date (*)</label>
                                <div class="input-group">
                                    <input id="return_date" required type="text" class="form-control" name="return_date" value="<?php echo $pre_data['return_date'] && $pre_data['return_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['return_date'])) : date('d-m-Y'); ?>">
                                </div>
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker('return_date');
                                    });
                                </script>
                            </div>
                            <div class="form-group">
                                <label>Comment</label>
                                <textarea class="form-control" name="comment"><?php echo $pre_data['comment'] ? $pre_data['comment'] : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="panel-footer tar">
        <a href="<?php echo url('admin/immediate_support/manage_access_to_pp') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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