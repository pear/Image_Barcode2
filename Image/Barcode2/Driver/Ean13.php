<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
 * Image_Barcode2_Driver_Ean13 class
 *
 * Renders EAN 13 barcodes
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
 * @author    Didier Fournout <didier.fournout@nyc.fr>
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Image_Barcode2
 */

require_once 'Image/Barcode2/Driver.php';
require_once 'Image/Barcode2/Common.php';
require_once 'Image/Barcode2/Exception.php';

/**
 * EAN 13
 *
 * Package which provides a method to create EAN 13 barcode using GD library.
 *
 * @category  Image
 * @package   Image_Barcode2
 * @author    Didier Fournout <didier.fournout@nyc.fr>
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Image_Barcode2
 * @since     Image_Barcode2 0.4
 */
class Image_Barcode2_Driver_Ean13 extends Image_Barcode2_Common implements Image_Barcode2_Driver
{
    /**
     * Number set
     * @var array
     */
    var $_number_set = array(
           '0' => array(
                    'A' => array(0,0,0,1,1,0,1),
                    'B' => array(0,1,0,0,1,1,1),
                    'C' => array(1,1,1,0,0,1,0)
                        ),
           '1' => array(
                    'A' => array(0,0,1,1,0,0,1),
                    'B' => array(0,1,1,0,0,1,1),
                    'C' => array(1,1,0,0,1,1,0)
                        ),
           '2' => array(
                    'A' => array(0,0,1,0,0,1,1),
                    'B' => array(0,0,1,1,0,1,1),
                    'C' => array(1,1,0,1,1,0,0)
                        ),
           '3' => array(
                    'A' => array(0,1,1,1,1,0,1),
                    'B' => array(0,1,0,0,0,0,1),
                    'C' => array(1,0,0,0,0,1,0)
                        ),
           '4' => array(
                    'A' => array(0,1,0,0,0,1,1),
                    'B' => array(0,0,1,1,1,0,1),
                    'C' => array(1,0,1,1,1,0,0)
                        ),
           '5' => array(
                    'A' => array(0,1,1,0,0,0,1),
                    'B' => array(0,1,1,1,0,0,1),
                    'C' => array(1,0,0,1,1,1,0)
                        ),
           '6' => array(
                    'A' => array(0,1,0,1,1,1,1),
                    'B' => array(0,0,0,0,1,0,1),
                    'C' => array(1,0,1,0,0,0,0)
                        ),
           '7' => array(
                    'A' => array(0,1,1,1,0,1,1),
                    'B' => array(0,0,1,0,0,0,1),
                    'C' => array(1,0,0,0,1,0,0)
                        ),
           '8' => array(
                    'A' => array(0,1,1,0,1,1,1),
                    'B' => array(0,0,0,1,0,0,1),
                    'C' => array(1,0,0,1,0,0,0)
                        ),
           '9' => array(
                    'A' => array(0,0,0,1,0,1,1),
                    'B' => array(0,0,1,0,1,1,1),
                    'C' => array(1,1,1,0,1,0,0)
                        )
        );

    var $_number_set_left_coding = array(
           '0' => array('A','A','A','A','A','A'),
           '1' => array('A','A','B','A','B','B'),
           '2' => array('A','A','B','B','A','B'),
           '3' => array('A','A','B','B','B','A'),
           '4' => array('A','B','A','A','B','B'),
           '5' => array('A','B','B','A','A','B'),
           '6' => array('A','B','B','B','A','A'),
           '7' => array('A','B','A','B','A','B'),
           '8' => array('A','B','A','B','B','A'),
           '9' => array('A','B','B','A','B','A')
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
        $this->setBarcodeWidth(1);
    }


    /**
     * Validate barcode
     * 
     * @throws Image_Barcode2_Exception
     */
    public function validate()
    {
        // Check barcode for invalid characters
        if (!preg_match('/^[0-9]{13}$/', $this->getBarcode())) {
            throw new Image_Barcode2_Exception('Invalid barcode');
        }
    }


