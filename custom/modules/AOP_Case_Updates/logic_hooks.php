<?php

$hook_version = 1; 
$hook_array = Array(); 
$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(1, 'Send email in Case Update comment added', 'custom/modules/AOP_Case_Updates/CaseUpdatesHook.php','CustomCaseUpdatesHook', 'notifyForCaseUpdatesThread');
