<?php

global $devdb;
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('delete_event_type')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_event_management/manage_event_types'));
    exit();
} else {
    $ret['event'] = $devdb->query("DELETE FROM dev_event_types WHERE pk_event_type_id = '" . $id . "'");

    if ($ret) {
        add_notification('Record Success', 'danger');
        header('location: ' . url('admin/dev_event_management/manage_event_types'));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_event_management/manage_event_types'));
        exit();
    }
}