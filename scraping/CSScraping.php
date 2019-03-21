
<?php
//
//
require_once("./function.php");


//サイクルスポット用取得urt(テスト用)


//代入用クラス(サイクルスポット)
class CSShop{
	public	$fileName="All";
	public	$shopName="サイクルスポット";
	public	$FirstPage=0;
	public	$page;
	//ここからサイクルスポット用取得パターン
	//最初に枠取得用パターン
	public	$firstPattern='/sclist-area clearfix(.*?)<!-- sclist-area clearfix -->/ius';
	//商品名取得用パターン
	public	$itemPattern='/lis_nm(.*?)<\/span>/ius';
	//値段取得用パターン
	public	$zeinukiPattern='/priceB(.*?)<\/span>/ius';
	public	$zeikomiPattern='/priceB2(.*?)<\/b>/ius';


	//リンク取得用パターン
	public	$linkPattern='/store\/ProductDetail(.*)onclick/iu';
	public	$linkReplacePattern=array('/store/ius','/\' onclick/ius');
	public	$linkReplacement=array('https://cyclespot.jp/store','');

	//カラー取得用パターン
	public	$kobetuColorLargePattern='/<b>カラーバリエーション<\/b><\/div>.*お気に入りへ追加<\/a>/ius';	
	public	$kobetuColorSmallPattern='/<span>.*?<\/span>/ius';	
	public	$kobetuColorDeletePattern=array("<span>","</span>");	

	//削除用パターン
	//商品名用
	public	$itemDeletePattern=array("lis_nm\">","</span>");

	//値段
	public	$zeinukiDeletePattern=array("priceB'><span>","円</span>");
	//
	public	$zeikomiDeletePattern=array("priceB2'>","円</b>");



	function url($page){
		return 'https://cyclespot.jp/store/CategoryList.aspx?ccd=F1000131&wkcd=F1000114&SKEY=price&SORDER=0&page='.$page;
	//サイクルスポット用取得パターンここまで
	}	
}


$shop=new CSShop();

$ShopScraping=new ShopScraping($shop);
$result=$ShopScraping->All();

?>
