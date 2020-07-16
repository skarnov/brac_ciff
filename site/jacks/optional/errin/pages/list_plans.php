<?php

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$args = array(
    'select_fields' => array(
        'dev_cases.returnee_name',
        'dev_cases.case_number',
        'errin_reintegration.reintegration_title',
        'errin_reintegration.reintegration_status',
        'errin_reintegration.delivered_service',
        'errin_reintegration.status_change_date',
        'errin_reintegration.reintegration_summary',
        'errin_reintegration.create_date',
        'errin_reintegration.update_date',
        'errin_reintegration.immediate_needs',
        'errin_reintegration.pk_reintegration_id',
    ),
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_reintegration_id',
        'order' => 'DESC'
    ),
);

$plans = $this->get_plans($args);
$pagination = pagination($plans['total'], $per_page_items, $start);

if ($_GET['download_csv']) {
    unset($args['limit_by']);
    $args['data_only'] = true;
    $data = $this->get_plans($args);
    $data = $data['data'];
      
    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'reintegration-plans-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');
    
    $report_title = array('', 'Reintegration Plan Report', '');
    fputcsv($fh, $report_title);
    
    $blank_row = array('');
    fputcsv($fh, $blank_row);
    
    $headers = array('#', 'Returnee', 'Case', 'Title', 'Status', 'Services to be delivered', 'Status Change Date', 'Summary of Reintegration', 'Created', 'Modified', 'Immediate reintegration needs');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['returnee_name']
                , $user['case_number']."\r"
                , $user['reintegration_title']
                , $user['reintegration_status']
                , $user['delivered_service']
                , $user['status_change_date']."\r"
                , $user['reintegration_summary']
                , $user['create_date']."\r"
                , $user['update_date']."\r"
                , $user['immediate_needs']);
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
    <h1>All Reintegration Plans</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_plan',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Reintegration Plan',
                'title' => 'New Reintegration Plan',
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1',
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download Plans List',
                'title' => 'Download Plans List',
            ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-primary table-responsive">
            <div class="table-header">
                <?php echo searchResultText($plans['total'], $start, $per_page_items, count($plans['data']), 'reintegration plans') ?>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>                    
                        <th>Returnee</th>
                        <th>Case</th>
                        <th>Status</th>
                        <th>Services to be delivered</th>
                        <th>Status Change Date</th>
                        <th>Created</th>
                        <th>Modified</th>
                        <th class="tar action_column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($plans['data'] as $i => $plan) {
                        ?>
                        <tr>
                            <td><?php echo $plan['returnee_name']; ?></td>
                            <td><?php echo $plan['case_number']; ?></td>
                            <td><?php echo $plan['reintegration_status']; ?></td>
                            <td><?php echo $plan['delivered_service']; ?></td>
                            <td><?php echo $plan['status_change_date']; ?></td>
                            <td><?php echo $plan['create_date']; ?></td>
                            <td><?php echo $plan['update_date']; ?></td>
                            <td class="tar action_column">
                                <?php if (has_permission('edit_plan')): ?>
                                    <div class="btn-toolbar">
                                        <?php
                                        echo linkButtonGenerator(array(
                                            'href' => build_url(array('action' => 'add_edit_plan', 'edit' => $plan['pk_reintegration_id'])),
                                            'action' => 'edit',
                                            'icon' => 'icon_edit',
                                            'text' => 'Edit',
                                            'title' => 'Edit Reintegration Plan',
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