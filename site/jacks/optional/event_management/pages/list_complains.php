<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_gender = $_GET['gender'] ? $_GET['gender'] : null;
$filter_upazila = $_GET['upazila'] ? $_GET['upazila'] : null;
$filter_service_recipient = $_GET['service_recipient'] ? $_GET['service_recipient'] : null;
$filter_service_seeking = $_GET['service_seeking'] ? $_GET['service_seeking'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;

$args = array(
    'gender' => $filter_gender,
    'upazila' => $filter_upazila,
    'type_recipient' => $filter_service_recipient,
    'type_service' => $filter_service_seeking,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_complain_id',
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

$complains = $this->get_complains($args);
$pagination = pagination($complains['total'], $per_page_items, $start);

doAction('render_start');
?>
<div class="page-header">
    <div class="row">
        <div class="col-md-8">
            <h1>All Community Services</h1>
            <div class="oh">
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => $myUrl . '?action=add_edit_complain',
                        'action' => 'add',
                        'icon' => 'icon_add',
                        'text' => 'New Community Service',
                        'title' => 'New Community Service',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-row">
                <div class="stat-cell bg-warning">
                    <span class="text-bg"><?php echo $complains['total'] ?></span><br>
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
<div class="form-group col-sm-2">
    <label>Union</label>
    <div class="select2-info">
        <select class="form-control" id="filter_union"></select>
    </div>
</div>
<div class="form-group col-sm-4">
    <label>Service Recipient</label>
    <div class="select2-primary">
        <select class="form-control" name="service_recipient">
            <option value="">Select One</option>
            <option value="victim" <?php echo ('victim' == $filter_service_recipient) ? 'selected' : '' ?>>Victim</option>
            <option value="family" <?php echo ('family' == $filter_service_recipient) ? 'selected' : '' ?>>Family</option>
            <option value="relative" <?php echo ('relative' == $filter_service_recipient) ? 'selected' : '' ?>>Relative</option>
            <option value="community_member" <?php echo ('community_member' == $filter_service_recipient) ? 'selected' : '' ?>>Community Member</option>
        </select>
    </div>
</div>
<div class="form-group col-sm-4">
    <label>Service Seeking</label>
    <div class="select2-primary">
        <select class="form-control" name="service_seeking">
            <option value="">Select One</option>
            <option value="Case filing support" <?php echo ('Case filing support' == $filter_service_seeking) ? 'selected' : '' ?>>Case filing support</option>
            <option value="Trafficking information" <?php echo ('Trafficking information' == $filter_service_seeking) ? 'selected' : '' ?>>Trafficking information</option>
            <option value="Safe migration information" <?php echo ('Safe migration information' == $filter_service_seeking) ? 'selected' : '' ?>>Safe migration information</option>
            <option value="Missing information" <?php echo ('Missing information' == $filter_service_seeking) ? 'selected' : '' ?>>Missing information</option>
            <option value="Rescue support" <?php echo ('Rescue support' == $filter_service_seeking) ? 'selected' : '' ?>>Rescue support</option>
            <option value="Dead body recover support" <?php echo ('Dead body recover support' == $filter_service_seeking) ? 'selected' : '' ?>>Dead body recover support</option>
            <option value="Claim compensation" <?php echo ('Claim compensation' == $filter_service_seeking) ? 'selected' : '' ?>>Claim compensation</option>
            <option value="Legal support" <?php echo ('Legal support' == $filter_service_seeking) ? 'selected' : '' ?>>Legal support</option>
            <option value="Project information" <?php echo ('Project information' == $filter_service_seeking) ? 'selected' : '' ?>>Project information</option>
            <option value="Training support" <?php echo ('Training support' == $filter_service_seeking) ? 'selected' : '' ?>>Training support</option>
            <option value="Loan support" <?php echo ('Loan support' == $filter_service_seeking) ? 'selected' : '' ?>>Loan support</option>
            <option value="Job placement" <?php echo ('Job placement' == $filter_service_seeking) ? 'selected' : '' ?>>Job placement</option>
            <option value="Others" <?php echo ('Others' == $filter_service_seeking) ? 'selected' : '' ?>>Others</option>
        </select>
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
    <div class="table-header">
        <?php echo searchResultText($complains['total'], $start, $per_page_items, count($complains['data']), 'Community Service') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Register Date</th>
                <th>Service Recipient</th>
                <th>Recipient Age</th>
                <th>Recipient Gender</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($complains['data'] as $i => $complain) {
                ?>
                <tr>
                    <td><?php echo date('d-m-Y', strtotime($complain['complain_register_date'])) ?></td>
                    <td style="text-transform: capitalize"><?php echo $complain['type_recipient']; ?></td>
                    <td style="text-transform: capitalize"><?php echo $complain['age']; ?></td>
                    <td>
                        <?php
                        if ($complain['gender'] == 'male' && $complain['age'] <= 17) {
                            echo 'Boy (<18)';
                        } else if ($complain['gender'] == 'male' && $complain['age'] > 17) {
                            echo 'Men (>=18)';
                        } else if ($complain['gender'] == 'female' && $complain['age'] <= 17) {
                            echo 'Girl (<18)';
                        } else if ($complain['gender'] == 'female' && $complain['age'] > 17) {
                            echo 'Women (>=18)';
                        }
                        ?>
                    </td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_complain')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_complain', 'edit' => $complain['pk_complain_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Complain',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_complain')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $complain['pk_complain_id']),
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
                        window.location.href = '?action=deleteComplain&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>