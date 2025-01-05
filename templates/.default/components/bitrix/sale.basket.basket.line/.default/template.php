<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * @global string $componentPath
 * @global string $templateName
 * @var CBitrixComponentTemplate $this
 */
$cartStyle = 'bx-basket';
$cartId = "bx_basket".$this->randString();
$arParams['cartId'] = $cartId;

if ($arParams['POSITION_FIXED'] == 'Y')
{
	$cartStyle .= "-fixed {$arParams['POSITION_HORIZONTAL']} {$arParams['POSITION_VERTICAL']}";
	if ($arParams['SHOW_PRODUCTS'] == 'Y')
		$cartStyle .= ' bx-closed';
}
else
{
	$cartStyle .= ' bx-opener';
}
?><script>
var <?=$cartId?> = new BitrixSmallCart;
</script>


<pre>
<? print_R($arResult);?>
</pre>

<? global $arResDelayed;?>
<?
$amount_delay = 0;
$arDelayed = array();
$arResDelayed = array();
if (isset($arResult['ITEMS'][0])) { 
	foreach($arResult['ITEMS'] as $arItem){	
		if ($arItem["DELAY"] == "Y") {
			$amount_delay += 1;
			//$mxResult  = CCatalogSku::GetProductInfo($arItem["PRODUCT_ID"]);
			//if (isset($mxResult['ID']) && intval($mxResult['ID'])) $arItem["PRODUCT_ID"] = $mxResult['ID'];
			array_push($arDelayed, $arItem["PRODUCT_ID"]);
		}
	} 
	
	
	if (count($arDelayed)) {
		$res = CIBlockElement::GetList(Array(), Array("ID"=>$arDelayed), false, false, Array("ID", "IBLOCK_ID"));
		while($ob = $res->GetNextElement()){ 
			$arFields = $ob->GetFields();
		//	if ($arFields["IBLOCK_ID"] == 1)
				array_push($arResDelayed, $arFields["ID"]);
		/*	else {
				$mxResult  = CCatalogSku::GetProductInfo($arFields["ID"]);
				if (is_array($mxResult))
					array_push($arResDelayed, $mxResult["ID"]);
			} */
		}
	}
} 
?>


<script>
	var delay_cart_items = [<?
		echo implode(",",$arResDelayed);
	?>];
	
	$(function(){
		if (delay_cart_items) {
			$.each($('.favorite_link_prod'), function(){
				if ($.inArray(parseInt($(this).attr('prod')), delay_cart_items) != -1) {
					$(this).addClass('active');
				}
			});
		}
	})
	
</script>

<div class="favorite">
	<a href="/wishlist/" class="btn">
		<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_favorite"></use></svg>
		<span><?=$amount_delay;?></span>
	</a>
</div>

<div id="<?=$cartId?>" class="<?=$cartStyle?> cart"><?
	/** @var \Bitrix\Main\Page\FrameBuffered $frame */
	$frame = $this->createFrame($cartId, false)->begin();
		require(realpath(__DIR__).'/ajax_template.php');
	$frame->beginStub();
		$arResult['COMPOSITE_STUB'] = 'Y';
		require(realpath(__DIR__).'/top_template.php');
		unset($arResult['COMPOSITE_STUB']);
	$frame->end();
?>
</div>
							
							
							
							
							
<script type="text/javascript">
	<?=$cartId?>.siteId       = '<?=SITE_ID?>';
	<?=$cartId?>.cartId       = '<?=$cartId?>';
	<?=$cartId?>.ajaxPath     = '<?=$componentPath?>/ajax.php';
	<?=$cartId?>.templateName = '<?=$templateName?>';
	<?=$cartId?>.arParams     =  <?=CUtil::PhpToJSObject ($arParams)?>; // TODO \Bitrix\Main\Web\Json::encode
	<?=$cartId?>.closeMessage = '<?=GetMessage('TSB1_COLLAPSE')?>';
	<?=$cartId?>.openMessage  = '<?=GetMessage('TSB1_EXPAND')?>';
	<?=$cartId?>.activate();
</script>