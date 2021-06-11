<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_tag = $_GET['tag'] ? $_GET['tag'] : null;
$filter_start_date = $_GET['start_date'] ? $_GET['start_date'] : null;
$filter_end_date = $_GET['end_date'] ? $_GET['end_date'] : null;

$args = array(
    'name' => $filter_name,
    'tags' => $filter_tag,
    'type' => 'research',
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_knowledge_id',
        'order' => 'DESC'
    ),
);

if ($filter_start_date && $filter_end_date) {
    $args['BETWEEN_INCLUSIVE'] = array(
        'create_date' => array(
            'left' => date_to_db($filter_start_date),
            'right' => date_to_db($filter_end_date),
        ),
    );
}

$research_reports = $this->get_knowledge($args);
$pagination = pagination($research_reports['total'], $per_page_items, $start);

$filterString = array();
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_tag)
    $filterString[] = 'Tag: ' . $filter_tag;
if ($filter_start_date)
    $filterString[] = 'Start Date: ' . $filter_start_date;
if ($filter_end_date)
    $filterString[] = 'End Date: ' . $filter_end_date;

$all_research_report_tags = $this->get_lookups('success_research_report');

doAction('render_start');
?>
<div class="page-header">
    <div class="row">
        <div class="col-md-8">
            <h1>All Research Report</h1>
            <div class="oh">
                <div class="btn-group btn-group-sm">
                    <?php
                    echo linkButtonGenerator(array(
                        'href' => $myUrl . '?action=add_edit_research_report',
                        'action' => 'add',
                        'icon' => 'icon_add',
                        'text' => 'New Research Report',
                        'title' => 'New Research Report',
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-row">
                <div class="stat-cell bg-warning">
                    <span class="text-bg"><?php echo $research_reports['total'] ?></span><br>
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
    'width' => 3, 'type' => 'text', 'label' => 'Name',
        ), $filter_name)
?>
<div class="form-group col-sm-3">
    <label>Tag</label>
    <select name="tag" class="form-control">
        <option value="">Select Tag</option>
        <?php
        foreach ($all_research_report_tags['data'] as $tag) {
            ?>
            <option value="<?php echo $tag['lookup_value'] ?>" <?php
            if ($tag['lookup_value'] == $filter_tag) {
                echo 'selected';
            }
            ?>><?php echo $tag['lookup_value'] ?></option>
                <?php } ?>
    </select>
</div>
<div class="form-group col-sm-3">
    <label>Entry Start Date</label>
    <div class="input-group">
        <input id="startDate" type="text" class="form-control" name="start_date" value="<?php echo $filter_start_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('startDate');
        });
    </script>
</div>
<div class="form-group col-sm-3">
    <label>Entry End Date</label>
    <div class="input-group">
        <input id="endDate" type="text" class="form-control" name="end_date" value="<?php echo $filter_end_date ?>">
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
        <?php echo searchResultText($research_reports['total'], $start, $per_page_items, count($research_reports['data']), 'research reports') ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>View/Download</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($research_reports['data'] as $i => $report) {
                ?>
                <tr>
                    <td><?php echo date('d-m-Y', strtotime($report['create_date'])) ?></td>
                    <td><?php echo $report['name']; ?></td>
                    <td><a href="<?php echo image_url($report['document_file']); ?>" target="_blank">Click Here</a></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_research_report')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_research_report', 'edit' => $report['pk_knowledge_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Research Report',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_research_report')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $report['pk_knowledge_id']),
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
                        window.location.href = '?action=deleteResearchReport&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>