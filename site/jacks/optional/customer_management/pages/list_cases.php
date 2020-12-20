<?php

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\CellAlignment;

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_customer_id = $_GET['customer_id'] ? $_GET['customer_id'] : null;
$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_nid = $_GET['nid'] ? $_GET['nid'] : null;
$filter_birth = $_GET['birth'] ? $_GET['birth'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;
$branch_id = $_config['user']['user_branch'] ? $_config['user']['user_branch'] : null;

$args = array(
    'listing' => TRUE,
    'select_fields' => array(
        'id' => 'dev_immediate_supports.fk_customer_id',
        'customer_id' => 'dev_customers.customer_id',
        'full_name' => 'dev_customers.full_name',
        'customer_mobile' => 'dev_customers.customer_mobile',
        'birth_reg_number' => 'dev_customers.birth_reg_number',
        'permanent_division' => 'dev_customers.permanent_division',
        'permanent_district' => 'dev_customers.permanent_district',
        'permanent_sub_district' => 'dev_customers.permanent_sub_district',
        'customer_status' => 'dev_customers.customer_status',
    ),
    'customer_id' => $filter_customer_id,
    'name' => $filter_name,
    'nid' => $filter_nid,
    'birth' => $filter_birth,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'dev_immediate_supports.fk_customer_id',
        'order' => 'DESC'
    ),
);

if ($filter_entry_start_date && $filter_entry_start_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'entry_date' => array(
            'left' => date_to_db($filter_entry_start_date),
            'right' => date_to_db($filter_entry_end_date),
        ),
    );
}

$cases = $this->get_cases($args);
$pagination = pagination($cases['total'], $per_page_items, $start);

$divisions = get_division();

if (isset($_POST['division_id'])) {
    $districts = get_district($_POST['division_id']);
    echo "<option value=''>Select One</option>";
    foreach ($districts as $district) :
        echo "<option id='" . $district['id'] . "' value='" . strtolower($district['name']) . "' >" . $district['name'] . "</option>";
    endforeach;
    exit;
} else if (isset($_POST['district_id'])) {
    $subdistricts = get_subdistrict($_POST['district_id']);
    echo "<option value=''>Select One</option>";
    foreach ($subdistricts as $subdistrict) :
        echo "<option id='" . $subdistrict['id'] . "' value='" . strtolower($subdistrict['name']) . "'>" . $subdistrict['name'] . "</option>";
    endforeach;
    exit;
}

$filterString = array();
if ($filter_id)
    $filterString[] = 'Customer ID: ' . $filter_customer_id;
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_nid)
    $filterString[] = 'NID: ' . $filter_nid;
if ($filter_birth)
    $filterString[] = 'Birth ID: ' . $filter_birth;
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Upazila: ' . $filter_sub_district;
if ($filter_entry_start_date)
    $filterString[] = 'Start Date: ' . $filter_entry_start_date;
if ($filter_entry_end_date)
    $filterString[] = 'End Date: ' . $filter_entry_end_date;

