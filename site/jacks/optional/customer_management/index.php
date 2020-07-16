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
                'manage_returnees' => array(
                    'add_customer' => 'Add Customer',
                    'edit_customer' => 'Edit Customer',
                    'delete_customer' => 'Delete Customer',
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
    }

    function manage_customers() {
        if (!has_permission('manage_customers'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_customers');

        if ($_GET['action'] == 'add_edit_customer')
            include('pages/add_edit_customer.php');

        elseif ($_GET['action'] == 'delete_customer')
            include('pages/delete_customer.php');
        else
            include('pages/list_customers.php');
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
                    LEFT JOIN dev_climate_change ON (dev_climate_change.fk_customer_id = dev_customers.pk_customer_id)
                    LEFT JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_customers.pk_customer_id)
                    LEFT JOIN dev_immediate_supports ON (dev_immediate_supports.fk_customer_id = dev_customers.pk_customer_id)
                    LEFT JOIN dev_cooperations ON (dev_cooperations.fk_customer_id = dev_customers.pk_customer_id)
                    LEFT JOIN dev_customer_health ON (dev_customer_health.fk_customer_id = dev_customers.pk_customer_id)
                    LEFT JOIN dev_economic_profile ON (dev_economic_profile.fk_customer_id = dev_customers.pk_customer_id)
                    LEFT JOIN dev_customer_skills ON (dev_customer_skills.fk_customer_id = dev_customers.pk_customer_id)
                    LEFT JOIN dev_psycho_evaluations ON (dev_psycho_evaluations.fk_customer_id = dev_customers.pk_customer_id AND dev_psycho_evaluations.fk_psycho_support_id ='0')
                    LEFT JOIN dev_social_evaluations ON (dev_social_evaluations.fk_customer_id = dev_customers.pk_customer_id AND dev_social_evaluations.fk_social_support_id ='0')
                    LEFT JOIN dev_economic_evaluations ON (dev_economic_evaluations.fk_customer_id = dev_customers.pk_customer_id AND dev_economic_evaluations.fk_economic_support_id ='0')
                    LEFT JOIN dev_reintegration_plan ON (dev_reintegration_plan.fk_customer_id = dev_customers.pk_customer_id)
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
            'bmet' => 'dev_customers.bmet_card_number',
            'last_visited_country' => 'dev_customers.last_visited_country',
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
            $oldData = $this->get_returnees(array('customer_id' => $is_update, 'single' => true));
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

        if ($_FILES['customer_photo']['name']) {
            $supported_ext = array('jpg', 'png');
            $max_filesize = 512000;
            $target_dir = _path('uploads', 'absolute') . "/";
            if (!file_exists($target_dir))
                mkdir($target_dir);
            $target_file = $target_dir . basename($_FILES['customer_photo']['name']);
            $fileinfo = pathinfo($target_file);
            $target_file = $target_dir . str_replace(' ', '_', $fileinfo['filename']) . '_' . time() . '.' . $fileinfo['extension'];
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            if (in_array(strtolower($imageFileType), $supported_ext)) {
                if ($max_filesize && $_FILES['customer_photo']['size'] <= $max_filesize) {
                    if (!move_uploaded_file($_FILES['customer_photo']['tmp_name'], $target_file)) {
                        $ret['error'][] = 'Customer Picture : File was not uploaded, please try again.';
                        $params['form_data']['customer_photo'] = '';
                    } else {
                        $fileinfo = pathinfo($target_file);
                        $params['form_data']['customer_photo'] = $fileinfo['basename'];
                        @unlink(_path('uploads', 'absolute') . '/' . $params['form_data']['customer_old_photo']);
                    }
                } else
                    $ret['error'][] = 'Customer Picture : <strong>' . $_FILES['customer_photo']['size'] . ' B</strong> is more than supported file size <strong>' . $max_filesize . ' B';
            } else
                $ret['error'][] = 'Customer Picture : <strong>.' . $imageFileType . '</strong> is not supported extension. Only supports .' . implode(', .', $supported_ext);
        } else {
            $params['form_data']['customer_photo'] = $params['form_data']['customer_old_photo'];
        }

        if (!$ret['error']) {
            $customer_data = array();
            $customer_data['full_name'] = $params['form_data']['full_name'];
            $customer_data['father_name'] = $params['form_data']['father_name'];
            $customer_data['mother_name'] = $params['form_data']['mother_name'];
            $customer_data['customer_photo'] = $params['form_data']['customer_photo'];
            $customer_data['marital_status'] = $params['form_data']['marital_status'];
            $customer_data['customer_spouse'] = $params['form_data']['customer_spouse'];
            $customer_data['customer_birthdate'] = date('Y-m-d', strtotime($params['form_data']['customer_birthdate']));
            if ($params['form_data']['new_gender']) {
                $customer_data['customer_gender'] = $params['form_data']['new_gender'];
            } else {
                $customer_data['customer_gender'] = $params['form_data']['customer_gender'];
            }
            if ($params['form_data']['new_religion']) {
                $customer_data['customer_religion'] = $params['form_data']['new_religion'];
            } else {
                $customer_data['customer_religion'] = $params['form_data']['customer_religion'];
            }
            $customer_data['nid_number'] = $params['form_data']['nid_number'];
            $customer_data['passport_number'] = $params['form_data']['passport_number'];
            $customer_data['birth_reg_number'] = $params['form_data']['birth_reg_number'];
            $customer_data['bmet_card_number'] = $params['form_data']['bmet_card_number'];
            $customer_data['travel_pass'] = $params['form_data']['travel_pass'];
            if ($params['form_data']['new_qualification']) {
                $customer_data['educational_qualification'] = $params['form_data']['new_qualification'];
            } else {
                $customer_data['educational_qualification'] = $params['form_data']['educational_qualification'];
            }
            $customer_data['customer_mobile'] = $params['form_data']['customer_mobile'];
            $customer_data['emergency_mobile'] = $params['form_data']['emergency_mobile'];
            $customer_data['emergency_name'] = $params['form_data']['emergency_name'];
            $customer_data['emergency_relation'] = $params['form_data']['emergency_relation'];
            $customer_data['present_flat'] = $params['form_data']['present_flat'];
            $customer_data['present_house'] = $params['form_data']['present_house'];
            $customer_data['present_road'] = $params['form_data']['present_road'];
            $customer_data['present_village'] = $params['form_data']['present_village'];
            $customer_data['present_union'] = $params['form_data']['present_union'];
            $customer_data['present_ward'] = $params['form_data']['present_ward'];
            $customer_data['present_post_office'] = $params['form_data']['present_post_office'];
            $customer_data['present_post_code'] = $params['form_data']['present_post_code'];
            $customer_data['present_police_station'] = $params['form_data']['present_police_station'];
            $customer_data['present_sub_district'] = $params['form_data']['present_sub_district'];
            $customer_data['present_district'] = $params['form_data']['present_district'];
            $customer_data['present_division'] = $params['form_data']['present_division'];
            $customer_data['permanent_flat'] = $params['form_data']['permanent_flat'];
            $customer_data['permanent_house'] = $params['form_data']['permanent_house'];
            $customer_data['permanent_road'] = $params['form_data']['permanent_road'];
            $customer_data['permanent_village'] = $params['form_data']['permanent_village'];
            $customer_data['permanent_union'] = $params['form_data']['permanent_union'];
            $customer_data['permanent_ward'] = $params['form_data']['permanent_ward'];
            $customer_data['permanent_post_office'] = $params['form_data']['permanent_post_office'];
            $customer_data['permanent_post_code'] = $params['form_data']['permanent_post_code'];
            $customer_data['permanent_police_station'] = $params['form_data']['permanent_police_station'];
            $customer_data['permanent_sub_district'] = $params['form_data']['permanent_sub_district'];
            $customer_data['permanent_district'] = $params['form_data']['permanent_district'];
            $customer_data['permanent_division'] = $params['form_data']['permanent_division'];
            $customer_data['last_visited_country'] = $params['form_data']['last_visited_country'];
            $customer_data['customer_status'] = 'active';
            $customer_data['customer_type'] = 'returnee';
            $customer_data['fk_staff_id'] = $params['form_data']['staff_id'];
            $customer_data['fk_branch_id'] = $params['form_data']['branch_id'];
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
                    $customerID = 'R-' . date('y-m-') . str_pad($totalCustomer, 6, "0", STR_PAD_LEFT);
                    $customerIDUpdate = $devdb->query("UPDATE dev_customers SET customer_id = '" . $customerID . "' WHERE pk_customer_id = '" . $ret['customer_insert']['success'] . "'");
                    if ($customerIDUpdate['success']) {
                        break;
                    }
                }
                $climate_data = array();
                $climate_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['climate_insert'] = $devdb->insert_update('dev_climate_change', $climate_data);
                $migration_data = array();
                $migration_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['migration_insert'] = $devdb->insert_update('dev_migrations', $migration_data);
                $immediate_support_data = array();
                $immediate_support_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['immediate_insert'] = $devdb->insert_update('dev_immediate_supports', $immediate_support_data);
                $cooperation_data = array();
                $cooperation_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['cooperation_insert'] = $devdb->insert_update('dev_cooperations', $cooperation_data);
                $customer_health_data = array();
                $customer_health_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['health_insert'] = $devdb->insert_update('dev_customer_health', $customer_health_data);
                $economic_profile_data = array();
                $economic_profile_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['economic_insert'] = $devdb->insert_update('dev_economic_profile', $economic_profile_data);
                $skill_data = array();
                $skill_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['skill_insert'] = $devdb->insert_update('dev_customer_skills', $skill_data);
                $psycho_data = array();
                $psycho_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['psycho_insert'] = $devdb->insert_update('dev_psycho_evaluations', $psycho_data);
                $social_data = array();
                $social_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['social_insert'] = $devdb->insert_update('dev_social_evaluations', $social_data);
                $economic_data = array();
                $economic_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['economic_insert'] = $devdb->insert_update('dev_economic_evaluations', $economic_data);
                $reintegration_data = array();
                $reintegration_data['fk_customer_id'] = $ret['customer_insert']['success'];
                $ret['reintegration_insert'] = $devdb->insert_update('dev_reintegration_plan', $reintegration_data);
            }
            $climate_data = array();
            $climate_data['climate_effect'] = $params['form_data']['climate_effect'];
            if ($params['form_data']['new_disaster'] == NULL) {
                $data_type = $params['form_data']['natural_disaster'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $climate_data['natural_disaster'] = $data_types;
            } elseif ($params['form_data']['natural_disaster'] == NULL) {
                $climate_data['natural_disaster'] = $params['form_data']['new_disaster'];
            } elseif ($params['form_data']['natural_disaster'] != NULL && $params['form_data']['new_disaster'] != NULL) {
                $data_type = $params['form_data']['natural_disaster'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $climate_data['natural_disaster'] = $params['form_data']['new_disaster'] . ',' . $data_types;
            }

            /* Put Relation */
            $climate_data_array = explode(',', $climate_data['natural_disaster']);
            if ($is_update) {
                $devdb->query("DELETE FROM dev_lookup_relation WHERE table_name = 'dev_climate_change' AND column_name = 'natural_disaster' AND fk_output_id = '$is_update'");
                foreach ($climate_data_array as $value) {
                    $climate_data_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_climate_change','natural_disaster','$is_update')";
                    $devdb->query($climate_data_relation);
                }
            } else {
                foreach ($climate_data_array as $value) {
                    $climate_data_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_climate_change','natural_disaster','" . $ret['customer_insert']['success'] . "')";
                    $devdb->query($climate_data_relation);
                }
            }
            if ($climate_data['climate_effect'] == 'no') {
                $devdb->query("DELETE FROM dev_lookup_relation WHERE table_name = 'dev_climate_change' AND column_name = 'natural_disaster' AND fk_output_id = '$is_update'");
            }
            /* End Put Relation */

            if ($params['form_data']['new_economic_impact'] == NULL) {
                $data_type = $params['form_data']['economic_impacts'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $climate_data['economic_impacts'] = $data_types;
            } elseif ($params['form_data']['economic_impacts'] == NULL) {
                $climate_data['economic_impacts'] = $params['form_data']['new_economic_impact'];
            } elseif ($params['form_data']['economic_impacts'] != NULL && $params['form_data']['new_economic_impact'] != NULL) {
                $data_type = $params['form_data']['economic_impacts'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $climate_data['economic_impacts'] = $params['form_data']['new_economic_impact'] . ',' . $data_types;
            }
            $climate_data['financial_losses'] = $params['form_data']['financial_losses'];
            if ($params['form_data']['new_social_impact'] == NULL) {
                $data_type = $params['form_data']['social_impacts'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $climate_data['social_impacts'] = $data_types;
            } elseif ($params['form_data']['social_impacts'] == NULL) {
                $climate_data['social_impacts'] = $params['form_data']['new_social_impact'];
            } elseif ($params['form_data']['social_impacts'] != NULL && $params['form_data']['new_social_impact'] != NULL) {
                $data_type = $params['form_data']['social_impacts'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $climate_data['social_impacts'] = $params['form_data']['new_social_impact'] . ',' . $data_types;
            }
            $climate_data['is_climate_migration'] = $params['form_data']['is_climate_migration'];
            if ($is_update) {
                $ret['climate_update'] = $devdb->insert_update('dev_climate_change', $climate_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['climate_new_insert'] = $devdb->insert_update('dev_climate_change', $climate_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }
            $migration_data = array();
            $migration_data['migration_status'] = $params['form_data']['migration_status'];
            $migration_data['is_cheated'] = $params['form_data']['is_cheated'];
            $migration_data['is_money_deducted'] = $params['form_data']['is_money_deducted'];
            $migration_data['is_movement_limitation'] = $params['form_data']['is_movement_limitation'];
            $migration_data['is_kept_document'] = $params['form_data']['is_kept_document'];
            $migration_data['migration_experience'] = $params['form_data']['migration_experience'];
            $migration_data['left_port'] = $params['form_data']['left_port'];
            $migration_data['preferred_country'] = $params['form_data']['preferred_country'];
            $migration_data['access_path'] = $params['form_data']['access_path'];

            $customer_id = $is_update ? $is_update : $ret['customer_insert']['success'];
            $check_duplicate = $devdb->query("SELECT pk_mapping_id FROM dev_migration_mapping WHERE fk_customer_id = '$customer_id' AND route_name = '" . $migration_data['access_path'] . "' AND destination_country = '" . $customer_data['last_visited_country'] . "'");

            if (!$check_duplicate) {
                $devdb->query("INSERT INTO dev_migration_mapping (fk_customer_id, route_name, destination_country) VALUES ('$customer_id','" . $migration_data['access_path'] . "','" . $customer_data['last_visited_country'] . "')");
            }

            if ($params['form_data']['new_transport'] == NULL) {
                $data_type = $params['form_data']['transport_modes'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['transport_modes'] = $data_types;
            } elseif ($params['form_data']['transport_modes'] == NULL) {
                $migration_data['transport_modes'] = $params['form_data']['new_transport'];
            } elseif ($params['form_data']['transport_modes'] != NULL && $params['form_data']['new_transport'] != NULL) {
                $data_type = $params['form_data']['transport_modes'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['transport_modes'] = $params['form_data']['new_transport'] . ',' . $data_types;
            }
            $migration_data['migration_type'] = $params['form_data']['migration_type'];
            if ($params['form_data']['new_visa']) {
                $migration_data['visa_type'] = $params['form_data']['new_visa'];
            } else {
                $migration_data['visa_type'] = $params['form_data']['visa_type'];
            }
            $migration_data['departure_date'] = date('Y-m-d', strtotime($params['form_data']['departure_date']));
            $migration_data['return_date'] = date('Y-m-d', strtotime($params['form_data']['return_date']));
            $migration_data['migration_duration'] = $params['form_data']['migration_duration'];
            if ($params['form_data']['new_migration_media'] == NULL) {
                $data_type = $params['form_data']['migration_medias'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['migration_medias'] = $data_types;
            } elseif ($params['form_data']['migration_medias'] == NULL) {
                $migration_data['migration_medias'] = $params['form_data']['new_migration_media'];
            } elseif ($params['form_data']['migration_medias'] != NULL && $params['form_data']['new_migration_media'] != NULL) {
                $data_type = $params['form_data']['migration_medias'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['migration_medias'] = $params['form_data']['new_migration_media'] . ',' . $data_types;
            }

            /* Put Relation */
            $migration_array = explode(',', $migration_data['migration_medias']);
            if ($is_update) {
                $devdb->query("DELETE FROM dev_lookup_relation WHERE table_name = 'dev_migrations' AND column_name = 'natural_disaster' AND fk_output_id = '$is_update'");
                foreach ($migration_array as $value) {
                    $migration_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_migrations','migration_medias','$is_update')";
                    $devdb->query($migration_relation);
                }
            } else {
                foreach ($migration_array as $value) {
                    $migration_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_migrations','migration_medias','" . $ret['customer_insert']['success'] . "')";
                    $devdb->query($migration_relation);
                }
            }
            /* End Put Relation */

            $migration_data['migration_cost'] = $params['form_data']['migration_cost'];
            $migration_data['agency_payment'] = $params['form_data']['agency_payment'];
            $migration_data['migration_occupation'] = $params['form_data']['migration_occupation'];
            if ($params['form_data']['new_leave_reason'] == NULL) {
                $data_type = $params['form_data']['destination_country_leave_reason'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['destination_country_leave_reason'] = $data_types;
            } elseif ($params['form_data']['destination_country_leave_reason'] == NULL) {
                $migration_data['destination_country_leave_reason'] = $params['form_data']['new_leave_reason'];
            } elseif ($params['form_data']['destination_country_leave_reason'] != NULL && $params['form_data']['new_leave_reason'] != NULL) {
                $data_type = $params['form_data']['destination_country_leave_reason'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['destination_country_leave_reason'] = $params['form_data']['new_leave_reason'] . ',' . $data_types;
            }
            $migration_data['earned_money'] = $params['form_data']['earned_money'];
            $migration_data['sent_money'] = $params['form_data']['sent_money'];
            if ($params['form_data']['new_spent'] == NULL) {
                $data_type = $params['form_data']['spent_types'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['spent_types'] = $data_types;
            } elseif ($params['form_data']['spent_types'] == NULL) {
                $migration_data['spent_types'] = $params['form_data']['new_spent'];
            } elseif ($params['form_data']['spent_types'] != NULL && $params['form_data']['new_spent'] != NULL) {
                $data_type = $params['form_data']['spent_types'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $migration_data['spent_types'] = $params['form_data']['new_spent'] . ',' . $data_types;
            }

            /* Put Relation */
            $spent_array = explode(',', $migration_data['spent_types']);
            if ($is_update) {
                $devdb->query("DELETE FROM dev_lookup_relation WHERE table_name = 'dev_migrations' AND column_name = 'spent_types' AND fk_output_id = '$is_update'");
                foreach ($spent_array as $value) {
                    $spent_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_migrations','spent_types','$is_update')";
                    $devdb->query($spent_relation);
                }
            } else {
                foreach ($spent_array as $value) {
                    $spent_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_migrations','spent_types','" . $ret['customer_insert']['success'] . "')";
                    $devdb->query($spent_relation);
                }
            }
            /* End Put Relation */

            if ($is_update) {
                $ret['migration_update'] = $devdb->insert_update('dev_migrations', $migration_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['migration_new_insert'] = $devdb->insert_update('dev_migrations', $migration_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }
            $immediate_support_data = array();
            if ($params['form_data']['new_support'] == NULL) {
                $data_type = $params['form_data']['immediate_support'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $immediate_support_data['immediate_support'] = $data_types;
            } elseif ($params['form_data']['immediate_support'] == NULL) {
                $immediate_support_data['immediate_support'] = $params['form_data']['new_support'];
            } elseif ($params['form_data']['immediate_support'] != NULL && $params['form_data']['new_support'] != NULL) {
                $data_type = $params['form_data']['immediate_support'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $immediate_support_data['immediate_support'] = $params['form_data']['new_support'] . ',' . $data_types;
            }

            /* Put Relation */
            $immediate_support_array = explode(',', $immediate_support_data['immediate_support']);
            if ($is_update) {
                $devdb->query("DELETE FROM dev_lookup_relation WHERE table_name = 'dev_immediate_supports' AND column_name = 'immediate_support' AND fk_output_id = '$is_update'");
                foreach ($immediate_support_array as $value) {
                    $immediate_support_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_immediate_supports','immediate_support','$is_update')";
                    $devdb->query($immediate_support_relation);
                }
            } else {
                foreach ($immediate_support_array as $value) {
                    $immediate_support_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_immediate_supports','immediate_support','" . $ret['customer_insert']['success'] . "')";
                    $devdb->query($immediate_support_relation);
                }
            }
            /* End Put Relation */

            if ($is_update) {
                $ret['immediate_update'] = $devdb->insert_update('dev_immediate_supports', $immediate_support_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['migration_new_insert'] = $devdb->insert_update('dev_immediate_supports', $immediate_support_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }
            $cooperation_data = array();
            $cooperation_data['is_cooperated'] = $params['form_data']['is_cooperated'];
            $cooperation_data['organization_name'] = $params['form_data']['organization_name'];
            if ($params['form_data']['new_cooperation']) {
                $cooperation_data['cooperation_type'] = $params['form_data']['new_cooperation'];
            } else {
                $cooperation_data['cooperation_type'] = $params['form_data']['cooperation_type'];
            }
            $cooperation_data['is_remigration_interest'] = $params['form_data']['is_remigration_interest'];
            if ($is_update) {
                $ret['cooperation_update'] = $devdb->insert_update('dev_cooperations', $cooperation_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['cooperation_insert'] = $devdb->insert_update('dev_cooperations', $cooperation_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
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
            $customer_health_data['need_psychosocial_support'] = $params['form_data']['need_psychosocial_support'];
            if ($is_update) {
                $ret['health_update'] = $devdb->insert_update('dev_customer_health', $customer_health_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['health_new_insert'] = $devdb->insert_update('dev_customer_health', $customer_health_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }
            $economic_profile_data = array();
            $economic_profile_data['economic_condition'] = $params['form_data']['economic_condition'];
            $economic_profile_data['pre_occupation'] = $params['form_data']['pre_occupation'];
            $economic_profile_data['present_occupation'] = $params['form_data']['present_occupation'];
            $economic_profile_data['present_income'] = $params['form_data']['present_income'];
            $economic_profile_data['male_household_member'] = $params['form_data']['male_household_member'];
            $economic_profile_data['female_household_member'] = $params['form_data']['female_household_member'];
            $economic_profile_data['total_member'] = $params['form_data']['total_member'];
            $economic_profile_data['total_dependent_member'] = $params['form_data']['total_dependent_member'];
            $economic_profile_data['male_earning_member'] = $params['form_data']['male_earning_member'];
            $economic_profile_data['female_earning_member'] = $params['form_data']['female_earning_member'];
            $economic_profile_data['total_earner'] = $params['form_data']['total_earner'];
            $economic_profile_data['household_income'] = $params['form_data']['household_income'];
            $economic_profile_data['household_expenditure'] = $params['form_data']['household_expenditure'];
            $economic_profile_data['personal_savings'] = $params['form_data']['personal_savings'];
            $economic_profile_data['personal_debt'] = $params['form_data']['personal_debt'];
            $economic_profile_data['remittance_expend'] = $params['form_data']['remittance_expend'];
            if ($params['form_data']['new_loan_source'] == NULL) {
                $loan_source = $params['form_data']['loan_sources'];
                $loan_sources = is_array($loan_source) ? implode(',', $loan_source) : '';
                $economic_profile_data['loan_sources'] = $loan_sources;
            } elseif ($params['form_data']['loan_sources'] == NULL) {
                $economic_profile_data['loan_sources'] = $params['form_data']['new_loan_source'];
            } elseif ($params['form_data']['loan_sources'] != NULL && $params['form_data']['new_loan_source'] != NULL) {
                $loan_source = $params['form_data']['loan_sources'];
                $loan_sources = is_array($loan_source) ? implode(',', $loan_source) : '';
                $economic_profile_data['loan_sources'] = $params['form_data']['new_loan_source'] . ',' . $loan_sources;
            }
            $economic_profile_data['have_mortgages'] = $params['form_data']['have_mortgages'];
            if ($params['form_data']['have_mortgages'] == 'yes') {
                $economic_profile_data['mortgage_name'] = $params['form_data']['mortgage_name'];
                $economic_profile_data['mortgage_value'] = $params['form_data']['mortgage_value'];
            }
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
            $skill_data['is_certification_required'] = $params['form_data']['is_certification_required'];
            /* Certification Skills */
            if ($params['form_data']['new_technical'] == NULL) {
                $data_type = $params['form_data']['technical_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $technical_skills = $data_types;
            } elseif ($params['form_data']['technical_skills'] == NULL) {
                $technical_skills = $params['form_data']['new_technical'];
            } elseif ($params['form_data']['technical_skills'] != NULL && $params['form_data']['new_technical'] != NULL) {
                $data_type = $params['form_data']['technical_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $technical_skills = $params['form_data']['new_technical'] . ',' . $data_types;
            }
            if ($params['form_data']['new_non_technical'] == NULL) {
                $data_type = $params['form_data']['non_technical_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $non_technical_skills = $data_types;
            } elseif ($params['form_data']['non_technical_skills'] == NULL) {
                $non_technical_skills = $params['form_data']['new_non_technical'];
            } elseif ($params['form_data']['non_technical_skills'] != NULL && $params['form_data']['new_non_technical'] != NULL) {
                $data_type = $params['form_data']['non_technical_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $non_technical_skills = $params['form_data']['new_non_technical'] . ',' . $data_types;
            }
            if ($params['form_data']['new_soft'] == NULL) {
                $data_type = $params['form_data']['soft_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $soft_skills = $data_types;
            } elseif ($params['form_data']['soft_skills'] == NULL) {
                $soft_skills = $params['form_data']['new_soft'];
            } elseif ($params['form_data']['soft_skills'] != NULL && $params['form_data']['new_soft'] != NULL) {
                $data_type = $params['form_data']['soft_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $soft_skills = $params['form_data']['new_soft'] . ',' . $data_types;
            }
            $skills = array();
            if ($technical_skills) {
                $skills['technical_skill'] = $technical_skills;
            }if ($non_technical_skills) {
                $skills['non_technical_skill'] = $non_technical_skills;
            }if ($soft_skills) {
                $skills['soft_skill'] = $soft_skills;
            }
            $skill_data['required_certification'] = implode(',', $skills);

            /* Put Relation */
            $skills_array = explode(',', $skill_data['required_certification']);
            if ($is_update) {
                $devdb->query("DELETE FROM dev_lookup_relation WHERE table_name = 'dev_customer_skills' AND column_name = 'required_certification' AND fk_output_id = '$is_update'");
                foreach ($skills_array as $value) {
                    $required_certification_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_customer_skills','required_certification','$is_update')";
                    $devdb->query($required_certification_relation);
                }
            } else {
                foreach ($skills_array as $value) {
                    $required_certification_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_customer_skills','required_certification','" . $ret['customer_insert']['success'] . "')";
                    $devdb->query($required_certification_relation);
                }
            }
            if ($skill_data['is_certification_required'] == 'no') {
                $devdb->query("DELETE FROM dev_lookup_relation WHERE table_name = 'dev_customer_skills' AND column_name = 'required_certification' AND fk_output_id = '$is_update'");
            }
            /* End Put Relation */

            /* End Certification Skills */

            $skill_data['have_earner_skill'] = $params['form_data']['have_earner_skill'];
            /* Income Earner Skills */
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
            if ($params['form_data']['new_non_have_technical'] == NULL) {
                $data_type = $params['form_data']['non_technical_have_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $have_non_technical_skills = $data_types;
            } elseif ($params['form_data']['non_technical_have_skills'] == NULL) {
                $have_non_technical_skills = $params['form_data']['new_non_have_technical'];
            } elseif ($params['form_data']['non_technical_have_skills'] != NULL && $params['form_data']['new_non_have_technical'] != NULL) {
                $data_type = $params['form_data']['non_technical_have_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $have_non_technical_skills = $params['form_data']['new_non_have_technical'] . ',' . $data_types;
            }
            if ($params['form_data']['new_have_soft'] == NULL) {
                $data_type = $params['form_data']['soft_have_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $have_soft_skills = $data_types;
            } elseif ($params['form_data']['soft_have_skills'] == NULL) {
                $have_soft_skills = $params['form_data']['new_have_soft'];
            } elseif ($params['form_data']['soft_have_skills'] != NULL && $params['form_data']['new_have_soft'] != NULL) {
                $data_type = $params['form_data']['soft_have_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $have_soft_skills = $params['form_data']['new_have_soft'] . ',' . $data_types;
            }
            $have_skills = array();
            if ($have_technical_skills) {
                $have_skills['technical'] = $have_technical_skills;
            }if ($have_non_technical_skills) {
                $have_skills['non_technical'] = $have_non_technical_skills;
            }if ($have_soft_skills) {
                $have_skills['soft'] = $have_soft_skills;
            }
            $skill_data['have_skills'] = implode(',', $have_skills);

            /* Put Relation */
            $have_skills_array = explode(',', $skill_data['have_skills']);
            if ($is_update) {
                $devdb->query("DELETE FROM dev_lookup_relation WHERE table_name = 'dev_customer_skills' AND column_name = 'have_skills' AND fk_output_id = '$is_update'");
                foreach ($have_skills_array as $value) {
                    $have_skills_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_customer_skills','have_skills','$is_update')";
                    $devdb->query($have_skills_relation);
                }
            } else {
                foreach ($have_skills_array as $value) {
                    $have_skills_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_customer_skills','have_skills','$is_update')";
                    $devdb->query($have_skills_relation);
                }
            }
            if ($skill_data['have_earner_skill'] == 'no') {
                $devdb->query("DELETE FROM dev_lookup_relation WHERE table_name = 'dev_customer_skills' AND column_name = 'have_skills' AND fk_output_id = '$is_update'");
            }
            /* End Put Relation */

            /* End Income Earner Skills */
            /* Skills Training */
            if ($params['form_data']['new_need_technical'] == NULL) {
                $data_type = $params['form_data']['technical_need_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $need_technical_skills = $data_types;
            } elseif ($params['form_data']['technical_need_skills'] == NULL) {
                $need_technical_skills = $params['form_data']['new_need_technical'];
            } elseif ($params['form_data']['technical_need_skills'] != NULL && $params['form_data']['new_need_technical'] != NULL) {
                $data_type = $params['form_data']['technical_need_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $need_technical_skills = $params['form_data']['new_need_technical'] . ',' . $data_types;
            }
            if ($params['form_data']['new_non_need_technical'] == NULL) {
                $data_type = $params['form_data']['non_technical_need_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $need_non_technical_skills = $data_types;
            } elseif ($params['form_data']['non_technical_need_skills'] == NULL) {
                $need_non_technical_skills = $params['form_data']['new_non_need_technical'];
            } elseif ($params['form_data']['non_technical_need_skills'] != NULL && $params['form_data']['new_non_need_technical'] != NULL) {
                $data_type = $params['form_data']['non_technical_need_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $need_non_technical_skills = $params['form_data']['new_non_need_technical'] . ',' . $data_types;
            }
            if ($params['form_data']['new_need_soft'] == NULL) {
                $data_type = $params['form_data']['soft_need_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $need_soft_skills = $data_types;
            } elseif ($params['form_data']['soft_need_skills'] == NULL) {
                $need_soft_skills = $params['form_data']['new_need_soft'];
            } elseif ($params['form_data']['soft_need_skills'] != NULL && $params['form_data']['new_need_soft'] != NULL) {
                $data_type = $params['form_data']['soft_need_skills'];
                $data_types = is_array($data_type) ? implode(',', $data_type) : '';
                $need_soft_skills = $params['form_data']['new_need_soft'] . ',' . $data_types;
            }
            $need_skills = array();
            if ($need_technical_skills) {
                $need_skills['need_technical'] = $need_technical_skills;
            }if ($need_non_technical_skills) {
                $need_skills['need_non_technical'] = $need_non_technical_skills;
            }if ($need_soft_skills) {
                $need_skills['need_soft'] = $need_soft_skills;
            }
            /* End Skills Training */
            $skill_data['need_skills'] = implode(',', $need_skills);

            /* Put Relation */
            $need_skills_array = explode(',', $skill_data['need_skills']);
            if ($is_update) {
                $devdb->query("DELETE FROM dev_lookup_relation WHERE table_name = 'dev_customer_skills' AND column_name = 'need_skills' AND fk_output_id = '$is_update'");
                foreach ($need_skills_array as $value) {
                    $required_certification_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_customer_skills','need_skills','$is_update')";
                    $devdb->query($required_certification_relation);
                }
            } else {
                foreach ($need_skills_array as $value) {
                    $required_certification_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_customer_skills','need_skills','" . $ret['customer_insert']['success'] . "')";
                    $devdb->query($required_certification_relation);
                }
            }
            /* End Put Relation */

            if ($is_update) {
                $ret['skill_update'] = $devdb->insert_update('dev_customer_skills', $skill_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['skill_new_insert'] = $devdb->insert_update('dev_customer_skills', $skill_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }
            $psycho_data = array();
            $psycho_data['entry_date'] = date('Y-m-d');
            $psycho_data['sleepwell_night'] = $params['form_data']['sleepwell_night'];
            $psycho_data['happy_family'] = $params['form_data']['happy_family'];
            $psycho_data['enjoy_life'] = $params['form_data']['enjoy_life'];
            $psycho_data['visit_neighbors'] = $params['form_data']['visit_neighbors'];
            $psycho_data['mental_health'] = $params['form_data']['mental_health'];
            $psycho_data['family_tension'] = $params['form_data']['family_tension'];
            $psycho_data['secure_feeling'] = $params['form_data']['secure_feeling'];
            $psycho_data['feeling_free'] = $params['form_data']['feeling_free'];
            $psycho_data['family_behave'] = $params['form_data']['family_behave'];
            $psycho_data['positive_impact'] = $params['form_data']['positive_impact'];
            $psycho_data['evaluated_score'] = @($psycho_data['sleepwell_night'] + $psycho_data['happy_family'] + $psycho_data['enjoy_life'] + $psycho_data['visit_neighbors'] + $psycho_data['mental_health'] + $psycho_data['family_tension'] + $psycho_data['secure_feeling'] + $psycho_data['feeling_free'] + $psycho_data['family_behave'] + $psycho_data['positive_impact']);
            if ($psycho_data['evaluated_score'] >= 35) {
                $psycho_data['review_remarks'] = 'Successfully Reintegrated';
            } else {
                $psycho_data['review_remarks'] = 'Not Reintegrated';
            }
            if ($is_update) {
                $ret['psycho_update'] = $devdb->insert_update('dev_psycho_evaluations', $psycho_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['psycho_new_insert'] = $devdb->insert_update('dev_psycho_evaluations', $psycho_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }
            $social_data = array();
            $social_data['entry_date'] = date('Y-m-d');
            $social_data['government_programme'] = $params['form_data']['government_programme'];
            $social_data['public_services'] = $params['form_data']['public_services'];
            $social_data['access_social'] = $params['form_data']['access_social'];
            $social_data['family_treat'] = $params['form_data']['family_treat'];
            $social_data['friends_treat'] = $params['form_data']['friends_treat'];
            $social_data['relatives_treat'] = $params['form_data']['relatives_treat'];
            $social_data['community_treat'] = $params['form_data']['community_treat'];
            $social_data['important_family'] = $params['form_data']['important_family'];
            $social_data['adapted_community'] = $params['form_data']['adapted_community'];
            $social_data['pleased_society'] = $params['form_data']['pleased_society'];
            $social_data['evaluated_score'] = @($social_data['government_programme'] + $social_data['public_services'] + $social_data['access_social'] + $social_data['family_treat'] + $social_data['friends_treat'] + $social_data['relatives_treat'] + $social_data['community_treat'] + $social_data['important_family'] + $social_data['adapted_community'] + $social_data['pleased_society']);
            if ($social_data['evaluated_score'] >= 35) {
                $social_data['review_remarks'] = 'Successfully Reintegrated';
            } else {
                $social_data['review_remarks'] = 'Not Reintegrated';
            }
            if ($is_update) {
                $ret['social_update'] = $devdb->insert_update('dev_social_evaluations', $social_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['social_new_insert'] = $devdb->insert_update('dev_social_evaluations', $social_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }
            $economic_data = array();
            $economic_data['entry_date'] = date('Y-m-d');
            $economic_data['happy_income'] = $params['form_data']['happy_income'];
            $economic_data['food_security'] = $params['form_data']['food_security'];
            $economic_data['work_support'] = $params['form_data']['work_support'];
            $economic_data['job_support'] = $params['form_data']['job_support'];
            $economic_data['satisfied_economic'] = $params['form_data']['satisfied_economic'];
            $economic_data['debt_situation'] = $params['form_data']['debt_situation'];
            $economic_data['happy_savings'] = $params['form_data']['happy_savings'];
            $economic_data['satisfied_family'] = $params['form_data']['satisfied_family'];
            $economic_data['peaceful_economic'] = $params['form_data']['peaceful_economic'];
            $economic_data['neighbor_respect'] = $params['form_data']['neighbor_respect'];
            $economic_data['evaluated_score'] = @($economic_data['happy_income'] + $economic_data['food_security'] + $economic_data['work_support'] + $economic_data['job_support'] + $economic_data['satisfied_economic'] + $economic_data['debt_situation'] + $economic_data['happy_savings'] + $economic_data['satisfied_family'] + $economic_data['peaceful_economic'] + $economic_data['neighbor_respect']);
            if ($economic_data['evaluated_score'] >= 35) {
                $economic_data['review_remarks'] = 'Successfully Reintegrated';
            } else {
                $economic_data['review_remarks'] = 'Not Reintegrated';
            }
            if ($is_update) {
                $ret['economic_update'] = $devdb->insert_update('dev_economic_evaluations', $economic_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['economic_new_insert'] = $devdb->insert_update('dev_economic_evaluations', $economic_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }

            $reintegration_data = array();
            $reintegration_data['reintegration_plan'] = is_array($params['form_data']['reintegration_plan']) ? implode(',', $params['form_data']['reintegration_plan']) : '';

            /* Put Relation */
            $reintegration_array = explode(',', $reintegration_data['reintegration_plan']);
            if ($is_update) {
                $devdb->query("DELETE FROM dev_lookup_relation WHERE table_name = 'dev_reintegration_plan' AND column_name = 'reintegration_plan' AND fk_output_id = '$is_update'");
                foreach ($reintegration_array as $value) {
                    $reintegration_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_reintegration_plan','reintegration_plan','$is_update')";
                    $devdb->query($reintegration_relation);
                }
            } else {
                foreach ($reintegration_array as $value) {
                    $reintegration_relation = "INSERT INTO dev_lookup_relation (lookup_value,table_name,column_name,fk_output_id) VALUES ('$value','dev_reintegration_plan','reintegration_plan','" . $ret['customer_insert']['success'] . "')";
                    $devdb->query($reintegration_relation);
                }
            }
            /* End Put Relation */

            if ($is_update) {
                $ret['reintegration_update'] = $devdb->insert_update('dev_reintegration_plan', $reintegration_data, " fk_customer_id = '" . $is_update . "'");
            } else {
                $ret['reintegration_new_insert'] = $devdb->insert_update('dev_reintegration_plan', $reintegration_data, " fk_customer_id = '" . $ret['customer_insert']['success'] . "'");
            }

            if ($ret['customer_update'] || $ret['customer_insert']) {
                //Processing migration history
                $totalMigrationDays = 0;
                if ($params['form_data']['eachMigrationHistory']) {
                    foreach ($params['form_data']['eachMigrationHistory'] as $eachHistory) {
                        $entryDate = new DateTime($eachHistory['entry_date']);
                        $exitDate = new DateTime($eachHistory['exit_date']);
                        $diff = $entryDate->diff($exitDate);
                        $totalMonths = $diff->m + ($diff->y * 12);
                        $totalMigrationDays += $diff->days;

                        if ($eachHistory['id']) {
                            $insertData = array(
                                'fk_country_id' => $eachHistory['country'],
                                'entry_date' => $eachHistory['entry_date'],
                                'exit_date' => $eachHistory['exit_date'],
                                'duration_days' => $diff->d,
                                'duration_month' => $diff->m,
                                'duration_year' => $diff->y,
                                'duration_total_months' => $totalMonths,
                                'duration_total_days' => $diff->days,
                            );
                            $devdb->insert_update('dev_customer_migrations', $insertData, " pk_migration_id = '" . $eachHistory['id'] . "'");
                        } else {
                            $insertData = array(
                                'fk_customer_id' => $is_update ? $is_update : $ret['customer_insert']['success'],
                                'fk_country_id' => $eachHistory['country'],
                                'entry_date' => $eachHistory['entry_date'],
                                'exit_date' => $eachHistory['exit_date'],
                                'duration_days' => $diff->d,
                                'duration_month' => $diff->m,
                                'duration_year' => $diff->y,
                                'duration_total_months' => $totalMonths,
                                'duration_total_days' => $diff->days,
                            );
                            $devdb->insert_update('dev_customer_migrations', $insertData);
                        }
                    }
                }
                $this->goThroughMigrationHistory($is_update ? $is_update : $ret['customer_insert']['success']);

                if ($params['form_data']['new_gender']) {
                    $data = array(
                        'lookup_group' => 'gender',
                        'lookup_value' => $params['form_data']['new_gender'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_religion']) {
                    $data = array(
                        'lookup_group' => 'religion',
                        'lookup_value' => $params['form_data']['new_religion'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_qualification']) {
                    $data = array(
                        'lookup_group' => 'educational_qualification',
                        'lookup_value' => $params['form_data']['new_qualification'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_disaster']) {
                    $data = array(
                        'lookup_group' => 'natural_disaster',
                        'lookup_value' => $params['form_data']['new_disaster'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_economic_impact']) {
                    $data = array(
                        'lookup_group' => 'economic_impacts',
                        'lookup_value' => $params['form_data']['new_economic_impact'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_social_impact']) {
                    $data = array(
                        'lookup_group' => 'social_impacts',
                        'lookup_value' => $params['form_data']['new_social_impact'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_transport']) {
                    $data = array(
                        'lookup_group' => 'transport_modes',
                        'lookup_value' => $params['form_data']['new_transport'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_visa']) {
                    $data = array(
                        'lookup_group' => 'visa_type',
                        'lookup_value' => $params['form_data']['new_visa'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_migration_media']) {
                    $data = array(
                        'lookup_group' => 'migration_medias',
                        'lookup_value' => $params['form_data']['new_migration_media'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_leave_reason']) {
                    $data = array(
                        'lookup_group' => 'destination_country_leave_reason',
                        'lookup_value' => $params['form_data']['new_leave_reason'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_spent']) {
                    $data = array(
                        'lookup_group' => 'spent_types',
                        'lookup_value' => $params['form_data']['new_spent'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_support']) {
                    $data = array(
                        'lookup_group' => 'immediate_support',
                        'lookup_value' => $params['form_data']['new_support'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_cooperation']) {
                    $data = array(
                        'lookup_group' => 'cooperation_type',
                        'lookup_value' => $params['form_data']['new_cooperation'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_disease_type']) {
                    $data = array(
                        'lookup_group' => 'disease_type',
                        'lookup_value' => $params['form_data']['new_disease_type'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_loan_source']) {
                    $data = array(
                        'lookup_group' => 'loan_sources',
                        'lookup_value' => $params['form_data']['new_loan_source'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_ownership']) {
                    $data = array(
                        'lookup_group' => 'current_residence_ownership',
                        'lookup_value' => $params['form_data']['new_ownership'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_residence']) {
                    $data = array(
                        'lookup_group' => 'current_residence_type',
                        'lookup_value' => $params['form_data']['new_residence'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_technical']) {
                    $data = array(
                        'lookup_group' => 'technical_skills',
                        'lookup_value' => $params['form_data']['new_technical'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_non_technical']) {
                    $data = array(
                        'lookup_group' => 'non_technical_skills',
                        'lookup_value' => $params['form_data']['new_non_technical'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_soft']) {
                    $data = array(
                        'lookup_group' => 'soft_skills',
                        'lookup_value' => $params['form_data']['new_soft'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_have_technical']) {
                    $data = array(
                        'lookup_group' => 'technical_skills',
                        'lookup_value' => $params['form_data']['new_have_technical'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_non_have_technical']) {
                    $data = array(
                        'lookup_group' => 'non_technical_skills',
                        'lookup_value' => $params['form_data']['new_non_have_technical'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_have_soft']) {
                    $data = array(
                        'lookup_group' => 'soft_skills',
                        'lookup_value' => $params['form_data']['new_have_soft'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_need_technical']) {
                    $data = array(
                        'lookup_group' => 'technical_skills',
                        'lookup_value' => $params['form_data']['new_need_technical'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_non_need_technical']) {
                    $data = array(
                        'lookup_group' => 'non_technical_skills',
                        'lookup_value' => $params['form_data']['new_non_need_technical'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }

                if ($params['form_data']['new_need_soft']) {
                    $data = array(
                        'lookup_group' => 'soft_skills',
                        'lookup_value' => $params['form_data']['new_need_soft'],
                    );
                    $devdb->insert_update('dev_lookups', $data);
                }
            }
        }
        return $ret;
    }

}

new dev_customer_management();
