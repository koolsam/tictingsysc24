<?php

/**
 * Return a list of all members of a group
 * @param string $groupId id of a group
 * @return Array an array of users info related to this group
 */
function getGroupMembers($groupId) {
    global $db;
    $user_array = Array();

    if ($groupId != '') {
        $query = "select users.id, users.user_name, users.first_name, users.last_name "
                . "from securitygroups "
                . "inner join securitygroups_users on securitygroups.id = securitygroups_users.securitygroup_id "
                . " and securitygroups_users.deleted = 0 "
                . "inner join users on securitygroups_users.user_id = users.id and users.deleted = 0 "
                . " where securitygroups.deleted = 0 and users.employee_status = 'Active' "
                . "  and securitygroups.id = '$groupId' "
                . " order by users.user_name asc ";
        $GLOBALS['log']->debug("SecuritySuite: getMembers: $query");
        $result = $db->query($query);
        while (($row = $db->fetchByAssoc($result)) != null) {
            $user_array[$row['id']] = $row;
        }
    }
    return $user_array;
}

/**
 * Sends an email and archives it inside Sugar
 *
 * @param array $emailTo
 * @param string $emailSubject
 * @param string $emailBody
 * @param object SugarBean of record to related email to ( optional )
 */
function sendEmail($emailTo, $emailSubject, $emailBodyHtml, $emailBody, SugarBean $relatedBean = null, $emailCc = '', $emailBcc = '', $emailFrom = '') {
    global $current_user;

    if ((is_array($emailTo) && count($emailTo) > 0) || (!is_array($emailTo) && $emailTo != '')) {
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = ($emailFrom != '') ? $emailFrom : $defaults['email'];
        $mail->FromName = ($emailFrom != '') ? $emailFrom : $defaults['name'];
        $mail->ClearAllRecipients();
        $mail->ClearReplyTos();
        $mail->Subject = from_html($emailSubject);
        //$mail->Body=from_html($emailBody);
        $mail->Body = $emailBodyHtml;
        $mail->AltBody = $emailBody;
        $mail->prepForOutbound();
        if (is_array($emailTo) && count($emailTo) > 0) {
            foreach ($emailTo as $tokey => $toval) {
                $mail->AddAddress($toval);
            }
        } else {
            $mail->AddAddress($emailTo);
        }
        if (is_array($emailCc) && count($emailCc) > 0) {
            foreach ($emailCc as $cckey => $ccval) {
                $mail->addCC($ccval);
            }
        } else {
            $mail->addCC($emailCc);
        }
        if (is_array($emailBcc) && count($emailBcc) > 0) {
            foreach ($emailBcc as $bcckey => $bccval) {
                $mail->addBCC($bccval);
            }
        } else {
            $mail->addBCC($emailBcc);
        }


        //now create email
        if (@$mail->Send()) {
            $emailObj->to_addrs = is_array($emailTo) ? implode(',', $emailTo) : $emailTo;
            $emailObj->cc_addrs = is_array($emailCc) ? implode(',', $emailCc) : $emailCc;
            $emailObj->bcc_addrs = is_array($emailBcc) ? implode(',', $emailBcc) : $emailBcc;
            $emailObj->type = 'out';
            $emailObj->deleted = '0';
            $emailObj->name = $mail->Subject;
            //$emailObj->description = NULL;
            //$emailObj->description_html = $mail->Body;
            $emailObj->description = $mail->AltBody;
            $emailObj->description_html = $mail->Body;
            $emailObj->from_addr = $mail->From;
            if ($relatedBean instanceOf SugarBean && !empty($relatedBean->id)) {
                $emailObj->parent_type = $relatedBean->module_dir;
                $emailObj->parent_id = $relatedBean->id;
            }
            $emailObj->date_sent = TimeDate::getInstance()->nowDb();
            $emailObj->modified_user_id = '1';
            $emailObj->created_by = $current_user->id;
            $emailObj->status = 'sent';
            $emailObj->save();
            return true;
        } else {
            $GLOBALS['log']->info("CaseUpdatesHook: Could not send email:  " . $mail->ErrorInfo);
        }
        return false;
    }
}

/**
 * Add a Security Group to a record
 */
function addGroupToRecord($module, $record_id, $securitygroup_id) {
    if (empty($module) || empty($record_id) || empty($securitygroup_id)) {
        return; //missing data
    }
    global $db, $current_user, $timedate;
    $query = "insert into securitygroups_records(id,securitygroup_id,record_id,module, date_modified, created_by, modified_user_id, deleted) "
            . "values( '" . create_guid() . "','" . $securitygroup_id . "','$record_id','$module','" . $timedate->nowDb() . "', '" . $current_user->id . "', '" . $current_user->id . "',0) ";
    $GLOBALS['log']->debug("SecuritySuite: addGroupToRecord: $query");
    $db->query($query, true);
}

/**
 * Remove a Security Group from a record
 */
function updateGroupFromRecord($module, $record_id, $securitygroup_id, $fetched_securitygroup_id) {
    if (empty($module) || empty($record_id) || empty($securitygroup_id)) {
        return; //missing data
    }
    global $db, $current_user, $timedate;

    $query = "update securitygroups_records set securitygroup_id = if(securitygroup_id = '" . $fetched_securitygroup_id . "', '" . $securitygroup_id . "',securitygroup_id), date_modified = '" . $timedate->nowDb() . "', modified_user_id= '" . $current_user->id . "' "
            . "where record_id = '$record_id' and module = '$module'";
    $GLOBALS['log']->debug("SecuritySuite: UpdateGroupToRecord: $query");
    $db->query($query, true);
}
