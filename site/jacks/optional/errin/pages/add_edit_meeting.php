<?php

global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_meeting', 'edit_meeting')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit','action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_meetings(array('meeting_id' => $edit, 'single' => true));
    if (!$pre_data) {
        add_notification('Invalid meeting, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST['ajax_type']) {
    if ($_POST['ajax_type'] == 'selectCase') {
        $sql = "SELECT pk_case_id, case_number FROM dev_cases WHERE fk_customer_id = '" . $_POST['customer_id'] . "'";
        $ret = $devdb->get_results($sql);
        foreach ($ret as $case) {
            if ($case['pk_case_id'] == $pre_data['fk_case_id']) {
                echo "<option value='" . $case['pk_case_id'] . "' selected>" . $case['case_number'] . "</option>";
            } else {
                echo "<option value='" . $case['pk_case_id'] . "'>" . $case['case_number'] . "</option>";
            }
        }
    } elseif ($_POST['ajax_type'] == 'selectTitle') {
        $sql = "SELECT case_number FROM dev_cases WHERE pk_case_id = '" . $_POST['case_id'] . "'";
        $ret = $devdb->get_row($sql)['case_number'].' Meeting Schedule';
        echo $ret;
    }
    exit();
}

if ($_POST) {
    $data = array(
        'required' => array(
            'customer_id' => 'Returnee',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_meeting($data);

    if ($ret['success']) {
        $msg = "Information of meeting has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_errin/manage_meetings'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

$all_customers = $this->get_errin_returnees(array('customer_type' => 'errin', 'single' => false));
doAction('render_start');
?>
<div class="page-header">
    <h1>New Meeting Schedule</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'icon' => 'icon_list',
                'text' => 'All Meeting Schedule Management',
                'title' => 'Manage Meeting Schedule',
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
                        <label>Returnee</label>
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
                        <label>Case</label>
                        <select class="form-control" data-selected="<?php echo $pre_data['pk_case_id'] ? $pre_data['pk_case_id'] : ''; ?>" id="availableCases" name="case_id" required>

                        </select>
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input readonly id="generatedTitle" class="form-control" type="text" name="meeting_title" value="<?php echo $pre_data['meeting_title'] ? $pre_data['meeting_title'] : ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Meeting</label>
                        <select class="form-control" name="meeting_type">
                            <option value="">Select One</option>
                            <option value="Ad HOC Meeting" <?php
                                if ('Ad HOC Meeting'== $pre_data['meeting_type']) {
                                    echo 'selected';
                                }
                                ?>>Ad HOC Meeting</option>
                            <option value="Airport Meet" <?php
                                if ('Airport Meet'== $pre_data['meeting_type']) {
                                    echo 'selected';
                                }
                                ?>>Airport Meet</option>
                            <option value="Meeting 1 (Initial Meeting)" <?php
                                if ('Meeting 1 (Initial Meeting)'== $pre_data['meeting_type']) {
                                    echo 'selected';
                                }
                                ?>>Meeting 1 (Initial Meeting)</option>
                            <option value="Meeting 3 (Mid Point Meeting)" <?php
                                if ('Meeting 3 (Mid Point Meeting)'== $pre_data['meeting_type']) {
                                    echo 'selected';
                                }
                                ?>>Meeting 3 (Mid Point Meeting)</option>
                            <option value="Final Meeting / LOC Payment" <?php
                                if ('Final Meeting / LOC Payment'== $pre_data['meeting_type']) {
                                    echo 'selected';
                                }
                                ?>>Final Meeting / LOC Payment</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Completion Date of Meeting</label>
                        <div class="input-group">
                            <input id="birthDate" type="text" class="form-control" name="complete_date" value="<?php echo $pre_data['complete_date'] && $pre_data['complete_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['complete_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('birthDate');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Meeting Note</label>
                        <textarea class="form-control autoHeight" name="meeting_note"><?php echo $pre_data['meeting_note'] ? $pre_data['meeting_note'] : ''; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="panel-footer tar">
                <a href="<?php echo url('admin/dev_errin/manage_meetings') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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
<script type="text/javascript">
    init.push(function () {
        $("select[name='customer_id']").change(function () {
            $('#availableCases').html('');
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    data: {
                        'customer_id': stateID,
                        'ajax_type': 'selectCase'
                    },
                    success: function (data) {
                        $('#availableCases').html(data);
                        $('#availableCases').trigger('change');
                    }
                });
            }
        }).change();
        $("select[name='case_id']").change(function () {
            $('#generatedTitle').val('');
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    data: {
                        'case_id': stateID,
                        'ajax_type': 'selectTitle'
                    },
                    success: function (data) {
                        $('#generatedTitle').val(data);
                    }
                });
            }
        }).change();
    });
</script>