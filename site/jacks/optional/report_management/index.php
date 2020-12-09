<?php

class dev_report_management {

    var $thsClass = 'dev_report_management';

    function __construct() {
        jack_register($this);
    }

    function init() {
        $permissions = array(
            'group_name' => 'Reports',
            'permissions' => array(
                'manage_reports' => array(
                    'view_report' => 'View Report',
                    'download_report' => 'Download Report',
                ),
            ),
        );

        if (!isPublic()) {
            register_permission($permissions);
            $this->adm_menus();
        }
    }

    function adm_menus() {
        $params = array(
            'label' => 'Reports',
            'iconClass' => 'fa-building',
            'jack' => $this->thsClass,
        );
        $params = array(
            'label' => 'Case Report',
            'description' => 'Generate Report By Selecting Case Modules',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'case_report',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('view_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Case Statistics',
            'description' => 'Case Statistics',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'case_statistics',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('view_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Observation Report',
            'description' => 'Activities Observation Report',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'observation_report',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('view_report'))
            admenu_register($params);

        $params = array(
            'label' => 'Event Validation',
            'description' => 'Event Validation Report',
            'menu_group' => 'Reports',
            'position' => 'top',
            'action' => 'event_validation',
            'iconClass' => 'fa-binoculars',
            'jack' => $this->thsClass,
        );
        if (has_permission('view_report'))
            admenu_register($params);
    }

    function case_report() {
        if (!has_permission('view_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'case_report');

        include('pages/case_report.php');
    }

    function case_statistics() {
        if (!has_permission('view_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'case_statistics');

        include('pages/case_statistics.php');
    }

    function observation_report() {
        if (!has_permission('view_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'observation_report');

        include('pages/observation_report.php');
    }

    function event_validation() {
        if (!has_permission('view_report'))
            return true;
        global $devdb, $_config;
        $myUrl = jack_url($this->thsClass, 'event_validation');

        include('pages/event_validation.php');
    }
}

new dev_report_management();