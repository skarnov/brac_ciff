<?php
$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_parent = $_GET['parent'] ? $_GET['parent'] : null;

if ($_GET['ajax_type'] == 'get_new_activity_category_form') {
    $id = $_POST['id'] ? $_POST['id'] : null;
    echo json_encode($this->get_new_activity_category_form($id));
    exit();
} else if ($_GET['ajax_type'] == 'put_new_activity_category_form') {
    echo json_encode($this->put_new_activity_category_form($_POST));
    exit();
}

if ($_POST['ajax_type'] == 'select_output') {    
    $acitivity_categories = $this->get_acitivity_categories(array('deletion_status' => 'not_deleted', 'project_id' => $_POST['project_id'], 'parent_only' => true, 'data_only' => true));
    
    foreach ($acitivity_categories['data'] as $i => $v) {
        if ($_GET['parent'] == $v['pk_activity_cat_id']) {
            $selected = 'selected';
        }else{
            $selected = NULL;
        }
        echo "<option value=" . $v['pk_activity_cat_id'] . " $selected>" . $v['cat_name'] . "</option>";
    }
    exit();
}

if ($_POST['ajax_type'] == 'delete_single_activity') {
    $ret = array('success' => array(), 'error' => array());
    $item = trim($_POST['item_id']);
    if (strlen($item)) {
        $ret2 = $devdb->query("UPDATE dev_acitivites_categories SET deletion_status = 'deleted' WHERE pk_activity_cat_id = '" . $item . "'");
        if ($ret2)
            $ret['success'] = 1;
        else
            $ret['error'][] = 'Could not delete the Item.';
    } else
        $ret['error'][] = 'Invalid Item.';
    echo json_encode($ret);
    exit();
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 10;

$args = array(
    'child_only' => false,
    'deletion_status' => 'not_deleted',
    'project_id' => $filter_project,
    
    'parent' => $filter_parent,
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

$projects = "SELECT * FROM dev_projects";
$all_projects = $devdb->get_results($projects, 'pk_project_id');

doAction('render_start');
?>
<div class="page-header">
    <h1>MIS Outputs/Activities</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'classes' => 'add_edit_item',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Output/Activitie',
                'title' => 'New Output/Activitie',
            ));
            ?>
        </div>
    </div>
</div>
<?php
ob_start();
?>
<div class="col-sm-4 form-group">
    <label>Project</label>
    <select class="form-control" data-ajax-type="select_output" name="project_id">
        <option value="">Select One</option>
        <?php
        foreach ($all_projects as $project) {
            ?>
            <option value="<?php echo $project['pk_project_id'] ?>" <?php echo $_GET['project_id'] && $_GET['project_id'] == $project['pk_project_id'] ? 'selected' : '' ?>><?php echo $project['project_short_name'] ?></option>
            <?php
        }
        ?>
    </select>
</div>
<div class="col-sm-8 form-group">
    <label>Output</label>
    <select class="form-control" data-selected="<?php echo $_GET['parent'] ? $_GET['parent'] : ''; ?>" id="availableOutputs" name="parent">

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
                <th class="">Project</th>
                <?php if (!$_GET['activity_group']): ?>
                    <th class="">Output</th>
                <?php endif; ?>
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
                    <td><?php echo $all_projects[$activity['fk_project_id']]['project_short_name']; ?></td>
                    <?php if (!$_GET['activity_group']): ?>
                        <td><?php echo $activity['parent_category_name']; ?></td>
                    <?php endif; ?>
                    <td><?php echo $activity['cat_name']; ?></td>
                    <td class="tar action_column">
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
                        <?php
                        echo buttonButtonGenerator(array(
                            'action' => 'delete',
                            'icon' => 'icon_delete',
                            'text' => 'Delete',
                            'title' => 'Delete Activity',
                            'attributes' => array('data-id' => $activity['pk_activity_cat_id']),
                            'classes' => 'delete_single_activity'))
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
    init.push(function () {
        $("select[name='project_id']").change(function () {
            $('#availableOutputs').html('');
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    data: {
                        'project_id': stateID,
                        'ajax_type': 'select_output'
                    },
                    beforeSend: function () {
                        $('#availableOutputs').html('<option value="">Loading...</option>');
                    },
                    success: function (data) {
                        console.log(data);
                        $('#availableOutputs').html(data);
                    }
                });
            }
        }).change();

        $(document).on('click', '.add_edit_item', function () {
            var ths = $(this);
            var data_id = ths.attr('data-id');
            var is_update = typeof data_id !== 'undefined' ? data_id : false;
            var thsRow = is_update ? ths.closest('tr') : null;

            new in_page_add_event({
                edit_form_success_callback: function () {
                    initCharLimit();
                },
                edit_mode: true,
                edit_form_url: '<?php echo build_url(array('ajax_type' => 'get_new_activity_category_form')) ?>',
                submit_button: is_update ? 'UPDATE' : 'ADD',
                form_title: is_update ? 'Update Activity' : 'Add New Activity/Output',
                form_container: $('#activity_category_form_container'),
                ths: ths,
                url: '<?php echo build_url(array('ajax_type' => 'put_new_activity_category_form')) ?>',
                additional_data: is_update ? {id: data_id} : {},
                callback: function (data) {
                    window.location.reload();
                }
            });
        });
    });
</script>
<!-- Delete -->
<script type="text/javascript">
    init.push(function () {
        $(document).on('click', '.delete_single_activity', function () {
            var ths = $(this);
            var thisCell = ths.closest('td');
            var thisRow = ths.closest('tr');
            var itemId = ths.attr('data-id');
            if (!itemId)
                return false;

            show_button_overlay_working(thisCell);
            bootboxConfirm({
                title: 'Delete',
                msg: 'Do you really want to delete this Item?',
                confirm: {
                    callback: function () {
                        basicAjaxCall({
                            url: _current_url_,
                            data: {
                                ajax_type: 'delete_single_activity',
                                item_id: itemId,
                            },
                            success: function (ret) {
                                if (ret.success) {
                                    thisRow.slideUp('slow').remove();
                                    $.growl.notice({message: 'Item deleted.'});
                                } else
                                    growl_error(ret.error);
                            }
                        });
                    }
                },
                cancel: {
                    callback: function () {
                        hide_button_overlay_working(thisCell);
                    }
                }
            });
        });
    });
</script>