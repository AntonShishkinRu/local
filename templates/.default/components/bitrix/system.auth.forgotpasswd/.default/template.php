<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

ShowMessage($arParams["~AUTH_RESULT"]);

?>
<form name="bform" method="post" target="_top" class="form" action="<?=$arResult["AUTH_URL"]?>">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="SEND_PWD">
	<p style="margin-bottom: 20px">
	<?=GetMessage("AUTH_FORGOT_PASSWORD_1")?>
	</p>
	
	<div class="cols flex">
		<div class="col">
			<div class="line_form flex">
				<div class="field">
					<input type="email" placeholder="Ваш E-mail*" title="E-mail" name="USER_EMAIL" class="input" required/>
				</div>
			</div>
		</div>
		
	</div>
<div class="btns flex">
			<button type="submit" class="submit_btn"  name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>">Отправить</button>
		</div>
</form>
<br/><br/>
