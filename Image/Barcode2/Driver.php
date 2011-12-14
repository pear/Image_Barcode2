<?php
interface Image_Barcode2_Driver 
{
    /**
     * Draws a barcode
     *
     * @return image            The corresponding image barcode
     */
    public function draw();

    /**
     * Set the image rendering library.
     *
     * @param Image_Barcode2_Writer $writer Library to use.
     */
    public function setWriter(Image_Barcode2_Writer $writer);

    /**
     * Set barcode
     *
     * @param string barcode
     */
    public function setBarcode($barcode);

    /**
     * Validate barcode
     * 
     * @throws Image_Barcode2_Exception
     */
    public function validate();
}
