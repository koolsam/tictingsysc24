<?php
/**
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2016 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */
/**
 * Class CaseUpdatesHook
 */
require_once 'modules/AOP_Case_Updates/CaseUpdatesHook.php';

class CustomCaseUpdatesHook extends CaseUpdatesHook {
    
    private $slug_size = 50;

    /**
     * @return string
     */
    private function getAssignToUser()
    {
        require_once 'modules/AOP_Case_Updates/AOPAssignManager.php';
        $assignManager = new AOPAssignManager();

        return $assignManager->getNextAssignedUser();
    }

    /**
     * @return int
     */
    private function arrangeFilesArray()
    {
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
    public function saveUpdate($case)
    {
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

}