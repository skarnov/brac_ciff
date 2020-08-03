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
    $pre_data = $this->get_satisfaction_scale(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid reintegration assistance satisfaction Scale, no data found.', 'error');
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
    $data['customer_id'] = $customer_id;
    $data['edit'] = $edit;

    $ret = $this->add_edit_satisfaction_scale($data);

    if ($ret['success']) {
        $msg = "Reintegration Assistance Satisfaction Scale Data has been " . ($edit ? 'updated.' : 'saved.');
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Reintegration Assistance Satisfaction Scale</h1>
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
            <div class="col-sm-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Comment on Situation of Case</legend>
                    <div class="form-group">
                        <textarea class="form-control" name="comment_psychosocial" value="<?php echo $pre_data['comment_psychosocial'] ? $pre_data['comment_psychosocial'] : ''; ?>" rows="3" placeholder="Comment on psychosocial reintegration"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="comment_economic" value="<?php echo $pre_data['comment_economic'] ? $pre_data['comment_economic'] : ''; ?>" rows="3" placeholder="Comment on economic reintegration"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="comment_social" value="<?php echo $pre_data['comment_social'] ? $pre_data['comment_social'] : ''; ?>" rows="3" placeholder="Comment on social reintegration"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="complete_income" value="<?php echo $pre_data['complete_income'] ? $pre_data['complete_income'] : ''; ?>" rows="3" placeholder="Complete income tracking information"></textarea>
                    </div>
                </fieldset>
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Status of Case after Receiving the Services</legend>
                    <div class="form-group">
                        <input class="form-control" name="monthly_income" value="<?php echo $pre_data['monthly_income'] ? $pre_data['monthly_income'] : ''; ?>" placeholder="Monthly income (BDT)" type="text" name="" value="">
                    </div>
                    <div class="form-group">
                        <input class="form-control" name="challenges" value="<?php echo $pre_data['challenges'] ? $pre_data['challenges'] : ''; ?>" placeholder="Challenges" type="text" name="" value="">
                    </div>
                    <div class="form-group">
                        <input class="form-control" name="actions_taken" value="<?php echo $pre_data['actions_taken'] ? $pre_data['actions_taken'] : ''; ?>" placeholder="Actions taken" type="text" name="" value="">
                    </div>
                    <div class="form-group">
                        <input class="form-control" name="remark_participant" value="<?php echo $pre_data['remark_participant'] ? $pre_data['remark_participant'] : ''; ?>" placeholder="Remark of the participant (if any)" type="text" name="" value="">
                    </div>
                    <div class="form-group">
                        <input class="form-control" name="comment_brac" value="<?php echo $pre_data['comment_brac'] ? $pre_data['comment_brac'] : ''; ?>" placeholder="Comment of BRAC Officer responsible for participant" type="text" name="" value="">
                    </div>
                    <div class="form-group">
                        <input class="form-control" name="remark_district" value="<?php echo $pre_data['remark_district'] ? $pre_data['remark_district'] : ''; ?>" placeholder="Remark of District Manager" type="text" name="" value="">
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