<?php
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\CellAlignment;

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_returnee_id = $_GET['returnee_id'] ? $_GET['returnee_id'] : null;
$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_nid = $_GET['nid'] ? $_GET['nid'] : null;
$filter_passport = $_GET['passport'] ? $_GET['passport'] : null;
$filter_division = $_GET['division'] ? $_GET['division'] : null;
$filter_district = $_GET['district'] ? $_GET['district'] : null;
$filter_sub_district = $_GET['sub_district'] ? $_GET['sub_district'] : null;
$filter_union = $_GET['union'] ? $_GET['union'] : null;
$filter_entry_start_date = $_GET['entry_start_date'] ? $_GET['entry_start_date'] : null;
$filter_entry_end_date = $_GET['entry_end_date'] ? $_GET['entry_end_date'] : null;
$branch_id = $_config['user']['user_branch'] ? $_config['user']['user_branch'] : null;

$args = array(
    'listing' => TRUE,
    'select_fields' => array(
        'pk_returnee_id' => 'dev_returnees.pk_returnee_id',
        'returnee_id' => 'dev_returnees.returnee_id',
        'full_name' => 'dev_returnees.full_name',
        'mobile_number' => 'dev_returnees.mobile_number',
        'passport_number' => 'dev_returnees.passport_number',
        'permanent_division' => 'dev_returnees.permanent_division',
        'permanent_district' => 'dev_returnees.permanent_district',
        'permanent_sub_district' => 'dev_returnees.permanent_sub_district',
        'permanent_union' => 'dev_returnees.permanent_union',
    ),
    'returnee_id' => $filter_returnee_id,
    'name' => $filter_name,
    'nid' => $filter_nid,
    'passport' => $filter_passport,
    'division' => $filter_division,
    'district' => $filter_district,
    'sub_district' => $filter_sub_district,
    'union' => $filter_union,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_returnee_id',
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

$returnees = $this->get_returnees($args);
$pagination = pagination($returnees['total'], $per_page_items, $start);

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
if ($filter_returnee_id)
    $filterString[] = 'ID: ' . $filter_returnee_id;
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_nid)
    $filterString[] = 'NID: ' . $filter_nid;
