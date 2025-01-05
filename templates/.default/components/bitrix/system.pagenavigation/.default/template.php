<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>


<? if ($arResult['NavPageCount'] > 1) { ?>
<? if($arResult["NavPageNomer"] < $arResult["NavPageCount"]) { ?>
	<div class="more_btn">
		<button class="btn more_btn load_more" onclick="getNextPage('<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageNomer"] + 1?>'); return false;">Показать ещё</button>
	</div>
<? } ?>
	
<? /* <div class="count">Вы посмотрели <?=$arResult['NavLastRecordShow']?> из <?=$arResult['NavRecordCount']?></div>  */?>
<div class="links">
<?
	$bFirst = true;

	if ($arResult["NavPageNomer"] > 1 || 1):
		if($arResult["bSavePage"]):
?>
			<a page="1" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" class="prev"><svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg></a>

<?
		else:
			if ($arResult["NavPageNomer"] > 2):
?>
			<a page="1" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" class="prev"><svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg></a>
<?
			else:
?>
			<a page="1" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="prev"><svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg></a>
<?
			endif;
		
		endif;
		
		if ($arResult["nStartPage"] > 1):
			$bFirst = false;
			if($arResult["bSavePage"]):
?>
			<a page="1"  href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1">1</a>
<?
			else:
?>
			<a page="1" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a>
<?
			endif;
			if ($arResult["nStartPage"] > 2):
?>
			<div class="sep"></div>
<?
			endif;
		endif;
	endif;

	do
	{
		if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
?>
		<a page="<?=$arResult["nStartPage"];?>" href="#" class="active"><?=$arResult["nStartPage"]?></a>
<?
		elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):
?>
		<a page="<?=$arResult["nStartPage"];?>" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
<?
		else:
?>
		<a page="<?=$arResult["nStartPage"];?>" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
<?
		endif;
		$arResult["nStartPage"]++;
		$bFirst = false;
	} while($arResult["nStartPage"] <= $arResult["nEndPage"]);


	if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
		if ($arResult["nEndPage"] < $arResult["NavPageCount"]): ?>
			<?if ($arResult["NavPageCount"] > $arResult["NavPageNomer"] + 3){?><div class="sep">...</div><?}?>
			<a page="<?=$arResult["NavPageCount"];?>" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a>
	<?	endif;

	endif;
?>
		<a page="<?=$arResult["NavPageCount"]?>" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>" class="next"><svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg></a>
<?

if ($arResult["bShowAll"]):
	if ($arResult["NavShowAll"]):
?>
		
		<a class="forum-page-pagen" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0"><?=GetMessage("nav_paged")?></a>
<?
	else:
?>
		<a class="forum-page-all" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1"><?=GetMessage("nav_all")?></a>
<?
	endif;
endif
?>
</div>

<? } ?>