<?php
interface Image_Barcode2_Driver 
{
    /**
     * Draws a barcode
     *
     * @param string $text A text that should be in the image barcode
     * @return image            The corresponding image barcode
     */
    public function draw($text);

    /**
     * Set the image rendering library.
     *
     * @param Image_Barcode2_Writer $writer Library to use.
     */
    public function setWriter(Image_Barcode2_Writer $writer);
}
