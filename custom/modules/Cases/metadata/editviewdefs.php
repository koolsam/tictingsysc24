<?php
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
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
 ********************************************************************************/

$viewdefs ['Cases'] =
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'includes' => 
      array (
        0 =>
            array (
                'file' => 'include/javascript/bindWithDelay.js',
            ),
        1 =>
            array (
                'file' => 'modules/AOK_KnowledgeBase/AOK_KnowledgeBase_SuggestionBox.js',
            ),
        2 =>
            array (
                'file' => 'include/javascript/qtip/jquery.qtip.min.js',
            ),
        3 =>
            array (
                'file' => 'custom/modules/Cases/javascript/case.js',
            ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_CASE_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'form' => 
      array (
        'enctype' => 'multipart/form-data',
      ),
    ),
    'panels' => 
    array (
      'lbl_case_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'case_number',
            'type' => 'readonly',
          ),
          1 => 'priority',
        ),
        1 => 
        array (
          0 => 'issue_faced_by_c',
          1 => 'status',
        ),
        2 => 
        array (
          0 => 'category',
          1 => 'sub_category',
        ),
        3 =>
        array (
          0 => 'region_c',
          1 => 'city_c',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'displayParams' => 
            array (
              //'size' => 75,
            ),
          ),
            1 => 'ragistered_email_c',
        ),
        5 =>
        array (
          0 => 'ragistered_mobile_c',
          1 => 'caller_name_c',          
        ),
        6 =>
        array (
          0 => 'caller_mobile_c',
          1 => 'caller_city_c',
        ),
        7 =>
        array (
          0 => 'appointment_id_c',
          1 => 'current_appt_datetime_c',
        ),
        8 =>
        array (
          0 => 'appointment_stage_c',
          1 => 'appointment_status_c',
        ),
        9 =>
        array (
          0 => 'car_won_date_c',
          1 => 'visit_date_c',
        ),
        10 =>
        array (
          0 => 'dealer_id_c',
          1 => 'dealer_name_c',
        ),
        11 =>
        array (
          0 => 'dealership_name_c',
          1 => 'dealer_spoc_name_c',
        ),
        12 =>
        array (
          0 => 'dealer_spoc_no_c',
          1 => 'contacted_customer_c',
        ),
        13 =>
        array (
          0 => 'hold_back_type_c',
          1 => 'contacted_channel_partner_c',
        ),
        14 =>
        array (
          0 => 'inspection_miss_for_evaluator_c',
          1 => 'refund_amount_c',
        ),        
        15 =>
        array (
          0 => 'dealer_region_c',
          1 => 'dealer_eligible',
        ),
        16 =>
        array (
          0 => 
          array (
            'name' => 'description',
          ),
        ),
        
        17 =>
        array (
          0 => 
          array (
            'name' => 'update_text',
            'studio' => 'visible',
            'label' => 'LBL_UPDATE_TEXT',
          ),
        ),
        18 => array(
//            0 => array (
//                'name' => 'internal',
//                'studio' => 'visible',
//                'label' => 'LBL_INTERNAL',
//            ),
            0 => 
            array (
              'name' => 'resolution',
              'nl2br' => true,
            ),
        ),
        19 =>
        array (
          0 => 
          array (
            'name' => 'case_update_form',
            'studio' => 'visible',
          ),
        ),
        20 =>
        array (
          0 => 'group_name_c',
          1 => 'assigned_user_name',
        ),
      ),
    ),
  ),
);
?>
