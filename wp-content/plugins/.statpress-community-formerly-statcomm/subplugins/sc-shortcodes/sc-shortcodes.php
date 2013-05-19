<?php
/*
Plugin Name: Shortcodes for Statcomm
Description: Adds a way to add shortcodes to any page. Beta 1
Author: WPGetReady
Author: http://wpgetready.com
Version: 1.0.1
*/

//Instance report
$statcommSC=new statcommShortCodes();

/**
 * Main class to create the report
 */
class statcommShortCodes
{

    /**
     * Called when the class is instanciaded
     */
    function __construct()
    {
        //Fire the action to hang the page from the WP admin menu
        add_action   ('admin_menu', 	        array(&$this,'shortcodePage')); //add menu pages
        add_shortcode( 'sc_fieldname', array(&$this,'statcommField') );             //enable shortcode.
        add_shortcode( 'sc_operator', array(&$this,'statcommOperator') );       //enable shortcode.
        add_shortcode( 'sc_operator2', array(&$this,'statcommOperator') );       //enable shortcode (recursion)
        add_shortcode( 'sc_operator3', array(&$this,'statcommOperator') );       //enable shortcode.

      //  add_action('wp_enqueue_scripts',         array(&$this,'addDetailsStyle'));    //style initialization

    }

    /**
     * Current version will not show icons. It has many problems involved
     * This will be adressed in future versions.*/

    function addDetailsStyle()
    {
        //Replace usual settings for plugin to customs
        wp_register_style( 'statcomm-sprites',   plugins_url('subplugins/sc-shortcodes/statcomm-override.php', dirname(dirname(__FILE__))), array());
        wp_enqueue_style('statcomm-sprites');

        wp_register_style( 'statcomm-flags', plugins_url('css/statcomm-flags.css',dirname(dirname(__FILE__))), array());
        wp_enqueue_style('statcomm-flags');

        wp_register_style( 'statcomm-os',    plugins_url('css/statcomm-os.css',  dirname(dirname(__FILE__))), array());
        wp_enqueue_style('statcomm-os');

        wp_register_style( 'statcomm-uas',   plugins_url('css/statcomm-uas.css', dirname(dirname(__FILE__))), array());
        wp_enqueue_style('statcomm-uas');


    }


    /**
     * Called when WP is creating the menus
     */
    function shortcodePage()
    {
        $errorRP= add_submenu_page('statComm',  __('Shortcodes','statcomm'),   __('Shortcodes Help','statcomm'),
            'activate_plugins',    'statComm/shortcodes', array(&$this, 'shortcodesMain'));


        //Add javascript and css style to this page.
//        add_action( 'admin_print_styles-'  . $errorRP,  'statPressCommunity::statcommAdminStyle' );
//        add_action( 'admin_print_scripts-' . $errorRP,  'statPressCommunity::jsScriptsPage' );

    }

    /**
     * We'll reuse the view system.
     * To do that we need: a xml file (error-report.xml) two folders (classes & templates) and some classes and
     * templates borrowed from Statcomm and modified.
     */
    function shortcodesMain()
    {
        ?>
    <div class='wrap'><h2><?php _e('HELP', 'statcomm');?></h2><?php require_once("help-page/help.php"); ?></div>
    <?php
    //TODO: include custom running samples here.
    }

/*
 * Format [sc_field fieldname='fieldname']
 *
 */
    function statcommField($attributes)
    {
        $options = get_option('statcomm_options'); //all options
        $fieldname = '';
        //Get the parameter fieldname or empty if it is not defined.
        extract( shortcode_atts( array('fieldname' => ''), $attributes ) );
        //lookup fieldname and return the value.
        return $this->getFieldValue($fieldname);
    }

