<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class aCustomCase extends aCase {
    
    public function __construct() 
    {
	parent::__construct();
    }
    
    function get_list_view_data(){
        global $current_language, $current_user;
        $app_list_strings = return_app_list_strings_language($current_language);

        $temp_array = $this->get_list_view_array();
        
        if(isset($temp_array['ASSIGNED_USER_NAME']) && $temp_array['ASSIGNED_USER_NAME'] == '') {
            $temp_array['ASSIGNED_USER_NAME'] = '<a href="#" style="color:#15c;" onclick="loading_spinner();saveFieldHTML(\'assigned_user_name\', \'Cases\', \''.$temp_array['ID'].'\', \''.$current_user->id.'\', \'\');saveFieldHTML(\'status\', \'Cases\', \''.$temp_array['ID'].'\', \'assigned\', \'\');saveFieldHTML(\'status_change_datetime_c\', \'Cases\', \''.$temp_array['ID'].'\', \''.$GLOBALS['timedate']->nowDb().'\', \'\'); location.reload(true);">Assign To Me</a>';
        } 

        return $temp_array;

	}
}
?>
