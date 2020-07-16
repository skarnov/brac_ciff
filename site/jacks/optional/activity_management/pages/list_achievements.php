<?php
$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects(array('single' => false));
$all_projects = $all_projects['data'];
if(!$all_projects){
    add_notification('You need to have projects before you can set achievements','error');
    header('location:'.url());
    exit();
}
$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches(array('single' => false));
$all_branches = $all_branches['data'];

if(!$all_branches){
    add_notification('You need to have branches before you can set achievements','error');
    header('location:'.url());
    exit();
}

$filter_month = $_GET['month'] ? $_GET['month'] : null;
$filter_year = $_GET['year'] ? $_GET['year'] : null;
$filter_branch = $_GET['branch'] ? $_GET['branch'] : null;
$filter_project = $_GET['project'] ? $_GET['project'] : null;

if (!$filter_month || !$filter_year || !$filter_branch || !$filter_project) {
    if (!$filter_month) $filter_month = date('n');
    if (!$filter_year) $filter_year = date('Y');
    if (!$filter_branch) $filter_branch = $all_branches[0]['pk_branch_id'];
    if (!$filter_project) $filter_project = $all_projects[0]['pk_project_id'];

    header('location:' . build_url(array('month' => $filter_month, 'year' => $filter_year, 'branch' => $filter_branch, 'project' => $filter_project)));
    exit();
}

if($_POST['save_achievements']){
    if($_POST['item']){
        $thisDate = date('Y-m-d');
        $thisTime = date('H:i:s');
        $thisUser = $_config['user']['pk_user_id'];

        foreach($_POST['item'] as $i=>$v){
            $thisActivity = $this->get_activities(array('activity_id' => $i, 'single' => true));
            if($thisActivity){
                $updateData = array(
                    'activity_achievement' => $v['achievement'],
                    'activity_variance' => $v['achievement'] - $thisActivity['activity_target'],
                    'male_participant' => $v['male'],
                    'female_participant' => $v['female'],
                    'boy_participant' => $v['boy'],
                    'girl_participant' => $v['girl'],
                    'total_participant' => $v['male'] + $v['female'] + $v['boy'] + $v['girl'],
                    'update_date' => $thisDate,
                    'update_time' => $thisTime,
                    'update_by' => $thisUser,
                );
                $ret = $devdb->insert_update('dev_activities', $updateData, " pk_activity_id = '$i'");
            }
        }
    add_notification('Achievements updates');
    header('Location: '.current_url());
    exit();
    }
}

if ($_GET['ajax_type'] == 'get_achievement_form') {
    echo json_encode($this->get_achievement_form());
    exit();
} else if ($_GET['ajax_type'] == 'put_achievement_form') {
    echo json_encode($this->put_achievement_form($_POST));
    exit();
}
/* else if ($_GET['ajax_type'] == 'get_new_activity_category_form') {
    echo json_encode($this->get_new_activity_category_form());
    exit();
} */

if ($_POST['ajax_type'] == 'get_child_activities') {
    echo json_encode($this->get_child_activities($_POST['parent']));
    exit();
}

$args = array(
    'project' => $filter_project,
    'branch' => $filter_branch,
    'month' => $filter_month,
    'year' => $filter_year,
    'data_only' => true
);

$activities = $this->get_activities($args);

if ($_GET['download_pdf']) {
    unset($args['limit_by']);
    $args['data_only'] = true;
    $data = $this->get_activities($args);    
    $data = $data['data'];
  
    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'mis-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $headers = array('#', 'Project', 'Branch', 'Month', 'Year', 'Activity Group', 'Activity', 'Target', 'Achievement', 'Variance', 'Male', 'Female', 'Boy', 'Girl', 'Total');

    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['project_short_name']
                , $user['branch_name']
                , $user['entry_month']
                , $user['entry_year']."\r"
                , $user['parent_category_name']."\r"
                , $user['category_name']."\r"
                , $user['activity_target']."\r"
                , $user['activity_achievement']."\r"
                , $user['activity_variance']."\r"
                , $user['male_participant']."\r"
                , $user['female_participant']."\r"
                , $user['boy_participant']."\r"
                , $user['girl_participant']."\r"
                , $user['total_participant']."\r");
            fputcsv($fh, $dataToSheet);
        }
    }

    fclose($fh);

    $now = time();
    foreach (glob($csvFolder . "*.csv") as $file) {
        if (is_file($file)) {
            if ($now - filemtime($file) >= 60 * 60 * 24 * 2) { // 2 days
                unlink($file);
            }
        }
    }

    if (function_exists('apache_setenv'))
        @apache_setenv('no-gzip', 1);
    @ini_set('zlib.output_compression', 'Off');

    //Get file type and set it as Content Type
    header('Content-Type: text/csv');
    //Use Content-Disposition: attachment to specify the filename
    header('Content-Disposition: attachment; filename=' . basename($csvFile));
    //No cache
    header('Expires: 0');
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Pragma: public');
    //Define file size
    header('Content-Length: ' . filesize($csvFile));
    set_time_limit(0);
    $file = @fopen($csvFile, "rb");
    while (!feof($file)) {
        print(@fread($file, 1024 * 8));
        ob_flush();
        flush();
    }
    @fclose($file);
    exit;
}

