<?php

global $devdb;
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('delete_returnee')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_customer_management/manage_returnee_migrants'));
    exit();
} else {
    $ret['customer'] = $devdb->query("DELETE FROM dev_customers WHERE pk_customer_id = '" . $id . "'");
    $ret['climate'] = $devdb->query("DELETE FROM dev_climate_change WHERE fk_customer_id = '" . $id . "'");
    $ret['migration'] = $devdb->query("DELETE FROM dev_migrations WHERE fk_customer_id = '" . $id . "'");
    $ret['immediate'] = $devdb->query("DELETE FROM dev_immediate_supports WHERE fk_customer_id = '" . $id . "'");
    $ret['cooperation'] = $devdb->query("DELETE FROM dev_cooperations WHERE fk_customer_id = '" . $id . "'");
    $ret['customer_health'] = $devdb->query("DELETE FROM dev_customer_health WHERE fk_customer_id = '" . $id . "'");
    $ret['economic_profile'] = $devdb->query("DELETE FROM dev_economic_profile WHERE fk_customer_id = '" . $id . "'");
    $ret['customer_skills'] = $devdb->query("DELETE FROM dev_customer_skills WHERE fk_customer_id = '" . $id . "'");
    $ret['psycho_evaluation'] = $devdb->query("DELETE FROM dev_psycho_evaluations WHERE fk_customer_id = '" . $id . "'");
    $ret['social_evaluation'] = $devdb->query("DELETE FROM dev_social_evaluations WHERE fk_customer_id = '" . $id . "'");
    $ret['economic_evaluation'] = $devdb->query("DELETE FROM dev_economic_evaluations WHERE fk_customer_id = '" . $id . "'");
    $ret['reintegration_plan'] = $devdb->query("DELETE FROM dev_reintegration_plan WHERE fk_customer_id = '" . $id . "'");
    if ($ret) {
        add_notification('Success', 'warning');
        header('location: ' . url('admin/dev_customer_management/manage_returnee_migrants'));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_customer_management/manage_returnee_migrants'));
        exit();
    }
}