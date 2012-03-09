<?php
class Image_Barcode2_Common
{
    protected $barcodeheight;
    protected $barcodewidth;
    protected $barcodethinwidth;
    protected $barcodethickwidth;
    protected $fontsize = 2;

    /**
     * @var Image_Barcode2_Writer
     */
    protected $writer;
    
    /**
     * @var string barcode
     */
    protected $barcode;


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
     * Get the image rendering library.
     *
     * @return Image_Barcode2_Writer
     */
    public function getWriter() 
    {
        return $this->writer;
    }

    /**
     * Set the barcode
     *
     * @param string $barcode barcode
     */
    public function setBarcode($barcode) 
    {
        $this->barcode = trim($barcode);
    }

    /**
     * Get the barcode
     *
     * @return string
     */
    public function getBarcode() 
    {
        return $this->barcode;
    }

    public function setFontSize($size)
    {
        $this->fontsize = $size;
    }

    public function getFontSize()
    {
        return $this->fontsize;
    }

    public function setBarcodeHeight($height) 
    {
        $this->barcodeheight = $height;
    }

    public function getBarcodeHeight()
    {
        return $this->barcodeheight;
    }

    public function setBarcodeWidth($width)
    {
        $this->barcodewidth = $width;
    }

    public function getBarcodeWidth()
    {
        return $this->barcodewidth;
    }

    public function setBarcodeWidthThick($width)
    {
        $this->barcodethickwidth = $width;
    }

    public function getBarcodeWidthThick()
    {
        return $this->barcodethickwidth;
    }

    public function setBarcodeWidthThin($width)
    {
        $this->barcodethinwidth = $width;
    }

    public function getBarcodeWidthThin()
    {
        return $this->barcodethinwidth;
    }
}
