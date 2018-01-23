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
    initHide: function (myfieldsArray) {

        var fieldsArrLen = myfieldsArray.length;
        for (var i = 0; i < fieldsArrLen; i++) {
            $('#' + myfieldsArray[i]).val('').closest('.edit-view-row-item').hide();
            removeFromValidate('EditView', myfieldsArray[i]);
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
    initCategoryChange: function (catFieldObj) {

        var showFieldArr = {};
        var showValidateFieldArr = {};
        var hideFieldArr = ['hold_back_type_c', 'car_won_date_c', 'contacted_customer_c', 'visit_date_c', 'appointment_id_c', , 'dealer_id_c', 'dealer_name_c', 'dealership_name_c', 'dealer_spoc_name_c', 'dealer_spoc_no_c', 'dealer_region_c', 'dealer_eligible'];
        var catVal = $(catFieldObj).val();

        switch (catVal) {

            case 'customer_visited':

                showFieldArr = {visit_date_c: 'date'};

                break;
            case 'customer_pr_waiting_or_won':

                showValidateFieldArr = {car_won_date_c: 'date'};

                break;
            case 'channel_partner_exisiting':

                showValidateFieldArr = {dealer_id_c: 'int', dealer_name_c: 'varchar', dealership_name_c: 'varchar', dealer_spoc_name_c: 'varchar', dealer_spoc_no_c: 'int', appointment_id_c: 'int', dealer_region_c: 'varchar'};

                break;
        }

        this.initHide(hideFieldArr);
        this.initShow(showFieldArr, false);
        this.initShow(showValidateFieldArr, true);
    },
    initSubCategoryChange: function (subCatFieldArr) {

        var showFieldArr = {};
        var showValidateFieldArr = {};
        var hideFieldArr = ['visit_date_c', 'car_won_date_c', 'contacted_customer_c', 'hold_back_type_c', 'appointment_id_c'];
        var subCatVal = $(subCatFieldArr).val();

        switch (subCatVal) {

            case 'customer_pr_waiting_or_won_hold_back_amount_not_received':

                showValidateFieldArr = {hold_back_type_c: 'varchar'};

                break;
        }
        this.initHide(hideFieldArr);
        this.initShow(showFieldArr, false);
        this.initShow(showValidateFieldArr, true);
    },
    initEditForm: function () {

        var issueFacedVal = $('#issue_faced_by_c').val();
        var catVal = $('#category').val();
        var subCatdVal = $('#sub_category').val();
        var hideFieldArr = [];
        var showFieldArr = {};
        var showValidateFieldArr = {};

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

                showFieldArr = {visit_date_c: 'date'};

                break;
            case 'customer_pr_waiting_or_won':

                showValidateFieldArr = {car_won_date_c: 'date'};

                break;
            case 'channel_partner_exisiting':

                showValidateFieldArr = {};

                break;
            case 'channel_partner_new':

                showValidateFieldArr = {dealer_eligible: 'bool'};

                break;
            default:

                showValidateFieldArr = {};

                break;
        }

        switch (subCatdVal) {

            case 'customer_pr_waiting_or_won_hold_back_amount_not_received':

                showValidateFieldArr = {hold_back_type_c: 'varchar'};

                break;
            case 'channel_partner_exisiting_inspection_miss':

                showValidateFieldArr = {inspection_miss_for_evaluator_c: 'bool', refund_amount_c: 'float'};

                break;
            default:

                showValidateFieldArr = {};

                break;
        }

        this.initHide(hideFieldArr);
        this.initShow(showFieldArr, false);
        this.initShow(showValidateFieldArr, true);
    },
};

