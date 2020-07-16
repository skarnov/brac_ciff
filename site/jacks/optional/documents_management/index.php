<?php

class dev_documents_management {

    var $thsClass = 'dev_documents_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Documents Management',
            'permissions' => array(
                'manage_documents' => array(
                    'upload_document' => 'Upload Document',
                    'download_document' => 'Download Document',
                    'delete_document' => 'Delete Document',
                ),
            ),
        );

        if (!isPublic()) {
            register_permission($permissions);
            $this->adm_menus();
        }

        if (!file_exists(_path('contents', 'absolute') . '/documents')) {
            @mkdir(_path('contents', 'absolute') . '/documents');
        }
    }

    function adm_menus() {
        $params = array(
            'label' => 'Documents',
            'description' => 'Manage All Documents',
            'menu_group' => 'Administration',
            'position' => 'default',
            'action' => 'manage_documents',
            'iconClass' => 'fa-file',
            'jack' => $this->thsClass,
        );
        if (has_permission('manage_documents'))
            admenu_register($params);
    }

    function manage_documents() {
        if (!has_permission('manage_documents'))
            return null;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'manage_documents');

        include('pages/list_documents.php');
    }

    function get_documents($param = null) {
        $param['single'] = $param['single'] ? $param['single'] : false;

        $select = "SELECT " . ($param['select_fields'] ? implode(", ", $param['select_fields']) . " " : 'documents.*,  uploader.user_fullname as uploader_name');
        $from = " FROM dev_documents AS documents LEFT JOIN dev_users AS uploader ON (documents.create_by=uploader.pk_user_id)";
        $where = "WHERE 1 ";
        $conditions = " ";
        $sql = $select . $from . $where;
        $count_sql = "SELECT COUNT(documents.pk_document_id) AS TOTAL " . $from . $where;

        $loopCondition = array(
            'id' => 'documents.pk_document_id',
            'name' => 'documents.document_name',
            'category' => 'documents.fk_category_id',
        );

        $conditions .= sql_condition_maker($loopCondition, $param);

        $orderBy = sql_order_by($param);
        $limitBy = sql_limit_by($param);

        $sql .= $conditions . $orderBy . $limitBy;
        $count_sql .= $conditions;

        $customers = sql_data_collector($sql, $count_sql, $param);
        return $customers;
    }

}

new dev_documents_management();
