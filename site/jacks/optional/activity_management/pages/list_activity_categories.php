<?php

if ($_GET['ajax_type'] == 'get_new_activity_category_form') {
    $id = $_POST['id'] ? $_POST['id'] : null;
    echo json_encode($this->get_new_activity_category_form($id));
    exit();
} else if ($_GET['ajax_type'] == 'put_new_activity_category_form') {
    echo json_encode($this->put_new_activity_category_form($_POST));
    exit();
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$args = array(
    'parent' => $_GET['activity_group'] ? $_GET['activity_group'] : null,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_activity_cat_id',
        'order' => 'DESC'
    ),
);

$data = $this->get_acitivity_categories($args);
$pagination = pagination($data['total'], $per_page_items, $start);

$acitivity_categories = $this->get_acitivity_categories(array('parent_only' => true, 'data_only' => true));
$acitivity_categories = $acitivity_categories['data'];

doAction('render_start');
?>
<div class="page-header">
    <h1>All Activity Items</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'classes' => 'add_edit_item',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'Add New',
                'title' => 'Add New',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<div class="col-sm-4 form-group">
    <label>Group</label>
    <select class="form-control" name="activity_group">
        <option value="">Select One</option>
        <?php
        foreach ($acitivity_categories as $i => $v) {
            ?>
            <option value="<?php echo $v['pk_activity_cat_id'] ?>" <?php echo $_GET['activity_group'] && $_GET['activity_group'] == $v['pk_activity_cat_id'] ? 'selected' : '' ?>><?php echo $v['cat_name'] ?></option>
        <?php
    }
    ?>
    </select>
</div>
<?php
$filterForm = ob_get_clean();
filterForm($filterForm, array('action'));
?>
<div class="table-primary table-responsive">
    <div class="table-header">
        <?php echo searchResultText($data['total'], $start, $per_page_items, count($data['data']), 'activities') ?>
    </div>
    <table class="table table-bordered table-condensed table-striped table-hover">
        <thead>
            <tr>
                <th class="">#</th>
                <th class="">Group</th>
                <th class="">Activity</th>
                <th class="tar">...</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = $per_page_items * $start;
            foreach ($data['data'] as $i => $activity) {
                ?>
                <tr>
                    <td><?php echo ++$count; ?></td>
                    <td><?php echo $activity['parent_category_name']; ?></td>
                    <td><?php echo $activity['cat_name']; ?></td>
                    <td class="tar">
                        <?php
                        echo linkButtonGenerator(array(
                            'classes' => 'add_edit_item',
                            'attributes' => array('data-id' => $activity['pk_activity_cat_id']),
                            'action' => 'edit',
                            'icon' => 'icon_edit',
                            'text' => 'Update',
                            'title' => 'Update',
                        ));
                        ?>
                    </td>
                </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <div class="table-footer oh">
        <?php echo $pagination ?>
    </div>
</div>
<div class="dn">
    <div id="activity_category_form_container"></div>
</div>
<script type="text/javascript">

    init.push(function() {

        $(document).on('click', '.add_edit_item', function() {
            var ths = $(this);
            var data_id = ths.attr('data-id');
            var is_update = typeof data_id !== 'undefined' ? data_id : false;
            var thsRow = is_update ? ths.closest('tr') : null;


            new in_page_add_event({
                edit_form_success_callback: function(){
                    initCharLimit();
                    },
                edit_mode: true,
                edit_form_url: '<?php echo build_url(array('ajax_type' => 'get_new_activity_category_form')) ?>',
                submit_button: is_update ? 'UPDATE' : 'ADD',
                form_title: is_update ? 'Update Activity' : 'Add New Activity',
                form_container: $('#activity_category_form_container'),
                ths: ths,
                url: '<?php echo build_url(array('ajax_type' => 'put_new_activity_category_form')) ?>',
                additional_data : is_update ? {id: data_id} : {},
                callback: function(data) {
                    window.location.reload();
                }
            });
        });
    });
</script>