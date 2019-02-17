
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
	public	$zeikomiDeletePattern=array("font-size:12px\">","</span>");



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
	public	$zeikomiDeletePattern=array("font-size:12px\">","</span>");



	function url($page){
		return 'https://www.aeonbike.co.jp/shop/category/category.aspx?category=020102&sort=sp&p='.$page.'&s_stock_list=&ismodesmartphone=off';
	//イオンバイク(子乗せ)用取得パターンここまで
	}	
}

//さいくるべーすあさひ取得urt(テスト用)


//代入用クラス(さいくるべーすあさひ)
class AsahiShop{
	public	$fileName="All";
	public	$shopName="サイクルベースあさひ";
	public	$FirstPage=0;
	public	$page;
	//ここからあさひ用取得パターン
	//最初に枠取得用パターン
	public	$firstPattern='/list_item__product sli_list_col2(.*?)<\/div><\/div>/ius';

	//商品名取得用パターン
	public	$itemPattern='/data-sli-test=\"resultlink\">(.*?)<\/a>/ius';
	//値段取得用パターン
	public	$zeinukiPattern='';
	public	$zeikomiPattern='/sli_price\">(.*?)<\/span>/ius';

	//リンク取得用パターン
	public	$linkPattern='<a data-tb-sid=\"st_title-link\" class="list_item__product__name\" rel=\"nofollow\" href=\"(.*)\">';
	public	$linkDeletePattern=array('<a data-tb-sid=\"st_title-link\" class=\"list_item__product__name\" rel="nofollow" href=','>');

	//カラー取得用パターン
	public	$kobetuColorPattern='/data-js-color-thumb-label=\".*\">/ius';	
	
	//削除用パターン
	//商品名用
	public	$itemDeletePattern=array("data-sli-test=\"resultlink\">\n","</a>");

	//値段
	public	$zeinukiDeletePattern=array("");
	//
	public	$zeikomiDeletePattern=array("sli_price\">￥","</span>");



	function url($page){
		$page=$page*24;
		return 'https://ec.cb-asahi.co.jp/category/cat1/%E9%9B%BB%E5%8B%95%E8%87%AA%E8%BB%A2%E8%BB%8A/'.$page.'?isort=price&view=list';
	//あさひ用取得パターンここまで
	}	
}


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
	public	$linkPattern='/store\/ProductDetailonclick/iu';
	public	$linkDeletePattern=array('store','\' onclick');
	public	$linkReplacePattern=array('https://cyclespot.jp/store','');

	//カラー取得用パターン
	public	$kobetuColorPattern='/img-responsive\" \/>.*<span>.*<\/span>/ius';	

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


$shop=new AeonShop();

$ShopScraping=new ShopScraping($shop);
$result=$ShopScraping->All();



$shop=new AeonChildShop();

$ShopScraping=new ShopScraping($shop);
$result=$ShopScraping->All();


$shop=new AsahiShop();

$ShopScraping=new ShopScraping($shop);
$result=$ShopScraping->All();


$shop=new CSShop();

$ShopScraping=new ShopScraping($shop);
$result=$ShopScraping->All();

?>
