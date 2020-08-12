<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_target', 'edit_target')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_targets(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid target, no data found.', 'error');
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

    $ret = $this->add_edit_target($data);

    if ($ret) {
        $msg = "Achievement has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_event_management/manage_targets?action=add_edit_target&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_event_management/manage_targets'));
        }
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

doAction('render_start');

ob_start();
?>
<style type="text/css">
    .removeReadOnly {
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Achievement </h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Targets',
                'title' => 'Manage Targets',
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
            <div class="col-md-6">
                <div class="form-group">
                    <label for="inputBranch">Select Month</label>
                    <select class="form-control" name="month" readonly>
                        <option value="January" <?php echo $pre_data && $pre_data['target_month'] == 'January' ? 'selected' : '' ?>>January</option>
                        <option value="February" <?php echo $pre_data && $pre_data['target_month'] == 'February' ? 'selected' : '' ?>>February</option>
                        <option value="March" <?php echo $pre_data && $pre_data['target_month'] == 'March' ? 'selected' : '' ?>>March</option>
                        <option value="April" <?php echo $pre_data && $pre_data['target_month'] == 'April' ? 'selected' : '' ?>>April</option>
                        <option value="May" <?php echo $pre_data && $pre_data['target_month'] == 'May' ? 'selected' : '' ?>>May</option>
                        <option value="June" <?php echo $pre_data && $pre_data['target_month'] == 'June' ? 'selected' : '' ?>>June</option>
                        <option value="July" <?php echo $pre_data && $pre_data['target_month'] == 'July' ? 'selected' : '' ?>>July</option>
                        <option value="August" <?php echo $pre_data && $pre_data['target_month'] == 'August' ? 'selected' : '' ?>>August</option>
                        <option value="September" <?php echo $pre_data && $pre_data['target_month'] == 'September' ? 'selected' : '' ?>>September</option>
                        <option value="October" <?php echo $pre_data && $pre_data['target_month'] == 'October' ? 'selected' : '' ?>>October</option>
                        <option value="November" <?php echo $pre_data && $pre_data['target_month'] == 'November' ? 'selected' : '' ?>>November</option>
                        <option value="December" <?php echo $pre_data && $pre_data['target_month'] == 'December' ? 'selected' : '' ?>>December</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Target Name (*)</label>
                    <input class="form-control" readonly type="text" name="target_name" value="<?php echo $pre_data['target_name'] ? $pre_data['target_name'] : ''; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Target Value/Amount (*)</label>
                    <input class="form-control" readonly type="text" name="target_value" value="<?php echo $pre_data['target_value'] ? $pre_data['target_value'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Enter Achievement</label>
                    <input class="form-control" type="text" name="achievement_value" value="<?php echo $pre_data['achievement_value'] ? $pre_data['achievement_value'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Remark</label>
                    <textarea class="form-control" readonly name="remark"><?php echo $pre_data['remark'] ? $pre_data['remark'] : ''; ?></textarea>
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_event_management/manage_targets') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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