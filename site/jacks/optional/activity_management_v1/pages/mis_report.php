<?php
$MONTHS = array(
    1 => 'january',
    2 => 'february',
    3 => 'march',
    4 => 'april',
    5 => 'may',
    6 => 'june',
    7 => 'july',
    8 => 'august',
    9 => 'september',
    10 => 'october',
    11 => 'november',
    12 => 'december'
);
$branches = jack_obj('dev_branch_management');

$processData = $_GET['download_report'] || $_GET['generate_report'] ? true : false;

$generateReport = $_GET['generate_report'] ? true : false;
$downloadReport = $_GET['download_report'] ? true : false;

$filterDrsc = $_GET['drsc'] ? $_GET['drsc'] : null;
$filterUlo = $_GET['ulo'] ? $_GET['ulo'] : null;
$filterProject = $_GET['project'] ? $_GET['project'] : null;

$filterFromMonthYear = $_GET['from_month_year'] ? $_GET['from_month_year'] : null;
$filterToMonthYear = $_GET['to_month_year'] ? $_GET['to_month_year'] : null;
$fromYears = array();
$fromYearsMonths = array();
$involvedDRSC = array();
$involvedULO = array();

if(!$filterFromMonthYear){
    $SQL = "SELECT MIN(_year) FROM mis_activity_distribution ";
    $minYear = $devdb->get_var($SQL);
    if(!$minYear) $minYear = date('Y');
    $filterFromMonthYear = $minYear.'-01';
    $_GET['from_month_year'] = $filterFromMonthYear;
}

if(!$filterToMonthYear){
    $thisYear = date('Y');
    $thisMonth = date('m');
    $filterToMonthYear = $thisYear.'-'.$thisMonth;
    $_GET['to_month_year'] = $filterToMonthYear;
}


$fromDate = $filterFromMonthYear.'-01';
$fromYear = date('Y', strtotime($fromDate));
$fromMonth = date('n', strtotime($fromDate));

$toDate = $filterToMonthYear.'-01';
$toYear = date('Y', strtotime($toDate));
$toMonth = date('n', strtotime($toDate));

if($fromYear > $toYear){
    $filterFromMonthYear = $filterToMonthYear;
    $fromDate = $toDate;
    $fromYear = $fromYear;
    $fromMonth = $toMonth;
    $_GET['from_month_year'] = $filterFromMonthYear;
}

for($i=$fromYear;$i<=$toYear;$i++){
    $fromYears[$i] = $i;
    $fromYearsMonths[$i] = array();
}

foreach($fromYears as $eachYear){
    if($eachYear == $fromYear){
        for($i=1;$i<=$fromMonth;$i++){
            $fromYearsMonths[$eachYear][$i] = $i;
        }
    }
    else if($eachYear == $toYear){
        for($i=1;$i<=$toMonth;$i++){
            $fromYearsMonths[$eachYear][$i] = $i;
        }
    }
    else{
        for($i=1;$i<=12;$i++){
            $fromYearsMonths[$eachYear][$i] = $i;
        }
    }
}


$isDrscUser = false;
$isUloUser = false;

$staffManager = jack_obj('dev_staff_management');
$myBranchType = $staffManager->getMyBranchType();

if($staffManager->isStaff() && $myBranchType == 'drsc'){
    $filterDrsc = $_config['user']['user_branch'];
    $isDrscUser = true;
}

if($staffManager->isStaff() && $myBranchType == 'ulo'){
    $myBranch = $staffManager->getMyBranch();
    $filterDrsc = $myBranch['fk_branch_id'];
    $filterUlo = $_config['user']['user_branch'];
    $isUloUser = true;
}

$ignoreDrscSelection = $isUloUser ? true : ($isDrscUser ? true : false);
$ignoreUloSelection = $isUloUser ? true : false;

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects(array('single' => false));

$all_drsc = $branches->get_branches(array('branch_type_slug' => 'drsc', 'single' => false));
$all_ulo = $branches->get_branches(array('branch_type_slug' => 'ulo', 'parent_branch' => $filterDrsc, 'single' => false));

