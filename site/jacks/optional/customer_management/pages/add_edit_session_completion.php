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
    $pre_data = $this->get_psychosocial_completion(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid psychosocial session completion, no data found.', 'error');
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

    $ret = $this->add_edit_psychosocial_completion($data);

    if ($ret['success']) {
        $msg = "Psychosocial Session Completion Data has been " . ($edit ? 'updated.' : 'saved.');
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Psychosocial Session Completion</h1>
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
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Completed Counselling Session</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" id="isCompletedYes" name="is_completed" value="yes" <?php echo $pre_data && $pre_data['is_completed'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" id="isCompletedNo" name="is_completed" value="no" <?php echo $pre_data && $pre_data['is_completed'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Reason for drop out from the Counseling Session</label>
                        <input class="form-control" type="text" id="dropout_reason"  name="dropout_reason" value="<?php echo $pre_data['dropout_reason'] ? $pre_data['dropout_reason'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Review of Counselling Session</label>
                        <input class="form-control" type="text" id="review_session"  name="review_session" value="<?php echo $pre_data['review_session'] ? $pre_data['review_session'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Comments of the Client</label>
                        <textarea class="form-control" name="client_comments" value="<?php echo $pre_data['client_comments'] ? $pre_data['client_comments'] : ''; ?>" rows="2" placeholder="Comments of the Client"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Counsellor’s Comment</label>
                        <textarea class="form-control" rows="2" name="counsellor_comments" value="<?php echo $pre_data['counsellor_comments'] ? $pre_data['counsellor_comments'] : ''; ?>" placeholder="Counsellor’s Comment"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Final Evaluation</label>
                        <input class="form-control" type="text" id="final_evaluation"  name="final_evaluation" value="<?php echo $pre_data['final_evaluation'] ? $pre_data['final_evaluation'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Required Session After Completion (If Any)</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" id="RequiredSessionYes" name="required_session" value="yes" <?php echo $pre_data && $pre_data['required_session'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" id="RequiredSessionNo" name="required_session" value="no" <?php echo $pre_data && $pre_data['required_session'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                </div>
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