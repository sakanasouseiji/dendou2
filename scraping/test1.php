<?php
require_once("./function.php");
require_once("./function2.php");

$url1="https://ec.cb-asahi.co.jp/category/cat1/%E9%9B%BB%E5%8B%95%E8%87%AA%E8%BB%A2%E8%BB%8A/0?isort=price&view=list";
$url2="https://ec.cb-asahi.co.jp/catalog/products/272993A3DD52464D86DEB010FD40F9C3";

$contents=scraping($url1);
file_put_contents("test1.html",$contents);

$contents2=scraping($url2);
file_put_contents("test2.html",$contents2);




?>
