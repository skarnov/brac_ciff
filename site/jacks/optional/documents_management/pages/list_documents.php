<?php

$tagManager = jack_obj('dev_tag_management');

if ($_POST['upload_documents']) {
    $thisDate = date('Y-m-d');
    $thisTime = date('H:i:s');
    $thisUser = $_config['user']['pk_user_id'];

    $fileHandler = new handle_file_upload(null, _path('contents', 'absolute') . '/documents', null);
    $totalRet = array('error' => array());
    if ($_FILES['doc']['name']) {
        foreach ($_FILES['doc']['name'] as $i => $orgFileName) {
            $dbFileName = isset($_POST['doc'][$i]['name']) && strlen($_POST['doc'][$i]['name']) ? $_POST['doc'][$i]['name'] : basename($orgFileName);
            $fileData = array(
                'name' => $orgFileName,
                'type' => $_FILES['doc']['type'][$i],
                'tmp_name' => $_FILES['doc']['tmp_name'][$i],
                'error' => $_FILES['doc']['error'][$i],
                'size' => $_FILES['doc']['size'][$i],
            );
            $ret = $fileHandler->upload_file($fileData, _path('contents', 'absolute') . '/documents');
            if ($ret['error']) {
                $totalRet['error'][] = 'File ' . $orgFileName . ' was not uploaded due to following reasons.';
                foreach ($ret['error'] as $e) {
                    $totalRet['error'][] = $e;
                }
            } else {
                $insertData = array(
                    'document_name' => $dbFileName,
                    'document_file' => $ret['success'],
                    'document_mime' => $ret['mime'],
                    'document_extension' => $ret['ext'],
                    'document_type' => $ret['type'],
                    'create_by' => $thisUser,
                    'create_date' => $thisDate,
                    'create_time' => $thisTime,
                );
                $insertNow = $devdb->insert_update('dev_documents', $insertData);
                if ($insertNow['success']) {
                    add_notification('File ' . $orgFileName . ' was uploaded successfully.');
                } else {
                    @unlink(_path('contents', 'absolute') . '/documents/' . $ret['success']);
                    $totalRet['error'][] = 'File ' . $orgFileName . ' failed to be uploaded';
                }
            }
        }
        if (!$totalRet['error']) {
            add_notification('All files has been uploaded successfully');
        } else {
            print_errors($totalRet['error']);
        }
        header('location: ' . current_url());
        exit();
    }
}

if ($_GET['delete']) {
    $ret = array('error' => array(), 'success' => array());
    $data = $this->get_documents(array('id' => $_GET['delete'], 'single' => true));
    if ($data) {
        if ($data['document_file'] && file_exists(_path('contents', 'absolute') . '/documents/' . $data['document_file']))
            @unlink(_path('contents', 'absolute') . '/documents/' . $data['document_file']);
        $sql = "DELETE FROM dev_documents WHERE pk_document_id = '" . $data['pk_document_id'] . "'";
        $deleted = $devdb->query($sql);
        if ($deleted)
            $ret['success'] = $deleted;
        else
            $ret['error'] = $deleted;
    } else {
        $ret['error'][] = 'No data found';
    }

    if ($ret['success']) {
        add_notification('Requested item has been deleted.');
    } else
        print_errors($ret['error']);

    header('Location: ' . build_url(null, array('delete')));
    exit();
}

$start = $_GET['start'] ? $_GET['start'] : 0;
$per_page_items = 50;

$filter_name = $_GET['name'] ? $_GET['name'] : null;
//$filter_type = $_GET['type'] ? $_GET['type'] : null;
//$filter_parent = $_GET['parent'] ? $_GET['parent'] : null;

$args = array(
    'name' => $filter_name,
    //'type' => $filter_type,
    //'parent' => $filter_parent,
    'limit' => array(
        'start' => $start * $per_page_items,
        'count' => $per_page_items
    ),
    'order_by' => array(
        'col' => 'pk_document_id',
        'order' => 'DESC'
    ),
);

$data = $this->get_documents($args);
$pagination = pagination($data['total'], $per_page_items, $start);

$filterString = array();
if ($filter_name)
    $filterString[] = 'Name: ' . $filter_name;
//if($filter_type) $filterString[] = 'Type: '.$filter_type;
//if($filter_parent) $filterString[] = 'Parent: '.$filter_parent;

$categories = $tagManager->get_tags(array('tag_group_slug' => 'document_category'));
$categories = $categories['data'];

doAction('render_start');
?>
<div class="page-header">
    <h1>Documents</h1>
    <div class="oh">
        <div class="btn-group btn-group-sm">
            <?php
            echo linkButtonGenerator(array(
                'id' => 'show_upload_form',
                'action' => 'upload',
                'icon' => 'icon_upload',
                'text' => 'Upload Documents',
                'title' => 'Upload New Documents',
            ));
            ?>
        </div>
    </div>
