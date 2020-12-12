<?php

$complain_id = $_GET['id'];

$complains = $this->get_complains(array('id' => $complain_id, 'single' => true));

$branch = "SELECT branch_name FROM dev_branches WHERE pk_branch_id = '" . $complains['fk_branch_id'] . "'";
$branch_name = $devdb->get_row($branch)['branch_name'];

$reportTitle = 'Community Service';

require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

$devPdf = new DEV_PDF('P', 'mm', 'A4');
$devPdf->init();
$devPdf->createPdf();

$devPdf->SetFont('Times', '', 18);
$devPdf->Cell(0, 10, $reportTitle, 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Geographical Information', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Branch Name: ' . $branch_name, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Division: ' . $complains['division'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'District: ' . $complains['branch_district'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Upazila: ' . $complains['upazila'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Union: ' . $complains['branch_union'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Village: ' . $complains['village'], 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');


$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Community Service Information', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Register Date: ' . $complains['complain_register_date'] ? date('d-m-Y', strtotime($complains['complain_register_date'])) : 'N/A', 0, 1, 'L');
$devPdf->Cell(0, 0, 'Name of service recipient: ' . $complains['name'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Age: ' . $complains['age'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Type of service seeking: ' . $complains['type_service'] . ' ' . $complains['other_type_service'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Service recipient: ' . ucfirst($complains['type_recipient']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Gender: ' . ucfirst($complains['gender']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'How to know about this service of the project: ' . $complains['know_service'] . ' ' . $complains['other_know_service'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Remark: ' . $complains['remark'], 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');

$devPdf->SetTitle($reportTitle, true);
$devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
exit();

doAction('render_start');