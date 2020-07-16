<?php
$months = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december', 'total');

$filter_project = $_GET['project_id'] ? $_GET['project_id'] : null;
$filter_drsc = $_GET['drsc'] ? $_GET['drsc'] : null;
$filter_ulo = $_GET['ulo'] ? $_GET['ulo'] : null;
$filter_year = $_GET['year'] ? $_GET['year'] : null;

$isDrscUser = false;
$isUloUser = false;

$staffManager = jack_obj('dev_staff_management');
$myBranchType = $staffManager->getMyBranchType();

if($staffManager->isStaff() && $myBranchType == 'drsc'){
    $filter_drsc = $_config['user']['user_branch'];
    $isDrscUser = true;
}

if($staffManager->isStaff() && $myBranchType == 'ulo'){
    $myBranch = $staffManager->getMyBranch();
    $filter_drsc = $myBranch['fk_branch_id'];
    $filter_ulo = $_config['user']['user_branch'];
    $isUloUser = true;
}

$ignoreDrscSelection = $isUloUser ? true : ($isDrscUser ? true : false);
$ignoreUloSelection = $isUloUser ? true : false;

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

$achievements = $this->get_mis_distributions($args);
$pagination = pagination($achievements['total'], $per_page_items, $start);

$projects = jack_obj('dev_project_management');
$all_projects = $projects->get_projects(array('single' => false));

$branches = jack_obj('dev_branch_management');

$all_drsc = $branches->get_branches(array('branch_type_slug' => 'drsc', 'single' => false));
$all_ulo = $branches->get_branches(array('branch_type_slug' => 'ulo', 'parent_branch' => $filter_drsc, 'single' => false));

doAction('render_start');
?>
<div class="page-header">
    <h1>Target Achievements</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'id' => 'add_distributions',
                'action' => 'add',
                'icon' => 'icon_add',
                'text' => 'Set/Update Achievements',
                'title' => 'Set/Update Achievements',
            ));
            ?>
        </div>
    </div>
</div>
<div style="display: none" id="add_target_form_container">
    <div class="panel panel-info">
        <div class="panel-heading">
            <span class="panel-title">Set/Update Achievements</span>
        </div>
        <form name="set_targets" action="<?php echo build_url(array('action' => 'add_edit_target_achievement')); ?>" method="post">
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
                    <?php if(!$ignoreDrscSelection): ?>
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

                    <?php if(!$ignoreUloSelection): ?>
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
                    <?php endif; ?>

                    <div class="col-sm-2 form-group">
                        <label>Year</label>
                        <input class="form-control" required type="number" name="year" value="" />
                    </div>
                    <div class="col-sm-2 form-group">
                        <label>Month</label>
                        <select class="form-control" name="month" required>
                            <?php
                            foreach($this->months as $i=>$v){
                                echo '<option value="'.strtolower($v).'">'.$v.'</option>';
                            }
                            ?>
                        </select>
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
<?php if(!$ignoreDrscSelection): ?>
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
<?php if(!$ignoreDrscSelection): ?>
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
<?php endif; ?>
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
        <?php echo searchResultText($achievements['total'], $start, $per_page_items, count($achievements['data']), 'target achievements') ?>
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
            </tr>
        </thead>
        <tbody>
            <?php
            $count = $per_page_items * $start;
            foreach ($achievements['data'] as $d) {          
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
                        <td class="tar">
                            <div style="color: #696969;font-size: 9px"><?php echo $d[$m.'_target']; ?></div>
                            <div style="color: <?php echo $d[$m.'_achievement'] >= $d[$m.'_target'] ? '#1d9007' : '#e42727' ?>"><?php echo $d[$m.'_achievement']; ?></div>
                        </td>
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
    });
</script>