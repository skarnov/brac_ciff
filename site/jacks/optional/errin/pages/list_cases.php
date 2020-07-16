<?php

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$args = array(
    'select_fields' => array(
        'dev_cases.pk_case_id',
        'dev_cases.case_number',
        'dev_cases.case_status',
        'dev_cases.create_date',
        'dev_cases.reintegration_spend',
        'dev_cases.epi_number',
        'dev_cases.close_date',
        'dev_cases.epi_country',
        'dev_cases.returnee_name',
        'dev_cases.family_name',
        'dev_cases.first_name',
    ),
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_case_id',
        'order' => 'DESC'
    ),
);

$cases = $this->get_cases($args);
$pagination = pagination($cases['total'], $per_page_items, $start);

if ($_GET['download_csv']) {
    unset($args['limit_by']);
    $args['data_only'] = true;
    $data = $this->get_cases($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'cases-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Case Report', '');
    fputcsv($fh, $report_title);
    
    $blank_row = array('');
    fputcsv($fh, $blank_row);
    
    $headers = array('#', 'Case Reference Number', 'Case Status', 'Created', 'Total Reintegration Spend', 'EPI Reference Number', 'Target Close Date', 'EPI Country', 'Returnee', 'Returnee Family Name', 'Returnee First Name');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['case_number']."\r"
                , $user['case_status']
                , $user['create_date']."\r"
                , $user['reintegration_spend']."\r"
                , $user['epi_number']."\r"
                , $user['close_date']."\r"
                , $user['epi_country']
                , $user['returnee_name']
                , $user['family_name']
                , $user['first_name']);
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
    <h1>All Cases</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_case',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Case',
                'title' => 'New Case Register',
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
                'text' => 'Download Cases List',
                'title' => 'Download Cases List',
            ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-primary table-responsive">
            <div class="table-header">
                <?php echo searchResultText($cases['total'], $start, $per_page_items, count($cases['data']), 'cases') ?>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Case Reference Number</th>
                        <th>Case Status</th>
                        <th>Created</th>
                        <th>Total Reintegration Spend</th>
                        <th>EPI Reference Number</th>
                        <th>Target Close Date</th>
                        <th>EPI Country</th>
                        <th>Returnee</th>
                        <th>Returnee Family Name</th>
                        <th>Returnee First Name</th>
                        <th class="tar action_column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($cases['data'] as $i => $case) {
                        ?>
                        <tr>
                            <td><?php echo $case['case_number']; ?></td>
                            <td style="text-transform: capitalize"><?php echo $case['case_status']; ?></td>
                            <td><?php echo $case['create_date']; ?></td>
                            <td><?php echo $case['reintegration_spend']; ?></td>
                            <td><?php echo $case['epi_number']; ?></td>
                            <td><?php echo $case['close_date']; ?></td>
                            <td><?php echo $case['epi_country']; ?></td>
                            <td><?php echo $case['returnee_name']; ?></td>
                            <td><?php echo $case['family_name']; ?></td>
                            <td><?php echo $case['first_name']; ?></td>
                            <td class="tar action_column">
                                <?php if (has_permission('edit_case')): ?>
                                    <div class="btn-toolbar">
                                        <?php
                                        echo linkButtonGenerator(array(
                                            'href' => build_url(array('action' => 'add_edit_case', 'edit' => $case['pk_case_id'])),
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
                    <?php echo $pagination?>
                </div>
            </div>
        </div>
    </div>
</div>