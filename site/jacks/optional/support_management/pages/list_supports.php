<?php
if ($_GET['ajax_type'] == 'get_sessions') {
    $pkSupport = $_POST['pk_support'];
    $supportID = $_POST['support_id'];
    $customerID = $_POST['support_customer'];
    $all_sessions = $this->get_session(array('support_id' => $supportID, 'single' => false));

    ob_start();
    ?>
    <a href="<?php echo url('dev_support_management/manage_supports?action=add_edit_session&id=' . $pkSupport . '&support_id=' . $supportID . '&support_customer=' . $customerID) ?>" class="btn btn-success btn-sm" style="margin-bottom: 1%">New Followup Sessions</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($all_sessions['data'] as $value):
                ?>
                <tr>
                    <td><?php echo $value['entry_date'] ?></td>
                    <td>
                        <a href="<?php echo url('dev_support_management/manage_supports?action=add_edit_session&edit=' . $value['pk_psycho_session_id'] . '&support_id=' . $supportID . '&support_customer=' . $customerID) ?>" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <?php
    $output = ob_get_clean();
    echo json_encode(array('success' => $output));
    exit();
}

if ($_GET['ajax_type'] == 'get_psychosocial_evaluation') {
    $pkSupport = $_POST['pk_support'];
    $supportID = $_POST['support_id'];
    $customerID = $_POST['support_customer'];
    $all_psychosocial_evaluations = $this->get_evaluation(array('support_id' => $supportID, 'single' => false));

    ob_start();
    ?>
    <a href="<?php echo url('dev_support_management/manage_supports?action=add_edit_evaluation&id=' . $pkSupport . '&support_id=' . $supportID . '&support_customer=' . $customerID) ?>" class="btn btn-success btn-sm" style="margin-bottom: 1%">New Psychosocial Evaluation</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Score</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($all_psychosocial_evaluations['data'] as $value):
                ?>
                <tr>
                    <td><?php echo $value['entry_date'] ?></td>
                    <td><?php echo $value['evaluated_score'] ?></td>
                    <td>
                        <a href="<?php echo url('dev_support_management/manage_supports?action=add_edit_evaluation&edit=' . $value['pk_psycho_evaluation_id'] . '&support_id=' . $supportID . '&support_customer=' . $customerID) ?>" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <?php
    $output = ob_get_clean();
    echo json_encode(array('success' => $output));
    exit();
}

if ($_GET['ajax_type'] == 'get_social_evaluation') {
    $pkSupport = $_POST['pk_support'];
    $supportID = $_POST['support_id'];
    $customerID = $_POST['support_customer'];
    $all_social_evaluations = $this->get_social_evaluation(array('support_id' => $supportID, 'single' => false));

    ob_start();
    ?>
    <a href="<?php echo url('dev_support_management/manage_supports?action=add_edit_social_evaluation&id=' . $pkSupport . '&support_id=' . $supportID . '&support_customer=' . $customerID) ?>" class="btn btn-success btn-sm" style="margin-bottom: 1%">New Social Evaluation</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Score</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($all_social_evaluations['data'] as $value):
                ?>
                <tr>
                    <td><?php echo $value['entry_date'] ?></td>
                    <td><?php echo $value['evaluated_score'] ?></td>
                    <td>
                        <a href="<?php echo url('dev_support_management/manage_supports?action=add_edit_social_evaluation&edit=' . $value['pk_social_evaluation_id'] . '&support_id=' . $supportID . '&support_customer=' . $customerID) ?>" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <?php
    $output = ob_get_clean();
    echo json_encode(array('success' => $output));
    exit();
}

