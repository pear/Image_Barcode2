<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
 * Image_Barcode2_upca class
 *
 * Renders UPC-A barcodes
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
 * @author    Jeffrey K. Brown <jkb@darkfantastic.net>
 * @author    Didier Fournout <didier.fournout@nyc.fr>
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Image_Barcode2
 */

require_once 'Image/Barcode2/Driver.php';

/**
 * Image_Barcode2_upca class
 *
 * Package which provides a method to create UPC-A barcode using GD library.
 *
 * Slightly Modified ean13.php to get upca.php I needed a way to print
 * UPC-A bar codes on a PHP page.  The Image_Barcode2 class seemed like
 * the best way to do it, so I modified ean13 to print in the UPC-A format.
 * Checked the bar code tables against some documentation below (no errors)
 * and validated the changes with my trusty cue-cat.
 * http://www.indiana.edu/~atmat/units/barcodes/bar_t4.htm
 *
 * @category  Image
 * @package   Image_Barcode2
 * @author    Jeffrey K. Brown <jkb@darkfantastic.net>
 * @author    Didier Fournout <didier.fournout@nyc.fr>
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Image_Barcode2
 */
class Image_Barcode2_upca implements Image_Barcode2_Driver
{
    /**
     * Barcode height
     *
     * @var integer
     */
    var $_barcodeheight = 50;

    /**
     * Font use to display text
     *
     * @var integer
     */
    var $_font = 2;  // gd internal small font

    /**
     * Bar width
     *
     * @var integer
     */
    var $_barwidth = 1;


    /**
     * Number set
     * @var array
     */
    var $_number_set = array(
           '0' => array(
                    'L' => array(0,0,0,1,1,0,1),
                    'R' => array(1,1,1,0,0,1,0)
                        ),
           '1' => array(
                    'L' => array(0,0,1,1,0,0,1),
                    'R' => array(1,1,0,0,1,1,0)
                        ),
           '2' => array(
                    'L' => array(0,0,1,0,0,1,1),
                    'R' => array(1,1,0,1,1,0,0)
                        ),
           '3' => array(
                    'L' => array(0,1,1,1,1,0,1),
                    'R' => array(1,0,0,0,0,1,0)
                        ),
           '4' => array(
                    'L' => array(0,1,0,0,0,1,1),
                    'R' => array(1,0,1,1,1,0,0)
                        ),
           '5' => array(
                    'L' => array(0,1,1,0,0,0,1),
                    'R' => array(1,0,0,1,1,1,0)
                        ),
           '6' => array(
                    'L' => array(0,1,0,1,1,1,1),
                    'R' => array(1,0,1,0,0,0,0)
                        ),
           '7' => array(
                    'L' => array(0,1,1,1,0,1,1),
                    'R' => array(1,0,0,0,1,0,0)
                        ),
           '8' => array(
                    'L' => array(0,1,1,0,1,1,1),
                    'R' => array(1,0,0,1,0,0,0)
                        ),
           '9' => array(
                    'L' => array(0,0,0,1,0,1,1),
                    'R' => array(1,1,1,0,1,0,0)
                        )
        );

    /**
     * Draws a UPC-A image barcode
     *
     * @param string $text A text that should be in the image barcode
     *
     * @return image            The corresponding Interleaved 2 of 5 image barcode
     *
     * @author  Jeffrey K. Brown <jkb@darkfantastic.net>
     * @author  Didier Fournout <didier.fournout@nyc.fr>
     */
    public function draw($text)
    {
        $text = trim($text);

        if (!preg_match('/[0-9]{12}/', $text)) {
            return 'Invalid text';
        }


        // Calculate the barcode width
        $barcodewidth = (strlen($text)) * (7 * $this->_barwidth)
            + 3 // left
            + 5 // center
            + 3 // right
            + imagefontwidth($this->_font) + 1
            + imagefontwidth($this->_font) + 1   // check digit's padding
            ;


        $barcodelongheight = (int) (imagefontheight($this->_font) / 2) 
            + $this->_barcodeheight;

        // Create the image
        $img = imagecreate(
            $barcodewidth,
            $barcodelongheight + imagefontheight($this->_font) + 1
        );

        // Alocate the black and white colors
        $black = imagecolorallocate($img, 0, 0, 0);
        $white = imagecolorallocate($img, 255, 255, 255);

        // Fill image with white color
        imagefill($img, 0, 0, $white);

        // get the first digit which is the key for creating the first 6 bars
        $key = substr($text, 0, 1);

        // Initiate x position
        $xpos = 0;

        // print first digit
        imagestring($img, $this->_font, $xpos, $this->_barcodeheight, $key, $black);
        $xpos = imagefontwidth($this->_font) + 1;


        // Draws the left guard pattern (bar-space-bar)
        // bar
        imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->_barwidth - 1,
            $barcodelongheight,
            $black
        );

