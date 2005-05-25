<html>
<head>
<title>Image_Barcode Class Test</title>
<body background="#FFFFFF">

<h1>Image_Barcode Class test</h1>

<?php

$num = "019671015005";

$num = isset($_REQUEST) && is_array($_REQUEST) && isset($_REQUEST['num']) ? $_REQUEST['num'] : $num;

echo "Test number: <b>$num</b>\n<p>\n";

?>

Interleave 2 of 5 (png):<br>
<img src="barcode_img.php?num=<?php echo($num)?>&type=int25&imgtype=png" alt="PNG: <?php echo($num)?>" title="PNG: <?php echo($num)?>">
<p>
Ean13 (png):<br>
<img src="barcode_img.php?num=<?php echo($num)?>&type=ean13&imgtype=png" alt="PNG: <?php echo($num)?>" title="PNG: <?php echo($num)?>">
<p>
Code39 (png):<br>
<img src="barcode_img.php?num=<?php echo($num)?>&type=Code39&imgtype=png" alt="PNG: <?php echo($num)?>" title="PNG: <?php echo($num)?>">
<p>
UPC-A (png):<br>
<img src="barcode_img.php?num=<?php echo($num)?>&type=upca&imgtype=png" alt="PNG: <?php echo($num)?>" title="PNG: <?php echo($num)?>">

</body>
</html>
