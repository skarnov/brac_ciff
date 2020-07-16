<?php
class dev_batch_management {

    var $thsClass = 'dev_batch_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Batchs',
            'permissions' => array(
                'manage_batches' => array(
                    'add_batch' => 'Add Batch',
                    'edit_batch' => 'Edit Batch',
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
            'label' => 'Batchs',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Batch Management',
            'description' => 'Manage All Batchs',
            'menu_group' => 'Courses',
            'position' => 'top',
            'action' => 'manage_batches',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_batches'))
            admenu_register($params);
    }

    function manage_batches() {
        if (!has_permission('manage_batches'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_batches');
        include('pages/list_batches.php');
    }

    function get_batches($param = null) {      
        $param['single'] = $param['single'] ? $param['single'] : false;
        $param['index_with'] = isset($param['index_with']) ? $param['index_with'] : 'pk_batch_id';

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_batches 
                    LEFT JOIN dev_courses ON (dev_courses.pk_course_id = dev_batches.fk_course_id)
                    LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_batches.fk_branch_id)
                ";
        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_batch_id) AS TOTAL " . $from . $where;
        
        $loopCondition = array(
            'batch_id' => 'dev_batches.pk_batch_id',
            'division' => 'dev_branches.branch_division',
            'district' => 'dev_branches.branch_district',
            'sub_district' => 'dev_branches.branch_sub_district',
            'branch_id' => 'dev_batches.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;
        
        $batches = sql_data_collector($sql, $count_sql, $param);
        return $batches; 
    }

    function add_edit_batch($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_batches(array('batch_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid batch id, no data found']);
            }
        }

        foreach ($params['required'] as $i => $v) {
            if (isset($params['form_data'][$i]))
                $temp = form_validator::required($params['form_data'][$i]);
            if ($temp !== true) {
                $ret['error'][] = $v . ' ' . $temp;
            }
        }

        $temp = form_validator::_length($params['required']['batch_name'], 490);
        if ($temp !== true)
            $ret['error'][] = 'Batch Name ' . $temp;

        if (!$ret['error']) {
            $insert_data = array();
            $insert_data['fk_branch_id'] = $params['form_data']['fk_branch_id'];
            $insert_data['fk_course_id'] = $params['form_data']['course_id'];        
            $batch_name = $params['form_data']['batch_name'];
            $space_remove = str_replace(' ', '', $batch_name);
            $insert_data['batch_name'] = $space_remove;
            $insert_data['update_date'] = date('Y-m-d');
            $insert_data['update_time'] = date('H:i:s');
            $insert_data['update_by'] = $_config['user']['pk_user_id'];

            if ($is_update)
                $ret = $devdb->insert_update('dev_batches', $insert_data, " pk_batch_id = '" . $is_update . "'");
            else {
                $insert_data['create_date'] = date('Y-m-d');
                $insert_data['create_time'] = date('H:i:s');
                $insert_data['create_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_batches', $insert_data);
            }
        }
        return $ret;
    }
}

new dev_batch_management();