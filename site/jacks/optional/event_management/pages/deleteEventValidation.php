<?php

global $devdb;
$id = $_GET['id'] ? $_GET['id'] : null;
$event_id = $_GET['event_id'] ? $_GET['event_id'] : null;

if (!has_permission('delete_event_validation')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_event_management/manage_event_validations'));
    exit();
} else {
    $ret['event'] = $devdb->query("DELETE FROM dev_event_validations WHERE pk_validation_id = '" . $id . "'");
    
    if ($ret) {
        add_notification('Record Success', 'danger');
        header('location: ' . url('admin/dev_event_management/manage_event_validations?event_id='.$event_id));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_event_management/manage_event_validations?event_id='.$event_id));
        exit();
    }
}