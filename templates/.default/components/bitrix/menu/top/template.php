<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<?
foreach($arResult as $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
	<div class="menu_item">
		<a href="<?=$arItem["LINK"]?>"<?if($arItem["SELECTED"]):?> class="active"<?endif?>><?=$arItem["TEXT"]?></a>
	</div>	
<?endforeach?>
<?endif?>