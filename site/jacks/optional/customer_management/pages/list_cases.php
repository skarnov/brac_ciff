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
        'col' => 'dev_immediate_supports.fk_customer_id',
        'order' => 'DESC'
    ),
);

$cases = $this->get_cases($args);
$pagination = pagination($cases['total'], $per_page_items, $start);

doAction('render_start');
?>
<div class="page-header">
    <h1>All Cases</h1>
    <div class="oh">
    </div>
</div>
<?php
ob_start();
?>
<div class="table-primary table-responsive">
    <div class="table-header">
        <?php echo searchResultText($cases['total'], $start, $per_page_items, count($cases['data']), 'cases') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Passport Number</th>
                <th>Present Address</th>
                <th>Status</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($cases['data'] as $i => $case) {
                ?>
                <tr>
                    <td><?php echo $case['pk_immediate_support_id']; ?></td>
                    <td><?php echo $case['full_name']; ?></td>
                    <td><?php echo $case['customer_mobile']; ?></td>
                    <td><?php echo $case['passport_number']; ?></td>
                    <td><?php echo '<b>Division - </b>' . $case['permanent_division'] . ',<br><b>District - </b>' . $case['permanent_district'] . ',<br><b>Sub-District - </b>' . $case['permanent_sub_district'] . ',<br><b>Police Station - </b>' . $case['permanent_police_station'] . ',<br><b>Post Office - </b>' . $case['permanent_post_office'] ?></td>
                    <td style="text-transform: capitalize"><?php echo $case['customer_status']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_case')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_case', 'edit' => $case['fk_customer_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Case',
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