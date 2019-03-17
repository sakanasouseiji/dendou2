<?php
$result=array();
$array1=array(
			1=>array("主食"=>"白米","おかず1"=>"野菜炒め"),
			2=>array("主食"=>"オートミール","おかず1"=>"無し")
			);

$array3=array("主食"=>"麦飯","おかず1"=>"鯖");
$array4=array("おかず2"=>"ポップコーン");
$array2[]=$array3;
$array2[]=$array4;

$result=$array1+$array2;
print "+の場合\n";
print_r($result);

$result=array_merge($array1,$array2);
print "array_mergeの場合\n";
print_r($result);


$result=array_merge_recursive($array1,$array2);
print "array_merge_recursiveの場合\n";
print_r($result);

?>
