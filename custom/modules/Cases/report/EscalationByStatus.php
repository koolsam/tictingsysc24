<?php

class EscalationMatrix {

    function getGroups() {

        $query = "select * "
                . "from securitygroups "
                . " where securitygroups.deleted = 0 ";
        $GLOBALS['log']->debug("SecuritySuite: get security group list: $query");
        return $result = $GLOBALS['db']->query($query);
    }

    function sendReminderForStatusNew($escalationNo = 1) {
        global $current_user;
        require_once('modules/EmailTemplates/EmailTemplate.php');

        $template_name = 'statusNewEscalate';
        $primary_email = array();

        if ($template_name != '') {
            $template = new EmailTemplate();
            $template->retrieve_by_string_fields(array('name' => $template_name, 'type' => 'email'));
            if (1 == 1 || isset($template->body_html) && $template->body_html != '') {

                $securityGroups = $this->getGroups();

                while (($row = $GLOBALS['db']->fetchByAssoc($securityGroups)) != null) {
                    $securityGroupObj = new SecurityGroup();
                    $securityGroupBean = $securityGroupObj->retrieve($row['id']);

                    $escWhere = '';
                    
                    if($escalationNo == 1) {
                        $escWhere = '(HOUR(TIMEDIFF(now(), cases.date_entered)) = 24 )';
                    } elseif ($escalationNo == 2) {
                        $escWhere = '(HOUR(TIMEDIFF(now(), cases.date_entered)) = 48 )';
                    } else {
                        $escWhere = '(HOUR(TIMEDIFF(now(), cases.date_entered)) = 60)';
                    }
                    
                    $query = "SELECT cases.id, cases.case_number, cases.status, CONCAT_WS(' ', cuser.first_name, cuser.last_name) as created_by_name, cases.date_entered, HOUR(TIMEDIFF(now(), date_entered)) AS hours_in_status "
                            . " FROM cases "
                            . " LEFT JOIN users cuser ON auser.id = cases.created_by"
                            . " WHERE cases.deleted = 0 and cases.status = 'new' "
                            . " AND $escWhere"
                            . " AND cases.group_id_c = '$bean->id' "
                            . " ORDER BY cases.number ASC ";
                    $GLOBALS['log']->debug("SecuritySuite: getMembers: $query");
                    $result = $GLOBALS['db']->query($query);

                    $caseList = '<table style="border-collapse: collapse;" border=1><tr><th>Case Number</th><th>Status</th><th>Created By</th><th>Created On</th><th>Hours In Status</th></tr>';

                    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
                        $caseList .= '<tr><td>' . $row['case_number'] . '</td><td>' . $row['status'] . '</td><td>' . $row['created_by_name'] . '</td><td>' . $row['date_created'] . '</td><td>' . $row['hours_in_status'] . '</td></tr>';
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
                }
            } else {
                
            }
        }
    }
    function sendReminderForStatusAssigned($escalationNo = 1) {
        global $current_user;
        require_once('modules/EmailTemplates/EmailTemplate.php');

        $template_name = 'statusAssignedEscalate';
        $primary_email = array();

        if ($template_name != '') {
            $template = new EmailTemplate();
            $template->retrieve_by_string_fields(array('name' => $template_name, 'type' => 'email'));
            if (1 == 1 || isset($template->body_html) && $template->body_html != '') {

                $securityGroups = $this->getGroups();

                while (($row = $GLOBALS['db']->fetchByAssoc($securityGroups)) != null) {
                    $securityGroupObj = new SecurityGroup();
                    $securityGroupBean = $securityGroupObj->retrieve($row['id']);
                    
                    $escWhere = '';
                    
                    if($escalationNo == 1) {
                        $escWhere = '(HOUR(TIMEDIFF(now(), cases.status_change_datetime_c)) = 24 )';
                    } elseif ($escalationNo == 2) {
                        $escWhere = '(HOUR(TIMEDIFF(now(), cases.status_change_datetime_c)) = 36 )';
                    } else {
                        $escWhere = '(HOUR(TIMEDIFF(now(), cases.status_change_datetime_c)) = 48 )';
                    }

                    $query = "SELECT cases.id, cases.case_number, cases.status, CONCAT_WS(' ', cuser.first_name, cuser.last_name) as created_by_name, cases.status_change_datetime_c, HOUR(TIMEDIFF(now(), date_entered)) AS hours_in_status "
                            . " FROM cases "
                            . " LEFT JOIN users cuser ON auser.id = cases.created_by"
                            . " WHERE cases.deleted = 0 and cases.status = 'assigned' "
                            . " AND $escWhere"
                            . " AND cases.group_id_c = '$bean->id' "
                            . " ORDER BY cases.number ASC ";
                    $GLOBALS['log']->debug("SecuritySuite: getMembers: $query");
                    $result = $GLOBALS['db']->query($query);

                    $caseList = '<table style="border-collapse: collapse;" border=1><tr><th>Case Number</th><th>Status</th><th>Created By</th><th>Status Changed On</th><th>Hours In Status</th></tr>';

                    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
                        $caseList .= '<tr><td>' . $row['case_number'] . '</td><td>' . $row['status'] . '</td><td>' . $row['created_by_name'] . '</td><td>' . $row['status_change_datetime_c'] . '</td><td>' . $row['hours_in_status'] . '</td></tr>';
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
                }
            } else {
                
            }
        }
    }
    function sendReminderForStatusAwaitingResponse($escalationNo = 1) {
        global $current_user;
        require_once('modules/EmailTemplates/EmailTemplate.php');

        $template_name = 'statusAwaitResponseEscalate';
        $primary_email = array();

        if ($template_name != '') {
            $template = new EmailTemplate();
            $template->retrieve_by_string_fields(array('name' => $template_name, 'type' => 'email'));
            if (1 == 1 || isset($template->body_html) && $template->body_html != '') {

                $securityGroups = $this->getGroups();

                while (($row = $GLOBALS['db']->fetchByAssoc($securityGroups)) != null) {
                    $securityGroupObj = new SecurityGroup();
                    $securityGroupBean = $securityGroupObj->retrieve($row['id']);
                    
                    $escWhere = '';
                    
                    if($escalationNo == 1) {
                        $escWhere = '(HOUR(TIMEDIFF(now(), cases.status_change_datetime_c)) = 12 )';
                    } else {
                        $escWhere = '(HOUR(TIMEDIFF(now(), cases.status_change_datetime_c)) = 24 )';
                    }

                    $query = "SELECT cases.id, cases.case_number, cases.status, CONCAT_WS(' ', cuser.first_name, cuser.last_name) as created_by_name, cases.status_change_datetime_c, HOUR(TIMEDIFF(now(), date_entered)) AS hours_in_status "
                            . " FROM cases "
                            . " LEFT JOIN users cuser ON auser.id = cases.created_by"
                            . " WHERE cases.deleted = 0 and cases.status = 'awaiting_response' "
                            . " AND $escWhere"
                            . " AND cases.group_id_c = '$bean->id' "
                            . " ORDER BY cases.number ASC ";
                    $GLOBALS['log']->debug("SecuritySuite: getMembers: $query");
                    $result = $GLOBALS['db']->query($query);

                    $caseList = '<table style="border-collapse: collapse;" border=1><tr><th>Case Number</th><th>Status</th><th>Created By</th><th>Status Changed On</th><th>Hours In Status</th></tr>';

                    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
                        $caseList .= '<tr><td>' . $row['case_number'] . '</td><td>' . $row['status'] . '</td><td>' . $row['created_by_name'] . '</td><td>' . $row['status_change_datetime_c'] . '</td><td>' . $row['hours_in_status'] . '</td></tr>';
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
                }
            } else {
                
            }
        }
    }

}

$remiderObj = new DailyReminder();
$remiderObj->groupWiseReminder();
