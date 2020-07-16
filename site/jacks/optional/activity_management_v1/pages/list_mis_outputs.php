<?php

if ($_GET['ajax_type'] == 'get_new_activity_group_form') {
    $id = $_POST['id'] ? $_POST['id'] : null;
    echo json_encode($this->get_new_activity_group_form($id));
    exit();
} else if ($_GET['ajax_type'] == 'put_new_activity_group_form') {
    echo json_encode($this->put_new_activity_group_form($_POST));
    exit();
}

$acitivity_categories = $this->get_acitivity_categories(array('deletion_status' => 'not_deleted', 'parent_only' => true, 'data_only' => true));
$acitivity_categories = $acitivity_categories['data'];

doAction('render_start');
?>
<div class="page-header">
    <h1>MIS Outputs</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'classes' => 'add_edit_item',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'New Output',
                'title' => 'New Output',
            ));
            ?>
        </div>
    </div>
</div>
<div class="table-primary table-responsive">
    <table class="table table-bordered table-condensed table-striped table-hover">
        <thead>
            <tr>
                <th class="">#</th>
                <th class="">Output</th>
                <th class="tar">...</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($acitivity_categories as $i => $output) {
                ?>
                <tr>
                    <td><?php echo ++$count; ?></td>
                    <td><?php echo $output['cat_name']; ?></td>
                    <td class="tar">
                        <?php
                        echo linkButtonGenerator(array(
                            'classes' => 'add_edit_item',
                            'attributes' => array('data-id' => $output['pk_activity_cat_id']),
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
                edit_form_url: '<?php echo build_url(array('ajax_type' => 'get_new_activity_group_form')) ?>',
                submit_button: is_update ? 'UPDATE' : 'ADD',
                form_title: is_update ? 'Update Output' : 'Add New Output',
                form_container: $('#activity_category_form_container'),
                ths: ths,
                url: '<?php echo build_url(array('ajax_type' => 'put_new_activity_group_form')) ?>',
                additional_data : is_update ? {id: data_id} : {},
                callback: function(data) {
                    window.location.reload();
                }
            });
        });
    });
</script>