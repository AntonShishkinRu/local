<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?$this->setFrameMode(true);?>

<? global $arResDelayed;
$arResDelayed = [];?> 
<script>
	var ready_cart_items = [<?
		foreach($arResult['ITEMS']['BASKET'] as $arItem){
			echo $arItem["PRODUCT_ID"].",";
		} 
	?>];
	var delay_cart_items = [<?
		foreach($arResult['ITEMS']['DELAYED'] as $arItem){
			echo $arItem["PRODUCT_ID"].",";
			array_push($arResDelayed, $arItem["PRODUCT_ID"]);
		} 
	?>];
</script>

<div class="favorite">
	<a href="/wishlist/" class="btn">
		<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_favorite"></use></svg>
		<span><?=$arResult['DELAYED_NUM'];?></span>
	</a>
</div>

<div class="cart">
	<a href="/personal/cart/" class="btn">
		<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_cart"></use></svg>
		<span><?=$arResult['BASKET_QUANT_NUM'];?></span>
	</a>
</div>
