<?php
/**
 * This is a complement for Mustache template including:
 * - template loading, html caching, Queries counter, Queries cached counter
 * 1.7.30: We expanded to be very easy to use and in this way it can reused for subplugins.
 * 1.7.50: Expansion to use Ajax Modules. Now adding javascript before render the module
 * 1.7.60: Adding the concept of metaboxes. Or at least I'm trying...
 */

class viewSystem
{
    const QRY_TOTALS="statcomm_qrytotals";
    const QRY_CACHED="statcomm_cachedtotals";
    const TOTAL_TIME="statcomm_totaltime";
    const MAX_TRANSIENT_TIME = 60; //It is unlikely result needs more time thant this

    private $view;
    private $viewPath;
    private $page;
    private $results;

    private $qryTotals=0;
    private $cachedTotals=0;
    private $totalTime =0;
    private $currenTimer;

    /**
     * Path: rooth pat for xml file, classes and templates, ending with '/'
     * Viewname: file xml where the view is described (WITHOUT xml extension)
     * @param $path
     * @param $viewName
     */
    public function __construct($path,$viewName,$page)
    {
       $filename = $path . $viewName. ".xml";
       //Error if not exists. TODO: IMPROVE use custom Exception
        if (!file_exists($filename))
        {
            trigger_error(sprintf(__("view %s missing","statcomm"),$viewName));
        }
        $this->view=$filename ;
        $this->viewPath=$path;
        $this->page=$page;
        //Initialize array
        $this->results=array();
    }

    /**
     * Not in use yet
     */
    public function ajaxInit()
    {
        $xml = simplexml_load_file($this->view);
        $results=array();
        //define ajax if there is file
        foreach($xml->children() as $child)
        {
            $filename =$this->viewPath. "javascript/$child.js";
            if (file_exists($filename))
            {
                //preload javascript before render
                utilities::fl("LOADING AJAX:", $filename);
                wp_enqueue_script("sc-$child", dirname(plugin_dir_url(__FILE__)) . "/mustache/javascript/$child.js", array('datatables'), FALSE, TRUE);
                //We need to refer the class to use it!
                include_once(dirname(plugin_dir_path(__FILE__)) . "/mustache/classes/$child.php");
                utilities::fl("INCLUDING:",dirname(plugin_dir_path(__FILE__)) . "/mustache/classes/$child.php" );
                add_action("wp_ajax_$child", "$child::Ajax" );//create Ajax invocation
            }
        }
    }


    /**
     * Loops into the view file and render the views.
     */
    public function render()
    {
        $timer= utilities::startTimer();
        $this->currenTimer= utilities::startTimer();

        //TODO: catch error here.
        $xml = simplexml_load_file($this->view);
        //$results=array();

        foreach($xml->children() as $child)
        {
            $filename =$this->viewPath. "javascript/$child.js";
            if (file_exists($filename))
            {
                //preload javascript before render
                utilities::fl("LOADING AJAX:", $filename);
                wp_enqueue_script("sc-$child", dirname(plugin_dir_url(__FILE__)) . "/mustache/javascript/$child.js", array('datatables'), FALSE, TRUE);
            }
        }
        foreach($xml->children() as $child)
        {


            //Something I couldn't figure it out yet. I'm adding metaboxes and for some unexplainable reason
            //the metadata begins to call backward previous modules I already made.
            switch ($child->getName()) {
                case "module":

                    //load module, instantiate and render it.
                    $childName=(string)$child;
                   // utilities::fl("CALLING MODULE", $childName);
                    $obj= $this->runModule($childName);
                    add_meta_box($childName,
                                $obj->title(),
                                array(&$this,"metaboxCallback"),
                                $this->page,
                                'normal',
                                'core',
                                array('module'=>$childName,'obj'=>$obj)
                    );

                    //$obj= $this->runModule((string)$child);
                    //$obj->render($this->viewPath);
                    break;
                default:
                    //Ignore it for now
                    break;
            }
/*
            $row=array();
            //We are assumming that the object is always statcommMustache, so those methods are always present
            $row['module'] = (string)$child;
            $row['time']   = $obj->getTimer();
            $row['queries']   = $obj->getQryCounter();
            $row['cached']   = $obj->getQryCached();

            $this->qryTotals += $obj->getQryCounter();
            $this->cachedTotals += $obj->getQryCached();
            $this->totalTime += utilities::stopTimer($currenTimer);
            $currenTimer = utilities::startTimer(); //start measuring next module
            //$results[] = $row;
            $this->results[]=$row;
            //Save current data to transient. This is can used by any module
            //in fact is used by footermainpage. Also, using transient allows to dispose temporary data.
            set_transient( self::QRY_TOTALS,$qryTotals,60);
            set_transient( self::QRY_CACHED,$cachedTotals,60);
            set_transient( self::TOTAL_TIME,$totalTime,60);
*/
        }
        ?>
         <div id="viewSystem" class="metabox-holder">
        <?php
        do_meta_boxes($this->page,'normal', null);
        ?>
         </div>
    <script type="text/javascript">
        //<![CDATA[
        jQuery(document).ready( function($) {
            // close postboxes that should be closed
            $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
            // postboxes setup
            postboxes.add_postbox_toggles('<?php echo $this->page; ?>');
        });
        //]]>
    </script>
    <?php

        $row=array();
        $row['module'] = 'Totals';
        $row['time']   = utilities::stopTimer($timer);
        $row['queries']   =$this->qryTotals;
        $row['cached']   = $this->cachedTotals;

        $this->results[] = $row;
        //This special module gather all the statistics as last step
        //Enable module only if debugging is on.
        if (utilities::FILE_LOG_DEBUG)
        {
            $lastModule = $this->runModule("statsModule",$this->results);
            $lastModule->render($this->viewPath);
        }

    }

