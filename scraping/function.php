<?php
/*
 * cookie必要サイトのスクレイピングの為のcURL利用関数,
 * 中身はweb検索のほぼマルコピ
 * コピ元
 * https://mayer.jp.net/?p=2599
 * 
 * 20190324 * 	追記:
 *			サイクルベースあさひでサーバー側から弾かれるのでリクエストヘッダーを追加
 * 			ヘッダー内容はchromeから丸コピ
 * 
 * 
 * 参考によし
 * https://qiita.com/kumasun/items/f7d17e7e74be5d441b29
 * cURLは要調べ
 *
 */

function scraping($url){
	$headers=array(
				"HTTP/1.1",
				"accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
				//"accept-encoding: gzip, deflate, br",		//文字化けになる
				"accept-language: ja,en-US;q=0.9,en;q=0.8",
				"cache-control: max-age=0",
				"user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36"
					);

	//最終的にアクセスしたいページ
	//クッキーがないとアクセスできない


	//クッキー取得のためのアクセス
	$ch=curl_init();//初期化
	curl_setopt($ch,CURLOPT_URL,$url);//cookieを取得ページへ取りに行く
	curl_setopt($ch,CURLOPT_HEADER,FALSE);//httpヘッダ情報は表示しない
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);//データをそのまま出力
	curl_setopt($ch,CURLOPT_COOKIEJAR,'cookie.txt');//$cookieから取得した情報を保存するファイル名
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,TRUE);//Locationヘッダの内容をたどっていく
	//curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);//リクエストヘッダー(20190324追記)
	curl_exec($ch);
	if(curl_error($ch)){
		print "クッキー取得のためのアクセスでのエラー:".curl_error($ch);
		print "\r\n";
	}
	curl_close($ch);//いったん終了
	
	//見たいページにアクセス

	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_HEADER,FALSE);
	curl_setopt($ch,CURLOPT_COOKIEFILE, 'cookie.txt');//cookie情報を読み取る
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,TRUE);
	curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);//リクエストヘッダー(20190324追記)
	$html=curl_exec($ch);
	if(curl_error($ch)){
		print "直接アクセスでのエラー:".curl_error($ch);
		print "\r\n";
	}
	curl_close($ch);

//	
//	mb_language('Japanese');
//	$html=mb_convert_encoding($html,'utf8','auto');//UTF-8に変換


	return $html;
}	//scraping終了

class ShopScraping{
	public	$shop;
	public	$shopName;
	public	$FirstPage;
	public	$page;
	public	$firstPattern;
	public	$itemPattern;
	public	$zeinukiPatternn;
	public	$zeikomiPattern;

	public	$itemDeletePattern;

	public	$zeinukiDeletePattern;
	public	$zeikomiDeletePattern;
	public	$url;
	public	$AllResult;
	public	$printResult;

	public	$host;
	public	$dbName;
	public	$dbUser;
	public	$dbPass;	
	public	$PDO;	
	public	$ScrapingErrMes="";	

	//
	function __construct($shop){
		require("dbConfig.php");
		
		//変数の代入
		$this->shop=$shop;
		$this->className=get_class($shop);

		

	}	//__construct終了

	//全体処理
	function All(){
		
		print "取得日".date("Ymd")."\r\n";
		$page=$this->shop->FirstPage;

		$this->dbOpen();

		do{

			//各ページの取得結果
			$pageResult=$this->pageScraping($page);

			//個別ページスクレイピング
			$pageResult=$this->kobetu($pageResult);

			//車種の紐付け
			$pageResult=$this->himotuke($pageResult);

			//
			//exit();

			//db書き込み
			$this->dbWrite($pageResult);

			//ここからpageResultをcsvにするために再度分割
			if(		$pageResult!=false or $pageResult!=0		){
				foreach($pageResult as $ireko){
					$ireko=@implode(",",$ireko);
					str_replace(	"\n,","\n",$ireko	);
					$this->printResult.=$ireko."\n";
				}
			}

			//ここまで


			$page++;
		}while(	$pageResult!=false or $pageResult!=0	);	

		/*		再取得前結果の出力
			file_put_contents($this->shop->fileName.'Result'.date("Ymd").'.csv',$this->printResult,FILE_APPEND|LOCK_EX);
	 	 */
		if(	$this->ScrapingErrMes!=""	){
			file_put_contents("ScrapingErrMes".date('Ymd').".sql",$this->ScrapingErrMes,FILE_APPEND|LOCK_EX);
		}

		$this->dbClose();
		return;

	}	//All終了

