<?php
class Image_Barcode2_Common 
{
    protected $_barcodeheight;
    protected $_barwidth;

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
    
    public function getWriter() 
    {
        return $this->writer;
    }

    public function setBarcodeHeight($height) 
    {
        $this->_barcodeheight = $height;
    }

    public function setBarWidth($width) 
    {
        $this->_barwidth = $width;
    }

    public function setBarWidthThick($width) 
    {
        $this->_barthickwidth = $width;
    }

    public function setBarWidthThin($width) 
    {
        $this->_barthinwidth = $width;
    }

    public function getBarcodeHeight() 
    {
        return $this->_barcodeheight;
    }

    public function getBarWidth() 
    {
        return $this->_barwidth;
    }

    public function getBarWidthThin()
    {
        return $this->_barthinwidth;
    }

    public function getBarWidthThick()
    {
        return $this->_barthickwidth;
    }
}
