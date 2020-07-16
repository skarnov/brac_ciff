<?php
if ($_GET['ajax_type'] == 'get_prices') {
    $stockID = $_POST['stock_id'];
    $all_prices = $this->get_prices(array('stock_id' => $stockID, 'single' => false));

    ob_start();
    ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($all_prices['data'] as $value):
                ?>
                <tr>
                    <td><?php echo $value['create_date'] ?></td>
                    <td><?php echo $value['item_price'] ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <?php
    $output = ob_get_clean();
    echo json_encode(array('success' => $output));
    exit();
}

$edit = $_GET['edit'] ? $_GET['edit'] : null;

$pre_data = array();
if ($edit) {
    $pre_data = $this->get_stocks(array('stock_id' => $edit, 'select_fields' => array(
            'dev_stocks.fk_project_id',
            'dev_stocks.fk_branch_id',
            'dev_stocks.fk_product_id',
            'dev_stocks.stock_in',
            'dev_stocks.expiration_date',
            'dev_stocks.stock_quantity',
            'dev_stocks.buying_price',
            'dev_stocks.sale_price',
        ), 'single' => true));
    if (!$pre_data) {
        add_notification('Invalid stock, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_branch = $_GET['branch_id'] ? $_GET['branch_id'] : null;
$branch_id = $_config['user']['user_branch'];
$branch = $branch_id ? $branch_id : $filter_branch;
$empty_stock = $_GET['empty_stock'] ? $_GET['empty_stock'] : null;

$args = array(
    'select_fields' => array(
        'dev_stocks.pk_stock_id',
        'dev_branches.branch_name',
        'dev_products.product_name',
        'dev_stocks.stock_in',
        'dev_stocks.expiration_date',
        'dev_stocks.stock_quantity',
        'dev_stocks.buying_price',
        'dev_stocks.sale_price',
    ),
    'project_id' => $filter_project,
    'branch_id' => $branch,
    'stock_quantity' => false,
    'empty_stock' => $empty_stock,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_stock_id',
        'order' => 'DESC'
    ),
);

$stocks = $this->get_stocks($args);
$pagination = pagination($stocks['total'], $per_page_items, $start);

$projects = jack_obj('dev_project_management');
$branchManager = jack_obj('dev_branch_management');
$products = jack_obj('dev_product_management');

$all_projects = $projects->get_projects(array('single' => false));
$all_branches = $branchManager->get_branches(array('select_fields' => array('branches.pk_branch_id', 'branches.branch_name'), 'data_only' => true));
$all_items = $products->get_products();

if ($_GET['download_csv']) {
    unset($args['limit_by']);
    $args['data_only'] = true;
    $data = $this->get_stocks($args);
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'stocks-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Stock List', '');
    fputcsv($fh, $report_title);

    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array('#', 'Stock Name', 'In', 'Expiration', 'Quantity', 'Buying Price', 'Sale Price');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['product_name']
                , $user['stock_in']
                , $user['expiration_date']
                , $user['stock_quantity'] . "\r"
                , $user['buying_price'] . "\r"
                , $user['sale_price'] . "\r");
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
    if (!checkPermission($edit, 'add_stock', 'edit_stock')) {
        add_notification('You don\'t have enough permission.', 'error');
        header('Location:' . build_url(NULL, array('edit', 'action')));
        exit();
    }
    $data = array(
        'required' => array(
            'product_id' => 'Select Stock',
        ),
    );
    $data['form_data'] = $_POST;
    $data['edit'] = $edit;

    $ret = $this->add_edit_stock($data);

    if ($ret['success']) {
        $msg = "Information of stock has been " . ($edit ? 'updated.' : 'saved.');
        add_notification($msg);
        $activityType = $edit ? 'update' : 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_stock_management/manage_stocks'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

doAction('render_start');
?>
<div class="page-header">
    <h1>All Stocks</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&project_id=' . $filter_project . '&branch_id=' . $filter_branch,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download Stock List',
                'title' => 'Download Total Stock',
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&empty_stock=1&project_id=' . $filter_project . '&branch_id=' . $filter_branch,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Empty Stock List',
                'title' => 'Download Empty Stock',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<div class="form-group col-sm-2">
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
<?php if (!$branch_id): ?>
    <div class="form-group col-sm-2">
        <label>Branch</label>
        <select class="form-control" id="select_holder_project_id" name="branch_id">
            <option value="">Select One</option>
            <?php
            foreach ($all_branches['data'] as $i => $v) {
                ?>
                <option value="<?php echo $v['pk_branch_id'] ?>" <?php if ($filter_branch == $v['pk_branch_id']) echo 'selected' ?>><?php echo $v['branch_name'] ?></option>
                <?php
            }
            ?>
        </select>
    </div>
<?php endif ?>
<?php
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="row">
    <div class="col-md-4">
        <form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
            <div class="panel">
                <div class="panel-body">
                    <div class="form-group">
                        <label>Select Project</label>
                        <select class="form-control" name="project_id">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_projects['data'] as $value) :
                                ?>
                                <option value="<?php echo $value['pk_project_id'] ?>" <?php
                                if ($value['pk_project_id'] == $pre_data['fk_project_id']) {
                                    echo 'selected';
                                }
                                ?>><?php echo $value['project_short_name'] ?></option>
                                    <?php endforeach ?>
                        </select>
                    </div>
                    <?php if (!$branch_id): ?>
                        <div class="form-group">
                            <label>Select Branch</label>
                            <select required class="form-control" name="branch_id">
                                <option value="">Select One</option>
                                <?php
                                foreach ($all_branches['data'] as $value) :
                                    ?>
                                    <option value="<?php echo $value['pk_branch_id'] ?>" <?php
                                    if ($value['pk_branch_id'] == $pre_data['fk_branch_id']) {
                                        echo 'selected';
                                    }
                                    ?>><?php echo $value['branch_name'] ?></option>
                                        <?php endforeach ?>
                            </select>
                        </div>
                    <?php endif ?>
                    <?php if ($branch_id): ?>
                        <input type="hidden" name="branch_id" value="<?php echo $_config['user']['user_branch'] ?>"/>
                    <?php endif ?>
                    <div class="form-group">
                        <label>Add Stock</label>
                        <select required class="form-control" name="product_id">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_items['data'] as $item) :
                                ?>    
                                <option value="<?php echo $item['pk_product_id'] ?>" <?php if ($item['pk_product_id'] == $pre_data['fk_product_id']) echo 'selected' ?>><?php echo $item['product_name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>In Date</label>
                        <div class="input-group">
                            <input id="inDate" type="text" class="form-control" name="stock_in" value="<?php echo $pre_data['stock_in'] && $pre_data['stock_in'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['stock_in'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('inDate');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Expiration Date</label>
                        <div class="input-group">
                            <input id="expirationDate" type="text" class="form-control" name="expiration_date" value="<?php echo $pre_data['expiration_date'] && $pre_data['expiration_date'] != '0000-00-00' ? date('d-m-Y', strtotime($pre_data['expiration_date'])) : date('d-m-Y'); ?>">
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('expirationDate');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input required class="form-control" type="text" name="stock_quantity" value="<?php echo $pre_data['stock_quantity'] ? $pre_data['stock_quantity'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Buying Price</label>
                        <input required class="form-control" type="text" name="buying_price" value="<?php echo $pre_data['buying_price'] ? $pre_data['buying_price'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Sale Price</label>
                        <input required class="form-control" type="text" name="sale_price" value="<?php echo $pre_data['sale_price'] ? $pre_data['sale_price'] : ''; ?>">
                    </div>
                </div>
                <div class="panel-footer tar">
                    <a href="<?php echo url('admin/dev_stock_management/manage_stocks') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
                    <?php
                    echo submitButtonGenerator(array(
                        'action' => $edit ? 'update' : 'update',
                        'size' => '',
                        'id' => 'submit',
                        'title' => $edit ? 'Update The Stock' : 'Save The Stock',
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
                <?php echo searchResultText($stocks['total'], $start, $per_page_items, count($stocks['data']), 'stocks') ?>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th style="width: 20%">Branch</th>
                        <th style="width: 30%">Name</th>
                        <th style="width: 10%">In</th>
                        <th style="width: 10%">Expire</th>
                        <th style="width: 5%">Qty</th>
                        <th style="width: 10%">Buy</th>
                        <th style="width: 15%">Sale</th>
                        <th class="tar action_column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($stocks['data'] as $i => $stock) {
                        ?>
                        <tr>
                            <td><?php echo $stock['branch_name']; ?></td>
                            <td><?php echo $stock['product_name']; ?></td>
                            <td><?php echo $stock['stock_in']; ?></td>
                            <td><?php echo $stock['expiration_date']; ?></td>
                            <td><?php echo $stock['stock_quantity']; ?></td>
                            <td><?php echo $stock['buying_price']; ?></td>
                            <td><?php echo $stock['sale_price']; ?></td>
                            <td class="tar action_column">
                                <?php if (has_permission('edit_stock')): ?>
                                    <div class="btn-toolbar">
                                        <?php
                                        echo linkButtonGenerator(array(
                                            'href' => build_url(array('action' => 'manage_stocks', 'edit' => $stock['pk_stock_id'])),
                                            'action' => 'edit',
                                            'icon' => 'icon_edit',
                                            'text' => 'Edit',
                                            'title' => 'Edit Stock',
                                        ));
                                        echo linkButtonGenerator(array(
                                            'href' => 'javascript:',
                                            'classes' => 'view_price',
                                            'attributes' => array('data-id' => $stock['pk_stock_id']),
                                            'action' => 'add',
                                            'icon' => 'icon_add',
                                            'text' => 'Prices',
                                            'title' => 'Price Management',
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
            <div class="dn">
                <div id="ajax_form_container"></div>
            </div>
        </div>
    </div>
</div>
<script>
    init.push(function () {
        $(document).on('click', '.view_price', function () {
            var ths = $(this);
            var data_id = ths.attr('data-id');
            new in_page_add_event({
                show_submit_btn: false,
                edit_mode: false,
                edit_form_url: '<?php echo build_url(array('ajax_type' => 'get_prices')) ?>',
                form_title: 'Price History',
                form_container: $('#ajax_form_container'),
                ths: ths,
                url: '<?php echo build_url(array('ajax_type' => 'put_sessions')) ?>',
                additional_data: {'stock_id': data_id},
            });
        });
    });
</script>