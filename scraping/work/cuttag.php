<?php
if(	php_sapi_name()!='cli'	){
	//cliではない
	print "これはcli環境からの実行のみ可能なアプリです。";
	exit();
}


$option=getopt("hi:o:",array("help"));

//var_dump($option);	//デバッグ用

if(	isset($option["h"])	or isset($option["help"])	){
	print "実行形式\n";
	print "cuttag -fファイル名\n";
	print "オプション\n";
	print "-h -help -i(ファイル名) -o(ファイル名)\n";
	exit();
}

//
$result=file_get_contents($option["i"]);
//$result=file_get_contents($option["f"]);
if(	$result===false	){
	print "読み込み失敗〜\n";
	exit();
}
//print $result;	//デバッグ用
//print "\n";
/*
	$result=htmlspecialchars($result,ENT_QUOTES|ENT_HTML5);
	print "ここからhtmlspecialchars\n";
	print $result;
	print "\n";
 */
$result=cuttag($result);


$current=file_put_contents($option["o"],$result);
if(	$current===false	){
	print "出力に失敗しました";
}

function cuttag($html){
	$html=preg_replace('/<table.*?>/','',$html);
	$html=preg_replace('/<tbody.*?>/','',$html);
	$html=preg_replace('/<tr.*?>/','',$html);
	$html=preg_replace('/<td.*?>/','',$html);
	$html=preg_replace('/<\/table.*?>/','',$html);
	$html=preg_replace('/<\/tbody.*?>/','',$html);
	$html=preg_replace('/<\/tr.*?>/','',$html);
	$html=preg_replace('/<\/td.*?>/','',$html);
	$html=preg_replace('/class=\".*?\"/','',$html);
	$html=str_replace('img_0','http:\/\/ss101972.stars.ne.jp\/iios9402\/wp-content\/uploads\/2018/10\/img_0',$html);
	$html=str_replace('img0','http:\/\/ss101972.stars.ne.jp\/iios9402\/wp-content\/uploads\/2018/10\/img0',$html);
	return $html;
}
?>