if($processData){
    $err = array();
    //TODO: also check if te ulo is under this drsc
    $theDrsc = null;
    if($filterDrsc){
        $theDrsc = $branches->get_branches(array('id' => $filterDrsc, 'single' => true));
        if(!$theDrsc){
            $filterDrsc = null;
            $err[] = 'Invalid DRSC selected';
        }
    }
    $theUlo = null;
    if($filterUlo){
        $theUlo = $branches->get_branches(array('id' => $filterUlo, 'single' => true));
        if(!$theUlo){
            $filterUlo = null;
            $err[] = 'Invalid ULO selected';
        }
    }

    $projects = jack_obj('dev_project_management');

    $theProject = null;
    if($filterProject){
        $theProject = $projects->get_projects(array('id' => $filterProject, 'single' => true));
        if(!$theProject){
            $filterProject = null;
            $err[] = 'Invalid project selected';
        }
    }
    else{
        $err[] = 'Project is required, select a project';
    }

	if($theUlo){
		if($theUlo['fk_branch_id'] != $filterDrsc){
			$err[] = 'Please select valid DRSC and ULO';
		}	
	}

    if($err){
        if($downloadReport){
            foreach($err as $e){
                echo '<h1 style="color: red">'.$e.'</h1>';
            }
        }
        else{
            foreach($err as $e){
                add_notification($e, 'error');
            }
        }
    }
    else{
        $targetSQL = "SUM((CASE ".PHP_EOL;
        $yearsWithAllMonths = array();
        foreach($fromYearsMonths as $eachYear=>$yearMonths){
            if(count($yearMonths) == 12){
                $yearsWithAllMonths[] = $eachYear;
                //$targetSQL .= " total_target ".PHP_EOL;
            }
            else{
                $targetSQL .= " WHEN distribution._year = '$eachYear' THEN ";
                $thisColumns = array();
                foreach($yearMonths as $eachMonth){
                    $thisColumns[] = $MONTHS[$eachMonth].'_target';
                }
                $targetSQL .= " (".implode(' + ', $thisColumns).") ".PHP_EOL;
            }
        }
        if($yearsWithAllMonths){
            $targetSQL .= " WHEN distribution._year IN ('".implode("', '", $yearsWithAllMonths)."') THEN total_target ".PHP_EOL;
            $yearsWithAllMonths = array();
        }
        $targetSQL .= " END)) as totalTargets".PHP_EOL;

        $achievementSQL = "SUM((CASE ".PHP_EOL;
        $yearsWithAllMonths = array();
        foreach($fromYearsMonths as $eachYear=>$yearMonths){

            if(count($yearMonths) == 12)
                $yearsWithAllMonths[] = $eachYear;
            else{
                $achievementSQL .= " WHEN distribution._year = '$eachYear' THEN ";
                $thisColumns = array();
                foreach($yearMonths as $eachMonth){
                    $thisColumns[] = $MONTHS[$eachMonth].'_achievement';
                }
                $achievementSQL .= " (".implode(' + ', $thisColumns).") ".PHP_EOL;
            }
        }
        if($yearsWithAllMonths){
            $achievementSQL .= " WHEN distribution._year IN ('".implode("', '", $yearsWithAllMonths)."') THEN total_achievement ".PHP_EOL;
            $yearsWithAllMonths = array();
        }
        $achievementSQL .= " END)) as totalAchievements".PHP_EOL;

        $maleSQL = "SUM((CASE ".PHP_EOL;
        $yearsWithAllMonths = array();
        foreach($fromYearsMonths as $eachYear=>$yearMonths){
            if(count($yearMonths) == 12)
                $yearsWithAllMonths[] = $eachYear;
            else{
                $maleSQL .= " WHEN distribution._year = '$eachYear' THEN ";
                $thisColumns = array();
                foreach($yearMonths as $eachMonth){
                    $thisColumns[] = $MONTHS[$eachMonth].'_male';
                }
                $maleSQL .= " (".implode(' + ', $thisColumns).") ".PHP_EOL;
            }
        }
        if($yearsWithAllMonths){
            $maleSQL .= " WHEN distribution._year IN ('".implode("', '", $yearsWithAllMonths)."') THEN total_male ".PHP_EOL;
            $yearsWithAllMonths = array();
        }
        $maleSQL .= " END)) as totalMale".PHP_EOL;

        $femaleSQL = "SUM((CASE ".PHP_EOL;
        $yearsWithAllMonths = array();
        foreach($fromYearsMonths as $eachYear=>$yearMonths){
            if(count($yearMonths) == 12)
                $yearsWithAllMonths[] = $eachYear;
            else{
                $femaleSQL .= " WHEN distribution._year = '$eachYear' THEN ";
                $thisColumns = array();
                foreach($yearMonths as $eachMonth){
                    $thisColumns[] = $MONTHS[$eachMonth].'_female';
                }
                $femaleSQL .= " (".implode(' + ', $thisColumns).") ".PHP_EOL;
            }
        }
        if($yearsWithAllMonths){
            $femaleSQL .= " WHEN distribution._year IN ('".implode("', '", $yearsWithAllMonths)."') THEN total_female ".PHP_EOL;
            $yearsWithAllMonths = array();
        }
        $femaleSQL .= " END)) as totalFemale".PHP_EOL;

        $SQL = "
                SELECT 
                    distribution.fk_project_id, 
                    distribution.fk_parent_branch_id,
                    distribution.fk_branch_id,
                    distribution.fk_activity_id,
                    targets.`pk_mis_target_id` AS parent_target_id, 
                    targets._target AS parent_target, 
                    activity.fk_parent_id AS output_id,
                    activity.cat_name AS activity_name,
                    $targetSQL, 
                    $achievementSQL, 
                    $maleSQL, 
                    $femaleSQL,
                    branch.branch_name as ulo_name,
                    p_branch.branch_name AS drsc_name
                FROM mis_activity_distribution AS distribution
                    LEFT JOIN mis_activity_targets AS targets ON (targets.`fk_project_id`=distribution.`fk_project_id` AND targets.`fk_branch_id`=distribution.`fk_parent_branch_id` AND targets.`_year`=distribution.`_year` AND targets.`fk_activity_id`=distribution.`fk_activity_id`)
                    LEFT JOIN dev_acitivites_categories AS activity ON (activity.pk_activity_cat_id = distribution.fk_activity_id)
                    LEFT JOIN dev_branches AS branch ON (branch.pk_branch_id = distribution.fk_branch_id)
                    LEFT JOIN dev_branches AS p_branch ON (p_branch.pk_branch_id = distribution.fk_parent_branch_id)
                WHERE 1
                AND targets.`pk_mis_target_id` IS NOT NULL
        ";

        if($filterProject) $SQL .= " AND distribution.fk_project_id = '$filterProject'".PHP_EOL;
        if($filterDrsc) $SQL .= " AND distribution.fk_parent_branch_id = '$filterDrsc'".PHP_EOL;
        if($filterUlo) $SQL .= " AND distribution.fk_branch_id = '$filterUlo'".PHP_EOL;

        $SQL .= " GROUP BY distribution.`fk_activity_id`".PHP_EOL;

        $data = $devdb->get_results($SQL);
        if(!$data) $data = array();
		else{
			$branchSql = "
                SELECT 
                    distribution.fk_project_id, 
                    distribution.fk_parent_branch_id,
                    distribution.fk_branch_id,
                    branch.branch_name as ulo_name,
                    p_branch.branch_name AS drsc_name
                FROM mis_activity_distribution AS distribution
                    LEFT JOIN mis_activity_targets AS targets ON (targets.`fk_project_id`=distribution.`fk_project_id` AND targets.`fk_branch_id`=distribution.`fk_parent_branch_id` AND targets.`_year`=distribution.`_year` AND targets.`fk_activity_id`=distribution.`fk_activity_id`)
                    LEFT JOIN dev_acitivites_categories AS activity ON (activity.pk_activity_cat_id = distribution.fk_activity_id)
                    LEFT JOIN dev_branches AS branch ON (branch.pk_branch_id = distribution.fk_branch_id)
                    LEFT JOIN dev_branches AS p_branch ON (p_branch.pk_branch_id = distribution.fk_parent_branch_id)
                WHERE 1
                AND targets.`pk_mis_target_id` IS NOT NULL
        ";

        if($filterProject) $branchSql .= " AND distribution.fk_project_id = '$filterProject'".PHP_EOL;
        if($filterDrsc) $branchSql .= " AND distribution.fk_parent_branch_id = '$filterDrsc'".PHP_EOL;
        if($filterUlo) $branchSql .= " AND distribution.fk_branch_id = '$filterUlo'".PHP_EOL;

        

        $branchData = $devdb->get_results($branchSql);
		}

        $outputs = $this->get_acitivity_categories(array('deletion_status' => 'not_deleted', 'parent_only' => true));
        $outputs = $outputs['data'];

		if($data && $branchData){
			foreach($branchData as $eachData){
				$involvedDRSC[$eachData['fk_parent_branch_id']] = $eachData['drsc_name'];
				$involvedULO[$eachData['fk_branch_id']] = $eachData['ulo_name'];
			}	
		}
       
        $dateToDate = strtoupper($MONTHS[$fromMonth]).' '.$fromYear.' - '.strtoupper($MONTHS[$toMonth]).' '.$toYear;

        if($downloadReport){
            require_once(common_files('absolute').'/FPDF/class.dev_pdf.php');

            $devPdf = new DEV_PDF('P','mm','A4');
            $devPdf->init();
            $devPdf->createPdf();

            $devPdf->SetFont('helvetica','B',14);
            $devPdf->MultiCell(0,6,$theProject['project_name'],0,'L');
            $devPdf->Ln(0);

            $devPdf->SetFont('helvetica','',10);
            $devPdf->Cell(0, 6, 'District wise monthly information sheet', 0, 1, 'L');

            $devPdf->SetFont('helvetica','',10);
            $devPdf->Cell(0, 6, 'DRSC: '.implode(', ',$involvedDRSC), 0, 1, 'L');

            $devPdf->SetFont('helvetica','',10);
            $devPdf->Cell(0, 6, 'ULO: '.implode(', ',$involvedULO), 0, 1, 'L');

            $devPdf->SetFont('helvetica','',10);
            $devPdf->Cell(0, 6, 'From-To: '.$dateToDate, 0, 1, 'L');

            $devPdf->SetFont('helvetica','',10);
            $devPdf->Cell(0, 6, 'Report Date: '.date('Y-m-d'), 0, 1, 'L');

            $headers = array(
                array(
                    array('text' => '#', 'align' => 'L', 'width' => 8),
                    array('text' => 'Activities', 'align' => 'L', 'width' => 32),
                    array('text' => $dateToDate, 'merged' => 5,  'align' => 'C', 'width' => 12),
                ),
                array(
                    array('merged' => 2, 'text' => '', 'align' => 'R'),
                    array('text' => 'Target', 'align' => 'R', 'width' => 10),
                    array('text' => 'Achievement', 'align' => 'R', 'width' => 14),
                    array('text' => 'Variance', 'align' => 'R'),
                    array('text' => 'Male', 'align' => 'R'),
                    array('text' => 'Female', 'align' => 'R'),
                ),

            );

            $reportData = array();


            $outputCount = 0;
            foreach($outputs as $output){
                $outputID = $output['pk_activity_cat_id'];
                $outputName = $output['cat_name'];
                $outputCount++;

                $reportData[] = array(
                    array('text' => 'OP. '.$outputCount, 'align' => 'L', 'copy_section_style' => 'footer'),
                    array('text' => $outputName, 'align' => 'L', 'merged' => 6),
                );

                $serial = 1;
                foreach($data as $i=>$activity){
                    if($activity['output_id'] == $outputID){
                        $reportData[] = array(
                            array('text' => $outputCount.'.'.$serial++, 'align' => 'L'),
                            array('text' => $activity['activity_name'], 'align' => 'L'),
                            array('text' => $activity['totalTargets'], 'align' => 'R'),
                            array('text' => $activity['totalAchievements'], 'align' => 'R'),
                            array('text' => $activity['totalAchievements'] - $activity['totalTargets'], 'align' => 'R'),
                            array('text' => $activity['totalMale'], 'align' => 'R'),
                            array('text' => $activity['totalFemale'], 'align' => 'R'),
                        );
                        unset($data[$i]);
                    }
                }
            }

            $devPdf->resetOptions();
            $devPdf->setOption('dataFontSize', 8);
            $devPdf->setOption('headerFontSize', 8);
            $devPdf->setOption('headers', $headers);
            $devPdf->setOption('data', $reportData);
            $devPdf->addTable(1,1,1,1);

            $devPdf->SetTitle('MIS REPORT - '.date('Y-m-d-H-i-s'),true);

            $devPdf->outputPdf('D', 'MIS REPORT - '.date('Y-m-d-H-i-s').'.pdf');
            exit();
        }

    }
}