        $xpos += $this->_barwidth;
        // space
        $xpos += $this->_barwidth;
        // bar
        imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->_barwidth - 1,
            $barcodelongheight,
            $black
        );

        $xpos += $this->_barwidth;


        foreach ($this->_number_set[$key]['L'] as $bar) { 
            if ($bar) {
                imagefilledrectangle(
                    $img, 
                    $xpos, 
                    0, 
                    $xpos + $this->_barwidth - 1,
                    $barcodelongheight, $black
                );
            }
            $xpos += $this->_barwidth;
        }



        // Draw left $text contents
        for ($idx = 1; $idx < 6; $idx ++) {
            $value = substr($text, $idx, 1);
            imagestring(
                $img,
                $this->_font,
                $xpos+1,
                $this->_barcodeheight,
                $value, 
                $black
            );

            foreach ($this->_number_set[$value]['L'] as $bar) { 
                if ($bar) {
                    imagefilledrectangle(
                        $img,
                        $xpos, 
                        0, 
                        $xpos + $this->_barwidth - 1,
                        $this->_barcodeheight,
                        $black
                    );
                }
                $xpos += $this->_barwidth;
            }
        }


        // Draws the center pattern (space-bar-space-bar-space)
        // space
        $xpos += $this->_barwidth;
        // bar
        imagefilledrectangle(
            $img,
            $xpos, 
            0, 
            $xpos + $this->_barwidth - 1,
            $this->_barcodeheight,
            $black
        );
        $xpos += $this->_barwidth;
        // space
        $xpos += $this->_barwidth;
        // bar
        imagefilledrectangle(
            $img,
            $xpos, 
            0, 
            $xpos + $this->_barwidth - 1,
            $this->_barcodeheight,
            $black
        );
        $xpos += $this->_barwidth;
        // space
        $xpos += $this->_barwidth;


        // Draw right $text contents
        for ($idx = 6; $idx < 11; $idx ++) {
            $value = substr($text, $idx, 1);
            imagestring(
                $img,
                $this->_font,
                $xpos + 1,
                $this->_barcodeheight,
                $value,
                $black
            );
            foreach ($this->_number_set[$value]['R'] as $bar) {
                if ($bar) {
                    imagefilledrectangle(
                        $img,
                        $xpos, 
                        0, 
                        $xpos + $this->_barwidth - 1,
                        $this->_barcodeheight,
                        $black
                    );
                }
                $xpos += $this->_barwidth;
            }
        }



        $value = substr($text, 11, 1);
        foreach ($this->_number_set[$value]['R'] as $bar) {
            if ($bar) {
                imagefilledrectangle(
                    $img,
                    $xpos, 
                    0, 
                    $xpos + $this->_barwidth - 1,
                    $this->_barcodeheight,
                    $black
                );

            }
            $xpos += $this->_barwidth;
        }



        // Draws the right guard pattern (bar-space-bar)
        // bar
        imagefilledrectangle(
            $img,
            $xpos, 
            0, 
            $xpos + $this->_barwidth - 1,
            $this->_barcodeheight,
            $black
        );

        $xpos += $this->_barwidth;
        // space
        $xpos += $this->_barwidth;
        // bar
        imagefilledrectangle(
            $img,
            $xpos, 
            0, 
            $xpos + $this->_barwidth - 1,
            $this->_barcodeheight,
            $black
        );

        $xpos += $this->_barwidth;


        // Print Check Digit
        imagestring(
            $img,
            $this->_font,
            $xpos + 1,
            $this->_barcodeheight,
            $value,
            $black
        );

        return $img;
    }

} // class

?>
