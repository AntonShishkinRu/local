<? define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/header.php'); ?>

<?
$dataClassSettings = \Bitrix\Iblock\Iblock::wakeUp(SETTINGS_ID)->getEntityDataClass();
$el_settings = $dataClassSettings::getList([
	'select' => ['IS_MAIN_MOBILE_SHOW_' => 'IS_MAIN_MOBILE_SHOW'],
	'limit' => 1,
	'cache' => [
        'ttl' => 3600,
        'cache_joins' => true,
    ]
]);

$is_main_mobile_show = "N";
if ($arItem = $el_settings->fetch()) {
	if ($arItem['IS_MAIN_MOBILE_SHOW_VALUE']) $is_main_mobile_show = "Y";
}
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"main_slider", 
	array(
		"IS_MAIN_MOBILE_SHOW" => $is_main_mobile_show,
		"COMPONENT_TEMPLATE" => "main_slider",
		"IBLOCK_TYPE" => "home",
		"IBLOCK_ID" => "5",
		"NEWS_COUNT" => "999",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "PREVIEW_TEXT",
		),
		"PROPERTY_CODE" => ["URL", "HEADER", "SHOW_MAIN", "SHOW_MOBILE"],
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36001",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "N",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"STRICT_SECTION_CHECK" => "N",
		"PAGER_BASE_LINK" => "",
		"PAGER_PARAMS_NAME" => "arrPager"
	),
	false
);?>

				
				
				
				
