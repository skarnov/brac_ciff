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
    $pre_data = $this->get_social(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid social support, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST['ajax_type']) {
    if ($_POST['ajax_type'] == 'selectBranch') {
        $sql = "SELECT dev_customers.pk_customer_id, dev_customers.customer_id, dev_customers.full_name 
                FROM dev_customers
                    LEFT JOIN dev_reintegration_plan on dev_reintegration_plan.fk_customer_id = dev_customers.pk_customer_id
                WHERE dev_customers.customer_status = 'active' AND dev_customers.customer_type = 'returnee' AND dev_customers.fk_branch_id = '" . $_POST['branch_id'] . "' AND dev_reintegration_plan.reintegration_plan LIKE '%social%'";

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
    $reintegration_plan = in_array('social', $support_array);

    if ($reintegration_plan == true && $customer_info['customer_status'] == 'active' && $customer_info['customer_type'] == 'returnee') {

        $ret = $this->add_edit_social($data);

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
        add_notification('Social Reintegration Support is not assign for this beneficiary', 'error');
        header('location: ' . url('admin/dev_support_management/manage_supports'));
        exit();
    }
}

$customers = jack_obj('dev_customer_management');
$branch_id = $_config['user']['user_branch'];
if ($branch_id) {
    $sql = "SELECT pk_customer_id, customer_id, full_name FROM dev_customers WHERE customer_status = 'active' AND customer_type = 'returnee' AND fk_branch_id = '$branch_id'";
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Social Reintegration Support</h1>
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
                                ?>><?php echo $value['project_name'] . ' [' . $value['project_code'] . ']' ?></option>
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
                        <label>Member of migration forums</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="is_migration_forum_member" value="yes" <?php echo $pre_data && $pre_data['is_migration_forum_member'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" name="is_migration_forum_member" value="no" <?php echo $pre_data && $pre_data['is_migration_forum_member'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $supports = explode(',', $pre_data['support_received']);
                ?>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Type of support received</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="checkbox" name="support_received[]" value="access_to_public_services" <?php
                                    if (in_array('access_to_public_services', $supports)) {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Access To Public Services</span></label>
                                <label><input class="px" type="checkbox" name="support_received[]" value="access_to_social_protection_scheme" <?php
                                    if (in_array('access_to_social_protection_scheme', $supports)) {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Access to social protection scheme</span></label>
                                <label><input class="px" type="checkbox" name="support_received[]" value="other_services_from_union_parishad" <?php
                                    if (in_array('other_services_from_union_parishad', $supports)) {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Other services from Union parishad/upazila</span></label>
                                <label><input class="px" type="checkbox" name="support_received[]" value="services_from_women_affair" <?php
                                    if (in_array('services_from_women_affair', $supports)) {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Services from women Affair</span></label>
                                <label><input class="px" type="checkbox" name="support_received[]" value="free_legal_assistance" <?php
                                    if (in_array('free_legal_assistance', $supports)) {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Free Legal Assistance</span></label>
                                <label><input class="px" type="checkbox" name="support_received[]" value="local_meditiation" <?php
                                    if (in_array('local_meditiation', $supports)) {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Local Meditiation</span></label>
                                <label><input class="px" type="checkbox" name="support_received[]" id="newSupport"><span class="lbl">Others</span></label>
                            </div>
                        </div>
                    </div>
                    <div id="newSupportType" style="display: none; margin-bottom: 1em;">
                        <input class="form-control" placeholder="Please Specity" type="text" name="new_support" value="">
                    </div>
                    <script>
                        init.push(function () {
                            $("#newSupport").on("click", function () {
                                $('#newSupportType').toggle();
                            });
                        });
                    </script>
                    <?php
                    $support_from_whom = explode(',', $pre_data['support_from_whom']);
                    ?>
                    <div class="form-group">
                        <label>From whom</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="checkbox" name="support_from_whom[]" value="forum" <?php
                                    if (in_array('forum', $support_from_whom)) {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Forum</span></label>
                                <label><input class="px" type="checkbox" name="support_from_whom[]" value="rsc" <?php
                                    if (in_array('rsc', $support_from_whom)) {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">RSC</span></label>
                                <label><input class="px" type="checkbox" name="support_from_whom[]" value="paracounsellor" <?php
                                    if (in_array('paracounsellor', $support_from_whom)) {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Paracounsellor</span></label>
                                <label><input class="px" type="checkbox" name="support_from_whom[]" value="counsellor" <?php
                                    if (in_array('counsellor', $support_from_whom)) {
                                        echo 'checked';
                                    }
                                    ?>><span class="lbl">Counsellor</span></label>

                                <label><input class="px" type="checkbox" name="support_from_whom[]" id="newReason"><span class="lbl">Others</span></label>
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
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Participated in IPT shows?</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="is_participated_show" value="yes" <?php echo $pre_data && $pre_data['is_participated_show'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" name="is_participated_show" value="no" <?php echo $pre_data && $pre_data['is_participated_show'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Lessons learned from IPT show</label>
                        <textarea class="form-control" name="learn_show"><?php echo $pre_data['learn_show'] ? $pre_data['learn_show'] : ''; ?></textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Participated in community video shows?</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="is_per_community_video" value="yes" <?php echo $pre_data && $pre_data['is_per_community_video'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" name="is_per_community_video" value="no" <?php echo $pre_data && $pre_data['is_per_community_video'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Lessons learned from community video show</label>
                        <textarea class="form-control" name="learn_video"><?php echo $pre_data['learn_video'] ? $pre_data['learn_video'] : ''; ?></textarea>
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