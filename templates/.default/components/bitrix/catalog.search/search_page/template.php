<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>


	
<div hidden style="display:none">

<?

global $arSearchFilter;
$arSearchFilter = array("PARAMS" => array());
if(isset($_GET["section"]) && intval($_GET["section"])) {
	$arSearchFilter["PARAMS"]["iblock_section"] = $_GET["section"];
}

$arElements = $APPLICATION->IncludeComponent(
	"bitrix:search.page",
	"custom_page",
	Array(
		"RESTART" => $arParams["RESTART"],
		"NO_WORD_LOGIC" => $arParams["NO_WORD_LOGIC"],
		"USE_LANGUAGE_GUESS" => $arParams["USE_LANGUAGE_GUESS"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],
		"arrFILTER" => array("iblock_".$arParams["IBLOCK_TYPE"]),
		"arrFILTER_iblock_".$arParams["IBLOCK_TYPE"] => array($arParams["IBLOCK_ID"]),
		"USE_TITLE_RANK" => "N",
		"DEFAULT_SORT" => "rank",
		"FILTER_NAME" => "arSearchFilter",
		"SHOW_WHERE" => "N",
		"arrWHERE" => array(),
		"SHOW_WHEN" => "N",
		"PAGE_RESULT_COUNT" => 99999,
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "N",
	),
	$component,
	array('HIDE_ICONS' => 'Y')
); ?>
</div>


<section class="page_content">
			
<? if (!empty($arElements) && is_array($arElements))
{
	global $searchFilter;
	$searchFilter = array(
		"=ID" => $arElements,
	);?>
	<?if (isset($_GET["q"])) { ?>
			<div class="text_block">Результаты поиска по запросу "<?=$_GET["q"];?>"</div> 
		<? } ?>
<?
$arPriceTypes = array();
$dbPriceType = CCatalogGroup::GetList(array(), array('CAN_BUY' => 'Y'), false, false, array('NAME'));
while ($arPriceType = $dbPriceType->Fetch()) array_push($arPriceTypes, $arPriceType['NAME']);
?>
	<section class="products" style="margin-top: 20px;">
	<?$APPLICATION->IncludeComponent(
				"bitrix:catalog.section", 
				".default", 
				array(
					"COMPONENT_TEMPLATE" => 'sale',
					"IBLOCK_TYPE" => 'catalog',
					"IBLOCK_ID" => '1',
					"ELEMENT_SORT_FIELD" => 'sort',
					"ELEMENT_SORT_ORDER" => 'asc',
					"ELEMENT_SORT_FIELD2" => 'id',
					"ELEMENT_SORT_ORDER2" => 'desc',
					"FILTER_NAME" => "searchFilter",
					"INCLUDE_SUBSECTIONS" => 'Y',
					"SHOW_ALL_WO_SECTION" => 'Y',
					"HIDE_NOT_AVAILABLE" => 'N',
					"PAGE_ELEMENT_COUNT" => '20',
					"LINE_ELEMENT_COUNT" => '2',
					"PROPERTY_CODE" => Array
						(
							"0" => 'STICKER'
						),

					"OFFERS_LIMIT" => '0',
					"TEMPLATE_THEME" => 'blue',
					"ADD_PICT_PROP" => '-',
					"LABEL_PROP" => '-',
					"PRODUCT_SUBSCRIPTION" => 'N',
					"SHOW_DISCOUNT_PERCENT" => 'N',
					"SHOW_OLD_PRICE" => 'Y',
					"SHOW_CLOSE_POPUP" => 'N',
					"MESS_BTN_BUY" => 'Купить',
					"MESS_BTN_ADD_TO_BASKET" => 'В корзину',
					"MESS_BTN_SUBSCRIBE" => 'Подписаться',
					"MESS_BTN_DETAIL" => 'Подробнее',
					"MESS_NOT_AVAILABLE" => 'Нет в наличии',
					"SECTION_URL" => '',
					"DETAIL_URL" => '',
					"SECTION_ID_VARIABLE" => 'SECTION_ID',
					"SEF_MODE" => 'N',
					"AJAX_MODE" => 'N',
					"AJAX_OPTION_JUMP" => 'N',
					"AJAX_OPTION_STYLE" => 'Y',
					"AJAX_OPTION_HISTORY" => 'N',
					"AJAX_OPTION_ADDITIONAL" => '',
					"CACHE_TYPE" => 'A',
					"CACHE_TIME" => '36000000',
					"CACHE_GROUPS" => 'Y',
					"SET_BROWSER_TITLE" => 'Y',
					"BROWSER_TITLE" => 'Избранное',
					"SET_META_KEYWORDS" => 'Y',
					"META_KEYWORDS" => '-',
					"SET_META_DESCRIPTION" => 'Y',
					"META_DESCRIPTION" => '-',
					"SET_LAST_MODIFIED" => 'N',
					"USE_MAIN_ELEMENT_SECTION" => 'N',
					"ADD_SECTIONS_CHAIN" => 'N',
					"CACHE_FILTER" => 'N',
					"ACTION_VARIABLE" => 'action',
					"PRODUCT_ID_VARIABLE" => 'id',
					"PRICE_CODE" => $arPriceTypes,

					"USE_PRICE_COUNT" => 'N',
					"SHOW_PRICE_COUNT" => '1',
					"PRICE_VAT_INCLUDE" => 'Y',
					"CONVERT_CURRENCY" => 'Y',
					"CURRENCY_ID" => 'RUB',
					"BASKET_URL" => '/personal/basket.php',
					"USE_PRODUCT_QUANTITY" => 'N',
					"PRODUCT_QUANTITY_VARIABLE" => '',
					"ADD_PROPERTIES_TO_BASKET" => 'Y',
					"PRODUCT_PROPS_VARIABLE" => 'prop',
					"PARTIAL_PRODUCT_PROPERTIES" => 'N',
					"PRODUCT_PROPERTIES" => Array
						(
						),

					"ADD_TO_BASKET_ACTION" => 'ADD',
					"PAGER_TEMPLATE" => '.default',
					"DISPLAY_TOP_PAGER" => 'N',
					"DISPLAY_BOTTOM_PAGER" => 'Y',
					"PAGER_TITLE" => 'Товары',
					"PAGER_SHOW_ALWAYS" => 'N',
					"PAGER_DESC_NUMBERING" => 'N',
					"PAGER_DESC_NUMBERING_CACHE_TIME" => '36000',
					"PAGER_SHOW_ALL" => 'N',
					"PAGER_BASE_LINK_ENABLE" => 'N',
					"SET_STATUS_404" => 'N',
					"SHOW_404" => 'N',
					"MESSAGE_404" => '',
					"SECTION_CODE" => '',
					"OFFERS_FIELD_CODE" => Array
						(
							"0" => '
							"1" => '
						),

					"OFFERS_PROPERTY_CODE" => Array
						(
							"0" => 'CML2_ATTRIBUTES
							"1" => '
						),

					"OFFERS_SORT_FIELD" => 'sort',
					"OFFERS_SORT_ORDER" => 'asc',
					"OFFERS_SORT_FIELD2" => 'id',
					"OFFERS_SORT_ORDER2" => 'desc',
					"PRODUCT_DISPLAY_MODE" => 'N',
					"SET_TITLE" => 'Y'
				),
				false
			);?></section>	
	<?
}
elseif (is_array($arElements))
{ ?><div class="text_block"><p>
<? echo GetMessage("CT_BCSE_NOT_FOUND"); ?>
</p></div>
<? }
?><br><br>
</section>