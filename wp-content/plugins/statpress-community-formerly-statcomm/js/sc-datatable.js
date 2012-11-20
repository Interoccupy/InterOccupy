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
 */

jQuery(document).ready(function() {
    jQuery('#ajaxFirst').dataTable( {
        "sPaginationType": "full_numbers",
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": ajaxurl +  "?action=datatable_action"
    } );
} );
