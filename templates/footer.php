
<?
/*

	if (!empty($arConstructorEls)) {
		foreach($arConstructorEls as $arConstructorItem) {
			// слайдер
			$arSlider = [];
			
			$oRes = $constructorClass::getList([
				'filter' => ['ID' => $arConstructorItem['ID']],
				'select' => ['SLIDER_' => 'SLIDER']
			]);
			while($arRes = $oRes->fetch()) {
				if ($pic = intval($arRes['SLIDER_IBLOCK_GENERIC_VALUE']))
					$arSlider[] = CFile::GetPath($pic);
			}
			
			if (count($arSlider)) { ?>
				<section class="main_slider block">
					<div class="swiper">
						<div class="swiper-wrapper">
							<? foreach($arSlider as $pic_path) { ?>
								<div class="swiper-slide">
									<div class="image">
										<img src="<?=$pic_path?>" class="img" loading="lazy">
									</div>
								</div>
							<? } ?>
						</div>

						<div class="swiper-pagination"></div>
					</div>
				</section>
		<?	}
		
			// контент + слайдер/изображение
			$arContentSlider = [];
			
			$oRes = $constructorClass::getList([
				'filter' => ['ID' => $arConstructorItem['ID']],
				'select' => ['SLIDER_' => 'CONTENT_SLIDER']
			]);
			while($arRes = $oRes->fetch()) {
				if ($pic = intval($arRes['SLIDER_IBLOCK_GENERIC_VALUE']))
					$arContentSlider[] = CFile::GetPath($pic);
			}
			
			$main_content = $arConstructorItem['DETAIL_TEXT'];
			if ($main_content) {
				if ($arConstructorItem['IS_TABLE_VALUE'])
					$main_content = '<div class="table_wrap">'.$main_content.'</div>';
				else
					$main_content = '<div class="small_w">'.$main_content.'</div>';
			}
			
			if ($arConstructorItem['HEADER_CONTENT_VALUE']) {
				if ($arConstructorItem['HEADER_TYPE_XML_ID'])
					$main_content = '<'.$arConstructorItem['HEADER_TYPE_XML_ID'].'>'.$arConstructorItem['HEADER_CONTENT_VALUE'].'</'.$arConstructorItem['HEADER_TYPE_XML_ID'].'>'.$main_content;
				else 
					$main_content = $arConstructorItem['HEADER_CONTENT_VALUE'].$main_content;
			}
			
			// Частые вопросы
			$oRes = $constructorClass::getList([
				'filter' => ['ID' => $arConstructorItem['ID']],
				'select' => ['FAQ_' => 'FAQ'],
				'order' => ['SORT' => 'ASC']
			]);
			
			$faq_html = '';
			while($arRes = $oRes->fetch()) {
				if ($faq_id = intval($arRes['FAQ_IBLOCK_GENERIC_VALUE'])) {
					if (!$faqClass)
						$faqClass = \Bitrix\Iblock\Iblock::wakeUp(FAQ_ID)->getEntityDataClass();
					
					$oFaqEl = $faqClass::getList([
						'filter' => ['ID' => $faq_id],
						'select' => ['NAME', 'PREVIEW_TEXT'],
						'limit' => 1
					])->fetch();
					
					if ($oFaqEl['NAME'] && $oFaqEl['PREVIEW_TEXT']) $faq_html .= '<div class="accordion_item">
									<div class="head">
										<div class="title">'.$oFaqEl['NAME'].'</div>
										<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>
									</div>
									<div class="data">'.$oFaqEl['PREVIEW_TEXT'].'</div>
								</div>';
				}
			}
			
			if ($faq_html) $main_content .= '<div class="accordion">'.$faq_html.'</div>';
			
			if (($cnt = count($arContentSlider)) || $arConstructorItem['PREVIEW_TEXT']) { ?>
				<? if ($cnt > 1) { ?>
					<section class="info_block block">
						<div class="cont row">
							<? if ($arConstructorItem['PREVIEW_TEXT']) { ?>
								<div class="text_block"><?=$arConstructorItem['PREVIEW_TEXT'];?></div>
							<? } ?>
							<? if (count($arContentSlider)) { ?>
								<div class="slider">
									<div class="swiper">
										<div class="swiper-wrapper">
											<? foreach($arContentSlider as $pic_path) { ?>
												<div class="swiper-slide">
													<div class="image">
														<img src="<?=$pic_path?>" loading="lazy">
													</div>
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
							<? } ?>
						</div>
					</section>
						
				<? } else { ?>
					<section class="block">
						<div class="cont">
							<div class="text_block">
								<div class="image_block">
									<? if ($cnt == 1) { ?>
										<div class="image">
											<img src="<?=$arContentSlider[0];?>" loading="lazy">
										</div>
									<? } ?>
									
									<? if ($arConstructorItem['PREVIEW_TEXT']) { ?>
										<div class="data"><?=$arConstructorItem['PREVIEW_TEXT'];?></div>
									<? } ?>
								</div>
								
								<?=$main_content;?>
								<? $main_content = '';?>
							</div>
						</div>
					</section>
				<? } ?>
		<?	} 
		
			// основной контент (если был блок с картинкой, то он выводится там, а тут нет)
			if ($main_content) { ?>
				<section class="block">
					<div class="cont">
						<div class="text_block">
							<?=$main_content;?>
						</div>
					</div>
				</section>
		<?	}
		
		

			// товары
			$arProdIDs = [];
			
			$oRes = $constructorClass::getList([
				'filter' => ['ID' => $arConstructorItem['ID']],
				'select' => ['PRODS_' => 'PRODS']
			]);
			while($arRes = $oRes->fetch()) {
				if ($prod_id = intval($arRes['PRODS_IBLOCK_GENERIC_VALUE'])) $arProdIDs[] = $prod_id;
			}
			
			if (count($arProdIDs)) {
				global $arFilterMainPage;
				$arFilterMainPage = array("ID" => $arProdIDs);
				$APPLICATION->IncludeComponent(
					"bitrix:catalog.section",
					"slider",
					Array
					(
						"PAGER_TITLE" => $arConstructorItem['PRODS_TITLE_VALUE'],
						"WITH_CATALOG_LINK" => "1",
						"IBLOCK_TYPE" => "catalog",
						"IBLOCK_ID" => "1",
						"ELEMENT_SORT_FIELD" => "id",
						"ELEMENT_SORT_ORDER" => "rand",
						"BASKET_URL" => "/personal/cart/",
						"ACTION_VARIABLE" => "action",
						"PRODUCT_ID_VARIABLE" => "id",
						"SECTION_ID_VARIABLE" => "SECTION_ID",
						"PRODUCT_QUANTITY_VARIABLE" => "quantity",
						"PRODUCT_PROPS_VARIABLE" => "prop",
						"FILTER_NAME" => "arFilterMainPage",
						"CACHE_TYPE" => "A",
						"CACHE_TIME" => "36000000",
						"CACHE_FILTER" => "N",
						"CACHE_GROUPS" => "Y",
						"DISPLAY_COMPARE" => "N",
						"PAGE_ELEMENT_COUNT" => "99999",
						"PRICE_CODE" => Array
							(
								"0" => "BASE"
							),

						"USE_PRICE_COUNT" => "N",
						"SHOW_PRICE_COUNT" => "1",
						"PRICE_VAT_INCLUDE" => "Y",
						"USE_PRODUCT_QUANTITY" => "N",
						"ADD_PROPERTIES_TO_BASKET" => "N",
						"PARTIAL_PRODUCT_PROPERTIES" => "N",

						"PAGER_TEMPLATE" => ".default",
						"PAGER_DESC_NUMBERING" => "N",
						"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
						"PAGER_SHOW_ALL" => "N",
						"PAGER_BASE_LINK_ENABLE" => "N",
						"LAZY_LOAD" => "N",
						"LOAD_ON_SCROLL" => "N",
						"SECTION_URL" => "/catalog/#SECTION_CODE#/",
						"DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
						"USE_MAIN_ELEMENT_SECTION" => "N",
						"CONVERT_CURRENCY" => "N",
						"CURRENCY_ID" => "",
						"HIDE_NOT_AVAILABLE" => "Y",
						"HIDE_NOT_AVAILABLE_OFFERS" => "Y",
						"LABEL_PROP_POSITION" => "top-left",
						"ADD_PICT_PROP" => "-",
						"PRODUCT_DISPLAY_MODE" => "N",
						"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
						"ENLARGE_PRODUCT" => "STRICT",
						"ENLARGE_PROP" => "",
						"SHOW_SLIDER" => "N",
						"SLIDER_INTERVAL" => "3000",
						"SLIDER_PROGRESS" => "N",
						"PRODUCT_SUBSCRIPTION" => "N",
						"SHOW_DISCOUNT_PERCENT" => "N",
						"DISCOUNT_PERCENT_POSITION" => "",
						"SHOW_OLD_PRICE" => "N",
						"SHOW_MAX_QUANTITY" => "N",
						"MESS_SHOW_MAX_QUANTITY" => "Наличие",
						"RELATIVE_QUANTITY_FACTOR" => "",
						"MESS_RELATIVE_QUANTITY_MANY" => "",
						"MESS_RELATIVE_QUANTITY_FEW" => "",
						"MESS_BTN_BUY" => "Купить",
						"MESS_BTN_ADD_TO_BASKET" => "ДОБАВИТЬ В КОРЗИНУ",
						"MESS_BTN_SUBSCRIBE" => "Подписаться",
						"MESS_BTN_DETAIL" => "Подробнее",
						"MESS_NOT_AVAILABLE" => "Нет в наличии",
						"MESS_BTN_COMPARE" => "Сравнение",
						"USE_ENHANCED_ECOMMERCE" => "N",
						"ADD_TO_BASKET_ACTION" => Array
							(
								"0" => "BUY",
							),

						"SHOW_CLOSE_POPUP" => "N",
						"BACKGROUND_IMAGE" => "-",
						"COMPATIBLE_MODE" => "N",
						"DISABLE_INIT_JS_IN_COMPONENT" => "N",
						"SHOW_ALL_WO_SECTION" => "Y"
					),
					false
				);

			}
			
			// баннер
			if ($banner_id = intval($arConstructorItem['BANNER_IBLOCK_GENERIC_VALUE'])) {
				$dataClassBanner = \Bitrix\Iblock\Iblock::wakeUp(BANNERS_ID)->getEntityDataClass();
				$el_banner = $dataClassBanner::getList([
					'select' => ['TITLE_' => 'BANNER_TITLE', 'SUBTITLE_' => 'BANNER_SUBTITLE', 'PREVIEW_PICTURE', 'PREVIEW_TEXT'],
					'filter' => ['ID' => $banner_id]
				])->fetch();
				
				if ($el_banner['TITLE_VALUE'] || $el_banner['SUBTITLE_VALUE'] || $el_banner['PREVIEW_PICTURE'] || $el_banner['PREVIEW_TEXT']) { ?>
				<section class="discount_banner block">
					<div class="cont">
						<? if($el_banner['TITLE_VALUE']) {?><div class="title"><?=$el_banner['TITLE_VALUE'];?></div><?}?>

						<div class="socials">
							<? if($el_banner['SUBTITLE_VALUE']) {?><div><?=$el_banner['SUBTITLE_VALUE'];?></div><?}?>
							<? if($el_banner['PREVIEW_TEXT']) {?><div class="row"><?=$el_banner['PREVIEW_TEXT'];?></div><?}?>
						</div>
					</div>

					<? if($pic = intval($el_banner['PREVIEW_PICTURE'])) {?><img src="<?=CFile::GetPath($pic);?>" loading="lazy" class="bg"><?}?>
				</section>
			<?	}
			}
			
			
			// контакты
			if ($arConstructorItem['IS_CONTACT_VALUE']) { 
				$arPhones = [];
				$el_settings = $dataClassSettings::getList([
					'select' => ['PHONES_' => 'PHONES']
				]);
				while($arRes = $el_settings->fetch()) {
					if ($arRes['PHONES_VALUE']) $arPhones[] = $arRes['PHONES_VALUE'];
				}
				
				$arRes = $dataClassSettings::getList([
					'select' => ['ADDRESS_' => 'ADDRESS', 'EMAIL_' => 'EMAIL', 'WORKTIME_' => 'WORKTIME', 'SOCIAL_' => 'SOCIAL', 'MAP_' => 'MAP'],
					'limit' => 1
				])->fetch(); 
				
				
				if(($cnt = count($arPhones)) || $arRes['ADDRESS_VALUE'] || $arRes['EMAIL_VALUE'] || $arRes['WORKTIME_VALUE'] || $arRes['SOCIAL_VALUE'] || $arRes['MAP_VALUE']) { ?>
				<div class="contacts_info block">
					<div class="cont">
						<div class="data">
							<div class="title">КОНТАКТЫ</div>

							<div class="row">
								<? if ($cnt) { ?>
									<div class="phones">
										<? foreach ($arPhones as $phone) { ?>
											<div><a href="tel:<?=str_replace(['(', ')', '-', ' '], "", $phone);?>"><?=$phone;?></a></div>
										<? } ?>
									</div>
								<? } ?>
								
								<? if ($arRes['ADDRESS_VALUE']) { ?>
									<div class="locations"><?=$arRes['ADDRESS_VALUE'];?></div>
								<? } ?>
								
								<? if ($arRes['EMAIL_VALUE']) { ?>
									<div class="email">
										<a href="mailto:<?=$arRes['EMAIL_VALUE'];?>"><?=$arRes['EMAIL_VALUE'];?></a>
									</div>
								<? } ?>

								<? if ($arRes['WORKTIME_VALUE']) { ?>
									<div class="time"><?=$arRes['WORKTIME_VALUE'];?></div>
								<? } ?>
							</div>
							
							<? if ($arRes['SOCIAL_VALUE']) { ?>
								<div class="socials"><?=unserialize($arRes['SOCIAL_VALUE'])['TEXT'];?></div>
							<? } ?>
						</div>
					</div>
					
					<? if ($arRes['MAP_VALUE']) { ?>
						<div class="map"><?=unserialize($arRes['MAP_VALUE'])['TEXT'];?></div>
					<? } ?>
				</div>	
				
			
		<?		}
			}
			
			// нужна консультация
			if ($arConstructorItem['IS_NEED_CONSULT_VALUE']) {
			?>
			<section class="contacts_block block">
				<div class="cont">
					<div class="block_head center small_m">
						<div class="title">НУЖНА КОНСУЛЬТАЦИЯ</div>
					</div>

					<div class="socials">
					<?	$el_settings = $dataClassSettings::getList([
							'select' => ['PREVIEW_TEXT'],
							'limit' => 1
						])->fetch(); 
						
						if ($el_settings['PREVIEW_TEXT']) echo $el_settings['PREVIEW_TEXT'];  ?>
					</div>
				</div>
			</section>
			<?}
		}
	} */

