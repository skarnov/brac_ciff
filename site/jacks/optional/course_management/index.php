<?php

class dev_course_management {

    var $thsClass = 'dev_course_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        apiRegister($this, 'get_tags_autocomplete');
        $permissions = array(
            'group_name' => 'Courses',
            'permissions' => array(
                'manage_courses' => array(
                    'add_course' => 'Add Course',
                    'edit_course' => 'Edit Course',
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
            'label' => 'Courses',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Course Management',
            'description' => 'Manage All Courses',
            'menu_group' => 'Courses',
            'position' => 'top',
            'action' => 'manage_courses',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_courses'))
            admenu_register($params);
        
        $params = array(
            'label' => 'Course Admission',
            'description' => 'Course Admission',
            'menu_group' => 'Courses',
            'position' => 'top',
            'action' => 'course_admission',
            'iconClass' => 'fa-hourglass',
            'jack' => $this->thsClass,
        );
        
        if (has_permission('manage_courses'))
            admenu_register($params);
        
        $params = array(
            'label' => 'Course Results',
            'description' => 'Publish The Course Result',
            'menu_group' => 'Courses',
            'position' => 'top',
            'action' => 'result_publish',
            'iconClass' => 'fa-bar-chart',
            'jack' => $this->thsClass,
        );
        
        if (has_permission('manage_courses'))
            admenu_register($params);
        
        $params = array(
            'label' => 'Certificate Creation',
            'description' => 'Create The Course Certificate',
            'menu_group' => 'Courses',
            'position' => 'top',
            'action' => 'search_certificate',
            'iconClass' => 'fa-certificate',
            'jack' => $this->thsClass,
        );
        
        if (has_permission('manage_courses'))
            admenu_register($params);
    }

    function manage_courses() {
        if (!has_permission('manage_courses'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_courses');

        include('pages/list_courses.php');
    }

    function course_admission() {
        if (!has_permission('manage_courses'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_courses');

        if ($_GET['action'] == 'add_edit_admission')
            include('pages/add_edit_admission.php');
        else
            include('pages/course_admissions.php');
    }

    function search_certificate() {
        if (!has_permission('manage_courses'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_courses');

        if ($_GET['action'] == 'download_certificate')
            include('pages/download_certificate.php');
        else
            include('pages/search_certificate.php');
    }

    function get_courses($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_courses "
                . "LEFT JOIN dev_branches ON (dev_courses.fk_branch_id = dev_branches.pk_branch_id)";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_course_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'course_id' => 'dev_courses.pk_course_id',
            'division' => 'dev_branches.branch_division',
            'district' => 'dev_branches.branch_district',
            'sub_district' => 'dev_branches.branch_sub_district',
            'branch_id' => 'dev_courses.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $courses = sql_data_collector($sql, $count_sql, $param);

        return $courses;
    }

    function add_edit_course($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_courses(array('course_id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid course id, no data found']);
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
            $insert_data['fk_branch_id'] = $params['form_data']['fk_branch_id'];
            $insert_data['course_name'] = $params['form_data']['course_name'];
            $insert_data['course_duration'] = $params['form_data']['course_duration'];
            $insert_data['course_price'] = $params['form_data']['course_price'];

            $insert_data['update_date'] = date('Y-m-d');
            $insert_data['update_time'] = date('H:i:s');
            $insert_data['update_by'] = $_config['user']['pk_user_id'];

            if ($is_update)
                $ret = $devdb->insert_update('dev_courses', $insert_data, " pk_course_id = '" . $is_update . "'");
            else {
                $insert_data['create_date'] = date('Y-m-d');
                $insert_data['create_time'] = date('H:i:s');
                $insert_data['create_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_courses', $insert_data);
            }
        }
        return $ret;
    }

    function get_tags_autocomplete() {
        global $devdb;

        $param = array_replace_recursive($_POST, $_GET);

        $items = $devdb->query("SELECT pk_customer_id as id, CONCAT(full_name, ' [', customer_id, ']') as label FROM dev_customers WHERE full_name LIKE '%" . $param['term'] . "%' AND customer_type = 'potential'");
        return $items;
    }

    function result_publish() {
        if (!has_permission('manage_courses'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_courses');

        include('pages/result_publish.php');
    }

    function get_customer($id) {
        global $devdb;

        $result = $devdb->query("SELECT pk_customer_id as id, full_name as label FROM dev_customers WHERE pk_customer_id = '$id'");
        return $result;
    }

    function get_admissions($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_admissions "
                . "LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_admissions.fk_customer_id)
                   LEFT JOIN dev_courses ON (dev_courses.pk_course_id = dev_admissions.fk_course_id)
                   LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_admissions.fk_branch_id)
                   ";
        if ($param['course_result']):
            $from .= "LEFT JOIN dev_results ON (dev_results.fk_admission_id = dev_admissions.pk_admission_id)";
        endif;
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_admission_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_admissions.pk_admission_id',
            'branch_id' => 'dev_admissions.fk_branch_id',
            'course_id' => 'dev_admissions.fk_course_id',
            'batch_name' => 'dev_admissions.batch_name',
            'customer_id' => 'dev_admissions.fk_customer_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $admissions = sql_data_collector($sql, $count_sql, $param);
        return $admissions;
    }

    function get_customer_results($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_results "
                . "LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_results.fk_customer_id)
                    LEFT JOIN dev_courses ON (dev_courses.pk_course_id = dev_results.fk_course_id)
                   ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_result_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'customer_id' => 'dev_customers.customer_id',
            'id' => 'dev_results.fk_customer_id',
            'certificate_id' => 'dev_results.pk_result_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $results = sql_data_collector($sql, $count_sql, $param);

        return $results;
    }

    function add_edit_admission($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_admissions(array('id' => $is_update, 'single' => true));
            if (!$oldData) {
                return array('error' => ['Invalid admission id, no data found']);
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
            $dateNow = date('Y-m-d');
            $timeNow = date('H:i:s');
            $customers = $params['form_data']['fk_customer_id'];
            $branch_id = $params['form_data']['branch_id'];
            $staff_id = $params['form_data']['staff_id'];
            $course_id = $params['form_data']['course_id'];
            $course_info = $this->get_courses(array('course_id' => $course_id, 'select_fields' => array('dev_courses.course_price'), 'single' => true));
            $course_price = $course_info['course_price'];
            $insert_data = array();
            if ($is_update) {
                $insert_data['fk_branch_id'] = $branch_id;
                $insert_data['fk_course_id'] = $course_id;
                $insert_data['batch_name'] = $params['form_data']['batch_name'];
                $insert_data['fk_customer_id'] = $customers;
                $insert_data['update_date'] = $dateNow;
                $insert_data['update_time'] = $timeNow;
                $insert_data['update_by'] = $_config['user']['pk_user_id'];
                $ret = $devdb->insert_update('dev_admissions', $insert_data, " pk_admission_id = '" . $is_update . "'");
            } else {
                if (is_array($customers) || is_object($customers)) {
                    foreach ($customers as $i => $customer_id) {
                        $insert_data['fk_branch_id'] = $branch_id;
                        $insert_data['fk_course_id'] = $course_id;
                        $insert_data['batch_name'] = $params['form_data']['batch_name'];
                        $insert_data['fk_customer_id'] = $customer_id;
                        $insert_data['fk_staff_id'] = $staff_id;
                        $insert_data['create_date'] = $dateNow;
                        $insert_data['create_time'] = $timeNow;
                        $insert_data['create_by'] = $_config['user']['pk_user_id'];
                        $ret = $devdb->insert_update('dev_admissions', $insert_data);

                        $sale_data = array();
                        $sale_data['fk_branch_id'] = $branch_id;
                        $sale_data['fk_customer_id'] = $customer_id;
                        $sale_data['fk_staff_id'] = $staff_id;
                        $sale_data['sale_title'] = $params['sale_title'];
                        $sale_data['invoice_date'] = $dateNow;
                        $sale_data['sale_total'] = $course_price;
                        $sale_data['create_date'] = $dateNow;
                        $sale_data['create_time'] = $timeNow;
                        $sale_data['create_by'] = $_config['user']['pk_user_id'];
                        $ret['sale_total'] = $devdb->insert_update('dev_sales', $sale_data);

                        $sale_id = $ret['sale_total']['success'];

                        $sale = array();
                        $sale['fk_sale_id'] = $sale_id;
                        $sale['fk_product_id'] = '-'.$course_id;
                        $sale['item_quantity'] = 1;
                        $sale['item_price'] = $course_price;
                        $sale['item_total_price'] = $course_price;
                        $sale['create_date'] = $dateNow;
                        $sale['create_time'] = $timeNow;
                        $sale['create_by'] = $_config['user']['pk_user_id'];
                        $ret['sale_items_insert'] = $devdb->insert_update('dev_sale_items', $sale);

                        $sale_income = array();
                        $sale_income['fk_sale_id'] = $sale_id;
                        $sale_income['fk_branch_id'] = $branch_id;
                        $sale_income['sale_income'] = $course_price;
                        $sale_income['create_date'] = $dateNow;
                        $sale_income['create_time'] = $timeNow;
                        $sale_income['create_by'] = $_config['user']['pk_user_id'];
                        $ret['insert_sales_income'] = $devdb->insert_update('dev_sales_income', $sale_income);

                        $financeManager = jack_obj('dev_financial_management');
                        $incentive = $financeManager->get_sales_incentive(array('staff_id' => $staff_id, 'order_by' => array('col' => 'pk_sales_incentive_id', 'order' => 'DESC'), 'single' => true));
                        
                        $current_month = date('F', strtotime($dateNow));
                        $incentive_month = $incentive['month_name'];
                        
                        if ($incentive) {
                            if ($incentive['sale_count'] >= $incentive['unit_incentive'] && ($current_month == $incentive_month)) {
                                $sale_incentive = array();
                                $sale_incentive['fk_branch_id'] = $branch_id;
                                $sale_incentive['fk_product_id'] = '-'.$course_id;;
                                $sale_incentive['fk_staff_id'] = $staff_id;
                                $sale_incentive['fk_sale_id'] = $sale_id;
                                $sale_incentive['sale_total'] = $course_price;
                                $sale_incentive['incentive_percentage'] = $incentive['incentive_percentage'];
                                $sale_incentive['incentive_amount'] = $course_price * ($sale_incentive['incentive_percentage'] / 100);
                                $sale_incentive['create_date'] = $dateNow;
                                $sale_incentive['create_time'] = $timeNow;
                                $sale_incentive['create_by'] = $_config['user']['pk_user_id'];
                                $ret['staff_incentive_insert'] = $devdb->insert_update('dev_staff_incentives', $sale_incentive);
                            }
                        }
                        
                        $ret['sale_count_increment'] = $devdb->query("UPDATE dev_sales_incentive SET sale_count = sale_count + 1 WHERE pk_sales_incentive_id = '" . $incentive['pk_sales_incentive_id'] . "'");
                        $target = $financeManager->get_sales_targets(array('staff_id' => $staff_id, 'order_by' => array('col' => 'pk_target_id', 'order' => 'DESC'), 'single' => true));
                        $ret['target_increment'] = $devdb->query("UPDATE dev_sales_targets SET achievement_quantity = achievement_quantity + 1 WHERE pk_target_id = '" . $target['pk_target_id'] . "' AND month_name = '$current_month'");
                    }
                }
            }
        }
        return $ret;
    }
}

new dev_course_management();
