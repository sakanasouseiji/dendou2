<?php
/*
$array1=array(	"果物"=>array("りんご","みかん","すいか","メロン"),
				"お菓子"=>array("ポテチ","どら焼き","キャラメルコーン","羊羹"),
				"ご飯"=>array("麦飯","白米","オートミール","ラーメン"),
				"野菜"=>array("キャベツ","ピーマン","じゃがいも","白菜"),
				"さかな"=>array("鯖","マグロ","イカ","鮭")
);
$array2=array(	"おかず"=>array("鯖缶","ツナ缶","野菜炒め","焼き魚"),
				"サプリメント"=>array("ウィダー","プロティン","スポーツ羊羹")	);
$array3=array(	"豚肉"=>array("ブタコマ","タン","バラ","豚足"),
				"鶏肉"=>array("胸肉","チキンカツ","手羽先","ナンコツ")	);
*/
$array1=array(	array("りんご","みかん","すいか","メロン"),
				array("ポテチ","どら焼き","キャラメルコーン","羊羹"),
				array("麦飯","白米","オートミール","ラーメン"),
				array("キャベツ","ピーマン","じゃがいも","白菜"),
				array("鯖","マグロ","イカ","鮭")
);
$array2=array(	array("鯖缶","ツナ缶","野菜炒め","焼き魚"),
				array("ウィダー","プロティン","スポーツ羊羹")	);
$array3=array(	array("ブタコマ","タン","バラ","豚足"),
				array("胸肉","チキンカツ","手羽先","ナンコツ")	);
$max=count($array1);
reset($array1);
print "max:".$max."\n";
for($i=0;$i<=$max;$i++){
	if(	$i==2	){
		array_splice($array1,$i,0,$array2);
	}
	if(	$i==4	){
		array_splice($array1,$i,0,$array3);
	}
	print_r(	current($array1)	);
	next($array1);
}
/*
array_splice($array1,2,0,$array2);
array_splice($array1,4,0,$array3);
*/
print_r($array1);
?>
