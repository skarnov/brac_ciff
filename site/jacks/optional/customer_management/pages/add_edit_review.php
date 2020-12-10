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
    $pre_data = $this->get_case_review(array('id' => $edit, 'single' => true));

    $reason_dropping = explode(',', $pre_data['reason_dropping']);
    $confirm_services = explode(',', $pre_data['confirm_services']);

    if (!$pre_data) {
        add_notification('Invalid review id, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'entry_date' => 'Entry Date'
        ),
    );
    $data['form_data'] = $_POST;
    $data['customer_id'] = $customer_id;
    $data['edit'] = $edit;

    $ret = $this->add_edit_review($data);

    if ($ret['success']) {
        $msg = "Case Review Data has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_customer_management/manage_cases'));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Case Review</h1>
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
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <fieldset>
                    <legend>Section 6: Review and Follow-Up</legend>
                    <div class="col-sm-6">
                        <div class="form-group">  
                            <label>Entry Date</label>
                            <div class="input-group">
                                <input id="entryDate" required type="text" class="form-control" name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : ''; ?>">
                            </div><br />
                            <script type="text/javascript">
                                init.push(function () {
                                    _datepicker("entryDate");
                                });
                            </script>
                        </div>
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
                        <?php
                        $reason_dropping = $reason_dropping ? $reason_dropping : array($reason_dropping);
                        ?> 
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
                                        <label class="col-sm-12"><input  class="px col-sm-12" type="checkbox" id="ReasonDropping" <?php echo $pre_data['other_reason_dropping'] != NULL ? 'checked' : '' ?>><span class="lbl">Others</span></label>
                                    </div>
                                </div>
                            </div>
                            <div id="ReasonDroppingType" style="display: none; margin-bottom: 1em;">
                                <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="new_reason_dropping" value="<?php echo $pre_data['other_reason_dropping'] ? $pre_data['other_reason_dropping'] : ''; ?>">
                            </div>
                            <script>
                                init.push(function () {
                                    var isChecked = $('#ReasonDropping').is(':checked');

                                    if (isChecked == true) {
                                        $('#ReasonDroppingType').show();
                                    }

                                    $("#ReasonDropping").on("click", function () {
                                        $('#ReasonDroppingType').toggle();
                                    });
                                });
                            </script>
                        </fieldset>
                        <?php
                        $confirm_services = $confirm_services ? $confirm_services : array($confirm_services);
                        ?> 
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Confirmed Services Received after 3 Months</legend>
                            <div class="form-group">
                                <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                                    <div class="options_holder radio">
                                        <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Child Care" <?php
                                            if (in_array('Child Care', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Child Care</span></label>
                                        <label class="col-sm-12"><input class="px" id="educationConfirm" type="checkbox" name="confirm_services[]" value="Education" <?php
                                            if (in_array('Education', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Education</span></label>
                                        <div id="educationConfirmAttr" class="form-group col-sm-12">
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
                                        <script>
                                            init.push(function () {
                                                $('#educationConfirmAttr').hide();

                                                var isChecked = $('#educationConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#educationConfirmAttr').show();
                                                }

                                                $("#educationConfirm").on("click", function () {
                                                    $('#educationConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="financialServiceConfirm" type="checkbox" name="confirm_services[]" value="Financial Services" <?php
                                            if (in_array('Financial Services', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Financial Services</span></label>
                                        <div id="financialServiceConfirmAttr" class="form-group col-sm-12">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Loan" <?php
                                                    if (in_array('Loan', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Loan</span></label>
                                            </div>
                                            <div class="form-group">
                                                <label>Other Financial Service</label>
                                                <input class="form-control" placeholder="Other financial service" type="text" name="followup_financial_service" value="<?php echo $pre_data['followup_financial_service'] ? $pre_data['followup_financial_service'] : ''; ?>">
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#financialServiceConfirmAttr').hide();

                                                var isChecked = $('#financialServiceConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#financialServiceConfirmAttr').show();
                                                }

                                                $("#financialServiceConfirm").on("click", function () {
                                                    $('#financialServiceConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="housingConfirm" type="checkbox" name="confirm_services[]" value="Housing" <?php
                                            if (in_array('Housing', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Housing</span></label>
                                        <div id="housingConfirmAttr" class="form-group col-sm-12">
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
                                        <script>
                                            init.push(function () {
                                                $('#housingConfirmAttr').hide();

                                                var isChecked = $('#housingConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#housingConfirmAttr').show();
                                                }

                                                $("#housingConfirm").on("click", function () {
                                                    $('#housingConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="legalServicesConfirm" type="checkbox" name="confirm_services[]" value="Legal Services" <?php
                                            if (in_array('Legal Services', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Legal Services</span></label>
                                        <div id="legalServicesConfirmAttr" class="form-group col-sm-12">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Legal Aid" <?php
                                                    if (in_array('Legal Aid"', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Legal Aid</span></label>
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Claiming Compensation" <?php
                                                    if (in_array('Claiming Compensation', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Claiming Compensation</span></label>
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Assistance in resolving family dispute" <?php
                                                    if (in_array('Assistance in resolving family dispute', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Assistance in resolving family dispute</span></label>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#legalServicesConfirmAttr').hide();

                                                var isChecked = $('#legalServicesConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#legalServicesConfirmAttr').show();
                                                }

                                                $("#legalServicesConfirm").on("click", function () {
                                                    $('#legalServicesConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" type="checkbox" name="confirm_services[]" value="Job Placement" <?php
                                            if (in_array('Job Placement', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Job Placement</span></label>
                                        <label class="col-sm-12"><input class="px" id="trainingConfirm" type="checkbox" name="confirm_services[]" value="Training" <?php
                                            if (in_array('Training', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Training</span></label>
                                        <div id="trainingConfirmAttr" class="form-group col-sm-12">
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
                                        <script>
                                            init.push(function () {
                                                $('#trainingConfirmAttr').hide();

                                                var isChecked = $('#trainingConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#trainingConfirmAttr').show();
                                                }

                                                $("#trainingConfirm").on("click", function () {
                                                    $('#trainingConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="materialAssistanceConfirm" type="checkbox" name="confirm_services[]" value="Material Assistance" <?php
                                            if (in_array('Material Assistance', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Material Assistance</span></label>
                                        <div id="materialAssistanceConfirmAttr" class="form-group col-sm-12">
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
                                        <script>
                                            init.push(function () {
                                                $('#materialAssistanceConfirmAttr').hide();

                                                var isChecked = $('#materialAssistanceConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#materialAssistanceConfirmAttr').show();
                                                }

                                                $("#materialAssistanceConfirm").on("click", function () {
                                                    $('#materialAssistanceConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="remigrationConfirm" type="checkbox" name="confirm_services[]" value="Remigration" <?php
                                            if (in_array('Remigration', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Remigration</span></label>
                                        <div id="remigrationConfirmAttr" class="form-group col-sm-12">
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
                                        <script>
                                            init.push(function () {
                                                $('#remigrationConfirmAttr').hide();

                                                var isChecked = $('#remigrationConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#remigrationConfirmAttr').show();
                                                }

                                                $("#remigrationConfirm").on("click", function () {
                                                    $('#remigrationConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="medicalSupportConfirm" type="checkbox" name="confirm_services[]" value="Medical Support" <?php
                                            if (in_array('Medical Support', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Medical Support</span></label>
                                        <div id="medicalSupportConfirmAttr" class="form-group col-sm-12">
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
                                        <script>
                                            init.push(function () {
                                                $('#medicalSupportConfirmAttr').hide();

                                                var isChecked = $('#medicalSupportConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#medicalSupportConfirmAttr').show();
                                                }

                                                $("#medicalSupportConfirm").on("click", function () {
                                                    $('#medicalSupportConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="microbusinessConfirm" type="checkbox" name="confirm_services[]" value="Microbusiness" <?php
                                            if (in_array('Microbusiness', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Microbusiness</span></label>
                                        <div id="microbusinessConfirmAttr" class="form-group col-sm-12">
                                            <div class="options_holder radio">
                                                <label><input class="px" type="checkbox" name="confirm_services[]" value="Business grant" <?php
                                                    if (in_array('Business grant"', $confirm_services)) {
                                                        echo 'checked';
                                                    }
                                                    ?>><span class="lbl">Business grant</span></label>
                                            </div>
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#microbusinessConfirmAttr').hide();

                                                var isChecked = $('#microbusinessConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#microbusinessConfirmAttr').show();
                                                }

                                                $("#microbusinessConfirm").on("click", function () {
                                                    $('#microbusinessConfirmAttr').toggle();
                                                });
                                            });
                                        </script>       
                                        <label class="col-sm-12"><input class="px" id="psychosocialSupportConfirm" type="checkbox" name="confirm_services[]" value="Psychosocial Support" <?php
                                            if (in_array('Psychosocial Support', $confirm_services)) {
                                                echo 'checked';
                                            }
                                            ?>><span class="lbl">Psychosocial Support</span></label>
                                        <div id="psychosocialSupportConfirmAttr" class="form-group col-sm-12">
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
                                        <script>
                                            init.push(function () {
                                                $('#psychosocialSupportConfirmAttr').hide();

                                                var isChecked = $('#psychosocialSupportConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#psychosocialSupportConfirmAttr').show();
                                                }

                                                $("#psychosocialSupportConfirm").on("click", function () {
                                                    $('#psychosocialSupportConfirmAttr').toggle();
                                                });
                                            });
                                        </script>    
                                        <label class="col-sm-12"><input class="px" id="socialProtectionConfirm" type="checkbox" value="Social Protection Schemes" <?php echo $pre_data && $pre_data['followup_social_protection'] != NULL ? 'checked' : '' ?>><span class="lbl">Social Protection Schemes</span></label>
                                        <div id="socialProtectionConfirmAttr" class="form-group">
                                            <input class="form-control" placeholder="Specify Social Protection Schemes" type="text" name="social_protection" value="<?php echo $pre_data['followup_social_protection'] ? $pre_data['followup_social_protection'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#socialProtectionConfirmAttr').hide();

                                                var isChecked = $('#socialProtectionConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#socialProtectionConfirmAttr').show();
                                                }

                                                $("#socialProtectionConfirm").on("click", function () {
                                                    $('#socialProtectionConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                        <label class="col-sm-12"><input class="px" id="securityMeasuresConfirm" type="checkbox" value="Special Security Measures" <?php echo $pre_data && $pre_data['special_security'] != NULL ? 'checked' : '' ?>><span class="lbl">Special Security Measures</span></label>
                                        <div id="securityMeasuresConfirmAttr" class="form-group">
                                            <input class="form-control" placeholder="Specify Security Measures" type="text" name="special_security" value="<?php echo $pre_data['special_security'] ? $pre_data['special_security'] : ''; ?>">
                                        </div>
                                        <script>
                                            init.push(function () {
                                                $('#securityMeasuresConfirmAttr').hide();

                                                var isChecked = $('#securityMeasuresConfirm').is(':checked');

                                                if (isChecked == true) {
                                                    $('#securityMeasuresConfirmAttr').show();
                                                }

                                                $("#securityMeasuresConfirm").on("click", function () {
                                                    $('#securityMeasuresConfirmAttr').toggle();
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Status of Case after Receiving the Services</legend>
                            <div class="form-group">
                                <input class="form-control" name="monthly_income" value="<?php echo $pre_data['monthly_income'] ? $pre_data['monthly_income'] : ''; ?>" placeholder="Monthly income (BDT)" type="number" name="" value="">
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="challenges" value="<?php echo $pre_data['challenges'] ? $pre_data['challenges'] : ''; ?>" placeholder="Challenges" type="text" name="" value="">
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="actions_taken" value="<?php echo $pre_data['actions_taken'] ? $pre_data['actions_taken'] : ''; ?>" placeholder="Actions taken" type="text" name="" value="">
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="remark_participant" value="<?php echo $pre_data['remark_participant'] ? $pre_data['remark_participant'] : ''; ?>" placeholder="Remark of the participant (If Any)" type="text" name="" value="">
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="comment_brac" value="<?php echo $pre_data['comment_brac'] ? $pre_data['comment_brac'] : ''; ?>" placeholder="Comment of BRAC Officer responsible for participant" type="text" name="" value="">
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="remark_district" value="<?php echo $pre_data['remark_district'] ? $pre_data['remark_district'] : ''; ?>" placeholder="Remark of District Manager" type="text" name="" value="">
                            </div>
                        </fieldset>
                    </div>					
                    <div class="col-sm-6">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Comment on Situation of Case</legend>
                            <div class="form-group">
                                <label>Psychosocial Reintegration Date</label>
                                <div class="input-group">
                                    <input id="commentPsychosocialDate" type="text" class="form-control" name="comment_psychosocial_date" value="<?php echo $pre_data['comment_psychosocial_date'] && $pre_data['comment_psychosocial_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['comment_psychosocial_date'])) : ''; ?>">
                                </div><br />
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker("commentPsychosocialDate");
                                    });
                                </script>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="comment_psychosocial" rows="3" placeholder="Comment on psychosocial reintegration"><?php echo $pre_data['comment_psychosocial'] ? $pre_data['comment_psychosocial'] : ''; ?></textarea>
                            </div>
                            <div class="form-group">  
                                <label>Economic Reintegration Date</label>
                                <div class="input-group">
                                    <input id="commentEconomicDate" type="text" class="form-control" name="comment_economic_date" value="<?php echo $pre_data['comment_economic_date'] && $pre_data['comment_economic_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['comment_economic_date'])) : ''; ?>">
                                </div><br />
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker("commentEconomicDate");
                                    });
                                </script>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="comment_economic" rows="3" placeholder="Comment on economic reintegration"><?php echo $pre_data['comment_economic'] ? $pre_data['comment_economic'] : ''; ?></textarea>
                            </div>
                            <div class="form-group">  
                                <label>Social Reintegration Date</label>
                                <div class="input-group">
                                    <input id="commentSocialDate" type="text" class="form-control" name="comment_social_date" value="<?php echo $pre_data['comment_social_date'] && $pre_data['comment_social_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['comment_social_date'])) : ''; ?>">
                                </div><br />
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker("commentSocialDate");
                                    });
                                </script>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="comment_social" rows="3" placeholder="Comment on social reintegration"><?php echo $pre_data['comment_social'] ? $pre_data['comment_social'] : ''; ?></textarea>
                            </div>
                            <div class="form-group">  
                                <label>Income Tracking Date</label>
                                <div class="input-group">
                                    <input id="commentIncomeDate" type="text" class="form-control" name="comment_income_date" value="<?php echo $pre_data['comment_income_date'] && $pre_data['comment_income_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['comment_income_date'])) : ''; ?>">
                                </div><br />
                                <script type="text/javascript">
                                    init.push(function () {
                                        _datepicker("commentIncomeDate");
                                    });
                                </script>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="comment_income" rows="3" placeholder="Complete income tracking information"><?php echo $pre_data['comment_income'] ? $pre_data['comment_income'] : ''; ?></textarea>
                            </div>
                        </fieldset>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_customer_management/manage_cases') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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
    </div>
</form>