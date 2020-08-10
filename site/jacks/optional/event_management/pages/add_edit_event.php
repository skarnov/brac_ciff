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
                    <select class="form-control" id="" name="" >
                        <option>Select One</option>
                        <option>Excellent</option>
                        <option>Good</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Select Branch</label>
                    <select class="form-control" id="" name="" >
                        <option>Select One</option>
                        <option>Excellent</option>
                        <option>Good</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Event Date</label>
                    <div class="input-group">
                        <input id="Datefirstmeeting" type="text" class="form-control" name="first_meeting" value="<?php echo $pre_data['first_meeting'] && $pre_data['first_meeting'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['first_meeting'])) : date('d-m-Y'); ?>">
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
                        <input type="text" name="family_entry_time"  value="<?php echo $pre_data['family_entry_time'] && $pre_data['family_entry_time'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['family_entry_time'])) : date('d-m-Y'); ?>"class="form-control" id="bs-timepicker-component"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
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
                            <label>District</label>
                            <div class="select2-success">
                                <select class="form-control" id="division" name="permanent_district" data-selected="<?php echo $pre_data['permanent_district'] ? $pre_data['permanent_district'] : ''; ?>"></select>
                            </div>
                        </div>
                        <label class="control-label input-label">Upazila</label>
                        <div class="form-group">
                            <input class="form-control" type="text" name="" value="">
                        </div>
                        <label class="control-label input-label">Union</label>
                        <div class="form-group">
                            <input class="form-control" type="text" name="" value="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Village</label>
                            <input class="form-control" type="text" name="" value="">
                        </div>
                        <div class="form-group">
                            <label>Ward</label>
                            <input class="form-control" type="text" name="" value="">
                        </div>
                        <div class="form-group">
                            <label>Exact Location (Para, bazar or school)</label>
                            <textarea class="form-control" type="text" name="" value=""></textarea>
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
                            <input class="form-control" type="text" name="" value="" placeholder="Male"><br />
                            <input class="form-control" type="text" name="" value="" placeholder="Female">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label input-label">Above 18</label>
                        <div class="form-group">
                            <input class="form-control" type="text" name="" value="" placeholder="Male"><br />
                            <input class="form-control" type="text" name="" value="" placeholder="Female">
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
                                <select class="form-control" id="" name="" >
                                    <option value="">Select One</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Good">Good</option>
                                    <option value="Neutral">Neutral</option>
                                    <option value="Need To Improved">Need To Improved</option>
                                    <option value="Not Observed">Not Observed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Time management of the event was</label>
                            <div class="select2-success">
                                <select class="form-control" id="" name="" >
                                    <option>Select One</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Good">Good</option>
                                    <option value="Neutral">Neutral</option>
                                    <option value="Need To Improved">Need To Improved</option>
                                    <option value="Not Observed">Not Observed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Participants' attention during the event was</label>
                            <div class="select2-success">
                                <select class="form-control" id="" name="" >
                                    <option>Select One</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Good">Good</option>
                                    <option value="Neutral">Neutral</option>
                                    <option value="Need To Improved">Need To Improved</option>
                                    <option value="Not Observed">Not Observed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Logistical arrangements (e.g. stationery, sitting arrangements, sound quality others) were</label>
                            <div class="select2-success">
                                <select class="form-control" id="" name="" >
                                    <option>Select One</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Good">Good</option>
                                    <option value="Neutral">Neutral</option>
                                    <option value="Need To Improved">Need To Improved</option>
                                    <option value="Not Observed">Not Observed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Relevancy of delivery of messages from the event was</label>
                            <div class="select2-success">
                                <select class="form-control" id="" name="" >
                                    <option>Select One</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Good">Good</option>
                                    <option value="Neutral">Neutral</option>
                                    <option value="Need To Improved">Need To Improved</option>
                                    <option value="Not Observed">Not Observed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Participants' feedback on the overall event was</label>
                            <div class="select2-success">
                                <select class="form-control" id="" name="" >
                                    <option>Select One</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Good">Good</option>
                                    <option value="Neutral">Neutral</option>
                                    <option value="Need To Improved">Need To Improved</option>
                                    <option value="Not Observed">Not Observed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Note</label>
                            <textarea class="form-control" type="text" name="fullName" value=""></textarea>
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
        theForm.find('input:submit, button:submit').prop('disabled', true);
    });
</script>