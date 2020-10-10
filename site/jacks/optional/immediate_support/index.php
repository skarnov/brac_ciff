<?php

class immediate_support {

    var $thsClass = 'immediate_support';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Immediate Support',
            'permissions' => array(
                'manage_airport_land_support' => array(
                    'add_airport_land_support' => 'Add Airport Land Support',
                    'edit_airport_land_support' => 'Edit Airport Land Support',
                    'delete_airport_land_support' => 'Delete Airport Land Support',
                ),
                'manage_access_to_pp' => array(
                    'add_access_to_pp' => 'Add Access To PP',
                    'edit_access_to_pp' => 'Edit Access To PP',
                    'delete_access_to_pp' => 'Delete Access To PP',
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
            'label' => 'Immediate Support',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Airport/Land Support',
            'description' => 'Manage All Airport/Land Support',
            'menu_group' => 'Immediate Support',
            'position' => 'default',
            'action' => 'manage_airport_land_support',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_airport_land_support'))
            admenu_register($params);

        $params = array(
            'label' => 'Access To PP',
            'description' => 'Manage All Access To Public & Private',
            'menu_group' => 'Immediate Support',
            'position' => 'default',
            'action' => 'manage_access_to_pp',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_access_to_pp'))
            admenu_register($params);
    }

    function manage_airport_land_support() {
        if (!has_permission('manage_airport_land_support'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_airport_land_support');

        if ($_GET['action'] == 'add_edit_airport_land_support')
            include('pages/add_edit_airport_land_support.php');
        elseif ($_GET['action'] == 'deleteAirportLandSupport')
            include('pages/deleteAirportLandSupport.php');
        else
            include('pages/list_airport_land_support.php');
    }

    function manage_access_to_pp() {
        if (!has_permission('manage_access_to_pp'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_cases');

        if ($_GET['action'] == 'add_edit_access_to_pp')
            include('pages/add_edit_access_to_pp.php');
        elseif ($_GET['action'] == 'deleteAccessToPP')
            include('pages/deleteAccessToPP.php');
        else
            include('pages/list_access_to_pp.php');
    }

    function get_airport_land_supports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_airport_land_supports ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_airport_land_supports.pk_support_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_support_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $customers = sql_data_collector($sql, $count_sql, $param);
        return $customers;
    }

    function get_access_to_pp($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_access_to_pp ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_access_to_pp.pk_access_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_access_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $migrations = sql_data_collector($sql, $count_sql, $param);
        return $migrations;
    }

    function add_edit_airport_land_support($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_airport_land_supports(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid airport/land support id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $data = array();
            
            $data['is_participant'] = $params['form_data']['is_participant'];

            if ($params['form_data']['new_evaluate_services'] == NULL) {
                $data_type = $params['form_data']['evaluate_services'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $evaluate_services = $data_types;
            } elseif ($params['form_data']['evaluate_services'] == NULL) {
                $evaluate_services = $params['form_data']['new_evaluate_services'];
            } elseif ($params['form_data']['evaluate_services'] != NULL && $params['form_data']['new_evaluate_services'] != NULL) {
                $data_type = $params['form_data']['evaluate_services'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $evaluate_services = $params['form_data']['new_evaluate_services'] . ',' . $data_types;
            }

            $plan = array();
            if ($evaluate_services) {
                $plan['evaluate_services'] = $evaluate_services;
                if ($params['form_data']['new_social_protection']) {
                    $plan['social_protection'] = $params['form_data']['new_social_protection'];
                }
                if ($params['form_data']['new_security_measures']) {
                    $plan['security_measures'] = $params['form_data']['new_security_measures'];
                }
            }

            $data['evaluate_services'] = implode(',', $plan);

            if ($is_update) {
                $data['update_date'] = date('Y-m-d');
                $data['update_time'] = date('H:i:s');
                $data['modified_by'] = $_config['user']['pk_user_id'];
                $ret['evaluation_update'] = $devdb->insert_update('dev_airport_land_supports', $data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['evaluation_insert'] = $devdb->insert_update('dev_airport_land_supports', $data);
            }
        }
        return $ret;
    }

    function add_edit_access_to_pp($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_access_to_pp(array('customer_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid evaluation id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        if (!$ret['error']) {
            $data = array();
            $data['is_participant'] = $params['form_data']['is_participant'];

            if ($params['form_data']['new_evaluate_services'] == NULL) {
                $data_type = $params['form_data']['evaluate_services'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $evaluate_services = $data_types;
            } elseif ($params['form_data']['evaluate_services'] == NULL) {
                $evaluate_services = $params['form_data']['new_evaluate_services'];
            } elseif ($params['form_data']['evaluate_services'] != NULL && $params['form_data']['new_evaluate_services'] != NULL) {
                $data_type = $params['form_data']['evaluate_services'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $evaluate_services = $params['form_data']['new_evaluate_services'] . ',' . $data_types;
            }

            $plan = array();
            if ($evaluate_services) {
                $plan['evaluate_services'] = $evaluate_services;
                if ($params['form_data']['new_social_protection']) {
                    $plan['social_protection'] = $params['form_data']['new_social_protection'];
                }
                if ($params['form_data']['new_security_measures']) {
                    $plan['security_measures'] = $params['form_data']['new_security_measures'];
                }
            }

            $data['evaluate_services'] = implode(',', $plan);

            if ($is_update) {
                $data['update_date'] = date('Y-m-d');
                $data['update_time'] = date('H:i:s');
                $data['modified_by'] = $_config['user']['pk_user_id'];
                $ret['evaluation_update'] = $devdb->insert_update('dev_access_to_pp', $data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['evaluation_insert'] = $devdb->insert_update('dev_access_to_pp', $data);
            }
        }
        return $ret;
    }

}

new immediate_support();