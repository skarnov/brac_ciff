<?php

class dev_report_management {

    var $thsClass = 'dev_report_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Reports',
            'permissions' => array(
                'manage_reports' => array(
                    'business_reports' => 'Business Reports',
                    'migration_reports' => 'Migration Reports',
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
            'label' => 'Reports',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Case Study',
            'description' => 'Case Study Reports',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'case_study',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('migration_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Skill Wise Beneficiary (Training)',
            'description' => 'Skilled But No Certificate',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'skilled_beneficiary',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('migration_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Skill Required By Beneficiary',
            'description' => 'Want To Get Trained',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'skill_required',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('migration_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Income Earner Skills (RPL Analysis)',
            'description' => 'Have Income Earner Skill',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'income_earner',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('migration_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Category Wise Beneficiary (Income)',
            'description' => 'Income Category Wise Beneficiary',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'income_wise_beneficiary',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('migration_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Statistics',
            'description' => 'Statistics Report',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'statistics',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('migration_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Branch Wise Beneficiary',
            'description' => 'Branch Wise Beneficiary',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'branch_wise_beneficiary',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('migration_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Climate Change Effects',
            'description' => 'Climate Change Effects',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'climate_change_effects',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('migration_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Project Wise Branch',
            'description' => 'Project Wise Branch',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'project_wise_branch',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('migration_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Project Wise Staff',
            'description' => 'Project Wise Staff',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'project_wise_staff',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('migration_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Willing To Go (Country)',
            'description' => 'Preferred Country',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'preferred_country',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('business_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Branch Activity',
            'description' => 'Branch Activity Report',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'branch_activity',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('business_reports'))
        admenu_register($params);

        $params = array(
            'label' => 'Sales Report (Monthly)',
            'description' => 'Sales Report',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'sales_report',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('business_reports'))
            admenu_register($params);

        $params = array(
            'label' => 'Sales Report (Staff)',
            'description' => 'Staff Wise Sales Report',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'staff_wise_sales_report',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('business_reports'))
            admenu_register($params);
    }

    function case_study() {
        if (!has_permission('migration_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'case_study');

        if ($_GET['action'] == 'download_case_study')
            include('pages/download_case_study.php');

        include('pages/case_study.php');
    }

    function get_case_study($param = null) {
        $temp = form_validator::_length($params['required']['customer_id'], 100);
        if ($temp !== true)
            $ret['error'][] = 'Customer ID' . $temp;

        if (!$ret['error']) {
            $param['single'] = $param['single'] ? $param['single'] : false;

            $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
            $from = "FROM dev_customers";
            $where = " WHERE 1 ";
            $conditions = " ";
            $sql = $select . $from . $where;
            $count_sql = "SELECT COUNT(pk_customer_id) AS TOTAL " . $from . $where;

            $loopCondition = array(
                'customer_id' => 'customer_id'
            );

            $conditions .= sql_condition_maker($loopCondition, $param);

            $orderBy = sql_order_by($param);
            $limitBy = sql_limit_by($param);

            $sql .= $conditions . $orderBy . $limitBy;
            $count_sql .= $conditions;

            $customers = sql_data_collector($sql, $count_sql, $param);
            return $customers;
        }
        return $ret;
    }

    function get_branch_info($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_branches";
        $where = " WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_branch_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'branch_id' => 'pk_branch_id'
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $branches = sql_data_collector($sql, $count_sql, $param);
        return $branches;
    }

    function get_staff_info($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_users";
        $where = " WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_user_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'staff_id' => 'pk_user_id'
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $staffs = sql_data_collector($sql, $count_sql, $param);
        return $staffs;
    }

    function skilled_beneficiary() {
        if (!has_permission('migration_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'skilled_beneficiary');

        include('pages/skilled_beneficiary.php');
    }
    
    function skill_required() {
        if (!has_permission('migration_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'skill_required');

        include('pages/skill_required.php');
    }
    
    function income_earner() {
        if (!has_permission('migration_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'income_earner');

        include('pages/income_earner.php');
    }
    
    function income_wise_beneficiary() {
        if (!has_permission('migration_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'income_wise_beneficiary');

        include('pages/income_wise_beneficiary.php');
    }
    
    function statistics() {
        if (!has_permission('migration_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'statistics');

        include('pages/statistics.php');
    }
    
    function branch_wise_beneficiary() {
        if (!has_permission('migration_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'branch_wise_beneficiary');

        include('pages/branch_wise_beneficiary.php');
    }
    
    function climate_change_effects() {
        if (!has_permission('migration_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'climate_change_effects');

        include('pages/climate_change_effects.php');
    }
    
    function project_wise_branch() {
        if (!has_permission('migration_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'project_wise_branch');

        include('pages/project_wise_branch.php');
    }
    
    function project_wise_staff() {
        if (!has_permission('migration_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'project_wise_staff');

        include('pages/project_wise_staff.php');
    }
    
    function preferred_country() {
        if (!has_permission('business_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'willing_to_go');

        include('pages/willing_to_go.php');
    }
        
    function branch_activity() {
        if (!has_permission('business_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'branch_activity');

        include('pages/branch_activity.php');
    }
    
    function sales_report() {
        if (!has_permission('business_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'sales_report');

        include('pages/sales_report.php');
    }
    
    function staff_wise_sales_report() {
        if (!has_permission('business_reports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'staff_wise_sales_report');

        include('pages/staff_wise_sales_report.php');
    }
}

new dev_report_management();