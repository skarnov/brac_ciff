<?php
error_reporting(0);
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_case', 'edit_case')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$args = array(
    'customer_id' => $edit,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_psycho_family_counselling_id',
        'order' => 'DESC'
    ),
);
$family_counsellings = $this->get_family_counselling($args);

$args = array(
    'customer_id' => $edit,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_psycho_session_id',
        'order' => 'DESC'
    ),
);
$psychosocial_sessions = $this->get_psychosocial_session($args);

$args = array(
    'customer_id' => $edit,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_psycho_completion_id',
        'order' => 'DESC'
    ),
);


$psychosocial_completions = $this->get_psychosocial_completion($args);

$args = array(
    'customer_id' => $edit,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_psycho_followup_id',
        'order' => 'DESC'
    ),
);

$psychosocial_followups = $this->get_psychosocial_followup($args);

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_cases(array('id' => $edit, 'single' => true));
    $immediate_support = explode(',', $pre_data['immediate_support']);
    $service_requested = explode(',', $pre_data['service_requested']);
    $issue_discussed = explode(',', $pre_data['issue_discussed']);
    $problem_identified = explode(',', $pre_data['problem_identified']);
    $reason_for_reffer = explode(',', $pre_data['reason_for_reffer']);
    $inkind_project = explode(',', $pre_data['inkind_project']);
    $received_vocational_training = explode(',', $pre_data['received_vocational_training']);
    $received_vocational = explode(',', $pre_data['received_vocational']);
    $economic_support = explode(',', $pre_data['economic_support']);
    $reintegration_economic = explode(',', $pre_data['reintegration_economic']);
    $support_referred = explode(',', $pre_data['support_referred']);
    $reason_dropping = explode(',', $pre_data['reason_dropping']);
    $confirm_services = explode(',', $pre_data['confirm_services']);

    if (!$pre_data) {
        add_notification('Invalid case, no data found.', 'error');
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

    $ret = $this->add_edit_case($data);

    if ($ret['case_insert'] || $ret['case_update']) {
        $msg = "Information of case has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_customer_management/manage_cases'));
        } else {
            header('location: ' . url('admin/dev_customer_management/manage_cases'));
        }
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Case  </h1>
    <?php if ($pre_data) : ?>
        <h4 class="text-primary">Case of: <?php echo $pre_data['full_name'] ?></h4>
        <h4 class="text-primary">Case ID: <?php echo $pre_data['pk_immediate_support_id'] ?></h4>
    <?php endif; ?>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Case ',
                'title' => 'Manage Case ',
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
                        <a href="#SupportProvided" data-toggle="tab">Section 1: Support Provided</a>
                    </li>
                    <li class="">
                        <a href="#PreferredServices" data-toggle="tab">Section 2: Preferred Services and Reintegration Plan</a>
                    </li>
                    <li class="">
                        <a href="#PsychosocialReintegration" data-toggle="tab">Section 3: Psychosocial Reintegration Support Services</a>
                    </li>
                    <li class="">
                        <a href="#FamilyCounseling" data-toggle="tab">Section 3.1: Family Counseling Session</a>
                    </li>
                    <li class="">
                        <a href="#ReintegrationSession" data-toggle="tab">Section 3.3: Psychosocial Reintegration Session Activities</a>
                    </li>
                    <li class="">
                        <a href="#SessionCompletion" data-toggle="tab">Section 3.4: Session Completion Status</a>
                    </li>
                    <li class="">
                        <a href="#ReintegrationFollowup" data-toggle="tab">Section 3.5: Psychosocial Reintegration (Followup)</a>
                    </li>
                    <li class="">
                        <a href="#EconomicReintegration" data-toggle="tab">Section 4: Economic Reintegration Support</a>
                    </li>
                    <li class="">
                        <a href="#EconomicReferrals" data-toggle="tab">Section 4.1: Economic Reintegration Referrals</a>
                    </li>
                    <li class="">
                        <a href="#SocialReintegrationSupport" data-toggle="tab">Section 5: Social Reintegration Support</a>
                    </li>
                    <li class="">
                        <a href="#ReviewFollowUp" data-toggle="tab">Section 6: Review and Follow-Up</a>
                    </li>
                </ul>
                <div class="tab-content tab-content-bordered">
                    <div class="tab-pane fade active in" id="SupportProvided">
                        <fieldset>
                            <legend>Section 1: Support Provided</legend>
                            <div class="row">
                                <!--                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Select Branch (*)</label>
                                                                        <select class="form-control" name="fk_branch_id">
                                                                            <option value="">Select One</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Select Case Manager (*)</label>
                                                                        <select class="form-control" name="fk_staff_id">
                                                                            <option value="">Select One</option>
                                                                        </select>
                                                                    </div>
                                                                </div>-->
                                <div class="col-sm-8">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Immediate support services received</legend>
                                        <div class="form-group">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <div class="col-sm-6">   
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value="Meet and greet at port of entry" <?php
                                                            if (in_array('Meet and greet at port of entry', $immediate_support)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Meet and greet at port of entry</span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value="Information provision" <?php
                                                            if (in_array('Information provision', $immediate_support)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Information provision</span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value="Pocket money" <?php
                                                            if (in_array('Pocket money', $immediate_support)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Pocket money</span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value="Shelter and accommodation" <?php
                                                            if (in_array('Shelter and accommodation', $immediate_support)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Shelter and accommodation</span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value="Want to leave home" <?php
                                                            if (in_array('Want to leave home', $immediate_support)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Want to leave home</span></label>
                                                    </div>
                                                    <div class="col-sm-6">   
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value="Onward transportation" <?php
                                                            if (in_array('Onward transportation', $immediate_support)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Onward transportation</span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value="Health assessment and health assistance" <?php
                                                            if (in_array('Health assessment and health assistance', $immediate_support)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Health assessment and health assistance</span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value="Food and nutrition" <?php
                                                            if (in_array('Food and nutrition', $immediate_support)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Food and nutrition</span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value="Non-Food Items (hygiene kits, etc.)" <?php
                                                            if (in_array('Non-Food Items (hygiene kits, etc.)', $immediate_support)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Non-Food Items (hygiene kits, etc.)</span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value="Psychosocial Conseling" <?php
                                                            if (in_array('Psychosocial Conseling', $immediate_support)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Psychosocial Conseling</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="PreferredServices">
                        <fieldset>
                            <legend>Section 2: Preferred Services and Reintegration Plan</legend>
                            <div class="row">
                                <div class="col-sm-12">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Type of Services Requested</legend>
                                        <div class="form-group">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <div class="col-sm-6">   
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="service_requested[]" value="Child Care" <?php
                                                            if (in_array('Child Care', $service_requested)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Child Care</span></label>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Education" <?php
                                                            if (in_array('Education', $service_requested)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Education</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Admission" <?php
                                                                    if (in_array('Admission', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Admission</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Scholarship/Stipend" <?php
                                                                    if (in_array('Scholarship/Stipend', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Scholarship/Stipend</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Financial Service" <?php
                                                            if (in_array('Financial Service', $service_requested)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Financial Services</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Loan" <?php
                                                                    if (in_array('Loan', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Loan</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Housing" <?php
                                                            if (in_array('Housing', $service_requested)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Housing</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Allocation for khas land" <?php
                                                                    if (in_array('Allocation for khas land', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Allocation for khas land</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Support for land allocation" <?php
                                                                    if (in_array('Support for land allocation', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Support for land allocation</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="service_requested[]" value="Job Placement" <?php
                                                            if (in_array('Job Placement', $service_requested)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Job Placement</span></label>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Legal Services" <?php
                                                            if (in_array('Legal Services', $service_requested)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Legal Services</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Legal Aid" <?php
                                                                    if (in_array('Legal Aid"', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Legal Aid</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Claiming Compensation" <?php
                                                                    if (in_array('Claiming Compensation', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Claiming Compensation</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Assistance in resolving family dispute" <?php
                                                                    if (in_array('Assistance in resolving family dispute', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Assistance in resolving family dispute</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Training" <?php
                                                            if (in_array('Training', $service_requested)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Training</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Financial Literacy Training" <?php
                                                                    if (in_array('Financial Literacy Training', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Financial Literacy Training</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Advance training from project" <?php
                                                                    if (in_array('Advance training from project', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Advance training from project</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Advance training through referrals" <?php
                                                                    if (in_array('Advance training through referrals', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Advance training through referrals</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Material Assistance" <?php
                                                            if (in_array('Material Assistance', $service_requested)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Material Assistance</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Business equipment/tools" <?php
                                                                    if (in_array('Business equipment/tools', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Business equipment/tools</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Allocation of land or pond for business" <?php
                                                                    if (in_array('Allocation of land or pond for business', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Allocation of land or pond for business</span></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">   
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Medical Support" <?php
                                                            if (in_array('Medical Support', $service_requested)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Medical Support</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value=">Medical treatment" <?php
                                                                    if (in_array('Medical treatment', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Medical treatment</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Psychiatric treatment" <?php
                                                                    if (in_array('Psychiatric treatment', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Psychiatric treatment</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Microbusiness" <?php
                                                            if (in_array('Microbusiness', $service_requested)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Microbusiness</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Business grant" <?php
                                                                    if (in_array('Business grant', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Business grant</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Psychosocial Support" <?php
                                                            if (in_array('Psychosocial Support', $service_requested)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Psychosocial Support</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Individual Counselling" <?php
                                                                    if (in_array('Individual Counselling', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Individual Counselling</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Family counselling" <?php
                                                                    if (in_array('Family counselling', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Family counselling</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Trauma Counseling" <?php
                                                                    if (in_array('Trauma Counseling', $service_requested)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Trauma Counseling</span></label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><input class="px" type="checkbox" value="Social Protection Schemes" ><span class="lbl">Social Protection Schemes</span></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <input class="form-control" placeholder="Specify the services" type="text" name="new_social_protection" value="<?php echo $pre_data['new_social_protection'] ? $pre_data['new_social_protection'] : ''; ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label><input class="px" type="checkbox" value="Special Security Measures"><span class="lbl">Special Security Measures</span></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <input class="form-control" placeholder="Specify the services" type="text" name="new_security_measures" value="<?php echo $pre_data['new_security_measures'] ? $pre_data['new_security_measures'] : ''; ?>">
                                                        </div>
                                                        <div class="form-group ">
                                                            <label>Other Services Requested</label>
                                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                                <div class="options_holder radio">
                                                                    <label><input class="px col-sm-12" type="checkbox" id="newServiceRequested"><span class="lbl">Others</span></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="newServiceRequestedTypes" style="display: none; margin-bottom: 1em;">
                                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_service_requested" value="<?php echo $pre_data['new_service_requested'] ? $pre_data['new_service_requested'] : ''; ?>">
                                                        </div>
                                                        <script>
                                                            init.push(function () {
                                                                $("#newServiceRequested").on("click", function () {
                                                                    $('#newServiceRequestedTypes').toggle();
                                                                });
                                                            });
                                                        </script>
                                                        <div class="form-group">
                                                            <label>Note (If any)</label>
                                                            <textarea class="form-control" name="service_requested_note" value="<?php echo $pre_data['service_requested_note'] ? $pre_data['service_requested_note'] : ''; ?>" rows="5" placeholder="Note"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="PsychosocialReintegration">
                        <fieldset>
                            <legend>Section 3: Psychosocial Reintegration Support Services</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date of first meeting</label>
                                        <div class="input-group">
                                            <input id="Datefirstmeeting" type="text" class="form-control" name="first_meeting" value="<?php echo $pre_data['first_meeting'] && $pre_data['first_meeting'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['first_meeting'])) : date('d-m-Y'); ?>">
                                        </div>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('Datefirstmeeting');
                                            });
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <label>Counsellor’s Home Visits</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="HomeVisitlYes" name="is_home_visit" value="yes" <?php echo $pre_data && $pre_data['is_home_visit'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="HomeVisitlNo" name="is_home_visit" value="no" <?php echo $pre_data && $pre_data['is_home_visit'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Issues Discussed (*)</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="issue_discussed[]" value="Referral Services"  <?php
                                                    if (in_array('Referral Services', $issue_discussed)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Referral Services</span></label>
                                                <label><input class="px" type="checkbox" name="issue_discussed[]" value="Information" <?php
                                                    if (in_array('Information', $issue_discussed)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Information</span></label>
                                                <label><input class="px" type="checkbox" value="Other"  <?php
                                                    if (in_array('Other', $issue_discussed)) {
                                                        echo 'checked';
                                                    }
                                                    ?> name="issue_discussed[]" id="newIssues"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newIssuesType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_issue_discussed" value="<?php echo $pre_data['new_issue_discussed'] ? $pre_data['new_issue_discussed'] : ''; ?>">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#newIssues").on("click", function () {
                                                $('#newIssuesType').toggle();
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Problems Identified</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Anxiety"<?php
                                                    if (in_array('Anxiety', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Anxiety</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Depression"<?php
                                                    if (in_array('Depression', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Depression</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Suicidal Ideation/Thought"<?php
                                                    if (in_array('Suicidal Ideation/Thought', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Suicidal Ideation/Thought</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Sleep Problems"<?php
                                                    if (in_array('Sleep Problems', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Sleep Problems</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Phobia/Fear"<?php
                                                    if (in_array('Phobia/Fear', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Phobia/Fear</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Acute Stress"<?php
                                                    if (in_array('Acute Stress', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Acute Stress</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Anger problems"<?php
                                                    if (in_array('Anger problems', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Anger problems</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Addiction issues (substance abuse of any kinds)"<?php
                                                    if (in_array('Addiction issues (substance abuse of any kinds)', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Addiction issues (substance abuse of any kinds)</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Schizophrenia"<?php
                                                    if (in_array('Schizophrenia', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Schizophrenia</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Bipolar Mood Disorder"<?php
                                                    if (in_array('Bipolar Mood Disorder', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Bipolar Mood Disorder</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Repetitive thought or Repetitive Behavior (OCD)"<?php
                                                    if (in_array('Repetitive thought or Repetitive Behavior (OCD)', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Repetitive thought or Repetitive Behavior (OCD)</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Conversion Reactions"<?php
                                                    if (in_array('Conversion Reactions', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Conversion Reactions</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Problems in Socialization"<?php
                                                    if (in_array('Problems in Socialization', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Problems in Socialization</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Family Problems"<?php
                                                    if (in_array('Family Problems', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Family Problems</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Description of the problem</label>
                                        <textarea class="form-control" name="problem_description" rows="5" placeholder="" value="<?php echo $pre_data['problem_description'] ? $pre_data['problem_description'] : ''; ?>"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Initial Plan</label>
                                        <textarea class="form-control" name="initial_plan" rows="5" placeholder="" value="<?php echo $pre_data['initial_plan'] ? $pre_data['initial_plan'] : ''; ?>"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Presence of Family members</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesFamilymembers" name="is_family_counceling" value="yes" <?php echo $pre_data && $pre_data['is_family_counceling'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noFamilymembers" name="is_family_counceling" value="no" <?php echo $pre_data && $pre_data['is_family_counceling'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group Familymembers" style="display:none">
                                        <label>If yes, how many?</label>
                                        <input class="form-control" type="text" name="family_counseling" value="<?php echo $pre_data['family_counseling'] ? $pre_data['family_counseling'] : ''; ?>">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#yesFamilymembers').is(':checked');

                                            if (isChecked == true) {
                                                $('.Familymembers').show();
                                            }

                                            $("#yesFamilymembers").on("click", function () {
                                                $('.Familymembers').show();
                                            });

                                            $("#noFamilymembers").on("click", function () {
                                                $('.Familymembers').hide();
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Place of Session</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px newPlaceSession" type="radio" name="session_place" value="home"  <?php echo $pre_data && $pre_data['session_place'] == 'home' ? 'checked' : '' ?>><span class="lbl">Home</span></label>
                                                <label><input class="px newPlaceSession" type="radio" name="session_place" value="jashore_office" <?php echo $pre_data && $pre_data['session_place'] == 'jashore_office' ? 'checked' : '' ?>><span class="lbl">Jashore office</span></label>
                                                <label><input class="px" type="radio" name="session_place" id="newPlaceSession"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newPlaceSessionType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_session_place" value="<?php echo $pre_data['new_session_place'] ? $pre_data['new_session_place'] : ''; ?>">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#newPlaceSession").on("click", function () {
                                                $('#newPlaceSessionType').show();
                                            });

                                            $(".newPlaceSession").on("click", function () {
                                                $('#newPlaceSessionType').hide();
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Number of Sessions (Estimate)</label>
                                        <input class="form-control" type="text" id="session_number" name="session_number" value="<?php echo $pre_data['session_number'] ? $pre_data['session_number'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Duration of Session</label>
                                        <input class="form-control" type="text" id="session_duration" name="session_duration" value="<?php echo $pre_data['session_duration'] ? $pre_data['session_duration'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Other Requirements</label>
                                        <input class="form-control" type="text" id="other_requirements" name="other_requirements" value="<?php echo $pre_data['other_requirements'] ? $pre_data['other_requirements'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Referrals</label>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Referred to" type="text" name="reffer_to" value="<?php echo $pre_data['reffer_to'] ? $pre_data['reffer_to'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Address of organization/individual" type="text" name="referr_address" value="<?php echo $pre_data['referr_address'] ? $pre_data['referr_address'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Phone Number" type="text" name="contact_number" value="<?php echo $pre_data['contact_number'] ? $pre_data['contact_number'] : ''; ?>">
                                    </div>
                                    <div class="form-group ">
                                        <label>Reason for Referral</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Trauma Counselling" <?php
                                                    if (in_array('Trauma Counselling', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Trauma Counselling </span></label>
                                                <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Family Counseling" <?php
                                                    if (in_array('Family Counseling', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Family Counseling </span></label>
                                                <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Psychiatric Treatment" <?php
                                                    if (in_array('Psychiatric Treatment', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Psychiatric Treatment  </span></label>
                                                <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Medical Treatment" <?php
                                                    if (in_array('Medical Treatment', $problem_identified)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Medical Treatment </span></label>
                                                <label><input class="px col-sm-12" type="checkbox" name="reason_for_reffer[]" id="newReasonReferral"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newReasonReferralTypes" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_reason_for_reffer" value="<?php echo $pre_data['new_reason_for_reffer'] ? $pre_data['new_reason_for_reffer'] : ''; ?>">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#newReasonReferral").on("click", function () {
                                                $('#newReasonReferralTypes').toggle();
                                            });
                                        });
                                    </script>
                                </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="FamilyCounseling">
                        <fieldset>
                            <legend>Section 3.1: Family Counseling Session</legend>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($family_counsellings['data'] as $value):
                                        ?>
                                        <tr>
                                            <td><?php echo date('d-m-Y', strtotime($value['entry_date'])) ?></td>
                                            <td>
                                                <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_family_counseling&edit=' . $value['pk_psycho_family_counselling_id']) ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach
                                    ?>
                                </tbody>
                            </table>
                            <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_family_counseling&customer_id=' . $edit) ?>" target="_blank" class="btn btn-success btn-sm" style="margin-bottom: 1%">Add New Family Counseling</a>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="ReintegrationSession">
                        <fieldset>
                            <legend>Section 3.3: Psychosocial Reintegration Session Activities</legend>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($psychosocial_sessions['data'] as $value):
                                        ?>
                                        <tr>
                                            <td><?php echo date('d-m-Y', strtotime($value['entry_date'])) ?></td>
                                            <td>
                                                <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_psychosocial_session&edit=' . $value['pk_psycho_session_id']) ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach
                                    ?>
                                </tbody>
                            </table>
                            <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_psychosocial_session&customer_id=' . $edit) ?>" target="_blank" class="btn btn-success btn-sm" style="margin-bottom: 1%">Add New Psychosocial Session</a>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="SessionCompletion">
                        <fieldset>
                            <legend>Section 3.4: Session Completion Status</legend>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($psychosocial_completions['data'] as $value):
                                        ?>
                                        <tr>
                                            <td><?php echo date('d-m-Y', strtotime($value['entry_date'])) ?></td>
                                            <td>
                                                <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_session_completion&edit=' . $value['pk_psycho_completion_id']) ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach
                                    ?>
                                </tbody>
                            </table>
                            <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_session_completion&customer_id=' . $edit) ?>" target="_blank" class="btn btn-success btn-sm" style="margin-bottom: 1%">Add New Session Completion</a>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="ReintegrationFollowup">
                        <fieldset>
                            <legend>Section 3.5: Psychosocial Reintegration (Followup)</legend>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($psychosocial_followups['data'] as $value):
                                        ?>
                                        <tr>
                                            <td><?php echo date('d-m-Y', strtotime($value['entry_date'])) ?></td>
                                            <td>
                                                <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_psychosocial_followup&edit=' . $value['pk_psycho_followup_id']) ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach
                                    ?>
                                </tbody>
                            </table>
                            <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_psychosocial_followup&customer_id=' . $edit) ?>" target="_blank" class="btn btn-success btn-sm" style="margin-bottom: 1%">Add New Psychosocial Followup</a>
                        </fieldset>
                    </div>





                    <div class="tab-pane fade " id="EconomicReintegration">
                        <fieldset>
                            <legend>Section 4: Economic Reintegration Support</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">In-kind Support from Project</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="inkind_project[]" value="Microbusiness" <?php
                                                        if (in_array('Microbusiness', $inkind_project)) {
                                                            echo 'checked';
                                                        }
                                                        ?>
                                                                                   ><span class="lbl">Microbusiness</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="inkind_project[]" value="Business grant from project" <?php
                                                                if (in_array('Business grant from project', $inkind_project)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>
                                                                          ><span class="lbl">Business grant from project</span></label>
                                                            <label><input class="px" type="checkbox" name="inkind_project[]" value="Enrolled in community enterprise" <?php
                                                                if (in_array('Enrolled in community enterprise', $inkind_project)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Enrolled in community enterprise</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="inkind_project[]" value="Material Assistance" <?php
                                                        if (in_array('Want to leave home', $inkind_project)) {
                                                            echo 'checked';
                                                        }
                                                        ?>
                                                                                   ><span class="lbl">Material Assistance</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="inkind_project[]" value="Business equipment/tools"
                                                                <?php
                                                                if (in_array('Business equipment/tools', $inkind_project)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>
                                                                          ><span class="lbl">Business equipment/tools</span></label>
                                                            <label><input class="px" type="checkbox" name="inkind_project[]" value="Lease land or pond for business"
                                                                <?php
                                                                if (in_array('Lease land or pond for business', $inkind_project)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>
                                                                          ><span class="lbl">Lease land or pond for business</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Training (Financial Literacy Training)" <?php
                                                        if (in_array('Training (Financial Literacy Training)', $inkind_project)) {
                                                            echo 'checked';
                                                        }
                                                        ?>
                                                                                    ><span class="lbl">Training (Financial Literacy Training)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Advance Training" <?php
                                                        if (in_array('Advance Training', $inkind_project)) {
                                                            echo 'checked';
                                                        }
                                                        ?>
                                                                                    ><span class="lbl">Advance Training</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Safe Migration" <?php
                                                        if (in_array('Safe Migration', $inkind_project)) {
                                                            echo 'checked';
                                                        }
                                                        ?>
                                                                                    ><span class="lbl">Safe Migration</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="inkind_project[]" id="newInkind"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newInkindType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_inkind_project" value="<?php echo $pre_data['new_inkind_project'] ? $pre_data['new_inkind_project'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newInkind").on("click", function () {
                                                    $('#newInkindType').toggle();
                                                });
                                            });
                                        </script>
                                    </fieldset >
                                    <div class="form-group">
                                        <label>Received Financial Literacy Training ?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="InkindReceived" name="inkind_received" value="yes" <?php echo $pre_data && $pre_data['inkind_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noInkindReceived" name="inkind_received" value="no" <?php echo $pre_data && $pre_data['inkind_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group LiteracyTraining" style="display:none">
                                        <input class="form-control" type="text" name="training_duration" placeholder="Duration of training" value="<?php echo $pre_data['training_duration'] ? $pre_data['training_duration'] : ''; ?>">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#yesCooperated').is(':checked');

                                            if (isChecked == true) {
                                                $('.LiteracyTraining').show();
                                            }

                                            $("#InkindReceived").on("click", function () {
                                                $('.LiteracyTraining').show();
                                            });

                                            $("#noInkindReceived").on("click", function () {
                                                $('.LiteracyTraining').hide();
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Training Certificate Received</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="is_certification_received" name="is_certification_received" value="yes" <?php echo $pre_data && $pre_data['is_certification_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="is_certification_received" name="is_certification_received" value="no" <?php echo $pre_data && $pre_data['is_certification_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>How has the training been used so far?</label>
                                        <textarea class="form-control" rows="2" name="training_used" value="<?php echo $pre_data['training_used'] ? $pre_data['training_used'] : ''; ?>" placeholder=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Any other Comments</label>
                                        <textarea class="form-control" rows="2"  name="other_comments" value="<?php echo $pre_data['other_comments'] ? $pre_data['other_comments'] : ''; ?>" placeholder="Any other Comments"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Microbusiness Established</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesMicrobusiness" name="microbusiness_established" value="yes" <?php echo $pre_data && $pre_data['microbusiness_established'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noMicrobusiness" name="microbusiness_established" value="no" <?php echo $pre_data && $pre_data['microbusiness_established'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group Microbusiness" style="display:none">
                                        <input class="form-control" type="text" name="month_inauguration" placeholder="Month Of Business Inauguration" value="<?php echo $pre_data['month_inauguration'] ? $pre_data['month_inauguration'] : ''; ?>"><br />
                                        <input class="form-control" type="text" name="year_inauguration" placeholder="Year of Business Inauguration" value="<?php echo $pre_data['year_inauguration'] ? $pre_data['year_inauguration'] : ''; ?>">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#yesCooperated').is(':checked');

                                            if (isChecked == true) {
                                                $('.Microbusiness').show();
                                            }

                                            $("#yesMicrobusiness").on("click", function () {
                                                $('.Microbusiness').show();
                                            });

                                            $("#noMicrobusiness").on("click", function () {
                                                $('.Microbusiness').hide();
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Family members received Financial Literacy Training</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesFamilyTraining" name="family_training" value="yes" <?php echo $pre_data && $pre_data['family_training'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noFamilyTraining" name="family_training" value="no" <?php echo $pre_data && $pre_data['family_training'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group FamilyTraining" style="display:none">
                                        <label>Date of Training</label>
                                        <div class="input-group">
                                            <input id="FamilyTrainingDATE" type="text" class="form-control" name="traning_entry_date" value="<?php echo $pre_data['traning_entry_date'] && $pre_data['traning_entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['traning_entry_date'])) : date('d-m-Y'); ?>">
                                        </div></br>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('FamilyTrainingDATE');
                                            });
                                        </script>
                                        <input class="form-control" type="text" name="place_traning" placeholder="Place of Training" value="<?php echo $pre_data['place_traning'] ? $pre_data['place_traning'] : ''; ?>"><br />
                                        <input class="form-control" type="text" name="duration_traning" placeholder="Duration of Training" value="<?php echo $pre_data['duration_traning'] ? $pre_data['duration_traning'] : ''; ?>">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#yesCooperated').is(':checked');

                                            if (isChecked == true) {
                                                $('.FamilyTraining').show();
                                            }

                                            $("#yesFamilyTraining").on("click", function () {
                                                $('.FamilyTraining').show();
                                            });

                                            $("#noFamilyTraining").on("click", function () {
                                                $('.FamilyTraining').hide();
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Status Of Training (*)</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="" name="training_status" value="completed" <?php echo $pre_data && $pre_data['training_status'] == 'completed' ? 'checked' : '' ?>><span class="lbl">Completed</span></label>
                                                <label><input class="px" type="radio" id="" name="training_status" value="uncompleted" <?php echo $pre_data && $pre_data['training_status'] == 'uncompleted' ? 'checked' : '' ?>><span class="lbl">Uncompleted</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Name of the Vocational Training Received</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Agriculture forestry, fishing and farming" <?php
                                                        if (in_array('Agriculture forestry, fishing and farming', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>
                                                                                    ><span class="lbl">Agriculture forestry, fishing and farming</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Mining and quarrying" <?php
                                                        if (in_array('Mining and quarrying', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Mining and quarrying</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Manufacturing" <?php
                                                        if (in_array('Manufacturing', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Manufacturing</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Energies supply" <?php
                                                        if (in_array('Energies supply', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Energies supply</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Water supply and waste management" <?php
                                                        if (in_array('Water supply and waste management', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Water supply and waste management</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Construction" <?php
                                                        if (in_array('Construction', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Construction</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Electrical and housing wiring" <?php
                                                        if (in_array('Electrical and housing wiring', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Electrical and housing wiring</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Masonry" <?php
                                                        if (in_array('Masonry', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Masonry</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="received_vocational_training[]" value="Trade and repair" <?php
                                                        if (in_array('Trade and repair', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Trade and repair</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="received_vocational_training[]" value="Carpentry" <?php
                                                                if (in_array('Carpentry', $received_vocational_training)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Carpentry </span></label>
                                                            <label><input class="px" type="checkbox" name="received_vocational_training[]" value="Electronics and Maintenance" <?php
                                                                if (in_array('Electronics and Maintenance', $received_vocational_training)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Electronics and Maintenance</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Transportation and storage" <?php
                                                        if (in_array('Transportation and storage', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Transportation and storage</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Hospitality" <?php
                                                        if (in_array('Hospitality', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Hospitality</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="IT and communication" <?php
                                                        if (in_array('IT and communication', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">IT and communication</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Computer classes" <?php
                                                        if (in_array('Computer classes', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Computer classes</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Financial and insurance activities" <?php
                                                        if (in_array('Financial and insurance activities', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Financial and insurance activities</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Real estate" <?php
                                                        if (in_array('Real estate', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Real estate</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Professional scientific and technical activities" <?php
                                                        if (in_array('Professional scientific and technical activities', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Professional scientific and technical activities</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Administrative and support services (incl. cleaning and maintenance)" <?php
                                                        if (in_array('Administrative and support services (incl. cleaning and maintenance)', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Administrative and support services (incl. cleaning and maintenance)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Public administration and defense"><span class="lbl" <?php
                                                        if (in_array('Public administration and defense', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>>Public administration and defense</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Education (Teaching)" <?php
                                                        if (in_array('Education (Teaching)', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Education (Teaching)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Arts and entertainment" <?php
                                                        if (in_array('Arts and entertainment', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Arts and entertainment</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Domestic work" <?php
                                                        if (in_array('Domestic work', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Domestic work</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="received_vocational_training[]" id="newVocational"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newVocationalType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" value="<?php echo $pre_data['new_received_vocational_training'] ? $pre_data['new_received_vocational_training'] : ''; ?>" name="new_received_vocational_training" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newVocational").on("click", function () {
                                                    $('#newVocationalType').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group">
                                            <label>Start Date of Training</label>
                                            <div class="input-group">
                                                <input id="StartDateTraining" type="text" class="form-control" name="training_start_date" value="<?php echo $pre_data['training_start_date'] && $pre_data['training_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['training_start_date'])) : date('d-m-Y'); ?>">
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('StartDateTraining');
                                            });
                                        </script>
                                        <div class="form-group">
                                            <label>End Date of Training</label>
                                            <div class="input-group">
                                                <input id="EndDateTraining" type="text" class="form-control" name="training_end_date" value="<?php echo $pre_data['training_end_date'] && $pre_data['training_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['training_end_date'])) : date('d-m-Y'); ?>">
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('EndDateTraining');
                                            });
                                        </script>
                                    </fieldset>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade" id="EconomicReferrals">
                        <fieldset>
                            <legend>Section 4.1: Economic Reintegration Referrals</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Referrals done for Vocational Training</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesReferralstraining" name="is_vocational_training" value="yes" <?php echo $pre_data && $pre_data['is_vocational_training'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noReferralstraining" name="is_vocational_training" value="no" <?php echo $pre_data && $pre_data['is_vocational_training'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $('.Referralstraining').hide();
                                            var isChecked = $('#yesReferralstraining').is(':checked');

                                            if (isChecked == true) {
                                                $('.Referralstraining').show();
                                            }

                                            $("#yesReferralstraining").on("click", function () {
                                                $('.Referralstraining').show();
                                            });

                                            $("#noReferralstraining").on("click", function () {
                                                $('.Referralstraining').hide();
                                            });
                                        });
                                    </script>
                                    <fieldset class="scheduler-border Referralstraining">
                                        <legend class="scheduler-border">Name of the Vocational Training Received</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Agriculture forestry, fishing and farming" <?php
                                                        if (in_array('Agriculture forestry, fishing and farming', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Agriculture forestry, fishing and farming</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Mining and quarrying" <?php
                                                        if (in_array('Mining and quarrying', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Mining and quarrying</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Manufacturing" <?php
                                                        if (in_array('Manufacturing', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Manufacturing</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Energies supply" <?php
                                                        if (in_array('Energies supply', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Energies supply</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Water supply and waste management" <?php
                                                        if (in_array('Water supply and waste management', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Water supply and waste management</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Construction" <?php
                                                        if (in_array('Construction', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Construction</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Electrical and housing wiring" <?php
                                                        if (in_array('Electrical and housing wiring', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Electrical and housing wiring</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Masonry" <?php
                                                        if (in_array('Masonry', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Masonry</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="received_vocational[]" value="Trade and repair" <?php
                                                        if (in_array('Trade and repair', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Trade and repair</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="received_vocational[]" value="Carpentry" <?php
                                                                if (in_array('Carpentry', $received_vocational)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Carpentry </span></label>
                                                            <label><input class="px" type="checkbox" name="received_vocational[]" value="Electronics and Maintenance" <?php
                                                                if (in_array('Electronics and Maintenance', $received_vocational)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Electronics and Maintenance</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Transportation and storage" <?php
                                                        if (in_array('Transportation and storage', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Transportation and storage</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Hospitality" <?php
                                                        if (in_array('Hospitality', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Hospitality</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="IT and communication" <?php
                                                        if (in_array('IT and communication', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">IT and communication</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Computer classes" <?php
                                                        if (in_array('Computer classes', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Computer classes</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Financial and insurance activities" <?php
                                                        if (in_array('Financial and insurance activities', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Financial and insurance activities</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Real estate" <?php
                                                        if (in_array('Real estate', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Real estate</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Professional scientific and technical activities" <?php
                                                        if (in_array('Professional scientific and technical activities', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Professional scientific and technical activities</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Administrative and support services (incl. cleaning and maintenance)" <?php
                                                        if (in_array('Administrative and support services (incl. cleaning and maintenance)', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Administrative and support services (incl. cleaning and maintenance)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Public administration and defense" <?php
                                                        if (in_array('Public administration and defense', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Public administration and defense</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Education (Teaching)" <?php
                                                        if (in_array('Education (Teaching)', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Education (Teaching)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Arts and entertainment" <?php
                                                        if (in_array('Arts and entertainment', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Arts and entertainment</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Domestic work" <?php
                                                        if (in_array('Domestic work', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Domestic work</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="received_vocational[]" id="newReferralstraining"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newReferralstrainingType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_received_vocational" value="<?php echo $pre_data['new_received_vocational'] ? $pre_data['new_received_vocational'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#newReferralstraining").on("click", function () {
                                                    $('#newReferralstrainingType').toggle();
                                                });
                                            });
                                        </script>
                                    </fieldset>
                                    <div class="form-group">
                                        <label>Training Certificate Received</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="is_Yes_certificate_received" name="is_certificate_received" value="yes" <?php echo $pre_data && $pre_data['is_certificate_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="is_No_certificate_received" name="is_certificate_received" value="no" <?php echo $pre_data && $pre_data['is_certificate_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="usedfar" style="display: none;">
                                        <div class="form-group">
                                            <label>How has the training been used so far?</label>
                                            <textarea class="form-control" name="used_far" rows="2" placeholder=""></textarea>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#is_Yes_certificate_received').change(function(){
                                                    if(this.checked){
                                                        $('#usedfar').show();
                                                    }else{
                                                        $('#usedfar').show();
                                                    }
                                                });
                                                $('#isCompletedNo').change(function(){
                                                    if(this.checked){
                                                        $('#usedfar').hide();
                                                    }else{
                                                        $('#usedfar').show();
                                                    }
                                                });
                                            });
                                        </script> 

                                    </div>
                                    <div class="form-group">
                                        <label>Any other Comments</label>
                                        <textarea class="form-control" name="other_comments" rows="2" placeholder=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Referrals done for economic services</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="is_economic_services" name="is_economic_services" value="yes" <?php echo $pre_data && $pre_data['is_economic_services'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="is_economic_services" name="is_economic_services" value="no" <?php echo $pre_data && $pre_data['is_economic_services'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Types of Economic Support</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="economic_support[]" value="Microbusiness" <?php
                                                        if (in_array('Microbusiness', $economic_support)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Microbusiness</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="economic_support[]" value="Business grant from project" <?php
                                                                if (in_array('Business grant from project', $economic_support)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Business grant from project</span></label>
                                                            <label><input class="px" type="checkbox" name="economic_support[]" value="Enrolled in community enterprise" <?php
                                                                if (in_array('Enrolled in community enterprise', $economic_support)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Enrolled in community enterprise</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="economic_support[]" value="Financial Services" <?php
                                                        if (in_array('Financial Services', $economic_support)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Financial Services</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="economic_support[]" value="Loan" <?php
                                                                if (in_array('Loan', $economic_support)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Loan</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="economic_support[]" value="Job Placement" <?php
                                                        if (in_array('Job Placement', $economic_support)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Job Placement</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="economic_support[]" value="Material Assistance" <?php
                                                        if (in_array('Material Assistance', $economic_support)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Material Assistance</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="economic_support[]" value="Business equipment/tools" <?php
                                                                if (in_array('Business equipment/tools', $economic_support)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Business equipment/tools</span></label>
                                                            <label><input class="px" type="checkbox" name="economic_support[]" value="Lease land or pond for business" <?php
                                                                if (in_array('Lease land or pond for business', $economic_support)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Lease land or pond for business</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="economic_support[]" value="Advance Training" <?php
                                                        if (in_array('Advance Training', $economic_support)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Advance Training</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="economic_support[]"  id="EconomicSupport"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="EconomicSupportType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_economic_support" value="<?php echo $pre_data['new_economic_support'] ? $pre_data['new_economic_support'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#EconomicSupport").on("click", function () {
                                                    $('#EconomicSupportType').toggle();
                                                });
                                            });
                                        </script>
                                    </fieldset >
                                    <div class="form-group">
                                        <label>Required Assistance Received</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="" name="is_assistance_received" value="yes" <?php echo $pre_data && $pre_data['is_assistance_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="" name="is_assistance_received" value="no" <?php echo $pre_data && $pre_data['is_assistance_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Referred To</label>
                                        <input class="form-control" type="text" name="refferd_to" placeholder="Name" value="<?php echo $pre_data['refferd_to'] ? $pre_data['refferd_to'] : ''; ?>"><br />
                                        <input class="form-control" type="text" name="refferd_address" placeholder="Address" value="<?php echo $pre_data['refferd_address'] ? $pre_data['refferd_address'] : ''; ?>"><br />
                                        <label>Date of Training</label>
                                        <div class="input-group">
                                            <input id="DatofTraining" type="text" class="form-control" name="trianing_date" value="<?php echo $pre_data['trianing_date'] && $pre_data['trianing_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['trianing_date'])) : date('d-m-Y'); ?>">
                                        </div><br />
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('DatofTraining');
                                            });
                                        </script>
                                        <input class="form-control" type="text" name="place_of_training" placeholder="Place of Training" value="<?php echo $pre_data['place_of_training'] ? $pre_data['place_of_training'] : ''; ?>"><br />
                                        <input class="form-control" type="text" name="duration_training" placeholder="Duration of Training" value="<?php echo $pre_data['duration_training'] ? $pre_data['duration_training'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Status of the Training</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="status_traning" name="status_traning" value="yes" <?php echo $pre_data && $pre_data['status_traning'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Completed</span></label>
                                                <label><input class="px" type="radio" id="status_traning" name="status_traning" value="no" <?php echo $pre_data && $pre_data['status_traning'] == 'no' ? 'checked' : '' ?>><span class="lbl">Not Completed</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>How has the assistance been utilized?</label>
                                        <textarea class="form-control" name="assistance_utilized" rows="2" placeholder=""></textarea>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="SocialReintegrationSupport">
                        <fieldset>
                            <legend>Section 5: Social Reintegration Support</legend>
                            <div class="row">
                                <div class="col-sm-12">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Types of Economic Support</legend>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <div class="options_holder radio">
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="reintegration_economic[]" value="Social Protection Schemes(Place to access to public services & Social Protection)" <?php
                                                            if (in_array('Social Protection Schemes(Place to access to public services & Social Protection)', $reintegration_economic)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Social Protection Schemes(Place to access to public services & Social Protection)</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Union Parished" <?php
                                                                    if (in_array('Union Parished"', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Union Parished</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Upazila Parished" <?php
                                                                    if (in_array('Upazila Parished', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Upazila Parished</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="District/Upazila Social Welfare office" <?php
                                                                    if (in_array('District/Upazila Social Welfare office', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">District/Upazila Social Welfare office</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="District/Upazila Youth Development office" <?php
                                                                    if (in_array('District/Upazila Youth Development office', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">District/Upazila Youth Development office</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="District/ Upazila Women Affairs Department" <?php
                                                                    if (in_array('District/ Upazila Women Affairs Department', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">District/ Upazila Women Affairs Department</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Private/ NGO" <?php
                                                                    if (in_array('Private/ NGO', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Private/ NGO</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="reintegration_economic[]" value="Medical Support" <?php
                                                            if (in_array('Medical Support', $reintegration_economic)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Medical Support</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Project" <?php
                                                                    if (in_array('Project', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Project</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Government health facility" <?php
                                                                    if (in_array('Government health facility', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Government health facility</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Private/ NGO operated health service centre" <?php
                                                                    if (in_array('Private/ NGO operated health service centre', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Private/ NGO operated health service centre</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="reintegration_economic[]" value="Education" <?php
                                                            if (in_array('Education', $reintegration_economic)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Education</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Admission" <?php
                                                                    if (in_array('Admission', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Admission</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Stipend/ Scholarship" <?php
                                                                    if (in_array('Stipend/ Scholarship', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Stipend/ Scholarship</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="reintegration_economic[]" value="Housing" <?php
                                                            if (in_array('Housing', $reintegration_economic)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Housing</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Allocation of Khas land" <?php
                                                                    if (in_array('Allocation of Khas land', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Allocation of ‘Khas land’</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Support for housing loan" <?php
                                                                    if (in_array('Support for housing loan', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Support for housing loan</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="reintegration_economic[]" value="Legal Services" <?php
                                                            if (in_array('Legal Services', $reintegration_economic)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Legal Services</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="reintegration_economic[]" value="Legal Aid" <?php
                                                            if (in_array('Legal Aid', $reintegration_economic)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Legal Aid</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="reintegration_economic[]" value="Legal Arbitration" <?php
                                                            if (in_array('Legal Arbitration', $reintegration_economic)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Legal Arbitration</span></label>
                                                        <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="reintegration_economic[]" id="TypesofEconomic"><span class="lbl">Others</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12" id="TypesofEconomicType" style="display: none; margin-bottom: 1em;">
                                                <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_reintegration_economic" value="<?php echo $pre_data['new_reintegration_economic'] ? $pre_data['new_reintegration_economic'] : ''; ?>">
                                            </div>
                                            <script>
                                                init.push(function () {
                                                    $("#TypesofEconomic").on("click", function () {
                                                        $('#TypesofEconomicType').toggle();
                                                    });
                                                });
                                            </script>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">  
                                                <label>Date Services Received (Social)</label>
                                                <div class="input-group">
                                                    <input id="ServicesReceivedSocial" type="text" class="form-control" name="soical_date" value="<?php echo $pre_data['soical_date'] && $pre_data['soical_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['soical_date'])) : date('d-m-Y'); ?>">
                                                </div><br />
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        _datepicker("ServicesReceivedSocial");
                                                    });
                                                </script>
                                            </div>
                                            <div class="form-group">
                                                <label>Date Services Received (Medical)</label>
                                                <div class="input-group">
                                                    <input id="ServicesReceivedMedical" type="text" class="form-control" name="medical_date" value="<?php echo $pre_data['medical_date'] && $pre_data['medical_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['medical_date'])) : date('d-m-Y'); ?>">
                                                </div><br />
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        _datepicker("ServicesReceivedMedical");
                                                    });
                                                </script>
                                            </div>
                                            <div class="form-group">
                                                <label>Date Services Received (Education))</label>
                                                <div class="input-group">
                                                    <input id="ServicesReceivedEducation" type="text" class="form-control" name="date_education" value="<?php echo $pre_data['date_education'] && $pre_data['date_education'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['date_education'])) : date('d-m-Y'); ?>">
                                                </div><br />
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        _datepicker("ServicesReceivedEducation");
                                                    });
                                                </script>
                                            </div>
                                            <div class="form-group">
                                                <label>Date Services Received (Housing))</label>
                                                <div class="input-group">
                                                    <input id="ServicesReceivedHousing" type="text" class="form-control" name="date_housing" value="<?php echo $pre_data['date_housing'] && $pre_data['date_housing'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['date_housing'])) : date('d-m-Y'); ?>">
                                                </div><br />
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        _datepicker("ServicesReceivedHousing");
                                                    });
                                                </script>
                                            </div>
                                            <div class="form-group">
                                                <label>Date Services Received (Legal & Others))</label>
                                                <div class="input-group">
                                                    <input id="ServicesReceivedOthers" type="text" class="form-control" name="date_legal" value="<?php echo $pre_data['date_legal'] && $pre_data['date_legal'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['date_legal'])) : date('d-m-Y'); ?>">
                                                </div><br />
                                                <script type="text/javascript">
                                                    init.push(function () {
                                                        _datepicker("ServicesReceivedOthers");
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </fieldset>	
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Attended IPT shows?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesLiteracyTraining" name="attended_ipt" value="yes" <?php echo $pre_data && $pre_data['attended_ipt'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noLiteracyTraining" name="attended_ipt" value="no" <?php echo $pre_data && $pre_data['attended_ipt'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="LearnShowType" style="display: none; margin-bottom: 1em;">
                                        <textarea class="form-control" name="learn_show" rows="3" value="<?php echo $pre_data['learn_show'] ? $pre_data['learn_show'] : ''; ?>" placeholder="Lessons learnt from IPT show"></textarea>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#yesLiteracyTraining").on("click", function () {
                                                $('#LearnShowType').toggle();
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Attended community video shows?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesCommunityVideo" name="is_per_community_video" value="yes" <?php echo $pre_data && $pre_data['is_per_community_video'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noCommunityVideo" name="is_per_community_video" value="no" <?php echo $pre_data && $pre_data['is_per_community_video'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group"id="LearnVideo" style="display: none; margin-bottom: 1em;">
                                        <textarea class="form-control" name="learn_video" rows="3" placeholder="Lessons learnt from video show"></textarea>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#yesCommunityVideo").on("click", function () {
                                                $('#LearnVideo').toggle();
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="col-sm-8">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Types of Support Referred for</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="support_referred[]" value="Social Protection Schemes(Place to access to public services & Social Protection)" <?php
                                                        if (in_array('Social Protection Schemes(Place to access to public services & Social Protection)', $support_referred)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Social Protection Schemes(Place to access to public services & Social Protection)</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Union Parished" <?php
                                                                if (in_array('Union Parished', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Union Parished</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Upazila Parished" <?php
                                                                if (in_array('Upazila Parished', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Upazila Parished</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="District/Upazila Social Welfare office" <?php
                                                                if (in_array('District/Upazila Social Welfare office', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">District/Upazila Social Welfare office</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="District/Upazila Youth Development office" <?php
                                                                if (in_array('District/Upazila Youth Development office', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">District/Upazila Youth Development office</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="District/ Upazila Women Affairs Department" <?php
                                                                if (in_array('District/ Upazila Women Affairs Department', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">District/ Upazila Women Affairs Department</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Private/ NGO" <?php
                                                                if (in_array('Private/ NGO', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Private/ NGO</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="support_referred[]" value="Medical Support" <?php
                                                        if (in_array('Medical Support', $support_referred)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Medical Support</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Project" <?php
                                                                if (in_array('Project', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Project</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Government health facility" <?php
                                                                if (in_array('Government health facility', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Government health facility</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Private/ NGO operated health service centre" <?php
                                                                if (in_array('Private/ NGO operated health service centre', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Private/ NGO operated health service centre</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="support_referred[]" value="Education" <?php
                                                        if (in_array('Education', $support_referred)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Education</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Admission" <?php
                                                                if (in_array('Admission', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Admission</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Stipend/ Scholarship" <?php
                                                                if (in_array('Stipend/ Scholarship', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Stipend/ Scholarship</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="support_referred[]" value="Housing" <?php
                                                        if (in_array('Housing', $support_referred)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Housing</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Allocation of ‘Khas land’" <?php
                                                                if (in_array('Allocation of ‘Khas land’', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Allocation of ‘Khas land’</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Support for housing loan" <?php
                                                                if (in_array('Support for housing loan', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Support for housing loan</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="support_referred[]" value="Legal Services" <?php
                                                        if (in_array('Legal Services', $support_referred)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Legal Services</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="support_referred[]" value="Legal Aid" <?php
                                                        if (in_array('Legal Aid', $support_referred)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Legal Aid</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="support_referred[]" value="Legal Arbitration" <?php
                                                        if (in_array('Legal Arbitration', $support_referred)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Legal Arbitration</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" id="supportreferred"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" id="TypesupportreferredType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_supportreferred" value="<?php echo $pre_data['new_supportreferred'] ? $pre_data['new_supportreferred'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#supportreferred").on("click", function () {
                                                    $('#TypesupportreferredType').toggle();
                                                });
                                            });
                                        </script>
                                    </fieldset>	
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="ReviewFollowUp">
                        <fieldset>
                            <legend>Section 6: Review and Follow-Up</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Case dropped out from the project? (*)</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesCasedropped" name="casedropped" value="yes" <?php echo $pre_data && $pre_data['casedropped'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noCasedropped" name="casedropped" value="no" <?php echo $pre_data && $pre_data['casedropped'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $('.Casedropped').hide();
                                            var isChecked = $('#yesCasedropped').is(':checked');

                                            if (isChecked == true) {
                                                $('.Casedropped').show();
                                            }

                                            $("#yesCasedropped").on("click", function () {
                                                $('.Casedropped').show();
                                            });

                                            $("#noCasedropped").on("click", function () {
                                                $('.Casedropped').hide();
                                            });
                                        });
                                    </script>
                                    <fieldset class="scheduler-border Casedropped">
                                        <legend class="scheduler-border">Reason for Dropping Out</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="reason_dropping[]" value="Lack of interest" <?php
                                                        if (in_array('Lack of interest', $reason_dropping)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Lack of interest</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="reason_dropping[]" value="Critical illness" <?php
                                                        if (in_array('Critical illness', $reason_dropping)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Critical illness</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="reason_dropping[]" value="Family issues" <?php
                                                        if (in_array('Family issues', $reason_dropping)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Family issues</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="reason_dropping[]" value="Have other issues to attend to" <?php
                                                        if (in_array('Have other issues to attend to', $reason_dropping)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Have other issues to attend to</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="reason_dropping[]" value="Re-migrated" <?php
                                                        if (in_array('Re-migrated', $reason_dropping)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Re-migrated</span></label>
                                                    <label class="col-sm-12"><input  class="px col-sm-12" type="checkbox" id="ReasonDropping"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="ReasonDroppingType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_reason_dropping" value="<?php echo $pre_data['new_reason_dropping'] ? $pre_data['new_reason_dropping'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#ReasonDropping").on("click", function () {
                                                    $('#ReasonDroppingType').toggle();
                                                });
                                            });
                                        </script>
                                    </fieldset >
                                    <fieldset class="scheduler-border Casedropped">
                                        <legend class="scheduler-border">Confirmed Services Received after 3 Months</legend>
                                        <div class="form-group">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value=">Child Care" <?php
                                                        if (in_array('Child Care"', $confirm_services)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Child Care</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Education" <?php
                                                        if (in_array('Education', $confirm_services)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Education</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Admission" <?php
                                                                if (in_array('Admission', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Admission</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Scholarship/Stipend" <?php
                                                                if (in_array('Scholarship/Stipend', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Scholarship/Stipend</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Financial Services" <?php
                                                        if (in_array('Financial Services', $confirm_services)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Financial Services</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Loan" <?php
                                                                if (in_array('Loan', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Loan</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Housing" <?php
                                                        if (in_array('Housing', $confirm_services)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Housing</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Allocation for khas land" <?php
                                                                if (in_array('Housing', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Allocation for khas land</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Support for land allocation" <?php
                                                                if (in_array('Support for land allocation', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Support for land allocation</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Job Placement" <?php
                                                        if (in_array('Job Placement', $confirm_services)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Job Placement</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Legal Services" <?php
                                                        if (in_array('Legal Services', $confirm_services)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Legal Services</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Legal Aid" <?php
                                                                if (in_array('Legal Aid', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Legal Aid</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Legal Arbitration" <?php
                                                                if (in_array('Legal Arbitration', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Legal Arbitration</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Job Placement" <?php
                                                        if (in_array('Job Placement', $confirm_services)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Job Placement</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Training" <?php
                                                        if (in_array('Training', $confirm_services)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Training</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Financial Literacy Training" <?php
                                                                if (in_array('Financial Literacy Training', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Financial Literacy Training</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Advance training from project" <?php
                                                                if (in_array('Advance training from project"', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Advance training from project</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Advance training through referrals" <?php
                                                                if (in_array('Advance training through referrals', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Advance training through referrals</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Material Assistance" <?php
                                                        if (in_array('Material Assistance', $confirm_services)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Material Assistance</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Business equipment/tools" <?php
                                                                if (in_array('Business equipment/tools', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Business equipment/tools</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Allocation of land or pond for business" <?php
                                                                if (in_array('Allocation of land or pond for business', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Allocation of land or pond for business</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Remigration" <?php
                                                        if (in_array('Remigration', $confirm_services)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Remigration</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Direct Assistance from project" <?php
                                                                if (in_array('Direct Assistance from project', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Direct Assistance from project</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Referrals support from project" <?php
                                                                if (in_array('Referrals support from project', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Referrals support from project</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Medical Support" <?php
                                                        if (in_array('Medical Support', $confirm_services)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Medical Support</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Medical treatment" <?php
                                                                if (in_array('Medical treatment', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Medical treatment</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Psychiatric treatment" <?php
                                                                if (in_array('Psychiatric treatment', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Psychiatric treatment</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Microbusiness" <?php
                                                        if (in_array('Microbusiness', $confirm_services)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Microbusiness</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Business grant" <?php
                                                                if (in_array('Business grant"', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Business grant</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Psychosocial Support" <?php
                                                        if (in_array('Psychosocial Support', $confirm_services)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Psychosocial Support</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Individual Counselling" <?php
                                                                if (in_array('Individual Counselling', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Individual Counselling</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Family Counselling" <?php
                                                                if (in_array('Family Counselling', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Family Counselling</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Trauma Counseling" <?php
                                                                if (in_array('Trauma Counseling', $confirm_services)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Trauma Counseling</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-sm-12">
                                                        <label><input class="px" type="checkbox" id="YesSpecifySevervice" value="Social Protection Schemes"  ><span class="lbl">Social Protection Schemes</span></label>
                                                    </div>
                                                    <div class="form-group col-sm-12" id="SpecifySevervice" style="margin-bottom: 1em; display: none;">
                                                        <input class="form-control" type="text" placeholder="Specify the services" name="social_protection_service" value="<?php echo $pre_data['social_protection_service'] ? $pre_data['social_protection_service'] : ''; ?>">
                                                    </div>
                                                    <script>
                                                        init.push(function () {
                                                            $("#YesSpecifySevervice").on("click", function () {
                                                                $('#SpecifySevervice').toggle();
                                                            });
                                                        });
                                                    </script>
                                                    <div class="form-group col-sm-12">
                                                        <label><input class="px" type="checkbox" id="YesSecurityMeasures" value="Special Security Measures, Please Specify"><span class="lbl">Special Security Measures</span></label>
                                                    </div>
                                                    <div class="form-group col-sm-12" id="SecurityMeasures" style="margin-bottom: 1em; display: none;">
                                                        <input class="form-control" type="text" name="special_security_measures" placeholder="Specify the services" value="<?php echo $pre_data['special_security_measures'] ? $pre_data['special_security_measures'] : ''; ?>">
                                                    </div>
                                                    <script>
                                                        init.push(function () {
                                                            $("#YesSecurityMeasures").on("click", function () {
                                                                $('#SecurityMeasures').toggle();
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>					
                                <div class="col-sm-6">
                                    <fieldset class="scheduler-border Casedropped">
                                        <legend class="scheduler-border">Comment on Situation of Case</legend>
                                        <div class="form-group">
                                            <textarea class="form-control" name="comment_psychosocial" value="<?php echo $pre_data['comment_psychosocial'] ? $pre_data['comment_psychosocial'] : ''; ?>" rows="3" placeholder="Comment on psychosocial reintegration"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" name="comment_economic" value="<?php echo $pre_data['comment_economic'] ? $pre_data['comment_economic'] : ''; ?>" rows="3" placeholder="Comment on economic reintegration"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" name="comment_social" value="<?php echo $pre_data['comment_social'] ? $pre_data['comment_social'] : ''; ?>" rows="3" placeholder="Comment on social reintegration"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" name="complete_income" value="<?php echo $pre_data['complete_income'] ? $pre_data['complete_income'] : ''; ?>" rows="3" placeholder="Complete income tracking information"></textarea>
                                        </div>
                                    </fieldset>
                                    <fieldset class="scheduler-border Casedropped">
                                        <legend class="scheduler-border">Status of Case after Receiving the Services</legend>
                                        <div class="form-group">
                                            <input class="form-control" name="monthly_income" value="<?php echo $pre_data['monthly_income'] ? $pre_data['monthly_income'] : ''; ?>" placeholder="Monthly income (BDT)" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="challenges" value="<?php echo $pre_data['challenges'] ? $pre_data['challenges'] : ''; ?>" placeholder="Challenges" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="actions_taken" value="<?php echo $pre_data['actions_taken'] ? $pre_data['actions_taken'] : ''; ?>" placeholder="Actions taken" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="remark_participant" value="<?php echo $pre_data['remark_participant'] ? $pre_data['remark_participant'] : ''; ?>" placeholder="Remark of the participant (if any)" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="comment_brac" value="<?php echo $pre_data['comment_brac'] ? $pre_data['comment_brac'] : ''; ?>" placeholder="Comment of BRAC Officer responsible for participant" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="remark_district" value="<?php echo $pre_data['remark_district'] ? $pre_data['remark_district'] : ''; ?>" placeholder="Remark of District Manager" type="text" name="" value="">
                                        </div>
                                    </fieldset>
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