<?php
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\CellAlignment;

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_branch_id = $_GET['branch_id'] ? $_GET['branch_id'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_union = $_GET['union'] ? $_GET['union'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;

$args = array(
    'select_fields' => array(
        'activity_name' => 'dev_activities.activity_name',
        'preparatory_work' => 'dev_events.preparatory_work',
        'time_management' => 'dev_events.time_management',
        'participants_attention' => 'dev_events.participants_attention',
        'logistical_arrangements' => 'dev_events.logistical_arrangements',
        'relevancy_delivery' => 'dev_events.relevancy_delivery',
        'participants_feedback' => 'dev_events.participants_feedback',
        'event_note' => 'dev_events.event_note',
        'observation_score' => 'dev_events.observation_score',
    ),
    'name' => $filter_name,
    'branch_id' => $filter_branch_id,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'union' => $filter_union,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_event_id',
        'order' => 'DESC'
    ),
);

if ($filter_entry_start_date && $filter_entry_start_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'create_date' => array(
            'left' => date_to_db($filter_entry_start_date),
            'right' => date_to_db($filter_entry_end_date),
        ),
    );
}

$eventManagement = jack_obj('dev_event_management');
$events = $eventManagement->get_events($args);

$pagination = pagination($events['total'], $per_page_items, $start);

$branchManagement = jack_obj('dev_branch_management');
$all_branches = $branchManagement->get_branches();

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
} else if (isset($_POST['subdistrict_id'])) {
    $unions = get_union($_POST['subdistrict_id']);
    echo "<option value=''>Select One</option>";
    foreach ($unions as $union) :
        echo "<option id='" . $union['id'] . "' value='" . strtolower($union['name']) . "'>" . $union['name'] . "</option>";
    endforeach;
    exit;
}

$filterString = array();
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_branch_id)
    $filterString[] = 'Branch: ' . $filter_branch_id;
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_sub_district)
    $filterString[] = 'Upazila: ' . $filter_sub_district;
if ($filter_union)
    $filterString[] = 'Union: ' . $filter_union;
if ($filter_entry_start_date)
    $filterString[] = 'Start Date: ' . $filter_entry_start_date;
if ($filter_entry_end_date)
    $filterString[] = 'End Date: ' . $filter_entry_end_date;

