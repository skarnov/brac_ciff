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
                'manage_events' => array(
                    'add_event' => 'Add Event',
                    'edit_event' => 'Edit Event',
                    'delete_event' => 'Delete Event',
                ),
                'manage_sharing_session' => array(
                    'add_sharing_session' => 'Add Training/Workshop Validation',
                    'edit_sharing_session' => 'Edit Training/Workshop Validation',
                    'delete_sharing_session' => 'Delete Training/Workshop Validation',
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
            'label' => 'Activities',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );

        $params = array(
            'label' => 'Events',
            'description' => 'Manage All Event',
            'menu_group' => 'Activities',
            'position' => 'default',
            'action' => 'manage_events',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_events'))
            admenu_register($params);

        $params = array(
            'label' => 'Community Service',
            'description' => 'Manage All Community Services',
            'menu_group' => 'Activities',
            'position' => 'default',
            'action' => 'manage_complains',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_complains'))
            admenu_register($params);

        $params = array(
            'label' => 'Complain Files',
            'description' => 'Manage All Complain Fileds',
            'menu_group' => 'Activities',
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
            'menu_group' => 'Activities',
            'position' => 'default',
            'action' => 'manage_complain_investigations',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_complain_investigations'))
            admenu_register($params);

        $params = array(
            'label' => 'Training/Workshop',
            'description' => 'Manage All Training/Workshop',
            'menu_group' => 'Activities',
            'position' => 'default',
            'action' => 'manage_trainings',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_trainings'))
            admenu_register($params);
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

        if ($_GET['action'] == 'add_edit_training_validation')
            include('pages/add_edit_training_validation.php');
        elseif ($_GET['action'] == 'deleteTrainingValidation')
            include('pages/deleteTrainingValidation.php');
        else
            include('pages/list_training_validations.php');
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

    function get_events($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_events 
                LEFT JOIN dev_activities ON (dev_activities.pk_activity_id = dev_events.fk_activity_id)
                LEFT JOIN dev_users ON (dev_users.pk_user_id = dev_events.created_by)
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_events.pk_event_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_events.pk_event_id',
            'name' => 'dev_activities.activity_name',
            'branch_id' => 'dev_events.fk_branch_id',
            'division' => 'dev_events.event_division',
            'district' => 'dev_events.event_district',
            'sub_district' => 'dev_events.event_upazila',
            'union' => 'dev_events.event_union',
            'create_date' => 'dev_events.create_date',
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
            $events_data['fk_branch_id'] = $params['form_data']['branch_id'];
            $events_data['fk_project_id'] = $params['form_data']['project_id'];
            $events_data['month'] = $params['form_data']['month'];
            $events_data['fk_activity_id'] = $params['form_data']['activity_id'];
            $events_data['event_start_date'] = date('Y-m-d', strtotime($params['form_data']['event_start_date']));
            $events_data['event_start_time'] = $params['form_data']['event_start_time'];
            $events_data['event_end_date'] = date('Y-m-d', strtotime($params['form_data']['event_end_date']));
            $events_data['event_end_time'] = $params['form_data']['event_end_time'];
            $events_data['event_division'] = $params['form_data']['event_division'];
            $events_data['event_district'] = $params['form_data']['event_district'];
            $events_data['event_upazila'] = $params['form_data']['event_upazila'];
            $events_data['event_union'] = $params['form_data']['event_union'];
            $events_data['event_village'] = $params['form_data']['event_village'];
            $events_data['event_ward'] = $params['form_data']['event_ward'];
            $events_data['event_location'] = $params['form_data']['event_location'];
            $events_data['participant_boy'] = $params['form_data']['participant_boy'];
            $events_data['participant_girl'] = $params['form_data']['participant_girl'];
            $events_data['participant_male'] = $params['form_data']['participant_male'];
            $events_data['participant_female'] = $params['form_data']['participant_female'];
            $events_data['preparatory_work'] = $params['form_data']['preparatory_work'];
            $events_data['time_management'] = $params['form_data']['time_management'];
            $events_data['participants_attention'] = $params['form_data']['participants_attention'];
            $events_data['logistical_arrangements'] = $params['form_data']['logistical_arrangements'];
            $events_data['relevancy_delivery'] = $params['form_data']['relevancy_delivery'];
            $events_data['participants_feedback'] = $params['form_data']['participants_feedback'];
            $events_data['observation_score'] = $events_data['preparatory_work'] + $events_data['time_management'] + $events_data['participants_attention'] + $events_data['logistical_arrangements'] + $events_data['relevancy_delivery'] + $events_data['participants_feedback'];
            $events_data['event_note'] = $params['form_data']['event_note'];
            if ($is_update) {
                $events_data['update_date'] = date('Y-m-d');
                $events_data['update_time'] = date('H:i:s');
                $events_data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['events_update'] = $devdb->insert_update('dev_events', $events_data, " pk_event_id  = '" . $is_update . "'");

                $achievement_male = $params['form_data']['participant_male'];
                $achievement_female = $params['form_data']['participant_female'];
                $achievement_boy = $params['form_data']['participant_boy'];
                $achievement_girl = $params['form_data']['participant_girl'];
                $achievement_total = $achievement_male + $achievement_female + $achievement_boy + $achievement_girl;
                $update_date = date('Y-m-d');
                $update_time = date('H:i:s');
                $updated_by = $_config['user']['pk_user_id'];

                $sql = "UPDATE dev_targets SET achievement_male = '$achievement_male', achievement_female = '$achievement_female', achievement_boy = '$achievement_boy', achievement_girl = '$achievement_girl', achievement_total = '$achievement_total', update_date = '$update_date', update_time = '$update_time', updated_by = '$updated_by' WHERE fk_activity_id = '" . $events_data['fk_activity_id'] . "' AND fk_branch_id = '" . $events_data['fk_branch_id'] . "' AND fk_project_id = '" . $events_data['fk_project_id'] . "' AND month = '" . $events_data['month'] . "'";
                $ret['misactivity_update'] = $devdb->query($sql);
            } else {
                $events_data['create_date'] = date('Y-m-d');
                $events_data['create_time'] = date('H:i:s');
                $events_data['created_by'] = $_config['user']['pk_user_id'];
                $ret['events_insert'] = $devdb->insert_update('dev_events', $events_data);

                $achievement_male = $params['form_data']['participant_male'];
                $achievement_female = $params['form_data']['participant_female'];
                $achievement_boy = $params['form_data']['participant_boy'];
                $achievement_girl = $params['form_data']['participant_girl'];
                $achievement_total = $achievement_male + $achievement_female + $achievement_boy + $achievement_girl;
                $update_date = date('Y-m-d');
                $update_time = date('H:i:s');
                $updated_by = $_config['user']['pk_user_id'];

                $sql = "UPDATE dev_targets SET achievement_male = '$achievement_male', achievement_female = '$achievement_female', achievement_boy = '$achievement_boy', achievement_girl = '$achievement_girl', achievement_total = '$achievement_total', activity_achievement = activity_achievement + 1, update_date = '$update_date', update_time = '$update_time', updated_by = '$updated_by' WHERE fk_activity_id = '" . $events_data['fk_activity_id'] . "' AND fk_branch_id = '" . $events_data['fk_branch_id'] . "' AND fk_project_id = '" . $events_data['fk_project_id'] . "' AND month = '" . $events_data['month'] . "'";
                $ret['misactivity_update'] = $devdb->query($sql);
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
                $data['updated_by'] = $_config['user']['pk_user_id'];
                $ret['event_validations_update'] = $devdb->insert_update('dev_event_validations', $data, " pk_validation_id  = '" . $is_update . "'");
            } else {
                $data['create_date'] = date('Y-m-d');
                $data['create_time'] = date('H:i:s');
                $data['created_by'] = $_config['user']['pk_user_id'];
                $ret['event_validations_insert'] = $devdb->insert_update('dev_event_validations', $data);

                $sql = "UPDATE dev_events SET validation_count = validation_count + 1";
                $devdb->query($sql);
            }
        }
        return $ret;
    }

    function get_sharing_sessions($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        $from = "FROM dev_sharing_sessions ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_sharing_sessions.pk_sharing_session_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_sharing_sessions.pk_sharing_session_id',
            'training_id' => 'dev_sharing_sessions.fk_training_id',
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
            $sharing_session_data['fk_training_id'] = $params['training_id'];

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
                $sharing_session_data['updated_by'] = $_config['user']['pk_user_id'];
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

        $from = "FROM dev_complains ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_complains.pk_complain_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_complains.pk_complain_id',
            'gender' => 'dev_complains.gender',
            'division' => 'dev_complains.division',
            'district' => 'dev_complains.branch_district',
            'sub_district' => 'dev_complains.upazila',
            'union' => 'dev_complains.branch_union',
            'type_recipient' => 'dev_complains.type_recipient',
            'type_service' => 'dev_complains.type_service',
            'complain_register_date' => 'dev_complains.complain_register_date'
        );

        if ($param['type_service']) {
            $conditions .= " AND type_service LIKE '%" . $param['type_service'] . "%'";
        }

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
            $complains_data['complain_register_date'] = date('Y-m-d', strtotime($params['form_data']['complain_register_date']));
            $complains_data['fk_branch_id'] = $params['form_data']['branch_id'];
            $complains_data['branch_district'] = $params['branch_district'];
            $complains_data['branch_sub_district'] = $params['branch_sub_district'];
            $complains_data['upazila'] = $params['form_data']['upazila'];
            $complains_data['branch_union'] = $params['form_data']['union'];
            $complains_data['village'] = $params['form_data']['village'];

            $complains_data['name'] = $params['form_data']['name'];
            $complains_data['age'] = $params['form_data']['age'];
            if ($params['form_data']['new_gender']) {
                $complains_data['gender'] = $params['form_data']['new_gender'];
            } else {
                $complains_data['gender'] = $params['form_data']['gender'];
            }
            $complains_data['type_recipient'] = $params['form_data']['type_recipient'];

            if ($params['form_data']['new_type_service'] == NULL) {
                $data_type = $params['form_data']['type_service'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $complains_data['type_service'] = $data_types;
            } elseif ($params['form_data']['type_service'] == NULL) {
                $complains_data['other_type_service'] = $params['form_data']['new_type_service'];
            } elseif ($params['form_data']['type_service'] != NULL && $params['form_data']['new_type_service'] != NULL) {
                $data_type = $params['form_data']['type_service'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $complains_data['type_service'] = $data_types;
                $complains_data['other_type_service'] = $params['form_data']['new_type_service'];
            }

            if ($params['form_data']['new_know_service'] == NULL) {
                $data_type = $params['form_data']['know_service'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $complains_data['know_service'] = $data_types;
            } elseif ($params['form_data']['know_service'] == NULL) {
                $complains_data['other_know_service'] = $params['form_data']['new_know_service'];
            } elseif ($params['form_data']['know_service'] != NULL && $params['form_data']['new_know_service'] != NULL) {
                $data_type = $params['form_data']['know_service'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $complains_data['know_service'] = $data_types;
                $complains_data['other_know_service'] = $params['form_data']['new_know_service'];
            }

            $complains_data['remark'] = $params['form_data']['remark'];

            if ($is_update) {
                $complains_data['update_date'] = date('Y-m-d');
                $complains_data['update_time'] = date('H:i:s');
                $complains_data['updated_by'] = $_config['user']['pk_user_id'];
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

        $from = "FROM dev_complain_fileds ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_complain_fileds.pk_complain_filed_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_complain_fileds.pk_complain_filed_id',
            'gender' => 'dev_complain_fileds.gender',
            'type_case' => 'dev_complain_fileds.type_case',
            'division' => 'dev_complain_fileds.division',
            'district' => 'dev_complain_fileds.district',
            'sub_district' => 'dev_complain_fileds.upazila',
            'complain_register_date' => 'dev_complain_fileds.complain_register_date'
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
                return array('error' => ['Invalid complain file id, no data found']);
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
            $complain_filed_data['full_name'] = $params['form_data']['full_name'];
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
                $complain_filed_data['updated_by'] = $_config['user']['pk_user_id'];
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

        $from = "FROM dev_complain_investigations 

            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_complain_investigations.pk_complain_investigation_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_complain_investigations.pk_complain_investigation_id',
            'gender' => 'dev_complain_investigations.gender',
            'type_case' => 'dev_complain_investigations.type_case',
            'upazila' => 'dev_complain_investigations.upazila',
            'entry_date' => 'dev_complain_investigations.complain_register_date'
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
            $complain_investigation_data['running_investigation'] = $params['form_data']['running_investigation'];
            $complain_investigation_data['full_name'] = $params['form_data']['full_name'];
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
                $complain_investigation_data['updated_by'] = $_config['user']['pk_user_id'];
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

        $from = "FROM dev_trainings 
            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_trainings.pk_training_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_trainings.pk_training_id',
            'profession' => 'dev_trainings.profession',
            'training_name' => 'dev_trainings.training_name',
            'date' => 'dev_trainings.date',
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
            $training_data['date'] = date('Y-m-d', strtotime($params['form_data']['date']));
            $training_data['beneficiary_id'] = $params['form_data']['beneficiary_id'];
            $training_data['name'] = $params['form_data']['name'];
            if ($params['form_data']['new_gender']) {
                $training_data['gender'] = $params['form_data']['new_gender'];
            } else {
                $training_data['gender'] = $params['form_data']['gender'];
            }
            $training_data['profession'] = $params['form_data']['profession'];
            $training_data['training_name'] = $params['form_data']['training_name'];
            $training_data['workshop_name'] = $params['form_data']['workshop_name'];
            $training_data['workshop_duration'] = $params['form_data']['workshop_duration'];
            $training_data['training_duration'] = $params['form_data']['training_duration'];
            $training_data['address'] = $params['form_data']['address'];
            $training_data['mobile'] = $params['form_data']['mobile'];
            $training_data['age'] = $params['form_data']['age'];

            if ($is_update) {
                $training_data['update_date'] = date('Y-m-d');
                $training_data['update_time'] = date('H:i:s');
                $training_data['updated_by'] = $_config['user']['pk_user_id'];
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
