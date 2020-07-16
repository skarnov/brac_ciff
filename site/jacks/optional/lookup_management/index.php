<?php

class dev_lookup_management {

    var $thsClass = 'dev_lookup_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Lookups',
            'permissions' => array(
                'manage_lookups' => array(
                    'add_lookup' => 'Add Lookup',
                    'edit_lookup' => 'Edit Lookup',
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
            'label' => 'Lookups',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Lookup Management',
            'description' => 'Manage All Lookups Data',
            'menu_group' => 'Administration',
            'position' => 'default',
            'action' => 'manage_lookups',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_lookups'))
            admenu_register($params);
    }

    function manage_lookups() {
        if (!has_permission('manage_lookups'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_lookups');
        
        if ($_GET['action'] == 'add_edit_lookup')
            include('pages/add_edit_lookup.php');
        else
            include('pages/list_lookups.php');  
    }

    function get_lookups($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_lookups ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_lookup_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'lookup_id' => 'dev_lookups.pk_lookup_id',
            'lookup_group' => 'dev_lookups.lookup_group'
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $lookups = sql_data_collector($sql, $count_sql, $param);

        return $lookups;
    }

    function add_edit_lookup($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_lookups(array('lookup_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid lookup Data, no data found']);
            }
        }

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['lookup_value'] = $params['form_data']['lookup_value'];
           
            if ($is_update)
                $ret = $devdb->insert_update('dev_lookups', $insert_data, " pk_lookup_id = '" . $is_update . "'");
            else {
//                $ret = $devdb->insert_update('dev_lookups', $insert_data);
            }
        }
        return $ret;
    }
}

new dev_lookup_management();
