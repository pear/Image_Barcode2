<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */
//
// +----------------------------------------------------------------------+
// | PHP version 4.0                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2001 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Marcelo Subtil Marcal <msmarcal@php.net>                     |
// +----------------------------------------------------------------------+
//
// $Id$
//

require_once("PEAR.php");

/**
 * The Image_Barcode class provides method to create barcode using GD library.
 *
 * @access public
 * @author Marcelo Subtil Marcal <msmarcal@php.net>
 * @since  PHP 4.2.3
 */
class Image_Barcode extends PEAR
{
    /**
     * Draws a image barcode
     *
     * @param  string $text     A text that should be in the image barcode
     * @param  string $type     The barcode type
     * @param  string $imgtype  The image type that will be generated
     *
     * @return image            The corresponding image barcode
     *
     * @access public
     * @author Marcelo Subtil Marcal <msmarcal@php.net>
     * @since  PHP 4.2.3
     */
    function draw($text, $type = 'int25', $imgtype = 'png') {

        @include_once("Image/Barcode/${type}.php");

        $classname = "Image_Barcode_${type}";

        if (!class_exists($classname)) {
            return PEAR::raiseError("Unable to include the Image/Barcode/${type}.php file");
        }

        if (!in_array('draw',get_class_methods($classname))) {
            return PEAR::raiseError("Unable to find create method in '$classname' class");
        }

        @$obj =& new $classname;

        $obj->draw($text, $imgtype);
    }

}

?>
