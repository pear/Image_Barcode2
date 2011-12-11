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
require_once 'Image/Barcode2/Common.php';

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
class Image_Barcode2_upca extends Image_Barcode2_Common implements Image_Barcode2_Driver
{
    /**
     * Font use to display text
     *
     * @var integer
     */
    var $_font = 2;  // gd internal small font

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
     * Class constructor
     *
     * @param Image_Barcode2_Writer $writer Library to use.
     */
    public function __construct(Image_Barcode2_Writer $writer) 
    {
        parent::__construct($writer);
        $this->setBarcodeHeight(50);
        $this->setBarWidth(1);
    }

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
        $barcodewidth = (strlen($text)) * (7 * $this->getBarWidth())
            + 3 // left
            + 5 // center
            + 3 // right
            + $this->writer->imagefontwidth($this->_font) + 1
            + $this->writer->imagefontwidth($this->_font) + 1 // check digit padding
            ;


        $barcodelongheight = (int)($this->writer->imagefontheight($this->_font) / 2) 
            + $this->getBarcodeHeight();

        // Create the image
        $img = $this->writer->imagecreate(
            $barcodewidth,
            $barcodelongheight + $this->writer->imagefontheight($this->_font) + 1
        );

        // Alocate the black and white colors
        $black = $this->writer->imagecolorallocate($img, 0, 0, 0);
        $white = $this->writer->imagecolorallocate($img, 255, 255, 255);

        // Fill image with white color
        $this->writer->imagefill($img, 0, 0, $white);

        // get the first digit which is the key for creating the first 6 bars
        $key = substr($text, 0, 1);

        // Initiate x position
        $xpos = 0;

        // print first digit
        $this->writer->imagestring(
            $img,
            $this->_font,
            $xpos,
            $this->getBarcodeHeight(),
            $key,
            $black
        );
        $xpos = $this->writer->imagefontwidth($this->_font) + 1;


        // Draws the left guard pattern (bar-space-bar)
        // bar
        $this->writer->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarWidth() - 1,
            $barcodelongheight,
            $black
        );

        $xpos += $this->getBarWidth();
        // space
        $xpos += $this->getBarWidth();
        // bar
        $this->writer->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarWidth() - 1,
            $barcodelongheight,
            $black
        );

        $xpos += $this->getBarWidth();


        foreach ($this->_number_set[$key]['L'] as $bar) { 
            if ($bar) {
                $this->writer->imagefilledrectangle(
                    $img, 
                    $xpos, 
                    0, 
                    $xpos + $this->getBarWidth() - 1,
                    $barcodelongheight, $black
                );
            }
            $xpos += $this->getBarWidth();
        }



        // Draw left $text contents
        for ($idx = 1; $idx < 6; $idx ++) {
            $value = substr($text, $idx, 1);
            $this->writer->imagestring(
                $img,
                $this->_font,
                $xpos+1,
                $this->getBarcodeHeight(),
                $value, 
                $black
            );

            foreach ($this->_number_set[$value]['L'] as $bar) { 
                if ($bar) {
                    $this->writer->imagefilledrectangle(
                        $img,
                        $xpos, 
                        0, 
                        $xpos + $this->getBarWidth() - 1,
                        $this->getBarcodeHeight(),
                        $black
                    );
                }
                $xpos += $this->getBarWidth();
            }
        }


        // Draws the center pattern (space-bar-space-bar-space)
        // space
        $xpos += $this->getBarWidth();
        // bar
        $this->writer->imagefilledrectangle(
            $img,
            $xpos, 
            0, 
            $xpos + $this->getBarWidth() - 1,
            $this->getBarcodeHeight(),
            $black
        );
        $xpos += $this->getBarWidth();
        // space
        $xpos += $this->getBarWidth();
        // bar
        $this->writer->imagefilledrectangle(
            $img,
            $xpos, 
            0, 
            $xpos + $this->getBarWidth() - 1,
            $this->getBarcodeHeight(),
            $black
        );
        $xpos += $this->getBarWidth();
        // space
        $xpos += $this->getBarWidth();


        // Draw right $text contents
        for ($idx = 6; $idx < 11; $idx ++) {
            $value = substr($text, $idx, 1);
            $this->writer->imagestring(
                $img,
                $this->_font,
                $xpos + 1,
                $this->getBarcodeHeight(),
                $value,
                $black
            );
            foreach ($this->_number_set[$value]['R'] as $bar) {
                if ($bar) {
                    $this->writer->imagefilledrectangle(
                        $img,
                        $xpos, 
                        0, 
                        $xpos + $this->getBarWidth() - 1,
                        $this->getBarcodeHeight(),
                        $black
                    );
                }
                $xpos += $this->getBarWidth();
            }
        }



        $value = substr($text, 11, 1);
        foreach ($this->_number_set[$value]['R'] as $bar) {
            if ($bar) {
                $this->writer->imagefilledrectangle(
                    $img,
                    $xpos, 
                    0, 
                    $xpos + $this->getBarWidth() - 1,
                    $this->getBarcodeHeight(),
                    $black
                );

            }
            $xpos += $this->getBarWidth();
        }



        // Draws the right guard pattern (bar-space-bar)
        // bar
        $this->writer->imagefilledrectangle(
            $img,
            $xpos, 
            0, 
            $xpos + $this->getBarWidth() - 1,
            $this->getBarcodeHeight(),
            $black
        );

        $xpos += $this->getBarWidth();
        // space
        $xpos += $this->getBarWidth();
        // bar
        $this->writer->imagefilledrectangle(
            $img,
            $xpos, 
            0, 
            $xpos + $this->getBarWidth() - 1,
            $this->getBarcodeHeight(),
            $black
        );

        $xpos += $this->getBarWidth();


        // Print Check Digit
        $this->writer->imagestring(
            $img,
            $this->_font,
            $xpos + 1,
            $this->getBarcodeHeight(),
            $value,
            $black
        );

        return $img;
    }

} // class

?>
