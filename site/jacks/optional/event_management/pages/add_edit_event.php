<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_event', 'edit_event')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_events(array('id' => $edit, 'single' => true));

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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Event </h1>
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
            <div class="col-md-3">
                <div class="form-group">
                    <label>Select Event Type</label>
                    <select class="form-control" id="" name="event_type" >
                        <option>Select One</option>

                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Event Date</label>
                    <div class="input-group">
                        <input id="Datefirstmeeting" type="text" class="form-control" name="event_date" value="<?php echo $pre_data['event_date'] && $pre_data['event_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['event_date'])) : date('d-m-Y'); ?>">
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
            <div class="col-md-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Section 1: Basic Geographical Information</legend>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Division (*)</label>
                            <div class="select2-primary">
                                <select class="form-control" id="permanent_division" name="division" data-selected="<?php echo $pre_data['division'] ? $pre_data['division'] : '' ?>"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>District (*)</label>
                            <div class="select2-success">
                                <select class="form-control" id="permanent_district" name="district" data-selected="<?php echo $pre_data['district'] ? $pre_data['district'] : ''; ?>"></select>
                            </div>
                        </div>
                        <label class="control-label input-label">Upazila</label>
                        <div class="form-group">
                            <input class="form-control" type="text" name="upazila" value="<?php echo $pre_data['upazila'] ? $pre_data['upazila'] : ''; ?>">
                        </div>
                        <label class="control-label input-label">Union</label>
                        <div class="form-group">
                            <input class="form-control" type="text" name="event_union" value="<?php echo $pre_data['event_union'] ? $pre_data['event_union'] : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Village</label>
                            <input class="form-control" type="text" name="village" value="<?php echo $pre_data['village'] ? $pre_data['village'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Ward</label>
                            <input class="form-control" type="text" name="ward" value="<?php echo $pre_data['ward'] ? $pre_data['ward'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Exact Location (Para, bazar or school)</label>
                            <textarea class="form-control" type="text" name="location"><?php echo $pre_data['location'] ? $pre_data['location'] : ''; ?></textarea>
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
                            <input class="form-control" type="text" name="below_male" value="<?php echo $pre_data['below_male'] ? $pre_data['below_male'] : ''; ?>" placeholder="Male"><br />
                            <input class="form-control" type="text" name="below_female" value="<?php echo $pre_data['below_female'] ? $pre_data['below_female'] : ''; ?>" placeholder="Female">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label input-label">Above 18</label>
                        <div class="form-group">
                            <input class="form-control" type="text" name="above_male" value="<?php echo $pre_data['above_male'] ? $pre_data['above_male'] : ''; ?>" placeholder="Male"><br />
                            <input class="form-control" type="text" name="above_female" value="<?php echo $pre_data['above_female'] ? $pre_data['above_female'] : ''; ?>" placeholder="Female">
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
                                <select class="form-control" id="" name="preparatory_work" >
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
                                <select class="form-control" id="" name="time_management" >
                                    <option>Select One</option>
                                    <option value="5" <?php echo $pre_data && $pre_data['time_management'] == '5' ? 'selected' : '' ?>>Excellent</option>
                                    <option value="4" <?php echo $pre_data && $pre_data['time_management'] == '4' ? 'selected' : '' ?>>Good</option>
                                    <option value="3" <?php echo $pre_data && $pre_data['time_management'] == '3' ? 'selected' : '' ?>>Neutral</option>
                                    <option value="2" <?php echo $pre_data && $pre_data['time_management'] == '2' ? 'selected' : '' ?>>Need To Improved</option>
                                    <option value="1" <?php echo $pre_data && $pre_data['time_management'] == '1' ? 'selected' : '' ?>>Not Observed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Participants' attention during the event was</label>
                            <div class="select2-success">
                                <select class="form-control" id="" name="participants_attention" >
                                    <option>Select One</option>
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
                                <select class="form-control" id="" name="logistical_arrangements" >
                                    <option>Select One</option>
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
                                <select class="form-control" id="" name="relevancy_delivery" >
                                    <option>Select One</option>
                                    <option value="5" <?php echo $pre_data && $pre_data['participants_attention'] == '5' ? 'selected' : '' ?>>Excellent</option>
                                    <option value="4" <?php echo $pre_data && $pre_data['participants_attention'] == '4' ? 'selected' : '' ?>>Good</option>
                                    <option value="3" <?php echo $pre_data && $pre_data['participants_attention'] == '3' ? 'selected' : '' ?>>Neutral</option>
                                    <option value="2" <?php echo $pre_data && $pre_data['participants_attention'] == '2' ? 'selected' : '' ?>>Need To Improved</option>
                                    <option value="1" <?php echo $pre_data && $pre_data['participants_attention'] == '1' ? 'selected' : '' ?>>Not Observed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Participants' feedback on the overall event was</label>
                            <div class="select2-success">
                                <select class="form-control" id="" name="participants_feedback" >
                                    <option>Select One</option>
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
                            <label>Note</label>
                            <textarea class="form-control" type="text" name="note"><?php echo $pre_data && $pre_data['note'] ?></textarea>
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

        $(document).on('click', '.delete_single_record', function () {
            var ths = $(this);
            var thisCell = ths.closest('td');
            var logId = ths.attr('data-id');
            if (!logId)
                return false;

            show_button_overlay_working(thisCell);
            bootbox.prompt({
                title: 'Delete Record!',
                inputType: 'checkbox',
                inputOptions: [{
                        text: 'Click To Confirm Delete',
                        value: 'delete'
                    }],
                callback: function (result) {
                    if (result == 'delete') {
                        window.location.href = '?action=deleteEvent&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });


        new bd_new_location_selector({
            'division': $('#permanent_division'),
            'district': $('#permanent_district'),
            'sub_district': $('#permanent_sub_district'),
            'police_station': $('#permanent_police_station'),
            'post_office': $('#permanent_post_office'),
        });

        var theForm = $('#theForm');
        theForm.data('serialized', theForm.serialize());

        theForm.on('change input', function () {
            theForm.find('input:submit, button:submit').prop('disabled', theForm.serialize() == theForm.data('serialized'));
        });
        theForm.find('input:submit, button:submit').prop('disabled', true);
    });
</script>