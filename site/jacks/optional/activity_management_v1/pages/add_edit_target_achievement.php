<?php
$months = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');

$month = $_POST['month'];
$year = $_POST['year'];
$drsc = $_POST['drsc'];
$ulo = $_POST['ulo'];
$project = $_POST['project'];

if ($staffManager->isStaff() && $myBranchType == 'drsc')
    $drsc = $_config['user']['user_branch'];
if ($staffManager->isStaff() && $myBranchType == 'ulo') {
    $myBranch = $staffManager->getMyBranch();
    $drsc = $myBranch['fk_branch_id'];
    $ulo = $_config['user']['user_branch'];
}

//TODO: also check if te ulo is under this drsc

if (!$month || !$year || !$drsc || !$ulo || !$project) {
    add_notification('Please select valid month, year, drsc, ulo and project', 'error');
    header('location: ' . build_url(null, array('action')));
    exit();
}

$branches = jack_obj('dev_branch_management');

$theDrsc = $branches->get_branches(array('id' => $drsc, 'single' => true));
$theUlo = $branches->get_branches(array('id' => $ulo, 'single' => true));

$projects = jack_obj('dev_project_management');
$theProject = $projects->get_projects(array('id' => $project, 'single' => true));

if (!$theDrsc || !$theProject || !$theUlo) {
    add_notification('Please select valid drsc, ulo and project', 'error');
    header('location: ' . build_url(null, array('action')));
    exit();
}

if ($theUlo['fk_branch_id'] != $drsc) {
    add_notification('Please select valid drsc and ulo', 'error');
    header('location: ' . build_url(null, array('action')));
    exit();
}

