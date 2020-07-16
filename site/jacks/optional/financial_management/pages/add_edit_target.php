<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_financial', 'edit_financial')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$staffs = jack_obj('dev_staff_management');
$branch_id = $_config['user']['user_branch'];
if ($branch_id) {
    $all_staffs = $staffs->get_staffs(array('branch' => $branch_id));
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_sales_targets(array('branch_id' => $branch_id, 'target_id' => $edit, 'select_fields' => array(
            'dev_sales_targets.fk_project_id',
            'dev_sales_targets.fk_branch_id',
            'dev_sales_targets.fk_staff_id',
            'dev_sales_targets.month_name',
            'dev_sales_targets.target_quantity',
        ), 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid target, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

$projects = jack_obj('dev_project_management');
$branches = jack_obj('dev_branch_management');

$all_projects = $projects->get_projects(array('single' => false));
$all_branches = $branches->get_branches(array('single' => false));

if ($_POST['ajax_type']) {
    if ($_POST['ajax_type'] == 'selectStaff') {
        $all_staffs = $staffs->get_staffs(array('branch' => $_POST['branch_id']));
        foreach ($all_staffs['data'] as $staff) {
            if ($staff['pk_user_id'] == $pre_data['fk_staff_id']) {
                echo "<option value='" . $staff['pk_user_id'] . "' selected>" . $staff['user_fullname'] . "</option>";
            } else {
                echo "<option value='" . $staff['pk_user_id'] . "'>" . $staff['user_fullname'] . "</option>";
            }
        }
    }
    exit();
}

if ($_POST) {
    $data = array();
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;
    $ret = $this->add_edit_target($data);

    if ($ret['success']) {
        $msg = "Target " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_financial_management/manage_sales_target'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

doAction('render_start');
?>
<div class="page-header">
    <h1>Add New Target</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => 'manage_sales_target',
                'action' => 'list',
                'icon' => 'icon_list',
                'text' => 'Manage Sales Targets',
                'title' => 'Manage Sales Targets',
            ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
        <div class="panel">
            <div class="panel-body">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Select Project</label>
                        <select required class="form-control" name="project_id">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_projects['data'] as $i => $v) {
                                ?>
                                <option value="<?php echo $v['pk_project_id'] ?>" <?php
                                if ($v['pk_project_id'] == $pre_data['fk_project_id']) {
                                    echo 'selected';
                                }
                                ?>><?php echo $v['project_short_name'] ?></option>
                                        <?php
                                    }
                                    ?>
                        </select>
                    </div>
                    <?php if (!$branch_id) : ?>
                        <div class="form-group">
                            <label>Select Branch</label>
                            <select required class="form-control" name="branch_id">
                                <option value="">Select One</option>
                                <?php
                                foreach ($all_branches['data'] as $value) :
                                    ?>
                                    <option value="<?php echo $value['pk_branch_id'] ?>" <?php
                                    if ($value['pk_branch_id'] == $pre_data['fk_branch_id']) {
                                        echo 'selected';
                                    }
                                    ?>><?php echo $value['branch_name'] ?></option>
                                        <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Select Staff</label>
                            <select required class="form-control" data-selected="<?php echo $pre_data['fk_staff_id'] ? $pre_data['fk_staff_id'] : ''; ?>" id="availableStaffs" name="staff_id">

                            </select>
                        </div>
                    <?php endif ?>
                    <?php if ($branch_id): ?>
                    <input type="hidden" name="branch_id" value="<?php echo $_config['user']['user_branch'] ?>"/>
                        <div class="form-group">
                            <label>Select Staff</label>
                            <select required class="form-control" name="branch_id">
                                <option value="">Select One</option>
                                <?php
                                foreach ($all_staffs['data'] as $value) :
                                    ?>
                                    <option value="<?php echo $value['pk_user_id'] ?>" <?php
                                    if ($value['pk_user_id'] == $pre_data['fk_staff_id']) {
                                        echo 'selected';
                                    }
                                    ?>><?php echo $value['user_fullname'] ?></option>
                                        <?php endforeach ?>
                            </select>
                        </div>
                    <?php endif ?>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Select Month</label>
                        <select required class="form-control" name="month_name">
                            <option value="">Select One</option>
                            <option value="January" <?php if ('January' == $pre_data['month_name']) echo 'selected' ?>>January</option>
                            <option value="February" <?php if ('February' == $pre_data['month_name']) echo 'selected' ?>>February</option>
                            <option value="March" <?php if ('March' == $pre_data['month_name']) echo 'selected' ?>>March</option>
                            <option value="April" <?php if ('April' == $pre_data['month_name']) echo 'selected' ?>>April</option>
                            <option value="May" <?php if ('May' == $pre_data['month_name']) echo 'selected' ?>>May</option>
                            <option value="June" <?php if ('June' == $pre_data['month_name']) echo 'selected' ?>>June</option>
                            <option value="July" <?php if ('July' == $pre_data['month_name']) echo 'selected' ?>>July</option>
                            <option value="August" <?php if ('August' == $pre_data['month_name']) echo 'selected' ?>>August</option>
                            <option value="September" <?php if ('September' == $pre_data['month_name']) echo 'selected' ?>>September</option>
                            <option value="October" <?php if ('October' == $pre_data['month_name']) echo 'selected' ?>>October</option>
                            <option value="November" <?php if ('November' == $pre_data['month_name']) echo 'selected' ?>>November</option>
                            <option value="December" <?php if ('December' == $pre_data['month_name']) echo 'selected' ?>>December</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Target Quantity</label>
                        <input required class="form-control" type="number" name="target_quantity" value="<?php echo $pre_data['target_quantity'] ? $pre_data['target_quantity'] : ''; ?>">
                    </div>
                </div>
            </div>
            <div class="panel-footer tar">
                <?php
                echo submitButtonGenerator(array(
                    'action' => $edit ? 'update' : 'update',
                    'size' => '',
                    'id' => 'submit',
                    'title' => $edit ? 'Update Target' : 'Save Target',
                    'icon' => $edit ? 'icon_update' : 'icon_save',
                    'text' => $edit ? 'Update Target' : 'Save Target'))
                ?>
            </div>
        </div>
    </form>
</div>
</div>
<script>
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
    });
</script>