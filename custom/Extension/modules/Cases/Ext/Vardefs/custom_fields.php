<?php

/**
 * @author Sandeepa Rajoriya <sandeepa.rajoriya@cars24.com>
 * @copyright (c) 2018, CARS24
 */
$dictionary["Case"]["fields"]["issue_faced_by_c"] = array(
    'name' => 'issue_faced_by_c',
    'vname' => 'LBL_ISSUE_FACED_BY',
    'required' => true,
    'type' => 'enum',
    'dbType' => 'varchar',
    'unified_search' => true,
    'len' => 50,
    'options' => 'issue_faced_by_list',
    'comment' => 'Issue faced by Source',
    'inline_edit' => false,
    'audited' => true,
);
$dictionary["Case"]["fields"]['category'] = array(
    'name' => 'category',
    'vname' => 'LBL_TICKET_CATEGORY',
    'type' => 'dynamicenum',
    'options' => 'ticket_category_list',
    'inline_edit' => false,
    //'len' => 100,
    'required' => true,
    'dbtype' => 'varchar',
    'parentenum' => 'issue_faced_by_c',
    'audited' => true,
    'comment' => 'Ticket category',
);
$dictionary["Case"]["fields"]['sub_category'] = array(
    'name' => 'sub_category',
    'vname' => 'LBL_TICKET_SUB_CATEGORY',
    'type' => 'dynamicenum',
    'options' => 'ticket_sub_category_list',
    'inline_edit' => false,
    //'len' => 100,
    'required' => true,
    'dbtype' => 'varchar',
    'parentenum' => 'category',
    'audited' => true,
    'comment' => 'Ticket Sub category',
);
$dictionary["Case"]["fields"]["securitygroups"] = array(
    'name' => 'securitygroups',
    'type' => 'link',
    'relationship' => 'securitygroups_cases',
    'link_type' => 'one',
    'side' => 'right',
    'source' => 'non-db',
    'vname' => 'LBL_GROUP_NAME',
);
$dictionary["Case"]["fields"]["group_name_c"] = array(
    'name' => 'group_name_c',
    'rname' => 'name', //which points to the field name in the related module record
    'id_name' => 'group_id_c',
    'vname' => 'LBL_GROUP_NAME',
    'type' => 'relate',
    'relationship' => 'securitygroups_cases',
    'link' => 'securitygroups', //which is the link field in the Contacts vardef which specifies this relationship
    'table' => 'securitygroups',
    'join_name' => 'securitygroups',
    'isnull' => 'true',
    'module' => 'SecurityGroups', //which is the module which contains the related record
    'dbType' => 'varchar',
    'len' => 100,
    'source' => 'non-db',
    'unified_search' => true,
    'comment' => 'The name of the group represented by the group_id field',
    'required' => true,
    'audited' => true,
    'inline_edit' => false,
    'importable' => 'required',
);
$dictionary["Case"]["fields"]["group_id_c"] = array(
    'name' => 'group_id_c',
    'type' => 'relate',
    'relationship' => 'securitygroups_cases',
    'dbType' => 'id',
    'rname' => 'id',
    'module' => 'SecurityGroups',
    'id_name' => 'group_id_c',
    'reportable' => true,
    'vname' => 'LBL_GROUP_NAME',
    'audited' => true,
    'massupdate' => true,
    'inline_edit' => false,
    'comment' => 'The group to which the case is associated'
);
$dictionary["Case"]["fields"]["ragistered_email_c"] = array(
    'name' => 'ragistered_email_c',
    'vname' => 'LBL_REGISTERED_EMAIL',
    'type' => 'email',
    'dbtype' => 'varchar',
    'len' => 150,
    'comment' => 'Registered Email',
    'audited' => TRUE,
    'required' => TRUE,
);
$dictionary["Case"]["fields"]["ragistered_mobile_c"] = array(
    'name' => 'ragistered_mobile_c',
    'vname' => 'LBL_REGISTERED_MOBILE',
    'type' => 'int',
    'dbtype' => 'varchar',
    'len' => 12,
    'comment' => 'Registered Mobile Number',
    'audited' => TRUE,
    'required' => TRUE,
    'inline_edit' => FALSE,
    'merge_filter' => 'disabled',
    'enable_range_search' => false,
    'min' => false,
    'max' => false,
    'disable_num_format' => '1',
);
$dictionary["Case"]["fields"]["caller_name_c"] = array(
    'name' => 'caller_name_c',
    'vname' => 'LBL_CALLER_NAME',
    'type' => 'name',
    'dbType' => 'varchar',
    'len' => 255,
    'audited' => true,
    'unified_search' => true,
    'full_text_search' => array('boost' => 3),
    'comment' => 'Name of caller',
    'required' => true,
);
$dictionary["Case"]["fields"]["appointment_id_c"] = array(
    'name' => 'appointment_id_c',
    'vname' => 'LBL_APPOINTMENT_ID',
    'type' => 'int',
    'dbtype' => 'bigint',
    'len' => 16,
    'required' => true,
    'comment' => 'Appointment Id',
    'disable_num_format' => true,
    'studio' => array('quickcreate' => false),
    'inline_edit' => false,    
    'disable_num_format' => '1',
);
$dictionary["Case"]["fields"]["current_appt_datetime_c"] = array(
    'name' => 'current_appt_datetime_c',
    'vname' => 'LBL_CURRENT_APPT_DATETIME',
    'type' => 'datetimecombo',
    'dbtype' => 'datetime',
    'default_value' => '',
    'comment' => 'Date and time for Appointment book',
    'mass_update' => false,
    'enable_range_search' => FALSE,
    'required' => false,
    'reportable' => true,
    'audited' => false,
    'duplicate_merge' => false,
    'importable' => 'true',
);
$dictionary["Case"]["fields"]["visit_date_c"] = array(
    'name' => 'visit_date_c',
    'vname' => 'LBL_VISIT_DATE',
    'type' => 'date',
    'default_value' => '',
    'comment' => 'Date of center visit',
    'mass_update' => false,
    'required' => false,
    'reportable' => true,
    'audited' => false,
    'duplicate_merge' => false,
    'importable' => 'true',
);
$dictionary["Case"]["fields"]["car_won_date_c"] = array(
    'name' => 'car_won_date_c',
    'vname' => 'LBL_CAR_WON_DATE',
    'type' => 'date',
    'default_value' => '',
    'comment' => 'Date of car deal won',
    'mass_update' => false,
    'required' => TRUE,
    'reportable' => true,
    'audited' => false,
    'duplicate_merge' => false,
    'importable' => 'true',
);
$dictionary["Case"]["fields"]["contacted_customer_c"] = array(
    'name' => 'contacted_customer_c',
    'vname' => 'LBL_CONTACTED_CUSTOMER',
    'type' => 'enum',
    'dbtype' => 'tinyint',
    'len' => 1,
    'default_value' => '1',
    'comment' => 'Contect customer by CC or not',
    'options' => 'dom_int_bool',
    'audited' => false,
    'mass_update' => false,
    'duplicate_merge' => false,
    'reportable' => true,
    'importable' => 'true',
    'required' => true,
);
$dictionary["Case"]["fields"]["dealer_id_c"] = array(
    'name' => 'dealer_id_c',
    'vname' => 'LBL_DEALER_ID',
    'type' => 'int',
    'dbtype' => 'bigint',
    'len' => 16,
    'required' => true,
    'comment' => 'Dealer Id',
    'disable_num_format' => true,
    'studio' => array('quickcreate' => false),
    'inline_edit' => false,
    'disable_num_format' => '1',
);
$dictionary["Case"]["fields"]["dealer_name_c"] = array(
    'name' => 'dealer_name_c',
    'vname' => 'LBL_DEALER_NAME',
    'type' => 'name',
    'dbType' => 'varchar',
    'len' => 255,
    'audited' => true,
    'unified_search' => true,
    'full_text_search' => array('boost' => 3),
    'comment' => 'Name of dealer',
    'required' => true,
);
$dictionary["Case"]["fields"]["dealership_name_c"] = array(
    'name' => 'dealership_name_c',
    'vname' => 'LBL_DEALERSHIP_NAME',
    'type' => 'name',
    'dbType' => 'varchar',
    'len' => 255,
    'audited' => true,
    'unified_search' => true,
    'full_text_search' => array('boost' => 3),
    'comment' => 'Name of dealership',
    'required' => true,
);
$dictionary["Case"]["fields"]["dealer_spoc_name_c"] = array(
    'name' => 'dealer_spoc_name_c',
    'vname' => 'LBL_DEALER_SPOC_NAME',
    'type' => 'name',
    'dbType' => 'varchar',
    'len' => 255,
    'audited' => true,
    'unified_search' => true,
    'full_text_search' => array('boost' => 3),
    'comment' => 'Name of SPOC for dealer',
    'required' => true,
);
$dictionary["Case"]["fields"]["dealer_spoc_no_c"] = array(
    'name' => 'dealer_spoc_no_c',
    'vname' => 'LBL_DEALER_SPOC_NO',
    'type' => 'int',
    'dbtype' => 'varchar',
    'len' => 12,
    'comment' => 'SPOC for dealer Mobile Number',
    'audited' => TRUE,
    'required' => TRUE,
    'disable_num_format' => '1',
);
$dictionary["Case"]["fields"]["appointment_stage_c"] = array(
    'name' => 'appointment_stage_c',
    'vname' => 'LBL_APPOINTMENT_STAGE',
    'required' => true,
    'type' => 'enum',
    'unified_search' => true,
    'len' => 50,
    'options' => 'appointment_stage_list',
    'comment' => 'Appointment Stage',
    'inline_edit' => false,
    'audited' => true,
);
$dictionary["Case"]["fields"]["appointment_status_c"] = array(
    'name' => 'appointment_status_c',
    'vname' => 'LBL_APPOINTMENT_STATUS',
    'required' => true,
    'type' => 'enum',
    'unified_search' => true,
    'len' => 50,
    'options' => 'appointment_status_list',
    'comment' => 'Appointment Status',
    'inline_edit' => false,
    'audited' => true,
);
$dictionary["Case"]["fields"]["caller_mobile_c"] = array(
    'name' => 'caller_mobile_c',
    'vname' => 'LBL_CALLER_MOBILE',
    'type' => 'int',
    'dbtype' => 'varchar',
    'len' => 12,
    'comment' => 'Mobile Number of Caller',
    'audited' => TRUE,
    'required' => TRUE,
    'disable_num_format' => '1',
);
$dictionary["Case"]["fields"]["caller_city_c"] = array(
    'name' => 'caller_city_c',
    'vname' => 'LBL_CALLER_CITY',
    'type' => 'varchar',
    'len' => 100,
    'audited' => true,
    'unified_search' => FALSE,
    'full_text_search' => array('boost' => 3),
    'comment' => 'City of caller',
    'required' => true,
);
$dictionary["Case"]["fields"]["contacted_channel_partner_c"] = array(
    'name' => 'contacted_channel_partner_c',
    'vname' => 'LBL_CONTACTED_CHANNEL_PARTNER',
    'type' => 'enum',
    'dbtype' => 'tinyint',
    'len' => 1,
    'default_value' => '1',
    'comment' => 'Contect channel partner by CC or not',
    'options' => 'dom_int_bool',
    'audited' => false,
    'mass_update' => false,
    'duplicate_merge' => false,
    'reportable' => true,
    'importable' => 'true',
    'required' => true,
);
$dictionary["Case"]["fields"]["inspection_miss_for_evaluator_c"] = array(
    'name' => 'inspection_miss_for_evaluator_c',
    'vname' => 'LBL_INSPECTION_MISS_FOR_EVALUATOR',
    'type' => 'enum',
    'dbtype' => 'tinyint',
    'len' => 1,
    'default_value' => '1',
    'comment' => 'Inspection miss by evaluator or not',
    'options' => 'dom_int_bool',
    'audited' => false,
    'mass_update' => false,
    'duplicate_merge' => false,
    'reportable' => true,
    'importable' => 'true',
    'required' => true,
);
$dictionary["Case"]["fields"]["refund_amount_c"] = array(
    'name' => 'refund_amount_c',
    'vname' => 'LBL_REFUND_AMOUNT_ID',
    'type' => 'float',
    'len' => 12,
    'precision' => 2,
    'required' => true,
    'comment' => 'Amount of refund to dealer',
    'disable_num_format' => true,
    'studio' => array('quickcreate' => false),
    'inline_edit' => false,
);
$dictionary["Case"]["fields"]["region_c"] = array(
    'name' => 'region_c',
    'vname' => 'LBL_REGION',
    'required' => true,
    'type' => 'enum',
    'dbType' => 'varchar',
    'unified_search' => true,
    'len' => 50,
    'options' => 'ticket_region_list',
    'comment' => 'Region List',
    'inline_edit' => false,
    'audited' => true,
);
$dictionary["Case"]["fields"]['city_c'] = array(
    'name' => 'city_c',
    'vname' => 'LBL_CITY',
    'type' => 'dynamicenum',
    'options' => 'ticket_city_list',
    'inline_edit' => false,
    'len' => 100,
    'required' => true,
    'dbtype' => 'varchar',
    'parentenum' => 'region_c',
    'audited' => true,
    'comment' => 'Ticket City',
);
$dictionary["Case"]["fields"]["dealer_region_c"] = array(
    'name' => 'dealer_region_c',
    'vname' => 'LBL_DEALER_REGION',
    'required' => true,
    'type' => 'enum',
    'dbType' => 'varchar',
    'unified_search' => true,
    'len' => 50,
    'options' => 'ticket_region_list',
    'comment' => 'Dealer Region List',
    'inline_edit' => false,
    'audited' => true,
);
$dictionary["Case"]["fields"]["hold_back_type_c"] = array(
    'name' => 'hold_back_type_c',
    'vname' => 'LBL_HOLD_BACK_TYPE',
    'required' => true,
    'type' => 'enum',
    'dbType' => 'varchar',
    'unified_search' => true,
    'len' => 100,
    'options' => 'hold_back_type_list',
    'comment' => 'Hold back type',
    'inline_edit' => false,
    'audited' => true,
);
$dictionary["Case"]["fields"]["dealer_eligible_c"] = array(
    'name' => 'dealer_eligible_c',
    'vname' => 'LBL_DEALER_ELIGIBLE',
    'required' => true,
    'type' => 'enum',
    'dbType' => 'varchar',
    'unified_search' => true,
    'len' => 100,
    'options' => 'dom_int_bool',
    'comment' => 'Dealer eligible or not',
    'inline_edit' => false,
    'audited' => true,
);
$dictionary["Case"]["fields"]["status_change_datetime_c"] = array(
    'name' => 'status_change_datetime_c',
    'vname' => 'LBL_STATUS_CHANGE_DATETIME',
    'type' => 'datetimecombo',
    'dbtype' => 'datetime',
    'default_value' => '',
    'comment' => 'Date and time for status change',
    'mass_update' => false,
    'enable_range_search' => FALSE,
    'required' => false,
    'reportable' => true,
    'audited' => false,
    'duplicate_merge' => false,
    'importable' => 'true',
);
$dictionary["Case"]["fields"]["name"]["vname"] = "LBL_CUSTOMER_NAME";
$dictionary["Case"]["fields"]["update_text"]["editor"] = "";
$dictionary["Case"]["fields"]["description"]["editor"] = "";
$dictionary["Case"]["fields"]["status"]["options"] = "case_status_list";