    /**
     * @param $fieldname
     * @return string
     * Giving the fieldname, tries to find it in the data.
     * If it suceed, it returns current value.
     * 20121106: added language
     * 20130217:Suppressed icons since there are useless
     *
     */
    private function getFieldValue($fieldname)
    {
        if (empty($fieldname))
        {
            return  __("Error: fieldname attribute empty. Use [sc_field fieldname='fieldname']","statcomm"); //to improve
        }

        $options = get_option('statcomm_options'); //all options
        if (empty($options['currentUser'])){
            return  __("Error; current User not found","statcomm") ;
        }
//return "the field name is: " . $fieldname;
        $cU=$options['currentUser'];

        //Although I don't like this kind of solutions it is a shortcut to a Reflection class...
        $currentUserInfo =array
        (
            'date' => 'getDate',
            'time' => 'getTime' ,
            'ip' => 'getIP',
            'urlrequested' => 'getUrlRequested',
            'agent' => 'getAgent',
            'referrer' => 'getReferrer',
            'search' => 'getSearch',
            'nation' => 'getNation',
            'os' => 'getOS',
            'browser' => 'getBrowser',
            'language' => 'getLanguage',
            'searchengine' => 'getSearchEngine',
            'spider' => 'getSpider',
            'feed' => 'getFeed',
            'user' => 'getUser',
            'timestamp' => 'getTimestamp',
            'statuscode' => 'getStatusCode',
            'typ' => 'getUserAgent',
            'ua_family' => 'getUserAgent',
            'ua_name' => 'getUserAgent',
            'ua_version' => 'getUserAgent',
            'ua_url' => 'getUserAgent',
            'ua_company' => 'getUserAgent',
            'ua_company_url' => 'getUserAgent',
            'ua_info_url' => 'getUserAgent',
            'os_family' => 'getUserAgent',
            'os_name' => 'getUserAgent',
            'os_url' => 'getUserAgent',
            'os_company' => 'getUserAgent',
            'os_company_url' => 'getUserAgent',
            'country_code' => 'getGeoLocation',
            'country_code3' => 'getGeoLocation',
            'country_name' => 'getGeoLocation',
            'region' => 'getGeoLocation',
            'region_name' => 'getGeoLocation',
            'city' => 'getGeoLocation',
            'postal_code' => 'getGeoLocation',
            'latitude' => 'getGeoLocation',
            'longitude' => 'getGeoLocation',
            'area_code' => 'getGeoLocation',
            'dma_code' => 'getGeoLocation',
            'metro_code' => 'getGeoLocation',
            'continent_code' => 'getGeoLocation',
            'continent_name' => 'getGeoLocation' );
/*
               'img_browser'  =>'getImage',
            'img_os'   =>'getImage',
            'img_country' =>'getImage'
        );
*/
        $match=FALSE;
        $method='';
        $prop ='';
        foreach ($currentUserInfo as $key =>$value)
        {
            if ($key == strtolower($fieldname))
            {
                $match=TRUE;
                $method =$value;
                $prop =$key;
                break;
            }
        }
        if ($match==FALSE) {
            return __("Error: Custom Field Not Found ($method)","statcomm");
        }


        switch ($method){
/*
            case "getImage":
                switch ($prop) {
                    case 'img_browser':
                        $gUA=$cU->getUserAgent();
                        return utilities::make_uas_icon($gUA['ua_icon']);
                        break;
                    case 'img_os' :
                        $gUA=$cU->getUserAgent();
                        return utilities::make_os_icon($gUA['os_icon']);
                        break;
                    case 'img_country' :
                        return $this->get_flag($cU->getIP);
                        break;
                }
*/
            case "getUserAgent":
                $gUA=$cU->getUserAgent();
                return $gUA[$prop];
                break;
            case "getGeoLocation":
                $gGL =$cU->getGeoLocation();
                return $gGL->$prop;
                break;
            default:
                return $cU->$method();
        }
    }

    function get_flag($currentIP){
        $gi="";
        $geoLocation=utilities::geolocationEnabled();
        if ($geoLocation == utilities::ERROR_NONE){$gi= utilities::geoLocationOpen();}
        $record =GeoIpCity_Ctrl::GeoIP_info_by_addr($gi, $currentIP);
        return utilities::make_flag_icon($record);
    }

    /**
     * Insert a custom style for overriding
     * @param $htmlTag
     * @param $value
     */
    function custom_style ($htmlTag,$value)
    {
        $htmlTag= strtolower($htmlTag);
        return str_replace("<img", "<img style='$value' ",$htmlTag);
    }

    /*
     * Atrributes
     * fieldname = field to reference
     * operator = by default is empty or "NOT" which means to ignore certains values. casing is ignored.
     * values = by comma delimited values
     * Example [sc_operator fieldname="ip" values ="192.168.1.100"]<p>display this only if ip value is 192.168.1.100[/sc_operator]
     * Example 2 [sc_operator  fieldname="country_code" operator="not" values="US,SP,UY"]Display only if country is different than USA, Spain and Uruguay[/sc_operator]
     */
    function statcommOperator($attributes,$content=NULL)
    {
        $fieldname='';
        $values='';
        $operator='';
        extract( shortcode_atts( array('fieldname' => '','operator' =>'', 'values'=>''), $attributes ) );
        //Validate info before proceed
        if (($fieldname == '') or ( $values=='' )){
            return __("Error: fieldname or values empty. Use [sc_operator  fieldname='(field name)' operator='(valid operator)' values='(separated comma values)']","statcomm");
        }
        //Get the fieldname value
        $fieldValue=trim(strtolower( $this->getFieldValue($fieldname)));
        //pending: prover operator validation.
        $operator = trim(strtolower($operator));
        if (!(empty($operator) or ($operator=='not'))){
            return __("Error: invalid operator. Options available for operation operator='' or operator='not'");
        }
        $values =trim(strtolower($values));

        //Pending: validation and proper return.
        //Process value(s) and decide what to do with it.
        $arrayValues=explode(",",$values);
        $found=FALSE;
        foreach($arrayValues as $v){
            if ($fieldValue==trim($v)){
                $found=TRUE;
                break;
            }

        }
        //We reach here with $found=true if we found the value, false if not.
        if ($operator =="not") {
            $found = !$found;
        }

        if($found){
            return do_shortcode($content);
        }
        return '';

    }
}

/*TO IMPLEMENT

* [wpgc_is_nearby"] - Uses the value you specify in the Nearby Range setting from the administrative panel
* [wpgc_is_not_nearby"]
* [wpgc_is_within" miles="10"]
* [wpgc_is_within kilometers="12"]
* icon os, icon browser, icon_country (or at least url for both)
*/

/*
 * Example
.flag { background:url(../../plugins/SC/images/flags.png) no-repeat}
.os     { background:url(../../plugins/SC/images/os.png) no-repeat}
.uas   { background:url(../../plugins/SC/images/uas.png) no-repeat }
 *
 */


