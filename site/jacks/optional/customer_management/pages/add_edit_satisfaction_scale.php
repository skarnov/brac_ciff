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
                    <legend class="scheduler-border">Reintegration Assistance Satisfaction Scale</legend>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>If applicable, how satisfied are you with the assistance of repatriation</label>
                            </div>                        
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <select class="form-control" name="satisfied_assistance">
                                    <option value="5">Very satisfied</option>
                                    <option value="4">Satisfied</option>
                                    <option value="3">Ok</option>
                                    <option value="2">Dissatisfied</option>
                                    <option value="1">Very Dissatisfied</option>
                                </select>
                            </div>                        
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>If applicable, how satisfied are you with the counseling assistance received</label>
                            </div>                        
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <select class="form-control" name="satisfied_counseling">
                                    <option value="5">Very satisfied</option>
                                    <option value="4">Satisfied</option>
                                    <option value="3">Ok</option>
                                    <option value="2">Dissatisfied</option>
                                    <option value="1">Very Dissatisfied</option>
                                </select>
                            </div>                        
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>If applicable, how satisfied are you with the economic assistance received</label>
                            </div>                        
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <select class="form-control" name="satisfied_economic">
                                    <option value="5">Very satisfied</option>
                                    <option value="4">Satisfied</option>
                                    <option value="3">Ok</option>
                                    <option value="2">Dissatisfied</option>
                                    <option value="1">Very Dissatisfied</option>
                                </select>
                            </div>                        
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>If applicable, how satisfied are you with the social  assistance received</label>
                            </div>                        
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <select class="form-control" name="satisfied_social">
                                    <option value="5">Very satisfied</option>
                                    <option value="4">Satisfied</option>
                                    <option value="3">Ok</option>
                                    <option value="2">Dissatisfied</option>
                                    <option value="1">Very Dissatisfied</option>
                                </select>
                            </div>                        
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>If applicable, how satisfied are you with the assistance received at the community level</label>
                            </div>                        
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <select class="form-control" name="satisfied_community">
                                    <option value="5">Very satisfied</option>
                                    <option value="4">Satisfied</option>
                                    <option value="3">Ok</option>
                                    <option value="2">Dissatisfied</option>
                                    <option value="1">Very Dissatisfied</option>
                                </select>
                            </div>                        
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>If applicable, how satisfied are with the reintegration support overall</label>
                            </div>                        
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <select class="form-control" name="satisfied_reintegration">
                                    <option value="5">Very satisfied</option>
                                    <option value="4">Satisfied</option>
                                    <option value="3">Ok</option>
                                    <option value="2">Dissatisfied</option>
                                    <option value="1">Very Dissatisfied</option>
                                </select>
                            </div>                        
                        </div>
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