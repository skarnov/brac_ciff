<?php
	use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
	use Box\Spout\Common\Entity\Row;
	use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
	use Box\Spout\Common\Entity\Style\Color;
	use Box\Spout\Common\Entity\Style\CellAlignment;

    $count_fc = 0;
    $count_ps = 0;
    $count_pc = 0;
    $count_pf = 0;
    $count_rv = 0;

    if(isset($family_counsellings_array[$case_info['fk_customer_id']]))
    	$count_fc = count($family_counsellings_array[$case_info['fk_customer_id']]);
    if(isset($psychosocial_sessions_array[$case_info['fk_customer_id']]))
    	$count_ps = count($psychosocial_sessions_array[$case_info['fk_customer_id']]);
    if(isset($psychosocial_completions_array[$case_info['fk_customer_id']]))
    	$count_pc = count($psychosocial_completions_array[$case_info['fk_customer_id']]);
    if(isset($psychosocial_followups_array[$case_info['fk_customer_id']]))
    	$count_pf = count($psychosocial_followups_array[$case_info['fk_customer_id']]);
    if(isset($reviews_array[$case_info['fk_customer_id']]))
    	$count_rv = count($reviews_array[$case_info['fk_customer_id']]);

    if($count_fc > 0 || $count_ps > 0 ||  $count_pc > 0 || $count_pf > 0 ||  $count_rv > 0){
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

    if($count_fc > 0 && $count_fc >= $count_ps && $count_fc >= $count_pc && $count_fc >= $count_pf && $count_fc >= $count_rv){

        $multipler = array();
        foreach ($family_counsellings_array[$case_info['fk_customer_id']] as $i => $item) {
            $cellss = array();

            $cellss[] = WriterEntityFactory::createCell('');
            $cellss[] = WriterEntityFactory::createCell($item['entry_date'] ? date('d-m-Y', strtotime($item['entry_date'])) : 'N/A');
            $cellss[] = WriterEntityFactory::createCell($item['entry_time']);
            $cellss[] = WriterEntityFactory::createCell($item['session_place']);
            $cellss[] = WriterEntityFactory::createCell($item['male_counseled']);
            $cellss[] = WriterEntityFactory::createCell($item['female_counseled']);
            $cellss[] = WriterEntityFactory::createCell($item['members_counseled']);
            $cellss[] = WriterEntityFactory::createCell($item['session_comments']);

            if ($psychosocial_sessions_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($psychosocial_sessions_array[$case_info['fk_customer_id']][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['entry_time']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['next_date'] ? date('d-m-Y', strtotime($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['next_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['activities_description']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['session_comments']);
                }else{
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	            }
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($psychosocial_completions_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($psychosocial_completions_array[$case_info['fk_customer_id']][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell(ucfirst($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['is_completed']));
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['dropout_reason']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['review_session']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['client_comments']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['counsellor_comments']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['final_evaluation']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['required_session']);
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

            if ($psychosocial_followups_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($psychosocial_followups_array[$case_info['fk_customer_id']][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['entry_time']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['followup_comments']);
                }else{
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	            }
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($reviews_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($reviews_array[$case_info['fk_customer_id']][$i])){
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell(ucfirst($reviews_array[$case_info['fk_customer_id']][$i]['casedropped']));
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['reason_dropping'] . ' ' . $reviews_array[$case_info['fk_customer_id']][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['confirm_services'] . ' ' . $reviews_array[$case_info['fk_customer_id']][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['followup_financial_service']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['social_protection']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['special_security']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['monthly_income']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['challenges']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['actions_taken']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['remark_participant']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_brac']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['remark_district']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_psychosocial_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_psychosocial_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_psychosocial']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_economic_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_economic_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_economic']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_social_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_social_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_income_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_income_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_income']);
                    
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
    else if($count_ps > 0 && $count_ps > $count_fc && $count_ps >= $count_pc && $count_ps >= $count_pf && $count_ps >= $count_rv){
        
        $multipler = array();
        foreach ($psychosocial_sessions_array[$case_info['fk_customer_id']] as $i => $item) {
            $cellss = array();

            $cellss[] = WriterEntityFactory::createCell('');
            if ($family_counsellings_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($family_counsellings_array[$case_info['fk_customer_id']][$i])){
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($family_counsellings_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['session_place']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['male_counseled']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['female_counseled']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['members_counseled']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['session_comments']);
                }else{
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
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

            if ($psychosocial_completions_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($psychosocial_completions_array[$case_info['fk_customer_id']][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell(ucfirst($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['is_completed']));
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['dropout_reason']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['review_session']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['client_comments']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['counsellor_comments']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['final_evaluation']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['required_session']);
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

            if ($psychosocial_followups_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($psychosocial_followups_array[$case_info['fk_customer_id']][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['entry_time']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['followup_comments']);
                }else{
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	            }
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($reviews_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($reviews_array[$case_info['fk_customer_id']][$i])){
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell(ucfirst($reviews_array[$case_info['fk_customer_id']][$i]['casedropped']));
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['reason_dropping'] . ' ' . $reviews_array[$case_info['fk_customer_id']][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['confirm_services'] . ' ' . $reviews_array[$case_info['fk_customer_id']][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['followup_financial_service']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['social_protection']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['special_security']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['monthly_income']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['challenges']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['actions_taken']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['remark_participant']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_brac']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['remark_district']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_psychosocial_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_psychosocial_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_psychosocial']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_economic_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_economic_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_economic']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_social_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_social_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_income_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_income_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_income']);
                    
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
    else if($count_pc > 0 && $count_pc > $count_fc && $count_pc > $count_ps && $count_pc > $count_pf && $count_pc > $count_rv){
        
        $multipler = array();
        foreach ($psychosocial_completions_array[$case_info['fk_customer_id']] as $i => $item) {
            $cellss = array();

            $cellss[] = WriterEntityFactory::createCell('');
            if ($family_counsellings_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($family_counsellings_array[$case_info['fk_customer_id']][$i])){
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($family_counsellings_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['session_place']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['male_counseled']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['female_counseled']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['members_counseled']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['session_comments']);
                }else{
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
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

            if ($psychosocial_sessions_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($psychosocial_sessions_array[$case_info['fk_customer_id']][$i])){
                	$cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['next_date'] ? date('d-m-Y', strtotime($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['next_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['activities_description']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['session_comments']);
                }else{
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
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

            if ($psychosocial_followups_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($psychosocial_followups_array[$case_info['fk_customer_id']][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['entry_time']);
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups[$case_info['fk_customer_id']][$i]['followup_comments']);
                }else{
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	            }
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($reviews_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($reviews_array[$case_info['fk_customer_id']][$i])){
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell(ucfirst($reviews_array[$case_info['fk_customer_id']][$i]['casedropped']));
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['reason_dropping'] . ' ' . $reviews_array[$case_info['fk_customer_id']][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['confirm_services'] . ' ' . $reviews_array[$case_info['fk_customer_id']][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['followup_financial_service']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['social_protection']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['special_security']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['monthly_income']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['challenges']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['actions_taken']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['remark_participant']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_brac']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['remark_district']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_psychosocial_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_psychosocial_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_psychosocial']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_economic_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_economic_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_economic']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_social_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_social_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_income_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_income_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_income']);
                    
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
    else if($count_pf > 0 && $count_pf >= $count_fc  && $count_pf >= $count_ps && $count_pf >= $count_pc && $count_pf >= $count_rv){
        
        $multipler = array();
        foreach ($psychosocial_followups_array[$case_info['fk_customer_id']] as $i => $item) {
            $cellss = array();

            $cellss[] = WriterEntityFactory::createCell('');
            if ($family_counsellings_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($family_counsellings_array[$case_info['fk_customer_id']][$i])){
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($family_counsellings_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['session_place']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['male_counseled']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['female_counseled']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['members_counseled']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['session_comments']);
                }else{
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
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

            if ($psychosocial_sessions_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($psychosocial_sessions_array[$case_info['fk_customer_id']][$i])){
                	$cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['next_date'] ? date('d-m-Y', strtotime($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['next_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['activities_description']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['session_comments']);
                }else{
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	            }
                
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if ($psychosocial_completions_array[$case_info['fk_customer_id']] != NULL) {
                if(isset($psychosocial_completions_array[$case_info['fk_customer_id']][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell(ucfirst($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['is_completed']));
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['dropout_reason']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['review_session']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['client_comments']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['counsellor_comments']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['final_evaluation']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['required_session']);
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

            if ($reviews[$case_info['fk_customer_id']] != NULL) {
                if(isset($reviews_array[$case_info['fk_customer_id']][$i])){
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell(ucfirst($reviews_array[$case_info['fk_customer_id']][$i]['casedropped']));
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['reason_dropping'] . ' ' . $reviews_array[$case_info['fk_customer_id']][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['confirm_services'] . ' ' . $reviews_array[$case_info['fk_customer_id']][$i]['other_reason_dropping']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['followup_financial_service']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['social_protection']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['special_security']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['monthly_income']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['challenges']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['actions_taken']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['remark_participant']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_brac']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['remark_district']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_psychosocial_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_psychosocial_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_psychosocial']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_economic_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_economic_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_economic']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_social_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_social_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_social']);
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_income_date'] ? date('d-m-Y', strtotime($reviews_array[$case_info['fk_customer_id']][$i]['comment_income_date'])) : 'N/A');
                    $cellss[] = WriterEntityFactory::createCell($reviews_array[$case_info['fk_customer_id']][$i]['comment_income']);
                    
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
    else if($count_rv > 0 && $count_rv >= $count_fc && $count_rv >= $count_ps && $count_rv >= $count_pc && $count_rv >= $count_pf){

        $multipler = array();
        foreach ($reviews_array[$case_info['fk_customer_id']] as $i => $item) {
            $cellss = array();

            $cellss[] = WriterEntityFactory::createCell('');
            if (isset($family_counsellings_array[$case_info['fk_customer_id']])) {
                if(isset($family_counsellings_array[$case_info['fk_customer_id']][$i])){
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($family_counsellings_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['session_place']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['male_counseled']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['female_counseled']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['members_counseled']);
		            $cellss[] = WriterEntityFactory::createCell($family_counsellings_array[$case_info['fk_customer_id']][$i]['session_comments']);
                }else{
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
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

            if (isset($psychosocial_sessions_array[$case_info['fk_customer_id']])) {
                if(isset($psychosocial_sessions_array[$case_info['fk_customer_id']][$i])){
                	$cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['next_date'] ? date('d-m-Y', strtotime($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['next_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['activities_description']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_sessions_array[$case_info['fk_customer_id']][$i]['session_comments']);
                }else{
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	            }
                
            }else{
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
                $cellss[] = WriterEntityFactory::createCell('');
            }

            if (isset($psychosocial_completions_array[$case_info['fk_customer_id']])) {
                if(isset($psychosocial_completions_array[$case_info['fk_customer_id']][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell(ucfirst($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['is_completed']));
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['dropout_reason']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['review_session']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['client_comments']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['counsellor_comments']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['final_evaluation']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_completions_array[$case_info['fk_customer_id']][$i]['required_session']);
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

            if (isset($psychosocial_followups_array[$case_info['fk_customer_id']])) {
                if(isset($psychosocial_followups_array[$case_info['fk_customer_id']][$i])){
                    $cellss[] = WriterEntityFactory::createCell($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['entry_date'] ? date('d-m-Y', strtotime($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['entry_date'])) : 'N/A');
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['entry_time']);
		            $cellss[] = WriterEntityFactory::createCell($psychosocial_followups_array[$case_info['fk_customer_id']][$i]['followup_comments']);
                }else{
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
	                $cellss[] = WriterEntityFactory::createCell('');
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
            $cellss[] = WriterEntityFactory::createCell($item['comment_economic_date'] ? date('d-m-Y', strtotime($item['comment_economic_date'])) : 'N/A');
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