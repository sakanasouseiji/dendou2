<?php
$result=array();
$array1=array("主食"=>"白米","おかず1"=>"野菜炒め");
$array2=array("主食"=>"オートミール","おかず1"=>"無し");
$array3=array("主食"=>"麦飯","おかず1"=>"鯖");
$add=array("おかず2"=>"ポップコーン");

$result+=$array1;
$result+=$array2;
$result+=$array3;
$result+=$add;
print_r($result);
?>
