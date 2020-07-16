<?php

class dev_activity_management_v1 {

    var $months = array(
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    );
    var $thsClass = 'dev_activity_management_v1';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Activities',
            'permissions' => array(
                'manage_mis_activities' => array(
                    'add_new_activity_output' => 'Add New output/Activity',
                    'update_activity_output' => 'Update output/Activity',
                    'set_mis_target' => 'Set Targets',
                    'set_mis_target_distribution' => 'Set Target Distributions',
                    'set_mis_target_achievement' => 'Set Target Achievements',
                    'download_mis_report' => 'Download MIS Report',
                ),
            ),
        );

        if (!isPublic()) {
            register_permission($permissions);
            $this->adm_menus();
        }
    }

    function adm_menus() {

        $staffManager = jack_obj('dev_staff_management');
        //pre($staffManager,0);
        $myBranchType = $staffManager->getMyBranchType();

        $params = array(
            'label' => 'Set Targets',
            'description' => 'Set Targets',
            'menu_group' => 'MIS Activity',
            'position' => 'default',
            'action' => 'manage_mis_targets',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (!$staffManager->isStaff()) {
            if (has_permission('set_mis_target'))
                admenu_register($params);
        } else if ($myBranchType == 'ho') {
            if (has_permission('set_mis_target'))
                admenu_register($params);
        }

        $params = array(
            'label' => 'Target Distribution',
            'description' => 'Target Distribution',
            'menu_group' => 'MIS Activity',
            'position' => 'default',
            'action' => 'manage_mis_target_distribution',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (!$staffManager->isStaff()) {
            if (has_permission('set_mis_target_distribution'))
                admenu_register($params);
        } else if ($myBranchType == 'drsc') {
            if (has_permission('set_mis_target_distribution'))
                admenu_register($params);
        }

        $params = array(
            'label' => 'Target Achievements',
            'description' => 'Target Achievements',
            'menu_group' => 'MIS Activity',
            'position' => 'default',
            'action' => 'manage_mis_target_achievement',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (!$staffManager->isStaff()) {
            if (has_permission('set_mis_target_achievement'))
                admenu_register($params);
        } else if ($myBranchType == 'ulo') {
            if (has_permission('set_mis_target_achievement'))
                admenu_register($params);
        }

        $params = array(
            'label' => 'MIS Report',
            'description' => 'Download MIS Report',
            'menu_group' => 'MIS Activity',
            'position' => 'default',
            'action' => 'mis_report',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('download_mis_report'))
            admenu_register($params);
        $params = array(
            'label' => 'Manage Outputs/Activities',
            'description' => 'Manage Outputs/Activities',
            'menu_group' => 'MIS Activity',
            'position' => 'default',
            'action' => 'manage_output_activity',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('add_new_activity_output') || has_permission('update_activity_output'))
            admenu_register($params);

//        $params = array(
//            'label' => 'Manage Activities',
//            'description' => 'Manage Activities',
//            'menu_group' => 'MIS Activity',
//            'position' => 'default',
//            'action' => 'manage_output_activity',
//            'iconClass' => 'fa-building',
//            'jack' => $this->thsClass,
//        );
//        if(has_permission('add_new_activity_output') || has_permission('update_activity_output')) admenu_register($params);
    }

    function manage_mis_targets() {
        if (!has_permission('set_mis_target'))
            return true;

        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_mis_targets');

        $staffManager = jack_obj('dev_staff_management');
        $myBranchType = $staffManager->getMyBranchType();

        if ($staffManager->isStaff() && $myBranchType != 'ho')
            return true;

        if ($_GET['action'] == 'add_targets')
            include('pages/add_mis_targets.php');
        else if ($_GET['action'] == 'edit_targets')
            include('pages/edit_mis_targets.php');
        else if ($_GET['action'] == 'list_targets')
            include('pages/list_targets.php');
        else
//            include('pages/list_targets.php');
        include('pages/set_mis_targets.php');
    }

    function manage_mis_target_distribution() {
        if (!has_permission('set_mis_target_distribution'))
            return true;

        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_mis_target_distribution');

        $staffManager = jack_obj('dev_staff_management');
        $myBranchType = $staffManager->getMyBranchType();

        if ($staffManager->isStaff() && $myBranchType != 'drsc')
            return true;

        if ($_GET['action'] == 'add_edit_target_distribution')
            include('pages/add_edit_target_distribution.php');
        else
            include('pages/list_distributions.php');
    }

    function manage_mis_target_achievement() {
        if (!has_permission('set_mis_target_achievement'))
            return true;

        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_mis_target_achievement');

        $staffManager = jack_obj('dev_staff_management');
        $myBranchType = $staffManager->getMyBranchType();

        if ($staffManager->isStaff() && $myBranchType != 'ulo')
            return true;

        if ($_GET['action'] == 'add_edit_target_achievement')
            include('pages/add_edit_target_achievement.php');
        else
            include('pages/list_achievements.php');
    }

    function mis_report() {
        if (!has_permission('download_mis_report'))
            return true;

        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'mis_report');

        $staffManager = jack_obj('dev_staff_management');
        $myBranchType = $staffManager->getMyBranchType();


        include('pages/mis_report.php');
    }

    function manage_output_activity() {
        if (!has_permission('add_new_activity_output') && !has_permission('update_activity_output'))
            return true;

        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_output_activity');

        include('pages/list_mis_activities.php');
    }

    function get_mis_targets($param = array()) {
        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : 'mis_activity_targets.*, activity.cat_name as activity_name, project.project_short_name as project_name, branch.branch_name ');
        $from = "FROM mis_activity_targets 
                        LEFT JOIN dev_acitivites_categories AS activity ON (activity.pk_activity_cat_id = mis_activity_targets.fk_activity_id)
                        LEFT JOIN dev_projects AS project ON (project.pk_project_id = mis_activity_targets.fk_project_id)
                        LEFT JOIN dev_branches AS branch ON (branch.pk_branch_id = mis_activity_targets.fk_branch_id)
                        ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(mis_activity_targets.pk_mis_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'mis_activity_targets.pk_mis_target_id',
            'project' => 'mis_activity_targets.fk_project_id',
            'branch' => 'mis_activity_targets.fk_branch_id',
            'activity' => 'mis_activity_targets.fk_activity_id',
            'year' => 'mis_activity_targets._year',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $data = sql_data_collector($sql, $count_sql, $param);

        return $data;
    }

    function get_mis_targets_with_distribution($param = array()) {
        $select = "
            SELECT 
                distribution.*, 
                targets.`pk_mis_target_id` as parent_target_id, 
                targets._target as parent_target, 
                targets.fk_activity_id as activity_id, 
                activity.cat_name as activity_name, 
                project.project_short_name as project_name, 
                branch.branch_name as ulo_name,
                p_branch.branch_name AS drsc_name
            ";
        $from = "FROM mis_activity_targets as targets
                    LEFT JOIN (SELECT * FROM mis_activity_distribution WHERE 1 " . ($param['ulo'] ? " AND mis_activity_distribution.fk_branch_id = '" . $param['ulo'] . "'" : "") . ") AS distribution ON (distribution.`fk_project_id`=targets.`fk_project_id` AND distribution.`fk_parent_branch_id`=targets.`fk_branch_id` AND distribution.`_year`=targets.`_year` AND distribution.`fk_activity_id`=targets.`fk_activity_id`)
                    LEFT JOIN dev_acitivites_categories AS activity ON (activity.pk_activity_cat_id = targets.fk_activity_id)
                    LEFT JOIN dev_projects AS project ON (project.pk_project_id = targets.fk_project_id)
                    LEFT JOIN dev_branches AS branch ON (branch.pk_branch_id = distribution.fk_branch_id)
                    LEFT JOIN dev_branches AS p_branch ON (p_branch.pk_branch_id = targets.fk_branch_id)
                        ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(distribution.pk_mis_distribution_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'targets.pk_mis_target_id',
            'project' => 'targets.fk_project_id',
            'drsc' => 'targets.fk_branch_id',
            'activity' => 'targets.fk_activity_id',
            'year' => 'targets._year',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $data = sql_data_collector($sql, $count_sql, $param);

//        if($param['ulo']){
//            $targetULO = $param['ulo'];
//            foreach($data['data'] as $i=>$v){
//                if($v['fk_branch_id'] && $v['fk_branch_id'] != $targetULO){
//                    unset($data['data'][$i]);
//                    $data['total'] -= 1;
//                }
//            }
//        }

        return $data;
    }

    function get_mis_distributions($param = array()) {
        $select = "
            SELECT 
                distribution.*, 
                targets.`pk_mis_target_id` as parent_target_id, 
                targets._target as parent_target, 
                activity.fk_parent_id as output_id,
                activity.cat_name as activity_name,
                project.project_short_name as project_name, 
                branch.branch_name as ulo_name,
                p_branch.branch_name AS drsc_name
            ";
        $from = "FROM mis_activity_distribution as distribution
                    LEFT join mis_activity_targets as targets ON (targets.`fk_project_id`=distribution.`fk_project_id` AND targets.`fk_branch_id`=distribution.`fk_parent_branch_id` AND targets.`_year`=distribution.`_year` AND targets.`fk_activity_id`=distribution.`fk_activity_id`)
                    LEFT JOIN dev_acitivites_categories AS activity ON (activity.pk_activity_cat_id = distribution.fk_activity_id)
                    LEFT JOIN dev_projects AS project ON (project.pk_project_id = distribution.fk_project_id)
                    LEFT JOIN dev_branches AS branch ON (branch.pk_branch_id = distribution.fk_branch_id)
                    LEFT JOIN dev_branches AS p_branch ON (p_branch.pk_branch_id = distribution.fk_parent_branch_id)
                        ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(distribution.pk_mis_distribution_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'distribution.pk_mis_distribution_id',
            'project' => 'distribution.fk_project_id',
            'drsc' => 'distribution.fk_parent_branch_id',
            'ulo' => 'distribution.fk_branch_id',
            'activity' => 'distribution.fk_activity_id',
            'year' => 'distribution._year',
            'month' => 'distribution._month',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        if ($param['only_set_distributions']) {
            $conditions .= " AND targets.`pk_mis_target_id` IS NOT NULL ";
        }

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $data = sql_data_collector($sql, $count_sql, $param);

        return $data;
    }

    function get_new_activity_group_form($id = null) {
        $preData = array();
        if ($id) {
            $preData = $this->get_acitivity_categories(array('id' => $id, 'single' => true));
        }

        ob_start();
        ?>
        <form onsubmit="return false;">
            <div class="col-sm-12 form-group">
                <label>Output</label>
                <input class="form-control" type="text" name="cat_name" value="<?php echo $preData['cat_name'] ?>" />
            </div>
        </form>
        <?php
        $output = ob_get_clean();

        return array('success' => $output);
    }

    function put_new_activity_group_form($data) {
        //both parent and child
        global $devdb;

        $ret = array('error' => [], 'success' => []);

        if (!strlen($data['cat_name']))
            $ret['error'][] = 'Please enter activity output name';

        if (!$ret['error']) {
            $insert_data = array(
                'cat_name' => $data['cat_name']
            );
            if ($data['id'])
                $ret = $devdb->insert_update('dev_acitivites_categories', $insert_data, " pk_activity_cat_id = '" . $data['id'] . "'");
            else
                $ret = $devdb->insert_update('dev_acitivites_categories', $insert_data);

            if ($ret['success']) {
                $item_id = $data['id'] ? $data['id'] : $ret['success'];
                $ret['success'] = $insert_data;
                $ret['success']['pk_activity_cat_id'] = $item_id;
            }
        }

        return $ret;
    }

    function get_new_activity_category_form($id = null, $init_parent = null) {
        //both parent and child
        global $_config;
        $preData = array();
        if ($id) {
            $preData = $this->get_acitivity_categories(array('id' => $id, 'single' => true));            
        }
        $acitivity_categories = $this->get_acitivity_categories(array('parent_only' => true, 'data_only' => true));
        $acitivity_categories = $acitivity_categories['data'];

        $projects = jack_obj('dev_project_management');
        $all_projects = $projects->get_projects();

        ob_start();
        ?>
        <form name="add_new_target" method="post" onsubmit="return false;">
            <div class="col-sm-12 form-group">
                <label>Select Project</label>
                <select required class="form-control" name="project_id">
                    <?php
                    foreach ($all_projects['data'] as $project) {
                        $selected = $preData['fk_project_id'] == $project['pk_project_id'] ? 'selected' : '';
                        ?>
                        <option value="<?php echo $project['pk_project_id'] ?>" <?php echo $selected ?>><?php echo $project['project_short_name'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-12 form-group">
                <label>Select Output</label>
                <select required class="form-control" name="fk_parent_id">
                    <option value="0">Create As Output</option>
                    <?php
                    foreach ($acitivity_categories as $i => $v) {
                        $selected = $preData && $preData['fk_parent_id'] == $v['pk_activity_cat_id'] ? 'selected' : ($init_parent && $init_parent == $v['pk_activity_cat_id'] ? 'selected' : '');
                        ?>
                        <option value="<?php echo $v['pk_activity_cat_id'] ?>" <?php echo $selected ?>><?php echo $v['cat_name'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-12 form-group">
                <label>Activity/Output</label>
                <input class="form-control" type="text" name="cat_name" value="<?php echo $preData['cat_name'] ?>" />
            </div>
        </form>
        <?php
        $out = ob_get_clean();

        return array('success' => $out);
    }

    function put_new_activity_category_form($data) {
        //both parent and child
        global $devdb;

        $ret = array('error' => [], 'success' => []);

        if (!strlen($data['cat_name']))
            $ret['error'][] = 'Pleae enter activity name';

        if (!$ret['error']) {
            $insert_data = array(
                'fk_project_id' => $data['project_id'],
                'fk_parent_id' => $data['fk_parent_id'],
                'cat_name' => $data['cat_name']
            );
            if ($data['id'])
                $ret = $devdb->insert_update('dev_acitivites_categories', $insert_data, " pk_activity_cat_id = '" . $data['id'] . "'");
            else
                $ret = $devdb->insert_update('dev_acitivites_categories', $insert_data);

            if ($ret['success']) {
                $item_id = $data['id'] ? $data['id'] : $ret['success'];
                $ret['success'] = $insert_data;
                $ret['success']['pk_activity_cat_id'] = $item_id;
            }
        }

        return $ret;
    }

    function get_child_activities($parent) {
        $data = $this->get_acitivity_categories(array('parent' => $parent, 'data_only' => true));
        return $data['data'] ? $data['data'] : array();
    }

    function get_target_edit_form($id) {
        global $_config;

        if (!$id)
            return array('error' => ['Insufficient Data']);

        $theActivity = $this->get_activities(array('activity_id' => $id, 'single' => true));

        if (!$theActivity)
            return array('error' => ['Activity not found for editing']);

        $jackProjects = jack_obj('dev_project_management');
        $jackBranches = jack_obj('dev_branch_management');

        $all_projects = $jackProjects->get_projects(array('data_only' => true));
        $all_projects = $all_projects['data'];

        $all_branches = $jackBranches->get_branches(array('data_only' => true));
        $all_branches = $all_branches['data'];

        $acitivity_categories = $this->get_acitivity_categories(array('parent_only' => true, 'data_only' => true));
        $acitivity_categories = $acitivity_categories['data'];

        ob_start();
        ?>
        <form name="add_new_target" method="post" onsubmit="return false;">
            <div class="col-sm-12 form-group">
                <label>Project</label>
                <select required class="form-control" name="fk_project_id">
                    <option value="">Select One</option>
                    <?php
                    foreach ($all_projects as $i => $v) {
                        ?>
                        <option value="<?php echo $v['pk_project_id'] ?>" <?php echo $theActivity['fk_project_id'] == $v['pk_project_id'] ? 'selected' : '' ?>><?php echo $v['project_short_name'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-12 form-group">
                <label>Branch</label>
                <select required class="form-control" name="fk_branch_id">
                    <option value="">Select One</option>
                    <?php
                    foreach ($all_branches as $i => $v) {
                        ?>
                        <option value="<?php echo $v['pk_branch_id'] ?>"><?php echo $v['branch_name'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 form-group">
                <label>Month</label>
                <select required class="form-control" name="entry_month">
                    <option value="">Select One</option>
                    <?php
                    foreach ($this->months as $i => $v) {
                        ?>
                        <option value="<?php echo $i ?>" <?php echo $theActivity['entry_month'] == $i ? 'selected' : '' ?>><?php echo $v ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 form-group">
                <label>Year</label>
                <input required class="form-control" type="number" name="entry_year" value="<?php echo $theActivity['entry_year'] ?>" />
            </div>
            <div class="col-sm-12 form-group">
                <label>Acitivity Group</label>
                <div class="input-group">
                    <select class="form-control" id="parent_activity">
                        <option value="">Select One</option>
                        <?php
                        foreach ($acitivity_categories as $i => $v) {
                            ?>
                            <option value="<?php echo $v['pk_activity_cat_id'] ?>" <?php echo $theActivity['parent_category_id'] == $v['pk_activity_cat_id'] ? 'selected' : '' ?>><?php echo $v['cat_name'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <span class="input-group-addon input-group-btn add_new_activity_group"><i class="fa fa-plus-circle"></i></span>
                </div>
            </div>
            <div class="col-sm-9 form-group">
                <label>Activity</label>
                <div class="input-group">
                    <select required id="child_activity" name="fk_activity_id" class="form-control" data-selected="<?php echo $theActivity['fk_activity_cat_id'] ?>"></select>
                    <span class="input-group-addon input-group-btn add_new_activity"><i class="fa fa-plus-circle"></i></span>
                </div>
            </div>
            <div class="col-sm-3">
                <label>Target</label>
                <input required type="number" class="form-control text-right" name="target" value="<?php echo $theActivity['activity_target'] ?>" />
            </div>
        </form>
        <script type="text/javascript">
            $(document).off('change', '#parent_activity').on('change', '#parent_activity', function () {
                var ths = $(this);
                $('#child_activity').html('<option value="">Select One</option>');
                if (!ths.val().length)
                    return true;
                basicAjaxCall({
                    data: {
                        ajax_type: 'get_child_activities',
                        parent: ths.val()
                    },
                    success: function (ret) {
                        $('#child_activity').html('<option value="">Select One</option>');
                        for (var i in ret) {
                            var selected = $('#child_activity').attr('data-selected') == ret[i]['pk_activity_cat_id'] ? 'selected' : '';
                            $('#child_activity').append('<option value="' + ret[i]['pk_activity_cat_id'] + '" ' + selected + '>' + ret[i]['cat_name'] + '</option>');
                        }
                    }
                });
            });
            $('#parent_activity').change();
        </script>
        <?php
        $out = ob_get_clean();

        return array('success' => $out);
    }

    function put_target_edit_form($data, $id) {
        global $devdb, $_config;
        $ret = array('error' => [], 'success' => []);

        if (!$id)
            return array('error' => ['Invalid or insufficient data']);

        if (!strlen($data['fk_project_id']))
            $ret['error'][] = 'Please select project';
        if (!strlen($data['fk_branch_id']))
            $ret['error'][] = 'Please select branch';
        if (!strlen($data['entry_month']))
            $ret['error'][] = 'Please select month';
        if (!strlen($data['entry_year']))
            $ret['error'][] = 'Please enter month';
        if (!strlen($data['fk_activity_id']))
            $ret['error'][] = 'Please enter month';
        if (!strlen($data['target']))
            $ret['error'][] = 'Please enter month';

        if (!$ret['error']) {

            $thisDate = date('Y-m-d');
            $thisTime = date('H:i:s');
            $thisUser = $_config['user']['pk_user_id'];

            $insert_data = array(
                'fk_project_id' => $data['fk_project_id'],
                'fk_branch_id' => $data['fk_branch_id'],
                'fk_activity_cat_id' => $data['fk_activity_id'],
                'entry_month' => $data['entry_month'],
                'entry_year' => $data['entry_year'],
                'activity_target' => $data['target'],
                'update_date' => $thisDate,
                'update_time' => $thisTime,
                'update_by' => $thisUser,
            );

            $ret = $devdb->insert_update('dev_activities', $insert_data, " pk_activity_id = '$id'");
        }

        return $ret;
    }

    function get_new_target_form() {
        global $_config;
        $jackProjects = jack_obj('dev_project_management');
        $jackBranches = jack_obj('dev_branch_management');

        $all_projects = $jackProjects->get_projects(array('data_only' => true));
        $all_projects = $all_projects['data'];

        $all_branches = $jackBranches->get_branches(array('data_only' => true));
        $all_branches = $all_branches['data'];

        $acitivity_categories = $this->get_acitivity_categories(array('parent_only' => true, 'data_only' => true));
        $acitivity_categories = $acitivity_categories['data'];

        ob_start();
        ?>
        <form name="add_new_target" method="post" onsubmit="return false;">
            <div class="col-sm-12 form-group">
                <label>Project</label>
                <select required class="form-control" name="fk_project_id">
                    <option value="">Select One</option>
                    <?php
                    foreach ($all_projects as $i => $v) {
                        ?>
                        <option value="<?php echo $v['pk_project_id'] ?>"><?php echo $v['project_short_name'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-12 form-group">
                <label>Branch</label>
                <select required multiple class="form-control adv_select" name="fk_branch_id[]">
                    <option value="">Select One</option>
                    <?php
                    foreach ($all_branches as $i => $v) {
                        ?>
                        <option value="<?php echo $v['pk_branch_id'] ?>" <?php echo $theActivity['fk_branch_id'] == $v['pk_branch_id'] ? 'selected' : '' ?>><?php echo $v['branch_name'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 form-group">
                <label>Month</label>
                <select required class="form-control" name="entry_month">
                    <option value="">Select One</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div class="col-sm-6 form-group">
                <label>Year</label>
                <input required class="form-control" type="number" name="entry_year" value="" />
            </div>
            <div class="col-sm-12 form-group">
                <label>Acitivity Output</label>
                <div class="input-group">
                    <select class="form-control" id="parent_activity">
                        <option value="">Select One</option>
                        <?php
                        foreach ($acitivity_categories as $i => $v) {
                            ?>
                            <option value="<?php echo $v['pk_activity_cat_id'] ?>"><?php echo $v['cat_name'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <span class="input-group-addon input-group-btn add_new_activity_group"><i class="fa fa-plus-circle"></i></span>
                </div>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th class="text-right">Target</th>
                        <th class="text-right vam">...</th>
                    </tr>
                </thead>
                <tbody class="add_acitivites_here"></tbody>
            </table>
            <div class="table-footer">
                <a href="javascript:" class="add_another_acitivity action_link">+ Add Another Acitivity</a>
            </div>
        </form>
        <script type="text/javascript">
            advanceSelect('.adv_select');
            var activity_options = '';
            var row_count = 0;
            $(document).off('change', '#parent_activity').on('change', '#parent_activity', function () {
                var ths = $(this);
                activity_options = '';
                $('.each_child_activity').html('<option value="">Select One</option>');
                if (!ths.val().length)
                    return true;
                basicAjaxCall({
                    data: {
                        ajax_type: 'get_child_activities',
                        parent: ths.val()
                    },
                    success: function (ret) {

                        for (var i in ret) {

                            activity_options += '<option value="' + ret[i]['pk_activity_cat_id'] + '">' + ret[i]['cat_name'] + '</option>';
                        }
                        $('.each_child_activity').each(function (i, e) {
                            $(e).html('<option value="">Select One</option>');
                            for (var i in ret) {
                                $(e).append('<option value="' + ret[i]['pk_activity_cat_id'] + '">' + ret[i]['cat_name'] + '</option>');
                            }
                        });
                    }
                });
            });
            $(document).off('click', '.add_another_acitivity').on('click', '.add_another_acitivity', function () {
                $('.add_acitivites_here').append('\
                                    <tr>\
                                    <td><div class="input-group"><select required name="activities[' + row_count + '][acitivity]" class="each_child_activity form-control"><option value="">Select One</option>' + activity_options + '</select><span class="input-group-addon input-group-btn add_new_activity"><i class="fa fa-plus-circle"></i></span></div></td>\
                                    <td class="text-right"><input required class="form-control text-right" type="number" name="activities[' + row_count + '][target]" value="" /></td>\
                                    <td class="text-right vam"><a href="javascript:" class="btn btn-xs btn-danger remove_row"><i class="fa fa-times-circle"></i></a></td>\
                                    </td>\
                                ');
                row_count += 1;
            }).trigger('click');
            $(document).off('click', '.remove_row').on('click', '.remove_row', function () {
                $(this).closest('tr').remove();
            });
        </script>
        <?php
        $out = ob_get_clean();

        return array('success' => $out);
    }

    function put_new_target_form($data) {
        global $devdb, $_config;
        $ret = array('error' => [], 'success' => []);

        if (!strlen($data['fk_project_id']))
            $ret['error'][] = 'Please select project';
        if (!$data['fk_branch_id'])
            $ret['error'][] = 'Please select branch(s)';
        if (!strlen($data['entry_month']))
            $ret['error'][] = 'Please select month';
        if (!strlen($data['entry_year']))
            $ret['error'][] = 'Please enter month';
        if (!$data['activities'])
            $ret['error'][] = 'Please add activities';
        else {
            foreach ($data['activities'] as $v) {
                if (!strlen($v['acitivity']))
                    $ret['error'][] = 'Missing activity';
                if (!strlen($v['target']))
                    $ret['error'][] = 'Missing target';
            }
        }

        if (!$ret['error']) {
            $thisDate = date('Y-m-d');
            $thisTime = date('H:i:s');
            $thisUser = $_config['user']['pk_user_id'];

            foreach ($data['fk_branch_id'] as $eachBranchID) {
                foreach ($data['activities'] as $v) {

                    $existing = $this->get_activities(array('single' => true, 'activity_category_id' => $v['acitivity'], 'project' => $data['fk_project_id'], 'branch' => $eachBranchID, 'month' => $data['entry_month'], 'year' => $data['entry_year']));

                    $insert_data = array(
                        'fk_project_id' => $data['fk_project_id'],
                        'fk_branch_id' => $eachBranchID,
                        'fk_activity_cat_id' => $v['acitivity'],
                        'entry_month' => $data['entry_month'],
                        'entry_year' => $data['entry_year'],
                        'entry_date' => $thisDate,
                        'activity_target' => $v['target'],
                        'update_date' => $thisDate,
                        'update_time' => $thisTime,
                        'update_by' => $thisUser,
                    );
                    if ($existing) {
                        $devdb->insert_update('dev_activities', $insert_data, " pk_activity_id = '" . $existing['pk_activity_id'] . "'");
                    } else {
                        $insert_data['create_date'] = $thisDate;
                        $insert_data['create_time'] = $thisTime;
                        $insert_data['create_by'] = $thisUser;
                        $devdb->insert_update('dev_activities', $insert_data);
                    }
                }
            }

            $ret['success'] = true;
        }
        return $ret;
    }

    function get_acitivity_categories($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;
        $param['index_with'] = isset($param['index_with']) ? $param['index_with'] : false;

        if (!$param['select_fields']) {
            $param['select_fields'] = array(
                'dev_acitivites_categories.*', 'parent_category.cat_name AS parent_category_name', 'dev_acitivites_categories.fk_project_id',
            );
        }

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_acitivites_categories 
                        LEFT JOIN dev_acitivites_categories AS parent_category ON  (dev_acitivites_categories.fk_parent_id = parent_category.pk_activity_cat_id)
                        ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_acitivites_categories.pk_activity_cat_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_acitivites_categories.pk_activity_cat_id',
            'project_id' => 'dev_acitivites_categories.fk_project_id',
            'parent' => 'dev_acitivites_categories.fk_parent_id',
            'deletion_status' => 'dev_acitivites_categories.deletion_status',
            'create_date' => 'create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        if ($param['child_only'])
            $conditions .= " AND dev_acitivites_categories.fk_parent_id != '0' ";
        if ($param['parent_only'])
            $conditions .= " AND dev_acitivites_categories.fk_parent_id = '0' ";

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $activities = sql_data_collector($sql, $count_sql, $param);

        return $activities;
    }

    function get_activities($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;
        $param['index_with'] = isset($param['index_with']) ? $param['index_with'] : false;

        if (!$param['select_fields']) {
            $param['select_fields'] = array(
                'dev_activities.*', 'dev_acitivites_categories.cat_name AS category_name', 'parent_category.cat_name AS parent_category_name', 'parent_category.pk_activity_cat_id AS parent_category_id', 'dev_branches.branch_name', 'dev_projects.project_short_name'
            );
        }

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_activities 
                        LEFT JOIN dev_acitivites_categories ON  (dev_activities.fk_activity_cat_id = dev_acitivites_categories.pk_activity_cat_id)
                        LEFT JOIN dev_acitivites_categories AS parent_category ON  (dev_acitivites_categories.fk_parent_id = parent_category.pk_activity_cat_id)
                        LEFT JOIN dev_branches ON  (dev_activities.fk_branch_id = dev_branches.pk_branch_id)
                        LEFT JOIN dev_projects ON  (dev_activities.fk_project_id = dev_projects.pk_project_id)
                        ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_activity_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'activity_id' => 'pk_activity_id',
            'project' => 'dev_activities.fk_project_id',
            'branch' => 'dev_activities.fk_branch_id',
            'activity_category_id' => 'dev_activities.fk_activity_cat_id',
            'month' => 'dev_activities.entry_month',
            'year' => 'dev_activities.entry_year',
            'date' => 'dev_activities.entry_date',
            'create_date' => 'dev_activities.create_date',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $activities = sql_data_collector($sql, $count_sql, $param);

        return $activities;
    }

    function add_edit_activity($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        if ($is_update && !has_permission('edit_activity')) {
            add_notification('You don\'t have enough permission to edit activity.', 'error');
            header('Location:' . build_url(NULL, array('edit', 'action')));
            exit();
        } elseif (!has_permission('add_activity')) {
            add_notification('You don\'t have enough permission to add activity.', 'error');
            header('Location:' . build_url(NULL, array('action')));
            exit();
        }

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_activities(array('activity_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid activity id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        $temp = form_validator::_length($params['required']['activity_target'], 490);
        if ($temp !== true)
            $ret['error'][] = 'Activity Target ' . $temp;

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['entry_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));
            $insert_data['fk_branch_id'] = $params['form_data']['branch_id'];
            $insert_data['activity_name'] = $params['form_data']['activity_name'];
            $insert_data['activity_output'] = $params['form_data']['activity_output'];
            $insert_data['activity_target'] = $params['form_data']['activity_target'];
            $insert_data['activity_achievement'] = $params['form_data']['activity_achievement'];
            $insert_data['activity_variance'] = $params['form_data']['activity_variance'];

            $insert_data['male_participant'] = $params['form_data']['male_participant'];
            $insert_data['female_participant'] = $params['form_data']['female_participant'];
            $insert_data['boy_participant'] = $params['form_data']['boy_participant'];
            $insert_data['girl_participant'] = $params['form_data']['girl_participant'];
            @$insert_data['total_participant'] = $insert_data['male_participant'] + $insert_data['female_participant'] + $insert_data['boy_participant'] + $insert_data['girl_participant'];

            $insert_data['update_date'] = date('Y-m-d');
            $insert_data['update_time'] = date('H:i:s');
            $insert_data['update_by'] = $_config['user']['pk_user_id'];

            if ($is_update)
                $ret = $devdb->insert_update('dev_activities', $insert_data, " pk_activity_id = '" . $is_update . "'");
            else {
                $insert_data['create_date'] = date('Y-m-d');
                $insert_data['create_time'] = date('H:i:s');
                $insert_data['create_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_activities', $insert_data);
            }
        }
        return $ret;
    }

}

new dev_activity_management_v1();
