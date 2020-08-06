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
            'label' => 'Targets',
            'description' => 'Manage All Targets',
            'menu_group' => 'Events',
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
            'menu_group' => 'Events',
            'position' => 'default',
            'action' => 'manage_achievements',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_achievements'))
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
        else
            include('pages/list_targets.php');
    }

    function manage_achievements() {
        if (!has_permission('manage_achievements'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_achievements');

        if ($_GET['action'] == 'add_edit_achievement')
            include('pages/add_edit_achievement.php');
        else
            include('pages/list_achievements.php');
    }

    function manage_event_types() {
        if (!has_permission('manage_event_types'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_event_types');

        if ($_GET['action'] == 'add_edit_event_type')
            include('pages/add_edit_event_type.php');
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
        else
            include('pages/list_events.php');
    }

    function manage_sharing_session() {
        if (!has_permission('manage_sharing_session'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_sharing_session');

        if ($_GET['action'] == 'add_edit_sharing_session')
            include('pages/add_edit_sharing_session.php');
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

    function get_events($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']) {
            $from = "FROM dev_events 

            ";
        } else {
            $from = "FROM dev_events 

            ";
        }

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

}

new dev_event_management();
