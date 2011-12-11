<?php
/**
 * An adapter for the non oo image writing code. Just used to
 * create a seam for phpunit
 *
 * @todo See if there are other packages doing this already
 */
class Image_Barcode2_Writer {
   public function imagestring($image, $font, $x, $y, $string, $color) {
      return imagestring($image, $font, $x, $y, $string, $color;
   }

   public function imagefill($image, $x, $y, $color) {
      return imagefill($image, $x, $y, $color);
   }

   public function imagefilledrectangle($image, $x1, $y1, $x2, $y2, $color) {
      return imagefilledrectangle($image, $x1, $y1, $x2, $y2, $color);
   }
   
   public function imagestring($image, $font, $x , $y, $string, $color) {
      return imagestring($image, $font, $x , $y, $string, $color);
   }
}