	//一覧ページスクレイピング
	function pageScraping($page){
		$shopName=$this->shop->shopName;
		$firstPattern=$this->shop->firstPattern;
		$itemPattern=$this->shop->itemPattern;
		$zeinukiPattern=$this->shop->zeinukiPattern;
		$zeikomiPattern=$this->shop->zeikomiPattern;
		$itemDeletePattern=$this->shop->itemDeletePattern;
		$zeinukiDeletePattern=$this->shop->zeinukiDeletePattern;
		$zeikomiDeletePattern=$this->shop->zeikomiDeletePattern;
		if(	isset($this->shop->linkPattern)	){
			$linkPattern=$this->shop->linkPattern;
			$linkReplacePattern=(isset($this->shop->linkReplacePattern))?$this->shop->linkReplacePattern:'';
			$linkReplacement=(isset($this->shop->linkReplacement))?$this->shop->linkReplacement:'';
		}
		$pageResult=array();
		$lineResult=array();

		$url=$this->shop->url($page);
		print "取得url ページ".$page."\r\n";
		print $url."\r\n";
		$firstPattern=$this->shop->firstPattern;

		$nenshiki="";
		$itemName=array();
		$zeinukiPrice=array();
		$zeikomiPrice=array();

		//クッキー取得のためのURL
		//ここにアクセスすればクッキーにフラグが立つというページ
		$scrap=scraping($url);



		//スクレイピングファイル出力(デバッグ用)
		//file_put_contents('pageScrap'.$page.'.html',$scrap,LOCK_EX);

		//ここから抽出


		$endFlag=preg_match_all($firstPattern,$scrap,$array);
		if(	$endFlag==0 or	$endFlag==false	){
			print "枠取得失敗か終了\r\n";
			return false;
		}

		//preg_match_allファイル出力(デバッグ用)
		//error_log(var_export($array[0],true),3,"./scrap".$page.".html");


		//必要なのは全体検索の結果のみ
		$array=$array[0];

		//file_put_contents('array'.$page.'.html',$array,LOCK_EX);
		foreach($array as $key => $cell){


			preg_match($itemPattern,$cell,$itemName);
			@preg_match($linkPattern,$cell,$linkURL);
			@preg_match($zeinukiPattern,$cell,$zeinukiPrice);
			@preg_match($zeikomiPattern,$cell,$zeikomiPrice);

			$itemName[0]=mb_convert_kana(	str_replace($itemDeletePattern,"",$itemName[0])	,'KVas','UTF-8'	);
			$itemName[0]=@str_replace(",",".",$itemName[0]);
			$linkURL[0]=@preg_replace($linkReplacePattern,$linkReplacement,$linkURL[0]);
			$zeinukiPrice[0]=@str_replace($zeinukiDeletePattern,"",$zeinukiPrice[0]);
			$zeikomiPrice[0]=str_replace($zeikomiDeletePattern,"",$zeikomiPrice[0]);
			//preg_match("/20[0-9][0-9][-ー\/]?[0-9]{0,4}/",$itemName[0],$nenshiki);
			if(	preg_match("/(20[0-9]{2}-20[0-9]{2}年\(継続\)|20[0-9]{2})/ius",$itemName[0],$nenshiki)!=1	){
				if(	preg_match("/【アウトレットSALE】[0-9]{2}/ius",$itemName[0],$nenshiki)==1	){				//あさひのアウトレット対応
					$nenshiki[0]="20".substr($nenshiki[0],-2,2);
				}
			}
			$itemName[0]=preg_replace("/(20[0-9]{2}-20[0-9]{2}年\(継続\)|20[0-9]{2}|【アウトレットSALE】[0-9]{2})/ius","",$itemName[0]);
			//,を取る
			$zeinukiPrice[0]=@str_replace(",","",$zeinukiPrice[0]);
			$zeikomiPrice[0]=@str_replace(",","",$zeikomiPrice[0]);


			//
			$lineResult["店名"]=$shopName;

			//年式取得できない場合暫定で0000を入れる
			$lineResult["年式"]=(array_key_exists(0,$nenshiki)	)?$nenshiki[0]:"0000";
			//print_r($itemName);
			//リンク取得で取れなかった場合""を入れる
			$lineResult["リンク"]=(array_key_exists(0,$linkURL))?$linkURL[0]:"";
			$lineResult["文言"]=trim($itemName[0]);
			$lineResult["税抜"]=(	!empty($zeinukiPrice[0])	)?$zeinukiPrice[0]:0;
			$lineResult["税込"]=$zeikomiPrice[0];

			$pageResult[]=$lineResult;
		}
		//print_r( $lineResult);
		//print_r( $pageResult);
		return $pageResult;
	}	//pageScraping終了
	
