<?php

class CaseGroupAddHook {

    public function caseAddGroup($bean, $event, $arguments) {
        global $timedate;
        if (!empty($bean->id)) {
            if (!empty($bean->fetched_row)) {
                if ($bean->group_id_c != $bean->fetched_row['group_id_c']) {

                    updateGroupFromRecord("Cases", $bean->id, $bean->group_id_c, $bean->fetched_row['group_id_c']);
                    //$bean->assign_group_modify_datetime_c = $timedate->nowDb();
                }
            } else {

                addGroupToRecord("Cases", $bean->id, $bean->group_id_c);
            }
        }
    }

}
