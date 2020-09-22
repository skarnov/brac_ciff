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
    <h1>MIS Reports</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
//                'href' => $myUrl . '?action=add_edit_target',
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download',
                'title' => 'Download MIS',
            ));
            ?>
        </div>
    </div>
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
                <th>SL No</th>
                <th>Activity</th>
                <th>Target</th>
                <th>Achievement</th>
                <th>Variance</th>
                <th>Male</th>
                <th>Female</th>
                <th>Boy</th>
                <th>Girl</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($results['data'] as $value) {
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $value['activity_name']; ?></td>
                    <td><?php echo $value['activity_target']; ?></td>
                    <td><?php echo $value['activity_achievement']; ?></td>
                    <td><?php echo $value['activity_target'] - $value['activity_achievement']; ?></td>
                    <td><?php echo $value['achievement_male']; ?></td>
                    <td><?php echo $value['achievement_female']; ?></td>
                    <td><?php echo $value['achievement_boy']; ?></td>
                    <td><?php echo $value['achievement_girl']; ?></td>
                </tr>
                <?php
                $i++;
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