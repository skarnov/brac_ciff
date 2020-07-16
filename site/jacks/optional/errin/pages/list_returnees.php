<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_id = $_GET['id'] ? $_GET['id'] : null;
$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_type = $_GET['type'] ? $_GET['type'] : null;
$filter_sex = $_GET['sex'] ? $_GET['sex'] : null;
$filter_contact_person = $_GET['contact_person'] ? $_GET['contact_person'] : null;
$filter_email = $_GET['email'] ? $_GET['email'] : null;
$filter_mobile = $_GET['mobile'] ? $_GET['mobile'] : null;
$filter_skype = $_GET['skype'] ? $_GET['skype'] : null;

$args = array(
    'id' => $filter_id,
    'name' => $filter_name,
    'type' => $filter_type,
    'sex' => $filter_sex,
    'contact_person' => $filter_contact_person,
    'email' => $filter_email,
    'mobile' => $filter_mobile,
    'skype' => $filter_skype,
    'customer_type' => 'errin',    
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_customer_id',
        'order' => 'DESC'
    ),
);

$customers = $this->get_errin_returnees($args);
$pagination = pagination($customers['total'], $per_page_items, $start);

$filterString = array();
if ($filter_id)
    $filterString[] = 'ID: ' . $filter_id;
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_type)
    $filterString[] = 'Returnee Type: ' . $filter_type;
if ($filter_sex)
    $filterString[] = 'Sex: ' . $filter_sex;
if ($filter_contact_person)
    $filterString[] = 'Contact Phone Number: ' . $filter_contact_person;
if ($filter_email)
    $filterString[] = 'Email: ' . $filter_email;
if ($filter_mobile)
    $filterString[] = 'Mobile: ' . $filter_mobile;
if ($filter_skype)
    $filterString[] = 'Skype ID: ' . $filter_skype;


if ($_GET['download_csv']) {
    unset($args['limit_by']);
    $args['data_only'] = true;
    $data = $this->get_errin_returnees($args);    
    $data = $data['data'];

    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'ERRIN-returnee-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'ERRIN Returnee Report', '');
    fputcsv($fh, $report_title);

    $filtered_with = array('', 'Returnee Type = '.$filter_type.', Returnee Sex = '.$filter_sex, '');
    fputcsv($fh, $filtered_with);
    
    $blank_row = array('');
    fputcsv($fh, $blank_row);
    
    $headers = array('#', 'ID', 'Name', 'Type', 'Sex', 'Date of Birth', 'Part of a family group', 'Volutary', 'Group', 'Contact Phone Number', 'Contact Email Address', 'Contact Mobile Number');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['pk_customer_id']
                , $user['full_name']
                , $user['returnee_type']
                , $user['customer_gender']
                , $user['customer_birthdate'] . "\r"
                , $user['family_part']
                , $user['having_volutary']
                , $user['having_group']
                , $user['emergency_mobile'] . "\r"
                , $user['email_address']
                , $user['customer_mobile'] . "\r");
            fputcsv($fh, $dataToSheet);
        }
    }

    fclose($fh);

    $now = time();
    foreach (glob($csvFolder . "*.csv") as $file) {
        if (is_file($file)) {
            if ($now - filemtime($file) >= 60 * 60 * 24 * 2) { // 2 days
                unlink($file);
            }
        }
    }

    if (function_exists('apache_setenv'))
        @apache_setenv('no-gzip', 1);
    @ini_set('zlib.output_compression', 'Off');

    //Get file type and set it as Content Type
    header('Content-Type: text/csv');
    //Use Content-Disposition: attachment to specify the filename
    header('Content-Disposition: attachment; filename=' . basename($csvFile));
    //No cache
    header('Expires: 0');
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Pragma: public');
    //Define file size
    header('Content-Length: ' . filesize($csvFile));
    set_time_limit(0);
    $file = @fopen($csvFile, "rb");
    while (!feof($file)) {
        print(@fread($file, 1024 * 8));
        ob_flush();
        flush();
    }
    @fclose($file);
    exit;
}

$customer = jack_obj('dev_customer_management');
$all_genders = $customer->get_lookups('gender')['data'];
foreach ($all_genders as $value) {
    $genders[] = $value['lookup_value'];
}

doAction('render_start');
?>
<div class="page-header">
    <h1>All Returnee Migrants</h1>
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
                'href' => '?download_csv=1&id='.$filter_id.'&name='.$filter_name.'&type='.$filter_type.'&sex='.$filter_sex.'&contact_person='.$filter_contact_person.'&email='.$filter_email.'&mobile='.$filter_mobile.'&skype='.$filter_skype,
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_edit',
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
echo formProcessor::form_elements('id', 'id', array(
    'width' => 2, 'type' => 'text', 'label' => 'ID',
        ), $filter_id);
echo formProcessor::form_elements('name', 'name', array(
    'width' => 3, 'type' => 'text', 'label' => 'Name',
        ), $filter_name);
echo formProcessor::form_elements('type', 'type', array(
    'width' => 2, 'type' => 'select', 'label' => 'Returnee Type', 'data' => array('static' => array('individual' => 'Individual', 'family' => 'Family'))
        ), $filter_type);
?>
<div class="form-group col-sm-2">
    <label>Sex</label>
    <select class="form-control" name="sex">
        <option value>Select One</option>
        <?php foreach ($genders as $value) {
            ?>
            <option name="<?php echo $value ?>" <?php if($value == $filter_sex) echo 'selected' ?>><?php echo $value ?></option>
        <?php } ?>
    </select>
</div>
<?php
echo formProcessor::form_elements('contact_person', 'contact_person', array(
    'width' => 3, 'type' => 'text', 'label' => 'Contact Phone Number',
        ), $filter_contact_person);
echo formProcessor::form_elements('email', 'email', array(
    'width' => 3, 'type' => 'text', 'label' => 'Email',
        ), $filter_email);
echo formProcessor::form_elements('mobile', 'mobile', array(
    'width' => 3, 'type' => 'text', 'label' => 'Mobile',
        ), $filter_mobile);
echo formProcessor::form_elements('skype', 'skype', array(
    'width' => 3, 'type' => 'text', 'label' => 'Skype ID',
        ), $filter_skype);
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
        <?php echo searchResultText($customers['total'], $start, $per_page_items, count($customers['data']), 'customers') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Sex</th>
                <th>Contact Phone Number</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Skype ID</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($customers['data'] as $i => $customer) {
                ?>
                <tr>
                    <td><?php echo $customer['pk_customer_id']; ?></td>
                    <td><?php echo $customer['full_name']; ?></td>
                    <td style="text-transform: capitalize"><?php echo $customer['returnee_type']; ?></td>
                    <td><?php echo $customer['customer_gender']; ?></td>
                    <td><?php echo $customer['emergency_mobile']; ?></td>
                    <td><?php echo $customer['email_address']; ?></td>
                    <td><?php echo $customer['customer_mobile']; ?></td>
                    <td><?php echo $customer['skype_id']; ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_errin_returnee')): ?>
                            <div class="btn-toolbar">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_returnee', 'edit' => $customer['pk_customer_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Returnee',
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
            <?php echo $pagination?>
        </div>
    </div>
</div>