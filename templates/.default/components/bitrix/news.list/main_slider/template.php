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
if (count($arResult["ITEMS"])) {
?>			

<?

function showSliderItem($arItem) { ?>
	<div class="swiper-slide">
		<? if ($arItem["PROPERTIES"]["VIDEO"]['VALUE']) { ?>
			<video playsinline muted autoplay loop>
				<source src="<?=CFile::GetPath($arItem["PROPERTIES"]["VIDEO"]['VALUE']);?>" type="video/mp4">
			</video>
		<? } else { ?>
			<? if ($arItem["PROPERTIES"]["HEADER"]['VALUE'] || $arItem["~PREVIEW_TEXT"] || $arItem["PROPERTIES"]["URL"]['VALUE']) { ?>
				<div class="cont small">
					<div class="data">
						<? if ($arItem["PROPERTIES"]["HEADER"]['VALUE']) { ?>
							<div class="title"><?=$arItem["PROPERTIES"]["HEADER"]['~VALUE'];?></div>
						<? } ?>
						
						<? if ($arItem["~PREVIEW_TEXT"]) { ?>
							<div class="desc"><?=$arItem["~PREVIEW_TEXT"];?></div>
						<? } ?>
						
						<? if ($arItem["PROPERTIES"]["URL"]['VALUE']) { ?>
							<a href="<?=$arItem["PROPERTIES"]["URL"]['VALUE'];?>" class="link">ПОДРОБНЕЕ</a>
						<? } ?>
					</div>
				</div>
			<? } ?>
			<? if (!empty($arItem["PREVIEW_PICTURE"]["SRC"])) { ?>
				<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>" alt="" class="img" loading="lazy">
			<? } ?>
		<? } ?>
	</div>
<?}

$show_main = false;
foreach($arResult["ITEMS"] as $key => $arItem) {
	if ($arItem["PROPERTIES"]["SHOW_MAIN"]['VALUE']) {
		$show_main = true;
		break;		
	}
}

if($show_main) { ?>
	<section class="main_slider<? if ($arParams['IS_MAIN_MOBILE_SHOW'] == 'N'){?> main_slider_always<?}?>">
		<div class="swiper">
			<div class="swiper-wrapper">
				<?
				foreach($arResult["ITEMS"] as $key => $arItem) {
					if ($arItem["PROPERTIES"]["SHOW_MAIN"]['VALUE']) { 
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						showSliderItem($arItem);
					}	
				} ?>
			</div>

			<button class="swiper-button-prev">
				<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>
			</button>

			<button class="swiper-button-next">
				<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>
			</button>

			<div class="swiper-pagination"></div>
		</div>
	</section>
<? } 

if ($arParams['IS_MAIN_MOBILE_SHOW'] == 'Y'){
	$show_mobile = false;
	foreach($arResult["ITEMS"] as $key => $arItem) {
		if ($arItem["PROPERTIES"]["SHOW_MOBILE"]['VALUE']) {
			$show_mobile = true;
			break;		
		}
	}	
	
	if($show_mobile) { ?>
		<section class="main_slider mob_main_slider">
			<div class="swiper">
				<div class="swiper-wrapper">
					<?
					foreach($arResult["ITEMS"] as $key => $arItem) {
						if ($arItem["PROPERTIES"]["SHOW_MOBILE"]['VALUE']) { 
							$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
							$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							showSliderItem($arItem);
						}	
					} ?>
				</div>

				<div class="swiper-pagination"></div>
			</div>
		</section>
	<? } 
	
}
?>




<?/*
<section class="main_slider mob_main_slider">
	<div class="swiper">
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<video playsinline muted autoplay loop>
					<source src="video-2.mp4" type="video/mp4">
				</video>
			</div>

			<div class="swiper-slide">
				<video playsinline muted loop>
					<source src="video-4.mp4" type="video/mp4">
				</video>
			</div>

			<a href="/" class="swiper-slide">
				<div class="cont small">
					<div class="data">
						<div class="title">Финальная распродажа</div>

						<div class="desc">Роскошное дизайнерское платье А силуэта с закрытой спиной и плечами — настоящая находка для любой женщины!</div>

						<div class="link">
							<span>ПОДРОБНЕЕ</span>
							<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>
						</div>
					</div>
				</div>

				<img src="images/tmp/main_slider_img.jpg" alt="" class="img" loading="lazy">
			</a>

			<div class="swiper-slide">
				<img src="images/tmp/main_slider_img.jpg" alt="" class="img" loading="lazy">
			</div>
		</div>

		<div class="swiper-pagination"></div>
	</div>
</section>
				
*/
 } ?>