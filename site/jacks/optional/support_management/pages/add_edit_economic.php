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

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_economic(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid economic support, no data found.', 'error');
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

    $reintegration_plan = in_array('economic', $support_array);

    if ($reintegration_plan == true && $customer_info['customer_status'] == 'active' && $customer_info['customer_type'] == 'returnee') {

        $ret = $this->add_edit_economic($data);

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
        add_notification('Economic Reintegration Support is not assign for this beneficiary', 'error');
        header('location: ' . url('admin/dev_support_management/manage_supports'));
        exit();
    }
}

$customers = jack_obj('dev_customer_management');
$branch_id = $_config['user']['user_branch'];

$all_economic_vocational_training = $this->get_lookups('economic_vocational_training');

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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Economic Reintegration Support</h1>
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
                    <input type="hidden" name="support_id" value="<?php echo $_GET['support_id'] ? $_GET['support_id'] : '' ?>"/>
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
                        <label>In-kind Received?</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="inkind_received" id="yesReceived" value="yes" <?php echo $pre_data && $pre_data['inkind_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" name="inkind_received" id="noReceived" value="no" <?php echo $pre_data && $pre_data['inkind_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="amount" style="display: none;">
                        <div class="form-group">
                            <label>In-kind Amount</label>
                            <input type="number" class="form-control" name="inkind_amount" value="<?php echo $pre_data['inkind_amount'] ?>">
                        </div>
                        <script>
                            init.push(function () {
                                var isChecked = $('#yesReceived').is(':checked');

                                if (isChecked == true) {
                                    $('#amount').show();
                                }

                                $("#noReceived").on("click", function () {
                                    $('#amount').hide();
                                });

                                $("#yesReceived").on("click", function () {
                                    $('#amount').show();
                                });
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Attended migration fair?</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="is_attended_fair" value="yes" <?php echo $pre_data && $pre_data['is_attended_fair'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" name="is_attended_fair" value="no" <?php echo $pre_data && $pre_data['is_attended_fair'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Received Financial Literacy Training</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="is_financial_training" value="yes" <?php echo $pre_data && $pre_data['is_financial_training'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" name="is_financial_training" value="no" <?php echo $pre_data && $pre_data['is_financial_training'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Duration of training</label>
                        <input class="form-control" type="text" name="training_duration" value="<?php echo $pre_data['training_duration'] ? $pre_data['training_duration'] : ''; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Name of the vocational training received (single response)</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <?php foreach ($all_economic_vocational_training['data'] as $v_training) {
                                    ?>
                                    <label><input class="px" type="radio" name="received_vocational_training" value="<?php echo $v_training['lookup_value'] ?>" <?php
                                        if ($pre_data['received_vocational_training'] == $v_training['lookup_value']) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl"><?php echo $v_training['lookup_value'] ?></span></label>
                                <?php } ?>
                                <label><input class="px" type="radio" name="received_vocational_training" id="newReason"><span class="lbl">Others</span></label>
                            </div>
                        </div>
                    </div>
                    <div id="newReasonType" style="display: none; margin-bottom: 1em;">
                        <input class="form-control" placeholder="Please Specity" type="text" name="new_vocational_training" value="">
                    </div>
                    <script>
                        init.push(function () {
                            $("#newReason").on("click", function () {
                                $('#newReasonType').show();
                            });
                        });
                    </script>
                    <div class="form-group">
                        <label>Start date of training</label>
                        <div class="input-group">
                            <input id="startDate" type="text" class="form-control" name="start_date" value="<?php echo $pre_data['start_date'] && $pre_data['start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['start_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('startDate');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Closing date of training</label>
                        <div class="input-group">
                            <input id="closeDate" type="text" class="form-control" name="close_date" value="<?php echo $pre_data['close_date'] && $pre_data['close_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['close_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('closeDate');
                            });
                        </script>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status of the training </label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="training_status" value="ongoing" <?php echo $pre_data && $pre_data['training_status'] == 'ongoing' ? 'checked' : '' ?>><span class="lbl">Ongoing</span></label>
                                <label><input class="px" type="radio" name="training_status" value="completed" <?php echo $pre_data && $pre_data['training_status'] == 'completed' ? 'checked' : '' ?>><span class="lbl">Completed</span></label>
                                <label><input class="px" type="radio" name="training_status" value="uncompleted" <?php echo $pre_data && $pre_data['training_status'] == 'uncompleted' ? 'checked' : '' ?>><span class="lbl">Not Completed</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Training certificate received</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="is_certification_received" value="yes" <?php echo $pre_data && $pre_data['is_certification_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" name="is_certification_received" value="no" <?php echo $pre_data && $pre_data['is_certification_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>How has the training been used so far?</label>
                        <textarea class="form-control" name="training_used"><?php echo $pre_data['training_used'] ? $pre_data['training_used'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Any other comments?</label>
                        <textarea class="form-control" name="other_comments"><?php echo $pre_data['other_comments'] ? $pre_data['other_comments'] : ''; ?></textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Referrals done?</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="is_referrals_done" value="yes" <?php echo $pre_data && $pre_data['is_referrals_done'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" name="is_referrals_done" value="no" <?php echo $pre_data && $pre_data['is_referrals_done'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Support required for which referral is done</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="referral_support_required" value="loan" <?php
                                    if ($pre_data['referral_support_required'] == 'loan') {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Loan</span></label>
                                <label><input class="px" type="radio" name="referral_support_required" value="purchase_of_equipments" <?php
                                    if ($pre_data['referral_support_required'] == 'purchase_of_equipments') {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Purchase of equipments/Tools</span></label>
                                <label><input class="px" type="radio" name="referral_support_required" value="lease_land" <?php
                                    if ($pre_data['referral_support_required'] == 'lease_land') {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Lease Land/Pond</span></label>
                                <label><input class="px" type="radio" name="referral_support_required" value="job_placement" <?php
                                    if ($pre_data['referral_support_required'] == 'job_placement') {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Job Placement</span></label>
                                <label><input class="px" type="radio" name="referral_support_required" value="computer_classes" <?php
                                    if ($pre_data['referral_support_required'] == 'computer_classes') {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Computer classes</span></label>
                                <label><input class="px" type="radio" name="referral_support_required" id="newReffer"><span class="lbl">Others</span></label>
                            </div>
                        </div>
                    </div>
                    <div id="newRefferType" style="display: none; margin-bottom: 1em;">
                        <input class="form-control" placeholder="Please Specity" type="text" name="new_support" value="">
                    </div>
                    <script>
                        init.push(function () {
                            $("#newReffer").on("click", function () {
                                $('#newRefferType').show();
                            });
                        });
                    </script>
                </div>
                <div class="col-md-3">
                    <fieldset>
                        <legend>Referred To</legend>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="referral_name" value="<?php echo $pre_data['referral_name'] ? $pre_data['referral_name'] : ''; ?>" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="referral_address" value="<?php echo $pre_data['referral_address'] ? $pre_data['referral_address'] : ''; ?>" class="form-control"/>
                        </div>
                    </fieldset>
                    <div class="form-group">
                        <label>Required assistance received?</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="is_assistance_received" value="yes" <?php echo $pre_data && $pre_data['is_assistance_received'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" name="is_assistance_received" value="no" <?php echo $pre_data && $pre_data['is_assistance_received'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>How has the assistance been utilized?</label>
                        <textarea class="form-control" name="assistance_utilize"><?php echo $pre_data['assistance_utilize'] ? $pre_data['assistance_utilize'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Any other comments?</label>
                        <textarea class="form-control" name="other_comments"><?php echo $pre_data['other_comments'] ? $pre_data['other_comments'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Received assistance to re-migrate?</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="is_assistance_remigrate" value="yes" <?php echo $pre_data && $pre_data['is_assistance_remigrate'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" name="is_assistance_remigrate" value="no" <?php echo $pre_data && $pre_data['is_assistance_remigrate'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Name of destination country</label>
                        <input class="form-control" type="text" name="remigrate_country" value="<?php echo $pre_data['remigrate_country'] ? $pre_data['remigrate_country'] : ''; ?>">
                    </div>
                    <?php
                    if ($edit):
                        ?>
                        <div class="form-group">
                            <label>End Date</label>
                            <div class="input-group">
                                <input id="endDate" type="text" class="form-control" name="end_date" value="<?php echo $pre_data['end_date'] && $pre_data['end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['end_date'])) : date('d-m-Y'); ?>">
                            </div>
                            <script type="text/javascript">
                                init.push(function () {
                                    _datepicker('endDate');
                                });
                            </script>
                        </div>
                    <?php endif ?>
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