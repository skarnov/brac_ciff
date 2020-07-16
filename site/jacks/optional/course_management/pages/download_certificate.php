<?php

$customer_id = $_GET['id'];
$result = $this->get_customer_results(array('single' => true, 'id' => $customer_id));

require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

$devPdf = new DEV_PDF('L', 'mm', 'A4');
$devPdf->init();
$devPdf->setOption('page_header','false');
$devPdf->setOption('page_footer','false');
$devPdf->createPdf();

$devPdf->Cell(0, 10, $result['customer_id'].'/'.$result['pk_result_id'], 0, 1, 'L');
$devPdf->SetFont('Times', 'B', 30);
$devPdf->Cell(0, 10, 'Certificate of Training', 0, 1, 'C');

$devPdf->SetFont('Times', '', 18);
$devPdf->Cell(0, 18, 'This certifies that', 0, 1, 'C');
$devPdf->SetFont('Times', 'B', 25);
$devPdf->Cell(0, 30, $result['full_name'], 0, 1, 'C');
$devPdf->SetFont('Times', '', 18);
$devPdf->Cell(0, 15, 'has successfully completed', 0, 1, 'C');
$devPdf->SetFont('Times', 'B', 25);
$devPdf->Cell(0, 10, $result['course_name'], 0, 1, 'C');

$devPdf->SetFont('Times', '', 14);
$devPdf->Cell(0, 40, 'Course Duration: '.$result['course_duration'].' (Hours)', 0, 1, 'C');

$devPdf->SetFont('Times', 'B', 16);
$devPdf->Cell(0, 15, 'Organized by', 0, 1, 'C');
$devPdf->Cell(0, 0, 'BRAC Migration Programme', 0, 1, 'C');

$devPdf->outputPdf($_GET['mode'], 'Certificate' . '.pdf');
exit();
doAction('render_start');