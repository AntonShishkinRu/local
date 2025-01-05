<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<p><?echo $arResult["MESSAGE_TEXT"]?></p>
<?//here you can place your own messages
	switch($arResult["MESSAGE_CODE"])
	{
	case "E01":
		?><? //When user not found
		break;
	case "E02":
		?><? //User was successfully authorized after confirmation
		break;
	case "E03":
		?><? //User already confirm his registration
		break;
	case "E04":
		?><? //Missed confirmation code
		break;
	case "E05":
		?><? //Confirmation code provided does not match stored one
		break;
	case "E06":
		?><? //Confirmation was successfull
		break;
	case "E07":
		?><? //Some error occured during confirmation
		break;
	}
?>
<?if($arResult["SHOW_FORM"]):?>
	<div class="data">
	<form method="post" class="form mini" action="<?echo $arResult["FORM_ACTION"]?>">
		

		<div class="line">
			<div class="field">
				<input type="text" class="input" name="<?echo $arParams["LOGIN"]?>" placeholder="E-mail" value="<?echo $arResult["LOGIN"]?>">
			</div>
		</div>
									
		<div class="line">
			<div class="field">
				<input type="text"  placeholder="<?echo GetMessage("CT_BSAC_CONFIRM_CODE")?>"  class="input" name="<?echo $arParams["CONFIRM_CODE"]?>" maxlength="50" value="<?echo $arResult["CONFIRM_CODE"]?>">

				<div class="rule">
					<div class="point"></div>
					<div class="text">Чтобы подтвердить регистрацию, пожалуйста<br> введите код подтверждения.</div>
				</div>
			</div>
		</div>
		
		
		<div class="submit">
			<button type="submit" class="submit_btn" value="<?echo GetMessage("CT_BSAC_CONFIRM")?>"><?echo GetMessage("CT_BSAC_CONFIRM")?></button>
		</div>

		<input type="hidden" name="<?echo $arParams["USER_ID"]?>" value="<?echo $arResult["USER_ID"]?>" />
	</form>
<?elseif(!$USER->IsAuthorized()):?>
	<script>
		$(function(){
			$('#login_modal .my_ok_text').html('Ваша запись подтверждена.<br/>Пожалуйста, авторизуйтесь.<br/><br/>')
			
			$.fancybox.close()
			$.fancybox.open({
				src: '#login_modal',
				type: 'inline',
				touch: false
			})
		})
	</script>
<?endif?>