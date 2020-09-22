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
        'col' => 'pk_complain_filed_id',
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

$complain_fileds = $this->get_complain_fileds($args);
$pagination = pagination($complain_fileds['total'], $per_page_items, $start);

doAction('render_start');
?>
<div class="page-header">
    <h1>All Complain Fileds</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_complain_filed',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Complain Filed',
                'title' => 'New Complain Filed',
            ));
            ?>
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
            <option value="male" <?php echo ('male' == $filter_gender) ? 'selected' : '' ?>>Male</option>
            <option value="female" <?php echo ('female' == $filter_gender) ? 'selected' : '' ?>>Female</option>
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
<div class="form-group col-sm-4">
    <label>Upazila</label>
    <div class="form-group">
        <select class="form-control" name="upazila">
            <option value="">Select One</option>
            <option value="Jashore Sadar" <?php echo $filter_upazila && $filter_upazila == 'Jashore Sadar' ? 'selected' : '' ?>>Jashore Sadar</option>
            <option value="Jhikargacha" <?php echo $filter_upazila && $filter_upazila == 'Jhikargacha' ? 'selected' : '' ?>>Jhikargacha</option>
            <option value="Sharsha" <?php echo $filter_upazila && $filter_upazila == 'Sharsha' ? 'selected' : '' ?>>Sharsha</option>
            <option value="Chougachha" <?php echo $filter_upazila && $filter_upazila == 'Chougachha' ? 'selected' : '' ?>>Chougachha</option>
            <option value="Manirampur" <?php echo $filter_upazila && $filter_upazila == 'Manirampur' ? 'selected' : '' ?>>Manirampur</option>
            <option value="Bagherpara" <?php echo $filter_upazila && $filter_upazila == 'Bagherpara' ? 'selected' : '' ?>>Bagherpara</option>
            <option value="Keshabpur" <?php echo $filter_upazila && $filter_upazila == 'Keshabpur' ? 'selected' : '' ?>>Keshabpur</option>
            <option value="Abhaynagar" <?php echo $filter_upazila && $filter_upazila == 'Abhaynagar' ? 'selected' : '' ?>>Abhaynagar</option>
        </select>
    </div>
</div>
<div class="form-group col-sm-4">
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
<div class="form-group col-sm-4">
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
        <?php echo searchResultText($complain_fileds['total'], $start, $per_page_items, count($complain_fileds['data']), 'complain fileds') ?>
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
            foreach ($complain_fileds['data'] as $i => $complain_filed) {
                ?>
                <tr>
                    <td><?php echo $complain_filed['case_id']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($complain_filed['complain_register_date'])) ?></td>
                    <td><?php echo $complain_filed['month']; ?></td>
                    <td><?php echo $complain_filed['police_station']; ?></td>
                    <td><?php echo $complain_filed['type_case']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_complain_filed')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_complain_filed', 'edit' => $complain_filed['pk_complain_filed_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Complain Filed',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_complain_filed')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $complain_filed['pk_complain_filed_id']),
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
                        window.location.href = '?action=deleteComplainFiled&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>