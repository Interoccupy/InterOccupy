<?php
/**
 * Created by WpGetReady
 * User: Fernando Zorrilla de San Martin
 * Date: 03/08/12
 * Time: 03:59 PM
 * Currently this class does nothing, since data doesn't come through here but Ajax.
 * But in next round, we should test if this works creating some code right here.
 * And also, is feasible to introduce some changes to hook javascript to the page (?)
 * This class is an attempt to make a complete isolate table design without hooking the ajax code outside on
 * the statcomm . This class CONFLICTS with ajaxFirst so watchout, because I'm testing it!
 */
class ajaxSecond extends statcommMustache
{
    public function title() {return "Ajax Second Test";}
    public function  __construct(){
        //Attempt to load the ajax function instead using statcomm.php to make it work.
       // add_action('statcomm_module_ajax', 'ajaxSecond::Ajax' );//data returned for tables.
    }

    public function templateName() {return "ajaxSecond";}

    //If this works, the method has to be the SAME in all class which uses Ajax

    /*Original query*/

    public function AjaxOLD()
    {
        $aColumns = array( 'date', 'time', 'ip', 'urlrequested','agent',   'feed', 'statuscode', 'nation', 'os', 'id' );
        //      $mapCols  = array (9,0,1,2,-1,-1,-1,7,3,-1,-1,-1,-1,-1,-1);
        $sIndexColumn = "id";
//        $sTable = "#table#"; //Will be replaced with prefix_statcomm table
        $sTable = "wp_statcomm"; //This test is to avoid selecting other table in multisite mode.
        utilities::fl("OLD JSON",mySql::statcommJson($aColumns,$sIndexColumn,$sTable));
        echo mySql::statcommJson($aColumns,$sIndexColumn,$sTable);
        die();
    }

/**/
    /**
     * To test functionality we will try to mimic the Last Hits behavior
     * I'll try to mimic the functionality as much is possible.
     * The first question is about formatting: how do I make the exact same format as the table?
     * Using mustache that was already solved and it was easy, but how about that now?
     * Nope, this needs a new library.
     * the first idea is to create the query and after that reprocess the library to convert every field
     * to the format we really need. after that, to encode using json. Why I have the feeling I will have trouble
     * using icons?
     * 20120805: makeQuery moved to utilities. mySql stays without change.
     * Creating data more like lastHits used to be.
     * Reusing a modification of lastHits
     * Simplified
     * TODO: Error control
     */
    public function Ajax()
    {
        $sEcho          = isset($_GET['sEcho'])?$_GET['sEcho']:"";
        //These are the normal columns for the query
        $aColumns = array( 'date', 'time', 'ip', 'urlrequested','agent',   'feed', 'statuscode', 'nation', 'os', 'id' );
        //These are the mapping between display and query, counting from zero, including columns hidden
        $mapCols  = array (9,0,1,2,-1,-1,-1,7,3,-1,-1,-1,-1,-1,-1);
        $sIndexColumn = "id";
//        $sTable = "#table#"; //Will be replaced with prefix_statcomm table
        $sTable = "wp_statcomm"; //This test is to avoid selecting other table in multisite mode.

        //Get how many records do we have
        $sQuery = " SELECT COUNT(`" . $sIndexColumn. "`)  FROM   $sTable  ";
        $iTotal = mySql::get_row($sQuery,NULL,ARRAY_N);

        utilities::fl("iTotal:", $iTotal);
        //Build the query.

        $sQuery    = utilities::makeQuery($aColumns,$sIndexColumn,$sTable,$mapCols);
        //Evaluate how many rows came from the result.
        $sQryCount = utilities::makeQuery($aColumns,$sIndexColumn,$sTable,$mapCols,true);
        $result = mySql::get_var($sQryCount);
        utilities::fl("Resultados:",$result);


        try
        {
            //$output = $this->rows($sQuery,$aColumns); //for use with testing function
            $output = $this->rows($sQuery);

        }
            catch(Exception $e)
        {
            return "ERROR:" . $e;
        }
        $output["sEcho"]                = intval($sEcho);
        $output["iTotalRecords"]        = $iTotal;
        $output["iTotalDisplayRecords"]  = intval($result);


        //utilities::fl("NEW JSON:" , json_encode($output));
        echo json_encode( $output );
        die();
    }

