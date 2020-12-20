<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_returnee', 'edit_returnee')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches();

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

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

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_returnees(array('id' => $edit, 'single' => true));

    $legal_documents = explode(',', $pre_data['legal_document']);

    if (!$pre_data) {
        add_notification('Invalid returnee, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST['ajax_type']) {
    if ($_POST['ajax_type'] == 'uniqueNID') {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE nid_number = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    } elseif ($_POST['ajax_type'] == 'uniqueBirth') {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE birth_reg_number = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    } elseif ($_POST['ajax_type'] == 'uniquePassport') {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE passport_number = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    } elseif ($_POST['ajax_type'] == 'uniqueMobile') {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE mobile_number = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    } elseif ($_POST['ajax_type'] == 'uniqueEmergencyMobile') {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE emergency_mobile = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    }
    exit();
}

if ($_POST) {
    $data = array(
        'required' => array(
            'branch_id' => 'District Centre / Branch Name',
            'returnee_id' => 'ID',
            'collection_date' => 'Date of data Collection',
            'person_type' => 'Type of person',
            'full_name' => 'Name of person',
            'returnee_gender' => 'Gender',
            'permanent_district' => 'District',
            'return_date' => 'Return Date',
            'destination_country' => 'Country of Destination',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $msg = array();

    if ($data['form_data']['nid_number']) {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE nid_number = '" . $data['form_data']['nid_number'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['nid'] = "This NID holder is already in our Database";
        }
    }
    if ($data['form_data']['birth_reg_number']) {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE birth_reg_number = '" . $data['form_data']['birth_reg_number'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['birth'] = "This Birth Registration holder is already in our Database";
        }
    }
    if ($data['form_data']['passport_number']) {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE passport_number = '" . $data['form_data']['passport_number'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['passport'] = "This Passport holder is already in our Database";
        }
    }
    if ($data['form_data']['mobile_number']) {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE mobile_number = '" . $data['form_data']['mobile_number'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['mobile'] = "This mobile holder is already in our Database";
        }
    }
    if ($data['form_data']['emergency_mobile']) {
        $sql = "SELECT pk_returnee_id FROM dev_returnees WHERE emergency_mobile = '" . $data['form_data']['emergency_mobile'] . "'";
        if ($edit) {
            $sql .= " AND NOT pk_returnee_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['mobile'] = "This emergency mobile holder is already in our Database";
        }
    }

    $message = implode('.<br>', $msg);
    if ($message) {
        add_notification($message, 'error');
        header('location: ' . url('admin/dev_returnee_management/manage_returnees'));
        exit();
    }

    $ret = $this->add_edit_returnee($data);

    if ($ret['returnee_insert'] || $ret['returnee_update']) {
        $returnee_id = $edit ? $edit : $ret['returnee_insert']['success'];
        $returnee_data = $this->get_returnees(array('returnee_id' => $returnee_id, 'single' => true));
        $msg = "Basic information of returnee profile " . $returnee_data['full_name'] . " (ID: " . $returnee_data['returnee_id'] . ") has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_customer_management/manage_returnees?action=add_edit_returnee&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_customer_management/manage_returnees'));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Returnee</h1>
    <?php if ($pre_data) : ?>
        <h4 class="text-primary">Returnee : <?php echo $pre_data['full_name'] ?></h4>
        <h4 class="text-primary">ID: <?php echo $pre_data['returnee_id'] ?></h4>
    <?php endif; ?>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Returnees',
                'title' => 'Manage Returnees',
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
                                <label>District Centre / Branch Name (*)</label>
                                <div class="select2-primary">
                                    <select class="form-control" name="branch_id" required>
                                        <option value="">Select One</option>
                                        <?php foreach ($all_branches['data'] as $branch) : ?>
                                            <option value="<?php echo $branch['pk_branch_id'] ?>" <?php echo ($branch['pk_branch_id'] == $pre_data['fk_branch_id']) ? 'selected' : '' ?>><?php echo $branch['branch_name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
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
                                <label>ID (*)</label>
                                <input class="form-control" type="text" required name="returnee_id" value="<?php echo $pre_data['returnee_id'] ? $pre_data['returnee_id'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>BRAC Info ID</label>
                                <input class="form-control" type="text" name="brac_info_id" value="<?php echo $pre_data['brac_info_id'] ? $pre_data['brac_info_id'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Date of data  Collection (*)</label>
                                <div class="input-group">
                                    <input id="collection_date" required type="text" class="form-control" name="collection_date" value="<?php echo $pre_data['collection_date'] && $pre_data['collection_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['collection_date'])) : date('d-m-Y'); ?>">
                                </div>
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker('collection_date');
                                    });
                                </script>
                            </div>
                            <div class="form-group">
                                <label>Type of person (*)</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="radio" name="person_type" value="trafficked_survivor" <?php echo $pre_data && $pre_data['person_type'] == 'trafficked_survivor' ? 'checked' : '' ?>><span class="lbl">Trafficked Survivor</span></label>
                                        <label><input class="px" type="radio" name="person_type" value="returnee_migrant" <?php echo $pre_data && $pre_data['person_type'] == 'returnee_migrant' ? 'checked' : '' ?>><span class="lbl">Returnee Migrant</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Name of person (*)</label>
                                <input class="form-control" type="text" required name="full_name" value="<?php echo $pre_data['full_name'] ? $pre_data['full_name'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Gender (*)</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px oldGender" type="radio" name="returnee_gender" value="male" <?php echo $pre_data && $pre_data['returnee_gender'] == 'male' ? 'checked' : '' ?>><span class="lbl">Men (>=18)</span></label>
                                        <label><input class="px oldGender" type="radio" name="returnee_gender" value="female" <?php echo $pre_data && $pre_data['returnee_gender'] == 'female' ? 'checked' : '' ?>><span class="lbl">Women (>=18)</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Marital Status (*)</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px notMarried" type="radio" name="marital_status" value="single" <?php echo $pre_data && $pre_data['marital_status'] == 'single' ? 'checked' : '' ?>><span class="lbl">Unmarried</span></label>
                                        <label><input class="px" id="isMarried" type="radio" name="marital_status" value="married" <?php echo $pre_data && $pre_data['marital_status'] == 'married' ? 'checked' : '' ?>><span class="lbl">Married</span></label>
                                        <label><input class="px notMarried" type="radio" name="marital_status" value="divorced" <?php echo $pre_data && $pre_data['marital_status'] == 'divorced' ? 'checked' : '' ?>><span class="lbl">Divorced/Separated</span></label>
                                        <label><input class="px notMarried" type="radio" name="marital_status" value="widowed" <?php echo $pre_data && $pre_data['marital_status'] == 'widowed' ? 'checked' : '' ?>><span class="lbl">Widowed</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="spouse" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Enter Spouse Name" type="text" id="customerSpouse" name="customer_spouse" value="<?php echo $pre_data['customer_spouse'] ? $pre_data['customer_spouse'] : ''; ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#isMarried').is(':checked');

                                    if (isChecked == true) {
                                        $('#spouse').show();
                                    }

                                    $("#isMarried").on("click", function () {
                                        $('#spouse').show();
                                    });

                                    $(".notMarried").on("click", function () {
                                        $('#spouse').hide();
                                        $('#customerSpouse').val('');
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <div class="input-group">
                                    <input data-verified="no" data-ajax-type="uniqueMobile" data-error-message="This Mobile holder is already in our Database" class="verifyUnique form-control" id="mobile" type="text" name="mobile_number" value="<?php echo $pre_data['mobile_number'] ? $pre_data['mobile_number'] : ''; ?>">
                                    <span class="input-group-addon"></span>
                                </div>
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>Emergency Mobile Number</label>
                                <div class="input-group">
                                    <input data-verified="no" data-ajax-type="uniqueEmergencyMobile" data-error-message="This Emergency Mobile holder is already in our Database" class="verifyUnique form-control" id="emergency_mobile" type="text" name="emergency_mobile" value="<?php echo $pre_data['emergency_mobile'] ? $pre_data['emergency_mobile'] : ''; ?>">
                                    <span class="input-group-addon"></span>
                                </div>
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>NID Number</label>
                                <div class="input-group">
                                    <input data-verified="no" data-ajax-type="uniqueNID" data-error-message="This NID holder is already in our Database" class="verifyUnique form-control" id="nid" type="text" name="nid_number" value="<?php echo $pre_data['nid_number'] ? $pre_data['nid_number'] : ''; ?>">
                                    <span class="input-group-addon"></span>
                                </div>
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>Birth Registration Number</label>
                                <div class="input-group">
                                    <input data-verified="no" data-ajax-type="uniqueBirth" data-error-message="This Birth Registration holder is already in our Database" class="verifyUnique form-control" id="birth" type="text" name="birth_reg_number" value="<?php echo $pre_data['birth_reg_number'] ? $pre_data['birth_reg_number'] : ''; ?>">
                                    <span class="input-group-addon"></span>
                                </div>
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>Passport Number</label>
                                <div class="input-group">
                                    <input data-verified="no" data-ajax-type="uniquePassport" data-error-message="This Passport holder is already in our Database" class="verifyUnique form-control" id="passport" type="text" name="passport_number" value="<?php echo $pre_data['passport_number'] ? $pre_data['passport_number'] : ''; ?>">
                                    <span class="input-group-addon"></span>
                                </div>
                                <p class="help-block"></p>
                            </div>
                            <div class="form-group">
                                <label>Father's Name (*)</label>
                                <input type="text" class="form-control" name="father_name" value="<?php echo $pre_data['father_name'] ? $pre_data['father_name'] : ''; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Mother's Name</label>
                                <input type="text" class="form-control" name="mother_name" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>" />
                            </div>
                            <div class="form-group">
                                <label>Spouse Name</label>
                                <input type="text" class="form-control" name="returnee_spouse" value="<?php echo $pre_data['returnee_spouse'] ? $pre_data['returnee_spouse'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label input-label">Division</label>
                                <div class="select2-primary">
                                    <select class="form-control division" name="permanent_division" style="text-transform: capitalize">
                                        <?php if ($pre_data['permanent_division']) : ?>
                                            <option value="<?php echo strtolower($pre_data['permanent_division']) ?>"><?php echo $pre_data['permanent_division'] ?></option>
                                        <?php else: ?>
                                            <option>Select One</option>
                                        <?php endif ?>
                                        <?php foreach ($divisions as $division) : ?>
                                            <option id="<?php echo $division['id'] ?>" value="<?php echo strtolower($division['name']) ?>" <?php echo $pre_data && $pre_data['permanent_division'] == $division['name'] ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">District</label>
                                <div class="select2-primary">
                                    <select class="form-control district" name="permanent_district" style="text-transform: capitalize" id="districtList">
                                        <?php if ($pre_data['permanent_district']) : ?>
                                            <option value="<?php echo $pre_data['permanent_district'] ?>"><?php echo $pre_data['permanent_district'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Upazila</label>
                                <div class="select2-primary">
                                    <select class="form-control subdistrict" name="permanent_sub_district" style="text-transform: capitalize" id="subdistrictList">
                                        <?php if ($pre_data['permanent_sub_district']) : ?>
                                            <option value="<?php echo $pre_data['permanent_sub_district'] ?>"><?php echo $pre_data['permanent_sub_district'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label input-label">Union</label>
                                <div class="select2-primary">
                                    <select class="form-control union" name="permanent_union" style="text-transform: capitalize" id="unionList">
                                        <?php if ($pre_data['permanent_union']) : ?>
                                            <option value="<?php echo $pre_data['permanent_union'] ?>"><?php echo $pre_data['permanent_union'] ?></option>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
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
                            <div class="form-group">
                                <label class="control-label input-label">Village</label>
                                <input class="form-control" type="text" name="permanent_village" value="<?php echo $pre_data['permanent_village'] ? $pre_data['permanent_village'] : ''; ?>">
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
                                <label class="control-label input-label">Country of Destination (*)</label>
                                <input class="form-control" type="text" required name="destination_country" value="<?php echo $pre_data['destination_country'] ? $pre_data['destination_country'] : ''; ?>">
                            </div>
                            <?php
                            $legal_documents = $legal_documents ? $legal_documents : array($legal_documents);
                            ?>
                            <div class="form-group">
                                <label>Legal Document</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="checkbox" name="legal_document[]" value="Travel document" <?php
                                            if (in_array('Travel document', $legal_documents)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Travel document</span></label>
                                        <label><input class="px" type="checkbox" name="legal_document[]" value="NID" <?php
                                            if (in_array('NID', $legal_documents)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">NID</span></label>
                                        <label><input class="px" type="checkbox" name="legal_document[]" value="Birth Registration" <?php
                                            if (in_array('Birth Registration', $legal_documents)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Birth Registration</span></label>
                                        <label><input class="px" type="checkbox" name="legal_document[]" value="Passport" <?php
                                            if (in_array('Passport', $legal_documents)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Passport</span></label>
                                        <label><input class="px" type="checkbox" name="legal_document[]" value="Smart Card" <?php
                                            if (in_array('Smart Card', $legal_documents)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Smart Card</span></label>
                                        <label><input class="px" type="checkbox" id="newLegalDocument" <?php echo $pre_data && $pre_data['other_legal_document'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="newLegalDocumentType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" id="newLegalDocumentTypeText" name="new_legal_document" value="<?php echo $pre_data['other_legal_document'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#newLegalDocument').is(':checked');

                                    if (isChecked == true) {
                                        $('#newLegalDocumentType').show();
                                    }

                                    $("#newLegalDocument").on("click", function () {
                                        $('#newLegalDocumentType').toggle();
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label>Intention to remigrate</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px oldGender" type="radio" name="remigrate_intention" value="yes" <?php echo $pre_data && $pre_data['remigrate_intention'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                        <label><input class="px oldGender" type="radio" name="remigrate_intention" value="no" <?php echo $pre_data && $pre_data['remigrate_intention'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Educational Qualification</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px educations" type="radio" name="educational_qualification" value="illiterate" <?php echo $pre_data && $pre_data['educational_qualification'] == 'illiterate' ? 'checked' : '' ?>><span class="lbl">Illiterate</span></label>
                                        <label><input class="px educations" type="radio" name="educational_qualification" value="sign" <?php echo $pre_data && $pre_data['educational_qualification'] == 'sign' ? 'checked' : '' ?>><span class="lbl">Can Sign only</span></label>
                                        <label><input class="px educations" type="radio" name="educational_qualification" value="psc" <?php echo $pre_data && $pre_data['educational_qualification'] == 'psc' ? 'checked' : '' ?>><span class="lbl">Primary education (Passed Grade 5)</span></label>
                                        <label><input class="px educations" type="radio" name="educational_qualification" value="not_psc" <?php echo $pre_data && $pre_data['educational_qualification'] == 'not_psc' ? 'checked' : '' ?>><span class="lbl">Did not complete primary education</span></label>
                                        <label><input class="px educations" type="radio" name="educational_qualification" value="jsc" <?php echo $pre_data && $pre_data['educational_qualification'] == 'jsc' ? 'checked' : '' ?>><span class="lbl">Completed JSC (Passed Grade 8)</span></label>
                                        <label><input class="px educations" type="radio" name="educational_qualification" value="ssc" <?php echo $pre_data && $pre_data['educational_qualification'] == 'ssc' ? 'checked' : '' ?>><span class="lbl">Completed School Secondary Certificate</span></label>
                                        <label><input class="px educations" type="radio" name="educational_qualification" value="hsc" <?php echo $pre_data && $pre_data['educational_qualification'] == 'hsc' ? 'checked' : '' ?>><span class="lbl">Higher Secondary Certificate/Diploma/ equivalent</span></label>
                                        <label><input class="px educations" type="radio" name="educational_qualification" value="bachelor" <?php echo $pre_data && $pre_data['educational_qualification'] == 'bachelor' ? 'checked' : '' ?>><span class="lbl">Bachelor’s degree or equivalent</span></label>
                                        <label><input class="px educations" type="radio" name="educational_qualification" value="master" <?php echo $pre_data && $pre_data['educational_qualification'] == 'master' ? 'checked' : '' ?>><span class="lbl">Masters or Equivalent</span></label>
                                        <label><input class="px educations" type="radio" name="educational_qualification" value="professional_education" <?php echo $pre_data && $pre_data['educational_qualification'] == 'professional_education' ? 'checked' : '' ?>><span class="lbl">Completed Professional education</span></label>
                                        <label><input class="px educations" type="radio" name="educational_qualification" value="general_education" <?php echo $pre_data && $pre_data['educational_qualification'] == 'general_education' ? 'checked' : '' ?>><span class="lbl">Completed general Education</span></label>
                                        <label><input class="px" type="radio" name="educational_qualification" id="newQualification"><span class="lbl">Others, Please specify…</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="newQualificationType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" id="newQualificationText" name="new_qualification" value="<?php echo $pre_data['educational_qualification'] ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    $("#newQualification").on("click", function () {
                                        $('#newQualificationType').show();
                                    });

                                    $(".educations").on("click", function () {
                                        $('#newQualificationType').hide();
                                        $('#newQualificationText').val('');
                                    });
                                });
                            </script>
                            <div class="form-group">
                                <label>Occupation in overseas country</label>
                                <input class="form-control" type="text" name="destination_country_profession" value="<?php echo $pre_data['destination_country_profession'] ? $pre_data['destination_country_profession'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Selected for profiling</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px oldGender" type="radio" name="profile_selection" value="yes" <?php echo $pre_data && $pre_data['profile_selection'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                        <label><input class="px oldGender" type="radio" name="profile_selection" value="no" <?php echo $pre_data && $pre_data['profile_selection'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea class="form-control" name="remarks"><?php echo $pre_data['remarks'] ? $pre_data['remarks'] : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="panel-footer tar">
        <a href="<?php echo url('admin/dev_returnee_management/manage_returnees') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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