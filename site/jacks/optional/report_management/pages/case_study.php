<?php

$filter_customer_id = $_GET['customer_id'] ? $_GET['customer_id'] : null;

if ($filter_customer_id) {
    $data = array(
        'select_fields' => array(
            'customer_id',
            'full_name',
            'customer_type',
            'customer_status',
            'pk_customer_id'
        ),
        'required' => array(
            'customer_id' => 'Customer ID',
        ),
    );
    $data['customer_id'] = $filter_customer_id;
    $result = $this->get_case_study($data);
}

doAction('render_start');
?>
<div class="page-header">
    <h1>Case Study</h1>
</div>
<div class="row">
    <div class="col-md-4">
        <form id="theForm" onsubmit="return true;" method="get" action="">
            <div class="panel">
                <div class="panel-body">
                    <div class="form-group">
                        <label>Enter Customer ID</label>
                        <input class="form-control" type="text" name="customer_id" value="<?php echo $filter_customer_id ? $filter_customer_id : ''; ?>" required="">
                    </div>
                </div>
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
        </form>
    </div>
    <div class="col-md-8">
        <div class="table-primary table-responsive">
            <div class="table-header">
                Search Results
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="tar action_column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result['data'] == null) {
                        ?>
                        <tr>
                            <td style="color:red" colspan="6">Not Found</td>
                        </tr>
                        <?php
                    } else {
                        foreach ($result['data'] as $i => $customer) {
                            ?>
                            <tr>
                                <td><?php echo $customer['customer_id'] ?></td>
                                <td><?php echo $customer['full_name'] ?></td>
                                <td style="text-transform: capitalize"><?php echo $customer['customer_type'] ?></td>
                                <td style="text-transform: capitalize"><?php echo $customer['customer_status'] ?></td>
                                <td class="tar action_column">
                                    <div class="btn-toolbar">
                                        <?php
                                        echo linkButtonGenerator(array(
                                            'href' => build_url(array('action' => 'download_case_study', 'id' => $customer['pk_customer_id'])),
                                            'attributes' => array(
                                                'target' => '_blank'
                                            ),
                                            'action' => 'download',
                                            'icon' => 'icon_download',
                                            'text' => 'Download',
                                            'title' => 'Download',
                                        ));
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div class="table-footer oh">
                <?php echo $pagination ?>
            </div>
        </div>
    </div>
</div>