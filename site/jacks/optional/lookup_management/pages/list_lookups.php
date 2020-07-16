<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_lookups(array('lookup_id' => $edit, 'single' => true));
    if (!$pre_data) {
        add_notification('Invalid Data, No data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_lookup_group = $_GET['lookup_group'] ? $_GET['lookup_group'] : null;

$args = array(
    'lookup_group' => $filter_lookup_group,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_lookup_id',
        'order' => 'DESC'
    ),
);

$lookups = $this->get_lookups($args);
$pagination = pagination($lookups['total'], $per_page_items, $start);

$filterString = array();
if ($filter_lookup_group)
    $filterString[] = 'Lookup Group: ' . $filter_lookup_group;

if ($_POST) {
    if (!checkPermission($edit, 'add_lookup', 'edit_lookup')) {
        add_notification('You don\'t have enough permission.', 'error');
        header('Location:' . build_url(NULL, array('edit', 'action')));
        exit();
    }

    $data = array();
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_lookup($data);

    if ($ret['success']) {
        $msg = "Information of lookup has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_lookup_management/manage_lookups'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

doAction('render_start');
?>
<div class="page-header">
    <h1>All Lookups</h1>
</div>
<?php
ob_start();
echo formProcessor::form_elements('lookup_group', 'lookup_group', array(
    'width' => 4, 'type' => 'text', 'label' => 'Lookup Group',
        ), $filter_lookup_group);
?>
<?php
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-primary table-responsive">
            <?php if ($filterString): ?>
                <div class="table-header">
                    Filtered With: <?php echo implode(', ', $filterString) ?>
                </div>
            <?php endif; ?>
            <div class="table-header">
                <?php echo searchResultText($lookups['total'], $start, $per_page_items, count($lookups['data']), 'lookups') ?>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Group</th>
                        <th>Value</th>
                        <th class="tar action_column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lookups['data'] as $i => $lookup) {
                        ?>
                        <tr>
                            <td><?php echo $lookup['lookup_group'] ?></td>
                            <td><?php echo $lookup['lookup_value'] ?></td>
                            <td class="tar action_column">
                                <?php if (has_permission('edit_lookup')): ?>
                                    <div class="btn-toolbar">
                                        <?php
                                        echo linkButtonGenerator(array(
                                            'href' => build_url(array('action' => 'add_edit_lookup', 'edit' => $lookup['pk_lookup_id'])),
                                            'action' => 'edit',
                                            'icon' => 'icon_edit',
                                            'text' => 'Edit',
                                            'title' => 'Edit Lookup',
                                        ));
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="table-footer oh">
                <div class="pull-left">
                    <?php echo $pagination ?>
                </div>
            </div>
        </div>
    </div>
</div>