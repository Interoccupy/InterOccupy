<?php
/**
 * Created by WpGetReady
 * User: FZSM
 * Date: 15/02/13
 * Time: 08:26 PM
 * This file override default background location to get flags working on any wordpress theme
 */
//This file is a must, otherwise the file can't be seen for wordpress (!?)
//Reference http://css-tricks.com/css-variables-with-php/
header("Content-type: text/css; charset: UTF-8");
$absolute_path="http://localhost/wpr2012/wp-content/plugins/SC";
?>

.flag {
width: 16px;
height: 11px;
background:url(<?php echo $absolute_path ?>/images/flags.png) no-repeat
}

.os {
width: 16px;
height: 16px;
background:url(<?php echo $absolute_path ?>/images/os.png) no-repeat
}

.uas {
width: 16px;
height: 16px;
background:url(<?php echo $absolute_path ?>/images/uas.png) no-repeat
}