<?php
class dev_financial_management {

    var $thsClass = 'dev_financial_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Financials',
            'permissions' => array(
                'manage_financials' => array(
                    'add_financial' => 'Add Financial',
                    'edit_financial' => 'Edit Financial',
                ),
            ),
        );

        if (!isPublic()) {
            register_permission($permissions);
            $this->adm_menus();
        }
    }

    function adm_menus() {
        $params = array(
            'label' => 'Financials',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Sales Target',
            'description' => 'Manage Sales Target',
            'menu_group' => 'Financial',
            'position' => 'default',
            'action' => 'manage_sales_target',
            'iconClass' => 'fa-street-view',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_financials'))
            admenu_register($params);
        
        $params = array(
            'label' => 'Sales Achievement',
            'description' => 'Manage Sales Target',
            'menu_group' => 'Financial',
            'position' => 'default',
            'action' => 'manage_sales_achievement',
            'iconClass' => 'fa-umbrella',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_financials'))
            admenu_register($params);
        
        $params = array(
            'label' => 'Sales Income',
            'description' => 'Manage Sales Income',
            'menu_group' => 'Financial',
            'position' => 'default',
            'action' => 'manage_sales_income',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_financials'))
            admenu_register($params);
        
        $params = array(
            'label' => 'Incentive Management',
            'description' => 'Manage Incentive Management',
            'menu_group' => 'Financial',
            'position' => 'default',
            'action' => 'manage_incentive_management',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_financials'))
            admenu_register($params);
        
        $params = array(
            'label' => 'Staff Incentive Report',
            'description' => 'Staff Incentive Report',
            'menu_group' => 'Financial',
            'position' => 'default',
            'action' => 'manage_staff_incentive',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_financials'))
            admenu_register($params);
    }

    function manage_sales_income() {
        if (!has_permission('manage_financials'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_financials');
        include('pages/list_sales_income.php');
    }

    function get_sales_income($param = null) {        
        $param['single'] = $param['single'] ? $param['single'] : false;
        
        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_sales_income     
            LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_sales_income.fk_project_id)            
            LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_sales_income.fk_branch_id)            
            LEFT JOIN dev_products ON (dev_products.pk_product_id = dev_sales_income.fk_product_id)            
        ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_financial_id) AS TOTAL " . $from . $where;
        
        $loopCondition = array(
            'income_id' => 'dev_sales_income.pk_income_id',
            'project_id' => 'dev_sales_income.fk_project_id',
            'branch_id' => 'dev_sales_income.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $income = sql_data_collector($sql, $count_sql, $param);
        return $income; 
    }
    
    function manage_incentive_management() {
        if (!has_permission('manage_financials'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_financials');
        if ($_GET['action'] == 'add_edit_incentive')
            include('pages/add_edit_incentive.php');
        else
            include('pages/list_incentive_management.php');
    }
    
    function add_edit_incentive($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_sales_incentive(array('incentive_id' => $edit, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid incentive id, no data found']);
            }
        }

        if (!$ret['error']) {
            $insert_data = array();
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');
            $insert_data['fk_project_id'] = $params['form_data']['project_id'];
            $insert_data['fk_branch_id'] = $params['form_data']['branch_id'];
            $insert_data['fk_staff_id'] = $params['form_data']['staff_id'];
            $insert_data['month_name'] = $params['form_data']['month_name'];
            $insert_data['unit_incentive'] = $params['form_data']['unit_incentive'];
            $insert_data['incentive_percentage'] = $params['form_data']['incentive_percentage'];
            
            if ($is_update){
                $insert_data['update_date'] = $dateNow;
                $insert_data['update_time'] = $timeNow;
                $insert_data['update_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_sales_incentive', $insert_data, " pk_sales_incentive_id = '" . $is_update . "'");
            }else {
                $insert_data['create_date'] = $dateNow;
                $insert_data['create_time'] = $timeNow;
                $insert_data['create_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_sales_incentive', $insert_data);
            }
        }
        return $ret;
    }
    
    function get_sales_incentive($param = null) {        
        $param['single'] = $param['single'] ? $param['single'] : false;
        
        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_sales_incentive            
            LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_sales_incentive.fk_branch_id)
            LEFT JOIN dev_users On (dev_users.pk_user_id = dev_sales_incentive.fk_staff_id)
            LEFT JOIN dev_products ON (dev_products.pk_product_id = dev_sales_incentive.fk_product_id)            
        ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_sales_incentive_id) AS TOTAL " . $from . $where;
        
        $loopCondition = array(
            'incentive_id' => 'pk_sales_incentive_id',
            'project_id' => 'dev_sales_incentive.fk_project_id',
            'branch_id' => 'dev_sales_incentive.fk_branch_id',
            'staff_id' => 'dev_sales_incentive.fk_staff_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;
        
        $income = sql_data_collector($sql, $count_sql, $param);        
        return $income; 
    }
    
    function manage_sales_target() {
        if (!has_permission('manage_financials'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_financials');
        if ($_GET['action'] == 'add_edit_target')
            include('pages/add_edit_target.php');
        else
            include('pages/list_sales_target.php');
    }
    
    function get_sales_targets($param = null) {        
        $param['single'] = $param['single'] ? $param['single'] : false;
        
        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_sales_targets     
            LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_sales_targets.fk_project_id)            
            LEFT JOIN dev_users On (dev_users.pk_user_id = dev_sales_targets.fk_staff_id)
            LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_sales_targets.fk_branch_id)                       
        ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_target_id) AS TOTAL " . $from . $where;
        
        $loopCondition = array(
            'target_id' => 'dev_sales_targets.pk_target_id',
            'project_id' => 'dev_sales_targets.fk_project_id',
            'branch_id' => 'dev_sales_targets.fk_branch_id',
            'staff_id' => 'dev_sales_targets.fk_staff_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;        
               
        $income = sql_data_collector($sql, $count_sql, $param);
        return $income; 
    }
    
    function add_edit_target($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_sales_targets(array('target_id' => $edit, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid target id, no data found']);
            }
        }

        if (!$ret['error']) {
            $insert_data = array();
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');
            $insert_data['fk_project_id'] = $params['form_data']['project_id'];
            $insert_data['fk_branch_id'] = $params['form_data']['branch_id'];
            $insert_data['fk_staff_id'] = $params['form_data']['staff_id'];
            $insert_data['month_name'] = $params['form_data']['month_name'];
            $insert_data['target_quantity'] = $params['form_data']['target_quantity'];
            
            if ($is_update){
                $insert_data['update_date'] = $dateNow;
                $insert_data['update_time'] = $timeNow;
                $insert_data['update_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_sales_targets', $insert_data, " pk_target_id = '" . $is_update . "'");
            }else {
                $insert_data['create_date'] = $dateNow;
                $insert_data['create_time'] = $timeNow;
                $insert_data['create_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_sales_targets', $insert_data);
            }
        }
        return $ret;
    }
    
    function manage_sales_achievement() {
        if (!has_permission('manage_financials'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_financials');
        if ($_GET['action'] == 'add_edit_achievement')
            include('pages/add_edit_achievement.php');
        else
            include('pages/list_sales_achievement.php');
    }
    
    function manage_staff_incentive() {
        if (!has_permission('manage_financials'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_financials');
            include('pages/list_staff_incentive.php');
    }
    
    function get_staff_incentive($param = null) {        
        $param['single'] = $param['single'] ? $param['single'] : false;
        
        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_staff_incentives            
            LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_staff_incentives.fk_branch_id)
            LEFT JOIN dev_users On (dev_users.pk_user_id = dev_staff_incentives.fk_staff_id)            
        ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_staff_incentive_id) AS TOTAL " . $from . $where;
        
        $loopCondition = array(
            'incentive_id' => 'pk_staff_incentive_id',
            'branch_id' => 'dev_staff_incentives.fk_branch_id',
            'staff_id' => 'dev_staff_incentives.fk_staff_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;
        
        $income = sql_data_collector($sql, $count_sql, $param);        
        return $income; 
    }
}

new dev_financial_management();