<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
 * Image_Barcode2_Driver_Postnet class
 *
 * Renders PostNet barcodes
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
 * @author    Josef "Jeff" Sipek <jeffpc@optonline.net>
 * @copyright 2005 Josef "Jeff" Sipek
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Image_Barcode2
 */

 /*
  * Note:
  *
  * The generated barcode must fit the following criteria to be useable
  * by the USPS scanners:
  *
  * When printed, the dimensions should be:
  *
  *     tall bar:       1/10 inches     = 2.54 mm
  *  short bar:      1/20 inches     = 1.27 mm
  *  density:        22 bars/inch    = 8.66 bars/cm
  */

require_once 'Image/Barcode2/Driver.php';
require_once 'Image/Barcode2/Common.php';
require_once 'Image/Barcode2/DualHeight.php';
require_once 'Image/Barcode2/Exception.php';

/**
 * PostNet
 *
 * Package which provides a method to create PostNet barcode using GD library.
 *
 * @category  Image
 * @package   Image_Barcode2
 * @author    Josef "Jeff" Sipek <jeffpc@optonline.net>
 * @copyright 2005 Josef "Jeff" Sipek
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Image_Barcode2
 */
class Image_Barcode2_Driver_Postnet extends Image_Barcode2_Common implements Image_Barcode2_Driver, Image_Barcode2_DualHeight
{
    /**
     * Bar short height
     *
     * @var integer
     */
    var $_barshortheight = 7;

    /**
     * Bar tall height
     *
     * @var integer
     */
    var $_bartallheight = 15;

    /**
     * Coding map
     * @var array
     */
    var $_coding_map = array(
           '0' => '11000',
           '1' => '00011',
           '2' => '00101',
           '3' => '00110',
           '4' => '01001',
           '5' => '01010',
           '6' => '01100',
           '7' => '10001',
           '8' => '10010',
           '9' => '10100'
        );

    /**
     * Class constructor
     *
     * @param Image_Barcode2_Writer $writer Library to use.
     */
    public function __construct(Image_Barcode2_Writer $writer) 
    {
        parent::__construct($writer);
        $this->setBarcodeWidth(2);
    }


    /**
     * Validate barcode
     * 
     * @throws Image_Barcode2_Exception
     */
    public function validate()
    {
        // Check barcode for invalid characters
        if (!preg_match('/^[0-9]+$/', $this->getBarcode())) {
            throw new Image_Barcode2_Exception('Invalid barcode');
        }
    }


    /**
     * Draws a PostNet image barcode
     *
     * @return resource            The corresponding PostNet image barcode
     *
     * @access public
     *
     * @author Josef "Jeff" Sipek <jeffpc@optonline.net>
     * @since  Image_Barcode2 0.3
     */
    public function draw()
    {
        $text = $this->getBarcode();

        // Calculate the barcode width
        $barcodewidth = (strlen($text)) * 2 * 5 * $this->getBarcodeWidth()
            + $this->getBarcodeWidth() * 3;

        // Create the image
        $img = $this->getWriter()->imagecreate($barcodewidth, $this->_bartallheight);

        // Alocate the black and white colors
        $black = $this->getWriter()->imagecolorallocate($img, 0, 0, 0);
        $white = $this->getWriter()->imagecolorallocate($img, 255, 255, 255);

        // Fill image with white color
        $this->getWriter()->imagefill($img, 0, 0, $white);

        // Initiate x position
        $xpos = 0;

        // Draws the leader
        $this->getWriter()->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarcodeWidth() - 1,
            $this->_bartallheight,
            $black
        );

        $xpos += 2 * $this->getBarcodeWidth();

        // Draw $text contents
        for ($idx = 0, $all = strlen($text); $idx < $all; $idx++) {
            $char = substr($text, $idx, 1);

            for ($baridx = 0; $baridx < 5; $baridx++) {
                $elementheight = $this->_barshortheight;

                if (substr($this->_coding_map[$char], $baridx, 1)) {
                    $elementheight = 0;
                }

                $this->getWriter()->imagefilledrectangle(
                    $img, 
                    $xpos, 
                    $elementheight,
                    $xpos + $this->getBarcodeWidth() - 1,
                    $this->_bartallheight,
                    $black
                );

                $xpos += 2 * $this->getBarcodeWidth();
            }
        }

        // Draws the trailer
        $this->getWriter()->imagefilledrectangle(
            $img, 
            $xpos, 
            0, 
            $xpos + $this->getBarcodeWidth() - 1,
            $this->_bartallheight,
            $black
        );

        return $img;
    }

} // class

?>
