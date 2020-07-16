<?php
class dev_product_management {

    var $thsClass = 'dev_product_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Products',
            'permissions' => array(
                'manage_products' => array(
                    'add_product' => 'Add Product',
                    'edit_product' => 'Edit Product',
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
            'label' => 'Products',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Product Management',
            'description' => 'Manage All Products',
            'menu_group' => 'Business',
            'position' => 'top',
            'action' => 'manage_products',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_products'))
            admenu_register($params);
    }

    function manage_products() {
        if (!has_permission('manage_products'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_products');
        
        include('pages/list_products.php');
    }

    function get_products($param = null) {        
        $param['single'] = $param['single'] ? $param['single'] : false;
        
        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_products ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_product_id) AS TOTAL " . $from . $where;
        
        $loopCondition = array(
            'product_id' => 'pk_product_id',
            'type' => 'product_type',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $products = sql_data_collector($sql, $count_sql, $param);
        
        return $products; 
    }

    function add_edit_product($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_products(array('product_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid product id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        $temp = form_validator::_length($params['required']['product_name'], 490);
        if ($temp !== true)
            $ret['error'][] = 'Product Name ' . $temp;

        if (!$ret['error']) {
            $insert_data = array();
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');
            $insert_data['product_name'] = $params['form_data']['product_name'];
            $insert_data['product_type'] = $params['form_data']['product_type'];
            $insert_data['product_sku'] = $params['form_data']['product_sku'];

            if ($is_update){
                $insert_data['update_date'] = $dateNow;
                $insert_data['update_time'] = $timeNow;
                $insert_data['update_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_products', $insert_data, " pk_product_id = '" . $is_update . "'");
            }else {
                $insert_data['create_date'] = $dateNow;
                $insert_data['create_time'] = $timeNow;
                $insert_data['create_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_products', $insert_data);
            }

            if($ret['success']){
                $product_id = $is_update ? $is_update : $ret['success'];
                if(!$is_update || ($insert_data['selling_price'] != $oldData['selling_price'])){
                    $insertUpdate = array(
                        'fk_product_id' => $product_id,
                        '_price' => $insert_data['selling_price'],
                        'create_date' => $dateNow,
                        'create_time' => $timeNow,
                        'create_by' => $_config['user']['pk_user_id']
                    );
                    $devdb->insert_update('dev_product_prices', $insertUpdate);
                }
            }
        }
        return $ret;
    }
}

new dev_product_management();
