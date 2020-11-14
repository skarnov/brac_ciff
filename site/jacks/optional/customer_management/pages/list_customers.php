<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_id = $_GET['id'] ? $_GET['id'] : null;
$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_nid = $_GET['nid'] ? $_GET['nid'] : null;
$filter_passport = $_GET['passport'] ? $_GET['passport'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_ps = $_GET['ps'] ? $_GET['ps'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;
$branch_id = $_config['user']['user_branch'] ? $_config['user']['user_branch'] : null;

$args = array(
    'listing' => TRUE,
    'select_fields' => array(
        'pk_customer_id' => 'dev_customers.pk_customer_id',
        'customer_id' => 'dev_customers.customer_id',
        'full_name' => 'dev_customers.full_name',
        'customer_mobile' => 'dev_customers.customer_mobile',
        'passport_number' => 'dev_customers.passport_number',
        'present_division' => 'dev_customers.permanent_division',
        'present_district' => 'dev_customers.permanent_district',
        'present_sub_district' => 'dev_customers.permanent_sub_district',
        'present_police_station' => 'dev_customers.permanent_police_station',
        'present_post_office' => 'dev_customers.permanent_post_office',
        'customer_status' => 'dev_customers.customer_status'
    ),
    'id' => $filter_id,
    'name' => $filter_name,
    'nid' => $filter_nid,
    'passport' => $filter_passport,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'ps' => $filter_ps,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_customer_id',
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

$customers = $this->get_customers($args);
$pagination = pagination($customers['total'], $per_page_items, $start);

$filterString = array();
if ($filter_id)
    $filterString[] = 'ID: ' . $filter_id;
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_nid)
    $filterString[] = 'NID: ' . $filter_nid;
if ($filter_passport)
    $filterString[] = 'Passport: ' . $filter_passport;
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Sub-District: ' . $filter_sub_district;
if ($filter_ps)
    $filterString[] = 'Police Station: ' . $filter_ps;
if ($filter_entry_start_date)
    $filterString[] = 'Start Date: ' . $filter_entry_start_date;
if ($filter_entry_end_date)
    $filterString[] = 'End Date: ' . $filter_entry_end_date;

if ($_GET['download_csv']) {
    unset($args['limit']);
    $args['data_only'] = true;
    $data = $this->get_customers($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . ' participant-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Participant Report', '');
    fputcsv($fh, $report_title);

    $filtered_with = array('', ' Division = ' . $filter_division . ', District = ' . $filter_district . ', Sub-District = ' . $filter_sub_district . ', Police Station = ' . $filter_ps, '');
    fputcsv($fh, $filtered_with);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array('#', 'ID', 'Name', 'Contact Number', 'Passport Number', 'Division', 'District', 'Sub-District', 'Police Station', 'Present Post Office');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['customer_id']
                , $user['full_name']
                , $user['customer_mobile'] . "\r"
                , $user['passport_number'] . "\r"
                , $user['permanent_division']
                , $user['permanent_district']
                , $user['permanent_sub_district']
                , $user['permanent_police_station']
                , $user['permanent_post_office']);
            fputcsv($fh, $dataToSheet);
        }
    }

    fclose($fh);

    $now = time();
    foreach (glob($csvFolder . "*.csv") as $file) {
        if (is_file($file)) {
            if ($now - filemtime($file) >= 60 * 60 * 24 * 2) { // 2 days
                unlink($file);
            }
        }
    }

    if (function_exists('apache_setenv'))
        @apache_setenv('no-gzip', 1);
    @ini_set('zlib.output_compression', 'Off');

    //Get file type and set it as Content Type
    header('Content-Type: text/csv');
    //Use Content-Disposition: attachment to specify the filename
    header('Content-Disposition: attachment; filename=' . basename($csvFile));
    //No cache
    header('Expires: 0');
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Pragma: public');
    //Define file size
    header('Content-Length: ' . filesize($csvFile));
    set_time_limit(0);
    $file = @fopen($csvFile, "rb");
    while (!feof($file)) {
        print(@fread($file, 1024 * 8));
        ob_flush();
        flush();
    }
    @fclose($file);
    exit;
}

doAction('render_start');
?>
<div class="page-header">
    <h1>All Participants</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_customer',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Participant Profile',
                'title' => 'New Participant Profile',
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&id=' . $filter_id . '&name=' . $filter_name . '&nid=' . $filter_nid . '&passport=' . $filter_passport . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&ps=' . $filter_ps . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_edit',
                'text' => 'Download Participants',
                'title' => 'Download Participants',
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
echo formProcessor::form_elements('nid', 'nid', array(
    'width' => 2, 'type' => 'text', 'label' => 'NID',
        ), $filter_nid);
echo formProcessor::form_elements('passport', 'passport', array(
    'width' => 2, 'type' => 'text', 'label' => 'Passport',
        ), $filter_passport);
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
<div class="form-group col-sm-2">
    <label>Police Station</label>
    <div class="select2-info">
        <select class="form-control" id="filter_police_station" name="ps" data-selected="<?php echo $filter_ps; ?>"></select>
    </div>
</div>
<div class="form-group col-sm-2">
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
<div class="form-group col-sm-2">
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
        <?php echo searchResultText($customers['total'], $start, $per_page_items, count($customers['data']), 'participants') ?>
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($customers['data'] as $i => $customer) {
                ?>
                <tr>
                    <td><?php echo $customer['customer_id']; ?></td>
                    <td><?php echo $customer['full_name']; ?></td>
                    <td><?php echo $customer['customer_mobile']; ?></td>
                    <td><?php echo $customer['passport_number']; ?></td>
                    <td><?php echo '<b>Division - </b>' . $customer['permanent_division'] . ',<br><b>District - </b>' . $customer['permanent_district'] . ',<br><b>Sub-District - </b>' . $customer['permanent_sub_district'] . ',<br><b>Police Station - </b>' . $customer['permanent_police_station'] . ',<br><b>Post Office - </b>' . $customer['permanent_post_office'] ?></td>
                    <td style="text-transform: capitalize"><?php echo $customer['customer_status']; ?></td>
                    <td>
                        <?php if (has_permission('edit_customer')): ?>
                            <div class="btn-group">
                                <a href="<?php echo url('admin/dev_customer_management/manage_customers?action=add_edit_customer&edit=' . $customer['pk_customer_id']) ?>" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i> Edit</a>
                                <a href="<?php echo url('admin/dev_customer_management/manage_customers?action=add_edit_evaluate&edit=' . $customer['pk_customer_id']) ?>" class="btn btn-warning">Evaluate</a>
                                <a href="<?php echo url('admin/dev_customer_management/manage_cases?action=add_edit_case&edit=' . $customer['pk_customer_id']) ?>" class="btn btn-info">Case Management</a>
                                <a href="<?php echo url('admin/dev_customer_management/manage_customers?action=list_satisfaction_scale&id=' . $customer['pk_customer_id']) ?>" class="btn btn-dark-gray">Reintegration Assistance Satisfaction Scale</a>
                            </div>                                
                        <?php endif ?>
                        <?php if (has_permission('delete_customer')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $customer['pk_customer_id']),
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
                        text: 'Delete Participant Profile Information',
                        value: 'deleteProfile'
                    }],
                callback: function (result) {
                    if (result == 'deleteProfile') {
                        window.location.href = '?action=deleteProfile&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>