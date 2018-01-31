/**
 * 
 * @description  Js file to handle case module fields
 * @author Sandeepa Rajoriya<sandeepa.rajoroiya@cars24.com> 
 */
Case = {
    /**
     * 
     * @param {Array} myfieldsArray 
     * 
     * @description Hide and blank all fields present in Array from case editview. 
     * 
     */
    initHide: function (myfieldsArray, fldBlank) {

        var fieldsArrLen = myfieldsArray.length;
        for (var i = 0; i < fieldsArrLen; i++) {
            $('#' + myfieldsArray[i]).closest('.edit-view-row-item').hide();
            removeFromValidate('EditView', myfieldsArray[i]);
            if (fldBlank == true) {
                $('#' + myfieldsArray[i]).val('');
            }
        }
    },
    /**
     * 
     * @param {Object} myfieldsArray
     * @param {Bool} validate (true or false)
     * 
     * @description Show all fields presents in object. 
     * Object contains field as Key and type as value
     * 
     * example: {name: 'varchar', age: 'int', dob: 'date'}
     *   
     */
    initShow: function (myfieldsArray, validate) {

        var msgStr = '';
        for (var ky in myfieldsArray) {
            $('#' + ky).closest('.edit-view-row-item').show();
            if (validate == true) {
                msgStr = ky.replace("_c", "");
                msgStr = msgStr.replace("_", " ");
                addToValidate('EditView', ky, myfieldsArray[ky], true, msgStr);
            }
        }
    },
    initIssueFacedChange: function (myfieldsArray, fieldobj) {

        this.initHide(myfieldsArray, true);
        var validateFieldArr = {};
        var fieldArr = {};
        var issFcdVal = $(fieldobj).val();

        switch (issFcdVal) {

            case 'customer':

                validateFieldArr = {appointment_id_c: 'int', caller_name_c: 'varchar', contacted_customer_c: 'bool'};
                fieldArr = {current_appt_datetime_c_date: 'date', appointment_stage_c: 'varchar', appointment_status_c: 'varchar'};
                break;
            case 'channel_partner':

                validateFieldArr = {caller_mobile_c: 'int', caller_name_c: 'varchar', caller_city_c: 'varchar', dealer_id_c: 'int', dealer_name_c: 'varchar', dealership_name_c: 'varchar', dealer_spoc_name_c: 'varchar', dealer_spoc_no_c: 'int', appointment_id_c: 'int', dealer_region_c: 'varchar'};

                break;
        }

        this.initShow(fieldArr, false);
        this.initShow(validateFieldArr, true);
    },
    initCategoryChange: function (catFieldObj) {

        var showFieldObj = {};
        var showValidateFieldObj = {};
        var hideFieldArr = ['hold_back_type_c', 'car_won_date_c', 'contacted_customer_c', 'visit_date_c', 'appointment_id_c', , 'dealer_id_c', 'dealer_name_c', 'dealership_name_c', 'dealer_spoc_name_c', 'dealer_spoc_no_c', 'dealer_region_c', 'dealer_eligible_c'];
        var catVal = $(catFieldObj).val();

        switch (catVal) {

            case 'customer_visited':

                showFieldObj = {visit_date_c: 'date'};

                break;
            case 'customer_pr_waiting_or_won':

                showValidateFieldObj = {car_won_date_c: 'date'};

                break;
            case 'channel_partner_exisiting':

                showValidateFieldObj = {dealer_id_c: 'int', dealer_name_c: 'varchar', dealership_name_c: 'varchar', dealer_spoc_name_c: 'varchar', dealer_spoc_no_c: 'int', appointment_id_c: 'int', dealer_region_c: 'varchar'};

                break;
        }

        this.initHide(hideFieldArr, true);
        this.initShow(showFieldObj, false);
        this.initShow(showValidateFieldObj, true);
    },
    initSubCategoryChange: function (subCatFieldObj) {

        var showFieldObj = {};
        var showValidateFieldObj = {};
        var hideFieldArr = ['visit_date_c', 'car_won_date_c', 'contacted_customer_c', 'hold_back_type_c', 'appointment_id_c'];
        var subCatVal = $(subCatFieldObj).val();

        switch (subCatVal) {

            case 'customer_pr_waiting_or_won_hold_back_amount_not_received':

                showValidateFieldObj = {hold_back_type_c: 'varchar'};

                break;
        }
        this.initHide(hideFieldArr, true);
        this.initShow(showFieldObj, false);
        this.initShow(showValidateFieldObj, true);
    },
    initEditForm: function () {

        var issueFacedVal = $('#issue_faced_by_c').val();
        var catVal = $('#category').val();
        var subCatdVal = $('#sub_category').val();
        var statusVal = $('#status').val();
        var hideFieldArr = [];
        var showFieldObj = {};
        var showValidateFieldObj = {};

        switch (issueFacedVal) {

            case 'customer':

                hideFieldArr = ["caller_name_c", "caller_city_c", "visit_date_c", "car_won_date_c", "dealer_id_c", "dealer_name_c", "dealership_name_c", "dealer_spoc_name_c", "dealer_spoc_no_c", "contacted_channel_partner_c", "inspection_miss_for_evaluator_c", "refund_amount_c", "priority", "dealer_region_c", "hold_back_type_c"];

                break;
            case 'channel_partner':

                hideFieldArr = ["current_appt_datetime_c_date", "visit_date_c", "car_won_date_c", "contacted_customer_c", "appointment_stage_c", "appointment_status_c", "inspection_miss_for_evaluator_c", "refund_amount_c", "priority", "hold_back_type_c"];

                break;
            default:

                hideFieldArr = ["caller_name_c", "appointment_id_c", "caller_mobile_c", "caller_city_c", "current_appt_datetime_c_date", "visit_date_c", "car_won_date_c", "contacted_customer_c", "dealer_id_c", "dealer_name_c", "dealership_name_c", "dealer_spoc_name_c", "dealer_spoc_no_c", "appointment_stage_c", "appointment_status_c", "contacted_channel_partner_c", "inspection_miss_for_evaluator_c", "refund_amount_c", "priority", "dealer_region_c", "hold_back_type_c"];

                break;
        }

        switch (catVal) {

            case 'customer_visited':

                showFieldObj = {visit_date_c: 'date'};

                break;
            case 'customer_pr_waiting_or_won':

                showValidateFieldObj = {car_won_date_c: 'date'};

                break;
            case 'channel_partner_exisiting':

                showValidateFieldObj = {};

                break;
            case 'channel_partner_new':

                showValidateFieldObj = {dealer_eligible: 'bool'};

                break;
            default:

                showValidateFieldObj = {};

                break;
        }

        switch (subCatdVal) {

            case 'customer_pr_waiting_or_won_hold_back_amount_not_received':

                showValidateFieldObj = {hold_back_type_c: 'varchar'};

                break;
            case 'channel_partner_exisiting_inspection_miss':

                showValidateFieldObj = {inspection_miss_for_evaluator_c: 'bool', refund_amount_c: 'float'};

                break;
            default:

                showValidateFieldObj = {};

                break;
        }

        switch (statusVal) {

            case 'resolved':

                showValidateFieldObj = {resolution: 'varchar'};

                break;
            case 'reopen':
            case 'Closed':

                showValidateFieldObj = {resolution: 'varchar'};

                break;
            case 'new':
            case 'assigned':
            case 'awaiting_response':
            case 'duplicate':

                hideFieldArr.push('resolution');

                break;
            default:

                showValidateFieldObj = {};

                break;
        }

        this.initHide(hideFieldArr, true);
        this.initShow(showFieldObj, false);
        this.initShow(showValidateFieldObj, true);
    },
    initEditIssueFacedChange: function (myfieldsArray, fieldobj) {

        this.initHide(myfieldsArray);
        var validateFieldArr = {};
        var fieldArr = {};
        var issFcdVal = $(fieldobj).val();

        switch (issFcdVal) {

            case 'customer':

                validateFieldArr = {appointment_id_c: 'int', caller_name_c: 'varchar', contacted_customer_c: 'bool'};
                fieldArr = {current_appt_datetime_c_date: 'date', appointment_stage_c: 'varchar', appointment_status_c: 'varchar'};
                break;
            case 'channel_partner':

                validateFieldArr = {caller_mobile_c: 'int', caller_name_c: 'varchar', caller_city_c: 'varchar', dealer_id_c: 'int', dealer_name_c: 'varchar', dealership_name_c: 'varchar', dealer_spoc_name_c: 'varchar', dealer_spoc_no_c: 'int', appointment_id_c: 'int', dealer_region_c: 'varchar'};

                break;
        }

        this.initShow(fieldArr, false);
        this.initShow(validateFieldArr, true);
    },
    initEditCategoryChange: function (catFieldObj) {

        var showFieldObj = {};
        var showValidateFieldObj = {};
        var hideFieldArr = ['hold_back_type_c', 'car_won_date_c', 'contacted_customer_c', 'visit_date_c', 'appointment_id_c', , 'dealer_id_c', 'dealer_name_c', 'dealership_name_c', 'dealer_spoc_name_c', 'dealer_spoc_no_c', 'dealer_region_c', 'dealer_eligible'];
        var catVal = $(catFieldObj).val();

        switch (catVal) {

            case 'customer_visited':

                showFieldObj = {visit_date_c: 'date'};

                break;
            case 'customer_pr_waiting_or_won':

                showValidateFieldObj = {car_won_date_c: 'date'};

                break;
            case 'channel_partner_exisiting':

                showValidateFieldObj = {dealer_id_c: 'int', dealer_name_c: 'varchar', dealership_name_c: 'varchar', dealer_spoc_name_c: 'varchar', dealer_spoc_no_c: 'int', appointment_id_c: 'int', dealer_region_c: 'varchar'};

                break;
            case 'channel_partner_new':

                showValidateFieldObj = {dealer_eligible: 'bool'};

                break;
        }

        this.initHide(hideFieldArr);
        this.initShow(showFieldObj, false);
        this.initShow(showValidateFieldObj, true);
    },
    initEditSubCategoryChange: function (subCatFieldObj) {

        var showFieldObj = {};
        var showValidateFieldObj = {};
        var hideFieldArr = ['visit_date_c', 'car_won_date_c', 'contacted_customer_c', 'hold_back_type_c', 'appointment_id_c'];
        var subCatVal = $(subCatFieldObj).val();

        switch (subCatVal) {

            case 'customer_pr_waiting_or_won_hold_back_amount_not_received':

                showValidateFieldObj = {hold_back_type_c: 'varchar'};

                break;
            case 'channel_partner_exisiting_inspection_miss':

                showValidateFieldObj = {inspection_miss_for_evaluator_c: 'bool', refund_amount_c: 'float'};

                break;
        }
        this.initHide(hideFieldArr);
        this.initShow(showFieldObj, false);
        this.initShow(showValidateFieldObj, true);
    },
    initStatusChange: function (statusFieldObj) {

        var showValidateFieldObj = {};
        var hideFieldArr = [];
        var statusVal = $(statusFieldObj).val();

        switch (statusVal) {

            case 'resolved':

                showValidateFieldObj = {resolution: 'varchar'};

                break;
            case 'reopen':
            case 'Closed':

                showValidateFieldObj = {resolution: 'varchar'};

                break;
            case 'new':
            case 'assigned':
            case 'awaiting_response':
            case 'duplicate':

                hideFieldArr.push('resolution');

                break;
            default:

                showValidateFieldObj = {};

                break;
        }
        this.initHide(hideFieldArr, false);
        this.initShow(showValidateFieldObj, true);
    },
    initDetailViewFieldsHide: function (myfieldsArray) {

        var fieldsArrLen = myfieldsArray.length;
        for (var i = 0; i < fieldsArrLen; i++) {
            $('#' + myfieldsArray[i]).closest('.detail-view-row-item').remove();
        }

        $('.detail-view-row').each(function () {
            if ($(this).find('div').length < 1) {
                $(this).remove();
            }
        });
    },
    initDetailViewDisplay: function (issueFacedVal) {
        
        var hideFieldsArray = [];

        switch (issueFacedVal) {

            case 'customer':

                hideFieldsArray = ["resolution", "caller_mobile_c", "caller_city_c", "visit_date_c", "car_won_date_c", "dealer_id_c", "dealer_name_c", "dealership_name_c", "dealer_spoc_name_c", "dealer_spoc_no_c", "contacted_channel_partner_c", "inspection_miss_for_evaluator_c", "refund_amount_c", "group_name_c", "assigned_user_name", "priority", "dealer_region_c", "hold_back_type_c", "dealer_eligible_c"];

                break;

            case 'channel_partner':

                hideFieldsArray = ["resolution", "current_appt_datetime_c", "visit_date_c", "car_won_date_c", "contacted_customer_c", "appointment_stage_c", "appointment_status_c", "contacted_channel_partner_c", "inspection_miss_for_evaluator_c", "refund_amount_c", "group_name_c", "assigned_user_name", "priority", "hold_back_type_c"];

                break;

            case 'internal_team':

                hideFieldsArray = ["resolution", "caller_name_c", "appointment_id_c", "caller_mobile_c", "caller_city_c", "current_appt_datetime_c", "visit_date_c", "car_won_date_c", "contacted_customer_c", "dealer_id_c", "dealer_name_c", "dealership_name_c", "dealer_spoc_name_c", "dealer_spoc_no_c", "appointment_stage_c", "appointment_status_c", "contacted_channel_partner_c", "inspection_miss_for_evaluator_c", "refund_amount_c", "group_name_c", "assigned_user_name", "priority", "dealer_region_c", "hold_back_type_c", "dealer_eligible_c"];

                break;
        }

        this.initDetailViewFieldsHide(hideFieldsArray);

    },
};