if ($_POST['xyz']) {
    $distributions = $_POST['distributions'];    
    //insert new targets
    foreach ($distributions as $activityID => $targets) {
        $rowTotal = 0;
        $insertData = array(
            'fk_project_id' => $project,
            'fk_activity_id' => $activityID,
            '_year' => $year,
            'fk_parent_branch_id' => $drsc,
            'fk_branch_id' => $ulo,
            $month . '_achievement' => $targets['total'] ? $targets['total'] : 0,
            $month . '_male' => $targets['male'] ? $targets['male'] : 0,
            $month . '_female' => $targets['female'] ? $targets['female'] : 0,
        );

        if ($targets['old_id']) {
            $ret = $devdb->insert_update('mis_activity_distribution', $insertData, " pk_mis_distribution_id = '" . $targets['old_id'] . "'");
        } else {
            $ret = $devdb->insert_update('mis_activity_distribution', $insertData);
        }
    }

    //update total achievement count

    $updateSQL = "UPDATE mis_activity_distribution 
                    SET
                        total_male = january_male + february_male + march_male + april_male + may_male + june_male + july_male + august_male + september_male + october_male + november_male + december_male
                      , total_female = january_female + february_female + march_female + april_female + may_female + june_female + july_female + august_female + september_female + october_female + november_female + december_female
                      , total_achievement = (january_achievement + february_achievement + march_achievement + april_achievement + may_achievement + june_achievement + july_achievement + august_achievement + september_achievement + october_achievement + november_achievement + december_achievement)
                        
                  WHERE 
                      fk_project_id = '$project'
                      AND mis_activity_distribution._year = '$year' 
                      AND fk_parent_branch_id = '$drsc' 
                      AND fk_branch_id = '$ulo'
                  ";

    $updateNow = $devdb->query($updateSQL);

    add_notification('All achievements were successfully set/updated to given branch, year and project', 'success');
    header('Location: ' . build_url(null, array('action')));
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
    <h1>Set/Update Targets Achievement</h1>
    <h4><strong>DRSC: <?php echo $theDrsc['branch_name']; ?>, ULO: <?php echo $theUlo['branch_name']; ?>, Project: <?php echo $theProject['project_short_name']; ?>, Year: <?php echo $year; ?>, Month: <?php echo strtoupper($month); ?></strong></h4>
    <div class="oh">
        <?php
        echo linkButtonGenerator(array(
            'href' => build_url(null, array('action')),
            'action' => 'list',
            'text' => 'All Achievements',
            'title' => 'All Achievements',
            'icon' => 'icon_list',
            'size' => 'sm'
        ));
        ?>
    </div>
</div>
<div class="panel panel-info">
    <form name="set_targets" action="" method="post">
        <input type="hidden" name="month" value="<?php echo $month; ?>">
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
                                    <th rowspan="2" class="vam">#</th>
                                    <th rowspan="2" class="vam">Activity</th>
                                    <th rowspan="2" class="vam" style="text-transform: capitalize"><?php echo $month; ?> Target</th>
                                    <th colspan="4" class="tac vam">Achievement</th>
                                </tr>
                                <tr>
                                    <th class="tar" style="text-transform: capitalize"><?php echo $month; ?> Achievement</th>
                                    <th class="tar">Male</th>
                                    <th class="tar">Female</th>
                                    <th class="tar">Total (M+F)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($distributions) {
                                    $count = 1;
                                    foreach ($distributions as $d) {
                                        ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td><?php echo $d['activity_name']; ?></td>
                                            <td class="tar">
                                                <input type="hidden" name="distributions[<?php echo $d['activity_id']; ?>][old_id]" value="<?php echo $d['pk_mis_distribution_id']; ?>">
                                                <?php echo $d[$month . '_target']; ?>
                                            </td>
                                            <td class="tar">
                                                <div class="form-group mb0">
                                                    <input name="distributions[<?php echo $d['activity_id']; ?>][total]" type="text" class="form-control hidden_spin tar" value="<?php echo $d[$month . '_achievement']; ?>" />
                                                </div>
                                            </td>
                                            <td class="tar">
                                                <div class="form-group mb0">
                                                    <input name="distributions[<?php echo $d['activity_id']; ?>][male]" type="text" class="form-control hidden_spin tar male_part" value="<?php echo $d[$month . '_male']; ?>" />
                                                </div>
                                            </td>
                                            <td class="tar">
                                                <div class="form-group mb0">
                                                    <input name="distributions[<?php echo $d['activity_id']; ?>][female]" type="text" class="form-control hidden_spin tar female_part" value="<?php echo $d[$month . '_female']; ?>" />
                                                </div>
                                            </td>
                                            <td class="tar text-bold total_achievement total_male_female">
                                                <?php echo $d[$month . '_male'] + $d[$month . '_female'] ?>
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
            <button type="submit" name="xyz" class="btn btn-success" value="1">Set/Update Targets Achievement</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    init.push(function () {
        $(document).on('keyup', '.male_part', function () {
            var ths = $(this);            
            var thsRow = ths.closest('tr');
            var items = thsRow.find('.male_part');            
            var otheritems = thsRow.find('.female_part');
            var otherItemsValue = parseInt(otheritems.val());
            var totalItems = items.length;
            var totalAchievement = 0;
            for (var i = 0; i < totalItems; i++) {
                var thsItem = items.eq(i);
                totalAchievement += parseInt(thsItem.val());
            }
            thsRow.find('.total_male_female').html(totalAchievement + otherItemsValue);
        });
        
        $(document).on('keyup', '.female_part', function () {
            var ths = $(this);
            var thsRow = ths.closest('tr');
            var items = thsRow.find('.female_part');            
            var otheritems = thsRow.find('.male_part');
            var otherItemsValue = parseInt(otheritems.val());
            var totalItems = items.length;
            var totalAchievement = 0;
            for (var i = 0; i < totalItems; i++) {
                var thsItem = items.eq(i);
                totalAchievement += parseInt(thsItem.val());
            }
            thsRow.find('.total_male_female').html(totalAchievement + otherItemsValue);
        });
    });
</script>