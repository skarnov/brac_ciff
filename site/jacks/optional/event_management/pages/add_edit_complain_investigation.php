<?php
global $devdb;
$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_complain_investigation', 'edit_complain_investigation')) {
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

$pre_data = array();

if ($edit) {
    $pre_data = $this->get_complain_investigations(array('id' => $edit, 'single' => true));

    if (!$pre_data) {
        add_notification('Invalid complain investigation, no data found.', 'error');
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

    $ret = $this->add_edit_complain_investigation($data);

    if ($ret) {
        $msg = "Complain Investigation has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        if ($edit) {
            header('location: ' . url('admin/dev_event_management/manage_complain_investigations?action=add_edit_complain_investigation&edit=' . $edit));
        } else {
            header('location: ' . url('admin/dev_event_management/manage_complain_investigations'));
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
    <h1><?php echo $edit ? 'Update ' : 'New ' ?> Complain Investigation </h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Complain Investigations',
                'title' => 'Manage Complain Investigations',
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
                    <label>Is It Continuing Investigation?</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px" type="radio" name="running_investigation" value="yes" <?php echo $pre_data && $pre_data['running_investigation'] == 'yes' ? 'checked' : '' ?>><span class="lbl">Yes</span></label>
                            <label><input class="px" type="radio" name="running_investigation" value="no" <?php echo $pre_data && $pre_data['running_investigation'] == 'no' ? 'checked' : '' ?>><span class="lbl">No</span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-control" name="full_name" value="<?php echo $pre_data['full_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="case_id">Case Number</label>
                    <input type="text" class="form-control" id="case_id" name="case_id" value="<?php echo $pre_data['case_id']; ?>">
                </div>
                <div class="form-group">
                    <label for="inputName">Date</label>
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
                    <label for="inputBranch">Select Month</label>
                    <select class="form-control" name="month" >
                        <option value="January" <?php echo $pre_data && $pre_data['month'] == 'January' ? 'selected' : '' ?>>January</option>
                        <option value="February" <?php echo $pre_data && $pre_data['month'] == 'February' ? 'selected' : '' ?>>February</option>
                        <option value="March" <?php echo $pre_data && $pre_data['month'] == 'March' ? 'selected' : '' ?>>March</option>
                        <option value="April" <?php echo $pre_data && $pre_data['month'] == 'April' ? 'selected' : '' ?>>April</option>
                        <option value="May" <?php echo $pre_data && $pre_data['month'] == 'May' ? 'selected' : '' ?>>May</option>
                        <option value="June" <?php echo $pre_data && $pre_data['month'] == 'June' ? 'selected' : '' ?>>June</option>
                        <option value="July" <?php echo $pre_data && $pre_data['month'] == 'July' ? 'selected' : '' ?>>July</option>
                        <option value="August" <?php echo $pre_data && $pre_data['month'] == 'August' ? 'selected' : '' ?>>August</option>
                        <option value="September" <?php echo $pre_data && $pre_data['month'] == 'September' ? 'selected' : '' ?>>September</option>
                        <option value="October" <?php echo $pre_data && $pre_data['month'] == 'October' ? 'selected' : '' ?>>October</option>
                        <option value="November" <?php echo $pre_data && $pre_data['month'] == 'November' ? 'selected' : '' ?>>November</option>
                        <option value="December" <?php echo $pre_data && $pre_data['month'] == 'December' ? 'selected' : '' ?>>December</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputAge">Age</label>
                    <input type="text" class="form-control" id="Age" name="age" value="<?php echo $pre_data['age']; ?>">
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                        <div class="options_holder radio">
                            <label><input class="px oldGender" type="radio" name="gender" value="male" <?php echo $pre_data && $pre_data['gender'] == 'male' ? 'checked' : '' ?>><span class="lbl">Men (>=18)</span></label>
                            <label><input class="px oldGender" type="radio" name="gender" value="female" <?php echo $pre_data && $pre_data['gender'] == 'female' ? 'checked' : '' ?>><span class="lbl">Women (>=18)</span></label>
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
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="comments">Type of Case</label>
                    <div class="options_holder radio">
                        <label><input class="px" type="radio"  class="Oldcase" name="type_case" value="Missing" <?php echo $pre_data && $pre_data['type_case'] == 'Missing' ? 'checked' : '' ?>><span class="lbl">Missing</span></label>
                        <label><input class="px" type="radio"  class="Oldcase" name="type_case" value="Flee away with" <?php echo $pre_data && $pre_data['type_case'] == 'Flee away with' ? 'checked' : '' ?>><span class="lbl">Flee away with</span></label>
                        <label><input class="px" type="radio"  class="Oldcase" name="type_case" value="Abduction" <?php echo $pre_data && $pre_data['type_case'] == 'Abduction' ? 'checked' : '' ?>><span class="lbl">Abduction</span></label>
                        <label><input class="px" type="radio" id="newCase" name="type_case" value="Other"><span class="lbl">Other</span></label>
                    </div>                          
                </div>  
                <div id="newCaseType" style="display: none; margin-bottom: 1em;">
                    <input class="form-control" placeholder="Please Specity" type="text" name="new_type_case" value="<?php echo $pre_data['type_case'] ? $pre_data['type_case'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label class="control-label input-label">Division (*)</label>
                    <div class="select2-primary">
                        <select class="form-control division" required name="division" style="text-transform: capitalize">
                            <?php if ($pre_data['division']) : ?>
                                <option value="<?php echo strtolower($pre_data['division']) ?>"><?php echo $pre_data['division'] ?></option>
                            <?php else: ?>
                                <option>Select One</option>
                            <?php endif ?>
                            <?php foreach ($divisions as $division) : ?>
                                <option id="<?php echo $division['id'] ?>" value="<?php echo strtolower($division['name']) ?>" <?php echo $pre_data && $pre_data['division'] == $division['name'] ? 'selected' : '' ?>><?php echo $division['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label input-label">District (*)</label>
                    <div class="select2-primary">
                        <select class="form-control district" required name="district" style="text-transform: capitalize" id="districtList">
                            <?php if ($pre_data['district']) : ?>
                                <option value="<?php echo $pre_data['district'] ?>"><?php echo $pre_data['district'] ?></option>
                            <?php endif ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label input-label">Select Municipality/Upazila</label>
                    <div class="select2-primary">
                        <select class="form-control subdistrict" name="upazila" style="text-transform: capitalize" id="subdistrictList">
                            <?php if ($pre_data['upazila']) : ?>
                                <option value="<?php echo $pre_data['upazila'] ?>"><?php echo $pre_data['upazila'] ?></option>
                            <?php endif ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="police_station">Name of police station</label>
                    <input type="text" class="form-control" id="police_station" name="police_station" value="<?php echo $pre_data['police_station']; ?>">
                </div>
                <div class="form-group">
                    <label for="comments">Comments</label>
                    <textarea class="form-control" name="comments"><?php echo $pre_data['comments']; ?></textarea>
                </div>
                <script>
                    init.push(function () {
                        $("#newCase").on("click", function () {
                            $('#newCaseType').show();
                        });

                        $(".Oldcase").on("click", function () {
                            $('#newCaseType').hide();
                        });
                    });
                </script>                                                                                                            
            </div>
        </div>
        <div class="panel-footer tar">
            <a href="<?php echo url('admin/dev_event_management/manage_complain_investigations') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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