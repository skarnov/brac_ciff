<?php

class dev_sale_management {

    var $thsClass = 'dev_sale_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Sales',
            'permissions' => array(
                'manage_sales' => array(
                    'add_sale' => 'Add Sale'
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
            'label' => 'Sales',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Sales Management',
            'description' => 'Manage All Sales',
            'menu_group' => 'Business',
            'position' => 'top',
            'action' => 'manage_sales',
            'iconClass' => 'fa-shopping-cart',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_sales'))
            admenu_register($params);
    }

    function manage_sales() {
        if (!has_permission('manage_sales'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_sales');
        if ($_GET['action'] == 'add_edit_sale')
            include('pages/add_edit_sale.php');
        elseif ($_GET['action'] == 'view_invoice')
            include('pages/view_invoice.php');
        else
            include('pages/list_sales.php');
    }

    function get_sales($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_sales 
            LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_sales.fk_branch_id)            
        ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_sale_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'sale_id' => 'dev_sales.pk_sale_id',
            'project_id' => 'dev_sales.fk_project_id',
            'branch_id' => 'dev_sales.fk_branch_id',
            'customer_id' => 'dev_sales.fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $sales = sql_data_collector($sql, $count_sql, $param);

        return $sales;
    }

    function get_invoices($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_sales       
            LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_sales.fk_customer_id)            
            LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_sales.fk_branch_id)            
        ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_sale_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'sale_id' => 'pk_sale_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $sales = sql_data_collector($sql, $count_sql, $param);

        return $sales;
    }

    function get_invoice_details($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_sale_items "
                . "LEFT JOIN dev_products ON (dev_products.pk_product_id = dev_sale_items.fk_product_id) ";
        $where = "WHERE 1 ";
        $conditions = "";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_sale_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'sale_id' => 'dev_sale_items.fk_sale_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;
        
        $sales = sql_data_collector($sql, $count_sql, $param);
        return $sales;
    }

