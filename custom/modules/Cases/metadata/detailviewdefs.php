<?php

$viewdefs ['Cases'] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'includes' => 
      array (
        0 =>
            array (
                'file' => 'custom/modules/Cases/javascript/case.js',
            ),
      ),
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
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
      'useTabs' => true,
      'tabDefs' => 
      array (
        'LBL_CASE_INFORMATION' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_AOP_CASE_UPDATES' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
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
            'label' => 'LBL_CASE_NUMBER',
          ),
          1 => 'status',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'issue_faced_by_c',
          ),
          1 => 'category',
        ),
        2 => 
        array (
          0 => 'sub_category',
          1 => 'region_c',
        ),
        3 => 
        array (
          0 => 'city_c',
          1 => 
          array (
            'name' => 'name',
          ),
        ),
        4 => 
        array (
          0 => 'ragistered_email_c',
          1 => 
          array (
            'name' => 'ragistered_mobile_c',
          ),
        ),
        5 => 
        array (
          0 => 'caller_name_c',
          1 => 
          array (
            'name' => 'caller_mobile_c',
          ),
        ),
        6 => 
        array (
          0 => 'caller_city_c',
          1 => 
          array (
            'name' => 'appointment_id_c',
          ),
        ),
        7 => 
        array (
          0 => 'current_appt_datetime_c',
          1 => 
          array (
            'name' => 'appointment_stage_c',
          ),
        ),
        8 => 
        array (
          0 => 'appointment_status_c',
          1 => 
          array (
            'name' => 'car_won_date_c',
          ),
        ),
        9 => 
        array (
          0 => 'visit_date_c',
          1 => 
          array (
            'name' => 'dealer_id_c',
          ),
        ),
        10 => 
        array (
          0 => 'dealer_name_c',
          1 => 
          array (
            'name' => 'dealership_name_c',
          ),
        ),
        11 => 
        array (
          0 => 'dealer_spoc_name_c',
          1 => 
          array (
            'name' => 'dealer_spoc_no_c',
          ),
        ),
        12 => 
        array (
          0 => 'contacted_customer_c',
          1 => 
          array (
            'name' => 'hold_back_type_c',
          ),
        ),
        13 => 
        array (
          0 => 'contacted_channel_partner_c',
          1 => 
          array (
            'name' => 'inspection_miss_for_evaluator_c',
          ),
        ),
        14 => 
        array (
          0 => 'refund_amount_c',
          1 => 
          array (
            'name' => 'dealer_region_c',
          ),
        ),
        15 => 
        array (
          0 => 'dealer_eligible',
          1 => 
          array (
            'name' => 'group_name_c',
          ),
        ),
        16 => 
        array (
          0 => 'description',
        ),
        17 => 
        array (
          0 => 'resolution',
        ),
        18 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
          1 => array(),
        ),
        19 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'label' => 'LBL_DATE_MODIFIED',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
          ),
        ),
      ),
      'LBL_AOP_CASE_UPDATES' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'aop_case_updates_threaded',
            'studio' => 'visible',
            'label' => 'LBL_AOP_CASE_UPDATES_THREADED',
          ),
        ),
      ),
    ),
  ),
);
?>