    /**
     * This functions PERFECT SO USE IT FOR TESTING ONLY
     * @param $sQuery
     * @param $aColumns
     *
     * @return array
     */
    /**
    public function rowsOLD($sQuery,$aColumns)
    {
        $rResult = mySql::get_results($sQuery,NULL,ARRAY_A);
        $recCounter=0;
        foreach ($rResult as $aRow)
        {
            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                if ( $aColumns[$i] == "version" ) // Special output formatting for 'version' column
                {

                    $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
                }
                else if ( $aColumns[$i] != ' ' ) // General output
                {
                    $row[] = $aRow[ $aColumns[$i] ];
                }
            }
            $output['aaData'][] = $row;
            $recCounter++;
        }
        $output["iTotalDisplayRecords"] = $recCounter;
        return $output;
    }
*/

    public function rows($Query)
    {
        $geoLocation=utilities::geolocationEnabled();
        $gi=NULL;
        $parser= new statcommParser();
        $lastHits = mySql::get_results($Query);
        if ($geoLocation == utilities::ERROR_NONE )  { $gi = utilities::geoLocationOpen(); }


        utilities::fl("Query", $Query);
        //utilities::fl("RESULTADOS:",$lastHits);

        $counter=1; //v1.6.60: bug correction
        $results=array();
        foreach ($lastHits as $hit) {
            $row=array();
            $ua=$parser->Parse($hit->agent); //Get info from agent
            //TODO: the problem is if type is robot the continue you cant' have an exact number.
           // if ($ua['typ']=='Robot') continue;
            //If the feed isn't empty then we can ignore OS and Browser Version
            $isFeed = (empty($hit->feed))?0:1; //PHP incapable of such simple evaluations?
            $isError=($hit->statuscode!="200" and !empty($hit->statuscode))?"scError":"";

            $row['isError'] = $isError;
            $row['date']    = utilities::conv2Date($hit->date);
            $row['time']    = $hit->time;
            $row['id']      = $hit->id;
            $row['ip']      = $hit->ip;

            $row['country_name'] = "";
            $row['flag_icon']    = "";
            $row['region_name']  = "";
            $row['city']         = "";

            if ($geoLocation == utilities::ERROR_NONE){
                $record =GeoIpCity_Ctrl::GeoIP_info_by_addr($gi, $hit->ip);
                if (!empty($record)){
                    $row['country_name'] =$record->country_name;
                    $row['flag_icon']    = utilities::make_flag_icon($record);
                    $row['region_name']  = $record->region_name;
                    $row['city']         = $record->city;
                }
                else{
                    $row['country_name'] = '?';
                    $row['flag_icon']    = utilities::make_flag_icon(null);
                    $row['region_name']  = 'NO DATA';
                    $row['city']         = 'NO DATA';
                }
            }

            $row['nation'] = $hit->nation;
            $row['url'] = utilities::outUrlDecode($hit->urlrequested);
            $row['url_requested'] = $hit->urlrequested;
            $row['url_ellipsis'] = utilities::makeEllipsis($row['url'], 40);

            //Prepare default values.
            $row['os_icon'] = "";
            $row['os'] = "";
            $row['ua_icon'] = "";
            $row['ua_info_url'] = "";
            $row['ua_family'] = "";
            $row['ua_version'] = "";


            if (!$isFeed){
                if ($hit->os != 'unknown'){
                    $row['os_icon'] = utilities::make_os_icon($ua['os_icon']);
                }
            }

            $row['os_url'] = $ua['os_url'];
            if (!$isFeed){
                if ($hit->os!='unknown'){
                    $row['os'] = $hit->os;
                }
            }

            if ($ua['ua_family']!='unknown'){
                $row['ua_icon'] = utilities::make_uas_icon($ua['ua_icon']);
            }
            $row['agent'] = $hit->agent;

            if ($ua['ua_family']!='unknown'){
                $row['ua_info_url'] = $ua['ua_info_url'];
                $row['ua_family'] = $ua['ua_family'];
            }

            if ($ua['ua_version']!='unknown'){
                $row['ua_version'] = $ua['ua_version'];
            }

            $row['feed'] = $hit->feed;
            $row['statuscode'] = $hit->statuscode;

            $output['aaData'][] = $row;
            $counter++;
         }

        if($geoLocation == utilities::ERROR_NONE){ utilities::geoLocationClose($gi); }


        return $output;
    }

}