if ($_GET['download_excel']) {
    $args = array(
        'select_fields' => array(
            'fk_branch_id' => 'dev_events.fk_branch_id',
            'fk_project_id' => 'dev_events.fk_project_id',
            'month' => 'dev_events.month',
            'fk_activity_id' => 'dev_events.fk_activity_id',
            'event_start_date' => 'dev_events.event_start_date',
            'event_start_time' => 'dev_events.event_start_time',
            'event_end_date' => 'dev_events.event_end_date',
            'event_end_time' => 'dev_events.event_end_time',
            'event_division' => 'dev_events.event_division',
            'event_district' => 'dev_events.event_district',
            'event_upazila' => 'dev_events.event_upazila',
            'event_union' => 'dev_events.event_union',
            'event_village' => 'dev_events.event_village',
            'event_ward' => 'dev_events.event_ward',
            'event_location' => 'dev_events.event_location',
            'preparatory_work' => 'dev_events.preparatory_work',
            'time_management' => 'dev_events.time_management',
            'participants_attention' => 'dev_events.participants_attention',
            'logistical_arrangements' => 'dev_events.logistical_arrangements',
            'relevancy_delivery' => 'dev_events.relevancy_delivery',
            'participants_feedback' => 'dev_events.participants_feedback',
            'event_note' => 'dev_events.event_note',
            'create_date' => 'dev_events.create_date',
            'created_by' => 'dev_events.created_by',
        ),
    );
    unset($args['limit']);
    $data = $eventManagement->get_events($args);
    $data = $data['data'];
    // This will be here in our project

    $writer = WriterEntityFactory::createXLSXWriter();
    $style = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            //->setShouldWrapText()
            ->build();

    $fileName = 'observation-report-' . time() . '.xlsx';
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
    $report_head = ['Observation Report'];
    $singleRow = WriterEntityFactory::createRowFromArray($report_head, $style2);
    $writer->addRow($singleRow);

    $report_date = ['Date: ' . Date('d-m-Y H:i')];
    $reportDateRow = WriterEntityFactory::createRowFromArray($report_date);
    $writer->addRow($reportDateRow);

    $filtered_with = ['Activity Name = '.$filter_name.', Division = ' . $filter_division . ', District = ' . $filter_district . ', Sub-District = ' . $filter_sub_district . ', Union = '.$filter_union. ', Police Station = ' . $filter_ps. ', Start Date = ' . $filter_entry_start_date. ', End Date = ' . $filter_entry_end_date];
    $rowFromVal = WriterEntityFactory::createRowFromArray($filtered_with);
    $writer->addRow($rowFromVal);

    $empty_row = [''];
    $rowFromVal = WriterEntityFactory::createRowFromArray($empty_row);
    $writer->addRow($rowFromVal);

    $header = [
        "SL",
        'Branch Name',
        'Project Name',
        'Activity Name',
        'Month', 
        'Start Date',
        'Start Time',
        "End Date",
        "End Time",
        "Division",
        "District",
        'Upazila',
        'Union',
        'Event Location',
        'Event Village',
        'Event Ward',
        'Preparatory Work',
        'Time management of the event was',
        'Participants attention',
        'Logistical arrangements',
        'Relevancy of delivery of messages',
        'Participants Feedback',
        'Event Note',
        'Submitted By',
        'Submitted Date',
    ];

    $rowFromVal = WriterEntityFactory::createRowFromArray($header, $style);
    $writer->addRow($rowFromVal);
    $multipleRows = array();

    if ($data) {
        $count = 0;
        foreach ($data as $i => $event) {
            $nid_number = $case_info['nid_number'] ? $case_info['nid_number'] : 'N/A';
            $birth_reg_number = $case_info['birth_reg_number'] ? $case_info['birth_reg_number'] : 'N/A';
            $support_date = $case_info['entry_date'] ? date('d-m-Y', strtotime($case_info['entry_date'])) : 'N/A';
            $itme_management = null;
            if ($event['time_management'] == 1):
                $itme_management = 'Not Observed';
            elseif ($event['time_management'] == 2):
                $itme_management = 'Need To Improved';
            elseif ($event['time_management'] == 3):
                $itme_management = 'Neutral';
            elseif ($event['time_management'] == 4):
                $itme_management = 'Good';
            elseif ($event['time_management'] == 5):
                $itme_management = 'Excellent';
            endif;

            $participants_attention = null;
            if ($event['participants_attention'] == 1):
                $participants_attention = 'Not Observed';
            elseif ($event['participants_attention'] == 2):
                $participants_attention = 'Need To Improved';
            elseif ($event['participants_attention'] == 3):
                $participants_attention = 'Neutral';
            elseif ($event['participants_attention'] == 4):
                $participants_attention = 'Good';
            elseif ($event['participants_attention'] == 5):
                $participants_attention = 'Excellent';
            endif;

            $logistical_arrangements = null;
            if ($event['logistical_arrangements'] == 1):
                $logistical_arrangements = 'Not Observed';
            elseif ($event['logistical_arrangements'] == 2):
                $logistical_arrangements = 'Need To Improved';
            elseif ($event['logistical_arrangements'] == 3):
                $logistical_arrangements = 'Neutral';
            elseif ($event['logistical_arrangements'] == 4):
                $logistical_arrangements = 'Good';
            elseif ($event['participants_attention'] == 5):
                $logistical_arrangements = 'Excellent';
            endif;

            $relevancy_delivery = null;
            if ($event['relevancy_delivery'] == 1):
                $relevancy_delivery = 'Not Observed';
            elseif ($event['relevancy_delivery'] == 2):
                $relevancy_delivery = 'Need To Improved';
            elseif ($event['relevancy_delivery'] == 3):
                $relevancy_delivery = 'Neutral';
            elseif ($event['relevancy_delivery'] == 4):
                $relevancy_delivery = 'Good';
            elseif ($event['relevancy_delivery'] == 5):
                $relevancy_delivery = 'Excellent';
            endif;

            $participants_feedback = null;
            if ($event['participants_feedback'] == 1):
                $participants_feedback = 'Not Observed';
            elseif ($event['participants_feedback'] == 2):
                $participants_feedback = 'Need To Improved';
            elseif ($event['participants_feedback'] == 3):
                $participants_feedback = 'Neutral';
            elseif ($event['participants_feedback'] == 4):
                $participants_feedback = 'Good';
            elseif ($event['participants_feedback'] == 5):
                $participants_feedback = 'Excellent';
            endif;


            $cells = [
                WriterEntityFactory::createCell(++$count),
                WriterEntityFactory::createCell($event['fk_branch_id']),
                WriterEntityFactory::createCell($event['fk_project_id']),
                WriterEntityFactory::createCell($event['fk_activity_id']),
                WriterEntityFactory::createCell($event['month']),
                WriterEntityFactory::createCell(date('d-m-Y', strtotime($event['event_start_date']))),
                WriterEntityFactory::createCell(date('H:i', strtotime($event['event_start_time']))),
                WriterEntityFactory::createCell(date('d-m-Y', strtotime($event['event_end_date']))),
                WriterEntityFactory::createCell(date('H:i', strtotime($event['event_end_time']))),
                WriterEntityFactory::createCell($event['event_division']),
                WriterEntityFactory::createCell($event['event_district']),
                WriterEntityFactory::createCell($event['event_upazila']),
                WriterEntityFactory::createCell($event['event_union']),
                WriterEntityFactory::createCell($event['event_location']),
                WriterEntityFactory::createCell($event['event_village']),
                WriterEntityFactory::createCell($event['event_ward']),
                WriterEntityFactory::createCell($event['preparatory_work']),
                WriterEntityFactory::createCell($itme_management),
                WriterEntityFactory::createCell($participants_attention),
                WriterEntityFactory::createCell($logistical_arrangements),
                WriterEntityFactory::createCell($relevancy_delivery),
                WriterEntityFactory::createCell($participants_feedback),
                WriterEntityFactory::createCell($event_note),
                WriterEntityFactory::createCell($event['created_by']),
                WriterEntityFactory::createCell(date('d-m-Y', strtotime($event['create_date']))),
            ];

            $multipleRows[] = WriterEntityFactory::createRow($cells);
        }
    }
    $writer->addRows($multipleRows);

    $currentSheet = $writer->getCurrentSheet();
    $mergeRanges = ['A1:X1', 'A2:X2', 'A3:X3']; // you can list the cells you want to merge like this ['A1:A4','A1:E1']
    $currentSheet->setMergeRanges($mergeRanges);

    $writer->close();
    exit;
    // End this is to our project
}

