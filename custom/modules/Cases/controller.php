<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once('include/MVC/Controller/SugarController.php');
require_once('custom/modules/Cases/CustomCase.php');

//require_once('modules/Cases/CustomCase.php');

class CasesController extends SugarController {

    function __construct() {
        parent::__construct();
    }

    function loadBean() {
        $GLOBALS['log']->debug("Custom CRM Account Controller loadBean() executed");
        $this->bean = new aCustomCase();
        if (!empty($this->record)) {
            $GLOBALS['log']->debug("Custom CRM Account Controller loadBean() record not empty executed");
            $this->bean->retrieve($this->record);
            if ($this->bean)
                $GLOBALS['FOCUS'] = $this->bean;
        }
    }

    public function action_get_kb_articles() {
        global $mod_strings;
        global $app_list_strings;
        $search = trim($_POST['search']);

        $relevanceCalculation = "CASE WHEN name LIKE '$search' THEN 10 
                                ELSE 0 END + CASE WHEN name LIKE '%$search%' THEN 5 
                                ELSE 0 END + CASE WHEN description LIKE '%$search%' THEN 2 ELSE 0 END";

        $query = "SELECT id, $relevanceCalculation AS relevance FROM aok_knowledgebase 
                  WHERE deleted = '0' AND $relevanceCalculation > 0 ORDER BY relevance DESC";

        $offset = 0;
        $limit = 30;

        $result = $GLOBALS['db']->limitQuery($query, $offset, $limit);

        $echo = '<table>';
        $echo .= '<tr><th>' . $mod_strings['LBL_SUGGESTION_BOX_REL'] . '</th><th>' . $mod_strings['LBL_SUGGESTION_BOX_TITLE'] . '</th><th>' . $mod_strings['LBL_SUGGESTION_BOX_STATUS'] . '</th></tr>';
        $count = 1;
        while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
            $kb = BeanFactory::getBean('AOK_KnowledgeBase', $row['id']);
            $echo .= '<tr class="kb_article" data-id="' . $kb->id . '">';
            $echo .= '<td> &nbsp;' . $count . '</td>';
            $echo .= '<td>' . $kb->name . '</td>';
            $echo .= '<td>' . $app_list_strings['aok_status_list'][$kb->status] . '</td>';
            $echo .= '</tr>';
            $count++;
        }
        $echo .= '</table>';

        if ($count > 1) {
            echo $echo;
        } else {
            echo $mod_strings['LBL_NO_SUGGESTIONS'];
        }
        die();
    }

    public function action_get_kb_article() {
        global $mod_strings;

        $article_id = $_POST['article'];
        $article = new AOK_KnowledgeBase();
        $article->retrieve($article_id);

        echo '<span class="tool-tip-title"><strong>' . $mod_strings['LBL_TOOL_TIP_TITLE'] . '</strong>' . $article->name . '</span><br />';
        echo '<span class="tool-tip-title"><strong>' . $mod_strings['LBL_TOOL_TIP_BODY'] . '</strong></span>' . html_entity_decode($article->description);

        if (!$this->IsNullOrEmptyString($article->additional_info)) {
            echo '<hr id="tool-tip-separator">';
            echo '<span class="tool-tip-title"><strong>' . $mod_strings['LBL_TOOL_TIP_INFO'] . '</strong></span><p id="additional_info_p">' . $article->additional_info . '</p>';
            echo '<span class="tool-tip-title"><strong>' . $mod_strings['LBL_TOOL_TIP_USE'] . '</strong></span><br />';
            echo '<input id="use_resolution" name="use_resolution" class="button" type="button" value="' . $mod_strings['LBL_RESOLUTION_BUTTON'] . '" />';
        }

        die();
    }

    /**
     * Function for basic field validation (present and neither empty nor only white space
     * @param string $question
     * @return bool
     */
    private function IsNullOrEmptyString($question) {
        return (!isset($question) || trim($question) === '');
    }

    /* function action_listview() {

      require_once "custom/modules/Cases/CaseListView.php";
      $this->view = 'list';
      $this->bean = new CaseListView();
      $this->bean->create_new_list_query();
      } */

    function pre_save() {
        
        if (!empty($_POST['assigned_user_id']) && $_POST['assigned_user_id'] != $this->bean->assigned_user_id && $_POST['assigned_user_id'] != $GLOBALS['current_user']->id && empty($GLOBALS['sugar_config']['exclude_notifications'][$this->bean->module_dir])) {
            $this->bean->notify_on_save = FALSE;
        }
        $GLOBALS['log']->debug("SugarController:: performing pre_save.");
        
        require_once('include/SugarFields/SugarFieldHandler.php');
        
        $sfh = new SugarFieldHandler();
        
        foreach ($this->bean->field_defs as $field => $properties) {
            $type = !empty($properties['custom_type']) ? $properties['custom_type'] : $properties['type'];
            $sf = $sfh->getSugarField(ucfirst($type), true);
            if (isset($_POST[$field])) {
                if (is_array($_POST[$field]) && !empty($properties['isMultiSelect'])) {
                    if (empty($_POST[$field][0])) {
                        unset($_POST[$field][0]);
                    }
                    $_POST[$field] = encodeMultienumValue($_POST[$field]);
                }
                $this->bean->$field = $_POST[$field];
            } else if (!empty($properties['isMultiSelect']) && !isset($_POST[$field]) && isset($_POST[$field . '_multiselect'])) {
                $this->bean->$field = '';
            }
            if ($sf != null) {
                $sf->save($this->bean, $_POST, $field, $properties);
            }
        }
        
        //Auto assign group for new cases 
        if($this->bean->id == '') {
            
            $sql = "SELECT group_id, group_name FROM auto_assign_group_mapping WHERE field_value = '{$_POST['sub_category']}'";
            $result = $GLOBALS['db']->query($sql);
            $group_info = $GLOBALS['db']->fetchByAssoc($result);
            $this->bean->group_id_c = $group_info['group_id'];
            $this->bean->group_name_c = $group_info['group_name'];
            
        }
        //End Auto assign group
        
        //Set Status change datetime
        if($this->bean->status != $_POST['status']) {
            $this->bean->status_change_datetime_c = date('Y-m-d H:i:s');
        }
        //End status change datetime set
        
        

        foreach ($this->bean->relationship_fields as $field => $link) {
            if (!empty($_POST[$field])) {
                $this->bean->$field = $_POST[$field];
            }
        }
        if (!$this->bean->ACLAccess('save') && (!isset($_POST['update_text']) || $_POST['update_text'] == '')) {
            ACLController::displayNoAccess(true);
            sugar_cleanup(true);
        }
    }

}
