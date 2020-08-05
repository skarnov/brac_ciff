<?php
//error_reporting(0);
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_customer', 'edit_customer')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_customers(array('customer_id' => $edit, 'single' => true));
    $migration_medias = json_decode($pre_data['migration_medias']);
    $migration_reasons = explode(',', $pre_data['migration_reasons']);

    $leave_reasons = explode(',', $pre_data['destination_country_leave_reason']);
    $have_skills = explode(',', $pre_data['have_skills']);
    $disease_types = explode(',', $pre_data['disease_type']);

    if (!$pre_data) {
        add_notification('Invalid participant, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST['ajax_type']) {
    if ($_POST['ajax_type'] == 'uniqueNID') {
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
    }
    exit();
}

if ($_POST) {
    $data = array(
        'required' => array(
            'full_name' => 'Full Name',
            'father_name' => 'Father Name',
            'customer_birthdate' => 'Customer Birthdate',
            'customer_mobile' => 'Customer Mobile',
            'emergency_mobile' => 'Emergency Mobile No',
            'emergency_name' => 'Name of that person',
            'emergency_relation' => 'Relation with Participant',
            'educational_qualification' => 'Educational Qualification',
            'customer_gender' => 'Sex',
            'marital_status' => 'Marital Status',
            'permanent_division' => 'Division',
            'permanent_sub_district' => 'Upazila',
            'permanent_district' => 'District',
            'permanent_house' => 'Address',
            'male_household_member' => 'Number of Accompany/ Number of Family Member (Male)',
            'female_household_member' => 'Number of Accompany/ Number of Family Member (Female)',
            'left_port' => 'Port of exit from Bangladesh',
            'preferred_country' => 'Desired destination',
            'final_destination' => 'Final destination',
            'migration_type' => 'Type of Channels',
            'visa_type' => 'Type of visa',
            'departure_media' => 'Media of Departure (Name)',
            'media_address' => 'Media of Departure (Address)',
            'departure_date' => 'Date of Departure from Bangladesh (*)',
            'return_date' => 'Date of Return to Bangladesh',
            'migration_occupation' => 'Occupation in overseas country',
            'migration_reasons' => 'Reasons for Migration',
            'destination_country_leave_reason' => 'Reasons for returning to Bangladesh',
            'pre_occupation' => 'Main occupation (before trafficking)',
            'present_income' => 'Monthly income of returnee after return(in BDT)',
            'personal_savings' => 'Savings (BDT)',
            'personal_debt' => 'Loan Amount',
            'current_residence_ownership' => 'Ownership of House',
            'current_residence_type' => 'Type of house',
            'have_earner_skill' => 'IGA Skills',
            'is_physically_challenged' => 'Do you have any disability?',
            'having_chronic_disease' => 'Any Chronic Disease?'
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $msg = array();
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

    $message = implode('.<br>', $msg);
    if ($message) {
        add_notification($message, 'error');
        header('location: ' . url('admin/dev_customer_management/manage_customers'));
        exit();
    }

    $ret = $this->add_edit_customer($data);

    if ($ret['customer_insert'] || $ret['customer_update']) {
        $customer_id = $edit ? $edit : $ret['customer_insert']['success'];
        $customer_data = $this->get_customers(array('customer_id' => $customer_id, 'single' => true));
        $msg = "Basic information of participant profile " . $customer_data['full_name'] . " (ID: " . $customer_data['customer_id'] . ") has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_customer_management/manage_customers?action=add_edit_customer&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_customer_management/manage_customers'));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Participant Profile </h1>
    <?php if ($pre_data) : ?>
        <h4 class="text-primary">Participant Profile : <?php echo $pre_data['full_name'] ?></h4>
        <h4 class="text-primary">ID: <?php echo $pre_data['customer_id'] ?></h4>
    <?php endif; ?>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Participant Profile',
                'title' => 'Manage Participant Profile',
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
            <div class="side_aligned_tab">
                <ul id="uidemo-tabs-default-demo" class="nav nav-tabs">
                    <li class="active">
                        <a href="#personalInfo" data-toggle="tab">Section 1: Personal information</a>
                    </li>
                    <li class="">
                        <a href="#migration" data-toggle="tab">Section 2: Migration Related Experiences</a>
                    </li>
                    <li class="">
                        <a href="#SocioEconomic" data-toggle="tab">Section 3: Socio Economic Profile</a>
                    </li>
                    <li class="">
                        <a href="#IgaSkills" data-toggle="tab">Section 4: Information on Income Generating Activities(IGA) Skills</a>
                    </li>
                    <li class="">
                        <a href="#VulnerabilityAssessment" data-toggle="tab">Section 5: Vulnerability Assessment</a>
                    </li>
                </ul>
                <div class="tab-content tab-content-bordered">
                    <div class="tab-pane fade active in" id="personalInfo">
                        <fieldset>
                            <legend>Section 1: Personal information</legend>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Participant ID/Reference number</label>
                                        <input class="form-control" type="text" name="customer_id" value="<?php echo $pre_data['customer_id'] ? $pre_data['customer_id'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Full Name (*)</label>
                                        <input class="form-control" type="text" name="full_name" value="<?php echo $pre_data['full_name'] ? $pre_data['full_name'] : ''; ?>">
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
                                        <label>Date of Birth (*)</label>
                                        <div class="input-group">
                                            <input id="birthdate" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
                                        </div>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('birthdate');
                                            });
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <label>Mobile Number (*)</label>
                                        <input class="form-control" type="text" name="customer_mobile" value="<?php echo $pre_data['customer_mobile'] ? $pre_data['customer_mobile'] : ''; ?>">
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Emergency</legend>
                                        <label class="control-label input-label">Emergency Mobile No (*)</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="emergency_mobile" value="<?php echo $pre_data['emergency_mobile'] ? $pre_data['emergency_mobile'] : ''; ?>" />
                                        </div>
                                        <label class="control-label input-label">Name of that person (*)</label>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="emergency_name" value="<?php echo $pre_data['emergency_name'] ? $pre_data['emergency_name'] : ''; ?>">
                                        </div>
                                        <label class="control-label input-label">Relation with Participant (*)</label>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="emergency_relation" value="<?php echo $pre_data['emergency_relation'] ? $pre_data['emergency_relation'] : ''; ?>">
                                        </div>
                                    </fieldset>
                                    <div class="form-group">
                                        <label>Educational Qualification (*)</label>
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
                                <div class="col-sm-6">
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
                                        <label>Sex (*)</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px oldGender" type="radio" name="customer_gender" value="male" <?php echo $pre_data && $pre_data['customer_gender'] == 'male' ? 'checked' : '' ?>><span class="lbl">Male</span></label>
                                                <label><input class="px oldGender" type="radio" name="customer_gender" value="female" <?php echo $pre_data && $pre_data['customer_gender'] == 'female' ? 'checked' : '' ?>><span class="lbl">Female</span></label>
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
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Address Information</legend>
                                        <div class="col-sm-6">   
                                            <label class="control-label input-label">Village</label>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="permanent_village" value="<?php echo $pre_data['permanent_village'] ? $pre_data['permanent_village'] : ''; ?>">
                                            </div>
                                            <label class="control-label input-label">Ward No</label>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="permanent_ward" value="<?php echo $pre_data['permanent_ward'] ? $pre_data['permanent_ward'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Division (*)</label>
                                                <div class="select2-primary">
                                                    <select class="form-control" id="permanent_division" name="permanent_division" data-selected="<?php echo $pre_data['permanent_division'] ? $pre_data['permanent_division'] : '' ?>"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label input-label">Union/Pourashava</label>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="permanent_union" value="<?php echo $pre_data['permanent_union'] ? $pre_data['permanent_union'] : ''; ?>">
                                            </div>
                                            <label class="control-label input-label">Upazila (*)</label>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="permanent_sub_district" value="<?php echo $pre_data['permanent_sub_district'] ? $pre_data['permanent_sub_district'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>District (*)</label>
                                                <div class="select2-success">
                                                    <select class="form-control" id="permanent_district" name="permanent_district" data-selected="<?php echo $pre_data['permanent_district'] ? $pre_data['permanent_district'] : ''; ?>"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">  
                                            <label class="control-label input-label">Address (*)</label>
                                            <div class="form-group">
                                                <textarea type="text" class="form-control" name="permanent_house" /><?php echo $pre_data['permanent_house'] ? $pre_data['permanent_house'] : ''; ?></textarea>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Number of Accompany/ Number of Family Member</legend>
                                    <div class="col-sm-4">   
                                        <label class="control-label input-label">Male (*)</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="maleMember" name="male_household_member" value="<?php echo $pre_data['male_household_member'] ? $pre_data['male_household_member'] : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4">   
                                        <label class="control-label input-label">Female (*)</label>
                                        <div class="form-group">
                                            <input class="form-control" id="femaleMember" type="text" name="female_household_member" value="<?php echo $pre_data['female_household_member'] ? $pre_data['female_household_member'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">   
                                        <label class="control-label input-label">Total</label>
                                        <div class="form-group">
                                            <input class="form-control" id="totalMember" type="text" value="<?php echo $pre_data['male_household_member'] + $pre_data['female_household_member'] ?>">
                                        </div>
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
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="migration">
                        <fieldset>
                            <legend>Section 2: Migration Related Experiences</legend>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Port of exit from Bangladesh (*)</label>
                                    <input class="form-control" type="text" name="left_port" value="<?php echo $pre_data['left_port'] ? $pre_data['left_port'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Desired destination(*)</label>
                                    <input class="form-control" type="text" name="preferred_country" value="<?php echo $pre_data['preferred_country'] ? $pre_data['preferred_country'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Final destination(*)</label>
                                    <input class="form-control" type="text" name="final_destination" value="<?php echo $pre_data['final_destination'] ? $pre_data['final_destination'] : ''; ?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Type of Channels (*)</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px educations" type="radio" name="migration_type" value="regular" <?php echo $pre_data && $pre_data['migration_type'] == 'regular' ? 'checked' : '' ?>><span class="lbl">Regular</span></label>
                                            <label><input class="px educations" type="radio" name="migration_type" value="irregular" <?php echo $pre_data && $pre_data['migration_type'] == 'irregular' ? 'checked' : '' ?>><span class="lbl">Irregular</span></label>
                                            <label><input class="px educations" type="radio" name="migration_type" value="both" <?php echo $pre_data && $pre_data['migration_type'] == 'both' ? 'checked' : '' ?>><span class="lbl">Both</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Type of visa (*)</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px visa" type="radio" name="visa_type" value="tourist" <?php echo $pre_data && $pre_data['visa_type'] == 'tourist' ? 'checked' : '' ?>><span class="lbl">Tourist</span></label>
                                            <label><input class="px visa" type="radio" name="visa_type" value="student" <?php echo $pre_data && $pre_data['visa_type'] == 'student' ? 'checked' : '' ?>><span class="lbl">Student</span></label>
                                            <label><input class="px visa" type="radio" name="visa_type" value="work" <?php echo $pre_data && $pre_data['visa_type'] == 'work' ? 'checked' : '' ?>><span class="lbl">Work</span></label>
                                            <label><input class="px" type="radio" name="visa_type" id="newvisa"><span class="lbl">Others. Please specify…</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="newvisaType" style="display: none; margin-bottom: 1em;">
                                    <input class="form-control" placeholder="Please Specity" type="text" name="new_visa" value="">
                                </div>
                                <script>
                                    init.push(function () {
                                        $("#newvisa").on("click", function () {
                                            $('#newvisaType').show();
                                        });

                                        $(".visa").on("click", function () {
                                            $('#newvisaType').hide();
                                        });
                                    });
                                </script>
                            </div>
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Media of Departure</legend>
                                <div class="col-sm-4">
                                    <label class="control-label input-label">Name(*)</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="departure_media" value="<?php echo $migration_medias->departure_media ? $migration_medias->departure_media : ''; ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label input-label">Relation</label>
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="media_relation" value="<?php echo $migration_medias->media_relation ? $migration_medias->media_relation : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label input-label">Address (*)</label>
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="media_address" value="<?php echo $migration_medias->media_address ? $migration_medias->media_address : ''; ?>">
                                    </div>
                                </div>
                            </fieldset>  
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Migration Documents (If applicable)</legend>
                                <div class="col-sm-6">
                                    <label class="control-label input-label">Passport No</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="passport_number" value="<?php echo $pre_data['passport_number'] ? $pre_data['passport_number'] : ''; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a href="javascript:;" id="addMoreDocument" class="btn btn-success"><i class="btn-label fa fa-plus-circle"></i> Add More Document</a>
                                    </div>
                                    <div id="documentUploads" style="display: none">
                                        <div class="col-md-6">
                                            <label class="control-label input-label">Document Name</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="document_name[]" />
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="control-label input-label">Upload Document</label>
                                            <div class="form-group">
                                                <input type="file" class="form-control" name="customer_photo[]" />
                                            </div>
                                        </div>
                                        <div class="col-md-1" style="margin-top:5%;">
                                            <div class="form-group">
                                                <a href="javascript:" class="btn btn-danger remove_row">X</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>  
                            <script>
                                init.push(function () {
                                    var upload = '\
                                        <aside>\
                                            <div class="col-md-6">\
                                                <label class="control-label input-label">Document Name</label>\
                                                <div class="form-group">\
                                                    <input type="text" class="form-control" name="document_name[]" />\
                                                </div>\
                                            </div>\
                                            <div class="col-md-5">\
                                                <label class="control-label input-label">Upload Document</label>\
                                                <div class="form-group">\
                                                    <input type="file" class="form-control" name="customer_photo[]" />\
                                                </div>\
                                            </div>\
                                            <div class="col-md-1" style="margin-top:5%;">\
                                                <div class="form-group">\
                                                    <a href="javascript:" class="btn btn-danger remove_row">X</a>\
                                                </div>\
                                            </div>\
                                        </aside>';

                                    $("#documentUploads").show();

                                    $("#addMoreDocument").on("click", function () {
                                        $("#documentUploads").append(upload);
                                    });

                                    $(document).on('click', '.remove_row', function () {
                                        $(this).closest('aside').remove();
                                    });
                                });
                            </script>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Date of Departure from Bangladesh (*)</label>
                                    <div class="input-group">
                                        <input id="date_of_depature" type="text" class="form-control" name="departure_date" value="<?php echo $pre_data['departure_date'] && $pre_data['departure_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['departure_date'])) : date('d-m-Y'); ?>">
                                    </div>
                                    <script type="text/javascript">
                                        init.push(function () {
                                            _datepicker('date_of_depature');
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label>Date of Return to Bangladesh (*)</label>
                                    <div class="input-group">
                                        <input id="date_of_return" type="text" class="form-control" name="return_date" value="<?php echo $pre_data['return_date'] && $pre_data['return_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['return_date'])) : date('d-m-Y'); ?>">
                                    </div>
                                    <script type="text/javascript">
                                        init.push(function () {
                                            _datepicker('date_of_return');
                                        });
                                    </script>
                                </div>
                                <?php if ($edit): ?>
                                    <div class="form-group">
                                        <label>Duration of Stay Abroad (Months)</label>
                                        <input class="form-control" type="text" value="<?php echo $pre_data['migration_duration'] ? $pre_data['migration_duration'] : ''; ?>">
                                    </div>
                                <?php endif ?>
                                <div class="form-group">
                                    <label>Occupation in overseas country (*) </label>
                                    <input class="form-control" type="text" name="migration_occupation" value="<?php echo $pre_data['migration_occupation'] ? $pre_data['migration_occupation'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Income: (If applicable)</label>
                                    <input class="form-control" type="number" name="earned_money" value="<?php echo $pre_data['earned_money'] ? $pre_data['earned_money'] : ''; ?>">
                                </div>
                            </div>
                            <?php
                            $migration_reasons = $migration_reasons ? $migration_reasons : array($migration_reasons);
                            ?>                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Reasons for Migration (*)</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="checkbox" name="migration_reasons[]" value="underemployed" <?php
                                                if (in_array('underemployed', $migration_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Under employed</span></label>
                                            <label><input class="px" type="checkbox" name="migration_reasons[]" value="unemployed" <?php
                                                if (in_array('unemployed', $migration_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Unemployed</span></label>
                                            <label><input class="px" type="checkbox" name="migration_reasons[]" value="higher_income" <?php
                                                if (in_array('higher_income', $migration_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Higher income</span></label>
                                            <label><input class="px" type="checkbox" name="migration_reasons[]" value="family_abroad" <?php
                                                if (in_array('family_abroad', $migration_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Join friends or family abroad</span></label>
                                            <label><input class="px" type="checkbox" name="migration_reasons[]" value="leave_home" <?php
                                                if (in_array('leave_home', $migration_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Want to leave home</span></label>
                                            <label><input class="px" type="checkbox" name="migration_reasons[]" value="pay_debts" <?php
                                                if (in_array('pay_debts', $migration_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Pay back debts</span></label>
                                            <label><input class="px" type="checkbox" name="migration_reasons[]" value="political_reason" <?php
                                                if (in_array('political_reason', $migration_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Political reason</span></label>
                                            <label><input class="px" type="checkbox" name="migration_reasons[]" value="education" <?php
                                                if (in_array('education', $migration_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Education</span></label>
                                            <label><input class="px" type="checkbox" id="newReasonsMigration"><span class="lbl">Others</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="newReasonsMigrationType" style="display: none; margin-bottom: 1em;">
                                    <input class="form-control" placeholder="Please Specity" type="text" name="new_migration_reason" value="">
                                </div>
                                <script>
                                    init.push(function () {
                                        $("#newReasonsMigration").on("click", function () {
                                            $('#newReasonsMigrationType').toggle();
                                        });
                                    });
                                </script>
                                <?php
                                $leave_reasons = $leave_reasons ? $leave_reasons : array($leave_reasons);
                                ?>
                                <div class="form-group">
                                    <label>Reasons for returning to Bangladesh (*) </label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="checkbox" name="destination_country_leave_reason[]" value="no_legal" <?php
                                                if (in_array('no_legal', $leave_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">No legal documents to stay </span></label>
                                            <label><input class="px" type="checkbox" name="destination_country_leave_reason[]" value="experienced_violence" <?php
                                                if (in_array('experienced_violence', $leave_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Experienced violence/abuse</span></label>
                                            <label><input class="px" type="checkbox" name="destination_country_leave_reason[]" value="no_job" <?php
                                                if (in_array('no_job', $leave_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Unable to find a job</span></label>
                                            <label><input class="px" type="checkbox" name="destination_country_leave_reason[]" value="low_salary" <?php
                                                if (in_array('low_salary', $leave_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Salary was too low</span></label>
                                            <label><input class="px" type="checkbox" name="destination_country_leave_reason[]" value="no_accommodation" <?php
                                                if (in_array('no_accommodation', $leave_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">No accommodation (lived in the streets)</span></label>
                                            <label><input class="px" type="checkbox" name="destination_country_leave_reason[]" value="sickness" <?php
                                                if (in_array('sickness', $leave_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Sickness</span></label>
                                            <label><input class="px" type="checkbox" name="destination_country_leave_reason[]" value="family_needs" <?php
                                                if (in_array('family_needs', $leave_reasons)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Family needs</span></label>
                                            <label><input class="px" type="checkbox" id="newreturningBangladesh"><span class="lbl">Others</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="newreturningBangladeshType" style="display: none; margin-bottom: 1em;">
                                    <input class="form-control" placeholder="Please Specity" type="text" name="new_return_reason" value="">
                                </div>
                                <script>
                                    init.push(function () {
                                        $("#newreturningBangladesh").on("click", function () {
                                            $('#newreturningBangladeshType').toggle();
                                        });
                                    });
                                </script>
                            </div>
                            <div class="col-sm-12">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Did the following happen to you when you in transit or during your stay in the country abroad? </legend>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>False promises about a job prior to arrival at workplace abroad</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="is_cheated" value="yes" <?php echo $pre_data && $pre_data['is_cheated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="is_cheated" value="no" <?php echo $pre_data && $pre_data['is_cheated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Forced to perform work or other activities against your will, after the departure from Bangladesh?</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="forced_work" value="yes" <?php echo $pre_data && $pre_data['forced_work'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="forced_work" value="no" <?php echo $pre_data && $pre_data['forced_work'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Experienced excessive working hours (more than 40 hours a week)</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="excessive_work" value="yes" <?php echo $pre_data && $pre_data['excessive_work'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="excessive_work" value="no" <?php echo $pre_data && $pre_data['excessive_work'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Deductions from salary for recruitment fees at workplace</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="is_money_deducted" value="yes" <?php echo $pre_data && $pre_data['is_money_deducted'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="is_money_deducted" value="no" <?php echo $pre_data && $pre_data['is_money_deducted'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Denied freedom of movement during or between work shifts after your departure from Bangladesh?</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="is_movement_limitation" value="yes" <?php echo $pre_data && $pre_data['is_movement_limitation'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="is_movement_limitation" value="no" <?php echo $pre_data && $pre_data['is_movement_limitation'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Threatened by employer or someone acting on their behalf, or the broker with violence or action by law enforcement/deportation?</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="employer_threatened" value="yes" <?php echo $pre_data && $pre_data['employer_threatened'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="employer_threatened" value="no" <?php echo $pre_data && $pre_data['employer_threatened'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Have you ever had identity or travel documents (passport) withheld by an employer or broker after your departure from Bangladesh?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" name="is_kept_document" value="yes" <?php echo $pre_data && $pre_data['is_kept_document'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" name="is_kept_document" value="no" <?php echo $pre_data && $pre_data['is_kept_document'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="SocioEconomic">
                        <fieldset>
                            <legend>Section 3: Socio Economic Profile</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Main occupation (before trafficking) (*)</label>
                                        <input type="text" class="form-control" name="pre_occupation" value="<?php echo $pre_data['pre_occupation'] ? $pre_data['pre_occupation'] : ''; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label>Main occupation (after return) (*)</label>
                                        <input type="text" class="form-control" name="present_occupation" value="<?php echo $pre_data['present_occupation'] ? $pre_data['present_occupation'] : ''; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label>Monthly income of returnee after return(in BDT) (*)</label>
                                        <input type="number" class="form-control" name="present_income" value="<?php echo $pre_data['present_income'] ? $pre_data['present_income'] : ''; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label>Savings (BDT) (*)</label>
                                        <input type="number" class="form-control" name="personal_savings" value="<?php echo $pre_data['personal_savings'] ? $pre_data['personal_savings'] : ''; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label>Loan Amount (BDT) (*)</label>
                                        <input type="number" class="form-control" name="personal_debt" value="<?php echo $pre_data['personal_debt'] ? $pre_data['personal_debt'] : ''; ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Ownership of House (*)</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px house_ownership" type="radio" name="current_residence_ownership" value="own" <?php echo $pre_data && $pre_data['current_residence_ownership'] == 'own' ? 'checked' : '' ?>><span class="lbl">Own</span></label>
                                                <label><input class="px house_ownership" type="radio" name="current_residence_ownership" value="rental" <?php echo $pre_data && $pre_data['current_residence_ownership'] == 'rental' ? 'checked' : '' ?>><span class="lbl">Rental</span></label>
                                                <label><input class="px house_ownership" type="radio" name="current_residence_ownership" value="without_paying" <?php echo $pre_data && $pre_data['current_residence_ownership'] == 'without_paying' ? 'checked' : '' ?>><span class="lbl">Live without paying</span></label>
                                                <label><input class="px house_ownership" type="radio" name="current_residence_ownership" value="khas_land" <?php echo $pre_data && $pre_data['current_residence_ownership'] == 'khas_land' ? 'checked' : '' ?>><span class="lbl">Khas land</span></label>
                                                <label><input class="px" type="radio" name="house_ownership" id="newHouseOwnership"><span class="lbl">Others. Please specify…</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newHouseOwnershipType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_ownership" value="">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#newHouseOwnership").on("click", function () {
                                                $('#newHouseOwnershipType').show();
                                            });

                                            $(".house_ownership").on("click", function () {
                                                $('#newHouseOwnershipType').hide();
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Type of house (*)</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px house" type="radio" name="current_residence_type" value="raw_house" <?php echo $pre_data && $pre_data['current_residence_type'] == 'raw_house' ? 'checked' : '' ?>><span class="lbl">Raw house (wall made of mud/straw, roof made of tin jute stick/ pampas grass/ khar/ leaves)</span></label>
                                                <label><input class="px house" type="radio" name="current_residence_type" value="pucca" <?php echo $pre_data && $pre_data['current_residence_type'] == 'pucca' ? 'checked' : '' ?>><span class="lbl">Pucca (wall, floor and roof of the house made of concrete)</span></label>
                                                <label><input class="px house" type="radio" name="current_residence_type" value="live" <?php echo $pre_data && $pre_data['current_residence_type'] == 'live' ? 'checked' : '' ?>><span class="lbl">Live Semi-pucca (roof made of tin, wall or floor made of concrete)</span></label>
                                                <label><input class="px house" type="radio" name="current_residence_type" value="tin" <?php echo $pre_data && $pre_data['current_residence_type'] == 'tin' ? 'checked' : '' ?>><span class="lbl">Tin (wall, and roof of the house made of tin)</span></label>
                                                <label><input class="px" type="radio" name="current_residence_type" id="newHouse"><span class="lbl">Others. Please specify…</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newHouseType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_residence" value="">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#newHouse").on("click", function () {
                                                $('#newHouseType').show();
                                            });

                                            $(".house").on("click", function () {
                                                $('#newHouseType').hide();
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade" id="IgaSkills">
                        <fieldset>
                            <legend>Section 4: Information on Income Generating Activities(IGA) Skills</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>IGA Skills ? (*)</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="iga_skillYes" name="have_earner_skill" value="yes" <?php echo $pre_data && $pre_data['have_earner_skill'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="iga_skillNo" name="have_earner_skill" value="no" <?php echo $pre_data && $pre_data['have_earner_skill'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $('.iga_skill').hide();
                                            var isChecked = $('#iga_skillYes').is(':checked');

                                            if (isChecked == true) {
                                                $('.iga_skill').show();
                                            }

                                            $("#iga_skillYes").on("click", function () {
                                                $('.iga_skill').show();
                                            });

                                            $("#iga_skillNo").on("click", function () {
                                                $('.iga_skill').hide();
                                            });
                                        });
                                    </script>
                                    <?php
                                    $have_skills = $have_skills ? $have_skills : array($have_skills);
                                    ?>
                                    <fieldset class="scheduler-border iga_skill">
                                        <legend class="scheduler-border">IGA Skills</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="technical_have_skills[]" value="vocational" <?php
                                                        if (in_array('vocational', $have_skills)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Vocational</span></label>
                                                    <div class="form-group col-sm-9">
                                                        <input type="text" class="form-control" placeholder="Specify....." name="new_vocational" value="" />
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="technical_have_skills[]" value=""><span class="lbl">Handicrafts</span></label>
                                                    <div class="form-group col-sm-9">
                                                        <input type="text" class="form-control" placeholder="Specify....." name="new_handicrafts" value="" />
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="technical_have_skills[]" value="beauty_parlour" <?php
                                                        if (in_array('beauty_parlour', $have_skills)) {
                                                            echo 'checked';
                                                        }
                                                        ?><span class="lbl">Beauty Parlour</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="technical_have_skills[]" value="tailor_work" <?php
                                                        if (in_array('tailor_work', $have_skills)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Tailor Work</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="technical_have_skills[]" value="block_batiks" <?php
                                                        if (in_array('block_batiks', $have_skills)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Block batik's</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="technical_have_skills[]" value="cultivation" <?php
                                                        if (in_array('cultivation', $have_skills)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Cultivating bees/crab fattening</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="technical_have_skills[]" value="livestock" <?php
                                                        if (in_array('livestock', $have_skills)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Livestock Rearing</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="technical_have_skills[]" value="poultry" <?php
                                                        if (in_array('poultry', $have_skills)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Poultry Rearing</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="technical_have_skills[]" value="cooking" <?php
                                                        if (in_array('cooking', $have_skills)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Cooking</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" id="newSkill"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newSkillType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_have_technical" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newSkill").on("click", function () {
                                                    $('#newSkillType').toggle();
                                                });
                                            });
                                        </script>
                                    </fieldset >
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="VulnerabilityAssessment">
                        <fieldset>
                            <legend>Section 5: Vulnerability Assessment</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Do you have any disability? (*)</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesdisability" name="is_physically_challenged" value="yes" <?php echo $pre_data && $pre_data['is_physically_challenged'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="nodisability" name="is_physically_challenged" value="no" <?php echo $pre_data && $pre_data['is_physically_challenged'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group disability" style="display:none">
                                        <label>Type of disability</label>
                                        <input class="form-control" type="text" name="disability_type" value="<?php echo $pre_data['disability_type'] ? $pre_data['disability_type'] : ''; ?>">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#yesCooperated').is(':checked');

                                            if (isChecked == true) {
                                                $('.disability').show();
                                            }

                                            $("#yesdisability").on("click", function () {
                                                $('.disability').show();
                                            });

                                            $("#nodisability").on("click", function () {
                                                $('.disability').hide();
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Any Chronic Disease? (*)</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yeChronicDisease" name="having_chronic_disease" value="yes" <?php echo $pre_data && $pre_data['having_chronic_disease'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noChronicDisease" name="having_chronic_disease" value="no" <?php echo $pre_data && $pre_data['having_chronic_disease'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#yeChronicDisease').is(':checked');

                                            if (isChecked == true) {
                                                $('.ChronicDisease').show();
                                            }

                                            $("#yeChronicDisease").on("click", function () {
                                                $('.ChronicDisease').show();
                                            });

                                            $("#noChronicDisease").on("click", function () {
                                                $('.ChronicDisease').hide();
                                            });
                                        });
                                    </script>
                                    <?php
                                    $disease_types = $disease_types ? $disease_types : array($disease_types);
                                    ?>
                                    <fieldset class="scheduler-border ChronicDisease" style="display: none; margin-bottom: 1em;">
                                        <legend class="scheduler-border">Type of  Disease</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="checkbox" name="disease_type[]" value="cancer" <?php
                                                        if (in_array('cancer', $disease_types)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Cancer</span></label>
                                                    <label><input class="px" type="checkbox" name="disease_type[]" value="diabetes" <?php
                                                        if (in_array('diabetes', $disease_types)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Diabetes</span></label>
                                                    <label><input class="px" type="checkbox" name="disease_type[]" value="arthritis" <?php
                                                        if (in_array('arthritis', $disease_types)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Arthritis</span></label>
                                                    <label><input class="px" type="checkbox" name="disease_type[]" value="asthmatic" <?php
                                                        if (in_array('asthmatic', $disease_types)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Asthmatic</span></label>
                                                    <label><input class="px" type="checkbox" name="disease_type[]" value="kidney_disease" <?php
                                                        if (in_array('kidney_disease', $disease_types)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Kidney Disease</span></label>
                                                    <label><input class="px" type="checkbox" name="disease_type[]" value="heart_diseases" <?php
                                                        if (in_array('heart_diseases', $disease_types)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Heart Diseases</span></label>
                                                    <label><input class="px" type="checkbox" name="disease_type[]" value="bronchitis" <?php
                                                        if (in_array('bronchitis', $disease_types)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Bronchitis</span></label>
                                                    <label><input class="px col-sm-12" type="checkbox" id="newDisease"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newDiseaseTypes" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_disease_type" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newDisease").on("click", function () {
                                                    $('#newDiseaseTypes').toggle();
                                                });
                                            });
                                        </script>
                                    </fieldset >
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_customer_management/manage_customers') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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