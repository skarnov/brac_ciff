<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

$courses = jack_obj('dev_course_management');
$all_courses = $courses->get_courses(array('single' => false));

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_batch_schedules(array('batch_schedule_id' => $edit, 'single' => true));
    if (!$pre_data) {
        add_notification('Invalid batch schedule, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;

$args = array(
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_batch_schedule_id',
        'order' => 'DESC'
    ),
);

$branchManager = jack_obj('dev_branch_management');
$branches = $branchManager->get_branches(array('select_fields' => array('branches.pk_branch_id', 'branches.branch_name'), 'data_only' => true));
$batch_schedules = $this->get_batch_schedules($args);

$pagination = pagination($batch_schedules['total'], $per_page_items, $start);

$filterString = array();
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Sub-District: ' . $filter_sub_district;

if ($_POST['ajax_type']) {
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
    if (!checkPermission($edit, has_permission('add_batch_schedule'), has_permission('edit_batch_schedule'))) {
        add_notification('You don\'t have enough permission.', 'error');
        header('Location:' . build_url(NULL, array('edit')));
        exit();
    }
    
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_batch_schedule($data);

    if ($ret['success']) {
        $msg = "Information of batch schedule has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_batch_schedule/manage_batch_schedules'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

doAction('render_start');
?>
<div class="page-header">
    <h1>All Batch Schedules</h1>
</div>
<?php
ob_start();
?>
<div class="form-group col-sm-2">
    <label>Division</label>
    <div class="select2-primary">
        <select class="form-control" id="filter_division" name="division" data-selected="<?php echo $filter_division ?>"></select>
    </div>
</div>
<div class="form-group col-sm-2">
    <label>District</label>
    <div class="select2-success">
        <select class="form-control" id="filter_district" name="district" data-selected="<?php echo $filter_district; ?>"></select>
    </div>
</div>
<div class="form-group col-sm-2">
    <label>Sub-District</label>
    <div class="select2-success">
        <select class="form-control" id="filter_sub_district" name="sub_district" data-selected="<?php echo $filter_sub_district; ?>"></select>
    </div>
</div>
<?php
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="row">
    <div class="col-md-4">
        <form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
            <div class="panel">
                <div class="panel-body">
                    <div class="form-group">
                        <label>Branch</label>
                        <select required id="branchId" class="form-control" name="branch_id">
                            <option value="">Select One</option>
                            <?php
                            foreach ($branches['data'] as $i => $v) {
                                ?>
                                <option value="<?php echo $v['pk_branch_id'] ?>" <?php if ($v['pk_branch_id'] == $pre_data['fk_branch_id']) echo 'selected' ?>><?php echo $v['branch_name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
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
                        <label>Target Date</label>
                        <div class="input-group">
                            <input id="createDate" type="text" class="form-control" name="target_date" value="<?php echo $pre_data['target_date'] && $pre_data['target_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['target_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('createDate');
                            });
                        </script>
                    </div>
                    <table class="table table-condensed">
                        <thead>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th class="tar">...</th>
                        </thead>
                        <tbody class="day_time">

                        </tbody>
                    </table>
                    <a href="javascript:" id="newSchedule" class="btn btn-success btn-sm">Add Another Day</a>
                </div>
                <div class="panel-footer tar">
                    <a href="<?php echo url('admin/dev_batch_schedule/manage_batch_schedules') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
                    <?php
                    echo submitButtonGenerator(array(
                        'action' => $edit ? 'update' : 'update',
                        'size' => '',
                        'id' => 'submit',
                        'title' => $edit ? 'Update The Batch' : 'Save The Batch Schedule',
                        'icon' => $edit ? 'icon_update' : 'icon_save',
                        'text' => $edit ? 'Update' : 'Save'
                    ))
                    ?>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-8">
        <div class="table-primary table-responsive">
            <?php if ($filterString): ?>
                <div class="table-header">
                    Filtered With: <?php echo implode(', ', $filterString) ?>
                </div>
            <?php endif; ?>
            <div class="table-header">
                <?php echo searchResultText($batch_schedules['total'], $start, $per_page_items, count(['data']), 'batches') ?>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Course Name</th>
                        <th>Batch Name</th>
                        <th>Schedule</th>
                        <th class="tar action_column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($batch_schedules['data'] as $i => $batch) {
                        ?>
                        <tr>
                            <td><?php echo $batch['branch_name']; ?></td>
                            <td><?php echo $batch['course_name']; ?></td>
                            <td><?php echo $batch['batch_name']; ?></td>
                            <td>
                                <table class="table table-bordered">
                                    <thead>
                                    <th>Day</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $data = json_decode($batch['day_time_set']);
                                        foreach ($data as $value) {
                                            ?>
                                            <tr>
                                                <td><?php echo $value->schedule_day ?></td>
                                                <td><?php echo $value->start_time ?></td>
                                                <td><?php echo $value->end_time ?></td> 
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </td>
                            <td class="tar action_column">
                                <?php if (has_permission('edit_batch')): ?>
                                    <div class="btn-toolbar">
                                        <?php
                                        echo linkButtonGenerator(array(
                                            'href' => build_url(array('action' => 'manage_batches', 'edit' => $batch['pk_batch_schedule_id'])),
                                            'action' => 'edit',
                                            'icon' => 'icon_edit',
                                            'text' => 'Edit',
                                            'title' => 'Edit Batch',
                                        ));
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="table-footer oh">
                <?php echo $pagination ?>
            </div>
        </div>
    </div>
</div>
<script>
    var totalDayTime = 0;
    var oldDayTime = <?php echo $pre_data && $pre_data['day_time_set'] ? $pre_data['day_time_set'] : 'false' ?>;
    var BD_LOCATIONS = <?php echo getBDLocationJson() ?>;

    init.push(function () {
        new bd_new_location_selector({
            'division': $('#filter_division'),
            'district': $('#filter_district'),
            'sub_district': $('#filter_sub_district')
        });
        
        function addNewDayTimeRow(data) {
            if (typeof data === 'undefined')
                data = null;
            var _html = '\
                        <tr class="">\
                            <td style="min-width: 135px">\
                                <select class="form-control" name="day_time_set[' + totalDayTime + '][schedule_day]">\
                                    <option value="Sunday" ' + (data && data['schedule_day'] == 'Sunday' ? 'selected' : '') + '>Sunday</option>\
                                    <option value="Monday" ' + (data && data['schedule_day'] == 'Monday' ? 'selected' : '') + '>Monday</option>\
                                    <option value="Tuesday" ' + (data && data['schedule_day'] == 'Tuesday' ? 'selected' : '') + '>Tuesday</option>\
                                    <option value="Wednesday" ' + (data && data['schedule_day'] == 'Wednesday' ? 'selected' : '') + '>Wednesday</option>\
                                    <option value="Thursday" ' + (data && data['schedule_day'] == 'Thursday' ? 'selected' : '') + '>Thursday</option>\
                                    <option value="Friday" ' + (data && data['schedule_day'] == 'Friday' ? 'selected' : '') + '>Friday</option>\
                                    <option value="Saturday" ' + (data && data['schedule_day'] == 'Saturday' ? 'selected' : '') + '>Saturday</option>\
                                </select>\
                            </td>\
                            <td class="">\
                                <div class="input-group">\
                                    <input id="startTime_' + totalDayTime + '" type="text" class="form-control" name="day_time_set[' + totalDayTime + '][start_time]" value="' + (data && data['start_time'] ? data['start_time'] : '') + '">\
                                </div>\
                            </td>\
                            <td class="">\
                                <div class="input-group">\
                                    <input id="endTime_' + totalDayTime + '" type="text" class="form-control" name="day_time_set[' + totalDayTime + '][end_time]" value="' + (data && data['end_time'] ? data['end_time'] : '') + '">\
                                </div>\
                            </td>\
                            <td class="tar vam">\
                                <a href="javascript:" class="remove_row btn btn-xs btn-danger" ><i class="fa fa-trash"></i></a>\
                            </td>\
                        </tr>\
                        ';

            $('.day_time').append(_html);
            _timepicker('startTime_' + totalDayTime, {'add_calendar_icon': false, 'add_clear_icon': false});
            _timepicker('endTime_' + totalDayTime, {'add_calendar_icon': false, 'add_clear_icon': false});
            ++totalDayTime;
        }

        $("#newSchedule").click(function (e) {
            addNewDayTimeRow();
        });
        $(document).on('click', '.remove_row', function () {
            $(this).closest('tr').remove();
        });

        if (oldDayTime) {
            for (var i in oldDayTime) {
                addNewDayTimeRow(oldDayTime[i]);
            }
        }

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