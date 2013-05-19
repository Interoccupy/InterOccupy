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

var asInitVals = new Array();
//
//Infinite Scroll
/*
 "bScrollInfinite": true,
 "bScrollCollapse": true,
 "sScrollY": "400px",
 */
jQuery(document).ready(function() {
    var oTable = jQuery('#ajaxSecondtable').dataTable( {
        "oLanguage": {  "sSearch": "Search all columns:" },
        "bAutoWidth": true,
        "bInfo": true,
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
                {"bSortable": false, "aTargets": [4,5,6,9,10,11,12,13,14,15] }
        ],
        "aaSorting" : [[0,'desc']]


    } );

   //Disabled global search
    //        "oLanguage": {
 //   "sSearch": "Search all columns:"
// }

    jQuery("tfoot input").keyup( function () {
        /* Filter on the column (the index) of this element */
        oTable.fnFilter( this.value, jQuery("tfoot input").index(this) );
    } );

    /*
     * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
     * the footer
     */
    jQuery("tfoot input").each( function (i) {
        asInitVals[i] = this.value;
    } );

    jQuery("tfoot input").focus( function () {
        if ( this.className == "search_init" )
        {
            this.className = "";
            this.value = "";
        }
    } );

    jQuery("tfoot input").blur( function (i) {
        if ( this.value == "" )
        {
            this.className = "search_init";
            this.value = asInitVals[jQuery("tfoot input").index(this)];
        }
    } );

    /**head*/

    jQuery("thead input").keyup( function () {
        /* Filter on the column (the index) of this element */
        oTable.fnFilter( this.value, jQuery("thead input").index(this) );
    } );

    /*
     * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
     * the footer
     */
    jQuery("thead input").each( function (i) {
        asInitVals[i] = this.value;
    } );

    jQuery("thead input").focus( function () {
        if ( this.className == "search_init" )
        {
            this.className = "";
            this.value = "";
        }
    } );

    jQuery("thead input").blur( function (i) {
        if ( this.value == "" )
        {
            this.className = "search_init";
            this.value = asInitVals[jQuery("thead input").index(this)];
        }
    } );




} );