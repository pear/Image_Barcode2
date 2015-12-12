<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
 * Image_Barcode2_Writer class
 *
 * An adapter for the non oo image writing code.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  Image
 * @package   Image_Barcode2
 * @author    Ryan Briones <ryanbriones@webxdesign.org>
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      http://pear.php.net/package/Image_Barcode2
 */
/**
 * Image_Barcode2_Writer class
 *
 * An adapter for the non oo image writing code.
 * Just used to create a seam for phpunit
 *
 * @category  Image
 * @package   Image_Barcode2
 * @author    Ryan Briones <ryanbriones@webxdesign.org>
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Image_Barcode2
 * @todo      See if http://pear.php.net/package/Image_Canvas can be made to work well
 */
class Image_Barcode2_Writer
{
    /**
     * Create a new palette based image
     *
     * @param int $width  The image width
     * @param int $height The image height
     *
     * @return resource
     */
    public function imagecreate($width, $height)
    {
        return imagecreate($width, $height);
    }

    /**
     * Draw a string horizontally
     *
     * @param resource $image  An image resource, returned by one of the image
     *                         creation functions, such as imagecreatetruecolor()
     * @param int      $font   Can be 1, 2, 3, 4, 5 for built-in fonts in latin2
     *                         encoding (where higher numbers corresponding to larger
     *                         fonts) or any of your own font identifiers registered
     *                         with imageloadfont()
     * @param int      $x      x-coordinate of the upper left corner
     * @param int      $y      y-coordinate of the upper left corner
     * @param string   $string The string to be written
     * @param int      $color  A color identifier created with imagecolorallocate()
     *
     * @return bool
     */
    public function imagestring($image, $font, $x, $y, $string, $color)
    {
        return imagestring($image, $font, $x, $y, $string, $color);
    }

    /**
     * Flood fill
     *
     * @param resource $image An image resource, returned by one of the image
     *                        creation functions, such as imagecreatetruecolor()
     * @param int      $x     x-coordinate of start point
     * @param int      $y     y-coordinate of start point
     * @param int      $color The fill color. A color identifier created with
     *                        imagecolorallocate()
     *
     * @return bool
     */
    public function imagefill($image, $x, $y, $color)
    {
        return imagefill($image, $x, $y, $color);
    }

    /**
     * Draw a filled rectangle
     *
     * @param resource $image An image resource, returned by one of the image
     *                        creation functions, such as imagecreatetruecolor()
     * @param int      $x1    x-coordinate for point 1
     * @param int      $y1    y-coordinate for point 1
     * @param int      $x2    x-coordinate for point 2
     * @param int      $y2    y-coordinate for point 2
     * @param int      $color The fill color. A color identifier created with
     *                        imagecolorallocate()
     *
     * @return bool
     */
    public function imagefilledrectangle($image, $x1, $y1, $x2, $y2, $color)
    {
        return imagefilledrectangle($image, $x1, $y1, $x2, $y2, $color);
    }

    /**
     * Get font height
     *
     * @param int $font Can be 1, 2, 3, 4, 5 for built-in fonts in latin2 encoding
     *                  (where higher numbers corresponding to larger fonts) or any
     *                  of your own font identifiers registered with imageloadfont()
     *
     * @return int
     */
    public function imagefontheight($font)
    {
        return imagefontheight($font);
    }

    /**
     * Get font width
     *
     * @param int $font Can be 1, 2, 3, 4, 5 for built-in fonts in latin2 encoding
     *                  (where higher numbers corresponding to larger fonts) or any
     *                  of your own font identifiers registered with imageloadfont()
     *
     * @return int
     */
    public function imagefontwidth($font)
    {
        return imagefontwidth($font);
    }

    /**
     * Allocate a color for an image
     *
     * @param resource $image An image resource, returned by one of the image
     *                        creation functions, such as imagecreatetruecolor()
     * @param int      $red   Value of red component
     * @param int      $green Value of green component
     * @param int      $blue  Value of blue component
     *
     * @return int
     */
    public function imagecolorallocate($image, $red, $green, $blue)
    {
        return imagecolorallocate($image, $red, $green, $blue);
    }

    /**
     * Draw a line
     *
     * @param resource $image An image resource, returned by one of the image
     *                        creation functions, such as imagecreatetruecolor()
     * @param int      $x1    x-coordinate for first point
     * @param int      $y1    y-coordinate for first point
     * @param int      $x2    x-coordinate for second point
     * @param int      $y2    y-coordinate for second point
     * @param int      $color The line color. A color identifier created with
     *                        imagecolorallocate()
     *
     * @return bool
     */
    public function imageline($image, $x1, $y1, $x2, $y2, $color)
    {
        return imageline($image, $x1, $y1, $x2, $y2, $color);
    }
}
