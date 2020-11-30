<?php

global $devdb;
$training_id = $_GET['training_id'] ? $_GET['training_id'] : null;
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('delete_sharing_session')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('id', 'action')));
    exit();
}

if (!$id) {
    add_notification('No data to delete');
    header('location: ' . url('admin/dev_event_management/manage_sharing_session'));
    exit();
} else {
    $ret['sharing_session'] = $devdb->query("DELETE FROM dev_sharing_sessions WHERE pk_sharing_session_id = '" . $id . "'");

    if ($ret) {
        add_notification('Record Deleted', 'danger');
        header('location: ' . url('admin/dev_event_management/manage_sharing_session?training_id=' . $training_id));
        exit();
    } else {
        add_notification('No data to delete', 'danger');
        header('location: ' . url('admin/dev_event_management/manage_sharing_session?training_id=' . $training_id));
        exit();
    }
}