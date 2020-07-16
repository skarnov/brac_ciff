<?php
$months = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');

$year = $_POST['year'];
$drsc = $_POST['drsc'];
$ulo = $_POST['ulo'];
$project = $_POST['project'];

if($staffManager->isStaff() && $myBranchType == 'drsc') $drsc = $_config['user']['user_branch'];

if(!$year || !$drsc || !$ulo || !$project){
    add_notification('Please select valid year, drsc, ulo and project', 'error');
    header('location: '.build_url(null, array('action')));
    exit();
}

$branches = jack_obj('dev_branch_management');

$theDrsc = $branches->get_branches(array('id' => $drsc, 'single' => true));
$theUlo = $branches->get_branches(array('id' => $ulo, 'single' => true));

$projects = jack_obj('dev_project_management');
$theProject = $projects->get_projects(array('id' => $project, 'single' => true));

if(!$theDrsc || !$theProject || !$theUlo){
    add_notification('Please select valid drsc, ulo and project', 'error');
    header('location: '.build_url(null, array('action')));
    exit();
}

if($theUlo['fk_branch_id'] != $drsc){
    add_notification('Please select valid drsc and ulo', 'error');
    header('location: '.build_url(null, array('action')));
    exit();
}

if($_POST['xyz']){
    $distributions = $_POST['distributions'];

    //insert new targets
    foreach($distributions as $activityID=>$targets){
        $totalTargets = 0;

        $insertData = array(
            'fk_project_id' => $project,
            'fk_activity_id' => $activityID,
            '_year' => $year,
            'fk_parent_branch_id' => $drsc,
            'fk_branch_id' => $ulo,
            'january_target' => $targets['january'] ? $targets['january'] : 0,
            'february_target' => $targets['february'] ? $targets['february'] : 0,
            'march_target' => $targets['march'] ? $targets['march'] : 0,
            'april_target' => $targets['april'] ? $targets['april'] : 0,
            'may_target' => $targets['may'] ? $targets['may'] : 0,
            'june_target' => $targets['june'] ? $targets['june'] : 0,
            'july_target' => $targets['july'] ? $targets['july'] : 0,
            'august_target' => $targets['august'] ? $targets['august'] : 0,
            'september_target' => $targets['september'] ? $targets['september'] : 0,
            'october_target' => $targets['october'] ? $targets['october'] : 0,
            'november_target' => $targets['november'] ? $targets['november'] : 0,
            'december_target' => $targets['december'] ? $targets['december'] : 0,
        );

        $totalTargets = $insertData['january_target']
                        + $insertData['february_target']
                        + $insertData['march_target']
                        + $insertData['april_target']
                        + $insertData['may_target']
                        + $insertData['june_target']
                        + $insertData['july_target']
                        + $insertData['august_target']
                        + $insertData['september_target']
                        + $insertData['october_target']
                        + $insertData['november_target']
                        + $insertData['december_target'];

        $insertData['total_target'] = $totalTargets;

        if($targets['old_id']){
            $ret = $devdb->insert_update('mis_activity_distribution', $insertData, " pk_mis_distribution_id = '".$targets['old_id']."'");
        }
        else {
            $ret = $devdb->insert_update('mis_activity_distribution', $insertData);
        }
    }

    add_notification('All targets were successfully distributed to given branch, year and project', 'success');
    header('Location: '.build_url(null, array('action')));
    exit();
}

$args = array(
    'project' => $project,
    'drsc' => $drsc,
    'ulo' => $ulo,
    'year' => $year,
);

$distributions = $this->get_mis_targets_with_distribution($args);
$distributions = $distributions['data'];

doAction('render_start');
?>
<div class="page-header">
    <h1>Set/Update Targets Distribution</h1>
    <h4><strong>DRSC: <?php echo $theDrsc['branch_name']; ?>, ULO: <?php echo $theUlo['branch_name']; ?>, Project: <?php echo $theProject['project_short_name']; ?>, Year: <?php echo $year; ?></strong></h4>
    <div class="oh">
        <?php
        echo linkButtonGenerator(array(
            'href' => build_url(null, array('action')),
            'action' => 'list',
            'text' => 'All Distributions',
            'title' => 'All Distributions',
            'icon' => 'icon_list',
            'size' => 'sm'
        ));
        ?>
    </div>
</div>
<div class="panel panel-info">
    <form name="set_targets" action="" method="post">
        <input type="hidden" name="year" value="<?php echo $year; ?>">
        <input type="hidden" name="project" value="<?php echo $project; ?>">
        <input type="hidden" name="drsc" value="<?php echo $drsc; ?>">
        <input type="hidden" name="ulo" value="<?php echo $ulo; ?>">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-primary table-responsive">
                        <table class="large_tables table table-bordered table-condensed table-striped table-hover">
                            <thead>
                            <tr>
                                <th class="vam">#</th>
                                <th class="vam">Activity</th>
                                <th class="tar">Yearly Target</th>
                                <th class="tar">January</th>
                                <th class="tar">February</th>
                                <th class="tar">March</th>
                                <th class="tar">April</th>
                                <th class="tar">May</th>
                                <th class="tar">June</th>
                                <th class="tar">July</th>
                                <th class="tar">August</th>
                                <th class="tar">September</th>
                                <th class="tar">October</th>
                                <th class="tar">November</th>
                                <th class="tar">December</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($distributions){
                                $count = 1;
                                foreach($distributions as $d){
                                    ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td><?php echo $d['activity_name']; ?></td>
                                        <td class="tar">
                                            <input type="hidden" name="distributions[<?php echo $d['activity_id']; ?>][old_id]" value="<?php echo $d['pk_mis_distribution_id']; ?>">
                                            <?php echo $d['parent_target']; ?>
                                        </td>
                                        <?php
                                        foreach($months as $m){
                                            ?>
                                            <td>
                                                <div class="form-group mb0">
                                                    <input name="distributions[<?php echo $d['activity_id']; ?>][<?php echo $m; ?>]" type="text" class="form-control hidden_spin tar" value="<?php echo $d[$m.'_target']; ?>" />
                                                </div>
                                            </td>
                                            <?php
                                        }
                                        ?>
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
            <button type="submit" name="xyz" class="btn btn-success" value="1">Set/Update Targets Distribution</button>
        </div>
    </form>
</div>