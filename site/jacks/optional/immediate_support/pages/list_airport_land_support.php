<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_id = $_GET['brac_info_id'] ? $_GET['brac_info_id'] : null;
$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;

$args = array(
    'brac_info_id' => $filter_id,
    'name' => $filter_name,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_support_id',
        'order' => 'DESC'
    ),
);

$results = $this->get_airport_land_supports($args);
$pagination = pagination($results['total'], $per_page_items, $start);

$filterString = array();
if ($filter_id)
    $filterString[] = 'ID: ' . $filter_id;
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Sub-District: ' . $filter_sub_district;

doAction('render_start');
?>
<div class="page-header">
    <h1>All Immediate Assistance after Arrival</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_airport_land_support',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Immediate Assistance after Arrival',
                'title' => 'New Immediate Assistance after Arrival',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<?php
echo formProcessor::form_elements('id', 'id', array(
    'width' => 2, 'type' => 'text', 'label' => 'ID',
        ), $filter_id);
echo formProcessor::form_elements('name', 'name', array(
    'width' => 2, 'type' => 'text', 'label' => 'Name',
        ), $filter_name);
$all_countries = getWorldCountry();
$result = array_combine($all_countries, $all_countries);
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
<div class="table-primary table-responsive">
    <?php if ($filterString): ?>
        <div class="table-header">
            Filtered With: <?php echo implode(', ', $filterString) ?>
        </div>
    <?php endif; ?>
    <div class="table-header">
        <?php echo searchResultText($results['total'], $start, $per_page_items, count($results['data']), 'Immediate Assistance after Arrival') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Present Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results['data'] as $i => $value) {
                ?>
                <tr>
                    <td><?php echo $value['brac_info_id']; ?></td>
                    <td><?php echo $value['full_name']; ?></td>
                    <td><?php echo $value['mobile_number']; ?></td>
                    <td><?php echo '<b>Division - </b>' . $value['division'] . ',<br><b>District - </b>' . $value['district'] . ',<br><b>Sub-District - </b>' . $value['sub_district'] ?></td>
                    <td>
                        <?php if (has_permission('edit_airport_land_support')): ?>
                            <div class="btn-group">
                                <a href="<?php echo url('admin/immediate_support/manage_airport_land_support?action=add_edit_airport_land_support&edit=' . $value['pk_support_id']) ?>" class="btn btn-flat btn-labeled btn-sm btn btn-primary"><i class="fa fa-pencil-square-o"></i> Edit</a>
                            </div>                                
                        <?php endif ?>
                        <?php if (has_permission('delete_airport_land_support')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $value['pk_support_id']),
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
                        text: 'Delete Immediate Assistance after Arrival',
                        value: 'deleteAirportLandSupport'
                    }],
                callback: function (result) {
                    if (result == 'deleteAirportLandSupport') {
                        window.location.href = '?action=deleteAirportLandSupport&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>