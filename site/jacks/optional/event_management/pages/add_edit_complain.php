<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_complain', 'edit_complain')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches();

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_complains(array('id' => $edit, 'single' => true));

    $type_service = explode(',', $pre_data['type_service']);
    $know_service = explode(',', $pre_data['know_service']);

    if (!$pre_data) {
        add_notification('Invalid complain, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $branch_id = $_POST['branch_id'];
    $branch_info = $branches->get_branches(array('id' => $branch_id, 'single' => true));

    $data = array(
        'required' => array(
        ),
    );
    $data['branch_district'] = $branch_info['branch_district'];
    $data['branch_sub_district'] = $branch_info['branch_sub_district'];
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_complain($data);

    if ($ret) {
        $msg = "Complain has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_event_management/manage_complains?action=add_edit_complain&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_event_management/manage_complains'));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Community Service</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Community Services',
                'title' => 'Manage Community Services',
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
            <div class="col-md-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Address</legend>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Branch</label>
                            <div class="select2-primary">
                                <select class="form-control" name="branch_id" required>
                                    <option value="">Select One</option>
                                    <?php foreach ($all_branches['data'] as $branch) : ?>
                                        <option value="<?php echo $branch['pk_branch_id'] ?>" <?php echo ($branch['pk_branch_id'] == $pre_data['fk_branch_id']) ? 'selected' : '' ?>><?php echo $branch['branch_name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <input class="form-control" type="hidden" name="branch_division" value="khulna">
                        <input class="form-control" type="hidden" name="branch_district" value="jashore">
                        <label class="control-label input-label">Upazila</label>
                        <div class="form-group">
                            <select class="form-control" name="upazila">
                                <option value="">Select One</option>
                                <option value="Jashore Sadar" <?php echo $pre_data && $pre_data['upazila'] == 'Jashore Sadar' ? 'selected' : '' ?>>Jashore Sadar</option>
                                <option value="Jhikargacha" <?php echo $pre_data && $pre_data['upazila'] == 'Jhikargacha' ? 'selected' : '' ?>>Jhikargacha</option>
                                <option value="Sharsha" <?php echo $pre_data && $pre_data['upazila'] == 'Sharsha' ? 'selected' : '' ?>>Sharsha</option>
                                <option value="Chougachha" <?php echo $pre_data && $pre_data['upazila'] == 'Chougachha' ? 'selected' : '' ?>>Chougachha</option>
                                <option value="Manirampur" <?php echo $pre_data && $pre_data['upazila'] == 'Manirampur' ? 'selected' : '' ?>>Manirampur</option>
                                <option value="Bagherpara" <?php echo $pre_data && $pre_data['upazila'] == 'Bagherpara' ? 'selected' : '' ?>>Bagherpara</option>
                                <option value="Keshabpur" <?php echo $pre_data && $pre_data['upazila'] == 'Keshabpur' ? 'selected' : '' ?>>Keshabpur</option>
                                <option value="Abhaynagar" <?php echo $pre_data && $pre_data['upazila'] == 'Abhaynagar' ? 'selected' : '' ?>>Abhaynagar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label input-label">Union</label>
                        <div class="form-group">
                            <input class="form-control" type="text" name="union" value="<?php echo $pre_data['branch_union'] ? $pre_data['branch_union'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Village</label>
                            <input class="form-control" type="text" name="village" value="<?php echo $pre_data['village'] ? $pre_data['village'] : ''; ?>">
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="inputName">Register Date</label>
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
                    <label for="inputName">Name of service recipient</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $pre_data['name']; ?>">
                </div>
                <div class="form-group">
                    <label for="inputAge">Age</label>
                    <input type="number" class="form-control" id="Age" name="age" value="<?php echo $pre_data['age']; ?>">
                </div>
                <?php
                $type_service = $type_service ? $type_service : array($type_service);
                ?> 
                <div class="form-group">
                    <label>Type of service seeking</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px" type="checkbox" name="type_service[]" value="Case filing support" <?php
                                if (in_array('Case filing support', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Case filing support</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Trafficking information" <?php
                                if (in_array('Trafficking information', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Trafficking information</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Safe migration information" <?php
                                if (in_array('Safe migration information', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Safe migration information</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Missing information" <?php
                                if (in_array('Missing information', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Missing information</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Rescue support" <?php
                                if (in_array('Rescue support', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Rescue support</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Dead body recover support" <?php
                                if (in_array('Dead body recover support', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Dead body recover support</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Claim compensation" <?php
                                if (in_array('Claim compensation', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Claim compensation</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Legal support" <?php
                                if (in_array('Legal support', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Legal support</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Project information" <?php
                                if (in_array('Project information', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Project information</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Training support" <?php
                                if (in_array('Training support', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Training support</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Loan support" <?php
                                if (in_array('Loan support', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Loan support</span></label>
                            <label><input class="px" type="checkbox" name="type_service[]" value="Job placement" <?php
                                if (in_array('Job placement', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Job placement</span></label>
                            <label><input class="px" type="checkbox" id="newTypeService" name="type_service[]" value="Others" <?php
                                if (in_array('Others', $type_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Other</span></label>
                        </div>
                    </div>
                </div>
                <div id="newTypeServiceType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control" placeholder="Please Specity" type="text" id="newTypeServiceText" name="new_type_service" value="<?php echo $pre_data['other_type_service'] ?>">
                </div>
                <script>
                    init.push(function () {
                        var isChecked = $('#newTypeService').is(':checked');

                        if (isChecked == true) {
                            $('#newTypeServiceType').show();
                        }

                        $("#newTypeService").on("click", function () {
                            $('#newTypeServiceType').toggle();
                        });
                    });
                </script>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Service recipient</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px" type="radio" name="type_recipient" value="victim" <?php echo $pre_data && $pre_data['type_recipient'] == 'victim' ? 'checked' : '' ?>><span class="lbl">Victim</span></label>
                            <label><input class="px" type="radio" name="type_recipient" value="family" <?php echo $pre_data && $pre_data['type_recipient'] == 'family' ? 'checked' : '' ?>><span class="lbl">Family</span></label>
                            <label><input class="px" type="radio" name="type_recipient" value="relative" <?php echo $pre_data && $pre_data['type_recipient'] == 'relative' ? 'checked' : '' ?>><span class="lbl">Relative</span></label>
                            <label><input class="px" type="radio" name="type_recipient" value="community_member" <?php echo $pre_data && $pre_data['type_recipient'] == 'community_member' ? 'checked' : '' ?>><span class="lbl">Community Member</span></label>
                        </div>
                    </div>
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
                <?php
                $know_service = $know_service ? $know_service : array($know_service);
                ?> 
                <div class="form-group">
                    <label>How to know about this service of the project</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px" type="checkbox" name="know_service[]" value="IPT show" <?php
                                if (in_array('IPT show', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">IPT show</span></label>
                            <label><input class="px" type="checkbox" name="know_service[]" value="Video show" <?php
                                if (in_array('Video show', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Video show</span></label>
                            <label><input class="px" type="checkbox" name="know_service[]" value="School quiz" <?php
                                if (in_array('School quiz', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">School quiz</span></label>
                            <label><input class="px" type="checkbox" name="know_service[]" value="Palli somaj" <?php
                                if (in_array('Palli somaj', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Palli somaj</span></label>
                            <label><input class="px" type="checkbox" name="know_service[]" value="CTC" <?php
                                if (in_array('CTC', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">CTC</span></label>
                            <label><input class="px" type="checkbox" name="know_service[]" value="DLAC" <?php
                                if (in_array('DLAC', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">DLAC</span></label>
                            <label><input class="px" type="checkbox" name="know_service[]" value="Social media" <?php
                                if (in_array('Social media', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Social media</span></label>
                            <label><input class="px" type="checkbox" name="know_service[]" value="IEC/BCC" <?php
                                if (in_array('IEC/BCC', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">IEC/BCC</span></label>
                            <label><input class="px" type="checkbox" id="newKnowService" name="know_service[]" value="Others" <?php
                                if (in_array('Others', $know_service)) {
                                    echo 'checked';
                                }
                                ?>><span class="lbl">Other</span></label>
                        </div>
                    </div>
                </div>
                <div id="newKnowServiceType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control" placeholder="Please Specity" type="text" id="newKnowServiceText" name="new_know_service" value="<?php echo $pre_data['other_know_service'] ?>">
                </div>
                <script>
                    init.push(function () {
                        var isChecked = $('#newKnowService').is(':checked');

                        if (isChecked == true) {
                            $('#newKnowServiceType').show();
                        }

                        $("#newKnowService").on("click", function () {
                            $('#newKnowServiceType').toggle();
                        });
                    });
                </script>
                <div class="form-group">
                    <label for="inputRemark">Remark</label>
                    <textarea class="form-control" name="remark"><?php echo $pre_data['remark']; ?></textarea>
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_event_management/manage_complains') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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
    init.push(function () {
        theForm.find('input:submit, button:submit').prop('disabled', true);
    });
</script>