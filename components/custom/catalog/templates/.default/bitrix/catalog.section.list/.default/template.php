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

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
	'LIST' => array(
		'CONT' => 'bx_sitemap',
		'TITLE' => 'bx_sitemap_title',
		'LIST' => 'bx_sitemap_ul',
	),
	'LINE' => array(
		'CONT' => 'bx_catalog_line',
		'TITLE' => 'bx_catalog_line_category_title',
		'LIST' => 'bx_catalog_line_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/line-empty.png'
	),
	'TEXT' => array(
		'CONT' => 'bx_catalog_text',
		'TITLE' => 'bx_catalog_text_category_title',
		'LIST' => 'bx_catalog_text_ul'
	),
	'TILE' => array(
		'CONT' => 'bx_catalog_tile',
		'TITLE' => 'bx_catalog_tile_category_title',
		'LIST' => 'bx_catalog_tile_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	)
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

?>


<?$this->SetViewTarget("h1");?>
<?
if ('Y' == $arParams['SHOW_PARENT_NAME'] && 0 < $arResult['SECTION']['ID'])
{
	$this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], $strSectionEdit);
	$this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

	?><h1
		class="<? echo $arCurView['TITLE']; ?> page_title small_m_bottom"
		id="<? echo $this->GetEditAreaId($arResult['SECTION']['ID']); ?>"
	><?
		echo (
			isset($arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) && $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != ""
			? $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]
			: $arResult['SECTION']['NAME']
		);
	?></h1><?
}
?>
<?$this->EndViewTarget();?>

<?$this->SetViewTarget("banners");?>
<? if (!empty($arResult['SECTION']['UF_BANNERS'][0])) { 
	$dataClassBanners = \Bitrix\Iblock\Iblock::wakeUp(BANNERS_ID)->getEntityDataClass();
	$arBanners = $dataClassBanners::getList([
		'select' => ['PREVIEW_PICTURE', 'URL_' => 'URL'],
	])->fetchAll();
	if (!empty($arBanners[0])) { ?>
		<section class="banners_slider block small_m">
			<div class="cont">
				<div class="swiper">
					<div class="swiper-wrapper">
						<? foreach($arBanners as $arBanner) {
							if ($pic_id = intval($arBanner['PREVIEW_PICTURE'])) { ?>
							<div class="swiper-slide">
								<a href="<?=$arBanner['URL_VALUE']?>" class="banner">
									<img src="<?=CFile::GetPath($pic_id);?>" loading="lazy">
								</a>
							</div>
						<? } 
						}?>
					</div>

					<div class="swiper-pagination"></div>
				</div>
			</div>
		</section>
<?	} 
} ?> 
<?$this->EndViewTarget();?>

<?$this->SetViewTarget("content");?>
<? if ($arResult['SECTION']['~DESCRIPTION'] || $arResult['SECTION']['~UF_HEADER']) { ?>
	<section class="block">
		<div class="cont padding">
			<? if ($arResult['SECTION']['~UF_HEADER']) { ?>
				<div class="block_head small_m">
					<div class="title"><?=$arResult['SECTION']['~UF_HEADER'];?></div>
				</div>
			<? } ?>

			<? if ($arResult['SECTION']['~DESCRIPTION']) { ?>
				<div class="text_block big"><?=$arResult['SECTION']['~DESCRIPTION'];?></div>
			<? } ?>
		</div>
	</section>
<? } ?>
<?$this->EndViewTarget();?>


