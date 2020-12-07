<?php

$case_id = $_GET['id'];

$args = array(
    'select_fields' => array(
        'pk_immediate_support_id' => 'dev_immediate_supports.pk_immediate_support_id',
        'customer_id' => 'dev_customers.customer_id',
        'full_name' => 'dev_customers.full_name',
        'nid_number' => 'dev_customers.nid_number',
        'birth_reg_number' => 'dev_customers.birth_reg_number',
        'customer_birthdate' => 'dev_customers.customer_birthdate',
        'customer_gender' => 'dev_customers.customer_gender',
        'customer_mobile' => 'dev_customers.customer_mobile',
        'emergency_mobile' => 'dev_customers.emergency_mobile',
        'emergency_name' => 'dev_customers.emergency_name',
        'emergency_relation' => 'dev_customers.emergency_relation',
        'father_name' => 'dev_customers.father_name',
        'mother_name' => 'dev_customers.mother_name',
        'permanent_village' => 'dev_customers.permanent_village',
        'permanent_ward' => 'dev_customers.permanent_ward',
        'permanent_union' => 'dev_customers.permanent_union',
        'permanent_sub_district' => 'dev_customers.permanent_sub_district',
        'permanent_district' => 'dev_customers.permanent_district',
        'permanent_house' => 'dev_customers.permanent_house',
        'fk_staff_id' => 'dev_immediate_supports.fk_staff_id',
        'fk_customer_id' => 'dev_immediate_supports.fk_customer_id',
        'immediate_support' => 'dev_immediate_supports.immediate_support',
        'entry_date' => 'dev_immediate_supports.entry_date AS entry_date',
        'arrival_place' => 'dev_immediate_supports.arrival_place',
        'reintegration_financial_service' => 'dev_reintegration_plan.reintegration_financial_service',
        'service_requested' => 'dev_reintegration_plan.service_requested',
        'other_service_requested' => 'dev_reintegration_plan.other_service_requested',
        'social_protection' => 'dev_reintegration_plan.social_protection',
        'security_measure' => 'dev_reintegration_plan.security_measure',
        'service_requested_note' => 'dev_reintegration_plan.service_requested_note',
        'first_meeting' => 'dev_psycho_supports.first_meeting',
        'problem_identified' => 'dev_psycho_supports.problem_identified',
        'problem_description' => 'dev_psycho_supports.problem_description',
        'initial_plan' => 'dev_psycho_supports.initial_plan',
        'family_counseling' => 'dev_psycho_supports.family_counseling',
        'session_place' => 'dev_psycho_supports.session_place',
        'session_number' => 'dev_psycho_supports.session_number',
        'session_duration' => 'dev_psycho_supports.session_duration',
        'other_requirements' => 'dev_psycho_supports.other_requirements',
        'reffer_to' => 'dev_psycho_supports.reffer_to',
        'referr_address' => 'dev_psycho_supports.referr_address',
        'contact_number' => 'dev_psycho_supports.contact_number',
        'reason_for_reffer' => 'dev_psycho_supports.reason_for_reffer',
        'other_reason_for_reffer' => 'dev_psycho_supports.other_reason_for_reffer',
        'full_name' => 'dev_customers.full_name',
        'inkind_project' => 'dev_economic_supports.inkind_project',
        'other_inkind_project' => 'dev_economic_supports.other_inkind_project',
        'entry_date' => 'dev_economic_supports.entry_date AS economic_reintegration_date',
        'is_certification_received' => 'dev_economic_supports.is_certification_received',
        'training_used' => 'dev_economic_supports.training_used',
        'economic_other_comments' => 'dev_economic_supports.other_comments AS economic_other_comments',
        'microbusiness_established' => 'dev_economic_supports.microbusiness_established',
        'month_inauguration' => 'dev_economic_supports.month_inauguration',
        'year_inauguration' => 'dev_economic_supports.year_inauguration',
        'family_training' => 'dev_economic_supports.family_training',
        'traning_entry_date' => 'dev_economic_supports.traning_entry_date',
        'place_traning' => 'dev_economic_supports.place_traning',
        'duration_traning' => 'dev_economic_supports.duration_traning',
        'training_status' => 'dev_economic_supports.training_status',
        'financial_literacy_date' => 'dev_economic_supports.financial_literacy_date',
        'business_development_date' => 'dev_economic_supports.business_development_date',
        'product_development_date' => 'dev_economic_supports.product_development_date',
        'entrepreneur_training_date' => 'dev_economic_supports.entrepreneur_training_date',
        'other_financial_training_name' => 'dev_economic_supports.other_financial_training_name',
        'other_financial_training_date' => 'dev_economic_supports.other_financial_training_date',
        'entry_date' => 'dev_economic_reintegration_referrals.entry_date AS economic_reintegration_referral_date',
        'is_vocational_training' => 'dev_economic_reintegration_referrals.is_vocational_training',
        'received_vocational_training' => 'dev_economic_reintegration_referrals.received_vocational_training',
        'other_received_vocational_training' => 'dev_economic_reintegration_referrals.other_received_vocational_training',
        'received_vocational' => 'dev_economic_reintegration_referrals.received_vocational',
        'other_received_vocational' => 'dev_economic_reintegration_referrals.other_received_vocational',
        'economic_referrals_other_comments' => 'dev_economic_reintegration_referrals.other_comments AS economic_referrals_other_comments',
        'is_economic_services' => 'dev_economic_reintegration_referrals.is_economic_services',
        'economic_financial_service' => 'dev_economic_reintegration_referrals.economic_financial_service',
        'economic_support' => 'dev_economic_reintegration_referrals.economic_support',
        'other_economic_support' => 'dev_economic_reintegration_referrals.other_economic_support',
        'is_assistance_received' => 'dev_economic_reintegration_referrals.is_assistance_received',
        'refferd_to' => 'dev_economic_reintegration_referrals.refferd_to',
        'refferd_address' => 'dev_economic_reintegration_referrals.refferd_address',
        'trianing_date' => 'dev_economic_reintegration_referrals.trianing_date',
        'place_of_training' => 'dev_economic_reintegration_referrals.place_of_training',
        'duration_training' => 'dev_economic_reintegration_referrals.duration_training',
        'status_traning' => 'dev_economic_reintegration_referrals.status_traning',
        'assistance_utilized' => 'dev_economic_reintegration_referrals.assistance_utilized',
        'job_placement_date' => 'dev_economic_reintegration_referrals.job_placement_date',
        'financial_services_date' => 'dev_economic_reintegration_referrals.financial_services_date',
        'reintegration_economic' => 'dev_social_supports.reintegration_economic',
        'other_reintegration_economic' => 'dev_social_supports.other_reintegration_economic',
        'soical_date' => 'dev_social_supports.soical_date',
        'medical_date' => 'dev_social_supports.medical_date',
        'date_education' => 'dev_social_supports.date_education',
        'date_housing' => 'dev_social_supports.date_housing',
        'date_legal' => 'dev_social_supports.date_legal',
        'support_referred' => 'dev_social_supports.support_referred',
        'other_support_referred' => 'dev_social_supports.other_support_referred',
    ),
    'id' => $case_id,
    'single' => true
);

