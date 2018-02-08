<?php

/**
 * Class CaseUpdatesHook
 */
require_once 'modules/AOP_Case_Updates/CaseUpdatesHook.php';

class CustomCaseUpdatesHook extends CaseUpdatesHook {

    private $slug_size = 50;

    /**
     * @return string
     */
    private function getAssignToUser() {
        require_once 'modules/AOP_Case_Updates/AOPAssignManager.php';
        $assignManager = new AOPAssignManager();

        return $assignManager->getNextAssignedUser();
    }

    /**
     * @return int
     */
    private function arrangeFilesArray() {
        $count = 0;
        foreach ($_FILES['case_update_file'] as $key => $vals) {
            foreach ($vals as $index => $val) {
                if (!array_key_exists('case_update_file' . $index, $_FILES)) {
                    $_FILES['case_update_file' . $index] = array();
                    ++$count;
                }
                $_FILES['case_update_file' . $index][$key] = $val;
            }
        }

        return $count;
    }

    /**
     * @param aCase $case
     */
    public function saveUpdate($case) {
        if (!isAOPEnabled()) {
            return;
        }
        global $current_user, $app_list_strings;
        if (empty($case->fetched_row) || !$case->id) {
            if (!$case->state) {
                $case->state = $app_list_strings['case_state_default_key'];
            }
            if ($case->status === 'New') {
                $case->status = $app_list_strings['case_status_default_key'];
            }

            //New case - assign
            if (!$case->assigned_user_id) {
                $userId = $this->getAssignToUser();
                $case->assigned_user_id = $userId;
                $case->notify_inworkflow = true;
            }

            //return;
        }
        if ($_REQUEST['module'] === 'Import') {
            return;
        }
        //Grab the update field and create a new update with it.
        $text = $case->update_text;
        if (!$text && empty($_FILES['case_update_file'])) {
            //No text or files, so nothing really to save.
            return;
        }
        $case->update_text = '';
        $case_update = new AOP_Case_Updates();
        $case_update->name = $text;
        $case_update->internal = $case->internal;
        $case->internal = false;
        $case_update->assigned_user_id = $current_user->id;
        if (strlen($text) > $this->slug_size) {
            $case_update->name = substr($text, 0, $this->slug_size) . '...';
        }
        $case_update->description = nl2br($text);
        $case_update->case_id = $case->id;
        $case_update->save();

        $fileCount = $this->arrangeFilesArray();

        for ($x = 0; $x < $fileCount; ++$x) {
            if ($_FILES['case_update_file']['error'][$x] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $uploadFile = new UploadFile('case_update_file' . $x);
            if (!$uploadFile->confirm_upload()) {
                continue;
            }
            $note = $this->newNote($case_update->id);
            $note->name = $uploadFile->get_stored_file_name();
            $note->file_mime_type = $uploadFile->mime_type;
            $note->filename = $uploadFile->get_stored_file_name();
            $note->save();
            $uploadFile->final_move($note->id);
        }
        $postPrefix = 'case_update_id_';
        foreach ($_POST as $key => $val) {
            if (empty($val) || strpos($key, $postPrefix) !== 0) {
                continue;
            }
            //Val is selected doc id
            $doc = BeanFactory::getBean('Documents', $val);
            if (!$doc) {
                continue;
            }
            $note = $this->newNote($case_update->id);
            $note->name = $doc->document_name;
            $note->file_mime_type = $doc->last_rev_mime_type;
            $note->filename = $doc->filename;
            $note->save();
            $srcFile = "upload://{$doc->document_revision_id}";
            $destFile = "upload://{$note->id}";
            copy($srcFile, $destFile);
        }
    }

    private function newNote($caseUpdateId) {

        $note = BeanFactory::newBean('Notes');
        $note->parent_type = 'AOP_Case_Updates';
        $note->parent_id = $caseUpdateId;
        $note->not_use_rel_in_req = true;

        return $note;
    }

    public function notifyForCaseUpdatesThread($bean, $event, $arguments) {

        global $current_user, $sugar_config;
        require_once('modules/EmailTemplates/EmailTemplate.php');
        
        $template_name = 'Update Thread Email';

        $primary_email = array($current_user->email1);
        
        $template = new EmailTemplate();
        $template->retrieve_by_string_fields(array('name' => $template_name, 'type' => 'email'));
        
        if (isset($template->body_html) && $template->body_html != '') {

            if (!empty($bean->id) && !empty($bean->case_id) && $bean->case_id != '' && $bean->id != '') {

                $caseObj = BeanFactory::getBean("Cases", $bean->case_id);
                                
                $mailArr['created_by'] = $caseObj->created_by;
                
                $group_member_list = getGroupMembers($caseObj->group_id_c);
                
                foreach ($group_members as $member) {
                    $role_obj = new ACLRole();
                    $role_arr = $role_obj->getUserRoles($member['id']);

                    if (!in_array('Manager', $role_arr)) {
                        $user = BeanFactory::getBean('Users', $member['id']);
                        $primary_email[] = $user->emailAddress->getPrimaryAddress($user);
                    }
                }
                
                if (count($primary_email) > 0) {
                    
                    $template->subject = str_replace('$current_user_name', $current_user->full_name, $template->subject);
                    $template->body_html = str_replace('$current_user_name', $current_user->full_name, $template->body_html);

                    $template->subject = $template->parse_template_bean($template->subject, $caseObj->module_dir, $caseObj);

                    $template->body_html = $template->parse_template_bean($template->body_html, $caseObj->module_dir, $caseObj);
                    $template->body_html = $template->parse_template_bean($template->body_html, $bean->module_dir, $bean);

                    $mailSend = sendEmail($primary_email, $template->subject, $template->body_html, $template->body, $caseObj);
                }
            }
        }
    }

}
