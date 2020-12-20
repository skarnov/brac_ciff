<?php
	use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
	use Box\Spout\Common\Entity\Row;
	use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
	use Box\Spout\Common\Entity\Style\Color;
	use Box\Spout\Common\Entity\Style\CellAlignment;

	// Array would be here
    $family_counsellings = $this->get_family_counselling(array('customer_id' => $case_info['fk_customer_id']));
    $psychosocial_sessions = $this->get_psychosocial_session(array('customer_id' => $case_info['fk_customer_id']));
    $psychosocial_completions = $this->get_psychosocial_completion(array('customer_id' => $case_info['fk_customer_id']));
    $psychosocial_followups = $this->get_psychosocial_followup(array('customer_id' => $case_info['fk_customer_id']));
    $reviews = $this->get_case_review(array('customer_id' => $case_info['fk_customer_id']));

    if(count($family_counsellings['data']) > 0 || count($psychosocial_sessions['data']) > 0 || count($psychosocial_completions['data']) > 0 || count($psychosocial_followups['data']) > 0 || count($reviews['data']) > 0){
        // $empty_row2 = WriterEntityFactory::createRowFromArray($empty_row);
        // $writer->addRow($empty_row2);
        // $count_for_mrg++;

        $mergeRanges[] = 'A'.$count_for_mrg.':CE'.$count_for_mrg;

        $style3 = (new StyleBuilder())
        ->setFontBold()
        ->setFontSize(12)
        ->setShouldWrapText()
        ->setCellAlignment(CellAlignment::CENTER)
        ->build();

        $style4 = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(11)
            ->build();

        $counseling_session = ['Counseling Session'];
        $singleRow2 = WriterEntityFactory::createRowFromArray($counseling_session, $style3);
        $writer->addRow($singleRow2);
        $count_for_mrg++;

        $con_header = [
            "",
            // Family counceling
            "Family Counseling Date",
            "Family Counseling Time",
            "Family Counseling Place",
            "Family Counseled Counseled Men",
            "Family Counseled Counseled Women",
            "Family Counseled Total Counseled",
            "Family Counseled Comments",
            // End Family Counseling

            // Psychosocial Reintegration
            "Psychosocial Reintegration Date",
            "Counseling Time",
            "Counseling Next Date",
            "Counseled Activities",
            "Comments",
            // End Psychosocial Reintegration

            // Session Completio
            "Session Completio Date",
            "Session Completion",
            "Drop-out Reason (If Any)",
            "Review of Counseling Session",
            "Comments of the Client",
            "Comments of the Counselor",
            "Final Evaluation",
            "Required Session After Completion",
            // End ession Completio

            // Psychosocial Reintegration (Followup)
            "Psychosocial Reintegration (Followup) Date",
            "Time",
            "Comments",
            // End Psychosocial Reintegration (Followup)

            // Psychosocial Review and Follow-Up
            "Review and Follow-Up Enter Date",
            "Case dropped out from the project?",
            "Reason for Dropping Out",
            "Confirmed Services Received after 3 Months",
            "Financial Service",
            "Social Protection",
            "Special Security Measures",

            "Monthly income (BDT)",
            "Challenges",
            "Actions taken",
            "Remark of the participant (If Any)",
            "Comment of BRAC Officer responsible for participant",
            "Remark of District Manager",
            "Psychosocial Reintegration Date",
            "Psychosocial Comment",
            "Economic Reintegration Date",
            "Economic Comment",
            "Social Reintegration Date",
            "Social Comment",
            "Comment on social reintegration",
            "Income Tracking Date",
            "Complete income tracking information",
            // End Review and Follow-Up
        ];

        $rowFromVal2 = WriterEntityFactory::createRowFromArray($con_header, $style4);
        $writer->addRow($rowFromVal2);
        $count_for_mrg++;
    }

    if(!empty($family_counsellings['data']) && count($family_counsellings['data']) >= count($psychosocial_sessions['data']) && count($family_counsellings['data']) >= count($psychosocial_completions['data']) && count($family_counsellings['data']) >= count($psychosocial_followups['data']) && count($family_counsellings['data']) >= count($reviews['data'])){

        $multipler = array();
        foreach ($family_counsellings['data'] as $i => $item) {
            $cellss = array();

            $cellss[] = WriterEntityFactory::createCell('');
            $cellss[] = WriterEntityFactory::createCell($item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A');
            $cellss[] = WriterEntityFactory::createCell($item['entry_time']);
            $cellss[] = WriterEntityFactory::createCell($item['session_place']);
            $cellss[] = WriterEntityFactory::createCell($item['male_household_member']);
            $cellss[] = WriterEntityFactory::createCell($item['female_household_member']);
            $cellss[] = WriterEntityFactory::createCell($item['male_household_member'] + $item['female_household_member']);
            $cellss[] = WriterEntityFactory::createCell($item['session_comments']);

            if ($psychosocial_sessions['data'] != NULL) {
                if(isset($psychosocial_sessions['data'][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_sessions['data'][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['entry_time']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['next_date'] ? date('d-m-Y', strtotime($psychosocial_sessions['data'][$i]['next_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['activities_description']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['session_comments']);
                }
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($psychosocial_completions['data'] != NULL) {
                if(isset($psychosocial_completions['data'][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_completions['data'][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell(ucfirst($psychosocial_completions['data'][$i]['is_completed']));
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['dropout_reason']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['review_session']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['client_comments']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['counsellor_comments']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['final_evaluation']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['required_session']);
                }
                
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($psychosocial_followups['data'] != NULL) {
                if(isset($psychosocial_followups['data'][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_followups['data'][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups['data'][$i]['entry_time']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups['data'][$i]['followup_comments']);
                }
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($reviews['data'] != NULL) {
                if(isset($reviews['data'][$i])){
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell(ucfirst($reviews['data'][$i]['casedropped']));
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['reason_dropping'] . ' ' . $reviews['data'][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['confirm_services'] . ' ' . $reviews['data'][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['followup_financial_service']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['social_protection']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['special_security']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['monthly_income']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['challenges']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['actions_taken']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['remark_participant']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_brac']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['remark_district']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_psychosocial_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_psychosocial_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_psychosocial']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_economic_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_economic_date'])) : 'N/A',);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_economic']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_social_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_social_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_income_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_income_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_income']);
                    
                }
                
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            $multipler[] = WriterEntityFactory::createRow($cellss);
            $count_for_mrg++;
        }

        $writer->addRows($multipler);
        //$count_for_mrg++;
        $empty_row2 = WriterEntityFactory::createRowFromArray($empty_row);
        $writer->addRow($empty_row2);
        $count_for_mrg++;

    }
    else if(!empty($psychosocial_sessions['data']) && count($psychosocial_sessions['data']) > count($family_counsellings['data']) && count($psychosocial_sessions['data']) > count($psychosocial_completions['data']) && count($psychosocial_sessions['data']) > count($psychosocial_followups['data']) && count($psychosocial_sessions['data']) > count($reviews['data'])){
        
        $multipler = array();
        foreach ($psychosocial_sessions['data'] as $i => $item) {
            $cellss = array();

            $cellss[] = WriterEntityFactory::createCell('');
            if ($family_counsellings['data'] != NULL) {
                if(isset($family_counsellings['data'][$i])){
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($family_counsellings['data'][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['session_place']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['male_household_member']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['female_household_member']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['male_household_member'] + $family_counsellings['data'][$i]['female_household_member']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['session_comments']);
                }
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            $cellss[] = WriterEntityFactory::createCell($item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A');
            $cellss[] = WriterEntityFactory::createCell($item['entry_time']);
            $cellss[] = WriterEntityFactory::createCell($item['next_date'] ? date('d-m-Y', strtotime($item['next_date'])) : 'N/A');
            $cellss[] = WriterEntityFactory::createCell($item['activities_description']);
            $cellss[] = WriterEntityFactory::createCell($item['session_comments']);

            if ($psychosocial_completions['data'] != NULL) {
                if(isset($psychosocial_completions['data'][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_completions['data'][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell(ucfirst($psychosocial_completions['data'][$i]['is_completed']));
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['dropout_reason']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['review_session']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['client_comments']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['counsellor_comments']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['final_evaluation']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['required_session']);
                }
                
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($psychosocial_followups['data'] != NULL) {
                if(isset($psychosocial_followups['data'][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_followups['data'][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups['data'][$i]['entry_time']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups['data'][$i]['followup_comments']);
                }
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($reviews['data'] != NULL) {
                if(isset($reviews['data'][$i])){
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell(ucfirst($reviews['data'][$i]['casedropped']));
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['reason_dropping'] . ' ' . $reviews['data'][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['confirm_services'] . ' ' . $reviews['data'][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['followup_financial_service']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['social_protection']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['special_security']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['monthly_income']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['challenges']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['actions_taken']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['remark_participant']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_brac']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['remark_district']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_psychosocial_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_psychosocial_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_psychosocial']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_economic_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_economic_date'])) : 'N/A',);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_economic']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_social_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_social_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_income_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_income_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_income']);
                    
                }
                
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            $multipler[] = WriterEntityFactory::createRow($cellss);
            $count_for_mrg++;
        }

        $writer->addRows($multipler, $style4);
        //$count_for_mrg++;
        $empty_row2 = WriterEntityFactory::createRowFromArray($empty_row);
        $writer->addRow($empty_row2);
        $count_for_mrg++;

    }
    else if(!empty($psychosocial_completions['data']) && count($psychosocial_completions['data']) > count($family_counsellings['data']) && count($psychosocial_completions['data']) > count($psychosocial_sessions['data']) && count($psychosocial_completions['data']) > count($psychosocial_followups['data']) && count($psychosocial_completions['data']) > count($reviews['data'])){
        
        $multipler = array();
        foreach ($psychosocial_completions['data'] as $i => $item) {
            $cellss = array();

            $cellss[] = WriterEntityFactory::createCell('');
            if ($family_counsellings['data'] != NULL) {
                if(isset($family_counsellings['data'][$i])){
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($family_counsellings['data'][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['session_place']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['male_household_member']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['female_household_member']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['male_household_member'] + $family_counsellings['data'][$i]['female_household_member']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['session_comments']);
                }
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($psychosocial_sessions['data'] != NULL) {
                if(isset($psychosocial_sessions['data'][$i])){
                	$cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_sessions['data'][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['next_date'] ? date('d-m-Y', strtotime($psychosocial_sessions['data'][$i]['next_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['activities_description']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['session_comments']);
                }
                
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            $cellss[] = WriterEntityFactory::createCell($item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A');
            $cellss[] = WriterEntityFactory::createCell(ucfirst($item['is_completed']));
            $cellss[] = WriterEntityFactory::createCell($item['dropout_reason']);
            $cellss[] = WriterEntityFactory::createCell($item['review_session']);
            $cellss[] = WriterEntityFactory::createCell($item['client_comments']);
            $cellss[] = WriterEntityFactory::createCell($item['counsellor_comments']);
            $cellss[] = WriterEntityFactory::createCell($item['final_evaluation']);
            $cellss[] = WriterEntityFactory::createCell($item['required_session']);

            if ($psychosocial_followups['data'] != NULL) {
                if(isset($psychosocial_followups['data'][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_followups['data'][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups['data'][$i]['entry_time']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups['data'][$i]['followup_comments']);
                }
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($reviews['data'] != NULL) {
                if(isset($reviews['data'][$i])){
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell(ucfirst($reviews['data'][$i]['casedropped']));
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['reason_dropping'] . ' ' . $reviews['data'][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['confirm_services'] . ' ' . $reviews['data'][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['followup_financial_service']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['social_protection']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['special_security']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['monthly_income']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['challenges']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['actions_taken']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['remark_participant']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_brac']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['remark_district']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_psychosocial_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_psychosocial_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_psychosocial']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_economic_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_economic_date'])) : 'N/A',);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_economic']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_social_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_social_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_income_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_income_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_income']);
                    
                }
                
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            $multipler[] = WriterEntityFactory::createRow($cellss);
            $count_for_mrg++;
        }

        $writer->addRows($multipler);
        $empty_row2 = WriterEntityFactory::createRowFromArray($empty_row);
        $writer->addRow($empty_row2);
        $count_for_mrg++;

    }
    else if(!empty($psychosocial_followups['data']) && count($psychosocial_followups['data']) > count($family_counsellings['data']) && count($psychosocial_followups['data']) > count($psychosocial_sessions['data']) && count($psychosocial_followups['data']) > count($psychosocial_completions['data']) && count($psychosocial_followups['data']) > count($reviews['data'])){
        
        $multipler = array();
        foreach ($psychosocial_followups['data'] as $i => $item) {
            $cellss = array();

            $cellss[] = WriterEntityFactory::createCell('');
            if ($family_counsellings['data'] != NULL) {
                if(isset($family_counsellings['data'][$i])){
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($family_counsellings['data'][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['session_place']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['male_household_member']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['female_household_member']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['male_household_member'] + $family_counsellings['data'][$i]['female_household_member']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['session_comments']);
                }
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($psychosocial_sessions['data'] != NULL) {
                if(isset($psychosocial_sessions['data'][$i])){
                	$cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_sessions['data'][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['next_date'] ? date('d-m-Y', strtotime($psychosocial_sessions['data'][$i]['next_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['activities_description']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['session_comments']);
                }
                
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($psychosocial_completions['data'] != NULL) {
                if(isset($psychosocial_completions['data'][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_completions['data'][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell(ucfirst($psychosocial_completions['data'][$i]['is_completed']));
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['dropout_reason']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['review_session']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['client_comments']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['counsellor_comments']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['final_evaluation']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['required_session']);
                }
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            $cellss[] = WriterEntityFactory::createCell($item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A');
            $cellss[] = WriterEntityFactory::createCell($item['entry_time']);
            $cellss[] = WriterEntityFactory::createCell($item['followup_comments']);

            if ($reviews['data'] != NULL) {
                if(isset($reviews['data'][$i])){
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell(ucfirst($reviews['data'][$i]['casedropped']));
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['reason_dropping'] . ' ' . $reviews['data'][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['confirm_services'] . ' ' . $reviews['data'][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['followup_financial_service']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['social_protection']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['special_security']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['monthly_income']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['challenges']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['actions_taken']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['remark_participant']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_brac']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['remark_district']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_psychosocial_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_psychosocial_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_psychosocial']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_economic_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_economic_date'])) : 'N/A',);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_economic']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_social_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_social_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_income_date'] ? date('d-m-Y', strtotime($reviews['data'][$i]['comment_income_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews['data'][$i]['comment_income']);
                    
                }
                
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            $multipler[] = WriterEntityFactory::createRow($cellss);
            $count_for_mrg++;
        }

        $writer->addRows($multipler);
        //$count_for_mrg++;
        $empty_row2 = WriterEntityFactory::createRowFromArray($empty_row);
        $writer->addRow($empty_row2);
        $count_for_mrg++;

    }
    else if(!empty($reviews['data']) && count($reviews['data']) > count($family_counsellings['data']) && count($reviews['data']) > count($psychosocial_sessions['data']) && count($reviews['data']) > count($psychosocial_completions['data']) && count($reviews['data']) > count($psychosocial_followups['data'])){
        
        $multipler = array();
        foreach ($reviews['data'] as $i => $item) {
            $cellss = array();

            $cellss[] = WriterEntityFactory::createCell('');
            if ($family_counsellings['data'] != NULL) {
                if(isset($family_counsellings['data'][$i])){
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($family_counsellings['data'][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['session_place']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['male_household_member']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['female_household_member']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['male_household_member'] + $family_counsellings['data'][$i]['female_household_member']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings['data'][$i]['session_comments']);
                }
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($psychosocial_sessions['data'] != NULL) {
                if(isset($psychosocial_sessions['data'][$i])){
                	$cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_sessions['data'][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['next_date'] ? date('d-m-Y', strtotime($psychosocial_sessions['data'][$i]['next_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['activities_description']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions['data'][$i]['session_comments']);
                }
                
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($psychosocial_completions['data'] != NULL) {
                if(isset($psychosocial_completions['data'][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_completions['data'][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell(ucfirst($psychosocial_completions['data'][$i]['is_completed']));
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['dropout_reason']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['review_session']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['client_comments']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['counsellor_comments']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['final_evaluation']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions['data'][$i]['required_session']);
                }
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($psychosocial_followups['data'] != NULL) {
                if(isset($psychosocial_followups['data'][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups['data'][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_followups['data'][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_followups['data'][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_followups['data'][$i]['followup_comments']);
                }
                
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            $cellss[] = WriterEntityFactory::createCell($item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A');
            $cellss[] = WriterEntityFactory::createCell(ucfirst($item['casedropped']));
            $cellss[] = WriterEntityFactory::createCell($item['reason_dropping'] . ' ' . $item['other_reason_dropping']);
            $cellss[] = WriterEntityFactory::createCell($item['confirm_services'] . ' ' . $item['other_reason_dropping']);
            $cellss[] = WriterEntityFactory::createCell($item['followup_financial_service']);
            $cellss[] = WriterEntityFactory::createCell($item['social_protection']);
            $cellss[] = WriterEntityFactory::createCell($item['special_security']);
            $cellss[] = WriterEntityFactory::createCell($item['monthly_income']);
            $cellss[] = WriterEntityFactory::createCell($item['challenges']);
            $cellss[] = WriterEntityFactory::createCell($item['actions_taken']);
            $cellss[] = WriterEntityFactory::createCell($item['remark_participant']);
            $cellss[] = WriterEntityFactory::createCell($item['comment_brac']);
            $cellss[] = WriterEntityFactory::createCell($item['remark_district']);
            $cellss[] = WriterEntityFactory::createCell($item['comment_psychosocial_date'] ? date('d-m-Y', strtotime($item['comment_psychosocial_date'])) : 'N/A');
            $cellss[] = WriterEntityFactory::createCell($item['comment_psychosocial']);
            $cellss[] = WriterEntityFactory::createCell($item['comment_economic_date'] ? date('d-m-Y', strtotime($item['comment_economic_date'])) : 'N/A',);
            $cellss[] = WriterEntityFactory::createCell($item['comment_economic']);
            $cellss[] = WriterEntityFactory::createCell($item['comment_social_date'] ? date('d-m-Y', strtotime($item['comment_social_date'])) : 'N/A');
            $cellss[] = WriterEntityFactory::createCell($item['comment_social']);
            $cellss[] = WriterEntityFactory::createCell($item['comment_social']);
            $cellss[] = WriterEntityFactory::createCell($item['comment_income_date'] ? date('d-m-Y', strtotime($item['comment_income_date'])) : 'N/A');
            $cellss[] = WriterEntityFactory::createCell($item['comment_income']);

            $multipler[] = WriterEntityFactory::createRow($cellss);
            $count_for_mrg++;
        }

        $writer->addRows($multipler);
        //$count_for_mrg++;
        $empty_row2 = WriterEntityFactory::createRowFromArray($empty_row);
        $writer->addRow($empty_row2);
        $count_for_mrg++;

    }
    

?>