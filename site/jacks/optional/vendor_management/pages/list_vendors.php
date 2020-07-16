<?php
$edit = $_GET['edit'] ? $_GET['edit'] : null;

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_vendors(array('vendor_id' => $edit, 'single' => true));
    if (!$pre_data) {
        add_notification('Invalid vendor, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$args = array(
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_vendor_id',
        'order' => 'DESC'
    ),
);

$vendors = $this->get_vendors($args);
$pagination = pagination($vendors['total'], $per_page_items, $start);

if ($_GET['download_csv']) {
    unset($args['limit_by']);
    $args['data_only'] = true;
    $data = $this->get_vendors($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'companies-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Company List', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);
    
    $headers = array('#', 'Name', 'Contact Person', 'Contact Number', 'Country', 'Address');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['company_name']
                , $user['contact_name']
                , $user['contact_number'] . "\r"
                , $user['vendor_country']
                , $user['vendor_address']);
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

if ($_POST) {
    if (!checkPermission($edit, 'add_vendor', 'edit_vendor')) {
        add_notification('You don\'t have enough permission.', 'error');
        header('Location:' . build_url(NULL, array('edit','action')));
        exit();
    }
    $data = array(
        'required' => array(
            'company_name' => 'Company Name',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_vendor($data);

    if ($ret['success']) {
        $msg = "Information of company has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_vendor_management/manage_vendors'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

$customers = jack_obj('dev_customer_management');
$all_company_business = $customers->get_lookups('company_business');

doAction('render_start');
?>
<div class="page-header">
    <h1>All Companies</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1',
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download Companies',
                'title' => 'Download Companies',
            ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
            <div class="panel">
                <div class="panel-body">
                    <div class="form-group">
                        <label>Company Name</label>
                        <input class="form-control" type="text" name="company_name" required value="<?php echo $pre_data['company_name'] ? $pre_data['company_name'] : ''; ?>">
                    </div>
                    <div class="form-group climate">
                        <label>Business Type</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <?php
                                $business_types = explode(',', $pre_data['company_business']);
                                foreach ($all_company_business['data'] as $business) {
                                    ?>
                                    <label><input class="px" type="checkbox" name="company_business[]" value="<?php echo $business['lookup_value'] ?>" <?php
                                        if (in_array($business['lookup_value'], $business_types)) {
                                            echo 'checked';
                                        }
                                        ?>><span class="lbl"><?php echo $business['lookup_value'] ?></span></label>
                                              <?php } ?>
                                <label><input class="px" type="checkbox" id="newBusiness"><span class="lbl">Others</span></label>
                            </div>
                        </div>
                    </div>
                    <div id="newBusinessType" style="display: none; margin-bottom: 1em;">
                        <input class="form-control" placeholder="Please Specity" type="text" name="new_business" value="">
                    </div>
                    <script>
                        init.push(function () {
                            $("#newBusiness").on("click", function () {
                                $('#newBusinessType').toggle();
                            });
                        });
                    </script>
                    <div class="form-group">
                        <label>Country</label>
                        <select class="form-control" name="vendor_country">
                            <option value="">Select One</option>
                            <?php
                            $all_countries = getWorldCountry();
                            foreach ($all_countries as $value) {
                                ?>
                                <option value="<?php echo $value ?>" <?php if ($pre_data['vendor_country'] == $value) echo 'selected' ?>><?php echo $value ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Contact Person Name</label>
                        <input class="form-control" type="text" name="contact_name" value="<?php echo $pre_data['contact_name'] ? $pre_data['contact_name'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input class="form-control" type="text" name="contact_number" value="<?php echo $pre_data['contact_number'] ? $pre_data['contact_number'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Company Address</label>
                        <textarea class="form-control" name="vendor_address"><?php echo $pre_data['vendor_address'] ? $pre_data['vendor_address'] : ''; ?></textarea>
                    </div>
                </div>
                <div class="panel-footer tar">
                    <a href="<?php echo url('admin/dev_vendor_management/manage_vendors') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
                    <?php
                    echo submitButtonGenerator(array(
                        'action' => $edit ? 'update' : 'update',
                        'size' => '',
                        'id' => 'submit',
                        'title' => $edit ? 'Update The Company' : 'Save The Company',
                        'icon' => $edit ? 'icon_update' : 'icon_save',
                        'text' => $edit ? 'Update' : 'Save'))
                    ?>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-8">
        <div class="table-primary table-responsive">
            <div class="table-header">
                <?php echo searchResultText($vendors['total'], $start, $per_page_items, count($vendors['data']), 'companies') ?>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Contact Person</th>
                        <th>Contact Number</th>
                        <th>Country</th>
                        <th>Address</th>
                        <th class="tar action_column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($vendors['data'] as $i => $vendor) {
                        ?>
                        <tr>
                            <td><?php echo $vendor['company_name']; ?></td>
                            <td><?php echo $vendor['contact_name']; ?></td>
                            <td><?php echo $vendor['contact_number']; ?></td>
                            <td><?php echo $vendor['vendor_country']; ?></td>
                            <td><?php echo $vendor['vendor_address']; ?></td>
                            <td class="tar action_column">
                                <?php if (has_permission('edit_vendor')): ?>
                                    <div class="btn-toolbar">
                                        <?php
                                        echo linkButtonGenerator(array(
                                            'href' => build_url(array('action' => 'manage_vendors', 'edit' => $vendor['pk_vendor_id'])),
                                            'action' => 'edit',
                                            'icon' => 'icon_edit',
                                            'text' => 'Edit',
                                            'title' => 'Edit Company',
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