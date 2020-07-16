<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_support', 'edit_support')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit','action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_counsellors(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid counsellor, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'entry_date' => 'Entry Date',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_counsellor($data);

    if ($ret['success']) {
        $msg = "Counsellor Data has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_support_management/manage_supports'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

$all_issues_discussed = $this->get_lookups('issues_discussed');

doAction('render_start');
?>
<style type="text/css">
    .removeReadOnly{
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : 'New ' ?>Social Counselling</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Supports',
                'title' => 'Manage Supports',
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
                    <input type="hidden" name="pk_support_id" value="<?php echo $_GET['id'] ?>"/>
                    <input type="hidden" name="fk_psycho_support_id" value="<?php echo $_GET['support_id'] ?>"/>
                    <input type="hidden" name="fk_customer_id" value="<?php echo $_GET['support_customer'] ?>"/>
                    <div class="form-group">
                        <label>Entry Date</label>
                        <div class="input-group">
                            <input id="entryDate" type="text" class="form-control" name="entry_date" value="<?php echo $pre_data['entry_date'] && $pre_data['entry_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['entry_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('entryDate');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Entry Time</label>
                        <div class="input-group">
                            <input id="entryTime" type="text" class="form-control" name="entry_time" value="<?php echo $pre_data['entry_time'] && $pre_data['entry_time'] != '0000-00-00' ? date('h:i', strtotime($pre_data['entry_time'])) : date('h:i'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _timepicker('entryTime');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Volunteer/Para counsellor home visits?</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" name="home_visit" value="yes" <?php echo $pre_data && $pre_data['home_visit'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" name="home_visit" value="no" <?php echo $pre_data && $pre_data['home_visit'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Issues Discussed of Change</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <?php
                                $issues_discussed = explode(',', $pre_data['issues_discussed']);
                                foreach ($all_issues_discussed['data'] as $impact) {
                                    ?>
                                    <label><input class="px" type="checkbox" name="issues_discussed[]" value="<?php echo $impact['lookup_value'] ?>" <?php
                                        if (in_array($impact['lookup_value'], $issues_discussed)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl"><?php echo $impact['lookup_value'] ?></span></label>
                                              <?php } ?>
                                <label><input class="px" type="checkbox" id="newIssueDiscussed"><span class="lbl">Others</span></label>
                            </div>
                        </div>
                    </div>
                    <div id="newIssueDiscussedType" style="display: none; margin-bottom: 1em;">
                        <input class="form-control" placeholder="Please Specity" type="text" name="new_issue_discussed" value="">
                    </div>
                    <script>
                        init.push(function () {
                            $("#newIssueDiscussed").on("click", function () {
                                $('#newIssueDiscussedType').toggle();
                            });
                        });
                    </script>
                    <div class="form-group">
                        <label>Family members present?</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px" type="radio" id="yesFamilyPresent" name="is_family_present" value="yes" <?php echo $pre_data && $pre_data['is_family_present'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                                <label><input class="px" type="radio" id="noFamilyPresent" name="is_family_present" value="no" <?php echo $pre_data && $pre_data['is_family_present'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                            </div>
                        </div>
                    </div>
                    <div id="howMany" style="display: none; margin-bottom: 1em;">
                        <label>How many family members present?</label>
                        <input class="form-control" placeholder="Please Specity" type="number" name="how_many" value="<?php echo $pre_data['how_many'] ? $pre_data['how_many'] : ''; ?>">
                    </div>
                    <script>
                        init.push(function () {
                            var isChecked = $('#yesFamilyPresent').is(':checked');

                            if (isChecked == true) {
                                $('#howMany').show();
                            }
                            
                            $("#yesFamilyPresent").on("click", function () {
                                $('#howMany').show();
                            });
                            $("#noFamilyPresent").on("click", function () {
                                $('#howMany').hide();
                            });
                        });
                    </script>
                    <div class="form-group">
                        <label>Note</label>
                        <textarea class="form-control" name="counsellor_note"><?php echo $pre_data['counsellor_note'] ? $pre_data['counsellor_note'] : ''; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer tar">
        <a href="<?php echo url('admin/dev_support_management/manage_supports') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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