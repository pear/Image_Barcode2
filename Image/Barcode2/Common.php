<?php
class Image_Barcode2_Common 
{
    protected $_barcodeheight;
    protected $_barcodewidth;
    protected $_barcodethinwidth;
    protected $_barcodethickwidth;
    protected $_fontsize = 2;

    /**
     * @var Image_Barcode2_Writer
     */
    protected $_writer;
    
    /**
     * @var Barcode
     */
    protected $_barcode;


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
        $this->_writer = $writer;
    }
    
    public function getWriter() 
    {
        return $this->_writer;
    }

    /**
     * @param string barcode
     */
    public function setBarcode($barcode) 
    {
        $this->_barcode = trim($barcode);
    }

    public function getBarcode() 
    {
        return $this->_barcode;
    }

    public function setFontSize($size)
    {
        $this->_fontsize = $size;
    }

    public function getFontSize()
    {
        return $this->_fontsize;
    }

    public function setBarcodeHeight($height) 
    {
        $this->_barcodeheight = $height;
    }

    public function getBarcodeHeight()
    {
        return $this->_barcodeheight;
    }

    public function setBarcodeWidth($width)
    {
        $this->_barcodewidth = $width;
    }

    public function getBarcodeWidth()
    {
        return $this->_barcodewidth;
    }

    public function setBarcodeWidthThick($width)
    {
        $this->_barcodethickwidth = $width;
    }

    public function getBarcodeWidthThick()
    {
        return $this->_barcodethickwidth;
    }

    public function setBarcodeWidthThin($width)
    {
        $this->_barcodethinwidth = $width;
    }

    public function getBarcodeWidthThin()
    {
        return $this->_barcodethinwidth;
    }
}
