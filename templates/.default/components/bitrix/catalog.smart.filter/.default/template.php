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

use Bitrix\Iblock\SectionPropertyTable;

$this->setFrameMode(true);

$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/colors.css',
	'TEMPLATE_CLASS' => 'bx-'.$arParams['TEMPLATE_THEME']
);

if (isset($templateData['TEMPLATE_THEME']))
{
	//$this->addExternalCss($templateData['TEMPLATE_THEME']);
}
//$this->addExternalCss("/bitrix/css/main/bootstrap.css");
//$this->addExternalCss("/bitrix/css/main/font-awesome.css");
?>
	

<section id="filter_modal">
	<script src="/local/js/ion.rangeSlider.min.js"></script>
	<div class="scroll bx-filter filter">
		<button class="close_btn">
			<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_filter"></use></svg>

			<span>Скрыть фильтр</span>

			<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_close"></use></svg>
		</button>

	<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter form">
		<?foreach($arResult["HIDDEN"] as $arItem):?>
		<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
		<?endforeach;?>

			<?foreach($arResult["ITEMS"] as $key=>$arItem)//prices
			{
				$key = $arItem["ENCODED_ID"];
				if(isset($arItem["PRICE"])):
					if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
						continue;
					
					$curr_min = $range_min = $arItem["VALUES"]["MIN"]["VALUE"];
					$curr_max = $range_max = $arItem["VALUES"]["MAX"]["VALUE"];
					if (isset($arItem["VALUES"]["MIN"]["HTML_VALUE"]) && $arItem["VALUES"]["MIN"]["HTML_VALUE"]) $curr_min = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
					if (isset($arItem["VALUES"]["MAX"]["HTML_VALUE"]) && $arItem["VALUES"]["MAX"]["HTML_VALUE"]) $curr_max = $arItem["VALUES"]["MAX"]["HTML_VALUE"];	
					?>

					<div class="item range price_range">
						<div class="name active">
							<span>Цена</span>

							<div class="range_val">
								<span class="from"><?=number_format($curr_min, 0, '.', ' ');?></span> ₽ – <span class="to"><?=number_format($curr_max, 0, '.', ' ');?></span> ₽
							</div>

							<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>
						</div>

						<div class="data" style="display: block;">
							<div class="row" style="display:none">
								<input type="text" original="<?=$range_min?>" id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>" name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>" value="<?=$curr_min;?>" class="from">
								<input type="text" original="<?=$range_max?>" id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>" name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>" value="<?=$curr_max;?>" class="to">
							</div>
							
							
							<input type="hidden" id="price_range" name="price_range" value="">
						</div>
						
						<script>
							priceRange = $('#filter_modal #price_range').ionRangeSlider({
								type: 'double',
								min      : <?=$range_min?>,
								max      : <?=$range_max?>,
								from     : <?=$curr_min?>,
								to       : <?=$curr_max?>,	
								step: 1,
								onChange: data => {
									$('#filter_modal .price_range .range_val .from').text(data.from.toLocaleString())
									$('#filter_modal .price_range .range_val .to').text(data.to.toLocaleString())
									console.log(data.from);
									$('#filter_modal .price_range .data .from').val(data.from)
									$('#filter_modal .price_range .data .to').val(data.to)
									
									smartFilter.click($('#<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>').get('0'));
								},
								onUpdate: data => {
									$('#filter_modal .price_range .range_val .from').text(data.from.toLocaleString())
									$('#filter_modal .price_range .range_val .to').text(data.to.toLocaleString())
									console.log(data.from);
									$('#filter_modal .price_range .data .from').val(data.from)
									$('#filter_modal .price_range .data .to').val(data.to)
									
									smartFilter.click($('#<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>').get('0'));
								}
							}).data('ionRangeSlider')
						</script>
					</div>
					
				<?endif;
			}

			//not prices
			foreach($arResult["ITEMS"] as $key=>$arItem)
			{
				if(
					empty($arItem["VALUES"])
					|| isset($arItem["PRICE"])
				)
					continue;

				if (
					$arItem["DISPLAY_TYPE"] === SectionPropertyTable::NUMBERS_WITH_SLIDER
					&& ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
				)
					continue;
				?>
				<div class="item bx-filter-parameters-box">
					<span class="bx-filter-container-modef"></span>
					
					<div class="name<?if (1 || $arItem["DISPLAY_EXPANDED"]== "Y"):?> active<?endif?>"<?/* onclick="smartFilter.hideFilterProps(this)"*/?>>
						<span><?=$arItem["NAME"]?> </span>

						<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>
					</div>

					<div class="bx-filter-block data" style="display: block;" data-role="bx_filter_block">
						<div class="bx-filter-parameters-box-container">
						<?
						$arCur = current($arItem["VALUES"]);
						switch ($arItem["DISPLAY_TYPE"])
						{
							case SectionPropertyTable::NUMBERS_WITH_SLIDER://NUMBERS_WITH_SLIDER
								?>
								<div class="col-xs-6 bx-filter-parameters-box-container-block bx-left">
									<i class="bx-ft-sub"><?=GetMessage("CT_BCSF_FILTER_FROM")?></i>
									<div class="bx-filter-input-container">
										<input
											class="min-price"
											type="text"
											name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
											id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
											value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
											size="5"
											onkeyup="smartFilter.keyup(this)"
										/>
									</div>
								</div>
								<div class="col-xs-6 bx-filter-parameters-box-container-block bx-right">
									<i class="bx-ft-sub"><?=GetMessage("CT_BCSF_FILTER_TO")?></i>
									<div class="bx-filter-input-container">
										<input
											class="max-price"
											type="text"
											name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
											id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
											value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
											size="5"
											onkeyup="smartFilter.keyup(this)"
										/>
									</div>
								</div>

								<div class="col-xs-10 col-xs-offset-1 bx-ui-slider-track-container">
									<div class="bx-ui-slider-track" id="drag_track_<?=$key?>">
										<?
										$precision = $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0;
										$step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 4;
										$value1 = number_format($arItem["VALUES"]["MIN"]["VALUE"], $precision, ".", "");
										$value2 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step, $precision, ".", "");
										$value3 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 2, $precision, ".", "");
										$value4 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 3, $precision, ".", "");
										$value5 = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
										?>
										<div class="bx-ui-slider-part p1"><span><?=$value1?></span></div>
										<div class="bx-ui-slider-part p2"><span><?=$value2?></span></div>
										<div class="bx-ui-slider-part p3"><span><?=$value3?></span></div>
										<div class="bx-ui-slider-part p4"><span><?=$value4?></span></div>
										<div class="bx-ui-slider-part p5"><span><?=$value5?></span></div>

										<div class="bx-ui-slider-pricebar-vd" style="left: 0;right: 0;" id="colorUnavailableActive_<?=$key?>"></div>
										<div class="bx-ui-slider-pricebar-vn" style="left: 0;right: 0;" id="colorAvailableInactive_<?=$key?>"></div>
										<div class="bx-ui-slider-pricebar-v"  style="left: 0;right: 0;" id="colorAvailableActive_<?=$key?>"></div>
										<div class="bx-ui-slider-range" 	id="drag_tracker_<?=$key?>"  style="left: 0;right: 0;">
											<a class="bx-ui-slider-handle left"  style="left:0;" href="javascript:void(0)" id="left_slider_<?=$key?>"></a>
											<a class="bx-ui-slider-handle right" style="right:0;" href="javascript:void(0)" id="right_slider_<?=$key?>"></a>
										</div>
									</div>
								</div>
								<?
								$arJsParams = array(
									"leftSlider" => 'left_slider_'.$key,
									"rightSlider" => 'right_slider_'.$key,
									"tracker" => "drag_tracker_".$key,
									"trackerWrap" => "drag_track_".$key,
									"minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
									"maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
									"minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
									"maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
									"curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
									"curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
									"fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
									"fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
									"precision" => $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0,
									"colorUnavailableActive" => 'colorUnavailableActive_'.$key,
									"colorAvailableActive" => 'colorAvailableActive_'.$key,
									"colorAvailableInactive" => 'colorAvailableInactive_'.$key,
								);
								?>
								<script type="text/javascript">
									BX.ready(function(){
										window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(<?=CUtil::PhpToJSObject($arJsParams)?>);
									});
								</script>
								<?
								break;
							case SectionPropertyTable::NUMBERS://NUMBERS
								?>
								<div class="col-xs-6 bx-filter-parameters-box-container-block bx-left">
									<i class="bx-ft-sub"><?=GetMessage("CT_BCSF_FILTER_FROM")?></i>
									<div class="bx-filter-input-container">
										<input
											class="min-price"
											type="text"
											name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
											id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
											value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
											size="5"
											onkeyup="smartFilter.keyup(this)"
											/>
									</div>
								</div>
								<div class="col-xs-6 bx-filter-parameters-box-container-block bx-right">
									<i class="bx-ft-sub"><?=GetMessage("CT_BCSF_FILTER_TO")?></i>
									<div class="bx-filter-input-container">
										<input
											class="max-price"
											type="text"
											name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
											id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
											value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
											size="5"
											onkeyup="smartFilter.keyup(this)"
											/>
									</div>
								</div>
								<?
								break;
							case SectionPropertyTable::CHECKBOXES_WITH_PICTURES://CHECKBOXES_WITH_PICTURES
								?>
								<div class="col-xs-12">
									<div class="bx-filter-param-btn-inline">
									<?foreach ($arItem["VALUES"] as $val => $ar):?>
										<input
											style="display: none"
											type="checkbox"
											name="<?=$ar["CONTROL_NAME"]?>"
											id="<?=$ar["CONTROL_ID"]?>"
											value="<?=$ar["HTML_VALUE"]?>"
											<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
										/>
										<?
										$class = "";
										if ($ar["CHECKED"])
											$class.= " bx-active";
										if ($ar["DISABLED"])
											$class.= " disabled";
										?>
										<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label <?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
											<span class="bx-filter-param-btn bx-color-sl">
												<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
												<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
												<?endif?>
											</span>
										</label>
									<?endforeach?>
									</div>
								</div>
								<?
								break;
							case SectionPropertyTable::CHECKBOXES_WITH_PICTURES_AND_LABELS://CHECKBOXES_WITH_PICTURES_AND_LABELS
								?>
								<div class="col-xs-12">
									<div class="bx-filter-param-btn-block">
									<?foreach ($arItem["VALUES"] as $val => $ar):?>
										<input
											style="display: none"
											type="checkbox"
											name="<?=$ar["CONTROL_NAME"]?>"
											id="<?=$ar["CONTROL_ID"]?>"
											value="<?=$ar["HTML_VALUE"]?>"
											<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
										/>
										<?
										$class = "";
										if ($ar["CHECKED"])
											$class.= " bx-active";
										if ($ar["DISABLED"])
											$class.= " disabled";
										?>
										<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
											<span class="bx-filter-param-btn bx-color-sl">
												<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
													<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
												<?endif?>
											</span>
											<span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?
											if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
												?> (<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
											endif;?></span>
										</label>
									<?endforeach?>
									</div>
								</div>
								<?
								break;
							case SectionPropertyTable::DROPDOWN://DROPDOWN
								$checkedItemExist = false;
								?>
								<div class="col-xs-12">
									<div class="bx-filter-select-container">
										<div class="bx-filter-select-block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
											<div class="bx-filter-select-text" data-role="currentOption">
												<?
												foreach ($arItem["VALUES"] as $val => $ar)
												{
													if ($ar["CHECKED"])
													{
														echo $ar["VALUE"];
														$checkedItemExist = true;
													}
												}
												if (!$checkedItemExist)
												{
													echo GetMessage("CT_BCSF_FILTER_ALL");
												}
												?>
											</div>
											<div class="bx-filter-select-arrow"></div>
											<input
												style="display: none"
												type="radio"
												name="<?=$arCur["CONTROL_NAME_ALT"]?>"
												id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
												value=""
											/>
											<?foreach ($arItem["VALUES"] as $val => $ar):?>
												<input
													style="display: none"
													type="radio"
													name="<?=$ar["CONTROL_NAME_ALT"]?>"
													id="<?=$ar["CONTROL_ID"]?>"
													value="<? echo $ar["HTML_VALUE_ALT"] ?>"
													<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
												/>
											<?endforeach?>
											<div class="bx-filter-select-popup" data-role="dropdownContent" style="display: none;">
												<ul>
													<li>
														<label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
															<? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
														</label>
													</li>
												<?
												foreach ($arItem["VALUES"] as $val => $ar):
													$class = "";
													if ($ar["CHECKED"])
														$class.= " selected";
													if ($ar["DISABLED"])
														$class.= " disabled";
												?>
													<li>
														<label for="<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" data-role="label_<?=$ar["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')"><?=$ar["VALUE"]?></label>
													</li>
												<?endforeach?>
												</ul>
											</div>
										</div>
									</div>
								</div>
								<?
								break;
							case SectionPropertyTable::DROPDOWN_WITH_PICTURES_AND_LABELS://DROPDOWN_WITH_PICTURES_AND_LABELS
								?>
								<div class="col-xs-12">
									<div class="bx-filter-select-container">
										<div class="bx-filter-select-block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
											<div class="bx-filter-select-text fix" data-role="currentOption">
												<?
												$checkedItemExist = false;
												foreach ($arItem["VALUES"] as $val => $ar):
													if ($ar["CHECKED"])
													{
													?>
														<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
															<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
														<?endif?>
														<span class="bx-filter-param-text">
															<?=$ar["VALUE"]?>
														</span>
													<?
														$checkedItemExist = true;
													}
												endforeach;
												if (!$checkedItemExist)
												{
													?><span class="bx-filter-btn-color-icon all"></span> <?
													echo GetMessage("CT_BCSF_FILTER_ALL");
												}
												?>
											</div>
											<div class="bx-filter-select-arrow"></div>
											<input
												style="display: none"
												type="radio"
												name="<?=$arCur["CONTROL_NAME_ALT"]?>"
												id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
												value=""
											/>
											<?foreach ($arItem["VALUES"] as $val => $ar):?>
												<input
													style="display: none"
													type="radio"
													name="<?=$ar["CONTROL_NAME_ALT"]?>"
													id="<?=$ar["CONTROL_ID"]?>"
													value="<?=$ar["HTML_VALUE_ALT"]?>"
													<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
												/>
											<?endforeach?>
											<div class="bx-filter-select-popup" data-role="dropdownContent" style="display: none">
												<ul>
													<li style="border-bottom: 1px solid #e5e5e5;padding-bottom: 5px;margin-bottom: 5px;">
														<label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
															<span class="bx-filter-btn-color-icon all"></span>
															<? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
														</label>
													</li>
												<?
												foreach ($arItem["VALUES"] as $val => $ar):
													$class = "";
													if ($ar["CHECKED"])
														$class.= " selected";
													if ($ar["DISABLED"])
														$class.= " disabled";
												?>
													<li>
														<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')">
															<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
																<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
															<?endif?>
															<span class="bx-filter-param-text">
																<?=$ar["VALUE"]?>
															</span>
														</label>
													</li>
												<?endforeach?>
												</ul>
											</div>
										</div>
									</div>
								</div>
								<?
								break;
							case SectionPropertyTable::RADIO_BUTTONS://RADIO_BUTTONS
								?>
								<div class="col-xs-12">
									<div class="radio">
										<label class="bx-filter-param-label" for="<? echo "all_".$arCur["CONTROL_ID"] ?>">
											<span class="bx-filter-input-checkbox">
												<input
													type="radio"
													value=""
													name="<? echo $arCur["CONTROL_NAME_ALT"] ?>"
													id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
													onclick="smartFilter.click(this)"
												/>
												<span class="bx-filter-param-text"><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?></span>
											</span>
										</label>
									</div>
									<?foreach($arItem["VALUES"] as $val => $ar):?>
										<div class="radio">
											<label data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label" for="<? echo $ar["CONTROL_ID"] ?>">
												<span class="bx-filter-input-checkbox <? echo $ar["DISABLED"] ? 'disabled': '' ?>">
													<input
														type="radio"
														value="<? echo $ar["HTML_VALUE_ALT"] ?>"
														name="<? echo $ar["CONTROL_NAME_ALT"] ?>"
														id="<? echo $ar["CONTROL_ID"] ?>"
														<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
														onclick="smartFilter.click(this)"
													/>
													<span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?
													if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
														?>&nbsp;(<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
													endif;?></span>
												</span>
											</label>
										</div>
									<?endforeach;?>
								</div>
								<?
								break;
							case SectionPropertyTable::CALENDAR://CALENDAR
								?>
								<div class="col-xs-12">
									<div class="bx-filter-parameters-box-container-block"><div class="bx-filter-input-container bx-filter-calendar-container">
										<?$APPLICATION->IncludeComponent(
											'bitrix:main.calendar',
											'',
											array(
												'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
												'SHOW_INPUT' => 'Y',
												'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MIN"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
												'INPUT_NAME' => $arItem["VALUES"]["MIN"]["CONTROL_NAME"],
												'INPUT_VALUE' => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
												'SHOW_TIME' => 'N',
												'HIDE_TIMEBAR' => 'Y',
											),
											null,
											array('HIDE_ICONS' => 'Y')
										);?>
									</div></div>
									<div class="bx-filter-parameters-box-container-block"><div class="bx-filter-input-container bx-filter-calendar-container">
										<?$APPLICATION->IncludeComponent(
											'bitrix:main.calendar',
											'',
											array(
												'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
												'SHOW_INPUT' => 'Y',
												'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MAX"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
												'INPUT_NAME' => $arItem["VALUES"]["MAX"]["CONTROL_NAME"],
												'INPUT_VALUE' => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
												'SHOW_TIME' => 'N',
												'HIDE_TIMEBAR' => 'Y',
											),
											null,
											array('HIDE_ICONS' => 'Y')
										);?>
									</div></div>
								</div>
								<?
								break;
							default://CHECKBOXES
								?>
									<? $iter = 0; ?>
									<?foreach($arItem["VALUES"] as $val => $ar):
										if (++$iter == 6) { ?><div class="hide_custom"><? } ?>
										<div class="field">
											<label data-role="label_<?=$ar["CONTROL_ID"]?>" class="checkbox <? echo $ar["DISABLED"] ? 'disabled': '' ?>" for="<? echo $ar["CONTROL_ID"] ?>">
										<?/*		<span class="bx-filter-input-checkbox"> */?>
													<input
														type="checkbox"
														value="<? echo $ar["HTML_VALUE"] ?>"
														name="<? echo $ar["CONTROL_NAME"] ?>"
														id="<? echo $ar["CONTROL_ID"] ?>"
														<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
														onclick="smartFilter.click(this)"
													/>
													<div class="check">
														<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_check"></use></svg>
													</div>
													
													<div class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?
													if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
														?>&nbsp;(<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
													endif;?></div>
										<?/*		</span>  */?>
											</label>
										</div>
									<?endforeach;?>
									
									<? if ($iter >= 6) {?>
									</div>

									<button class="spoler_btn">
										<span>Показать еще</span>
										<span>Свернуть</span>
									</button>
									<? } ?>
									
									
									
						<?
						}
						?>
						</div>
						<div style="clear: both"></div>
					</div>
				</div>
			<?
			}
			?>
			

			<div class="item submit">
				<button
					class="submit_btn"
					type="submit"
					id="set_filter"
					name="set_filter"
					value="<?=GetMessage("CT_BCSF_SET_FILTER")?>"
				><?=GetMessage("CT_BCSF_SET_FILTER")?></button>

				<button type="submit" class="reset_btn" id="del_filter" name="del_filter" value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>">
					<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_delete"></use></svg>
					<span>Сбросить</span>
				</button>
				
				
				<div class="bx-filter-popup-result <?if ($arParams["FILTER_VIEW_MODE"] == "VERTICAL") echo $arParams["POPUP_POSITION"]?>" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?> style="display: inline-block;">
					<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.(int)($arResult["ELEMENT_COUNT"] ?? 0).'</span>'));?>
					<span class="arrow"></span>
					<br/>
					<a href="<?echo $arResult["FILTER_URL"]?>" target=""><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
				</div>
			</div>
	
	</form>
	

	<script type="text/javascript">
		var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
	</script>
	
	
	
	
	
	
	<button class="close_btn">
			<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_filter"></use></svg>

			<span>Скрыть фильтр</span>

			<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_close"></use></svg>
		</button>
	</div>
</section>
