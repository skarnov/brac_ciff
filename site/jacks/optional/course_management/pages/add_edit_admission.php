<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_course', 'edit_course')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit','action')));
    exit();
}

$tagger = jack_obj('dev_tag_management');
$customers = jack_obj('dev_customer_management');

$branchManager = jack_obj('dev_branch_management');
$branches = $branchManager->get_branches(array('select_fields' => array('branches.pk_branch_id', 'branches.branch_name'), 'data_only' => true));
$all_customers = $customers->get_customers(array('select_fields' => array('pk_customer_id', 'full_name', 'customer_id'), 'data_only' => true));
$all_courses = $this->get_courses(array('select_fields' => array('pk_course_id', 'course_name'), 'single' => false));

if ($edit) {
    $pre_data = $this->get_admissions(array('id' => $edit, 'single' => true));
    if (!$pre_data) {
        add_notification('Invalid course admission, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
    $refillPreviousStudents = array();
    $refillPreviousStudents = $this->get_customer($pre_data['fk_customer_id']);
}

if ($_POST['ajax_type']) {
    
    if ($_POST['ajax_type'] == 'selectStaff') {
        $staffs = jack_obj('dev_staff_management');
        $all_staffs = $staffs->get_staffs(array('branch' => $_POST['branch_id']));
        foreach ($all_staffs['data'] as $staff) {
            if ($staff['pk_user_id'] == $pre_data['fk_staff_id']) {
                echo "<option value='" . $staff['pk_user_id'] . "'>" . $staff['user_fullname'] . "</option>";
            } else {
                echo "<option value='" . $staff['pk_user_id'] . "'>" . $staff['user_fullname'] . "</option>";
            }
        }
    }
    
    if ($_POST['ajax_type'] == 'selectBatch') {
        $sql = "SELECT pk_batch_id, batch_name FROM dev_batches WHERE fk_branch_id = '" . $_POST['branch_id'] . "' AND fk_course_id = '" . $_POST['id'] . "'";
        $ret = $devdb->get_results($sql);

        foreach ($ret as $data) {
            if ($data['pk_batch_id'] == $pre_data['pk_batch_id']) {
                echo "<option value='" . $data['batch_name'] . "' selected>" . $data['batch_name'] . "</option>";
            } else {
                echo "<option value='" . $data['batch_name'] . "'>" . $data['batch_name'] . "</option>";
            }
        }
    }
    exit();
}

if ($_POST) {
    $data = array(
        'required' => array(
            'course_id' => 'Course Name',
            'batch_name' => 'Batch Name',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $data['sale_title'] = 'Course Sale';
    $ret = $this->add_edit_admission($data);

    if ($ret['success']) {
        $msg = 'Admission Success!';
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_course_management/course_admission'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

doAction('render_start');
?>
<div class="page-header">
    <h1>Course Admission</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => 'course_admission',
                'action' => 'list',
                'text' => 'All Course Admissions',
                'title' => 'Manage All Course Admissions',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<form class="preventDoubleClick" name="course_admission_form" id="admission_form" onsubmit="return validate_invoice(this);" method="post" action="">
    <div class="row">
        <div class="col-sm-9">
            <div class="table-primary">
                <table class="table table-bordered table-stripped table-condensed">
                    <thead>
                        <tr>
                            <th>Customers</th>
                        </tr>
                    </thead>
                    <tbody class="the_students">

                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-dark panel-info">
                <div class="panel-heading">
                    <span class="panel-title">Course Info</span>
                </div>
                <div class="panel-body p5">
                    <div class="form-group">
                        <label>Branch</label>
                        <select required id="branchId" class="form-control" name="branch_id">
                            <option value="">Select One</option>
                            <?php
                            foreach ($branches['data'] as $i => $v) {
                                ?>
                                <option value="<?php echo $v['pk_branch_id'] ?>" <?php if ($v['pk_branch_id'] == $pre_data['pk_branch_id']) echo 'selected' ?>><?php echo $v['branch_name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <?php if(!$edit): ?>
                    <div class="form-group">
                        <label>Incentive Staff</label>
                        <select required class="form-control" id="availableStaffs" name="staff_id">

                        </select>
                    </div>
                    <?php endif ?>
                    <div class="form-group">
                        <label>Course</label>
                        <select required class="form-control" name="course_id">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_courses['data'] as $value) :
                                ?>
                                <option value="<?php echo $value['pk_course_id'] ?>" <?php
                                if ($value['pk_course_id'] == $pre_data['fk_course_id']) {
                                    echo 'selected';
                                }
                                ?>><?php echo $value['course_name'] ?></option>
                                    <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Batch</label>
                        <select required class="form-control" id="availableBatches" name="batch_name">
                            <?php if ($edit): ?>
                                <option value="<?php echo $pre_data['batch_name'] ? $pre_data['batch_name'] : ''; ?>"><?php echo $pre_data['batch_name'] ? $pre_data['batch_name'] : ''; ?></option>
                            <?php endif ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="return_btn form-control btn btn-success">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    var returns = [];
    var edit = <?php echo $is_update ? $is_update : 0 ?>;
    var count_empty_row = <?php echo $content['invoice_detail'] ? count($content['invoice_detail']) : 0 ?>;
    var products = <?php echo json_encode($products['data']) ?>;
    var pre_products = <?php echo $the_invoice_detail['data'] ? json_encode($the_invoice_detail['data']) : '{}' ?>;

    init.push(function () {
        function fix_serial_number() {
            var itemCount = 1;
            $('.serialNumber').each(function (i, e) {
                $(e).html(itemCount++);
            });
        }

        function get_empty_row($data) {
            count_empty_row++;
            $pointer = count_empty_row;

            var admitUser = '<div class="form-group">\n\
                        <div id="previous_locations_autocomplete"></div>\n\
                    </div>';

            var empty_row = '<tr class="each_row empty_row">\n\
                                <td style="width: 350px;">\n\
                                ' + admitUser + '</td>\
                            </tr>';
            return empty_row;
        }

        if (!$('.the_students .empty_row').length && !edit) {
            add_empty_row(null);
        }

        function add_empty_row($data) {
            $('.the_students').append(get_empty_row($data));
            bind_remove_row_event();
            $(".adv_select_" + count_empty_row).select2();
            fix_serial_number();
        }
        $('.add_row').click(function () {
            add_empty_row(null);
        });

        new set_autosuggest({
            container: '#previous_locations_autocomplete',
            submit_labels: false,
            ajax_page: _root_path_ + '/api/dev_course_management/get_tags_autocomplete',
            single: <?php if($edit) echo 'true'; else echo 'false' ?>,
            parameters: {'tag_group': 'migration_locations'},
                multilingual: true,
                input_field: '#input_previous_locations',
                field_name: 'fk_customer_id',
                add_new: false,
                existing_items: <?php echo to_json_object($refillPreviousStudents); ?>
        });

        function bind_remove_row_event() {
            $(document).off('click', '.remove_row').on('click', '.remove_row', function () {
                $(this).closest('.each_row').remove();
                $('.each_row').length;
                fix_serial_number();
            });
        }

        $(document).off('click', '.remove_row').on('click', '.remove_row', function () {
            $(this).closest('.each_row').remove();
            $('.each_row').length;
            fix_serial_number();
        });

        $("select[name='branch_id']").change(function () {
            $('#availableStaffs').html('');
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    data: {
                        'branch_id': stateID,
                        'ajax_type': 'selectStaff'
                    },
                    success: function (data) {
                        $('#availableStaffs').html(data);
                    }
                });
            }
        }).change();

        $("select[name='course_id']").change(function () {
            $('#availableBatches').html('');
            var stateID = $(this).val();
            var branchId = $('#branchId').val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    data: {
                        'id': stateID,
                        'branch_id': branchId,
                        'ajax_type': 'selectBatch'
                    },
                    success: function (data) {
                        $('#availableBatches').html(data);
                    }
                });
            } else {
                $('select[name="course_id"]').empty();
            }
        });
    });
</script>