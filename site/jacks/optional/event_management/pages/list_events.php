<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;

$args = array(
    'division' => $filter_division,
    'district' => $filter_district,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_event_id',
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

$events = $this->get_events($args);
$pagination = pagination($events['total'], $per_page_items, $start);

$filterString = array();
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_entry_start_date)
    $filterString[] = 'Start Date: ' . $filter_entry_start_date;
if ($filter_entry_end_date)
    $filterString[] = 'End Date: ' . $filter_entry_end_date;


doAction('render_start');
?>
<div class="page-header">
    <h1>All Events</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_event',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'Add Event',
                'title' => 'Add Event',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
echo formProcessor::form_elements('name', 'name', array(
    'width' => 3, 'type' => 'text', 'label' => 'Name',
        ), $filter_name);
?>
<div class="form-group col-sm-3">
    <label>Branch</label>
    <div class="select2-primary">
        <select class="form-control" name="branch_id"></select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Division</label>
    <div class="select2-primary">
        <select class="form-control" id="filter_division" name="division" data-selected="<?php echo $filter_division ?>"></select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>District</label>
    <div class="select2-success">
        <select class="form-control" id="filter_district" name="district" data-selected="<?php echo $filter_district; ?>"></select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Upazila</label>
    <div class="select2-success">
        <select class="form-control" id="filter_district" name="district" data-selected="<?php echo $filter_district; ?>"></select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Union</label>
    <div class="select2-success">
        <select class="form-control" id="filter_union" name="union" data-selected="<?php echo $filter_union; ?>"></select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Entry Start Date</label>
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
    <label>Entry End Date</label>
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
        <?php echo searchResultText($events['total'], $start, $per_page_items, count($events['data']), 'activities') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>District</th>
                <th>Upazila</th>
                <th>Submitted By</th>
                <th>Participant Number</th>
                <th>Event Validation Count</th>
                <th>Observation Score</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($events['data'] as $i => $event) {
                ?>
                <tr>
                    <td><?php echo $event['activity_name'] ?></td>
                    <td><?php echo date('d-m-Y', strtotime($event['event_start_date'])) ?></td>
                    <td><?php echo date('d-m-Y', strtotime($event['event_end_date'])) ?></td>
                    <td style="text-transform: capitalize"><?php echo $event['event_district']; ?></td>
                    <td style="text-transform: capitalize"><?php echo $event['event_upazila']; ?></td>
                    <td><?php echo $event['user_fullname']; ?></td>
                    <td><?php echo 'Boy: '.$event['participant_boy'].'<br><hr/>Girl: '.$event['participant_girl'].'<br><hr/>Men: '.$event['participant_male'].'<br><hr/>Women: '.$event['participant_female'] ?></td>
                    <td><?php echo $event['validation_count']; ?></td>
                    <td><?php echo $event['observation_score']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_event')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => url('admin/dev_event_management/manage_event_validations&event_id=' . $event['pk_event_id']),
                                    'action' => 'edit',
                                    'icon' => 'icon_add',
                                    'text' => 'Event Validation',
                                    'title' => 'Event Validation',
                                ));
                                ?>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_event', 'edit' => $event['pk_event_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Event',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_event')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $event['pk_event_id']),
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
    var BD_LOCATIONS = <?php echo getBDLocationJson(); ?>;
    init.push(function () {
        new bd_new_location_selector({
            'division': $('#filter_division'),
            'district': $('#filter_district'),
            'sub_district': $('#filter_sub_district'),
            'police_station': $('#filter_police_station'),
            'post_office': $('#filter_post_office'),
        });
    });
</script>
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
                        window.location.href = '?action=deleteEvent&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>