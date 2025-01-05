<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
	<?
	foreach($arResult as $arItem):
		if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
			continue;
	?>
		<div>
			<a href="<?=$arItem["LINK"]?>" class="<?/*sub_link*/?><?if($arItem["SELECTED"]):?> active<?endif?>">
				<span><?=$arItem["TEXT"]?></span>
			</a>
		</div>
	<?endforeach?>
<?endif?>


<?/*

	<div>
		<a href="/" class="sub_link">
			<span>КОМПАНИЯ</span>
			<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>
		</a>
	</div>

	<div>
		<a href="/" class="sub_link">
			<span>SALE</span>
		</a>
	</div>
			
			*/?>