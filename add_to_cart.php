<?// подключение служебной части пролога  
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

//use Bitrix\Highloadblock as HL;
//use Bitrix\Main\Entity;
//CModule::IncludeModule('highloadblock'); 


//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?> 
  
<?if (CModule::IncludeModule("sale") && intval($_REQUEST['p_id'])) { 
    $arProps = array();  
	$quantity = $_REQUEST['quantity'];

/*	if ($_REQUEST['size']) array_push($arProps, array("NAME" => "Размер", "CODE" => "SIZE", "VALUE" => $_REQUEST['size']));
	if ($_REQUEST['color']) {
		$arTmp = explode("|", $_REQUEST['color']);
		$color = $arTmp[0];
		if (isset($arTmp[1]) && $arTmp[1]) $color = $color.' ('.$arTmp[1].')';
		if ($color) array_push($arProps, array("NAME" => "Цвет", "CODE" => "COLOR", "VALUE" => $color));
	} */
	
	
////////////////////////
/*	$cat_quant = 999999;
	$res = CIBlockElement::GetList(Array(), Array("ID"=>267), false, Array("nTopCount"=>1), Array("IBLOCK_ID", "XML_ID", "CATALOG_QUANTITY"));
	if($ob = $res->GetNextElement()){ 
		$arFields = $ob->GetFields();  
		$cat_quant = $arFields['CATALOG_QUANTITY'];
		
	}


	$arBasketItems = array();
	$dbBasketItems = CSaleBasket::GetList(false, array("PRODUCT_ID" => 267, "FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL", "DELAY" => "N"), false, false, array("PRODUCT_ID", "QUANTITY"));
	while ($arItems = $dbBasketItems->Fetch())
		
	{
		echo '<pre>';
		print_r($arItems);
		echo '</pre>';
	}
*/

	$arBasketItems = array();
	$dbBasketItems = CSaleBasket::GetList(false, array("PRODUCT_ID" => $_REQUEST['p_id'], "FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL", "DELAY" => "N"), false, false, array("ID"));
	while ($arItems = $dbBasketItems->Fetch())	{
		CSaleBasket::Delete($arItems['ID']);
	}


/////////////////////////	
	
	
	
	
	if ($_REQUEST['delay'] && ($_REQUEST['delay'] == "Y"))
		array_push($arProps, array("NAME" => "Избранное", "CODE" => "DELAY_IN", "VALUE" => 1));
	
	
	$res = CIBlockElement::GetList(Array(), Array("ID"=>$_REQUEST['p_id']), false, Array("nTopCount"=>1), Array("IBLOCK_ID", "XML_ID", 'PROPERTY_SIZE', 'PROPERTY_COLOR'));
	if($ob = $res->GetNextElement()){ 
		$arFields = $ob->GetFields();  
		if ($arFields['XML_ID']) {
			array_push($arProps, array("NAME" => "Product XML_ID", "CODE" => "PRODUCT.XML_ID", "VALUE" => $arFields['XML_ID']));
		}
		if ($arFields['IBLOCK_ID']) {
			$res = CIBlock::GetList(array(), array("ID" => $arFields['IBLOCK_ID']));
			if($ar = $res->Fetch()){ 
				array_push($arProps, array("NAME" => "Catalog XML_ID", "CODE" => "CATALOG.XML_ID", "VALUE" => $ar['XML_ID']));
			}
		}
		/*
		if (mb_strlen($arFields['PROPERTY_SIZE_VALUE'])) {
			array_push($arProps, array("NAME" => "Размер", "CODE" => "SIZE", "VALUE" => $arFields['PROPERTY_SIZE_VALUE']));
		}*/
	}
			
	Add2BasketByProductID(
		$_REQUEST['p_id'], 
		$quantity, 
		array("DELAY" => $_REQUEST['delay']), 
		$arProps
	);
?>	
<?$APPLICATION->IncludeComponent(
	"tochka:basket_light", 
	".default", 
	[],
	false
);?>



<?
}?>  





<?// подключение служебной части эпилога  
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>