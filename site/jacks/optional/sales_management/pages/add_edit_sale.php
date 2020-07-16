<?php

if (!has_permission('add_sale')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit','action')));
    exit();
}

$stockManager = jack_obj('dev_stock_management');
$branchManager = jack_obj('dev_branch_management');
$projects = jack_obj('dev_project_management');
$customers = jack_obj('dev_customer_management');

$all_customers = $customers->get_customers(array('select_fields' => array('pk_customer_id', 'full_name', 'customer_id'), 'customer_type' => 'potential', 'data_only' => true));
$branches = $branchManager->get_branches(array('select_fields' => array('branches.pk_branch_id', 'branches.branch_name'), 'data_only' => true));
$all_projects = $projects->get_projects(array('single' => false));

$args = array(
    'select_fields' => array(
        'dev_stocks.fk_branch_id',
        'dev_stocks.fk_product_id',
        'dev_stocks.stock_quantity',
        'dev_stocks.buying_price',
        'dev_stocks.sale_price',
        'dev_products.pk_product_id',
        'dev_products.product_name',
    ),
    'branch_id' => $_config['user']['user_branch'] ? $_config['user']['user_branch']: 0,
    'stock_quantity' => true,
);
$_stocks = $stockManager->get_stocks($args);

$stocks = array();
if ($_stocks['data']) {
    foreach ($_stocks['data'] as $i => $v) {
        $stocks[$v['fk_product_id']] = $v;
    }
}

if ($_POST['ajax_type']) {
    if ($_POST['ajax_type'] == 'selectStaff') {
        $staffs = jack_obj('dev_staff_management');
        $all_staffs = $staffs->get_staffs(array('branch' => $_POST['branch_id']));
        echo "<option value=''>If Any</option>";
        foreach ($all_staffs['data'] as $staff) {
            if ($staff['pk_user_id'] == $pre_data['fk_staff_id']) {
                echo "<option value='" . $staff['pk_user_id'] . "' selected>" . $staff['user_fullname'] . "</option>";
            } else {
                echo "<option value='" . $staff['pk_user_id'] . "'>" . $staff['user_fullname'] . "</option>";
            }
        }
    }
    exit();
}

if ($_POST) {
    $data = array(
        'required' => array(
            'fk_customer_id' => 'Customer Name',
        ),
    );
    $data['form_data'] = $_POST;
    
    $total_amount = $data['form_data']['total_amount'];
    if(!($total_amount > 0)){
        add_notification('Sale Cannot Be Zero', 'error');
        header('location: ' . url('admin/dev_sale_management/manage_sales?action=add_edit_sale'));
        exit();        
    }
    
    $sale_total = $data['form_data']['sale_total'];
    if(!is_numeric($sale_total)){
        add_notification('Discout Must Be A Number', 'error');
        header('location: ' . url('admin/dev_sale_management/manage_sales?action=add_edit_sale'));
        exit();
    }
    
    foreach ($data['form_data']['invoice_detail'] as $item) {
        global $devdb;

        $product_id = $item['fk_product_id'];
        $order_quantity = $item['item_quantity'];
       
        $result = $devdb->query("SELECT pk_stock_id FROM dev_stocks WHERE fk_product_id = '$product_id' AND stock_quantity >= '$order_quantity'");

        if (!$result) {
            $msg = 'Stock Inability!';
            add_notification($msg, 'error');
            header('location: ' . url('admin/dev_sale_management/manage_sales'));
            exit();
        }
    }
    $data['sale_title'] = 'Stock Sale';
    $ret = $this->add_sale($data);

    if ($ret['sale_income_insert']['success']) {
        $msg = 'Success';
        add_notification($msg);
        $activityType = 'create';
        user_activity::add_activity($msg, 'success', $activityType);
        header('location: ' . url('admin/dev_sale_management/manage_sales'));
        exit();
    } else {
        $pre_data = $_POST;
        print_errors($ret['error']);
    }
}

