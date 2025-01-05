<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$curr_page = $APPLICATION->GetCurPage();

if (isset($_REQUEST["BasketClear"]) && CModule::IncludeModule("sale")) {
   CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
}
if ($_GET['logout'] == 'yes') {
	global $USER; 
	$USER->Logout();
	
	$url_tmp = explode('?', $_SERVER['REQUEST_URI']);
	$url_tmp = $url_tmp[0];
	if ($url_tmp == '/personal/')
		$url = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/';
	else 
		$url = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].$url_tmp;
	echo $url;
	header('Location: '.$url);
} else if ($_GET['clear']) {
	if ($curr_page == '/personal/cart/') {
		CModule::IncludeModule("sale");
		\CSaleBasket::DeleteAll(\Bitrix\Sale\Fuser::getId());
	}
}
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<!-- Adapting the page for mobile devices -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		<!-- Prohibition of phone number recognition -->
		<meta name="format-detection" content="telephone=no">
		<meta name="SKYPE_TOOLBAR" content ="SKYPE_TOOLBAR_PARSER_COMPATIBLE">

		<title><?$APPLICATION->ShowTitle()?></title>
		<?$APPLICATION->ShowHead();?>

		<!-- Site icon, size 32x32, transparency supported. Recommended format: .ico or .png -->
		<link rel="shortcut icon" href="/favicon.png">

		<!-- Changing the color of the mobile browser panel -->
		<meta name="msapplication-TileColor" content="#000000">
		<meta name="theme-color" content="#000000">

		<!-- Connecting fonts from Google -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

		<!-- Connecting style files -->
		<link rel="stylesheet" href="/local/css/swiper-bundle.min.css" async>
		<link rel="stylesheet" href="/local/css/swiper.css" async>
		<link rel="stylesheet" href="/local/css/fancybox.css" async>
		<link rel="stylesheet" href="/local/css/ion.rangeSlider.css" async>
		<link rel="stylesheet" href="/local/css/styles.css" async>
		<?$APPLICATION->AddHeadString('<link href="/local/css/custom.css"  type="text/css" rel="stylesheet" />',true)?>

		<link rel="stylesheet" href="/local/css/response_1899.css" media="print, (max-width: 1899px)" async>
		<link rel="stylesheet" href="/local/css/response_1439.css" media="print, (max-width: 1439px)" async>
		<link rel="stylesheet" href="/local/css/response_1279.css" media="print, (max-width: 1279px)" async>
		<link rel="stylesheet" href="/local/css/response_1023.css" media="print, (max-width: 1023px)" async>
		<link rel="stylesheet" href="/local/css/response_767.css" media="print, (max-width: 767px)" async>
		<link rel="stylesheet" href="/local/css/response_479.css" media="(max-width: 479px)" async>
		<script src="/local/js/jquery-3.6.3.min.js"></script>
	</head>


	<body>
		<?$APPLICATION->ShowPanel()?>

		<div class="wrap">
			<div class="main">	
				<? 
				\Bitrix\Main\Loader::includeModule('iblock');
				$dataClassSettings = \Bitrix\Iblock\Iblock::wakeUp(SETTINGS_ID)->getEntityDataClass();
				$el_settings = $dataClassSettings::getList([
					'select' => ['DETAIL_TEXT'],
					'limit' => 1
				])->fetch(); 
				
				if ($el_settings['DETAIL_TEXT']) {  ?>
					<section id="top_banner_main" class="top_banner" cache_length="<?=mb_strlen($el_settings['DETAIL_TEXT']);?>">
						<div class="cont small">
							<?=$el_settings['DETAIL_TEXT'];?>
						</div>
						<script>
							if (localStorage.getItem("top_banner_main") == $('#top_banner_main').attr('cache_length')) $('#top_banner_main').hide();
						</script>
					</section>
				<? } ?>

				<header <? if ($curr_page == '/') { ?>class="absolute"<? } ?>>
					<div class="cont small row">
						<? if ($curr_page == '/') { ?>
							<div class="logo">
								<?$APPLICATION->IncludeComponent(
								"bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => "/local/include/header_logo.php"
								));?>
							</div>
						<? } else { ?>
							<a href="/" class="logo">
								<?$APPLICATION->IncludeComponent(
								"bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => "/local/include/header_logo.php"
								));?>
							</a>
						<? } ?>
	
						<nav class="menu row">							
							<div class="menu_item">
								<a href="/catalog/" class="sub_link<? if (substr($curr_page, 0, 9) == '/catalog/') { ?> active<? } ?>">КАТАЛОГ</a>

								<?$APPLICATION->IncludeComponent(
									"bitrix:menu", 
									"catalog_top", 
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
										"CHILD_MENU_TYPE" => "catalog",
										"DELAY" => "N",
										"COMPONENT_TEMPLATE" => "catalog"
									),
									false
								);?>
							</div>
							
							<?$APPLICATION->IncludeComponent(
								"bitrix:menu", 
								"top", 
								array(
									"ROOT_MENU_TYPE" => "top",
									"MENU_CACHE_TYPE" => "Y",
									"MENU_CACHE_TIME" => "36000000",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(
									),
									"MAX_LEVEL" => "1",
									"USE_EXT" => "Y",
									"ALLOW_MULTI_SELECT" => "N",
									"DELAY" => "N",
									"COMPONENT_TEMPLATE" => "top"
								),
								false
							);?>
						</nav>

						<div class="data">
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

								<button class="callback_btn modal_btn" data-modal="callback_modal">Обратный звонок</button>
							</div>

							<div class="icons">
								<div class="search modal_cont">
									<button class="btn mini_modal_btn" data-modal-id="#search_modal">
										<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_search"></use></svg>
									</button>

									<div class="mini_modal" id="search_modal">
										<div class="cont small">
											<form action="/search/">
												<input type="text" name="q" value="<?=$_GET['q']?>" class="input" placeholder="Поиск">

												<button type="submit" class="submit_btn">
													<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_search"></use></svg>
												</button>

												<div class="sep"></div>

												<button type="button" class="close_btn">
													<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_close"></use></svg>
												</button>
											</form>
										</div>
									</div>
								</div>
								
								
								<?
								global $USER;
								if($USER->IsAuthorized()){ ?>
									<div class="account">
										<a href="/personal/" class="btn">
											<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_user"></use></svg>
										</a>
									</div>
								<?	} else { ?>
									<div class="account">
										<button class="btn modal_btn" data-modal="login_modal">
											<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_user"></use></svg>
										</button>
									</div>
									<?
								}?>

								<?$APPLICATION->IncludeComponent(
									"tochka:basket_light", 
									"", 
									[],
									false
								);?>
								
								
								<button class="mob_menu_btn">
									<span></span>
								</button>
							</div>
						</div>
					</div>
				</header>
				<?/*
				<section class="mob_header<? if ($curr_page == '/') { ?> absolute<? } ?>">
					<div class="cont padding row">
						<a href="/" class="logo">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => "/local/include/header_logo.php"
								));?>
						</a>

						<div class="icons">
							<div class="search modal_cont">
								<button class="btn mini_modal_btn" data-modal-id="#mob_search_modal">
									<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_search"></use></svg>
								</button>

								<div class="mini_modal" id="mob_search_modal">
									<div class="cont small">
										<form action="/search/">
											<input type="text" name="q" value="<?=$_GET['q']?>" class="input" placeholder="Поиск">

											<button type="submit" class="submit_btn">
												<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_search"></use></svg>
											</button>

											<div class="sep"></div>

											<button type="button" class="close_btn">
												<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_close"></use></svg>
											</button>
										</form>
									</div>
								</div>
							</div>

							<?
							global $USER;
							if($USER->IsAuthorized()){ ?>
								<div class="account">
									<a href="/personal/" class="btn active">
										<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_user"></use></svg>
									</a>
								</div>
							<?	} else { ?>
								<div class="account">
									<button class="btn modal_btn" data-modal="login_modal">
										<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_user"></use></svg>
									</button>
								</div>
								<?
							}?>

							<?$APPLICATION->IncludeComponent(
								"tochka:basket_light", 
								"", 
								[],
								false
							);?>

							<button class="mob_menu_btn">
								<span></span>
							</button>
						</div>
					</div>
				</section> */?>
				
				
				
				
				
				<section class="mob_menu">
					<div class="scroll">
						<div class="menu">
							<div>
								<a href="/catalog/" class="sub_link<? if (substr($curr_page, 0, 9) == '/catalog/') { ?> active<? } ?>">
									<span>КАТАЛОГ</span>
									<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>
								</a>

								<div class="sub">
									<?$APPLICATION->IncludeComponent(
										"bitrix:menu", 
										"catalog_top_mob", 
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
											"CHILD_MENU_TYPE" => "catalog",
											"DELAY" => "N",
											"COMPONENT_TEMPLATE" => "catalog"
										),
										false
									);?>
								</div>
							</div>
							
							<?$APPLICATION->IncludeComponent(
								"bitrix:menu", 
								"top_mob", 
								array(
									"ROOT_MENU_TYPE" => "top",
									"MENU_CACHE_TYPE" => "Y",
									"MENU_CACHE_TIME" => "36000000",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(
									),
									"MAX_LEVEL" => "1",
									"USE_EXT" => "Y",
									"ALLOW_MULTI_SELECT" => "N",
									"DELAY" => "N",
									"COMPONENT_TEMPLATE" => "top"
								),
								false
							);?>
						</div>

						<div class="contacts">
							<div class="phone">
								<?$APPLICATION->IncludeComponent(
									"bitrix:main.include", "",
									Array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => "/local/include/phone.php"
									));?>
							</div>

							<button class="callback_btn modal_btn" data-modal="callback_modal">Обратный звонок</button>

							<div class="socials">
								<?$APPLICATION->IncludeComponent(
								"bitrix:main.include", "",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => "/local/include/header_socials.php"
								));?>
							</div>
						</div>
					</div>
				</section>   
				
				
				
				
<?
/*
// конструктор
$resConstructor = CIBlockElement::GetList(["SORT"=>"ASC"], ["IBLOCK_ID"=>CONSTRUCTOR_ID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "PROPERTY_URL"=>$curr_page], false, false, ["*"]);
$constructor_items_cnt = $resConstructor->SelectedRowsCount();

$constructorClass = \Bitrix\Iblock\Iblock::wakeUp(CONSTRUCTOR_ID)->getEntityDataClass();
$oConstructorEls = $constructorClass::getList([
	'filter' => ['URL.VALUE' => $curr_page],
	'select' => ['ID', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'PRODS_TITLE_' => 'PRODS_TITLE', 'BANNER_' => 'BANNER', 'IS_TABLE_' => 'IS_TABLE', 'HEADER_TYPE_' => 'HEADER_TYPE.ITEM', 'HEADER_CONTENT_' => 'HEADER_CONTENT', 'IS_CONTACT_' => 'IS_CONTACT', 'IS_NEED_CONSULT_' => 'IS_NEED_CONSULT']
]);

$arConstructorEls = $oConstructorEls->fetchAll();     */
?>