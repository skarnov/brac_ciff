<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_profession = $_GET['profession'] ? $_GET['profession'] : null;
$filter_training_name = $_GET['training_name'] ? $_GET['training_name'] : null;
$filter_workshop_name = $_GET['workshop_name'] ? $_GET['workshop_name'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;

$args = array(
    'profession' => $filter_profession,
    'training_name' => $filter_training_name,
    'workshop_name' => $filter_workshop_name,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_training_id',
        'order' => 'DESC'
    ),
);

if ($filter_entry_start_date && $filter_entry_start_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'entry_date' => array(
            'left' => date_to_db($filter_entry_start_date),
            'right' => date_to_db($filter_entry_end_date),
        ),
    );
}

$trainings = $this->get_trainings($args);
$pagination = pagination($trainings['total'], $per_page_items, $start);


$filterString = array();
if ($filter_profession)
    $filterString[] = 'Profession: ' . $filter_profession;
if ($filter_training_name)
    $filterString[] = 'Training Name: ' . $filter_training_name;
if ($filter_workshop_name)
    $filterString[] = 'Workshop Name: ' . $filter_workshop_name;
if ($filter_entry_start_date)
    $filterString[] = 'Start Date: ' . $filter_entry_start_date;
if ($filter_entry_end_date)
    $filterString[] = 'End Date: ' . $filter_entry_end_date;

doAction('render_start');
?>
<div class="page-header">
    <h1>All Training/Workshop</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_training',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Training/Workshop',
                'title' => 'New Training/Workshop',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<div class="form-group col-sm-3">
    <label>Start Date</label>
    <div class="input-group">
        <input id="startDate" type="text" class="form-control" name="entry_start_date" value="<?php echo $filter_entry_start_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('startDate');
        });
    </script>
</div>
<div class="form-group col-sm-3">
    <label>End Date</label>
    <div class="input-group">
        <input id="endDate" type="text" class="form-control" name="entry_end_date" value="<?php echo $filter_entry_end_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('endDate');
        });
    </script>
</div>
<?php
echo formProcessor::form_elements('name', 'profession', array(
    'width' => 3, 'type' => 'text', 'label' => 'Profession',
        ), $filter_profession);
echo formProcessor::form_elements('name', 'training_name', array(
    'width' => 3, 'type' => 'text', 'label' => 'Training Name',
        ), $filter_training_name);
echo formProcessor::form_elements('workshop_name', 'workshop_name', array(
    'width' => 3, 'type' => 'text', 'label' => 'Workshop Name',
        ), $filter_workshop_name);
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="table-primary table-responsive">
    <?php if ($filterString): ?>
        <div class="table-header">
            Filtered With: <?php echo implode(', ', $filterString) ?>
        </div>
    <?php endif; ?>
    <div class="table-header">
        <?php echo searchResultText($trainings['total'], $start, $per_page_items, count($trainings['data']), 'trainings') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Date</th>
                <th>Profession</th>
                <th>Training Name</th>
                <th>Participant Name</th>
                <th>Mobile</th>
                <th>Gender</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($trainings['data'] as $i => $training) {
                ?>
                <tr>
                    <td><?php echo date('d-m-Y', strtotime($training['date'])) ?></td>
                    <td><?php echo $training['profession']; ?></td>
                    <td><?php echo $training['training_name']; ?></td>
                    <td><?php echo $training['name']; ?></td>
                    <td><?php echo $training['mobile']; ?></td>
                    <td>
                        <?php
                        if ($training['gender'] == 'male') {
                            echo 'Men (>=18)';
                        } else {
                            echo 'Women (>=18)';
                        }
                        ?>
                    </td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_training')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => url('admin/dev_event_management/manage_sharing_session?training_id=' . $training['pk_training_id']),
                                    'action' => 'edit',
                                    'icon' => 'icon_add',
                                    'text' => 'Training/Workshop Validation',
                                    'title' => 'Training/Workshop Validation',
                                ));
                                ?>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_training', 'edit' => $training['pk_training_id'])),
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
                        text: 'Click To Confirm Delete',
                        value: 'delete'
                    }],
                callback: function (result) {
                    if (result == 'delete') {
                        window.location.href = '?action=deleteTraining&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>