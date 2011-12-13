<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
 * Image_Barcode2_code128 class
 *
 * Renders Code128 barcodes
 * Code128 is a high density encoding for alphanumeric strings.
 * This module prints the Code128B representation of the most common
 * ASCII characters (32 to 134).
 *
 * These are the components of a Code128 Bar code:
 * - 10 Unit Quiet Zone
 * - 6 Unit Start Character
 * - (n * 6) Unit Message
 * - 6 Unit "Check Digit" Character
 * - 7 Unit Stop Character
 * - 10 Unit Quiet Zone
 *
 * I originally wrote this algorithm in Visual Basic 6 for a Rapid 
 * Software Development class, where we printed Code128 B bar codes
 * to read using Cue Cat bar code readers.  I rewrote the algorithm
 * using PHP for inclusion in the PEAR Image_Barcode2 project.
 *
 * The Code128B bar codes produced by the algorithm have been validated
 * using my trusty Cue-Cat bar code reader.
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
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Image_Barcode2
 */

require_once 'Image/Barcode2/Driver.php';
require_once 'Image/Barcode2/Common.php';

/**
 * Code128
 *
 * @category  Image
 * @package   Image_Barcode2
 * @author    Jeffrey K. Brown <jkb@darkfantastic.net>
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link      http://pear.php.net/package/Image_Barcode2
 */

class Image_Barcode2_code128 extends Image_Barcode2_Common implements Image_Barcode2_Driver
{
    var $_code;

