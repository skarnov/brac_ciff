<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_errin_returnee', 'edit_errin_returnee')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit', 'action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_errin_returnees(array('id' => $edit, 'single' => true));
    
    if (!$pre_data) {
        add_notification('Invalid returnee, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

if ($_POST) {
    $data = array(
        'required' => array(
            'returnee_name' => 'Returnee Name',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_errin_returnee($data);

    if ($ret['success']) {
        $msg = "Information of returnee has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_errin/manage_returnees'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

$customer = jack_obj('dev_customer_management');
$all_genders = $customer->get_lookups('gender')['data'];
foreach ($all_genders as $value) {
    $genders[] = $value['lookup_value'];
}

doAction('render_start');
?>
<div class="page-header">
    <h1>New Returnee</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'icon' => 'icon_list',
                'text' => 'All Returnee Management',
                'title' => 'Manage Returnees',
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
                        <label>Returnee Type</label>
                        <select class="form-control" required name="returnee_type">
                            <option value="individual" <?php if ('individual' == $pre_data['returnee_type']) echo 'selected' ?>>Individual</option>
                            <option value="family" <?php if ('family' == $pre_data['returnee_type']) echo 'selected' ?>>Family</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Returnee Name</label>
                        <input class="form-control" required type="text" name="returnee_name" value="<?php echo $pre_data['full_name'] ? $pre_data['full_name'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Family Name</label>
                        <input class="form-control" required type="text" name="family_name" value="<?php echo $pre_data['family_name'] ? $pre_data['family_name'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>First Name</label>
                        <input class="form-control" required type="text" name="first_name" value="<?php echo $pre_data['first_name'] ? $pre_data['first_name'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Other Names</label>
                        <input class="form-control" type="text" name="other_name" value="<?php echo $pre_data['other_name'] ? $pre_data['other_name'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Sex</label>
                        <select class="form-control" required name="returnee_sex">
                            <?php foreach ($genders as $value) {
                                ?>
                                <option name="<?php echo $value ?>" <?php if ($value == $pre_data['customer_gender']) echo 'selected' ?>><?php echo $value ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <div class="input-group">
                            <input id="birthDate" type="text" class="form-control" name="birth_date" value="<?php echo $pre_data['customer_birthdate'] && $pre_data['customer_birthdate'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['customer_birthdate'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('birthDate');
                            });
                        </script>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Upload Document</label>
                        <?php
                        if ($pre_data['user_document']) {
                            ?>
                            <div class="old_image_holder">
                                <div class="old_image">
                                    <a href="<?php echo url('site/contents/uploads/'.$pre_data['user_document']) ?>" target="_blank"><?php echo $pre_data['user_document'] ?></a>
                                    <input type="hidden" name="user_old_document" value="<?php echo $pre_data['user_document'] ?>">
                                </div>
                            </div>
                            <div class="new_image mt10">
                                <input type="file" id="profile_picture" name="user_document">
                                <script type="text/javascript">
                                    init.push(function () {
                                        $('#profile_picture').pixelFileInput({
                                            placeholder: 'No file selected...'
                                        });
                                    })
                                </script>
                                <p class="help-block">Upload ZIP File</p>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="new_image">
                                <input type="file" id="profile_picture" name="user_document">
                                <script type="text/javascript">
                                    init.push(function () {
                                        $('#profile_picture').pixelFileInput({
                                            placeholder: 'No file selected...'
                                        });
                                    })
                                </script>
                                <p class="help-block">Upload ZIP File</p>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="form-group">
                        <label>Part of a family group</label>
                        <select class="form-control" name="family_part">
                            <option value="no" <?php if ('no' == $pre_data['family_part']) echo 'selected' ?>>No</option>
                            <option value="yes" <?php if ('yes' == $pre_data['family_part']) echo 'selected' ?>>Yes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Voluntary</label>
                        <select class="form-control" name="having_voluntary">
                            <option value="no" <?php if ('no' == $pre_data['having_voluntary']) echo 'selected' ?>>No</option>
                            <option value="yes" <?php if ('yes' == $pre_data['having_voluntary']) echo 'selected' ?>>Yes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Group</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <label><input class="px oldGroup" type="radio" name="having_group" value="Single Male" <?php echo $pre_data && $pre_data['having_group'] == 'Single Male' ? 'checked' : '' ?>><span class="lbl">Single Male</span></label>
                                <label><input class="px oldGroup" type="radio" name="having_group" value="Family with Young Children" <?php echo $pre_data && $pre_data['having_group'] == 'Family with Young Children' ? 'checked' : '' ?>><span class="lbl">Family with Young Children</span></label>
                                <label><input class="px" type="radio" name="having_group" id="newGroup"><span class="lbl">Other</span></label>
                            </div>
                        </div>
                    </div>
                    <div id="newGroupType" style="display: none; margin-bottom: 1em;">
                        <input class="form-control" placeholder="Please Specity" type="text" name="new_group" value="">
                    </div>
                    <script>
                        init.push(function () {
                            $("#newGroup").on("click", function () {
                                $('#newGroupType').show();
                            });

                            $(".oldGroup").on("click", function () {
                                $('#newGroupType').hide();
                            });
                        });
                    </script>
                    <div class="form-group">
                        <label>Contact Phone Number</label>
                        <input class="form-control" type="text" name="contact_phone_number" value="<?php echo $pre_data['emergency_mobile'] ? $pre_data['emergency_mobile'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Contact Email Address</label>
                        <input class="form-control" type="text" name="email_address" value="<?php echo $pre_data['email_address'] ? $pre_data['email_address'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Contact Mobile Number</label>
                        <input class="form-control" type="text" name="customer_mobile" value="<?php echo $pre_data['customer_mobile'] ? $pre_data['customer_mobile'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Contact Skype ID</label>
                        <input class="form-control" type="text" name="skype_id" value="<?php echo $pre_data['skype_id'] ? $pre_data['skype_id'] : ''; ?>">
                    </div>
                </div>
            </div>
            <div class="panel-footer tar">
                <a href="<?php echo url('admin/dev_errin/manage_returnees') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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