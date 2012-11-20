/**
 * Created by: Fernando Zorrilla de San Martín
 * Date: 8/1/12
 * Time: 10:58 PM
 */

//ajaxurl is a value provided by WP
//action is a parameter needed to compose wp_axjax_datatable_action action
//The other two parameters are needed to narrow the query
//Later, I should see how to pass the date parameter.
/*
 "fnServerParams": function ( aoData ) {
 aoData.push( { "action": "datatable_action", "date": "20120606", "query": "QRY_lastHits_dt" } );
}

IMPORTANT:action has to be the SAME NAME as the class
Added: aocolumndefs to include Id invisible and also a first sort descending by Id.
 */

jQuery(document).ready(function() {
    jQuery('#ajaxSecond').dataTable( {
        "sPaginationType": "full_numbers",
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": ajaxurl +  "?action=StatcommAjax&module=ajaxSecond",
        "aoColumns": [
            {"mDataProp": "id"},
            {"mDataProp": "date"},
            {"mDataProp": "time"},
            {"mDataProp": "ip"},
            {"mDataProp": "flag_icon"},
            {"mDataProp": "region_name"},
            {"mDataProp": "city"},
            {"mDataProp": "nation"},
            {"mDataProp": "url_requested"},
            {"mDataProp": "os_icon"},
            {"mDataProp": "os"},
            {"mDataProp": "ua_icon"},
            {"mDataProp": "ua_family"},
            {"mDataProp": "ua_version"},
            {"mDataProp": "feed"},
            {"mDataProp": "statuscode"}
            ]
        ,
        "aoColumnDefs": [
            {"bVisible": false , "aTargets": [0]}
        ]
        ,
        "aaSorting" : [[0,'desc']]

    } );
} );
