<?php

class dev_support_management {

    var $thsClass = 'dev_support_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Supports',
            'permissions' => array(
                'manage_supports' => array(
                    'add_support' => 'Add Support',
                    'edit_support' => 'Edit Support',
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
            'label' => 'Supports',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Supports Management',
            'description' => 'Supports Management',
            'menu_group' => 'Supports',
            'position' => 'top',
            'action' => 'manage_supports',
            'iconClass' => 'fa-handshake-o',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_supports'))
            admenu_register($params);
    }

    function manage_supports() {
        if (!has_permission('manage_supports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_supports');

        if ($_GET['action'] == 'add_edit_psychosocial')
            include('pages/add_edit_psychosocial.php');
        elseif ($_GET['action'] == 'add_edit_social')
            include('pages/add_edit_social.php');
        elseif ($_GET['action'] == 'add_edit_economic')
            include('pages/add_edit_economic.php');
        elseif ($_GET['action'] == 'add_edit_session')
            include('pages/add_edit_session.php');
        elseif ($_GET['action'] == 'add_edit_counsellor')
            include('pages/add_edit_counsellor.php');
        elseif ($_GET['action'] == 'add_edit_family_counseling')
            include('pages/add_edit_family_counseling.php');
        elseif ($_GET['action'] == 'add_edit_completion')
            include('pages/add_edit_completion.php');
        elseif ($_GET['action'] == 'add_edit_evaluation')
            include('pages/add_edit_evaluation.php');
        elseif ($_GET['action'] == 'add_edit_social_evaluation')
            include('pages/add_edit_social_evaluation.php');
        elseif ($_GET['action'] == 'add_edit_economic_evaluation')
            include('pages/add_edit_economic_evaluation.php');
        elseif ($_GET['action'] == 'psycho_review')
            include('pages/psycho_review.php');
        elseif ($_GET['action'] == 'social_review')
            include('pages/social_review.php');
        elseif ($_GET['action'] == 'economic_review')
            include('pages/economic_review.php');
        else
            include('pages/list_supports.php');
    }

    function get_lookups($lookup_group) {
        $sql = "SELECT * FROM dev_lookups WHERE lookup_group = '$lookup_group'";
        $data = sql_data_collector($sql);
        return $data;
    }

    function get_supports($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_supports 
                    LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_supports.fk_customer_id)
                ";
        $where = "WHERE 1 ";
        $conditions = " ";

        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_supports.pk_support_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_supports.pk_support_id',
            'customer_id' => 'dev_customers.customer_id',
            'support_customer_id' => 'dev_supports.fk_customer_id',
            'division' => 'dev_supports.division_name',
            'district' => 'dev_supports.district_name',
            'sub_district' => 'dev_supports.sub_district_name',
            'support_status' => 'dev_supports.support_status',
            'support_type' => 'dev_supports.support_name',
            'branch_id' => 'dev_supports.fk_branch_id',
            'project_id' => 'dev_supports.fk_project_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_psychosocial($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_psycho_supports 
                    LEFT JOIN dev_supports ON (dev_supports.fk_support_id = dev_psycho_supports.pk_psycho_support_id)
                ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_psycho_supports.pk_psycho_support_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_psycho_supports.pk_psycho_support_id',
            'customer_id' => 'dev_psycho_supports.fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_session($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_psycho_sessions ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_psycho_session_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_psycho_session_id',
            'support_id' => 'fk_psycho_support_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_family_counselling($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_psycho_family_counselling ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_psycho_family_counselling_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_psycho_family_counselling_id',
            'support_id' => 'fk_psycho_support_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_counsellors($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_psycho_counsellors ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_counsellor_support_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_counsellor_support_id',
            'support_id' => 'fk_psycho_support_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_completion($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_psycho_completions ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_psycho_completion_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_psycho_completion_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_evaluation($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_psycho_evaluations ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_psycho_evaluation_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_psycho_evaluation_id',
            'support_id' => 'fk_psycho_support_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_social($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_social_supports ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_social_support_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_social_support_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_social_evaluation($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_social_evaluations ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_social_evaluation_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_social_evaluation_id',
            'support_id' => 'fk_social_support_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_economic($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_economic_supports ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_economic_support_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_economic_support_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function get_economic_evaluation($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_economic_evaluations ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_economic_evaluation_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_economic_evaluation_id',
            'support_id' => 'fk_economic_support_id',
            'customer_id' => 'fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_psychosocial($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_psychosocial(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid psychosocial support id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        $temp = form_validator::_length($params['required']['entry_date'], 490);
        if ($temp !== true)
            $ret['error'][] = 'Entry Date ' . $temp;

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['fk_project_id'] = $params['form_data']['project_id'];
            $insert_data['fk_customer_id'] = $params['form_data']['customer_id'];
            $insert_data['sub_type'] = $params['form_data']['sub_type'];
            $insert_data['entry_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));
            $insert_data['first_meeting'] = date('Y-m-d', strtotime($params['form_data']['first_meeting']));
            $insert_data['support_note'] = $params['form_data']['support_note'];
            $insert_data['support_place'] = $params['form_data']['support_place'];
            $insert_data['is_home_visit'] = $params['form_data']['is_home_visit'];
            $insert_data['problem_identified'] = $params['form_data']['problem_identified'];
            $insert_data['problem_description'] = $params['form_data']['problem_description'];
            $insert_data['problem_history'] = $params['form_data']['problem_history'];
            $insert_data['required_session'] = $params['form_data']['required_session'];
            $insert_data['session_duration'] = $params['form_data']['session_duration'];
            $insert_data['session_place'] = $params['form_data']['session_place'];
            $insert_data['is_family_counceling'] = $params['form_data']['is_family_counceling'];
            if ($insert_data['is_family_counceling'] == 'yes') {
                $insert_data['family_counseling'] = $params['form_data']['family_counseling'];
            }
            $insert_data['other_requirements'] = $params['form_data']['other_requirements'];
            $insert_data['assesment_score'] = $params['form_data']['assesment_score'];
            $insert_data['reffer_to'] = $params['form_data']['reffer_to'];
            $insert_data['referr_address'] = $params['form_data']['referr_address'];
            $insert_data['contact_number'] = $params['form_data']['contact_number'];
            $insert_data['reason_for_reffer'] = $params['form_data']['reason_for_reffer'];
            if ($params['form_data']['new_reason']) {
                $insert_data['reason_for_reffer'] = $params['form_data']['new_reason'];
            } else {
                $insert_data['reason_for_reffer'] = $params['form_data']['reason_for_reffer'];
            }

            if ($is_update) {
                $ret = $devdb->insert_update('dev_psycho_supports', $insert_data, " pk_psycho_support_id = '" . $is_update . "'");

                $support_data = array();
                $support_data['fk_project_id'] = $params['form_data']['project_id'];
                $support_data['fk_branch_id'] = $params['form_data']['branch_id'];

                $customerManager = jack_obj('dev_customer_management');
                $customer_info = $customerManager->get_returnees(array('customer_id' => $insert_data['fk_customer_id'], 'single' => true));

                $support_data['division_name'] = $customer_info['present_division'];
                $support_data['district_name'] = $customer_info['present_district'];
                $support_data['sub_district_name'] = $customer_info['present_sub_district'];

                $support_data['fk_customer_id'] = $params['form_data']['customer_id'];

                $support_data['support_status'] = 'ongoing';
                $support_data['support_name'] = 'psychosocial';
                $support_data['sub_type'] = $params['form_data']['sub_type'];
                $support_data['start_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));
                $ret = $devdb->insert_update('dev_supports', $support_data, " fk_support_id = '" . $is_update . "' AND support_name = 'psychosocial'");
            } else {
                $ret = $devdb->insert_update('dev_psycho_supports', $insert_data);
                $item_id = $is_update ? $is_update : $ret['success'];
                $support_data = array();
                $support_data['fk_project_id'] = $params['form_data']['project_id'];
                $support_data['fk_branch_id'] = $params['form_data']['branch_id'];

                $customerManager = jack_obj('dev_customer_management');
                $customer_info = $customerManager->get_returnees(array('customer_id' => $insert_data['fk_customer_id'], 'single' => true));

                $support_data['division_name'] = $customer_info['present_division'];
                $support_data['district_name'] = $customer_info['present_district'];
                $support_data['sub_district_name'] = $customer_info['present_sub_district'];

                $support_data['fk_customer_id'] = $params['form_data']['customer_id'];
                $support_data['fk_support_id'] = $item_id;
                $support_data['support_status'] = 'ongoing';
                $support_data['support_name'] = 'psychosocial';
                $support_data['sub_type'] = $params['form_data']['sub_type'];
                $support_data['start_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));
                $ret = $devdb->insert_update('dev_supports', $support_data);
            }
        }
        return $ret;
    }

    

    function add_edit_counsellor($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_counsellors(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid psychosocial counsellor id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        $temp = form_validator::_length($params['required']['entry_date'], 490);
        if ($temp !== true)
            $ret['error'][] = 'Entry Date ' . $temp;

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['fk_psycho_support_id'] = $params['form_data']['fk_psycho_support_id'];
            $insert_data['fk_customer_id'] = $params['form_data']['fk_customer_id'];
            $insert_data['entry_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));
            $insert_data['entry_time'] = $params['form_data']['entry_time'];
            $insert_data['home_visit'] = $params['form_data']['home_visit'];
            if ($params['form_data']['new_issue_discussed'] == NULL) {
                $data_type = $params['form_data']['issues_discussed'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $insert_data['issues_discussed'] = $data_types;
            } elseif ($params['form_data']['issues_discussed'] == NULL) {
                $insert_data['issues_discussed'] = $params['form_data']['new_issue_discussed'];
            } elseif ($params['form_data']['issues_discussed'] != NULL && $params['form_data']['new_issue_discussed'] != NULL) {
                $data_type = $params['form_data']['issues_discussed'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $insert_data['issues_discussed'] = $params['form_data']['new_issue_discussed'] . ',' . $data_types;
            }
            $insert_data['is_family_present'] = $params['form_data']['is_family_present'];
            if ($params['form_data']['is_family_present'] == 'yes') {
                $insert_data['how_many'] = $params['form_data']['how_many'];
            }

            $insert_data['counsellor_note'] = $params['form_data']['counsellor_note'];

            if ($is_update)
                $ret = $devdb->insert_update('dev_psycho_counsellors', $insert_data, " 	pk_counsellor_support_id = '" . $is_update . "'");
            else {
                $ret = $devdb->insert_update('dev_psycho_counsellors', $insert_data);
                $update = "UPDATE dev_supports SET para_counsellor = para_counsellor + 1 WHERE pk_support_id = '" . $params['form_data']['pk_support_id'] . "'";
                $devdb->query($update);
            }
        }

        if ($params['form_data']['new_issue_discussed']) {
            $data = array(
                'lookup_group' => 'issues_discussed',
                'lookup_value' => $params['form_data']['new_issue_discussed'],
            );
            $devdb->insert_update('dev_lookups', $data);
        }
        return $ret;
    }

//    function add_edit_family_counselling($params = array()) {
//        global $devdb, $_config;
//
//        $ret = array('success' => array(), 'error' => array());
//        $is_update = $params['edit'] ? $params['edit'] : array();
//
//        $oldData = array();
//        if ($is_update) {
//            $oldData = $this->get_family_counselling(array('id' => $is_update, 'single' => true));
//            if (!$oldData) {
//                return array('error' => ['Invalid psychosocial followup id, no data found']);
//            }
//        }
//
//        foreach ($params['required'] as $i => $v) {
//            if (isset($params['form_data'][$i]))
//                $temp = form_validator::required($params['form_data'][$i]);
//            if ($temp !== true) {
//                $ret['error'][] = $v . ' ' . $temp;
//            }
//        }
//
//        $temp = form_validator::_length($params['required']['entry_date'], 490);
//        if ($temp !== true)
//            $ret['error'][] = 'Entry Date ' . $temp;
//
//        if (!$ret['error']) {
//            $insert_data = array();
//            $insert_data['fk_psycho_support_id'] = $params['form_data']['fk_psycho_support_id'];
//            $insert_data['fk_customer_id'] = $params['form_data']['fk_customer_id'];
//            $insert_data['entry_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));
//            $insert_data['entry_time'] = $params['form_data']['entry_time'];
//            $insert_data['session_place'] = $params['form_data']['session_place'];
//            $insert_data['session_comments'] = $params['form_data']['session_comments'];
//            if ($is_update)
//                $ret = $devdb->insert_update('dev_psycho_family_counselling', $insert_data, " pk_psycho_family_counselling_id = '" . $is_update . "'");
//            else {
//                $ret = $devdb->insert_update('dev_psycho_family_counselling', $insert_data);
//                $update = "UPDATE dev_supports SET family_counselling = family_counselling + 1 WHERE pk_support_id = '" . $params['form_data']['pk_support_id'] . "'";
//                $devdb->query($update);
//            }
//        }
//        return $ret;
//    }

  

    function add_edit_evaluation($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_evaluation(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid social evaluation id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        $temp = form_validator::_length($params['required']['entry_date'], 490);
        if ($temp !== true)
            $ret['error'][] = 'Entry Date ' . $temp;

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['entry_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));
            $insert_data['fk_psycho_support_id'] = $params['form_data']['fk_psycho_support_id'];
            $insert_data['fk_customer_id'] = $params['form_data']['fk_customer_id'];

            $insert_data['sleepwell_night'] = $params['form_data']['sleepwell_night'];
            $insert_data['happy_family'] = $params['form_data']['happy_family'];
            $insert_data['enjoy_life'] = $params['form_data']['enjoy_life'];
            $insert_data['visit_neighbors'] = $params['form_data']['visit_neighbors'];
            $insert_data['mental_health'] = $params['form_data']['mental_health'];
            $insert_data['family_tension'] = $params['form_data']['family_tension'];
            $insert_data['secure_feeling'] = $params['form_data']['secure_feeling'];
            $insert_data['feeling_free'] = $params['form_data']['feeling_free'];
            $insert_data['family_behave'] = $params['form_data']['family_behave'];
            $insert_data['positive_impact'] = $params['form_data']['positive_impact'];

            $insert_data['evaluated_score'] = @($insert_data['sleepwell_night'] + $insert_data['happy_family'] + $insert_data['enjoy_life'] + $insert_data['visit_neighbors'] + $insert_data['mental_health'] + $insert_data['family_tension'] + $insert_data['secure_feeling'] + $insert_data['feeling_free'] + $insert_data['family_behave'] + $insert_data['positive_impact']);

            if ($insert_data['evaluated_score'] >= 35) {
                $insert_data['review_remarks'] = 'Successfully Reintegrated';
            } else {
                $insert_data['review_remarks'] = 'Not Reintegrated';
            }

            if ($is_update)
                $ret = $devdb->insert_update('dev_psycho_evaluations', $insert_data, " pk_psycho_evaluation_id = '" . $is_update . "'");
            else {
                $ret = $devdb->insert_update('dev_psycho_evaluations', $insert_data);
                $update = "UPDATE dev_supports SET total_evaluation = total_evaluation + 1, support_status = 'evaluated', evaluation_date = '" . date('Y-m-d', strtotime($params['form_data']['entry_date'])) . "' WHERE pk_support_id = '" . $params['form_data']['pk_support_id'] . "'";
                $devdb->query($update);
            }
        }
        return $ret;
    }

    function add_edit_social($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_social(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid social support id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        $temp = form_validator::_length($params['required']['entry_date'], 490);
        if ($temp !== true)
            $ret['error'][] = 'Entry Date ' . $temp;

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['fk_project_id'] = $params['form_data']['project_id'];
            $insert_data['fk_customer_id'] = $params['form_data']['customer_id'];
            $insert_data['entry_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));
            $insert_data['is_migration_forum_member'] = $params['form_data']['is_migration_forum_member'];

            if ($params['form_data']['new_support'] == NULL) {
                $data_type = $params['form_data']['support_received'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $insert_data['support_received'] = $data_types;
            } elseif ($params['form_data']['support_received'] == NULL) {
                $insert_data['support_received'] = $params['form_data']['new_support'];
            } elseif ($params['form_data']['support_received'] != NULL && $params['form_data']['new_support'] != NULL) {
                $data_type = $params['form_data']['support_received'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $insert_data['support_received'] = $params['form_data']['new_support'] . ',' . $data_types;
            }

            if ($params['form_data']['new_reason'] == NULL) {
                $data_type = $params['form_data']['support_from_whom'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $insert_data['support_from_whom'] = $data_types;
            } elseif ($params['form_data']['support_from_whom'] == NULL) {
                $insert_data['support_from_whom'] = $params['form_data']['new_reason'];
            } elseif ($params['form_data']['support_from_whom'] != NULL && $params['form_data']['new_reason'] != NULL) {
                $data_type = $params['form_data']['support_from_whom'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $insert_data['support_from_whom'] = $params['form_data']['new_reason'] . ',' . $data_types;
            }

            $insert_data['is_participated_show'] = $params['form_data']['is_participated_show'];
            $insert_data['learn_show'] = $params['form_data']['learn_show'];
            $insert_data['is_per_community_video'] = $params['form_data']['is_per_community_video'];
            $insert_data['learn_video'] = $params['form_data']['learn_video'];
            $insert_data['end_date'] = date('Y-m-d', strtotime($params['form_data']['end_date']));

            if ($is_update) {
                $ret = $devdb->insert_update('dev_social_supports', $insert_data, " pk_social_support_id = '" . $is_update . "'");
                $support_data = array();
                $support_data['fk_project_id'] = $params['form_data']['project_id'];
                $support_data['fk_branch_id'] = $params['form_data']['branch_id'];
                $support_data['fk_customer_id'] = $params['form_data']['customer_id'];

                $customerManager = jack_obj('dev_customer_management');
                $customer_info = $customerManager->get_returnees(array('customer_id' => $insert_data['fk_customer_id'], 'single' => true));

                $support_data['division_name'] = $customer_info['present_division'];
                $support_data['district_name'] = $customer_info['present_district'];
                $support_data['sub_district_name'] = $customer_info['present_sub_district'];

                $support_data['fk_support_id'] = $is_update;
                $support_data['support_name'] = 'social';
                $support_data['start_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));
                $support_data['end_date'] = date('Y-m-d', strtotime($params['form_data']['end_date']));

                $ret = $devdb->insert_update('dev_supports', $support_data, " fk_support_id = '" . $is_update . "' AND support_name = 'social'");
            } else {
                $ret = $devdb->insert_update('dev_social_supports', $insert_data);

                $item_id = $is_update ? $is_update : $ret['success'];
                $support_data = array();
                $support_data['fk_project_id'] = $params['form_data']['project_id'];
                $support_data['fk_branch_id'] = $params['form_data']['branch_id'];
                $support_data['fk_customer_id'] = $params['form_data']['customer_id'];

                $customerManager = jack_obj('dev_customer_management');
                $customer_info = $customerManager->get_returnees(array('customer_id' => $insert_data['fk_customer_id'], 'single' => true));

                $support_data['division_name'] = $customer_info['present_division'];
                $support_data['district_name'] = $customer_info['present_district'];
                $support_data['sub_district_name'] = $customer_info['present_sub_district'];

                $support_data['fk_support_id'] = $item_id;
                $support_data['support_status'] = 'ongoing';
                $support_data['support_name'] = 'social';
                $support_data['start_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));
                $support_data['end_date'] = date('Y-m-d', strtotime($params['form_data']['end_date']));
                $ret = $devdb->insert_update('dev_supports', $support_data);
            }
        }
        return $ret;
    }

    function add_edit_social_evaluation($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_social_evaluation(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid psychosocial social_evaluation id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        $temp = form_validator::_length($params['required']['entry_date'], 490);
        if ($temp !== true)
            $ret['error'][] = 'Entry Date ' . $temp;

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['entry_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));
            $insert_data['fk_social_support_id'] = $params['form_data']['fk_social_support_id'];
            $insert_data['fk_customer_id'] = $params['form_data']['fk_customer_id'];

            $insert_data['government_programme'] = $params['form_data']['government_programme'];
            $insert_data['public_services'] = $params['form_data']['public_services'];
            $insert_data['access_social'] = $params['form_data']['access_social'];
            $insert_data['family_treat'] = $params['form_data']['family_treat'];
            $insert_data['friends_treat'] = $params['form_data']['friends_treat'];
            $insert_data['relatives_treat'] = $params['form_data']['relatives_treat'];
            $insert_data['community_treat'] = $params['form_data']['community_treat'];
            $insert_data['important_family'] = $params['form_data']['important_family'];
            $insert_data['adapted_community'] = $params['form_data']['adapted_community'];
            $insert_data['pleased_society'] = $params['form_data']['pleased_society'];

            $insert_data['evaluated_score'] = @($insert_data['government_programme'] + $insert_data['public_services'] + $insert_data['access_social'] + $insert_data['family_treat'] + $insert_data['friends_treat'] + $insert_data['relatives_treat'] + $insert_data['community_treat'] + $insert_data['important_family'] + $insert_data['adapted_community'] + $insert_data['pleased_society']);
            if ($insert_data['evaluated_score'] >= 35) {
                $insert_data['review_remarks'] = 'Successfully Reintegrated';
            } else {
                $insert_data['review_remarks'] = 'Not Reintegrated';
            }

            if ($is_update)
                $ret = $devdb->insert_update('dev_social_evaluations', $insert_data, " pk_social_evaluation_id = '" . $is_update . "'");
            else {
                $ret = $devdb->insert_update('dev_social_evaluations', $insert_data);
                $update = "UPDATE dev_supports SET total_evaluation = total_evaluation + 1, support_status = 'evaluated', evaluation_date = '" . date('Y-m-d', strtotime($params['form_data']['entry_date'])) . "' WHERE pk_support_id = '" . $params['form_data']['pk_support_id'] . "'";
                $devdb->query($update);
            }
        }
        return $ret;
    }

    function add_edit_economic($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_economic(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid economic support id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        $temp = form_validator::_length($params['required']['entry_date'], 490);
        if ($temp !== true)
            $ret['error'][] = 'Entry Date ' . $temp;

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['fk_project_id'] = $params['form_data']['project_id'];

            $insert_data['inkind_received'] = $params['form_data']['inkind_received'];
            if ($insert_data['inkind_received'] == 'yes') {
                $insert_data['inkind_amount'] = $params['form_data']['inkind_amount'];
            }

            $insert_data['fk_customer_id'] = $params['form_data']['customer_id'];
            $insert_data['is_attended_fair'] = $params['form_data']['is_attended_fair'];
            $insert_data['is_financial_training'] = $params['form_data']['is_financial_training'];
            $insert_data['training_duration'] = $params['form_data']['training_duration'];
            $insert_data['entry_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));

            if ($params['form_data']['new_vocational_training']) {
                $insert_data['received_vocational_training'] = $params['form_data']['new_vocational_training'];
            } else {
                $insert_data['received_vocational_training'] = $params['form_data']['received_vocational_training'];
            }
            if ($params['form_data']['new_support']) {
                $insert_data['referral_support_required'] = $params['form_data']['new_support'];
            } else {
                $insert_data['referral_support_required'] = $params['form_data']['referral_support_required'];
            }
            $insert_data['start_date'] = date('Y-m-d', strtotime($params['form_data']['start_date']));
            $insert_data['close_date'] = date('Y-m-d', strtotime($params['form_data']['close_date']));
            $insert_data['training_status'] = $params['form_data']['training_status'];
            $insert_data['is_certification_received'] = $params['form_data']['is_certification_received'];
            $insert_data['training_used'] = $params['form_data']['training_used'];
            $insert_data['other_comments'] = $params['form_data']['other_comments'];
            $insert_data['is_referrals_done'] = $params['form_data']['is_referrals_done'];
            $insert_data['referral_name'] = $params['form_data']['referral_name'];
            $insert_data['referral_address'] = $params['form_data']['referral_address'];
            $insert_data['is_assistance_received'] = $params['form_data']['is_assistance_received'];
            $insert_data['is_assistance_remigrate'] = $params['form_data']['is_assistance_remigrate'];
            $insert_data['remigrate_country'] = $params['form_data']['remigrate_country'];
            $insert_data['assistance_utilize'] = $params['form_data']['assistance_utilize'];
            $insert_data['other_comments'] = $params['form_data']['other_comments'];
            $insert_data['end_date'] = date('Y-m-d', strtotime($params['form_data']['end_date']));

            if ($is_update) {
                $ret = $devdb->insert_update('dev_economic_supports', $insert_data, " pk_economic_support_id = '" . $is_update . "'");
                
                $support_data = array();
                $support_data['fk_project_id'] = $params['form_data']['project_id'];
                $support_data['fk_branch_id'] = $params['form_data']['branch_id'];
                $support_data['fk_customer_id'] = $params['form_data']['customer_id'];

                $customerManager = jack_obj('dev_customer_management');
                $customer_info = $customerManager->get_returnees(array('customer_id' => $insert_data['fk_customer_id'], 'single' => true));

                $support_data['division_name'] = $customer_info['present_division'];
                $support_data['district_name'] = $customer_info['present_district'];
                $support_data['sub_district_name'] = $customer_info['present_sub_district'];

                $support_data['fk_support_id'] = $is_update;
                $support_data['support_name'] = 'economic';
                $support_data['start_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));
                $support_data['end_date'] = date('Y-m-d', strtotime($params['form_data']['end_date']));
                
                $ret = $devdb->insert_update('dev_supports', $support_data, " fk_support_id = '" . $is_update . "' AND support_name = 'economic'");

            } else {
                $ret = $devdb->insert_update('dev_economic_supports', $insert_data);
                $item_id = $is_update ? $is_update : $ret['success'];
                $support_data = array();
                $support_data['fk_project_id'] = $params['form_data']['project_id'];
                $support_data['fk_branch_id'] = $params['form_data']['branch_id'];
                $support_data['fk_customer_id'] = $params['form_data']['customer_id'];

                $customerManager = jack_obj('dev_customer_management');
                $customer_info = $customerManager->get_returnees(array('customer_id' => $insert_data['fk_customer_id'], 'single' => true));

                $support_data['division_name'] = $customer_info['present_division'];
                $support_data['district_name'] = $customer_info['present_district'];
                $support_data['sub_district_name'] = $customer_info['present_sub_district'];

                $support_data['fk_support_id'] = $item_id;
                $support_data['support_status'] = 'ongoing';
                $support_data['support_name'] = 'economic';
                $support_data['start_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));
                $support_data['end_date'] = date('Y-m-d', strtotime($params['form_data']['end_date']));
                $ret = $devdb->insert_update('dev_supports', $support_data);
            }

            if ($params['form_data']['new_vocational_training']) {
                $data = array(
                    'lookup_group' => 'economic_vocational_training',
                    'lookup_value' => $params['form_data']['new_vocational_training'],
                );
                $devdb->insert_update('dev_lookups', $data);
            }
        }
        return $ret;
    }

    function add_edit_economic_evaluation($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_economic_evaluation(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid psychosocial economic_evaluation id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        $temp = form_validator::_length($params['required']['entry_date'], 490);
        if ($temp !== true)
            $ret['error'][] = 'Entry Date ' . $temp;

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['entry_date'] = date('Y-m-d', strtotime($params['form_data']['entry_date']));
            $insert_data['fk_economic_support_id'] = $params['form_data']['fk_economic_support_id'];
            $insert_data['fk_customer_id'] = $params['form_data']['fk_customer_id'];

            $insert_data['happy_income'] = $params['form_data']['happy_income'];
            $insert_data['food_security'] = $params['form_data']['food_security'];
            $insert_data['work_support'] = $params['form_data']['work_support'];
            $insert_data['job_support'] = $params['form_data']['job_support'];
            $insert_data['satisfied_economic'] = $params['form_data']['satisfied_economic'];
            $insert_data['debt_situation'] = $params['form_data']['debt_situation'];
            $insert_data['happy_savings'] = $params['form_data']['happy_savings'];
            $insert_data['satisfied_family'] = $params['form_data']['satisfied_family'];
            $insert_data['peaceful_economic'] = $params['form_data']['peaceful_economic'];
            $insert_data['neighbor_respect'] = $params['form_data']['neighbor_respect'];

            $insert_data['evaluated_score'] = @($insert_data['happy_income'] + $insert_data['food_security'] + $insert_data['work_support'] + $insert_data['job_support'] + $insert_data['satisfied_economic'] + $insert_data['debt_situation'] + $insert_data['happy_savings'] + $insert_data['satisfied_family'] + $insert_data['peaceful_economic'] + $insert_data['neighbor_respect']);

            if ($insert_data['evaluated_score'] >= 35) {
                $insert_data['review_remarks'] = 'Successfully Reintegrated';
            } else {
                $insert_data['review_remarks'] = 'Not Reintegrated';
            }

            if ($is_update)
                $ret = $devdb->insert_update('dev_economic_evaluations', $insert_data, " pk_economic_evaluation_id = '" . $is_update . "'");
            else {
                $ret = $devdb->insert_update('dev_economic_evaluations', $insert_data);
                $update = "UPDATE dev_supports SET total_evaluation = total_evaluation + 1, support_status = 'evaluated', evaluation_date = '" . date('Y-m-d', strtotime($params['form_data']['entry_date'])) . "' WHERE pk_support_id = '" . $params['form_data']['pk_support_id'] . "'";
                $devdb->query($update);
            }
        }
        return $ret;
    }

}

new dev_support_management();
