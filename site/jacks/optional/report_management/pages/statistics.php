<?php
global $devdb;

$sql = "SELECT CONCAT(route_name, destination_country) AS route_name, COUNT(route_name) AS uses_number FROM dev_migration_mapping GROUP BY route_name, destination_country";
$results = $devdb->get_results($sql);

$required_certification = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        lookup_value AS `name`,
        SUM(IF(c.customer_gender = 'Male',1,0)) AS `_male`,
        SUM(IF(c.customer_gender = 'Female',1,0)) AS `_female`
    FROM dev_customers AS c
INNER JOIN dev_lookup_relation AS m ON (m.fk_output_id = c.pk_customer_id)
WHERE m.column_name = 'required_certification'
GROUP BY m.lookup_value) AS source_tb";
$required_certification_results = $devdb->get_results($required_certification);

$need_skill = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        lookup_value AS `name`,
        SUM(IF(c.customer_gender = 'Male',1,0)) AS `_male`,
        SUM(IF(c.customer_gender = 'Female',1,0)) AS `_female`
    FROM dev_customers AS c
INNER JOIN dev_lookup_relation AS m ON (m.fk_output_id = c.pk_customer_id)
WHERE m.column_name = 'need_skills'
GROUP BY m.lookup_value) AS source_tb";
$need_skill_results = $devdb->get_results($need_skill);

$have_skill = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        lookup_value AS `name`,
        SUM(IF(c.customer_gender = 'Male',1,0)) AS `_male`,
        SUM(IF(c.customer_gender = 'Female',1,0)) AS `_female`
    FROM dev_customers AS c
INNER JOIN dev_lookup_relation AS m ON (m.fk_output_id = c.pk_customer_id)
WHERE m.column_name = 'have_skills'
GROUP BY m.lookup_value) AS source_tb";
$have_skill_results = $devdb->get_results($have_skill);

$economic = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        m.economic_condition AS `name`,
        SUM(IF(c.customer_gender = 'Male',1,0)) AS `_male`,
        SUM(IF(c.customer_gender = 'Female',1,0)) AS `_female`
    FROM dev_customers AS c
INNER JOIN dev_economic_profile AS m ON (m.fk_customer_id = c.pk_customer_id)
GROUP BY m.economic_condition) AS source_tb";
$economic_results = $devdb->get_results($economic);

$climate = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        lookup_value AS `name`,
        SUM(IF(c.customer_gender = 'Male',1,0)) AS `_male`,
        SUM(IF(c.customer_gender = 'Female',1,0)) AS `_female`
    FROM dev_customers AS c
INNER JOIN dev_lookup_relation AS m ON (m.fk_output_id = c.pk_customer_id)
WHERE m.column_name = 'natural_disaster'
GROUP BY m.lookup_value) AS source_tb";
$climate_results = $devdb->get_results($climate);

$migration = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        m.migration_type AS `name`,
        SUM(IF(c.customer_gender = 'Male',1,0)) AS `_male`,
        SUM(IF(c.customer_gender = 'Female',1,0)) AS `_female`
    FROM dev_customers AS c
INNER JOIN dev_migrations AS m ON (m.fk_customer_id = c.pk_customer_id)
GROUP BY m.migration_type) AS source_tb";
$migration_results = $devdb->get_results($migration);

$migrationCostWiseCount = "SELECT
source_table._range
,SUM(IF(source_table.customer_gender = 'Male',1,0)) AS `_male`
,SUM(IF(source_table.customer_gender = 'Female',1,0)) AS `_female`
,SUM(IF(source_table.customer_gender NOT IN ('Male', 'Female'),1,0)) AS `_other`
FROM
    (
    SELECT
    dev_customers.`customer_gender`,
    dev_migrations.`migration_cost`,
    (CASE
        WHEN migration_cost < 100000 THEN 'Below 100000'
        WHEN migration_cost >= 100001 AND migration_cost <= 200000 THEN '100001 - 200000'
        WHEN migration_cost >= 300001 AND migration_cost <= 400000 THEN '300001 - 400000'
        WHEN migration_cost >= 400001 AND migration_cost <= 500000 THEN '400001 - 500000'
        WHEN migration_cost >= 500001 AND migration_cost <= 600000 THEN '500001 - 600000'
        WHEN migration_cost >= 600001 AND migration_cost <= 700000 THEN '600001 - 700000'
        WHEN migration_cost >= 700001 AND migration_cost <= 800000 THEN '700001 - 800000'
        WHEN migration_cost >= 800001 AND migration_cost <= 900000 THEN '800001 - 900000'
        WHEN migration_cost >= 900001 AND migration_cost <= 1000000 THEN '900001 - 1000000'
        ELSE 'Above 1000000'
    END) AS _range
    FROM dev_customers INNER JOIN dev_migrations ON (dev_migrations.fk_customer_id = dev_customers.pk_customer_id)) AS source_table
