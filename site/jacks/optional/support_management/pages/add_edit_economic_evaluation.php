<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_support', 'edit_support')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit','action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_economic_evaluation(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid evaluation, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

$customers = jack_obj('dev_customer_management');

if ($_POST) {
    $data = array(
        'required' => array(
            'entry_date' => 'Entry Date',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_economic_evaluation($data);

    if ($ret['success']) {
        $msg = "Evaluation Data has been " . ($edit ? 'updated.' : 'saved.');
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Economic Evaluation</h1>
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
                    <input type="hidden" name="fk_economic_support_id" value="<?php echo $_GET['support_id'] ?>"/>
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
                    <?php
                    foreach ($customers->evaluationQA as $dbCol => $eachQA) {
                        if ($eachQA['type'] !== 'economic')
                            continue;
                        ?>
                        <div class="form-group">
                            <label><?php echo $eachQA['q'] ?></label>
                            <select class="form-control" name="<?php echo $dbCol ?>">
                                <option value="">Select One</option>
                                <?php
                                foreach ($eachQA['a'] as $aValue => $aLabel) {
                                    $selected = $pre_data && $pre_data[$dbCol] == $aValue ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $aValue ?>" <?php echo $selected ?>><?php echo $aLabel ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <?php
                    }
                    ?>
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