if ($_GET['ajax_type'] == 'get_economic_evaluation') {
    $pkSupport = $_POST['pk_support'];
    $supportID = $_POST['support_id'];
    $customerID = $_POST['support_customer'];
    $all_economic_evaluations = $this->get_economic_evaluation(array('support_id' => $supportID, 'single' => false));

    ob_start();
    ?>
    <a href="<?php echo url('dev_support_management/manage_supports?action=add_edit_economic_evaluation&id=' . $pkSupport . '&support_id=' . $supportID . '&support_customer=' . $customerID) ?>" class="btn btn-success btn-sm" style="margin-bottom: 1%">New Economic Evaluation</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Score</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($all_economic_evaluations['data'] as $value):
                ?>
                <tr>
                    <td><?php echo $value['entry_date'] ?></td>
                    <td><?php echo $value['evaluated_score'] ?></td>
                    <td>
                        <a href="<?php echo url('dev_support_management/manage_supports?action=add_edit_economic_evaluation&edit=' . $value['pk_economic_evaluation_id'] . '&support_id=' . $supportID . '&support_customer=' . $customerID) ?>" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <?php
    $output = ob_get_clean();
    echo json_encode(array('success' => $output));
    exit();
}

if ($_GET['ajax_type'] == 'get_family_counseling') {
    $pkSupport = $_POST['pk_support'];
    $supportID = $_POST['support_id'];
    $customerID = $_POST['support_customer'];
    $all_family_counseling = $this->get_family_counselling(array('support_id' => $supportID, 'single' => FALSE));

    ob_start();
    ?>
    <a href="<?php echo url('dev_support_management/manage_supports?action=add_edit_family_counseling&id=' . $pkSupport . '&support_id=' . $supportID . '&support_customer=' . $customerID) ?>" class="btn btn-success btn-sm" style="margin-bottom: 1%">New Family Counseling</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($all_family_counseling['data'] as $value):
                ?>
                <tr>
                    <td><?php echo $value['entry_date'] ?></td>
                    <td>
                        <a href="<?php echo url('dev_support_management/manage_supports?action=add_edit_family_counseling&edit=' . $value['pk_psycho_family_counselling_id'] . '&support_id=' . $supportID . '&support_customer=' . $customerID) ?>" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                    </td>
                </tr>
                <?php
            endforeach
            ?>
        </tbody>
    </table>
    <?php
    $output = ob_get_clean();
    echo json_encode(array('success' => $output));
    exit();
}