?>




<? if (substr($curr_page, 0, 10) != '/personal/') { 
	$dataClassSettings = \Bitrix\Iblock\Iblock::wakeUp(SETTINGS_ID)->getEntityDataClass();
	$arBannersRes = $dataClassSettings::getList([
		'select' => ['BANNERS_' => 'BANNERS_PRE_FOOTER'],
		'cache' => [
			'ttl' => 3600,
			'cache_joins' => true,
		]
	])->fetchAll();

	if (isset($arBannersRes[0]['BANNERS_IBLOCK_GENERIC_VALUE']) && $arBannersRes[0]['BANNERS_IBLOCK_GENERIC_VALUE']) {
		$arBannersIDs = [];
		foreach($arBannersRes as $arItem) {
			$arBannersIDs[] = $arItem['BANNERS_IBLOCK_GENERIC_VALUE'];
		}
		global $arFilterBanners;
		$arFilterBanners = array("ID" => $arBannersIDs);
		
		$APPLICATION->IncludeComponent(
			"bitrix:news.list", 
			"banners_pre_footer", 
			array(
				"FILTER_NAME" => "arFilterBanners",
				"COMPONENT_TEMPLATE" => "banners_pre_footer",
				"IBLOCK_TYPE" => "home",
				"IBLOCK_ID" => "6",
				"NEWS_COUNT" => "999",
				"SORT_BY1" => "SORT",
				"SORT_ORDER1" => "ASC",
				"SORT_BY2" => "ID",
				"SORT_ORDER2" => "ASC",
				"FIELD_CODE" => array(
					0 => "PREVIEW_PICTURE",
				),
				"PROPERTY_CODE" => ["URL"],
				"CHECK_DATES" => "Y",
				"DETAIL_URL" => "",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36001",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "Y",
				"PREVIEW_TRUNCATE_LEN" => "",
				"ACTIVE_DATE_FORMAT" => "d.m.Y",
				"SET_TITLE" => "N",
				"SET_BROWSER_TITLE" => "N",
				"SET_META_KEYWORDS" => "N",
				"SET_META_DESCRIPTION" => "N",
				"SET_LAST_MODIFIED" => "N",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"ADD_SECTIONS_CHAIN" => "N",
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",
				"PARENT_SECTION" => "",
				"PARENT_SECTION_CODE" => "",
				"INCLUDE_SUBSECTIONS" => "Y",
				"DISPLAY_DATE" => "N",
				"DISPLAY_NAME" => "N",
				"DISPLAY_PICTURE" => "Y",
				"DISPLAY_PREVIEW_TEXT" => "N",
				"PAGER_TEMPLATE" => ".default",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "N",
				"PAGER_TITLE" => "Новости",
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
				"PAGER_SHOW_ALL" => "N",
				"PAGER_BASE_LINK_ENABLE" => "N",
				"SET_STATUS_404" => "N",
				"SHOW_404" => "N",
				"MESSAGE_404" => "",
				"STRICT_SECTION_CHECK" => "N",
				"PAGER_BASE_LINK" => "",
				"PAGER_PARAMS_NAME" => "arrPager"
			),
			false
		);
	}
} ?>
				
			
			
			
			
			
			
			
			
			
			</div>


			<footer>
				<div class="cont small row">
					<div class="col">
						<div class="logo"><?$APPLICATION->IncludeComponent(
							"bitrix:main.include", "",
							Array(
								"AREA_FILE_SHOW" => "file",
								"PATH" => "/local/include/footer_logo.php"
							));?>
						</div>

						<div class="copyright"><?$APPLICATION->IncludeComponent(
							"bitrix:main.include", "",
							Array(
								"AREA_FILE_SHOW" => "file",
								"PATH" => "/local/include/footer_copyright.php"
							));?></div>
					</div>
					
					<div class="data">
						<div class="links">
							<div class="col">
								<div class="title"><?$APPLICATION->IncludeComponent(
								"bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => "/local/include/footer_title_1.php"
								));?></div>
								
								<?$APPLICATION->IncludeComponent(
									"bitrix:menu",
									"catalog_bottom",
									array(
										"ROOT_MENU_TYPE" => "catalog",
										"MENU_CACHE_TYPE" => "Y",
										"MENU_CACHE_TIME" => "36000000",
										"MENU_CACHE_USE_GROUPS" => "Y",
										"MENU_CACHE_GET_VARS" => array(
										),
										"MAX_LEVEL" => "1",
										"USE_EXT" => "Y",
										"ALLOW_MULTI_SELECT" => "N",
										"DELAY" => "N",
										"COMPONENT_TEMPLATE" => "bottom"
									),
									false
								);?>
							</div>

							<div class="col">
								<div class="title"><?$APPLICATION->IncludeComponent(
								"bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => "/local/include/footer_title_2.php"
								));?></div>
								
								
								
								<div class="items">
									<?
									global $USER;
									if($USER->IsAuthorized()){ ?>
										<div><a href="/personal/">Личный кабинет</a></div>
									<?	} else { ?>
										<div><a href="#" class=" modal_btn" data-modal="login_modal">Личный кабинет</a></div>
										<?
									}?>
									
									
									<?$APPLICATION->IncludeComponent(
										"bitrix:menu",
										"bottom",
										array(
											"ROOT_MENU_TYPE" => "bottom_center",
											"MENU_CACHE_TYPE" => "Y",
											"MENU_CACHE_TIME" => "36000000",
											"MENU_CACHE_USE_GROUPS" => "Y",
											"MENU_CACHE_GET_VARS" => array(
											),
											"MAX_LEVEL" => "1",
											"USE_EXT" => "Y",
											"ALLOW_MULTI_SELECT" => "N",
											"DELAY" => "N",
											"COMPONENT_TEMPLATE" => "bottom"
										),
										false
									);?>
								</div>
							</div>

							<div class="col">
								<div class="title"><?$APPLICATION->IncludeComponent(
								"bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => "/local/include/footer_title_3.php"
								));?></div>
								
								<div class="items">
									<?$APPLICATION->IncludeComponent(
									"bitrix:menu",
									"bottom",
									array(
										"ROOT_MENU_TYPE" => "bottom_right",
										"MENU_CACHE_TYPE" => "Y",
										"MENU_CACHE_TIME" => "36000000",
										"MENU_CACHE_USE_GROUPS" => "Y",
										"MENU_CACHE_GET_VARS" => array(
										),
										"MAX_LEVEL" => "1",
										"USE_EXT" => "Y",
										"ALLOW_MULTI_SELECT" => "N",
										"DELAY" => "N",
										"COMPONENT_TEMPLATE" => "bottom"
									),
									false
								);?>
								</div>
							</div>
						</div>

						<div class="privacy_policy_link">
							<?$APPLICATION->IncludeComponent(
							"bitrix:main.include", "",
							Array(
								"AREA_FILE_SHOW" => "file",
								"PATH" => "/local/include/footer_privacy_policy.php"
							));?>
						</div>
					</div>
				
				
					<div class="sep"></div>

					<div class="contacts">
						<div class="phone">
							<?$APPLICATION->IncludeComponent(
							"bitrix:main.include", "",
							Array(
								"AREA_FILE_SHOW" => "file",
								"PATH" => "/local/include/phone.php"
							));?>

							<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_whatsapp"></use></svg>
						</div>

						<div class="socials">
							<div class="label">Мы в социальных сетях</div>

							<div class="items">
								<?$APPLICATION->IncludeComponent(
								"bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => "/local/include/footer_socials.php"
								));?>
							</div>
						</div>

						<div class="creator">
							<a href="https://tochka-ru.ru/" target="_blank" rel="noopener">Создание сайта <span>Точка.ру</span></a>
						</div>
					</div>
				</div>
			</footer>


			<div class="overlay"></div>
		</div>
		
		<section class="modal" id="callback_modal">
			<div class="modal_title">Обратный звонок</div>

			<form action="" class="form">
				<div class="line">
					<div class="field">
						<input type="text" name="" value="" class="input" placeholder="Имя">
					</div>
				</div>

				<div class="line">
					<div class="field">
						<input type="tel" name="" value="" class="input" placeholder="Телефон">
					</div>
				</div>

				<div class="line agree">
					<label class="checkbox">
						<input type="checkbox" name="agree">

						<div class="check">
							<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_check"></use></svg>
						</div>

						<div><span><?$APPLICATION->IncludeComponent(
								"bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => "/local/include/forms_privacy_policy.php"
								));?></span></div>
					</label>
				</div>

				<div class="submit">
					<button type="submit" class="submit_btn">ОТПРАВИТЬ</button>
				</div>
			</form>
		</section>
		
		
		<section class="modal" id="login_modal">
			<div class="modal_title">Авторизация</div>
			
			<form action="" class="form ajax_submit userAuth">
				<div class="error_text"></div><div class="my_ok_text"></div>
				<input type="hidden" name="mode" value="auth">
				
				<div class="line">
					<div class="field">
						<input type="email" title="E-mail" name="EMAIL" value="" class="input" placeholder="E-mail" required>
					</div>
				</div>

				<div class="line">
					<div class="field">
						<input type="password" title="Пароль" name="PASSWORD" value="" class="input" placeholder="Пароль" required>
					</div>
				</div>

				<div class="line agree">
					<label class="checkbox">
						<input type="checkbox" name="agree">

						<div class="check">
							<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_check"></use></svg>
						</div>

						<div><span><?$APPLICATION->IncludeComponent(
								"bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => "/local/include/forms_privacy_policy.php"
								));?></span></div>
					</label>
				</div>

				<div class="submit">
					<button type="submit" class="submit_btn">ВОЙТИ</button>

					<button type="button" class="btn modal_btn" data-modal="register_modal">ЗАРЕГИСТРИРОВАТЬСЯ</button>
				</div>
			</form>

			<div class="recovery">
				<button class="btn modal_btn" data-modal="recovery_modal">Напомнить пароль</button>
			</div>
		</section>

		
		<section class="modal" id="recovery_modal">
			<div class="modal_title">Восстановление пароля</div>

			<form action="" class="form ajax_submit custom_submit" id="forgot_form">
				<div class="error_text"></div>
				<input type="hidden" name="mode" value="lost">
				<div class="line">
					<div class="field">
						<input type="email" title="E-mail" name="EMAIL" value="" class="input" placeholder="E-mail" required>
						<!-- <input type="email" name="" value="" class="input error" placeholder="E-mail"> -->

						<!-- <div class="error_text">Поле E-mail необходимо заполнить</div> -->
					</div>
				</div>

				<div class="line agree">
					<label class="checkbox">
						<input type="checkbox" name="agree">

						<div class="check">
							<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_check"></use></svg>
						</div>

						<div><span><?$APPLICATION->IncludeComponent(
								"bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => "/local/include/forms_privacy_policy.php"
								));?></span></div>
					</label>
				</div>

				<div class="submit">
					<button type="submit" class="submit_btn">ВОССТАНОВИТЬ</button>
				</div>
			</form>
		</section>
		
		<section class="modal" id="register_modal">
			<div class="modal_title">Регистрация</div>

			<form action="" class="form">
				<input type="hidden" name="mode" value="reg">
				

				<div class="line">
					<div class="field">
						<input type="text" name="user[LAST_NAME]" title="Фамилия" value="" class="input" placeholder="Фамилия *" required>
					</div>
				</div>
				
				<div class="line">
					<div class="field">
						<input type="text" name="user[NAME]" title="Имя" value="" class="input" placeholder="Имя *" required>
					</div>
				</div>

				<div class="line">
					<div class="field">
						<input type="tel" name="user[PERSONAL_PHONE]" title="Телефон" value="" class="input" placeholder="Телефон *" required>
					</div>
				</div>

				<div class="line">
					<div class="field">
						<input type="email" name="user[EMAIL]" title="E-mail" value="" class="input" placeholder="E-mail *" required>
					</div>
				</div>

				<div class="line">
					<div class="field">
						<input type="password" name="user[PASSWORD]" title="Пароль" value="" class="input" placeholder="Пароль *" required>
					</div>
				</div>

				<div class="line">
					<div class="field">
						<input type="password" name="user[CONFIRM_PASSWORD]" title="Подтверждение пароля" value="" class="input" placeholder="Повторите пароль *" required>
					</div>
				</div>

				<div class="line agree">
					<label class="checkbox">
						<input type="checkbox" name="agree">

						<div class="check">
							<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_check"></use></svg>
						</div>

						<div><span><?$APPLICATION->IncludeComponent(
								"bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => "/local/include/forms_privacy_policy.php"
								));?></span></div>
					</label>
				</div>

				<div class="submit">
					<button type="submit" class="submit_btn">ЗАРЕГИСТРИРОВАТЬЯ</button>
				</div>
			</form>
		</section>

		
		<!-- Connecting javascript files -->
		<script src="/local/js/functions.js"></script>
		<script src="/local/js/swiper-bundle.min.js"></script>
		<script src="/local/js/fancybox.js"></script>
		<script src="/local/js/nice-select2.js"></script>
		<!-- 	<script src="/local/js/ion.rangeSlider.min.js"></script>  -->
		<script src="/local/js/imask.js"></script>
		<script src="https://www.youtube.com/iframe_api"></script>
		<script src="/local/js/scripts.js"></script>
		<script src="/local/js/custom.js"></script>
	</body>
