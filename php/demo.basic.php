<?php

require_once('getid3.php');
$getID3 = new getID3;
$ThisFileInfo = $getID3->analyze('eBook-page-1.swf');
echo '<pre>'.htmlentities(print_r($ThisFileInfo, true)).'</pre>';

?>