<?php

$customer_id = $_GET['id'];
$profile = $this->get_customers(array('customer_id' => $customer_id, 'single' => true));

$nid_number = $profile['nid_number'] ? $profile['nid_number'] : 'N/A';
$birth_reg_number = $profile['birth_reg_number'] ? $profile['birth_reg_number'] : 'N/A';
$customer_spouse = $profile['customer_spouse'] ? $profile['customer_spouse'] : 'N/A';

if ($profile['educational_qualification'] == 'illiterate'):
    $educational_qualification = 'Illiterate';
elseif ($profile['educational_qualification'] == 'sign'):
    $educational_qualification = 'Can Sign only';
elseif ($profile['educational_qualification'] == 'psc'):
    $educational_qualification = 'Primary education (Passed Grade 5)';
elseif ($profile['educational_qualification'] == 'not_psc'):
    $educational_qualification = 'Did not complete primary education';
elseif ($profile['educational_qualification'] == 'jsc'):
    $educational_qualification = 'Completed JSC (Passed Grade 8) or equivalent';
elseif ($profile['educational_qualification'] == 'ssc'):
    $educational_qualification = 'Completed School Secondary Certificate or equivalent';
elseif ($profile['educational_qualification'] == 'hsc'):
    $educational_qualification = 'Higher Secondary Certificate/Diploma/ equivalent';
elseif ($profile['educational_qualification'] == 'bachelor'):
    $educational_qualification = 'Bachelor’s degree or equivalent';
elseif ($profile['educational_qualification'] == 'master'):
    $educational_qualification = 'Masters or Equivalent';
elseif ($profile['educational_qualification'] == 'professional_education'):
    $educational_qualification = 'Completed Professional education';
elseif ($profile['educational_qualification'] == 'general_education'):
    $educational_qualification = 'Completed general Education';
else:
    $educational_qualification = 'N/A';
endif;

if ($profile['current_residence_ownership'] == 'own'):
    $current_residence_ownership = 'Own';
elseif ($profile['educational_qualification'] == 'rental'):
    $current_residence_ownership = 'Rental';
elseif ($profile['educational_qualification'] == 'without_paying'):
    $current_residence_ownership = 'Live without paying';
elseif ($profile['educational_qualification'] == 'khas_land'):
    $current_residence_ownership = 'Khas land';
else:
    $current_residence_ownership = 'N/A';
endif;

if ($profile['current_residence_type'] == 'raw_house'):
    $current_residence_type = 'Raw house (wall made of mud/straw, roof made of tin jute stick/ pampas grass/ khar/ leaves)';
elseif ($profile['current_residence_type'] == 'pucca'):
    $current_residence_type = 'Pucca (wall, floor and roof of the house made of concrete)';
elseif ($profile['current_residence_type'] == 'live'):
    $current_residence_type = 'Live Semi-pucca (roof made of tin, wall or floor made of concrete)';
elseif ($profile['current_residence_type'] == 'tin'):
    $current_residence_type = 'Tin (wall, and roof of the house made of tin)';
else:
    $current_residence_type = 'N/A';
endif;

$family_members = $profile['male_household_member'] + $profile['female_household_member'] + $profile['boy_household_member'] + $profile['girl_household_member'];
$disability_type = $profile['disability_type'] ? $profile['disability_type'] : 'N/A';
$have_skills = $profile['have_skills'] ? $profile['have_skills'] : 'N/A';
$disease_type = $profile['disease_type'] ? $profile['disease_type'] : 'N/A';

$reportTitle = 'Beneficiary Profile';

require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

$devPdf = new DEV_PDF('P', 'mm', 'A4');
$devPdf->init();
$devPdf->createPdf();

