<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<?/*
<div class="sub_menu">
	<div class="cont small">
		<div class="row">
			<div class="col">
			<?
			foreach($arResult as $key => $arItem):
				if ($key && !($key % 2)) echo '</div><div class="col">';
			?>
				<div><a href="<?=$arItem["LINK"]?>"<?if($arItem["SELECTED"]):?> class="active"<?endif?>><?=$arItem["TEXT"]?></a></div>
			<?endforeach?>
			</div>
		</div>
	</div>
</div> */?>

<?
$hided_items = 0;
foreach($arResult as $key => $arItem): ?>
	<? if ($key == 6) { $hided_items = 1;?>
		<div class="hide">
	<? } ?>
	<div><a href="<?=$arItem["LINK"]?>"<?if($arItem["SELECTED"]):?> class="active"<?endif?>><?=$arItem["TEXT"]?></a></div>
<?endforeach?>

<? if ($hided_items) { ?>
	</div>

	<button class="spoler_btn">
		<span>Еще</span>
		<span>Свернуть</span>
		<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>
	</button>
<? } else { ?>
	<button class="spoler_btn">
		<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>
	</button>
<? } ?>

<?endif?>