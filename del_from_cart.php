<?// подключение служебной части пролога  
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?> 
  ууууууууууу
<?if (CModule::IncludeModule("sale")) { 
	if (isset($_REQUEST["id"]) && intval($_REQUEST["id"])) {
		$db_res = CSaleBasket::GetPropsList(
			array(),
			array("BASKET_ID" => $_REQUEST["id"], "CODE" => "PRINT_ID")
		);
		if ($ar_res = $db_res->Fetch())
		{
		   if ($ar_res['VALUE']) CSaleBasket::Delete($ar_res['VALUE']);
		}

		CSaleBasket::Delete($_REQUEST["id"]);
	} else {
		$dbBasketItems = CSaleBasket::GetList(
				array(),
				array(
						"FUSER_ID" => CSaleBasket::GetBasketUserID(),
						"LID" => SITE_ID,
						"ORDER_ID" => "NULL", 
						"PRODUCT_ID" => $_REQUEST["p_id"]
					),
				false,
				false,
				array("ID")
			);
		while ($arItems = $dbBasketItems->Fetch())
		{
			CSaleBasket::Delete($arItems["ID"]);
		} 
	}
}?>  

<?$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket.line", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"PATH_TO_BASKET" => "/personal/cart/",
		"PATH_TO_ORDER" => "/personal/cart/",
		"SHOW_DELAY" => "Y",
		"SHOW_NOTAVAIL" => "N",
		"SHOW_SUBSCRIBE" => "N",
		"SHOW_NUM_PRODUCTS" => "Y",
		"SHOW_TOTAL_PRICE" => "N",
		"SHOW_EMPTY_VALUES" => "Y",
		"SHOW_PERSONAL_LINK" => "N",
		"PATH_TO_PERSONAL" => "/personal/",
		"SHOW_AUTHOR" => "N",
		"PATH_TO_AUTHORIZE" => "",
		"SHOW_REGISTRATION" => "N",
		"PATH_TO_REGISTER" => SITE_DIR."login/",
		"PATH_TO_PROFILE" => SITE_DIR."personal/",
		"SHOW_PRODUCTS" => "N",
		"POSITION_FIXED" => "N",
		"HIDE_ON_BASKET_PAGES" => "N"
	),
	false
);?>
 
<?// подключение служебной части эпилога  
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>