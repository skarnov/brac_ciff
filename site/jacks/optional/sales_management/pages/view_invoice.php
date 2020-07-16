<?php

if (!has_permission('add_sale')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit','action')));
    exit();
}

require_once(common_files('absolute').'/FPDF/class.dev_pdf.php');

$invoice_id = $_GET['id'] ? $_GET['id'] : null;

if (!$invoice_id) {
    add_notification('Please select an invoice to view detail.', 'error');
    header('Location:' . $myUrl);
    exit();
}

$args = array(
    'select_fields' => array(
        'dev_branches.branch_name',
        'dev_branches.branch_address',
        'dev_branches.branch_sub_district',
        'dev_branches.branch_district',
        'dev_branches.branch_division',
        'dev_branches.branch_contact_number',
        'dev_sales.pk_sale_id',
        'dev_sales.invoice_date',
        'dev_sales.create_date',
        'dev_sales.sale_total',
        'dev_sales.sale_discount',
        'dev_customers.customer_id',
        'dev_customers.full_name',
        'dev_customers.customer_mobile',
        'dev_customers.present_house',
        'dev_customers.present_road',
        'dev_customers.present_sub_district',
        'dev_customers.present_district',
        'dev_customers.present_division',
    ),
    'sale_id' => $invoice_id,
    'single' => true,
);
$the_invoice = $this->get_invoices($args);

$the_invoice_details = $this->get_invoice_details(array('sale_id' => $invoice_id, 'select_fields' => array(
        'dev_products.product_name',
        'dev_sale_items.item_quantity',
        'dev_sale_items.item_price',
        'dev_sale_items.item_total_price',
    ), 'single' => false));

if (!$the_invoice) {
    add_notification('Invoice information wasn\'t found.', 'error');
    header('Location:' . $myUrl);
    exit();
}

if($_GET['reportToPdf']){
    $companyInfo = array(
        'company_logo' => null,
        'company_title' => 'BRAC MIS '. $the_invoice['branch_name'].' Branch',
        'company_address' => $the_invoice['branch_address'].' '. $the_invoice['branch_sub_district'].' '. $the_invoice['branch_district'].' '. $the_invoice['branch_division'],
        'company_mobile' => $the_invoice['branch_contact_number'],
        'company_email' => 'info@brac.net'
    );

    $devPdf = new DEV_PDF('P','mm','A4');
    $devPdf->init();
    $devPdf->createPdf();

    $devPdf->defaultPDFHeader($companyInfo,0,0);

    $leftSideData = array(
        'CUSTOMER' => '',
        'ID: ' => $the_invoice['customer_id'],
        'Name: ' => $the_invoice['full_name'],
        'Cell: ' => $the_invoice['customer_mobile'],
        'Address: ' => 'House-'. $the_invoice['present_house'].', Road-'. $the_invoice['present_road'].', Sub-district- '.$the_invoice['present_sub_district'].', District- '.$the_invoice['present_district'].', Division- '.$the_invoice['present_division']
    );

    $rightSideData = array(
        'SALES INVOICE' => '',
        'ID: ' => $the_invoice['pk_sale_id'],
        'Invoice Date: ' => date_to_user($the_invoice['invoice_date']),
        'Entry Date: ' => date_to_user($the_invoice['create_date']),
    );

    $devPdf->defaultLeftSideRightSidePrint($leftSideData, $rightSideData, 12);

    $headers = array(
        array('text' => '#', 'align' => 'L', 'width' => 5),
        array('text' => 'Product', 'align' => 'L', 'width' => 35),
        array('text' => 'Quantity', 'align' => 'R', 'width' => 12),
        array('text' => 'Rate', 'align' => 'R', 'width' => 18),
        array('text' => 'Total', 'align' => 'R', 'width' => 30),
    );

    $reportData = array();
    $count = 1;
    foreach($the_invoice_details['data'] as $i=>$v){
        $reportData[] = array(
            $count++,
            $v['product_name'],
            $v['item_quantity'],
            numberToCurrency($v['item_price']),
            numberToCurrency($v['item_total_price'])
        );
    }

    $footer = array(
        array('merged' => 4,'text' => 'TOTAL','align' => 'R'),
        array('text' => numberToCurrency($the_invoice['sale_total'] + $the_invoice['sale_discount']))
    );

    $devPdf->resetOptions();
    $devPdf->setOption('headers', $headers);
    $devPdf->setOption('data', $reportData);
    $devPdf->setOption('footers', $footer);
    $devPdf->addTable();

    $reportData = array();

    $extFooterData = array(
        'Discount' => numberToCurrency($the_invoice['sale_discount']),
        'Grand Total' => numberToCurrency($the_invoice['sale_total']),
    );

    foreach($extFooterData as $i=>$v){
        $reportData[] = array(
            array('merged' => 4, 'text' => $i, 'align' => 'R'),
            array('text' => $v)
        );
    }

    $devPdf->setOption('data', $reportData);
    $devPdf->addTable(false, true, false);

    $devPdf->SetTitle('Sales Invoice - '.$the_invoice['pk_sale_id'],true);

    $devPdf->outputPdf($_GET['mode'], 'Sales Invoice - '.$the_invoice['pk_sale_id'].'.pdf');
    exit();
}

