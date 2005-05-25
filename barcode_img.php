<?php

require_once("Image/Barcode.php");

$num     = '15101967';
$type    = 'int25';
$imgtype = 'png';

$num = isset($_REQUEST) && is_array($_REQUEST) && isset($_REQUEST['num']) ? $_REQUEST['num'] : $num;
$type = isset($_REQUEST) && is_array($_REQUEST) && isset($_REQUEST['type']) ? $_REQUEST['type'] : $type;
$imgtype = isset($_REQUEST) && is_array($_REQUEST) && isset($_REQUEST['imgtype']) ? $_REQUEST['imgtype'] : $imgtype;

Image_Barcode::draw($num, $type, $imgtype);


?>
