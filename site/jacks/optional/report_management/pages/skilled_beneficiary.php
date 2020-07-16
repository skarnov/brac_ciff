<?php

global $devdb;
$filter_customer_skills = $_GET['customer_skills'] ? $_GET['customer_skills'] : null;

if ($filter_customer_skills) {
    $queries = @implode("' OR lookup_value = '", $filter_customer_skills);
    $search = strlen($queries) ? " AND lookup_value = '" . $queries . "'" : '';

    $sql = "SELECT full_name, customer_id, present_house, present_road, present_village, present_post_office, present_police_station, present_sub_district, present_district, lookup_value, column_name FROM dev_customers LEFT JOIN dev_lookup_relation ON (dev_lookup_relation.fk_output_id = dev_customers.pk_customer_id) WHERE column_name = 'required_certification' " . $search . 'GROUP BY full_name';
    $results = $devdb->get_results($sql);
}

if($_GET['download_csv']){
    $skills_url = explode("/", $_GET['filter']);
    
    $queries = implode("' OR lookup_value = '", $skills_url);
    $search = strlen($queries) ? " AND lookup_value = '" . $queries . "'" : '';

    $sql = "SELECT full_name, customer_id, present_house, present_road, present_village, present_post_office, present_police_station, present_sub_district, present_district, lookup_value, column_name FROM dev_customers LEFT JOIN dev_lookup_relation ON (dev_lookup_relation.fk_output_id = dev_customers.pk_customer_id) WHERE column_name = 'required_certification' " . $search . 'GROUP BY full_name';
    $results = $devdb->get_results($sql);
    
    $data = $results;
    $target_dir = _path('uploads', 'absolute') . "/";
            if (!file_exists($target_dir))
                mkdir($target_dir);
        
    $csvFolder = $target_dir;
    $csvFile = $csvFolder.'skilled_beneficiary-'.time().'.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Skilled Beneficiary Report', '');
    fputcsv($fh, $report_title);
    
    
    $blank_row = array('');
    fputcsv($fh, $blank_row);
   
    $headers = array('#', 'Name', 'ID', 'Address');
    fputcsv($fh, $headers);

    if($data){
        $count = 0;
        foreach($data as $user){
            $dataToSheet = array(
                ++$count
                , $user['full_name']
                , $user['customer_id']."\r"
                , 'H-'.$user['present_house'].', R-'.$user['present_road'].', V-'.$user['present_village'].', PO-'.$user['present_post_office'].', PS-'.$user['present_police_station'].', SD-'.$user['present_sub_district'].', D-'.$user['present_district']);
            fputcsv($fh, $dataToSheet);
        }
    }

    fclose($fh);

    $now = time();
    foreach (glob($csvFolder."*.csv") as $file) {
        if (is_file($file)) {
            if ($now - filemtime($file) >= 60 * 60 * 24 * 2) { // 2 days
                unlink($file);
            }
        }
    }

    if(function_exists('apache_setenv')) @apache_setenv('no-gzip', 1);
    @ini_set('zlib.output_compression', 'Off');

    //Get file type and set it as Content Type
    header('Content-Type: text/csv');
    //Use Content-Disposition: attachment to specify the filename
    header('Content-Disposition: attachment; filename='.basename($csvFile));
    //No cache
    header('Expires: 0');
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Pragma: public');
    //Define file size
    header('Content-Length: ' . filesize($csvFile));
    set_time_limit(0);
    $file = @fopen($csvFile,"rb");
    while(!feof($file))
        {
        print(@fread($file, 1024*8));
        ob_flush();
        flush();
        }
    @fclose($file);    
    exit;
    
}

$customers = jack_obj('dev_customer_management');
$all_technical_skills = $customers->get_lookups('technical_skills');
$all_non_technical_skills = $customers->get_lookups('non_technical_skills');
$all_soft_skills = $customers->get_lookups('soft_skills');

doAction('render_start');
?>
<div class="page-header">
    <h1>Skilled Beneficiary</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => '?download_csv=1&filter='. @implode("/", $filter_customer_skills),
                'attributes' => array('target' => '_blank'),
                'action' => 'download',
                'icon' => 'icon_edit',
                'text' => 'Download Skilled Beneficiary',
                'title' => 'Download Skilled Beneficiary Report',
            ));
            ?>
        </div>
    </div>
</div>
<form id="theForm" onsubmit="return true;" method="get" action="">
    <div class="panel" id="fullForm">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Technical Skills</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <?php
                                foreach ($all_technical_skills['data'] as $technical) {
                                    ?>
                                    <label><input class="px" type="checkbox" name="customer_skills[]" value="<?php echo $technical['lookup_value'] ?>" <?php if (@in_array($technical['lookup_value'], $filter_customer_skills)) {echo 'checked';} ?>><span class="lbl"><?php echo $technical['lookup_value'] ?></span></label>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Non-Technical Skills</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <?php
                                foreach ($all_non_technical_skills['data'] as $non_technical) {
                                    ?>
                                    <label><input class="px" type="checkbox" name="customer_skills[]" value="<?php echo $non_technical['lookup_value'] ?>" <?php if (@in_array($non_technical['lookup_value'], $filter_customer_skills)) {echo 'checked';} ?>><span class="lbl"><?php echo $non_technical['lookup_value'] ?></span></label>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Soft Skills</label>
                        <div class="form_element_holder radio_holder radio_holder_static_featured_show_link">
                            <div class="options_holder radio">
                                <?php
                                foreach ($all_soft_skills['data'] as $soft) {
                                    ?>
                                    <label><input class="px" type="checkbox" name="customer_skills[]" value="<?php echo $soft['lookup_value'] ?>" <?php if (@in_array($soft['lookup_value'], $filter_customer_skills)) {echo 'checked';} ?>><span class="lbl"><?php echo $soft['lookup_value'] ?></span></label>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer tar">
                        <?php
                        echo submitButtonGenerator(array(
                            'action' => 'search',
                            'size' => '',
                            'id' => 'submit',
                            'title' => 'Search',
                            'icon' => 'icon_search',
                            'text' => 'Search'))
                        ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="table-primary table-responsive">
                        <div class="table-header">
                            Search Results
                        </div>
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>ID</th>
                                    <th>Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($results == null) {
                                    ?>
                                    <tr>
                                        <td style="color:red" colspan="6">Not Found</td>
                                    </tr>
                                    <?php
                                } else {
                                    foreach ($results as $i => $customer) {
                                        ?>
                                        <tr>
                                            <td><?php echo $customer['full_name'] ?></td>
                                            <td><?php echo $customer['customer_id'] ?></td>
                                            <td><?php echo 'H-' . $customer['present_house'] . ', R-' . $customer['present_road'] . ', V-' . $customer['present_village'] . ', PO-' . $customer['present_post_office'] . ', PS-' . $customer['present_police_station'] . ', SD-' . $customer['present_sub_district'] . ', D-' . $customer['present_district'] ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>