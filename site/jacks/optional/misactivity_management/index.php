<?php

class dev_misactivity_management {

    var $thsClass = 'dev_misactivity_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'MIS Activity',
            'permissions' => array(
                'manage_misactivities' => array(
                    'add_misactivity' => 'Add MIS Activity',
                    'edit_misactivity' => 'Edit MIS Activity',
                    'delete_misactivity' => 'Delete MIS Activity',
                ),
                'manage_targets' => array(
                    'add_target' => 'Add Target',
                    'edit_target' => 'Edit Target',
                    'delete_target' => 'Delete Target',
                ),
                'manage_achievements' => array(
                    'add_achievement' => 'Add Achievement',
                    'edit_achievement' => 'Edit Achievement',
                    'delete_achievement' => 'Delete Achievement',
                ),
                'manage_misreports' => array(
                    'view_misreport' => 'View MIS Report',
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
            'label' => 'MIS Activity',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Activities Management',
            'description' => 'Manage All MIS Activities',
            'menu_group' => 'MIS Activity',
            'position' => 'default',
            'action' => 'manage_misactivities',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misactivities'))
            admenu_register($params);

        $params = array(
            'label' => 'Target Management',
            'description' => 'Manage All MIS Target',
            'menu_group' => 'MIS Activity',
            'position' => 'default',
            'action' => 'manage_targets',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_targets'))
            admenu_register($params);

        $params = array(
            'label' => 'Achievements',
            'description' => 'Manage All Achievements',
            'menu_group' => 'MIS Activity',
            'position' => 'default',
            'action' => 'manage_achievements',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_achievements'))
            admenu_register($params);

        $params = array(
            'label' => 'MIS Report',
            'description' => 'View All MIS Reports',
            'menu_group' => 'MIS Activity',
            'position' => 'default',
            'action' => 'manage_misreports',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_misreports'))
            admenu_register($params);
    }

    function manage_misactivities() {
        if (!has_permission('manage_misactivities'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_misactivities');

        if ($_GET['action'] == 'add_edit_misactivity')
            include('pages/add_edit_misactivity.php');
        elseif ($_GET['action'] == 'deleteMisactivity')
            include('pages/deleteMisactivity.php');
        else
            include('pages/list_misactivities.php');
    }

    function manage_targets() {
        if (!has_permission('manage_targets'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_targets');

        if ($_GET['action'] == 'add_edit_target')
            include('pages/add_edit_target.php');
        elseif ($_GET['action'] == 'add_edit_mis_target')
            include('pages/add_edit_mis_target.php');
        elseif ($_GET['action'] == 'deleteTarget')
            include('pages/deleteTarget.php');
        else
            include('pages/list_targets.php');
    }

    function manage_achievements() {
        if (!has_permission('manage_achievements'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_achievements');

        include('pages/list_achievements.php');
    }

    function manage_misreports() {
        if (!has_permission('manage_misreports'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_misreports');

        include('pages/list_misreports.php');
    }

    function get_misactivities($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_activities 
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_activities.fk_project_id)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_activities.pk_activity_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_activities.pk_activity_id',
            'project_id' => 'dev_activities.fk_project_id',
            'activity_name' => 'dev_activities.activity_name',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $misactivities = sql_data_collector($sql, $count_sql, $param);
        return $misactivities;
    }

    function add_edit_misactivity($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_misactivities(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid activity id, no data found']);
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
            $misactivity_data = array();
            $misactivity_data['fk_project_id'] = $params['form_data']['project_id'];
            $misactivity_data['activity_name'] = $params['form_data']['activity_name'];

            if ($is_update) {
                $misactivity_data['update_date'] = date('Y-m-d');
                $misactivity_data['update_time'] = date('H:i:s');
                $misactivity_data['modified_by'] = $_config['user']['pk_user_id'];
                $ret['misactivity_update'] = $devdb->insert_update('dev_activities', $misactivity_data, " pk_activity_id = '" . $is_update . "'");
            } else {
                $misactivity_data['create_date'] = date('Y-m-d');
                $misactivity_data['create_time'] = date('H:i:s');
                $misactivity_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['misactivity_insert'] = $devdb->insert_update('dev_activities', $misactivity_data);
            }
        }
        return $ret;
    }

    function get_months() {
        $data = array(
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        );

        return $data;
    }

    function get_targets($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_targets 
                LEFT JOIN dev_projects ON (dev_projects.pk_project_id  = dev_targets.fk_project_id)
                LEFT JOIN dev_branches ON (dev_branches.pk_branch_id  = dev_targets.fk_branch_id)
                LEFT JOIN dev_activities ON (dev_activities.pk_activity_id  = dev_targets.fk_activity_id)
            ";

        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_targets.pk_target_id',
            'month' => 'dev_targets.month',
            'branch_id' => 'dev_targets.fk_branch_id',
            'district' => 'dev_targets.district',
            'sub_district' => 'dev_targets.sub_district',
            'project_id' => 'dev_targets.fk_project_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_initial_evaluation($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_initial_evaluation(array('misactivity_id' => $is_update, 'single' => true));
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
                $ret['evaluation_update'] = $devdb->insert_update('dev_initial_evaluation', $data, " fk_misactivity_id = '" . $is_update . "'");
            } else {
                $ret['evaluation_insert'] = $devdb->insert_update('dev_initial_evaluation', $data);
            }
        }
        return $ret;
    }

    function get_satisfaction_scale($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_reintegration_satisfaction_scale ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_satisfaction_scale) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_satisfaction_scale',
            'misactivity_id' => 'fk_misactivity_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_satisfaction_scale($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_satisfaction_scale(array('misactivity_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid satisfaction_scale id, no data found']);
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
            $satisfaction_scale_data = array();
            $satisfaction_scale_data['satisfied_assistance'] = $params['form_data']['satisfied_assistance'];
            $satisfaction_scale_data['satisfied_counseling'] = $params['form_data']['satisfied_counseling'];
            $satisfaction_scale_data['satisfied_economic'] = $params['form_data']['satisfied_economic'];
            $satisfaction_scale_data['satisfied_social'] = $params['form_data']['satisfied_social'];
            $satisfaction_scale_data['satisfied_community'] = $params['form_data']['satisfied_community'];
            $satisfaction_scale_data['satisfied_reintegration'] = $params['form_data']['satisfied_reintegration'];
            $satisfaction_scale_data['total_score'] = ($satisfaction_scale_data['satisfied_assistance'] + $satisfaction_scale_data['satisfied_counseling'] + $satisfaction_scale_data['satisfied_economic'] + $satisfaction_scale_data['satisfied_social'] + $satisfaction_scale_data['satisfied_community'] + $satisfaction_scale_data['satisfied_reintegration']);

            if ($is_update) {
                $satisfaction_scale_data['update_date'] = date('Y-m-d');
                $satisfaction_scale_data['update_time'] = date('H:i:s');
                $satisfaction_scale_data['modified_by'] = $_config['user']['pk_user_id'];
                $ret['satisfaction_update'] = $devdb->insert_update('dev_reintegration_satisfaction_scale', $satisfaction_scale_data, " fk_misactivity_id = '" . $is_update . "'");
            } else {
                $ret['satisfaction_new_insert'] = $devdb->insert_update('dev_reintegration_satisfaction_scale', $satisfaction_scale_data);
            }
        }
        return $ret;
    }

    function get_cases($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']) {
            $from = "FROM dev_immediate_supports 
                        LEFT JOIN dev_misactivities ON (dev_misactivities.pk_misactivity_id = dev_immediate_supports.fk_misactivity_id)
                        LEFT JOIN dev_reintegration_plan ON (dev_reintegration_plan.fk_misactivity_id = dev_immediate_supports.fk_misactivity_id)
            ";
        } else {
            $from = "FROM dev_immediate_supports
                LEFT JOIN dev_misactivities ON (dev_misactivities.pk_misactivity_id = dev_immediate_supports.fk_misactivity_id)
                        LEFT JOIN dev_reintegration_plan ON (dev_reintegration_plan.fk_misactivity_id = dev_immediate_supports.fk_misactivity_id)
                        LEFT JOIN dev_psycho_supports ON (dev_psycho_supports.fk_misactivity_id = dev_immediate_supports.fk_misactivity_id)
                        LEFT JOIN dev_psycho_family_counselling ON (dev_psycho_family_counselling.fk_misactivity_id = dev_immediate_supports.fk_misactivity_id)
                        LEFT JOIN dev_psycho_sessions ON (dev_psycho_sessions.fk_misactivity_id = dev_immediate_supports.fk_misactivity_id)
                        LEFT JOIN dev_psycho_completions ON (dev_psycho_completions.fk_misactivity_id = dev_immediate_supports.fk_misactivity_id)
                        LEFT JOIN dev_psycho_followups ON (dev_psycho_followups.fk_misactivity_id = dev_immediate_supports.fk_misactivity_id)
                        LEFT JOIN dev_economic_supports ON (dev_economic_supports.fk_misactivity_id = dev_immediate_supports.fk_misactivity_id)
                        LEFT JOIN dev_economic_reintegration_referrals ON (dev_economic_reintegration_referrals.fk_misactivity_id = dev_immediate_supports.fk_misactivity_id)
                        LEFT JOIN dev_social_supports ON (dev_social_supports.fk_misactivity_id = dev_immediate_supports.fk_misactivity_id)
                        LEFT JOIN dev_followups ON (dev_followups.fk_misactivity_id = dev_immediate_supports.fk_misactivity_id)
            ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_immediate_supports.fk_misactivity_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_immediate_supports.fk_misactivity_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $cases = sql_data_collector($sql, $count_sql, $param);
        return $cases;
    }

    function add_edit_case($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_cases(array('id' => $is_update, 'single' => true));
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
            /*
              -------------------------------------------------------------------
              | Table Name : dev_immediate_supports
              |------------------------------------------------------------------
             */
            $immediate_support = array();
            $immediate_support['fk_branch_id'] = $_config['user']['user_branch'];
            $immediate_support['fk_staff_id'] = $params['form_data']['fk_staff_id'];

            $data_type = $params['form_data']['immediate_support'];
            $data_types = is_array($data_type) ? implode(',', $data_type) : '';
            $immediate_support['immediate_support'] = $data_types;

            if ($is_update) {
                $immediate_support['update_date'] = date('Y-m-d');
                $immediate_support['update_time'] = date('H:i:s');
                $immediate_support['update_by'] = $_config['user']['pk_user_id'];
                $ret['support_update'] = $devdb->insert_update('dev_immediate_supports', $immediate_support, " fk_misactivity_id = '" . $is_update . "'");
            }

            /*
              -------------------------------------------------------------------
              | Table Name : dev_reintegration_plan
              |------------------------------------------------------------------
             */

            $reintegration_plan = array();

            if ($params['form_data']['new_service_requested'] == NULL) {
                $data_type = $params['form_data']['service_requested'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $reintegration_plan['service_requested'] = $data_types;
            } elseif ($params['form_data']['service_requested'] == NULL) {
                $reintegration_plan['other_service_requested'] = $params['form_data']['new_service_requested'];
            } elseif ($params['form_data']['service_requested'] != NULL && $params['form_data']['new_service_requested'] != NULL) {
                $data_type = $params['form_data']['service_requested'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $reintegration_plan['service_requested'] = $data_types;
                $reintegration_plan['other_service_requested'] = $params['form_data']['new_service_requested'];
            }

            $reintegration_plan['reintegration_financial_service'] = $params['form_data']['reintegration_financial_service'];

            if ($params['form_data']['new_social_protection']) {
                $reintegration_plan['social_protection'] = $params['form_data']['new_social_protection'];
            }
            if ($params['form_data']['new_security_measures']) {
                $reintegration_plan['security_measure'] = $params['form_data']['new_security_measures'];
            }

            $reintegration_plan['service_requested_note'] = $params['form_data']['service_requested_note'];
            if ($is_update) {
                $sql = "SELECT fk_misactivity_id FROM dev_reintegration_plan WHERE fk_misactivity_id = '$is_update'";
                $pre_misactivity_id = $devdb->get_row($sql);

                if ($pre_misactivity_id) {
                    $ret['reintegration_update'] = $devdb->insert_update('dev_reintegration_plan', $reintegration_plan, " fk_misactivity_id = '" . $is_update . "'");
                } else {
                    $reintegration_plan['fk_misactivity_id'] = $is_update;
                    $ret['reintegration_insert'] = $devdb->insert_update('dev_reintegration_plan', $reintegration_plan);
                }
            }

            /*
              -------------------------------------------------------------------
              | Table Name : dev_psycho_supports
              |------------------------------------------------------------------
             */

            $psycho_supports = array();
            $psycho_supports['first_meeting'] = date('Y-m-d', strtotime($params['form_data']['first_meeting']));
            $psycho_supports['is_home_visit'] = $params['form_data']['is_home_visit'];

            if ($params['form_data']['new_issue_discussed'] == NULL) {
                $data_type = $params['form_data']['issue_discussed'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $psycho_supports['issue_discussed'] = $data_types;
            } elseif ($params['form_data']['issue_discussed'] == NULL) {
                $psycho_supports['other_issue_discussed'] = $params['form_data']['new_issue_discussed'];
            } elseif ($params['form_data']['issue_discussed'] != NULL && $params['form_data']['new_issue_discussed'] != NULL) {
                $data_type = $params['form_data']['issue_discussed'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $psycho_supports['issue_discussed'] = $data_types;
                $psycho_supports['other_issue_discussed'] = $params['form_data']['new_issue_discussed'];
            }

            if ($params['form_data']['new_problem_identified'] == NULL) {
                $data_type = $params['form_data']['problem_identified'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $psycho_supports['problem_identified'] = $data_types;
            } elseif ($params['form_data']['problem_identified'] == NULL) {
                $psycho_supports['problem_identified'] = $params['form_data']['new_problem_identified'];
            } elseif ($params['form_data']['problem_identified'] != NULL && $params['form_data']['new_problem_identified'] != NULL) {
                $data_type = $params['form_data']['problem_identified'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $psycho_supports['problem_identified'] = $params['form_data']['new_problem_identified'] . ',' . $data_types;
            }

            $psycho_supports['problem_description'] = $params['form_data']['problem_description'];
            $psycho_supports['initial_plan'] = $params['form_data']['initial_plan'];
            $psycho_supports['is_family_counceling'] = $params['form_data']['is_family_counceling'];
            $psycho_supports['family_counseling'] = $params['form_data']['family_counseling'];

            if ($params['form_data']['new_session_place']) {
                $psycho_supports['session_place'] = $params['form_data']['new_session_place'];
            } else {
                $psycho_supports['session_place'] = $params['form_data']['session_place'];
            }

            $psycho_supports['session_number'] = $params['form_data']['session_number'];
            $psycho_supports['session_duration'] = $params['form_data']['session_duration'];
            $psycho_supports['other_requirements'] = $params['form_data']['other_requirements'];
            $psycho_supports['reffer_to'] = $params['form_data']['reffer_to'];
            $psycho_supports['referr_address'] = $params['form_data']['referr_address'];
            $psycho_supports['contact_number'] = $params['form_data']['contact_number'];

            if ($params['form_data']['new_reason_for_reffer'] == NULL) {
                $data_type = $params['form_data']['reason_for_reffer'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $psycho_supports['reason_for_reffer'] = $data_types;
            } elseif ($params['form_data']['reason_for_reffer'] == NULL) {
                $psycho_supports['other_reason_for_reffer'] = $params['form_data']['new_reason_for_reffer'];
            } elseif ($params['form_data']['reason_for_reffer'] != NULL && $params['form_data']['new_reason_for_reffer'] != NULL) {
                $data_type = $params['form_data']['reason_for_reffer'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $psycho_supports['reason_for_reffer'] = $data_types;
                $psycho_supports['other_reason_for_reffer'] = $params['form_data']['new_reason_for_reffer'];
            }

            if ($is_update) {
                $sql = "SELECT fk_misactivity_id FROM dev_psycho_supports WHERE fk_misactivity_id = '$is_update'";
                $pre_misactivity_id = $devdb->get_row($sql);

                if ($pre_misactivity_id) {
                    $ret['psycho_support_update'] = $devdb->insert_update('dev_psycho_supports', $psycho_supports, " fk_misactivity_id = '" . $is_update . "'");
                } else {
                    $psycho_supports['fk_misactivity_id'] = $is_update;
                    $ret['psycho_support_insert'] = $devdb->insert_update('dev_psycho_supports', $psycho_supports);
                }
            }

            /*
              -------------------------------------------------------------------
              | Table Name : dev_economic_supports
              |------------------------------------------------------------------
             */

            $economic_supports_data = array();

            if ($params['form_data']['new_inkind_project'] == NULL) {
                $data_type = $params['form_data']['inkind_project'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_supports_data['inkind_project'] = $data_types;
            } elseif ($params['form_data']['inkind_project'] == NULL) {
                $economic_supports_data['other_inkind_project'] = $params['form_data']['new_inkind_project'];
            } elseif ($params['form_data']['inkind_project'] != NULL && $params['form_data']['new_inkind_project'] != NULL) {
                $data_type = $params['form_data']['inkind_project'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_supports_data['inkind_project'] = $data_types;
                $economic_supports_data['other_inkind_project'] = $params['form_data']['new_inkind_project'];
            }

            $economic_supports_data['inkind_received'] = $params['form_data']['inkind_received'];
            $economic_supports_data['training_duration'] = $params['form_data']['training_duration'];
            $economic_supports_data['is_certification_received'] = $params['form_data']['is_certification_received'];
            $economic_supports_data['training_used'] = $params['form_data']['training_used'];
            $economic_supports_data['other_comments'] = $params['form_data']['economic_other_comments'];
            $economic_supports_data['microbusiness_established'] = $params['form_data']['microbusiness_established'];
            $economic_supports_data['month_inauguration'] = $params['form_data']['month_inauguration'];
            $economic_supports_data['year_inauguration'] = $params['form_data']['year_inauguration'];
            $economic_supports_data['family_training'] = $params['form_data']['family_training'];

            $economic_supports_data['traning_entry_date'] = date('Y-m-d', strtotime($params['form_data']['traning_entry_date']));
            $economic_supports_data['duration_traning'] = $params['form_data']['duration_traning'];
            $economic_supports_data['training_status'] = $params['form_data']['training_status'];

            if ($params['form_data']['new_received_vocational_training'] == NULL) {
                $data_type = $params['form_data']['received_vocational_training'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_supports_data['received_vocational_training'] = $data_types;
            } elseif ($params['form_data']['received_vocational_training'] == NULL) {
                $economic_supports_data['other_received_vocational_training'] = $params['form_data']['new_received_vocational_training'];
            } elseif ($params['form_data']['received_vocational_training'] != NULL && $params['form_data']['new_received_vocational_training'] != NULL) {
                $data_type = $params['form_data']['received_vocational_training'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_supports_data['received_vocational_training'] = $data_types;
                $economic_supports_data['other_received_vocational_training'] = $params['form_data']['new_received_vocational_training'];
            }

            $economic_supports_data['training_start_date'] = date('Y-m-d', strtotime($params['form_data']['training_start_date']));
            $economic_supports_data['training_end_date'] = date('Y-m-d', strtotime($params['form_data']['training_end_date']));

            if ($is_update) {
                $sql = "SELECT fk_misactivity_id FROM dev_economic_supports WHERE fk_misactivity_id = '$is_update'";
                $pre_misactivity_id = $devdb->get_row($sql);

                if ($pre_misactivity_id) {
                    $ret['economic_support_update'] = $devdb->insert_update('dev_economic_supports', $economic_supports_data, " fk_misactivity_id = '" . $is_update . "'");
                } else {
                    $economic_supports_data['fk_misactivity_id'] = $is_update;
                    $ret['economic_support_insert'] = $devdb->insert_update('dev_economic_supports', $economic_supports_data);
                }
            }

            /*
              -------------------------------------------------------------------
              | Table Name : dev_economic_reintegration_referrals
              |------------------------------------------------------------------
             */

            $economic_reintegration_data = array();
            $economic_reintegration_data['is_vocational_training'] = $params['form_data']['is_vocational_training'];
            if ($params['form_data']['new_received_vocational'] == NULL) {
                $data_type = $params['form_data']['received_vocational'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_reintegration_data['received_vocational'] = $data_types;
            } elseif ($params['form_data']['received_vocational'] == NULL) {
                $economic_reintegration_data['other_received_vocational'] = $params['form_data']['new_received_vocational'];
            } elseif ($params['form_data']['received_vocational'] != NULL && $params['form_data']['new_received_vocational'] != NULL) {
                $data_type = $params['form_data']['received_vocational'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_reintegration_data['received_vocational'] = $data_types;
                $economic_reintegration_data['other_received_vocational'] = $params['form_data']['new_received_vocational'];
            }
            $economic_reintegration_data['is_certificate_received'] = $params['form_data']['is_certificate_received'];
            $economic_reintegration_data['used_far'] = $params['form_data']['used_far'];
            $economic_reintegration_data['other_comments'] = $params['form_data']['economic_referrals_other_comments'];
            $economic_reintegration_data['is_economic_services'] = $params['form_data']['is_economic_services'];

            if ($params['form_data']['new_economic_support'] == NULL) {
                $data_type = $params['form_data']['economic_support'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_reintegration_data['economic_support'] = $data_types;
            } elseif ($params['form_data']['economic_support'] == NULL) {
                $economic_reintegration_data['other_economic_support'] = $params['form_data']['new_economic_support'];
            } elseif ($params['form_data']['economic_support'] != NULL && $params['form_data']['new_economic_support'] != NULL) {
                $data_type = $params['form_data']['economic_support'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_reintegration_data['economic_support'] = $data_types;
                $economic_reintegration_data['other_economic_support'] = $params['form_data']['new_economic_support'];
            }

            $economic_reintegration_data['economic_financial_service'] = $params['form_data']['economic_financial_service'];

            $economic_reintegration_data['is_assistance_received'] = $params['form_data']['is_assistance_received'];
            $economic_reintegration_data['refferd_to'] = $params['form_data']['refferd_to'];
            $economic_reintegration_data['refferd_address'] = $params['form_data']['refferd_address'];
            $economic_reintegration_data['trianing_date'] = date('Y-m-d', strtotime($params['form_data']['trianing_date']));
            $economic_reintegration_data['place_of_training'] = $params['form_data']['place_of_training'];
            $economic_reintegration_data['duration_training'] = $params['form_data']['duration_training'];
            $economic_reintegration_data['status_traning'] = $params['form_data']['status_traning'];
            $economic_reintegration_data['assistance_utilized'] = $params['form_data']['assistance_utilized'];

            if ($is_update) {
                $sql = "SELECT fk_misactivity_id FROM dev_economic_reintegration_referrals WHERE fk_misactivity_id = '$is_update'";
                $pre_misactivity_id = $devdb->get_row($sql);

                if ($pre_misactivity_id) {
                    $ret['economic_support_update'] = $devdb->insert_update('dev_economic_reintegration_referrals', $economic_reintegration_data, " fk_misactivity_id = '" . $is_update . "'");
                } else {
                    $economic_reintegration_data['fk_misactivity_id'] = $is_update;
                    $ret['economic_support_insert'] = $devdb->insert_update('dev_economic_reintegration_referrals', $economic_reintegration_data);
                }
            }

            /*
              -------------------------------------------------------------------
              | Table Name : dev_social_supports
              |------------------------------------------------------------------
             */

            $dev_social_supports_data = array();

            if ($params['form_data']['new_reintegration_economic'] == NULL) {
                $data_type = $params['form_data']['reintegration_economic'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $dev_social_supports_data['reintegration_economic'] = $data_types;
            } elseif ($params['form_data']['reintegration_economic'] == NULL) {
                $dev_social_supports_data['other_reintegration_economic'] = $params['form_data']['new_reintegration_economic'];
            } elseif ($params['form_data']['reintegration_economic'] != NULL && $params['form_data']['new_reintegration_economic'] != NULL) {
                $data_type = $params['form_data']['reintegration_economic'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $dev_social_supports_data['reintegration_economic'] = $data_types;
                $dev_social_supports_data['other_reintegration_economic'] = $params['form_data']['new_reintegration_economic'];
            }

            $dev_social_supports_data['soical_date'] = date('Y-m-d', strtotime($params['form_data']['soical_date']));
            $dev_social_supports_data['medical_date'] = date('Y-m-d', strtotime($params['form_data']['medical_date']));
            $dev_social_supports_data['date_education'] = date('Y-m-d', strtotime($params['form_data']['date_education']));
            $dev_social_supports_data['date_housing'] = date('Y-m-d', strtotime($params['form_data']['date_housing']));
            $dev_social_supports_data['date_legal'] = date('Y-m-d', strtotime($params['form_data']['date_legal']));
            $dev_social_supports_data['attended_ipt'] = $params['form_data']['attended_ipt'];
            $dev_social_supports_data['learn_show'] = $params['form_data']['learn_show'];
            $dev_social_supports_data['is_per_community_video'] = $params['form_data']['is_per_community_video'];
            $dev_social_supports_data['learn_video'] = $params['form_data']['learn_video'];

            if ($params['form_data']['new_supportreferred'] == NULL) {
                $data_type = $params['form_data']['support_referred'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $dev_social_supports_data['support_referred'] = $data_types;
            } elseif ($params['form_data']['support_referred'] == NULL) {
                $dev_social_supports_data['other_support_referred'] = $params['form_data']['new_supportreferred'];
            } elseif ($params['form_data']['support_referred'] != NULL && $params['form_data']['new_supportreferred'] != NULL) {
                $data_type = $params['form_data']['support_referred'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $dev_social_supports_data['support_referred'] = $data_types;
                $dev_social_supports_data['other_support_referred'] = $params['form_data']['new_supportreferred'];
            }

            if ($is_update) {
                $sql = "SELECT fk_misactivity_id FROM dev_social_supports WHERE fk_misactivity_id = '$is_update'";
                $pre_misactivity_id = $devdb->get_row($sql);

                if ($pre_misactivity_id) {
                    $ret['social_support_update'] = $devdb->insert_update('dev_social_supports', $dev_social_supports_data, " fk_misactivity_id = '" . $is_update . "'");
                } else {
                    $dev_social_supports_data['fk_misactivity_id'] = $is_update;
                    $ret['social_support_insert'] = $devdb->insert_update('dev_social_supports', $dev_social_supports_data);
                }
            }

            /*
              -------------------------------------------------------------------
              | Table Name : dev_followups
              |------------------------------------------------------------------
             */

            $dev_followups_data = array();

            $dev_followups_data['casedropped'] = $params['form_data']['casedropped'];
            if ($params['form_data']['new_reason_dropping'] == NULL) {
                $data_type = $params['form_data']['reason_dropping'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $dev_followups_data['reason_dropping'] = $data_types;
            } elseif ($params['form_data']['reason_dropping'] == NULL) {
                $dev_followups_data['other_reason_dropping'] = $params['form_data']['new_reason_dropping'];
            } elseif ($params['form_data']['reason_dropping'] != NULL && $params['form_data']['new_reason_dropping'] != NULL) {
                $data_type = $params['form_data']['reason_dropping'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $dev_followups_data['reason_dropping'] = $data_types;
                $dev_followups_data['other_reason_dropping'] = $params['form_data']['new_reason_dropping'];
            }

            if ($params['form_data']['new_confirm_services'] == NULL) {
                $data_type = $params['form_data']['confirm_services'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $dev_followups_data['confirm_services'] = $data_types;
            } elseif ($params['form_data']['confirm_services'] == NULL) {
                $dev_followups_data['confirm_services'] = $params['form_data']['new_confirm_services'];
            } elseif ($params['form_data']['confirm_services'] != NULL && $params['form_data']['new_confirm_services'] != NULL) {
                $data_type = $params['form_data']['confirm_services'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $dev_followups_data['confirm_services'] = $params['form_data']['new_confirm_services'] . ',' . $data_types;
            }

            $dev_followups_data['financial_service'] = $params['form_data']['followup_financial_service'];

            if ($params['form_data']['social_protection']) {
                $dev_followups_data['social_protection'] = $params['form_data']['social_protection'];
            }
            if ($params['form_data']['special_security']) {
                $dev_followups_data['special_security'] = $params['form_data']['special_security'];
            }

            $dev_followups_data['comment_psychosocial'] = $params['form_data']['comment_psychosocial'];
            $dev_followups_data['comment_economic'] = $params['form_data']['comment_economic'];
            $dev_followups_data['comment_social'] = $params['form_data']['comment_social'];

            $dev_followups_data['complete_income'] = $params['form_data']['complete_income'];
            $dev_followups_data['monthly_income'] = $params['form_data']['monthly_income'];
            $dev_followups_data['challenges'] = $params['form_data']['challenges'];
            $dev_followups_data['actions_taken'] = $params['form_data']['actions_taken'];
            $dev_followups_data['remark_participant'] = $params['form_data']['remark_participant'];
            $dev_followups_data['comment_brac'] = $params['form_data']['comment_brac'];
            $dev_followups_data['remark_district'] = $params['form_data']['remark_district'];

            if ($is_update) {
                $sql = "SELECT fk_misactivity_id FROM dev_followups WHERE fk_misactivity_id = '$is_update'";
                $pre_misactivity_id = $devdb->get_row($sql);

                if ($pre_misactivity_id) {
                    $ret['followup_update'] = $devdb->insert_update('dev_followups', $dev_followups_data, " fk_misactivity_id = '" . $is_update . "'");
                } else {
                    $dev_followups_data['fk_misactivity_id'] = $is_update;
                    $ret['followup_insert'] = $devdb->insert_update('dev_followups', $dev_followups_data);
                }
            }
        }
        return $ret;
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
            'misactivity_id' => 'fk_misactivity_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_family_counselling($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_family_counselling(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid psychosocial followup id, no data found']);
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
            $psycho_family_counselling_data = array();
            $psycho_family_counselling_data['entry_date'] = date('Y-m-d', strtotime($params['form_data']['family_entry_date']));
            $psycho_family_counselling_data['entry_time'] = $params['form_data']['family_entry_time'];
            $psycho_family_counselling_data['session_place'] = $params['form_data']['session_place'];
            $psycho_family_counselling_data['members_counseled'] = $params['form_data']['members_counseled'];
            $psycho_family_counselling_data['session_comments'] = $params['form_data']['session_comments'];

            if ($is_update)
                $ret = $devdb->insert_update('dev_psycho_family_counselling', $psycho_family_counselling_data, " pk_psycho_family_counselling_id = '" . $is_update . "'");
            else {
                $psycho_family_counselling_data['fk_misactivity_id'] = $params['misactivity_id'];
                $ret = $devdb->insert_update('dev_psycho_family_counselling', $psycho_family_counselling_data);
            }
        }
        return $ret;
    }

    function get_psychosocial_session($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_psycho_sessions ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_psycho_session_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_psycho_session_id',
            'misactivity_id' => 'fk_misactivity_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_psychosocial_session($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_psychosocial_session(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid psychosocial session id, no data found']);
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
            $psycho_sessions_data = array();
            $psycho_sessions_data['entry_time'] = $params['form_data']['session_entry_time'];
            $psycho_sessions_data['entry_date'] = date('Y-m-d', strtotime($params['form_data']['session_entry_date']));
            $psycho_sessions_data['activities_description'] = $params['form_data']['activities_description'];
            $psycho_sessions_data['session_comments'] = $params['form_data']['session_comments'];
            $psycho_sessions_data['next_date'] = date('Y-m-d', strtotime($params['form_data']['next_date']));

            if ($is_update)
                $ret = $devdb->insert_update('dev_psycho_sessions', $psycho_sessions_data, " pk_psycho_session_id = '" . $is_update . "'");
            else {
                $psycho_sessions_data['fk_misactivity_id'] = $params['misactivity_id'];
                $ret = $devdb->insert_update('dev_psycho_sessions', $psycho_sessions_data);
            }
        }
        return $ret;
    }

    function get_psychosocial_completion($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_psycho_completions ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_psycho_completion_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_psycho_completion_id',
            'misactivity_id' => 'fk_misactivity_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_psychosocial_completion($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_psychosocial_completion(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid psychosocial completion id, no data found']);
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
            $psycho_completions_data = array();
            $psycho_completions_data['entry_date'] = date('Y-m-d');
            $psycho_completions_data['is_completed'] = $params['form_data']['is_completed'];
            $psycho_completions_data['dropout_reason'] = $params['form_data']['dropout_reason'];
            $psycho_completions_data['review_session'] = $params['form_data']['review_session'];
            $psycho_completions_data['client_comments'] = $params['form_data']['client_comments'];
            $psycho_completions_data['counsellor_comments'] = $params['form_data']['counsellor_comments'];
            $psycho_completions_data['final_evaluation'] = $params['form_data']['final_evaluation'];
            $psycho_completions_data['required_session'] = $params['form_data']['required_session'];

            if ($is_update)
                $ret = $devdb->insert_update('dev_psycho_completions', $psycho_completions_data, " pk_psycho_completion_id = '" . $is_update . "'");
            else {
                $psycho_completions_data['fk_misactivity_id'] = $params['misactivity_id'];
                $ret = $devdb->insert_update('dev_psycho_completions', $psycho_completions_data);
            }
        }
        return $ret;
    }

    function get_psychosocial_followup($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_psycho_followups ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_psycho_followup_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_psycho_followup_id',
            'misactivity_id' => 'fk_misactivity_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $supports = sql_data_collector($sql, $count_sql, $param);
        return $supports;
    }

    function add_edit_psychosocial_followup($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_psychosocial_followup(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid psychosocial followup id, no data found']);
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
            $psycho_followups_data = array();
            $psycho_followups_data['entry_time'] = $params['form_data']['followup_entry_time'];
            $psycho_followups_data['entry_date'] = date('Y-m-d', strtotime($params['form_data']['followup_entry_date']));
            $psycho_followups_data['followup_comments'] = $params['form_data']['followup_comments'];

            if ($is_update)
                $ret = $devdb->insert_update('dev_psycho_followups', $psycho_followups_data, " pk_psycho_followup_id = '" . $is_update . "'");
            else {
                $psycho_followups_data['fk_misactivity_id'] = $params['misactivity_id'];
                $ret = $devdb->insert_update('dev_psycho_followups', $psycho_followups_data);
            }
        }
        return $ret;
    }

}

new dev_misactivity_management();
