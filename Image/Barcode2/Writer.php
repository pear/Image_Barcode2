<?php
/**
 * An adapter for the non oo image writing code. Just used to
 * create a seam for phpunit
 *
 * @todo See if http://pear.php.net/package/Image_Canvas can be made
 *       to work well
 */
class Image_Barcode2_Writer
{
    public function imagecreate($width, $height) {
        return imagecreate($width, $height);
    }

    public function imagestring($image, $font, $x, $y, $string, $color) {
        return imagestring($image, $font, $x, $y, $string, $color);
    }

    public function imagefill($image, $x, $y, $color) {
        return imagefill($image, $x, $y, $color);
    }

    public function imagefilledrectangle($image, $x1, $y1, $x2, $y2, $color) {
        return imagefilledrectangle($image, $x1, $y1, $x2, $y2, $color);
    }

    public function imagefontheight($font) {
        return imagefontheight($font);
    }

    public function imagefontwidth($font) {
        return imagefontwidth($font);
    }

    public function imagecolorallocate($image, $red, $green, $blue) {
        return imagecolorallocate($image, $red, $green, $blue);
    }

    public function imageline($image, $x1, $y1, $x2, $y2, $color) {
        return imageline($image, $x1, $y1, $x2, $y2, $color);
    }
}
