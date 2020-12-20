<?php

$complain_id = $_GET['id'];

$complains = $this->get_complain_fileds(array('id' => $complain_id, 'single' => true));

$reportTitle = 'Complain File';

require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

$devPdf = new DEV_PDF('P', 'mm', 'A4');
$devPdf->init();
$devPdf->createPdf();

$devPdf->SetFont('Times', '', 18);
$devPdf->Cell(0, 10, $reportTitle, 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Geographical Information', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Division: ' . ucfirst($complains['division']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'District: ' . ucfirst($complains['district']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Upazila: ' . ucfirst($complains['upazila']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Police Station: ' . ucfirst($complains['police_station']), 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');


$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Complain File Information', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, $complains['complain_register_date'] ? 'Register Date: ' . date('d-m-Y', strtotime($complains['complain_register_date'])) : 'N/A', 0, 1, 'L');
$devPdf->Cell(0, 0, 'Name of service recipient: ' . $complains['full_name'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Age: ' . $complains['age'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Gender: ' . ucfirst($complains['gender']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Case Number: ' . $complains['case_id'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Month: ' . $complains['month'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Type of Case: ' . ucfirst($complains['type_case']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Comments: ' . $complains['comments'], 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');

$devPdf->SetTitle($reportTitle, true);
$devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
exit();

doAction('render_start');
