<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_returnee', 'edit_returnee')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();
$migrationHistory = array();
if ($edit) {
    $pre_data = $this->get_returnees(array('customer_id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid returnee migrant, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
    $migrationHistory = $this->get_migration_history(array('customer_id' => $edit, 'data_only' => true));
    $migrationHistory = $migrationHistory['data'];

    $duration = array();
    $days = 0;
    $months = 0;
    $years = 0;
    foreach ($migrationHistory as $v_duration) {
        $days += $v_duration['duration_days'];
        $months += $v_duration['duration_month'];
        $years += $v_duration['duration_year'];
    }
}

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches(array('single' => false));

if ($_POST['ajax_type']) {
    if ($_POST['ajax_type'] == 'uniqueBmet') {
        $sql = "SELECT pk_customer_id FROM dev_customers WHERE bmet_card_number = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND customer_status = 'active' AND NOT pk_customer_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    } elseif ($_POST['ajax_type'] == 'uniqueNID') {
        $sql = "SELECT pk_customer_id FROM dev_customers WHERE nid_number = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND customer_status = 'active' AND NOT pk_customer_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    } elseif ($_POST['ajax_type'] == 'uniqueBirth') {
        $sql = "SELECT pk_customer_id FROM dev_customers WHERE birth_reg_number = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND customer_status = 'active' AND NOT pk_customer_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    } elseif ($_POST['ajax_type'] == 'uniquePassport') {
        $sql = "SELECT pk_customer_id FROM dev_customers WHERE passport_number = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND customer_status = 'active' AND NOT pk_customer_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    } elseif ($_POST['ajax_type'] == 'uniqueMobile') {
        $sql = "SELECT pk_customer_id FROM dev_customers WHERE customer_mobile = '" . $_POST['valueToCheck'] . "'";
        if ($edit) {
            $sql .= " AND customer_status = 'active' AND NOT pk_customer_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    } else if ($_POST['ajax_type'] == 'selectStaff') {
        $staffs = jack_obj('dev_staff_management');
        $all_staffs = $staffs->get_staffs(array('branch' => $_POST['branch_id']));
        foreach ($all_staffs['data'] as $staff) {
            if ($staff['pk_user_id'] == $pre_data['fk_staff_id']) {
                echo "<option value='" . $staff['pk_user_id'] . "' selected>" . $staff['user_fullname'] . "</option>";
            } else {
                echo "<option value='" . $staff['pk_user_id'] . "'>" . $staff['user_fullname'] . "</option>";
            }
        }
    } else if ($_POST['ajax_type'] == 'delete_migration_history') {
        $item_ID = $_POST['data_id'];
        if (!$edit)
            echo json_encode(array('error' => array('Invalid delete request')));
        else {
            if (!$item_ID)
                echo json_encode(array('error' => array('No data to delete')));
            else {
                $ret = $devdb->query("DELETE FROM dev_customer_migrations WHERE pk_migration_id = '" . $item_ID . "'");
                if ($ret) {
                    $this->goThroughMigrationHistory($edit);
                    echo json_encode(array('success' => 1));
                } else
                    echo json_encode(array('error' => array('Could not delete the item, please try again')));
            }
        }
    }
    exit();
}

if ($_POST) {
    $data = array(
        'required' => array(
            'full_name' => 'Full Name'
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $msg = array();
    if ($data['form_data']['bmet_card_number']) {
        $sql = "SELECT pk_customer_id FROM dev_customers WHERE bmet_card_number = '" . $data['form_data']['bmet_card_number'] . "'";
        if ($edit) {
            $sql .= " AND customer_status = 'active' AND NOT pk_customer_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['bmet'] = "This BMET Card holder is already in our Database";
        }
    }
    if ($data['form_data']['nid_number']) {
        $sql = "SELECT pk_customer_id FROM dev_customers WHERE nid_number = '" . $data['form_data']['nid_number'] . "'";
        if ($edit) {
            $sql .= " AND customer_status = 'active' AND NOT pk_customer_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['nid'] = "This NID holder is already in our Database";
        }
    }
    if ($data['form_data']['birth_reg_number']) {
        $sql = "SELECT pk_customer_id FROM dev_customers WHERE birth_reg_number = '" . $data['form_data']['birth_reg_number'] . "'";
        if ($edit) {
            $sql .= " AND customer_status = 'active' AND NOT pk_customer_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['birth'] = "This Birth Registration holder is already in our Database";
        }
    }
    if ($data['form_data']['passport_number']) {
        $sql = "SELECT pk_customer_id FROM dev_customers WHERE passport_number = '" . $data['form_data']['passport_number'] . "'";
        if ($edit) {
            $sql .= " AND customer_status = 'active' AND NOT pk_customer_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['passport'] = "This Passport holder is already in our Database";
        }
    }
    if ($data['form_data']['customer_mobile']) {
        $sql = "SELECT pk_customer_id FROM dev_customers WHERE customer_mobile = '" . $data['form_data']['customer_mobile'] . "'";
        if ($edit) {
            $sql .= " AND customer_status = 'active' AND NOT pk_customer_id = '$edit'";
        }
        $sql .= " LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            $msg['mobile'] = "This Mobile holder is already in our Database";
        }
    }

    $message = implode('.<br>', $msg);
    if ($message) {
        add_notification($message, 'error');
        header('location: ' . url('admin/dev_customer_management/manage_returnee_migrants'));
        exit();
    }

    $ret = $this->add_edit_returnee($data);

    if ($ret['customer_insert'] || $ret['customer_update']) {
        $returnee_id = $edit ? $edit : $ret['customer_insert']['success'];
        $returneeData = $this->get_returnees(array('customer_id' => $returnee_id, 'single' => true));
        $msg = "Basic information of returnee migrant " . $returneeData['full_name'] . " (ID: " . $returneeData['customer_id'] . ") has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_customer_management/manage_returnee_migrants?action=add_edit_returnee_migrant&edit=' . $returnee_id));
        } else {
            header('location: ' . url('admin/dev_customer_management/manage_returnee_migrants'));
        }
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

$all_genders = $this->get_lookups('gender');
$all_religions = $this->get_lookups('religion');
$all_educational_qualifications = $this->get_lookups('educational_qualification');
$all_natural_disasters = $this->get_lookups('natural_disaster');
$all_economic_impacts = $this->get_lookups('economic_impacts');
$all_social_impacts = $this->get_lookups('social_impacts');
$all_countries = getWorldCountry();
$all_countries_json = json_encode($all_countries);
$all_transports = $this->get_lookups('transport_modes');
$all_visas = $this->get_lookups('visa_type');
$all_migration_medias = $this->get_lookups('migration_medias');
$all_reasons_for_leave_destination_country = $this->get_lookups('destination_country_leave_reason');
$all_spent_types = $this->get_lookups('spent_types');
$all_immediate_supports = $this->get_lookups('immediate_support');
$all_cooperations = $this->get_lookups('cooperation_type');
$all_chronic_diseases = $this->get_lookups('disease_type');
$all_loan_sources = $this->get_lookups('loan_sources');
$all_residence_ownership_types = $this->get_lookups('current_residence_ownership');
$all_residence_types = $this->get_lookups('current_residence_type');
$all_technical_skills = $this->get_lookups('technical_skills');
$all_non_technical_skills = $this->get_lookups('non_technical_skills');
$all_soft_skills = $this->get_lookups('soft_skills');

$staffs = jack_obj('dev_staff_management');
$branch_id = $_config['user']['user_branch'];
if ($branch_id) {
    $all_staffs = $staffs->get_staffs(array('branch' => $branch_id));
}

doAction('render_start');
?>
<style type="text/css">
    .removeReadOnly {
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Returnee Migrant</h1>
    <?php if ($pre_data) : ?>
        <h4 class="text-primary">Returnee Migrant: <?php echo $pre_data['full_name'] ?></h4>
        <h4 class="text-primary">ID: <?php echo $pre_data['customer_id'] ?></h4>
    <?php endif; ?>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Returnee Migrants',
                'title' => 'Manage Returnee Migrants',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
    <?php if (!$edit) : ?>
        <div class="panel" id="basicForm">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label>BMET Card Number</label>
                            <div class="input-group">
                                <input data-verified="no" data-ajax-type="uniqueBmet" data-error-message="This BMET holder is already in our Database" id="bmet" class="verifyUnique form-control" type="text" name="bmet_card_number">
                                <span class="input-group-addon"></span>
                            </div>
                            <p class="help-block"></p>
                        </div>
                        <div class="form-group">
                            <label>NID Number</label>
                            <div class="input-group">
                                <input data-verified="no" data-ajax-type="uniqueNID" data-error-message="This NID holder is already in our Database" id="nid" class="verifyUnique form-control" type="text" name="nid_number">
                                <span class="input-group-addon"></span>
                            </div>
                            <p class="help-block"></p>
                        </div>
                        <div class="form-group">
                            <label>Birth Registration Number</label>
                            <div class="input-group">
                                <input data-verified="no" data-ajax-type="uniqueBirth" data-error-message="This Birth Registration holder is already in our Database" id="birth" class="verifyUnique form-control" type="text" name="birth_reg_number">
                                <span class="input-group-addon"></span>
                            </div>
                            <p class="help-block"></p>
                        </div>
                        <div class="form-group">
                            <label>Passport Number</label>
                            <div class="input-group">
                                <input data-verified="no" data-ajax-type="uniquePassport" data-error-message="This Passport holder is already in our Database" id="passport" class="verifyUnique form-control" type="text" name="passport_number">
                                <span class="input-group-addon"></span>
                            </div>
                            <p class="help-block"></p>
                        </div>
                        <div class="form-group">
                            <label>Last Destination Country</label>
                            <select class="form-control" name="last_visited_country" id="lastCountry" required>
                                <option value="">Select One</option>
                                <?php
                                foreach ($all_countries as $value) {
                                    ?>
                                    <option value="<?php echo $value ?>" <?php if ($pre_data['last_visited_country'] == $value) echo 'selected' ?>><?php echo $value ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Phone/Mobile Number</label>
                            <div class="input-group">
                                <input required data-verified="no" data-ajax-type="uniqueMobile" data-error-message="This Mobile holder is already in our Database" id="mobile" class="verifyUnique form-control" type="text" name="customer_mobile">
                                <span class="input-group-addon"></span>
                            </div>
                            <p class="help-block"></p>
                        </div>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input class="form-control" type="text" id="fullName" required name="full_name">
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="alert alert-danger" style="display:none">
                            <span id="IdDanger"></span>
                            <span id="danger"></span>
                        </div>
                        <a href="javascript:" id="checkNext" class="btn btn-flat btn-labeled btn-info"><i class="fa fa-cog btn-label"></i>Check &amp; Proceed</a>
                    </div>
                </div>
            </div>
        </div>
        <script>
            init.push(function () {
                $("#checkNext").on("click", function () {
                    $('#IdDanger').html('');
                    $('#danger').html('');
                    var bmet = $('#bmet').val();
                    var nid = $('#nid').val();
                    var birth = $('#birth').val();
                    var passport = $('#passport').val();

                    if (!(bmet && nid && birth && passport)) {
                        $('.alert-danger').show();
                        $('#IdDanger').append('Please enter any <strong>BMET, NID, Birth Registration or Passport number</strong>.<br>');
                    }

                    var lastCountry = $('#lastCountry').val();
                    if (!lastCountry) {
                        $('.alert-danger').show();
                        $('#danger').append('Please select <strong>Last Destination Country</strong>.<br>');
                    }

                    var mobile = $('#mobile').val();
                    if (!mobile) {
                        $('.alert-danger').show();
                        $('#danger').append('Please enter <strong>Phone/Mobile Number</strong>.<br>');
                    }

                    var name = $('#fullName').val();
                    if (!name) {
                        $('.alert-danger').show();
                        $('#danger').append('Please enter <strong>Full Name</strong>.<br>');
                    }

                    var bmetV = $('#bmet').attr('data-verified') == 'yes' ? true : false;
                    var nidV = $('#nid').attr('data-verified') == 'yes' ? true : false;
                    var birthV = $('#birth').attr('data-verified') == 'yes' ? true : false;
                    var passportV = $('#passport').attr('data-verified') == 'yes' ? true : false;
                    var mobileV = $('#mobile').attr('data-verified') == 'yes' ? true : false;

                    if (bmetV || nidV || birthV || passportV) {
                        $('#IdDanger').html('');
                    }

                    if ((bmet || nidV || birthV || passportV) && (lastCountry && mobileV && name)) {
                        $('#basicForm').slideUp();
                        $('#fullForm').slideDown();
                    }
                });
            });
        </script>
    <?php endif; ?>
    <div class="panel" id="fullForm" style="<?php echo!$edit ? 'display: none;' : '' ?>">
        <div class="panel-body">
            <div class="side_aligned_tab">
                <ul id="uidemo-tabs-default-demo" class="nav nav-tabs">
                    <li class="active">
                        <a href="#personalInfo" data-toggle="tab">Personal Information</a>
                    </li>
                    <li class="">
                        <a href="#climateChange" data-toggle="tab">Climate Change Impact</a>
                    </li>
                    <li class="">
                        <a href="#migration" data-toggle="tab">Migration Information</a>
                    </li>
                    <li class="">
                        <a href="#immediateSupport" data-toggle="tab">Immediate Support</a>
                    </li>
                    <li class="">
                        <a href="#cooperation" data-toggle="tab">Received Cooperation</a>
                    </li>
                    <li class="">
                        <a href="#psychosocial" data-toggle="tab">Psychosocial and Health</a>
                    </li>
                    <li class="">
                        <a href="#socioEconomic" data-toggle="tab">Socio-Economic Profile</a>
                    </li>
                    <li class="">
                        <a href="#skills" data-toggle="tab">Skills Information</a>
                    </li>
                    <li class="">
                        <a href="#plan" data-toggle="tab">Reintegration Plan</a>
                    </li>
                    <li class="">
                        <a href="#baseline" data-toggle="tab">Baseline Status</a>
                    </li>
                </ul>
                <div class="tab-content tab-content-bordered">
                    <div class="tab-pane fade active in" id="personalInfo">
                        <fieldset>
                            <legend>Section 1: Personal Information of the Beneficiary</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?php if ($edit) : ?>
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input class="form-control" type="text" name="full_name" value="<?php echo $pre_data['full_name'] ? $pre_data['full_name'] : ''; ?>" required>
                                        </div>
                                    <?php endif ?>
                                    <div class="form-group">
                                        <label>Mother's Name</label>
                                        <input class="form-control" type="text" name="mother_name" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Father's Name</label>
                                        <input class="form-control" type="text" name="father_name" value="<?php echo $pre_data['father_name'] ? $pre_data['father_name'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Profile Picture</label>
                                        <?php
                                        if ($pre_data['customer_photo']) {
                                            ?>
                                            <div class="old_image_holder">
                                                <div class="old_image">
                                                    <img src="<?php echo user_picture($pre_data['customer_photo']); ?>" />
                                                    <input type="hidden" name="customer_old_photo" value="<?php echo $pre_data['customer_photo'] ?>">
                                                </div>
                                            </div>
                                            <div class="new_image mt10">
                                                <input type="hidden" name="customer_photo_hidden" value="<?php echo $pre_data['customer_photo'] ?>">
                                                <input type="file" id="profile_picture" name="customer_photo">
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        $('#profile_picture').pixelFileInput({
                                                            placeholder: 'No file selected...'
                                                        });
                                                    })
                                                </script>
                                                <p class="help-block">JPG or PNG image with max file size 500KB &amp; MAX 300x300 resolution.</p>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="new_image">
                                                <input type="hidden" name="customer_photo_hidden" value="<?php echo $pre_data ? $pre_data['customer_photo'] : "" ?>">
                                                <input type="file" id="profile_picture" name="customer_photo">
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        $('#profile_picture').pixelFileInput({
                                                            placeholder: 'No file selected...'
                                                        });
                                                    })
                                                </script>
                                                <p class="help-block">JPG or PNG image with max file size 500KB &amp; MAX 300x300 resolution.</p>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Marital Status</label>
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <div class="options_holder radio">
                                                        <label><input class="px notMarried" type="radio" name="marital_status" value="single" <?php echo $pre_data && $pre_data['marital_status'] == 'single' ? 'checked' : '' ?>><span class="lbl">Single</span></label>
                                                        <label><input class="px" id="isMarried" type="radio" name="marital_status" value="married" <?php echo $pre_data && $pre_data['marital_status'] == 'married' ? 'checked' : '' ?>><span class="lbl">Married</span></label>
                                                        <label><input class="px notMarried" type="radio" name="marital_status" value="divorced" <?php echo $pre_data && $pre_data['marital_status'] == 'divorced' ? 'checked' : '' ?>><span class="lbl">Divorced</span></label>
                                                        <label><input class="px notMarried" type="radio" name="marital_status" value="widowed" <?php echo $pre_data && $pre_data['marital_status'] == 'widowed' ? 'checked' : '' ?>><span class="lbl">Widowed</span></label>
                                                        <label><input class="px notMarried" type="radio" name="marital_status" value="separated" <?php echo $pre_data && $pre_data['marital_status'] == 'separated' ? 'checked' : '' ?>><span class="lbl">Separated</span></label>
                                                        <label><input class="px" type="radio" name="marital_status"><span class="lbl">Others</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="spouse" style="display: none; margin-bottom: 1em;">
                                                <input class="form-control" placeholder="Enter Spouse Name" type="text" name="customer_spouse" value="<?php echo $pre_data['customer_spouse'] ? $pre_data['customer_spouse'] : ''; ?>">
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
                                                    });
                                                });
                                            </script>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <div class="options_holder radio">
                                                        <?php
                                                        foreach ($all_genders['data'] as $gender) {
                                                            ?>
                                                            <label><input class="px oldGender" type="radio" name="customer_gender" value="<?php echo $gender['lookup_value'] ?>" <?php echo $pre_data && $pre_data['customer_gender'] == $gender['lookup_value'] ? 'checked' : '' ?>><span class="lbl"><?php echo $gender['lookup_value'] ?></span></label>
                                                        <?php } ?>
                                                        <label><input class="px" type="radio" name="customer_gender" id="newGender"><span class="lbl">Other</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="newGenderType" style="display: none; margin-bottom: 1em;">
                                                <input class="form-control" placeholder="Please Specity" type="text" name="new_gender" value="">
                                            </div>
                                            <script>
                                                init.push(function () {
                                                    $("#newGender").on("click", function () {
                                                        $('#newGenderType').show();
                                                    });

                                                    $(".oldGender").on("click", function () {
                                                        $('#newGenderType').hide();
                                                    });
                                                });
                                            </script>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label>Educational Qualification</label>
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <div class="options_holder radio">
                                                        <?php
                                                        foreach ($all_educational_qualifications['data'] as $qualification) {
                                                            ?>
                                                            <label><input class="px educations" type="radio" name="educational_qualification" value="<?php echo $qualification['lookup_value'] ?>" <?php
                                                                if ($pre_data['educational_qualification'] == $qualification['lookup_value']) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl"><?php echo $qualification['lookup_value'] ?></span></label>
                                                                      <?php } ?>
                                                        <label><input class="px" type="radio" name="educational_qualification" id="newQualification"><span class="lbl">Others</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="newQualificationType" style="display: none; margin-bottom: 1em;">
                                                <input class="form-control" placeholder="Please Specity" type="text" name="new_qualification" value="">
                                            </div>
                                            <script>
                                                init.push(function () {
                                                    $("#newQualification").on("click", function () {
                                                        $('#newQualificationType').show();
                                                    });

                                                    $(".educations").on("click", function () {
                                                        $('#newQualificationType').hide();
                                                    });
                                                });
                                            </script>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Religion</label>
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <div class="options_holder radio">
                                                        <?php
                                                        foreach ($all_religions['data'] as $religion) {
                                                            ?>
                                                            <label><input class="px religions" type="radio" name="customer_religion" value="<?php echo $religion['lookup_value'] ?>" <?php echo $pre_data && $pre_data['customer_religion'] == $religion['lookup_value'] ? 'checked' : '' ?>><span class="lbl"><?php echo $religion['lookup_value'] ?></span></label>
                                                        <?php } ?>
                                                        <label><input class="px" type="radio" name="customer_religion" id="newReligion"><span class="lbl">Other</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="newReligionType" style="display: none; margin-bottom: 1em;">
                                                <input class="form-control" placeholder="Please Specity" type="text" name="new_religion" value="">
                                            </div>
                                            <script>
                                                init.push(function () {
                                                    $("#newReligion").on("click", function () {
                                                        $('#newReligionType').show();
                                                    });

                                                    $(".religions").on("click", function () {
                                                        $('#newReligionType').hide();
                                                    });
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Date of Birth <?php if ($edit) : ?> (Age: <?php echo floor((time() - strtotime(date('d-m-Y', strtotime($pre_data['customer_birthdate'])))) / (60 * 60 * 24 * 365)) ?>) <?php endif ?></label>
                                        <div class="input-group">
                                            <input id="birthdate" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
                                        </div>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('birthdate');
                                            });
                                        </script>
                                    </div>
                                    <?php if ($edit) : ?>
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
                                            <label>Phone/Mobile Number</label>
                                            <div class="input-group">
                                                <input data-verified="no" data-ajax-type="uniqueMobile" data-error-message="This Mobile holder is already in our Database" class="verifyUnique form-control" id="mobile" type="text" name="customer_mobile" value="<?php echo $pre_data['customer_mobile'] ? $pre_data['customer_mobile'] : ''; ?>">
                                                <span class="input-group-addon"></span>
                                            </div>
                                            <p class="help-block"></p>
                                        </div>
                                        <div class="form-group">
                                            <label>BMET Smart Card Number</label>
                                            <div class="input-group">
                                                <input data-verified="no" data-ajax-type="uniqueBmet" data-error-message="This BMET holder is already in our Database" class="verifyUnique form-control" id="bmet" type="text" name="bmet_card_number" value="<?php echo $pre_data['bmet_card_number'] ? $pre_data['bmet_card_number'] : ''; ?>">
                                                <span class="input-group-addon"></span>
                                            </div>
                                            <p class="help-block"></p>
                                        </div>
                                    <?php endif ?>
                                    <div class="form-group">
                                        <label>Travel Pass</label>
                                        <input class="form-control" type="text" name="travel_pass" value="<?php echo $pre_data['travel_pass'] ? $pre_data['travel_pass'] : ''; ?>">
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Emergency</legend>
                                        <label class="control-label input-label">Emergency Mobile No</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="emergency_mobile" value="<?php echo $pre_data['emergency_mobile'] ? $pre_data['emergency_mobile'] : ''; ?>" />
                                        </div>
                                        <label class="control-label input-label">Name</label>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="emergency_name" value="<?php echo $pre_data['emergency_name'] ? $pre_data['emergency_name'] : ''; ?>">
                                        </div>
                                        <label class="control-label input-label">Relation with Returnee</label>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="emergency_relation" value="<?php echo $pre_data['emergency_relation'] ? $pre_data['emergency_relation'] : ''; ?>">
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <fieldset>
                                        <legend>Permanent Address</legend>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Flat</label>
                                                    <input class="form-control" type="text" name="permanent_flat" value="<?php echo $pre_data['permanent_flat'] ? $pre_data['permanent_flat'] : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>House</label>
                                                    <input class="form-control" type="text" name="permanent_house" value="<?php echo $pre_data['permanent_house'] ? $pre_data['permanent_house'] : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Road</label>
                                                    <input class="form-control" type="text" name="permanent_road" value="<?php echo $pre_data['permanent_road'] ? $pre_data['permanent_road'] : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Ward</label>
                                                    <input class="form-control" type="text" name="permanent_ward" value="<?php echo $pre_data['permanent_ward'] ? $pre_data['permanent_ward'] : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Village</label>
                                                    <input class="form-control" type="text" name="permanent_village" value="<?php echo $pre_data['permanent_village'] ? $pre_data['permanent_village'] : ''; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Union</label>
                                                    <input class="form-control" type="text" name="permanent_union" value="<?php echo $pre_data['present_union'] ? $pre_data['present_union'] : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Division</label>
                                                    <div class="select2-primary">
                                                        <select class="form-control" id="permanent_division" name="permanent_division" data-selected="<?php echo $pre_data['permanent_division'] ? $pre_data['permanent_division'] : '' ?>"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>District</label>
                                                    <div class="select2-success">
                                                        <select class="form-control" id="permanent_district" name="permanent_district" data-selected="<?php echo $pre_data['permanent_district'] ? $pre_data['permanent_district'] : ''; ?>"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sub-District</label>
                                                    <div class="select2-success">
                                                        <select class="form-control" id="permanent_sub_district" name="permanent_sub_district" data-selected="<?php echo $pre_data['permanent_sub_district'] ? $pre_data['permanent_sub_district'] : ''; ?>"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Police Station</label>
                                                    <div class="select2-info">
                                                        <select class="form-control" id="permanent_police_station" name="permanent_police_station" data-selected="<?php echo $pre_data['permanent_police_station'] ? $pre_data['permanent_police_station'] : ''; ?>"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Post Office</label>
                                                    <div class="select2-warning">
                                                        <select class="form-control" id="permanent_post_office" name="permanent_post_office" data-selected="<?php echo $pre_data['permanent_post_office'] ? $pre_data['permanent_post_office'] : ''; ?>"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Post Code</label>
                                                    <input class="form-control" type="text" name="permanent_post_code" value="<?php echo $pre_data['permanent_post_code'] ? $pre_data['permanent_post_code'] : ''; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-sm-6">
                                    <fieldset>
                                        <legend>Present Address</legend>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Flat</label>
                                                    <input class="form-control" type="text" name="present_flat" value="<?php echo $pre_data['present_flat'] ? $pre_data['present_flat'] : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>House</label>
                                                    <input class="form-control" type="text" name="present_house" value="<?php echo $pre_data['present_house'] ? $pre_data['present_house'] : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Road</label>
                                                    <input class="form-control" type="text" name="present_road" value="<?php echo $pre_data['present_road'] ? $pre_data['present_road'] : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Ward</label>
                                                    <input class="form-control" type="text" name="present_ward" value="<?php echo $pre_data['present_ward'] ? $pre_data['present_ward'] : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Village</label>
                                                    <input class="form-control" type="text" name="present_village" value="<?php echo $pre_data['present_village'] ? $pre_data['present_village'] : ''; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Union</label>
                                                    <input class="form-control" type="text" name="present_union" value="<?php echo $pre_data['present_union'] ? $pre_data['present_union'] : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Division</label>
                                                    <div class="select2-primary">
                                                        <select class="form-control" id="present_division" name="present_division" data-selected="<?php echo $pre_data['present_division'] ? $pre_data['present_division'] : '' ?>"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>District</label>
                                                    <div class="select2-success">
                                                        <select class="form-control" id="present_district" name="present_district" data-selected="<?php echo $pre_data['present_district'] ? $pre_data['present_district'] : ''; ?>"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sub-District</label>
                                                    <div class="select2-success">
                                                        <select class="form-control" id="present_sub_district" name="present_sub_district" data-selected="<?php echo $pre_data['present_sub_district'] ? $pre_data['present_sub_district'] : ''; ?>"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Police Station</label>
                                                    <div class="select2-info">
                                                        <select class="form-control" id="present_police_station" name="present_police_station" data-selected="<?php echo $pre_data['present_police_station'] ? $pre_data['present_police_station'] : ''; ?>"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Post Office</label>
                                                    <div class="select2-warning">
                                                        <select class="form-control" id="present_post_office" name="present_post_office" data-selected="<?php echo $pre_data['present_post_office'] ? $pre_data['present_post_office'] : ''; ?>"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Post Code</label>
                                                    <input class="form-control" type="text" name="present_post_code" value="<?php echo $pre_data['present_post_code'] ? $pre_data['present_post_code'] : ''; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <?php if (!$branch_id) : ?>
                                        <div class="form-group">
                                            <label>Name of Center Office/Branch</label>
                                            <select class="form-control" name="branch_id">
                                                <option value="">Select One</option>
                                                <?php
                                                foreach ($all_branches['data'] as $value) :
                                                    ?>
                                                    <option value="<?php echo $value['pk_branch_id'] ?>" <?php
                                                    if ($value['pk_branch_id'] == $pre_data['fk_branch_id']) {
                                                        echo 'selected';
                                                    }
                                                    ?>><?php echo $value['branch_name'] ?></option>
                                                        <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Name of Case Manager</label>
                                            <select class="form-control" data-selected="<?php echo $pre_data['fk_staff_id'] ? $pre_data['fk_staff_id'] : ''; ?>" id="availableStaffs" name="staff_id">

                                            </select>
                                        </div>
                                    <?php endif ?>
                                    <?php if ($branch_id): ?>
                                        <input type="hidden" name="branch_id" value="<?php echo $_config['user']['user_branch'] ?>"/>
                                        <div class="form-group">
                                            <label>Name of Case Manager</label>
                                            <select required class="form-control" name="staff_id">
                                                <option value="">Select One</option>
                                                <?php
                                                foreach ($all_staffs['data'] as $value) :
                                                    ?>
                                                    <option value="<?php echo $value['pk_user_id'] ?>" <?php
                                                    if ($value['pk_user_id'] == $pre_data['fk_staff_id']) {
                                                        echo 'selected';
                                                    }
                                                    ?>><?php echo $value['user_fullname'] ?></option>
                                                        <?php endforeach ?>
                                            </select>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="climateChange">
                        <fieldset>
                            <legend>Section 2: Climate Change Impact on Migration</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Consequent migration occurs due to natural disasters caused by climate change?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="climateYes" name="climate_effect" value="yes" <?php echo $pre_data && $pre_data['climate_effect'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="climateNo" name="climate_effect" value="no" <?php echo $pre_data && $pre_data['climate_effect'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $('.disaster').hide();
                                            $('.economic').hide();
                                            var isChecked = $('#climateYes').is(':checked');

                                            if (isChecked == true) {
                                                $('.disaster').show();
                                                $('.economic').show();
                                            }

                                            $("#climateYes").on("click", function () {
                                                $('.disaster').show();
                                                $('.economic').show();
                                            });

                                            $("#climateNo").on("click", function () {
                                                $('.disaster').hide();
                                                $('.economic').hide();
                                            });
                                        });
                                    </script>
                                    <div class="form-group disaster">
                                        <label>Nature of disaster</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <?php
                                                $disaster_types = explode(',', $pre_data['natural_disaster']);
                                                foreach ($all_natural_disasters['data'] as $disaster) {
                                                    ?>
                                                    <label><input class="px" type="checkbox" name="natural_disaster[]" value="<?php echo $disaster['lookup_value'] ?>" <?php
                                                        if (in_array($disaster['lookup_value'], $disaster_types)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl"><?php echo $disaster['lookup_value'] ?></span></label>
                                                              <?php } ?>
                                                <label><input class="px" type="checkbox" id="newDisaster"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newDisasterType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_disaster" value="">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#newDisaster").on("click", function () {
                                                $('#newDisasterType').toggle();
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="col-sm-6 economic">
                                    <div class="form-group">
                                        <label>Economic Impact of Change</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <?php
                                                $economic_impacts = explode(',', $pre_data['economic_impacts']);
                                                foreach ($all_economic_impacts['data'] as $impact) {
                                                    ?>
                                                    <label><input class="px" type="checkbox" name="economic_impacts[]" value="<?php echo $impact['lookup_value'] ?>" <?php
                                                        if (in_array($impact['lookup_value'], $economic_impacts)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl"><?php echo $impact['lookup_value'] ?></span></label>
                                                              <?php } ?>
                                                <label><input class="px" type="checkbox" id="newEconomicImpact"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newEconomicImpactType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_economic_impact" value="">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#newEconomicImpact").on("click", function () {
                                                $('#newEconomicImpactType').toggle();
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Financial Losses</label>
                                        <input class="form-control" type="number" name="financial_losses" value="<?php echo $pre_data['financial_losses'] ? $pre_data['financial_losses'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Social Impact of Change</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <?php
                                                $social_impacts = explode(',', $pre_data['social_impacts']);
                                                foreach ($all_social_impacts['data'] as $impact) {
                                                    ?>
                                                    <label><input class="px" type="checkbox" name="social_impacts[]" value="<?php echo $impact['lookup_value'] ?>" <?php
                                                        if (in_array($impact['lookup_value'], $social_impacts)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl"><?php echo $impact['lookup_value'] ?></span></label>
                                                              <?php } ?>
                                                <label><input class="px" type="checkbox" id="newSocialImpact"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newSocialImpactType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_social_impact" value="">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#newSocialImpact").on("click", function () {
                                                $('#newSocialImpactType').toggle();
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Whether overseas migration is the main reason for climate change?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" name="is_climate_migration" value="yes" <?php echo $pre_data && $pre_data['is_climate_migration'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" name="is_climate_migration" value="no" <?php echo $pre_data && $pre_data['is_climate_migration'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="migration">
                        <fieldset>
                            <legend>Section 3: Migration Related Information</legend>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="table-primary">
                                        <div class="table-header">Migration History - <?php echo $years . ' Years, ' . $months . ' Months, ' . $days . ' Days' ?></div>
                                        <table class="table table-bordered table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Country</th>
                                                    <th>Entry Date</th>
                                                    <th>Exit Date</th>
                                                    <th class="text-right">...</th>
                                                </tr>
                                            </thead>
                                            <tbody id="migration_history_rows">

                                            </tbody>
                                        </table>
                                        <div class="table-footer">
                                            <a href="javascript:" id="btn_add_migration_history" class="action_link"><i class="fa fa-plus-circle"></i>&nbsp;Add Another</a>    
                                        </div>
                                        <script type="text/javascript">
                                            var allCountries = <?php echo $all_countries_json ?>;
                                            var oldMigrationHistory = <?php echo $migrationHistory ? json_encode($migrationHistory) : '{}' ?>;
                                            var countryOptions = '';
                                            for (var i in allCountries) {
                                                countryOptions += '<option value="' + i + '">' + allCountries[i] + '</option>';
                                            }
                                            var migrationHistoryCount = 1;
                                            init.push(function () {
                                                function fixMigrationSerialNumber() {
                                                    $('#migration_history_rows tr').each(function (i, e) {
                                                        $(e).find('.sl_num').html(i + 1);
                                                    });
                                                }
                                                function addNewMigrationHistory(data) {
                                                    if (typeof data === 'undefined')
                                                        data = null;
                                                    var thisRowID = 'migration_history_row_' + migrationHistoryCount;
                                                    var thisEntryDateID = 'migration_entry_date_' + migrationHistoryCount;
                                                    var thisExitDateID = 'migration_exit_date_' + migrationHistoryCount;
                                                    var _html = '\
                                                    <tr id="' + thisRowID + '">\
                                                        <td class="tac vam sl_num">' + migrationHistoryCount + '</td>\
                                                        <td>\
                                                            <input class="migration_id" type="hidden" name="eachMigrationHistory[' + migrationHistoryCount + '][id]" value="" />\
                                                            <select class="form-control migration_country" name="eachMigrationHistory[' + migrationHistoryCount + '][country]">' + countryOptions + '</select>\
                                                        </td>\
                                                        <td>\
                                                            <div class="input-group date hideInputGroupAddons">\
                                                                <input type="text" readonly id="' + thisEntryDateID + '" class="form-control migration_entry_date" name="eachMigrationHistory[' + migrationHistoryCount + '][entry_date]" />\
                                                            </div>\
                                                        </td>\
                                                        <td>\
                                                            <div class="input-group date hideInputGroupAddons">\
                                                                <input type="text" readonly id="' + thisExitDateID + '" class="form-control migration_exit_date" name="eachMigrationHistory[' + migrationHistoryCount + '][exit_date]" />\
                                                            </div>\
                                                        </td>\
                                                        <td class="text-right vam">\
                                                            <a href="javascript:" data-id="' + (data ? data.pk_migration_id : '') + '" class="btn btn-xs btn-danger remove_migration_history_row"><i class="fa fa-trash"></i></a>\
                                                        </td>\
                                                    </tr>\
                                                    ';
                                                    $('#migration_history_rows').append(_html);
                                                    if (data) {
                                                        $('#' + thisRowID).find('.migration_id').val(data.pk_migration_id);
                                                        $('#' + thisRowID).find('.migration_country').val(data.fk_country_id);
                                                        $('#' + thisRowID).find('.migration_entry_date').val(data.entry_date);
                                                        $('#' + thisRowID).find('.migration_exit_date').val(data.exit_date);
                                                    }
                                                    _datepicker(thisEntryDateID, {format: 'yyyy-mm-dd'});
                                                    _datepicker(thisExitDateID, {format: 'yyyy-mm-dd'});
                                                    // $('#'+thisRowID).find('.migration_country').select2();

                                                    ++migrationHistoryCount;
                                                    fixMigrationSerialNumber();
                                                }

                                                $("#btn_add_migration_history").click(function (e) {
                                                    addNewMigrationHistory();
                                                });

                                                $(document).on('click', '.remove_migration_history_row', function () {
                                                    var ths = $(this);
                                                    var dataID = ths.attr('data-id');
                                                    if (dataID.length) {
                                                        bootboxConfirm({
                                                            title: 'Delete Migration History',
                                                            msg: 'Are you sure abour deleting this migration history?<br /><br /><span class="text-danger">This item will be deleted permanently.</span>',
                                                            confirm: {
                                                                callback: function () {
                                                                    basicAjaxCall({
                                                                        data: {
                                                                            ajax_type: 'delete_migration_history',
                                                                            data_id: dataID
                                                                        },
                                                                        success: function (ret) {
                                                                            if (ret.hasOwnProperty('error') && Object.keys(ret.error).length) {
                                                                                growl_error(ret.error);
                                                                            } else {
                                                                                ths.closest('tr').remove();
                                                                                fixMigrationSerialNumber();
                                                                            }
                                                                        }
                                                                    });
                                                                }
                                                            },
                                                        });
                                                    } else {
                                                        ths.closest('tr').remove();
                                                        fixMigrationSerialNumber();
                                                    }
                                                });
                                                if (oldMigrationHistory) {
                                                    for (var i in oldMigrationHistory) {
                                                        addNewMigrationHistory(oldMigrationHistory[i]);
                                                    }
                                                }
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Migration Status</label>
                                        <div class="form-group">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="migration_status" value="remigrated" <?php echo $pre_data && $pre_data['migration_status'] == 'remigrated' ? 'checked' : '' ?>><span class="lbl">Already Re-Migrated</span></label>
                                                    <label><input class="px" type="radio" name="migration_status" value="want_to_remigrated" <?php echo $pre_data && $pre_data['migration_status'] == 'want_to_remigrated' ? 'checked' : '' ?>><span class="lbl">Want To Re-Migrate</span></label>
                                                    <label><input class="px" type="radio" name="migration_status" value="not_interested" <?php echo $pre_data && $pre_data['migration_status'] == 'not_interested' ? 'checked' : '' ?>><span class="lbl">Not Interested In Re-Migration</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <fieldset>
                                        <legend>Situation while abroad</legend>
                                        <div class="form-group">
                                            <label>Whether during the time of abroad the victim was cheated by fraud / temptation in the job?</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="is_cheated" value="yes" <?php echo $pre_data && $pre_data['is_cheated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="is_cheated" value="no" <?php echo $pre_data && $pre_data['is_cheated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Whether the money has been deducted from salary as a recruitment fee / cost?</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="is_money_deducted" value="yes" <?php echo $pre_data && $pre_data['is_money_deducted'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="is_money_deducted" value="no" <?php echo $pre_data && $pre_data['is_money_deducted'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Limitation of movement (only from Residence to workplace)</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="is_movement_limitation" value="yes" <?php echo $pre_data && $pre_data['is_movement_limitation'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="is_movement_limitation" value="no" <?php echo $pre_data && $pre_data['is_movement_limitation'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Whether the employer or middleman has kept the travel related necessary documents (Passport) after leaving destination country</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="is_kept_document" value="yes" <?php echo $pre_data && $pre_data['is_kept_document'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="is_kept_document" value="no" <?php echo $pre_data && $pre_data['is_kept_document'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Overall experience of migration</label>
                                            <textarea class="form-control" name="migration_experience"><?php echo $pre_data['migration_experience'] ? $pre_data['migration_experience'] : ''; ?></textarea>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-sm-6">
                                    <fieldset>
                                        <legend>Situation during the process of migration</legend>
                                        <div class="form-group">
                                            <label>Left Bangladesh from which port?</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="left_port" value="airport" <?php echo $pre_data && $pre_data['left_port'] == 'airport' ? 'checked' : '' ?>><span class="lbl">Airport</span></label>
                                                    <label><input class="px" type="radio" name="left_port" value="seaport" <?php echo $pre_data && $pre_data['left_port'] == 'seaport' ? 'checked' : '' ?>><span class="lbl">Seaport</span></label>
                                                    <label><input class="px" type="radio" name="left_port" value="landport" <?php echo $pre_data && $pre_data['left_port'] == 'landport' ? 'checked' : '' ?>><span class="lbl">Land-Port</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Preferred Country</label>
                                            <select class="form-control" name="preferred_country">
                                                <option value="">Select One</option>
                                                <?php
                                                foreach ($all_countries as $value) :
                                                    ?>
                                                    <option value="<?php echo $value ?>" <?php
                                                    if ($value == $pre_data['preferred_country']) {
                                                        echo 'selected';
                                                    }
                                                    ?>><?php echo $value ?></option>
                                                        <?php endforeach ?>
                                            </select>
                                        </div>
                                        <?php if ($edit) : ?>
                                            <div class="form-group">
                                                <label>Last Destination Country</label>
                                                <select class="form-control" name="last_visited_country">
                                                    <option value="">Select One</option>
                                                    <?php
                                                    foreach ($all_countries as $value) :
                                                        ?>
                                                        <option value="<?php echo $value ?>" <?php
                                                        if ($value == $pre_data['last_visited_country']) {
                                                            echo 'selected';
                                                        }
                                                        ?>><?php echo $value ?></option>
                                                            <?php endforeach ?>
                                                </select>
                                            </div>
                                        <?php endif ?>
                                        <div class="form-group">
                                            <label>Root/Country</label>
                                            <input class="form-control" type="text" name="access_path" value="<?php echo $pre_data['access_path'] ? $pre_data['access_path'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Mode of transport</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $transport_types = explode(',', $pre_data['transport_modes']);
                                                    foreach ($all_transports['data'] as $transport) {
                                                        ?>
                                                        <label><input class="px" type="checkbox" name="transport_modes[]" value="<?php echo $transport['lookup_value'] ?>" <?php
                                                            if (in_array($transport['lookup_value'], $transport_types)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $transport['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="checkbox" id="newTransport"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newTransportType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_transport" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newTransport").on("click", function () {
                                                    $('#newTransportType').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group">
                                            <label>Type of Migration</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="migration_type" value="regular" <?php echo $pre_data && $pre_data['migration_type'] == 'regular' ? 'checked' : '' ?>><span class="lbl">Regular</span></label>
                                                    <label><input class="px" type="radio" name="migration_type" value="irregular" <?php echo $pre_data && $pre_data['migration_type'] == 'irregular' ? 'checked' : '' ?>><span class="lbl">Irregular</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Type of Visa</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $visa_types = explode(',', $pre_data['visa_type']);
                                                    foreach ($all_visas['data'] as $visa) {
                                                        ?>
                                                        <label><input class="px visas" type="radio" name="visa_type" value="<?php echo $visa['lookup_value'] ?>" <?php
                                                            if (in_array($visa['lookup_value'], $visa_types)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $visa['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="radio" name="visa_type" id="newVisa"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newVisaType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_visa" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newVisa").on("click", function () {
                                                    $('#newVisaType').show();
                                                });
                                                $(".visas").on("click", function () {
                                                    $('#newVisaType').hide();
                                                });
                                            });
                                        </script>
                                        <div class="form-group">
                                            <label>Media of migration</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $migration_media_types = explode(',', $pre_data['migration_medias']);
                                                    foreach ($all_migration_medias['data'] as $migration_media) {
                                                        ?>
                                                        <label><input class="px" type="checkbox" name="migration_medias[]" value="<?php echo $migration_media['lookup_value'] ?>" <?php
                                                            if (in_array($migration_media['lookup_value'], $migration_media_types)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $migration_media['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="checkbox" id="newMigrationMedia"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newMigrationMediaType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_migration_media" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newMigrationMedia").on("click", function () {
                                                    $('#newMigrationMediaType').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group">
                                            <label>Cost of Migration (BDT)</label>
                                            <input class="form-control" type="number" name="migration_cost" value="<?php echo $pre_data['migration_cost'] ? $pre_data['migration_cost'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Pay to Middleman/Agency</label>
                                            <input class="form-control" type="number" name="agency_payment" value="<?php echo $pre_data['agency_payment'] ? $pre_data['agency_payment'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Occupation while abroad</label>
                                            <input class="form-control" type="text" name="migration_occupation" value="<?php echo $pre_data['migration_occupation'] ? $pre_data['migration_occupation'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Reason of leave destination country</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $leave_types = explode(',', $pre_data['destination_country_leave_reason']);
                                                    foreach ($all_reasons_for_leave_destination_country['data'] as $leave) {
                                                        ?>
                                                        <label><input class="px" type="checkbox" name="destination_country_leave_reason[]" value="<?php echo $leave['lookup_value'] ?>" <?php
                                                            if (in_array($leave['lookup_value'], $leave_types)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $leave['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="checkbox" id="newLeave"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newLeaveType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_leave_reason" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newLeave").on("click", function () {
                                                    $('#newLeaveType').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group">
                                            <label>Total amount of money earned during stay in abroad (BDT):</label>
                                            <input class="form-control" type="number" name="earned_money" value="<?php echo $pre_data['earned_money'] ? $pre_data['earned_money'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Amount of money to send at home while abroad (BDT): </label>
                                            <input class="form-control" type="number" name="sent_money" value="<?php echo $pre_data['sent_money'] ? $pre_data['sent_money'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Area of expenditure of remittance after return in source country</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $spent_types = explode(',', $pre_data['spent_types']);
                                                    foreach ($all_spent_types['data'] as $spent) {
                                                        ?>
                                                        <label><input class="px" type="checkbox" name="spent_types[]" value="<?php echo $spent['lookup_value'] ?>" <?php
                                                            if (in_array($spent['lookup_value'], $spent_types)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $spent['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="checkbox" id="newSpent"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newSpentType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_spent" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newSpent").on("click", function () {
                                                    $('#newSpentType').toggle();
                                                });
                                            });
                                        </script>
                                    </fieldset>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="immediateSupport">
                        <fieldset>
                            <legend>Section 4: Immediate support services received</legend>
                            <div class="form-group">
                                <label>Immediate Support Service</label>
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <?php
                                        $immediate_supports = explode(',', $pre_data['immediate_support']);
                                        foreach ($all_immediate_supports['data'] as $support) {
                                            ?>
                                            <label><input class="px" type="checkbox" name="immediate_support[]" value="<?php echo $support['lookup_value'] ?>" <?php
                                                if (in_array($support['lookup_value'], $immediate_supports)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl"><?php echo $support['lookup_value'] ?></span></label>
                                                      <?php } ?>
                                        <label><input class="px" type="checkbox" id="newSupport"><span class="lbl">Others</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="newSupportType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control" placeholder="Please Specity" type="text" name="new_support" value="">
                            </div>
                            <script>
                                init.push(function () {
                                    $("#newSupport").on("click", function () {
                                        $('#newSupportType').toggle();
                                    });
                                });
                            </script>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="cooperation">
                        <fieldset>
                            <legend>Section 5: The cooperation that has been received since returning to the country</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Cooperation from Government-nongovernment organization?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesCooperated" name="is_cooperated" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noCooperated" name="is_cooperated" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group cooperation" style="display:none">
                                        <label>Name of Organization</label>
                                        <input class="form-control" type="text" name="organization_name" value="<?php echo $pre_data['organization_name'] ? $pre_data['organization_name'] : ''; ?>">
                                    </div>
                                </div>
                                <script>
                                    init.push(function () {
                                        var isChecked = $('#yesCooperated').is(':checked');

                                        if (isChecked == true) {
                                            $('.cooperation').show();
                                        }

                                        $("#yesCooperated").on("click", function () {
                                            $('.cooperation').show();
                                        });

                                        $("#noCooperated").on("click", function () {
                                            $('.cooperation').hide();
                                        });
                                    });
                                </script>
                                <div class="col-sm-6">
                                    <div class="form-group cooperation" style="display:none">
                                        <label>Type of cooperation</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <?php
                                                foreach ($all_cooperations['data'] as $cooperation) {
                                                    ?>
                                                    <label><input class="px" type="radio" name="cooperation_type" value="<?php echo $cooperation['lookup_value'] ?>" <?php
                                                        if ($pre_data['cooperation_type'] == $cooperation['lookup_value']) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl"><?php echo $cooperation['lookup_value'] ?></span></label>
                                                              <?php } ?>
                                                <label><input class="px" type="radio" name="cooperation_type" id="newCooperation"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newCooperationType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_cooperation" value="">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#newCooperation").on("click", function () {
                                                $('#newCooperationType').show();
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Interested to remigration?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" name="is_remigration_interest" value="yes" <?php echo $pre_data && $pre_data['is_remigration_interest'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" name="is_remigration_interest" value="no" <?php echo $pre_data && $pre_data['is_remigration_interest'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="psychosocial">
                        <fieldset>
                            <legend>Section 6: Psychosocial and Health Related Risk Assessment</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Any kind of challenge?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesPhysical" name="is_physically_challenged" value="yes" <?php echo $pre_data && $pre_data['is_physically_challenged'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noPhysical" name="is_physically_challenged" value="no" <?php echo $pre_data && $pre_data['is_physically_challenged'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#yesPhysical').is(':checked');

                                            if (isChecked == true) {
                                                $('#disabilityType').show();
                                            }

                                            $("#yesPhysical").on("click", function () {
                                                $('#writeDisabilityType').show();
                                            });

                                            $("#noPhysical").on("click", function () {
                                                $('#writeDisabilityType').hide();
                                            });
                                        });
                                    </script>
                                    <div class="form-group" id="writeDisabilityType" style="display:none;">
                                        <label>Type of challenge</label>
                                        <input class="form-control" type="text" name="disability_type" value="<?php echo $pre_data['disability_type'] ? $pre_data['disability_type'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Chronic physical disorders?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" name="having_chronic_disease" id="yesDisease" value="yes" <?php echo $pre_data && $pre_data['having_chronic_disease'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" name="having_chronic_disease" id="noDisease" value="no" <?php echo $pre_data && $pre_data['having_chronic_disease'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="diseases" style="display: none;">
                                        <div class="form-group">
                                            <label>The type of disease</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $disease_types = explode(',', $pre_data['disease_type']);
                                                    foreach ($all_chronic_diseases['data'] as $disease) {
                                                        ?>
                                                        <label><input class="px" type="checkbox" name="disease_type[]" value="<?php echo $disease['lookup_value'] ?>" <?php
                                                            if (in_array($disease['lookup_value'], $disease_types)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $disease['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="checkbox" id="newDisease"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newDiseaseType" style="display: none;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_disease_type" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#yesDisease').is(':checked');

                                                if (isChecked == true) {
                                                    $('#diseases').show();
                                                }

                                                $("#yesDisease").on("click", function () {
                                                    $('#diseases').show();
                                                });
                                                $("#noDisease").on("click", function () {
                                                    $('#diseases').hide();
                                                });

                                                $("#newDisease").on("click", function () {
                                                    $('#newDiseaseType').toggle();
                                                });
                                            });
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <label>Need psychosocial support?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" name="need_psychosocial_support" value="yes" <?php echo $pre_data && $pre_data['need_psychosocial_support'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" name="need_psychosocial_support" value="no" <?php echo $pre_data && $pre_data['need_psychosocial_support'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="socioEconomic">
                        <fieldset>
                            <legend>Section 7: Socio-economic profile</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Occupation before migration</label>
                                        <input class="form-control" type="text" name="pre_occupation" value="<?php echo $pre_data['pre_occupation'] ? $pre_data['pre_occupation'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Present occupation</label>
                                        <input class="form-control" type="text" name="present_occupation" value="<?php echo $pre_data['present_occupation'] ? $pre_data['present_occupation'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Present monthly income (BDT)</label>
                                        <input class="form-control" type="number" name="present_income" value="<?php echo $pre_data['present_income'] ? $pre_data['present_income'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Mark Beneficiary As</label>
                                        <div class="form-group">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="economic_condition" value="poor" <?php echo $pre_data && $pre_data['economic_condition'] == 'poor' ? 'checked' : '' ?>><span class="lbl">Poor</span></label>
                                                    <label><input class="px" type="radio" name="economic_condition" value="disadvantage" <?php echo $pre_data && $pre_data['economic_condition'] == 'disadvantage' ? 'checked' : '' ?>><span class="lbl">Disadvantage</span></label>
                                                    <label><input class="px" type="radio" name="economic_condition" value="marginalized" <?php echo $pre_data && $pre_data['economic_condition'] == 'marginalized' ? 'checked' : '' ?>><span class="lbl">Marginalized</span></label>
                                                    <label><input class="px" type="radio" name="economic_condition" value="mid" <?php echo $pre_data && $pre_data['economic_condition'] == 'mid' ? 'checked' : '' ?>><span class="lbl">Mid And Above Income</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Number of household member</legend>
                                        <label class="control-label input-label">Male</label>
                                        <div class="form-group">
                                            <input class="form-control" id="maleMember" type="number" name="male_household_member" value="<?php echo $pre_data['male_household_member'] ? $pre_data['male_household_member'] : ''; ?>">
                                        </div>
                                        <label class="control-label input-label">Female</label>
                                        <div class="form-group">
                                            <input class="form-control" id="femaleMember" type="number" name="female_household_member" value="<?php echo $pre_data['female_household_member'] ? $pre_data['female_household_member'] : ''; ?>">
                                        </div>
                                        <label class="control-label input-label">Total</label>
                                        <div class="form-group">
                                            <input class="form-control" id="totalMember" type="number" name="total_member" value="<?php echo $pre_data['total_member'] ? $pre_data['total_member'] : ''; ?>">
                                        </div>
                                    </fieldset>
                                    <script>
                                        init.push(function () {
                                            $("#maleMember").on("change", function () {
                                                var maleMember = $(this).val();
                                                var femaleMember = $('#femaleMember').val();
                                                var total = Number(maleMember) + Number(femaleMember);
                                                $('#totalMember').val(total);
                                            });

                                            $("#femaleMember").on("change", function () {
                                                var maleMember = $(this).val();
                                                var femaleMember = $('#maleMember').val();
                                                var total = Number(maleMember) + Number(femaleMember);
                                                $('#totalMember').val(total);
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Number of household member depends on the returnee’s income</label>
                                        <input class="form-control" type="number" name="total_dependent_member" value="<?php echo $pre_data['total_dependent_member'] ? $pre_data['total_dependent_member'] : ''; ?>">
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Number of earning household member</legend>
                                        <label class="control-label input-label">Male</label>
                                        <div class="form-group">
                                            <input class="form-control" id="maleEarner" type="number" name="male_earning_member" value="<?php echo $pre_data['male_earning_member'] ? $pre_data['male_earning_member'] : ''; ?>">
                                        </div>
                                        <label class="control-label input-label">Female</label>
                                        <div class="form-group">
                                            <input class="form-control" id="femaleEarner" type="number" name="female_earning_member" value="<?php echo $pre_data['female_earning_member'] ? $pre_data['female_earning_member'] : ''; ?>">
                                        </div>
                                        <label class="control-label input-label">Total</label>
                                        <div class="form-group">
                                            <input class="form-control" id="totalEarner" type="number" name="total_earner" value="<?php echo $pre_data['total_earner'] ? $pre_data['total_earner'] : ''; ?>">
                                        </div>
                                    </fieldset>
                                    <script>
                                        init.push(function () {
                                            $("#maleEarner").on("change", function () {
                                                var maleEarner = $(this).val();
                                                var femaleEarner = $('#femaleEarner').val();
                                                var total = Number(maleEarner) + Number(femaleEarner);
                                                $('#totalEarner').val(total);
                                            });

                                            $("#femaleEarner").on("change", function () {
                                                var maleEarner = $(this).val();
                                                var femaleEarner = $('#maleEarner').val();
                                                var total = Number(maleEarner) + Number(femaleEarner);
                                                $('#totalEarner').val(total);
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Monthly household income</label>
                                        <input class="form-control" type="number" name="household_income" value="<?php echo $pre_data['household_income'] ? $pre_data['household_income'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Monthly household expenditure</label>
                                        <input class="form-control" type="number" name="household_expenditure" value="<?php echo $pre_data['household_expenditure'] ? $pre_data['household_expenditure'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Returnee’s personal savings (BDT)</label>
                                        <input class="form-control" type="text" name="personal_savings" value="<?php echo $pre_data['personal_savings'] ? $pre_data['personal_savings'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Amount of personal debt of the Returnee</label>
                                        <input class="form-control" type="number" name="personal_debt" value="<?php echo $pre_data['personal_debt'] ? $pre_data['personal_debt'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Remittance Expend In</label>
                                        <input class="form-control" type="text" name="remittance_expend" value="<?php echo $pre_data['remittance_expend'] ? $pre_data['remittance_expend'] : ''; ?>">
                                    </div>
                                    <div class="form-group ">
                                        <label>Loan Source</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <?php
                                                $loan_sources = explode(',', $pre_data['loan_sources']);
                                                foreach ($all_loan_sources['data'] as $source) {
                                                    ?>
                                                    <label><input class="px" type="checkbox" name="loan_sources[]" value="<?php echo $source['lookup_value'] ?>" <?php
                                                        if (in_array($source['lookup_value'], $loan_sources)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl"><?php echo $source['lookup_value'] ?></span></label>
                                                              <?php } ?>
                                                <label><input class="px" type="checkbox" id="newSource"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newSourceType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_loan_source" value="">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#newSource").on("click", function () {
                                                $('#newSourceType').toggle();
                                            });
                                        });
                                    </script>
                                    <div class="form-group ">
                                        <label>Mortgage property</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" name="have_mortgages" value="yes" id="yesMortgage" <?php
                                                    if ($pre_data['have_mortgages'] == 'yes') {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" name="have_mortgages" value="no" id="noMortgage" <?php
                                                    if ($pre_data['have_mortgages'] == 'no') {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="MortgageAmount" style="display: none; margin-bottom: 1em;">
                                        <div class="form-group">
                                            <label>Mortgage Name/Type</label>
                                            <input class="form-control" type="text" name="mortgage_name" value="<?php echo $pre_data['mortgage_name'] ? $pre_data['mortgage_name'] : '' ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Mortgage Value</label>
                                            <input class="form-control" type="number" name="mortgage_value" value="<?php echo $pre_data['mortgage_value'] ? $pre_data['mortgage_value'] : '' ?>">
                                        </div>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#yesMortgage').is(':checked');

                                            if (isChecked == true) {
                                                $('#MortgageAmount').show();
                                            }

                                            $("#yesMortgage").on("click", function () {
                                                $('#MortgageAmount').show();
                                            });
                                            $("#noMortgage").on("click", function () {
                                                $('#MortgageAmount').hide();
                                            });
                                        });
                                    </script>
                                    <div class="form-group ">
                                        <label>The type of ownership of the Residence</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <?php
                                                foreach ($all_residence_ownership_types['data'] as $ownership) {
                                                    ?>
                                                    <label><input class="px" type="radio" name="current_residence_ownership" value="<?php echo $ownership['lookup_value'] ?>" <?php
                                                        if ($pre_data['current_residence_ownership'] == $ownership['lookup_value']) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl"><?php echo $ownership['lookup_value'] ?></span></label>
                                                              <?php } ?>
                                                <label><input class="px" type="radio" name="current_residence_ownership" id="newOwnership"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newOwnershipType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_ownership" value="">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#newOwnership").on("click", function () {
                                                $('#newOwnershipType').show();
                                            });
                                        });
                                    </script>
                                    <div class="form-group ">
                                        <label>Type of residence</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <?php
                                                foreach ($all_residence_types['data'] as $residence) {
                                                    ?>
                                                    <label><input class="px" type="radio" name="current_residence_type" value="<?php echo $residence['lookup_value'] ?>" <?php
                                                        if ($pre_data['current_residence_type'] == $residence['lookup_value']) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl"><?php echo $residence['lookup_value'] ?></span></label>
                                                              <?php } ?>
                                                <label><input class="px" type="radio" name="current_residence_type" id="newResidence"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newResidenceType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_residence" value="">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#newResidence").on("click", function () {
                                                $('#newResidenceType').show();
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="skills">
                        <fieldset>
                            <legend>Section 8: Skills Related Information</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group ">
                                        <label>Is there a certification required for any particular work previously acquired?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" name="is_certification_required" value="yes" id="yesCertification" <?php
                                                    if ($pre_data['is_certification_required'] == 'yes') {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" name="is_certification_required" value="no" id="noCertification" <?php
                                                    if ($pre_data['is_certification_required'] == 'no') {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-offset-1" id="certifications" style="display: none;">
                                        <div class="form-group">
                                            <label>Technical Skills</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $technical_skills = explode(',', $pre_data['required_certification']);
                                                    foreach ($all_technical_skills['data'] as $technical) {
                                                        ?>
                                                        <label><input class="px" type="checkbox" name="technical_skills[]" value="<?php echo $technical['lookup_value'] ?>" <?php
                                                            if (in_array($technical['lookup_value'], $technical_skills)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $technical['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="checkbox" id="newTechnical"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newTechnicalType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_technical" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newTechnical").on("click", function () {
                                                    $('#newTechnicalType').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group">
                                            <label>Non-Technical Skills</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $non_technical_skills = explode(',', $pre_data['required_certification']);
                                                    foreach ($all_non_technical_skills['data'] as $non_technical) {
                                                        ?>
                                                        <label><input class="px" type="checkbox" name="non_technical_skills[]" value="<?php echo $non_technical['lookup_value'] ?>" <?php
                                                            if (in_array($non_technical['lookup_value'], $non_technical_skills)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $non_technical['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="checkbox" id="newNon-Technical"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newNon-TechnicalType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_non_technical" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newNon-Technical").on("click", function () {
                                                    $('#newNon-TechnicalType').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group">
                                            <label>Soft Skills</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $soft_skills = explode(',', $pre_data['required_certification']);
                                                    foreach ($all_soft_skills['data'] as $soft) {
                                                        ?>
                                                        <label><input class="px" type="checkbox" name="soft_skills[]" value="<?php echo $soft['lookup_value'] ?>" <?php
                                                            if (in_array($soft['lookup_value'], $soft_skills)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $soft['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="checkbox" id="newSoft"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newSoftType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_soft" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#yesCertification').is(':checked');

                                                if (isChecked == true) {
                                                    $('#certifications').show();
                                                }

                                                $("#newSoft").on("click", function () {
                                                    $('#newSoftType').toggle();
                                                });

                                                $("#yesCertification").on("click", function () {
                                                    $('#certifications').show();
                                                });

                                                $("#noCertification").on("click", function () {
                                                    $('#certifications').hide();
                                                });

                                                $("#newCertification").on("click", function () {
                                                    $('#newCertificationType').toggle();
                                                });
                                            });
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <label>Have any income earner skills?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesIncomeEarner" name="have_earner_skill" value="yes" <?php echo $pre_data && $pre_data['have_earner_skill'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noIncomeEarner" name="have_earner_skill" value="no" <?php echo $pre_data && $pre_data['have_earner_skill'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-offset-1" id="beforeAbroadSkills" style="display: none;">
                                        <div class="form-group">
                                            <label>Technical Skills</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $have_skills = explode(',', $pre_data['have_skills']);
                                                    foreach ($all_technical_skills['data'] as $technical) {
                                                        ?>
                                                        <label><input class="px" type="checkbox" name="technical_have_skills[]" value="<?php echo $technical['lookup_value'] ?>" <?php
                                                            if (in_array($technical['lookup_value'], $have_skills)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $technical['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="checkbox" id="newHaveTechnical"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newHaveTechnicalType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_have_technical" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newHaveTechnical").on("click", function () {
                                                    $('#newHaveTechnicalType').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group">
                                            <label>Non-Technical Skills</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    foreach ($all_non_technical_skills['data'] as $non_technical) {
                                                        ?>
                                                        <label><input class="px" type="checkbox" name="non_technical_have_skills[]" value="<?php echo $non_technical['lookup_value'] ?>" <?php
                                                            if (in_array($non_technical['lookup_value'], $have_skills)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $non_technical['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="checkbox" id="newHaveNon-Technical"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newHaveNon-TechnicalType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_non_have_technical" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newHaveNon-Technical").on("click", function () {
                                                    $('#newHaveNon-TechnicalType').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group">
                                            <label>Soft Skills</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    foreach ($all_soft_skills['data'] as $soft) {
                                                        ?>
                                                        <label><input class="px" type="checkbox" name="soft_have_skills[]" value="<?php echo $soft['lookup_value'] ?>" <?php
                                                            if (in_array($soft['lookup_value'], $have_skills)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $soft['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="checkbox" id="newHaveSoft"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newHaveSoftType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_have_soft" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#yesIncomeEarner').is(':checked');

                                                if (isChecked == true) {
                                                    $('#beforeAbroadSkills').show();
                                                }

                                                $("#newHaveSoft").on("click", function () {
                                                    $('#newHaveSoftType').toggle();
                                                });

                                                $("#yesIncomeEarner").on("click", function () {
                                                    $('#beforeAbroadSkills').show();
                                                });

                                                $("#noIncomeEarner").on("click", function () {
                                                    $('#beforeAbroadSkills').hide();
                                                });

                                                $("#newSkill").on("click", function () {
                                                    $('#newSkillType').toggle();
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Do you have interested for skills training?</label>
                                    </div>
                                    <div class="col-md-offset-1">
                                        <div class="form-group">
                                            <label>Technical Skills</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    $need_skills = explode(',', $pre_data['need_skills']);
                                                    foreach ($all_technical_skills['data'] as $technical) {
                                                        ?>
                                                        <label><input class="px" type="checkbox" name="technical_need_skills[]" value="<?php echo $technical['lookup_value'] ?>" <?php
                                                            if (in_array($technical['lookup_value'], $need_skills)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $technical['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="checkbox" id="newNeedTechnical"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newNeedTechnicalType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_need_technical" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newNeedTechnical").on("click", function () {
                                                    $('#newNeedTechnicalType').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group">
                                            <label>Non-Technical Skills</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    foreach ($all_non_technical_skills['data'] as $non_technical) {
                                                        ?>
                                                        <label><input class="px" type="checkbox" name="non_technical_need_skills[]" value="<?php echo $non_technical['lookup_value'] ?>" <?php
                                                            if (in_array($non_technical['lookup_value'], $need_skills)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $non_technical['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="checkbox" id="newNeedNon-Technical"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newNeedNon-TechnicalType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_non_need_technical" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newNeedNon-Technical").on("click", function () {
                                                    $('#newNeedNon-TechnicalType').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group">
                                            <label>Soft Skills</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <?php
                                                    foreach ($all_soft_skills['data'] as $soft) {
                                                        ?>
                                                        <label><input class="px" type="checkbox" name="soft_need_skills[]" value="<?php echo $soft['lookup_value'] ?>" <?php
                                                            if (in_array($soft['lookup_value'], $need_skills)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl"><?php echo $soft['lookup_value'] ?></span></label>
                                                                  <?php } ?>
                                                    <label><input class="px" type="checkbox" id="newNeedSoft"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newNeedSoftType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control" placeholder="Please Specity" type="text" name="new_need_soft" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newNeedSoft").on("click", function () {
                                                    $('#newNeedSoftType').toggle();
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="plan">
                        <fieldset>
                            <legend>Section 9: Reintegration Plan</legend>
                            <?php
                            $reintegration_plan = explode(',', $pre_data['reintegration_plan']);
                            ?>
                            <div class="form-group">
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label><input class="px" type="checkbox" name="reintegration_plan[]" id="psychosocialPlan" value="psycho" <?php
                                            if (in_array('psycho', $reintegration_plan)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Psychosocial Reintegration Support</span></label>
                                        <label><input class="px" type="checkbox" name="reintegration_plan[]" id="socialPlan" value="social" <?php
                                            if (in_array('social', $reintegration_plan)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Social Reintegration Support</span></label>
                                        <label><input class="px" type="checkbox" name="reintegration_plan[]" id="economicPlan" value="economic" <?php
                                            if (in_array('economic', $reintegration_plan)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Economic Reintegration Support</span></label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="baseline">
                        <fieldset>
                            <legend>Section 10: Baseline Status</legend>
                            <div class="row">
                                <div class="col-sm-4" id="psychosocialBaseLine" style="display:none">
                                    <h4>Psychosocial</h4>
                                    <?php
                                    foreach ($this->evaluationQA as $dbCol => $eachQA) {
                                        if ($eachQA['type'] !== 'psycho')
                                            continue;
                                        ?>
                                        <div class="form-group">
                                            <label><?php echo $eachQA['q'] ?></label>
                                            <select class="form-control" name="<?php echo $dbCol ?>">
                                                <option value="">Select One</option>
                                                <?php
                                                foreach ($eachQA['a'] as $aValue => $aLabel) {
                                                    $selected = $pre_data && $pre_data[$dbCol] == $aValue ? 'selected' : '';
                                                    ?>
                                                    <option value="<?php echo $aValue ?>" <?php echo $selected ?>><?php echo $aLabel ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-4" id="socialBaseLine" style="display:none">
                                    <h4>Social</h4>
                                    <?php
                                    foreach ($this->evaluationQA as $dbCol => $eachQA) {
                                        if ($eachQA['type'] !== 'social')
                                            continue;
                                        ?>
                                        <div class="form-group">
                                            <label><?php echo $eachQA['q'] ?></label>
                                            <select class="form-control" name="<?php echo $dbCol ?>">
                                                <option value="">Select One</option>
                                                <?php
                                                foreach ($eachQA['a'] as $aValue => $aLabel) {
                                                    $selected = $pre_data && $pre_data[$dbCol] == $aValue ? 'selected' : '';
                                                    ?>
                                                    <option value="<?php echo $aValue ?>" <?php echo $selected ?>><?php echo $aLabel ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-4" id="economicBaseLine" style="display:none">
                                    <h4>Economic Status</h4>
                                    <?php
                                    foreach ($this->evaluationQA as $dbCol => $eachQA) {
                                        if ($eachQA['type'] !== 'economic')
                                            continue;
                                        ?>
                                        <div class="form-group">
                                            <label><?php echo $eachQA['q'] ?></label>
                                            <select class="form-control" name="<?php echo $dbCol ?>">
                                                <option value="">Select One</option>
                                                <?php
                                                foreach ($eachQA['a'] as $aValue => $aLabel) {
                                                    $selected = $pre_data && $pre_data[$dbCol] == $aValue ? 'selected' : '';
                                                    ?>
                                                    <option value="<?php echo $aValue ?>" <?php echo $selected ?>><?php echo $aLabel ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <script>
                                    init.push(function () {
                                        var psychosocialPlan = $('#psychosocialPlan').is(':checked');

                                        if (psychosocialPlan == true) {
                                            $('#psychosocialBaseLine').show();
                                        }

                                        var socialPlan = $('#socialPlan').is(':checked');

                                        if (socialPlan == true) {
                                            $('#socialBaseLine').show();
                                        }

                                        var economicPlan = $('#economicPlan').is(':checked');

                                        if (economicPlan == true) {
                                            $('#economicBaseLine').show();
                                        }

                                        $("#psychosocialPlan").on("click", function () {
                                            $('#psychosocialBaseLine').toggle();
                                        });

                                        $("#socialPlan").on("click", function () {
                                            $('#socialBaseLine').toggle();
                                        });

                                        $("#economicPlan").on("click", function () {
                                            $('#economicBaseLine').toggle();
                                        });
                                    });
                                </script>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_customer_management/manage_returnee_migrants') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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
        $("select[name='branch_id']").change(function () {
            $('#availableStaffs').html('');
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    data: {
                        'branch_id': stateID,
                        'ajax_type': 'selectStaff'
                    },
                    success: function (data) {
                        $('#availableStaffs').html(data);
                    }
                });
            }
        }).change();

        $('#profile_picture').change(function () {
            var thsName = $(this).prop('name');
            $('[name="' + thsName + '_hidden"]').val($(this).val());
        });

        new bd_new_location_selector({
            'division': $('#present_division'),
            'district': $('#present_district'),
            'sub_district': $('#present_sub_district'),
            'police_station': $('#present_police_station'),
            'post_office': $('#present_post_office'),
        });

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