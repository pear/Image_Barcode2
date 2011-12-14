<?php
interface Image_Barcode2_DualWidth
{
    public function setBarcodeWidthThick($width);
    public function setBarcodeWidthThin($width);
    public function getBarcodeWidthThin();
    public function getBarcodeWidthThick();
}
