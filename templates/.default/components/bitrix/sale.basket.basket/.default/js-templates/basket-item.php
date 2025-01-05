<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $mobileColumns
 * @var array $arParams
 * @var string $templateFolder
 */

$usePriceInAdditionalColumn = in_array('PRICE', $arParams['COLUMNS_LIST']) && $arParams['PRICE_DISPLAY_MODE'] === 'Y';
$useSumColumn = in_array('SUM', $arParams['COLUMNS_LIST']);
$useActionColumn = in_array('DELETE', $arParams['COLUMNS_LIST']);

$restoreColSpan = 2 + $usePriceInAdditionalColumn + $useSumColumn + $useActionColumn;

$positionClassMap = array(
	'left' => 'basket-item-label-left',
	'center' => 'basket-item-label-center',
	'right' => 'basket-item-label-right',
	'bottom' => 'basket-item-label-bottom',
	'middle' => 'basket-item-label-middle',
	'top' => 'basket-item-label-top'
);

$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
	{
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
	{
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}
?>
<script id="basket-item-template" type="text/html">
	<div class="basket-items-list-item-container{{#SHOW_RESTORE}} basket-items-list-item-container-expend{{/SHOW_RESTORE}} product"
		id="basket-item-{{ID}}" data-entity="basket-item" data-id="{{ID}}">
		
					
		{{#SHOW_RESTORE}}
			<div class="basket-items-list-item-notification" colspan="<?=$restoreColSpan?>">
				<div class="basket-items-list-item-notification-inner basket-items-list-item-notification-removed" id="basket-item-height-aligner-{{ID}}">
					{{#SHOW_LOADING}}
						<div class="basket-items-list-item-overlay"></div>
					{{/SHOW_LOADING}}
					<div class="basket-items-list-item-removed-container">
						<div>
							<?=Loc::getMessage('SBB_GOOD_CAP')?> <strong>{{NAME}}</strong> <?=Loc::getMessage('SBB_BASKET_ITEM_DELETED')?>.
						</div>
						<div class="basket-items-list-item-removed-block">
							<a href="javascript:void(0)" data-entity="basket-item-restore-button">
								<?=Loc::getMessage('SBB_BASKET_ITEM_RESTORE')?>
							</a>
							<span class="basket-items-list-item-clear-btn" data-entity="basket-item-close-restore-button"></span>
						</div>
					</div>
				</div>
			</div>
		{{/SHOW_RESTORE}}
		{{^SHOW_RESTORE}}
		
			<?
			if (in_array('PREVIEW_PICTURE', $arParams['COLUMNS_LIST']))
			{
				?>

					{{#DETAIL_PAGE_URL}}
						<a href="{{DETAIL_PAGE_URL}}" class="basket-item-image-link thumb" target="_blank">
					{{/DETAIL_PAGE_URL}}

					<img class="basket-item-image" alt="{{NAME}}"
						src="{{{IMAGE_URL}}}{{^IMAGE_URL}}<?=$templateFolder?>/images/no_photo.png{{/IMAGE_URL}}" loading="lazy">

					{{#SHOW_LABEL}}
						<div class="basket-item-label-text basket-item-label-big <?=$labelPositionClass?>">
							{{#LABEL_VALUES}}
								<div{{#HIDE_MOBILE}} class="hidden-xs"{{/HIDE_MOBILE}}>
									<span title="{{NAME}}">{{NAME}}</span>
								</div>
							{{/LABEL_VALUES}}
						</div>
					{{/SHOW_LABEL}}

					<?
					if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y')
					{
						?>
						{{#DISCOUNT_PRICE_PERCENT}}
							<div class="basket-item-label-ring basket-item-label-small <?=$discountPositionClass?>">
								-{{DISCOUNT_PRICE_PERCENT_FORMATED}}
							</div>
						{{/DISCOUNT_PRICE_PERCENT}}
						<?
					}
					?>

					{{#DETAIL_PAGE_URL}}
						</a>
					{{/DETAIL_PAGE_URL}}
			
				<?
			}
			?>
			<div>
				<div class="name">
					{{#DETAIL_PAGE_URL}}
						<a href="{{DETAIL_PAGE_URL}}" target="_blank" class="basket-item-info-name-link--CUSTOM">
					{{/DETAIL_PAGE_URL}}

					{{NAME}}

					{{#DETAIL_PAGE_URL}}
						</a>
					{{/DETAIL_PAGE_URL}}
				</div>

				СДЕ
				<button class="favorite_btn">
					<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_favorite"></use></svg>

					<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_favorite_a"></use></svg>
				</button>ЛАТЬ
										
										
				


				
										
										
										
										
		
				<div class="basket-items-list-item-descriptions-inner--CUSTOM    features" id="basket-item-height-aligner-{{ID}}">
					{{#NOT_AVAILABLE}}
						<div class="basket-items-list-item-warning-container">
							<div class="alert alert-warning text-center">
								<?=Loc::getMessage('SBB_BASKET_ITEM_NOT_AVAILABLE')?>.
							</div>
						</div>
					{{/NOT_AVAILABLE}}
					{{#DELAYED}}
						<div class="basket-items-list-item-warning-container">
							<div class="alert alert-warning text-center">
								<?=Loc::getMessage('SBB_BASKET_ITEM_DELAYED')?>.
								<a href="javascript:void(0)" data-entity="basket-item-remove-delayed">
									<?=Loc::getMessage('SBB_BASKET_ITEM_REMOVE_DELAYED')?>
								</a>
							</div>
						</div>
					{{/DELAYED}}
					{{#WARNINGS.length}}
						<div class="basket-items-list-item-warning-container">
							<div class="alert alert-warning alert-dismissable" data-entity="basket-item-warning-node">
								<span class="close" data-entity="basket-item-warning-close">&times;</span>
									{{#WARNINGS}}
										<div data-entity="basket-item-warning-text">{{{.}}}</div>
									{{/WARNINGS}}
							</div>
						</div>
					{{/WARNINGS.length}}
					
					
					{{ #BRAND_NAME }}
					<div class="brand">
						
						<a href="{{ BRAND_LINK }}" target="_blank">{{ BRAND_NAME }}</a>
					</div>
					{{ /BRAND_NAME }}

					
					
					
					
					
					
					
					
					<?
					if (!empty($arParams['PRODUCT_BLOCKS_ORDER']))
					{
						foreach ($arParams['PRODUCT_BLOCKS_ORDER'] as $blockName)
						{
							switch (trim((string)$blockName))
							{
								case 'props':
									if (0 && in_array('PROPS', $arParams['COLUMNS_LIST']))
									{
										?>
										{{#PROPS}}
											<div class="basket-item-property">
												<div class="label">
													{{{NAME}}}
												</div>
												<div class="val"
													data-entity="basket-item-property-value" data-property-code="{{CODE}}">
													{{{VALUE}}}
												</div>
											</div>
										{{/PROPS}}
										<?
									}

									break;
								case 'sku':
									?>
									{{#SKU_BLOCK_LIST}}
										{{#IS_IMAGE}}
											<div class="basket-item-property basket-item-property-scu-image"
												data-entity="basket-item-sku-block">
												<div class="basket-item-property-name">{{NAME}}</div>
												<div class="basket-item-property-value">
													<ul class="basket-item-scu-list">
														{{#SKU_VALUES_LIST}}
															<li class="basket-item-scu-item{{#SELECTED}} selected{{/SELECTED}}
																{{#NOT_AVAILABLE_OFFER}} not-available{{/NOT_AVAILABLE_OFFER}}"
																title="{{NAME}}"
																data-entity="basket-item-sku-field"
																data-initial="{{#SELECTED}}true{{/SELECTED}}{{^SELECTED}}false{{/SELECTED}}"
																data-value-id="{{VALUE_ID}}"
																data-sku-name="{{NAME}}"
																data-property="{{PROP_CODE}}">
																		<span class="basket-item-scu-item-inner"
																			style="background-image: url({{PICT}});"></span>
															</li>
														{{/SKU_VALUES_LIST}}
													</ul>
												</div>
											</div>
										{{/IS_IMAGE}}

										{{^IS_IMAGE}}
										<?/*
											<div class="basket-item-property basket-item-property-scu-text"
												data-entity="basket-item-sku-block">
												<div class="basket-item-property-name">{{NAME}}</div>
												<div class="basket-item-property-value">
													<ul class="basket-item-scu-list">
														{{#SKU_VALUES_LIST}}
															<li class="basket-item-scu-item{{#SELECTED}} selected{{/SELECTED}}
																{{#NOT_AVAILABLE_OFFER}} not-available{{/NOT_AVAILABLE_OFFER}}"
																title="{{NAME}}"
																data-entity="basket-item-sku-field"
																data-initial="{{#SELECTED}}true{{/SELECTED}}{{^SELECTED}}false{{/SELECTED}}"
																data-value-id="{{VALUE_ID}}"
																data-sku-name="{{NAME}}"
																data-property="{{PROP_CODE}}">
																<span class="basket-item-scu-item-inner">{{NAME}}</span>
															</li>
														{{/SKU_VALUES_LIST}}
													</ul>
												</div>
											</div> */?>
										{{/IS_IMAGE}}
									{{/SKU_BLOCK_LIST}}

									{{#HAS_SIMILAR_ITEMS}}
										<div class="basket-items-list-item-double" data-entity="basket-item-sku-notification">
											<div class="alert alert-info alert-dismissable text-center">
												{{#USE_FILTER}}
													<a href="javascript:void(0)"
														class="basket-items-list-item-double-anchor"
														data-entity="basket-item-show-similar-link">
												{{/USE_FILTER}}
												<?=Loc::getMessage('SBB_BASKET_ITEM_SIMILAR_P1')?>{{#USE_FILTER}}</a>{{/USE_FILTER}}
												<?=Loc::getMessage('SBB_BASKET_ITEM_SIMILAR_P2')?>
												{{SIMILAR_ITEMS_QUANTITY}} {{MEASURE_TEXT}}
												<br>
												<a href="javascript:void(0)" class="basket-items-list-item-double-anchor"
													data-entity="basket-item-merge-sku-link">
													<?=Loc::getMessage('SBB_BASKET_ITEM_SIMILAR_P3')?>
													{{TOTAL_SIMILAR_ITEMS_QUANTITY}} {{MEASURE_TEXT}}?
												</a>
											</div>
										</div>
									{{/HAS_SIMILAR_ITEMS}}
									<?
									break;
								case 'columns':
									?>
									{{#COLUMN_LIST}}
										{{#IS_IMAGE}}
											<div class="basket-item-property-custom basket-item-property-custom-photo
												{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}}"
												data-entity="basket-item-property">
												<div class="basket-item-property-custom-name">{{NAME}}</div>
												<div class="basket-item-property-custom-value">
													{{#VALUE}}
														<span>
															<img class="basket-item-custom-block-photo-item"
																src="{{{IMAGE_SRC}}}" data-image-index="{{INDEX}}"
																data-column-property-code="{{CODE}}">
														</span>
													{{/VALUE}}
												</div>
											</div>
										{{/IS_IMAGE}}

										{{#IS_TEXT}}
											<div>
												<div class="label">{{NAME}}</div>
												<div class="val">{{VALUE}}</div>
											</div>
											
										<?/*	<div class="basket-item-property-custom basket-item-property-custom-text
												{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}}"
												data-entity="basket-item-property">
												<div class="basket-item-property-custom-name">{{NAME}}</div>
												<div class="basket-item-property-custom-value"
													data-column-property-code="{{CODE}}"
													data-entity="basket-item-property-column-value">
													{{VALUE}}
												</div>
											</div> */?>
										{{/IS_TEXT}}

										{{#IS_HTML}}
											<div class="basket-item-property-custom basket-item-property-custom-text
												{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}}"
												data-entity="basket-item-property">
												<div class="basket-item-property-custom-name">{{NAME}}</div>
												<div class="basket-item-property-custom-value"
													data-column-property-code="{{CODE}}"
													data-entity="basket-item-property-column-value">
													{{{VALUE}}}
												</div>
											</div>
										{{/IS_HTML}}

										{{#IS_LINK}}
											<div class="basket-item-property-custom basket-item-property-custom-text
												{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}}"
												data-entity="basket-item-property">
												<div class="basket-item-property-custom-name">{{NAME}}</div>
												<div class="basket-item-property-custom-value"
													data-column-property-code="{{CODE}}"
													data-entity="basket-item-property-column-value">
													{{#VALUE}}
													{{{LINK}}}{{^IS_LAST}}<br>{{/IS_LAST}}
													{{/VALUE}}
												</div>
											</div>
										{{/IS_LINK}}
									{{/COLUMN_LIST}}
									<?
									break;
							}
						}
					}
					?>
					</div>	

					
					
					
					
					
				<?/*	
				<div class="features">
					<div>
						<div class="label">Артикул</div>
						<div class="val">146337416</div>
					</div>

					<div>
						<div class="label">Размер</div>
						<div class="val">40</div>
					</div>

					<div>
						<div class="label">Цвет</div>
						<div class="val">Розовый</div>
					</div>
				</div> */?>						
										
										
										
				<div class="buy">
				<?/*	<div class="price">
						<div class="label">Цена</div>
						<div class="val">2 325 ₽</div>
					</div>

					<div class="amount">
						<button type="button" class="btn minus">
							<svg class="icon"><use xlink:href="images/sprite.svg#ic_minus"></use></svg>
						</button>

						<input type="text" value="1" class="input" data-minimum="1" data-maximum="99" data-step="1" data-unit="" maxlength="2">

						<button type="button" class="btn plus">
							<svg class="icon"><use xlink:href="images/sprite.svg#ic_plus"></use></svg>
						</button>
					</div>

					<div class="price">
						<div class="label">Сумма</div>
						<div class="val">2 325 ₽</div>
					</div>
*/?>
									








					
					
						<?
						if ($usePriceInAdditionalColumn)
						{
							?>
							<div class="basket-items-list-item-price--CUSTOM   price">
								<div class="label">Цена</div>
								
								<div class="basket-item-price-current-text--CUSTOM val" id="basket-item-price-{{ID}}">
									{{{PRICE_FORMATED}}}
									
									{{#SHOW_DISCOUNT_PRICE}}
									<spam class="basket-item-price-old-text-- old">
										{{{FULL_PRICE_FORMATED}}}
									</spam>
									{{/SHOW_DISCOUNT_PRICE}}
								</div>
	
								{{#SHOW_LOADING}}
									<div class="basket-items-list-item-overlay"></div>
								{{/SHOW_LOADING}}
							</div>
							<?
						}
						?>
			
		
					
							<div class="basket-item-block-amount--CUSTOM{{#NOT_AVAILABLE}} disabled{{/NOT_AVAILABLE}}  amount"
								data-entity="basket-item-quantity-block">
						
								<button type="button" class="basket-item-amount-btn-minus--CUSTOM btn" data-entity="basket-item-quantity-minus">
									<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_minus"></use></svg>
								</button>
						
						
								<div class="basket-item-amount-filed-block">
									<input type="text" class="basket-item-amount-filed--CUSTOM  input" value="{{QUANTITY}}"
										{{#NOT_AVAILABLE}} disabled="disabled"{{/NOT_AVAILABLE}}
										data-value="{{QUANTITY}}" data-entity="basket-item-quantity-field"
										id="basket-item-quantity-{{ID}}">
								</div>
	
								
								<button type="button" class="basket-item-amount-btn-plus--CUSTOM btn" data-entity="basket-item-quantity-plus">
									<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_plus"></use></svg>
								</button>
								
								
								
								<div class="basket-item-amount-field-description">
									<?
									if ($arParams['PRICE_DISPLAY_MODE'] === 'Y')
									{
										/*?>
										{{MEASURE_TEXT}}
										<?*/
									}
									else
									{
										?>
										{{#SHOW_PRICE_FOR}}
											{{MEASURE_RATIO}} {{MEASURE_TEXT}} =
											<span id="basket-item-price-{{ID}}">{{{PRICE_FORMATED}}}</span>
										{{/SHOW_PRICE_FOR}}
										{{^SHOW_PRICE_FOR}}
											{{MEASURE_TEXT}}
										{{/SHOW_PRICE_FOR}}
										<?
									}
									?>
								</div>
								{{#SHOW_LOADING}}
									<div class="basket-items-list-item-overlay"></div>
								{{/SHOW_LOADING}}
							</div>
			
						<?
						if ($useSumColumn)
						{
							?>	

								<div class="basket-item-block-price--CUSTOM  price total">
									<div class="label">Сумма</div>
								<?/*	{{#SHOW_DISCOUNT_PRICE}}
										<div class="basket-item-price-old">
											<span class="basket-item-price-old-text" id="basket-item-sum-price-old-{{ID}}">
												{{{SUM_FULL_PRICE_FORMATED}}}
											</span>
										</div>
									{{/SHOW_DISCOUNT_PRICE}} */?>

									<div class="basket-item-price-current-text--CUSTOM val" id="basket-item-sum-price-{{ID}}">
										{{{SUM_PRICE_FORMATED}}}
									</div>
								<?/*
									{{#SHOW_DISCOUNT_PRICE}}
										<div class="basket-item-price-difference">
											<?=Loc::getMessage('SBB_BASKET_ITEM_ECONOMY')?>
											<span id="basket-item-sum-price-difference-{{ID}}" style="white-space: nowrap;">
												{{{SUM_DISCOUNT_PRICE_FORMATED}}}
											</span>
										</div>
									{{/SHOW_DISCOUNT_PRICE}} */?>
									{{#SHOW_LOADING}}
										<div class="basket-items-list-item-overlay"></div>
									{{/SHOW_LOADING}}
								</div>
					
							<?
						}

						?>
			
				
			
			
						
					
					{{#SHOW_LOADING}}
						<div class="basket-items-list-item-overlay"></div>
					{{/SHOW_LOADING}}
				
						<?	
					if ($useActionColumn)
					{
						?>
						<button class="delete_btn" data-entity="basket-item-delete">
							<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_delete"></use></svg>
							<div class="hover">Удалить товар из корзины</div>
							{{#SHOW_LOADING}}
								<div class="basket-items-list-item-overlay"></div>
							{{/SHOW_LOADING}}
						</button>
						<?
					} ?>
							
				</div>	
				
		</div>		
				
		{{/SHOW_RESTORE}}
	</div>
</script>