    function metaboxCallback($post,$metabox)
    {

        $child=$metabox['args']['module'];
        $obj = $metabox['args']['obj'];

        $obj->render($this->viewPath);

        $row=array();
        //We are assumming that the object is always statcommMustache, so those methods are always present
        $row['module'] = (string)$child;
        $row['time']   = $obj->getTimer();
        $row['queries']   = $obj->getQryCounter();
        $row['cached']   = $obj->getQryCached();

        $this->qryTotals += $obj->getQryCounter();
        $this->cachedTotals += $obj->getQryCached();
        $this->totalTime += utilities::stopTimer($this->currenTimer);
        $this->currenTimer = utilities::startTimer(); //start measuring next module
        $this->results[]=$row;
        //Save current data to transient. This is can used by any module
        //in fact is used by footermainpage. Also, using transient allows to dispose temporary data.
        set_transient( self::QRY_TOTALS,$this->qryTotals,60);
        set_transient( self::QRY_CACHED,$this->cachedTotals,60);
        set_transient( self::TOTAL_TIME,$this->totalTime,60);


    }

    /**
     * Load module (if exists) and render it. Filename should match class name
     * @param $child
     * @param null $parameters
     * @return object
     * TODO: Custom exception handler.
     */
    private function runModule($child, $parameters = NULL)
    {
        $timer= utilities::startTimer();
        $timeModules="";

        $filename =$this->viewPath. "classes/$child.php";
        if (!file_exists($filename))
        {
            trigger_error(sprintf(__("class %s missing","statcomm"),$child));
        }
        require_once($filename);
        if (!class_exists($child))
        {
            trigger_error(sprintf(__("couldn't find class inside file %s","statcomm"),$child));
        }
        $reflex = new ReflectionClass($child);
        //We check if we pass parameters to the class. If not just create an instance.
        if (empty($parameters))
        {
            $view = $reflex->newInstance();
        }
        else
        {
            //If class needs parameters, send the array when object is instantiated
            $view = $reflex->newInstanceArgs(array($parameters));
        }
        return $view;
    }


    public static function StatcommAjax()
    {
        //This proves that Ajax javascript can be deferred to load before rendering the module(s)
        //Now i'm trying to prove that is possible to add a class dinamically and use the action to hook it
        //the first workaround is to pass to the ajax link a parameter to identify the module we are using.
        //But the challenge is to find way round to call a class loaded using reflection.

        //Check if the module is passed as parameter
        $module = isset($_GET['module'])?$_GET['module']:"";
        //if it is, check if it is available and include it
        if (file_exists(dirname(plugin_dir_path(__FILE__)) . "/mustache/classes/$module.php")) {
            include_once(dirname(plugin_dir_path(__FILE__)) . "/mustache/classes/$module.php");
            $m= new $module; //Instanciate the clas
            if (method_exists($m,"Ajax"))
            {
                $m->Ajax(); //Invoke Ajax method
            }
            else
            {
              throw new Exception ("Error: class $module should have an Ajax method");
            }
        }
        else
        {
            throw new Exception("Ajax Error: class $module is not available");
        }
    }
}

    /**
     * This class handles loading template and merging with the respective class.
     * 20120724: It seems there is a bug related with PHP. Constructors calling mysql queries doesnt' increase
     * counters. This buggy behavior is under analysis.
     * 1.7.60: module title forced to pre-render metaboxes with the proper titles.
     */
abstract class statcommMustache extends Mustache
{
    abstract function templateName(); //force to return template name
    abstract function title(); //force to return module title

    private $timer;
    private $qryCounter; //These values stores and maintain how many queries were spent and cached
    private $qryCached;

    public function render($viewPath)
    {
        $t=$this->templateName();
        $filename = $viewPath. "templates/$t.mustache";
        if (!file_exists($filename)) //search for template in templates folder. TODO: use Custom Exception
        {
            trigger_error(sprintf(__("template %s missing","statcomm"),$t));
        }
        $templateContent=file_get_contents($filename);  //Get the template
        mySql::resetQryCounter();                       //Reset queries counter (and cached counter also)
        $timer=utilities::startTimer();                 //Start timing
        ob_start();                                     //Start caching
        echo parent::render($templateContent);          //Render the template
        ob_end_flush();                                 //Release results
        $this->qryCounter = mySql::qryCounter();        //store qry counter
        $this->qryCached = mySql::qryCounterCached();   //store cached counter
        $this->timer=utilities::stopTimer($timer);      //store time spent
    }

    public function getTimer()       {  return $this->timer; }
    public function getQryCached()   {  return $this->qryCached; }
    public function getQryCounter()  {  return $this->qryCounter; }
} //170