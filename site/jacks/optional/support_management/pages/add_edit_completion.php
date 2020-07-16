<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_support', 'edit_support')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit','action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_completion(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid completion, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'entry_date' => 'Entry Date',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_completion($data);

    if ($ret['success']) {
        $msg = "Completion Data has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_support_management/manage_supports'));
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
    <h1>Psychosocial Support Completion</h1>
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
                <div class="col-md-6">
                    <input type="hidden" name="fk_psycho_support_id" value="<?php echo $_GET['support_id'] ?>"/>
                    <input type="hidden" name="fk_customer_id" value="<?php echo $_GET['support_customer'] ?>"/>
                    <input type="hidden" name="support_id" value="<?php echo $_GET['support'] ?>"/>
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
                        <label>Completed counseling session?</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="is_completed" id="yesCompleted" value="yes" <?php echo $pre_data && $pre_data['is_completed'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" name="is_completed" id="noCompleted" value="no" <?php echo $pre_data && $pre_data['is_completed'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="reason" style="display: none;">
                        <div class="form-group">
                            <label>Reason for drop out from the counseling session</label>
                            <textarea class="form-control" name="dropout_reason"><?php echo $pre_data['dropout_reason'] ? $pre_data['dropout_reason'] : ''; ?></textarea>
                        </div>
                        <script>
                            init.push(function () {
                                var isChecked = $('#noCompleted').is(':checked');

                                if (isChecked == true) {
                                    $('#reason').show();
                                }

                                $("#noCompleted").on("click", function () {
                                    $('#reason').show();
                                });
                                $("#yesCompleted").on("click", function () {
                                    $('#reason').hide();
                                });
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Final Evaluation</label>
                        <textarea class="form-control" name="final_evaluation"><?php echo $pre_data['final_evaluation'] ? $pre_data['final_evaluation'] : ''; ?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Impact of counseling session</label>
                        <textarea class="form-control" name="counselling_impact"><?php echo $pre_data['counselling_impact'] ? $pre_data['counselling_impact'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Comments of client</label>
                        <textarea class="form-control" name="client_comments"><?php echo $pre_data['client_comments'] ? $pre_data['client_comments'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Counselor comment</label>
                        <textarea class="form-control" name="counsellor_comments"><?php echo $pre_data['counsellor_comments'] ? $pre_data['counsellor_comments'] : ''; ?></textarea>
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