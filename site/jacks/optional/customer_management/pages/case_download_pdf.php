<?php

$customer_id = $_GET['id'];

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
        'plan_date' => 'dev_reintegration_plan.plan_date',
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
    'id' => $customer_id,
    'single' => true
);

$case_info = $this->get_cases($args);

$nid_number = $case_info['nid_number'] ? $case_info['nid_number'] : 'N/A';
$birth_reg_number = $case_info['birth_reg_number'] ? $case_info['birth_reg_number'] : 'N/A';
$support_date = $case_info['entry_date'] ? date('d-m-Y', strtotime($case_info['entry_date'])) : 'N/A';
$soical_date = $case_info['soical_date'] ? date('d-m-Y', strtotime($case_info['soical_date'])) : 'N/A';
$medical_date = $case_info['medical_date'] ? date('d-m-Y', strtotime($case_info['medical_date'])) : 'N/A';
$date_education = $case_info['date_education'] ? date('d-m-Y', strtotime($case_info['date_education'])) : 'N/A';
$date_housing = $case_info['date_housing'] ? date('d-m-Y', strtotime($case_info['date_housing'])) : 'N/A';
$date_legal = $case_info['date_legal'] ? date('d-m-Y', strtotime($case_info['date_legal'])) : 'N/A';
$plan_date = $case_info['plan_date'] ? date('d-m-Y', strtotime($case_info['plan_date'])) : 'N/A';
$first_meeting = $case_info['first_meeting'] ? date('d-m-Y', strtotime($case_info['first_meeting'])) : 'N/A';
$traning_entry_date = $case_info['traning_entry_date'] ? date('d-m-Y', strtotime($case_info['traning_entry_date'])) : 'N/A';
$training_start_date = $case_info['training_start_date'] ? date('d-m-Y', strtotime($case_info['training_start_date'])) : 'N/A';
$training_end_date = $case_info['training_end_date'] ? date('d-m-Y', strtotime($case_info['training_end_date'])) : 'N/A';
$job_placement_date = $case_info['job_placement_date'] ? date('d-m-Y', strtotime($case_info['job_placement_date'])) : 'N/A';
$economic_reintegration_date = $case_info['economic_reintegration_date'] ? date('d-m-Y', strtotime($case_info['economic_reintegration_date'])) : 'N/A';
$economic_reintegration_referral_date = $case_info['economic_reintegration_referral_date'] ? date('d-m-Y', strtotime($case_info['economic_reintegration_referral_date'])) : 'N/A';
$financial_services_date = $case_info['financial_services_date'] ? date('d-m-Y', strtotime($case_info['financial_services_date'])) : 'N/A';
$financial_literacy_date = $case_info['financial_literacy_date'] ? date('d-m-Y', strtotime($case_info['financial_literacy_date'])) : 'N/A';
$business_development_date = $case_info['business_development_date'] ? date('d-m-Y', strtotime($case_info['business_development_date'])) : 'N/A';
$product_development_date = $case_info['product_development_date'] ? date('d-m-Y', strtotime($case_info['product_development_date'])) : 'N/A';
$entrepreneur_training_date = $case_info['entrepreneur_training_date'] ? date('d-m-Y', strtotime($case_info['entrepreneur_training_date'])) : 'N/A';
$other_financial_training_name = $case_info['other_financial_training_name'] ? $case_info['other_financial_training_name'] : 'N/A';
$other_financial_training_date = $case_info['other_financial_training_date'] ? date('d-m-Y', strtotime($case_info['other_financial_training_date'])) : 'N/A';

$family_counsellings = $this->get_family_counselling(array('customer_id' => $customer_id));
$psychosocial_sessions = $this->get_psychosocial_session(array('customer_id' => $customer_id));
$psychosocial_completions = $this->get_psychosocial_completion(array('customer_id' => $customer_id));
$psychosocial_followups = $this->get_psychosocial_followup(array('customer_id' => $customer_id));
$reviews = $this->get_case_review(array('customer_id' => $customer_id));

