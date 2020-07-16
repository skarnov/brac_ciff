<?php

global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_detainee', 'edit_detainee')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit','action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_detainees(array('customer_id' => $edit, 'single' => true));
    if (!$pre_data) {
        add_notification('Invalid detainee migrant, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'full_name' => 'Full Name'
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_detainee($data);

    if ($ret['customer_insert'] || $ret['customer_update']) {
        $detainee_id = $edit ? $edit : $ret['customer_insert']['success'];
        $detaineeData = $this->get_detainees(array('customer_id' => $detainee_id, 'single' => true));
        $msg = "Basic information of detainee migrant " . $detaineeData['full_name'] . " (ID: " . $detaineeData['customer_id'] . ") has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_customer_management/manage_detainee_migrants'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

$all_countries = getWorldCountry();
$all_countries_json = json_encode($all_countries);

doAction('render_start');
?>
<style type="text/css">
    .removeReadOnly {
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Detainee Migrant</h1>
    <?php if ($pre_data) : ?>
        <h4 class="text-primary">Detainee Migrant: <?php echo $pre_data['full_name'] ?></h4>
        <h4 class="text-primary">ID: <?php echo $pre_data['customer_id'] ?></h4>
    <?php endif; ?>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Detainee Migrants',
                'title' => 'Manage Detainee Migrants',
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
                    <div class="form-group">
                        <label>Entry Date</label>
                        <div class="input-group">
                            <input id="createDate" type="text" class="form-control" name="create_date" value="<?php echo $pre_data['create_date'] && $pre_data['create_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['create_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('createDate');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <select class="form-control" name="present_country" required>
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_countries as $value) {
                                ?>
                                <option value="<?php echo $value ?>" <?php if ($pre_data['present_country'] == $value) echo 'selected' ?>><?php echo $value ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Camp Name</label>
                        <input class="form-control" type="text" name="camp_name" value="<?php echo $pre_data['camp_name'] ? $pre_data['camp_name'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Body Number</label>
                        <input class="form-control" type="text" name="body_number" value="<?php echo $pre_data['body_number'] ? $pre_data['body_number'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Detainee Name</label>
                        <input class="form-control" type="text" name="full_name" value="<?php echo $pre_data['full_name'] ? $pre_data['full_name'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Father's Name</label>
                        <input class="form-control" type="text" name="father_name" value="<?php echo $pre_data['father_name'] ? $pre_data['father_name'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Contact Person</label>
                        <input class="form-control" type="text" name="customer_mobile" value="<?php echo $pre_data['customer_mobile'] ? $pre_data['customer_mobile'] : ''; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Division</label>
                        <div class="select2-primary">
                            <select class="form-control" id="division" name="present_division" data-selected="<?php echo $pre_data['present_division'] ? $pre_data['present_division'] : '' ?>"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>District</label>
                        <div class="select2-success">
                            <select class="form-control" id="district" name="present_district" data-selected="<?php echo $pre_data['present_district'] ? $pre_data['present_district'] : ''; ?>"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Having Passport?</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" id="yes" name="having_passport" value="yes" <?php echo $pre_data && $pre_data['having_passport'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" id="no" name="having_passport" value="no" <?php echo $pre_data && $pre_data['having_passport'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group passport" style="display:none">
                        <label>Passport No</label>
                        <input class="form-control" type="text" name="passport_number" value="<?php echo $pre_data['passport_number'] ? $pre_data['passport_number'] : ''; ?>">
                    </div>
                    <script>
                        init.push(function () {
                            var isChecked = $('#yes').is(':checked');

                            if (isChecked == true) {
                                $('.passport').show();
                            }

                            $("#yes").on("click", function () {
                                $('.passport').show();
                            });

                            $("#no").on("click", function () {
                                $('.passport').hide();
                            });
                        });
                    </script>
                    <div class="form-group">
                        <label>TP Applied</label>
                        <div class="input-group">
                            <input id="tpDate" type="text" class="form-control" name="tp_applied_date" value="<?php echo $pre_data['tp_applied_date'] && $pre_data['tp_applied_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['tp_applied_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('tpDate');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>TP No</label>
                        <input class="form-control" type="text" name="travel_pass" value="<?php echo $pre_data['travel_pass'] ? $pre_data['travel_pass'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>TP Issued</label>
                        <div class="input-group">
                            <input id="issuedDate" type="text" class="form-control" name="tp_issued_date" value="<?php echo $pre_data['tp_issued_date'] && $pre_data['tp_issued_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['tp_issued_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('issuedDate');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Ticket Status Date</label>
                        <div class="input-group">
                            <input id="statusDate" type="text" class="form-control" name="ticket_status_date" value="<?php echo $pre_data['ticket_status_date'] && $pre_data['ticket_status_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['ticket_status_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('statusDate');
                            });
                        </script>
                    </div>
                    <?php if ($edit): ?>
                        <div class="form-group">
                            <label>Detainee Status</label>
                            <select class="form-control" name="detainee_status">
                                <option value="">Select One</option>
                                <option value="repatriated" <?php if ('repatriated' == $pre_data['detainee_status']) echo 'selected' ?>>Repatriated</option>
                                <option value="not_repatriated" <?php if ('not_repatriated' == $pre_data['detainee_status']) echo 'selected' ?>>Not Repatriated</option>
                            </select>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer tar">
        <a href="<?php echo url('admin/dev_customer_management/manage_detainee_migrants') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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
<script type="text/javascript">
    var BD_LOCATIONS = <?php echo getBDLocationJson(); ?>;
    init.push(function () {
        new bd_new_location_selector({
            'division': $('#division'),
            'district': $('#district')
        });
    });
</script>