    function add_sale($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $insert_data = array();
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');
            $insert_data['fk_project_id'] = $params['form_data']['fk_project_id'];
            $insert_data['fk_branch_id'] = $params['form_data']['fk_branch_id'];
            $insert_data['fk_customer_id'] = $params['form_data']['fk_customer_id'];
            $insert_data['fk_staff_id'] = $params['form_data']['staff_id'];
            $insert_data['sale_title'] = $params['sale_title'];
            $insert_data['invoice_date'] = date_to_db($params['form_data']['invoice_date']);
            $insert_data['customer_name'] = $params['form_data']['customer_name'];
            $insert_data['sale_discount'] = $params['form_data']['discount'];
            $insert_data['sale_total'] = $params['form_data']['sale_total'];
            $insert_data['create_date'] = $dateNow;
            $insert_data['create_time'] = $timeNow;
            $insert_data['create_by'] = $_config['user']['pk_user_id'];
            $ret['sale_insert'] = $devdb->insert_update('dev_sales', $insert_data);
            
            $sale_id = $ret['sale_insert']['success'];
            $project_id = $insert_data['fk_project_id'];
            $branch_id = $insert_data['fk_branch_id'];
            $staff_id = $insert_data['fk_staff_id'];

            $financeManager = jack_obj('dev_financial_management');
            $incentive = $financeManager->get_sales_incentive(array('staff_id' => $staff_id, 'order_by' => array('col' => 'pk_sales_incentive_id', 'order' => 'DESC'), 'single' => true));

            foreach ($params['form_data']['invoice_detail'] as $value) {
                $sale = array();
                $sale['fk_sale_id'] = $sale_id;
                $sale['fk_product_id'] = $value['fk_product_id'];
                $sale['item_quantity'] = $value['item_quantity'];
                $sale['item_price'] = $value['item_price'];
                $sale['item_total_price'] = $value['item_total_price'];
                $sale['create_date'] = $dateNow;
                $sale['create_time'] = $timeNow;
                $sale['create_by'] = $_config['user']['pk_user_id'];
                $ret['sale_items_insert'] = $devdb->insert_update('dev_sale_items', $sale);
                $ret['stock_updated'] = $devdb->query("UPDATE dev_stocks SET stock_quantity = stock_quantity - ".$value['item_quantity']." WHERE fk_product_id = '" . $value['fk_product_id'] . "'");

                $current_month = date('F', strtotime($dateNow));
                $incentive_month = $incentive['month_name'];

                if ($incentive['sale_count'] >= $incentive['unit_incentive'] && ($current_month == $incentive_month)) { 
                    $sale_incentive = array();
                    $sale_incentive['fk_project_id'] = $project_id;
                    $sale_incentive['fk_branch_id'] = $branch_id;
                    $sale_incentive['fk_product_id'] = $value['fk_product_id'];
                    $sale_incentive['fk_staff_id'] = $staff_id;
                    $sale_incentive['fk_sale_id'] = $sale_id;
                    $sale_incentive['sale_total'] = $insert_data['sale_total'];
                    $sale_incentive['incentive_percentage'] = $incentive['incentive_percentage'];
                    $sale_incentive['incentive_amount'] = $sale_incentive['sale_total'] * ($sale_incentive['incentive_percentage'] / 100);
                    $sale_incentive['create_date'] = $dateNow;
                    $sale_incentive['create_time'] = $timeNow;
                    $sale_incentive['create_by'] = $_config['user']['pk_user_id'];
                    $ret['staff_incentive_insert'] = $devdb->insert_update('dev_staff_incentives', $sale_incentive);
                }
                elseif($value['item_quantity'] >= $incentive['unit_incentive'] && ($current_month == $incentive_month)){
                    $sale_incentive = array();
                    $sale_incentive['fk_project_id'] = $project_id;
                    $sale_incentive['fk_branch_id'] = $branch_id;
                    $sale_incentive['fk_product_id'] = $value['fk_product_id'];
                    $sale_incentive['fk_staff_id'] = $staff_id;
                    $sale_incentive['fk_sale_id'] = $sale_id;
                    
                    $not_incentive = $incentive['unit_incentive'] - $incentive['sale_count'];
                    $sale_incentive['sale_total'] = $value['item_price'] * ($value['item_quantity'] - $not_incentive);
                    
                    $sale_incentive['incentive_percentage'] = $incentive['incentive_percentage'];
                    $sale_incentive['incentive_amount'] = $sale_incentive['sale_total'] * ($sale_incentive['incentive_percentage'] / 100);
                    $sale_incentive['create_date'] = $dateNow;
                    $sale_incentive['create_time'] = $timeNow;
                    $sale_incentive['create_by'] = $_config['user']['pk_user_id'];
                    $ret['staff_incentive_insert'] = $devdb->insert_update('dev_staff_incentives', $sale_incentive);
                }
                $ret['sale_count_increment'] = $devdb->query("UPDATE dev_sales_incentive SET sale_count = sale_count + ".$value['item_quantity']." WHERE pk_sales_incentive_id = '" . $incentive['pk_sales_incentive_id'] . "'");
               
                $target = $financeManager->get_sales_targets(array('staff_id' => $staff_id, 'order_by' => array('col' => 'pk_target_id', 'order' => 'DESC'), 'single' => true));                
                $ret['target_increment'] = $devdb->query("UPDATE dev_sales_targets SET achievement_quantity = achievement_quantity + ".$value['item_quantity']." WHERE pk_target_id = '" . $target['pk_target_id'] . "' AND month_name = '$current_month'");
              
                $sale_income = array();
                $sale_income['fk_sale_id'] = $sale_id;
                $sale_income['fk_project_id'] = $project_id;
                $sale_income['fk_product_id'] = $value['fk_product_id'];
                $sale_income['fk_branch_id'] = $branch_id;
                $sale_income['buying_price'] = $value['buying_price'];
                $sale_income['selling_price'] = $value['item_price'];
                $sale_income['sale_income'] = ($value['item_quantity'] * ($value['item_price'] - $value['buying_price']))- $insert_data['sale_discount'];
                $sale_income['create_date'] = $dateNow;
                $sale_income['create_time'] = $timeNow;
                $sale_income['create_by'] = $_config['user']['pk_user_id'];
                $ret['sale_income_insert'] = $devdb->insert_update('dev_sales_income', $sale_income);
            }
        }
        return $ret;
    }

}

new dev_sale_management();