if ($filter_passport)
    $filterString[] = 'Passport: ' . $filter_passport;
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
    unset($args['select_fields']);
    unset($args['limit']);
    
    $args['data_only'] = true;
    $args['report'] = true;
    $data = $this->get_returnees($args);
    $data = $data['data'];
    
    // This will be here in our project

    $writer =WriterEntityFactory::createXLSXWriter();
    $style = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(12)
           //->setShouldWrapText()
           ->build();

    $fileName = 'returnee-' . time() . '.xlsx';
    //$writer->openToFile('lemon1.xlsx'); // write data to a file or to a PHP stream
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
    $report_head = ['Returnee Report'];
    $singleRow = WriterEntityFactory::createRowFromArray($report_head,$style2);
    $writer->addRow($singleRow);

    $report_date = ['Date: '.Date('d-m-Y H:i')];
    $reportDateRow = WriterEntityFactory::createRowFromArray($report_date);
    $writer->addRow($reportDateRow);

    $filtered_with = ['Returnee ID = '.$filter_returnee_id.', Name = '.$filter_name.', NID = '.$filter_nid.', Passport = '.$filter_passport.', Division = ' . $filter_division . ', District = ' . $filter_district . ', Sub-District = ' . $filter_sub_district . ', Union = '.$filter_union. ', Police Station = ' . $filter_ps. ', Start Date = ' . $filter_entry_start_date. ', End Date = ' . $filter_entry_end_date];
    $rowFromVal = WriterEntityFactory::createRowFromArray($filtered_with);
    $writer->addRow($rowFromVal);

    $empty_row = [''];
    $rowFromVal = WriterEntityFactory::createRowFromArray($empty_row);
    $writer->addRow($rowFromVal);

    $header = [
                "SL",
                'Returnee ID', 
                'District Centre / Branch Name', 
                'Project', 
                'Name', 
                "Father's Name", 
                "Mother's Name",
                'Marital Status',
                'Gender',
                'Educational Qualification',
                'Mobile Number',
                'Emergency Mobile Number',
                'NID Number',
                'Birth Registration Number', 
                'Passport',
                'Division',
                'District',
                'Upazila',
                'Union',
                'BRAC Info ID',
                'Collection Date',
                'Type of person',
                'Return Date',
                'Country of Destination',
                'Legal Document',
                'Intention to Remigrate',
                'Occupation in overseas country',
                'Selected for profiling',
                'Remarks',
    ];

    $rowFromVal = WriterEntityFactory::createRowFromArray($header,$style);
    $writer->addRow($rowFromVal);
    $multipleRows = array();

    if ($data) {
        $count = 0;
        foreach ($data as $returnee) {

            $nid_number = $returnee['nid_number'] ? $returnee['nid_number'] : 'N/A';
            $birth_reg_number = $returnee['birth_reg_number'] ? $returnee['birth_reg_number'] : 'N/A';
            $customer_spouse = $returnee['customer_spouse'] ? $returnee['customer_spouse'] : 'N/A';

            if ($returnee['educational_qualification'] == 'illiterate'):
                $educational_qualification = 'Illiterate';
            elseif ($returnee['educational_qualification'] == 'sign'):
                $educational_qualification = 'Can Sign only';
            elseif ($returnee['educational_qualification'] == 'psc'):
                $educational_qualification = 'Primary education (Passed Grade 5)';
            elseif ($returnee['educational_qualification'] == 'not_psc'):
                $educational_qualification = 'Did not complete primary education';
            elseif ($returnee['educational_qualification'] == 'jsc'):
                $educational_qualification = 'Completed JSC (Passed Grade 8) or equivalent';
            elseif ($returnee['educational_qualification'] == 'ssc'):
                $educational_qualification = 'Completed School Secondary Certificate or equivalent';
            elseif ($returnee['educational_qualification'] == 'hsc'):
                $educational_qualification = 'Higher Secondary Certificate/Diploma/ equivalent';
            elseif ($returnee['educational_qualification'] == 'bachelor'):
                $educational_qualification = 'Bachelor’s degree or equivalent';
            elseif ($returnee['educational_qualification'] == 'master'):
                $educational_qualification = 'Masters or Equivalent';
            elseif ($returnee['educational_qualification'] == 'professional_education'):
                $educational_qualification = 'Completed Professional education';
            elseif ($returnee['educational_qualification'] == 'general_education'):
                $educational_qualification = 'Completed general Education';
            else:
                $educational_qualification = 'N/A';
            endif;

            $cells = [
                WriterEntityFactory::createCell(++$count),
                WriterEntityFactory::createCell($returnee['returnee_id']),
                WriterEntityFactory::createCell($returnee['branch_name']),
                WriterEntityFactory::createCell($returnee['project_name']),
                WriterEntityFactory::createCell($returnee['full_name']),
                WriterEntityFactory::createCell($returnee['father_name']),
                WriterEntityFactory::createCell($returnee['mother_name']),
                WriterEntityFactory::createCell(ucfirst($returnee['marital_status'])),
                WriterEntityFactory::createCell(ucfirst($returnee['returnee_gender'])),
                WriterEntityFactory::createCell($educational_qualification),
                WriterEntityFactory::createCell($mobile_number),
                WriterEntityFactory::createCell($emergency_mobile),
                WriterEntityFactory::createCell($nid_number),
                WriterEntityFactory::createCell($birth_reg_number),
                WriterEntityFactory::createCell($returnee['passport_number']),
                WriterEntityFactory::createCell($returnee['permanent_division']),
                WriterEntityFactory::createCell($returnee['permanent_district']),
                WriterEntityFactory::createCell($returnee['permanent_sub_district']),
                WriterEntityFactory::createCell($returnee['permanent_union']),
                WriterEntityFactory::createCell($returnee['brac_info_id']),
                WriterEntityFactory::createCell(date('d-m-Y', strtotime($returnee['collection_date']))),
                WriterEntityFactory::createCell($returnee['person_type']),
                WriterEntityFactory::createCell(date('d-m-Y', strtotime($returnee['return_date']))),
                WriterEntityFactory::createCell($returnee['destination_country']),
                WriterEntityFactory::createCell($returnee['legal_document'].' '.$returnee['other_legal_document']),
                WriterEntityFactory::createCell(ucfirst($returnee['remigrate_intention'])),
                WriterEntityFactory::createCell($returnee['destination_country_profession']),
                WriterEntityFactory::createCell(ucfirst($returnee['profile_selection'])),
                WriterEntityFactory::createCell($returnee['remarks']),

            ];

            $multipleRows[] = WriterEntityFactory::createRow($cells);

        }
    }

    
    $writer->addRows($multipleRows); 

    $currentSheet = $writer->getCurrentSheet();
    $mergeRanges = ['A1:AC1','A2:AC2','A3:AC3']; // you can list the cells you want to merge like this ['A1:A4','A1:E1']
    $currentSheet->setMergeRanges($mergeRanges);

    $writer->close();
    exit;
    // End this is to our project
}

