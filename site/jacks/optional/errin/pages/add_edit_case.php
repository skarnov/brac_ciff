<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_case', 'edit_case')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit','action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_cases(array('case_id' => $edit, 'single' => true));
    if (!$pre_data) {
        add_notification('Invalid case, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'customer_id' => 'Returnee',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_case($data);

    if ($ret['success']) {
        $msg = "Information of case has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_errin/manage_cases'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

$all_customers = $this->get_errin_returnees(array('customer_type' => 'errin', 'single' => false));

$all_countries = getWorldCountry();
$all_countries_json = json_encode($all_countries);

doAction('render_start');
?>
<div class="page-header">
    <h1>New Case</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'icon' => 'icon_list',
                'text' => 'All Case Management',
                'title' => 'Manage Cases',
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
                        <label>Select Returnee</label>
                        <select class="form-control" name="customer_id" required>
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_customers['data'] as $value) :
                                ?>
                                <option value="<?php echo $value['pk_customer_id'] ?>" <?php
                                if ($value['pk_customer_id'] == $pre_data['fk_customer_id']) {
                                    echo 'selected';
                                }
                                ?>><?php echo $value['full_name'] ?></option>
                                    <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>EPI Country</label>
                        <select class="form-control" name="present_country" required>
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_countries as $value) {
                                ?>
                                <option value="<?php echo $value ?>" <?php if ($pre_data['epi_country'] == $value) echo 'selected' ?>><?php echo $value ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Total Reintegration Spend</label>
                        <input class="form-control" type="text" name="reintegration_spend" value="<?php echo $pre_data['reintegration_spend'] ? $pre_data['reintegration_spend'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>EPI Reference Number</label>
                        <input class="form-control" type="text" name="epi_number" value="<?php echo $pre_data['epi_number'] ? $pre_data['epi_number'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Target Close Date</label>
                        <div class="input-group">
                            <input id="createDate" type="text" class="form-control" name="close_date" value="<?php echo $pre_data['close_date'] && $pre_data['close_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['close_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('createDate');
                            });
                        </script>
                    </div> 
                </div>
            </div>
            <div class="panel-footer tar">
                <a href="<?php echo url('admin/dev_errin/manage_cases') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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
</div>