    /**
     * Class constructor
     *
     * @param Image_Barcode2_Writer $writer Library to use.
     */
    public function __construct(Image_Barcode2_Writer $writer) 
    {
        parent::__construct($writer);
        $this->setBarcodeHeight(60);
        $this->setBarcodeWidth(1);

        $this->_code[0] = "212222";  // " "
        $this->_code[1] = "222122";  // "!"
        $this->_code[2] = "222221";  // "{QUOTE}"
        $this->_code[3] = "121223";  // "#"
        $this->_code[4] = "121322";  // "$"
        $this->_code[5] = "131222";  // "%"
        $this->_code[6] = "122213";  // "&"
        $this->_code[7] = "122312";  // "'"
        $this->_code[8] = "132212";  // "("
        $this->_code[9] = "221213";  // ")"
        $this->_code[10] = "221312"; // "*"
        $this->_code[11] = "231212"; // "+"
        $this->_code[12] = "112232"; // ","
        $this->_code[13] = "122132"; // "-"
        $this->_code[14] = "122231"; // "."
        $this->_code[15] = "113222"; // "/"
        $this->_code[16] = "123122"; // "0"
        $this->_code[17] = "123221"; // "1"
        $this->_code[18] = "223211"; // "2"
        $this->_code[19] = "221132"; // "3"
        $this->_code[20] = "221231"; // "4"
        $this->_code[21] = "213212"; // "5"
        $this->_code[22] = "223112"; // "6"
        $this->_code[23] = "312131"; // "7"
        $this->_code[24] = "311222"; // "8"
        $this->_code[25] = "321122"; // "9"
        $this->_code[26] = "321221"; // ":"
        $this->_code[27] = "312212"; // ";"
        $this->_code[28] = "322112"; // "<"
        $this->_code[29] = "322211"; // "="
        $this->_code[30] = "212123"; // ">"
        $this->_code[31] = "212321"; // "?"
        $this->_code[32] = "232121"; // "@"
        $this->_code[33] = "111323"; // "A"
        $this->_code[34] = "131123"; // "B"
        $this->_code[35] = "131321"; // "C"
        $this->_code[36] = "112313"; // "D"
        $this->_code[37] = "132113"; // "E"
        $this->_code[38] = "132311"; // "F"
        $this->_code[39] = "211313"; // "G"
        $this->_code[40] = "231113"; // "H"
        $this->_code[41] = "231311"; // "I"
        $this->_code[42] = "112133"; // "J"
        $this->_code[43] = "112331"; // "K"
        $this->_code[44] = "132131"; // "L"
        $this->_code[45] = "113123"; // "M"
        $this->_code[46] = "113321"; // "N"
        $this->_code[47] = "133121"; // "O"
        $this->_code[48] = "313121"; // "P"
        $this->_code[49] = "211331"; // "Q"
        $this->_code[50] = "231131"; // "R"
        $this->_code[51] = "213113"; // "S"
        $this->_code[52] = "213311"; // "T"
        $this->_code[53] = "213131"; // "U"
        $this->_code[54] = "311123"; // "V"
        $this->_code[55] = "311321"; // "W"
        $this->_code[56] = "331121"; // "X"
        $this->_code[57] = "312113"; // "Y"
        $this->_code[58] = "312311"; // "Z"
        $this->_code[59] = "332111"; // "["
        $this->_code[60] = "314111"; // "\"
        $this->_code[61] = "221411"; // "]"
        $this->_code[62] = "431111"; // "^"
        $this->_code[63] = "111224"; // "_"
        $this->_code[64] = "111422"; // "`"
        $this->_code[65] = "121124"; // "a"
        $this->_code[66] = "121421"; // "b"
        $this->_code[67] = "141122"; // "c"
        $this->_code[68] = "141221"; // "d"
        $this->_code[69] = "112214"; // "e"
        $this->_code[70] = "112412"; // "f"
        $this->_code[71] = "122114"; // "g"
        $this->_code[72] = "122411"; // "h"
        $this->_code[73] = "142112"; // "i"
        $this->_code[74] = "142211"; // "j"
        $this->_code[75] = "241211"; // "k"
        $this->_code[76] = "221114"; // "l"
        $this->_code[77] = "413111"; // "m"
        $this->_code[78] = "241112"; // "n"
        $this->_code[79] = "134111"; // "o"
        $this->_code[80] = "111242"; // "p"
        $this->_code[81] = "121142"; // "q"
        $this->_code[82] = "121241"; // "r"
        $this->_code[83] = "114212"; // "s"
        $this->_code[84] = "124112"; // "t"
        $this->_code[85] = "124211"; // "u"
        $this->_code[86] = "411212"; // "v"
        $this->_code[87] = "421112"; // "w"
        $this->_code[88] = "421211"; // "x"
        $this->_code[89] = "212141"; // "y"
        $this->_code[90] = "214121"; // "z"
        $this->_code[91] = "412121"; // "{"
        $this->_code[92] = "111143"; // "|"
        $this->_code[93] = "111341"; // "}"
        $this->_code[94] = "131141"; // "~"
        $this->_code[95] = "114113"; // 95
        $this->_code[96] = "114311"; // 96
        $this->_code[97] = "411113"; // 97
        $this->_code[98] = "411311"; // 98
        $this->_code[99] = "113141"; // 99
        $this->_code[100] = "114131"; // 100
        $this->_code[101] = "311141"; // 101
        $this->_code[102] = "411131"; // 102
    }

