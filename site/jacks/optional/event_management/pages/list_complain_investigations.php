<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_gender = $_GET['gender'] ? $_GET['gender'] : null;
$filter_case_type = $_GET['type_case'] ? $_GET['type_case'] : null;
$filter_upazila = $_GET['upazila'] ? $_GET['upazila'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;


$args = array(
    'gender' => $filter_gender,
    'type_case' => $filter_case_type,
    'upazila' => $filter_upazila,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_complain_investigation_id',
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

$complain_investigations = $this->get_complain_investigations($args);
$pagination = pagination($complain_investigations['total'], $per_page_items, $start);

doAction('render_start');
?>
<div class="page-header">
    <div class="row">
        <div class="col-md-8">
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
        <div class="col-md-4">
            <div class="stat-row">
                <div class="stat-cell bg-warning">
                    <span class="text-bg"><?php echo $complain_investigations['total'] ?></span><br>
                    <span class="text-sm">Stored in Database</span>
                </div>
            </div>
            <div class="stat-row">
                <div class="stat-cell bg-warning padding-sm no-padding-t text-center">
                    <div id="stats-sparklines-2" class="stats-sparklines" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<div class="form-group col-sm-4">
    <label>Gender</label>
    <div class="select2-primary">
        <select class="form-control" name="gender">
            <option value="">Select One</option>
            <option value="male" <?php echo ('male' == $filter_gender) ? 'selected' : '' ?>>Men (>=18)</option>
            <option value="female" <?php echo ('female' == $filter_gender) ? 'selected' : '' ?>>Women (>=18)</option>
        </select>
    </div>
</div>
<div class="form-group col-sm-4">
    <label>Case Type</label>
    <div class="select2-primary">
        <select class="form-control" name="type_case">
            <option value="">Select One</option>
            <option value="Missing" <?php echo ('Missing' == $filter_case_type) ? 'selected' : '' ?>>Missing</option>
            <option value="Flee away with" <?php echo ('Flee away with' == $filter_case_type) ? 'selected' : '' ?>>Flee away with</option>
            <option value="Abduction" <?php echo ('Abduction' == $filter_case_type) ? 'selected' : '' ?>>Abduction</option>
        </select>
    </div>
</div>
<div class="form-group col-sm-2">
    <label>Division</label>
    <div class="select2-primary">
        <select class="form-control" id="filter_division" name="division" data-selected="<?php echo $filter_division ?>"></select>
    </div>
</div>
<div class="form-group col-sm-2">
    <label>District</label>
    <div class="select2-success">
        <select class="form-control" id="filter_district" name="district" data-selected="<?php echo $filter_district; ?>"></select>
    </div>
</div>
<div class="form-group col-sm-2">
    <label>Upazila</label>
    <div class="select2-success">
        <select class="form-control" id="filter_sub_district" name="sub_district" data-selected="<?php echo $filter_sub_district; ?>"></select>
    </div>
</div>
<div class="form-group col-sm-4">
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
<div class="form-group col-sm-4">
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
                <th>Gender</th>
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
                    <td>
                        <?php
                        if ($complain_investigation['gender'] == 'male' && $complain_investigation['age'] <= 17) {
                            echo 'Boy (<18)';
                        } else if ($complain_investigation['gender'] == 'male' && $complain_investigation['age'] > 17) {
                            echo 'Men (>=18)';
                        } else if ($complain_investigation['gender'] == 'female' && $complain_investigation['age'] <= 17) {
                            echo 'Girl (<18)';
                        } else if ($complain_investigation['gender'] == 'female' && $complain_investigation['age'] > 17) {
                            echo 'Women (>=18)';
                        }
                        ?>
                    </td>
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