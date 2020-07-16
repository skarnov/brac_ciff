<?php
global $devdb;

$filter_project = $_GET['project'] ? $_GET['project'] : null;
$filter_branch = $_GET['branch'] ? $_GET['branch'] : null;
$branch_id = $_config['user']['user_branch'];
$branch = $branch_id ? $branch_id : $filter_branch;
$filter_month = $_GET['month'] ? $_GET['month'] : date('m');
$filter_year = $_GET['year'] ? $_GET['year'] : date('Y');
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;

$sql = "SELECT "
        . "dev_branches.branch_name, "
        . "dev_sales.invoice_date, "
        . "dev_sales.sale_title, "
        . "dev_products.product_name, "
        . "dev_sale_items.item_quantity, "
        . "dev_sale_items.item_price, "
        . "dev_sale_items.item_total_price "
        . " FROM dev_sale_items"
        . " LEFT JOIN dev_sales ON (dev_sales.pk_sale_id = dev_sale_items.fk_sale_id)"
        . " LEFT JOIN dev_branches ON (dev_branches.pk_branch_id = dev_sales.fk_branch_id)"
        . " LEFT JOIN dev_products ON (dev_products.pk_product_id = dev_sale_items.fk_product_id)"
        . " WHERE 1";

if ($filter_project) {
    $sql .= " AND dev_sales.fk_project_id = '$filter_project'";
}
if ($branch) {
    $sql .= " AND dev_sales.fk_branch_id = '$branch'";
}
if ($filter_month && $filter_year) {
    $first_day = $filter_year . '-' . $filter_month . '-01';
    $last_day = date('Y-m-t', strtotime($first_day));
    $sql .= " AND dev_sales.invoice_date BETWEEN '$first_day' AND '$last_day'";
}
if ($filter_division) {
    $sql .= " AND dev_branches.branch_division = '$filter_division'";
}
if ($filter_district) {
    $sql .= " AND dev_branches.branch_district = '$filter_district'";
}
if ($filter_sub_district) {
    $sql .= " AND dev_branches.branch_sub_district = '$filter_sub_district'";
}

if ($filter_project || $branch || $filter_month || $filter_year || $filter_division || $filter_district || $filter_sub_district) {    
    $results = $devdb->get_results($sql);
    foreach ($results as $i => $value) {
        if ($value['sale_title'] == 'Stock Sale') {
            $results[$i]['sale_title'] = $value['product_name'];
        }
    }
}

if ($_GET['download_csv']) {    
    if ($filter_project || $branch || $filter_month || $filter_year || $filter_division || $filter_district || $filter_sub_district) {
        $results = $devdb->get_results($sql);
        foreach ($results as $i => $value) {
            if ($value['sale_title'] == 'Stock Sale') {
                $results[$i]['sale_title'] = $value['product_name'];
            }
        }
    }

    if ($filter_month && $filter_year) {
        $first_day = $filter_year . '-' . $filter_month . '-01';
        $last_day = date('Y-m-t', strtotime($first_day));
    }
    
    $data = $results;
    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'Sales Report' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('','Sales Report From - '.$first_day.' To - '.$last_day.'', '');
    fputcsv($fh, $report_title);
    
    if($filter_project){
        $projectManager = jack_obj('dev_project_management');
        $project_info = $projectManager->get_projects(array('id'=>$filter_project, 'single'=> true, 'select_fields' => array('project_short_name'), 'data_only' => true));
        $project_name = array('','Project - '.$project_info['project_short_name'].'');
        fputcsv($fh, $project_name);
    }
    
    if($branch){
        $branchManager = jack_obj('dev_branch_management');
        $branch_info = $branchManager->get_branches(array('id'=>$branch, 'single'=> true, 'select_fields' => array('branches.branch_name'), 'data_only' => true));
        $branch_name = array('','Branch - '.$branch_info['branch_name'].'');
        fputcsv($fh, $branch_name);
    }
        
    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array('#', 'Name', 'Designation', 'Branch', 'User ID', 'Cell');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['branch_name']
                , $user['invoice_date']
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

$jackProjects = jack_obj('dev_project_management');
$jackBranches = jack_obj('dev_branch_management');

$all_projects = $jackProjects->get_projects(array('data_only' => true));
$all_branches = $jackBranches->get_branches(array('data_only' => true));

$current_year = date('Y') - 5;
$years = range($current_year, $current_year + 20);

doAction('render_start');
?>
<div class="page-header">
    <h1>Sales Report (Monthly)</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&project=' . $filter_project . '&branch=' . $branch . '&month=' . $filter_month . '&year=' . $filter_year . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_edit',
                'text' => 'Download Monthly Sales Report',
                'title' => 'Download Monthly Sales Report',
            ));
            ?>
        </div>
    </div>
