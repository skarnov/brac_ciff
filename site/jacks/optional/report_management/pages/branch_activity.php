<?php
global $devdb;

$filter_month = $_GET['month'] ? $_GET['month'] : null;
$filter_year = $_GET['year'] ? $_GET['year'] : null;
$filter_branch = $_GET['fk_branch_id'] ? $_GET['fk_branch_id'] : null;
$branch_id = $_config['user']['user_branch'];
$branch = $branch_id ? $branch_id : $filter_branch;

if ($filter_month && $filter_year && $branch) {
    $first_day = $filter_year . '-' . $filter_month . '-01';
    $last_day = date('Y-m-t', strtotime($first_day));
    $sql = "SELECT invoice_date, sale_total FROM dev_sales WHERE invoice_date BETWEEN '$first_day' AND '$last_day' AND fk_branch_id = '$branch'";
    $results = $devdb->get_results($sql);

    $income = "SELECT create_date, buying_price, sale_income FROM dev_sales_income WHERE create_date BETWEEN '$first_day' AND '$last_day' AND fk_branch_id = '$branch'";
    $sales_income = $devdb->get_results($income);
}

$current_year = date('Y') - 5;
$years = range($current_year, $current_year + 20);

$branchManager = jack_obj('dev_branch_management');
$branches = $branchManager->get_branches(array('select_fields' => array('branches.pk_branch_id', 'branches.branch_name'), 'data_only' => true));

doAction('render_start');
?>
<div class="page-header">
    <h1>Branch Activity</h1>
</div>
<form id="theForm" onsubmit="return true;" method="get" action="">
    <div class="panel" id="fullForm">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Select Month</label>
                        <select required class="form-control" name="month">
                            <option value="">Select One</option>
                            <option value="01" <?php if ('01' == $filter_month) echo 'selected' ?>>January</option>
                            <option value="02" <?php if ('02' == $filter_month) echo 'selected' ?>>February</option>
                            <option value="03" <?php if ('03' == $filter_month) echo 'selected' ?>>March</option>
                            <option value="04" <?php if ('04' == $filter_month) echo 'selected' ?>>April</option>
                            <option value="05" <?php if ('05' == $filter_month) echo 'selected' ?>>May</option>
                            <option value="06" <?php if ('06' == $filter_month) echo 'selected' ?>>June</option>
                            <option value="07" <?php if ('07' == $filter_month) echo 'selected' ?>>July</option>
                            <option value="08" <?php if ('08' == $filter_month) echo 'selected' ?>>August</option>
                            <option value="09" <?php if ('09' == $filter_month) echo 'selected' ?>>September</option>
                            <option value="10" <?php if ('10' == $filter_month) echo 'selected' ?>>October</option>
                            <option value="11" <?php if ('11' == $filter_month) echo 'selected' ?>>November</option>
                            <option value="12" <?php if ('12' == $filter_month) echo 'selected' ?> >December</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Year</label>
                        <select required class="form-control" name="year">
                            <option value="">Select One</option>
                            <?php foreach ($years as $year) : ?>
                                <option value="<?php echo $year ?>" <?php if ($year == $filter_year) echo 'selected' ?>><?php echo $year ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <?php if (!$branch_id): ?>
                    <div class="form-group">
                        <label>Branch</label>
                        <select required id="branchId" class="form-control" name="fk_branch_id">
                            <option value="">Select One</option>
                            <?php
                            foreach ($branches['data'] as $i => $v) {
                                ?>
                                <option value="<?php echo $v['pk_branch_id'] ?>" <?php echo $v['pk_branch_id'] == $branch ? 'selected' : '' ?>><?php echo $v['branch_name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <?php endif ?>
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
                <div class="col-md-9">
                    <script>
                    init.push(function () {
                            Morris.Bar({
                            element: 'hero-bar',
                                data: [
                                    <?php foreach ($results as $value) : ?>
                                        {device: '<?php echo $value['invoice_date'] ?>', geekbench: <?php echo $value['sale_total'] ?>},
                                    <?php endforeach ?>
                                ],
                                xkey: 'device',
                                ykeys: ['geekbench'],
                                labels: ['Sales Amount'],
                                barRatio: 0.4,
                                xLabelAngle: 35,
                                hideHover: 'auto',
                                barColors: PixelAdmin.settings.consts.COLORS,
                                gridLineColor: '#cfcfcf',
                                resize: true
                            });
                        }
                    );
                    </script>
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">Sales</span>
                        </div>
                        <div class="panel-body">
                            <div class="graph-container">
                                <div id="hero-bar" class="graph"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <script>
                            init.push(function () {
                            var doughnutChartData = [
                                <?php foreach ($sales_income as $income) : ?>
                                    {
                                        label: "<?php echo $income['create_date'] ?>", data: <?php echo $income['sale_income'] ?>
                                    },
                                <?php endforeach ?>

                            ];
                            $('#jq-flot-pie').pixelPlot(doughnutChartData, {
                                series: {
                                    pie: {
                                        show: true,
                                        radius: 1,
                                        innerRadius: 0.5,
                                        label: {
                                            show: true,
                                            radius: 3 / 4,
                                            formatter: function (label, series) {
                                                return '<div style="font-size:14px;text-align:center;padding:2px;color:white;">' + Math.round(series.percent) + '%</div>';
                                            },
                                            background: {opacity: 0}
                                        }
                                    }
                                }
                            }, {
                                height: 505
                            });
                        });
                    </script>
                    <div class="panel">
                        <div class="panel-heading">
                            <span class="panel-title">Top Sales Income Day</span>
                        </div>
                        <div class="panel-body">
                            <div class="graph-container">
                                <div id="jq-flot-pie"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>