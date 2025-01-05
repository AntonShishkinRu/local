<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="bx-auth">
<?
ShowMessage($arParams["~AUTH_RESULT"]);
?>
<form method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform" class="form change_password_form">
	<?if (strlen($arResult["BACKURL"]) > 0): ?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<? endif ?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="CHANGE_PWD">
	
	<div class="cols flex">
		<div class="col">
			<div class="line_form flex" style="display:none" hidden>
				<div class="field">
					<input type="email" placeholder="<?=GetMessage("AUTH_LOGIN")?>*" title="E-mail" name="USER_LOGIN" class="input"  value="<?=$arResult["LAST_LOGIN"]?>" required/>
				</div>
			</div>
			<div class="line_form flex" style="display:none" hidden>
				<div class="field">
					<input type="text" placeholder="<?=GetMessage("AUTH_CHECKWORD")?>*" title="<?=GetMessage("AUTH_CHECKWORD")?>" name="USER_CHECKWORD" class="input" value="<?=$arResult["USER_CHECKWORD"]?>" required/>
				</div>
			</div>

			<div class="line_form flex">
				<div class="field" style="width:300px">
					<input type="password" placeholder="Новый пароль *" title="Пароль" name="USER_PASSWORD" class="input" value="<?=$arResult["USER_PASSWORD"]?>" required/>
				</div>
				
				<?if($arResult["SECURE_AUTH"]):?>
								<span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
									<div class="bx-auth-secure-icon"></div>
								</span>
								<noscript>
								<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
									<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
								</span>
								</noscript>
									<script type="text/javascript">
									document.getElementById('bx_auth_secure').style.display = 'inline-block';
									</script>
				<?endif?>
			</div>
			<div class="line_form flex">
				<div class="field" style="width:300px">
					<input type="password" placeholder="Подтверждение пароля *" title="Подтверждение пароля" name="USER_CONFIRM_PASSWORD" class="input" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" required/>
				</div>
			</div>
		</div>
	</div>
	
	<div class="btns flex">
		<button type="submit" class="submit_btn"  name="change_pwd" value="<?=GetMessage("AUTH_CHANGE")?>" onclick="return checkChangeFields();" >Отправить</button>
	</div>
	<?/*
	<table class="data-table bx-changepass-table form-group-wrapper"  style="border-bottom:0">
		<tbody>
			<tr style="display:none">
				<td><?=GetMessage("AUTH_LOGIN")?> <span class="starrequired">*</span></td>
				<td><input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" class="form-control" /></td>
			</tr>
			<tr style="display:none">
				<td><span class="starrequired">*</span><?=GetMessage("AUTH_CHECKWORD")?></td>
				<td><input type="text" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" class="form-control" /></td>
			</tr>
			<tr>
				<td>Новый пароль *</td>
				<td class="form-group form-group-custom"><input type="password" title="Пароль" name="USER_PASSWORD" maxlength="50" value="<?=$arResult["USER_PASSWORD"]?>" class="form-control" autocomplete="off" />
<?if($arResult["SECURE_AUTH"]):?>
				<span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
					<div class="bx-auth-secure-icon"></div>
				</span>
				<noscript>
				<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
					<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
				</span>
				</noscript>
<script type="text/javascript">
document.getElementById('bx_auth_secure').style.display = 'inline-block';
</script>
<?endif?>
				</td>
			</tr>
			<tr>
				<td>Подтверждение пароля *</td>
				<td  class="form-group form-group-custom"><input type="password" title="Подтверждение пароля" name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" class="form-control" autocomplete="off" /></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td></td>
				<td class="form-group form-group-custom"><input type="submit" class="submit_btn btn btn-green" onclick="return checkChangeFields();" name="change_pwd" value="<?=GetMessage("AUTH_CHANGE")?>" /></td>
			</tr>
		</tfoot>
	</table>

<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
<p><span class="starrequired">*</span><?=GetMessage("AUTH_REQ")?></p>
*/ ?>
</form>

</div>
<br><br>

