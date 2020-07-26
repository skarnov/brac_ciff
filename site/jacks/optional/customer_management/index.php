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
        elseif ($_GET['action'] == 'deleteCase')
            include('pages/deleteCase.php');
        else
            include('pages/list_cases.php');
    }

    function get_lookups($lookup_group) {
        $sql = "SELECT * FROM dev_lookups WHERE lookup_group = '$lookup_group'";
        $data = sql_data_collector($sql);
        return $data;
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
            'division' => 'dev_customers.present_division',
            'district' => 'dev_customers.present_district',
            'sub_district' => 'dev_customers.present_sub_district',
            'ps' => 'dev_customers.present_police_station',
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
                $sql = "SELECT COUNT(pk_customer_id) as TOTAL FROM dev_customers WHERE (create_date >= '" . date('Y-m-01') . "' AND create_date <= '" . date('Y-m-t') . "')";
                $totalCustomer = $devdb->get_row($sql);
                $totalCustomer = $totalCustomer['TOTAL'];
                $x = 10;
                while ($x--) {
                    $totalCustomer += 1;
                    $customerID = 'C-' . date('y-m-') . str_pad($totalCustomer, 6, "0", STR_PAD_LEFT);
                    $customerIDUpdate = $devdb->query("UPDATE dev_customers SET customer_id = '" . $customerID . "' WHERE pk_customer_id = '" . $ret['customer_insert']['success'] . "'");
                    if ($customerIDUpdate['success']) {
                        break;
                    }
                }

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

            //Migration Documents

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
                $migration_data['migration_reasons'] = $params['form_data']['new_migration_reason'];
            } elseif ($params['form_data']['migration_reasons'] != NULL && $params['form_data']['new_migration_reason'] != NULL) {
                $data_type = $params['form_data']['migration_reasons'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['migration_reasons'] = $params['form_data']['new_migration_reason'] . ',' . $data_types;
            }

            if ($params['form_data']['new_return_reason'] == NULL) {
                $data_type = $params['form_data']['destination_country_leave_reason'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['destination_country_leave_reason'] = $data_types;
            } elseif ($params['form_data']['destination_country_leave_reason'] == NULL) {
                $migration_data['destination_country_leave_reason'] = $params['form_data']['new_return_reason'];
            } elseif ($params['form_data']['destination_country_leave_reason'] != NULL && $params['form_data']['new_return_reason'] != NULL) {
                $data_type = $params['form_data']['destination_country_leave_reason'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['destination_country_leave_reason'] = $params['form_data']['new_return_reason'] . ',' . $data_types;
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
                $have_technical_skills = $data_types;
            } elseif ($params['form_data']['technical_have_skills'] == NULL) {
                $have_technical_skills = $params['form_data']['new_have_technical'];
            } elseif ($params['form_data']['technical_have_skills'] != NULL && $params['form_data']['new_have_technical'] != NULL) {
                $data_type = $params['form_data']['technical_have_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $have_technical_skills = $params['form_data']['new_have_technical'] . ',' . $data_types;
            }

            $skills = array();
            if ($have_technical_skills) {
                $skills['technical_skill'] = $have_technical_skills;
                if ($params['form_data']['new_vocational']) {
                    $skills['vocational_skills'] = $params['form_data']['new_vocational'];
                }
                if ($params['form_data']['new_handicrafts']) {
                    $skills['handicraft_skills'] = $params['form_data']['new_handicrafts'];
                }
            }
            $skill_data['have_skills'] = implode(',', $skills);

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
                $customer_health_data['disease_type'] = $params['form_data']['new_disease_type'];
            } elseif ($params['form_data']['disease_type'] != NULL && $params['form_data']['new_disease_type'] != NULL) {
                $data_type = $params['form_data']['disease_type'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $customer_health_data['disease_type'] = $params['form_data']['new_disease_type'] . ',' . $data_types;
            }

            if ($is_update) {
                $ret['health_update'] = $devdb->insert_update('dev_customer_health', $customer_health_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['health_new_insert'] = $devdb->insert_update('dev_customer_health', $customer_health_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }
        }
        return $ret;
    }

    function get_cases($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : '* ');


        $from = "FROM dev_immediate_supports 

            ";

        $where = " WHERE 1";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(dev_immediate_supports.pk_customer_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
//            'customer_id' => 'dev_customers.pk_customer_id',
            'id' => 'dev_immediate_supports.fk_customer_id',
//            'name' => 'dev_customers.full_name',
//            'nid' => 'dev_customers.nid_number',
//            'birth' => 'dev_customers.birth_reg_number',
//            'passport' => 'dev_customers.passport_number',
//            'division' => 'dev_customers.present_division',
//            'district' => 'dev_customers.present_district',
//            'sub_district' => 'dev_customers.present_sub_district',
//            'ps' => 'dev_customers.present_police_station',
//            'entry_date' => 'dev_customers.create_date',
//            'customer_type' => 'dev_customers.customer_type',
//            'customer_status' => 'dev_customers.customer_status',
//            'branch_id' => 'dev_customers.fk_branch_id',
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
            $immediate_support = array();





            $data_type = $params['form_data']['immediate_support'];
            $data_types = is_array($data_type) ? implode(',', $data_type) : '';
            $immediate_support['immediate_support'] = $data_types;



            if ($is_update) {
//                $immediate_support['update_date'] = date('Y-m-d');
//                $immediate_support['update_time'] = date('H:i:s');
//                $immediate_support['update_by'] = $_config['user']['pk_user_id'];
                $ret['customer_update'] = $devdb->insert_update('dev_immediate_supports', $immediate_support, " fk_customer_id = '" . $is_update . "'");
            } else {
//                $customer_data['create_date'] = date('Y-m-d');
//                $customer_data['create_time'] = date('H:i:s');
//                $customer_data['create_by'] = $_config['user']['pk_user_id'];
                $ret['customer_insert'] = $devdb->insert_update('dev_immediate_supports', $immediate_support);
            }




//            $reintegration_plan = array();
//
//            
//            if ($is_update) {
//                $ret['migration_update'] = $devdb->insert_update('dev_migrations', $migration_data, " fk_customer_id = '" . $is_update . "'");
//            } else {
//                $ret['migration_new_insert'] = $devdb->insert_update('dev_migrations', $migration_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
//            }


            $psycho_supports = array();

            $psycho_supports['first_meeting'] = date('Y-m-d', strtotime($params['form_data']['first_meeting']));
            
            

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
            
            
            $psycho_supports['session_duration'] = $params['form_data']['session_duration'];
            
            if ($params['form_data']['new_place']) {
                $economic_profile_data['session_place'] = $params['form_data']['new_place'];
            } else {
                $economic_profile_data['session_place'] = $params['form_data']['session_place'];
            }
            
            
//            if ($is_update) {
//                $ret['migration_update'] = $devdb->insert_update('dev_migrations', $migration_data, " fk_customer_id = '" . $is_update . "'");
//            } else {
//                $ret['migration_new_insert'] = $devdb->insert_update('dev_migrations', $migration_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
//            }







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
                $have_technical_skills = $data_types;
            } elseif ($params['form_data']['technical_have_skills'] == NULL) {
                $have_technical_skills = $params['form_data']['new_have_technical'];
            } elseif ($params['form_data']['technical_have_skills'] != NULL && $params['form_data']['new_have_technical'] != NULL) {
                $data_type = $params['form_data']['technical_have_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $have_technical_skills = $params['form_data']['new_have_technical'] . ',' . $data_types;
            }

            $skills = array();
            if ($have_technical_skills) {
                $skills['technical_skill'] = $have_technical_skills;
                if ($params['form_data']['new_vocational']) {
                    $skills['vocational_skills'] = $params['form_data']['new_vocational'];
                }
                if ($params['form_data']['new_handicrafts']) {
                    $skills['handicraft_skills'] = $params['form_data']['new_handicrafts'];
                }
            }
            $skill_data['have_skills'] = implode(',', $skills);

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
                $customer_health_data['disease_type'] = $params['form_data']['new_disease_type'];
            } elseif ($params['form_data']['disease_type'] != NULL && $params['form_data']['new_disease_type'] != NULL) {
                $data_type = $params['form_data']['disease_type'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $customer_health_data['disease_type'] = $params['form_data']['new_disease_type'] . ',' . $data_types;
            }

            if ($is_update) {
                $ret['health_update'] = $devdb->insert_update('dev_customer_health', $customer_health_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['health_new_insert'] = $devdb->insert_update('dev_customer_health', $customer_health_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }
        }
        return $ret;
    }

}

new dev_customer_management();
