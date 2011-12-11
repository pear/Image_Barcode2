<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
 * Image_Barcode2_int25 class
 *
 * Renders Interleaved 2 of 5 barcodes
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
 * @author    Marcelo Subtil Marcal <msmarcal@php.net>
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Image_Barcode2
 */


/**
 * Image_Barcode2_int25 class
 *
 * Package which provides a method to create Interleaved 2 of 5
 * barcode using GD library.
 *
 * @category  Image
 * @package   Image_Barcode2
 * @author    Marcelo Subtil Marcal <msmarcal@php.net>
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Image_Barcode2
 */
class Image_Barcode2_int25
{
    /**
     * Barcode height
     *
     * @var integer
     */
    var $_barcodeheight = 50;

    /**
     * Bar thin width
     *
     * @var integer
     */
    var $_barthinwidth = 1;

    /**
     * Bar thick width
     *
     * @var integer
     */
    var $_barthickwidth = 3;

    /**
     * Coding map
     * @var array
     */
    var $_coding_map = array(
           '0' => '00110',
           '1' => '10001',
           '2' => '01001',
           '3' => '11000',
           '4' => '00101',
           '5' => '10100',
           '6' => '01100',
           '7' => '00011',
           '8' => '10010',
           '9' => '01010'
        );

    /**
     * @var Image_Barcode2_Writer
     */
    protected $writer;

    /**
     * Class constructor
     *
     * @param Image_Barcode2_Writer $writer Library to use.
     */
    public function __construct(Image_Barcode2_Writer $writer) 
    {
        $this->setWriter($writer);
    }

    /**
     * Set the image rendering library.
     *
     * @param Image_Barcode2_Writer $writer Library to use.
     */
    public function setWriter(Image_Barcode2_Writer $writer) 
    {
        $this->writer = $writer;
    }

    /**
     * Draws a Interleaved 2 of 5 image barcode
     *
     * @param string $text A text that should be in the image barcode
     *
     * @return image            The corresponding Interleaved 2 of 5 image barcode
     *
     * @access public
     *
     * @author Marcelo Subtil Marcal <msmarcal@php.net>
     * @since  Image_Barcode2 0.3
     */

    public function draw($text)
    {
        $text = trim($text);

        if (!preg_match('/[0-9]/', $text)) {
            return 'Invalid text';
        }

        // if odd $text lenght adds a '0' at string beginning
        $text = strlen($text) % 2 ? '0' . $text : $text;

        // Calculate the barcode width
        $barcodewidth = (strlen($text)) 
            * (3 * $this->_barthinwidth + 2 * $this->_barthickwidth)
            + (strlen($text))
            * 2.5
            + (7 * $this->_barthinwidth + $this->_barthickwidth) + 3;

        // Create the image
        $img = $this->writer->imagecreate($barcodewidth, $this->_barcodeheight);

        // Alocate the black and white colors
        $black = $this->writer->imagecolorallocate($img, 0, 0, 0);
        $white = $this->writer->imagecolorallocate($img, 255, 255, 255);

        // Fill image with white color
        $this->writer->imagefill($img, 0, 0, $white);

        // Initiate x position
        $xpos = 0;

        // Draws the leader
        for ($i = 0; $i < 2; $i++) {
            $elementwidth = $this->_barthinwidth;
            $this->writer->imagefilledrectangle(
                $img,
                $xpos,
                0,
                $xpos + $elementwidth - 1,
                $this->_barcodeheight,
                $black
            );
            $xpos += $elementwidth;
            $xpos += $this->_barthinwidth;
            $xpos ++;
        }

        // Draw $text contents
        $all = strlen($text);

        // Draw 2 chars at a time
        for ($idx = 0; $idx < $all; $idx += 2) {
            $oddchar  = substr($text, $idx, 1);
            $evenchar = substr($text, $idx + 1, 1);

            // interleave
            for ($baridx = 0; $baridx < 5; $baridx++) {

                // Draws odd char corresponding bar (black)
                $elementwidth = $this->_barthinwidth;
                if (substr($this->_coding_map[$oddchar], $baridx, 1)) {
                    $elementwidth = $this->_barthickwidt;
                }

                $this->writer->imagefilledrectangle(
                    $img, 
                    $xpos, 
                    0, 
                    $xpos + $elementwidth - 1, 
                    $this->_barcodeheight, 
                    $black
                );

                $xpos += $elementwidth;

                // Left enought space to draw even char (white)
                $elementwidth = $this->_barthinwidth;
                if (substr($this->_coding_map[$evenchar], $baridx, 1)) {
                    $elementwidth = $this->_barthickwidth;
                }

                $xpos += $elementwidth; 
                $xpos ++;
            }
        }


        // Draws the trailer
        $elementwidth = $this->_barthickwidth;
        $this->writer->imagefilledrectangle(
            $img, 
            $xpos, 
            0, 
            $xpos + $elementwidth - 1,
            $this->_barcodeheight, 
            $black
        );
        $xpos += $elementwidth;
        $xpos += $this->_barthinwidth;
        $xpos ++;
        $elementwidth = $this->_barthinwidth;
        $this->writer->imagefilledrectangle(
            $img,
            $xpos,
            0, 
            $xpos + $elementwidth - 1,
            $this->_barcodeheight, 
            $black
        );

        return $img;
    }

} // class

?>
