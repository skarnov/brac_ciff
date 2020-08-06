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
    $pre_data = $this->get_psychosocial_session(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid psychosocial session, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'session_entry_date' => 'Entry Date',
        ),
    );
    $data['form_data'] = $_POST;
    $data['customer_id'] = $customer_id;
    $data['edit'] = $edit;

    $ret = $this->add_edit_psychosocial_session($data);

    if ($ret['success']) {
        $msg = "Psychosocial Data has been " . ($edit ? 'updated.' : 'saved.');
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Psychosocial  Session</h1>
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
                        <label>Time (*)</label>
                        <div class="input-group date">
                            <input type="text" name="session_entry_time" value="<?php echo $pre_data['session_entry_time'] && $pre_data['session_entry_time'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['session_entry_time'])) : date('d-m-Y'); ?>" class="form-control" id="ReintegrationTime"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                        </div>
                    </div>
                    <script>
                        init.push(function () {
                            var options2 = {
                                minuteStep: 1,
                                showSeconds: true,
                                showMeridian: false,
                                showInputs: false,
                                orientation: $('body').hasClass('right-to-left') ? {x: 'right', y: 'auto'} : {x: 'auto', y: 'auto'}
                            }
                            $('#ReintegrationTime').timepicker(options2);
                        });
                    </script>
                    <div class="form-group">
                        <label>Date (*)</label>
                        <div class="input-group">
                            <input id="ReintegrationDate" type="text" class="form-control" name="session_entry_date" value="<?php echo $pre_data['session_entry_date'] && $pre_data['session_entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['session_entry_date'])) : date('d-m-Y'); ?>">
                        </div>
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('ReintegrationDate');
                        });
                    </script>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Description of Activities</label>
                        <textarea class="form-control" rows="3"  name="activities_description" value="<?php echo $pre_data['activities_description'] ? $pre_data['activities_description'] : ''; ?>" placeholder="Description of Activities"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Comments</label>
                        <textarea class="form-control" rows="3" name="session_comments" value="<?php echo $pre_data['session_comments'] ? $pre_data['session_comments'] : ''; ?>" placeholder="Comments"></textarea>
                    </div>
                    <label>Next date for Session</label>
                    <div class="input-group">
                        <input id="NextdateforSession" type="text" class="form-control" name="next_date" value="<?php echo $pre_data['next_date'] && $pre_data['next_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['next_date'])) : date('d-m-Y'); ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('NextdateforSession');
                        });
                    </script>
                </div>
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