</div>
<div id="upload_form_container" style="display: none">
    <form name="document_upload_form" method="post" enctype="multipart/form-data">
        <div class="table-primary">
            <div class="table-header">
                <div class="row">
                    <div class="col-sm-3 form-group mb0">
                        <label>Document Category</label>
                        <select class="form-control" id="document_category" name="category">
                            <option value="">N/A</option>
                            <?php
                            if ($categories) {
                                foreach ($categories as $i => $v) {
                                    echo '<option value="' . $v['pk_tag_id'] . '">' . processToRender($v['tag_title']) . '</option>';
                                }
                            }
                            ?>
                            <option value="new_category">+ New category</option>
                        </select>
                    </div>
                    <div id="new_category_container" class="col-sm-3 form-group mb0" style="display: none;">
                        <label>Enter New Category Name</label>
                        <input type="text" class="form-control" name="new_category" />
                    </div>
                    <script type="text/javascript">
                        init.push(function () {
                            $('#document_category').change(function () {
                                if ($(this).val() == 'new_category')
                                    $('#new_category_container').slideDown();
                                else
                                    $('#new_category_container').slideUp();
                            });
                        });
                    </script>
                </div>
            </div>
            <table class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Name</th>
                        <th class="tar">...</th>
                    </tr>
                </thead>
                <tbody class="documents_list"></tbody>
            </table>
            <div class="table-footer">
                <?php
                echo linkButtonGenerator(array(
                    'id' => 'add_more_document',
                    'action' => 'add',
                    'icon' => 'icon_add',
                    'text' => 'Add Another File',
                    'title' => 'Add Another File',
                ));
                ?>
                <?php
                echo submitButtonGenerator(array(
                    'action' => 'upload',
                    'icon' => 'icon_upload',
                    'name' => 'upload_documents',
                    'value' => '1',
                    'text' => 'Upload Documents',
                    'title' => 'Upload Documents',
                ));
                ?>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    init.push(function () {
        $('#show_upload_form').click(function () {
            if ($('#upload_form_container').is(':visible'))
                $('#upload_form_container').slideUp();
            else
                $('#upload_form_container').slideDown();
        });
        var totalFiles = 0;

        function add_another_row() {
            var _html = '\
                        <tr>\
                            <td><input type="file" id="theFile_' + totalFiles + '" name="doc[' + totalFiles + ']" /></td>\
                            <td><input type="text" class="form-control" name="doc[' + totalFiles + '][name]" value="" /></td>\
                            <td class="tar vam"><a href="javascript:" class="btn btn-xs btn-danger remove_row"><i class="fa fa-trash"></i></a></td>\
                        </tr>\
                        ';
            $('.documents_list').append(_html);
            $('#theFile_' + totalFiles).pixelFileInput({
                placeholder: 'No file selected...'
            });
            ++totalFiles;
        }
        $('#add_more_document').click(function () {
            add_another_row();
        });
        $(document).on('click', '.remove_row', function () {
            $(this).closest('tr').remove();
        });
        add_another_row();
    });
</script>
<?php
ob_start();
?>
<?php
echo formProcessor::form_elements('name', 'name', array(
    'width' => 2, 'type' => 'text', 'label' => 'Name',
        ), $filter_name);
$filterForm = ob_get_clean();
filterForm($filterForm);
?>
<div class="table-primary table-responsive">
    <?php if ($filterString) : ?>
        <div class="table-header">
            Filtered With: <?php echo implode(', ', $filterString) ?>
        </div>
    <?php endif; ?>
    <div class="table-header">
        <?php echo searchResultText($data['total'], $start, $per_page_items, count($data['data']), 'documents') ?>
    </div>
    <table class="table table-bordered table-condensed table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Type</th>
                <th>Name</th>
                <th>Uploaded By</th>
                <th>Uploaded On</th>
                <th class="tar action_column">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = ($start * $per_page_items);
            foreach ($data['data'] as $i => $item) {
                ?>
                <tr>
                    <td><?php echo ++$count; ?></td>
                    <td><?php echo $item['document_type']; ?></td>
                    <td><?php echo $item['document_name']; ?></td>
                    <td><?php echo $item['uploader_name']; ?></td>
                    <td><?php echo $item['create_date'] . ' ' . date('H:i A', strtotime($item['create_time'])); ?></td>
                    <td class="tar action_column">
                        <?php if (has_permission('delete_document')) : ?>
                            <?php
                            echo linkButtonGenerator(array(
                                'classes' => 'delete_document',
                                'attributes' => array('rel' => build_url(array('delete' => $item['pk_document_id']))),
                                'action' => 'delete',
                                'icon' => 'icon_delete',
                                'text' => 'Delete',
                                'title' => 'Delete',
                            ));
                            ?>
                        <?php endif; ?>
                        <?php
                        echo linkButtonGenerator(array(
                            'href' => _path('contents') . '/documents/' . $item['document_file'],
                            'attributes' => array('target' => '_blank'),
                            'action' => 'view',
                            'icon' => 'icon_view',
                            'text' => 'View',
                            'title' => 'View This File',
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
        <div class="pull-left">
            <?php echo $pagination ?>
        </div>
    </div>
</div>
<div class="dn">
    <div id="ajax_form_container"></div>
</div>
<script type="text/javascript">
    init.push(function () {
        $(document).on('click', '.delete_document', function () {
            var ths = $(this);
            var to_url = ths.attr('rel');
            bootboxConfirm({
                title: 'Delete Document',
                msg: 'Do you really want to delete this document?',
                confirm: {
                    callback: function () {
                        window.location.href = to_url;
                    }
                },
            });
        });
    });
</script>