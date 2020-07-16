<?php
$year = $_GET['year'];
$branch = $_GET['branch'];
$project = $_GET['project'];

if(!$year || !$branch || !$project){
    add_notification('Please select valid year, branch and project', 'error');
    header('location: '.build_url(null, array('action')));
    exit();
}

if($_POST['set_new_drsc_targets']){
    $activity_targets = $_POST['activity_targets'];

    //delete previous targets (if any)
    $sql = "DELETE FROM mis_activity_targets WHERE fk_project_id = '$project' AND fk_branch_id = '$branch' AND mis_activity_targets._year = '$year'";
    $deleteNow = $devdb->query($sql);

    //insert new targets
    foreach($activity_targets as $activityID=>$target){
        $insertData = array(
            'fk_project_id' => $project,
            'fk_branch_id' => $branch,
            'fk_activity_id' => $activityID,
            '_target' => $target ? $target : 0,
            '_year' => $year
        );

        $ret = $devdb->insert_update('mis_activity_targets', $insertData);
    }

    add_notification('All targets were successfully set for the given branch, year and project', 'success');
    header('Location: '.build_url(null, array('action')));
    exit();
}

$_activities = $this->get_acitivity_categories(array('deletion_status' => 'not_deleted', 'child_only' => true));
$activities = array();//$activities['data'];
foreach($_activities['data'] as $i=>$v){
    $activities[$v['pk_activity_cat_id']] = $v;
}

$projects = jack_obj('dev_project_management');
$theProject = $projects->get_projects(array('id' => $project, 'single' => true));

$branches = jack_obj('dev_branch_management');
$theBranch = $branches->get_branches(array('id' => $branch, 'single' => true));

$args = array(
    'project' => $project,
    'branch' => $branch,
    'year' => $year,
);

$targets = $this->get_mis_targets($args);
$targets = $targets['data'];

doAction('render_start');
?>
<div class="page-header">
    <h1>Update Targets</h1>
    <h4><strong>Branch: <?php echo $theBranch['branch_name']; ?>, Project: <?php echo $theProject['project_short_name']; ?>, Year: <?php echo $year; ?></strong></h4>
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
    <form name="set_targets" action="" method="post">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-primary table-responsive">
                        <table class="table table-bordered table-condensed table-striped table-hover">
                            <thead>
                            <tr>
                                <th class="vam">#</th>
                                <th class="vam">Activity</th>
                                <th class="vam tar" width="100">Target</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($targets){
                                $count = 1;
                                foreach($targets as $target){
                                    unset($activities[$target['fk_activity_id']]);
                                    ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td><?php echo $target['activity_name']; ?></td>
                                        <td>
                                            <div class="form-group">
                                                <input name="activity_targets[<?php echo $target['fk_activity_id']; ?>]" type="number" class="form-control tar" value="<?php echo $target['_target']; ?>" />
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            if($activities){
                                foreach($activities as $activity){
                                    ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td><?php echo $activity['cat_name']; ?></td>
                                        <td>
                                            <div class="form-group">
                                                <input name="activity_targets[<?php echo $activity['pk_activity_cat_id']; ?>]" type="number" class="form-control tar" value="0" />
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" name="set_new_drsc_targets" class="btn btn-success" value="1">Update Targets</button>
        </div>
    </form>
</div>