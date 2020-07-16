<?php
$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_branch = $_GET['branch_id'] ? $_GET['branch_id'] : null;
$filter_year = $_GET['year'] ? $_GET['year'] : null;

if (!$filter_year) {
    if (!$filter_year)
        $filter_year = date('Y');
    header('location:' . build_url(array('year' => $filter_year)));
    exit();
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 100;

$args = array(
    'project' => $filter_project,
    'branch' => $filter_branch,
    'year' => $filter_year,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
);

$targets = $this->get_mis_targets($args);
$pagination = pagination($targets['total'], $per_page_items, $start);

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects(array('single' => false));

$branches = jack_obj('dev_branch_management');
$all_branches = $branches->get_branches(array('branch_type_slug' => 'drsc', 'single' => false));
doAction('render_start');
?>
<div class="page-header">
    <h1>Targets</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => build_url(array('action' => 'add_targets')),
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'Set Targets',
                'title' => 'Set Targets',
            ));
            ?>
            <?php
            echo linkButtonGenerator(array(
                'id' => 'edit_targets',
                'action' => 'edit',
                'icon' => 'icon_edit',
                'text' => 'Update Targets',
                'title' => 'Update Targets',
            ));
            ?>
        </div>
    </div>
</div>
<div style="display: none" id="add_target_form_container">
    <div class="panel panel-info">
        <div class="panel-heading">
            <span class="panel-title">Update targets</span>
        </div>
        <form name="set_targets" action="" method="get">
            <input type="hidden" name="action" value="edit_targets">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-2 form-group">
                        <label>Project</label>
                        <select class="form-control" required name="project">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_projects['data'] as $i => $v) {
                                ?>
                                <option value="<?php echo $v['pk_project_id'] ?>"><?php echo $v['project_short_name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-2 form-group">
                        <label>Branch</label>
                        <select class="form-control" required name="branch">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_branches['data'] as $i => $v) {
                                ?>
                                <option value="<?php echo $v['pk_branch_id'] ?>"><?php echo $v['branch_name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-2 form-group">
                        <label>Year</label>
                        <input class="form-control" required type="number" name="year" value="" />
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" name="set_new_drsc_targets" class="btn btn-success" value="1">Continue</button>
                <a href="javascript:" id="cancel_add_target_form" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php
ob_start();
?>
<div class="col-sm-2 form-group">
    <label>Project</label>
    <select class="form-control" name="project_id">
        <option value="">Select One</option>
        <?php
        foreach ($all_projects['data'] as $i => $v) {
            ?>
            <option value="<?php echo $v['pk_project_id'] ?>" <?php if($filter_project == $v['pk_project_id']) echo 'selected' ?>><?php echo $v['project_short_name'] ?></option>
            <?php
        }
        ?>
    </select>
</div>
<div class="col-sm-2 form-group">
    <label>Branch</label>
    <select class="form-control" name="branch_id">
        <option value="">Select One</option>
        <?php
        foreach ($all_branches['data'] as $i => $v) {
            ?>
            <option value="<?php echo $v['pk_branch_id'] ?>" <?php if($filter_branch == $v['pk_branch_id']) echo 'selected' ?>><?php echo $v['branch_name'] ?></option>
            <?php
        }
        ?>
    </select>
</div>
<div class="col-sm-2 form-group">
    <label>Year</label>
    <input class="form-control" type="number" name="year" value="<?php echo $filter_year ?>" />
</div>
<?php
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="table-primary table-responsive">
    <div class="table-header">
        <?php echo searchResultText($targets['total'], $start, $per_page_items, count($targets['data']), 'targets') ?>
    </div>
    <table class="table table-bordered table-condensed table-striped table-hover">
        <thead>
            <tr>
                <th class="vam">#</th>
                <th class="vam">Project</th>
                <th class="vam">Branch</th>
                <th class="vam">Year</th>
                <th class="vam">Activity</th>
                <th class="tar">Target</th>
<!--                <th class="action_column">...</th>-->
            </tr>
        </thead>
        <tbody>
            <?php
            $count = $per_page_items * $start;
            foreach ($targets['data'] as $target) {
                ?>
                <tr>
                    <td><?php echo ++$count; ?></td>
                    <td><?php echo $target['project_name']; ?></td>
                    <td><?php echo $target['branch_name']; ?></td>
                    <td><?php echo $target['_year']; ?></td>
                    <td><?php echo $target['activity_name']; ?></td>
                    <td class="tar"><?php echo $target['_target']; ?></td>
<!--                    <td class="action_column">-->
<!--                        --><?php
//                        echo linkButtonGenerator(array(
//                            'classes' => 'edit_target',
//                            'attributes' => array('data-id' => $activity['pk_activity_id']),
//                            'action' => 'edit',
//                            'icon' => 'icon_edit',
//                            'text' => 'Update',
//                            'title' => 'Update',
//                        ));
//                        ?>
<!--                    </td>-->
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

<script type="text/javascript">
    var thisYear = <?php echo $filter_year ?>;

    init.push(function () {
        $('#edit_targets').on('click', function(){
            $('#add_target_form_container').slideDown();
        });
        $('#cancel_add_target_form').on('click', function(){
            $('#add_target_form_container').slideUp();
        });
//        $('.edit_target').on('click', function () {
//            var ths = $(this);
//            var data_id = ths.attr('data-id');
//            var is_update = typeof data_id !== 'undefined' ? data_id : false;
//            var thsRow = is_update ? ths.closest('tr') : null;
//
//            new in_page_add_event({
//                edit_mode: true,
//                edit_form_url: '<?php //echo build_url(array('ajax_type' => 'get_target_edit_form')) ?>//',
//                submit_button: 'Update',
//                form_title: 'Update Target',
//                form_container: $('#ajax_form_container'),
//                ths: ths,
//                url: '<?php //echo build_url(array('ajax_type' => 'put_target_edit_form')) ?>//',
//                additional_data: {
//                    id: data_id,
//                },
//                callback: function (data) {
//                    window.location.reload();
//                }
//            });
//        });
    });
</script>