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
        
        $where = '';
        if(!empty($this->where)) {
            $where = "AND $this->where";
        }
        
        
        if(isset($_REQUEST['listflt']) && $_REQUEST['listflt'] == 'created') {
            
            $this->params['custom_where'] = " AND ( cases.created_by = '" . $current_user->id . "' AND cases.deleted = 0 $where)";
            
        } elseif(isset($_REQUEST['listflt']) && $_REQUEST['listflt'] == 'assigned') {
            
            $this->params['custom_where'] = " AND ( cases.assigned_user_id = '" . $current_user->id . "' AND cases.deleted = 0 $where)";
        } else {
            $this->params['custom_where'] = " AND (CASE WHEN $current_user->is_admin != 1 THEN cases.created_by = '" . $current_user->id . "' OR cases.assigned_user_id = '" . $current_user->id . "' OR cases.assigned_user_id = '' ELSE 1=1 END )";
        }
        

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
        $tabasdClass = '';
        $listflt = (isset($_REQUEST['listflt']))? $_REQUEST['listflt'] : '';
        
                
        if($listflt == 'created') {
            $tabcrtClass = 'active';
        } else if($listflt == 'assigned') { 
            $tabasdClass = 'active';
        }else if (isset($_REQUEST['current_query_by_page'])) {
            $formated_str = str_replace('&quot;', '"', $_REQUEST['current_query_by_page']);
            $current_query_by_page = json_decode($formated_str);
            if(isset($current_query_by_page->listflt) && $current_query_by_page->listflt == 'created') {
                $listflt = $current_query_by_page->listflt;
                $tabcrtClass = 'active';
            } else if(isset($current_query_by_page->listflt) && $current_query_by_page->listflt == 'assigned') {
                $listflt = $current_query_by_page->listflt;
                $tabasdClass = 'active';
            } else {
                $tabdefClass = 'active';
            }
        
        } else {
            $tabdefClass = 'active';
        }
        
        $javascript = <<<EOQ
                <script language='javascript'>
                    YAHOO.util.Event.onDOMReady(function(){
                    
                        var tabdefact = "$tabdefClass";
                        var tabcrtact = "$tabcrtClass";
                        var tabasdact = "$tabasdClass";
                        
//                        $('#listflt').remove();
//                
//                        $("<input>").attr({
//                            type: "text",
//                            id: "listflter",
//                            name: "listflter",
//                            value: "$listflt",
//                            style: "display:none"
//                        }).appendTo("#search_form");
                        
                
                        if($('#case_tab').length == 0) { 
                            $('.listViewBody').before('<ul id="case_tab"><li id="list_default_tab" class="$tabdefClass">My Cases</li><li id="list_created_tab" class="$tabcrtClass">Created by Me</li><li id="list_assigned_tab" class="$tabasdClass">Assigned to Me</li></ul>');
                        }
                                     
                        
                        $('#list_default_tab').on('click', function() {
                            if(tabdefact == '') {
                                $(this).addClass('active');
                                $('#list_created_tab').removeClass('active');
                                $('#list_assigned_tab').removeClass('active');
                                window.location.href = "$baseUrl/index.php?module=Cases&action=index&return_module=Cases&return_action=DetailView";
                            }
                        });      
                        $('#list_created_tab').on('click', function() {
                            if(tabcrtact == '') {
                                $('#list_default_tab').removeClass('active');
                                $('#list_assigned_tab').removeClass('active');
                                $(this).addClass('active');
                                window.location.href = "$baseUrl/index.php?module=Cases&action=index&return_module=Cases&return_action=DetailView&listflt=created";
                            }
                        });
                        $('#list_assigned_tab').on('click', function() {
                            if(tabasdact == '') {
                                $('#list_default_tab').removeClass('active');
                                $('#list_created_tab').removeClass('active');
                                $(this).addClass('active');
                                window.location.href = "$baseUrl/index.php?module=Cases&action=index&return_module=Cases&return_action=DetailView&listflt=assigned";
                            }
                        });
                    
                    });      
                    function loading_spinner() {
                        var _loadingBar;
                        _loadingBar = new YAHOO.widget.Panel("wait", { width:"240px", fixedcenter:true, close:false, draggable:false, modal:true, visible:false, effect:{effect:YAHOO.widget.ContainerEffect.FADE, duration:0.5} });

                        _loadingBar.setHeader("Please Wait");
                        _loadingBar.setBody("<img src=\"themes/SuiteP/images/loading.gif\"/>");
                        _loadingBar.render(document.body);
                        _loadingBar.show();
                    }
                </script>
EOQ;
        parent::display();
        echo $javascript; //Printing the javascript
    }

}

?>