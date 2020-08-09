<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_complain', 'edit_complain')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_complains(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid complain, no data found.', 'error');
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

    $ret = $this->add_edit_complain($data);

    if ($ret) {
        $msg = "Complain has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_event_management/manage_complains?action=add_edit_complain&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_event_management/manage_complains'));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Complain </h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Complains',
                'title' => 'Manage Complains',
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
                    <label for="inputName">Name of service recipient</label>
                    <input type="text" class="form-control" id="Name" name="name" value="<?php echo $pre_data['name']; ?>">
                </div>                                             
                <div class="form-group">
                    <label for="inputName">Service recipient (victim, family, relative, community)</label>
                    <input type="text" class="form-control" id="ServiceRepcipienItem" name="type_recipient" value="<?php echo $pre_data['type_recipient']; ?>">
                </div>                                             
                <div class="form-group">
                    <label for="inputName">Type of service seeking advice/complaints/information/support</label>
                    <input type="text" class="form-control" id="ServiceRepcipienItem" name="type_service" value="<?php echo $pre_data['type_service']; ?>">
                </div>      
                <div class="form-group">
                    <label for="inputName">how he/she knows about this service of the project (IPT show, vedio show, scholl quiz, palli somaj,CTC, DLAC, social media, IEC/BCC, other (please sepecify)</label>
                    <textarea class="form-control" id="ServiceRepcipienItem" name="know_service" value=""><?php echo $pre_data['know_service']; ?></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="inputName">Complain Register Date</label>
                    <div class="input-group">
                        <input id="ComplainRegisterDate" type="text" class="form-control" name="complain_register_date" value="<?php echo $pre_data['complain_register_date'] && $pre_data['complain_register_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['complain_register_date'])) : date('d-m-Y'); ?>">
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            _datepicker('ComplainRegisterDate');
                        });
                    </script>
                </div>
                <div class="form-group">
                    <label for="inputAge">Age</label>
                    <input type="text" class="form-control" id="Age" name="age" value="<?php echo $pre_data['age']; ?>">
                </div>
                <div class="form-group">
                    <label>Sex</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px oldGender" type="radio" name="gender" value="male" <?php echo $pre_data && $pre_data['gender'] == 'male' ? 'checked' : '' ?>><span class="lbl">Male</span></label>
                            <label><input class="px oldGender" type="radio" name="gender" value="female" <?php echo $pre_data && $pre_data['gender'] == 'female' ? 'checked' : '' ?>><span class="lbl">Female</span></label>
                            <label><input class="px" type="radio" name="gender" id="newGender"><span class="lbl">Other</span></label>
                        </div>
                    </div>
                </div>           
                <div id="newGenderType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control" placeholder="Please Specity" type="text" id="newGenderText" name="new_gender" value="<?php echo $pre_data['gender'] ?>">
                </div>
                <script>
                    init.push(function () {
                        $("#newGender").on("click", function () {
                            $('#newGenderType').show();
                        });

                        $(".oldGender").on("click", function () {
                            $('#newGenderType').hide();
                            $('#newGenderText').val('');
                        });
                    });
                </script>
                <div class="form-group">
                    <label for="inputAddress">Address</label>
                    <textarea class="form-control" name="address"><?php echo $pre_data['address']; ?></textarea>
                </div>  
                <div class="form-group">
                    <label for="inputRemark">Remark</label>
                    <textarea class="form-control" name="remark"><?php echo $pre_data['remark']; ?></textarea>
                </div>
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_event_management/manage_complains') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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
    init.push(function () {
        theForm.find('input:submit, button:submit').prop('disabled', true);
    });
</script>