$devPdf->SetFont('Times', '', 18);
$devPdf->Cell(0, 6, $reportTitle, 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 7, 'Section 1: Personal information', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Beneficiary ID/Reference number: ' . $profile['customer_id'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Full Name: ' . $profile['full_name'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'NID Number: ' . $nid_number, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Birth Registration Number: ' . $birth_reg_number, 0, 1, 'L');
$devPdf->Cell(0, 10, "Father's Name: " . $profile['father_name'], 0, 1, 'L');
$devPdf->Cell(0, 0, "Mother's Name: " . $profile['mother_name'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Date of Birth: ' . date('d-m-Y', strtotime($profile['customer_birthdate'])), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Gender: ' . ucfirst($profile['customer_gender']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Marital Status: ' . ucfirst($profile['marital_status']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Spouse Name: ' . $customer_spouse, 0, 1, 'L');
$devPdf->Cell(0, 10, 'Mobile No: ' . $profile['customer_mobile'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Emergency Mobile No: ' . $profile['emergency_mobile'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Name of that Person: ' . $profile['emergency_name'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Relation with Participant: ' . $profile['emergency_relation'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Village: ' . $profile['permanent_village'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Ward No: ' . $profile['permanent_ward'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Union: ' . ucfirst($profile['permanent_union']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Upazilla: ' . ucfirst($profile['permanent_sub_district']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'District: ' . ucfirst($profile['permanent_district']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Present Address of Beneficiary: ' . $profile['permanent_house'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Educational Qualification: ' . $educational_qualification, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Boy (<18): ' . $profile['boy_household_member'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Girl (<18): ' . $profile['girl_household_member'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Men (>=18): ' . $profile['male_household_member'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Women (>=18): ' . $profile['female_household_member'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Number of Accompany/ Number of Family Member: ' . $family_members, 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 2: Trafficking/ Migration history', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Transit/ Route of Migration/ Trafficking: ' . $profile['left_port'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Desired destination: ' . $profile['preferred_country'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Final destination: ' . $profile['final_destination'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Type of Channels: ' . ucfirst($profile['migration_type']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Type of Visa: ' . ucfirst($profile['visa_type']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Name of Media Departure: ' . ucfirst($profile['departure_media']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Relation of Media Departure: ' . ucfirst($profile['media_relation']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Address of Media Departure: ' . ucfirst($profile['media_address']), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Passport No: ' . $profile['passport_number'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Date of Departure from Bangladesh: ' . date('d-m-Y', strtotime($profile['departure_date'])), 0, 1, 'L');
$devPdf->Cell(0, 10, 'Date of Return to Bangladesh: ' . date('d-m-Y', strtotime($profile['return_date'])), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Age (When come back in Bangladesh): ' . $profile['returned_age'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Duration of Stay Abroad (Months): ' . $profile['migration_duration'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Occupation in overseas country: ' . $profile['migration_occupation'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Income: (If applicable): ' . $profile['earned_money'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Reasons for Migration: ' . $profile['migration_reasons'] . ' ' . $profile['other_migration_reason'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Reasons for returning to Bangladesh: ' . $profile['destination_country_leave_reason'] . ' ' . $profile['other_destination_country_leave_reason'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'False promises about a job prior to arrival at workplace abroad', 0, 1, 'L');
$devPdf->Cell(0, 10, '   - ' . ucfirst($profile['is_cheated']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Forced to perform work or other activities against your will, after the departure from Bangladesh?', 0, 1, 'L');
$devPdf->Cell(0, 10, '   - ' . ucfirst($profile['forced_work']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Experienced excessive working hours (more than 40 hours a week)', 0, 1, 'L');
$devPdf->Cell(0, 10, '   - ' . ucfirst($profile['excessive_work']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Deductions from salary for recruitment fees at workplace', 0, 1, 'L');
$devPdf->Cell(0, 10, '   - ' . ucfirst($profile['is_money_deducted']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Denied freedom of movement during or between work shifts after your departure from Bangladesh?', 0, 1, 'L');
$devPdf->Cell(0, 7, '   - ' . ucfirst($profile['is_movement_limitation']), 0, 1, 'L');
$devPdf->MultiCell(0, 5, 'Threatened by employer or someone acting on their behalf, or the broker with violence or action by law enforcement/deportation?');
$devPdf->Cell(0, 5, '   - ' . ucfirst($profile['employer_threatened']), 0, 1, 'L');
$devPdf->MultiCell(0, 5, 'Have you ever had identity or travel documents (passport) withheld by an employer or broker after your departure from Bangladesh?');
$devPdf->Cell(0, 5, '   - ' . ucfirst($profile['is_kept_document']), 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 3: Socio Economic Profile', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Any Property/wealth: ' . $profile['property_name'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Any Property/wealth Value: ' . $profile['property_value'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Main occupation (Before trafficking): ' . $profile['pre_occupation'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Main occupation (after return): ' . $profile['present_occupation'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Monthly income of Survivor after return(in BDT): ' . $profile['present_income'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Source of income (Last month in BDT): ' . $profile['returnee_income_source'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Source of income: ' . $profile['income_source'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Total Family income(last month): ' . $profile['family_income'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Savings (BDT): ' . $profile['personal_savings'], 0, 1, 'L');
$devPdf->Cell(0, 0, 'Loan Amount (BDT): ' . $profile['personal_debt'], 0, 1, 'L');
$devPdf->Cell(0, 10, 'Ownership of House: ' . $current_residence_ownership, 0, 1, 'L');
$devPdf->Cell(0, 0, 'Type of house: ' . $current_residence_type, 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 4: Information on Income Generating Activities(IGA) Skills', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Any IGA Skills? ' . ucfirst($profile['have_earner_skill']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'IGA Skills: ' . $have_skills . ' ' . $profile['other_have_skills']. ' ' . $profile['vocational_skill']. ' ' . $profile['handicraft_skill'], 0, 1, 'L');
$devPdf->Cell(0, 10, '', 0, 1, 'L');

$devPdf->SetFont('Times', 'B', 14);
$devPdf->Cell(0, 13, 'Section 5: Vulnerability Assessment', 0, 1, 'C');

$devPdf->SetFont('Times', '', 12);
$devPdf->Cell(0, 10, 'Do you have any disability? ' . ucfirst($profile['is_physically_challenged']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Type of disability: ' . $disability_type, 0, 1, 'L');

$devPdf->Cell(0, 10, 'Any Chronic Disease? ' . ucfirst($profile['having_chronic_disease']), 0, 1, 'L');
$devPdf->Cell(0, 0, 'Type of Disease: ' . $disease_type . ' ' . $profile['other_disease_type'], 0, 1, 'L');

$devPdf->SetTitle($reportTitle, true);
$devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
exit();

doAction('render_start');