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

}

new dev_event_management();
