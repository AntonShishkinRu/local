<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<? if (count($arResult["ITEMS"])) { ?>
<section class="banners block">
	<div class="cont">
		<div class="row">
			<?foreach($arResult["ITEMS"] as $key => $arItem) { ?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<a id="<?=$this->GetEditAreaId($arItem['ID']);?>" href="<?=$arItem['PROPERTIES']['URL']['VALUE']?>" class="banner" target="_blank" rel="noopener nofollow">
					<div class="thumb">
						<? if ($arItem["PREVIEW_PICTURE"]["SRC"]) { ?>
							<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" loading="lazy">
						<? } ?>
						
					</div>
				</a>
			<? } ?>
		</div>
	</div>
</section>		
<? } ?>