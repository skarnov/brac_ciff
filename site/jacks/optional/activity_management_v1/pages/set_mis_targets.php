<?php
$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects(array('single' => false));

$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_branch = $_GET['branch_id'] ? $_GET['branch_id'] : null;
$filter_year = $_GET['year'] ? $_GET['year'] : null;

if ($filter_project) {
    header('location: ' . url('admin/dev_activity_management_v1/manage_mis_targets?action=list_targets&project_id='.$filter_project.'&branch_id='.$filter_branch.'&year='.$filter_year));
    exit();
}

doAction('render_start');
?>
<div class="page-header">
    <h1>Set Targets</h1>
    <div class="oh">
<?php
echo linkButtonGenerator(array(
    'href' => build_url(null, array('action')),
    'action' => 'list',
    'text' => 'All Targets',
    'title' => 'All Targets',
    'icon' => 'icon_list',
    'size' => 'sm'
));
?>
    </div>
</div>
<div class="panel panel-info">
    <form action="" method="GET">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-2 form-group">
                    <label>Project</label>
                    <select class="form-control" required name="project_id">
                        <option value="">Select One</option>
<?php
foreach ($all_projects['data'] as $i => $v) {
    ?>
                            <option value="<?php echo $v['pk_project_id'] ?>"><?php echo $v['project_short_name'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-xs btn-success btn-flat btn-labeled"><i class="btn-label fa fa-filter"></i>Show Activities</button>
                </div>
            </div>
        </div>
    </form>
</div>