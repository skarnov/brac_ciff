<?php
global $devdb;
$filter_project = $_GET['project'] ? $_GET['project'] : null;

if ($filter_project) {
    $sql = "SELECT * FROM dev_branches WHERE fk_project_id = '$filter_project'";
    $results = $devdb->get_results($sql);
}

if ($_GET['download_csv']) {
    $filter = $_GET['filter'];

    $sql = "SELECT * FROM dev_branches WHERE fk_project_id = '$filter'";
    $results = $devdb->get_results($sql);

    $data = $results;
    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'project_wise_project-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Project Wise Branch Report', '');
    fputcsv($fh, $report_title);


    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array('#', 'Name', 'Division', 'District', 'Sub-District', 'Address');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['branch_name']
                , $user['branch_division']
                , $user['branch_district']
                , $user['branch_sub_district']
                , $user['branch_address']);
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
    <h1>Project Wise Branch</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&filter=' . $filter_project,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_edit',
                'text' => 'Download Project Wise Branch',
                'title' => 'Download Project Wise Branch Report',
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
                                ?>><?php echo $value['project_short_name'].' [' .$value['project_code'].']' ?></option>
                                    <?php endforeach ?>
                        </select>
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
                <div class="col-md-8">
                    <div class="table-primary table-responsive">
                        <div class="table-header">
                            Search Results
                        </div>
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Division</th>
                                    <th>District</th>
                                    <th>Sub-District</th>
                                    <th>Address </th>
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
                                            <td><?php echo $v['branch_division'] ?></td>
                                            <td><?php echo $v['branch_district'] ?></td>
                                            <td><?php echo $v['branch_sub_district'] ?></td>
                                            <td><?php echo $v['branch_address'] ?></td>
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