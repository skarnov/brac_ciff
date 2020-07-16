<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_support', 'edit_support')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit','action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_session(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid session, no data found.', 'error');
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

    $ret = $this->add_edit_session($data);

    if ($ret['success']) {
        $msg = "Followup Session Data has been " . ($edit ? 'updated.' : 'saved.');
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Followup Counselling Session</h1>
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
                    <input type="hidden" name="pk_support_id" value="<?php echo $_GET['id'] ?>"/>
                    <input type="hidden" name="fk_psycho_support_id" value="<?php echo $_GET['support_id'] ?>"/>
                    <input type="hidden" name="fk_customer_id" value="<?php echo $_GET['support_customer'] ?>"/>
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
                        <label>Entry Time</label>
                        <div class="input-group">
                            <input id="entryTime" type="text" class="form-control" name="entry_time" value="<?php echo $pre_data['entry_time'] && $pre_data['entry_time'] != '0000-00-00' ? date('h:i', strtotime($pre_data['entry_time'])) : date('h:i'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _timepicker('entryTime');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Place</label>
                        <input class="form-control" type="text" name="session_place" value="<?php echo $pre_data['session_place'] ? $pre_data['session_place'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Session Comments/Remarks</label>
                        <textarea class="form-control" name="session_comments"><?php echo $pre_data['session_comments'] ? $pre_data['session_comments'] : ''; ?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Session Plan</label>
                        <textarea class="form-control" name="initial_plan"><?php echo $pre_data['initial_plan'] ? $pre_data['initial_plan'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Description of Activities</label>
                        <textarea class="form-control" name="activities_description"><?php echo $pre_data['activities_description'] ? $pre_data['activities_description'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Next date for Session</label>
                        <div class="input-group">
                            <input id="nextDate" type="text" class="form-control" name="next_date" value="<?php echo $pre_data['next_date'] && $pre_data['next_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['next_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('nextDate');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Followup Comments/Remarks</label>
                        <textarea class="form-control" name="followup_comments"><?php echo $pre_data['followup_comments'] ? $pre_data['followup_comments'] : ''; ?></textarea>
                    </div>
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