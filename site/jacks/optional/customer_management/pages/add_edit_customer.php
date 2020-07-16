<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_customer', 'edit_customer')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();

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
            'full_name' => 'Full Name'
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
            header('location: ' . url('admin/dev_customer_management/manage_customer?action=add_edit_customer&edit=' . $profile_id));
        } else {
            header('location: ' . url('admin/dev_customer_management/manage_customer'));
        }
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

//$all_natural_disasters = $this->get_lookups('natural_disaster');
//$all_economic_impacts = $this->get_lookups('economic_impacts');
//$all_social_impacts = $this->get_lookups('social_impacts');
//$all_countries = getWorldCountry();
//$all_countries_json = json_encode($all_countries);
//$all_transports = $this->get_lookups('transport_modes');
//$all_visas = $this->get_lookups('visa_type');
//$all_migration_medias = $this->get_lookups('migration_medias');
//$all_reasons_for_leave_destination_country = $this->get_lookups('destination_country_leave_reason');
//$all_spent_types = $this->get_lookups('spent_types');
//$all_immediate_supports = $this->get_lookups('immediate_support');
//$all_cooperations = $this->get_lookups('cooperation_type');
//$all_chronic_diseases = $this->get_lookups('disease_type');
//$all_loan_sources = $this->get_lookups('loan_sources');
//$all_residence_ownership_types = $this->get_lookups('current_residence_ownership');
//$all_residence_types = $this->get_lookups('current_residence_type');
//$all_technical_skills = $this->get_lookups('technical_skills');
//$all_non_technical_skills = $this->get_lookups('non_technical_skills');
//$all_soft_skills = $this->get_lookups('soft_skills');

