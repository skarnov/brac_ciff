<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$args = array(
    'listing' => TRUE,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_complain_investigation_id',
        'order' => 'DESC'
    ),
);

$complain_investigations = $this->get_complain_investigations($args);
$pagination = pagination($complain_investigations['total'], $per_page_items, $start);

doAction('render_start');
ob_start();
?>
<div class="page-header">
    <h1>All Complain Investigations</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_complain_investigation',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Complain Investigation',
                'title' => 'New Complain Investigation',
            ));
            ?>
        </div>
    </div>
</div>
<div class="table-primary table-responsive">
    <div class="table-header">
        <?php echo searchResultText($complain_investigations['total'], $start, $per_page_items, count($complain_investigations['data']), 'complain investigations') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Case ID</th>
                <th>Date</th>
                <th>Month</th>
                <th>Police Station</th>
                <th>Case Type</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($complain_investigations['data'] as $i => $complain_investigation) {
                ?>
                <tr>
                    <td><?php echo $complain_investigation['case_id']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($complain_investigation['complain_register_date'])) ?></td>
                    <td><?php echo $complain_investigation['month']; ?></td>
                    <td><?php echo $complain_investigation['police_station']; ?></td>
                    <td><?php echo $complain_investigation['type_case']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_complain_investigation')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_complain_investigation', 'edit' => $complain_investigation['pk_complain_investigation_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Complain Investigation',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_complain_investigation')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $complain_investigation['pk_complain_investigation_id']),
                                    'classes' => 'delete_single_record'));
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
<script type="text/javascript">
    init.push(function () {
        $(document).on('click', '.delete_single_record', function () {
            var ths = $(this);
            var thisCell = ths.closest('td');
            var logId = ths.attr('data-id');        
            if (!logId)
                return false;

            show_button_overlay_working(thisCell);
            bootbox.prompt({
                title: 'Delete Record!',
                inputType: 'checkbox',
                inputOptions: [{
                        text: 'Click To Confirm Delete',
                        value: 'delete'
                    }],
                callback: function (result) {
                    if (result == 'delete') {
                        window.location.href = '?action=deleteComplainInvestigation&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>