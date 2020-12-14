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
        'col' => 'pk_event_id',
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

$events = $this->get_events($args);
$pagination = pagination($events['total'], $per_page_items, $start);

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
            'participant_boy' => 'dev_events.participant_boy',
            'participant_girl' => 'dev_events.participant_girl',
            'participant_male' => 'dev_events.participant_male',
            'participant_female' => 'dev_events.participant_female',
            'create_date' => 'dev_events.create_date',
            'created_by' => 'dev_events.created_by',
        ),
    );
    unset($args['limit']);
    $data = $this->get_events($args);
    $data = $data['data'];
    
    // This will be here in our project

    $writer =WriterEntityFactory::createXLSXWriter();
    $style = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(12)
           //->setShouldWrapText()
           ->build();

    $fileName = 'event-management-' . time() . '.xlsx';
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
    $report_head = ['Event Management Report '];
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
                'Event Name', 
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
                'Submitted By', 
                'Participant Number', 
                'Event Validation Count',
                'Observation Score', 
                'Preparatory Work', 
                'Time management of the event was', 
                'Participants attention', 
                'Logistical arrangements', 
                'Relevancy of delivery of messages', 
                'Participants Feedback', 
                'Event Note', 

    ];

    $rowFromVal = WriterEntityFactory::createRowFromArray($header,$style);
    $writer->addRow($rowFromVal);
    $multipleRows = array();

    if ($data) {
        $count = 0;
        foreach ($data as $i => $event) {
            $nid_number = $case_info['nid_number'] ? $case_info['nid_number'] : 'N/A';
            $birth_reg_number = $case_info['birth_reg_number'] ? $case_info['birth_reg_number'] : 'N/A';
            $support_date = $case_info['entry_date'] ? date('d-m-Y', strtotime($case_info['entry_date'])) : 'N/A';
            $itme_aanagement = null;
            if($event['time_management']==1):
                $itme_aanagement = 'Not Observed';
            elseif($event['time_management']==2):
                $itme_aanagement = 'Need To Improved';
            elseif($event['time_management']==3):
                $itme_aanagement = 'Neutral';
            elseif($event['time_management']==4):
                $itme_aanagement = 'Good';
            elseif($event['time_management']==5):
                $itme_aanagement = 'Excellent';
            endif;

            $participants_attention = null;
            if($event['participants_attention']==1):
                $participants_attention = 'Not Observed';
            elseif($event['participants_attention']==2):
                $participants_attention = 'Need To Improved';
            elseif($event['participants_attention']==3):
                $participants_attention = 'Neutral';
            elseif($event['participants_attention']==4):
                $participants_attention = 'Good';
            elseif($event['participants_attention']==5):
                $participants_attention = 'Excellent';
            endif;

            $logistical_arrangements = null;
            if($event['logistical_arrangements']==1):
                $logistical_arrangements = 'Not Observed';
            elseif($event['logistical_arrangements']==2):
                $logistical_arrangements = 'Need To Improved';
            elseif($event['logistical_arrangements']==3):
                $logistical_arrangements = 'Neutral';
            elseif($event['logistical_arrangements']==4):
                $logistical_arrangements = 'Good';
            elseif($event['participants_attention']==5):
                $logistical_arrangements = 'Excellent';
            endif;

            $relevancy_delivery = null;
            if($event['relevancy_delivery']==1):
                $relevancy_delivery = 'Not Observed';
            elseif($event['relevancy_delivery']==2):
                $relevancy_delivery = 'Need To Improved';
            elseif($event['relevancy_delivery']==3):
                $relevancy_delivery = 'Neutral';
            elseif($event['relevancy_delivery']==4):
                $relevancy_delivery = 'Good';
            elseif($event['relevancy_delivery']==5):
                $relevancy_delivery = 'Excellent';
            endif;

            $participants_feedback = null;
            if($event['participants_feedback']==1):
                $participants_feedback = 'Not Observed';
            elseif($event['participants_feedback']==2):
                $participants_feedback = 'Need To Improved';
            elseif($event['participants_feedback']==3):
                $participants_feedback = 'Neutral';
            elseif($event['participants_feedback']==4):
                $participants_feedback = 'Good';
            elseif($event['participants_feedback']==5):
                $participants_feedback = 'Excellent';
            endif;
            

            $cells = [
                WriterEntityFactory::createCell(++$count),
                WriterEntityFactory::createCell($event['activity_name']),
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

                WriterEntityFactory::createCell($event['user_fullname']),
                WriterEntityFactory::createCell('Boy: ' . $event['participant_boy'] . '; Girl: ' . $event['participant_girl'] . '; Men: ' . $event['participant_male'] . '; Women: ' . $event['participant_female']),
                WriterEntityFactory::createCell($event['validation_count']),
                WriterEntityFactory::createCell($event['observation_score']),
                WriterEntityFactory::createCell($event['preparatory_work']),
                WriterEntityFactory::createCell($itme_aanagement),
                WriterEntityFactory::createCell($participants_attention),
                WriterEntityFactory::createCell($logistical_arrangements),
                WriterEntityFactory::createCell($relevancy_delivery),
                WriterEntityFactory::createCell($participants_feedback),
                WriterEntityFactory::createCell($event_note),
            ];

            $multipleRows[] = WriterEntityFactory::createRow($cells);

        }
    }
    $writer->addRows($multipleRows); 

    $currentSheet = $writer->getCurrentSheet();
    $mergeRanges = ['A1:X1','A2:X2','A3:X3']; // you can list the cells you want to merge like this ['A1:A4','A1:E1']
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
            <h1>All Events</h1>
            <div class="oh">
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => $myUrl . '?action=add_edit_event',
                        'action' => 'add',
                        'icon' => 'icon_add',
                        'text' => 'Add Event',
                        'title' => 'Add Event',
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
                        'text' => 'Download Case',
                        'title' => 'Download Case',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-row">
                <div class="stat-cell bg-warning">
                    <span class="text-bg"><?php echo $events['total'] ?></span><br>
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
    'width' => 3, 'type' => 'text', 'label' => 'Event Name',
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
        <?php echo searchResultText($events['total'], $start, $per_page_items, count($events['data']), 'activities') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>District</th>
                <th>Upazila</th>
                <th>Submitted By</th>
                <th>Participant Number</th>
                <th>Event Validation Count</th>
                <th>Observation Score</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($events['data'] as $i => $event) {
                ?>
                <tr>
                    <td><?php echo $event['activity_name'] ?></td>
                    <td><?php echo date('d-m-Y', strtotime($event['event_start_date'])) ?></td>
                    <td><?php echo date('d-m-Y', strtotime($event['event_end_date'])) ?></td>
                    <td style="text-transform: capitalize"><?php echo $event['event_district']; ?></td>
                    <td style="text-transform: capitalize"><?php echo $event['event_upazila']; ?></td>
                    <td><?php echo $event['user_fullname']; ?></td>
                    <td><?php echo 'Boy: ' . $event['participant_boy'] . '<br><hr/>Girl: ' . $event['participant_girl'] . '<br><hr/>Men: ' . $event['participant_male'] . '<br><hr/>Women: ' . $event['participant_female'] ?></td>
                    <td><?php echo $event['validation_count']; ?></td>
                    <td><?php echo $event['observation_score']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_event')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => url('admin/dev_event_management/manage_event_validations&event_id=' . $event['pk_event_id']),
                                    'action' => 'edit',
                                    'icon' => 'icon_add',
                                    'text' => 'Event Validation',
                                    'title' => 'Event Validation',
                                ));
                                ?>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_event', 'edit' => $event['pk_event_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Event',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_event')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $event['pk_event_id']),
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
                        window.location.href = '?action=deleteEvent&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>