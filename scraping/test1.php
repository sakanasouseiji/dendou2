<?php
require_once("./function.php");

//$url="https://ec.cb-asahi.co.jp/category/cat1/%E9%9B%BB%E5%8B%95%E8%87%AA%E8%BB%A2%E8%BB%8A/0?isort=price&view=list";
$url="https://ec.cb-asahi.co.jp/catalog/products/272993A3DD52464D86DEB010FD40F9C3";

$contents=file_get_contents($url);
file_put_contents("test1.html",$contents);

$contents2=scraping($url);
file_put_contents("test2.html",$contents2);


?>
