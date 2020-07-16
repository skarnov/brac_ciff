<?php

$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_branch = $_GET['branch_id'] ? $_GET['branch_id'] : null;
$filter_year = $_GET['year'] ? $_GET['year'] : null;

if($_POST['set_new_drsc_targets']){
    $project = $_POST['project'];
    $year = $_POST['year'];
    $branches = $_POST['branch'];
    $activity_targets = $_POST['activity_targets'];

    //delete previous targets (if any)
    foreach($branches as $eachBranch){
        $sql = "DELETE FROM mis_activity_targets WHERE fk_project_id = '$project' AND fk_branch_id = '$eachBranch' AND mis_activity_targets._year = '$year'";
        $deleteNow = $devdb->query($sql);
    }

    //insert new targets
    foreach($branches as $eachBranch){
        foreach($activity_targets as $activityID=>$target){
            $insertData = array(
                'fk_project_id' => $project,
                'fk_branch_id' => $eachBranch,
                'fk_activity_id' => $activityID,
                '_target' => $target ? $target : 0,
                '_year' => $year
            );

            $ret = $devdb->insert_update('mis_activity_targets', $insertData);
        }
    }

    add_notification('All targets were successfully set to branches for the given year and project', 'success');
    header('Location: '.build_url(null, array('action')));
    exit();
}

$activities = $this->get_acitivity_categories(array('deletion_status' => 'not_deleted', 'project_id' => $filter_project, 'child_only' => true));
$activities = $activities['data'];

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects(array('single' => false));

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches(array('branch_type_slug' => 'drsc', 'single' => false));
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
    <form name="set_targets" action="" method="post">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-2 form-group">
                    <label>Year</label>
                    <input class="form-control" required type="number" name="year" value="<?php echo $filter_year ? $filter_year :'' ?>" />
                </div>
                <div class="col-sm-2 form-group" style="display: none;">
                    <label>Project</label>
                    <select class="form-control" required name="project">
                        <option value="">Select One</option>
                        <?php
                        foreach ($all_projects['data'] as $i => $v) {
                            ?>
                            <option value="<?php echo $v['pk_project_id'] ?>" <?php echo $filter_project == $v['pk_project_id'] ? 'selected' : ''?>><?php echo $v['project_short_name'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-8 form-group">
                    <label>Branch</label>
                    <select class="form-control adv_select " multiple required name="branch[]">
                        <option value="">Select One</option>
                        <?php
                        foreach ($all_branches['data'] as $i => $v) {
                            ?>
                        <option value="<?php echo $v['pk_branch_id'] ?>" <?php echo $filter_branch == $v['pk_branch_id'] ? 'selected' : ''?>><?php echo $v['branch_name'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
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
                            if($activities){
                                $count = 1;
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
            <button type="submit" name="set_new_drsc_targets" class="btn btn-success" value="1">Save Targets</button>
        </div>
    </form>
</div>