GROUP BY _range
;";
$migrationCostWiseCount_result = $devdb->get_results($migrationCostWiseCount);

$visa = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        m.visa_type AS `name`,
        SUM(IF(c.customer_gender = 'Male',1,0)) AS `_male`,
        SUM(IF(c.customer_gender = 'Female',1,0)) AS `_female`
    FROM dev_customers AS c
INNER JOIN dev_migrations AS m ON (m.fk_customer_id = c.pk_customer_id)
GROUP BY m.visa_type) AS source_tb";
$visa_results = $devdb->get_results($visa);

$media = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        lookup_value AS `name`,
        SUM(IF(c.customer_gender = 'Male',1,0)) AS `_male`,
        SUM(IF(c.customer_gender = 'Female',1,0)) AS `_female`
    FROM dev_customers AS c
INNER JOIN dev_lookup_relation AS m ON (m.fk_output_id = c.pk_customer_id)
WHERE m.column_name = 'migration_medias'
GROUP BY m.lookup_value) AS source_tb";
$media_results = $devdb->get_results($media);

$occupation = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        m.migration_occupation AS `name`,
        SUM(IF(c.customer_gender = 'Male',1,0)) AS `_male`,
        SUM(IF(c.customer_gender = 'Female',1,0)) AS `_female`
    FROM dev_customers AS c
INNER JOIN dev_migrations AS m ON (m.fk_customer_id = c.pk_customer_id)
GROUP BY m.migration_occupation) AS source_tb";
$occupation_results = $devdb->get_results($occupation);

$migration_status = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        m.migration_status AS `name`,
        SUM(IF(c.customer_gender = 'Male',1,0)) AS `_male`,
        SUM(IF(c.customer_gender = 'Female',1,0)) AS `_female`
    FROM dev_customers AS c
INNER JOIN dev_migrations AS m ON (m.fk_customer_id = c.pk_customer_id)
GROUP BY m.migration_status) AS source_tb";
$migration_status_results = $devdb->get_results($migration_status);

$support = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        lookup_value AS `name`,
        SUM(IF(c.customer_gender = 'Male',1,0)) AS `_male`,
        SUM(IF(c.customer_gender = 'Female',1,0)) AS `_female`
    FROM dev_customers AS c
INNER JOIN dev_lookup_relation AS m ON (m.fk_output_id = c.pk_customer_id)
WHERE m.column_name = 'immediate_support'
GROUP BY m.lookup_value) AS source_tb";
$support_results = $devdb->get_results($support);

$spent_type = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        lookup_value AS `name`,
        SUM(IF(c.customer_gender = 'Male',1,0)) AS `_male`,
        SUM(IF(c.customer_gender = 'Female',1,0)) AS `_female`
    FROM dev_customers AS c
INNER JOIN dev_lookup_relation AS m ON (m.fk_output_id = c.pk_customer_id)
WHERE m.column_name = 'spent_types'
GROUP BY m.lookup_value) AS source_tb";
$spent_type_results = $devdb->get_results($spent_type);

$reintegration_plan = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        lookup_value AS `name`,
        SUM(IF(c.customer_gender = 'Male',1,0)) AS `_male`,
        SUM(IF(c.customer_gender = 'Female',1,0)) AS `_female`
    FROM dev_customers AS c
INNER JOIN dev_lookup_relation AS m ON (m.fk_output_id = c.pk_customer_id)
WHERE m.column_name = 'reintegration_plan'
GROUP BY m.lookup_value) AS source_tb";
$reintegration_plan_results = $devdb->get_results($reintegration_plan);

$support_provide = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        m.support_name AS `name`,
        SUM(IF(c.customer_gender = 'Male',1,0)) AS `_male`,
        SUM(IF(c.customer_gender = 'Female',1,0)) AS `_female`
    FROM dev_customers AS c
INNER JOIN dev_supports AS m ON (m.fk_customer_id = c.pk_customer_id)
GROUP BY m.support_name) AS source_tb";
$support_provide_results = $devdb->get_results($support_provide);

