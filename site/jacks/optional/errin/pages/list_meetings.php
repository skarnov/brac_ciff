<?php

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$args = array(
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_meeting_id',
        'order' => 'DESC'
    ),
);

$meetings = $this->get_meetings($args);
$pagination = pagination($meetings['total'], $per_page_items, $start);

if ($_GET['download_csv']) {
    unset($args['limit_by']);
    $args['data_only'] = true;
    $data = $this->get_meetings($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'meeting-schedule-list-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Meeting Schedule Report', '');
    fputcsv($fh, $report_title);
    
    $blank_row = array('');
    fputcsv($fh, $blank_row);
    
    $headers = array('#', 'Returnee', 'Case', 'Meeting', 'Completion Date of Meeting', 'Created', 'ID');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['returnee_name']
                , $user['case_number']."\r"
                , $user['meeting_type']
                , $user['complete_date']."\r"
                , $user['create_date']."\r"
                , $user['pk_meeting_id']."\r");
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
    <h1>All Meeting Schedule</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_meeting',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Schedule',
                'title' => 'New Meeting Schedule',
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
                'text' => 'Download Meeting Schedule List',
                'title' => 'Download Meeting Schedule List',
            ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-primary table-responsive">
            <div class="table-header">
                <?php echo searchResultText($meetings['total'], $start, $per_page_items, count($meetings['data']), 'meeting schedule') ?>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Returnee</th>
                        <th>Case</th>
                        <th>Meeting</th>
                        <th>Completion Date of Meeting</th>
                        <th>Created</th>
                        <th>ID</th>
                        <th class="tar action_column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($meetings['data'] as $i => $meeting) {
                        ?>
                        <tr>
                            <td><?php echo $meeting['returnee_name']; ?></td>
                            <td><?php echo $meeting['case_number']; ?></td>
                            <td><?php echo $meeting['meeting_type']; ?></td>
                            <td><?php echo $meeting['complete_date']; ?></td>
                            <td><?php echo $meeting['create_date']; ?></td>
                            <td><?php echo $meeting['pk_meeting_id']; ?></td>
                            <td class="tar action_column">
                                <?php if (has_permission('edit_meeting')): ?>
                                    <div class="btn-toolbar">
                                        <?php
                                        echo linkButtonGenerator(array(
                                            'href' => build_url(array('action' => 'add_edit_meeting', 'edit' => $meeting['pk_meeting_id'])),
                                            'action' => 'edit',
                                            'icon' => 'icon_edit',
                                            'text' => 'Edit',
                                            'title' => 'Edit Meeting',
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