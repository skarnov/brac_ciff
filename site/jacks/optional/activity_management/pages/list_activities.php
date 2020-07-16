<?php
$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_branch = $_GET['branch_id'] ? $_GET['branch_id'] : null;
$filter_month = $_GET['month'] ? $_GET['month'] : null;
$filter_year = $_GET['year'] ? $_GET['year'] : null;

if (!$filter_month || !$filter_year) {
    if (!$filter_month)
        $filter_month = date('n');
    if (!$filter_year)
        $filter_year = date('Y');
    header('location:' . build_url(array('month' => $filter_month, 'year' => $filter_year)));
    exit();
}

if ($_GET['ajax_type'] == 'get_new_target_form') {
    echo json_encode($this->get_new_target_form());
    exit();
} else if ($_GET['ajax_type'] == 'put_new_target_form') {
    echo json_encode($this->put_new_target_form($_POST));
    exit();
} else if ($_GET['ajax_type'] == 'get_target_edit_form') {
    $ID = $_POST['id'] ? $_POST['id'] : null;
    echo json_encode($this->get_target_edit_form($ID));
    exit();
} else if ($_GET['ajax_type'] == 'put_target_edit_form') {
    $ID = $_POST['id'] ? $_POST['id'] : null;
    echo json_encode($this->put_target_edit_form($_POST, $ID));
    exit();
} else if ($_GET['ajax_type'] == 'add_new_activity') {
    echo json_encode($this->get_new_activity_category_form(null, $_POST['initial_parent']));
    exit();
} else if ($_GET['ajax_type'] == 'put_new_activity') {
    echo json_encode($this->put_new_activity_category_form($_POST));
    exit();
} else if ($_GET['ajax_type'] == 'get_new_activity_group_form') {
    echo json_encode($this->get_new_activity_group_form());
    exit();
} else if ($_GET['ajax_type'] == 'put_new_activity_group_form') {
    echo json_encode($this->put_new_activity_group_form($_POST));
    exit();
}

if ($_POST['ajax_type'] == 'get_child_activities') {
    echo json_encode($this->get_child_activities($_POST['parent']));
    exit();
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$args = array(
    'project' => $filter_project,
    'branch' => $filter_branch,
    'month' => $filter_month,
    'year' => $filter_year,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_activity_id',
        'order' => 'DESC'
    ),
);

