<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_month = $_GET['month'] ? $_GET['month'] : null;
$filter_branch_id = $_GET['branch_id'] ? $_GET['branch_id'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_project_id = $_GET['project_id'] ? $_GET['project_id'] : null;

$args = array(
    'select_fields' => array(
        'id' => 'dev_targets.pk_target_id',
        'branch_id' => 'dev_targets.fk_branch_id',
        'project_id' => 'dev_targets.fk_project_id',
        'project_name' => 'dev_projects.project_short_name',
        'branch_name' => 'dev_branches.branch_name',
        'district' => 'dev_targets.branch_district',
        'sub_district' => 'dev_targets.branch_sub_district',
        'month' => 'dev_targets.month',
        'activity_name' => 'dev_activities.activity_name',
        'activity_target' => 'dev_targets.activity_target',
        'activity_achievement' => 'dev_targets.activity_achievement',
    ),
    'month' => $filter_month,
    'branch_id' => $filter_branch_id,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'project_id' => $filter_project_id,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_target_id',
        'order' => 'DESC'
    ),
);

$results = $this->get_targets($args);
$pagination = pagination($results['total'], $per_page_items, $start);

$filterString = array();
if ($filter_project_id)
    $filterString[] = 'Project: ' . $filter_project_id;
if ($filter_month)
    $filterString[] = 'Month: ' . $filter_month;
if ($filter_branch_id)
    $filterString[] = 'Branch: ' . $filter_branch_id;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Sub District: ' . $filter_sub_district;

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects();

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches();

$all_months = $this->get_months();

doAction('render_start');
?>
<div class="page-header">
    <h1>All Achievements</h1>
</div>
<?php
ob_start();
?>
<div class="form-group col-sm-3">
    <label>Project</label>
    <div class="select2-primary">
        <select class="form-control" name="project_id">
            <option value="">Select One</option>
            <?php foreach ($all_projects['data'] as $project) : ?>
                <option value="<?php echo $project['pk_project_id'] ?>" <?php echo ($project['pk_project_id'] == $filter_project_id) ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Branch</label>
    <div class="select2-primary">
        <select class="form-control" name="branch_id">
            <option value="">Select One</option>
            <?php foreach ($all_branches['data'] as $branch) : ?>
                <option value="<?php echo $branch['pk_branch_id'] ?>" <?php echo ($branch['pk_branch_id'] == $filter_branch_id) ? 'selected' : '' ?>><?php echo $branch['branch_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
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
<div class="form-group col-sm-3">
    <label>Month</label>
    <div class="select2-primary">
        <select class="form-control" name="month">
            <option value="">Select One</option>
            <?php foreach ($all_months as $i => $month) :
                ?>
                <option value="<?php echo $i ?>" <?php echo ($i == $filter_month) ? 'selected' : '' ?>><?php echo $month ?></option>
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
        <?php echo searchResultText($results['total'], $start, $per_page_items, count($results['data']), 'target') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Project</th>
                <th>Branch</th>
                <th>District</th>
                <th>Sub-District</th>
                <th>Month</th>
                <th>Activity Name</th>
                <th>Target</th>
                <th>Achievement</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results['data'] as $i => $value) {
                ?>
                <tr>
                    <td><?php echo $value['project_short_name']; ?></td>
                    <td><?php echo $value['branch_name']; ?></td>
                    <td><?php echo $value['branch_district']; ?></td>
                    <td><?php echo $value['branch_sub_district']; ?></td>
                    <td>
                        <?php
                        if ($value['month'] == '1') {
                            echo 'January';
                        } elseif ($value['month'] == '2') {
                            echo 'February';
                        } elseif ($value['month'] == '3') {
                            echo 'March';
                        } elseif ($value['month'] == '4') {
                            echo 'April';
                        } elseif ($value['month'] == '5') {
                            echo 'May';
                        } elseif ($value['month'] == '6') {
                            echo 'June';
                        } elseif ($value['month'] == '7') {
                            echo 'July';
                        } elseif ($value['month'] == '8') {
                            echo 'August';
                        } elseif ($value['month'] == '9') {
                            echo 'September';
                        } elseif ($value['month'] == '10') {
                            echo 'October';
                        } elseif ($value['month'] == '11') {
                            echo 'November';
                        } elseif ($value['month'] == '12') {
                            echo 'December';
                        }
                        ?>
                    </td>
                    <td><?php echo $value['activity_name']; ?></td>
                    <td><?php echo $value['activity_target']; ?></td>
                    <td><?php echo $value['activity_achievement']; ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <div class="table-footer oh">
        <div class="pull-left">
            <?php echo $pagination ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var BD_LOCATIONS = <?php echo getBDLocationJson(); ?>;
    init.push(function () {
        new bd_new_location_selector({
            'division': $('#filter_division'),
            'district': $('#filter_district'),
            'sub_district': $('#filter_sub_district'),
        });
    });
</script>