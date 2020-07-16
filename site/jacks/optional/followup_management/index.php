<?php

class dev_followup_management {

    var $thsClass = 'dev_followup_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Followups',
            'permissions' => array(
                'manage_followups' => array(
                    'add_followup' => 'Add Followup',
                    'edit_followup' => 'Edit Followup',
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
            'label' => 'Followups',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Followup Management',
            'description' => 'Manage All Followups',
            'menu_group' => 'Supports',
            'position' => 'top',
            'action' => 'manage_followups',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_followups'))
            admenu_register($params);
    }

    function manage_followups() {
        if (!has_permission('manage_followups'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_followups');

        if ($_GET['action'] == 'add_edit_followup')
            include('pages/add_edit_followup.php');
        else
            include('pages/list_followups.php');
    }

    function get_followups($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        
        $from = "FROM dev_followups 
                    LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_followups.fk_customer_id)
                ";
       
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_followup_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'followup_id' => 'dev_followups.pk_followup_id',
            'customer_id' => 'dev_customers.customer_id',
            'division' => 'dev_followups.division_name',
            'district' => 'dev_followups.district_name',
            'sub_district' => 'dev_followups.sub_district_name',
            'branch_id' => 'dev_followups.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $followups = sql_data_collector($sql, $count_sql, $param);
        return $followups;
    }

    function add_edit_followup($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_followups(array('followup_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid followup id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        $temp = form_validator::_length($params['required']['customer_id'], 490);
        if ($temp !== true)
            $ret['error'][] = 'Select Customer ' . $temp;

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['fk_branch_id'] = $params['form_data']['branch_id'];
            $insert_data['fk_customer_id'] = $params['form_data']['customer_id'];

            $customerManager = jack_obj('dev_customer_management');
            $customer_info = $customerManager->get_returnees(array('customer_id' => $insert_data['fk_customer_id'], 'single' => true));

            $insert_data['division_name'] = $customer_info['present_division'];
            $insert_data['district_name'] = $customer_info['present_district'];
            $insert_data['sub_district_name'] = $customer_info['present_sub_district'];

            $insert_data['followup_date'] = date('Y-m-d', strtotime($params['form_data']['followup_date']));
            $insert_data['beneficiary_status'] = $params['form_data']['beneficiary_status'];
            $insert_data['followup_challenges'] = $params['form_data']['followup_challenges'];
            $insert_data['action_taken'] = $params['form_data']['action_taken'];

            if ($is_update) {
                $insert_data['update_date'] = date('Y-m-d');
                $insert_data['update_time'] = date('H:i:s');
                $insert_data['update_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_followups', $insert_data, " pk_followup_id = '" . $is_update . "'");
            } else {
                $insert_data['create_date'] = date('Y-m-d');
                $insert_data['create_time'] = date('H:i:s');
                $insert_data['create_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_followups', $insert_data);
            }
        }
        return $ret;
    }

}

new dev_followup_management();
