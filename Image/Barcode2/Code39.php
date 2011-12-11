<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
 * Image_Barcode2_Code39 class
 *
 * Image_Barcode2_Code39 creates Code 3 of 9 ( Code39 ) barcode images. It's
 * implementation borrows heavily for the perl module GD::Barcode::Code39
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
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Image_Barcode2
 */

require_once 'Image/Barcode2/Driver.php';
require_once 'Image/Barcode2/Common.php';
require_once 'Image/Barcode2/DualWidth.php';

/**
 * Image_Barcode2_Code39 class
 *
 * Package which provides a method to create Code39 using GD library.
 *
 * @category  Image
 * @package   Image_Barcode2
 * @author    Ryan Briones <ryanbriones@webxdesign.org>
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Image_Barcode2
 * @since     Image_Barcode2 0.5
 */
class Image_Barcode2_Code39 extends Image_Barcode2_Common implements Image_Barcode2_Driver, Image_Barcode2_DualWidth
{
    /**
     * Font size
     *
     * @var integer
     */
    var $_font_size = 2;

    /**
     * Coding map
     * @var array
     */
    var $_coding_map = array(
        '0' => '000110100',
        '1' => '100100001',
        '2' => '001100001',
        '3' => '101100000',
        '4' => '000110001',
        '5' => '100110000',
        '6' => '001110000',
        '7' => '000100101',
        '8' => '100100100',
        '9' => '001100100',
        'A' => '100001001',
        'B' => '001001001',
        'C' => '101001000',
        'D' => '000011001',
        'E' => '100011000',
        'F' => '001011000',
        'G' => '000001101',
        'H' => '100001100',
        'I' => '001001100',
        'J' => '000011100',
        'K' => '100000011',
        'L' => '001000011',
        'M' => '101000010',
        'N' => '000010011',
        'O' => '100010010',
        'P' => '001010010',
        'Q' => '000000111',
        'R' => '100000110',
        'S' => '001000110',
        'T' => '000010110',
        'U' => '110000001',
        'V' => '011000001',
        'W' => '111000000',
        'X' => '010010001',
        'Y' => '110010000',
        'Z' => '011010000',
        '-' => '010000101',
        '*' => '010010100',
        '+' => '010001010',
        '$' => '010101000',
        '%' => '000101010',
        '/' => '010100010',
        '.' => '110000100',
        ' ' => '011000100'
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
        $this->setBarWidthThin(1);
        $this->setBarWidthThick(3);
    }

   /**
    * Make an image resource using the GD image library
    *
    * @param    string $text        A text that should be in the image barcode
    * @return   resource           The Barcode Image (TM)
    *
    * @author   Ryan Briones <ryanbriones@webxdesign.org>
    *
    */
    private function _plot($text)
    {
        // add start and stop * characters
        $final_text = '*' . $text . '*';

        $barcode = '';
        foreach (str_split($final_text) as $character) {
            $barcode .= $this->_dumpCode($this->_coding_map[$character] . '0');
        }

        $barcode_len = strlen($barcode);

        // Create GD image object
        $img = $this->writer->imagecreate($barcode_len, $this->getBarcodeHeight());

        // Allocate black and white colors to the image
        $black = $this->writer->imagecolorallocate($img, 0, 0, 0);
        $white = $this->writer->imagecolorallocate($img, 255, 255, 255);
        $font_height = $this->writer->imagefontheight($this->_font_size);
        $font_width = $this->writer->imagefontwidth($this->_font_size);

        // fill background with white color
        $this->writer->imagefill($img, 0, 0, $white);

        // Initialize X position
        $xpos = 0;

        // draw barcode bars to image
        foreach (str_split($barcode) as $character_code) {
            if ($character_code == 0) {
                $this->writer->imageline(
                    $img, 
                    $xpos, 
                    0, 
                    $xpos, 
                    $this->getBarcodeHeight() - $font_height - 1, 
                    $white
                );
            } else {
                $this->writer->imageline(
                    $img, 
                    $xpos, 
                    0, 
                    $xpos, 
                    $this->getBarcodeHeight() - $font_height - 1, 
                    $black
                );
            }

            $xpos++;
        }

        // draw text under barcode
        $this->writer->imagestring(
            $img,
            $this->_font_size,
            ($barcode_len - $font_width * strlen($this->text)) / 2,
            $this->getBarcodeHeight() - $font_height,
            $text,
            $black
        );


        return $img;
    }


    /**
     * Send image to the browser; for Image_Barcode2 compatibility
     *
     * @param string $text Text to render
     *
     * @return   gd_image            GD image object
     * @author   Ryan Briones <ryanbriones@webxdesign.org>
     */
    public function draw($text)
    {
        $text = trim($text);

        // Check $text for invalid characters
        if ($this->_checkInvalid($text)) {
            return 'Invalid text';
        }

        return $this->_plot($text);
    }


    /**
     * _dumpCode is a PHP implementation of dumpCode from the Perl module
     * GD::Barcode::Code39. I royally screwed up when trying to do the thing
     * my own way the first time. This way works.
     *
     * @param string $code Code39 barcode code
     *
     * @return  string $result      barcode line code
     * @access  private
     * @author   Ryan Briones <ryanbriones@webxdesign.org>
     *
     *
     */
    private function _dumpCode($code)
    {
        $result = '';
        $color = 1; // 1: Black, 0: White

        // if $bit is 1, line is wide; if $bit is 0 line is thin
        foreach (str_split($code) as $bit) {
            if ($bit == 1) {
                $result .= str_repeat($color, $this->getBarWidthThick());
            } else {
                $result .= str_repeat($color, $this->getBarWidthThin());
            }

            $color = ($color == 0) ? 1 : 0;
        }

        return $result;
    }


    /**
     * Check for invalid characters
     *
     * @param string $text text to be ckecked
     *
     * @return  bool            returns true when invalid characters have been found
     * @author  Ryan Briones <ryanbriones@webxdesign.org>
     *
     */
    private function _checkInvalid($text)
    {
        return preg_match("/[^0-9A-Z\-*+\$%\/. ]/", $text);
    }
}

?>
