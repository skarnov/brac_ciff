<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_branch = $_GET['branch_id'] ? $_GET['branch_id'] : null;
$branch_id = $_config['user']['user_branch'];
$branch = $branch_id ? $branch_id : $filter_branch;

$args = array(
    'project_id' => $filter_project,
    'branch_id' => $branch,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_sale_id',
        'order' => 'DESC'
    ),
);

$branchManager = jack_obj('dev_branch_management');
$jackProjects = jack_obj('dev_project_management');
$branches = $branchManager->get_branches(array('select_fields' => array('branches.pk_branch_id', 'branches.branch_name'), 'data_only' => true));
$all_projects = $jackProjects->get_projects(array('data_only' => true));

$sales = $this->get_sales($args);
$pagination = pagination($sales['total'], $per_page_items, $start);

if ($_GET['download_csv']) {
    unset($args['limit_by']);
    $args['data_only'] = true;
    $data = $this->get_sales($args);  
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'sales-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');
    
    $report_title = array('', 'Sales Report', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array('#', 'Date', 'Branch', 'Sub-Total', 'Discount', 'Grand Total');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['invoice_date']
                , $user['branch_name']
                , $user['sale_total'] + $user['sale_discount']."\r"
                , $user['sale_discount']."\r"
                , $user['sale_total']."\r");
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
    <h1>All Sales</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_sale',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Sale',
                'title' => 'New Sale',
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&project_id='.$filter_project.'&branch_id='.$filter_branch,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download',
                'title' => 'Download',
            ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title"><i class="panel-title-icon fa fa-filter"></i>&nbsp;Filter</span>
            </div>
            <div class="panel-body p5 pb15 pt15">
                <form class="filter_form" role="form" name="filter_form" method="get" action="">
                    <div class="col-sm-3">
                        <div class="form-group form_element_holder select_holder select_holder_project_id">
                            <label>Project</label>
                            <select class="form-control" id="select_holder_project_id" name="project_id">
                                <option value="">Select One</option>
                                <?php
                                foreach ($all_projects['data'] as $i => $v) {
                                    ?>
                                    <option value="<?php echo $v['pk_project_id'] ?>" <?php if ($filter_project == $v['pk_project_id']) echo 'selected' ?>><?php echo $v['project_short_name'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <?php if (!$branch_id): ?>
                    <div class="col-sm-3">
                        <div class="form-group form_element_holder select_holder select_holder_project_id">
                            <label>Branch</label>
                            <select class="form-control" id="select_holder_project_id" name="branch_id">
                                <option value="">Select One</option>
                                <?php
                                foreach ($branches['data'] as $i => $v) {
                                    ?>
                                    <option value="<?php echo $v['pk_branch_id'] ?>" <?php if ($filter_branch == $v['pk_branch_id']) echo 'selected' ?>><?php echo $v['branch_name'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <?php endif ?>
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-xs btn-success btn-flat btn-labeled"><i class="btn-label fa fa-filter"></i>FILTER</button>
                        <button type="button" class="btn btn-xs btn-danger btn-flat btn-labeled" onclick="clearFilters(this);"><i class="btn-label fa fa-trash"></i>CLEAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="table-primary table-responsive">
            <div class="table-header">
                <?php echo searchResultText($sales['total'], $start, $per_page_items, count($sales['data']), 'sales') ?>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Branch</th>
                        <th>Sale Type</th>
                        <th>Discount</th>
                        <th>Sub-Total</th>
                        <th>Total</th>
                        <th class="tar action_column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($sales['data'] as $i => $sale) {
                        ?>
                        <tr>
                            <td><?php echo $sale['invoice_date'] ?></td>
                            <td><?php echo $sale['branch_name'] ?></td>
                            <td><?php echo $sale['sale_title'] ?></td>
                            <td><?php echo $sale['sale_discount'] ?></td>
                            <td><?php echo $sale['sale_total'] ?></td>
                            <td><?php echo $sale['sale_total'] - $sale['sale_discount'] ?></td>
                            <td class="tar action_column">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'view_invoice', 'id' => $sale['pk_sale_id'])),
                                    'icon' => 'icon_view',
                                    'text' => 'View',
                                    'title' => 'View Invoice',
                                ));
                                ?>
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