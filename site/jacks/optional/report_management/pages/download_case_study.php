<?php

$customer_id = $_GET['id'];
$customers = jack_obj('dev_customer_management');

$profile = $customers->get_customers(array('customer_id' => $customer_id, 'single' => true));
$age = floor((time() - strtotime(date('d-m-Y', strtotime($profile['customer_birthdate'])))) / (60*60*24*365));

if ($profile['customer_type'] == 'returnee') {
    $supports = jack_obj('dev_support_management');
    $followups = jack_obj('dev_followup_management');
    $branch_info = $this->get_branch_info(array('branch_id' => $profile['fk_branch_id'], 'single' => true));
    $staff_info = $this->get_staff_info(array('staff_id' => $profile['fk_staff_id'], 'single' => true));
    $migration_history = $customers->get_migration_history(array('customer_id' => $_GET['id']));
    $support = $supports->get_supports(array('support_customer_id' => $_GET['id']));

    foreach ($support['data'] as $i => $support_head) {
        if ($support_head['support_name'] == 'psychosocial') {
            $counsellors_data = $supports->get_counsellors(array('customer_id' => $_GET['id'], 'support_id' => $support_head['fk_support_id'], 'single' => false));
            $sessions_data = $supports->get_session(array('customer_id' => $_GET['id'], 'support_id' => $support_head['fk_support_id'], 'single' => false));
            $family_counselling_data = $supports->get_family_counselling(array('customer_id' => $_GET['id'], 'support_id' => $support_head['fk_support_id'], 'single' => false));
            $psychosocial_evaluations = $supports->get_evaluation(array('customer_id' => $_GET['id'], 'support_id' => $support_head['fk_support_id'], 'single' => false));
        }
        if ($support_head['support_name'] == 'social') {
            $social_evaluations = $supports->get_social_evaluation(array('customer_id' => $_GET['id'], 'support_id' => $support_head['fk_support_id'], 'single' => false));
        }
        if ($support_head['support_name'] == 'economic') {
            $economic_evaluations = $supports->get_economic_evaluation(array('customer_id' => $_GET['id'], 'support_id' => $support_head['fk_support_id'], 'single' => false));
        }
    }

    $all_followups = $followups->get_followups(array('customer_id' => $_GET['id']));

    $reportTitle = 'Case Study';

    require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

    $devPdf = new DEV_PDF('L', 'mm', 'A4');
    $devPdf->init();
    $devPdf->createPdf();

    $devPdf->SetFont('Times', '', 18);
    $devPdf->Cell(0, 6, $reportTitle, 0, 1, 'L');
    $devPdf->SetFont('Times', 'B', 16);
    $devPdf->Cell(0, 7, 'ID: ' . $profile['customer_id'], 0, 1, 'L');
    $devPdf->Cell(0, 6, 'Baseline Status: ' . $profile['review_remarks'], 0, 1, 'L');
    $devPdf->Cell(0, 7, '', 'T', 1);

    $devPdf->SetFont('Times', 'B', 14);
    $devPdf->Cell(0, 7, 'Personal Information of The Beneficiary', 0, 1, 'C');

    $devPdf->SetFont('Times', '', 12);
    $devPdf->Cell(0, 10, 'Name: ' . $profile['full_name'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'NID: ' . $profile['nid_number'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Birth Registration Number: ' . $profile['birth_reg_number'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Date of Birth: ' . $profile['customer_birthdate'].'.' .' (Age: '.$age.' Years)', 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Gender: ' . $profile['customer_gender'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Mobile No: ' . $profile['customer_mobile'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Emergency Mobile No: ' . $profile['emergency_mobile'] . ', Name: ' . $profile['emergency_name'] . ', Relation: ' . $profile['emergency_relation'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Marital Status: ' . ucfirst($profile['marital_status']), 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Spouse Name: ' . $profile['customer_spouse'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Educational Qualification: ' . $profile['educational_qualification'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Religion: ' . $profile['customer_religion'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Passport No: ' . $profile['passport_number'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Travel Pass: ' . $profile['travel_pass'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'BMET Smart Card No: ' . $profile['bmet_card_number'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Mother Name: ' . $profile['mother_name'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Father Name: ' . $profile['father_name'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Permanent Address: Flat- ' . $profile['permanent_flat'] . ', House- ' . $profile['permanent_house'] . ', Road- ' . $profile['permanent_road'] . ', Ward- ' . $profile['permanent_ward'] . ', Village- ' . $profile['permanent_village'] . ', Division- ' . $profile['permanent_division'] . ', District- ' . $profile['permanent_district'], 0, 1, 'L');
    $devPdf->Cell(0, 0, '                                 Sub-District- ' . $profile['permanent_sub_district'] . ', Police Station- ' . $profile['permanent_police_station'] . ', Post Office- ' . $profile['permanent_post_office'] . ', Post Code- ' . $profile['permanent_post_code'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Present Address: Flat- ' . $profile['present_flat'] . ', House- ' . $profile['present_house'] . ', Road- ' . $profile['present_road'] . ', Ward- ' . $profile['present_ward'] . ', Village- ' . $profile['present_village'] . ', Division- ' . $profile['present_division'] . ', District- ' . $profile['present_district'], 0, 1, 'L');
    $devPdf->Cell(0, 0, '                            Sub-District- ' . $profile['present_sub_district'] . ', Police Station- ' . $profile['present_police_station'] . ', Post Office- ' . $profile['present_post_office'] . ', Post Code- ' . $profile['present_post_code'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Name of Center Office/Branch: ' . $branch_info['branch_name'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Name of Case Manager: ' . $staff_info['user_fullname'], 0, 1, 'L');

    $devPdf->SetFont('Times', 'B', 14);
    $devPdf->Cell(0, 20, 'Climate Change Impact on Migration', 0, 1, 'C');

    $devPdf->SetFont('Times', '', 12);
    $devPdf->Cell(0, 0, 'Consequent migration occurs due to natural disasters caused by climate change?', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['climate_effect']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Nature of disaster: ' . $profile['natural_disaster'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Economic Impact of Change: ' . $profile['economic_impacts'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Financial Losses: ' . $profile['financial_losses'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Social Impact of Change: ' . $profile['social_impacts'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Whether overseas migration is the main reason for climate change?', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['is_climate_migration']), 0, 1, 'L');

    $devPdf->SetFont('Times', 'B', 14);
    $devPdf->Cell(0, 20, 'Migration Related Information', 0, 1, 'C');

    $devPdf->SetFont('Times', 'B', 13);
    $devPdf->Cell(0, 20, 'Situation while abroad', 0, 1, 'L');

    $devPdf->SetFont('Times', '', 12);
    $devPdf->Cell(0, 0, 'Whether during the time of abroad the victim was cheated by fraud / temptation in the job?', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['is_cheated']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Whether the money has been deducted from salary as a recruitment fee / cost?', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['is_money_deducted']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Limitation of movement (only from Residence to workplace)', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['is_movement_limitation']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Whether the employer or middleman has kept the travel related necessary documents (Passport) after leaving destination country', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['is_kept_document']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Overall experience of migration', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['migration_experience']), 0, 1, 'L');

    $devPdf->SetFont('Times', 'B', 13);
    $devPdf->Cell(0, 20, 'Situation during the process of migration', 0, 1, 'L');

    $devPdf->SetFont('Times', '', 12);
    $devPdf->Cell(0, 0, 'Left Bangladesh from which port?', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['left_port']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Preferred Country', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['preferred_country'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Last Destination Country', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['last_visited_country'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Root/Path', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['access_path']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Mode of transport', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['transport_modes']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Type of Migration', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['migration_type']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Type of Visa', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['visa_type']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Media of migration', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['migration_medias']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Cost of Migration (BDT)', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['migration_cost'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Pay to Middleman/Agency', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['agency_payment'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Occupation while abroad', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['migration_occupation'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Reason of leave destination country', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['destination_country_leave_reason'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Total amount of money earned during stay in abroad (BDT)', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['earned_money'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Amount of money to send at home while abroad (BDT)', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['sent_money'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Area of expenditure of remittance after return in source country', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['spent_types'], 0, 1, 'L');

    $devPdf->SetFont('Times', 'B', 14);
    $devPdf->Cell(0, 20, 'Immediate support services received', 0, 1, 'L');

    $devPdf->SetFont('Times', '', 12);
    $devPdf->Cell(0, 0, 'Immediate Support Service', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['immediate_support'], 0, 1, 'L');

    $devPdf->SetFont('Times', 'B', 14);
    $devPdf->Cell(0, 20, 'The cooperation that has been received since returning to the country', 0, 1, 'L');

    $devPdf->SetFont('Times', '', 12);
    $devPdf->Cell(0, 0, 'Cooperation from Government-nongovernment organization?', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['is_cooperated']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Name of Organization', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['organization_name']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Type of cooperation', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['cooperation_type']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Interested to remigration?', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['is_remigration_interest']), 0, 1, 'L');

    $devPdf->SetFont('Times', 'B', 14);
    $devPdf->Cell(0, 20, 'Psychosocial and Health Related Risk Assessment', 0, 1, 'L');

    $devPdf->SetFont('Times', '', 12);
    $devPdf->Cell(0, 0, 'Any kind of physical disability?', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['is_physically_challenged']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Type of disability', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['disability_type'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Chronic physical disorders?', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['having_chronic_disease']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'The type of disease', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['disease_type'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Need psychosocial support?', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['need_psychosocial_support']), 0, 1, 'L');

    $devPdf->SetFont('Times', 'B', 14);
    $devPdf->Cell(0, 20, 'Socio-economic profile', 0, 1, 'L');

    $devPdf->SetFont('Times', '', 12);
    $devPdf->Cell(0, 0, 'Occupation before migration', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['pre_occupation'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Present occupation', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['present_occupation'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Present monthly income (BDT)', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['present_income'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Number of household member', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   Male- ' . $profile['male_household_member'] . ', Female- ' . $profile['female_household_member'] . ', Total- ' . $profile['total_member'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Number of household member depends on the returnees income', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['total_dependent_member'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Number of earning household member', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   Male- ' . $profile['male_earning_member'] . ', Female- ' . $profile['female_earning_member'] . ', Total- ' . $profile['total_earner'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Monthly household income', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['household_income'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Monthly household expenditure', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['household_expenditure'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Returnees personal savings (BDT)', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['personal_savings'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Amount of personal debt of the Returnee', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['personal_debt'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Loan Source', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['loan_sources'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Mortgage property', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['have_mortgages']), 0, 1, 'L');
    $devPdf->Cell(0, 0, 'The type of ownership of the Residence', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['current_residence_ownership'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Type of residence', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . $profile['current_residence_type'], 0, 1, 'L');

    $devPdf->SetFont('Times', 'B', 14);
    $devPdf->Cell(0, 20, 'Skills Related Information', 0, 1, 'L');

    $devPdf->SetFont('Times', '', 12);
    $devPdf->Cell(0, 0, 'Is there a certification required for any particular work previously acquired?', 0, 1, 'L');
    $devPdf->Cell(0, 10, '   - ' . ucfirst($profile['is_certification_required']), 0, 1, 'L');
    $devPdf->Cell(0, 0, '     Technical Skills: ' . $profile['technical_skills'] . '. Non-Technical Skills: ' . $profile['non_technical_skills'] . '. Soft Skills: ' . $profile['soft_skills'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Have any income earner skills?', 0, 1, 'L');
    $devPdf->Cell(0, 0, '   - ' . ucfirst($profile['have_earner_skill']), 0, 1, 'L');
    $devPdf->Cell(0, 10, '     Technical Skills: ' . $profile['technical_have_skills'] . '. Non-Technical Skills: ' . $profile['non_technical_have_skills'] . '. Soft Skills: ' . $profile['soft_have_skills'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Do you have interested for skills training?', 0, 1, 'L');
    $devPdf->Cell(0, 10, '     Technical Skills: ' . $profile['need_skills'] . '. Non-Technical Skills: ' . $profile['non_technical_need_skills'] . '. Soft Skills: ' . $profile['soft_need_skills'], 0, 1, 'L');

    if ($support['data'] != NULL) {
        $devPdf->SetFont('Times', 'B', 14);
        $devPdf->Cell(0, 20, 'Supports', 0, 1, 'C');
        $header = array(
            array('text' => 'Support Name', 'align' => 'L', 'width' => 50),
            array('text' => 'Status', 'align' => 'L', 'width' => 20),
            array('text' => 'Social Counselling', 'align' => 'L', 'width' => 10),
            array('text' => 'Followup Counselling Session', 'align' => 'L', 'width' => 10),
            array('text' => 'Family Counselling', 'align' => 'L', 'width' => 10),
        );

        foreach ($support['data'] as $i => $item) {
            $reportData[] = array(
                ucfirst($item['support_name']),
                ucfirst($item['support_status']),
                $item['para_counsellor'],
                $item['total_session'],
                $item['family_counselling'],
            );
        }
        $devPdf->resetOptions();
        $devPdf->setOption('headers', $header);
        $devPdf->setOption('data', $reportData);
        $devPdf->addTable();
    }

    if ($counsellors_data['data'] != NULL) {
        $devPdf->SetFont('Times', 'B', 14);
        $devPdf->Cell(0, 20, 'Social Counselling/Field Organizer', 0, 1, 'C');
        $counsellor_header = array(
            array('text' => 'Date', 'align' => 'L', 'width' => 10),
            array('text' => 'Time', 'align' => 'L', 'width' => 10),
            array('text' => 'Home Visited', 'align' => 'L', 'width' => 5),
            array('text' => 'Issues Discussed', 'align' => 'L', 'width' => 10),
            array('text' => 'Family Present', 'align' => 'L', 'width' => 5),
            array('text' => 'How Many', 'align' => 'L', 'width' => 10),
            array('text' => 'Note', 'align' => 'L', 'width' => 40),
        );

        foreach ($counsellors_data['data'] as $i => $item) {
            $counsellor_reportData[] = array(
                $item['entry_date'],
                $item['entry_time'],
                ucfirst($item['home_visit']),
                $item['issues_discussed'],
                ucfirst($item['is_family_present']),
                $item['how_many'],
                $item['counsellor_note'],
            );
        }
        $devPdf->resetOptions();
        $devPdf->setOption('headers', $counsellor_header);
        $devPdf->setOption('data', $counsellor_reportData);
        $devPdf->addTable();
    }

    if ($sessions_data['data'] != NULL) {
        $devPdf->SetFont('Times', 'B', 14);
        $devPdf->Cell(0, 20, 'Followup Counselling Session', 0, 1, 'C');
        $session_header = array(
            array('text' => 'Date', 'align' => 'L', 'width' => 10),
            array('text' => 'Time', 'align' => 'L', 'width' => 10),
            array('text' => 'Place', 'align' => 'L', 'width' => 5),
            array('text' => 'Sesssion Comments', 'align' => 'L', 'width' => 10),
            array('text' => 'Plan', 'align' => 'L', 'width' => 5),
            array('text' => 'Description', 'align' => 'L', 'width' => 10),
            array('text' => 'Next Date', 'align' => 'L', 'width' => 10),
            array('text' => 'Followup Comments', 'align' => 'L', 'width' => 40),
        );

        foreach ($sessions_data['data'] as $i => $item) {
            $session_reportData[] = array(
                $item['entry_date'],
                $item['entry_time'],
                $item['session_place'],
                $item['session_comments'],
                $item['initial_plan'],
                $item['activities_description'],
                $item['next_date'],
                $item['followup_comments'],
            );
        }
        $devPdf->resetOptions();
        $devPdf->setOption('headers', $session_header);
        $devPdf->setOption('data', $session_reportData);
        $devPdf->addTable();
    }

    if ($all_followups['data'] != NULL) {
        $devPdf->SetFont('Times', 'B', 14);
        $devPdf->Cell(0, 20, 'Followups', 0, 1, 'C');
        $followup_header = array(
            array('text' => 'Date', 'align' => 'L', 'width' => 10),
            array('text' => 'Status', 'align' => 'L', 'width' => 10),
            array('text' => 'Challenges', 'align' => 'L', 'width' => 40),
            array('text' => 'Action Taken', 'align' => 'L', 'width' => 40),
        );

        foreach ($all_followups['data'] as $i => $item) {
            $followup_reportData[] = array(
                $item['followup_date'],
                $item['beneficiary_status'],
                $item['followup_challenges'],
                $item['action_taken'],
            );
        }
        $devPdf->resetOptions();
        $devPdf->setOption('headers', $followup_header);
        $devPdf->setOption('data', $followup_reportData);
        $devPdf->addTable();
    }

    if ($family_counselling_data['data'] != NULL) {
        $devPdf->SetFont('Times', 'B', 14);
        $devPdf->Cell(0, 20, 'Family Counselling', 0, 1, 'C');
        $family_counselling_header = array(
            array('text' => 'Date', 'align' => 'L', 'width' => 10),
            array('text' => 'Time', 'align' => 'L', 'width' => 10),
            array('text' => 'Place', 'align' => 'L', 'width' => 10),
            array('text' => 'Comments', 'align' => 'L', 'width' => 70),
        );

        foreach ($family_counselling_data['data'] as $i => $item) {
            $family_counselling_reportData[] = array(
                $item['entry_date'],
                $item['entry_time'],
                $item['session_place'],
                $item['session_comments'],
            );
        }
        $devPdf->resetOptions();
        $devPdf->setOption('headers', $family_counselling_header);
        $devPdf->setOption('data', $family_counselling_reportData);
        $devPdf->addTable();
    }

    if ($psychosocial_evaluations['data'] != NULL) {
        $devPdf->SetFont('Times', 'B', 14);
        $devPdf->Cell(0, 20, 'Psychosocial Evaluations', 0, 1, 'C');
        $psychosocial_evaluation_header = array(
            array('text' => 'Date', 'align' => 'L', 'width' => 10),
            array('text' => 'Score', 'align' => 'L', 'width' => 5),
            array('text' => 'Remarks', 'align' => 'L', 'width' => 10),
        );

        foreach ($psychosocial_evaluations['data'] as $i => $item) {
            $psychosocial_evaluation_reportData[] = array(
                $item['entry_date'],
                $item['evaluated_score'],
                $item['review_remarks'],
            );
        }
        $devPdf->resetOptions();
        $devPdf->setOption('headers', $psychosocial_evaluation_header);
        $devPdf->setOption('data', $psychosocial_evaluation_reportData);
        $devPdf->addTable();
    }

    if ($social_evaluations['data'] != NULL) {
        $devPdf->SetFont('Times', 'B', 14);
        $devPdf->Cell(0, 20, 'Social Evaluations', 0, 1, 'C');
        $social_evaluation_header = array(
            array('text' => 'Date', 'align' => 'L', 'width' => 10),
            array('text' => 'Score', 'align' => 'L', 'width' => 5),
            array('text' => 'Remarks', 'align' => 'L', 'width' => 10),
        );

        foreach ($social_evaluations['data'] as $i => $item) {
            $social_evaluation_reportData[] = array(
                $item['entry_date'],
                $item['evaluated_score'],
                $item['review_remarks'],
            );
        }
        $devPdf->resetOptions();
        $devPdf->setOption('headers', $social_evaluation_header);
        $devPdf->setOption('data', $social_evaluation_reportData);
        $devPdf->addTable();
    }

    if ($economic_evaluations['data'] != NULL) {
        $devPdf->SetFont('Times', 'B', 14);
        $devPdf->Cell(0, 20, 'Economic Evaluations', 0, 1, 'C');
        $economic_evaluation_header = array(
            array('text' => 'Date', 'align' => 'L', 'width' => 10),
            array('text' => 'Score', 'align' => 'L', 'width' => 5),
            array('text' => 'Remarks', 'align' => 'L', 'width' => 10),
        );

        foreach ($economic_evaluations['data'] as $i => $item) {
            $economic_evaluation_reportData[] = array(
                $item['entry_date'],
                $item['evaluated_score'],
                $item['review_remarks'],
            );
        }
        $devPdf->resetOptions();
        $devPdf->setOption('headers', $economic_evaluation_header);
        $devPdf->setOption('data', $economic_evaluation_reportData);
        $devPdf->addTable();
    }

    $devPdf->SetTitle($reportTitle, true);
    $devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
    exit();

    doAction('render_start');
}

if ($profile['customer_type'] == 'detainee') {
    $detainee_info = $customers->get_detainees(array('customer_id' => $customer_id, 'single' => true));

    $reportTitle = 'Case Study';

    require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

    $devPdf = new DEV_PDF('L', 'mm', 'A4');
    $devPdf->init();
    $devPdf->createPdf();

    $devPdf->SetFont('Times', '', 18);
    $devPdf->Cell(0, 6, $reportTitle, 0, 1, 'L');
    $devPdf->SetFont('Times', 'B', 16);
    $devPdf->Cell(0, 7, 'ID: ' . $detainee_info['customer_id'], 0, 1, 'L');
    $devPdf->Cell(0, 7, '', 'T', 1);

    $devPdf->SetFont('Times', 'B', 14);
    $devPdf->Cell(0, 7, 'Information of The Beneficiary', 0, 1, 'C');

    $devPdf->SetFont('Times', '', 12);
    $devPdf->Cell(0, 10, 'Entry Date: ' . $detainee_info['create_date'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Country: ' . $detainee_info['present_country'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Camp Name: ' . $detainee_info['camp_name'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Body Number: ' . $detainee_info['body_number'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Detainee Name: ' . $detainee_info['full_name'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Father Name: ' . $detainee_info['father_name'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Division: ' . $detainee_info['present_division'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'District: ' . $detainee_info['present_district'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Passport: ' . $detainee_info['having_passport'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'TP Applied: ' . $detainee_info['tp_applied_date'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'TP No: ' . $detainee_info['travel_pass'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'TP Issued: ' . $detainee_info['tp_issued_date'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Ticket Status Date: ' . $detainee_info['ticket_status_date'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Detainee Status: ' . $detainee_info['detainee_status'], 0, 1, 'L');
  
    $devPdf->SetTitle($reportTitle, true);
    $devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
    exit();

    doAction('render_start');  
}

if ($profile['customer_type'] == 'potential') {
    $potential = jack_obj('dev_potential_management');
    $sales = jack_obj('dev_sale_management');
    $admisions = jack_obj('dev_course_management');

    $potential_info = $potential->get_potentials(array('customer_id' => $customer_id, 'single' => true));
    $sales_info = $sales->get_sales(array('customer_id' => $customer_id, 'single' => false));
    $admission_info = $admisions->get_admissions(array('customer_id' => $customer_id, 'single' => false));
    
    $reportTitle = 'Case Study';

    require_once(common_files('absolute') . '/FPDF/class.dev_pdf.php');

    $devPdf = new DEV_PDF('L', 'mm', 'A4');
    $devPdf->init();
    $devPdf->createPdf();

    $devPdf->SetFont('Times', '', 18);
    $devPdf->Cell(0, 6, $reportTitle, 0, 1, 'L');
    $devPdf->SetFont('Times', 'B', 16);
    $devPdf->Cell(0, 7, 'ID: ' . $potential_info['customer_id'], 0, 1, 'L');
    $devPdf->Cell(0, 6, 'Skill: ' . $potential_info['skill_level'], 0, 1, 'L');
    $devPdf->Cell(0, 7, '', 'T', 1);

    $devPdf->SetFont('Times', 'B', 14);
    $devPdf->Cell(0, 7, 'Personal Information of The Customer', 0, 1, 'C');

    $devPdf->SetFont('Times', '', 12);
    $devPdf->Cell(0, 10, 'Name: ' . $potential_info['full_name'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'NID: ' . $potential_info['nid_number'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Mother Name: ' . $potential_info['mother_name'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Birth Registration Number: ' . $potential_info['birth_reg_number'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Father Name: ' . $potential_info['father_name'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Passport No: ' . $potential_info['passport_number'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Date of Birth: ' . $potential_info['customer_birthdate']    , 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Mobile No: ' . $potential_info['customer_mobile'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Monthly Income: ' . $potential_info['monthly_income'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Gender: ' . $potential_info['customer_gender'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Emergency Mobile No: ' . $potential_info['emergency_mobile'] . ', Name: ' . $potential_info['emergency_name'] . ', Relation: ' . $potential_info['emergency_relation'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Marital Status: ' . ucfirst($potential_info['marital_status']), 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Spouse Name: ' . $potential_info['customer_spouse'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Educational Qualification: ' . $potential_info['educational_qualification'], 0, 1, 'L');
    $devPdf->Cell(0, 10, 'Religion: ' . $potential_info['customer_religion'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Permanent Address: Flat- ' . $potential_info['permanent_flat'] . ', House- ' . $potential_info['permanent_house'] . ', Road- ' . $potential_info['permanent_road'] . ', Ward- ' . $potential_info['permanent_ward'] . ', Village- ' . $potential_info['permanent_village'] . ', Division- ' . $potential_info['permanent_division'] . ', District- ' . $potential_info['permanent_district'], 0, 1, 'L');
    $devPdf->Cell(0, 10, '                                 Sub-District- ' . $potential_info['permanent_sub_district'] . ', Police Station- ' . $potential_info['permanent_police_station'] . ', Post Office- ' . $potential_info['permanent_post_office'] . ', Post Code- ' . $potential_info['permanent_post_code'], 0, 1, 'L');
    $devPdf->Cell(0, 0, 'Present Address: Flat- ' . $potential_info['present_flat'] . ', House- ' . $potential_info['present_house'] . ', Road- ' . $potential_info['present_road'] . ', Ward- ' . $potential_info['present_ward'] . ', Village- ' . $potential_info['present_village'] . ', Division- ' . $potential_info['present_division'] . ', District- ' . $potential_info['present_district'], 0, 1, 'L');
    $devPdf->Cell(0, 10, '                            Sub-District- ' . $potential_info['present_sub_district'] . ', Police Station- ' . $potential_info['present_police_station'] . ', Post Office- ' . $potential_info['present_post_office'] . ', Post Code- ' . $potential_info['present_post_code'], 0, 1, 'L');

    if ($sales_info['data'] != NULL) {
        $devPdf->SetFont('Times', 'B', 14);
        $devPdf->Cell(0, 20, 'Sales', 0, 1, 'C');
        $header = array(
            array('text' => 'Date', 'align' => 'L', 'width' => 10),
            array('text' => 'Branch', 'align' => 'L', 'width' => 50),
            array('text' => 'Sale Title', 'align' => 'L', 'width' => 20),
            array('text' => 'Discount', 'align' => 'L', 'width' => 10),
            array('text' => 'Sale Total', 'align' => 'L', 'width' => 10),
        );

        foreach ($sales_info['data'] as $i => $item) {
            $reportData[] = array(
                $item['invoice_date'],
                $item['branch_name'],
                $item['sale_title'],
                $item['sale_discount'],
                $item['sale_total'],
            );
        }
        $devPdf->resetOptions();
        $devPdf->setOption('headers', $header);
        $devPdf->setOption('data', $reportData);
        $devPdf->addTable();
    }

    if ($admission_info['data'] != NULL) {
        $devPdf->SetFont('Times', 'B', 14);
        $devPdf->Cell(0, 20, 'Training', 0, 1, 'C');
        $counsellor_header = array(
            array('text' => 'Course', 'align' => 'L', 'width' => 50),
            array('text' => 'Branch', 'align' => 'L', 'width' => 30),
            array('text' => 'Batch Name', 'align' => 'L', 'width' => 20),
        );

        foreach ($admission_info['data'] as $i => $item) {
            $counsellor_reportData[] = array(
                $item['course_name'],
                $item['branch_name'],
                $item['batch_name'],
            );
        }
        $devPdf->resetOptions();
        $devPdf->setOption('headers', $counsellor_header);
        $devPdf->setOption('data', $counsellor_reportData);
        $devPdf->addTable();
    }

    $devPdf->SetTitle($reportTitle, true);
    $devPdf->outputPdf($_GET['mode'], $reportTitle . '.pdf');
    exit();

    doAction('render_start');
    
}