<?/*
<? if ($arResult["SECTIONS_COUNT"] == 0) {
	$rsSection = \Bitrix\Iblock\SectionTable::getList(array(
		'filter' => array(
			'ID' => $arResult["SECTION"]["IBLOCK_SECTION_ID"]
		), 
		'select' =>  array(
			'ID',
			'SECTION_CODE' => 'CODE',
			'SECTION_PAGE_URL_TEMPLATE' => 'IBLOCK.SECTION_PAGE_URL')
	));
	if ($arSection=$rsSection->fetch()) {
		$arSection['NAME'] = "Все";
		$arSection['SECTION_PAGE_URL'] = \CIBlock::ReplaceDetailUrl($arSection['SECTION_PAGE_URL_TEMPLATE'], $arSection, true, false);
		$arResult['SECTIONS'][] = $arSection;
	}

	$rsSection = \Bitrix\Iblock\SectionTable::getList(array(
		'filter' => array(
			'IBLOCK_SECTION_ID' => $arResult["SECTION"]["IBLOCK_SECTION_ID"],
			'DEPTH_LEVEL' => $arResult["SECTION"]["DEPTH_LEVEL"],
			'ACTIVE' => 'Y',
			'GLOBAL_ACTIVE' => 'Y',
		), 
		'select' =>  array(
			'ID', 'NAME',
			'SECTION_CODE' => 'CODE',
			'SECTION_PAGE_URL_TEMPLATE' => 'IBLOCK.SECTION_PAGE_URL')
	));

	while ($arSection=$rsSection->fetch()) {
		$arSection['SECTION_PAGE_URL'] = \CIBlock::ReplaceDetailUrl($arSection['SECTION_PAGE_URL_TEMPLATE'], $arSection, true, false);
		$arResult['SECTIONS'][] = $arSection;
	}
}?>
<section class="sub_categories <? echo $arCurView['CONT']; ?>">
	<div class="cont">
		<div class="swiper">
			<div class="swiper-wrapper">
				<? foreach ($arResult['SECTIONS'] as &$arSection) {
					if ($arSection['EDIT_LINK']) $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
					if ($arSection['DELETE_LINK']) $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);?>
					<div class="swiper-slide">
						<a href="<? echo $arSection['SECTION_PAGE_URL']; ?>" class="link"><? echo $arSection['NAME']; ?></a>
					</div>
				<? } ?>
			</div>

			<button class="swiper-button-prev">
				<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>
			</button>

			<button class="swiper-button-next">
				<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>
			</button>
		</div>
	</div>
</section>
<? */
//if (0 < $arResult["SECTIONS_COUNT"])
if (0)
{
?>
<section class="sub_categories">
	<div class="cont">
		<div class="swiper">
			<div class="swiper-wrapper <? echo $arCurView['LIST']; ?>">
<?
	switch ($arParams['VIEW_MODE'])
	{
		case 'LINE':
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				if (false === $arSection['PICTURE'])
					$arSection['PICTURE'] = array(
						'SRC' => $arCurView['EMPTY_IMG'],
						'ALT' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							: $arSection["NAME"]
						),
						'TITLE' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							: $arSection["NAME"]
						)
					);
				?><li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
				<a
					href="<? echo $arSection['SECTION_PAGE_URL']; ?>"
					class="bx_catalog_line_img"
					style="background-image: url('<? echo $arSection['PICTURE']['SRC']; ?>');"
					title="<? echo $arSection['PICTURE']['TITLE']; ?>"
				></a>
				<h2 class="bx_catalog_line_title"><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
				if ($arParams["COUNT_ELEMENTS"] && $arSection['ELEMENT_CNT'] !== null)
				{
					?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
				}
				?></h2><?
				if ('' != $arSection['DESCRIPTION'])
				{
					?><p class="bx_catalog_line_description"><? echo $arSection['DESCRIPTION']; ?></p><?
				}
				?><div style="clear: both;"></div>
				</li><?
			}
			unset($arSection);
			break;
		case 'TEXT':
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				?><li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>"><h2 class="bx_catalog_text_title"><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
				if ($arParams["COUNT_ELEMENTS"] && $arSection['ELEMENT_CNT'] !== null)
				{
					?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
				}
				?></h2></li><?
			}
			unset($arSection);
			break;
		case 'TILE':
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				if (false === $arSection['PICTURE'])
					$arSection['PICTURE'] = array(
						'SRC' => $arCurView['EMPTY_IMG'],
						'ALT' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							: $arSection["NAME"]
						),
						'TITLE' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							: $arSection["NAME"]
						)
					);
				?><li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
				<a
					href="<? echo $arSection['SECTION_PAGE_URL']; ?>"
					class="bx_catalog_tile_img"
					style="background-image:url('<? echo $arSection['PICTURE']['SRC']; ?>');"
					title="<? echo $arSection['PICTURE']['TITLE']; ?>"
					> </a><?
				if ('Y' != $arParams['HIDE_SECTION_NAME'])
				{
					?><h2 class="bx_catalog_tile_title"><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
					if ($arParams["COUNT_ELEMENTS"] && $arSection['ELEMENT_CNT'] !== null)
					{
						?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
					}
				?></h2><?
				}
				?></li><?
			}
			unset($arSection);
			break;
		case 'LIST':
			$intCurrentDepth = 1;
			$boolFirst = true;
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				if ($intCurrentDepth < $arSection['RELATIVE_DEPTH_LEVEL'])
				{
					if (0 < $intCurrentDepth)
						echo "\n",str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']),'<ul>';
				}
				elseif ($intCurrentDepth == $arSection['RELATIVE_DEPTH_LEVEL'])
				{
					if (!$boolFirst)
						echo '</li>';
				}
				else
				{
					while ($intCurrentDepth > $arSection['RELATIVE_DEPTH_LEVEL'])
					{
						echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
						$intCurrentDepth--;
					}
					echo str_repeat("\t", $intCurrentDepth-1),'</li>';
				}

				echo (!$boolFirst ? "\n" : ''),str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']);
				?><li id="<?=$this->GetEditAreaId($arSection['ID']);?>"><h2 class="bx_sitemap_li_title"><a href="<? echo $arSection["SECTION_PAGE_URL"]; ?>"><? echo $arSection["NAME"];?><?
				if ($arParams["COUNT_ELEMENTS"] && $arSection['ELEMENT_CNT'] !== null)
				{
					?> <span>(<? echo $arSection["ELEMENT_CNT"]; ?>)</span><?
				}
				?></a></h2><?

				$intCurrentDepth = $arSection['RELATIVE_DEPTH_LEVEL'];
				$boolFirst = false;
			}
			unset($arSection);
			while ($intCurrentDepth > 1)
			{
				echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
				$intCurrentDepth--;
			}
			if ($intCurrentDepth > 0)
			{
				echo '</li>',"\n";
			}
			break;
	}
?>
			</div>

			<button class="swiper-button-prev">
				<svg class="icon"><use xlink:href="images/sprite.svg#ic_arr_hor"></use></svg>
			</button>

			<button class="swiper-button-next">
				<svg class="icon"><use xlink:href="images/sprite.svg#ic_arr_hor"></use></svg>
			</button>
		</div>
	</div>
</section>
<?
	//echo ('LINE' != $arParams['VIEW_MODE'] ? '<div style="clear: both;"></div>' : '');
}
?>