</div>
<form id="theForm" onsubmit="return true;" method="get" action="">
    <div class="panel" id="fullForm">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Select Project</label>
                        <select class="form-control" name="project">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_projects['data'] as $value) :
                                ?>
                                <option value="<?php echo $value['pk_project_id'] ?>" <?php
                            if ($value['pk_project_id'] == $filter_project) {
                                echo 'selected';
                            }
                                ?>><?php echo $value['project_short_name'] ?></option>
                                    <?php endforeach ?>
                        </select>
                    </div>
                    <?php if (!$branch_id): ?>
                    <div class="form-group">
                        <label>Branch</label>
                        <select class="form-control" name="branch">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_branches['data'] as $i => $v) {
                                ?>
                                <option value="<?php echo $v['pk_branch_id'] ?>" <?php if ($v['pk_branch_id'] == $branch) echo 'selected' ?>><?php echo $v['branch_name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <?php endif ?>
                    <div class="form-group">
                        <label>Select Month</label>
                        <select required class="form-control" name="month">
                            <option value="">Select One</option>
                            <option value="01" <?php if ('01' == $filter_month) echo 'selected' ?>>January</option>
                            <option value="02" <?php if ('02' == $filter_month) echo 'selected' ?>>February</option>
                            <option value="03" <?php if ('03' == $filter_month) echo 'selected' ?>>March</option>
                            <option value="04" <?php if ('04' == $filter_month) echo 'selected' ?>>April</option>
                            <option value="05" <?php if ('05' == $filter_month) echo 'selected' ?>>May</option>
                            <option value="06" <?php if ('06' == $filter_month) echo 'selected' ?>>June</option>
                            <option value="07" <?php if ('07' == $filter_month) echo 'selected' ?>>July</option>
                            <option value="08" <?php if ('08' == $filter_month) echo 'selected' ?>>August</option>
                            <option value="09" <?php if ('09' == $filter_month) echo 'selected' ?>>September</option>
                            <option value="10" <?php if ('10' == $filter_month) echo 'selected' ?>>October</option>
                            <option value="11" <?php if ('11' == $filter_month) echo 'selected' ?>>November</option>
                            <option value="12" <?php if ('12' == $filter_month) echo 'selected' ?> >December</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Year</label>
                        <select required class="form-control" name="year">
                            <option value="">Select One</option>
                            <?php foreach ($years as $year) : ?>
                                <option value="<?php echo $year ?>" <?php if ($year == $filter_year) echo 'selected' ?>><?php echo $year ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Division</label>
                        <div class="select2-primary">
                            <select class="form-control" id="filter_division" name="division" data-selected="<?php echo $filter_division ?>"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>District</label>
                        <div class="select2-success">
                            <select class="form-control" id="filter_district" name="district" data-selected="<?php echo $filter_district; ?>"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Sub-District</label>
                        <div class="select2-success">
                            <select class="form-control" id="filter_sub_district" name="sub_district" data-selected="<?php echo $filter_sub_district; ?>"></select>
                        </div>
                    </div>
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
                <div class="col-md-9">
                    <div class="table-primary table-responsive">
                        <div class="table-header">
                            Search Results
                        </div>
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>Date</th>
                                    <th>Sale Title</th>
                                    <th>Quantity</th>
                                    <th>Item Price</th>
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
                                            <td><?php echo $v['branch_name'] ?></td>
                                            <td><?php echo $v['invoice_date'] ?></td>
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