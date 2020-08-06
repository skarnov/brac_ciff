<?php

class dev_customer_management {

    var $thsClass = 'dev_customer_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Beneficiaries',
            'permissions' => array(
                'manage_customers' => array(
                    'add_customer' => 'Add Customer',
                    'edit_customer' => 'Edit Customer',
                    'delete_customer' => 'Delete Customer',
                ),
                'manage_cases' => array(
                    'add_case' => 'Add Case',
                    'edit_case' => 'Edit Case',
                    'delete_case' => 'Delete Case',
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
            'label' => 'Beneficiaries',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Participant Profiles',
            'description' => 'Manage All Participant Profiles',
            'menu_group' => 'Beneficiaries',
            'position' => 'default',
            'action' => 'manage_customers',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_customers'))
            admenu_register($params);

        $params = array(
            'label' => 'Cases Management',
            'description' => 'Manage All Beneficiary Case Management',
            'menu_group' => 'Beneficiaries',
            'position' => 'default',
            'action' => 'manage_cases',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_cases'))
            admenu_register($params);
    }

    function manage_customers() {
        if (!has_permission('manage_customers'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_customers');

        if ($_GET['action'] == 'add_edit_customer')
            include('pages/add_edit_customer.php');
        elseif ($_GET['action'] == 'add_edit_evaluate')
            include('pages/add_edit_evaluate.php');
        elseif ($_GET['action'] == 'add_edit_satisfaction_scale')
            include('pages/add_edit_satisfaction_scale.php');
        elseif ($_GET['action'] == 'deleteProfile')
            include('pages/deleteProfile.php');
        elseif ($_GET['action'] == 'deleteProfileCase')
            include('pages/deleteProfileCase.php');
        else
            include('pages/list_customers.php');
    }

    function manage_cases() {
        if (!has_permission('manage_cases'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_cases');

        if ($_GET['action'] == 'add_edit_case')
            include('pages/add_edit_case.php');
        elseif ($_GET['action'] == 'add_edit_family_counseling')
            include('pages/add_edit_family_counseling.php');
        elseif ($_GET['action'] == 'add_edit_psychosocial_session')
            include('pages/add_edit_psychosocial_session.php');
        elseif ($_GET['action'] == 'add_edit_session_completion')
            include('pages/add_edit_session_completion.php');
        elseif ($_GET['action'] == 'add_edit_psychosocial_followup')
            include('pages/add_edit_psychosocial_followup.php');
        elseif ($_GET['action'] == 'deleteCase')
            include('pages/deleteCase.php');
        else
            include('pages/list_cases.php');
    }

    function get_customers($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');

        if ($param['listing']) {
            $from = "FROM dev_customers 

            ";
        } else {
            $from = "FROM dev_customers 
                LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_customers.pk_customer_id)
                LEFT JOIN dev_customer_health ON (dev_customer_health.fk_customer_id = dev_customers.pk_customer_id)
                LEFT JOIN dev_economic_profile ON (dev_economic_profile.fk_customer_id = dev_customers.pk_customer_id)
                LEFT JOIN dev_customer_skills ON (dev_customer_skills.fk_customer_id = dev_customers.pk_customer_id)
            ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_customers.pk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'customer_id' => 'dev_customers.pk_customer_id',
            'id' => 'dev_customers.customer_id',
            'name' => 'dev_customers.full_name',
            'nid' => 'dev_customers.nid_number',
            'birth' => 'dev_customers.birth_reg_number',
            'passport' => 'dev_customers.passport_number',
            'division' => 'dev_customers.permanent_division',
            'district' => 'dev_customers.permanent_district',
            'sub_district' => 'dev_customers.permanent_sub_district',
            'ps' => 'dev_customers.permanent_police_station',
            'entry_date' => 'dev_customers.create_date',
            'customer_type' => 'dev_customers.customer_type',
            'customer_status' => 'dev_customers.customer_status',
            'branch_id' => 'dev_customers.fk_branch_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $customers = sql_data_collector($sql, $count_sql, $param);
        return $customers;
    }

    function add_edit_customer($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_customers(array('customer_id' => $is_update, 'single' => true));
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

        if (!$ret['error']) {
            $customer_data = array();
            $customer_data['customer_id'] = $params['form_data']['customer_id'];
            $customer_data['full_name'] = $params['form_data']['full_name'];
            $customer_data['father_name'] = $params['form_data']['father_name'];
            $customer_data['mother_name'] = $params['form_data']['mother_name'];
            $customer_data['customer_birthdate'] = date('Y-m-d', strtotime($params['form_data']['customer_birthdate']));
            $customer_data['customer_mobile'] = $params['form_data']['customer_mobile'];
            $customer_data['emergency_mobile'] = $params['form_data']['emergency_mobile'];
            $customer_data['emergency_name'] = $params['form_data']['emergency_name'];
            $customer_data['emergency_relation'] = $params['form_data']['emergency_relation'];
            if ($params['form_data']['new_qualification']) {
                $customer_data['educational_qualification'] = $params['form_data']['new_qualification'];
            } else {
                $customer_data['educational_qualification'] = $params['form_data']['educational_qualification'];
            }
            $customer_data['nid_number'] = $params['form_data']['nid_number'];
            $customer_data['birth_reg_number'] = $params['form_data']['birth_reg_number'];
            $customer_data['passport_number'] = $params['form_data']['passport_number'];
            if ($params['form_data']['new_gender']) {
                $customer_data['customer_gender'] = $params['form_data']['new_gender'];
            } else {
                $customer_data['customer_gender'] = $params['form_data']['customer_gender'];
            }
            $customer_data['marital_status'] = $params['form_data']['marital_status'];
            $customer_data['customer_spouse'] = $params['form_data']['customer_spouse'];
            $customer_data['permanent_village'] = $params['form_data']['permanent_village'];
            $customer_data['permanent_ward'] = $params['form_data']['permanent_ward'];
            $customer_data['permanent_division'] = $params['form_data']['permanent_division'];
            $customer_data['permanent_union'] = $params['form_data']['permanent_union'];
            $customer_data['permanent_sub_district'] = $params['form_data']['permanent_sub_district'];
            $customer_data['permanent_district'] = $params['form_data']['permanent_district'];
            $customer_data['permanent_house'] = $params['form_data']['permanent_house'];
            $customer_data['customer_status'] = 'active';
            $customer_data['customer_type'] = 'ciff';
            if ($is_update) {
                $customer_data['update_date'] = date('Y-m-d');
                $customer_data['update_time'] = date('H:i:s');
                $customer_data['update_by'] = $_config['user']['pk_user_id'];
                $ret['customer_update'] = $devdb->insert_update('dev_customers', $customer_data, " pk_customer_id = '" . $is_update . "'");
            } else {
                $customer_data['create_date'] = date('Y-m-d');
                $customer_data['create_time'] = date('H:i:s');
                $customer_data['create_by'] = $_config['user']['pk_user_id'];
                $ret['customer_insert'] = $devdb->insert_update('dev_customers', $customer_data);
                /* Customer ID Creation */
//                $sql = "SELECT COUNT(pk_customer_id) as TOTAL FROM dev_customers WHERE (create_date >= '" . date('Y-m-01') . "' AND create_date <= '" . date('Y-m-t') . "')";
//                $totalCustomer = $devdb->get_row($sql);
//                $totalCustomer = $totalCustomer['TOTAL'];
//                $x = 10;
//                while ($x--) {
//                    $totalCustomer += 1;
//                    $customerID = 'C-' . date('y-m-') . str_pad($totalCustomer, 6, "0", STR_PAD_LEFT);
//                    $customerIDUpdate = $devdb->query("UPDATE dev_customers SET customer_id = '" . $customerID . "' WHERE pk_customer_id = '" . $ret['customer_insert']['success'] . "'");
//                    if ($customerIDUpdate['success']) {
//                        break;
//                    }
//                }

                $migration_data = array();
                $migration_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['migration_insert'] = $devdb->insert_update('dev_migrations', $migration_data);

                $economic_profile_data = array();
                $economic_profile_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['economic_insert'] = $devdb->insert_update('dev_economic_profile', $economic_profile_data);

                $skill_data = array();
                $skill_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['skill_insert'] = $devdb->insert_update('dev_customer_skills', $skill_data);

                $customer_health_data = array();
                $customer_health_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['health_insert'] = $devdb->insert_update('dev_customer_health', $customer_health_data);

                $case_data = array();
                $case_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['case_insert'] = $devdb->insert_update('dev_immediate_supports', $case_data);

                $evaluate_data = array();
                $evaluate_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['evaluate_insert'] = $devdb->insert_update('dev_initial_evaluation', $evaluate_data);

                $satisfaction_data = array();
                $satisfaction_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['satisfaction_insert'] = $devdb->insert_update('dev_reintegration_satisfaction_scale', $satisfaction_data);
            }

            $migration_data = array();

            $migration_data['left_port'] = $params['form_data']['left_port'];
            $migration_data['preferred_country'] = $params['form_data']['preferred_country'];
            $migration_data['final_destination'] = $params['form_data']['final_destination'];
            $migration_data['migration_type'] = $params['form_data']['migration_type'];

            if ($params['form_data']['new_visa']) {
                $migration_data['visa_type'] = $params['form_data']['new_visa'];
            } else {
                $migration_data['visa_type'] = $params['form_data']['visa_type'];
            }

            $migration_medias = array();
            $migration_medias['departure_media'] = $params['form_data']['departure_media'];
            $migration_medias['media_relation'] = $params['form_data']['media_relation'];
            $migration_medias['media_address'] = $params['form_data']['media_address'];

            $migration_data['migration_medias'] = json_encode($migration_medias);

            //Migration Documents For DADA

            $migration_data['departure_date'] = date('Y-m-d', strtotime($params['form_data']['departure_date']));
            $migration_data['return_date'] = date('Y-m-d', strtotime($params['form_data']['return_date']));

            $diff = abs(strtotime($migration_data['return_date']) - strtotime($migration_data['departure_date']));

            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

            $migration_data['migration_duration'] = "Year: $years, Month: $months, Days: $days";

            $migration_data['migration_occupation'] = $params['form_data']['migration_occupation'];
            $migration_data['earned_money'] = $params['form_data']['earned_money'];

            if ($params['form_data']['new_migration_reason'] == NULL) {
                $data_type = $params['form_data']['migration_reasons'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['migration_reasons'] = $data_types;
            } elseif ($params['form_data']['migration_reasons'] == NULL) {
                $migration_data['other_migration_reason'] = $params['form_data']['new_migration_reason'];
            } elseif ($params['form_data']['migration_reasons'] != NULL && $params['form_data']['new_migration_reason'] != NULL) {
                $data_type = $params['form_data']['migration_reasons'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['migration_reasons'] = $data_types;
                $migration_data['other_migration_reason'] = $params['form_data']['new_migration_reason'];
            }

            if ($params['form_data']['new_return_reason'] == NULL) {
                $data_type = $params['form_data']['destination_country_leave_reason'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['destination_country_leave_reason'] = $data_types;
            } elseif ($params['form_data']['destination_country_leave_reason'] == NULL) {
                $migration_data['other_destination_country_leave_reason'] = $params['form_data']['new_return_reason'];
            } elseif ($params['form_data']['destination_country_leave_reason'] != NULL && $params['form_data']['new_return_reason'] != NULL) {
                $data_type = $params['form_data']['destination_country_leave_reason'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['destination_country_leave_reason'] = $data_types;
                $migration_data['other_destination_country_leave_reason'] = $params['form_data']['new_return_reason'];
            }

            $migration_data['is_cheated'] = $params['form_data']['is_cheated'];
            $migration_data['forced_work'] = $params['form_data']['forced_work'];
            $migration_data['excessive_work'] = $params['form_data']['excessive_work'];
            $migration_data['is_money_deducted'] = $params['form_data']['is_money_deducted'];
            $migration_data['is_movement_limitation'] = $params['form_data']['is_movement_limitation'];
            $migration_data['employer_threatened'] = $params['form_data']['employer_threatened'];
            $migration_data['is_kept_document'] = $params['form_data']['is_kept_document'];

            if ($is_update) {
                $ret['migration_update'] = $devdb->insert_update('dev_migrations', $migration_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['migration_new_insert'] = $devdb->insert_update('dev_migrations', $migration_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }

            $economic_profile_data = array();
            $economic_profile_data['pre_occupation'] = $params['form_data']['pre_occupation'];
            $economic_profile_data['present_occupation'] = $params['form_data']['present_occupation'];
            $economic_profile_data['present_income'] = $params['form_data']['present_income'];
            $economic_profile_data['personal_savings'] = $params['form_data']['personal_savings'];
            $economic_profile_data['personal_debt'] = $params['form_data']['personal_debt'];
            if ($params['form_data']['new_ownership']) {
                $economic_profile_data['current_residence_ownership'] = $params['form_data']['new_ownership'];
            } else {
                $economic_profile_data['current_residence_ownership'] = $params['form_data']['current_residence_ownership'];
            }
            if ($params['form_data']['new_residence']) {
                $economic_profile_data['current_residence_type'] = $params['form_data']['new_residence'];
            } else {
                $economic_profile_data['current_residence_type'] = $params['form_data']['current_residence_type'];
            }
            $economic_profile_data['male_household_member'] = $params['form_data']['male_household_member'];
            $economic_profile_data['female_household_member'] = $params['form_data']['female_household_member'];
            $economic_profile_data['total_member'] = $params['form_data']['male_household_member'] + $params['form_data']['female_household_member'];

            if ($params['form_data']['new_ownership']) {
                $economic_profile_data['current_residence_ownership'] = $params['form_data']['new_ownership'];
            } else {
                $economic_profile_data['current_residence_ownership'] = $params['form_data']['current_residence_ownership'];
            }
            if ($params['form_data']['new_residence']) {
                $economic_profile_data['current_residence_type'] = $params['form_data']['new_residence'];
            } else {
                $economic_profile_data['current_residence_type'] = $params['form_data']['current_residence_type'];
            }
            if ($is_update) {
                $ret['economic_update'] = $devdb->insert_update('dev_economic_profile', $economic_profile_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['economic_new_insert'] = $devdb->insert_update('dev_economic_profile', $economic_profile_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }

            $skill_data = array();
            $skill_data['have_earner_skill'] = $params['form_data']['have_earner_skill'];

            if ($params['form_data']['new_have_technical'] == NULL) {
                $data_type = $params['form_data']['technical_have_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $skill_data['have_skills'] = $data_types;
            } elseif ($params['form_data']['technical_have_skills'] == NULL) {
                $skill_data['other_have_skills'] = $params['form_data']['new_have_technical'];
            } elseif ($params['form_data']['technical_have_skills'] != NULL && $params['form_data']['new_have_technical'] != NULL) {
                $data_type = $params['form_data']['technical_have_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $skill_data['have_skills'] = $data_types;
                $skill_data['other_have_skills'] = $params['form_data']['new_have_technical'];
            }

            if ($params['form_data']['new_vocational']) {
                $skill_data['vocational_skill'] = $params['form_data']['new_vocational'];
            }

            if ($params['form_data']['new_handicrafts']) {
                $skill_data['handicraft_skill'] = $params['form_data']['new_handicrafts'];
            }

            if ($is_update) {
                $ret['skill_update'] = $devdb->insert_update('dev_customer_skills', $skill_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['skill_new_insert'] = $devdb->insert_update('dev_customer_skills', $skill_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }

            $customer_health_data = array();
            $customer_health_data['is_physically_challenged'] = $params['form_data']['is_physically_challenged'];
            $customer_health_data['disability_type'] = $params['form_data']['disability_type'];
            $customer_health_data['having_chronic_disease'] = $params['form_data']['having_chronic_disease'];

            if ($params['form_data']['new_disease_type'] == NULL) {
                $data_type = $params['form_data']['disease_type'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $customer_health_data['disease_type'] = $data_types;
            } elseif ($params['form_data']['disease_type'] == NULL) {
                $customer_health_data['other_disease_type'] = $params['form_data']['new_disease_type'];
            } elseif ($params['form_data']['disease_type'] != NULL && $params['form_data']['new_disease_type'] != NULL) {
                $data_type = $params['form_data']['disease_type'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $customer_health_data['disease_type'] = $data_types;
                $customer_health_data['other_disease_type'] = $params['form_data']['new_disease_type'];
            }

            if ($is_update) {
                $ret['health_update'] = $devdb->insert_update('dev_customer_health', $customer_health_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['health_new_insert'] = $devdb->insert_update('dev_customer_health', $customer_health_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }
        }
        return $ret;
    }

    function get_initial_evaluation($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');
        $from = "FROM dev_initial_evaluation ";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(pk_evaluation_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'pk_evaluation_id',
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

    function add_edit_initial_evaluation($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_initial_evaluation(array('customer_id' => $is_update, 'single' => true));
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
                $ret['evaluation_update'] = $devdb->insert_update('dev_initial_evaluation', $data, " fk_customer_id = '" . $is_update . "'");
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

    function add_edit_satisfaction_scale($params = array()) {
        global $devdb, $_config;

        $ret = array('success' => array(), 'error' => array());
        $is_update = $params['edit'] ? $params['edit'] : array();

        $oldData = array();
        if ($is_update) {
            $oldData = $this->get_satisfaction_scale(array('customer_id' => $is_update, 'single' => true));
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
                $ret['satisfaction_update'] = $devdb->insert_update('dev_reintegration_satisfaction_scale', $satisfaction_scale_data, " fk_customer_id = '" . $is_update . "'");
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
                        LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_reintegration_plan ON (dev_reintegration_plan.fk_customer_id = dev_immediate_supports.fk_customer_id)
            ";
        } else {
            $from = "FROM dev_immediate_supports
                LEFT JOIN dev_customers ON (dev_customers.pk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_reintegration_plan ON (dev_reintegration_plan.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_psycho_supports ON (dev_psycho_supports.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_psycho_family_counselling ON (dev_psycho_family_counselling.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_psycho_sessions ON (dev_psycho_sessions.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_psycho_completions ON (dev_psycho_completions.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_psycho_followups ON (dev_psycho_followups.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_economic_supports ON (dev_economic_supports.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_economic_reintegration_referrals ON (dev_economic_reintegration_referrals.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_social_supports ON (dev_social_supports.fk_customer_id = dev_immediate_supports.fk_customer_id)
                        LEFT JOIN dev_followups ON (dev_followups.fk_customer_id = dev_immediate_supports.fk_customer_id)
            ";
        }

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_immediate_supports.fk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'dev_immediate_supports.fk_customer_id',
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
                $ret['customer_update'] = $devdb->insert_update('dev_immediate_supports', $immediate_support, " fk_customer_id = '" . $is_update . "'");
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

            if ($params['form_data']['new_social_protection']) {
                $reintegration_plan['social_protection'] = $params['form_data']['new_social_protection'];
            }
            if ($params['form_data']['new_security_measures']) {
                $reintegration_plan['security_measure'] = $params['form_data']['new_security_measures'];
            }

            $reintegration_plan['service_requested_note'] = $params['form_data']['service_requested_note'];
            if ($is_update) {
                $sql = "SELECT fk_customer_id FROM dev_reintegration_plan WHERE fk_customer_id = '$is_update'";
                $pre_customer_id = $devdb->get_row($sql);

                if ($pre_customer_id) {
                    $ret['reintegration_update'] = $devdb->insert_update('dev_reintegration_plan', $reintegration_plan, " fk_customer_id = '" . $is_update . "'");
                } else {
                    $reintegration_plan['fk_customer_id'] = $is_update;
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
                $psycho_supports['issue_discussed'] = $params['form_data']['new_issue_discussed'];
            } elseif ($params['form_data']['issue_discussed'] != NULL && $params['form_data']['new_issue_discussed'] != NULL) {
                $data_type = $params['form_data']['issue_discussed'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $psycho_supports['issue_discussed'] = $params['form_data']['new_issue_discussed'] . ',' . $data_types;
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
                $psycho_supports['reason_for_reffer'] = $params['form_data']['new_reason_for_reffer'];
            } elseif ($params['form_data']['reason_for_reffer'] != NULL && $params['form_data']['new_reason_for_reffer'] != NULL) {
                $data_type = $params['form_data']['reason_for_reffer'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $psycho_supports['reason_for_reffer'] = $params['form_data']['new_reason_for_reffer'] . ',' . $data_types;
            }

            if ($is_update) {
                $sql = "SELECT fk_customer_id FROM dev_psycho_supports WHERE fk_customer_id = '$is_update'";
                $pre_customer_id = $devdb->get_row($sql);

                if ($pre_customer_id) {
                    $ret['psycho_support_update'] = $devdb->insert_update('dev_psycho_supports', $psycho_supports, " fk_customer_id = '" . $is_update . "'");
                } else {
                    $psycho_supports['fk_customer_id'] = $is_update;
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
                $economic_supports_data['inkind_project'] = $params['form_data']['new_inkind_project'];
            } elseif ($params['form_data']['inkind_project'] != NULL && $params['form_data']['new_inkind_project'] != NULL) {
                $data_type = $params['form_data']['inkind_project'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_supports_data['inkind_project'] = $params['form_data']['new_inkind_project'] . ',' . $data_types;
            }

            $economic_supports_data['inkind_received'] = $params['form_data']['inkind_received'];
            $economic_supports_data['training_duration'] = $params['form_data']['training_duration'];
            $economic_supports_data['is_certification_received'] = $params['form_data']['is_certification_received'];
            $economic_supports_data['training_used'] = $params['form_data']['training_used'];
            $economic_supports_data['other_comments'] = $params['form_data']['other_comments'];
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
                $economic_supports_data['received_vocational_training'] = $params['form_data']['new_received_vocational_training'];
            } elseif ($params['form_data']['received_vocational_training'] != NULL && $params['form_data']['new_received_vocational_training'] != NULL) {
                $data_type = $params['form_data']['received_vocational_training'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_supports_data['received_vocational_training'] = $params['form_data']['new_received_vocational_training'] . ',' . $data_types;
            }

            $economic_supports_data['training_start_date'] = date('Y-m-d', strtotime($params['form_data']['training_start_date']));
            $economic_supports_data['training_end_date'] = date('Y-m-d', strtotime($params['form_data']['training_end_date']));

            if ($is_update) {
                $sql = "SELECT fk_customer_id FROM dev_economic_supports WHERE fk_customer_id = '$is_update'";
                $pre_customer_id = $devdb->get_row($sql);

                if ($pre_customer_id) {
                    $ret['economic_support_update'] = $devdb->insert_update('dev_economic_supports', $economic_supports_data, " fk_customer_id = '" . $is_update . "'");
                } else {
                    $economic_supports_data['fk_customer_id'] = $is_update;
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
                $economic_reintegration_data['received_vocational'] = $params['form_data']['new_received_vocational'];
            } elseif ($params['form_data']['received_vocational'] != NULL && $params['form_data']['new_received_vocational'] != NULL) {
                $data_type = $params['form_data']['received_vocational'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_reintegration_data['received_vocational'] = $params['form_data']['new_received_vocational'] . ',' . $data_types;
            }
            $economic_reintegration_data['is_certificate_received'] = $params['form_data']['is_certificate_received'];
            $economic_reintegration_data['used_far'] = $params['form_data']['used_far'];
            $economic_reintegration_data['other_comments'] = $params['form_data']['other_comments'];
            $economic_reintegration_data['is_economic_services'] = $params['form_data']['is_economic_services'];

            if ($params['form_data']['new_economic_support'] == NULL) {
                $data_type = $params['form_data']['economic_support'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_reintegration_data['economic_support'] = $data_types;
            } elseif ($params['form_data']['economic_support'] == NULL) {
                $economic_reintegration_data['economic_support'] = $params['form_data']['new_economic_support'];
            } elseif ($params['form_data']['economic_support'] != NULL && $params['form_data']['new_economic_support'] != NULL) {
                $data_type = $params['form_data']['economic_support'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $economic_reintegration_data['economic_support'] = $params['form_data']['new_economic_support'] . ',' . $data_types;
            }
            $economic_reintegration_data['is_assistance_received'] = $params['form_data']['is_assistance_received'];
            $economic_reintegration_data['refferd_to'] = $params['form_data']['refferd_to'];
            $economic_reintegration_data['refferd_address'] = $params['form_data']['refferd_address'];
            $economic_reintegration_data['trianing_date'] = date('Y-m-d', strtotime($params['form_data']['trianing_date']));
            $economic_reintegration_data['place_of_training'] = $params['form_data']['place_of_training'];
            $economic_reintegration_data['duration_training'] = $params['form_data']['duration_training'];
            $economic_reintegration_data['status_traning'] = $params['form_data']['status_traning'];
            $economic_reintegration_data['assistance_utilized'] = $params['form_data']['assistance_utilized'];

            if ($is_update) {
                $sql = "SELECT fk_customer_id FROM dev_economic_reintegration_referrals WHERE fk_customer_id = '$is_update'";
                $pre_customer_id = $devdb->get_row($sql);

                if ($pre_customer_id) {
                    $ret['economic_support_update'] = $devdb->insert_update('dev_economic_reintegration_referrals', $economic_reintegration_data, " fk_customer_id = '" . $is_update . "'");
                } else {
                    $economic_reintegration_data['fk_customer_id'] = $is_update;
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
                $dev_social_supports_data['reintegration_economic'] = $params['form_data']['new_reintegration_economic'];
            } elseif ($params['form_data']['reintegration_economic'] != NULL && $params['form_data']['new_reintegration_economic'] != NULL) {
                $data_type = $params['form_data']['reintegration_economic'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $dev_social_supports_data['reintegration_economic'] = $params['form_data']['new_reintegration_economic'] . ',' . $data_types;
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
                $dev_social_supports_data['support_referred'] = $params['form_data']['new_supportreferred'];
            } elseif ($params['form_data']['support_referred'] != NULL && $params['form_data']['new_supportreferred'] != NULL) {
                $data_type = $params['form_data']['support_referred'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $dev_social_supports_data['support_referred'] = $params['form_data']['new_supportreferred'] . ',' . $data_types;
            }

            if ($is_update) {
                $sql = "SELECT fk_customer_id FROM dev_social_supports WHERE fk_customer_id = '$is_update'";
                $pre_customer_id = $devdb->get_row($sql);

                if ($pre_customer_id) {
                    $ret['social_support_update'] = $devdb->insert_update('dev_social_supports', $dev_social_supports_data, " fk_customer_id = '" . $is_update . "'");
                } else {
                    $dev_social_supports_data['fk_customer_id'] = $is_update;
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
                $dev_social_supports_data['reason_dropping'] = $data_types;
            } elseif ($params['form_data']['reason_dropping'] == NULL) {
                $dev_social_supports_data['reason_dropping'] = $params['form_data']['new_reason_dropping'];
            } elseif ($params['form_data']['reason_dropping'] != NULL && $params['form_data']['new_reason_dropping'] != NULL) {
                $data_type = $params['form_data']['reason_dropping'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $dev_social_supports_data['reason_dropping'] = $params['form_data']['new_reason_dropping'] . ',' . $data_types;
            }


            if ($params['form_data']['new_confirm_services'] == NULL) {
                $data_type = $params['form_data']['confirm_services'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $confirm_services = $data_types;
            } elseif ($params['form_data']['confirm_services'] == NULL) {
                $confirm_services = $params['form_data']['new_confirm_services'];
            } elseif ($params['form_data']['confirm_services'] != NULL && $params['form_data']['new_confirm_services'] != NULL) {
                $data_type = $params['form_data']['confirm_services'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $confirm_services = $params['form_data']['new_confirm_services'] . ',' . $data_types;
            }

            $confirm_services_data = array();
            if ($confirm_services) {
                $confirm_services_data['confirm_services'] = $confirm_services;
                if ($params['form_data']['social_protection_service']) {
                    $confirm_services_data['social_protection'] = $params['form_data']['social_protection_service'];
                }
                if ($params['form_data']['special_security_measures']) {
                    $confirm_services_data['security_measures'] = $params['form_data']['special_security_measures'];
                }
            }
            $dev_followups_data['confirm_services'] = implode(',', $confirm_services_data);

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
                $sql = "SELECT fk_customer_id FROM dev_followups WHERE fk_customer_id = '$is_update'";
                $pre_customer_id = $devdb->get_row($sql);

                if ($pre_customer_id) {
                    $ret['followup_update'] = $devdb->insert_update('dev_followups', $dev_followups_data, " fk_customer_id = '" . $is_update . "'");
                } else {
                    $dev_followups_data['fk_customer_id'] = $is_update;
                    $ret['followup_insert'] = $devdb->insert_update('dev_followups', $dev_followups_data);
                }
            }

            exit();
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
                $psycho_family_counselling_data['fk_customer_id'] = $params['customer_id'];
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
                $psycho_sessions_data['fk_customer_id'] = $params['customer_id'];
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
                $psycho_completions_data['fk_customer_id'] = $params['customer_id'];
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
                $psycho_followups_data['fk_customer_id'] = $params['customer_id'];
                $ret = $devdb->insert_update('dev_psycho_followups', $psycho_followups_data);
            }
        }
        return $ret;
    }

}

new dev_customer_management();