doAction('render_start');
?>
<div class="page-header">
    <h1>MIS Report</h1>
</div>
<div class="panel panel-info">
    <form name="set_targets" action="" method="get">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-2 form-group">
                    <label>Project</label>
                    <select class="form-control" required name="project">
                        <option value="">Select One</option>
                        <?php
                        foreach ($all_projects['data'] as $i => $v) {
                            $selected = $filterProject && $filterProject == $v['pk_project_id'] ? 'selected' : '';
                            ?>
                            <option <?php echo $selected; ?> value="<?php echo $v['pk_project_id'] ?>"><?php echo $v['project_short_name'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <?php if(!$ignoreDrscSelection): ?>
                    <div class="col-sm-2 form-group">
                        <label>DRSC</label>
                        <select class="form-control" name="drsc">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_drsc['data'] as $i => $v) {
                                $selected = $filterDrsc && $filterDrsc == $v['pk_branch_id'] ? 'selected' : '';
                                ?>
                                <option <?php echo $selected; ?> value="<?php echo $v['pk_branch_id'] ?>"><?php echo $v['branch_name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                <?php endif; ?>

                <?php if(!$ignoreUloSelection): ?>
                    <div class="col-sm-2 form-group">
                        <label>ULO</label>
                        <select class="form-control" name="ulo">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_ulo['data'] as $i => $v) {
                                $selected = $filterUlo && $filterUlo == $v['pk_branch_id'] ? 'selected' : '';
                                ?>
                                <option <?php echo $selected; ?> value="<?php echo $v['pk_branch_id'] ?>"><?php echo $v['branch_name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="col-sm-2 form-group">
                    <label>From Month, Year</label>
                    <div class="input-group">
                        <input id="from_month_year" type="text" class="form-control" name="from_month_year" value="<?php echo $_GET['from_month_year'] ? $_GET['from_month_year'] : date('Y-m'); ?>"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                    <script type="text/javascript">
                        init.push(function(){
                            _month_year_picker('from_month_year');
                        });
                    </script>
                </div>
                <div class="col-sm-2 form-group">
                    <label>To Month, Year</label>
                    <div class="input-group">
                        <input id="to_month_year" type="text" class="form-control" name="to_month_year" value="<?php echo $_GET['to_month_year'] ? $_GET['to_month_year'] : date('Y-m'); ?>"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                    <script type="text/javascript">
                        init.push(function(){
                            _month_year_picker('to_month_year');
                        });
                    </script>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" name="download_report" class="submit_in_new_tab btn btn-flat btn-labeled btn-success" value="1"><i class="fa fa-download btn-label"></i>Download Report</button>
            <button type="submit" name="generate_report" class="submit_in_current_tab btn btn-flat btn-labeled btn-primary " value="1"><i class="fa fa-cog btn-label"></i>View Report</button>
        </div>
    </form>
</div>
<?php if($generateReport): ?>
    <div class="table-primary table-responsive">
        <div class="table-header">
            <?php
            echo $theProject['project_name']."<br />";
            echo 'District wise monthly information sheet'.'<br />';
            echo 'DRSC: '.implode(', ',$involvedDRSC).'<br />';
            echo 'ULO: '.implode(', ',$involvedULO).'<br />';
            echo 'From-To: '.$dateToDate.'<br />';
            echo 'Report Date: '.date('Y-m-d');
            ?>
        </div>
        <table class="table table-bordered table-condensed table-hover table-striped">
            <thead>
            <tr>
                <th style="width: 60px">#</th>
                <th>Activities</th>
                <th style="width: 50%" class="text-center" colspan="6"><?php echo $dateToDate; ?></th>
            </tr>
            <tr>
                <th colspan="2"></th>
                <th class="text-right">Target</th>
                <th class="text-right">Achievement</th>
                <th class="text-right">Variance</th>
                <th class="text-right">Male</th>
                <th class="text-right">Female</th>
                <th class="text-right">Total (M+F)</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $outputCount = 0;
            foreach($outputs as $output){
                $outputID = $output['pk_activity_cat_id'];
                $outputName = $output['cat_name'];
                $outputCount++;
                ?>
                <tr>
                    <td class="text-bold"><?php echo 'OP. '.$outputCount; ?></td>
                    <td class="text-bold" colspan="7"><?php echo $outputName; ?></td>
                </tr>
                <?php

                $serial = 1;
                foreach($data as $i=>$activity){
                    if($activity['output_id'] == $outputID){
                        ?>
                        <tr>
                            <td><?php echo $outputCount.'.'.$serial++; ?></td>
                            <td><?php echo $activity['activity_name']; ?></td>
                            <td class="text-right"><?php echo $activity['totalTargets']; ?></td>
                            <td class="text-right"><?php echo $activity['totalAchievements']; ?></td>
                            <td class="text-right"><?php echo $activity['totalAchievements'] - $activity['totalTargets']; ?></td>
                            <td class="text-right"><?php echo $activity['totalMale']; ?></td>
                            <td class="text-right"><?php echo $activity['totalFemale']; ?></td>
                            <td class="text-right"><?php echo $activity['totalFemale'] + $activity['totalFemale'] ?></td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>