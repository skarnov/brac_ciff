<?php
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\CellAlignment;

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;

$args = array(
    'division' => $filter_division,
    'district' => $filter_district,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_meeting_entry_id',
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

$meeting_entries = $this->get_meeting_entries($args);
$pagination = pagination($meeting_entries['total'], $per_page_items, $start);

$filterString = array();
if ($filter_division)
    $filterString[] = 'Division: ' . $filter_division;
if ($filter_district)
    $filterString[] = 'District: ' . $filter_district;
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_entry_start_date)
    $filterString[] = 'Start Date: ' . $filter_entry_start_date;
if ($filter_entry_end_date)
    $filterString[] = 'End Date: ' . $filter_entry_end_date;

if ($_GET['download_excel']) {
    $args = array(
        'select_fields' => array(
            'fk_branch_id' => 'dev_meeting_entries.fk_branch_id',
            'fk_project_id' => 'dev_meeting_entries.fk_project_id',
            'month' => 'dev_meeting_entries.month',
            'fk_meeting_id' => 'dev_meeting_entries.fk_meeting_id',
            'meeting_entries_start_date' => 'dev_meeting_entries.meeting_entries_start_date',
            'meeting_entries_start_time' => 'dev_meeting_entries.meeting_entries_start_time',
            'meeting_entries_end_date' => 'dev_meeting_entries.meeting_entries_end_date',
            'meeting_entries_end_time' => 'dev_meeting_entries.meeting_entries_end_time',
            'meeting_entries_division' => 'dev_meeting_entries.meeting_entries_division',
            'meeting_entries_district' => 'dev_meeting_entries.meeting_entries_district',
            'meeting_entries_upazila' => 'dev_meeting_entries.meeting_entries_upazila',
            'meeting_entries_union' => 'dev_meeting_entries.meeting_entries_union',
            'meeting_entries_village' => 'dev_meeting_entries.meeting_entries_village',
            'meeting_entries_ward' => 'dev_meeting_entries.meeting_entries_ward',
            'meeting_entries_location' => 'dev_meeting_entries.meeting_entries_location',
            'participant_boy' => 'dev_meeting_entries.participant_boy',
            'participant_girl' => 'dev_meeting_entries.participant_girl',
            'participant_male' => 'dev_meeting_entries.participant_male',
            'participant_female' => 'dev_meeting_entries.participant_female',
            'create_date' => 'dev_meeting_entries.create_date',
            'created_by' => 'dev_meeting_entries.created_by',
        ),
    );
    unset($args['limit']);
    $data = $this->get_meeting_entries($args);
    $data = $data['data'];
    
    // This will be here in our project

    $writer =WriterEntityFactory::createXLSXWriter();
    $style = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(12)
           //->setShouldWrapText()
           ->build();

    $fileName = 'meeting_entries-management-' . time() . '.xlsx';
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
    $report_head = ['Meeting Management Report '];
    $singleRow = WriterEntityFactory::createRowFromArray($report_head,$style2);
    $writer->addRow($singleRow);

    $report_date = ['Date: '.Date('d-m-Y H:i')];
    $reportDateRow = WriterEntityFactory::createRowFromArray($report_date);
    $writer->addRow($reportDateRow);

    $filtered_with = ['Division = ' . $filter_division . ', District = ' . $filter_district];
    $rowFromVal = WriterEntityFactory::createRowFromArray($filtered_with);
    $writer->addRow($rowFromVal);

    $empty_row = [''];
    $rowFromVal = WriterEntityFactory::createRowFromArray($empty_row);
    $writer->addRow($rowFromVal);

    $header = [
                "SL",
                "Branch Name",
                "Project Name",
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
                'Meeting Location', 
                'Meeting Village',
                'Meeting Ward',
                'Participant Number',
                'Submitted by',
                'Submitted Date',

    ];

    $rowFromVal = WriterEntityFactory::createRowFromArray($header,$style);
    $writer->addRow($rowFromVal);
    $multipleRows = array();

    if ($data) {
        $count = 0;
        foreach ($data as $i => $meeting_entries) {
            $nid_number = $case_info['nid_number'] ? $case_info['nid_number'] : 'N/A';
            $birth_reg_number = $case_info['birth_reg_number'] ? $case_info['birth_reg_number'] : 'N/A';
            $support_date = $case_info['entry_date'] ? date('d-m-Y', strtotime($case_info['entry_date'])) : 'N/A';
            $itme_aanagement = null;

            $cells = [
                WriterEntityFactory::createCell(++$count),
                WriterEntityFactory::createCell($meeting_entries['fk_branch_id']),
                WriterEntityFactory::createCell($meeting_entries['fk_project_id']),
                WriterEntityFactory::createCell($meeting_entries['fk_meeting_id']),
                WriterEntityFactory::createCell($meeting_entries['month']),
                WriterEntityFactory::createCell(date('d-m-Y', strtotime($meeting_entries['meeting_entries_start_date']))),
                WriterEntityFactory::createCell(date('H:i', strtotime($meeting_entries['meeting_entries_start_time']))),
                WriterEntityFactory::createCell(date('d-m-Y', strtotime($meeting_entries['meeting_entries_end_date']))),
                WriterEntityFactory::createCell(date('H:i', strtotime($meeting_entries['meeting_entries_end_time']))),
                WriterEntityFactory::createCell($meeting_entries['meeting_entries_division']),
                WriterEntityFactory::createCell($meeting_entries['meeting_entries_district']),
                WriterEntityFactory::createCell($meeting_entries['meeting_entries_upazila']),
                WriterEntityFactory::createCell($meeting_entries['meeting_entries_union']),
                WriterEntityFactory::createCell($meeting_entries['meeting_entries_location']),
                WriterEntityFactory::createCell($meeting_entries['meeting_entries_village']),
                WriterEntityFactory::createCell($meeting_entries['meeting_entries_ward']),

                WriterEntityFactory::createCell('Boy: ' . $meeting_entries['participant_boy'] . '; Girl: ' . $meeting_entries['participant_girl'] . '; Men: ' . $meeting_entries['participant_male'] . '; Women: ' . $meeting_entries['participant_female']),
                WriterEntityFactory::createCell($meeting_entries['created_by']),
                WriterEntityFactory::createCell(date('d-m-Y', strtotime($meeting_entries['create_date']))),
            ];

            $multipleRows[] = WriterEntityFactory::createRow($cells);

        }
    }
    $writer->addRows($multipleRows); 

    $currentSheet = $writer->getCurrentSheet();
    $mergeRanges = ['A1:S1','A2:S2','A3:S3']; // you can list the cells you want to merge like this ['A1:A4','A1:E1']
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
            <h1>All Meeting Entries</h1>
            <div class="oh">
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => $myUrl . '?action=add_edit_meeting_entry',
                        'action' => 'add',
                        'icon' => 'icon_add',
                        'text' => 'Add Meeting Entry',
                        'title' => 'Add Meeting Entry',
                    ));
                    ?>
                </div>

                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => '?download_excel=1&division=' . $filter_division . '&district=' . $filter_district,
                        'attributes' => array('target' => '_blank'),
                        'action' => 'download',
                        'icon' => 'icon_download',
                        'text' => 'Download Meeting Entries',
                        'title' => 'Download Meeting Entries',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-row">
                <div class="stat-cell bg-warning">
                    <span class="text-bg"><?php echo $meeting_entries['total'] ?></span><br>
                    <span class="text-sm">Stored in Database</span>
                </div>
            </div>
            <div class="stat-row">
                <div class="stat-cell bg-warning padding-sm no-padding-t text-center">
                    <div id="stats-sparklines-2" class="stats-sparklines" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
