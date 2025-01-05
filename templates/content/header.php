<?
/*
CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
die();*/
?>

<? define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/header.php'); ?>

<section class="page_head">
	<div class="cont">
		<? 
			$APPLICATION->IncludeComponent(
			"bitrix:breadcrumb", 
			".default", 
			array(
				"COMPONENT_TEMPLATE" => ".default",
				"START_FROM" => "0",
				"PATH" => ""
			),
			false
		);
		?>

		<h1 class="page_title"><?$APPLICATION->ShowTitle(false)?></h1>
	</div>
</section>


<? if (empty($arConstructorEls)) { ?>
	<section class="block">
		<div class="cont row">
			<div class="text_block">
<? } ?>