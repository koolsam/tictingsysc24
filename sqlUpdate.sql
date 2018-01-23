/**
 * Author:  Sandeepa Rajoriya
 * Created: 11 Jan, 2018
 */

ALTER TABLE cases   add COLUMN issue_faced_by_c varchar(50)  NULL ;
ALTER TABLE cases   add COLUMN category varchar(255)  NULL ;
ALTER TABLE cases   add COLUMN sub_category varchar(255)  NULL ;
ALTER TABLE cases   add COLUMN group_id_c char(36)  NULL ;
ALTER TABLE cases   add COLUMN ragistered_email_c varchar(150)  NULL ,  add COLUMN ragistered_mobile_c varchar(12)  NULL ;
ALTER TABLE cases   add COLUMN caller_name_c varchar(255)  NULL ,  add COLUMN appointment_id_c bigint(16)  NULL ,  add COLUMN current_appt_datetime_c datetime  NULL ,  add COLUMN visit_date_c date  NULL ,  add COLUMN car_won_date_c date  NULL ,  add COLUMN contacted_customer_c bool  DEFAULT '0' NULL ;
ALTER TABLE cases   add COLUMN dealer_id_c bigint  NULL ,  add COLUMN dealer_name_c varchar(255)  NULL ,  add COLUMN dealership_name_c varchar(255)  NULL ,  add COLUMN dealer_spoc_name_c varchar(255)  NULL ,  add COLUMN dealer_spoc_no_c varchar(12)  NULL ,  add COLUMN appointment_stage_c varchar(50)  NULL ,  add COLUMN appointment_status_c varchar(50)  NULL ,  add COLUMN caller_mobile_c varchar(12)  NULL ,  add COLUMN caller_city_c varchar(100)  NULL ,  add COLUMN contacted_channel_partner_c bool  DEFAULT '0' NULL ,  add COLUMN inspection_miss_for_evaluator_c bool  DEFAULT '0' NULL ,  add COLUMN refund_amount_c float(12,2)  NULL ;
ALTER TABLE cases   add COLUMN region_c varchar(50)  NULL ,  add COLUMN city_c varchar(100)  NULL ,  add COLUMN dealer_region_c varchar(50)  NULL ;
ALTER TABLE cases   add COLUMN hold_back_type_c varchar(100)  NULL ;
ALTER TABLE cases   add COLUMN dealer_eligible_c varchar(100)  NULL ;
