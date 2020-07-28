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
                            <!--                            dev_immediate_supports-->
                            ALTER TABLE `dev_immediate_supports` ADD `case_manage_id` BIGINT(20) NULL AFTER `immediate_support`;

                            <legend>Section 1: Support Provided</legend>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Select Case Manager (*)</label>
                                        <select class="form-control" name="case_manage_id">
                                            <option value="">Select One</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
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
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value="Meet and greet at port of entry"><span class="lbl">Meet and greet at port of entry </span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value=""><span class="lbl">Information provision   </span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value=""><span class="lbl">Pocket money</span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value=""><span class="lbl">Shelter and accommodation </span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value=""><span class="lbl">Want to leave home  </span></label>
                                                    </div>
                                                    <div class="col-sm-6">   
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value=""><span class="lbl">Onward transportation </span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value=""><span class="lbl">Health assessment and health assistance</span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value=""><span class="lbl">Food and nutrition </span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value=""><span class="lbl">Non-Food Items (hygiene kits, etc.)</span></label>
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value=""><span class="lbl">Psychosocial Conseling</span></label>
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
                            <!--                            dev_reintegration_plan-->
                            <legend>Section 2: Preferred Services and Reintegration Plan</legend>
                            <!-- ALTER TABLE `dev_reintegration_plan` ADD `service_requested` VARCHAR(100) NULL AFTER `reintegration_plan`; -->

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
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="cholarship/Stipend"><span class="lbl">cholarship/Stipend</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="new_service_requested" value="Others"><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Financial Service"><span class="lbl">Financial Services</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Loan"><span class="lbl">Loan</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="new_service_requested" value="Others"><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Housing"><span class="lbl">Housing</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Allocation for khas land"><span class="lbl">Allocation for khas land</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Support for land allocation"><span class="lbl">Support for land allocation</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="new_service_requested" value="Others"><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="service_requested[]" value="Job Placement"><span class="lbl">Job Placement</span></label>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Legal Services"><span class="lbl">Legal Services</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Legal Aid"><span class="lbl">Legal Aid</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Claiming Compensation"><span class="lbl">Claiming Compensation</span></label>
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Assistance in resolving family dispute"><span class="lbl">Assistance in resolving family dispute</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="service_requested[]" value="Others"><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
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
                                                                <label style="float:left"><input class="px" type="checkbox" name="new_service_requested" value="Others"><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="service_requested[]" value="Microbusiness"><span class="lbl">Microbusiness</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="service_requested[]" value="Business grant"><span class="lbl">Business grant</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="new_service_requested" value="Others"><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
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
                                                            <label><input class="px" type="checkbox" name="service_requested[]" value="Social Protection Schemes"><span class="lbl">Social Protection Schemes</span></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <input class="form-control" placeholder="Specify the services" type="text" name="" value="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label><input class="px" type="checkbox" name="service_requested[]" value="Special Security Measures"><span class="lbl">Special Security Measures</span></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <input class="form-control" placeholder="Specify the services" type="text" name="" value="">
                                                        </div>
                                                        <div class="form-group">
                                                            <textarea class="form-control" rows="5" placeholder="Note"></textarea>
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
                            <!--  dev_psycho_supports -->
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
                                    <!-- ALTER TABLE `dev_psycho_supports` ADD `issue_discussed` VARCHAR(50) NULL AFTER `is_home_visit`; -->
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
                                        <textarea class="form-control" rows="5" placeholder=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Initial Plan</label>
                                        <textarea class="form-control" rows="5" placeholder=""></textarea>
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
                                                <label><input class="px PlaceSession" type="radio" name="session_place[]" value=""><span class="lbl">Home </span></label>
                                                <label><input class="px PlaceSession" type="radio" name="session_place[]" value=""><span class="lbl">Jashore office</span></label>
                                                <label><input class="px" type="radio" name="session_place[]" id="newPlaceSession"><span class="lbl">Others</span></label>
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
                                    <!-- ALTER TABLE `dev_psycho_supports` ADD `session_number` VARCHAR(100) NULL AFTER `family_counseling`; -->
                                    <div class="form-group">
                                        <label>Number of Sessions (Estimate)</label>
                                        <input class="form-control" type="text" id="session_number"  name="session_number">
                                    </div>
                                    <div class="form-group">
                                        <label>Duration of Session</label>
                                        <input class="form-control" type="text" id="session_duration"  name="session_duration">
                                    </div>
                                    <div class="form-group">
                                        <label>Other Requirements</label>
                                        <input class="form-control" type="text" id="other_requirements"  name="other_requirements">
                                    </div>
                                    <div class="form-group">
                                        <label>Referrals</label>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Referred to" type="text" id="reffer_to"  name="reffer_to">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Address of organization/individual" type="text" id="referr_address"  name="referr_address">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Phone Number" type="text" id=""  name="">
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
                            <!--                             dev_psycho_family_counselling-->
                            <legend>Section 3.1: Family Counseling Session</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Family Counseling Date</label>

                                        <div class="input-group">
                                            <input id="FamilyCounselingDate" type="text" class="form-control" name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : date('d-m-Y'); ?>">
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
                                            <input type="text" name="entry_time" class="form-control" id="bs-timepicker-component"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
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
                                        <input class="form-control" type="text" id="session_place"  name="session_place">
                                    </div>
                                    <div class="form-group">
                                        <label>No of Family Members Counseled</label>
                                        <!-- ALTER TABLE `dev_psycho_family_counselling` ADD `members_counseled` VARCHAR(100) NULL AFTER `counseling_time`; -->
                                        <input class="form-control" type="text" id="members_counseled"  name="members_counseled">
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
                            <!--                            dev_psycho_sessions-->
                            <legend>Section 3.3: Psychosocial Reintegration Session Activities</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Time (*)</label>
                                        <div class="input-group date">
                                            <input type="text" name="entry_time" class="form-control" id="ReintegrationTime"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
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
                                            <input id="ReintegrationDate" type="text" class="form-control" name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : date('d-m-Y'); ?>">
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
                            <!--                            dev_psycho_completions-->
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
                                        <!-- ALTER TABLE `dev_psycho_completions` ADD `review_session` VARCHAR(100) NULL AFTER `dropout_reason`; -->
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
                                        <!-- ALTER TABLE `dev_psycho_completions` ADD `required_session` ENUM('yes','no') NULL AFTER `counsellor_comments`; -->
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
                            <!--                            dev_psycho_followups-->
                            <legend>Section 3.5: Psychosocial Reintegration (Followup)</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Time (*)</label>
                                        <div class="input-group date">
                                            <input type="text" name="entry_time" class="form-control" id="FollowupTime"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
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
                                            <input id="FollowupTimeDate" type="text" class="form-control" name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : date('d-m-Y'); ?>">
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        init.push(function () {
                                            _datepicker('FollowupTimeDate');
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Followup Comment (*)</label>
                                        <textarea class="form-control" name="followup_comments" rows="3" placeholder="Followup Comment "></textarea>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade " id="EconomicReintegration">
                        <fieldset>
                            <!--                            dev_economic_supports-->
                            <legend>Section 4: Economic Reintegration Support</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">In-kind Support from Project</legend>
                                        <!-- ALTER TABLE `dev_economic_supports` ADD `inkind_project` VARCHAR(100) NULL AFTER `fk_project_id`; -->
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
                                                <label><input class="px" type="radio" id="is_certification_received" name="is_certification_received[]" value="yes" <?php echo $pre_data && $pre_data['is_certification_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="is_certification_received" name="is_certification_received[]" value="no" <?php echo $pre_data && $pre_data['is_certification_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
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
                                            <!-- ALTER TABLE `dev_economic_supports` ADD `microbusiness_established` ENUM('yes','no') NULL AFTER `support_from_whom`; -->
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesMicrobusiness" name="microbusiness_established[]" value="yes" <?php echo $pre_data && $pre_data['microbusiness_established'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noMicrobusiness" name="microbusiness_established[]" value="no" <?php echo $pre_data && $pre_data['microbusiness_established'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group Microbusiness" style="display:none">

                                        <input class="form-control" type="text" name="microbusiness_established[]" placeholder="Month Of Business Inauguration" value="<?php echo $pre_data['microbusiness_established'] ? $pre_data['microbusiness_established'] : ''; ?>"><BR />
                                        <input class="form-control" type="text" name="microbusiness_established[]" placeholder="Year of Business Inauguration" value="<?php echo $pre_data['microbusiness_established'] ? $pre_data['microbusiness_established'] : ''; ?>">
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
                                        <!-- ALTER TABLE `dev_economic_supports` ADD `family_training` ENUM('yes','no') NULL AFTER `microbusiness_established`; -->
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesFamilyTraining" name="family_training[]" value="yes" <?php echo $pre_data && $pre_data['family_training'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noFamilyTraining" name="family_training[]" value="no" <?php echo $pre_data && $pre_data['family_training'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group FamilyTraining" style="display:none">
                                       	<label>Date of Training</label>
                                        <div class="input-group">
                                            <input id="FamilyTrainingDATE" type="text" class="form-control" name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : date('d-m-Y'); ?>">
                                        </div></BR>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('FamilyTrainingDATE');
                                            });
                                        </script>
                                        <!-- ALTER TABLE `dev_economic_supports` ADD `place_traning` VARCHAR(100) NULL AFTER `family_training`; -->
                                        <input class="form-control" type="text" name="place_traning" placeholder="Place of Training" value="<?php echo $pre_data['place_traning'] ? $pre_data['place_traning'] : ''; ?>"><BR />
                                        <!-- ALTER TABLE `dev_economic_supports` ADD `duration_traning` VARCHAR(100) NULL AFTER `place_traning`; -->
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
                                                <label><input class="px" type="radio" id="" name="training_status[]" value="yes" <?php echo $pre_data && $pre_data['training_status'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="" name="training_status[]" value="no" <?php echo $pre_data && $pre_data['training_status'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
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
                                    </fieldset >
                                    <div class="form-group">
                                        <label>Start Date of Training</label>
                                        <div class="input-group">
                                            <input id="StartDateTraining" type="text" class="form-control" name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : date('d-m-Y'); ?>">
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
                                            <input id="EndDateTraining" type="text" class="form-control" name="end_date" value="<?php echo $pre_data['end_date'] && $pre_data['end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['end_date'])) : date('d-m-Y'); ?>">
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        init.push(function () {
                                            _datepicker('EndDateTraining');
                                        });
                                    </script>


                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade" id="EconomicReferrals">
                        <fieldset>
                            <!--           CREATE                 dev_economic_reintegration_referrals-->

                            <!-- CREATE TABLE `dev_economic_reintegration_referrals` (
                              `is_vocational_training` enum('yes','no') DEFAULT NULL,
                              `received_vocational` varchar(100) DEFAULT NULL,
                              `is_certificate_received` enum('yes','no') DEFAULT NULL,
                              `used_far` varchar(100) DEFAULT NULL,
                              `other_comments` text DEFAULT NULL,
                              `is_economic_services` enum('yes','no') DEFAULT NULL,
                              `economic_support` varchar(100) DEFAULT NULL,
                              `is_assistance_received` enum('yes','no') DEFAULT NULL,
                              `refferd_to` varchar(100) DEFAULT NULL,
                              `status_traning` varchar(100) DEFAULT NULL,
                              `assistance_utilized` text DEFAULT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            COMMIT; -->
                            <legend>Section 4.1: Economic Reintegration Referrals</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Referrals done for Vocational Training</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesReferralstraining" name="is_vocational_training[]" value="yes" <?php echo $pre_data && $pre_data['is_vocational_training'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noReferralstraining" name="is_vocational_training[]" value="no" <?php echo $pre_data && $pre_data['is_vocational_training'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
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

                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox"  name="received_vocational[]" id="newReferralstraining"><span class="lbl">Others</span></label>
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
                                                <label><input class="px" type="radio" id="is_economic_services" name="is_economic_services[]" value="yes" <?php echo $pre_data && $pre_data['is_economic_services'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="is_economic_services" name="is_economic_services[]" value="no" <?php echo $pre_data && $pre_data['is_economic_services'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
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
                                                            <label style="float:left"><input class="px" type="checkbox" name="economic_support[]" value="Others"><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

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

                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" id="EconomicSupport"><span class="lbl">Others</span></label>
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
                                                <label><input class="px" type="radio" id="yesLiteracyTraining" name="is_assistance_received[]" value="yes" <?php echo $pre_data && $pre_data['is_assistance_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noLiteracyTraining" name="is_assistance_received[]" value="no" <?php echo $pre_data && $pre_data['is_assistance_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Referred To</label>
                                        <input class="form-control" type="text" name="refferd_to" placeholder="Name" value="<?php echo $pre_data['refferd_to'] ? $pre_data['refferd_to'] : ''; ?>"><br />
                                        <!-- ALTER TABLE `dev_economic_reintegration_referrals` ADD `refferd_address` TEXT NULL AFTER `refferd_to`; -->
                                        <input class="form-control" type="text" name="refferd_address" placeholder="Address" value="<?php echo $pre_data['refferd_address'] ? $pre_data['refferd_address'] : ''; ?>"><br />
                                        <label>Date of Training</label>
                                        <!-- ALTER TABLE `dev_economic_reintegration_referrals` ADD `trianing_date` DATE NULL AFTER `refferd_to`; -->
                                        <div class="input-group">
                                            <input id="DatofTraining" type="text" class="form-control" name="trianing_date" value="<?php echo $pre_data['trianing_date'] && $pre_data['trianing_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['trianing_date'])) : date('d-m-Y'); ?>">
                                        </div><br />
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('DatofTraining');
                                            });
                                        </script>
                                        <!-- ALTER TABLE `dev_economic_reintegration_referrals` ADD `place_of_training` VARCHAR(100) NULL AFTER `trianing_date`; -->
                                        <input class="form-control" type="text" name="place_of_training" placeholder="Place of Training" value="<?php echo $pre_data['place_of_training'] ? $pre_data['place_of_training'] : ''; ?>"><br />
                                        <!-- ALTER TABLE `dev_economic_reintegration_referrals` ADD `duration_training` VARCHAR(100) NULL AFTER `place_of_training`; -->
                                        <input class="form-control" type="text" name="duration_training" placeholder="Duration of Training" value="<?php echo $pre_data['duration_training'] ? $pre_data['duration_training'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Status of the Training</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="status_traning" name="status_traning[]" value="yes" <?php echo $pre_data && $pre_data['status_traning'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Completed</span></label>
                                                <label><input class="px" type="radio" id="status_traning" name="status_traning[]" value="no" <?php echo $pre_data && $pre_data['status_traning'] == 'no' ? 'checked' : '' ?>><span class="lbl">Not Completed</span></label>
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
                            <!--                            dev_social_supports-->
                            <legend>Section 5: Social Reintegration Support</legend>
                            <div class="row">
                                <div class="col-sm-12">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Types of Economic Support</legend>
                                        <!-- ALTER TABLE `dev_social_supports` ADD `reintegration_economic` VARCHAR(100) NULL AFTER `end_date`; -->
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
                                                                <label style="float:left"><input class="px" type="checkbox" name="new_reintegration_economic" value=""><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
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
                                                                <label style="float:left"><input class="px" type="checkbox" name="new_reintegration_economic" value=""><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

                                                            </div>
                                                        </div>

                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Housing</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Allocation of ‘Khas land’</span></label>
                                                                <label><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Support for housing loan</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="new_reintegration_economic" value=""><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

                                                            </div>
                                                        </div>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Legal Services</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Legal Aid</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="reintegration_economic[]" value=""><span class="lbl">Legal Arbitration</span></label>
                                                        <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" name="new_reintegration_economic id="TypesofEconomic"><span class="lbl">Others</span></label>
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
                                                    <!-- ALTER TABLE `dev_social_supports` ADD `soical_date` DATE NULL AFTER `reintegration_economic`;    -->
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
                                                <!-- ALTER TABLE `dev_social_supports` ADD `medical_date` DATE NULL AFTER `soical_date`; -->
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
                                                <!-- ALTER TABLE `dev_social_supports` ADD `date_education` DATE NULL AFTER `medical_date`; -->
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
                                                <!-- ALTER TABLE `dev_social_supports` ADD `date_housing` DATE NULL AFTER `date_education`; -->
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
                                                <!-- ALTER TABLE `dev_social_supports` ADD `date_legal` DATE NULL AFTER `date_housing`; -->
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
                                        <!-- ALTER TABLE `dev_social_supports` ADD `attended_ipt` ENUM('yes','no') NOT NULL AFTER `date_legal`; -->
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesLiteracyTraining" name="attended_ipt[]" value="yes" <?php echo $pre_data && $pre_data['attended_ipt'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noLiteracyTraining" name="attended_ipt[]" value="no" <?php echo $pre_data && $pre_data['attended_ipt'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="learn_show" rows="3" placeholder="Lessons learnt from IPT show"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Attended community video shows?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesLiteracyTraining" name="is_per_community_video" value="yes" <?php echo $pre_data && $pre_data['is_per_community_video'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noLiteracyTraining" name="is_per_community_video" value="no" <?php echo $pre_data && $pre_data['is_per_community_video'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="learn_video" rows="3" placeholder="Lessons learnt from video show"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Types of Support Referred for</legend>
                                        <!-- ALTER TABLE `dev_social_supports` ADD `support_referred` VARCHAR(100) NULL AFTER `attended_ipt`; -->
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
                                                            <label style="float:left"><input class="px" type="checkbox" name="new_support_referred" value="Others"><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
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
                                                            <label style="float:left"><input class="px" type="checkbox" name="new_support_referred" value="Others"><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

                                                        </div>
                                                    </div>

                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="support_referred[]" value="Housing"><span class="lbl">Housing</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Allocation of ‘Khas land’"><span class="lbl">Allocation of ‘Khas land’</span></label>
                                                            <label><input class="px" type="checkbox" name="support_referred[]" value="Support for housing loan"><span class="lbl">Support for housing loan</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="new_support_referred" value="Others"><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

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
                            <!--                            dev_followups-->
                            <legend>Section 6: Review and Follow-Up</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Case dropped out from the project? (*)</label>
                                        <!-- ALTER TABLE `dev_followups` ADD `casedropped` ENUM('yes','no') NULL AFTER `create_by`; -->
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesCasedropped" name="casedropped[]" value="yes" <?php echo $pre_data && $pre_data['casedropped'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noCasedropped" name="casedropped[]" value="no" <?php echo $pre_data && $pre_data['casedropped'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
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
                                        <!-- ALTER TABLE `dev_followups` ADD `reason_dropping` VARCHAR(100) NULL AFTER `casedropped`; -->
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
                                        <!-- ALTER TABLE `dev_followups` ADD `confirm_services` VARCHAR(100) NULL AFTER `reason_dropping`; -->
                                        <div class="form-group">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value=">Child Care"><span class="lbl">Child Care</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Education"><span class="lbl">Education</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Admission"><span class="lbl">Admission</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Scholarship/Stipend"><span class="lbl">Scholarship/Stipend</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="new_confirm_services" value="Others"><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Financial Services"><span class="lbl">Financial Services</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Loan"><span class="lbl">Loan</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="new_confirm_services" value="Others"><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Housing"><span class="lbl">Housing</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Allocation for khas land"><span class="lbl">Allocation for khas land</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Support for land allocation"><span class="lbl">Support for land allocation</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="new_confirm_services" value="Others"><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Job Placement"><span class="lbl">Job Placement</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Legal Services"><span class="lbl">Legal Services</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Legal Aid"><span class="lbl">Legal Aid</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Legal Arbitration"><span class="lbl">Legal Arbitration</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="new_confirm_services" value=""><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
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
                                                            <label style="float:left"><input class="px" type="checkbox" name="new_confirm_services" value=""><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

                                                        </div>
                                                    </div>

                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Medical Support"><span class="lbl">Medical Support</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Medical treatment"><span class="lbl">Medical treatment</span></label>
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Psychiatric treatment"><span class="lbl">Psychiatric treatment</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="new_confirm_services" value=""><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="confirm_services[]" value="Microbusiness"><span class="lbl">Microbusiness</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="confirm_services[]" value="Business grant"><span class="lbl">Business grant</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="new_confirm_services" value="Others"><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

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
                                                        <label><input class="px" type="checkbox" name="confirm_services[]" value="Social Protection Schemes"><span class="lbl">Social Protection Schemes</span></label>
                                                    </div>
                                                    <div class="form-group col-sm-12">
                                                        <input class="form-control" type="text" placeholder="Specify the services" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>">
                                                    </div>
                                                    <div class="form-group col-sm-12">
                                                        <label><input class="px" type="checkbox" name="confirm_services[]" value="Special Security Measures<"><span class="lbl">Special Security Measures</span></label>
                                                    </div>
                                                    <div class="form-group col-sm-12">
                                                        <input class="form-control" type="text" placeholder="Specify the services" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>							
                                <div class="col-sm-6">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Comment on Situation of Case</legend>
                                        <div class="form-group">
                                            <!-- ALTER TABLE `dev_followups` ADD `comment_psychosocial` TEXT NULL AFTER `confirm_services`; -->
                                            <textarea class="form-control" rows="3" placeholder="Comment on psychosocial reintegration"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <!-- ALTER TABLE `dev_followups` ADD `comment_economic` TEXT NULL AFTER `comment_psychosocial`; -->
                                            <textarea class="form-control" rows="3" placeholder="Comment on economic reintegration"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <!-- ALTER TABLE `dev_followups` ADD `comment_social` TEXT NULL AFTER `comment_economic`; -->
                                            <textarea class="form-control" rows="3" placeholder="Comment on social reintegration"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <!-- ALTER TABLE `dev_followups` ADD `complete_income` TEXT NULL AFTER `comment_social`; -->
                                            <textarea class="form-control" rows="3" placeholder="Complete income tracking information"></textarea>
                                        </div>
                                    </fieldset>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Status of Case after Receiving the Services</legend>
                                        <!-- ALTER TABLE `dev_followups` ADD `monthly_income` VARCHAR(100) NULL AFTER `complete_income`, ADD `challenges` VARCHAR(100) NULL AFTER `monthly_income`, ADD `actions_taken` VARCHAR(100) NULL AFTER `challenges`, ADD `remark_participant` VARCHAR(100) NULL AFTER `actions_taken`, ADD `comment_brac` VARCHAR(100) NULL AFTER `remark_participant`, ADD `remark_district` VARCHAR(100) NULL AFTER `comment_brac`; -->
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