<?
/*
$dataClassCatalog = \Bitrix\Iblock\Iblock::wakeUp(CAT_ID)->getEntityDataClass();
$dataClassSettings = \Bitrix\Iblock\Iblock::wakeUp(SETTINGS_ID)->getEntityDataClass();
$el_settings = $dataClassSettings::getList([
	'select' => ['MAIN_TOP_SLIDER_' => 'MAIN_TOP_SLIDER']
]);

$arSlides = [];
while ($arItem = $el_settings->fetch()) {
	$arSlides[] = CFile::GetPath($arItem['MAIN_TOP_SLIDER_IBLOCK_GENERIC_VALUE']);
}

if (count($arSlides)) { 
?>
	<section class="main_slider block">
		<div class="swiper">
			<div class="swiper-wrapper">
			<? foreach($arSlides as $pic_path) { ?>
				<div class="swiper-slide">
					<div class="image">
						<img src="<?=$pic_path;?>" class="img" loading="lazy">
					</div>
				</div>
			<? } ?>
			</div>

			<div class="swiper-pagination"></div>
		</div>
	</section>
<? 
} ?>

<?
$el_settings = $dataClassSettings::getList([
	'select' => ['MAIN_CATS_' => 'MAIN_CATS']
]);
$arBlocks = [];
while ($arItem = $el_settings->fetch()) {
	$arBlocks[] = $arItem['MAIN_CATS_IBLOCK_GENERIC_VALUE'];
}

if (count($arBlocks)) { 
?>

	<section class="categories block">
		<div class="row">
			<? foreach($arBlocks as $block_id) { 
				$rsSection = \Bitrix\Iblock\SectionTable::getList(array(
					'filter' => ['ID' => $block_id], 
					'select' =>  [
						'ID', 'NAME', 'PICTURE',
						'SECTION_CODE' => 'CODE',
						'SECTION_PAGE_URL_TEMPLATE' => 'IBLOCK.SECTION_PAGE_URL'],
					'cache' => ['ttl' => 3600],
				)); 
				if ($arSection=$rsSection->fetch()) { ?>
				<a href="<?=\CIBlock::ReplaceDetailUrl($arSection['SECTION_PAGE_URL_TEMPLATE'], $arSection, true, false);?>" class="category">
					<div class="info">
						<div class="name"><?=$arSection['NAME'];?></div>

						<div class="btn">СМОТРЕТЬ</div>
					</div>

					<? if ($pic = intval($arSection['PICTURE'])) {?><img src="<?=CFile::GetPath($pic);?>" class="img" loading="lazy"><?}?>
				</a>
			<? } 
			}?>
		</div>
	</section>
<? 
} ?>


<?
$el_settings = $dataClassSettings::getList([
	'select' => ['MAIN_PRODS_ELEMENTS_' => 'MAIN_PRODS_ELEMENTS']
]);

$arProdIDs = [];
while ($arItem = $el_settings->fetch()) {
	$arProdIDs[] = $arItem['MAIN_PRODS_ELEMENTS_IBLOCK_GENERIC_VALUE'];
}

if (count($arProdIDs)) {
	$title = '';
	$el_settings = $dataClassSettings::getList([
		'select' => ['MAIN_PRODS_TITLE_' => 'MAIN_PRODS_TITLE'],
		'limit' => 1
	]);
	if ($arItem = $el_settings->fetch()) {
		$title = $arItem['MAIN_PRODS_TITLE_VALUE'];
	}
	global $arFilterMainPage;
	$arFilterMainPage = array("ID" => $arProdIDs);
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"slider",
		Array
		(
			"PAGER_TITLE" => $title,
			"WITH_CATALOG_LINK" => "1",
			"IBLOCK_TYPE" => "catalog",
			"IBLOCK_ID" => "1",
			"ELEMENT_SORT_FIELD" => "id",
			"ELEMENT_SORT_ORDER" => "rand",
			"BASKET_URL" => "/personal/cart/",
			"ACTION_VARIABLE" => "action",
			"PRODUCT_ID_VARIABLE" => "id",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"PRODUCT_QUANTITY_VARIABLE" => "quantity",
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"FILTER_NAME" => "arFilterMainPage",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"DISPLAY_COMPARE" => "N",
			"PAGE_ELEMENT_COUNT" => "99999",
			"PRICE_CODE" => Array
				(
					"0" => "BASE"
				),

			"USE_PRICE_COUNT" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"PRICE_VAT_INCLUDE" => "Y",
			"USE_PRODUCT_QUANTITY" => "N",
			"ADD_PROPERTIES_TO_BASKET" => "N",
			"PARTIAL_PRODUCT_PROPERTIES" => "N",

			"PAGER_TEMPLATE" => ".default",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"LAZY_LOAD" => "N",
			"LOAD_ON_SCROLL" => "N",
			"SECTION_URL" => "/catalog/#SECTION_CODE#/",
			"DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
			"USE_MAIN_ELEMENT_SECTION" => "N",
			"CONVERT_CURRENCY" => "N",
			"CURRENCY_ID" => "",
			"HIDE_NOT_AVAILABLE" => "Y",
			"HIDE_NOT_AVAILABLE_OFFERS" => "Y",
			"LABEL_PROP_POSITION" => "top-left",
			"ADD_PICT_PROP" => "-",
			"PRODUCT_DISPLAY_MODE" => "N",
			"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
			"ENLARGE_PRODUCT" => "STRICT",
			"ENLARGE_PROP" => "",
			"SHOW_SLIDER" => "N",
			"SLIDER_INTERVAL" => "3000",
			"SLIDER_PROGRESS" => "N",
			"PRODUCT_SUBSCRIPTION" => "N",
			"SHOW_DISCOUNT_PERCENT" => "N",
			"DISCOUNT_PERCENT_POSITION" => "",
			"SHOW_OLD_PRICE" => "N",
			"SHOW_MAX_QUANTITY" => "N",
			"MESS_SHOW_MAX_QUANTITY" => "Наличие",
			"RELATIVE_QUANTITY_FACTOR" => "",
			"MESS_RELATIVE_QUANTITY_MANY" => "",
			"MESS_RELATIVE_QUANTITY_FEW" => "",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_ADD_TO_BASKET" => "ДОБАВИТЬ В КОРЗИНУ",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"MESS_BTN_DETAIL" => "Подробнее",
			"MESS_NOT_AVAILABLE" => "Нет в наличии",
			"MESS_BTN_COMPARE" => "Сравнение",
			"USE_ENHANCED_ECOMMERCE" => "N",
			"ADD_TO_BASKET_ACTION" => Array
				(
					"0" => "BUY",
				),

			"SHOW_CLOSE_POPUP" => "N",
			"BACKGROUND_IMAGE" => "-",
			"COMPATIBLE_MODE" => "N",
			"DISABLE_INIT_JS_IN_COMPONENT" => "N",
			"SHOW_ALL_WO_SECTION" => "Y"
		),
		false
	);

} ?>





<?
$el_settings = $dataClassSettings::getList([
	'select' => ['BANNER_' => 'BANNER']
])->fetch();
if ($banner_id = intval($el_settings['BANNER_IBLOCK_GENERIC_VALUE'])) {
	$dataClassBanner = \Bitrix\Iblock\Iblock::wakeUp(BANNERS_ID)->getEntityDataClass();
	$el_banner = $dataClassBanner::getList([
		'select' => ['TITLE_' => 'BANNER_TITLE', 'SUBTITLE_' => 'BANNER_SUBTITLE', 'PREVIEW_PICTURE', 'PREVIEW_TEXT'],
		'filter' => ['ID' => $banner_id]
	])->fetch();
	
	if ($el_banner['TITLE_VALUE'] || $el_banner['SUBTITLE_VALUE'] || $el_banner['PREVIEW_PICTURE'] || $el_banner['PREVIEW_TEXT']) { ?>
	<section class="discount_banner">
		<div class="cont">
			<? if($el_banner['TITLE_VALUE']) {?><div class="title"><?=$el_banner['TITLE_VALUE'];?></div><?}?>

			<div class="socials">
				<? if($el_banner['SUBTITLE_VALUE']) {?><div><?=$el_banner['SUBTITLE_VALUE'];?></div><?}?>
				<? if($el_banner['PREVIEW_TEXT']) {?><div class="row"><?=$el_banner['PREVIEW_TEXT'];?></div><?}?>
			</div>
		</div>

		<? if($pic = intval($el_banner['PREVIEW_PICTURE'])) {?><img src="<?=CFile::GetPath($pic);?>" loading="lazy" class="bg"><?}?>
	</section>
<?	}
} */
?>