if ($_GET['download_excel']) {
    $args['select_fields'] = array(
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
    );
    unset($args['listing']);
    unset($args['limit']);

    $args['data_only'] = true;
    $data = $this->get_cases($args);
    $data = $data['data'];

    // This will be here in our project
    $writer = WriterEntityFactory::createXLSXWriter();
    $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            //->setShouldWrapText()
            ->build();

    $style10 = (new StyleBuilder())
        ->setBackgroundColor(Color::ALICEBLUE)
        ->build();

    $fileName = 'case-management-' . time() . '.xlsx';
    $writer->openToBrowser($fileName); // stream data directly to the browser
    // Header text
    $style2 = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(15)
            //->setFontColor(Color::BLUE)
            ->setShouldWrapText()
            ->setCellAlignment(CellAlignment::LEFT)
            ->build();

    /** add a row at a time */
    $report_head = ['Case Management Report '];
    $singleRow = WriterEntityFactory::createRowFromArray($report_head, $style2);
    $writer->addRow($singleRow);

    $report_date = ['Date: ' . Date('d-m-Y H:i')];
    $reportDateRow = WriterEntityFactory::createRowFromArray($report_date);
    $writer->addRow($reportDateRow);

    $filtered_with = ['Participant ID = ' . $filter_id . ', Name = ' . $filter_name . ', NID = ' . $filter_nid . ', Passport = ' . $filter_passport . ', Division = ' . $filter_division . ', District = ' . $filter_district . ', Sub-District = ' . $filter_sub_district . ', Police Station = ' . $filter_ps . ', Start Date = ' . $filter_entry_start_date . ', End Date = ' . $filter_entry_end_date];
    $rowFromVal = WriterEntityFactory::createRowFromArray($filtered_with);
    $writer->addRow($rowFromVal);

    $empty_row = [''];
    $rowFromVal = WriterEntityFactory::createRowFromArray($empty_row);
    $writer->addRow($rowFromVal);

    $header = [
        "SL",
        'Participant ID',
        'Full Name',
        "NID Number",
        "Birth Registration Number",
        'Date of Birth',
        'Gender',
        'Mobile No',
        'Emergency Mobile No',
        'Name of that Person',
        "Relation with Participant",
        "Father's Name",
        "Mother's Name",
        "Village",
        'Ward No',
        'Union',
        'Upazilla',
        "District",
        'Present Address of Beneficiary',
        'Support Date',
        'Arrival Place',
        "Immediate support services received",

        "Plan Date",
        "Type of Services Requested",
        "Social Protection Schemes",
        "Special Security Measures",
        "Note",
        "Date of first meeting",
        "Problems Identified",
        "Problem Description",
        "Initial Plan",
        "Place of Session",
        "Number of Sessions (Estimate)",
        "Duration of Session",
        "Other Requirements",
        "Referred to",
        "Referred Address of organization/Individual",
        "Referred Phone Number",
        "Reason for Referral",

        "Economic Reintegration Support Date",
        "In-kind Support from Project",
        "Training Certificate Received",
        "How has the training been used so far?",
        "Any other Comments",
        "Micro-business Established",
        "Month Of Business Inauguration",
        "Year of Business Inauguration",
        "Family members received Financial Literacy Training",
        "Date of Training",
        "Place of Training",
        "Duration of Training",
        "Status Of Training",
        "Financial Literacy Training Date",
        "Business Development Training Date",
        "Product Development Training Date",
        "Entrepreneur Training Training Date",
        "Other Training Name (If Any)",
        "Other Training Start Date",

        "conomic Reintegration Referrals Date",
        "Referrals done for Vocational Training",
        "Vocational Training Received (Referrals) (If Any)",
        "Start Date of Training",
        "End Date of Training",
        "Referrals done for economic services",
        "Economic Support (If Any)",
        "Required Assistance Received",
        "Job Placement Date",
        "Financial Services Date",
        "Referred To",
        "Referred Address",
        "Date of Training",
        "Place of Training",
        "Duration of Training",
        "Status of the Training",
        "How has the assistance been utilized?",
        "Any other Comments",

        "Support Referred",
        "Social Reintegration Support",
        "Social Services Received",
        "Medical Services Received",
        "Education Services Received",
        "Housing Services Received",
        "Legal & Others Services Received",

        // "Date",
        // "Case dropped out from the project?",
        // "Reason for Dropping Out",
        // "Confirmed Services Received after 3 Months",
        // "Financial Service",
        // "Social Protection",
        // "Special Security Measures",

        // "Monthly income (BDT)",
        // "Challenges",
        // "Actions taken",
        // "Remark of the participant (If Any)",
        // "Comment of BRAC Officer responsible for participant",
        // "Remark of District Manager",
        // "Psychosocial Reintegration Date",
        // "Psychosocial Comment",
        // "Economic Reintegration Date",
        // "Economic Comment",
        // "Social Reintegration Date",
        // "Social Comment",
        // "Comment on social reintegration",
        // "Income Tracking Date",
        // "Complete income tracking information",
    ];

    $rowFromVal = WriterEntityFactory::createRowFromArray($header, $style);
    $writer->addRow($rowFromVal);
    $multipleRows = array();
    $merg_col = "";
    $mergeRanges = ['A1:CE1', 'A2:CE2', 'A3:CE3'];

    if ($data) {
        $count_for_mrg = 6;
        $count = 0;
        foreach ($data as $case_info) {
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

            $cells = [
                WriterEntityFactory::createCell(++$count),
                WriterEntityFactory::createCell($case_info['customer_id']),
                WriterEntityFactory::createCell($case_info['full_name']),
                WriterEntityFactory::createCell($nid_number),
                WriterEntityFactory::createCell($birth_reg_number),
                WriterEntityFactory::createCell(date('d-m-Y', strtotime($case_info['customer_birthdate']))),
                WriterEntityFactory::createCell(ucfirst($case_info['customer_gender'])),
                WriterEntityFactory::createCell($case_info['customer_mobile']),
                WriterEntityFactory::createCell($case_info['emergency_mobile']),
                WriterEntityFactory::createCell($case_info['emergency_name']),
                WriterEntityFactory::createCell($case_info['emergency_relation']),
                WriterEntityFactory::createCell($case_info['father_name']),
                WriterEntityFactory::createCell($case_info['mother_name']),
                WriterEntityFactory::createCell($case_info['permanent_village']),
                WriterEntityFactory::createCell($case_info['permanent_ward']),
                WriterEntityFactory::createCell($case_info['permanent_union']),
                WriterEntityFactory::createCell($case_info['permanent_sub_district']),
                WriterEntityFactory::createCell($case_info['permanent_district']),
                WriterEntityFactory::createCell($case_info['permanent_house']),
                WriterEntityFactory::createCell($support_date),
                WriterEntityFactory::createCell($case_info['arrival_place']),
                WriterEntityFactory::createCell($case_info['immediate_support']),

                WriterEntityFactory::createCell($plan_date),
                WriterEntityFactory::createCell($case_info['service_requested'] . ' ' . $case_info['other_service_requested']),
                WriterEntityFactory::createCell($case_info['social_protection']),
                WriterEntityFactory::createCell($case_info['security_measure']),
                WriterEntityFactory::createCell($case_info['service_requested_note']),

                WriterEntityFactory::createCell($first_meeting),
                WriterEntityFactory::createCell($case_info['problem_identified']),
                WriterEntityFactory::createCell($case_info['problem_description']),
                WriterEntityFactory::createCell($case_info['initial_plan']),
                WriterEntityFactory::createCell(ucfirst($case_info['session_place'])),
                WriterEntityFactory::createCell($case_info['session_number']),
                WriterEntityFactory::createCell($case_info['session_duration']),
                WriterEntityFactory::createCell($case_info['other_requirements']),
                WriterEntityFactory::createCell($case_info['reffer_to']),
                WriterEntityFactory::createCell($case_info['referr_address']),
                WriterEntityFactory::createCell($case_info['contact_number']),
                WriterEntityFactory::createCell($case_info['reason_for_reffer'] . ' ' . $case_info['other_reason_for_reffer']),

                WriterEntityFactory::createCell($economic_reintegration_date),
                WriterEntityFactory::createCell($case_info['inkind_project'] . ' ' . $case_info['other_inkind_project']),
                WriterEntityFactory::createCell(ucfirst($case_info['is_certification_received'])),
                WriterEntityFactory::createCell($case_info['training_used']),
                WriterEntityFactory::createCell($case_info['economic_other_comments']),
                WriterEntityFactory::createCell(ucfirst($case_info['microbusiness_established'])),
                WriterEntityFactory::createCell($case_info['month_inauguration']),
                WriterEntityFactory::createCell($case_info['year_inauguration']),
                WriterEntityFactory::createCell(ucfirst($case_info['family_training'])),
                WriterEntityFactory::createCell($traning_entry_date),
                WriterEntityFactory::createCell($case_info['place_traning']),
                WriterEntityFactory::createCell($case_info['duration_traning']),
                WriterEntityFactory::createCell(ucfirst($case_info['training_status'])),
                WriterEntityFactory::createCell($financial_literacy_date),
                WriterEntityFactory::createCell($business_development_date),
                WriterEntityFactory::createCell($product_development_date),
                WriterEntityFactory::createCell($entrepreneur_training_date),
                WriterEntityFactory::createCell($other_financial_training_name),
                WriterEntityFactory::createCell($other_financial_training_date),

                WriterEntityFactory::createCell($economic_reintegration_referral_date),
                WriterEntityFactory::createCell(ucfirst($case_info['is_vocational_training'])),
                WriterEntityFactory::createCell($case_info['received_vocational'] . ' ' . $case_info['other_received_vocational']),
                WriterEntityFactory::createCell($training_start_date),
                WriterEntityFactory::createCell($training_end_date),
                WriterEntityFactory::createCell(ucfirst($case_info['is_economic_services'])),
                WriterEntityFactory::createCell($case_info['economic_support'] . ' ' . $case_info['other_economic_support']),
                WriterEntityFactory::createCell(ucfirst($case_info['is_assistance_received'])),
                WriterEntityFactory::createCell($job_placement_date),
                WriterEntityFactory::createCell($financial_services_date),
                WriterEntityFactory::createCell($case_info['refferd_to']),
                WriterEntityFactory::createCell($case_info['refferd_address']),
                WriterEntityFactory::createCell($case_info['trianing_date']),
                WriterEntityFactory::createCell($case_info['place_of_training']),
                WriterEntityFactory::createCell($case_info['duration_training']),
                WriterEntityFactory::createCell(ucfirst($case_info['status_traning'])),
                WriterEntityFactory::createCell($case_info['assistance_utilized']),
                WriterEntityFactory::createCell($case_info['economic_referrals_other_comments']),

                WriterEntityFactory::createCell($case_info['support_referred'] . ' ' . $case_info['other_support_referred']),
                WriterEntityFactory::createCell($case_info['reintegration_economic'] . ' ' . $case_info['other_reintegration_economic']),
                WriterEntityFactory::createCell($soical_date),
                WriterEntityFactory::createCell($medical_date),
                WriterEntityFactory::createCell($date_education),
                WriterEntityFactory::createCell($date_housing),
                WriterEntityFactory::createCell($date_legal),
            ];

            //$multipleRows[] = WriterEntityFactory::createRow($cells);
            $get_rows = WriterEntityFactory::createRow($cells, $style10);
            $writer->addRow($get_rows);
            $count_for_mrg++;

            // Newly added
            include('case_excel_multiple.php');
            // End newly added

            
        }
    }
    //$writer->addRows($multipleRows);

    $currentSheet = $writer->getCurrentSheet();
     // you can list the cells you want to merge like this ['A1:A4','A1:E1']
    $currentSheet->setMergeRanges($mergeRanges);

    $writer->close();
    exit;
    // End this is to our project
}

