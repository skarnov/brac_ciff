<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_case', 'edit_case')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_cases(array('id' => $edit, 'single' => true));
    $immediate_support = explode(',', $pre_data['immediate_support']);
    
    
//    echo '<pre>';
//    print_r($immediate_support);
//    exit();
    

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
            header('location: ' . url('admin/dev_customer_management/manage_cases?action=add_edit_case&edit=' . $edit));
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
        <h4 class="text-primary">Case ID: <?php echo $pre_data['pk_immediate_support_id '] ?></h4>
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
                                <div class="col-sm-4">
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
                                </div>
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
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="service_requested[]" value="Child Care"><span class="lbl">Child Care</span></label>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Education"><span class="lbl">Education</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Admission"><span class="lbl">Admission</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Scholarship/Stipend"><span class="lbl">Scholarship/Stipend</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Financial Service"><span class="lbl">Financial Services</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Loan"><span class="lbl">Loan</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Housing"><span class="lbl">Housing</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Allocation for khas land"><span class="lbl">Allocation for khas land</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Support for land allocation"><span class="lbl">Support for land allocation</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="service_requested[]" value="Job Placement"><span class="lbl">Job Placement</span></label>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Legal Services"><span class="lbl">Legal Services</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Legal Aid"><span class="lbl">Legal Aid</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Claiming Compensation"><span class="lbl">Claiming Compensation</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Assistance in resolving family dispute"><span class="lbl">Assistance in resolving family dispute</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Training"><span class="lbl">Training</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Financial Literacy Training"><span class="lbl">Financial Literacy Training</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Advance training from project"><span class="lbl">Advance training from project</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Advance training through referrals"><span class="lbl">Advance training through referrals</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Material Assistance"><span class="lbl">Material Assistance</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Business equipment/tools"><span class="lbl">Business equipment/tools</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Allocation of land or pond for business"><span class="lbl">Allocation of land or pond for business</span></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">   
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Medical Support"><span class="lbl">Medical Support</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value=">Medical treatment"><span class="lbl">Medical treatment</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Psychiatric treatment"><span class="lbl">Psychiatric treatment</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Microbusiness"><span class="lbl">Microbusiness</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Business grant"><span class="lbl">Business grant</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Psychosocial Support"><span class="lbl">Psychosocial Support</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Individual Counselling"><span class="lbl">Individual Counselling</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Family counselling"><span class="lbl">Family counselling</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Trauma Counseling"><span class="lbl">Trauma Counseling</span></label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><input class="px" type="checkbox" value="Social Protection Schemes"><span class="lbl">Social Protection Schemes</span></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <input class="form-control" placeholder="Specify the services" type="text" name="new_social_protection" value="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label><input class="px" type="checkbox" value="Special Security Measures"><span class="lbl">Special Security Measures</span></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <input class="form-control" placeholder="Specify the services" type="text" name="new_security_measures" value="">
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
                                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_service_requested" value="">
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
                                                            <textarea class="form-control" name="service_requested_note" rows="5" placeholder="Note"></textarea>
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
                                                <label><input class="px" type="checkbox" name="issue_discussed[]" value="Referral Services"><span class="lbl">Referral Services</span></label>
                                                <label><input class="px" type="checkbox" name="issue_discussed[]" value="Information"><span class="lbl">Information</span></label>
                                                <label><input class="px" type="checkbox"  name="issue_discussed[]" id="newIssues"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newIssuesType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_issue_discussed" value="">
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
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Anxiety"><span class="lbl">Anxiety</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Depression"><span class="lbl">Depression</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Suicidal Ideation/Thought"><span class="lbl">Suicidal Ideation/Thought</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Sleep Problems"><span class="lbl">Sleep Problems</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Phobia/Fear"><span class="lbl">Phobia/Fear</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Acute Stress"><span class="lbl">Acute Stress</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Anger problems"><span class="lbl">Anger problems</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Addiction issues (substance abuse of any kinds)"><span class="lbl">Addiction issues (substance abuse of any kinds)</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Schizophrenia"><span class="lbl">Schizophrenia</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Bipolar Mood Disorder"><span class="lbl">Bipolar Mood Disorder</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Repetitive thought or Repetitive Behavior (OCD)"><span class="lbl">Repetitive thought or Repetitive Behavior (OCD)</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Conversion Reactions"><span class="lbl">Conversion Reactions</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Problems in Socialization"><span class="lbl">Problems in Socialization</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value="Family Problems"><span class="lbl">Family Problems</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Description of the problem</label>
                                        <textarea class="form-control" name="problem_description" rows="5" placeholder=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Initial Plan</label>
                                        <textarea class="form-control" name="initial_plan" rows="5" placeholder=""></textarea>
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
                                                <label><input class="px newPlaceSession" type="radio" name="session_place" value="home"><span class="lbl">Home</span></label>
                                                <label><input class="px newPlaceSession" type="radio" name="session_place" value="jashore_office"><span class="lbl">Jashore office</span></label>
                                                <label><input class="px" type="radio" name="session_place" id="newPlaceSession"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newPlaceSessionType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_session_place" value="">
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
                                        <input class="form-control" type="text" id="session_number" name="session_number">
                                    </div>
                                    <div class="form-group">
                                        <label>Duration of Session</label>
                                        <input class="form-control" type="text" id="session_duration" name="session_duration">
                                    </div>
                                    <div class="form-group">
                                        <label>Other Requirements</label>
                                        <input class="form-control" type="text" id="other_requirements" name="other_requirements">
                                    </div>
                                    <div class="form-group">
                                        <label>Referrals</label>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Referred to" type="text" name="reffer_to">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Address of organization/individual" type="text" name="referr_address">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Phone Number" type="text" name="contact_number">
                                    </div>
                                    <div class="form-group ">
                                        <label>Reason for Referral</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Trauma Counselling "><span class="lbl">Trauma Counselling </span></label>
                                                <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Family Counseling"><span class="lbl">Family Counseling </span></label>
                                                <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Psychiatric Treatment"><span class="lbl">Psychiatric Treatment  </span></label>
                                                <label><input class="px" type="checkbox" name="reason_for_reffer[]" value="Medical Treatment"><span class="lbl">Medical Treatment </span></label>
                                                <label><input class="px col-sm-12" type="checkbox" name="reason_for_reffer[]" id="newReasonReferral"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newReasonReferralTypes" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_reason_for_reffer" value="">
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
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Family Counseling Date</label>
                                        <div class="input-group">
                                            <input id="FamilyCounselingDate" type="text" class="form-control" name="family_entry_date" value="<?php echo $pre_data['family_entry_date'] && $pre_data['family_entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['family_entry_date'])) : date('d-m-Y'); ?>">
                                        </div>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('FamilyCounselingDate');
                                            });
                                        </script>
                                    </div>
                                    <div class="form-group ">
                                        <label>Family Counseling Time</label>
                                        <div class="input-group date">
                                            <input type="text" name="family_entry_time" class="form-control" id="bs-timepicker-component"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                        </div>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var options2 = {
                                                minuteStep: 1,
                                                showSeconds: true,
                                                showMeridian: false,
                                                showInputs: false,
                                                orientation: $('body').hasClass('right-to-left') ? {x: 'right', y: 'auto'} : {x: 'auto', y: 'auto'}
                                            }
                                            $('#bs-timepicker-component').timepicker(options2);
                                        });
                                    </script>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Place of Family Counseling</label>
                                        <input class="form-control" type="text" id="session_place" name="session_place">
                                    </div>
                                    <div class="form-group">
                                        <label>No of Family Members Counseled</label>
                                        <input class="form-control" type="text" id="members_counseled" name="members_counseled">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Comments/Remarks</label>
                                        <textarea class="form-control" name="session_comments" rows="5" placeholder="Comments/Remarks"></textarea>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="ReintegrationSession">
                        <fieldset>
                            <legend>Section 3.3: Psychosocial Reintegration Session Activities</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Time (*)</label>
                                        <div class="input-group date">
                                            <input type="text" name="session_entry_time" class="form-control" id="ReintegrationTime"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                        </div>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var options2 = {
                                                minuteStep: 1,
                                                showSeconds: true,
                                                showMeridian: false,
                                                showInputs: false,
                                                orientation: $('body').hasClass('right-to-left') ? {x: 'right', y: 'auto'} : {x: 'auto', y: 'auto'}
                                            }
                                            $('#ReintegrationTime').timepicker(options2);
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Date (*)</label>
                                        <div class="input-group">
                                            <input id="ReintegrationDate" type="text" class="form-control" name="session_entry_date" value="<?php echo $pre_data['session_entry_date'] && $pre_data['session_entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['session_entry_date'])) : date('d-m-Y'); ?>">
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        init.push(function () {
                                            _datepicker('ReintegrationDate');
                                        });
                                    </script>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Description of Activities</label>
                                        <textarea class="form-control" rows="3"  name="activities_description" placeholder="Description of Activities"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Comments</label>
                                        <textarea class="form-control" rows="3" name="session_comments" placeholder="Comments"></textarea>
                                    </div>
                                    <label>Next date for Session</label>
                                    <div class="input-group">
                                        <input id="NextdateforSession" type="text" class="form-control" name="next_date" value="<?php echo $pre_data['next_date'] && $pre_data['next_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['next_date'])) : date('d-m-Y'); ?>">
                                    </div>
                                    <script type="text/javascript">
                                        init.push(function () {
                                            _datepicker('NextdateforSession');
                                        });
                                    </script>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="SessionCompletion">
                        <fieldset>
                            <legend>Section 3.4: Session Completion Status</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Completed Counselling Session</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="isCompletedYes" name="is_completed" value="yes" <?php echo $pre_data && $pre_data['is_completed'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="isCompletedNo" name="is_completed" value="no" <?php echo $pre_data && $pre_data['is_completed'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Reason for drop out from the Counseling Session</label>
                                        <input class="form-control" type="text" id="dropout_reason"  name="dropout_reason">
                                    </div>
                                    <div class="form-group">
                                        <label>Review of Counselling Session</label>
                                        <input class="form-control" type="text" id="review_session"  name="review_session">
                                    </div>
                                    <div class="form-group">
                                        <label>Comments of the Client</label>
                                        <textarea class="form-control" name="client_comments" rows="2" placeholder="Comments of the Client"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Counsellor’s Comment</label>
                                        <textarea class="form-control" rows="2" name="counsellor_comments" placeholder="Counsellor’s Comment"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Final Evaluation</label>
                                        <input class="form-control" type="text" id="final_evaluation"  name="final_evaluation">
                                    </div>
                                    <div class="form-group">
                                        <label>Required Session After Completion (If Any)</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="RequiredSessionYes" name="required_session" value="yes" <?php echo $pre_data && $pre_data['required_session'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="RequiredSessionNo" name="required_session" value="no" <?php echo $pre_data && $pre_data['required_session'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="ReintegrationFollowup">
                        <fieldset>
                            <legend>Section 3.5: Psychosocial Reintegration (Followup)</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Time (*)</label>
                                        <div class="input-group date">
                                            <input type="text" name="followup_entry_time" class="form-control" id="FollowupTime"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                        </div>
                                    </div>
                                    <script>
                                        init.push(function () {
                                            var options2 = {
                                                minuteStep: 1,
                                                showSeconds: true,
                                                showMeridian: false,
                                                showInputs: false,
                                                orientation: $('body').hasClass('right-to-left') ? {x: 'right', y: 'auto'} : {x: 'auto', y: 'auto'}
                                            }
                                            $('#FollowupTime').timepicker(options2);
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Date (*)</label>
                                        <div class="input-group">
                                            <input id="FollowupTimeDate" name="followup_entry_date" type="text" class="form-control" value="<?php echo $pre_data['followup_entry_date'] && $pre_data['followup_entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['followup_entry_date'])) : date('d-m-Y'); ?>">
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        init.push(function () {
                                            _datepicker('FollowupTimeDate');
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Followup Comment (*)</label>
                                        <textarea class="form-control" name="followup_comments" rows="3" placeholder=""></textarea>
                                    </div>
                                </div>
                            </div>
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
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="inkind_project[]" value="Microbusiness"><span class="lbl">Microbusiness</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="inkind_project[]" value="Business grant from project"><span class="lbl">Business grant from project</span></label>
                                                            <label><input class="px" type="checkbox" name="inkind_project[]" value="Enrolled in community enterprise"><span class="lbl">Enrolled in community enterprise</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="inkind_project[]" value="Material Assistance"><span class="lbl">Material Assistance</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="inkind_project[]" value="Business equipment/tools"><span class="lbl">Business equipment/tools</span></label>
                                                            <label><input class="px" type="checkbox" name="inkind_project[]" value="Lease land or pond for business"><span class="lbl">Lease land or pond for business</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Training (Financial Literacy Training)"><span class="lbl">Training (Financial Literacy Training)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Advance Training"><span class="lbl">Advance Training</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="inkind_project[]" value="Safe Migration"><span class="lbl">Safe Migration</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="inkind_project[]" id="newInkind"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newInkindType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_inkind_project" value="">
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
                                        <textarea class="form-control" rows="2" name="training_used" placeholder=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Any other Comments</label>
                                        <textarea class="form-control" rows="2"  name="other_comments" placeholder="Any other Comments"></textarea>
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
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Agriculture forestry, fishing and farming"><span class="lbl">Agriculture forestry, fishing and farming</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Mining and quarrying"><span class="lbl">Mining and quarrying</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Manufacturing"><span class="lbl">Manufacturing</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Energies supply"><span class="lbl">Energies supply</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Water supply and waste management"><span class="lbl">Water supply and waste management</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Construction"><span class="lbl">Construction</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Electrical and housing wiring"><span class="lbl">Electrical and housing wiring</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Masonry"><span class="lbl">Masonry</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="received_vocational_training[]" value="Trade and repair"><span class="lbl">Trade and repair</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="received_vocational_training[]" value="Carpentry"><span class="lbl">Carpentry </span></label>
                                                            <label><input class="px" type="checkbox" name="received_vocational_training[]" value="Electronics and Maintenance"><span class="lbl">Electronics and Maintenance</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Transportation and storage"><span class="lbl">Transportation and storage</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Hospitality"><span class="lbl">Hospitality</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="IT and communication"><span class="lbl">IT and communication</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Computer classes"><span class="lbl">Computer classes</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Financial and insurance activities"><span class="lbl">Financial and insurance activities</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Real estate"><span class="lbl">Real estate</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Professional scientific and technical activities"><span class="lbl">Professional scientific and technical activities</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Administrative and support services (incl. cleaning and maintenance)"><span class="lbl">Administrative and support services (incl. cleaning and maintenance)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Public administration and defense"><span class="lbl">Public administration and defense</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Education (Teaching)"><span class="lbl">Education (Teaching)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Arts and entertainment"><span class="lbl">Arts and entertainment</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational_training[]" value="Domestic work"><span class="lbl">Domestic work</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="received_vocational_training[]" id="newVocational"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newVocationalType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_received_vocational_training" value="">
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
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Agriculture forestry, fishing and farming"><span class="lbl">Agriculture forestry, fishing and farming</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Mining and quarrying"><span class="lbl">Mining and quarrying</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Manufacturing"><span class="lbl">Manufacturing</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Energies supply"><span class="lbl">Energies supply</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Water supply and waste management"><span class="lbl">Water supply and waste management</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Construction"><span class="lbl">Construction</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Electrical and housing wiring"><span class="lbl">Electrical and housing wiring</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Masonry"><span class="lbl">Masonry</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="received_vocational[]" value="Trade and repair"><span class="lbl">Trade and repair</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="received_vocational[]" value="Carpentry"><span class="lbl">Carpentry </span></label>
                                                            <label><input class="px" type="checkbox" name="received_vocational[]" value="Electronics and Maintenance"><span class="lbl">Electronics and Maintenance</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Transportation and storage"><span class="lbl">Transportation and storage</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Hospitality"><span class="lbl">Hospitality</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="IT and communication"><span class="lbl">IT and communication</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Computer classes"><span class="lbl">Computer classes</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Financial and insurance activities"><span class="lbl">Financial and insurance activities</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Real estate"><span class="lbl">Real estate</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Professional scientific and technical activities"><span class="lbl">Professional scientific and technical activities</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Administrative and support services (incl. cleaning and maintenance)"><span class="lbl">Administrative and support services (incl. cleaning and maintenance)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Public administration and defense"><span class="lbl">Public administration and defense</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Education (Teaching)"><span class="lbl">Education (Teaching)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Arts and entertainment"><span class="lbl">Arts and entertainment</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="received_vocational[]" value="Domestic work"><span class="lbl">Domestic work</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="received_vocational[]" id="newReferralstraining"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newReferralstrainingType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_received_vocational" value="">
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
                                                <label><input class="px" type="radio" id="is_certificate_received" name="is_certificate_received" value="yes" <?php echo $pre_data && $pre_data['is_certificate_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="is_certificate_received" name="is_certificate_received" value="no" <?php echo $pre_data && $pre_data['is_certificate_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>How has the training been used so far?</label>
                                        <textarea class="form-control" name="used_far" rows="2" placeholder=""></textarea>
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
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="economic_support[]" value="Microbusiness"><span class="lbl">Microbusiness</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="economic_support[]" value="Business grant from project"><span class="lbl">Business grant from project</span></label>
                                                            <label><input class="px" type="checkbox" name="economic_support[]" value="Enrolled in community enterprise"><span class="lbl">Enrolled in community enterprise</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="economic_support[]" value="Financial Services"><span class="lbl">Financial Services</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="economic_support[]" value="Loan"><span class="lbl">Loan</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="economic_support[]" value="Job Placement"><span class="lbl">Job Placement</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="economic_support[]" value="Material Assistance"><span class="lbl">Material Assistance</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="economic_support[]" value="Business equipment/tools"><span class="lbl">Business equipment/tools</span></label>
                                                            <label><input class="px" type="checkbox" name="economic_support[]" value="Lease land or pond for business"><span class="lbl">Lease land or pond for business</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="economic_support[]" value="Advance Training"><span class="lbl">Advance Training</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="economic_support[]"  id="EconomicSupport"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="EconomicSupportType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_economic_support" value="">
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
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="reintegration_economic[]" value="Social Protection Schemes(Place to access to public services & Social Protection)"><span class="lbl">Social Protection Schemes(Place to access to public services & Social Protection)</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Union Parished"><span class="lbl">Union Parished</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Upazila Parished"><span class="lbl">Upazila Parished</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="District/Upazila Social Welfare office"><span class="lbl">District/Upazila Social Welfare office</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="District/Upazila Youth Development office"><span class="lbl">District/Upazila Youth Development office</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="District/ Upazila Women Affairs Department"><span class="lbl">District/ Upazila Women Affairs Department</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value="Private/ NGO"><span class="lbl">Private/ NGO</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Medical Support</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Project</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Government health facility</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Private/ NGO operated health service centre</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Education</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Admission</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Stipend/ Scholarship</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Housing</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Allocation of ‘Khas land’</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Support for housing loan</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Legal Services</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Legal Aid</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Legal Arbitration</span></label>
                                                        <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="reintegration_economic[]" id="TypesofEconomic"><span class="lbl">Others</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12" id="TypesofEconomicType" style="display: none; margin-bottom: 1em;">
                                                <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_reintegration_economic" value="">
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
                                        <textarea class="form-control" name="learn_show" rows="3" placeholder="Lessons learnt from IPT show"></textarea>
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
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="support_referred[]" value="Social Protection Schemes(Place to access to public services & Social Protection)"><span class="lbl">Social Protection Schemes(Place to access to public services & Social Protection)</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Union Parished"><span class="lbl">Union Parished</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Upazila Parished"><span class="lbl">Upazila Parished</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="District/Upazila Social Welfare office"><span class="lbl">District/Upazila Social Welfare office</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="District/Upazila Youth Development office"><span class="lbl">District/Upazila Youth Development office</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="District/ Upazila Women Affairs Department"><span class="lbl">District/ Upazila Women Affairs Department</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Private/ NGO"><span class="lbl">Private/ NGO</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="support_referred[]" value="Medical Support"><span class="lbl">Medical Support</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Project"><span class="lbl">Project</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Government health facility<"><span class="lbl">Government health facility</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Private/ NGO operated health service centre"><span class="lbl">Private/ NGO operated health service centre</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="support_referred[]" value="Education"><span class="lbl">Education</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Admission"><span class="lbl">Admission</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Stipend/ Scholarship"><span class="lbl">Stipend/ Scholarship</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="support_referred[]" value="Housing"><span class="lbl">Housing</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Allocation of ‘Khas land’"><span class="lbl">Allocation of ‘Khas land’</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Support for housing loan"><span class="lbl">Support for housing loan</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="support_referred[]" value="Legal Services"><span class="lbl">Legal Services</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="support_referred[]" value="Legal Aid"><span class="lbl">Legal Aid</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="support_referred[]" value="Legal Arbitration"><span class="lbl">Legal Arbitration</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" id="supportreferred"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" id="TypesupportreferredType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_supportreferred" value="">
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
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="reason_dropping[]" value="Lack of interest"><span class="lbl">Lack of interest</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="reason_dropping[]" value="Critical illness"><span class="lbl">Critical illness</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="reason_dropping[]" value="Family issues"><span class="lbl">Family issues</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="reason_dropping[]" value="Have other issues to attend to"><span class="lbl">Have other issues to attend to</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="reason_dropping[]" value="Re-migrated"><span class="lbl">Re-migrated</span></label>
                                                    <label class="col-sm-12"><input  class="px col-sm-12" type="checkbox" id="ReasonDropping"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="ReasonDroppingType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_reason_dropping" value="">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $("#ReasonDropping").on("click", function () {
                                                    $('#ReasonDroppingType').toggle();
                                                });
                                            });
                                        </script>
                                    </fieldset >
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Confirmed Services Received after 3 Months</legend>
                                        <div class="form-group">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value=">Child Care"><span class="lbl">Child Care</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Education"><span class="lbl">Education</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Admission"><span class="lbl">Admission</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Scholarship/Stipend"><span class="lbl">Scholarship/Stipend</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Financial Services"><span class="lbl">Financial Services</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Loan"><span class="lbl">Loan</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Housing"><span class="lbl">Housing</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Allocation for khas land"><span class="lbl">Allocation for khas land</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Support for land allocation"><span class="lbl">Support for land allocation</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Job Placement"><span class="lbl">Job Placement</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Legal Services"><span class="lbl">Legal Services</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Legal Aid"><span class="lbl">Legal Aid</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Legal Arbitration"><span class="lbl">Legal Arbitration</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Job Placement"><span class="lbl">Job Placement</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Training"><span class="lbl">Training</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Financial Literacy Training"><span class="lbl">Financial Literacy Training</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Advance training from project"><span class="lbl">Advance training from project</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Advance training through referrals"><span class="lbl">Advance training through referrals</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Material Assistance"><span class="lbl">Material Assistance</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Business equipment/tools"><span class="lbl">Business equipment/tools</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Allocation of land or pond for business"><span class="lbl">Allocation of land or pond for business</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Remigration"><span class="lbl">Remigration</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Direct Assistance from project"><span class="lbl">Direct Assistance from project</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Referrals support from project"><span class="lbl">Referrals support from project</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Medical Support"><span class="lbl">Medical Support</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Medical treatment"><span class="lbl">Medical treatment</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Psychiatric treatment"><span class="lbl">Psychiatric treatment</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Microbusiness"><span class="lbl">Microbusiness</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Business grant"><span class="lbl">Business grant</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Psychosocial Support"><span class="lbl">Psychosocial Support</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Individual Counselling"><span class="lbl">Individual Counselling</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Family Counselling"><span class="lbl">Family Counselling</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Trauma Counseling"><span class="lbl">Trauma Counseling</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-sm-12">
                                                        <label><input class="px" type="checkbox" id="YesSpecifySevervice" value="Social Protection Schemes"><span class="lbl">Social Protection Schemes</span></label>
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
                                                        <input class="form-control" type="text" name="special_security_measures" placeholder="Specify the services" name="" value="">
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
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Comment on Situation of Case</legend>
                                        <div class="form-group">
                                            <textarea class="form-control" name="comment_psychosocial" rows="3" placeholder="Comment on psychosocial reintegration"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" name="comment_economic" rows="3" placeholder="Comment on economic reintegration"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" name="comment_social" rows="3" placeholder="Comment on social reintegration"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" name="complete_income" rows="3" placeholder="Complete income tracking information"></textarea>
                                        </div>
                                    </fieldset>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Status of Case after Receiving the Services</legend>
                                        <div class="form-group">
                                            <input class="form-control" name="monthly_income" placeholder="Monthly income (BDT)" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="challenges" placeholder="Challenges" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="actions_taken" placeholder="Actions taken" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="remark_participant" placeholder="Remark of the participant (if any)" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="comment_brac" placeholder="Comment of BRAC Officer responsible for participant" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="remark_district" placeholder="Remark of District Manager" type="text" name="" value="">
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