    /**
     * Draws a Code128 image barcode
     *
     * @param string $text A text that should be in the image barcode
     *
     * @return image            The corresponding interleaved 2 of 5 image barcode
     *
     * @access public
     *
     * @author Jeffrey K. Brown <jkb@darkfantastic.net>
     *
     * @internal
     * The draw() method is broken into three sections.  First, we take
     * the input string and convert it to a string of barcode widths.
     * Then, we size and allocate the image.  Finally, we print the bars to
     * the image along with the barcode text and display it to the beholder.
     *
     */
    public function draw($text)
    {
        // We start with the Code128 Start Code character.  We
        // initialize checksum to 104, rather than calculate it.
        // We then add the startcode to $allbars, the main string
        // containing the bar sizes for the entire code.
        $startcode = $this->_getStartCode();
        $checksum  = 104;
        $allbars   = $startcode;
        $text      = trim($text);


        // Next, we read the $text string that was passed to the
        // method and for each character, we determine the bar
        // pattern and add it to the end of the $allbars string.
        // In addition, we continually add the character's value
        // to the checksum
        for ($i = 0, $all = strlen($text); $i < $all; ++$i) {
            $char = $text[$i];
            $val = $this->_getCharNumber($char);

            $checksum += ($val * ($i + 1));

            $allbars .= $this->_getCharCode($char);
        }


        // Then, Take the Mod 103 of the total to get the index
        // of the Code128 Check Character.  We get its bar
        // pattern and add it to $allbars in the next section.
        $checkdigit = $checksum % 103;
        $bars = $this->_getNumCode($checkdigit);


        // Finally, we get the Stop Code pattern and put the
        // remaining pieces together.  We are left with the
        // string $allbars containing all of the bar widths
        // and can now think about writing it to the image.

        $stopcode = $this->_getStopCode();
        $allbars = $allbars . $bars . $stopcode;

        //------------------------------------------------------//
        // Next, we will calculate the width of the resulting
        // bar code and size the image accordingly.

        // 10 Pixel "Quiet Zone" in front, and 10 Pixel
        // "Quiet Zone" at the end.
        $barcodewidth = 20;


        // We will read each of the characters (1,2,3,or 4) in
        // the $allbars string and add its width to the running
        // total $barcodewidth.  The height of the barcode is
        // calculated by taking the bar height plus the font height.

        for ($i = 0, $all = strlen($allbars); $i < $all; ++$i) {
            $nval = $allbars[$i];
            $barcodewidth += ($nval * $this->getBarcodeWidth());
        }

        $barcodelongheight = (int)($this->getWriter()->imagefontheight($this->getFontSize()) / 2)
            + $this->getBarcodeHeight();


        // Then, we create the image, allocate the colors, and fill
        // the image with a nice, white background, ready for printing
        // our black bars and the text.

        $img = $this->getWriter()->imagecreate(
            $barcodewidth,
            $barcodelongheight + $this->getWriter()->imagefontheight($this->getFontSize()) + 1
        );
        $black = $this->getWriter()->imagecolorallocate($img, 0, 0, 0);
        $white = $this->getWriter()->imagecolorallocate($img, 255, 255, 255);
        $this->getWriter()->imagefill($img, 0, 0, $white);


        //------------------------------------------------------//
        // Finally, we write our text line centered across the
        // bottom and the bar patterns and display the image.


        // First, print the image, centered across the bottom.
        $this->getWriter()->imagestring(
            $img,
            $this->getFontSize(),
            $barcodewidth / 2 - strlen($text) / 2 * ($this->getWriter()->imagefontwidth($this->getFontSize())),
            $this->getBarcodeHeight() + $this->getWriter()->imagefontheight($this->getFontSize()) / 2,
            $text,
            $black
        );

        // We set $xpos to 10 so we start bar printing after 
        // position 10 to simulate the 10 pixel "Quiet Zone"
        $xpos = 10;

        // We will now process each of the characters in the $allbars
        // array.  The number in each position is read and then alternating
        // black bars and spaces are drawn with the corresponding width.
        $bar = 1;
        for ($i = 0, $all = strlen($allbars); $i < $all; ++$i) {
            $nval = $allbars[$i];
            $width = $nval * $this->getBarcodeWidth();

            if ($bar == 1) {
                $this->getWriter()->imagefilledrectangle(
                    $img, 
                    $xpos, 
                    0, 
                    $xpos + $width - 1, 
                    $barcodelongheight, 
                    $black
                );
                $xpos += $width;
                $bar = 0;
            } else {
                $xpos += $width;
                $bar = 1;
            }
        }

        return $img;
    }


    /**
     * Get the Code128 code for a character
     *
     * @param string $char Chacter
     *
     * @return string
     */
    private function _getCharCode($char)
    {
        return $this->_code[ord($char) - 32];
    }


    /**
     * Get the Start Code for Code128
     *
     * @return string
     */
    private function _getStartCode()
    {
        return '211214';
    }


    /**
     * Get the Stop Code for Code128
     *
     * @return string
     */
    private function _getStopCode()
    {
        return '2331112';
    }


    /**
     * Rhe Code128 code equivalent of a character number
     *
     * @param int $index Index
     *
     * @return string 
     */
    private function _getNumCode($index)
    {
        return $this->_code[$index];
    }


    /**
     * Get the Code128 numerical equivalent of a character.
     *
     * @param string $char Character
     *
     * @return int
     */
    private function _getCharNumber($char)
    {
        return ord($char) - 32;
    }

} // class

?>
