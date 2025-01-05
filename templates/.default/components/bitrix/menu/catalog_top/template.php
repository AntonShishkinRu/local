<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
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
</div>
<?endif?>