doAction('render_start');
?>
<div class="page-header">
    <h1>All Achievements</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_pdf=1&project='.$filter_project.'&branch='.$filter_branch.'&month='.$filter_month.'&year='.$filter_year,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download',
                'title' => 'Download',
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
    <select class="form-control" name="project">
        <?php
        foreach ($all_projects as $i => $v) {
            ?>
            <option value="<?php echo $v['pk_project_id'] ?>" <?php echo $filter_project == $v['pk_project_id'] ? 'selected' : '' ?>><?php echo $v['project_short_name'] ?></option>
        <?php
    }
    ?>
    </select>
</div>
<div class="col-sm-2 form-group">
    <label>Branch</label>
    <select class="form-control" name="branch">
        <?php
        foreach ($all_branches as $i => $v) {
            ?>
            <option value="<?php echo $v['pk_branch_id'] ?>" <?php echo $filter_branch == $v['pk_branch_id'] ? 'selected' : '' ?>><?php echo $v['branch_name'] ?></option>
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
    <form name="activity_achievement" method="post" action="">
        <table class="table table-bordered table-condensed table-striped table-hover">
            <thead>
                <tr>
                    <th rowspan="2" class="vam">#</th>
                    <th rowspan="2" class="vam">Project</th>
                    <th rowspan="2" class="vam">Branch</th>
                    <th rowspan="2" class="vam">Month</th>
                    <th rowspan="2" class="vam">Year</th>
                    <th rowspan="2" class="vam">Activity Group</th>
                    <th rowspan="2" class="vam">Activity</th>
                    <th colspan="3" class="text-center">Number of Events</th>
                    <th colspan="5" class="text-center">Number of Participants</th>
                </tr>
                <tr>
                    <th class="tar">Target</th>
                    <th class="tar">Achievement</th>
                    <th class="tar">Variance</th>
                    <th class="tar">Male</th>
                    <th class="tar">Female</th>
                    <th class="tar">Boy</th>
                    <th class="tar">Girl</th>
                    <th class="tar">Total</th>
                </tr>
            </thead>
            <tbody class="items">
                <?php
                $count = 0;
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
                        <td class="tar"><input type="number" class="form-control tar inactive_input" readonly value="<?php echo $activity['activity_target']; ?>" /></td>
                        <td class="tar"><input class="editible form-control tar inactive_input" readonly type="number" name="item[<?php echo $activity['pk_activity_id']?>][achievement]" data-org="<?php echo $activity['activity_achievement']; ?>" value="<?php echo $activity['activity_achievement']; ?>"></td>
                        <td class="tar"><input type="number" class="form-control tar inactive_input" readonly value="<?php echo $activity['activity_variance']; ?>"/></td>
                        <td class="tar"><input class="editible form-control tar inactive_input" readonly type="number" name="item[<?php echo $activity['pk_activity_id']?>][male]" data-org="<?php echo $activity['male_participant']; ?>" value="<?php echo $activity['male_participant']; ?>"></td>
                        <td class="tar"><input class="editible form-control tar inactive_input" readonly type="number" name="item[<?php echo $activity['pk_activity_id']?>][female]" data-org="<?php echo $activity['female_participant']; ?>" value="<?php echo $activity['female_participant']; ?>"></td>
                        <td class="tar"><input class="editible form-control tar inactive_input" readonly type="number" name="item[<?php echo $activity['pk_activity_id']?>][boy]" data-org="<?php echo $activity['boy_participant']; ?>" value="<?php echo $activity['boy_participant']; ?>"></td>
                        <td class="tar"><input class="editible form-control tar inactive_input" readonly type="number" name="item[<?php echo $activity['pk_activity_id']?>][girl]" data-org="<?php echo $activity['girl_participant']; ?>" value="<?php echo $activity['girl_participant']; ?>"></td>
                        <td class="tar"><input type="number" class="form-control tar inactive_input" readonly value="<?php echo $activity['total_participant']; ?>"></td>
                    </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <div class="table-footer oh">
            <a href="javascript:" class="btn btn-xs btn-flat btn-labeled btn-primary" id="show_controls"><i class="fa fa-edit btn-label"></i>Update Data</a>
            <div id="control_btns" class="dn">
                <a href="javascript:" class="btn btn-xs btn-flat btn-labeled btn-danger" id="hide_controls"><i class="fa fa-times btn-label"></i>Cancel</a>
                <button type="submit" name="save_achievements" value="1" class="btn btn-xs btn-success btn-flat btn-labeled"><i class="fa fa-save btn-label"></i>Save Updates Now</button>
            </div>
        </div>
    </form>
</form>
<script type="text/javascript">
    init.push(function() {
        $('#show_controls').on('click', function(){
            $(this).hide();
            $('.items .editible')
                .removeClass('inactive_input')
                .attr('readonly', false);
            $('#control_btns').removeClass('dn');
        });
        $('#hide_controls').on('click', function(){
            bootboxConfirm({
                title: 'Cancel Update',
                msg: 'If you cancel now, all your changes will be changed to previous data.<br /><br />Are you sure?',
                confirm: {
                    callback: function(){
                        $('#control_btns').addClass('dn');
                        $('#show_controls').show();
                        $('.items .editible').each(function(i,e){
                            $(e)
                                .val($(e).attr('data-org'))
                                .addClass('inactive_input')
                                .attr('readonly', true);
                        });
                    }
                }
            });
        });
    });
</script>