doAction('render_start');
?>
<div class="page-header">
    <div class="row">
        <div class="col-md-8">
            <h1>All Observation Report</h1>
            <div class="oh">
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => '?download_excel=1&name=' . $filter_name . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&union=' . $filter_union . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
                        'attributes' => array('target' => '_blank'),
                        'action' => 'download',
                        'icon' => 'icon_download',
                        'text' => 'Download Observation Report',
                        'title' => 'Download Observation Report',
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
ob_start();
echo formProcessor::form_elements('name', 'name', array(
    'width' => 3, 'type' => 'text', 'label' => 'Activity Name',
        ), $filter_name);
?>
<div class="form-group col-sm-3">
    <label>Branch</label>
    <div class="select2-primary">
        <select class="form-control" name="branch_id">
            <option value="">Select One</option>
<?php foreach ($all_branches['data'] as $branch) : ?>
                <option value="<?php echo $branch['pk_branch_id'] ?>" <?php if ($branch['pk_branch_id'] == $filter_branch_id) echo 'selected' ?> ><?php echo $branch['branch_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
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
<div class="form-group col-sm-3">
    <label>District</label>
    <div class="select2-primary">
        <select class="form-control district" name="district" id="districtList" style="text-transform: capitalize">
<?php if ($filter_district) : ?>
                <option value="<?php echo $filter_district ?>"><?php echo $filter_district ?></option>
            <?php endif ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-3">
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
    <label>Union</label>
    <div class="select2-primary">
        <select class="form-control union" name="union" id="unionList" style="text-transform: capitalize">
<?php if ($filter_union) : ?>
                <option value="<?php echo $filter_union ?>"><?php echo $filter_union ?></option>
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
    <?php echo searchResultText($events['total'], $start, $per_page_items, count($events['data']), 'Events') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Preparatory Work</th>
                <th>Time Management</th>
                <th>Participants Attention</th>
                <th>Logistical Arrangements</th>
                <th>Relevancy Delivery Messages</th>
                <th>Feedback</th>
                <th>Total Score</th>
            </tr>
        </thead>
        <tbody>
<?php
foreach ($events['data'] as $i => $event) {
    ?>
                <tr>
                    <td><?php echo $event['activity_name'] ?></td>
                    <td><?php echo $event['preparatory_work'] ?></td>
                    <td><?php echo $event['time_management'] ?></td>
                    <td><?php echo $event['participants_attention'] ?></td>
                    <td><?php echo $event['logistical_arrangements'] ?></td>
                    <td><?php echo $event['relevancy_delivery'] ?></td>
                    <td><?php echo $event['participants_feedback'] ?></td>
                    <td><?php echo $event['observation_score']; ?></td>
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
        $('.subdistrict').change(function () {
            var subdistrictId = $(this).find('option:selected').attr('id');
            $.ajax({
                type: 'POST',
                data: {subdistrict_id: subdistrictId},
                success: function (result) {
                    $('#unionList').html(result);
                }}
            );
        });
    });
</script>
<script type="text/javascript">
    init.push(function () {
        $(document).on('click', '.delete_single_record', function () {
            var ths = $(this);
            var thisCell = ths.closest('td');
            var logId = ths.attr('data-id');
            if (!logId)
                return false;

            show_button_overlay_working(thisCell);
            bootbox.prompt({
                title: 'Delete Record!',
                inputType: 'checkbox',
                inputOptions: [{
                        text: 'Click To Confirm Delete',
                        value: 'delete'
                    }],
                callback: function (result) {
                    if (result == 'delete') {
                        window.location.href = '?action=deleteEvent&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>