doAction('render_start');
?>
<div class="page-header">
    <h1>All Cases</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_excel=1&customer_id=' . $filter_customer_id . '&name=' . $filter_name . '&nid=' . $filter_nid . '&birth=' . $filter_birth . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download Case',
                'title' => 'Download Case',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<?php
echo formProcessor::form_elements('customer_id', 'customer_id', array(
    'width' => 2, 'type' => 'text', 'label' => 'Participant ID',
        ), $filter_customer_id);
echo formProcessor::form_elements('name', 'name', array(
    'width' => 2, 'type' => 'text', 'label' => 'Participant Name',
        ), $filter_name);
echo formProcessor::form_elements('nid', 'nid', array(
    'width' => 2, 'type' => 'text', 'label' => 'NID',
        ), $filter_nid);
echo formProcessor::form_elements('birth', 'birth', array(
    'width' => 2, 'type' => 'text', 'label' => 'Birth ID',
        ), $filter_birth);
?>
<div class="form-group col-sm-2">
    <label>Division</label>
    <div class="select2-primary">
        <select class="form-control division" name="division" style="text-transform: capitalize">
            <?php if ($filter_division) : ?>
                <option value="<?php echo $filter_division ?>"><?php echo $filter_division ?></option>
            <?php else: ?>
                <option value="">Select One</option>
            <?php endif ?>
            <?php foreach ($divisions as $division) : ?>
                <option id="<?php echo $division['id'] ?>" value="<?php echo strtolower($division['name']) ?>"><?php echo $division['name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-2">
    <label>District</label>
    <div class="select2-primary">
        <select class="form-control district" name="district" id="districtList" style="text-transform: capitalize">
            <?php if ($filter_district) : ?>
                <option value="<?php echo $filter_district ?>"><?php echo $filter_district ?></option>
            <?php endif ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-2">
    <label>Upazila</label>
    <div class="select2-primary">
        <select class="form-control subdistrict" name="sub_district" id="subdistrictList" style="text-transform: capitalize">
            <?php if ($filter_sub_district) : ?>
                <option value="<?php echo $filter_sub_district ?>"><?php echo $filter_sub_district ?></option>
            <?php endif ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Start Date</label>
    <div class="input-group">
        <input id="startDate" type="text" class="form-control" name="entry_start_date" value="<?php echo $filter_entry_start_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('startDate');
        });
    </script>