$activities = $this->get_activities($args);
$pagination = pagination($activities['total'], $per_page_items, $start);

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects(array('single' => false));

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches(array('single' => false));
doAction('render_start');
?>
<div class="page-header">
    <h1>All Activity Targets</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'id' => 'add_target',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'Add Target',
                'title' => 'Add Target',
            ));
            ?>
            <?php
            echo linkButtonGenerator(array(
                'href' => build_url(array('action' => 'activities'), array('start', 'month', 'year')),
                'action' => 'list',
                'icon' => 'icon_list',
                'text' => 'Activity Groups/Types',
                'title' => 'Activity Groups/Types',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<div class="col-sm-2 form-group">
    <label>Project</label>
    <select class="form-control" name="project_id">
        <option value="">Select One</option>
        <?php
        foreach ($all_projects['data'] as $i => $v) {
            ?>
            <option value="<?php echo $v['pk_project_id'] ?>" <?php if($filter_project == $v['pk_project_id']) echo 'selected' ?>><?php echo $v['project_short_name'] ?></option>
            <?php
        }
        ?>
    </select>
</div>
<div class="col-sm-2 form-group">
    <label>Branch</label>
    <select class="form-control" name="branch_id">
        <option value="">Select One</option>
        <?php
        foreach ($all_branches['data'] as $i => $v) {
            ?>
            <option value="<?php echo $v['pk_branch_id'] ?>" <?php if($filter_branch == $v['pk_branch_id']) echo 'selected' ?>><?php echo $v['branch_name'] ?></option>
            <?php
        }
        ?>
    </select>
</div>
<div class="col-sm-2 form-group">
    <label>Month</label>
    <select class="form-control" name="month">
        <?php
        foreach ($this->months as $i => $v) {
            ?>
            <option value="<?php echo $i ?>" <?php echo $filter_month == $i ? 'selected' : '' ?>><?php echo $v ?></option>
            <?php
        }
        ?>
    </select>
</div>
<div class="col-sm-2 form-group">
    <label>Year</label>
    <input class="form-control" type="number" name="year" value="<?php echo $filter_year ?>" />
</div>
<?php
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="table-primary table-responsive">
    <div class="table-header">
        <?php echo searchResultText($activities['total'], $start, $per_page_items, count($activities['data']), 'activities') ?>
    </div>
    <table class="table table-bordered table-condensed table-striped table-hover">
        <thead>
            <tr>
                <th class="vam">#</th>
                <th class="vam">Project</th>
                <th class="vam">Branch</th>
                <th class="vam">Month</th>
                <th class="vam">Year</th>
                <th class="vam">Activity Group</th>
                <th class="vam">Activity</th>
                <th class="tar">Target</th>
                <th class="action_column">...</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = $per_page_items * $start;
            foreach ($activities['data'] as $i => $activity) {
                ?>
                <tr>
                    <td><?php echo ++$count; ?></td>
                    <td><?php echo $activity['project_short_name']; ?></td>
                    <td><?php echo $activity['branch_name']; ?></td>
                    <td><?php echo $this->months[$activity['entry_month']]; ?></td>
                    <td><?php echo $activity['entry_year']; ?></td>
                    <td><?php echo $activity['parent_category_name']; ?></td>
                    <td><?php echo $activity['category_name']; ?></td>
                    <td class="tar"><?php echo $activity['activity_target']; ?></td>
                    <td class="action_column">
                        <?php
                        echo linkButtonGenerator(array(
                            'classes' => 'edit_target',
                            'attributes' => array('data-id' => $activity['pk_activity_id']),
                            'action' => 'edit',
                            'icon' => 'icon_edit',
                            'text' => 'Update',
                            'title' => 'Update',
                        ));
                        ?>
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
<div class="dn">
    <div id="ajax_form_container"></div>
</div>
<div class="dn">
    <div id="activity_category_form_container"></div>
</div>
<div class="dn">
    <div id="activity_group_form_container"></div>
</div>
<script type="text/javascript">
    var thisMonth = <?php echo $filter_month ?>;
    var thisYear = <?php echo $filter_year ?>;

    init.push(function () {
        $('#add_target').on('click', function () {
            var ths = $(this);
            var data_id = ths.attr('data-id');
            var is_update = typeof data_id !== 'undefined' ? data_id : false;
            var thsRow = is_update ? ths.closest('tr') : null;

            new in_page_add_event({
                edit_mode: true,
                edit_form_url: '<?php echo build_url(array('ajax_type' => 'get_new_target_form')) ?>',
                submit_button: is_update ? 'UPDATE' : 'ADD',
                form_title: is_update ? 'Update Role' : 'Add New Target',
                form_container: $('#ajax_form_container'),
                ths: ths,
                url: '<?php echo build_url(array('ajax_type' => 'put_new_target_form')) ?>',
                additional_data: {
                    month: thisMonth,
                    year: thisYear
                },
                callback: function (data) {
                    window.location.reload();
                }
            });
        });

        $('.edit_target').on('click', function () {
            var ths = $(this);
            var data_id = ths.attr('data-id');
            var is_update = typeof data_id !== 'undefined' ? data_id : false;
            var thsRow = is_update ? ths.closest('tr') : null;

            new in_page_add_event({
                edit_mode: true,
                edit_form_url: '<?php echo build_url(array('ajax_type' => 'get_target_edit_form')) ?>',
                submit_button: 'Update',
                form_title: 'Update Target',
                form_container: $('#ajax_form_container'),
                ths: ths,
                url: '<?php echo build_url(array('ajax_type' => 'put_target_edit_form')) ?>',
                additional_data: {
                    id: data_id,
                },
                callback: function (data) {
                    window.location.reload();
                }
            });
        });

        $(document).on('click', '.add_new_activity', function () {
            var ths = $(this);
            var parent = $('#parent_activity').val();
            new in_page_add_event({
                edit_mode: true,
                edit_form_url: '<?php echo build_url(array('ajax_type' => 'add_new_activity')) ?>',
                submit_button: 'Add',
                form_title: 'Add New Activity',
                form_container: $('#activity_category_form_container'),
                ths: ths,
                url: '<?php echo build_url(array('ajax_type' => 'put_new_activity')) ?>',
                title: 'cat_name',
                value: 'pk_activity_cat_id',
                additional_data: {initial_parent: parent},
                callback: function (data) {
                    if (data.fk_parent_id == parent) {
                        activity_options += '<option value="' + data.pk_activity_cat_id + '">' + data.cat_name + '</option>';
                        $('.each_child_activity').each(function (i, e) {
                            if (!$(e).find('[value="' + data.pk_activity_cat_id + '"]').length) {
                                $(e).append('<option value="' + data.pk_activity_cat_id + '">' + data.cat_name + '</option>');
                            }
                        });
                    }
                }
            });
        });
        $(document).on('click', '.add_new_activity_group', function () {
            var ths = $(this);
            new in_page_add_event({
                edit_mode: true,
                edit_form_url: '<?php echo build_url(array('ajax_type' => 'get_new_activity_group_form')) ?>',
                submit_button: 'Add',
                form_title: 'Add New Activity Output',
                form_container: $('#activity_group_form_container'),
                ths: ths,
                url: '<?php echo build_url(array('ajax_type' => 'put_new_activity_group_form')) ?>',
                title: 'cat_name',
                value: 'pk_activity_cat_id',
                callback: function (data) {}
            });
        });
    });
</script>