<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if (!empty($arResult)):?>

<div class="items">
<?
foreach($arResult as $key => $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>	
	<div><a href="<?=$arItem["LINK"]?>"<?if($arItem["SELECTED"]){?> class="active"<?}?><? if($key >= 6){?> style="display:none"<?}?>><?=$arItem["TEXT"]?></a></div>
<?endforeach?>
	
	<? if (count($arResult) > 6) { ?>
		<div><a href="" class="more_footer_cat">
			<span>Еще</span>
			<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>
		</a></div>
	<? } ?>
</div>						
<?endif?>

