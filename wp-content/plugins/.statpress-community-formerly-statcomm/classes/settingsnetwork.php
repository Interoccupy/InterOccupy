<?php
/**
 * Created by WpGetReady n @2012
 * Author: Fernando Zorrilla de San Martin
 * Date: 15/06/12
 * Time: 07:33 PM
 */

/**
 * This class handles network settings for multisite environment.
 * The following items are set from network admin:
 * Maxmind database installation and check
 * UAParser database installation
 * Table deletion
 * Note: not settings API for network-wide settings?. WP is just beginning in this play area...
 * 1.7.30: bye bye static , since we need to build subplugin menu right under network settings.
 * These are independent.
 * -Added subplugin network control.
 * 1.7.60: Adding manual disabling Maxmind database
 */
class settingsNetwork
{
    private $subplugins;
    const NETWORK_PAGE ="statComm";
    function __construct()
    {
        $this->subplugins = new subPlugins();
        $this->subplugins->setSubpluginPage(self::NETWORK_PAGE);
    }

    function statcomm_network_page()
    {
        add_menu_page('SC Multisite',
            __('SC Multisite'       ,'statcomm'),
            'manage_network_options',  self::NETWORK_PAGE,
            array(&$this,'statcomm_network_menu'),
            plugins_url('images/statcomm-bw-16x16.png', dirname(__FILE__)));
        //This method has to be BEFORE statcomm_network_menu since the header will be sent in that method.
        $this->subplugins->subplugin_prevalidation();
    }

    /**
     * 1.7.60: changed using isset by empty
     * Caution: statcomm_network_menu works very different comparing with for example statcomm_setting_chk_maxmind
     * Furthermore, the way variables are handled are different.
     */
    function statcomm_network_menu(){

        //If no checked box in post set value to false
        if(isset($_POST['sc_submit'])){
            //Check docs.php.net/manual/en/types.comparisons.php
            //When we get here, the check has two posible values: on (if checked) or isset(variable)=false (or undefined)

            if(!isset($_POST['sc_setting']['chk_deltable']))         $_POST['sc_setting']['chk_deltable'] = "";
            if(!isset($_POST['sc_setting']['chk_disable_maxmind']))  $_POST['sc_setting']['chk_disable_maxmind']  = "";
            if(!isset($_POST['sc_setting']['chk_disable_uas']))  $_POST['sc_setting']['chk_disable_uas']  = "";

            //So, when we get here, the check has two posible values: true (if checked) or "" (meaning false)

            if(isset($_POST['sc_setting']))
            {
                $networkOptions=array();
                foreach((array)$_POST['sc_setting'] as $key => $value){//Add more sc_setting[FIELDNAME] to form for more fields
                    $networkOptions[$key] = $value;
                }
                update_site_option(settingsAPI::SC_ADVANCED_OPTIONS_KEY,$networkOptions);
            }
        }
        $options=get_site_option(settingsAPI::SC_ADVANCED_OPTIONS_KEY);
        $settings = new settingsAPI();
        //prevalidation before rendering.
       $chk_disable_uas = isset($options['chk_disable_uas'])?$options['chk_disable_uas']:"";
       $chk_disable_maxmind = isset($options['chk_disable_maxmind'])?$options['chk_disable_maxmind']:"";
       $chk_deltable = isset($options['chk_deltable'])?$options['chk_deltable']:"";
        ?>

    <div class="wrap">
        <h2>StatComm Network Settings</h2>
        <?php if(isset($_POST['sc_submit'])) : ?>
        <div id="message" class="updated fade">
            <p>
                <?php _e( 'Settings Saved', 'my' ) ?>
            </p>
        </div>
        <?php endif; ?>
        <form method="post" action="">

            <p style="margin-bottom:20px;">
                <input name="sc_setting[chk_disable_uas]" type="checkbox" <?php if($chk_disable_uas =='on')  echo 'checked'; ?> value="on" />
                <span class="checkbox_text"><?php _e('Check to disable User Agent database','statcomm') ?></span><br/>
                <?php
                //add additional info. This validation will work both for single and multisite
                $settings->statcomm_setting_chk_uas_validation();
                ?>
            </p>

            <p style="margin-bottom:20px;">
                <input name="sc_setting[chk_disable_maxmind]" type="checkbox" <?php if($chk_disable_maxmind =='on')  echo 'checked'; ?> value="on" />
                <span class="checkbox_text"><?php _e('Check to disable Maxmind database','statcomm') ?></span><br/>
                <?php
                    $settings->statcomm_setting_chk_glc();
                ?>
            </p>
            <p style="margin-bottom:30px;">
                <input name="sc_setting[chk_deltable]" type="checkbox" <?php if($chk_deltable == 'on') echo 'checked'; ?> value="on" />
                <span class="checkbox_text"><?php _e('Delete Statcomm table and data when uninstall','statcomm') ?></span><br />
            </p>
            <p>
                <input name="sc_submit" type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </form>
    <?php

        $this->subplugins->subplugin_render();
    ?>
    </div><!-- /.wrap -->
    <?php
    }

}

/*
 *
 *     function statcomm_setting_chk_maxmind() {
        // get option 'text_string' value from the database
        $options = self::getOptionsAdvanced();
        $chk =isset($options['chk_disable_maxmind'])?$options['chk_disable_maxmind']:"";
        $checked='';
        if($chk) { $checked = ' checked="checked" '; }
        echo "<input id='chk_disable_maxmind' name='" . self::SC_ADVANCED_OPTIONS_KEY. "[chk_disable_maxmind]' type='checkbox' $checked/>";
        echo __(" Note that Geo-IP related columns (Flag,Region,City) will be hidden in the reports","statcomm");
    }
 */