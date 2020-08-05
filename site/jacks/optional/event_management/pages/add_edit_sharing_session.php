<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_sharing_session', 'edit_sharing_session')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_sharing_sessions(array('sharing_session_id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid sharing_session, no data found.', 'error');
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

    $ret = $this->add_edit_sharing_session($data);

    if ($ret['sharing_session_insert'] || $ret['sharing_session_update']) {
        $msg = "Sharing Session has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_sharing_session_management/manage_sharing_sessions?action=add_edit_sharing_session&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_sharing_session_management/manage_sharing_sessions'));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Sharing Session </h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Sharing Sessions',
                'title' => 'Manage Sharing Sessions',
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
            dev_sharing_sessions
            <div class="col-md-4">
                <div class="form-group">
                    <label>Training Date</label>
                    <div class="input-group">
                        <input id="TrainingwDate" type="text" class="form-control" name="" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('TrainingwDate');
                        });
                    </script>
                </div>
                <label class="control-label input-label">Training Name</label>
                <div class="form-group">
                    <input class="form-control" type="text" name="" value="">
                </div>
                <div class="form-group">
                    <label>Evaluator Profession</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Judicial govt. employee</span></label>
                            <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Non-judicial Govt. employee</span></label>
                            <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Lawyers</span></label>
                            <label><input class="px" type="checkbox" name="" value=""><span class="lbl">NGO</span></label>
                            <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Journalist</span></label>
                            <label><input class="px" type="checkbox" name="" value=""><span class="lbl">Public representative</span></label>
                            <label><input class="px col-sm-12" type="checkbox" id="newEvaluatorProfession"><span class="lbl">Others</span></label>
                        </div>
                    </div>
                </div>
                <div id="newEvaluatorProfessionType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control col-sm-12" placeholder="Please Specity" type="text" name="" value="">
                </div>
                <script>
                    init.push(function () {
                        $("#newEvaluatorProfession").on("click", function () {
                            $('#newEvaluatorProfessionType').toggle();
                        });
                    });
                </script>
            </div>
            <div class="col-md-4">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Organization of The Training (Please Tick √)</legend>
                    <div class="form-group">
                        <label>How satisfied are you with the contents of training/workshop</label>
                        <div class="select2-success">
                            <select class="form-control" id="" name="" >
                                <option value="">Select One</option>
                                <option value="Very Satisfied">Very Satisfied</option>
                                <option value="Satisfied">Satisfied</option>
                                <option value="Ok">Ok</option>
                                <option value="Dissatisfied">Dissatisfied</option>
                                <option value="Very Dissatisfied">Very Dissatisfied</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>How satisfied are you with the training venue and other logistic supports</label>
                        <div class="select2-success">
                            <select class="form-control" id="" name="" >
                                <option value="">Select One</option>
                                <option value="Very Satisfied">Very Satisfied</option>
                                <option value="Satisfied">Satisfied</option>
                                <option value="Ok">Ok</option>
                                <option value="Dissatisfied">Dissatisfied</option>
                                <option value="Very Dissatisfied">Very Dissatisfied</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>How satisfied are you with the training/workshop facilitation</label>
                        <div class="select2-success">
                            <select class="form-control" id="" name="" >
                                <option value="">Select One</option>
                                <option value="Very Satisfied">Very Satisfied</option>
                                <option value="Satisfied">Satisfied</option>
                                <option value="Ok">Ok</option>
                                <option value="Dissatisfied">Dissatisfied</option>
                                <option value="Very Dissatisfied">Very Dissatisfied</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-4">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Outcome of The Training (Please Tick √)</legend>
                    <div class="form-group">
                        <label>What extent your knowledge increased on NPA</label>
                        <div class="select2-success">
                            <select class="form-control" id="" name="" >
                                <option value="">Select One</option>
                                <option value="Excellent">Excellent</option>
                                <option value="Very Good">Very Good</option>
                                <option value="Ok">Ok</option>
                                <option value="Not Very Good">Not Very Good</option>
                                <option value="Not At All">Not At All</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>What extent your knowledge increased on trafficking law</label>
                        <div class="select2-success">
                            <select class="form-control" id="" name="" >
                                <option value="">Select One</option>
                                <option value="Excellent">Excellent</option>
                                <option value="Very Good">Very Good</option>
                                <option value="Ok">Ok</option>
                                <option value="Not Very Good">Not Very Good</option>
                                <option value="Not At All">Not At All</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>What extent your knowledge increased on policy process</label>
                        <div class="select2-success">
                            <select class="form-control" id="" name="" >
                                <option value="">Select One</option>
                                <option value="Excellent">Excellent</option>
                                <option value="Very Good">Very Good</option>
                                <option value="Ok">Ok</option>
                                <option value="Not Very Good">Not Very Good</option>
                                <option value="Not At All">Not At All</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>What extent your knowledge increased on over all contents</label>
                        <div class="select2-success">
                            <select class="form-control" id="" name="" >
                                <option value="">Select One</option>
                                <option value="Excellent">Excellent</option>
                                <option value="Very Good">Very Good</option>
                                <option value="Ok">Ok</option>
                                <option value="Not Very Good">Not Very Good</option>
                                <option value="Not At All">Not At All</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Recommendation (If Any)" type="text" name="" value=""></textarea>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="panel-footer tar">
        <a href="<?php echo url('admin/dev_sharing_session_management/manage_sharing_sessions') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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
</form>
<script type="text/javascript">
    var BD_LOCATIONS = <?php echo getBDLocationJson(); ?>;
    init.push(function () {
        new bd_new_location_selector({
            'division': $('#division'),
            'district': $('#district'),
            'sub_district': $('#sub_district'),
            'police_station': $('#police_station'),
        });

        theForm.find('input:submit, button:submit').prop('disabled', true);
    });
</script>