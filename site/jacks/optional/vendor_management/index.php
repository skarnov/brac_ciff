<?php

class dev_vendor_management {

    var $thsClass = 'dev_vendor_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Vendors',
            'permissions' => array(
                'manage_vendors' => array(
                    'add_vendor' => 'Add Vendor',
                    'edit_vendor' => 'Edit Vendor',
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
            'label' => 'Vendors',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Company Management',
            'description' => 'Manage All Vendors',
            'menu_group' => 'Business',
            'position' => 'top',
            'action' => 'manage_vendors',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_vendors'))
            admenu_register($params);
    }

    function manage_vendors() {
        if (!has_permission('manage_vendors'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_vendors');
        include('pages/list_vendors.php');
    }

    function get_vendors($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;
        $param['index_with'] = isset($param['index_with']) ? $param['index_with'] : 'pk_vendor_id';

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_vendors ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_vendor_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'vendor_id' => 'pk_vendor_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $vendors = sql_data_collector($sql, $count_sql, $param);

        return $vendors;
    }

    function add_edit_vendor($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_vendors(array('vendor_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid vendor id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        $temp = form_validator::_length($params['required']['company_name'], 490);
        if ($temp !== true)
            $ret['error'][] = 'Company Name ' . $temp;

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['company_name'] = $params['form_data']['company_name'];
            if ($params['form_data']['new_business'] == NULL) {
                $data_type = $params['form_data']['company_business'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $company_business = $data_types;
            } elseif ($params['form_data']['company_business'] == NULL) {
                $company_business = $params['form_data']['new_business'];
            } elseif ($params['form_data']['company_business'] != NULL && $params['form_data']['new_business'] != NULL) {
                $data_type = $params['form_data']['company_business'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $company_business = $params['form_data']['new_business'] . ',' . $data_types;
            }
            $insert_data['company_business'] = $company_business;
            $insert_data['vendor_country'] = $params['form_data']['vendor_country'];
            $insert_data['contact_name'] = $params['form_data']['contact_name'];
            $insert_data['contact_number'] = $params['form_data']['contact_number'];
            $insert_data['vendor_address'] = $params['form_data']['vendor_address'];

            if ($is_update) {
                $insert_data['update_date'] = date('Y-m-d');
                $insert_data['update_time'] = date('H:i:s');
                $insert_data['update_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_vendors', $insert_data, " pk_vendor_id = '" . $is_update . "'");
            } else {
                $insert_data['create_date'] = date('Y-m-d');
                $insert_data['create_time'] = date('H:i:s');
                $insert_data['create_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_vendors', $insert_data);
            }
            if ($params['form_data']['new_business']) {
                $data = array(
                    'lookup_group' => 'company_business',
                    'lookup_value' => $params['form_data']['new_business'],
                );
                $devdb->insert_update('dev_lookups', $data);
            }
        }
        return $ret;
    }
}

new dev_vendor_management();