$reportTitle = 'Case Management Form';

require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

$devPdf = new DEV_PDF('P', 'mm', 'A4');
$devPdf->init();
$devPdf->createPdf();

$devPdf->SetFont('Times', '', 18);
$devPdf->Cell(0, 10, $reportTitle, 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 1: Immediate Support Provided After Arrival', 0, 1, 'C');

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
$devPdf->Cell(0, 10, 'Plan Date: ' . $plan_date, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Type of Services Requested: ' . $case_info['service_requested'] . ' ' . $case_info['other_service_requested'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Social Protection Schemes: ' . $case_info['social_protection'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Special Security Measures: ' . $case_info['security_measure'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Note: ' . $case_info['service_requested_note'], 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 3: Psychosocial Reintegration Support Services', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Date of first meeting: ' . $first_meeting, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Problems Identified: ' . $case_info['problem_identified'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Problem Description: ' . $case_info['problem_description'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Initial Plan: ' . $case_info['initial_plan'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Place of Session: ' . ucfirst($case_info['session_place']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Number of Sessions (Estimate): ' . $case_info['session_number'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Duration of Session: ' . $case_info['session_duration'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Other Requirements: ' . $case_info['other_requirements'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Referred to: ' . $case_info['reffer_to'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Referred Address of organization/Individual: ' . $case_info['referr_address'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Referred Phone Number: ' . $case_info['contact_number'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Reason for Referral: ' . $case_info['reason_for_reffer'] . ' ' . $case_info['other_reason_for_reffer'], 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 3.1: Family Counseling Session', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($family_counsellings['data'] != NULL) {
    $family_counselling_header = array(
        array('text' => 'Date', 'align' => 'L', 'width' => 10),
        array('text' => 'Time', 'align' => 'L', 'width' => 10),
        array('text' => 'Place', 'align' => 'L', 'width' => 10),
        array('text' => 'Counseled Men', 'align' => 'L', 'width' => 10),
        array('text' => 'Counseled Women', 'align' => 'L', 'width' => 10),
        array('text' => 'Total Counseled', 'align' => 'L', 'width' => 10),
        array('text' => 'Comments', 'align' => 'L', 'width' => 40),
    );

    foreach ($family_counsellings['data'] as $i => $item) {
        $family_counselling_reportData[] = array(
            $item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A',
            $item['entry_time'],
            $item['session_place'],
            $item['male_household_member'],
            $item['female_household_member'],
            $item['male_household_member'] + $item['female_household_member'],
            $item['session_comments'],
        );
    }
    $devPdf->resetOptions();
    $devPdf->setOption('headers', $family_counselling_header);
    $devPdf->setOption('data', $family_counselling_reportData);
    $devPdf->addTable();
}

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 3.2: Psychosocial Reintegration Session Activities', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($psychosocial_sessions['data'] != NULL) {
    $psychosocial_session_header = array(
        array('text' => 'Date', 'align' => 'L', 'width' => 10),
        array('text' => 'Time', 'align' => 'L', 'width' => 10),
        array('text' => 'Next Date', 'align' => 'L', 'width' => 10),
        array('text' => 'Activities', 'align' => 'L', 'width' => 10),
        array('text' => 'Comments', 'align' => 'L', 'width' => 10),
    );

    foreach ($family_counsellings['data'] as $i => $item) {
        $psychosocial_session_reportData[] = array(
            $item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A',
            $item['entry_time'],
            $item['next_date'] ? date('d-m-Y', strtotime($item['next_date'])) : 'N/A',
            $item['activities_description'],
            $item['session_comments'],
        );
    }

    $devPdf->resetOptions();
    $devPdf->setOption('headers', $psychosocial_session_header);
    $devPdf->setOption('data', $psychosocial_session_reportData);
    $devPdf->addTable();
}

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 3.3: Session Completion Status', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($psychosocial_completions['data'] != NULL) {
    foreach ($psychosocial_completions['data'] as $i => $item) {
        $devPdf->Cell(0, 10, 'Date: ' . $item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A', 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Session Completion: ' . ucfirst($item['is_completed']), 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Drop-out Reason (If Any): ' . $item['dropout_reason'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Review of Counseling Session: ' . $item['review_session'], 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Comments of the Client: ' . $item['client_comments'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Comments of the Counselor: ' . $item['counsellor_comments'], 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Final Evaluation: ' . $item['final_evaluation'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Required Session After Completion: ' . ucfirst($item['required_session']), 0, 1, 'L');
        $devPdf->Cell(0, 10, '', 0, 1, 'L');
    }
}

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 3.4: Psychosocial Reintegration (Followup)', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);

if ($psychosocial_followups['data'] != NULL) {
    $psychosocial_followup_header = array(
        array('text' => 'Date', 'align' => 'L', 'width' => 10),
        array('text' => 'Time', 'align' => 'L', 'width' => 10),
        array('text' => 'Comments', 'align' => 'L', 'width' => 80),
    );

    foreach ($psychosocial_followups['data'] as $i => $item) {
        $psychosocial_followup_reportData[] = array(
            $item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A',
            $item['entry_time'],
            $item['followup_comments'],
        );
    }
    $devPdf->resetOptions();
    $devPdf->setOption('headers', $psychosocial_followup_header);
    $devPdf->setOption('data', $psychosocial_followup_reportData);
    $devPdf->addTable();
}

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 4: Economic Reintegration Support', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Date: ' . $economic_reintegration_date, 0, 1, 'L');
$devPdf->Cell(0, 0, 'In-kind Support from Project: ' . $case_info['inkind_project'] . ' ' . $case_info['other_inkind_project'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Training Certificate Received: ' . ucfirst($case_info['is_certification_received']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'How has the training been used so far?', 0, 1, 'L');
$devPdf->Cell(0, 10, '- ' . $case_info['training_used'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Any other Comments: ' . $case_info['economic_other_comments'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Micro-business Established: ' . ucfirst($case_info['microbusiness_established']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Month Of Business Inauguration: ' . $case_info['month_inauguration'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Year of Business Inauguration: ' . $case_info['year_inauguration'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Family members received Financial Literacy Training: ' . ucfirst($case_info['family_training']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Date of Training: ' . $traning_entry_date, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Place of Training: ' . $case_info['place_traning'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Duration of Training: ' . $case_info['duration_traning'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Status Of Training: ' . ucfirst($case_info['training_status']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Financial Literacy Training Date: ' . $financial_literacy_date, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Business Development Training Date: ' . $business_development_date, 0, 1, 'L');
$devPdf->Cell(0, 10, 'Product Development Training Date: ' . $product_development_date, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Entrepreneur Training Training Date: ' . $entrepreneur_training_date, 0, 1, 'L');
$devPdf->Cell(0, 10, 'Other Training Name (If Any): ' . $other_financial_training_name, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Other Training Start Date: ' . $other_financial_training_date, 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 4.1: Economic Reintegration Referrals', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Date: ' . $economic_reintegration_referral_date, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Referrals done for Vocational Training: ' . ucfirst($case_info['is_vocational_training']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Vocational Training Received (Referrals) (If Any): ' . $case_info['received_vocational'] . ' ' . $case_info['other_received_vocational'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Start Date of Training: ' . $training_start_date, 0, 1, 'L');
$devPdf->Cell(0, 10, 'End Date of Training: ' . $training_end_date, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Referrals done for economic services: ' . ucfirst($case_info['is_economic_services']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Economic Support (If Any): ' . $case_info['economic_support'] . ' ' . $case_info['other_economic_support'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Required Assistance Received: ' . ucfirst($case_info['is_assistance_received']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Job Placement Date: ' . $job_placement_date, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Financial Services Date: ' . $financial_services_date, 0, 1, 'L');
$devPdf->Cell(0, 10, 'Referred To: ' . $case_info['refferd_to'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Referred Address: ' . $case_info['refferd_address'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Date of Training: ' . $case_info['trianing_date'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Place of Training: ' . $case_info['place_of_training'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Duration of Training: ' . $case_info['duration_training'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Status of the Training: ' . ucfirst($case_info['status_traning']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'How has the assistance been utilized?', 0, 1, 'L');
$devPdf->Cell(0, 0, '- ' . $case_info['assistance_utilized'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Any other Comments', 0, 1, 'L');
$devPdf->Cell(0, 0, '- ' . $case_info['economic_referrals_other_comments'], 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 5: Social Reintegration Support', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Support Referred: ' . $case_info['support_referred'] . ' ' . $case_info['other_support_referred'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Social Reintegration Support: ' . $case_info['reintegration_economic'] . ' ' . $case_info['other_reintegration_economic'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Social Services Received: ' . $soical_date, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Medical Services Received: ' . $medical_date, 0, 1, 'L');
$devPdf->Cell(0, 10, 'Education Services Received: ' . $date_education, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Housing Services Received: ' . $date_housing, 0, 1, 'L');
$devPdf->Cell(0, 10, 'Legal & Others Services Received: ' . $date_legal, 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 6: Review and Follow-Up', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
if ($reviews['data'] != NULL) {
    foreach ($reviews['data'] as $i => $item) {
        $devPdf->Cell(0, 10, 'Entry Date: ' . $item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A', 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Case dropped out from the project? ' . ucfirst($item['casedropped']), 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Reason for Dropping Out: ' . $item['reason_dropping'] . ' ' . $item['other_reason_dropping'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Confirmed Services Received after 3 Months: ' . $item['confirm_services'] . ' ' . $item['other_reason_dropping'], 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Financial Service: ' . $item['followup_financial_service'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Social Protection: ' . $item['social_protection'], 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Special Security Measures: ' . $item['special_security'], 0, 1, 'L');
        $devPdf->SetFont('Times', 'B', 14);
        $devPdf->Cell(0, 10, 'Status of Case after Receiving the Services: ', 0, 1, 'L');
       
        $devPdf->SetFont('Times', '', 12);
        $devPdf->Cell(0, 10, 'Monthly income (BDT): ' . $item['monthly_income'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Challenges: ' . $item['challenges'], 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Actions taken: ' . $item['actions_taken'], 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Remark of the participant (If Any): ' . $item['remark_participant'], 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Comment of BRAC Officer responsible for participant: ', 0, 1, 'L');
        $devPdf->Cell(0, 0, '- ' . $item['comment_brac'], 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Remark of District Manager', 0, 1, 'L');
        $devPdf->Cell(0, 0, '- ' . $item['remark_district'], 0, 1, 'L');

        $devPdf->Cell(0, 10, 'Psychosocial Reintegration Date: ' . $item['comment_psychosocial_date'] ? date('d-m-Y', strtotime($item['comment_psychosocial_date'])) : 'N/A', 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Economic Reintegration Date: ' . $item['comment_economic_date'] ? date('d-m-Y', strtotime($item['comment_economic_date'])) : 'N/A', 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Social Reintegration Date: ' . $item['comment_social_date'] ? date('d-m-Y', strtotime($item['comment_social_date'])) : 'N/A', 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Comment on social reintegration: ' . $item['comment_social'], 0, 1, 'L');
        $devPdf->Cell(0, 10, 'Income Tracking Date: ' . $item['comment_income_date'] ? date('d-m-Y', strtotime($item['comment_income_date'])) : 'N/A', 0, 1, 'L');
        $devPdf->Cell(0, 0, 'Complete income tracking information: ' . $item['comment_income'], 0, 1, 'L');
    }
}

$devPdf->SetTitle($reportTitle, true);
$devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
exit();

doAction('render_start');