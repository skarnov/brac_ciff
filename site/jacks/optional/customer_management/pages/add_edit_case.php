<?php
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

$args = array(
    'customer_id' => $edit,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_followup_id',
        'order' => 'DESC'
    ),
);

$reviews = $this->get_case_review($args);

$pre_data = array();
if ($edit) {
    $args = array(
        'select_fields' => array(
            'pk_immediate_support_id' => 'dev_immediate_supports.pk_immediate_support_id',
            'fk_staff_id' => 'dev_immediate_supports.fk_staff_id',
            'fk_customer_id' => 'dev_immediate_supports.fk_customer_id',
            'immediate_support' => 'dev_immediate_supports.immediate_support',
            'entry_date' => 'dev_immediate_supports.entry_date AS entry_date',
            'arrival_place' => 'dev_immediate_supports.arrival_place',
            'reintegration_financial_service' => 'dev_reintegration_plan.reintegration_financial_service',
            'service_requested' => 'dev_reintegration_plan.service_requested',
            'other_service_requested' => 'dev_reintegration_plan.other_service_requested',
            'social_protection' => 'dev_reintegration_plan.social_protection',
            'security_measure' => 'dev_reintegration_plan.security_measure',
            'service_requested_note' => 'dev_reintegration_plan.service_requested_note',
            'first_meeting' => 'dev_psycho_supports.first_meeting',
            'problem_identified' => 'dev_psycho_supports.problem_identified',
            'problem_description' => 'dev_psycho_supports.problem_description',
            'initial_plan' => 'dev_psycho_supports.initial_plan',
            'family_counseling' => 'dev_psycho_supports.family_counseling',
            'session_place' => 'dev_psycho_supports.session_place',
            'session_number' => 'dev_psycho_supports.session_number',
            'session_duration' => 'dev_psycho_supports.session_duration',
            'other_requirements' => 'dev_psycho_supports.other_requirements',
            'reffer_to' => 'dev_psycho_supports.reffer_to',
            'referr_address' => 'dev_psycho_supports.referr_address',
            'contact_number' => 'dev_psycho_supports.contact_number',
            'reason_for_reffer' => 'dev_psycho_supports.reason_for_reffer',
            'other_reason_for_reffer' => 'dev_psycho_supports.other_reason_for_reffer',
            'full_name' => 'dev_customers.full_name',
            'inkind_project' => 'dev_economic_supports.inkind_project',
            'other_inkind_project' => 'dev_economic_supports.other_inkind_project',
            'entry_date' => 'dev_economic_supports.entry_date AS economic_reintegration_date',
            'is_certification_received' => 'dev_economic_supports.is_certification_received',
            'training_used' => 'dev_economic_supports.training_used',
            'economic_other_comments' => 'dev_economic_supports.other_comments AS economic_other_comments',
            'microbusiness_established' => 'dev_economic_supports.microbusiness_established',
            'month_inauguration' => 'dev_economic_supports.month_inauguration',
            'year_inauguration' => 'dev_economic_supports.year_inauguration',
            'family_training' => 'dev_economic_supports.family_training',
            'traning_entry_date' => 'dev_economic_supports.traning_entry_date',
            'place_traning' => 'dev_economic_supports.place_traning',
            'duration_traning' => 'dev_economic_supports.duration_traning',
            'training_status' => 'dev_economic_supports.training_status',
            'financial_literacy_date' => 'dev_economic_supports.financial_literacy_date',
            'business_development_date' => 'dev_economic_supports.business_development_date',
            'product_development_date' => 'dev_economic_supports.product_development_date',
            'entrepreneur_training_date' => 'dev_economic_supports.entrepreneur_training_date',
            'other_financial_training_name' => 'dev_economic_supports.other_financial_training_name',
            'other_financial_training_date' => 'dev_economic_supports.other_financial_training_date',
            'entry_date' => 'dev_economic_reintegration_referrals.entry_date AS economic_reintegration_referral_date',
            'is_vocational_training' => 'dev_economic_reintegration_referrals.is_vocational_training',
            'received_vocational_training' => 'dev_economic_reintegration_referrals.received_vocational_training',
            'other_received_vocational_training' => 'dev_economic_reintegration_referrals.other_received_vocational_training',
            'received_vocational' => 'dev_economic_reintegration_referrals.received_vocational',
            'other_received_vocational' => 'dev_economic_reintegration_referrals.other_received_vocational',
            'economic_referrals_other_comments' => 'dev_economic_reintegration_referrals.other_comments AS economic_referrals_other_comments',
            'is_economic_services' => 'dev_economic_reintegration_referrals.is_economic_services',
            'economic_financial_service' => 'dev_economic_reintegration_referrals.economic_financial_service',
            'economic_support' => 'dev_economic_reintegration_referrals.economic_support',
            'other_economic_support' => 'dev_economic_reintegration_referrals.other_economic_support',
            'is_assistance_received' => 'dev_economic_reintegration_referrals.is_assistance_received',
            'refferd_to' => 'dev_economic_reintegration_referrals.refferd_to',
            'refferd_address' => 'dev_economic_reintegration_referrals.refferd_address',
            'trianing_date' => 'dev_economic_reintegration_referrals.trianing_date',
            'place_of_training' => 'dev_economic_reintegration_referrals.place_of_training',
            'duration_training' => 'dev_economic_reintegration_referrals.duration_training',
            'status_traning' => 'dev_economic_reintegration_referrals.status_traning',
            'assistance_utilized' => 'dev_economic_reintegration_referrals.assistance_utilized',
            'job_placement_date' => 'dev_economic_reintegration_referrals.job_placement_date',
            'financial_services_date' => 'dev_economic_reintegration_referrals.financial_services_date',
            'reintegration_economic' => 'dev_social_supports.reintegration_economic',
            'other_reintegration_economic' => 'dev_social_supports.other_reintegration_economic',
            'soical_date' => 'dev_social_supports.soical_date',
            'medical_date' => 'dev_social_supports.medical_date',
            'date_education' => 'dev_social_supports.date_education',
            'date_housing' => 'dev_social_supports.date_housing',
            'date_legal' => 'dev_social_supports.date_legal',
            'support_referred' => 'dev_social_supports.support_referred',
            'other_support_referred' => 'dev_social_supports.other_support_referred',
        ),
        'id' => $edit,
        'single' => true
    );

    $pre_data = $this->get_cases($args);

    $immediate_support = explode(',', $pre_data['immediate_support']);
    $service_requested = explode(',', $pre_data['service_requested']);
    $problem_identified = explode(',', $pre_data['problem_identified']);
    $reason_for_reffer = explode(',', $pre_data['reason_for_reffer']);
    $inkind_project = explode(',', $pre_data['inkind_project']);
    $received_vocational_training = explode(',', $pre_data['received_vocational_training']);
    $received_vocational = explode(',', $pre_data['received_vocational']);
    $economic_support = explode(',', $pre_data['economic_support']);
    $reintegration_economic = explode(',', $pre_data['reintegration_economic']);
    $support_referred = explode(',', $pre_data['support_referred']);

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

    if ($ret['support_update']) {
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
                        <a href="#SupportProvided" data-toggle="tab">Section 1: Immediate Support Provided After Arrival</a>
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
                            <legend>Section 1: Immediate Support Provided After Arrival</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Support Date</label>
                                        <div class="input-group">
                                            <input id="supportDate" type="text" class="form-control" name="support_date" value="<?php echo $pre_data['support_date'] && $pre_data['support_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['support_date'])) : ''; ?>">
                                        </div>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('supportDate');
                                            });
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <label>Arrival Place</label>
                                        <input type="text" class="form-control" name="arrival_place" value="<?php echo $pre_data['arrival_place'] ? $pre_data['arrival_place'] : ''; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label>Select Case Manager (*)</label>
                                        <select class="form-control" name="fk_staff_id">
                                            <option value="">Select One</option>
                                            <?php foreach ($all_staffs['data'] as $staff) : ?>
                                                <option value="<?php echo $staff['pk_user_id'] ?>" <?php echo ($pre_data['fk_staff_id'] == $staff['pk_user_id']) ? 'selected' : '' ?>><?php echo $staff['user_fullname'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <?php
                                $immediate_support = $immediate_support ? $immediate_support : array($immediate_support);
                                ?>   
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Immediate support services received</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">

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
                            </div>
                        </fieldset>
                    </div>
                    <?php
                    $service_requested = $service_requested ? $service_requested : array($service_requested);
                    ?> 
                    <div class="tab-pane fade " id="PreferredServices">
                        <fieldset>
                            <legend>Section 2: Preferred Services and Reintegration Plan</legend>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Plan Date</label>
                                            <div class="input-group">
                                                <input id="planDate" type="text" class="form-control" name="plan_date" value="<?php echo $pre_data['plan_date'] && $pre_data['plan_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['plan_date'])) : ''; ?>">
                                            </div>
                                            <script type="text/javascript">
                                                init.push(function () {
                                                    _datepicker('planDate');
                                                });
                                            </script>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">

                                    </div>
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
                                                            <label class="col-sm-6"><input class="px" type="checkbox" id="education" name="service_requested[]" value="Education" <?php
                                                                if (in_array('Education', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Education</span></label>
                                                            <div id="educationAttr" class="form-group col-sm-8">
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
                                                            <script>
                                                                init.push(function () {
                                                                    $('#educationAttr').hide();

                                                                    $("#education").on("click", function () {
                                                                        $('#educationAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" id="financialService" type="checkbox" name="service_requested[]" value="Financial Service" <?php
                                                                if (in_array('Financial Service', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Financial Services</span></label>
                                                            <div id="financialServiceAttr" class="form-group col-sm-12">
                                                                <div class="options_holder radio">
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Loan" <?php
                                                                        if (in_array('Loan', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Loan</span></label>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Other Financial Service</label>
                                                                    <input class="form-control" placeholder="Other financial service" type="text" name="reintegration_financial_service" value="<?php echo $pre_data['reintegration_financial_service'] ? $pre_data['reintegration_financial_service'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    $('#financialServiceAttr').hide();

                                                                    $("#financialService").on("click", function () {
                                                                        $('#financialServiceAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-6"><input class="px" id="housing" type="checkbox" name="service_requested[]" value="Housing" <?php
                                                                if (in_array('Housing', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Housing</span></label>
                                                            <div id="housingAttr" class="form-group col-sm-12">
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
                                                            <script>
                                                                init.push(function () {
                                                                    $('#housingAttr').hide();

                                                                    $("#housing").on("click", function () {
                                                                        $('#housingAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" name="service_requested[]" value="Job Placement" <?php
                                                                if (in_array('Job Placement', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Job Placement</span></label>
                                                            <label class="col-sm-12"><input class="px" id="legalServices" type="checkbox" name="service_requested[]" value="Legal Services" <?php
                                                                if (in_array('Legal Services', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Legal Services</span></label>
                                                            <div id="legalServicesAttr" class="form-group col-sm-12">
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
                                                            <script>
                                                                init.push(function () {
                                                                    $('#legalServicesAttr').hide();

                                                                    $("#legalServices").on("click", function () {
                                                                        $('#legalServicesAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" id="training" type="checkbox" name="service_requested[]" value="Training" <?php
                                                                if (in_array('Training', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Training</span></label>
                                                            <div id="trainingAttr" class="form-group col-sm-12">
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
                                                            <script>
                                                                init.push(function () {
                                                                    $('#trainingAttr').hide();

                                                                    $("#training").on("click", function () {
                                                                        $('#trainingAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" type="checkbox" id="materialAssistance" name="service_requested[]" value="Material Assistance" <?php
                                                                if (in_array('Material Assistance', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Material Assistance</span></label>
                                                            <div id="materialAssistanceAttr" class="form-group col-sm-12">
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
                                                            <script>
                                                                init.push(function () {
                                                                    $('#materialAssistanceAttr').hide();

                                                                    $("#materialAssistance").on("click", function () {
                                                                        $('#materialAssistanceAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" id="medicalSupport" type="checkbox" name="service_requested[]" value="Medical Support" <?php
                                                                if (in_array('Medical Support', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Medical Support</span></label>
                                                            <div id="medicalSupportAttr" class="form-group col-sm-12">
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
                                                            <script>
                                                                init.push(function () {
                                                                    $('#medicalSupportAttr').hide();

                                                                    $("#medicalSupport").on("click", function () {
                                                                        $('#medicalSupportAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" id="microbusiness" type="checkbox" name="service_requested[]" value="Microbusiness" <?php
                                                                if (in_array('Microbusiness', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Microbusiness</span></label>
                                                            <div id="microbusinessAttr" class="form-group col-sm-12">
                                                                <div class="options_holder radio">
                                                                    <label><input class="px" type="checkbox" name="service_requested[]" value="Business grant" <?php
                                                                        if (in_array('Business grant', $service_requested)) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>><span class="lbl">Business grant</span></label>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    $('#microbusinessAttr').hide();

                                                                    $("#microbusiness").on("click", function () {
                                                                        $('#microbusinessAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <label class="col-sm-12"><input class="px" id="psychosocialSupport" type="checkbox" name="service_requested[]" value="Psychosocial Support" <?php
                                                                if (in_array('Psychosocial Support', $service_requested)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Psychosocial Support</span></label>
                                                            <div id="psychosocialSupportAttr" class="form-group col-sm-12">
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
                                                            <script>
                                                                init.push(function () {
                                                                    $('#psychosocialSupportAttr').hide();

                                                                    $("#psychosocialSupport").on("click", function () {
                                                                        $('#psychosocialSupportAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                        </div>
                                                        <div class="col-sm-6">   
                                                            <div class="form-group">
                                                                <label><input class="px" id="socialProtection" type="checkbox" value="Social Protection Schemes" <?php echo $pre_data && $pre_data['social_protection'] != NULL ? 'checked' : '' ?>><span class="lbl">Social Protection Schemes</span></label>
                                                            </div>
                                                            <div id="socialProtectionAttr" class="form-group">
                                                                <input class="form-control" placeholder="Specify Social Protection Schemes" type="text" name="new_social_protection" value="<?php echo $pre_data['social_protection'] ? $pre_data['social_protection'] : ''; ?>">
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    $('#socialProtectionAttr').hide();

                                                                    $("#socialProtection").on("click", function () {
                                                                        $('#socialProtectionAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <div class="form-group">
                                                                <label><input class="px" id="securityMeasures" type="checkbox" value="Special Security Measures" <?php echo $pre_data && $pre_data['security_measure'] != NULL ? 'checked' : '' ?>><span class="lbl">Special Security Measures</span></label>
                                                            </div>
                                                            <div id="securityMeasuresAttr" class="form-group">
                                                                <input class="form-control" placeholder="Specify Security Measures" type="text" name="new_security_measures" value="<?php echo $pre_data['security_measure'] ? $pre_data['security_measure'] : ''; ?>">
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    $('#securityMeasuresAttr').hide();

                                                                    $("#securityMeasures").on("click", function () {
                                                                        $('#securityMeasuresAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <div class="form-group">
                                                                <label><input class="px" id="servicesRequested" type="checkbox" value="Other Services Requested" <?php echo $pre_data && $pre_data['other_service_requested'] != NULL ? 'checked' : '' ?>><span class="lbl">Other Services Requested</span></label>
                                                            </div>
                                                            <div id="servicesRequestedAttr" class="form-group">
                                                                <input class="form-control" placeholder="Specify Services Requested" type="text" name="new_service_requested" value="<?php echo $pre_data['other_service_requested'] ? $pre_data['other_service_requested'] : ''; ?>">
                                                            </div>
                                                            <script>
                                                                init.push(function () {
                                                                    $('#servicesRequestedAttr').hide();

                                                                    $("#servicesRequested").on("click", function () {
                                                                        $('#servicesRequestedAttr').toggle();
                                                                    });
                                                                });
                                                            </script>
                                                            <div class="form-group">
                                                                <label>Note (If any)</label>
                                                                <textarea class="form-control" name="service_requested_note" rows="5" placeholder="Note"><?php echo $pre_data['service_requested_note'] ? $pre_data['service_requested_note'] : ''; ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
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
                                            <input id="Datefirstmeeting" type="text" class="form-control" name="first_meeting" value="<?php echo $pre_data['first_meeting'] && $pre_data['first_meeting'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['first_meeting'])) : ''; ?>">
                                        </div>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('Datefirstmeeting');
                                            });
                                        </script>
                                    </div>
                                    <?php
                                    $problem_identified = $problem_identified ? $problem_identified : array($problem_identified);
                                    ?> 
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
                                        <textarea class="form-control" name="problem_description" rows="5"><?php echo $pre_data['problem_description'] ? $pre_data['problem_description'] : ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Initial Plan</label>
                                        <textarea class="form-control" name="initial_plan" rows="5" placeholder=""><?php echo $pre_data['initial_plan'] ? $pre_data['initial_plan'] : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group Familymembers" style="display:none">
                                        <label>If yes, how many?</label>
                                        <input class="form-control" type="number" name="family_counseling" value="<?php echo $pre_data['family_counseling'] ? $pre_data['family_counseling'] : ''; ?>">
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
                                        <input class="form-control" placeholder="Please Specity" type="text" id="newPlaceSessionTypeText" name="new_session_place" value="<?php echo $pre_data['session_place'] ? $pre_data['session_place'] : ''; ?>">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#newPlaceSession").on("click", function () {
                                                $('#newPlaceSessionType').show();
                                            });

                                            $(".newPlaceSession").on("click", function () {
                                                $('#newPlaceSessionType').hide();
                                                $('#newPlaceSessionTypeText').val('');
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Number of Sessions (Estimate)</label>
                                        <input class="form-control" type="number" id="session_number" name="session_number" value="<?php echo $pre_data['session_number'] ? $pre_data['session_number'] : ''; ?>">
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
                                    <?php
                                    $reason_for_reffer = $reason_for_reffer ? $reason_for_reffer : array($reason_for_reffer);
                                    ?> 
                                    <div class="form-group ">
                                        <label>Reason for Referral</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Trauma Counselling" <?php
                                                    if (in_array('Trauma Counselling', $reason_for_reffer)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Trauma Counselling </span></label>
                                                <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Family Counseling" <?php
                                                    if (in_array('Family Counseling', $reason_for_reffer)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Family Counseling </span></label>
                                                <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Psychiatric Treatment" <?php
                                                    if (in_array('Psychiatric Treatment', $reason_for_reffer)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Psychiatric Treatment  </span></label>
                                                <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Medical Treatment" <?php
                                                    if (in_array('Medical Treatment', $reason_for_reffer)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Medical Treatment </span></label>
                                                <label><input class="px col-sm-12" type="checkbox" name="reason_for_reffer[]" id="newReasonReferral" <?php echo $pre_data && $pre_data['other_reason_for_reffer'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newReasonReferralTypes" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_reason_for_reffer" value="<?php echo $pre_data['other_reason_for_reffer'] ? $pre_data['other_reason_for_reffer'] : ''; ?>">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#newReasonReferral').is(':checked');

                                            if (isChecked == true) {
                                                $('#newReasonReferralTypes').show();
                                            }

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
                    <?php
                    $inkind_project = $inkind_project ? $inkind_project : array($inkind_project);
                    ?> 
                    <div class="tab-pane fade " id="EconomicReintegration">
                        <fieldset>
                            <legend>Section 4: Economic Reintegration Support</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <div class="input-group">
                                            <input id="EconomicReintegrationDate" type="text" class="form-control" name="economic_reintegration_date" value="<?php echo $pre_data['economic_reintegration_date'] && $pre_data['economic_reintegration_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['economic_reintegration_date'])) : ''; ?>">
                                        </div>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('EconomicReintegrationDate');
                                            });
                                        </script>
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">In-kind Support from Project</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12"><input class="px" id="inkindMicro" type="checkbox" name="inkind_project[]" value="Microbusiness" <?php
                                                        if (in_array('Microbusiness', $inkind_project)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Microbusiness</span></label>
                                                    <div id="inkindMicroAttr" class="form-group col-sm-12">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="inkind_project[]" value="Business grant from project" <?php
                                                                if (in_array('Business grant from project', $inkind_project)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Business grant from project</span></label>
                                                            <label><input class="px" type="checkbox" name="inkind_project[]" value="Enrolled in community enterprise" <?php
                                                                if (in_array('Enrolled in community enterprise', $inkind_project)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Enrolled in community enterprise</span></label>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        init.push(function () {
                                                            $('#inkindMicroAttr').hide();

                                                            var isChecked = $('#inkindMicro').is(':checked');

                                                            if (isChecked == true) {
                                                                $('#inkindMicroAttr').show();
                                                            }

                                                            $("#inkindMicro").on("click", function () {
                                                                $('#inkindMicroAttr').toggle();
                                                            });
                                                        });
                                                    </script>
                                                    <label class="col-sm-12"><input class="px" id="inkindMaterial" type="checkbox" name="inkind_project[]" value="Material Assistance" <?php
                                                        if (in_array('Want to leave home', $inkind_project)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Material Assistance</span></label>
                                                    <div id="inkindMaterialAttr" class="form-group col-sm-12">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="inkind_project[]" value="Business equipment/tools"
                                                                <?php
                                                                if (in_array('Business equipment/tools', $inkind_project)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Business equipment/tools</span></label>
                                                            <label><input class="px" type="checkbox" name="inkind_project[]" value="Lease land or pond for business"
                                                                <?php
                                                                if (in_array('Lease land or pond for business', $inkind_project)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Lease land or pond for business</span></label>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        init.push(function () {
                                                            $('#inkindMaterialAttr').hide();

                                                            var isChecked = $('#inkindMaterial').is(':checked');

                                                            if (isChecked == true) {
                                                                $('#inkindMaterialAttr').show();
                                                            }

                                                            $("#inkindMaterial").on("click", function () {
                                                                $('#inkindMaterialAttr').toggle();
                                                            });
                                                        });
                                                    </script>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Training (Financial Literacy Training)" <?php
                                                        if (in_array('Training (Financial Literacy Training)', $inkind_project)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Training (Financial Literacy Training)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Advance Training" <?php
                                                        if (in_array('Advance Training', $inkind_project)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Advance Training</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Safe Migration" <?php
                                                        if (in_array('Safe Migration', $inkind_project)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Safe Migration</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="inkind_project[]" id="newInkind" <?php echo $pre_data && $pre_data['other_inkind_project'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newInkindType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_inkind_project" value="<?php echo $pre_data['other_inkind_project'] ? $pre_data['other_inkind_project'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#newInkind').is(':checked');

                                                if (isChecked == true) {
                                                    $('#newInkindType').show();
                                                }

                                                $("#newInkind").on("click", function () {
                                                    $('#newInkindType').toggle();
                                                });
                                            });
                                        </script>
                                    </fieldset>
                                    <div class="form-group">
                                        <label>Training Certificate Received</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yes_certification_received" name="is_certification_received" value="yes" <?php echo $pre_data && $pre_data['is_certification_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="no_certification_received" name="is_certification_received" value="no" <?php echo $pre_data && $pre_data['is_certification_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group Training">
                                        <label>How has the training been used so far?</label>
                                        <textarea class="form-control" rows="2" name="training_used" placeholder=""><?php echo $pre_data['training_used'] ? $pre_data['training_used'] : ''; ?></textarea>
                                    </div>
                                    <div class="form-group Training">
                                        <label>Any other Comments</label>
                                        <textarea class="form-control" rows="2" name="economic_other_comments" placeholder="Any other Comments"><?php echo $pre_data['economic_other_comments'] ? $pre_data['economic_other_comments'] : ''; ?></textarea>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $('.Training').hide();
                                            var isChecked = $('#yes_certification_received').is(':checked');

                                            if (isChecked == true) {
                                                $('.Training').show();
                                            }

                                            $("#yes_certification_received").on("click", function () {
                                                $('.Training').show();
                                            });

                                            $("#no_certification_received").on("click", function () {
                                                $('.Training').hide();
                                            });
                                        });
                                    </script>
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
                                            var isChecked = $('#yesMicrobusiness').is(':checked');

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
                                            <input id="FamilyTrainingDATE" type="text" class="form-control" name="traning_entry_date" value="<?php echo $pre_data['traning_entry_date'] && $pre_data['traning_entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['traning_entry_date'])) : ''; ?>">
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
                                        <label>Status Of Training</label>
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
                                        <legend class="scheduler-border">Name of Trainings</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12">
                                                        <input class="px col-sm-12" type="checkbox" <?php echo $pre_data && $pre_data['financial_literacy_date'] != NULL ? 'checked' : '' ?> id="financialLiteracy">
                                                        <span class="lbl">Financial Literacy</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="financialLiteracyDate" style="display: none; margin-bottom: 1em;">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Financial Literacy Date</label>
                                                    <div class="input-group">
                                                        <input id="financialDate" type="text" class="form-control" name="financial_literacy_date" value="<?php echo $pre_data['financial_literacy_date'] && $pre_data['financial_literacy_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['financial_literacy_date'])) : ''; ?>">
                                                    </div>
                                                    <script type="text/javascript">
                                                        init.push(function () {
                                                            _datepicker('financialDate');
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#financialLiteracy').is(':checked');

                                                if (isChecked == true) {
                                                    $('#financialLiteracyDate').show();
                                                }

                                                $("#financialLiteracy").on("click", function () {
                                                    $('#financialLiteracyDate').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12">
                                                        <input class="px col-sm-12" type="checkbox" <?php echo $pre_data && $pre_data['business_development_date'] != NULL ? 'checked' : '' ?> id="businessDevelopment">
                                                        <span class="lbl">Business Development</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="businessDevelopmentDate" style="display: none; margin-bottom: 1em;">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Business Development Date</label>
                                                    <div class="input-group">
                                                        <input id="businessDate" type="text" class="form-control" name="business_development_date" value="<?php echo $pre_data['business_development_date'] && $pre_data['business_development_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['business_development_date'])) : ''; ?>">
                                                    </div>
                                                    <script type="text/javascript">
                                                        init.push(function () {
                                                            _datepicker('businessDate');
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#businessDevelopment').is(':checked');

                                                if (isChecked == true) {
                                                    $('#businessDevelopmentDate').show();
                                                }

                                                $("#businessDevelopment").on("click", function () {
                                                    $('#businessDevelopmentDate').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12">
                                                        <input class="px col-sm-12" type="checkbox" <?php echo $pre_data && $pre_data['product_development_date'] != NULL ? 'checked' : '' ?>  id="productDevelopment">
                                                        <span class="lbl">Product Development</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="productDevelopmentDate" style="display: none; margin-bottom: 1em;">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Product Development Date</label>
                                                    <div class="input-group">
                                                        <input id="productDate" type="text" class="form-control" name="product_development_date" value="<?php echo $pre_data['product_development_date'] && $pre_data['product_development_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['product_development_date'])) : ''; ?>">
                                                    </div>
                                                    <script type="text/javascript">
                                                        init.push(function () {
                                                            _datepicker('productDate');
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#productDevelopment').is(':checked');

                                                if (isChecked == true) {
                                                    $('#productDevelopmentDate').show();
                                                }

                                                $("#productDevelopment").on("click", function () {
                                                    $('#productDevelopmentDate').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12">
                                                        <input class="px col-sm-12" type="checkbox" <?php echo $pre_data && $pre_data['entrepreneur_training_date'] != NULL ? 'checked' : '' ?> id="entrepreneurTraining">
                                                        <span class="lbl">Entrepreneur Training</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="entrepreneurTrainingDate" style="display: none; margin-bottom: 1em;">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Entrepreneur Training Date</label>
                                                    <div class="input-group">
                                                        <input id="entrepreneur" type="text" class="form-control" name="entrepreneur_training_date" value="<?php echo $pre_data['entrepreneur_training_date'] && $pre_data['entrepreneur_training_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entrepreneur_training_date'])) : ''; ?>">
                                                    </div>
                                                    <script type="text/javascript">
                                                        init.push(function () {
                                                            _datepicker('entrepreneur');
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#entrepreneurTraining').is(':checked');

                                                if (isChecked == true) {
                                                    $('#entrepreneurTrainingDate').show();
                                                }

                                                $("#entrepreneurTraining").on("click", function () {
                                                    $('#entrepreneurTrainingDate').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12">
                                                        <input class="px col-sm-12" type="checkbox" <?php echo $pre_data && $pre_data['other_financial_training_name'] != NULL ? 'checked' : '' ?> id="otherFinancialTraining">
                                                        <span class="lbl">Other Training</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="otherFinancialTrainingAtr" style="display: none; margin-bottom: 1em;">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Training Name</label>
                                                    <input type="text" name="other_financial_training_name" value="<?php echo $pre_data && $pre_data['other_financial_training_name'] ?>" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Training Start Date</label>
                                                    <div class="input-group">
                                                        <input id="trainingDate" type="text" class="form-control" name="other_financial_training_date" value="<?php echo $pre_data['other_financial_training_date'] && $pre_data['other_financial_training_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['other_financial_training_date'])) : '' ?>">
                                                    </div>
                                                    <script type="text/javascript">
                                                        init.push(function () {
                                                            _datepicker('trainingDate');
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#otherFinancialTraining').is(':checked');

                                                if (isChecked == true) {
                                                    $('#otherFinancialTrainingAtr').show();
                                                }

                                                $("#otherFinancialTraining").on("click", function () {
                                                    $('#otherFinancialTrainingAtr').toggle();
                                                });
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
                                        <label>Date</label>
                                        <div class="input-group">
                                            <input id="EconomicReintegrationReferrals" type="text" class="form-control" name="economic_reintegration_referral_date" value="<?php echo $pre_data['economic_reintegration_referral_date'] && $pre_data['economic_reintegration_referral_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['economic_reintegration_referral_date'])) : ''; ?>">
                                        </div>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('EconomicReintegrationReferrals');
                                            });
                                        </script>
                                    </div>
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
                                    <?php
                                    $received_vocational = $received_vocational ? $received_vocational : array($received_vocational);
                                    ?> 
                                    <fieldset class="scheduler-border Referralstraining">
                                        <legend class="scheduler-border">Name of the Vocational Training Received (Referrals)</legend>
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
                                                    <label class="col-sm-4"><input class="px" type="checkbox" id="tradeRepair" name="received_vocational[]" value="Trade and repair" <?php
                                                        if (in_array('Trade and repair', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Trade and repair</span></label>
                                                    <div id="tradeRepairList" class="form-group col-sm-8">
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
                                                    <script>
                                                        init.push(function () {
                                                            $('#tradeRepairList').hide();
                                                            var isChecked = $('#tradeRepair').is(':checked');

                                                            if (isChecked == true) {
                                                                $('#tradeRepairList').show();
                                                            }

                                                            $("#tradeRepair").on("click", function () {
                                                                $('#tradeRepairList').toggle();
                                                            });
                                                        });
                                                    </script>
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
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Health and social work" <?php
                                                        if (in_array('Health and social work', $received_vocational)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Health and social work</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="received_vocational[]" id="newReferralstraining" <?php echo $pre_data && $pre_data['other_received_vocational'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newReferralstrainingType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_received_vocational" value="<?php echo $pre_data['other_received_vocational'] ? $pre_data['other_received_vocational'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#newReferralstraining').is(':checked');

                                                if (isChecked == true) {
                                                    $('#newReferralstrainingType').show();
                                                }

                                                $("#newReferralstraining").on("click", function () {
                                                    $('#newReferralstrainingType').toggle();
                                                });
                                            });
                                        </script>
                                    </fieldset>
                                    <?php
                                    $received_vocational_training = $received_vocational_training ? $received_vocational_training : array($received_vocational_training);
                                    ?> 
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Name of the Vocational Training Received</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Agriculture forestry, fishing and farming" <?php
                                                        if (in_array('Agriculture forestry, fishing and farming', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Agriculture forestry, fishing and farming</span></label>
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
                                                    <label class="col-sm-4"><input class="px" id="receivedTrade" type="checkbox" name="received_vocational_training[]" value="Trade and repair" <?php
                                                        if (in_array('Trade and repair', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Trade and repair</span></label>
                                                    <div id="receivedTradeList" class="form-group col-sm-8">
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
                                                    <script>
                                                        init.push(function () {
                                                            $('#receivedTradeList').hide();
                                                            var isChecked = $('#receivedTrade').is(':checked');

                                                            if (isChecked == true) {
                                                                $('#receivedTradeList').show();
                                                            }

                                                            $("#receivedTrade").on("click", function () {
                                                                $('#receivedTradeList').toggle();
                                                            });
                                                        });
                                                    </script>
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
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Health and social work" <?php
                                                        if (in_array('Health and social work', $received_vocational_training)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Health and social work</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="received_vocational_training[]" id="newVocational" <?php echo $pre_data && $pre_data['other_received_vocational_training'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newVocationalType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" value="<?php echo $pre_data['other_received_vocational_training'] ? $pre_data['other_received_vocational_training'] : ''; ?>" name="new_received_vocational_training" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#newVocational').is(':checked');

                                                if (isChecked == true) {
                                                    $('#newVocationalType').show();
                                                }

                                                $("#newVocational").on("click", function () {
                                                    $('#newVocationalType').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group">
                                            <label>Start Date of Training</label>
                                            <div class="input-group">
                                                <input id="StartDateTraining" type="text" class="form-control" name="training_start_date" value="<?php echo $pre_data['training_start_date'] && $pre_data['training_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['training_start_date'])) : ''; ?>">
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
                                                <input id="EndDateTraining" type="text" class="form-control" name="training_end_date" value="<?php echo $pre_data['training_end_date'] && $pre_data['training_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['training_end_date'])) : ''; ?>">
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('EndDateTraining');
                                            });
                                        </script>
                                    </fieldset>
                                    <div class="form-group">
                                        <label>Referrals done for economic services</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yes_economic_services" name="is_economic_services" value="yes" <?php echo $pre_data && $pre_data['is_economic_services'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="no_economic_services" name="is_economic_services" value="no" <?php echo $pre_data && $pre_data['is_economic_services'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $('.areaEconomicSupport').hide();
                                            var isChecked = $('#yes_economic_services').is(':checked');

                                            if (isChecked == true) {
                                                $('.areaEconomicSupport').show();
                                            }

                                            $("#yes_economic_services").on("click", function () {
                                                $('.areaEconomicSupport').show();
                                            });

                                            $("#no_economic_services").on("click", function () {
                                                $('.areaEconomicSupport').hide();
                                            });
                                        });
                                    </script>
                                    <?php
                                    $economic_support = $economic_support ? $economic_support : array($economic_support);
                                    ?> 
                                    <fieldset class="scheduler-border areaEconomicSupport">
                                        <legend class="scheduler-border">Types of Economic Support</legend>
                                        <div class="form-group">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12"><input class="px" id="microEco" type="checkbox" name="economic_support[]" value="Microbusiness" <?php
                                                        if (in_array('Microbusiness', $economic_support)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Microbusiness</span></label>
                                                    <div id="microEcoAttr" class="form-group col-sm-12">
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
                                                    <script>
                                                        init.push(function () {
                                                            $('#microEcoAttr').hide();

                                                            var isChecked = $('#microEco').is(':checked');

                                                            if (isChecked == true) {
                                                                $('#microEcoAttr').show();
                                                            }

                                                            $("#microEco").on("click", function () {
                                                                $('#microEcoAttr').toggle();
                                                            });
                                                        });
                                                    </script>
                                                    <label class="col-sm-12"><input class="px" id="finService" type="checkbox" name="economic_support[]" value="Financial Services" <?php
                                                        if (in_array('Financial Services', $economic_support)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Financial Services</span></label>
                                                    <div id="finServiceAttr" class="form-group col-sm-12">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="economic_support[]" value="Loan" <?php
                                                                if (in_array('Loan', $economic_support)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Loan</span></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Other Financial Service</label>
                                                            <input class="form-control" placeholder="Other financial service" type="text" name="economic_financial_service" value="<?php echo $pre_data['economic_financial_service'] ? $pre_data['economic_financial_service'] : ''; ?>">
                                                        </div>
                                                    </div>
                                                    <script>
                                                        init.push(function () {
                                                            $('#finServiceAttr').hide();

                                                            var isChecked = $('#finService').is(':checked');

                                                            if (isChecked == true) {
                                                                $('#finServiceAttr').show();
                                                            }

                                                            $("#finService").on("click", function () {
                                                                $('#finServiceAttr').toggle();
                                                            });
                                                        });
                                                    </script>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="economic_support[]" value="Job Placement" <?php
                                                        if (in_array('Job Placement', $economic_support)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Job Placement</span></label>
                                                    <label class="col-sm-4"><input class="px" id="materialAssist" type="checkbox" name="economic_support[]" value="Material Assistance" <?php
                                                        if (in_array('Material Assistance', $economic_support)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Material Assistance</span></label>
                                                    <div id="materialAssistAttr" class="form-group col-sm-12">
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
                                                    <script>
                                                        init.push(function () {
                                                            $('#materialAssistAttr').hide();

                                                            var isChecked = $('#materialAssist').is(':checked');

                                                            if (isChecked == true) {
                                                                $('#materialAssistAttr').show();
                                                            }

                                                            $("#materialAssist").on("click", function () {
                                                                $('#materialAssistAttr').toggle();
                                                            });
                                                        });
                                                    </script>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="economic_support[]" value="Advance Training" <?php
                                                        if (in_array('Advance Training', $economic_support)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Advance Training</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="economic_support[]" id="newEconomicSupport" <?php echo $pre_data && $pre_data['other_economic_support'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="EconomicSupportType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_economic_support" value="<?php echo $pre_data['other_economic_support'] ? $pre_data['other_economic_support'] : ''; ?>">
                                        </div>
                                    </fieldset>
                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#newEconomicSupport').is(':checked');

                                            if (isChecked == true) {
                                                $('#EconomicSupportType').show();
                                            }

                                            $("#newEconomicSupport").on("click", function () {
                                                $('#EconomicSupportType').toggle();
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Required Assistance Received</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" name="is_assistance_received" value="yes" <?php echo $pre_data && $pre_data['is_assistance_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" name="is_assistance_received" value="no" <?php echo $pre_data && $pre_data['is_assistance_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Date Wise Support</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12">
                                                        <input class="px col-sm-12" type="checkbox" <?php echo $pre_data && $pre_data['job_placement_date'] != NULL ? 'checked' : '' ?> id="jobPlacement">
                                                        <span class="lbl">Job Placement</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="jobPlacementDate" style="display: none; margin-bottom: 1em;">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Job Placement Date</label>
                                                    <div class="input-group">
                                                        <input id="placementDate" type="text" class="form-control" name="job_placement_date" value="<?php echo $pre_data['job_placement_date'] && $pre_data['job_placement_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['job_placement_date'])) : ''; ?>">
                                                    </div>
                                                    <script type="text/javascript">
                                                        init.push(function () {
                                                            _datepicker('placementDate');
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#jobPlacement').is(':checked');

                                                if (isChecked == true) {
                                                    $('#jobPlacementDate').show();
                                                }

                                                $("#jobPlacement").on("click", function () {
                                                    $('#jobPlacementDate').toggle();
                                                });
                                            });
                                        </script>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12">
                                                        <input class="px col-sm-12" type="checkbox" <?php echo $pre_data && $pre_data['financial_services_date'] != NULL ? 'checked' : '' ?> id="financialServices">
                                                        <span class="lbl">Financial Services</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="financialServicesDate" style="display: none; margin-bottom: 1em;">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Financial Services Date</label>
                                                    <div class="input-group">
                                                        <input id="servicesFinancialDate" type="text" class="form-control" name="financial_services_date" value="<?php echo $pre_data['financial_services_date'] && $pre_data['financial_services_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['financial_services_date'])) : ''; ?>">
                                                    </div>
                                                    <script type="text/javascript">
                                                        init.push(function () {
                                                            _datepicker('servicesFinancialDate');
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#financialServices').is(':checked');

                                                if (isChecked == true) {
                                                    $('#financialServicesDate').show();
                                                }

                                                $("#financialServices").on("click", function () {
                                                    $('#financialServicesDate').toggle();
                                                });
                                            });
                                        </script>
                                    </fieldset>
                                    <div class="form-group">
                                        <label>Referred To</label>
                                        <input class="form-control" type="text" name="refferd_to" placeholder="Name" value="<?php echo $pre_data['refferd_to'] ? $pre_data['refferd_to'] : ''; ?>"><br />
                                        <input class="form-control" type="text" name="refferd_address" placeholder="Address" value="<?php echo $pre_data['refferd_address'] ? $pre_data['refferd_address'] : ''; ?>"><br />
                                        <label>Date of Training</label>
                                        <div class="input-group">
                                            <input id="DatofTraining" type="text" class="form-control" name="trianing_date" value="<?php echo $pre_data['trianing_date'] && $pre_data['trianing_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['trianing_date'])) : ''; ?>">
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
                                                <label><input class="px" type="radio" name="status_traning" value="yes" <?php echo $pre_data && $pre_data['status_traning'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Completed</span></label>
                                                <label><input class="px" type="radio" name="status_traning" value="no" <?php echo $pre_data && $pre_data['status_traning'] == 'no' ? 'checked' : '' ?>><span class="lbl">Not Completed</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>How has the assistance been utilized?</label>
                                        <textarea class="form-control" name="assistance_utilized" rows="2" placeholder=""><?php echo $pre_data['assistance_utilized'] ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Any other Comments</label>
                                        <textarea class="form-control" name="economic_referrals_other_comments" rows="2" placeholder=""><?php echo $pre_data['economic_referrals_other_comments'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="SocialReintegrationSupport">
                        <fieldset>
                            <legend>Section 5: Social Reintegration Support</legend>
                            <div class="row">
                                <?php
                                $support_referred = $support_referred ? $support_referred : array($support_referred);
                                ?> 
                                <div class="col-sm-12">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Types of Support Referred for</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12"><input class="px" id="reintegrationEconomicReferred" type="checkbox" name="support_referred[]" value="Social Protection Schemes(Place to access to public services & Social Protection)" <?php
                                                        if (in_array('Social Protection Schemes(Place to access to public services & Social Protection)', $support_referred)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Social Protection Schemes(Place to access to public services & Social Protection)</span></label>
                                                    <div id="reintegrationEconomicReferredAttr" class="form-group col-sm-12">
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
                                                    <script>
                                                        init.push(function () {
                                                            $('#reintegrationEconomicReferredAttr').hide();

                                                            var isChecked = $('#reintegrationEconomicReferred').is(':checked');

                                                            if (isChecked == true) {
                                                                $('#reintegrationEconomicReferredAttr').show();
                                                            }

                                                            $("#reintegrationEconomicReferred").on("click", function () {
                                                                $('#reintegrationEconomicReferredAttr').toggle();
                                                            });
                                                        });
                                                    </script>
                                                    <label class="col-sm-12"><input class="px" id="socialMedicalSupportReferred" type="checkbox" name="support_referred[]" value="Medical Support" <?php
                                                        if (in_array('Medical Support', $support_referred)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Medical Support</span></label>
                                                    <div id="socialMedicalSupportReferredAttr" class="form-group col-sm-12">
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
                                                    <script>
                                                        init.push(function () {
                                                            $('#socialMedicalSupportReferredAttr').hide();

                                                            var isChecked = $('#socialMedicalSupportReferred').is(':checked');

                                                            if (isChecked == true) {
                                                                $('#socialMedicalSupportReferredAttr').show();
                                                            }

                                                            $("#socialMedicalSupportReferred").on("click", function () {
                                                                $('#socialMedicalSupportReferredAttr').toggle();
                                                            });
                                                        });
                                                    </script>
                                                    <label class="col-sm-12"><input class="px" id="socialEducationReferred" type="checkbox" name="support_referred[]" value="Education" <?php
                                                        if (in_array('Education', $support_referred)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Education</span></label>
                                                    <div id="socialEducationReferredAttr" class="form-group col-sm-12">
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
                                                    <script>
                                                        init.push(function () {
                                                            $('#socialEducationReferredAttr').hide();

                                                            var isChecked = $('#socialEducationReferred').is(':checked');

                                                            if (isChecked == true) {
                                                                $('#socialEducationReferredAttr').show();
                                                            }

                                                            $("#socialEducationReferred").on("click", function () {
                                                                $('#socialEducationReferredAttr').toggle();
                                                            });
                                                        });
                                                    </script>
                                                    <label class="col-sm-12"><input class="px" id="socialHousingReferred" type="checkbox" name="support_referred[]" value="Housing" <?php
                                                        if (in_array('Housing', $support_referred)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Housing</span></label>
                                                    <div id="socialHousingReferredAttr" class="form-group col-sm-12">
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
                                                    <script>
                                                        init.push(function () {
                                                            $('#socialHousingReferredAttr').hide();

                                                            var isChecked = $('#socialHousingReferred').is(':checked');

                                                            if (isChecked == true) {
                                                                $('#socialEducationReferredAttr').show();
                                                            }

                                                            $("#socialHousingReferred").on("click", function () {
                                                                $('#socialHousingReferredAttr').toggle();
                                                            });
                                                        });
                                                    </script>
                                                    <label class="col-sm-12"><input class="px" id="socialLegalReferred" type="checkbox" name="support_referred[]" value="Legal Services" <?php
                                                        if (in_array('Legal Services', $support_referred)) {
                                                            echo 'checked';
                                                        }
                                                        ?>><span class="lbl">Legal Services</span></label>
                                                    <div id="socialLegalReferredAttr" class="form-group col-sm-12">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Legal Aid" <?php
                                                                if (in_array('Legal Aid"', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Legal Aid</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Claiming Compensation" <?php
                                                                if (in_array('Claiming Compensation', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Claiming Compensation</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Assistance in resolving family dispute" <?php
                                                                if (in_array('Assistance in resolving family dispute', $support_referred)) {
                                                                    echo 'checked';
                                                                }
                                                                ?>><span class="lbl">Assistance in resolving family dispute</span></label>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        init.push(function () {
                                                            $('#socialLegalReferredAttr').hide();

                                                            var isChecked = $('#socialLegalReferred').is(':checked');

                                                            if (isChecked == true) {
                                                                $('#socialLegalReferredAttr').show();
                                                            }

                                                            $("#socialLegalReferred").on("click", function () {
                                                                $('#socialLegalReferredAttr').toggle();
                                                            });
                                                        });
                                                    </script>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" id="supportreferred" <?php echo $pre_data['other_support_referred'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" id="TypesupportreferredType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_supportreferred" value="<?php echo $pre_data['other_support_referred'] ? $pre_data['other_support_referred'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                var isChecked = $('#supportreferred').is(':checked');

                                                if (isChecked == true) {
                                                    $('#TypesupportreferredType').show();
                                                }

                                                $("#supportreferred").on("click", function () {
                                                    $('#TypesupportreferredType').toggle();
                                                });
                                            });
                                        </script>
                                    </fieldset>	
                                </div>
                                <div class="col-sm-12">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Types of Social Reintegration Support</legend>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <?php
                                                    $reintegration_economic = $reintegration_economic ? $reintegration_economic : array($reintegration_economic);
                                                    ?> 
                                                    <div class="options_holder radio">
                                                        <label class="col-sm-12"><input class="px" id="reintegrationEconomic" type="checkbox" name="reintegration_economic[]" value="Social Protection Schemes(Place to access to public services & Social Protection)" <?php
                                                            if (in_array('Social Protection Schemes(Place to access to public services & Social Protection)', $reintegration_economic)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Social Protection Schemes(Place to access to public services & Social Protection)</span></label>
                                                        <div id="reintegrationEconomicAttr" class="form-group col-sm-12">
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
                                                        <script>
                                                            init.push(function () {
                                                                $('#reintegrationEconomicAttr').hide();

                                                                var isChecked = $('#reintegrationEconomic').is(':checked');

                                                                if (isChecked == true) {
                                                                    $('#reintegrationEconomicAttr').show();
                                                                }

                                                                $("#reintegrationEconomic").on("click", function () {
                                                                    $('#reintegrationEconomicAttr').toggle();
                                                                });
                                                            });
                                                        </script>
                                                        <label class="col-sm-12"><input class="px" id="socialMedicalSupport" type="checkbox" name="reintegration_economic[]" value="Medical Support" <?php
                                                            if (in_array('Medical Support', $reintegration_economic)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Medical Support</span></label>
                                                        <div id="socialMedicalSupportAttr" class="form-group col-sm-8">
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
                                                        <script>
                                                            init.push(function () {
                                                                $('#socialMedicalSupportAttr').hide();

                                                                var isChecked = $('#socialMedicalSupport').is(':checked');

                                                                if (isChecked == true) {
                                                                    $('#socialMedicalSupportAttr').show();
                                                                }

                                                                $("#socialMedicalSupport").on("click", function () {
                                                                    $('#socialMedicalSupportAttr').toggle();
                                                                });
                                                            });
                                                        </script>
                                                        <label class="col-sm-12"><input class="px" id="socialEducation" type="checkbox" name="reintegration_economic[]" value="Education" <?php
                                                            if (in_array('Education', $reintegration_economic)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Education</span></label>
                                                        <div id="socialEducationAttr" class="form-group col-sm-12">
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
                                                        <script>
                                                            init.push(function () {
                                                                $('#socialEducationAttr').hide();

                                                                var isChecked = $('#socialEducation').is(':checked');

                                                                if (isChecked == true) {
                                                                    $('#socialEducationAttr').show();
                                                                }

                                                                $("#socialEducation").on("click", function () {
                                                                    $('#socialEducationAttr').toggle();
                                                                });
                                                            });
                                                        </script>
                                                        <label class="col-sm-12"><input class="px" id="socialHousing" type="checkbox" name="reintegration_economic[]" value="Housing" <?php
                                                            if (in_array('Housing', $reintegration_economic)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Housing</span></label>
                                                        <div id="socialHousingAttr" class="form-group col-sm-12">
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
                                                        <script>
                                                            init.push(function () {
                                                                $('#socialHousingAttr').hide();

                                                                var isChecked = $('#socialHousing').is(':checked');

                                                                if (isChecked == true) {
                                                                    $('#socialEducationAttr').show();
                                                                }

                                                                $("#socialHousing").on("click", function () {
                                                                    $('#socialHousingAttr').toggle();
                                                                });
                                                            });
                                                        </script>
                                                        <label class="col-sm-12"><input class="px" id="socialLegal" type="checkbox" name="reintegration_economic[]" value="Legal Services" <?php
                                                            if (in_array('Legal Services', $reintegration_economic)) {
                                                                echo 'checked';
                                                            }
                                                            ?>><span class="lbl">Legal Services</span></label>
                                                        <div id="socialLegalAttr" class="form-group col-sm-12">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Legal Aid" <?php
                                                                    if (in_array('Legal Aid"', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Legal Aid</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Claiming Compensation" <?php
                                                                    if (in_array('Claiming Compensation', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Claiming Compensation</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Assistance in resolving family dispute" <?php
                                                                    if (in_array('Assistance in resolving family dispute', $reintegration_economic)) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>><span class="lbl">Assistance in resolving family dispute</span></label>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            init.push(function () {
                                                                $('#socialLegalAttr').hide();

                                                                var isChecked = $('#socialLegal').is(':checked');

                                                                if (isChecked == true) {
                                                                    $('#socialLegalAttr').show();
                                                                }

                                                                $("#socialLegal").on("click", function () {
                                                                    $('#socialLegalAttr').toggle();
                                                                });
                                                            });
                                                        </script>
                                                        <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="reintegration_economic[]" id="TypesofEconomic" <?php echo $pre_data['other_reintegration_economic'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12" id="TypesofEconomicType" style="display: none; margin-bottom: 1em;">
                                                <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_reintegration_economic" value="<?php echo $pre_data['other_reintegration_economic'] ? $pre_data['other_reintegration_economic'] : ''; ?>">
                                            </div>
                                            <script>
                                                init.push(function () {
                                                    var isChecked = $('#TypesofEconomic').is(':checked');

                                                    if (isChecked == true) {
                                                        $('#TypesofEconomicType').show();
                                                    }

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
                                                    <input id="ServicesReceivedSocial" type="text" class="form-control" name="soical_date" value="<?php echo $pre_data['soical_date'] && $pre_data['soical_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['soical_date'])) : ''; ?>">
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
                                                    <input id="ServicesReceivedMedical" type="text" class="form-control" name="medical_date" value="<?php echo $pre_data['medical_date'] && $pre_data['medical_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['medical_date'])) : ''; ?>">
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
                                                    <input id="ServicesReceivedEducation" type="text" class="form-control" name="date_education" value="<?php echo $pre_data['date_education'] && $pre_data['date_education'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['date_education'])) : ''; ?>">
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
                                                    <input id="ServicesReceivedHousing" type="text" class="form-control" name="date_housing" value="<?php echo $pre_data['date_housing'] && $pre_data['date_housing'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['date_housing'])) : ''; ?>">
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
                                                    <input id="ServicesReceivedOthers" type="text" class="form-control" name="date_legal" value="<?php echo $pre_data['date_legal'] && $pre_data['date_legal'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['date_legal'])) : ''; ?>">
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
                            </div>
                        </fieldset>
                    </div>
                    <?php
                    $reason_dropping = $reason_dropping ? $reason_dropping : array($reason_dropping);
                    ?> 
                    <div class="tab-pane fade " id="ReviewFollowUp">
                        <fieldset>
                            <legend>Section 6: Review and Follow-Up</legend>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($reviews['data'] as $value):
                                        ?>
                                        <tr>
                                            <td><?php echo date('d-m-Y', strtotime($value['update_date'] ? $value['update_date'] : $value['entry_date'])) ?></td>
                                            <td>
                                                <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_review&edit=' . $value['pk_followup_id']) ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach
                                    ?>
                                </tbody>
                            </table>
                            <a href="<?php echo url('dev_customer_management/manage_cases?action=add_edit_review&customer_id=' . $edit) ?>" target="_blank" class="btn btn-success btn-sm" style="margin-bottom: 1%">Add New Review and Follow-Up</a>
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