doAction('render_start');
?>
<div class="page-header">
    <h1>Invoice Details</h1>
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
<div class="panel">
    <?php DEV_PDF::reportControlPanel(); ?>
    <div class="panel-body">
        <div id='DivIdToPrint'>
            <div class="sales_invoice">
                <div style="text-align: center">
                    <h3><?php echo 'BRAC MIS '. $the_invoice['branch_name'].' Branch' ?></h3>
                    <h5><?php echo $the_invoice['branch_address'].' '. $the_invoice['branch_sub_district'].' '. $the_invoice['branch_district'].' '. $the_invoice['branch_division'] ?></h5>
                    <h5><?php $the_invoice['branch_contact_number']?> | info@brac.net</h5>
                </div>
                <div style="float:left;">
                    <strong>Customer ID: </strong><?php echo $the_invoice['customer_id'] ?><br />
                    <?php echo $the_invoice['full_name']; ?><br />
                    Cell: <?php echo $the_invoice['customer_mobile'] ?><br />
                    Address: <?php echo 'House-'. $the_invoice['present_house'].', Road-'. $the_invoice['present_road'].', Sub-district- '.$the_invoice['present_sub_district'].', District- '.$the_invoice['present_district'].', Division- '.$the_invoice['present_division'] ?>
                </div>
                <div style="float:right">
                    <div class="text-bg"><strong>SALES INVOICE</strong></div>
                    <strong>Invoice ID: </strong><?php echo $the_invoice['pk_sale_id'] ?><br />
                    <strong>Invoice Date: </strong><?php echo date('d-m-Y', strtotime($the_invoice['invoice_date'])) ?><br />
                    <strong>Entry Date: </strong><?php echo date('d-m-Y', strtotime($the_invoice['create_date'])) ?>
                </div>
            </div>
            <div style="float:none;clear:both;">&nbsp;</div>
            <table class="background" style="width:100%;margin-bottom:20px;border-top: 1px solid #ddd;">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th class="tar">Quantity</th>
                    <th class="tar">Rate</th>
                    <th class="tar">Total</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 1;
                foreach ($the_invoice_details['data'] as $item) {
                    ?>
                    <tr style="border-top: 1px solid #ddd">
                        <td><?php echo $count++ ?></td>
                        <td><?php echo $item['product_name'] ?></td>
                        <td class="tar"><?php echo $item['item_quantity'] ?></td>
                        <td class="tar"><?php echo numberToCurrency($item['item_price']) ?></td>
                        <td class="tar"><?php echo numberToCurrency($item['item_total_price']) ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr style="border-top: 3px solid #ddd;">
                    <td style="text-align: right" colspan="4" ><strong>Sub-Total</strong></td>
                    <td style="text-align: right"><?php echo numberToCurrency($the_invoice['sale_total'] + $the_invoice['sale_discount']) ?></td>
                </tr>
                <tr style="border-top: 1px solid #ddd">
                    <td style="text-align: right" colspan="4" ><strong>Discount</strong></td>
                    <td style="text-align: right"><?php echo numberToCurrency($the_invoice['sale_discount']) ?></td>
                </tr>
                <tr style="border-top: 1px solid #ddd">
                    <td style="text-align: right" colspan="4" ><strong>Grand Total</strong></td>
                    <td style="text-align: right"><?php echo numberToCurrency($the_invoice['sale_total']) ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php DEV_PDF::reportControlPanel(true); ?>
</div>