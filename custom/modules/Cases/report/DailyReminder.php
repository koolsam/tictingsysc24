<?php

class DailyReminder {
    
    function groupWiseReminder() {
        
        $query = "select * "
            . "from securitygroups "
            . " where securitygroups.deleted = 0 ";
        $GLOBALS['log']->debug("SecuritySuite: get security group list: $query");
        $result = $GLOBALS['db']->query($query);
        
        while (($row = $GLOBALS['db']->fetchByAssoc($result)) != null) {
            $securityGroupObj = new SecurityGroup();
            $securityGroupBean = $securityGroupObj->retrieve($row['id']);
            $this->sendReminderEmail($securityGroupBean, 'ReminderEmail');
        }
        
        
    }
            
    function sendReminderEmail($bean, $template_name) {
        global $current_user;
        require_once('modules/EmailTemplates/EmailTemplate.php');

        $primary_email = array($current_user->email1);

        if ($template_name != '') {
            $template = new EmailTemplate();
            $template->retrieve_by_string_fields(array('name' => $template_name, 'type' => 'email'));
            if (1==1 || isset($template->body_html) && $template->body_html != '') {
                
                               
                $query = "select cases.id, cases.case_number, cases.status, CONCAT_WS(' ', auser.first_name, auser.last_name) as assigned_user_name "
                . " from cases "
                . " LEFT JOIN users auser ON auser.id = cases.assigned_user_id"
                . " where cases.deleted = 0 and cases.status != 'closed' "
                . "  and cases.group_id_c = '$bean->id' "
                . " order by cases.status asc ";
                $GLOBALS['log']->debug("SecuritySuite: getMembers: $query");
                $result = $GLOBALS['db']->query($query);
                
                $caseList = '<table style="border-collapse: collapse;" border=1><tr><th>Case Number</th><th>Status</th><th>Assign To</th></tr>';
                
                while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
                    $caseList .= '<tr><td>'.$row['case_number'].'</td><td>'.$row['status'].'</td><td>'.$row['assigned_user_name'].'</td></tr>';
                }
                
                $caseList .= '</table>';
                
                $group_members = getGroupMembers($bean->id);

                foreach ($group_members as $member) {
                    $role_obj = new ACLRole();
                    $role_arr = $role_obj->getUserRoles($member['id']);

                    if (!in_array('Manager', $role_arr)) {
                        $user = BeanFactory::getBean('Users', $member['id']);
                        $primary_email[] = $user->emailAddress->getPrimaryAddress($user);
                    }
                }
                
                $primary_email = array_unique($primary_email);
                
                $template->body_html = str_replace('$list_of_cases', $caseList, $template->body_html);

                
                if (count($primary_email) > 0) {
                    $template->subject = $template->parse_template_bean($template->subject, $bean->module_dir, $bean);

                    $template->body_html = $template->parse_template_bean($template->body_html, $bean->module_dir, $bean);


                    $mailSend = sendEmail($primary_email, $template->subject, $template->body_html, $template->body, $bean);
                } else {
                    
                }
            } else {
                
            }
        }
    }
}

$remiderObj = new DailyReminder();
$remiderObj->groupWiseReminder();