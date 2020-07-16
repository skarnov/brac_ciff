<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_support', 'edit_support')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects(array('single' => false));

$branchManager = jack_obj('dev_branch_management');
$branches = $branchManager->get_branches(array('select_fields' => array('branches.pk_branch_id', 'branches.branch_name'), 'data_only' => true));

$branch_id = $_config['user']['user_branch'];

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_psychosocial(array('id' => $edit, 'single' => true));
    
    if (!$pre_data) {
        add_notification('Invalid psychosocial support, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST['ajax_type']) {
    if ($_POST['ajax_type'] == 'selectBranch') {

        if ($branch_id) {
            $sql = "SELECT dev_customers.pk_customer_id, dev_customers.customer_id, dev_customers.full_name 
                FROM dev_customers
                    LEFT JOIN dev_reintegration_plan on dev_reintegration_plan.fk_customer_id = dev_customers.pk_customer_id
                WHERE dev_customers.customer_status = 'active' AND dev_customers.customer_type = 'returnee' AND dev_customers.fk_branch_id = '" . $_POST['branch_id'] . "' AND dev_reintegration_plan.reintegration_plan LIKE '%psycho%'";
        } else {
            $sql = "SELECT dev_customers.pk_customer_id, dev_customers.customer_id, dev_customers.full_name 
                FROM dev_customers
                    LEFT JOIN dev_reintegration_plan on dev_reintegration_plan.fk_customer_id = dev_customers.pk_customer_id
                WHERE dev_customers.customer_status = 'active' AND dev_customers.customer_type = 'returnee' AND dev_reintegration_plan.reintegration_plan LIKE '%psycho%'";
        }
        
        $ret = $devdb->get_results($sql);

        foreach ($ret as $data) {
            if ($data['pk_customer_id'] == $pre_data['fk_customer_id']) {
                echo "<option value='" . $data['pk_customer_id'] . "' selected>" . $data['full_name'] . ' (' . $data['customer_id'] . ')' . "</option>";
            } else {
                echo "<option value='" . $data['pk_customer_id'] . "'>" . $data['full_name'] . ' (' . $data['customer_id'] . ')' . "</option>";
            }
        }
    }
    exit();
}

if ($_POST) {
    $data = array(
        'required' => array(
            'entry_date' => 'Entry Date',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $customerManager = jack_obj('dev_customer_management');
    $customer_id = $data['form_data']['customer_id'];

    $customer_info = $customerManager->get_returnees(array('customer_id' => $customer_id, 'select_fields' => array(
            'dev_customers.customer_status',
            'dev_customers.customer_type',
            'dev_reintegration_plan.reintegration_plan',
        ), 'single' => true));

    $support_plan = $customer_info['reintegration_plan'];
    $support_array = (explode(',', $support_plan));
    $reintegration_plan = in_array('psycho', $support_array);

    if ($reintegration_plan == true && $customer_info['customer_status'] == 'active' && $customer_info['customer_type'] == 'returnee') {

        $ret = $this->add_edit_psychosocial($data);

        if ($ret['success']) {
            $msg = "Support Data has been " . ($edit ? 'updated.' : 'saved.');
            add_notification($msg);
            $activityType = $edit ? 'update' : 'create';
            user_activity::add_activity($msg, 'success', $activityType);
            header('location: ' . url('admin/dev_support_management/manage_supports'));
            exit();
        } else {
            $pre_data = $_POST;
            print_errors($ret['error']);
        }
    } else {
        add_notification('Psychosocial Reintegration Support is not assign for this beneficiary', 'error');
        header('location: ' . url('admin/dev_support_management/manage_supports'));
        exit();
    }
}

$customers = jack_obj('dev_customer_management');

if ($branch_id) {
    $sql = "SELECT pk_customer_id, customer_id, full_name FROM dev_customers WHERE customer_status = 'active' AND customer_type = 'returnee' AND fk_branch_id = '$branch_id'";
    $all_customers = $devdb->get_results($sql);
} else {
    $sql = "SELECT pk_customer_id, customer_id, full_name FROM dev_customers WHERE customer_status = 'active' AND customer_type = 'returnee'";
    $all_customers = $devdb->get_results($sql);
}

doAction('render_start');
?>
<style type="text/css">
    .removeReadOnly{
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Psychosocial Reintegration Support</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Supports',
                'title' => 'Manage Supports',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Select Project</label>
                        <select class="form-control" name="project_id">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_projects['data'] as $value) :
                                ?>
                                <option value="<?php echo $value['pk_project_id'] ?>" <?php
                                if ($value['pk_project_id'] == $pre_data['fk_project_id']) {
                                    echo 'selected';
                                }
                                ?>><?php echo $value['project_short_name'] . ' [' . $value['project_code'] . ']' ?></option>
                                    <?php endforeach ?>
                        </select>
                    </div>
                    <?php if (!$edit) : ?>
                        <?php if (!$branch_id) : ?>
                            <div class="form-group">
                                <label>Branch</label>
                                <select required class="form-control" name="branch_id">
                                    <option value="">Select One</option>
                                    <?php
                                    foreach ($branches['data'] as $i => $v) {
                                        ?>
                                        <option value="<?php echo $v['pk_branch_id'] ?>" <?php if ($v['pk_branch_id'] == $pre_data['fk_branch_id']) echo 'selected' ?>><?php echo $v['branch_name'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Select Customer</label>
                                <select id="availableCustomers" required class="form-control" name="customer_id">

                                </select>
                            </div>
                        <?php endif ?>
                        <?php if ($branch_id): ?>
                            <input type="hidden" name="branch_id" value="<?php echo $_config['user']['user_branch'] ?>"/>
                            <div class="form-group">
                                <label>Select Customer</label>
                                <select required class="form-control" name="customer_id">
                                    <option value="">Select One</option>
                                    <?php
                                    foreach ($all_customers as $value) :
                                        ?>
                                        <option value="<?php echo $value['pk_customer_id'] ?>" <?php
                                        if ($value['pk_customer_id'] == $pre_data['fk_customer_id']) {
                                            echo 'selected';
                                        }
                                        ?>><?php echo $value['full_name'] ?></option>
                                            <?php endforeach ?>
                                </select>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                    <div class="form-group">
                        <label>Entry Date</label>
                        <div class="input-group">
                            <input id="entryDate" type="text" class="form-control" name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('entryDate');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Date of first meeting</label>
                        <div class="input-group">
                            <input id="firstMeeting" type="text" class="form-control" name="first_meeting" value="<?php echo $pre_data['first_meeting'] && $pre_data['first_meeting'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['first_meeting'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('firstMeeting');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Sub Type</label>
                        <select class="form-control" name="sub_type">
                            <option value="">Select One</option>
                            <option value="psycho_social_counselling" <?php
                            if ('psycho_social_counselling' == $pre_data['sub_type']) {
                                echo 'selected';
                            }
                            ?>>Psycho Social Counselling</option>
                            <option value="family_counselling" <?php
                            if ('family_counselling' == $pre_data['sub_type']) {
                                echo 'selected';
                            }
                            ?>>Family Counselling</option>
                            <option value="trauma_counseling" <?php
                            if ('trauma_counseling' == $pre_data['sub_type']) {
                                echo 'selected';
                            }
                            ?>>Trauma Counseling</option>
                            <option value="medical_assistance" <?php
                            if ('medical_assistance' == $pre_data['sub_type']) {
                                echo 'selected';
                            }
                            ?>>Medical Assistance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Note</label>
                        <input class="form-control" type="text" name="support_note" value="<?php echo $pre_data['support_note'] ? $pre_data['support_note'] : ''; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Place</label>
                        <input class="form-control" type="text" name="support_place" value="<?php echo $pre_data['support_place'] ? $pre_data['support_place'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Home Visit</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="is_home_visit" value="yes" <?php echo $pre_data && $pre_data['is_home_visit'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" name="is_home_visit" value="no" <?php echo $pre_data && $pre_data['is_home_visit'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Problems Identified</label>
                        <select class="form-control" name="problem_identified">
                            <option value="">Select One</option>
                            <option value="anxiety" <?php
                            if ('anxiety' == $pre_data['problem_identified']) {
                                echo 'selected';
                            }
                            ?>>Anxiety</option>
                            <option value="depression" <?php
                            if ('depression' == $pre_data['problem_identified']) {
                                echo 'selected';
                            }
                            ?>>Depression</option>
                            <option value="suicidal_ideation" <?php
                            if ('suicidal_ideation' == $pre_data['problem_identified']) {
                                echo 'selected';
                            }
                            ?>>Suicidal Ideation/Thought</option>
                            <option value="sleep_problems" <?php
                            if ('sleep_problems' == $pre_data['problem_identified']) {
                                echo 'selected';
                            }
                            ?>>Sleep Problems</option>
                            <option value="phobia" <?php
                            if ('phobia' == $pre_data['problem_identified']) {
                                echo 'selected';
                            }
                            ?>>Phobia/Fear</option>
                            <option value="acute_stress" <?php
                            if ('acute_stress' == $pre_data['problem_identified']) {
                                echo 'selected';
                            }
                            ?>>Acute Stress</option>
                            <option value="anger_problems" <?php
                            if ('anger_problems' == $pre_data['problem_identified']) {
                                echo 'selected';
                            }
                            ?>>Anger Problems</option>
                            <option value="addiction_issues" <?php
                            if ('addiction_issues' == $pre_data['problem_identified']) {
                                echo 'selected';
                            }
                            ?>>Addiction Issues (Substance Abuse of any Kinds)</option>
                            <option value="schizophrenia"<?php
                            if ('schizophrenia' == $pre_data['problem_identified']) {
                                echo 'selected';
                            }
                            ?>>Schizophrenia</option>
                            <option value="bipolar_disorder" <?php
                            if ('bipolar_disorder' == $pre_data['problem_identified']) {
                                echo 'selected';
                            }
                            ?>>Bipolar Mood Disorder</option>
                            <option value="ocd" <?php
                            if ('ocd' == $pre_data['problem_identified']) {
                                echo 'selected';
                            }
                            ?>>Repetitive Thought or Repetitive Behavior (OCD)</option>
                            <option value="conversion_reactions" <?php
                            if ('conversion_reactions' == $pre_data['problem_identified']) {
                                echo 'selected';
                            }
                            ?>>Conversion Reactions</option>
                            <option value="problems_socialization" <?php
                            if ('problems_socialization' == $pre_data['problem_identified']) {
                                echo 'selected';
                            }
                            ?>>Problems in Socialization</option>
                            <option value="family_problems" <?php
                            if ('family_problems' == $pre_data['problem_identified']) {
                                echo 'selected';
                            }
                            ?>>Family Problems</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description of the problem</label>
                        <textarea class="form-control" name="problem_description"><?php echo $pre_data['problem_description'] ? $pre_data['problem_description'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>History (Use 4P Factor Model)</label>
                        <textarea class="form-control" name="problem_history"><?php echo $pre_data['problem_history'] ? $pre_data['problem_history'] : ''; ?></textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <legend>Initial Plan</legend>
                    <div class="form-group">
                        <label>Number of sessions required</label>
                        <input class="form-control" type="number" name="required_session" value="<?php echo $pre_data['required_session'] ? $pre_data['required_session'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Duration of session (hours)</label>
                        <input class="form-control" type="text" name="session_duration" value="<?php echo $pre_data['session_duration'] ? $pre_data['session_duration'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Place of session</label>
                        <input class="form-control" type="text" name="session_place" value="<?php echo $pre_data['session_place'] ? $pre_data['session_place'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Require Family Counceling?</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" id="yesFamily" name="is_family_counceling" value="yes" <?php echo $pre_data && $pre_data['is_family_counceling'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" id="noFamily" name="is_family_counceling" value="no" <?php echo $pre_data && $pre_data['is_family_counceling'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="numberOfFamily" style="display:none;">
                        <label>Number of Family Counceling</label>
                        <input class="form-control" type="text" name="family_counseling" value="<?php echo $pre_data['family_counseling'] ? $pre_data['family_counseling'] : ''; ?>">
                    </div>
                    <script>
                        init.push(function () {
                            var isChecked = $('#yesFamily').is(':checked');

                            if (isChecked == true) {
                                $('#numberOfFamily').show();
                            }

                            $("#yesFamily").on("click", function () {
                                $('#numberOfFamily').show();
                            });
                            $("#noFamily").on("click", function () {
                                $('#numberOfFamily').hide();
                            });
                        });
                    </script>
                    <div class="form-group">
                        <label>Other requirements</label>
                        <textarea class="form-control" name="other_requirements"><?php echo $pre_data['other_requirements'] ? $pre_data['other_requirements'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Assessment  of  Psychometric score & Subjective rating score</label>
                        <textarea class="form-control" name="assesment_score"><?php echo $pre_data['assesment_score'] ? $pre_data['assesment_score'] : ''; ?></textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <legend>Referrals</legend>
                    <div class="form-group">
                        <label>Referred to</label>
                        <input class="form-control" type="text" name="reffer_to" value="<?php echo $pre_data['reffer_to'] ? $pre_data['reffer_to'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Address of organization/individual</label>
                        <input class="form-control" type="text" name="referr_address" value="<?php echo $pre_data['referr_address'] ? $pre_data['referr_address'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input class="form-control" type="text" name="contact_number" value="<?php echo $pre_data['contact_number'] ? $pre_data['contact_number'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Reason for referral</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="reason_for_reffer" value="trauma_counselling" <?php
                                    if ($pre_data['reason_for_reffer'] == 'trauma_counselling') {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Trauma Counselling</span></label>
                                <label><input class="px" type="radio" name="reason_for_reffer" value="family_counseling" <?php
                                    if ($pre_data['reason_for_reffer'] == 'family_counseling') {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Family Counseling</span></label>
                                <label><input class="px" type="radio" name="reason_for_reffer" value="problems_socialization" <?php
                                    if ($pre_data['reason_for_reffer'] == 'problems_socialization') {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Psychiatric Treatment</span></label>

                                <label><input class="px" type="radio" name="reason_for_reffer" id="newReason"><span class="lbl">Others</span></label>
                            </div>
                        </div>
                    </div>
                    <div id="newReasonType" style="display: none; margin-bottom: 1em;">
                        <input class="form-control" placeholder="Please Specity" type="text" name="new_reason" value="">
                    </div>
                    <script>
                        init.push(function () {
                            $("#newReason").on("click", function () {
                                $('#newReasonType').show();
                            });
                        });
                    </script>
                </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="panel-footer tar">
        <a href="<?php echo url('admin/dev_support_management/manage_supports') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
        <?php
        echo submitButtonGenerator(array(
            'action' => $edit ? 'update' : 'update',
            'size' => '',
            'id' => 'submit',
            'title' => $edit ? 'Update' : 'Save',
            'icon' => $edit ? 'icon_update' : 'icon_save',
            'text' => $edit ? 'Update' : 'Save'))
        ?>
    </div>
</form>
<script>
    var BD_LOCATIONS = <?php echo getBDLocationJson() ?>;
    init.push(function () {
        new bd_new_location_selector({
            'division': $('#filter_division'),
            'district': $('#filter_district'),
            'sub_district': $('#filter_sub_district')
        });

        $("select[name='branch_id']").change(function () {
            $('#availableCustomers').html('');
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    data: {
                        'branch_id': stateID,
                        'ajax_type': 'selectBranch'
                    },
                    success: function (data) {      
                        $('#availableCustomers').html(data);
                    }
                });
            } else {
                $('select[name="branch_id"]').empty();
            }
        });
    });
</script>