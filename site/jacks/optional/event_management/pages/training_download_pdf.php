<?php

$training_id = $_GET['id'];

$trainings = $this->get_trainings(array('id' => $training_id, 'single' => true));
$training_validation = $this->get_sharing_sessions(array('training_id' => $training_id));

$reportTitle = 'Training/Workshop';

require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

$devPdf = new DEV_PDF('P', 'mm', 'A4');
$devPdf->init();
$devPdf->createPdf();

$devPdf->SetFont('Times', '', 18);
$devPdf->Cell(0, 10, $reportTitle, 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Training/Workshop Information', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, $trainings['date'] ? 'Date: ' . date('d-m-Y', strtotime($trainings['date'])) : 'N/A', 0, 1, 'L');
$devPdf->Cell(0, 0, 'Beneficiary ID: ' . $trainings['beneficiary_id'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Participant Name: ' . $trainings['name'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Age: ' . $trainings['age'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Profession: ' . $trainings['profession'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Gender: ' . ucfirst($trainings['gender']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Name of the training: ' . $trainings['training_name'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Name of the workshop: ' . $trainings['workshop_name'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Duration of Workshop: ' . $trainings['workshop_duration'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Duration of training: ' . $trainings['training_duration'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Mobile: ' . $trainings['mobile'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Address: ' . $trainings['address'], 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Training/Workshop Validation', 0, 1, 'C');

if ($training_validation['data'] != NULL) {
    foreach ($training_validation['data'] as $i => $item) {

        $devPdf->SetFont('Times', '', 12);
        $devPdf->Cell(0, 10, $item['traning_date'] ? 'Training Date: ' . date('d-m-Y', strtotime($item['traning_date'])) : 'N/A', 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Evaluator Profession: ' . ucfirst($item['evaluator_profession']), 0, 1, 'L');

        $devPdf->SetFont('Times', 'B', 12);
        $devPdf->Cell(0, 13, 'Organization of The Training', 0, 1, 'C');
        $devPdf->SetFont('Times', '', 12);

        $devPdf->Cell(0, 10, 'How satisfied are you with the contents of training/workshop: ' . $item['satisfied_training'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'How satisfied are you with the training venue and other logistic supports: ' . $item['satisfied_supports'], 0, 1, 'L');
        $devPdf->Cell(0, 10, 'How satisfied are you with the training/workshop facilitation: ' . $item['satisfied_facilitation'], 0, 1, 'L');

        $devPdf->SetFont('Times', 'B', 12);
        $devPdf->Cell(0, 13, 'Outcome of The Training', 0, 1, 'C');
        $devPdf->SetFont('Times', '', 12);

        $devPdf->Cell(0, 10, 'What extent your knowledge increased on NPA: ' . $item['outcome_training'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'What extent your knowledge increased on trafficking law: ' . $item['trafficking_law'], 0, 1, 'L');
        $devPdf->Cell(0, 10, 'What extent your knowledge increased on policy process: ' . $item['policy_process'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'What extent your knowledge increased on over all contents: ' . $item['all_contents'], 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Recommendation (If Any): ' . $item['recommendation'], 0, 1, 'L');
    }
}

$devPdf->SetTitle($reportTitle, true);
$devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
exit();

doAction('render_start');
