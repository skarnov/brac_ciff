<?php
global $devdb;
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$branchManager = jack_obj('dev_branch_management');
$branches = $branchManager->get_branches(array('select_fields' => array('branches.pk_branch_id', 'branches.branch_name'), 'data_only' => true));
$all_courses = $this->get_courses(array('select_fields' => array('pk_course_id', 'course_name'), 'single' => false));

if ($_POST['ajax_type']) {
    if ($_POST['ajax_type'] == 'selectBatch') {
        $sql = "SELECT pk_batch_id, batch_name FROM dev_batches WHERE fk_branch_id = '" . $_POST['branch_id'] . "' AND fk_course_id = '" . $_POST['id'] . "'";
        $ret = $devdb->get_results($sql);

        foreach ($ret as $data) {
            if ($data['pk_batch_id'] == $pre_data['pk_batch_id']) {
                echo "<option value='" . $data['batch_name'] . "' selected>" . $data['batch_name'] . "</option>";
            } else {
                echo "<option value='" . $data['batch_name'] . "'>" . $data['batch_name'] . "</option>";
            }
        }
    }
    exit();
}
$filter_branch_id = $_GET['branch_id'] ? $_GET['branch_id'] : null;
$branch_id = $_config['user']['user_branch'];
$branch = $branch_id ? $branch_id : $filter_branch_id;
$filter_course_id = $_GET['course_id'] ? $_GET['course_id'] : null;
$filter_batch_name = $_GET['batch_name'] ? $_GET['batch_name'] : null;

$args = array(
    'select_fields' => array(
        'dev_admissions.pk_admission_id',
        'dev_courses.course_name',
        'dev_admissions.batch_name',
        'dev_customers.customer_id',
        'dev_customers.full_name',
    ),
    'branch_id' => $branch,
    'course_id' => $filter_course_id,
    'batch_name' => $filter_batch_name,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_admission_id',
        'order' => 'DESC'
    ),
);

$admissions = $this->get_admissions($args);
$pagination = pagination($admissions['total'], $per_page_items, $start);

$filterString = array();
if ($filter_batch_name)
    $filterString[] = 'Batch Name: ' . $filter_batch_name;

if ($_GET['download_csv']) {
    unset($args['limit']);
    $args['data_only'] = true;
    $data = $this->get_admissions($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'course-list' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Course Admissions', '');
    fputcsv($fh, $report_title);

    $filtered_with = array('', 'Batch = ' . $filter_batch_name, '');
    fputcsv($fh, $filtered_with);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array('#', 'Course Name', 'Batch', 'Customer ID', 'Customer Name');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['course_name']
                , $user['batch_name']
                , $user['customer_id'] . "\r"
                , $user['full_name']);
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
    <h1>Course Admissions</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => 'course_admission?action=add_edit_admission',
                'action' => 'list',
                'text' => 'New Admission',
                'title' => 'Manage All Admissions',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&branch_id=' . $filter_branch_id . '&course_id=' . $filter_course_id . '&batch_name=' . $filter_batch_name,
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
<?php
ob_start();
?>
<?php if (!$branch_id): ?>
<div class="form-group col-sm-3">
    <div class="form-group">
        <label>Branch</label>
        <select required class="form-control" id="branchId" name="branch_id">
            <option value="">Select One</option>
            <?php
            foreach ($branches['data'] as $i => $v) {
                ?>
                <option value="<?php echo $v['pk_branch_id'] ?>" <?php if ($v['pk_branch_id'] == $filter_branch_id) echo 'selected' ?>><?php echo $v['branch_name'] ?></option>
                <?php
            }
            ?>
        </select>
    </div>
</div>
<?php endif ?>
<div class="form-group col-sm-3">
    <label>Course</label>
    <div class="select2-success">
        <select required class="form-control" name="course_id">
            <option value="">Select One</option>
            <?php
            foreach ($all_courses['data'] as $value) :
                ?>
                <option value="<?php echo $value['pk_course_id'] ?>" <?php
                if ($value['pk_course_id'] == $filter_course_id) {
                    echo 'selected';
                }
                ?>><?php echo $value['course_name'] ?></option>
                    <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Batch</label>
    <div class="select2-success">
        <select class="form-control" id="availableBatches" name="batch_name">
            <?php if ($filter_batch_name): ?>
                <option value="<?php echo $filter_batch_name ? $filter_batch_name : ''; ?>"><?php echo $filter_batch_name ? $filter_batch_name : ''; ?></option>
            <?php endif ?>
        </select>
    </div>
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
        <?php echo searchResultText($admissions['total'], $start, $per_page_items, count($admissions['data']), 'Course Admissions') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Course</th>
                <th>Batch</th>
                <th>Customer ID</th>
                <th>Customer</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($admissions['data'] as $i => $admission) {
                ?>
                <tr>
                    <td><?php echo $admission['course_name']; ?></td>
                    <td><?php echo $admission['batch_name']; ?></td>
                    <td><?php echo $admission['customer_id']; ?></td>
                    <td><?php echo $admission['full_name']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_course')): ?>
                            <div class="btn-toolbar">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_admission', 'edit' => $admission['pk_admission_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Admission',
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
            <?php echo $pagination ?>
        </div>
    </div>
</div>
<script>
    init.push(function () {
        $("select[name='course_id']").change(function () {
            $('#availableBatches').html('');
            var stateID = $(this).val();
            var branchId = $('#branchId').val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    data: {
                        'id': stateID,
                        'branch_id': branchId ? branchId : <?php echo $branch_id ?>,
                        'ajax_type': 'selectBatch'
                    },
                    success: function (data) {
                        $('#availableBatches').html(data);
                    }
                });
            } else {
                $('select[name="course_id"]').empty();
            }
        });
    });
</script>