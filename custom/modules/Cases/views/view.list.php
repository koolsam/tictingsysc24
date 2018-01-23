<?php

require_once('include/MVC/View/views/view.list.php');
require_once('modules/Cases/CasesListViewSmarty.php');

class CasesViewList extends ViewList {

    function __construct() {
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    function CasesViewList() {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }

    function preDisplay() {
        $this->lv = new CasesListViewSmarty();
    }

    function listViewProcess() {
        global $current_user;

        $this->processSearchForm();

        if(isset($_GET['listflt']) && $_GET['listflt'] == 'created') {
            $this->params['custom_where'] = " AND (CASE WHEN $current_user->is_admin != 1 THEN cases.created_by = '" . $current_user->id . "' ELSE 1=1 END )";
        } else {
            $this->params['custom_where'] = " AND (CASE WHEN $current_user->is_admin != 1 THEN cases.assigned_user_id = '" . $current_user->id . "' ELSE 1=1 END )";
        }
        //$this->params['custom_where'] = " AND (CASE WHEN $current_user->is_admin != 1 THEN cases.created_by = '" . $current_user->id . "' OR cases.assigned_user_id = '" . $current_user->id . "' ELSE 1=1 END )";

        $this->lv->searchColumns = $this->searchForm->searchColumns;
        if (!$this->headers)
            return;
        if (empty($_REQUEST['search_form_only']) || $_REQUEST['search_form_only'] == false) {

            $tplFile = 'include/ListView/ListViewGeneric.tpl';
            $this->lv->setup($this->seed, $tplFile, $this->where, $this->params);
            echo $this->lv->display();
        }
    }

    function display() {
        
        global $sugar_config;
        $baseUrl = $sugar_config['site_url'];

        $tabdefClass = '';
        $tabcrtClass = '';
        if(isset($_GET['listflt']) && $_GET['listflt'] == 'created') {
            $tabcrtClass = 'active';
        } else {
            $tabdefClass = 'active';
        }
        
        $javascript = <<<EOQ
                <script language='javascript'>
                    YAHOO.util.Event.onDOMReady(function(){
                    
                        var tabact = "$tabdefClass";
                        $('.listViewBody').before('<ul id="case_tab"><li id="list_default_tab" class="$tabdefClass">One</li><li id="list_created_tab" class="$tabcrtClass">Two</li></ul>');
                                     
                        
                        $('#list_default_tab').on('click', function() {
                            if(tabact == '') {
                                $(this).addClass('active');
                                $('#list_created_tab').removeClass('active');
                                window.location.href = "$baseUrl/index.php?module=Cases&action=index&return_module=Cases&return_action=DetailView";
                            }
                        });      
                        $('#list_created_tab').on('click', function() {
                            if(tabact != '') {
                                $('#list_default_tab').removeClass('active');
                                $(this).addClass('active');
                                window.location.href = "$baseUrl/index.php?module=Cases&action=index&return_module=Cases&return_action=DetailView&listflt=created";
                            }
                        });      
                    
                    
                    });
                </script>
EOQ;
        parent::display();
        echo $javascript; //Printing the javascript
    }

}

?>