$project_staff = "SELECT *, (_male+_female) AS _total
    FROM (
    SELECT
        project_short_name AS `name`,
        SUM(IF(dev_users.user_gender = 'male',1,0)) AS `_male`,
        SUM(IF(dev_users.user_gender = 'female',1,0)) AS `_female`
    FROM dev_users
INNER JOIN dev_projects ON (dev_projects.pk_project_id = dev_users.fk_project_id)
GROUP BY dev_projects.project_short_name) AS source_tb";
$project_staff_results = $devdb->get_results($project_staff);

if ($_GET['download_csv']) {
    $sql = "SELECT CONCAT(route_name, destination_country) AS route_name, COUNT(route_name) AS uses_number FROM dev_migration_mapping GROUP BY route_name, destination_country";
    $results = $devdb->get_results($sql);

    $data = $results;
    $target_dir = _path('uploads', 'absolute') . "/";
    if (!file_exists($target_dir))
        mkdir($target_dir);

    $csvFolder = $target_dir;
    $csvFile = $csvFolder . 'migration_countries-' . time() . '.csv';

    $fh = fopen($csvFile, 'w');

    $report_title = array('', 'Migration Country Report', '');
    fputcsv($fh, $report_title);


    $blank_row = array('');
    fputcsv($fh, $blank_row);

    $headers = array('#', 'Country', 'Uses Number');
    fputcsv($fh, $headers);

    if ($data) {
        $count = 0;
        foreach ($data as $user) {
            $dataToSheet = array(
                ++$count
                , $user['route_name']
                , $user['uses_number'] . "\r");
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

doAction('render_start');
?>
<div class="page-header">
    <h1>Statistics</h1>
</div>
<div class="panel" id="fullForm">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Migration Countries
                        <div class="pull-right">
                            <?php
                            echo linkButtonGenerator(array(
                                'href' => '?download_csv=1',
                                'attributes' => array('target' => '_blank'),
                                'action' => 'download',
                                'icon' => 'icon_edit',
                                'text' => 'Download',
                                'title' => 'Download Migration Mapping Report',
                            ));
                            ?>
                        </div>
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Countries</th>
                                <th>Uses Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['route_name'] ?></td>
                                    <td><?php echo $value['uses_number'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Skill Wise Beneficiary (Training)
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Skill Category</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($required_certification_results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['name'] ? $value['name'] : 'Unskilled' ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_male'] ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_total'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Skill Required By Beneficiary
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Skill Category</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($need_skill_results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['name'] ? $value['name'] : 'Unskilled' ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_male'] ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_total'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Income Earner Skills (RPL Analysis)
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Skill Category</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($have_skill_results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['name'] ? $value['name'] : 'Unskilled' ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_male'] ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_total'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Category Wise Beneficiary
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Beneficiary Category</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($economic_results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['name'] ? $value['name'] : 'Unspecified' ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_male'] ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_total'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Climate Change Effects
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Disaster Nature</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($climate_results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['name'] ? $value['name'] : 'Unspecified' ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_male'] ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_total'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Migration Type
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Migration Nature</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($migration_results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['name'] ? $value['name'] : 'Unspecified' ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_male'] ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_total'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Migration Cost
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Range</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Other</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($migrationCostWiseCount_result as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['_range'] ?></td>
                                    <td><?php echo $value['_male'] ?></td>
                                    <td><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_other'] ?></td>
                                    <td><?php echo $value['_male'] + $value['_female'] + $value['_other'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Type of Visa
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Visa Types</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($visa_results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['name'] ? $value['name'] : 'Unspecified' ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_male'] ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_total'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Media of Migration
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Migration Media</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($media_results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['name'] ? $value['name'] : 'Unspecified' ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_male'] ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_total'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Occupation in Abroad
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Occupation</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($occupation_results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['name'] ? $value['name'] : 'Unspecified' ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_male'] ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_total'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Remigration
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($migration_status_results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['name'] ? str_replace('_', ' ', $value['name']) : 'Unspecified' ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_male'] ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_total'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Remittance Expenditure
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Type of Area</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($spent_type_results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['name'] ? $value['name'] : 'Unspecified' ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_male'] ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_total'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Reintegration Plan
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Reintegration</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($reintegration_plan_results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['name'] ? $value['name'] : 'Unspecified' ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_male'] ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_total'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Support Provides
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Support</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($support_provide_results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['name'] ? $value['name'] : 'Unspecified' ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_male'] ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_total'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-primary table-responsive">
                    <div class="table-header">
                        Project Staff
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($project_staff_results as $value) {
                                ?>
                                <tr>
                                    <td style="text-transform: capitalize"><?php echo $value['name'] ? $value['name'] : 'Unspecified' ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_male'] ?></td>
                                    <td style="text-transform: capitalize"><?php echo $value['_female'] ?></td>
                                    <td><?php echo $value['_total'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>