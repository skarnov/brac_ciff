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
        'col' => 'pk_training_id',
        'order' => 'DESC'
    ),
);

$trainings = $this->get_trainings($args);
$pagination = pagination($trainings['total'], $per_page_items, $start);

doAction('render_start');
ob_start();
?>
<div class="page-header">
    <h1>All Trainings</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_training',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Training',
                'title' => 'New Training',
            ));
            ?>
        </div>
    </div>
</div>
<div class="table-primary table-responsive">
    <div class="table-header">
        <?php echo searchResultText($trainings['total'], $start, $per_page_items, count($trainings['data']), 'trainings') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Branch</th>
                <th>Month</th>
                <th>Training Name</th>
                <th>Training Value</th>
                <th>Achievement Value</th>
                <th>Remark</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($trainings['data'] as $i => $training) {
                ?>
                <tr>
                    <td>
                        <?php if (has_permission('edit_training')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_training', 'edit' => $training['fk_customer_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Training',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_training')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $training['pk_training_id']),
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
                        text: 'Delete Only Profile',
                        value: 'deleteProfile'
                    },
                    {
                        text: 'Delete Profile With Case Management',
                        value: 'deleteProfileCase'
                    }],
                callback: function (result) {
                    if (result == 'deleteProfile') {
                        window.location.href = '?action=deleteProfile&id=' + logId;
                    }
                    if (result == 'deleteProfileCase') {
                        window.location.href = '?action=deleteProfileCase&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>