</div>
<div class="form-group col-sm-3">
    <label>End Date</label>
    <div class="input-group">
        <input id="endDate" type="text" class="form-control" name="entry_end_date" value="<?php echo $filter_entry_end_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('endDate');
        });
    </script>
</div>
<?php
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="table-primary table-responsive">
    <?php if ($filterString): ?>
        <div class="table-header">
            Filtered With: <?php echo implode(', ', $filterString) ?>
        </div>
    <?php endif; ?>
    <div class="table-header">
        <?php echo searchResultText($cases['total'], $start, $per_page_items, count($cases['data']), 'cases') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Participant ID</th>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Birth ID</th>
                <th>Present Address</th>
                <th>Status</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($cases['data'] as $i => $case) {
                ?>
                <tr>
                    <td><?php echo $case['customer_id']; ?></td>
                    <td><?php echo $case['full_name']; ?></td>
                    <td><?php echo $case['customer_mobile']; ?></td>
                    <td><?php echo $case['birth_reg_number']; ?></td>
                    <td style="text-transform: capitalize"><?php echo '<b>Division - </b>' . $case['permanent_division'] . ',<br><b>District - </b>' . $case['permanent_district'] . ',<br><b>Upazila - </b>' . $case['permanent_sub_district'] ?></td>
                    <td style="text-transform: capitalize"><?php echo $case['customer_status']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_case')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_case', 'edit' => $case['fk_customer_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Case',
                                ));
                                ?>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'download_pdf', 'id' => $case['fk_customer_id'])),
                                    'action' => 'download',
                                    'icon' => 'icon_download',
                                    'text' => 'Download',
                                    'title' => 'Download Case',
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
</div>
<script type="text/javascript">
    init.push(function () {
        $('.division').change(function () {
            var divisionId = $(this).find('option:selected').attr('id');
            $.ajax({
                type: 'POST',
                data: {division_id: divisionId},
                success: function (result) {
                    $('#districtList').html(result);
                }}
            );
        });
        $('.district').change(function () {
            var districtId = $(this).find('option:selected').attr('id');
            $.ajax({
                type: 'POST',
                data: {district_id: districtId},
                success: function (result) {
                    $('#subdistrictList').html(result);
                }}
            );
        });
    });
</script>