<?php
$id = $_GET['id'] ? $_GET['id'] : null;

if (!has_permission('manage_supports')) {
    add_notification('You don\'t have enough permission.', 'error');
    header('Location:' . build_url(NULL, array('edit','action')));
    exit();
}

$pre_data = array();
if ($id) {
    $pre_data = $this->get_supports(array('id' => $id, 'select_fields' => array(
            'dev_supports.pk_support_id',
            'dev_supports.fk_support_id',
            'dev_supports.fk_customer_id',
            'dev_supports.support_name',
            'dev_customers.full_name',
            'dev_supports.support_status',
            'dev_supports.start_date',
            'dev_supports.end_date',
        ), 'single' => true));
    if ($pre_data['support_name'] == 'social') {
        $baseline_evaluations = $this->get_social_evaluation(array('customer_id' => $pre_data['fk_customer_id'], 'support_id' => 0, 'single' => false));
        $all_social_evaluations = $this->get_social_evaluation(array('support_id' => $pre_data['fk_support_id'], 'single' => false));
    }
    if (!$pre_data) {
        add_notification('Invalid review support, no data found.', 'error');
        header('Location:' . build_url(NULL, array('action', 'edit')));
        exit();
    }
}

doAction('render_start');
?>
<style type="text/css">
    .removeReadOnly{
        cursor: pointer;
    }
</style>
<div class="page-header">
    <h1><?php echo $edit ? 'Update ' : '' ?>Support Review</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'href' => $myUrl,
                'action' => 'list',
                'text' => 'All Supports',
                'title' => 'Manage Supports',
                'icon' => 'icon_list',
                'size' => 'sm'
            ));
            ?>
        </div>
    </div>
</div>
<form id="theForm" onsubmit="return true;" method="post" action="" enctype="multipart/form-data">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3">
                    <p><b>Customer Name:</b> <span style="text-transform: capitalize;"><?php echo $pre_data['full_name'] ?></span></p>
                    <p><b>Support Name:</b> <span style="text-transform: capitalize;"><?php echo $pre_data['support_name'] ?></span> Reintegration Support</p>
                    <p><b>Status:</b> <span style="text-transform: capitalize;"><?php echo $pre_data['support_status'] ?></span></p>
                    <p><b>Start Date:</b> <span style="text-transform: capitalize;"><?php echo $pre_data['start_date'] ?></span></p>
                    <p><b>End Date:</b> <span style="text-transform: capitalize;"><?php echo $pre_data['end_date'] ?></span></p>
                </div>
                <div class="col-md-9">
                    <table class="table table-bordered">
                        <thead>
                        <th>Date</th>
                        <th>Score</th>
                        <th>Remarks</th>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($baseline_evaluations['data'] as $social_evaluation) {
                                ?>
                                <tr>
                                    <td><?php echo $social_evaluation['entry_date'] ?></td>
                                    <td><?php echo $social_evaluation['evaluated_score'] ?></td>
                                    <td><?php echo $social_evaluation['review_remarks'] ?></td>
                                </tr>
                                <?php
                            }
                            foreach ($all_social_evaluations['data'] as $social_evaluation) {
                                ?>
                                <tr>
                                    <td><?php echo $social_evaluation['entry_date'] ?></td>
                                    <td><?php echo $social_evaluation['evaluated_score'] ?></td>
                                    <td><?php echo $social_evaluation['review_remarks'] ?></td>
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
</form>