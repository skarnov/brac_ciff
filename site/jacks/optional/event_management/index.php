<?php

class dev_event_management {

    var $thsClass = 'dev_event_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Events',
            'permissions' => array(
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
                'manage_event_types' => array(
                    'add_event_type' => 'Add Event Type',
                    'edit_event_type' => 'Edit Event Type',
                    'delete_event_type' => 'Delete Event Type',
                ),
                'manage_events' => array(
                    'add_event' => 'Add Event',
                    'edit_event' => 'Edit Event',
                    'delete_event' => 'Delete Event',
                ),
                'manage_event_validations' => array(
                    'add_event_validation' => 'Add Event Validation',
                    'edit_event_validation' => 'Edit Event Validation',
                    'delete_event_validation' => 'Delete Event Validation',
                ),
                'manage_complains' => array(
                    'add_complain' => 'Add Complain',
                    'edit_complain' => 'Edit Complain',
                    'delete_complain' => 'Delete Complain',
                ),
                'manage_complain_fileds' => array(
                    'add_complain_filed' => 'Add Complain Filed',
                    'edit_complain_filed' => 'Edit Complain Filed',
                    'delete_complain_filed' => 'Delete Complain Filed',
                ),
                'manage_complain_investigations' => array(
                    'add_complain_investigation' => 'Add Complain Investigation',
                    'edit_complain_investigation' => 'Edit Complain Investigation',
                    'delete_complain_investigation' => 'Delete Complain Investigation',
                ),
                'manage_trainings' => array(
                    'add_training' => 'Add Training',
                    'edit_training' => 'Edit Training',
                    'delete_training' => 'Delete Training',
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
            'label' => 'Events',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Activity',
            'description' => 'Manage All Activities',
            'menu_group' => 'Events',
            'position' => 'default',
            'action' => 'manage_targets',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_targets'))
            admenu_register($params);

        $params = array(
            'label' => 'Event Types',
            'description' => 'Manage All Event Types',
            'menu_group' => 'Events',
            'position' => 'default',
            'action' => 'manage_event_types',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_event_types'))
            admenu_register($params);

        $params = array(
            'label' => 'Event',
            'description' => 'Manage All Event',
            'menu_group' => 'Events',
            'position' => 'default',
            'action' => 'manage_events',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_events'))
            admenu_register($params);

        $params = array(
            'label' => 'Sharing Session',
            'description' => 'Manage All Sharing Session',
            'menu_group' => 'Events',
            'position' => 'default',
            'action' => 'manage_sharing_session',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_sharing_session'))
            admenu_register($params);

        $params = array(
            'label' => 'Complains',
            'description' => 'Manage All Complains',
            'menu_group' => 'Events',
            'position' => 'default',
            'action' => 'manage_complains',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_complains'))
            admenu_register($params);

        $params = array(
            'label' => 'Complain Fileds',
            'description' => 'Manage All Complain Fileds',
            'menu_group' => 'Events',
            'position' => 'default',
            'action' => 'manage_complain_fileds',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_complain_fileds'))
            admenu_register($params);

        $params = array(
            'label' => 'Complain Investigations',
            'description' => 'Manage All Complain Investigations',
            'menu_group' => 'Events',
            'position' => 'default',
            'action' => 'manage_complain_investigations',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_complain_investigations'))
            admenu_register($params);

        $params = array(
            'label' => 'Trainings',
            'description' => 'Manage All Trainings',
            'menu_group' => 'Events',
            'position' => 'default',
            'action' => 'manage_trainings',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_trainings'))
            admenu_register($params);
    }

    function manage_targets() {
        if (!has_permission('manage_targets'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_targets');

        if ($_GET['action'] == 'add_edit_target')
            include('pages/add_edit_target.php');
        elseif ($_GET['action'] == 'deleteTarget')
            include('pages/deleteTarget.php');
        elseif ($_GET['action'] == 'add_edit_achievement')
            include('pages/add_edit_achievement.php');
        else
            include('pages/list_targets.php');
    }

    function manage_event_types() {
        if (!has_permission('manage_event_types'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_event_types');

        if ($_GET['action'] == 'add_edit_event_type')
            include('pages/add_edit_event_type.php');
        elseif ($_GET['action'] == 'deleteEventType')
            include('pages/deleteEventType.php');
        else
            include('pages/list_event_types.php');
    }

    function manage_events() {
        if (!has_permission('manage_events'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_events');

        if ($_GET['action'] == 'add_edit_event')
            include('pages/add_edit_event.php');
        elseif ($_GET['action'] == 'add_edit_event_validation')
            include('pages/add_edit_event_validation.php');
        elseif ($_GET['action'] == 'deleteEvent')
            include('pages/deleteEvent.php');
        else
            include('pages/list_events.php');
    }

    function manage_event_validations() {
        if (!has_permission('manage_event_validations'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_event_validations');

        if ($_GET['action'] == 'add_edit_event_validation')
            include('pages/add_edit_event_validation.php');
        elseif ($_GET['action'] == 'deleteEventValidation')
            include('pages/deleteEventValidation.php');
        else
            include('pages/list_event_validations.php');
    }

    function manage_sharing_session() {
        if (!has_permission('manage_sharing_session'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_sharing_session');

        if ($_GET['action'] == 'add_edit_sharing_session')
            include('pages/add_edit_sharing_session.php');
        elseif ($_GET['action'] == 'deleteSession')
            include('pages/deleteSession.php');
        else
            include('pages/list_sharing_sessions.php');
    }

    function manage_complains() {
        if (!has_permission('manage_complains'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_complains');

        if ($_GET['action'] == 'add_edit_complain')
            include('pages/add_edit_complain.php');
        elseif ($_GET['action'] == 'deleteComplain')
            include('pages/deleteComplain.php');
        else
            include('pages/list_complains.php');
    }

    function manage_complain_fileds() {
        if (!has_permission('manage_complain_fileds'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_complain_fileds');

        if ($_GET['action'] == 'add_edit_complain_filed')
            include('pages/add_edit_complain_filed.php');
        elseif ($_GET['action'] == 'deleteComplainInvestigation')
            include('pages/deleteComplainInvestigation.php');
        else
            include('pages/list_complain_fileds.php');
    }

    function manage_complain_investigations() {
        if (!has_permission('manage_complain_investigations'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_complain_investigations');

        if ($_GET['action'] == 'add_edit_complain_investigation')
            include('pages/add_edit_complain_investigation.php');
        elseif ($_GET['action'] == 'deleteComplainInvestigation')
            include('pages/deleteComplainInvestigation.php');
        else
            include('pages/list_complain_investigations.php');
    }

    function manage_trainings() {
        if (!has_permission('manage_trainings'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_trainings');

        if ($_GET['action'] == 'add_edit_training')
            include('pages/add_edit_training.php');
        elseif ($_GET['action'] == 'deleteTraining')
            include('pages/deleteTraining.php');
        else
            include('pages/list_trainings.php');
    }

    function get_targets($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']) {
            $from = "FROM dev_targets 

            ";
        } else {
            $from = "FROM dev_targets 

            ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_targets.pk_target_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $targets = sql_data_collector($sql, $count_sql, $param);
        return $targets;
    }

    function add_edit_target($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_targets(array('id' => $edit, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid target id, no data found']);
            }
        }

        if (!$ret['error']) {
            $data = array();
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');

            $data['fk_branch_id'] = $_config['user']['user_branch'];
            $data['target_month'] = $params['form_data']['month'];
            $data['target_name'] = $params['form_data']['target_name'];
            $data['target_value'] = $params['form_data']['target_value'];
            $data['achievement_value'] = $params['form_data']['achievement_value'];
            $data['remark'] = $params['form_data']['remark'];

            if ($is_update) {
                $data['update_date'] = $dateNow;
                $data['update_time'] = $timeNow;
                $data['modified_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_targets', $data, " pk_target_id  = '" . $is_update . "'");
            } else {
                $data['create_date'] = $dateNow;
                $data['create_time'] = $timeNow;
                $data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_targets', $data);
            }
        }
        return $ret;
    }

    function get_achievements($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']) {
            $from = "FROM dev_targets 

            ";
        } else {
            $from = "FROM dev_targets 

            ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_targets.pk_target_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_targets.pk_target_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $targets = sql_data_collector($sql, $count_sql, $param);
        return $targets;
    }

    function get_event_types($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']) {
            $from = "FROM dev_event_types 

            ";
        } else {
            $from = "FROM dev_event_types 

            ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_event_types.pk_event_type_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_event_types.pk_event_type_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $targets = sql_data_collector($sql, $count_sql, $param);
        return $targets;
    }

    function add_edit_event_type($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_event_types(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid event type id, no data found']);
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
            $data['event_type'] = $params['form_data']['event_type'];
            if ($is_update) {
                $data['update_date'] = date('Y-m-d');
                $data['update_time'] = date('H:i:s');
                $data['modified_by'] = $_config['user']['pk_user_id'];
                $ret['event_types_update'] = $devdb->insert_update('dev_event_types', $data, " pk_event_type_id  = '" . $is_update . "'");
            } else {
                $data['create_date'] = date('Y-m-d');
                $data['create_time'] = date('H:i:s');
                $data['created_by'] = $_config['user']['pk_user_id'];
                $ret['event_types_insert'] = $devdb->insert_update('dev_event_types', $data);
            }
        }
        return $ret;
    }

    function get_events($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_events 
                LEFT JOIN dev_users ON (dev_users.pk_user_id = dev_events.created_by)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_events.pk_event_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_events.pk_event_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $targets = sql_data_collector($sql, $count_sql, $param);
        return $targets;
    }

    function add_edit_event($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_events(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid event id, no data found']);
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
            $events_data = array();
            $events_data['event_type'] = $params['form_data']['event_type'];
            $events_data['event_branch'] = $_config['user']['user_branch'];
            $events_data['event_start_date'] = date('Y-m-d', strtotime($params['form_data']['event_start_date']));
            $events_data['event_start_time'] = $params['form_data']['event_start_time'];
            $events_data['event_end_date'] = date('Y-m-d', strtotime($params['form_data']['event_end_date']));
            $events_data['event_end_time'] = $params['form_data']['event_end_time'];
            $events_data['division'] = $params['form_data']['division'];
            $events_data['district'] = $params['form_data']['district'];
            $events_data['upazila'] = $params['form_data']['upazila'];
            $events_data['event_union'] = $params['form_data']['event_union'];
            $events_data['village'] = $params['form_data']['village'];
            $events_data['ward'] = $params['form_data']['ward'];
            $events_data['location'] = $params['form_data']['location'];
            $events_data['below_male'] = $params['form_data']['below_male'];
            $events_data['below_female'] = $params['form_data']['below_female'];
            $events_data['above_male'] = $params['form_data']['above_male'];
            $events_data['above_female'] = $params['form_data']['above_female'];
            $events_data['preparatory_work'] = $params['form_data']['preparatory_work'];
            $events_data['time_management'] = $params['form_data']['time_management'];
            $events_data['participants_attention'] = $params['form_data']['participants_attention'];
            $events_data['logistical_arrangements'] = $params['form_data']['logistical_arrangements'];
            $events_data['relevancy_delivery'] = $params['form_data']['relevancy_delivery'];
            $events_data['participants_feedback'] = $params['form_data']['participants_feedback'];
            $events_data['observation_score'] = $events_data['preparatory_work'] + events_data['time_management'] + $events_data['participants_attention'] + $events_data['logistical_arrangements'] + $events_data['relevancy_delivery'] + $events_data['participants_feedback'];
            $events_data['note'] = $params['form_data']['note'];
            if ($is_update) {
                $events_data['update_date'] = date('Y-m-d');
                $events_data['update_time'] = date('H:i:s');
                $events_data['modified_by'] = $_config['user']['pk_user_id'];
                $ret['events_update'] = $devdb->insert_update('dev_events', $events_data, " pk_event_id  = '" . $is_update . "'");
            } else {
                $events_data['create_date'] = date('Y-m-d');
                $events_data['create_time'] = date('H:i:s');
                $events_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['events_insert'] = $devdb->insert_update('dev_events', $events_data);
            }
        }
        return $ret;
    }

    function get_event_validations($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']) {
            $from = "FROM dev_event_validations 

            ";
        } else {
            $from = "FROM dev_event_validations 

            ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_event_validations.pk_validation_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_event_validations.pk_validation_id',
            'event_id' => 'dev_event_validations.fk_event_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $targets = sql_data_collector($sql, $count_sql, $param);
        return $targets;
    }

    function add_edit_event_validation($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_event_validations(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid event validation id, no data found']);
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
            $data['fk_event_id'] = $params['event_id'];
            $data['interview_date'] = date('Y-m-d', strtotime($params['form_data']['interview_date']));
            $data['interview_time'] = $params['form_data']['interview_time'];
            $data['reviewed_by'] = $params['form_data']['reviewed_by'];
            $data['beneficiary_id'] = $params['form_data']['beneficiary_id'];
            $data['participant_name'] = $params['form_data']['participant_name'];
            if ($params['form_data']['new_gender']) {
                $data['gender'] = $params['form_data']['new_gender'];
            } else {
                $data['gender'] = $params['form_data']['gender'];
            }
            $data['age'] = $params['form_data']['age'];
            $data['mobile'] = $params['form_data']['mobile'];
            $data['enjoyment'] = $params['form_data']['enjoyment'];
            $data['victim'] = $params['form_data']['victim'];
            $data['victim_family'] = $params['form_data']['victim_family'];

            if ($params['form_data']['new_message'] == NULL) {
                $data_type = $params['form_data']['message'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $data['message'] = $data_types;
            } elseif ($params['form_data']['message'] == NULL) {
                $data['other_message'] = $params['form_data']['new_message'];
            } elseif ($params['form_data']['message'] != NULL && $params['form_data']['new_message'] != NULL) {
                $data_type = $params['form_data']['message'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $data['message'] = $data_types;
                $data['other_message'] = $params['form_data']['new_message'];
            }

            $data['use_message'] = $params['form_data']['use_message'];
            $data['mentioned_event'] = $params['form_data']['mentioned_event'];
            $data['additional_comments'] = $params['form_data']['additional_comments'];
            $data['quote'] = $params['form_data']['quote'];

            if ($is_update) {
                $data['update_date'] = date('Y-m-d');
                $data['update_time'] = date('H:i:s');
                $data['modified_by'] = $_config['user']['pk_user_id'];
                $ret['event_validations_update'] = $devdb->insert_update('dev_event_validations', $data, " pk_validation_id  = '" . $is_update . "'");
            } else {
                $data['create_date'] = date('Y-m-d');
                $data['create_time'] = date('H:i:s');
                $data['created_by'] = $_config['user']['pk_user_id'];
                $ret['event_validations_insert'] = $devdb->insert_update('dev_event_validations', $data);
            }
        }
        return $ret;
    }

    function get_sharing_sessions($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']) {
            $from = "FROM dev_sharing_sessions 

            ";
        } else {
            $from = "FROM dev_sharing_sessions 

            ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_sharing_sessions.pk_sharing_session_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_sharing_sessions.pk_sharing_session_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $targets = sql_data_collector($sql, $count_sql, $param);
        return $targets;
    }

    function add_edit_sharing_session($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_sharing_sessions(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid sharing session id, no data found']);
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
            $sharing_session_data = array();
            $sharing_session_data['traning_date'] = date('Y-m-d', strtotime($params['form_data']['traning_date']));
            $sharing_session_data['traning_name'] = $params['form_data']['traning_name'];

            if ($params['form_data']['new_evaluator_profession']) {
                $sharing_session_data['evaluator_profession'] = $params['form_data']['new_evaluator_profession'];
            } else {
                $sharing_session_data['evaluator_profession'] = $params['form_data']['evaluator_profession'];
            }

            $sharing_session_data['satisfied_training'] = $params['form_data']['satisfied_training'];
            $sharing_session_data['satisfied_supports'] = $params['form_data']['satisfied_supports'];
            $sharing_session_data['satisfied_facilitation'] = $params['form_data']['satisfied_facilitation'];
            $sharing_session_data['outcome_training'] = $params['form_data']['outcome_training'];
            $sharing_session_data['trafficking_law'] = $params['form_data']['trafficking_law'];
            $sharing_session_data['policy_process'] = $params['form_data']['policy_process'];
            $sharing_session_data['all_contents'] = $params['form_data']['all_contents'];
            $sharing_session_data['recommendation'] = $params['form_data']['recommendation'];
            if ($is_update) {
                $sharing_session_data['update_date'] = date('Y-m-d');
                $sharing_session_data['update_time'] = date('H:i:s');
                $sharing_session_data['modified_by'] = $_config['user']['pk_user_id'];
                $ret['update'] = $devdb->insert_update('dev_sharing_sessions', $sharing_session_data, " pk_sharing_session_id  = '" . $is_update . "'");
            } else {
                $sharing_session_data['create_date'] = date('Y-m-d');
                $sharing_session_data['create_time'] = date('H:i:s');
                $sharing_session_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['insert'] = $devdb->insert_update('dev_sharing_sessions', $sharing_session_data);
            }
        }
        return $ret;
    }

    function get_complains($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']) {
            $from = "FROM dev_complains 

            ";
        } else {
            $from = "FROM dev_complains

            ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_complains.pk_complain_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_complains.pk_complain_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $targets = sql_data_collector($sql, $count_sql, $param);
        return $targets;
    }

    function add_edit_complain($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_complains(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid complain id, no data found']);
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
            $complains_data = array();
            $complains_data['fk_branch_id'] = $_config['user']['user_branch'];
            $complains_data['name'] = $params['form_data']['name'];
            $complains_data['type_recipient'] = $params['form_data']['type_recipient'];
            $complains_data['type_service'] = $params['form_data']['type_service'];
            $complains_data['know_service'] = $params['form_data']['know_service'];
            $complains_data['complain_register_date'] = date('Y-m-d', strtotime($params['form_data']['complain_register_date']));
            $complains_data['age'] = $params['form_data']['age'];
            if ($params['form_data']['new_gender']) {
                $complains_data['gender'] = $params['form_data']['new_gender'];
            } else {
                $complains_data['gender'] = $params['form_data']['gender'];
            }
            $complains_data['address'] = $params['form_data']['address'];
            $complains_data['remark'] = $params['form_data']['remark'];

            if ($is_update) {
                $complains_data['update_date'] = date('Y-m-d');
                $complains_data['update_time'] = date('H:i:s');
                $complains_data['modified_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_complains', $complains_data, " pk_complain_id  = '" . $is_update . "'");
            } else {
                $complains_data['create_date'] = date('Y-m-d');
                $complains_data['create_time'] = date('H:i:s');
                $complains_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_complains', $complains_data);
            }
        }
        return $ret;
    }

    function get_complain_fileds($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']) {
            $from = "FROM dev_complain_fileds

            ";
        } else {
            $from = "FROM dev_complain_fileds

            ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_complain_fileds.pk_complain_filed_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_complain_fileds.pk_complain_filed_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);
        return $results;
    }

    function add_edit_complain_filed($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_complain_fileds(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid complain fileds id, no data found']);
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
            $complain_filed_data = array();
            $complain_filed_data['complain_register_date'] = date('Y-m-d', strtotime($params['form_data']['complain_register_date']));
            $complain_filed_data['month'] = $params['form_data']['month'];
            $complain_filed_data['division'] = $params['form_data']['division'];
            $complain_filed_data['district'] = $params['form_data']['district'];
            $complain_filed_data['upazila'] = $params['form_data']['upazila'];
            $complain_filed_data['police_station'] = $params['form_data']['police_station'];
            $complain_filed_data['case_id'] = $params['form_data']['case_id'];
            $complain_filed_data['age'] = $params['form_data']['age'];
            if ($params['form_data']['new_gender']) {
                $complain_filed_data['gender'] = $params['form_data']['new_gender'];
            } else {
                $complain_filed_data['gender'] = $params['form_data']['gender'];
            }

            if ($params['form_data']['new_type_case']) {
                $complain_filed_data['type_case'] = $params['form_data']['new_type_case'];
            } else {
                $complain_filed_data['type_case'] = $params['form_data']['type_case'];
            }
            $complain_filed_data['comments'] = $params['form_data']['comments'];

            if ($is_update) {
                $complain_filed_data['update_date'] = date('Y-m-d');
                $complain_filed_data['update_time'] = date('H:i:s');
                $complain_filed_data['modified_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_complain_fileds', $complain_filed_data, " pk_complain_filed_id  = '" . $is_update . "'");
            } else {
                $complain_filed_data['create_date'] = date('Y-m-d');
                $complain_filed_data['create_time'] = date('H:i:s');
                $complain_filed_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_complain_fileds', $complain_filed_data);
            }
        }
        return $ret;
    }

    function get_complain_investigations($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']) {
            $from = "FROM dev_complain_investigations

            ";
        } else {
            $from = "FROM dev_complain_investigations 

            ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_complain_investigations.pk_complain_investigation_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_complain_investigations.pk_complain_investigation_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);
        return $results;
    }

    function add_edit_complain_investigation($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_complain_investigations(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid complain investigation id, no data found']);
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
            $complain_investigation_data = array();
            $complain_investigation_data['complain_register_date'] = date('Y-m-d', strtotime($params['form_data']['complain_register_date']));
            $complain_investigation_data['month'] = $params['form_data']['month'];
            $complain_investigation_data['division'] = $params['form_data']['division'];
            $complain_investigation_data['district'] = $params['form_data']['district'];
            $complain_investigation_data['upazila'] = $params['form_data']['upazila'];
            $complain_investigation_data['police_station'] = $params['form_data']['police_station'];
            $complain_investigation_data['case_id'] = $params['form_data']['case_id'];
            $complain_investigation_data['age'] = $params['form_data']['age'];
            if ($params['form_data']['new_gender']) {
                $complain_investigation_data['gender'] = $params['form_data']['new_gender'];
            } else {
                $complain_investigation_data['gender'] = $params['form_data']['gender'];
            }

            if ($params['form_data']['new_type_case']) {
                $complain_investigation_data['type_case'] = $params['form_data']['new_type_case'];
            } else {
                $complain_investigation_data['type_case'] = $params['form_data']['type_case'];
            }
            $complain_investigation_data['comments'] = $params['form_data']['comments'];

            if ($is_update) {
                $complain_investigation_data['update_date'] = date('Y-m-d');
                $complain_investigation_data['update_time'] = date('H:i:s');
                $complain_investigation_data['modified_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_complain_investigations', $complain_investigation_data, " pk_complain_investigation_id  = '" . $is_update . "'");
            } else {
                $complain_investigation_data['create_date'] = date('Y-m-d');
                $complain_investigation_data['create_time'] = date('H:i:s');
                $complain_investigation_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_complain_investigations', $complain_investigation_data);
            }
        }
        return $ret;
    }

    function get_trainings($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']) {
            $from = "FROM dev_trainings

            ";
        } else {
            $from = "FROM dev_trainings 

            ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_trainings.pk_training_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_trainings.pk_training_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);
        return $results;
    }

    function add_edit_training($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_complains(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid training id, no data found']);
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
            $training_data = array();
            $training_data['beneficiary_id'] = $params['form_data']['beneficiary_id'];
            $training_data['name'] = $params['form_data']['name'];
            if ($params['form_data']['new_gender']) {
                $training_data['gender'] = $params['form_data']['new_gender'];
            } else {
                $training_data['gender'] = $params['form_data']['gender'];
            }
            $training_data['profession'] = $params['form_data']['profession'];
            $training_data['training_name'] = $params['form_data']['training_name'];
            $training_data['address'] = $params['form_data']['address'];
            $training_data['mobile'] = $params['form_data']['mobile'];
            $training_data['age'] = $params['form_data']['age'];

            if ($is_update) {
                $training_data['update_date'] = date('Y-m-d');
                $training_data['update_time'] = date('H:i:s');
                $training_data['modified_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_trainings', $training_data, " pk_training_id   = '" . $is_update . "'");
            } else {
                $training_data['create_date'] = date('Y-m-d');
                $training_data['create_time'] = date('H:i:s');
                $training_data['created_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_trainings', $training_data);
            }
        }
        return $ret;
    }

}

new dev_event_management();