doAction('render_start');
?>
<div class="page-header">
    <h1>New Sale</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'Manage Sales',
                'title' => 'Manage All Sales',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<form class="preventDoubleClick" method="post" action="">
    <div class="row">
        <div class="col-sm-9">
            <div class="table-primary">
                <div class="table-header">POS</div>
                <table id="myTable" class="table table-bordered table-stripped table-condensed">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 350px;">Item</th>
                            <th class="tar">Quantity</th>
                            <th class="tar">Stock Price</th>
                            <th class="tar">Selling Price</th>
                            <th class="tar">Total</th>
                            <th class="tar">...</th>
                        </tr>
                    </thead>
                    <tbody class="the_stocks">

                    </tbody>
                </table>
                <div class="table-footer">
                    <a class="btn btn-info add_row" href="javascript:"><i class="fa fa-plus"></i>&nbsp;Add Product</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-dark panel-info">
                <div class="panel-heading">
                    <span class="panel-title">Sales Information</span>
                </div>
                <div class="panel-body p5">
                    <div class="form-group">
                        <label>Date</label>
                        <div class="input-group date">
                            <span class="input-group-addon the_calendar_icon"><i class="fa fa-calendar"></i></span>
                            <input id="invoice_date" type="text" class="form-control" name="invoice_date" value="<?php echo date('d-m-Y') ?>" />
                            <span class="input-group-addon clear_date"><i class="text-danger fa fa-times"></i></span>
                        </div>
                        <script type="text/javascript">
                            init.push(function () {
                                _datepicker('invoice_date');
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Project</label>
                        <select required class="form-control" name="fk_project_id">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_projects['data'] as $i => $v) {
                                ?>
                                <option value="<?php echo $v['pk_project_id'] ?>"><?php echo $v['project_short_name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group" <?php if($_config['user']['user_branch']) echo "style='display: none'"; ?>>
                        <label>Branch</label>
                        <select required id="branchId" class="form-control" name="fk_branch_id">
                            <option value="">Select One</option>
                            <?php
                            foreach ($branches['data'] as $i => $v) {
                                ?>
                                <option value="<?php echo $v['pk_branch_id'] ?>" <?php if($_config['user']['user_branch'] == $v['pk_branch_id']) echo 'selected' ?>><?php echo $v['branch_name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Incentive Staff</label>
                        <select class="form-control" data-selected="<?php echo $pre_data['fk_staff_id'] ? $pre_data['fk_staff_id'] : ''; ?>" id="availableStaffs" name="staff_id">

                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Customer</label>
                        <select required class="form-control" name="fk_customer_id">
                            <option value="">Select One</option>
                            <?php
                            if ($all_customers['data']) {
                                foreach ($all_customers['data'] as $i => $v) {
                                    $selected = $the_invoice['fk_customer_id'] && $the_invoice['fk_customer_id'] == $v['pk_customer_id'] ? 'selected' : '';
                                    ?>
                                    <option <?php echo $selected ?> value="<?php echo $v['pk_customer_id'] ?>"><?php echo $v['full_name'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <table class="mb0 table table-condensed">
                        <tr>
                            <td>Total Amount</td>
                            <td class="tar" style="padding-right: 18px">
                                <span class="textBoxSpan" id="total_amount"></span>
                                <input type="hidden" id="total_amount_box" name="total_amount" />
                            </td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td class="tar">
                                <div class="form-group mb0">
                                    <input class="form-control tar" type="text" name="discount" id="discount" value="0">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Grand Total</td>
                            <input type="hidden" name="sale_total" id="grand_total_box"/>
                            <td class="tar" style="padding-right: 18px">
                                <span class="textBoxSpan" id="grand_total"></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button type="submit" class="return_btn form-control btn btn-success">COMPLETE</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    var count_empty_row = '';
    var stocks = <?php echo json_encode($stocks) ?>;

    init.push(function () {
        function fix_serial_number() {
            var itemCount = 1;
            $('.serialNumber').each(function (i, e) {
                $(e).html(itemCount++);
            });
        }

        $('#discount').keyup(function () {
            var total_amount = $('#total_amount').html();
            var discount = $('#discount').val();
            $('#grand_total').html(parseFloat(total_amount) - parseFloat(discount));
            $('#grand_total_box').val(parseFloat(total_amount) - parseFloat(discount));
        });

        $("select[name='fk_branch_id']").change(function () {
            $('#availableStaffs').html('');
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    data: {
                        'branch_id': stateID,
                        'ajax_type': 'selectStaff'
                    },
                    success: function (data) {
                        $('#availableStaffs').html(data);
                    }
                });
            }
        }).change();
        
        function get_stock_options($data) {
            var html = '';
            if ($data) {
                html += '<option selected value="' + $data['item']['pk_product_id'] + '">' + $data['item']['product_name'] + '</option>';
            } else {
                for (var i in stocks) {
                    ths_item = stocks[i];
                    html += '<option value="' + ths_item['pk_product_id'] + '">' + ths_item['product_name'] + '</option>';
                }
            }
            return html;
        }

        function get_empty_row($data) {
            count_empty_row++;
            $pointer = count_empty_row;

            var empty_row = '<tr class="each_row empty_row"><td class="tac vam"><span class="serialNumber"></span></td>\
                    <td style="width: 350px;">\
                        <select ' + ($data ? 'readonly' : '') + ' class="' + ($data ? '' : 'adv_select_' + $pointer) + ' form-control the_item" required name="invoice_detail[' + $pointer + '][fk_product_id]">\
                            ' + ($data ? '' : '<option value="">---</option>') + '\
                            ' + get_stock_options($data) + '\
                        </select>\
                    </td>\
                    <td class="tar">\
                        <input type="text" step="1" class="tar quantity form-control" name="invoice_detail[' + $pointer + '][item_quantity]" value="' + ($data ? $data['item_quantity'] : '1') + '">\
                    </td>\
                    <td class="tar">\
                        <input readonly type="text" class="tar selling_rate_span form-control" name="invoice_detail[' + $pointer + '][buying_price]" value="0">\
                    </td>\
                    <td class="tar">\
                        <input readonly type="text" class="tar unit_price form-control" name="invoice_detail[' + $pointer + '][item_price]" value="' + ($data ? $data['item_price'] : '0') + '">\
                    </td>\
                    <td class="tar">\
                        <input readonly type="text" class="tar total_price form-control" name="invoice_detail[' + $pointer + '][item_total_price]" value="' + ($data ? $data['item_total_price'] : '0') + '">\
                    </td>\
                    <td class="tar">\
                        <i class="fa fa-times btn btn-danger btn-xxs remove_row"></i>\
                    </td>\
                </tr>';
            return empty_row;
        }

        $(document).on('change', '.the_item', function () {
            var ths = $(this);
            var this_row = ths.closest('.each_row');
            if (!ths.val()) {
                this_row.find('.quantity').val('0');
                this_row.find('.selling_rate_span').val('0');
                this_row.find('.unit_price').val('0');
            }
            var ths_item = stocks[ths.val()];

            if (ths_item) {
                this_row.find('.selling_rate_span').val(ths_item['buying_price']);
                this_row.find('.unit_price').val(ths_item['sale_price']);
                this_row.find('.total_price').val(ths_item['sale_price']);
                $('.quantity').keyup(function () {
                    var quantity = this_row.find('.quantity').val();
                    var unit_price = this_row.find('.unit_price').val();
                    this_row.find('.total_price').val(parseFloat(quantity) * parseFloat(unit_price));
                    calculate_invoice();
                });
                calculate_invoice();
            }
        });

        function calculate_invoice() {
            var calculated_total_sum = 0;
            $(".total_price").each(function () {
                var get_textbox_value = $(this).val();
                if ($.isNumeric(get_textbox_value)) {
                    calculated_total_sum += parseFloat(get_textbox_value);
                }
            });
            $("#total_amount").html(calculated_total_sum);
            $("#total_amount_box").val(calculated_total_sum);
            var total_amount = $('#total_amount').html();
            var discount = $('#discount').val();
            $('#grand_total').html(parseFloat(total_amount) - parseFloat(discount));
            $('#grand_total_box').val(parseFloat(total_amount) - parseFloat(discount));
        }

        if (!$('.the_stocks .empty_row').length) {
            add_empty_row(null);
        }

        function add_empty_row($data) {
            $('.the_stocks').append(get_empty_row($data));
            bind_remove_row_event();
            $(".adv_select_" + count_empty_row).select2();
            fix_serial_number();
        }

        $('.add_row').click(function () {
            add_empty_row(null);
        });

        function bind_remove_row_event() {
            $(document).off('click', '.remove_row').on('click', '.remove_row', function () {
                $(this).closest('.each_row').remove();
                calculate_invoice();
                fix_serial_number();
            });
        }
        
        $(document).off('click', '.remove_row').on('click', '.remove_row', function () {
            $(this).closest('.each_row').remove();
            calculate_invoice();
            fix_serial_number();
        });
    });
</script>