doAction('render_start');
?>
<div class="page-header">
    <h1>All Returnees</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_returnee',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Returnee',
                'title' => 'New Returnee',
            ));
            ?>
        </div>
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_excel=1&returnee_id=' . $filter_returnee_id . '&name=' . $filter_name . '&nid=' . $filter_nid . '&passport=' . $filter_passport . '&division=' . $filter_division . '&district=' . $filter_district . '&sub_district=' . $filter_sub_district . '&union=' . $filter_union . '&entry_start_date=' . $filter_entry_start_date . '&entry_end_date=' . $filter_entry_end_date,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_download',
                'text' => 'Download Returnees',
                'title' => 'Download Returnees',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<?php
echo formProcessor::form_elements('returnee_id', 'returnee_id', array(
    'width' => 2, 'type' => 'text', 'label' => 'Returnee ID',
        ), $filter_returnee_id);
echo formProcessor::form_elements('name', 'name', array(
    'width' => 2, 'type' => 'text', 'label' => 'Returnee Name',
        ), $filter_name);
echo formProcessor::form_elements('nid', 'nid', array(
    'width' => 2, 'type' => 'text', 'label' => 'NID',
        ), $filter_nid);
echo formProcessor::form_elements('passport', 'passport', array(
    'width' => 2, 'type' => 'text', 'label' => 'Passport',
        ), $filter_passport);
$all_countries = getWorldCountry();
$result = array_combine($all_countries, $all_countries);
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
<div class="form-group col-sm-2">
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
        <?php echo searchResultText($returnees['total'], $start, $per_page_items, count($returnees['data']), 'returnees') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Passport Number</th>
                <th>Present Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($returnees['data'] as $i => $returnee) {
                ?>
                <tr>
                    <td><?php echo $returnee['returnee_id']; ?></td>
                    <td><?php echo $returnee['full_name']; ?></td>
                    <td><?php echo $returnee['mobile_number']; ?></td>
                    <td><?php echo $returnee['passport_number']; ?></td>
                    <td style="text-transform: capitalize"><?php echo '<b>Division - </b>' . $returnee['permanent_division'] . ',<br><b>District - </b>' . $returnee['permanent_district'] . ',<br><b>Upazila - </b>' . $returnee['permanent_sub_district'] . ',<br><b>Union - </b>' . $returnee['permanent_union'] ?></td>
                    <td>
                        <?php if (has_permission('edit_returnee')): ?>
                            <div class="btn-group">
                                <a href="<?php echo url('admin/dev_customer_management/manage_returnees?action=add_edit_returnee&edit=' . $returnee['pk_returnee_id']) ?>" class="btn btn-flat btn-labeled btn-sm btn btn-primary"><i class="fa fa-pencil-square-o"></i> Edit</a>
                            </div>                                
                        <?php endif ?>
                        <?php if (has_permission('delete_returnee')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $returnee['pk_returnee_id']),
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
                        text: 'Delete Returnee Profile Information',
                        value: 'deleteProfile'
                    }],
                callback: function (result) {
                    if (result == 'deleteProfile') {
                        window.location.href = '?action=deleteProfile&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>