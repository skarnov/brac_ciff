<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_event', 'edit_event')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$divisions = get_division();

if (isset($_POST['division_id'])) {
    $districts = get_district($_POST['division_id']);
    echo "<option value=''>Select One</option>";
    foreach ($districts as $district) :
        echo "<option id='" . $district['id'] . "' value='" . strtolower($district['name']) . "' >" . $district['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['district_id'])) {
    $subdistricts = get_subdistrict($_POST['district_id']);
    echo "<option value=''>Select One</option>";
    foreach ($subdistricts as $subdistrict) :
        echo "<option id='" . $subdistrict['id'] . "' value='" . strtolower($subdistrict['name']) . "'>" . $subdistrict['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['subdistrict_id'])) {
    $unions = get_union($_POST['subdistrict_id']);
    echo "<option value=''>Select One</option>";
    foreach ($unions as $union) :
        echo "<option id='" . $union['id'] . "' value='" . strtolower($union['name']) . "'>" . $union['name'] . "</option>";
    endforeach;
    exit;
}

$activities = jack_obj('dev_misactivity_management');
$all_activities = $activities->get_misactivities();

$all_months = $activities->get_months();

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches();

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$pre_data = array();

if ($edit) {
    $args = array(
        'select_fields' => array(
            'fk_branch_id' => 'dev_events.fk_branch_id',
            'fk_project_id' => 'dev_events.fk_project_id',
            'month' => 'dev_events.month',
            'fk_activity_id' => 'dev_events.fk_activity_id',
            'event_start_date' => 'dev_events.event_start_date',
            'event_start_time' => 'dev_events.event_start_time',
            'event_end_date' => 'dev_events.event_end_date',
            'event_end_time' => 'dev_events.event_end_time',
            'event_division' => 'dev_events.event_division',
            'event_district' => 'dev_events.event_district',
            'event_upazila' => 'dev_events.event_upazila',
            'event_union' => 'dev_events.event_union',
            'event_village' => 'dev_events.event_village',
            'event_ward' => 'dev_events.event_ward',
            'event_location' => 'dev_events.event_location',
            'participant_boy' => 'dev_events.participant_boy',
            'participant_girl' => 'dev_events.participant_girl',
            'participant_male' => 'dev_events.participant_male',
            'participant_female' => 'dev_events.participant_female',
            'preparatory_work' => 'dev_events.preparatory_work',
            'time_management' => 'dev_events.time_management',
            'participants_attention' => 'dev_events.participants_attention',
            'logistical_arrangements' => 'dev_events.logistical_arrangements',
            'relevancy_delivery' => 'dev_events.relevancy_delivery',
            'participants_feedback' => 'dev_events.participants_feedback',
            'event_note' => 'dev_events.event_note',
        ),
        'id' => $edit,
        'single' => true
    );

    $pre_data = $this->get_events($args);

    if (!$pre_data) {
        add_notification('Invalid event, no data found.', 'error');
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
    $data['edit'] = $edit;

    $ret = $this->add_edit_event($data);

    if ($ret) {
        $msg = "Event has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_event_management/manage_events?action=add_edit_event&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_event_management/manage_events'));
        }
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

doAction('render_start');

ob_start();
?>
<style type="text/css">
    .removeReadOnly {
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'Add ' ?> Event </h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Events',
                'title' => 'Manage Events',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
    <div class="panel" id="fullForm" style="">
        <div class="panel-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Branch</label>
                    <div class="select2-primary">
                        <select class="form-control" name="branch_id" required>
                            <option value="">Select One</option>
                            <?php foreach ($all_branches['data'] as $branch) : ?>
                                <option value="<?php echo $branch['pk_branch_id'] ?>" <?php echo ($branch['pk_branch_id'] == $pre_data['fk_branch_id']) ? 'selected' : '' ?>><?php echo $branch['branch_name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Project</label>
                    <div class="select2-primary">
                        <select class="form-control" name="project_id" required>
                            <option value="">Select One</option>
                            <?php foreach ($all_projects['data'] as $project) : ?>
                                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $pre_data['fk_project_id']) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Month</label>
                    <div class="select2-primary">
                        <select class="form-control" name="month" required>
                            <option value="">Select One</option>
                            <?php foreach ($all_months as $i => $value) :
                                ?>
                                <option value="<?php echo $i ?>" <?php echo ($i == $pre_data['month']) ? 'selected' : '' ?>><?php echo $value ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Select Activity</label>
                    <select class="form-control" name="activity_id" required>
                        <option>Select One</option>
                        <?php foreach ($all_activities['data'] as $activity) : ?>
                            <option value="<?php echo $activity['pk_activity_id'] ?>" <?php echo ($activity['pk_activity_id'] == $pre_data['fk_activity_id']) ? 'selected' : '' ?>><?php echo $activity['activity_name'] ?></option>   
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Event Start Date</label>
                    <div class="input-group">
                        <input id="Datefirstmeeting" type="text" class="form-control" name="event_start_date" value="<?php echo $pre_data['event_start_date'] && $pre_data['event_start_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['event_start_date'])) : date('d-m-Y'); ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('Datefirstmeeting');
                        });
                    </script>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group ">
                    <label>Event Start Time</label>
                    <div class="input-group date">
                        <input type="text" name="event_start_time"  value="<?php echo $pre_data['event_start_time'] && $pre_data['event_start_time'] != '00-00-00' ? date('H:i:s', strtotime($pre_data['event_start_time'])) : date('H:i:s'); ?>"class="form-control" id="bs-timepicker-component"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
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
                        $('#bs-timepicker-component').timepicker(options2);
                    });
                </script>
            </div>
            <div class="col-md-6"></div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Event End Date</label>
                    <div class="input-group">
                        <input id="end_date" type="text" class="form-control" name="event_end_date" value="<?php echo $pre_data['event_end_date'] && $pre_data['event_end_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['event_end_date'])) : date('d-m-Y'); ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('end_date');
                        });
                    </script>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group ">
                    <label>Event End Time</label>
                    <div class="input-group date">
                        <input id="end_time" type="text" name="event_end_time" value="<?php echo $pre_data['event_end_time'] && $pre_data['event_end_time'] != '00-00-00' ? date('H:i:s', strtotime($pre_data['event_end_time'])) : date('H:i:s'); ?>"class="form-control" id="bs-timepicker-component"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
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
                        $('#end_time').timepicker(options2);
                    });
                </script>
            </div>
            <div class="col-md-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Section 1: Basic Geographical Information</legend>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label input-label">Division</label>
                            <div class="select2-primary">
                                <select class="form-control division" name="event_division" style="text-transform: capitalize">
                                    <?php if ($pre_data['event_division']) : ?>
                                        <option value="<?php echo strtolower($pre_data['event_division']) ?>"><?php echo $pre_data['event_division'] ?></option>
                                    <?php else: ?>
                                        <option>Select One</option>
                                    <?php endif ?>
                                    <?php foreach ($divisions as $division) : ?>
                                        <option id="<?php echo $division['id'] ?>" value="<?php echo strtolower($division['name']) ?>" <?php echo $pre_data && $pre_data['event_division'] == $division['name'] ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label input-label">District</label>
                            <div class="select2-primary">
                                <select class="form-control district" name="event_district" style="text-transform: capitalize" id="districtList">
                                    <?php if ($pre_data['event_district']) : ?>
                                        <option value="<?php echo $pre_data['event_district'] ?>"><?php echo $pre_data['event_district'] ?></option>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label input-label">Upazila</label>
                            <div class="select2-primary">
                                <select class="form-control subdistrict" name="event_upazila" style="text-transform: capitalize" id="subdistrictList">
                                    <?php if ($pre_data['event_upazila']) : ?>
                                        <option value="<?php echo $pre_data['event_upazila'] ?>"><?php echo $pre_data['event_upazila'] ?></option>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label input-label">Union</label>
                            <div class="select2-primary">
                                <select class="form-control union" name="event_union" style="text-transform: capitalize" id="unionList">
                                    <?php if ($pre_data['event_union']) : ?>
                                        <option value="<?php echo $pre_data['event_union'] ?>"><?php echo $pre_data['event_union'] ?></option>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            $('.division').change(function () {
                                var divisionId = $(this).find('option:selected').attr('id');
                                $.ajax({
                                    type: 'POST',
                                    data: {division_id: divisionId},
                                    success: function (result) {
                                        $('#districtList').html(result);
                                    }}
                                );
                            });
                            $('.district').change(function () {
                                var districtId = $(this).find('option:selected').attr('id');
                                $.ajax({
                                    type: 'POST',
                                    data: {district_id: districtId},
                                    success: function (result) {
                                        $('#subdistrictList').html(result);
                                    }}
                                );
                            });
                            $('.subdistrict').change(function () {
                                var subdistrictId = $(this).find('option:selected').attr('id');
                                $.ajax({
                                    type: 'POST',
                                    data: {subdistrict_id: subdistrictId},
                                    success: function (result) {
                                        $('#unionList').html(result);
                                    }}
                                );
                            });
                        });
                    </script>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Village</label>
                            <input class="form-control" type="text" name="event_village" value="<?php echo $pre_data['event_village'] ? $pre_data['event_village'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Ward</label>
                            <input class="form-control" type="text" name="event_ward" value="<?php echo $pre_data['event_ward'] ? $pre_data['event_ward'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Exact Location (Para, Bazar or School)</label>
                            <textarea class="form-control" type="text" name="event_location"><?php echo $pre_data['event_location'] ? $pre_data['event_location'] : ''; ?></textarea>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Section 2: Number of participants in the Event</legend>
                    <div class="col-md-6">
                        <label class="control-label input-label">Below 18</label>
                        <div class="form-group">
                            <input class="form-control" type="number" name="participant_boy" value="<?php echo $pre_data['participant_boy'] ? $pre_data['participant_boy'] : ''; ?>" placeholder="Boy (<18)"><br />
                            <input class="form-control" type="number" name="participant_girl" value="<?php echo $pre_data['participant_girl'] ? $pre_data['participant_girl'] : ''; ?>" placeholder="Girl (<18)">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label input-label">Above 18</label>
                        <div class="form-group">
                            <input class="form-control" type="number" name="participant_male" value="<?php echo $pre_data['participant_male'] ? $pre_data['participant_male'] : ''; ?>" placeholder="Men (>=18)"><br />
                            <input class="form-control" type="number" name="participant_female" value="<?php echo $pre_data['participant_female'] ? $pre_data['participant_female'] : ''; ?>" placeholder="Women (>=18)">
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Section 3: Show Observation Checklist</legend>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Preparatory work for the event was</label>
                            <div class="select2-success">
                                <select class="form-control" required name="preparatory_work" >
                                    <option value="">Select One</option>
                                    <option value="5" <?php echo $pre_data && $pre_data['preparatory_work'] == '5' ? 'selected' : '' ?>>Excellent</option>
                                    <option value="4" <?php echo $pre_data && $pre_data['preparatory_work'] == '4' ? 'selected' : '' ?>>Good</option>
                                    <option value="3" <?php echo $pre_data && $pre_data['preparatory_work'] == '3' ? 'selected' : '' ?>>Neutral</option>
                                    <option value="2" <?php echo $pre_data && $pre_data['preparatory_work'] == '2' ? 'selected' : '' ?>>Need To Improved</option>
                                    <option value="1" <?php echo $pre_data && $pre_data['preparatory_work'] == '1' ? 'selected' : '' ?>>Not Observed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Time management of the event was</label>
                            <div class="select2-success">
                                <select class="form-control" required name="time_management" >
                                    <option value="">Select One</option>
                                    <option value="5" <?php echo $pre_data && $pre_data['time_management'] == '5' ? 'selected' : '' ?>>Excellent</option>
                                    <option value="4" <?php echo $pre_data && $pre_data['time_management'] == '4' ? 'selected' : '' ?>>Good</option>
                                    <option value="3" <?php echo $pre_data && $pre_data['time_management'] == '3' ? 'selected' : '' ?>>Neutral</option>
                                    <option value="2" <?php echo $pre_data && $pre_data['time_management'] == '2' ? 'selected' : '' ?>>Need To Improved</option>
                                    <option value="1" <?php echo $pre_data && $pre_data['time_management'] == '1' ? 'selected' : '' ?>>Not Observed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Participants attention during the event was</label>
                            <div class="select2-success">
                                <select class="form-control" required name="participants_attention" >
                                    <option value="">Select One</option>
                                    <option value="5" <?php echo $pre_data && $pre_data['participants_attention'] == '5' ? 'selected' : '' ?>>Excellent</option>
                                    <option value="4" <?php echo $pre_data && $pre_data['participants_attention'] == '4' ? 'selected' : '' ?>>Good</option>
                                    <option value="3" <?php echo $pre_data && $pre_data['participants_attention'] == '3' ? 'selected' : '' ?>>Neutral</option>
                                    <option value="2" <?php echo $pre_data && $pre_data['participants_attention'] == '2' ? 'selected' : '' ?>>Need To Improved</option>
                                    <option value="1" <?php echo $pre_data && $pre_data['participants_attention'] == '1' ? 'selected' : '' ?>>Not Observed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Logistical arrangements (e.g. stationery, sitting arrangements, sound quality others) were</label>
                            <div class="select2-success">
                                <select class="form-control" required name="logistical_arrangements" >
                                    <option value="">Select One</option>
                                    <option value="5" <?php echo $pre_data && $pre_data['logistical_arrangements'] == '5' ? 'selected' : '' ?>>Excellent</option>
                                    <option value="4" <?php echo $pre_data && $pre_data['logistical_arrangements'] == '4' ? 'selected' : '' ?>>Good</option>
                                    <option value="3" <?php echo $pre_data && $pre_data['logistical_arrangements'] == '3' ? 'selected' : '' ?>>Neutral</option>
                                    <option value="2" <?php echo $pre_data && $pre_data['logistical_arrangements'] == '2' ? 'selected' : '' ?>>Need To Improved</option>
                                    <option value="1" <?php echo $pre_data && $pre_data['logistical_arrangements'] == '1' ? 'selected' : '' ?>>Not Observed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Relevancy of delivery of messages from the event was</label>
                            <div class="select2-success">
                                <select class="form-control" required name="relevancy_delivery" >
                                    <option value="">Select One</option>
                                    <option value="5" <?php echo $pre_data && $pre_data['participants_attention'] == '5' ? 'selected' : '' ?>>Excellent</option>
                                    <option value="4" <?php echo $pre_data && $pre_data['participants_attention'] == '4' ? 'selected' : '' ?>>Good</option>
                                    <option value="3" <?php echo $pre_data && $pre_data['participants_attention'] == '3' ? 'selected' : '' ?>>Neutral</option>
                                    <option value="2" <?php echo $pre_data && $pre_data['participants_attention'] == '2' ? 'selected' : '' ?>>Need To Improved</option>
                                    <option value="1" <?php echo $pre_data && $pre_data['participants_attention'] == '1' ? 'selected' : '' ?>>Not Observed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Participants feedback on the overall event was</label>
                            <div class="select2-success">
                                <select class="form-control" required name="participants_feedback" >
                                    <option value="">Select One</option>
                                    <option value="5" <?php echo $pre_data && $pre_data['participants_feedback'] == '5' ? 'selected' : '' ?>>Excellent</option>
                                    <option value="4" <?php echo $pre_data && $pre_data['participants_feedback'] == '4' ? 'selected' : '' ?>>Good</option>
                                    <option value="3" <?php echo $pre_data && $pre_data['participants_feedback'] == '3' ? 'selected' : '' ?>>Neutral</option>
                                    <option value="2" <?php echo $pre_data && $pre_data['participants_feedback'] == '2' ? 'selected' : '' ?>>Need To Improved</option>
                                    <option value="1" <?php echo $pre_data && $pre_data['participants_feedback'] == '1' ? 'selected' : '' ?>>Not Observed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Event Note</label>
                            <textarea class="form-control" name="event_note"><?php echo $pre_data['event_note'] ?></textarea>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_event_management/manage_events') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
            <?php
            echo submitButtonGenerator(array(
                'action' => $edit ? 'update' : 'update',
                'size' => '',
                'id' => 'submit',
                'title' => $edit ? 'Update' : 'Save',
                'icon' => $edit ? 'icon_update' : 'icon_save',
                'text' => $edit ? 'Update' : 'Save'
            ))
            ?>
        </div>
    </div>
</form>
<script type="text/javascript">
    var BD_LOCATIONS = <?php echo getBDLocationJson(); ?>;
    init.push(function () {
        new bd_new_location_selector({
            'division': $('#filter_division'),
            'district': $('#filter_district'),
            'sub_district': $('#filter_sub_district')
        });
    });
</script>
<script type="text/javascript">
    init.push(function () {
        theForm.find('input:submit, button:submit').prop('disabled', true);
    });
</script>