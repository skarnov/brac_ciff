<?php
$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$filter_name = $_GET['name'] ? $_GET['name'] : null;
$filter_tag = $_GET['tag'] ? $_GET['tag'] : null;
$filter_start_date = $_GET['start_date'] ? $_GET['start_date'] : null;
$filter_end_date = $_GET['end_date'] ? $_GET['end_date'] : null;

$args = array(
    'name' => $filter_name,
    'tag' => $filter_tag,
    'type' => 'story',
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

$stories = $this->get_knowledge($args);
$pagination = pagination($stories['total'], $per_page_items, $start);

$filterString = array();
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
if ($filter_tag)
    $filterString[] = 'Tag: ' . $filter_tag;
if ($filter_start_date)
    $filterString[] = 'Start Date: ' . $filter_start_date;
if ($filter_end_date)
    $filterString[] = 'End Date: ' . $filter_end_date;

doAction('render_start');
?>
<div class="page-header">
    <h1>All Stories</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl . '?action=add_edit_story',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Story',
                'title' => 'New Story',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
echo formProcessor::form_elements('name', 'name', array(
    'width' => 2, 'type' => 'text', 'label' => 'Name',
        ), $filter_name);
echo formProcessor::form_elements('nid', 'nid', array(
    'width' => 2, 'type' => 'text', 'label' => 'NID',
        ), $filter_nid);
?>
<div class="form-group col-sm-2">
    <label>Entry Start Date</label>
    <div class="input-group">
        <input id="startDate" type="text" class="form-control" name="entry_start_date" value="<?php echo $filter_entry_start_date ?>">
    </div>
    <script type="text/javascript">
        init.push(function () {
            _datepicker('startDate');
        });
    </script>
</div>
<div class="form-group col-sm-2">
    <label>Entry End Date</label>
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
        <?php echo searchResultText($stories['total'], $start, $per_page_items, count($stories['data']), 'stories') ?>
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
            foreach ($stories['data'] as $i => $story) {
                ?>
                <tr>
                    <td><?php echo date('d-m-Y', strtotime($story['create_date'])) ?></td>
                    <td><?php echo $story['name']; ?></td>
                    <td><a href="<?php echo image_url($story['document_file']); ?>" target="_blank">Click Here</a></td>
                    <td class="tar action_column">
                        <?php if (has_permission('edit_story')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo linkButtonGenerator(array(
                                    'href' => build_url(array('action' => 'add_edit_story', 'edit' => $story['pk_knowledge_id'])),
                                    'action' => 'edit',
                                    'icon' => 'icon_edit',
                                    'text' => 'Edit',
                                    'title' => 'Edit Story',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_permission('delete_story')): ?>
                            <div class="btn-group btn-group-sm">
                                <?php
                                echo buttonButtonGenerator(array(
                                    'action' => 'delete',
                                    'icon' => 'icon_delete',
                                    'text' => 'Delete',
                                    'title' => 'Delete Record',
                                    'attributes' => array('data-id' => $story['pk_knowledge_id']),
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
                        window.location.href = '?action=deleteStory&id=' + logId;
                    }
                    hide_button_overlay_working(thisCell);
                }
            });
        });
    });
</script>