</html>


















<?/*





<section class="modal" id="login_modal">
    <div class="modal_title">Вход</div>

    <div class="modal_data">
        <form action="" class="form custom_submit ajax_submit" id="userAuth">
            <div class="error_text_form"></div>
            <input type="hidden" name="mode" value="auth">
            <div class="line">
                <div class="field">
                    <input type="email" title="E-mail" name="NAME" class="input" value="" placeholder="E-mail" required>
                </div>
            </div>

            <div class="line">
                <div class="field">
                    <input type="password" title="Пароль"  name="PASSWORD" value="" class="input" placeholder="Пароль" required>
                </div>
            </div>

            <a href="#" class="modal_link" data-content="#forget">Забыли пароль?</a>

            <div class="submit">
                <button type="submit" class="submit_btn">Войти</button>
            </div>

            <p class="or-text-center">
                <span>или</span>
                <a href="#" class="modal_link" data-content="#register">зарегистрироваться</a>
            </p>
        </form>
    </div>
</section>



<section class="modal" id="register">
    <div class="modal_title">Регистрация</div>

    <form action="" class="form custom_submit ajax_submit">
        <div class="error_text_form"></div><div class="my_ok_text"></div>
        <input type="hidden" name="mode" value="reg">

        <div class="line">
            <div class="field">
                <input type="text" name="user[NAME]" placeholder="Ваше имя *" title="Имя" class="input" value="" required/>
            </div>
        </div>

        <div class="line">
            <div class="field">
                <input type="email" name="user[EMAIL]" placeholder="E-mail *" title="E-mail" class="input" required/>
            </div>
        </div>

        <div class="line">
            <div class="field">
                <input type="password" name="user[PASSWORD]" placeholder="Пароль *" title="Пароль" class="input" required/>
            </div>
        </div>

        <div class="line">
            <div class="field">
                <input type="password" type="password" placeholder="Подтверждение пароля *"  name="user[CONFIRM_PASSWORD]" title="Подтверждение пароля" class="input" required/>
            </div>
        </div>

        <div class="submit">
            <button type="submit" class="submit_btn" onclick="checkRegFields(this); return false;">Зарегистрироваться</button>
        </div>
    </form>
</section>

<section class="modal" id="forget">
    <div class="modal_title">Восстановление пароля</div>

    <form action="" class="form custom_submit ajax_submit"  id="forgot_form">
        <div class="error_text_form"></div><div class="my_ok_text"></div>
        <input type="hidden" name="mode" value="lost">

        <div class="line">
            <div class="field">
                <input type="email" title="E-mail" placeholder="E-mail *" name="EMAIL" class="input" required/>
            </div>
        </div>

        <div class="submit">
            <button type="submit" class="submit_btn">Отправить</button>
        </div>
    </form>
</section>





<section class="modal" id="callback_modal">
    <?$APPLICATION->IncludeComponent(
        "custom:form.result.new",
        "",
        Array(
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "CHAIN_ITEM_LINK" => "",
            "CHAIN_ITEM_TEXT" => "",
            "EDIT_URL" => "result_edit.php",
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "LIST_URL" => "/result_list.php",
            "SEF_MODE" => "N",
            "SUCCESS_URL" => "",
            "USE_EXTENDED_ERRORS" => "N",
            "VARIABLE_ALIASES" => Array("RESULT_ID"=>"RESULT_ID","WEB_FORM_ID"=>"WEB_FORM_ID"),
            "WEB_FORM_ID" => "1"
        )
    );?>
</section>

<section class="modal" id="order_modal">
    <?$APPLICATION->IncludeComponent(
        "custom:form.result.new",
        "",
        Array(
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "CHAIN_ITEM_LINK" => "",
            "CHAIN_ITEM_TEXT" => "",
            "EDIT_URL" => "result_edit.php",
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "LIST_URL" => "/result_list.php",
            "SEF_MODE" => "N",
            "SUCCESS_URL" => "",
            "USE_EXTENDED_ERRORS" => "N",
            "VARIABLE_ALIASES" => Array("RESULT_ID"=>"RESULT_ID","WEB_FORM_ID"=>"WEB_FORM_ID"),
            "WEB_FORM_ID" => "3"
        )
    );?>
</section>

<section class="modal" id="request_modal">
    <?$APPLICATION->IncludeComponent(
        "custom:form.result.new",
        "",
        Array(
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "CHAIN_ITEM_LINK" => "",
            "CHAIN_ITEM_TEXT" => "",
            "EDIT_URL" => "result_edit.php",
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "LIST_URL" => "/result_list.php",
            "SEF_MODE" => "N",
            "SUCCESS_URL" => "",
            "USE_EXTENDED_ERRORS" => "N",
            "VARIABLE_ALIASES" => Array("RESULT_ID"=>"RESULT_ID","WEB_FORM_ID"=>"WEB_FORM_ID"),
            "WEB_FORM_ID" => "4"
        )
    );?>
</section>

<section class="modal" id="success_modal">
    <div class="modal_title"><?$APPLICATION->IncludeComponent(
            "bitrix:main.include", "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/local/include/success_modal_header.php"
            ));?></div>
    <div class="text_block"><?$APPLICATION->IncludeComponent(
            "bitrix:main.include", "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/local/include/success_modal.php"
            ));?></div>
</section>

*/?>