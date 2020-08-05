<?php
$customer_id = $_GET['customer_id'] ? $_GET['customer_id'] : null;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_case', 'edit_case')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_initial_evaluation(array('customer_id' => $edit, 'single' => true));
    
    $service_requested = explode(',', $pre_data['evaluate_services']);

    if (!$pre_data) {
        add_notification('Invalid evaluation, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'is_participant' => 'Selected as a participant'
        ),
    );
    $data['form_data'] = $_POST;
    $data['customer_id'] = $customer_id;
    $data['edit'] = $edit;

    $ret = $this->add_edit_initial_evaluation($data);

    if ($ret['success']) {
        $msg = "Evaluation has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_customer_management/manage_customers'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

doAction('render_start');
?>
<style type="text/css">
    .removeReadOnly{
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Initial Evaluation</h1>
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
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Selected as a participant (*)</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" id="Yesparticipant" name="is_participant" value="yes" <?php echo $pre_data && $pre_data['is_participant'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" id="Noparticipant" name="is_participant" value="no" <?php echo $pre_data && $pre_data['is_participant'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12" id="Justification" style="margin-bottom: 1em; display: none;">
                        <input class="form-control" type="text" placeholder="Justification of selecting for project" name="justification_project" value="<?php echo $pre_data['justification_project'] ? $pre_data['justification_project'] : ''; ?>">
                    </div>
                    <script>
                        init.push(function () {
                            $("#Yesparticipant").on("click", function () {
                                $('#Justification').toggle();
                            });
                        });
                    </script>
                    <?php
                        $service_requested = $service_requested ? $service_requested : array($service_requested);
                    ?> 
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Types of support that can be provided under the project or referred (based on the needs identified)</legend>
                        <div class="form-group">
                            <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                <div class="options_holder radio">
                                    <label class="col-sm-12"><input class="px" type="checkbox" name="evaluate_services[]" value="Child Care" <?php
                                        if (in_array('Child Care', $service_requested)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Child Care</span></label>
                                    <label class="col-sm-4"><input class="px" type="checkbox" name="evaluate_services[]" value="Education" <?php
                                        if (in_array('Education', $service_requested)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Education</span></label>
                                    <div class="form-group col-sm-8">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Admission" <?php
                                                if (in_array('Admission', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Admission</span></label>
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Scholarship/Stipend" <?php
                                                if (in_array('Scholarship/Stipend', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Scholarship/Stipend</span></label>
                                        </div>
                                    </div>
                                    <label class="col-sm-4"><input class="px" type="checkbox" name="evaluate_services[]" value="Financial Services" <?php
                                        if (in_array('Financial Services', $service_requested)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Financial Services</span></label>
                                    <div class="form-group col-sm-8">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Loan" <?php
                                                if (in_array('Loan', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Loan</span></label>
                                        </div>
                                    </div>
                                    <label class="col-sm-4"><input class="px" type="checkbox" name="evaluate_services[]" value="Housing" <?php
                                        if (in_array('Housing', $service_requested)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Housing</span></label>
                                    <div class="form-group col-sm-8">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Allocation for khas land" <?php
                                                if (in_array('Allocation for khas land', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Allocation for khas land</span></label>
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Support for land allocation" <?php
                                                if (in_array('Support for land allocation', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Support for land allocation</span></label>
                                        </div>
                                    </div>
                                    <label class="col-sm-12"><input class="px" type="checkbox" name="evaluate_services[]" value="Job Placement" <?php
                                        if (in_array('Job Placement', $service_requested)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Job Placement</span></label>
                                    <label class="col-sm-4"><input class="px" type="checkbox" name="evaluate_services[]" value="Legal Services" <?php
                                        if (in_array('Legal Services', $service_requested)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Legal Services</span></label>
                                    <div class="form-group col-sm-8">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Legal Aid" <?php
                                                if (in_array('Legal Aid', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Legal Aid</span></label>
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Legal Arbitration" <?php
                                                if (in_array('Legal Arbitration', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Legal Arbitration</span></label>
                                        </div>
                                    </div>
                                    <label class="col-sm-12"><input class="px" type="checkbox" name="evaluate_services[]" value="Job Placement" <?php
                                        if (in_array('Job Placement', $service_requested)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Job Placement</span></label>
                                    <label class="col-sm-4"><input class="px" type="checkbox" name="evaluate_services[]" value="Training" <?php
                                        if (in_array('Training', $service_requested)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Training</span></label>
                                    <div class="form-group col-sm-8">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Financial Literacy Training" <?php
                                                if (in_array('Financial Literacy Training', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Financial Literacy Training</span></label>
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Advance training from project" <?php
                                                if (in_array('Advance training from project', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Advance training from project</span></label>
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Advance training through referrals" <?php
                                                if (in_array('Advance training through referrals', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Advance training through referrals</span></label>
                                        </div>
                                    </div>
                                    <label class="col-sm-4"><input class="px" type="checkbox" name="evaluate_services[]" value="Material Assistance" <?php
                                        if (in_array('Material Assistance', $service_requested)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Material Assistance</span></label>
                                    <div class="form-group col-sm-8">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Business equipment/tools" <?php
                                                if (in_array('Business equipment/tools', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Business equipment/tools</span></label>
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Allocation of land or pond for business" <?php
                                                if (in_array('Allocation of land or pond for business', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Allocation of land or pond for business</span></label>
                                        </div>
                                    </div>
                                    <label class="col-sm-4"><input class="px" type="checkbox" name="evaluate_services[]" value="Remigration" <?php
                                        if (in_array('Remigration', $service_requested)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Remigration</span></label>
                                    <div class="form-group col-sm-8">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Direct Assistance from project" <?php
                                                if (in_array('Direct Assistance from project', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Direct Assistance from project</span></label>
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Referrals support from project" <?php
                                                if (in_array('Referrals support from project', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Referrals support from project</span></label>
                                        </div>
                                    </div>
                                    <label class="col-sm-4"><input class="px" type="checkbox" name="evaluate_services[]" value="Medical Support" <?php
                                        if (in_array('Medical Support', $service_requested)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Medical Support</span></label>
                                    <div class="form-group col-sm-8">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Medical treatment" <?php
                                                if (in_array('Medical treatment', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Medical treatment</span></label>
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Psychiatric treatment" <?php
                                                if (in_array('Psychiatric treatment', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Psychiatric treatment</span></label>
                                        </div>
                                    </div>
                                    <label class="col-sm-4"><input class="px" type="checkbox" name="evaluate_services[]" value="Microbusiness" <?php
                                        if (in_array('Microbusiness', $service_requested)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Microbusiness</span></label>
                                    <div class="form-group col-sm-8">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Business grant" <?php
                                                if (in_array('Business grant', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Business grant</span></label>
                                        </div>
                                    </div>
                                    <label class="col-sm-4"><input class="px" type="checkbox" name="evaluate_services[]" value="Psychosocial Support" <?php
                                        if (in_array('Psychosocial Support', $service_requested)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl">Psychosocial Support</span></label>
                                    <div class="form-group col-sm-8">
                                        <div class="options_holder radio">
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Individual Counselling" <?php
                                                if (in_array('Individual Counselling', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Individual Counselling</span></label>
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Family Counselling" <?php
                                                if (in_array('Family Counselling', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Family Counselling</span></label>
                                            <label><input class="px" type="checkbox" name="evaluate_services[]" value="Trauma Counseling" <?php
                                                if (in_array('Trauma Counseling', $service_requested)) {
                                                    echo 'checked';
                                                }
                                                ?>><span class="lbl">Trauma Counseling</span></label>
                                        </div>
                                    </div>
                                    <label class="col-sm-4"><input class="px" id="OtherEval" type="checkbox" name="evaluate_services[]" value="Psychosocial Support"><span class="lbl">Other</span></label>
                                    <div class="form-group col-sm-8">
                                        <br>
                                        <div class="form-group col-sm-12" id="InputOtherEval" style="margin-bottom: 1em; display: none;">
                                            <input class="form-control" type="text" placeholder="Others" name="new_evaluate_services" value="">
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label><input class="px" type="checkbox" id="YesEvaluateSpecifySevervice" name="is_social_schemes" value="Social Protection Schemes"><span class="lbl">Social Protection Schemes</span></label>
                                    </div>
                                    <div class="form-group col-sm-12" id="EvaluateSpecifySevervice" style="margin-bottom: 1em; display: none;">
                                        <input class="form-control" type="text" placeholder="Specify the services" name="new_social_protection" value="">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#OtherEval").on("click", function () {
                                                $('#InputOtherEval').toggle();
                                            });

                                            $("#YesEvaluateSpecifySevervice").on("click", function () {
                                                $('#EvaluateSpecifySevervice').toggle();
                                            });
                                        });
                                    </script>
                                    <div class="form-group col-sm-12">
                                        <label><input class="px" id="YesMeasures" type="checkbox" name="is_special_security_measures" value="Special Security Measures"><span class="lbl">Special Security Measures</span></label>
                                    </div>
                                    <div class="form-group col-sm-12" id="MeasuresSevervice" style="margin-bottom: 1em; display: none;">
                                        <input class="form-control" type="text" name="new_security_measures" placeholder="Specify the services" name="" value="<?php echo $pre_data['special_security_measures'] ? $pre_data['special_security_measures'] : ''; ?>">
                                    </div>
                                    <script>
                                        init.push(function () {
                                            $("#YesMeasures").on("click", function () {
                                                $('#MeasuresSevervice').toggle();
                                            });
                                        });
                                    </script>
                                </div>
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
            'text' => $edit ? 'Update' : 'Save'))
        ?>
    </div>
</form>