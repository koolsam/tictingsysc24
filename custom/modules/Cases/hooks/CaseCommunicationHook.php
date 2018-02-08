<?php

class CaseCommunicationHook {

    function caseCreationGroupEmail($bean, $event, $arguments) {
        global $current_user;
        
        if ($bean->assigned_user_id == '') {
            if ((isset($bean->fetched_rel_row['group_name_c']) && ($bean->fetched_rel_row['group_name_c'] != $bean->group_name_c)) || (!isset($bean->fetched_rel_row['group_name_c']))) {
                $this->sendEmailForAssignment($bean, 'Case Creation', 'group');
            }
        } else {
            if ((isset($bean->fetched_rel_row['assigned_user_name']) && $bean->fetched_rel_row['assigned_user_name'] != $bean->assigned_user_name) || (!isset($bean->fetched_rel_row['assigned_user_name']) && $current_user->id != $bean->assigned_user_id)) {
                $this->sendEmailForAssignment($bean, 'Case Creation', 'individual');
            }
        }
    }

    function sendEmailForAssignment($bean, $template_name, $mail_type) {
        global $current_user;
        require_once('modules/EmailTemplates/EmailTemplate.php');

        $primary_email = array($current_user->email1);

        if ($template_name != '') {
            $template = new EmailTemplate();
            $template->retrieve_by_string_fields(array('name' => $template_name, 'type' => 'email'));
            if (isset($template->body_html) && $template->body_html != '') {
                $caseObj = new aCase();
                $caseObj->retrieve($bean->id);  //Case ID

                if ($mail_type == 'group') {
                    $group_members = getGroupMembers($bean->group_id_c);

                    foreach ($group_members as $member) {
                        $role_obj = new ACLRole();
                        $role_arr = $role_obj->getUserRoles($member['id']);

                        if (!in_array('Manager', $role_arr)) {
                            $user = BeanFactory::getBean('Users', $member['id']);
                            $primary_email[] = $user->emailAddress->getPrimaryAddress($user);
                        }
                    }
                    $template->body_html = str_replace('$name_group_or_ind', 'group ' . $bean->group_name_c, $template->body_html);
                } else {
                    $user = BeanFactory::getBean('Users', $bean->assigned_user_id);
                    $primary_email[] = $user->emailAddress->getPrimaryAddress($user);
                    $template->body_html = str_replace('$name_group_or_ind', 'you', $template->body_html);
                }

                $template->body_html = str_replace('$acase_modified_by_name', $bean->modified_by_name, $template->body_html);
                $template->body_html = str_replace('$acase_assigned_user_name', $bean->assigned_user_name, $template->body_html);

                if (count($primary_email) > 0) {
                    $template->subject = $template->parse_template_bean($template->subject, $caseObj->module_dir, $caseObj);

                    $template->body_html = $template->parse_template_bean($template->body_html, $caseObj->module_dir, $caseObj);


                    $mailSend = sendEmail($primary_email, $template->subject, $template->body_html, $template->body, $bean);
                } else {
                    
                }
            } else {
                
            }
        }
    }

}