ob_start();
echo formProcessor::form_elements('name', 'name', array(
    'width' => 3, 'type' => 'text', 'label' => 'Meeting Name',
        ), $filter_name);
?>
<div class="form-group col-sm-3">
    <label>Branch</label>
    <div class="select2-primary">
        <select class="form-control" name="branch_id"></select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Division</label>
    <div class="select2-primary">
        <select class="form-control" id="filter_division" name="division" data-selected="<?php echo $filter_division ?>"></select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>District</label>
    <div class="select2-success">
        <select class="form-control" id="filter_district" name="district" data-selected="<?php echo $filter_district; ?>"></select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Upazila</label>
    <div class="select2-success">
        <select class="form-control" id="filter_district" name="district" data-selected="<?php echo $filter_district; ?>"></select>
    </div>
</div>
<div class="form-group col-sm-3">
    <label>Union</label>
    <div class="select2-success">
        <select class="form-control" id="filter_union" name="union" data-selected="<?php echo $filter_union; ?>"></select>
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
        <?php echo searchResultText($meeting_entries['total'], $start, $per_page_items, count($meeting_entries['data']), 'meeting') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Meeting Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>District</th>
                <th>Upazila</th>
                <th>Submitted By</th>
                <th>Participant Number</th>
                <th>Observation Score</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($meeting_entries['data'] as $i => $meeting_entries) {
                ?>
                <tr>
                    <td><?php echo $meeting_entries['meeting_name'] ?></td>
                    <td><?php echo date('d-m-Y', strtotime($meeting_entries['meeting_entry_start_date'])) ?></td>
                    <td><?php echo date('d-m-Y', strtotime($meeting_entries['meeting_entry_end_date'])) ?></td>
                    <td style="text-transform: capitalize"><?php echo $meeting_entries['meeting_entry_district']; ?></td>
                    <td style="text-transform: capitalize"><?php echo $meeting_entries['meeting_entry_upazila']; ?></td>
                    <td><?php echo $meeting_entries['user_fullname']; ?></td>
                    <td><?php echo 'Boy: ' . $meeting_entries['participant_boy'] . '<br><hr/>Girl: ' . $meeting_entries['participant_girl'] . '<br><hr/>Men: ' . $meeting_entries['participant_male'] . '<br><hr/>Women: ' . $meeting_entries['participant_female'] ?></td>
                    <td><?php echo $meeting_entries['observation_score']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_meeting_entries')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_meeting_entry', 'edit' => $meeting_entries['pk_meeting_entry_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Meeting',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_meeting_entry')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $meeting_entries['pk_meeting_entry_id']),
                                    'classes' => 'delete_single_record'));
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
    var BD_LOCATIONS = <?php echo getBDLocationJson(); ?>;
    init.push(function () {
        new bd_new_location_selector({
            'division': $('#filter_division'),
            'district': $('#filter_district'),
            'sub_district': $('#filter_sub_district'),
            'police_station': $('#filter_police_station'),
            'post_office': $('#filter_post_office'),
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
                        window.location.href = '?action=deleteMeeting&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>