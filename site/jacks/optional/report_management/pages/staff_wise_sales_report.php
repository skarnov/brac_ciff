<?php
global $devdb;
$filter_branch = $_GET['fk_branch_id'] ? $_GET['fk_branch_id'] : null;
$branch_id = $_config['user']['user_branch'];
$branch = $branch_id ? $branch_id : $filter_branch;
$filter_staff = $_GET['staff_id'] ? $_GET['staff_id'] : null;

$staffs = jack_obj('dev_staff_management');
if ($branch_id) {
    $staffs = $staffs->get_staffs(array('branch' => $branch_id));
}

if ($filter_staff) {
    $sql = "SELECT
                dev_projects.project_name,   
                dev_sales.sale_title,   
                dev_products.product_name,   
                dev_sale_items.item_quantity,   
                dev_sale_items.item_price,   
                dev_sale_items.item_total_price   
        FROM dev_sale_items 
            LEFT JOIN dev_sales ON (dev_sales.pk_sale_id = dev_sale_items.fk_sale_id)
            LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_sales.fk_project_id)
            LEFT JOIN dev_products ON (dev_products.pk_product_id = dev_sale_items.fk_product_id)
        WHERE dev_sales.fk_staff_id = '$filter_staff'";

    $results = $devdb->get_results($sql);
    foreach ($results as $i => $value) {
        if ($value['sale_title'] == 'Stock Sale') {
            $results[$i]['sale_title'] = $value['product_name'];
        }
    }
}

$branchManager = jack_obj('dev_branch_management');
$branches = $branchManager->get_branches(array('select_fields' => array('branches.pk_branch_id', 'branches.branch_name'), 'data_only' => true));

if ($_POST['ajax_type']) {
    if ($_POST['ajax_type'] == 'selectStaff') {
        $all_staffs = $staffs->get_staffs(array('branch' => $_POST['branch_id']));
        foreach ($all_staffs['data'] as $staff) {
            if ($staff['pk_user_id'] == $filter_staff) {
                echo "<option value='" . $staff['pk_user_id'] . "' selected>" . $staff['user_fullname'] . "</option>";
            } else {
                echo "<option value='" . $staff['pk_user_id'] . "'>" . $staff['user_fullname'] . "</option>";
            }
        }
    }
    exit();
}

if ($_GET['download_csv']) {
    $filter = $_GET['filter'];
    $sql = "SELECT
                dev_projects.project_name,   
                dev_sales.sale_title,   
                dev_products.product_name,   
                dev_sale_items.item_quantity,   
                dev_sale_items.item_price,   
                dev_sale_items.item_total_price   
        FROM dev_sale_items 
            LEFT JOIN dev_sales ON (dev_sales.pk_sale_id = dev_sale_items.fk_sale_id)
            LEFT JOIN dev_projects ON (dev_projects.pk_project_id = dev_sales.fk_project_id)
            LEFT JOIN dev_products ON (dev_products.pk_product_id = dev_sale_items.fk_product_id)
        WHERE dev_sales.fk_staff_id = '$filter'";

    $results = $devdb->get_results($sql);

    foreach ($results as $i => $value) {
        if ($value['sale_title'] == 'Stock Sale') {
            $results[$i]['sale_title'] = $value['product_name'];
        }
    }

    $branch_info = $branchManager->get_branches(array('id' => $branch, 'select_fields' => array('branches.branch_name'), 'data_only' => true, 'single' => true));

    $staffManager = jack_obj('dev_staff_management');
    $staff_info = $staffManager->get_staffs(array('user_id' => $filter, 'select_fields' => array('dev_users.user_fullname'), 'data_only' => true, 'single' => true));

    $data = $results;
    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'sales-report-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Sales Report/ Branch - ' . $branch_info['branch_name'] . '/ Staff - ' . $staff_info['user_fullname'] . '', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array('#', 'Project', 'Sale Title', 'Quantity', 'Unit Price', 'Sub-Total');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['project_name']
                , $user['sale_title']
                , $user['item_quantity']
                , $user['item_price']
                , $user['item_total_price']);
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


$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects(array('single' => false));

doAction('render_start');
?>
<div class="page-header">
    <h1>Staff Wise Sales Report</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&filter=' . $filter_staff,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_edit',
                'text' => 'Download Sales Report',
                'title' => 'Download Sales Report',
            ));
            ?>
        </div>
    </div>
</div>
<form id="theForm" onsubmit="return true;" method="get" action="">
    <div class="panel" id="fullForm">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4">
                    <?php if (!$branch_id): ?>
                        <div class="form-group">
                            <label>Branch</label>
                            <select required id="branchId" class="form-control" name="fk_branch_id">
                                <option value="">Select One</option>
                                <?php
                                foreach ($branches['data'] as $i => $v) {
                                    ?>
                                    <option value="<?php echo $v['pk_branch_id'] ?>" <?php echo $v['pk_branch_id'] == $branch ? 'selected' : '' ?>><?php echo $v['branch_name'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Select Staff</label>
                            <select class="form-control" data-selected="<?php echo $filter_staff ? $filter_staff : ''; ?>" id="availableStaffs" name="staff_id">

                            </select>
                        </div>
                    <?php endif ?>
                    <?php if ($branch_id): ?>
                        <input type="hidden" name="fk_branch_id" value="<?php echo $_config['user']['user_branch'] ?>"/>
                        <div class="form-group">
                            <label>Select Staff</label>
                            <select required class="form-control" name="staff_id">
                                <option value="">Select One</option>
                                <?php
                                foreach ($staffs['data'] as $value) :
                                    ?>
                                    <option value="<?php echo $value['pk_user_id'] ?>" <?php
                                    if ($value['pk_user_id'] == $pre_data['fk_staff_id']) {
                                        echo 'selected';
                                    }
                                    ?>><?php echo $value['user_fullname'] ?></option>
                                        <?php endforeach ?>
                            </select>
                        </div>
                    <?php endif ?>
                    <div class="panel-footer tar">
                        <?php
                        echo submitButtonGenerator(array(
                            'action' => 'search',
                            'size' => '',
                            'id' => 'submit',
                            'title' => 'Search',
                            'icon' => 'icon_search',
                            'text' => 'Search'))
                        ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="table-primary table-responsive">
                        <div class="table-header">
                            Search Results
                        </div>
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Sale Title</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($results == null) {
                                    ?>
                                    <tr>
                                        <td style="color:red" colspan="6">Not Found</td>
                                    </tr>
                                    <?php
                                } else {
                                    foreach ($results as $i => $v) {
                                        ?>
                                        <tr>
                                            <td><?php echo $v['project_name'] ?></td>
                                            <td><?php echo $v['sale_title'] ?></td>
                                            <td><?php echo $v['item_quantity'] ?></td>
                                            <td><?php echo $v['item_price'] ?></td>
                                            <td><?php echo $v['item_total_price'] ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    init.push(function () {
        $("select[name='fk_branch_id']").change(function () {
            $('#availableStaffs').html('');
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    data: {
                        'branch_id': stateID,
                        'ajax_type': 'selectStaff'
                    },
                    success: function (data) {
                        $('#availableStaffs').html(data);
                    }
                });
            }
        }).change();

    });
</script>