<?php

class dev_stock_management {

    var $thsClass = 'dev_stock_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Stocks',
            'permissions' => array(
                'manage_stocks' => array(
                    'add_stock' => 'Add Stock',
                    'edit_stock' => 'Edit Stock',
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
            'label' => 'Stocks',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Stock Management',
            'description' => 'Manage All Stocks',
            'menu_group' => 'Business',
            'position' => 'top',
            'action' => 'manage_stocks',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_stocks'))
            admenu_register($params);
    }

    function manage_stocks() {
        if (!has_permission('manage_stocks'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_stocks');
        include('pages/list_stocks.php');
    }

    function get_stocks($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_stocks "
                . "LEFT JOIN dev_products ON (dev_products.pk_product_id = dev_stocks.fk_product_id)"
                . "LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_stocks.fk_branch_id) ";
        $where = "WHERE 1 ";

        if ($param['stock_quantity']) {
            $conditions = "AND stock_quantity > 0";
        }

        if ($param['empty_stock']) {
            $conditions = "AND stock_quantity <= 0";
        }

        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_stock_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'stock_id' => 'dev_stocks.pk_stock_id',
            'project_id' => 'dev_stocks.fk_project_id',
            'branch_id' => 'dev_stocks.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;
        
        $stocks = sql_data_collector($sql, $count_sql, $param);
        return $stocks;
    }

    function get_prices($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_stock_prices ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_stock_price_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'stock_id' => 'fk_stock_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);

        return $results;
    }

    function add_edit_stock($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_stocks(array('stock_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid stock id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        $temp = form_validator::_length($params['required']['stock_name'], 490);
        if ($temp !== true)
            $ret['error'][] = 'Stock Name ' . $temp;

        if (!$ret['error']) {
            $insert_data = array();
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');
            $insert_data['fk_project_id'] = $params['form_data']['project_id'];
            $insert_data['fk_branch_id'] = $params['form_data']['branch_id'];
            $insert_data['fk_product_id'] = $params['form_data']['product_id'];
            $insert_data['stock_in'] = date('Y-m-d', strtotime($params['form_data']['stock_in']));
            $insert_data['expiration_date'] = date('Y-m-d', strtotime($params['form_data']['expiration_date']));
            $insert_data['stock_quantity'] = $params['form_data']['stock_quantity'];
            $insert_data['buying_price'] = $params['form_data']['buying_price'];
            $insert_data['sale_price'] = $params['form_data']['sale_price'];

            if ($is_update) {
                $insert_data['update_date'] = $dateNow;
                $insert_data['update_time'] = $timeNow;
                $insert_data['update_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_stocks', $insert_data, " pk_stock_id = '" . $is_update . "'");
            } else {
                $insert_data['create_date'] = $dateNow;
                $insert_data['create_time'] = $timeNow;
                $insert_data['create_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_stocks', $insert_data);
            }

            if ($ret['success']) {
                $stock_id = $is_update ? $is_update : $ret['success'];
                if (!$is_update || ($insert_data['sale_price'] != $oldData['sale_price'])) {
                    $insertUpdate = array(
                        'fk_stock_id' => $stock_id,
                        'item_price' => $insert_data['sale_price'],
                        'create_date' => $dateNow,
                        'create_time' => $timeNow,
                        'create_by' => $_config['user']['pk_user_id']
                    );
                    $devdb->insert_update('dev_stock_prices', $insertUpdate);
                }
            }
        }
        return $ret;
    }

}

new dev_stock_management();
