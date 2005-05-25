<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at                              |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Ryan Briones <ryanbriones@webxdesign.org>                   |
// |                                                                      |
// +----------------------------------------------------------------------+
//
// Last Modified: 2003/12/09 - Ryan Briones

/**
 *
 * Image_Barcode_Code39 creates Code 3 of 9 ( Code39 ) barcode images. It's
 * implementation borrows heavily for the perl module GD::Barcode::Code39
 *
 * @author Ryan Briones <ryanbriones@webxdesign.org>
 * @todo add a function to print images directly to browser not using the draw() function
 *
 */


require_once "PEAR.php";
require_once "Image/Barcode.php";

/*
 *
 * the function str_split() has been including, not part of the class for
 * it's ability to emulate Perl's split( //, $text ). This function was
 * stolen from the PHP function documentation comments on PHP's str_split()
 * which is to be included in PHP5.
 *
 * @param string $str
 * @param int number of characters you wish to split on
 * @return array|false Returns an array or false when $num is less than 1
 */
function str_split( $str, $num = '1' ) {
   if($num < 1) return FALSE;
   $arr = array();
   for ($j = 0; $j < strlen($str); $j= $j+$num) {
       $arr[] = substr($str,$j,$num);
   }
   return $arr;
}

class Image_Barcode_Code39 extends Image_Barcode {
	/**
	 * Barcode type
	 * @var string
	 */
	var $_type = 'Code39';
	
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
		
		/*
		 *
		 * constructor
		 *
		 * @param string $text
		 * @param int $wThin Width of the thin lines on the barcode
		 * @param int $wThick Width of the thick lines on the barcode
		 *
		 */
    function Image_Barcode_Code39( $text = '', $wThin = 0, $wThick = 0 ) {
        
        // Check $text for invalid characters
        if ( $this->checkInvalid( $text ) ) {
            return false;
        }

        $this->text = $text;
        if ( $wThin > 0 ) $this->_barthinwidth = $wThin;
        if ( $wThick > 0 ) $this->_barthickwidth = $wThick;

        return true;
    }
		
		/*
		 *
		 * make an image resource using the GD image library
		 *
		 * @param bool $noText Set to true if you'd like your barcode to be sans text
		 * @param int $bHeight height of the barcode image including text
		 * @return resource The Barcode Image (TM)
		 *
		 */
    function plot( $noText = false, $bHeight = 0 ) {
       // add start and stop * characters
       $final_text = "*" . $this->text . "*";
			 
			 if ( $bHeight > 0 ) {
				 $this->_barcodeheight = $bHeight;
			 }
       
       $barcode = '';
       foreach ( str_split( $final_text ) as $character ) {
           $barcode .= $this->_dumpCode( $this->_coding_map[$character] . '0' );
       }
        
       $barcode_len = strlen( $barcode );

       // Create GD image object
       $img = imagecreate( $barcode_len, $this->_barcodeheight );
       
       // Allocate black and white colors to the image
       $black = imagecolorallocate( $img, 0, 0, 0 );
       $white = imagecolorallocate( $img, 255, 255, 255 );
       $font_height = ( $noText ? 0 : imagefontheight( "gdFontSmall" ) );
       $font_width = imagefontwidth( "gdFontSmall" );

       // fill background with white color
       imagefill( $img, 0, 0, $white );

       // Initialize X position
       $xpos = 0;
       
       // draw barcode bars to image
			 if ( $noText ) {
				 foreach ( str_split( $barcode ) as $character_code ) {
						 if ( $character_code == 0 ) {
								 imageline( $img, $xpos, 0, $xpos, $this->_barcodeheight, $white );
						 } else {
								 imageline( $img, $xpos, 0, $xpos, $this->_barcodeheight, $black );
						 }
						 
						 $xpos++;
					}
				} else {
					 foreach ( str_split( $barcode ) as $character_code ) {
							 if ( $character_code == 0 ) {
									 imageline( $img, $xpos, 0, $xpos, $this->_barcodeheight - $font_height - 1, $white );
							 } else {
									 imageline( $img, $xpos, 0, $xpos, $this->_barcodeheight - $font_height - 1, $black );
							 }
							 
							 $xpos++;
						}
						
						// draw text under barcode
						imagestring( $img, "gdFontSmall", 
								( $barcode_len - $font_width * strlen( $this->text ) )/2,
								$this->_barcodeheight - $font_height,
								$this->text,
								$black );
				}

        return $img;
    }

		/*
		 *
		 * send image to the browser; for Image_Barcode compaitbility
		 *
		 * @param string $text
		 * @param string $imgtype Image type; accepts jpg, png, and gif, but gif only works if you've payed for licensing
		 * @param bool $noText Set to true if you'd like your barcode to be sans text
		 * @param int $bHeight height of the barcode image including text
		 * @return bool true or die
		 *
		 */
    function draw( $text, $imgtype = 'png', $noText = false, $bHeight = 0 ) {
				// Check $text for invalid characters
        if ( $this->checkInvalid( $text ) ) {
            return false;
        }
				
				$this->text = $text;
        $img = $this->plot( $noText, $bHeight );

				// Send image to browser
				switch($imgtype) {
								case 'gif':
							header("Content-type: image/gif");
						imagegif($img);
						imagedestroy($img);
						break;
				
					case 'jpg':
						header("Content-type: image/jpg");
						imagejpeg($img);
						imagedestroy($img);
					break;
				
					default:
						header("Content-type: image/png");
						imagepng($img);
						imagedestroy($img);
					break;
				}
				
				return true;
    }

	/*
     *
     * _dumpCode is a PHP implementation of dumpCode from the Perl module
     * GD::Barcode::Code39. I royally screwed up when trying to do the thing
     * my own way the first time. This way works.
		 *
		 * @param string $code Code39 barcode code
		 * @return string $result barcode line code
     *
     * @access private
     *
     */
	function _dumpCode( $code ) {
		$result = '';
		$color = 1;	// 1: Black, 0: White
		
		// if $bit is 1, line is wide; if $bit is 0 line is thin
		foreach ( str_split( $code ) as $bit ) {
			$result .= ( ( $bit == 1 ) ? str_repeat( "$color", $this->_barthickwidth ) : str_repeat( "$color", $this->_barthinwidth ) );
			$color = ( ( $color == 0 ) ? 1 : 0 );
		}
		
		return $result;
	}
	
	/*
	 *
	 * check for invalid characters
	 *
	 * @param string $text
	 * @return bool returns true when invalid characters have been found
	 *
	 */
	function checkInvalid( $text ) {
		return preg_match( "/[^0-9A-Z\-*+\$%\/. ]/", $text );
	}
}

?>
