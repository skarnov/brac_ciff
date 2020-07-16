<?php

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_branch = $_GET['branch_id'] ? $_GET['branch_id'] : null;
$branch_id = $_config['user']['user_branch'];
$branch = $branch_id ? $branch_id : $filter_branch;

$args = array(
    'select_fields' => array(
        'dev_staff_incentives.create_date',
        'dev_branches.branch_name',
        'dev_users.user_fullname',
        'dev_staff_incentives.sale_total',
        'dev_staff_incentives.incentive_percentage',
        'dev_staff_incentives.incentive_amount',
    ),
    'project_id' => $filter_project,
    'branch_id' => $branch,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_staff_incentive_id',
        'order' => 'DESC'
    ),
);

$branchManager = jack_obj('dev_branch_management');
$branches = $branchManager->get_branches(array('select_fields' => array('branches.pk_branch_id', 'branches.branch_name'), 'data_only' => true));

$incentive = $this->get_staff_incentive($args);
$pagination = pagination($incentive['total'], $per_page_items, $start);

if ($_GET['download_csv']) {
    unset($args['limit_by']);
    $args['data_only'] = true;
    $data = $this->get_staff_incentive($args);
    
    $data = $data['data'];
    
    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'salesIncentive-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Staff Incentive Report', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);
    
    $headers = array('#', 'Date', 'Branch', 'Staff Name', 'Sale Amount', 'Incentive Percentage', 'Incentive Amount');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['create_date']. "\r"
                , $user['branch_name']
                , $user['user_fullname']
                , $user['sale_total'] . "\r"
                , $user['incentive_percentage'] . "\r"
                , $user['incentive_amount'] . "\r");
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
    <h1>All Staff Incentive</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&branch_id='.$filter_branch,
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
        <?php if (!$branch_id): ?>
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title"><i class="panel-title-icon fa fa-filter"></i>&nbsp;Filter</span>
            </div>
            <div class="panel-body p5 pb15 pt15">
                <form class="filter_form" role="form" name="filter_form" method="get" action="">
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
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-xs btn-success btn-flat btn-labeled"><i class="btn-label fa fa-filter"></i>FILTER</button>
                        <button type="button" class="btn btn-xs btn-danger btn-flat btn-labeled" onclick="clearFilters(this);"><i class="btn-label fa fa-trash"></i>CLEAR</button>
                    </div>
                </form>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="table-primary table-responsive">
            <?php if ($filterString): ?>
                <div class="table-header">
                    Filtered With: <?php echo implode(', ', $filterString) ?>
                </div>
            <?php endif; ?>
            <div class="table-header">
                <?php echo searchResultText($incentive['total'], $start, $per_page_items, count($incentive['data']), 'sales incentive') ?>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th style="text-align: right; width: 10%">Date</th>
                        <th style="width: 10%">Branch</th>
                        <th style="width: 20%">Staff</th>
                        <th style="width: 10%; text-align: right">Sale Amount</th>
                        <th style="width: 10%; text-align: right">Incentive Percentage</th>
                        <th style="width: 10%; text-align: right">Incentive Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($incentive['data'] as $i => $v_incentive) {
                        ?>
                        <tr>
                            <td style="text-align: right"><?php echo $v_incentive['create_date'] ?></td>
                            <td><?php echo $v_incentive['branch_name'] ?></td>
                            <td><?php echo $v_incentive['user_fullname'] ?></td>
                            <td style="text-align: right"><?php echo $v_incentive['sale_total'] ?></td>
                            <td style="text-align: right"><?php echo $v_incentive['incentive_percentage'] ?></td>
                            <td style="text-align: right"><?php echo $v_incentive['incentive_amount'] ?></td>
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