    /**
     * Draws a EAN 13 image barcode
     *
     * @return resource            The corresponding EAN13 image barcode
     *
     * @access public
     *
     * @author     Didier Fournout <didier.fournout@nyc.fr>
     */
    public function draw()
    {
        $text = $this->getBarcode();

        // Calculate the barcode width
        $barcodewidth = (strlen($text)) * (7 * $this->getBarcodeWidth())
            + 3 * $this->getBarcodeWidth()  // left
            + 5 * $this->getBarcodeWidth()  // center
            + 3 * $this->getBarcodeWidth() // right
            + $this->getWriter()->imagefontwidth($this->getFontSize()) + 1
            ;

        $barcodelongheight = (int)($this->getWriter()->imagefontheight($this->getFontSize()) / 2)
            + $this->getBarcodeHeight();

        // Create the image
        $img = $this->getWriter()->imagecreate(
            $barcodewidth,
            $barcodelongheight + $this->getWriter()->imagefontheight($this->getFontSize()) + 1
        );

        // Alocate the black and white colors
        $black = $this->getWriter()->imagecolorallocate($img, 0, 0, 0);
        $white = $this->getWriter()->imagecolorallocate($img, 255, 255, 255);

        // Fill image with white color
        $this->getWriter()->imagefill($img, 0, 0, $white);

        // get the first digit which is the key for creating the first 6 bars
        $key = substr($text, 0, 1);

        // Initiate x position
        $xpos = 0;

        // print first digit
        $this->getWriter()->imagestring(
            $img,
            $this->getFontSize(),
            $xpos, 
            $this->getBarcodeHeight(), 
            $key, 
            $black
        );

        $xpos = $this->getWriter()->imagefontwidth($this->getFontSize()) + 1;

        // Draws the left guard pattern (bar-space-bar)
        // bar
        $this->getWriter()->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarcodeWidth() - 1,
            $barcodelongheight, 
            $black
        );
        $xpos += $this->getBarcodeWidth();
        // space
        $xpos += $this->getBarcodeWidth();
        // bar
        $this->getWriter()->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarcodeWidth() - 1,
            $barcodelongheight, 
            $black
        );
        $xpos += $this->getBarcodeWidth();

        // Draw left $text contents
        $set_array = $this->_number_set_left_coding[$key];
        for ($idx = 1; $idx < 7; $idx ++) {
            $value = substr($text, $idx, 1);

            $this->getWriter()->imagestring(
                $img, 
                $this->getFontSize(),
                $xpos + 1, 
                $this->getBarcodeHeight(), 
                $value, 
                $black
            );

            foreach ($this->_number_set[$value][$set_array[$idx - 1]] as $bar) {
                if ($bar) {
                    $this->getWriter()->imagefilledrectangle(
                        $img,
                        $xpos,
                        0,
                        $xpos + $this->getBarcodeWidth() - 1,
                        $this->getBarcodeHeight(),
                        $black
                    );
                }
                $xpos += $this->getBarcodeWidth();
            }
        }

        // Draws the center pattern (space-bar-space-bar-space)
        // space
        $xpos += $this->getBarcodeWidth();
        // bar
        $this->getWriter()->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarcodeWidth() - 1,
            $barcodelongheight,
            $black
        );
        $xpos += $this->getBarcodeWidth();
        // space
        $xpos += $this->getBarcodeWidth();
        // bar
        $this->getWriter()->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarcodeWidth() - 1,
            $barcodelongheight,
            $black
        );
        $xpos += $this->getBarcodeWidth();
        // space
        $xpos += $this->getBarcodeWidth();


        // Draw right $text contents
        for ($idx = 7; $idx < 13; $idx ++) {
            $value = substr($text, $idx, 1);

            $this->getWriter()->imagestring(
                $img,
                $this->getFontSize(),
                $xpos + 1,
                $this->getBarcodeHeight(),
                $value,
                $black
            );

            foreach ($this->_number_set[$value]['C'] as $bar) {
                if ($bar) {
                    $this->getWriter()->imagefilledrectangle(
                        $img,
                        $xpos,
                        0,
                        $xpos + $this->getBarcodeWidth() - 1,
                        $this->getBarcodeHeight(),
                        $black
                    );
                }
                $xpos += $this->getBarcodeWidth();
            }
        }

        // Draws the right guard pattern (bar-space-bar)
        // bar
        $this->getWriter()->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarcodeWidth() - 1,
            $barcodelongheight, 
            $black
        );
        $xpos += $this->getBarcodeWidth();
        // space
        $xpos += $this->getBarcodeWidth();
        // bar
        $this->getWriter()->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarcodeWidth() - 1,
            $barcodelongheight, 
            $black
        );

        return $img;
    } // function create

} // class

?>
