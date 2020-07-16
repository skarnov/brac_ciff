<?php
$months = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december', 'total');

$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_drsc = $_GET['drsc'] ? $_GET['drsc'] : null;
$filter_ulo = $_GET['ulo'] ? $_GET['ulo'] : null;
$filter_year = $_GET['year'] ? $_GET['year'] : null;

$isDrscUser = false;

if($staffManager->isStaff() && $myBranchType == 'drsc'){
    $filter_drsc = $_config['user']['user_branch'];
    $isDrscUser = true;
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 100;

$args = array(
    'only_set_distributions' => true,
    'project' => $filter_project,
    'drsc' => $filter_drsc,
    'ulo' => $filter_ulo,
    'year' => $filter_year,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
);

$distributions = $this->get_mis_distributions($args);
$pagination = pagination($distributions['total'], $per_page_items, $start);

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects(array('single' => false));

$branches = jack_obj('dev_branch_management');

$all_drsc = $branches->get_branches(array('branch_type_slug' => 'drsc', 'single' => false));
$all_ulo = $branches->get_branches(array('branch_type_slug' => 'ulo', 'parent_branch' => $filter_drsc, 'single' => false));

doAction('render_start');
?>
<div class="page-header">
    <h1>Target Distributions</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'id' => 'add_distributions',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'Set/Update Targets Distribution',
                'title' => 'Set/Update Targets Distribution',
            ));
            ?>
        </div>
    </div>
</div>
<div style="display: none" id="add_target_form_container">
    <div class="panel panel-info">
        <div class="panel-heading">
            <span class="panel-title">Set/Update targets distribution</span>
        </div>
        <form name="set_targets" action="<?php echo build_url(array('action' => 'add_edit_target_distribution')); ?>" method="post">
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
                    <?php if(!$isDrscUser): ?>
                        <div class="col-sm-2 form-group">
                            <label>DRSC</label>
                            <select class="form-control" required name="drsc">
                                <option value="">Select One</option>
                                <?php
                                foreach ($all_drsc['data'] as $i => $v) {
                                    ?>
                                    <option value="<?php echo $v['pk_branch_id'] ?>"><?php echo $v['branch_name'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="col-sm-2 form-group">
                        <label>ULO</label>
                        <select class="form-control" required name="ulo">
                            <option value="">Select One</option>
                            <?php
                            foreach ($all_ulo['data'] as $i => $v) {
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
<?php if(!$isDrscUser): ?>
<div class="col-sm-2 form-group">
    <label>DRSC</label>
    <select class="form-control" name="drsc">
        <option value="">Select One</option>
        <?php
        foreach ($all_drsc['data'] as $i => $v) {
            ?>
            <option value="<?php echo $v['pk_branch_id'] ?>" <?php if($filter_drsc == $v['pk_branch_id']) echo 'selected' ?>><?php echo $v['branch_name'] ?></option>
            <?php
        }
        ?>
    </select>
</div>
<?php endif; ?>
<div class="col-sm-2 form-group">
    <label>ULO</label>
    <select class="form-control" name="ulo">
        <option value="">Select One</option>
        <?php
        foreach ($all_ulo['data'] as $i => $v) {
            ?>
            <option value="<?php echo $v['pk_branch_id'] ?>" <?php if($filter_ulo == $v['pk_branch_id']) echo 'selected' ?>><?php echo $v['branch_name'] ?></option>
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
        <?php echo searchResultText($distributions['total'], $start, $per_page_items, count($distributions['data']), 'target distributions') ?>
    </div>
    <table class="large_tables table table-bordered table-condensed table-striped table-hover">
        <thead>
            <tr>
                <th class="vam">#</th>
                <th class="vam">Project</th>
                <th class="vam">DRSC</th>
                <th class="vam">ULO</th>
                <th class="vam">Activity</th>
                <th class="vam">Year</th>
                <th class="tar">January</th>
                <th class="tar">February</th>
                <th class="tar">March</th>
                <th class="tar">April</th>
                <th class="tar">May</th>
                <th class="tar">June</th>
                <th class="tar">July</th>
                <th class="tar">August</th>
                <th class="tar">September</th>
                <th class="tar">October</th>
                <th class="tar">November</th>
                <th class="tar">December</th>
                <th class="tar">Total</th>
<!--                <th class="action_column">...</th>-->
            </tr>
        </thead>
        <tbody>
            <?php
            $count = $per_page_items * $start;
            foreach ($distributions['data'] as $d) {
                ?>
                <tr>
                    <td><?php echo ++$count; ?></td>
                    <td><?php echo $d['project_name']; ?></td>
                    <td><?php echo $d['drsc_name']; ?></td>
                    <td><?php echo $d['ulo_name']; ?></td>
                    <td><?php echo $d['activity_name']; ?></td>
                    <td><?php echo $d['_year']; ?></td>
                    <?php
                    foreach($months as $m){
                        ?>
                        <td class="tar"><?php echo $d[$m.'_target']; ?></td>
                        <?php
                    }
                    ?>
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


    init.push(function () {
        $('#add_distributions').on('click', function(){
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