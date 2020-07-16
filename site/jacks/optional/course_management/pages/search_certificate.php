<?php
require_once(common_files('absolute').'/FPDF/class.dev_pdf.php');

if($_GET['toPdf']){
    $certificate_id = $_GET['certificate_id'];
    $result = $this->get_customer_results(array('single' => true, 'certificate_id' => $certificate_id));

    $devPdf = new DEV_PDF('L','mm','A4');

    $devPdf->init();

    $devPdf->setOption('page_header', false);
    $devPdf->setOption('page_footer', false);

    $devPdf->createPdf();

    $pathToCertificateBG = _path('admin').'/assets/images/language-training-certificate.jpg';

    $devPdf->Image($pathToCertificateBG,$devPdf->GetX(),$devPdf->GetY(),$devPdf->GetPageWidth()-25.4,$devPdf->GetPageHeight()-25.4);

    $devPdf->SetXY(12.7,12.7);

    $certificateID = $result['customer_id'].'/'.$result['pk_result_id'];
    $customerName = $result['full_name'];
    $courseName = $result['course_name'];
    $heldOn = $result['admission_date'];

    $devPdf->SetXY(25,25);
    $devPdf->SetFont('Helvetica','','10');
    $devPdf->Cell(0,5, $certificateID);

    $w = $devPdf->pageContentWidth - 90;

    $devPdf->SetXY(56,99);
    $devPdf->SetFont('Helvetica','','14');
    $devPdf->Cell($w,5, $customerName,0, 0, 'C');

    $devPdf->SetXY(122,120);
    //$devPdf->SetFont('Helvetica','','14');
    $devPdf->Cell(0,5, $courseName);

    $devPdf->SetXY(132,132);
    //$devPdf->SetFont('Helvetica','B','20');
    $devPdf->Cell(0,5, $heldOn);

    $devPdf->outputPdf('I', 'Certificate-'.$certificateID . '.pdf');
    exit();

}
if ($_POST) {
    $data = array(
        'required' => array(
            'customer_id' => 'Customer ID',
        ),
    );
    $data['customer_id'] = $_POST['customer_id'];
    $result = $this->get_customer_results($data);   
}

doAction('render_start');
?>
<div class="page-header">
    <h1>Certificate Creation</h1>
</div>
<div class="row">
    <div class="col-md-4">
        <form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
            <div class="panel">
                <div class="panel-body">
                    <div class="form-group">
                        <label>Enter Customer ID</label>
                        <input class="form-control" type="text" name="customer_id" value="<?php echo $data['customer_id'] ? $data['customer_id'] : ''; ?>" required="">
                    </div>
                </div>
                <div class="panel-footer tar">
                    <a href="<?php echo url('admin/dev_course_management/search_certificate') ?>" class="btn btn-flat btn-labeled btn-danger"><span class="btn-label icon fa fa-times"></span>Cancel</a>
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
        </form>
    </div>
    <div class="col-md-8">
        <div class="table-primary table-responsive">
            <div class="table-header">
                Customer Result
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Batch Name</th>
                        <th>Result</th>
                        <th class="tar action_column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result['data'] == null) {
                        ?>
                        <tr>
                            <td style="color:red" colspan="6">Not Found</td>
                        </tr>
                        <?php
                    } else {
                        foreach ($result['data'] as $i => $result) {
                            ?>
                            <tr>
                                <td><?php echo $result['course_name'] ?></td>
                                <td><?php echo $result['batch_name'] ?></td>
                                <td><?php echo $result['achieved_grade'] ?></td>
                                <td class="tar action_column">
                                    <div class="btn-toolbar">
                                        <?php
                                        echo linkButtonGenerator(array(
                                            'attributes' => array('target' => '_blank'),
                                            'href' => build_url(array('toPdf' => '1', 'certificate_id' => $result['pk_result_id'])),
                                            'action' => 'download',
                                            'icon' => 'icon_download',
                                            'text' => 'Download',
                                            'title' => 'Download',
                                        ));
                                        ?>
                                    </div>
                                </td>
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