<?php

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_id = $_GET['customer_id'] ? $_GET['customer_id'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;

$branch_id = $_config['user']['user_branch'];

$args = array(
    'customer_id' => $filter_id,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'branch_id' => $branch_id,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_followup_id',
        'order' => 'DESC'
    ),
);

$followups = $this->get_followups($args);
$pagination = pagination($followups['total'], $per_page_items, $start);

$filterString = array();
if ($filter_id)
    $filterString[] = 'Customer ID: ' . $filter_id;

doAction('render_start');
?>
<div class="page-header">
    <h1>All Followups</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_followup',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Followup',
                'title' => 'New Followup',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
echo formProcessor::form_elements('customer_id','customer_id',array(
    'width' => 2, 'type' => 'text', 'label' => 'Customer ID',
    ),$filter_id);
?>
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
    <label>Sub-District</label>
    <div class="select2-success">
        <select class="form-control" id="filter_sub_district" name="sub_district" data-selected="<?php echo $filter_sub_district; ?>"></select>
    </div>
</div>
<?php
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-primary table-responsive">
            <div class="table-header">
                <?php echo searchResultText($followups['total'], $start, $per_page_items, count($followups['data']), 'followups') ?>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Customer Name</th>
                        <th>Followup Date</th>
                        <th>Status of beneficiary</th>
                        <th>Challenges</th>
                        <th>Action Taken</th>
                        <th class="tar action_column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($followups['data'] as $i => $followup) {
                        ?>
                        <tr>
                            <td><?php echo $followup['customer_id']; ?></td>
                            <td><?php echo $followup['full_name']; ?></td>
                            <td><?php echo $followup['followup_date']; ?></td>
                            <td><?php echo $followup['beneficiary_status']; ?></td>
                            <td><?php echo $followup['followup_challenges']; ?></td>
                            <td><?php echo $followup['action_taken']; ?></td>
                            <td class="tar action_column">
                                <?php if (has_permission('edit_followup')): ?>
                                    <div class="btn-toolbar">
                                        <?php
                                        echo linkButtonGenerator(array(
                                            'href' => build_url(array('action' => 'add_edit_followup', 'edit' => $followup['pk_followup_id'])),
                                            'action' => 'edit',
                                            'icon' => 'icon_edit',
                                            'text' => 'Edit',
                                            'title' => 'Edit Followup',
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
                    <?php echo $pagination?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var BD_LOCATIONS = <?php echo getBDLocationJson() ?>;
    init.push(function () {
        new bd_new_location_selector({
            'division': $('#filter_division'),
            'district': $('#filter_district'),
            'sub_district': $('#filter_sub_district')
        });
    });
</script>