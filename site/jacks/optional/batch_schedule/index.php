<?php
class dev_batch_schedule {

    var $thsClass = 'dev_batch_schedule';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Batch Schedule',
            'permissions' => array(
                'batch_schedules' => array(
                    'add_batch_schedule' => 'Add Batch Schedule',
                    'edit_batch_schedule' => 'Edit Batch Schedule',
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
            'label' => 'Batch Schedule',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Batch Schedule',
            'description' => 'Manage All Batch Schedule',
            'menu_group' => 'Courses',
            'position' => 'top',
            'action' => 'manage_batch_schedules',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_batch_schedules'))
            admenu_register($params);
    }

    function manage_batch_schedules() {
        if (!has_permission('manage_batch_schedules'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'batch_schedules');
        include('pages/list_batch_schedules.php');
    }

    function get_batch_schedules($param = null) {      
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_batch_schedules "
                . "LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_batch_schedules.fk_branch_id) "
                . "LEFT JOIN dev_courses ON (dev_courses.pk_course_id = dev_batch_schedules.fk_course_id) ";
        
        $where = "WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_batch_schedule_id) AS TOTAL " . $from . $where;
        
        $loopCondition = array(
            'batch_schedule_id' => 'pk_batch_schedule_id',
            'division' => 'dev_branches.branch_division',
            'district' => 'dev_branches.branch_district',
            'sub_district' => 'dev_branches.branch_sub_district',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;
        
        $batch_schedules = sql_data_collector($sql, $count_sql, $param);
        
        return $batch_schedules; 
    }

    function add_edit_batch_schedule($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        if ($is_update && !has_permission('edit_batch_schedule')) {
            add_notification('You don\'t have enough permission to edit batch.', 'error');
            header('Location:' . build_url(NULL, array('edit', 'action')));
            exit();
        } elseif (!has_permission('add_batch_schedule')) {
            add_notification('You don\'t have enough permission to add batch schedule.', 'error');
            header('Location:' . build_url(NULL, array('action')));
            exit();
        }

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_batch_schedules(array('batch_schedule_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid batch schedule id, no data found']);
            }
        }
        
        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['fk_branch_id'] = $params['form_data']['branch_id'];        
            $insert_data['fk_course_id'] = $params['form_data']['course_id'];        
            $insert_data['batch_name'] = $params['form_data']['batch_name'];
            $insert_data['target_date'] = date('Y-m-d', strtotime($params['form_data']['target_date']));
            $insert_data['day_time_set'] = json_encode($params['form_data']['day_time_set']);        
            
            if ($is_update){
                $insert_data['update_date'] = date('Y-m-d');
                $insert_data['update_time'] = date('H:i:s');
                $insert_data['update_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_batch_schedules', $insert_data, " pk_batch_schedule_id = '" . $is_update . "'");
            }
            else {
                $insert_data['create_date'] = date('Y-m-d');
                $insert_data['create_time'] = date('H:i:s');
                $insert_data['create_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_batch_schedules', $insert_data);
            }
        }
        return $ret;
    }
}

new dev_batch_schedule();