<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

$branchManager = jack_obj('dev_branch_management');
$branches = $branchManager->get_branches(array('select_fields' => array('branches.pk_branch_id', 'branches.branch_name'), 'data_only' => true));

$courses = jack_obj('dev_course_management');
$all_courses = $courses->get_courses(array('single' => false));

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_batches(array('batch_id' => $edit, 'select_fields' => array(
            'dev_batches.fk_branch_id',
            'dev_batches.fk_course_id',
            'dev_batches.batch_name',
        ), 'single' => true));
    if (!$pre_data) {
        add_notification('Invalid batch, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$branch_id = $_config['user']['user_branch'];

$args = array(
    'select_fields' => array(
        'dev_batches.pk_batch_id',
        'dev_branches.branch_name',
        'dev_courses.course_name',
        'dev_batches.batch_name',
    ),
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'branch_id' => $branch_id,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_batch_id',
        'order' => 'DESC'
    ),
);

$batches = $this->get_batches($args);
$pagination = pagination($batches['total'], $per_page_items, $start);

$filterString = array();
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Sub-District: ' . $filter_sub_district;

if ($_POST) {
    if (!checkPermission($edit, 'add_batch', 'edit_batch')) {
        add_notification('You don\'t have enough permission.', 'error');
        header('Location:' . build_url(NULL, array('edit', 'action')));
        exit();
    }

    $data = array(
        'required' => array(
            'batch_name' => 'Batch Name',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_batch($data);

    if ($ret['success']) {
        $msg = "Information of batch has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_batch_management/manage_batches'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

if ($_GET['download_csv']) {
    unset($args['limit']);
    $args['data_only'] = true;
    $data = $this->get_batches($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'batch-list' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Batch Report', '');
    fputcsv($fh, $report_title);

    $filtered_with = array('', 'Division = ' . $filter_division . ', District = ' . $filter_district . ', Sub-District = ' . $filter_sub_district, '');
    fputcsv($fh, $filtered_with);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array('#', 'Branch Name', 'Course Name', 'Batch Name');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['branch_name']
                , $user['course_name']
                , $user['batch_name']);
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
    <h1>All Batches</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district,
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
<?php if (!$branch_id) : ?>
<?php
ob_start();
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
<?php endif ?>
<div class="row">
    <div class="col-md-4">
        <form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
            <div class="panel">
                <div class="panel-body">
                    <?php if (!$branch_id) : ?>
                        <div class="form-group">
                            <label>Branch</label>
                            <select required class="form-control" name="fk_branch_id">
                                <option value="">Select One</option>
                                <?php
                                foreach ($branches['data'] as $i => $v) {
                                    ?>
                                    <option value="<?php echo $v['pk_branch_id'] ?>" <?php if ($v['pk_branch_id'] == $pre_data['fk_branch_id']) echo 'selected' ?>><?php echo $v['branch_name'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    <?php endif ?>
                    <?php if ($branch_id): ?>
                        <input type="hidden" name="fk_branch_id" value="<?php echo $_config['user']['user_branch'] ?>"/>
                    <?php endif ?>
                    <div class="form-group">
                        <label>Select Course</label>
                        <select required class="form-control" name="course_id">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_courses['data'] as $value) :
                                ?>
                                <option value="<?php echo $value['pk_course_id'] ?>" <?php
                                if ($value['pk_course_id'] == $pre_data['fk_course_id']) {
                                    echo 'selected';
                                }
                                ?>><?php echo $value['course_name'] ?></option>
                                    <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Batch Name</label>
                        <input class="form-control" type="text" name="batch_name" value="<?php echo $pre_data['batch_name'] ? $pre_data['batch_name'] : ''; ?>" required="">
                        <span class="help-block">Input Must Be No Space</span>
                    </div>
                </div>
                <div class="panel-footer tar">
                    <a href="<?php echo url('admin/dev_batch_management/manage_batches') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
                    <?php
                    echo submitButtonGenerator(array(
                        'action' => $edit ? 'update' : 'update',
                        'size' => '',
                        'id' => 'submit',
                        'title' => $edit ? 'Update The Batch' : 'Save The Batch',
                        'icon' => $edit ? 'icon_update' : 'icon_save',
                        'text' => $edit ? 'Update' : 'Save'))
                    ?>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-8">
        <div class="table-primary table-responsive">
            <?php if ($filterString): ?>
                <div class="table-header">
                    Filtered With: <?php echo implode(', ', $filterString) ?>
                </div>
            <?php endif; ?>
            <div class="table-header">
                <?php echo searchResultText($batches['total'], $start, $per_page_items, count($batches['data']), 'batches') ?>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Course Name</th>
                        <th>Batch Name</th>
                        <th class="tar action_column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($batches['data'] as $i => $batch) {
                        ?>
                        <tr>
                            <td><?php echo $batch['branch_name']; ?></td>
                            <td><?php echo $batch['course_name']; ?></td>
                            <td><?php echo $batch['batch_name']; ?></td>
                            <td class="tar action_column">
                                <?php if (has_permission('edit_batch')): ?>
                                    <div class="btn-toolbar">
                                        <?php
                                        echo linkButtonGenerator(array(
                                            'href' => build_url(array('action' => 'manage_batches', 'edit' => $batch['pk_batch_id'])),
                                            'action' => 'edit',
                                            'icon' => 'icon_edit',
                                            'text' => 'Edit',
                                            'title' => 'Edit Batch',
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
                <?php echo $pagination ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var BD_LOCATIONS = <?php echo getBDLocationJson(); ?>;
    init.push(function () {
        new bd_new_location_selector({
            'division': $('#filter_division'),
            'district': $('#filter_district'),
            'sub_district': $('#filter_sub_district')
        });
    });
</script>