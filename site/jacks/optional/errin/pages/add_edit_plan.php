<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_plan', 'edit_plan')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit','action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_plans(array('plan_id' => $edit, 'single' => true));
    if (!$pre_data) {
        add_notification('Invalid plan, no data found.', 'error');
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
        $ret = $devdb->get_row($sql)['case_number'] . ' Reintegration Plan';
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

    $ret = $this->add_edit_plan($data);

    if ($ret['success']) {
        $msg = "Information of reintegration plan has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_errin/manage_plans'));
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
    <h1>New Reintegration Plan</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'icon' => 'icon_list',
                'text' => 'All Reintegration Plan Management',
                'title' => 'Manage Reintegration Plans',
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
                        <input readonly id="generatedTitle" class="form-control" type="text" name="reintegration_title" value="<?php echo $pre_data['reintegration_title'] ? $pre_data['reintegration_title'] : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Immediate Services Delivered Date</label>
                        <div class="input-group">
                            <input id="createDate" type="text" class="form-control" name="delivered_date" value="<?php echo $pre_data['delivered_date'] && $pre_data['delivered_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['delivered_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('createDate');
                            });
                        </script>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="reintegration_status">
                            <option value="">Select One</option>
                            <option value="GOC Processing" <?php
                                if ('GOC Processing' == $pre_data['reintegration_status']) {
                                    echo 'selected';
                                }
                                ?>>GOC Processing</option>
                            <option value="Awaiting EPI" <?php
                                if ('Awaiting EPI' == $pre_data['reintegration_status']) {
                                    echo 'selected';
                                }
                                ?>>Awaiting EPI</option>
                            <option value="Signature Required" <?php
                                if ('Signature Required' == $pre_data['reintegration_status']) {
                                    echo 'selected';
                                }
                                ?>>Signature Required</option>
                            <option value="Authorised" <?php
                                if ('Authorised' == $pre_data['reintegration_status']) {
                                    echo 'selected';
                                }
                                ?>>Authorised</option>
                            <option value="LOC To Update" <?php
                                if ('LOC To Update' == $pre_data['reintegration_status']) {
                                    echo 'selected';
                                }
                                ?>>LOC To Update</option>
                            <option value="GOC To Review" <?php
                                if ('GOC To Review' == $pre_data['reintegration_status']) {
                                    echo 'selected';
                                }
                                ?>>GOC To Review</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Services to be delivered</label>
                        <select class="form-control" name="delivered_service">
                            <option value="">Select One</option>
                            <option value="Taxi Driver" <?php
                                if ('Taxi Driver' == $pre_data['delivered_service']) {
                                    echo 'selected';
                                }
                                ?>>Taxi Driver</option>
                            <option value="Small Business" <?php
                                if ('Small Business' == $pre_data['delivered_service']) {
                                    echo 'selected';
                                }
                                ?>>Small Business</option>
                            <option value="Farmer" <?php
                                if ('Farmer' == $pre_data['delivered_service']) {
                                    echo 'selected';
                                }
                                ?>>Farmer</option>
                            <option value="Accommodation" <?php
                                if ('Accommodation' == $pre_data['delivered_service']) {
                                    echo 'selected';
                                }
                                ?>>Accommodation</option>
                            <option value="Medical Treatment" <?php
                                if ('Medical Treatment' == $pre_data['delivered_service']) {
                                    echo 'selected';
                                }
                                ?>>Medical Treatment</option>
                            <option value="Training" <?php
                                if ('Training' == $pre_data['delivered_service']) {
                                    echo 'selected';
                                }
                                ?>>Training</option>
                            <option value="Trades Professional" <?php
                                if ('Trades Professional' == $pre_data['delivered_service']) {
                                    echo 'selected';
                                }
                                ?>>Trades Professional</option>
                            <option value="Job Counselling" <?php
                                if ('Job Counselling' == $pre_data['delivered_service']) {
                                    echo 'selected';
                                }
                                ?>>Job Counselling</option>
                            <option value="Vocational Training" <?php
                                if ('Vocational Training' == $pre_data['delivered_service']) {
                                    echo 'selected';
                                }
                                ?>>Vocational Training</option>
                            <option value="Medical Assistance" <?php
                                if ('Medical Assistance' == $pre_data['delivered_service']) {
                                    echo 'selected';
                                }
                                ?>>Medical Assistance</option>
                            <option value="UAM - Specialist Service" <?php
                                if ('UAM - Specialist Service' == $pre_data['delivered_service']) {
                                    echo 'selected';
                                }
                                ?>>UAM - Specialist Service</option>
                            <option value="Other Reintegration Service" <?php
                                if ('Other Reintegration Service' == $pre_data['delivered_service']) {
                                    echo 'selected';
                                }
                                ?>>Other Reintegration Service</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Immediate reintegration needs</label>
                        <select class="form-control" name="immediate_needs">
                            <option value="">Select One</option>
                            <option value="Airport Pick Up" <?php
                                if ('Airport Pick Up'== $pre_data['immediate_needs']) {
                                    echo 'selected';
                                }
                                ?>>Airport Pick Up</option>
                            <option value="Onward Transportation" <?php
                                if ('Onward Transportation'== $pre_data['immediate_needs']) {
                                    echo 'selected';
                                }
                                ?>>Onward Transportation</option>
                            <option value="Emergency Housing" <?php
                                if ('Emergency Housing'== $pre_data['immediate_needs']) {
                                    echo 'selected';
                                }
                                ?>>Emergency Housing</option>
                            <option value="Temporary Accommodation" <?php
                                if ('Temporary Accommodation'== $pre_data['immediate_needs']) {
                                    echo 'selected';
                                }
                                ?>>Temporary Accommodation</option>
                            <option value="Other Short Term Needs" <?php
                                if ('Other Short Term Needs'== $pre_data['immediate_needs']) {
                                    echo 'selected';
                                }
                                ?>>Other Short Term Needs</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Summary of Reintegration</label>
                        <textarea class="form-control autoHeight" name="reintegration_summary"><?php echo $pre_data['reintegration_summary'] ? $pre_data['reintegration_summary'] : ''; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="panel-footer tar">
                <a href="<?php echo url('admin/dev_errin/manage_plans') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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