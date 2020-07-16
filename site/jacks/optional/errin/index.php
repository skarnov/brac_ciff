<?php

class dev_errin {

    var $thsClass = 'dev_errin';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'ERRIN',
            'permissions' => array(
                'manage_errin_returnees' => array(
                    'add_errin_returnee' => 'Add Errin Returnee',
                    'edit_errin_returnee' => 'Edit Errin Returnee',
                ),
                'manage_cases' => array(
                    'add_case' => 'Add Case',
                    'edit_case' => 'Edit Case',
                ),
                'manage_meetings' => array(
                    'add_meeting' => 'Add Meeting',
                    'edit_meeting' => 'Edit Meeting',
                ),
                'manage_plans' => array(
                    'add_plan' => 'Add Plan',
                    'edit_plan' => 'Edit Plan',
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
            'label' => 'Returnees',
            'description' => 'Manage All Returnees',
            'menu_group' => 'ERRIN',
            'position' => 'default',
            'action' => 'manage_returnees',
            'iconClass' => 'fa-users',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_errin_returnees'))
            admenu_register($params);
        
        $params = array(
            'label' => 'Case Management',
            'iconClass' => 'fa-bullseye',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Case Management',
            'description' => 'Manage All Cases',
            'menu_group' => 'ERRIN',
            'position' => 'default',
            'action' => 'manage_cases',
            'iconClass' => 'fa-bullseye',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_cases'))
            admenu_register($params);
        
        $params = array(
            'label' => 'Meeting Schedules',
            'description' => 'Manage All Meeting Schedules',
            'menu_group' => 'ERRIN',
            'position' => 'default',
            'action' => 'manage_meetings',
            'iconClass' => 'fa-wpexplorer',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_meetings'))
            admenu_register($params);
        
        $params = array(
            'label' => 'Reintegration Plan',
            'description' => 'Manage All Reintegration Plan',
            'menu_group' => 'ERRIN',
            'position' => 'default',
            'action' => 'manage_plans',
            'iconClass' => 'fa-life-ring',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_plans'))
            admenu_register($params);
    }
    
    function manage_returnees() {
        if (!has_permission('manage_errin_returnees'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_returnees');
        if ($_GET['action'] == 'add_edit_returnee')
            include('pages/add_edit_returnee.php');
        else
            include('pages/list_returnees.php');
    }

    function get_errin_returnees($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        
        $from = "FROM dev_customers 
                    LEFT JOIN errin_meta ON (errin_meta.fk_customer_id = dev_customers.pk_customer_id)
                ";
        
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_customers.pk_customer_id',
            'name' => 'dev_customers.full_name',
            'type' => 'errin_meta.returnee_type',
            'sex' => 'dev_customers.customer_gender',
            'contact_person' => 'dev_customers.emergency_mobile',
            'email' => 'errin_meta.email_address',
            'mobile' => 'dev_customers.customer_mobile',
            'skype' => 'errin_meta.skype_id',
            'customer_type' => 'dev_customers.customer_type',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);

        return $results;
    }

    function add_edit_errin_returnee($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_errin_returnees(array('returnee_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid returnee id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }
        
        if ($_FILES['user_document']['name']) {
            $supported_ext = array('zip');
            $max_filesize = 512000;
            $target_dir = _path('uploads', 'absolute') . "/";
            if (!file_exists($target_dir))
                mkdir($target_dir);
            $target_file = $target_dir . basename($_FILES['user_document']['name']);
            $fileinfo = pathinfo($target_file);
            $target_file = $target_dir . str_replace(' ', '_', $fileinfo['filename']) . '_' . time() . '.' . $fileinfo['extension'];
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            if (in_array(strtolower($imageFileType), $supported_ext)) {
                if ($max_filesize && $_FILES['user_document']['size'] <= $max_filesize) {
                    if (!move_uploaded_file($_FILES['user_document']['tmp_name'], $target_file)) {
                        $ret['error'][] = 'Customer Document : File was not uploaded, please try again.';
                        $params['form_data']['user_document'] = '';
                    } else {
                        $fileinfo = pathinfo($target_file);
                        $params['form_data']['user_document'] = $fileinfo['basename'];
                        @unlink(_path('uploads', 'absolute') . '/' . $params['form_data']['user_old_document']);
                    }
                } else
                    $ret['error'][] = 'Customer Document : <strong>' . $_FILES['user_document']['size'] . ' B</strong> is more than supported file size <strong>' . $max_filesize . ' B';
            } else
                $ret['error'][] = 'Customer Document : <strong>.' . $imageFileType . '</strong> is not supported extension. Only supports .' . implode(', .', $supported_ext);
        }
        else {
            $params['form_data']['user_document'] = $params['form_data']['user_old_document'];
        }

        if (!$ret['error']) {
            $insert_data = array();
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');
            $insert_data['full_name'] = $params['form_data']['returnee_name'];
            $insert_data['customer_gender'] = $params['form_data']['returnee_sex'];
            $insert_data['customer_birthdate'] = date('Y-m-d', strtotime($params['form_data']['birth_date']));
            $insert_data['customer_mobile'] = $params['form_data']['customer_mobile'];
            $insert_data['emergency_mobile'] = $params['form_data']['contact_phone_number'];
            $insert_data['customer_type'] = 'errin';

            if ($is_update) {
                $insert_data['update_date'] = $dateNow;
                $insert_data['update_time'] = $timeNow;
                $insert_data['update_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_customers', $insert_data, " pk_customer_id = '" . $is_update . "'");

                $metadata = array();
                $metadata['fk_customer_id'] = $is_update;
                $metadata['returnee_type'] = $params['form_data']['returnee_type'];
                $metadata['family_name'] = $params['form_data']['family_name'];
                $metadata['first_name'] = $params['form_data']['first_name'];
                $metadata['other_name'] = $params['form_data']['other_name'];
                $metadata['family_part'] = $params['form_data']['family_part'];
                $metadata['having_voluntary'] = $params['form_data']['having_voluntary'];
                if ($params['form_data']['new_group']) {
                    $metadata['having_group'] = $params['form_data']['new_group'];
                } else {
                    $metadata['having_group'] = $params['form_data']['having_group'];
                }
                $metadata['email_address'] = $params['form_data']['email_address'];
                $metadata['skype_id'] = $params['form_data']['skype_id'];
                $metadata['user_document'] = $params['form_data']['user_document'];
                $ret['update_meta'] = $devdb->insert_update('errin_meta', $metadata, " fk_customer_id = '" . $is_update . "'");
            } else {
                $insert_data['create_date'] = $dateNow;
                $insert_data['create_time'] = $timeNow;
                $insert_data['create_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_customers', $insert_data);

                $metadata = array();
                $metadata['fk_customer_id'] = $ret['success'];
                $metadata['returnee_type'] = $params['form_data']['returnee_type'];
                $metadata['family_name'] = $params['form_data']['family_name'];
                $metadata['first_name'] = $params['form_data']['first_name'];
                $metadata['other_name'] = $params['form_data']['other_name'];
                $metadata['family_part'] = $params['form_data']['family_part'];
                $metadata['having_voluntary'] = $params['form_data']['having_voluntary'];
                if ($params['form_data']['new_group']) {
                    $metadata['having_group'] = $params['form_data']['new_group'];
                } else {
                    $metadata['having_group'] = $params['form_data']['having_group'];
                }
                $metadata['email_address'] = $params['form_data']['email_address'];
                $metadata['skype_id'] = $params['form_data']['skype_id'];
                $metadata['user_document'] = $params['form_data']['user_document'];
                $ret['insert_meta'] = $devdb->insert_update('errin_meta', $metadata);
            }
        }
        return $ret;
    }

    function manage_cases() {
        if (!has_permission('manage_cases'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_cases');
        if ($_GET['action'] == 'add_edit_case')
            include('pages/add_edit_case.php');
        else
            include('pages/list_cases.php');
    }

    function get_cases($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_cases "
                    . "LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_cases.fk_customer_id)";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_case_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'case_id' => 'pk_case_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);

        return $results;
    }

    function add_edit_case($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_cases(array('case_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid case id, no data found']);
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
            $insert_data = array();
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');
            $insert_data['case_status'] = 'active';
            $insert_data['reintegration_spend'] = $params['form_data']['reintegration_spend'];
            $insert_data['epi_number'] = $params['form_data']['epi_number'];
            $insert_data['close_date'] = date('Y-m-d', strtotime($params['form_data']['close_date']));
            $insert_data['epi_country'] = $params['form_data']['present_country'];
            $insert_data['fk_customer_id'] = $params['form_data']['customer_id'];

            $customer_info = $this->get_errin_returnees(array('id' => $insert_data['fk_customer_id'], 'single' => true));
            $insert_data['returnee_name'] = $customer_info['full_name'];
            $insert_data['family_name'] = $customer_info['family_name'];
            $insert_data['first_name'] = $customer_info['first_name'];
            
            if ($is_update) {
                $insert_data['update_date'] = $dateNow;
                $insert_data['update_time'] = $timeNow;
                $insert_data['update_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_cases', $insert_data, " pk_case_id = '" . $is_update . "'");
            } else {
                $insert_data['create_date'] = $dateNow;
                $insert_data['create_time'] = $timeNow;
                $insert_data['create_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_cases', $insert_data);
                
                if($ret['success']){
                    $case = array();
                    if($customer_info['returnee_type'] == 'individual'){
                        $case['type'] = 'INV';
                    }else{
                        $case['type'] = 'FAM';
                    }
                    $case['departure'] = array_search($insert_data['epi_country'], getWorldCountry());
                    $case_id = 'BG-'.implode('-',$case).'-'.$ret['success'];
                    $ret['case_id'] = $devdb->query("UPDATE dev_cases SET case_number = '" . $case_id . "' WHERE pk_case_id = '" . $ret['success'] . "'");           
                }
            }
        }
        return $ret;
    }

    function manage_meetings() {
        if (!has_permission('manage_meetings'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_meetings');
        if ($_GET['action'] == 'add_edit_meeting')
            include('pages/add_edit_meeting.php');
        else
            include('pages/list_meetings.php');
    }

    function get_meetings($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_meetings "
                    . "LEFT JOIN dev_cases ON (dev_cases.pk_case_id = dev_meetings.fk_case_id)";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_meeting_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'meeting_id' => 'pk_meeting_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);

        return $results;
    }

    function add_edit_meeting($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_meetings(array('meeting_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid meeting id, no data found']);
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
            $insert_data = array();
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');
            $insert_data['meeting_title'] = $params['form_data']['meeting_title'];
            $insert_data['fk_customer_id'] = $params['form_data']['customer_id'];
            $insert_data['fk_case_id'] = $params['form_data']['case_id'];
            $insert_data['meeting_type'] = $params['form_data']['meeting_type'];
            $insert_data['complete_date'] = date('Y-m-d', strtotime($params['form_data']['complete_date']));
            $insert_data['meeting_note'] = $params['form_data']['meeting_note'];

            if ($is_update) {
                $insert_data['update_date'] = $dateNow;
                $insert_data['update_time'] = $timeNow;
                $insert_data['update_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_meetings', $insert_data, " pk_meeting_id = '" . $is_update . "'");
            } else {
                $insert_data['create_date'] = $dateNow;
                $insert_data['create_time'] = $timeNow;
                $insert_data['create_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_meetings', $insert_data);
            }
        }
        return $ret;
    }
    
    function manage_plans() {
        if (!has_permission('manage_plans'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_plans');
        if ($_GET['action'] == 'add_edit_plan')
            include('pages/add_edit_plan.php');
        else
            include('pages/list_plans.php');
    }

    function get_plans($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM errin_reintegration "
                . "LEFT JOIN dev_cases ON (dev_cases.pk_case_id = errin_reintegration.fk_case_id)";
        $where = " WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_reintegration_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'plan_id' => 'pk_reintegration_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;
  
        $results = sql_data_collector($sql, $count_sql, $param);

        return $results;
    }

    function add_edit_plan($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_plans(array('plan_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid plan id, no data found']);
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
            $insert_data = array();
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');
            $insert_data['reintegration_title'] = $params['form_data']['reintegration_title'];
            $insert_data['fk_customer_id'] = $params['form_data']['customer_id'];
            $insert_data['fk_case_id'] = $params['form_data']['case_id'];
            $insert_data['delivered_date'] = date('Y-m-d', strtotime($params['form_data']['delivered_date']));
            $insert_data['reintegration_status'] = $params['form_data']['reintegration_status'];

            if ($is_update && ($oldData['reintegration_status'] != $insert_data['reintegration_status'])) {
                $insert_data['status_change_date'] = date('Y-m-d');
            }
            
            $insert_data['reintegration_summary'] = $params['form_data']['reintegration_summary'];
            $insert_data['delivered_service'] = $params['form_data']['delivered_service'];
            $insert_data['immediate_needs'] = $params['form_data']['immediate_needs'];
            
            if ($is_update) {
                $insert_data['update_date'] = $dateNow;
                $insert_data['update_time'] = $timeNow;
                $insert_data['update_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('errin_reintegration', $insert_data, " pk_reintegration_id = '" . $is_update . "'");
            } else {
                $insert_data['create_date'] = $dateNow;
                $insert_data['create_time'] = $timeNow;
                $insert_data['create_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('errin_reintegration', $insert_data);
            }
        }
        return $ret;
    }
}

new dev_errin();