if ($_GET['ajax_type'] == 'get_counsellors') {
    $pkSupport = $_POST['pk_support'];
    $supportID = $_POST['support_id'];
    $customerID = $_POST['support_customer'];
    $all_counsellors = $this->get_counsellors(array('support_id' => $supportID, 'single' => FALSE));

    ob_start();
    ?>
    <a href="<?php echo url('dev_support_management/manage_supports?action=add_edit_counsellor&id=' . $pkSupport . '&support_id=' . $supportID . '&support_customer=' . $customerID) ?>" class="btn btn-success btn-sm" style="margin-bottom: 1%">New Social Counseling/Para-Counseling Support</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($all_counsellors['data'] as $value):
                ?>
                <tr>
                    <td><?php echo $value['entry_date'] ?></td>
                    <td>
                        <a href="<?php echo url('dev_support_management/manage_supports?action=add_edit_counsellor&edit=' . $value['pk_counsellor_support_id'] . '&support_id=' . $supportID . '&support_customer=' . $customerID) ?>" class="btn btn-primary btn-sm" style="margin-bottom: 1%">Edit</a>
                    </td>
                </tr>
                <?php
            endforeach
            ?>
        </tbody>
    </table>
    <?php
    $output = ob_get_clean();
    echo json_encode(array('success' => $output));
    exit();
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects(array('single' => false));

$filter_id = $_GET['id'] ? $_GET['id'] : null;
$filter_customer_id = $_GET['customer_id'] ? $_GET['customer_id'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_support_status = $_GET['support_status'] ? $_GET['support_status'] : null;
$filter_project_id = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_support_type = $_GET['support_type'] ? $_GET['support_type'] : null;

if($_config['user']['user_branch']):
    $branch_id = $_config['user']['user_branch'];
endif;

$args = array(
    'select_fields' => array(
        'dev_supports.pk_support_id',
        'dev_supports.fk_support_id',
        'dev_supports.fk_project_id',
        'dev_supports.division_name',
        'dev_supports.district_name',
        'dev_supports.sub_district_name',
        'dev_supports.fk_customer_id',
        'dev_supports.support_status',
        'dev_supports.support_name',
        'dev_supports.total_session',
        'dev_supports.family_counselling',
        'dev_supports.para_counsellor',
        'dev_supports.total_evaluation',
        'dev_supports.start_date',
        'dev_supports.end_date',
        'dev_customers.customer_id',
        'dev_customers.full_name',
    ),
    'id' => $filter_id,
    'customer_id' => $filter_customer_id,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'support_status' => $filter_support_status,
    'support_type' => $filter_support_type,
    'branch_id' => $branch_id,
    'project_id' => $filter_project_id,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'dev_supports.fk_customer_id',
        'order' => 'DESC'
    ),
);

$supports = $this->get_supports($args);
$pagination = pagination($supports['total'], $per_page_items, $start);

$filterString = array();
if ($filter_id)
    $filterString[] = 'ID: ' . $filter_id;
if ($filter_customer_id)
    $filterString[] = 'Customer ID: ' . $filter_customer_id;

doAction('render_start');
?>
<div class="page-header">
    <h1>All Supports</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_psychosocial',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Psychosocial Reintegration',
                'title' => 'New Psychosocial Reintegration',
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_social',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Social Reintegration',
                'title' => 'New Social Reintegration',
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_economic',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Economic Reintegration',
                'title' => 'New Economic Reintegration',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
echo formProcessor::form_elements('customer_id', 'customer_id', array(
    'width' => 2, 'type' => 'text', 'label' => 'Customer ID',
        ), $filter_customer_id);
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
<div class="col-sm-2">
    <div class="form-group form_element_holder select_holder select_holder_project_id">
        <label>Status</label>
        <select class="form-control" id="select_holder_project_id" name="support_status">
            <option value="">Select One</option>
            <option value="ongoing" <?php
            if ($_GET['support_status'] == 'ongoing') {
                echo 'selected';
            }
            ?>>Ongoing</option>
            <option value="completed" <?php
            if ($_GET['support_status'] == 'completed') {
                echo 'selected';
            }
            ?>>Completed</option>
            <option value="evaluated" <?php
            if ($_GET['support_status'] == 'evaluated') {
                echo 'selected';
            }
            ?>>Evaluated</option>
        </select>
    </div>
</div>
<div class="col-sm-2">
    <div class="form-group form_element_holder select_holder select_holder_project_id">
        <label>Support Type</label>
        <select class="form-control" id="select_holder_project_id" name="support_type">
            <option value="">Select One</option>
            <option value="psychosocial" <?php
            if ($_GET['support_type'] == 'psychosocial') {
                echo 'selected';
            }
            ?>>Psychosocial</option>
            <option value="social" <?php
            if ($_GET['support_type'] == 'social') {
                echo 'selected';
            }
            ?>>Social</option>
            <option value="economic" <?php
            if ($_GET['support_type'] == 'economic') {
                echo 'selected';
            }
            ?>>Economic</option>
        </select>
    </div>
</div>
<div class="col-sm-2">
    <div class="form-group form_element_holder select_holder select_holder_project_id">
        <label>Project</label>
        <select class="form-control" id="select_holder_project_id" name="project_id">
            <option value="">Select One</option>
            <?php foreach ($all_projects['data'] as $value): ?>
                <option value="<?php echo $value['pk_project_id'] ?>" <?php
                if ($_GET['project_id'] == $value['pk_project_id']) {
                    echo 'selected';
                }
                ?>><?php echo $value['project_short_name'] ?></option>
                    <?php endforeach ?>
        </select>
    </div>
</div>
<?php
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="table-primary table-responsive">
    <?php if ($filterString): ?>
        <div class="table-header">
            Filtered With: <?php echo implode(', ', $filterString) ?>
        </div>
    <?php endif; ?>
    <div class="table-header">
        <?php echo searchResultText($supports['total'], $start, $per_page_items, count($supports['data']), 'supports') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Customer Info</th>
                <th>Status</th>
                <th>Support Name</th>
                <th>Social Counseling/Field Organizer</th>
                <th>Followup Counseling Session</th>
                <th>Family Counseling</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th class="text-center">Completion</th>
                <th class="text-center">Evaluations</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($supports['data'] as $i => $support) {
                ?>
                <tr>
                    <td><b>ID:</b> <?php echo $support['customer_id'] ?> <br> <b>Name:</b> <?php echo $support['full_name'] ?> </td> 
                    <td style="text-transform: capitalize"><?php echo $support['support_status'] ?></td>
                    <td style="text-transform: capitalize"><?php echo $support['support_name'] ?> Reintegration Support</td>
                    <?php if ($support['support_name'] == 'psychosocial') { ?>
                        <td><a href="javascript:" class="view_counsellor" data-support="<?php echo $support['pk_support_id']; ?>" data-id="<?php echo $support['fk_support_id']; ?>" data-customer="<?php echo $support['fk_customer_id']; ?>"><?php echo $support['para_counsellor'] ?></a></td>
                        <td><a href="javascript:" class="view_sessions" data-support="<?php echo $support['pk_support_id']; ?>" data-id="<?php echo $support['fk_support_id']; ?>" data-customer="<?php echo $support['fk_customer_id']; ?>"><?php echo $support['total_session'] ?></a></td>
                        <td><a href="javascript:" class="view_family_counseling" data-support="<?php echo $support['pk_support_id']; ?>" data-id="<?php echo $support['fk_support_id']; ?>" data-customer="<?php echo $support['fk_customer_id']; ?>"><?php echo $support['family_counselling'] ?></a></td>
                    <?php } if ($support['support_name'] == 'social' || $support['support_name'] == 'economic') { ?>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                    <?php } ?>
                    <td><?php echo $support['start_date'] ?></td>
                    <td><?php echo $support['end_date'] ? $support['end_date'] : '-' ?></td>
                    <?php if ($support['support_name'] == 'psychosocial') { ?>
                        <td class="text-center">
                            <?php
                            if ($support['end_date']) {
                                echo "<span style='text-transform: capitalize'>" . $support['support_status'] . "</span>";
                            } else {
                                ?>
                                <a class="btn btn-flat btn-labeled btn-xs btn-info" href="<?php echo url('dev_support_management/manage_supports?action=add_edit_completion&support_id=' . $support['fk_support_id'] . '&support_customer=' . $support['fk_customer_id'] . '&support=' . $support['pk_support_id']) ?>" title="Complete Now"><i class="btn-label fa fa-edit"></i>Complete Now</a>
                                <?php
                            }
                            ?>
                        </td>
                    <?php } else { ?>    
                        <td class="text-center">-</td>
                    <?php } ?>
                    <td class="text-center">
                        <?php if ($support['support_name'] == 'psychosocial'): ?> 
                            <a href="javascript:" class="view_psychosocial_evaluation" data-support="<?php echo $support['pk_support_id']; ?>" data-id="<?php echo $support['fk_support_id']; ?>" data-customer="<?php echo $support['fk_customer_id']; ?>"><?php echo $support['total_evaluation'] ?></a>
                        <?php endif ?>
                        <?php if ($support['support_name'] == 'social'): ?>
                            <a href="javascript:" class="view_social_evaluation" data-support="<?php echo $support['pk_support_id']; ?>" data-id="<?php echo $support['fk_support_id']; ?>" data-customer="<?php echo $support['fk_customer_id']; ?>"><?php echo $support['total_evaluation'] ?></a>
                        <?php endif ?>
                        <?php if ($support['support_name'] == 'economic'): ?>
                            <a href="javascript:" class="view_economic_evaluation" data-support="<?php echo $support['pk_support_id']; ?>" data-id="<?php echo $support['fk_support_id']; ?>" data-customer="<?php echo $support['fk_customer_id']; ?>"><?php echo $support['total_evaluation'] ?></a>
                        <?php endif ?>
                    </td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_support')): ?>
                            <div class="btn-toolbar">
                                <?php if ($support['support_name'] == 'psychosocial'): ?>
                                    <a class="btn btn-flat btn-labeled btn-xs btn-info" href="<?php echo url('dev_support_management/manage_supports?action=add_edit_psychosocial&edit=') . $support['fk_support_id'] ?>" title="Edit Support"><i class="btn-label fa fa-edit"></i>Edit</a>    
                                    <a class="btn btn-flat btn-labeled btn-xs btn-primary" href="<?php echo url('dev_support_management/manage_supports?action=psycho_review&id=') . $support['pk_support_id'] ?>" title="Review Support"><i class="btn-label fa fa-eye"></i>Review</a>    
                                <?php endif ?>
                                <?php if ($support['support_name'] == 'social'): ?>
                                    <a class="btn btn-flat btn-labeled btn-xs btn-info" href="<?php echo url('dev_support_management/manage_supports?action=add_edit_social&edit=') . $support['fk_support_id'] . '&support_id=' . $support['pk_support_id'] ?>" title="Edit Support"><i class="btn-label fa fa-edit"></i>Edit</a>    
                                    <a class="btn btn-flat btn-labeled btn-xs btn-primary" href="<?php echo url('dev_support_management/manage_supports?action=social_review&id=') . $support['pk_support_id'] ?>" title="Review Support"><i class="btn-label fa fa-eye"></i>Review</a>
                                <?php endif ?>
                                <?php if ($support['support_name'] == 'economic'): ?>
                                    <a class="btn btn-flat btn-labeled btn-xs btn-info" href="<?php echo url('dev_support_management/manage_supports?action=add_edit_economic&edit=') . $support['fk_support_id'] . '&support_id=' . $support['pk_support_id'] ?>" title="Edit Support"><i class="btn-label fa fa-edit"></i>Edit</a>    
                                    <a class="btn btn-flat btn-labeled btn-xs btn-primary" href="<?php echo url('dev_support_management/manage_supports?action=economic_review&id=') . $support['pk_support_id'] ?>" title="Review Support"><i class="btn-label fa fa-eye"></i>Review</a>                               
                                <?php endif ?>
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
        <div class="pull-left">
            <?php echo $pagination?>
        </div>
    </div>
    <div class="dn">
        <div id="ajax_form_container"></div>
    </div>
</div>
<script>
    var BD_LOCATIONS = <?php echo getBDLocationJson() ?>;
    init.push(function () {
        new bd_new_location_selector({
            'division': $('#filter_division'),
            'district': $('#filter_district'),
            'sub_district': $('#filter_sub_district')
        });

        $(document).on('click', '.view_sessions', function () {
            var ths = $(this);
            var data_support = ths.attr('data-support');
            var data_id = ths.attr('data-id');
            var data_customer = ths.attr('data-customer');

            new in_page_add_event({
                show_submit_btn: false,
                edit_mode: true,
                edit_form_url: '<?php echo build_url(array('ajax_type' => 'get_sessions')) ?>',
                submit_button: 'ADD',
                form_title: 'Followup Counseling Support Sessions',
                form_container: $('#ajax_form_container'),
                ths: ths,
                url: '<?php echo build_url(array('ajax_type' => 'put_sessions')) ?>',
                additional_data: {'pk_support': data_support, 'support_id': data_id, 'support_customer': data_customer},
            });
        });

        $(document).on('click', '.view_family_counseling', function () {
            var ths = $(this);
            var data_support = ths.attr('data-support');
            var data_id = ths.attr('data-id');
            var data_customer = ths.attr('data-customer');

            new in_page_add_event({
                show_submit_btn: false,
                edit_mode: true,
                edit_form_url: '<?php echo build_url(array('ajax_type' => 'get_family_counseling')) ?>',
                submit_button: 'ADD',
                form_title: 'Family Counseling Support',
                form_container: $('#ajax_form_container'),
                ths: ths,
                url: '<?php echo build_url(array('ajax_type' => 'put_sessions')) ?>',
                additional_data: {'pk_support': data_support, 'support_id': data_id, 'support_customer': data_customer},
            });
        });

        $(document).on('click', '.view_counsellor', function () {
            var ths = $(this);
            var data_support = ths.attr('data-support');
            var data_id = ths.attr('data-id');
            var data_customer = ths.attr('data-customer');

            new in_page_add_event({
                show_submit_btn: false,
                edit_mode: true,
                edit_form_url: '<?php echo build_url(array('ajax_type' => 'get_counsellors')) ?>',
                submit_button: 'ADD',
                form_title: 'Social Counseling Supports',
                form_container: $('#ajax_form_container'),
                ths: ths,
                url: '<?php echo build_url(array('ajax_type' => 'put_sessions')) ?>',
                additional_data: {'pk_support': data_support, 'support_id': data_id, 'support_customer': data_customer},
            });
        });

        $(document).on('click', '.view_psychosocial_evaluation', function () {
            var ths = $(this);
            var data_support = ths.attr('data-support');
            var data_id = ths.attr('data-id');
            var data_customer = ths.attr('data-customer');

            new in_page_add_event({
                show_submit_btn: false,
                edit_mode: true,
                edit_form_url: '<?php echo build_url(array('ajax_type' => 'get_psychosocial_evaluation')) ?>',
                submit_button: 'ADD',
                form_title: 'Psychosocial Evaluation',
                form_container: $('#ajax_form_container'),
                ths: ths,
                url: '<?php echo build_url(array('ajax_type' => 'put_sessions')) ?>',
                additional_data: {'pk_support': data_support, 'support_id': data_id, 'support_customer': data_customer},
            });
        });

        $(document).on('click', '.view_social_evaluation', function () {
            var ths = $(this);
            var data_support = ths.attr('data-support');
            var data_id = ths.attr('data-id');
            var data_customer = ths.attr('data-customer');

            new in_page_add_event({
                show_submit_btn: false,
                edit_mode: true,
                edit_form_url: '<?php echo build_url(array('ajax_type' => 'get_social_evaluation')) ?>',
                submit_button: 'ADD',
                form_title: 'Social Evaluation',
                form_container: $('#ajax_form_container'),
                ths: ths,
                url: '<?php echo build_url(array('ajax_type' => 'put_sessions')) ?>',
                additional_data: {'pk_support': data_support, 'support_id': data_id, 'support_customer': data_customer},
            });
        });

        $(document).on('click', '.view_economic_evaluation', function () {
            var ths = $(this);
            var data_support = ths.attr('data-support');
            var data_id = ths.attr('data-id');
            var data_customer = ths.attr('data-customer');

            new in_page_add_event({
                show_submit_btn: false,
                edit_mode: true,
                edit_form_url: '<?php echo build_url(array('ajax_type' => 'get_economic_evaluation')) ?>',
                submit_button: 'ADD',
                form_title: 'Economic Evaluation',
                form_container: $('#ajax_form_container'),
                ths: ths,
                url: '<?php echo build_url(array('ajax_type' => 'put_sessions')) ?>',
                additional_data: {'pk_support': data_support, 'support_id': data_id, 'support_customer': data_customer},
            });
        });
    });
</script>