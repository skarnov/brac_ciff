<?php

$edit = $_GET['edit'] ? $_GET['edit'] : null;

if (!checkPermission($edit, 'add_product', 'edit_product')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit','action')));
    exit();
}

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_products(array('product_id' => $edit, 'single' => true));    
    if (!$pre_data) {
        add_notification('Invalid product, no data found.', 'error');
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
        'col' => 'pk_product_id',
        'order' => 'DESC'
    ),
);

$products = $this->get_products($args);
$pagination = pagination($products['total'], $per_page_items, $start);

if ($_POST['ajax_type']) {
    if ($_POST['ajax_type'] == 'uniqueSKU') {
        global $devdb;
        $sql = "SELECT pk_product_id FROM dev_products WHERE product_sku = '" . $_POST['valueToCheck'] . "' LIMIT 1";
        $ret = $devdb->get_row($sql);
        if ($ret) {
            echo json_encode('0');
        } else {
            echo json_encode('1');
        }
    }
    exit();
}

if ($_GET['download_csv']) {
    unset($args['limit_by']);
    $args['data_only'] = true;
    $data = $this->get_products($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'products-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Product List', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);
    
    $headers = array('#', 'Item Name', 'Type', 'SKU');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['product_name']
                , $user['product_type']
                , $user['product_sku']."\r");
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
    $data = array(
        'required' => array(
            'product_name' => 'Product Name',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_product($data);

    if ($ret['success']) {
        $msg = "Information of product has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_product_management/manage_products'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

doAction('render_start');
?>
<div class="page-header">
    <h1>All Products</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1',
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download Products List',
                'title' => 'Download Products List',
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
                        <label>Item Name</label>
                        <input class="form-control char_limit" data-max-char="490" type="text"  name="product_name" value="<?php echo $pre_data['product_name'] ? $pre_data['product_name'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Item Type</label>
                        <select class="form-control" name="product_type">
                            <option value="">Select One</option>
                            <option value="product" <?php if ($pre_data['product_type'] == 'product') { echo 'selected';} ?>>Product</option>
                            <option value="service" <?php if ($pre_data['product_type'] == 'service') { echo 'selected';} ?>>Service</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>SKU</label>
                        <div class="input-group">
                            <input data-ajax-type="uniqueSKU" id="sku" data-error-message="SKU number is already in use" class="verifyUnique form-control" type="text" name="product_sku" value="<?php echo $pre_data['product_sku'] ? $pre_data['product_sku'] : ''; ?>" required="">
                            <span class="input-group-addon"></span>
                        </div>
                        <p class="help-block"></p>
                    </div>
                </div>
                <div class="panel-footer tar">
                    <a href="<?php echo url('admin/dev_product_management/manage_products') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
                    <?php
                    echo submitButtonGenerator(array(
                        'action' => $edit ? 'update' : 'update',
                        'size' => '',
                        'id' => 'submit',
                        'title' => $edit ? 'Update' : 'Save',
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
                <?php echo searchResultText($products['total'], $start, $per_page_items, count($products['data']), 'products') ?>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Type</th>
                        <th>SKU</th>
                        <th class="tar action_column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($products['data'] as $i => $product) {
                        ?>
                        <tr>
                            <td><?php echo $product['product_name']; ?></td>
                            <td style="text-transform: capitalize"><?php echo $product['product_type']; ?></td>
                            <td><?php echo $product['product_sku']; ?></td>
                            <td class="tar action_column">
                                <?php if (has_permission('edit_product')): ?>
                                    <div class="btn-toolbar">
                                        <?php
                                        echo linkButtonGenerator(array(
                                            'href' => build_url(array('action' => 'manage_products', 'edit' => $product['pk_product_id'])),
                                            'action' => 'edit',
                                            'icon' => 'icon_edit',
                                            'text' => 'Edit',
                                            'title' => 'Edit Product',
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
    init.push(function () {
        $('.verifyUnique').keyup(function () {
            var ths = $(this);
            var container = ths.closest('.form-group');
            container.find('.input-group-addon').html('&nbsp;');
            container.removeClass('has-error');
            container.find('.help-block').html('');
        });
        $('.verifyUnique').blur(function () {
            var ths = $(this);
            var thsVal = ths.val();

            if (!thsVal.length)
                return true;
            var ajaxType = ths.attr('data-ajax-type');
            var messageError = ths.attr('data-error-message');

            var container = ths.closest('.form-group');
            var helpContainer = container.find('.help-block');
            var symbolContainer = container.find('.input-group-addon');

            container.removeClass('has-error');
            helpContainer.html('');
            symbolContainer.html('&nbsp;');

            var data = {
                ajax_type: ajaxType,
                valueToCheck: thsVal,
            };

            basicAjaxCall({
                beforeSend: function () {
                    show_button_overlay_working(ths);
                },
                complete: function () {
                    hide_button_overlay_working(ths);
                },
                data: data,
                success: function (ret) {
                    if (ret === '1') {
                        symbolContainer.html('<i class="fa fa-check text-success"></i>').show();
                        $('#submit').prop('disabled', false);
                    } else if (ret === '0') {
                        container.addClass('has-error');
                        helpContainer.html(messageError);
                        symbolContainer.html('<i class="fa fa-exclamation-triangle text-danger"></i>').show();
                        $('#submit').prop('disabled', true);
                    }
                }
            });
        });
        
    });
</script>