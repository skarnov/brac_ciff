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
                            <legend>Section 1: Support Provided</legend>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Select Case Manager (*)</label>
                                        <select class="form-control" name="passport">
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
                                                        <label><input class="px" type="checkbox" name="immediate_support[]" value="meet"><span class="lbl">Meet and greet at port of entry </span></label>
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
                            <div class="row">
                                <div class="col-sm-12">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Type of Services Requested</legend>
                                        <div class="form-group">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <div class="col-sm-6">   
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="transport_modes[]" value=""><span class="lbl">Child Care</span></label>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Education</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Admission</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">cholarship/Stipend</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Financial Services</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Loan</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Housing</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Allocation for khas land</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Support for land allocation</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="transport_modes[]" value=""><span class="lbl">Job Placement</span></label>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Legal Services</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Legal Aid</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Claiming Compensation</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Assistance in resolving family dispute</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Training</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Financial Literacy Training</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Advance training from project</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Advance training through referrals</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Material Assistance</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Business equipment/tools</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Allocation of land or pond for business</span></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">   
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Medical Support</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Medical treatment</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Psychiatric treatment</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Microbusiness</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Business grant</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Psychosocial Support</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Individual Counselling</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Family counselling</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Trauma Counseling</span></label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><input class="px" type="checkbox" name="transport_modes[]" value=""><span class="lbl">Social Protection Schemes</span></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <input class="form-control" placeholder="Specify the services" type="text" name="" value="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label><input class="px" type="checkbox" name="transport_modes[]" value=""><span class="lbl">Special Security Measures</span></label>
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
                                                <label><input class="px" type="radio" id="iga_skillYes" name="iga_skill" value="yes" <?php echo $pre_data && $pre_data['iga_skill'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="iga_skillNo" name="iga_skill" value="no" <?php echo $pre_data && $pre_data['iga_skill'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Issues Discussed (*)</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">

                                                <label><input class="px" type="checkbox" name="transport_modes[]" value=""><span class="lbl">Referral Services</span></label>
                                                <label><input class="px" type="checkbox" name="transport_modes[]" value=""><span class="lbl">Information</span></label>


                                                <label><input class="px" type="checkbox" id="newIssues"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newIssuesType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_transport" value="">
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

                                                <label><input class="px" type="checkbox" name="problem_identified[]" value=""><span class="lbl">Anxiety</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value=""><span class="lbl">Depression</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value=""><span class="lbl">Suicidal Ideation/Thought</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value=""><span class="lbl">Sleep Problems</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value=""><span class="lbl">Phobia/Fear</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value=""><span class="lbl">Acute Stress</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value=""><span class="lbl">Anger problems</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value=""><span class="lbl">Addiction issues (substance abuse of any kinds)</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value=""><span class="lbl">Schizophrenia</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value=""><span class="lbl">Bipolar Mood Disorder</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value=""><span class="lbl">Repetitive thought or Repetitive Behavior (OCD)</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value=""><span class="lbl">Conversion Reactions</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value=""><span class="lbl">Problems in Socialization</span></label>
                                                <label><input class="px" type="checkbox" name="problem_identified[]" value=""><span class="lbl">Family Problems</span></label>
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
                                                <label><input class="px" type="radio" id="yesFamilymembers" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noFamilymembers" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group Familymembers" style="display:none">
                                        <label>If yes, how many?</label>
                                        <input class="form-control" type="text" name="organization_name" value="<?php echo $pre_data['disability_name'] ? $pre_data['disability_name'] : ''; ?>">
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
                                                <label><input class="px PlaceSession" type="radio" name="session_place" value=""><span class="lbl">Home </span></label>
                                                <label><input class="px PlaceSession" type="radio" name="session_place" value=""><span class="lbl">Jashore office</span></label>
                                                <label><input class="px" type="radio" name="session_place" id="newPlaceSession"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newPlaceSessionType" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control" placeholder="Please Specity" type="text" name="new_place" value="">
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
                                        <input class="form-control" type="text" id=""  name="">
                                    </div>
                                    <div class="form-group">
                                        <label>Duration of Session</label>
                                        <input class="form-control" type="text" id=""  name="session_duration">
                                    </div>
                                    <div class="form-group">
                                        <label>Other Requirements</label>
                                        <input class="form-control" type="text" id=""  name="">
                                    </div>
                                    <div class="form-group">
                                        <label>Referrals</label>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Referred to" type="text" id=""  name="">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Address of organization/individual" type="text" id=""  name="">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Phone Number" type="text" id=""  name="">
                                    </div>
                                    <div class="form-group ">
                                        <label>Reason for Referral</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Trauma Counselling </span></label>
                                                <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Family Counseling </span></label>
                                                <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Psychiatric Treatment  </span></label>
                                                <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Medical Treatment </span></label>
                                                <label><input class="px col-sm-12" type="checkbox" id="newReasonReferral"><span class="lbl">Others</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newReasonReferralTypes" style="display: none; margin-bottom: 1em;">
                                        <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_disaster" value="">
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
                                            <input id="FamilyCounselingDate" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
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
                                            <input type="text" class="form-control" id="bs-timepicker-component"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
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
                                        <input class="form-control" type="text" id=""  name="">
                                    </div>
                                    <div class="form-group">
                                        <label>No of Family Members Counseled</label>
                                        <input class="form-control" type="text" id=""  name="">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Comments/Remarks</label>
                                        <textarea class="form-control" rows="5" placeholder=""></textarea>
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
                                            <input type="text" class="form-control" id="ReintegrationTime"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
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
                                            <input id="ReintegrationDate" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
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
                                        <textarea class="form-control" rows="3" placeholder=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Comments</label>
                                        <textarea class="form-control" rows="3" placeholder=""></textarea>
                                    </div>
                                    <label>Next date for Session</label>
                                    <div class="input-group">
                                        <input id="NextdateforSession" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
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
                                                <label><input class="px" type="radio" id="iga_skillYes" name="iga_skill" value="yes" <?php echo $pre_data && $pre_data['iga_skill'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="iga_skillNo" name="iga_skill" value="no" <?php echo $pre_data && $pre_data['iga_skill'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Reason for drop out from the Counseling Session</label>
                                        <input class="form-control" type="text" id=""  name="">
                                    </div>
                                    <div class="form-group">
                                        <label>Review of Counselling Session</label>
                                        <input class="form-control" type="text" id=""  name="">
                                    </div>
                                    <div class="form-group">
                                        <label>Comments of the Client</label>
                                        <textarea class="form-control" rows="2" placeholder=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Counsellor’s Comment</label>
                                        <textarea class="form-control" rows="2" placeholder=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Final Evaluation</label>
                                        <input class="form-control" type="text" id=""  name="">
                                    </div>
                                    <div class="form-group">
                                        <label>Required Session After Completion (If Any)</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="iga_skillYes" name="iga_skill" value="yes" <?php echo $pre_data && $pre_data['iga_skill'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="iga_skillNo" name="iga_skill" value="no" <?php echo $pre_data && $pre_data['iga_skill'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
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
                                            <input type="text" class="form-control" id="FollowupTime"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
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
                                            <input id="FollowupTimeDate" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        init.push(function () {
                                            _datepicker('FollowupTimeDate');
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Followup Comment (*)</label>
                                        <textarea class="form-control" rows="3" placeholder=""></textarea>
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
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Microbusiness</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Business grant from project</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Enrolled in community enterprise</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Material Assistance</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Business equipment/tools</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Lease land or pond for business</span></label>
                                                        </div>
                                                    </div>

                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Training (Financial Literacy Training)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Advance Training</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Safe Migration</span></label>


                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" id="newInkind"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newInkindType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_disaster" value="">
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
                                                <label><input class="px" type="radio" id="yesLiteracyTraining" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noLiteracyTraining" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group LiteracyTraining" style="display:none">
                                        <input class="form-control" type="text" name="organization_name" placeholder="Duration of training" value="<?php echo $pre_data['disability_name'] ? $pre_data['disability_name'] : ''; ?>">
                                    </div>

                                    <script>
                                        init.push(function () {
                                            var isChecked = $('#yesCooperated').is(':checked');

                                            if (isChecked == true) {
                                                $('.LiteracyTraining').show();
                                            }

                                            $("#yesLiteracyTraining").on("click", function () {
                                                $('.LiteracyTraining').show();
                                            });

                                            $("#noLiteracyTraining").on("click", function () {
                                                $('.LiteracyTraining').hide();
                                            });
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Training Certificate Received</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>How has the training been used so far?</label>
                                        <textarea class="form-control" rows="2" placeholder=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Any other Comments</label>
                                        <textarea class="form-control" rows="2" placeholder=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Microbusiness Established</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesMicrobusiness" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noMicrobusiness" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group Microbusiness" style="display:none">

                                        <input class="form-control" type="text" name="organization_name" placeholder="Month Of Business Inauguration" value="<?php echo $pre_data['disability_name'] ? $pre_data['disability_name'] : ''; ?>"><BR />
                                        <input class="form-control" type="text" name="organization_name" placeholder="Year of Business Inauguration" value="<?php echo $pre_data['disability_name'] ? $pre_data['disability_name'] : ''; ?>">
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
                                                <label><input class="px" type="radio" id="yesFamilyTraining" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noFamilyTraining" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group FamilyTraining" style="display:none">
                                       	<label>Date of Training</label>
                                        <div class="input-group">
                                            <input id="FamilyTrainingDATE" type="text" class="form-control" name="" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
                                        </div></BR>
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('FamilyTrainingDATE');
                                            });
                                        </script>
                                        <input class="form-control" type="text" name="" placeholder="Place of Training" value="<?php echo $pre_data['disability_name'] ? $pre_data['disability_name'] : ''; ?>"><BR />
                                        <input class="form-control" type="text" name="" placeholder="Duration of Training" value="<?php echo $pre_data['disability_name'] ? $pre_data['disability_name'] : ''; ?>">
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
                                                <label><input class="px" type="radio" id="" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
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
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Agriculture forestry, fishing and farming</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Mining and quarrying</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Manufacturing</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Energies supply</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Water supply and waste management</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Construction</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Electrical and housing wiring</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Masonry</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Trade and repair</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Carpentry </span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Electronics and Maintenance</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Transportation and storage</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Hospitality</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">IT and communication</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Computer classes</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Financial and insurance activities</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Real estate</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Professional scientific and technical activities</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Administrative and support services (incl. cleaning and maintenance)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Public administration and defense</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Education (Teaching)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Arts and entertainment</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Domestic work</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" id="newVocational"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newVocationalType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_disaster" value="">
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
                                            <input id="StartDateTraining" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
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
                                            <input id="EndDateTraining" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        init.push(function () {
                                            _datepicker('EndDateTraining');
                                        });
                                    </script>
                                    <div class="form-group">
                                        <label>Status of the Training</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Completed</span></label>
                                                <label><input class="px" type="radio" id="" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">Declined</span></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="tab-pane fade" id="EconomicReferrals">
                        <fieldset>
<!--           CREATE                 dev_economic_reintegration_referrals-->
                            <legend>Section 4.1: Economic Reintegration Referrals</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Referrals done for Vocational Training</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesReferralstraining" name="Referralstraining" value="yes" <?php echo $pre_data && $pre_data['Referralstraining'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noReferralstraining" name="Referralstraining" value="no" <?php echo $pre_data && $pre_data['Referralstraining'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
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
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Agriculture forestry, fishing and farming</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Mining and quarrying</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Manufacturing</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Energies supply</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Water supply and waste management</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Construction</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Electrical and housing wiring</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Masonry</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Trade and repair</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Carpentry </span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Electronics and Maintenance</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Transportation and storage</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Hospitality</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">IT and communication</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Computer classes</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Financial and insurance activities</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Real estate</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Professional scientific and technical activities</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Administrative and support services (incl. cleaning and maintenance)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Public administration and defense</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Education (Teaching)</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Arts and entertainment</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Domestic work</span></label>

                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" id="newReferralstraining"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="newReferralstrainingType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_disaster" value="">
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
                                                <label><input class="px" type="radio" id="" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>How has the training been used so far?</label>
                                        <textarea class="form-control" rows="2" placeholder=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Any other Comments</label>
                                        <textarea class="form-control" rows="2" placeholder=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Referrals done for economic services</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Types of Economic Support</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Microbusiness</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Business grant from project</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Enrolled in community enterprise</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Financial Services</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Loan</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

                                                        </div>
                                                    </div>

                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Job Placement</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Material Assistance</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Business equipment/tools</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Lease land or pond for business</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Advance Training</span></label>

                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" id="EconomicSupport"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="EconomicSupportType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_disaster" value="">
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
                                                <label><input class="px" type="radio" id="yesLiteracyTraining" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noLiteracyTraining" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Referred To</label>
                                        <input class="form-control" type="text" name="" placeholder="Name" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"><br />
                                        <input class="form-control" type="text" name="" placeholder="Address" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"><br />
                                        <label>Date of Training</label>
                                        <div class="input-group">
                                            <input id="DatofTraining" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
                                        </div><br />
                                        <script type="text/javascript">
                                            init.push(function () {
                                                _datepicker('DatofTraining');
                                            });
                                        </script>
                                        <input class="form-control" type="text" name="" placeholder="Place of Training" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"><br />
                                        <input class="form-control" type="text" name="" placeholder="Duration of Training" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Status of the Training</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Completed</span></label>
                                                <label><input class="px" type="radio" id="" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">Not Completed</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Referred To</label>
                                        <input class="form-control" type="text" name="" placeholder="Name" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"><br />
                                        <input class="form-control" type="text" name="" placeholder="Address" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"><br />
                                    </div>
                                    <div class="form-group">
                                        <label>How has the assistance been utilized?</label>
                                        <textarea class="form-control" rows="2" placeholder=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Any Other Comments?</label>
                                        <textarea class="form-control" rows="2" placeholder=""></textarea>
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
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                    <div class="options_holder radio">
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Social Protection Schemes(Place to access to public services & Social Protection)</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Union Parished</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Upazila Parished</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">District/Upazila Social Welfare office</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">District/Upazila Youth Development office</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">District/ Upazila Women Affairs Department</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Private/ NGO</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Medical Support</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Project</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Government health facility</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Private/ NGO operated health service centre</span></label>
                                                            </div>
                                                        </div>
                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Education</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Admission</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Stipend/ Scholarship</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

                                                            </div>
                                                        </div>

                                                        <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Housing</span></label>
                                                        <div class="form-group col-sm-8">
                                                            <div class="options_holder radio">
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Allocation of ‘Khas land’</span></label>
                                                                <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Support for housing loan</span></label>
                                                                <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                    <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

                                                            </div>
                                                        </div>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Legal Services</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Legal Aid</span></label>
                                                        <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Legal Arbitration</span></label>
                                                        <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" id="TypesofEconomic"><span class="lbl">Others</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12" id="TypesofEconomicType" style="display: none; margin-bottom: 1em;">
                                                <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="TypesofEconomic" value="">
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
                                            <div class="form-group"  
                                                 <label>Date Services Received (Social)</label>
                                                <div class="input-group">
                                                    <input id="ServicesReceivedSocial" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
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
                                                    <input id="ServicesReceivedMedical" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
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
                                                    <input id="ServicesReceivedEducation" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
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
                                                    <input id="ServicesReceivedHousing" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
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
                                                    <input id="ServicesReceivedOthers" type="text" class="form-control" name="customer_birthdate" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
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
                                                <label><input class="px" type="radio" id="yesLiteracyTraining" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noLiteracyTraining" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" rows="3" placeholder="Lessons learnt from IPT show"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Attended community video shows?</label>
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesLiteracyTraining" name="is_disability" value="yes" <?php echo $pre_data && $pre_data['is_cooperated'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noLiteracyTraining" name="is_disability" value="no" <?php echo $pre_data && $pre_data['is_cooperated'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" rows="3" placeholder="Lessons learnt from video show"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Types of Support Referred for</legend>
                                        <div class="form-group ">
                                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                                <div class="options_holder radio">
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Social Protection Schemes(Place to access to public services & Social Protection)</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Union Parished</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Upazila Parished</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">District/Upazila Social Welfare office</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">District/Upazila Youth Development office</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">District/ Upazila Women Affairs Department</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Private/ NGO</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Medical Support</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Project</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Government health facility</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Private/ NGO operated health service centre</span></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Education</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Admission</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Stipend/ Scholarship</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

                                                        </div>
                                                    </div>

                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Housing</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Allocation of ‘Khas land’</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Support for housing loan</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Legal Services</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Legal Aid</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Legal Arbitration</span></label>
                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" id="supportreferred"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" id="TypesupportreferredType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="supportreferred" value="">
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
                                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="radio" id="yesCasedropped" name="Casedropped" value="yes" <?php echo $pre_data && $pre_data['Casedropped'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                                <label><input class="px" type="radio" id="noCasedropped" name="Casedropped" value="no" <?php echo $pre_data && $pre_data['Casedropped'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
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


                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Lack of interest</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Critical illness</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Family issues</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Have other issues to attend to</span></label>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Re-migrated</span></label>

                                                    <label class="col-sm-12"><input class="px col-sm-12" type="checkbox" id="ReasonDropping"><span class="lbl">Others</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="ReasonDroppingType" style="display: none; margin-bottom: 1em;">
                                            <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_disaster" value="">
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
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Child Care</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Education</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Admission</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Scholarship/Stipend</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Financial Services</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Loan</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Housing</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Allocation for khas land</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Support for land allocation</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Job Placement</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Legal Services</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Legal Aid</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Legal Arbitration</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-12"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Job Placement</span></label>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Training</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Financial Literacy Training</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Advance training from project</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Advance training through referrals</span></label>
                                                        </div>
                                                    </div>

                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Material Assistance</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Business equipment/tools</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Allocation of land or pond for business</span></label>

                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Remigration</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Direct Assistance from project</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Referrals support from project</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

                                                        </div>
                                                    </div>

                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Medical Support</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Medical treatment</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Psychiatric treatment</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Microbusiness</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Business grant</span></label>
                                                            <label style="float:left"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Others</span>
                                                                <input style="float:left" class="form-control" type="text" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>"></label>

                                                        </div>
                                                    </div>
                                                    <label class="col-sm-4"><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Psychosocial Support</span></label>
                                                    <div class="form-group col-sm-8">
                                                        <div class="options_holder radio">
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Individual Counselling</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Family Counselling</span></label>
                                                            <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Trauma Counseling</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-sm-12">
                                                        <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Social Protection Schemes</span></label>
                                                    </div>
                                                    <div class="form-group col-sm-12">
                                                        <input class="form-control" type="text" placeholder="Specify the services" name="" value="<?php echo $pre_data['mother_name'] ? $pre_data['mother_name'] : ''; ?>">
                                                    </div>
                                                    <div class="form-group col-sm-12">
                                                        <label><input class="px" type="checkbox" name="natural_disaster[]" value=""><span class="lbl">Special Security Measures</span></label>
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
                                            <textarea class="form-control" rows="3" placeholder="Comment on psychosocial reintegration"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3" placeholder="Comment on economic reintegration"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3" placeholder="Comment on social reintegration"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3" placeholder="Complete income tracking information"></textarea>
                                        </div>
                                    </fieldset>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Status of Case after Receiving the Services</legend>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Monthly income (BDT)" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Challenges" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Actions taken" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Remark of the participant (if any)" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Comment of BRAC Officer responsible for participant" type="text" name="" value="">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Remark of District Manager" type="text" name="" value="">
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