	//dbオープン
	function dbOpen(){
		$host=$this->host;
		$dbName=$this->dbName;
		$dbUser=$this->dbUser;
		$dbPass=$this->dbPass;

		try{
			$this->PDO=new PDO("mysql:host=".$host.";dbname=".$dbName,$dbUser,$dbPass);

		}catch(PDOException $error){
			print "db接続エラー\n";
			exit(	$error->getMessage()	);
		}
		print "接続完了";
		return;
	}	//dbOpen終了

	//dbクローズ
	function dbClose(){
		print "dbClose\n";
		$this->PDO=null;	
	}	//dbClose終了

	//db書き込み
	function dbWrite($pageResult){

		//print_r($pageResult);
		//return;

		//
		if(	!is_array($pageResult)	or	$pageResult==false or $pageResult==0	){
			return;
		}	


		foreach($pageResult as $lineResult){
			//print_r($lineResult);
			if(	$lineResult["リンク"]!=""	){
				$sql=	"INSERT INTO t001_AllShouhinList ".
						"(tenmei,year,mongon,zeinuki_kakaku,zeikomi_kakaku,index_No,linkURL,touroku_date) ".
						"VALUES( :tenmei , :year , :mongon , :zeinuki_kakaku , :zeikomi_kakaku , :index_No , :linkURL ,  DATE(now())	)";
			}else{
				$sql=	"INSERT INTO t001_AllShouhinList ".
						"(tenmei,year,mongon,zeinuki_kakaku,zeikomi_kakaku,index_No,touroku_date) ".
						"VALUES( :tenmei , :year , :mongon , :zeinuki_kakaku , :zeikomi_kakaku , :index_No ,  DATE(now())	)";
			}
			$stmt=$this->PDO->prepare($sql);
			$stmt->bindvalue(':tenmei',$lineResult["店名"],PDO::PARAM_STR);
			$stmt->bindvalue(':year',$lineResult["年式"],PDO::PARAM_STR);
			$stmt->bindvalue(':mongon',$lineResult["文言"],PDO::PARAM_STR);
			$stmt->bindvalue(':zeinuki_kakaku',$lineResult["税抜"],PDO::PARAM_INT);
			$stmt->bindvalue(':zeikomi_kakaku',$lineResult["税込"],PDO::PARAM_INT);
			$stmt->bindvalue(':index_No',$lineResult["index_No"],PDO::PARAM_INT);
			if(	$lineResult["リンク"]!=""	){
				$stmt->bindvalue(':linkURL',$lineResult["リンク"],PDO::PARAM_STR);
			}
			$res=$stmt->execute();
			if($res){
				//print "true ".$res."line comp\n";
			}else{

				$err=	"false!\n".
						"INSERT INTO t001_AllShouhinList ".
						"(tenmei,year,mongon,zeinuki_kakaku,zeikomi_kakaku,index_No,touroku_date) ".
						//"VALUES( '".$lineResult['店名']."','".$lineResult['年式']."','".$lineResult['文言']."','".$lineResult['税抜']."','".$lineResult['税込']."','".$lineResult['index_No']."',DATE(now())	)\n";
						"VALUES( '".$lineResult['店名']."','".$lineResult['年式']."','".$lineResult['文言']."','".$lineResult['税抜']."','".$lineResult['税込']."','".$lineResult['index_No']."','".$lineResult['リンク']."',DATE(now())	)\n";
				print $err;
				$this->ScrapingErrMes.=$err;
			}	
			$stmt=null;
		}
		return;
	}	//dbWrite終了

