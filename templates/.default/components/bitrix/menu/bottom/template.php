<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>

<?
foreach($arResult as $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>	
	<div><a href="<?=$arItem["LINK"]?>"<?if($arItem["SELECTED"]){?> class="active"<?}?>><?=$arItem["TEXT"]?></a></div>
<?endforeach?>