$case_info = $this->get_cases($args);

$nid_number = $case_info['nid_number'] ? $case_info['nid_number'] : 'N/A';
$birth_reg_number = $case_info['birth_reg_number'] ? $case_info['birth_reg_number'] : 'N/A';
$support_date = $case_info['entry_date'] ? date('d-m-Y', strtotime($case_info['entry_date'])) : 'N/A';

$reportTitle = 'Case Management Form';

require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

$devPdf = new DEV_PDF('P', 'mm', 'A4');
$devPdf->init();
$devPdf->createPdf();

$devPdf->SetFont('Times', '', 18);
$devPdf->Cell(0, 10, $reportTitle, 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 1. Personal Information', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 0, 'Participant ID: ' . $case_info['customer_id'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Full Name: ' . $case_info['full_name'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'NID Number: ' . $nid_number, 0, 1, 'L');
$devPdf->Cell(0, 10, 'Birth Registration Number: ' . $birth_reg_number, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Date of Birth: ' . date('d-m-Y', strtotime($case_info['customer_birthdate'])), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Gender: ' . ucfirst($case_info['customer_gender']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Mobile No: ' . $case_info['customer_mobile'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Emergency Mobile No: ' . $case_info['emergency_mobile'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Name of that Person: ' . $case_info['emergency_name'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Relation with Participant: ' . $case_info['emergency_relation'], 0, 1, 'L');
$devPdf->Cell(0, 0, "Father's Name: " . $case_info['father_name'], 0, 1, 'L');
$devPdf->Cell(0, 10, "Mother's Name: " . $case_info['mother_name'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Village: ' . $case_info['permanent_village'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Ward No: ' . $case_info['permanent_ward'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Union: ' . ucfirst($case_info['permanent_union']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Upazilla: ' . ucfirst($case_info['permanent_sub_district']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'District: ' . ucfirst($case_info['permanent_district']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Present Address of Beneficiary: ' . $case_info['permanent_house'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Support Date: ' . $support_date, 0, 1, 'L');
$devPdf->Cell(0, 10, 'Arrival Place: ' . $case_info['arrival_place'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Immediate support services received: ' . $case_info['immediate_support'], 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 2: Preferred Services and Reintegration Plan', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);




$devPdf->SetTitle($reportTitle, true);
$devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
exit();

doAction('render_start');
