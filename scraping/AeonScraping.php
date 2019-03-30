
<?php
//
//
require_once("./function.php");


//イオンバイク取得urt(テスト用)


//代入用クラス(イオンバイク)
class AeonShop{
	public	$fileName="All";
	public	$shopName="イオンバイク";
	public	$FirstPage=1;
	public	$page;
	//ここからイオンバイク用取得パターン
	//最初に枠取得用パターン
	public	$firstPattern='/StyleD_Item(.*?)\/table>/ius';
	//商品名取得用パターン
	public	$itemPattern="/title=\".*?\"><img alt/ius";
	//値段取得用パターン
	public	$zeinukiPattern="/font-size:16px\">.*?<\/span>/ius";
	public	$zeikomiPattern="/font-size:12px\">.*?<\/span>/ius";

	//削除用パターン
	//商品名用
	public	$itemDeletePattern=array("title=\"","\"><img alt");

	//値段
	public	$zeinukiDeletePattern=array("font-size:16px\">","</span>");
	//
	public	$zeikomiDeletePattern=array("font-size:12px\"><font size=\"3\">","</font></span>");



	function url($page){
		return 'https://www.aeonbike.co.jp/shop/category/category.aspx?category=020103&sort=sp&p='.$page.'&s_stock_list=#top';
	//イオンバイク用取得パターンここまで
	}	
}



//代入用クラス(イオンバイク子乗せ自転車用)
class AeonChildShop{
	public	$fileName="All";
	public	$shopName="イオンバイク";
	public	$FirstPage=1;
	public	$page;
	//ここからイオンバイク用取得パターン
	//最初に枠取得用パターン
	public	$firstPattern='/StyleD_Item(.*?)\/table>/ius';
	//商品名取得用パターン
	public	$itemPattern="/title=\".*?\"><img alt/ius";
	//値段取得用パターン
	public	$zeinukiPattern="/font-size:16px\">.*?<\/span>/ius";
	public	$zeikomiPattern="/font-size:12px\">.*?<\/span>/ius";

	//削除用パターン
	//商品名用
	public	$itemDeletePattern=array("title=\"","\"><img alt");

	//値段
	public	$zeinukiDeletePattern=array("font-size:16px\">","</span>");
	//
	public	$zeikomiDeletePattern=array("font-size:12px\"><font size=\"3\">","</font></span>");



	function url($page){
		return 'https://www.aeonbike.co.jp/shop/category/category.aspx?category=020102&sort=sp&p='.$page.'&s_stock_list=&ismodesmartphone=off';
	//イオンバイク(子乗せ)用取得パターンここまで
	}	
}

$shop=new AeonShop();

$ShopScraping=new ShopScraping($shop);
$result=$ShopScraping->All();



$shop=new AeonChildShop();

$ShopScraping=new ShopScraping($shop);
$result=$ShopScraping->All();

?>