doAction('render_start');
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
                                        <input class="form-control" type="text" name="fullName" value="<?php echo $pre_data['fullName'] ? $pre_data['fullName'] : ''; ?>">
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
                                        <input class="form-control" type="text" name="mobile" value="<?php echo $pre_data['mobile'] ? $pre_data['mobile'] : ''; ?>">
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
                                        <label>Educational Qualification</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px educations" type="radio" name="educational_qualification" value="illiterate"><span class="lbl">Illiterate</span></label>
                                                <label><input class="px educations" type="radio" name="educational_qualification" value="sign"><span class="lbl">Can Sign only</span></label>
                                                <label><input class="px educations" type="radio" name="educational_qualification" value="psc"><span class="lbl">Primary education (Passed Grade 5)</span></label>
                                                <label><input class="px educations" type="radio" name="educational_qualification" value="not_psc"><span class="lbl">Did not complete primary education</span></label>
                                                <label><input class="px educations" type="radio" name="educational_qualification" value="jsc"><span class="lbl">Completed JSC (Passed Grade 8)</span></label>
                                                <label><input class="px educations" type="radio" name="educational_qualification" value="ssc"><span class="lbl">Completed School Secondary Certificate</span></label>
                                                <label><input class="px educations" type="radio" name="educational_qualification" value="hsc"><span class="lbl">Higher Secondary Certificate/Diploma/ equivalent</span></label>
                                                <label><input class="px educations" type="radio" name="educational_qualification" value="bachelor"><span class="lbl">Bachelor’s degree or equivalent</span></label>
                                                <label><input class="px educations" type="radio" name="educational_qualification" value="master"><span class="lbl">Masters or Equivalent</span></label>
                                                <label><input class="px educations" type="radio" name="educational_qualification" value="professional_education"><span class="lbl">Completed Professional education</span></label>
                                                <label><input class="px educations" type="radio" name="educational_qualification" value="general_education"><span class="lbl">Completed general Education</span></label>
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
                                        <label>Gender</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" name="customer_gender" value="male" <?php echo $pre_data && $pre_data['customer_gender'] == 'male' ? 'checked' : '' ?>><span class="lbl">Male</span></label>
                                                <label><input class="px" type="radio" name="customer_gender" value="female" <?php echo $pre_data && $pre_data['customer_gender'] == 'female' ? 'checked' : '' ?>><span class="lbl">Female</span></label>
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
                                        <label>Marital Status</label>
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
                                        <legend class="scheduler-border">Address</legend>
                                        <div class="col-sm-6">   
                                            <label class="control-label input-label">Village</label>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="village" value="<?php echo $pre_data['village'] ? $pre_data['village'] : ''; ?>">
                                            </div>
                                            <label class="control-label input-label">Ward No</label>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="ward" value="<?php echo $pre_data['ward'] ? $pre_data['ward'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Division</label>
                                                <div class="select2-primary">
                                                    <select class="form-control" id="permanent_division" name="permanent_division" data-selected="<?php echo $pre_data['permanent_division'] ? $pre_data['permanent_division'] : '' ?>"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label input-label">Union</label>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="union" value="<?php echo $pre_data['union'] ? $pre_data['union'] : ''; ?>">
                                            </div>
                                            <label class="control-label input-label">Upazila</label>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="sub_district" value="<?php echo $pre_data['sub_district'] ? $pre_data['sub_district'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>District</label>
                                                <div class="select2-success">
                                                    <select class="form-control" id="permanent_district" name="district" data-selected="<?php echo $pre_data['district'] ? $pre_data['district'] : ''; ?>"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">  
                                            <label class="control-label input-label">Address</label>
                                            <div class="form-group">
                                                <textarea type="text" class="form-control" name="address" value="<?php echo $pre_data['address'] ? $pre_data['address'] : ''; ?>" /></textarea>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Number of Accompany/ Number of Family Member</legend>
                                    <div class="col-sm-4">   
                                        <label class="control-label input-label">Male:</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="maleMember" name="male_member" value="<?php echo $pre_data['male_member'] ? $pre_data['male_member'] : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4">   
                                        <label class="control-label input-label">Female</label>
                                        <div class="form-group">
                                            <input class="form-control" id="femaleMember" type="text" name="female_member" value="<?php echo $pre_data['female_member'] ? $pre_data['female_member'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">   
                                        <label class="control-label input-label">Total</label>
                                        <div class="form-group">
                                            <input class="form-control" id="totalMember" type="text" value="<?php echo $pre_data['male_member'] + $pre_data['female_member'] ?>">
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
                                    <input class="form-control" type="text" name="exit_port" value="<?php echo $pre_data['exit_port'] ? $pre_data['exit_port'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Desired destination(*)</label>
                                    <input class="form-control" type="text" name="desired_destination" value="<?php echo $pre_data['desired_destination'] ? $pre_data['desired_destination'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Final destination(*)</label>
                                    <input class="form-control" type="text" name="final_destination" value="<?php echo $pre_data['final_destination'] ? $pre_data['final_destination'] : ''; ?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Type of Channels</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px educations" type="radio" name="channel" value="regular"><span class="lbl">Regular</span></label>
                                            <label><input class="px educations" type="radio" name="channel" value="irregular"><span class="lbl">Irregular</span></label>
                                            <label><input class="px educations" type="radio" name="channel" value="both"><span class="lbl">Both</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Type of visa</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px visa" type="radio" name="visa_type" value="tourist"><span class="lbl">Tourist</span></label>
                                            <label><input class="px visa" type="radio" name="visa_type" value="student"><span class="lbl">Student</span></label>
                                            <label><input class="px visa" type="radio" name="visa_type" value="work"><span class="lbl">Work</span></label>
                                            <label><input class="px" type="radio" name="visa_type" id="newvisa"><span class="lbl">Others. Please specify…</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="newvisaType" style="display: none; margin-bottom: 1em;">
                                    <input class="form-control" placeholder="Please Specity" type="text" name="new_qualification" value="">
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
                                        <input type="text" class="form-control" name="departure_media" value="<?php echo $pre_data['departure_media'] ? $pre_data['departure_media'] : ''; ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label input-label">Relation</label>
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="media_relation" value="<?php echo $pre_data['relation'] ? $pre_data['relation'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label input-label">Address</label>
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="media_address" value="<?php echo $pre_data['media_address'] ? $pre_data['media_address'] : ''; ?>">
                                    </div>
                                </div>
                            </fieldset>  
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Migration Documents (If applicable)</legend>
                                <div class="col-sm-6">
                                    <label class="control-label input-label">Passport No</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="passport" value="<?php echo $pre_data['passport'] ? $pre_data['passport'] : ''; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a href="" class="btn btn-success"><i class="btn-label fa fa-plus-circle"></i> Add More Document</a>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label input-label">Document Name</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="passport" value="<?php echo $pre_data['passport'] ? $pre_data['passport'] : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label input-label">Upload Document</label>
                                        <div class="form-group">
                                            <input type="file" class="form-control" name="migration_document" value="<?php echo $pre_data['migration_document'] ? $pre_data['migration_document'] : ''; ?>" />
                                        </div>
                                    </div>
                                </div>
                            </fieldset>  
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Date of Departure from Bangladesh (*)</label>
                                    <div class="input-group">
                                        <input id="date_of_depature" type="text" class="form-control" name="departure_date" value="<?php echo $pre_data['departure_date'] && $pre_data['departure_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['departure_date'])) : date('d-m-Y'); ?>">
                                    </div>
                                    <script type="text/javascript">
                                        init.push(function () {
                                            _datepicker('departure_date');
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
                                            _datepicker('return_date');
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label>Duration of Stay Abroad (Months)</label>
                                    <input class="form-control" type="text" name="duration_of_stay" value="<?php echo $pre_data['duration_of_stay'] ? $pre_data['duration_of_stay'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Occupation in overseas country (*) </label>
                                    <input class="form-control" type="text" name="overseas_occupation" value="<?php echo $pre_data['overseas_occupation'] ? $pre_data['overseas_occupation'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Income: (If applicable)</label>
                                    <input class="form-control" type="text" name="overseas_income" value="<?php echo $pre_data['overseas_income'] ? $pre_data['overseas_income'] : ''; ?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Reasons for Migration (*)</label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="checkbox" name="migration_reason[]" value="underemployed"><span class="lbl">Under employed</span></label>
                                            <label><input class="px" type="checkbox" name="migration_reason[]" value="unemployed"><span class="lbl">Unemployed</span></label>
                                            <label><input class="px" type="checkbox" name="migration_reason[]" value="higher_income"><span class="lbl">Higher income</span></label>
                                            <label><input class="px" type="checkbox" name="migration_reason[]" value="family_abroad"><span class="lbl">Join friends or family abroad</span></label>
                                            <label><input class="px" type="checkbox" name="migration_reason[]" value="leave_home"><span class="lbl">Want to leave home</span></label>
                                            <label><input class="px" type="checkbox" name="migration_reason[]" value="pay_debts"><span class="lbl">Pay back debts</span></label>
                                            <label><input class="px" type="checkbox" name="migration_reason[]" value="political_reason"><span class="lbl">Political reason</span></label>
                                            <label><input class="px" type="checkbox" name="migration_reason[]" value="education"><span class="lbl">Education</span></label>
                                            <label><input class="px" type="checkbox" id="newReasonsMigration"><span class="lbl">Others</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="newReasonsMigrationType" style="display: none; margin-bottom: 1em;">
                                    <input class="form-control" placeholder="Please Specity" type="text" name="new_transport" value="">
                                </div>
                                <script>
                                    init.push(function () {
                                        $("#newReasonsMigration").on("click", function () {
                                            $('#newReasonsMigrationType').toggle();
                                        });
                                    });
                                </script>
                                <div class="form-group">
                                    <label>Reasons for returning to Bangladesh (*) </label>
                                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="checkbox" name="return_reason[]" value="no_legal"><span class="lbl">No legal documents to stay </span></label>
                                            <label><input class="px" type="checkbox" name="return_reason[]" value="experienced_violence"><span class="lbl">Experienced violence/abuse</span></label>
                                            <label><input class="px" type="checkbox" name="return_reason[]" value="no_job"><span class="lbl">Unable to find a job</span></label>
                                            <label><input class="px" type="checkbox" name="return_reason[]" value="low_salary"><span class="lbl">Salary was too low</span></label>
                                            <label><input class="px" type="checkbox" name="return_reason[]" value="no_accommodation"><span class="lbl">No accommodation (lived in the streets)</span></label>
                                            <label><input class="px" type="checkbox" name="return_reason[]" value="sickness"><span class="lbl">Sickness</span></label>
                                            <label><input class="px" type="checkbox" name="return_reason[]" value="family_needs"><span class="lbl">Family needs</span></label>
                                            <label><input class="px" type="checkbox" id="newreturningBangladesh"><span class="lbl">Others</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="newreturningBangladeshType" style="display: none; margin-bottom: 1em;">
                                    <input class="form-control" placeholder="Please Specity" type="text" name="new_transport" value="">
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
                                                    <label><input class="px" type="radio" name="false_promises" value="yes"><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="false_promises" value="no"><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Forced to perform work or other activities against your will, after the departure from Bangladesh?</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="forced_work" value="yes"><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="forced_work" value="no"><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Experienced excessive working hours (more than 40 hours a week)</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="excessive_work" value="yes"><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="excessive_work" value="no"><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Deductions from salary for recruitment fees at workplace</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="salary_deduction" value="yes"><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="salary_deduction" value="no"><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Denied freedom of movement during or between work shifts after your departure from Bangladesh? </label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="movement_restriction" value="yes"><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="movement_restriction" value="no"><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Threatened by employer or someone acting on their behalf, or the broker with violence or action by law enforcement/deportation?</label>
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label><input class="px" type="radio" name="employer_threatened" value="yes"><span class="lbl">Yes</span></label>
                                                    <label><input class="px" type="radio" name="employer_threatened" value="no"><span class="lbl">No</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Have you ever had identity or travel documents (passport) withheld by an employer or broker after your departure from Bangladesh?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" name="withheld_documents" value="yes"><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" name="withheld_documents" value="no"><span class="lbl">No</span></label>
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
                                        <label>Main occupation (before trafficking)</label>
                                        <input type="text" class="form-control" name="main_occupation" value="<?php echo $pre_data['main_occupation'] ? $pre_data['main_occupation'] : ''; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label>Main occupation (after return)</label>
                                        <input type="text" class="form-control" name="return_occupation" value="<?php echo $pre_data['return_occupation'] ? $pre_data['return_occupation'] : ''; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label>Monthly income of returnee after return(in BDT)</label>
                                        <input type="text" class="form-control" name="monthly_income" value="<?php echo $pre_data['monthly_income'] ? $pre_data['monthly_income'] : ''; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label>Savings (BDT)</label>
                                        <input type="text" class="form-control" name="total_saving" value="<?php echo $pre_data['total_saving'] ? $pre_data['total_saving'] : ''; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label>Loan Amount (BDT)</label>
                                        <input type="text" class="form-control" name="loan_amount" value="<?php echo $pre_data['loan_amount'] ? $pre_data['loan_amount'] : ''; ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Ownership of House</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px house_ownership" type="radio" name="house_ownership" value="own"><span class="lbl">Own</span></label>
                                                <label><input class="px house_ownership" type="radio" name="house_ownership" value="rental"><span class="lbl">Rental</span></label>
                                                <label><input class="px house_ownership" type="radio" name="house_ownership" value="without_paying"><span class="lbl">Live without paying</span></label>
                                                <label><input class="px house_ownership" type="radio" name="house_ownership" value="khas_land"><span class="lbl">Khas land</span></label>
                                                <label><input class="px" type="radio" name="house_ownership" id="newHouseOwnership"><span class="lbl">Others. Please specify…</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newHouseOwnershipType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_qualification" value="">
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
                                        <label>Type of house</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px house" type="radio" name="house_type" value="raw_house"><span class="lbl">Raw house (wall made of mud/straw, roof made of tin jute stick/ pampas grass/ khar/ leaves)</span></label>
                                                <label><input class="px house" type="radio" name="house_type" value="pucca"><span class="lbl">Pucca (wall, floor and roof of the house made of concrete)</span></label>
                                                <label><input class="px house" type="radio" name="house_type" value="live"><span class="lbl">Live Semi-pucca (roof made of tin, wall or floor made of concrete)</span></label>
                                                <label><input class="px house" type="radio" name="house_type" value="tin"><span class="lbl">Tin (wall, and roof of the house made of tin)</span></label>
                                                <label><input class="px" type="radio" name="house_type" id="newHouse"><span class="lbl">Others. Please specify…</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newHouseType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_qualification" value="">
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
                    <div class="tab-pane fade " id="IgaSkills">
                        <fieldset>
                            <legend>Section 4: Information on Income Generating Activities(IGA) Skills</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>IGA Skills ?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="iga_skillYes" name="iga_skill" value="yes" <?php echo $pre_data && $pre_data['iga_skill'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="iga_skillNo" name="iga_skill" value="no" <?php echo $pre_data && $pre_data['iga_skill'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
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


                                    <fieldset class="scheduler-border iga_skill">
                                        <legend class="scheduler-border">IGA Skills</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-3"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Vocational</span></label>
                                                    <div class="form-group col-sm-9">
                                                        <input type="text" class="form-control" placeholder="specify....." name="emergency_mobile" value="<?php echo $pre_data['emergency_mobile'] ? $pre_data['emergency_mobile'] : ''; ?>" />
                                                    </div>
                                                    <label class="col-sm-3"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Handicrafts</span></label>
                                                    <div class="form-group col-sm-9">
                                                        <input type="text" class="form-control" placeholder="specify....." name="emergency_mobile" value="<?php echo $pre_data['emergency_mobile'] ? $pre_data['emergency_mobile'] : ''; ?>" />
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Cultivating bees/crab fattening</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Livestock Rearing</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Poultry Rearing  </span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Cooking</span></label>

                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" id="newDisaster"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newDisasterType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_disaster" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newDisaster").on("click", function () {
                                                    $('#newDisasterType').toggle();
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
                                        <label>Do you have any disability?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesdisability" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="nodisability" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group disability" style="display:none">
                                        <label>Type of  disability</label>
                                        <input class="form-control" type="text" name="organization_name" value="<?php echo $pre_data['disability_name'] ? $pre_data['disability_name'] : ''; ?>">
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
                                        <label>Any Chronic Disease?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yeChronicDisease" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noChronicDisease" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
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
                                    <fieldset class="scheduler-border ChronicDisease" style="display: none; margin-bottom: 1em;">
                                        <legend class="scheduler-border">Type of  Disease</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">

                                                    <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Cancer </span></label>
                                                    <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Diabetes </span></label>
                                                    <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Arthritis  </span></label>
                                                    <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Asthmatic </span></label>
                                                    <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Kidney disease </span></label>
                                                    <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Heart diseases  </span></label>
                                                    <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Bronchitis   </span></label>
                                                    <label><input class="px col-sm-12" type="checkbox" id="newDisease"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newDiseaseTypes" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_disaster" value="">
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