	//紐付け
	function himotuke($pageResult){

		if(	!is_array($pageResult)	or	$pageResult==false or $pageResult==0	){
			return;
		}	
		//return $pageResult;


		//紐付けのその１(index)
		$sql=	"SELECT `index_No`,`メーカー`,`品番`,`正規表現名`,`フレームサイズ`,`タイヤサイズ`,`単一タイヤサイズ`,`年度`,`単一商品フラグ`,`色`,`正規表現色` FROM jitensha_index";
		$stmt=$this->PDO->query($sql);
		$result=$stmt->fetchall(PDO::FETCH_ASSOC);
		$himotuke_index=$result;

 
		foreach($pageResult as $key => $lineResult){
			foreach(	$himotuke_index as $shashu	){
				if(	preg_match($shashu["正規表現名"],$lineResult["文言"])==1	&&
					(	(	$lineResult["年式"]==$shashu["年度"]	&&	isset($shashu["年度"])		||	
							@stripos($lineResult["文言"],$shashu["品番"])	&&	isset($shashu["品番"])	&&	$shashu["メーカー"]!="ヤマハ"	)	||	
							isset($shashu["単一商品フラグ"])	&&	$shashu["単一商品フラグ"]==1	)	&&
					(	(	@stripos($lineResult["文言"],$shashu["タイヤサイズ"])		||	$shashu["単一タイヤサイズ"]==1	)	||
						(	@stripos($lineResult["文言"],$shashu["フレームサイズ"])	)	)	&&
						(	preg_match($shashu["正規表現色"],$lineResult["文言"])==1	)	
				){
					//print ($shashu["単一商品フラグ"])?"true":"false";
					//print_r($shashu);
					//print_r($lineResult);
					//exit();
					$pageResult[$key]["index_No"]=$shashu["index_No"];
					break 1;
				}
				//$pageResult[$key]["index_No"]="0";
			}
		}
		//紐付けその１ここまで
		return $pageResult;
	}	//himotuke終了

	//個別ページ処理
	function kobetu($pageResult){
		$colorGetAfterResult=array();

		//デバッグ出力
		$this->arrayPut($pageResult,"kobetuMae",1);

		//リンク先取得
		if(	is_array($pageResult)	){
			print "pageResult not array!!\n";
			return false;
		}
		foreach($pageResult as $value){
			if(	isset($value["リンク"])	){
				$allColor=$this->linkSakiGet($value["リンク"]);
				if($allColor){
					foreach($allColor as $color){
						if(	$color!="在庫なし"	){
							$add=$value;
							$add=$add+array("カラー"=>$color);
							$colorGetAfterResult[]=$add;
						}
					}
				}
			}
		}

		//デバッグ出力
		$this->arrayPut($colorGetAfterResult,"colorGetAfterResult",0);

		return $pageResult;
	}	//kobetu終了

	//リンク先ゲット
	function linkSakiGet($link){
		$color=array();
		$kobetuColorLargePattern=$this->shop->kobetuColorLargePattern;
		$kobetuColorSmallPattern=$this->shop->kobetuColorSmallPattern;
		$kobetuColorDeletePattern=$this->shop->kobetuColorDeletePattern;
		$scrap=scraping($link);
		$this->arrayPut($scrap,"scrap",0);
		if(	preg_match($kobetuColorLargePattern,$scrap,$result)>=1	){
			$subject=$result[0];
			if(	preg_match_all($kobetuColorSmallPattern,$subject,$result)>=1	){
				foreach($result[0] as $cutSubject){
					$cutAfter=str_replace($kobetuColorDeletePattern,"",$cutSubject);
					$color[]=$cutAfter;
				}
				return $color;
			}
		}
		return false;
	}	//linkSakiGet終了


	//配列のファイル出力(デバッグ用)
	function arrayPut($array,$fileName,$fileAppend=0){
		$midashi="";
		$honbun="";
		if(	!is_array($array)	){
			//file_put_contents("error".date('Ymd').".mes","fales!");
			file_put_contents($fileName.date('Ymd').".txt",$array,LOCK_EX);
			return false;
		}
		foreach($array as $key => $value){
			if(	!is_array($value)	){
				$midashi=implode(	",",array_keys($array)	);
				$honbun=implode(	",",$array	);
				break 1;
			}else{ 
				$midashi="見出し,".implode(	",",array_keys($value)	);
				$honbun.=$key.",".implode(	",",$value)."\n";	
			} 
		}
		$csv=$midashi."\n".$honbun;
		if(	$fileAppend==0	){
			file_put_contents($fileName.date('Ymd').".csv",$csv,LOCK_EX);
			file_put_contents($fileName.date('Ymd').".txt",print_r($array,true),LOCK_EX);
		}else{
			file_put_contents($fileName.date('Ymd').".csv",$csv,LOCK_EX|FILE_APPEND);
			file_put_contents($fileName.date('Ymd').".txt",print_r($array,true),LOCK_EX|FILE_APPEND);
		}
		return;

	}	//arrayPut終了

}	//class終了

?>
