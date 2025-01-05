<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
\Bitrix\Main\Loader::includeModule("sale");
$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
$basketItems = $basket->getBasketItems();
$arResult = ['ITEMS' => ['DELAYED' => [], 'BASKET' => []], 'DELAYED_NUM' => 0, 'BASKET_POS_NUM' => 0, 'BASKET_QUANT_NUM' => 0];
foreach ($basket as $basketItem) {
	if ($basketItem->getField('DELAY') == 'Y') {
		++$arResult['DELAYED_NUM'];
		$arResult['ITEMS']['DELAYED'][] = ['ID' => $basketItem->getId(), 'PRODUCT_ID' => $basketItem->getProductId(), 'NAME' => $basketItem->getField('NAME')];
	} else {
		++$arResult['BASKET_POS_NUM'];
		$quantity = $basketItem->getQuantity();
		$arResult['BASKET_QUANT_NUM'] += $quantity;
		$arResult['ITEMS']['BASKET'][] = ['ID' => $basketItem->getId(), 'PRODUCT_ID' => $basketItem->getProductId(), 'NAME' => $basketItem->getField('NAME'), 'QUANTITY' => $quantity];
	}
}
$this->IncludeComponentTemplate();