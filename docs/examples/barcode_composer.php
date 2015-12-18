<?php
require __DIR__ . '/../../vendor/autoload.php';

$img = \Image_Barcode2::draw(
    '4003994155486',
    \Image_Barcode2::BARCODE_EAN13,
    \Image_Barcode2::IMAGE_PNG,
    false
);

imagepng($img, '/test.png');
