<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_followup', 'edit_followup')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_followups(array('followup_id' => $edit, 'single' => true));
    if (!$pre_data) {
        add_notification('Invalid followup, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

$branchManager = jack_obj('dev_branch_management');
$branches = $branchManager->get_branches(array('select_fields' => array('branches.pk_branch_id', 'branches.branch_name'), 'data_only' => true));

if ($_POST['ajax_type']) {
    if ($_POST['ajax_type'] == 'selectBranch') {
        $sql = "SELECT pk_customer_id, customer_id, full_name FROM dev_customers WHERE customer_status = 'active' AND customer_type = 'returnee' AND fk_branch_id = '" . $_POST['branch_id'] . "'";
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
            'customer_id' => 'Select Customer',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_followup($data);

    if ($ret['success']) {
        $msg = "Information of followup has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_followup_management/manage_followups'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
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
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Followup</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Followups',
                'title' => 'Manage All Followups',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
        <div class="panel">
            <div class="panel-body">
                <div class="col-md-4">
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
                    <div class="form-group">
                        <label>Follow up Date</label>
                        <div class="input-group">
                            <input id="birthdate" type="text" class="form-control" name="followup_date" value="<?php echo $pre_data['followup_date'] && $pre_data['followup_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['followup_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('birthdate');
                            });
                        </script>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Status of beneficiary </label>
                        <textarea class="form-control autoHeight" name="beneficiary_status"><?php echo $pre_data['beneficiary_status'] ? $pre_data['beneficiary_status'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Challenges </label>
                        <textarea class="form-control autoHeight" name="followup_challenges"><?php echo $pre_data['followup_challenges'] ? $pre_data['followup_challenges'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Action Taken</label>
                        <textarea class="form-control autoHeight" name="action_taken"><?php echo $pre_data['action_taken'] ? $pre_data['action_taken'] : ''; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="panel-footer tar">
                <a href="<?php echo url('admin/dev_followup_management/manage_followups') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
                <?php
                echo submitButtonGenerator(array(
                    'action' => $edit ? 'update' : 'update',
                    'size' => '',
                    'id' => 'submit',
                    'title' => $edit ? 'Update The Followup' : 'Save The Followup',
                    'icon' => $edit ? 'icon_update' : 'icon_save',
                    'text' => $edit ? 'Update' : 'Save'))
                ?>
            </div>
        </div>
    </form>
</div>
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