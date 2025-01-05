<?
/**
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
?>



<script type="text/javascript">
<!--
var opened_sections = [<?
$arResult["opened"] = $_COOKIE[$arResult["COOKIE_PREFIX"]."_user_profile_open"];
$arResult["opened"] = preg_replace("/[^a-z0-9_,]/i", "", $arResult["opened"]);
if (strlen($arResult["opened"]) > 0)
{
	echo "'".implode("', '", explode(",", $arResult["opened"]))."'";
}
else
{
	$arResult["opened"] = "reg";
	echo "'reg'";
}
?>];
//-->

var cookie_prefix = '<?=$arResult["COOKIE_PREFIX"]?>';
</script>
<?if($arResult["strProfileError"]) { ?>
<span class="my_error_text"><?=$arResult["strProfileError"];?></span>
<? } ?>
<?
if ($arResult['DATA_SAVED'] == 'Y') { ?>
<span class="my_ok_text"><?=GetMessage('PROFILE_DATA_SAVED');?></span>
<? }?>



		<div class="section">
			<div class="title">ДАННЫЕ ПОКУПАТЕЛЯ</div>

			<form method="post" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data" class="form personal_form" id="profile">
				<?=$arResult["BX_SESSION_CHECK"]?>
				<input type="hidden" name="lang" value="<?=LANG?>" />
				<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
				<input type="text" name="LOGIN" value="<? echo $arResult["arUser"]["~LOGIN"]?>" style="display:none" hidden="hidden">
				
				<div class="columns">
					<div class="line width1of6">
						<div class="field">
							<input type="text" name="LAST_NAME" value="<?=$arResult["arUser"]["~LAST_NAME"]?>" placeholder="ФАМИЛИЯ" title="Фамилия" class="input">
						</div>
					</div>

					<div class="line width1of6">
						<div class="field">
							<input type="text" name="NAME" value="<?=$arResult["arUser"]["~NAME"]?>" placeholder="ИМЯ *" title="Имя" class="input" required>
						</div>
					</div>

					<div class="line width1of6">
						<div class="field">
							<input type="text" name="SECOND_NAME" value="<?=$arResult["arUser"]["~SECOND_NAME"]?>" placeholder="ОТЧЕСТВО" title="Отчество" class="input">
						</div>
					</div>
				</div>

				<div class="columns">
					<div class="line width1of6">
						<div class="field">
							<input type="tek" name="PERSONAL_PHONE_REC" value="<?=$arResult["arUser"]["~PERSONAL_PHONE"]?>" placeholder="ТЕЛЕФОН" title="Телефон" class="input">
						</div>
					</div>

					<div class="line width1of6">
						<div class="field">
							<input type="email" name="EMAIL" value="<?=$arResult["arUser"]["~EMAIL"]?>" placeholder="E-MAIL *" title="E-mail" class="input" required>
						</div>
					</div>

					<div class="line width1of6">
						<div class="field">
							<input type="text" placeholder="ГОРОД" title="Город" name="UF_CITY" value="<?=$arResult["arUser"]["~UF_CITY"]?>" class="input">
						</div>
					</div>

					<div class="line birthday">
						<div class="label">Дата рождения</div>

						<div class="field">
							<input type="text" title="Дата рождения" name="PERSONAL_BIRTHDAY" value="<?=$arResult["arUser"]["~PERSONAL_BIRTHDAY"]?>" class="input input_date">

							<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_calendar"></use></svg>
						</div>
					</div>


					<div class="line gender">
						<div class="label">Пол</div>

						<div class="field">
							<label class="radio">
								<input type="radio" name="PERSONAL_GENDER"<?if ($arResult["arUser"]["~PERSONAL_GENDER"] =="F"){?> checked<?}?> value="F">

								<div class="check"></div>

								<div>Женский</div>
							</label>

							<label class="radio">
								<input type="radio" name="PERSONAL_GENDER"<?if ($arResult["arUser"]["~PERSONAL_GENDER"] =="M"){?> checked<?}?> value="M">

								<div class="check"></div>

								<div>Мужской</div>
							</label>
						</div>
					</div>
				</div>

				<div class="columns">
					<div class="submit width1of6">
						<button type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD"))?>" class="submit_btn" onclick="return checkUserFields(this);">СОХРАНИТЬ ИЗМЕНЕНИЯ</button>
					</div>
				</div>
			</form>
		</div>

		<div class="section">
			<div class="title">ДАННЫЕ ОРГАНИЗАЦИИ</div>

			<form id="org_form" method="post" class="form" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data" style="display:block">
				<?=$arResult["BX_SESSION_CHECK"]?>
				<input type="hidden" name="lang" value="<?=LANG?>" />
				<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
				<input type="hidden" name="LOGIN" value="<?=$arResult["arUser"]["LOGIN"]?>">
				<input type="hidden" name="EMAIL" value="<?=$arResult["arUser"]["EMAIL"]?>">
				<div class="columns">
					<div class="line width1of6">
						<div class="field">
							<input type="text" placeholder="Наименование компании" title="Наименование компании" name="UF_COMPANY_NAME" value="<?=$arResult["arUser"]["~UF_COMPANY_NAME"]?>" class="input">
						</div>
					</div>

					<div class="line width2of6">
						<div class="field">
							<input type="text" placeholder="Адрес компании" title="Адрес компании" name="UF_COMPANY_ADDRESS" value="<?=$arResult["arUser"]["~UF_COMPANY_ADDRESS"]?>" class="input">
						</div>
					</div>
				</div>

				<div class="columns">
					<div class="line width1of6">
						<div class="field">
							<input type="text" placeholder="БИК банка" title="БИК банка" name="UF_BIC" value="<?=$arResult["arUser"]["~UF_BIC"]?>" class="input">
						</div>
					</div>

					<div class="line width1of6">
						<div class="field">
							<input type="text" placeholder="Наименование банка" title="Наименование банка" name="UF_BANK_NAME" value="<?=$arResult["arUser"]["~UF_BANK_NAME"]?>" class="input">
						</div>
					</div>

					<div class="line width1of6">
						<div class="field">
							<input type="text" placeholder="ИНН" title="ИНН" name="UF_INN" value="<?=$arResult["arUser"]["~UF_INN"]?>" class="input">
						</div>
					</div>

					<div class="line width1of6">
						<div class="field">
							<input type="text" placeholder="КПП" title="КПП" name="UF_KPP" value="<?=$arResult["arUser"]["~UF_KPP"]?>" class="input">
						</div>
					</div>

					<div class="line width1of6">
						<div class="field">
							<input type="text" placeholder="Кор/счет" title="Кор/счет" name="UF_KOR_SCHET" value="<?=$arResult["arUser"]["~UF_KOR_SCHET"]?>" class="input">
						</div>
					</div>

					<div class="line width1of6">
						<div class="field">
							<input type="text" placeholder="Р/счет" title="Р/счет" name="UF_R_SCHET" value="<?=$arResult["arUser"]["~UF_R_SCHET"]?>" class="input">
						</div>
					</div>
				</div>

				<div class="columns">
					<div class="line file width1of3">
						<? if ($arResult["arUser"]["UF_FILE"]) { ?>
							<div class="selected">
								<div>
									<? $arFile = CFile::GetFileArray($arResult["arUser"]["UF_FILE"]); ?>
									<span><?=$arFile['ORIGINAL_NAME']?></span>

									<button type="button" class="remove_btn">
										<svg><use xlink:href="/local/images/sprite.svg#ic_close"></use></svg>
									</button>
								</div> 
							</div>
						<? } ?>

						<label id="file_lk">
							<input type="file" name="UF_FILE"/>

							<div>
								<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_add"></use></svg>
								<span>ПРИКРЕПИТЬ ФАЙЛ С РЕКВИЗИТАМИ</span>
							</div>
						</label>
					</div>
				</div>

				<div class="columns">
					<div class="submit width1of6 small_p">
						<button type="submit" name="save" class="submit_btn" value="<?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD"))?>">СОХРАНИТЬ ИЗМЕНЕНИЯ</button>
					</div>
				</div>
			</form>
		</div>
		
		

		<div class="section">
			<div class="title spoler_btn active">
				<span>СМЕНА ПАРОЛЯ</span>

				<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>
			</div>


			<form method="post" class="form password_form change_password_form hide" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data" style="display:block">
				<?=$arResult["BX_SESSION_CHECK"]?>
				<input type="hidden" name="lang" value="<?=LANG?>" />
				<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
				<input type="hidden" name="LOGIN" value="<?=$arResult["arUser"]["LOGIN"]?>">
				<input type="hidden" name="EMAIL" value="<?=$arResult["arUser"]["EMAIL"]?>">
				<div class="columns">
					<div class="line width1of6">
						<div class="field">
							<input type="password" name="NEW_PASSWORD" title="Пароль" class="input required_custom" required placeholder="НОВЫЙ ПАРОЛЬ *">
						</div>
					</div>

					<div class="line width1of6">
						<div class="field">
							<input type="password" name="NEW_PASSWORD_CONFIRM" title="Подтверждение пароля" class="input required_custom" required placeholder="ПОДТВЕРЖДЕНИЕ ПАРОЛЯ *">
						</div>
					</div>
				</div>

				<div class="columns">
					<div class="submit width1of6 small_p">
						<button type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD"))?>" class="submit_btn" onclick="return checkPasswordFields();">СОХРАНИТЬ ИЗМЕНЕНИЯ</button>
					</div>
				</div>
			</form>
		</div>




		
	<?/*
		if ($isOpt) { ?>
			<form method="post" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data" class="form personal_form" id="profile">
				<?=$arResult["BX_SESSION_CHECK"]?>
				<input type="hidden" name="lang" value="<?=LANG?>" />
				<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
				<input type="text" name="LOGIN" value="<? echo $arResult["arUser"]["~LOGIN"]?>" style="display:none" hidden="hidden">
				<div class="section">
					<div class="title">Мои данные</div>

					<div class="cols row">
						<div class="col">
							
							<div class="line">
								<div class="label">ИНН</div>

								<div class="field">
									<input type="text" title="ИНН" name="UF_INN" value="<?=$arResult["arUser"]["~UF_INN"]?>" class="input">
								</div>
							</div>

							<div class="line">
								<div class="label">КПП</div>

								<div class="field">
									<input type="text" title="КПП" name="UF_KPP" value="<?=$arResult["arUser"]["~UF_KPP"]?>" class="input">
								</div>
							</div>

							<div class="line">
								<div class="label">Юридический адрес</div>

								<div class="field">
									<input type="text" title="Юридический адрес" name="UF_YR_ADDRESS" value="<?=$arResult["arUser"]["~UF_YR_ADDRESS"]?>" class="input">
								</div>
							</div>

							<div class="line">
								<div class="label">Фактический адрес</div>

								<div class="field">
									<input type="text" title="Фактический адрес" name="UF_FACT_ADDRESS" value="<?=$arResult["arUser"]["~UF_FACT_ADDRESS"]?>" class="input">
								</div>
							</div>
						</div>

						<div class="col">
						

							<div class="line">
								<div class="label">Кор/счет</div>

								<div class="field">
									<input type="text" title="Кор/счет" name="UF_KOR_SCHET" value="<?=$arResult["arUser"]["~UF_KOR_SCHET"]?>" class="input">
								</div>
							</div>

							<div class="line">
								<div class="label">Р/счет</div>

								<div class="field">
									<input type="text" title="Р/счет" name="UF_R_SCHET" value="<?=$arResult["arUser"]["~UF_R_SCHET"]?>" class="input">
								</div>
							</div>

							<div class="line">
								<div class="label">Наименование банка</div>

								<div class="field">
									<input type="text" title="Наименование банка" name="UF_BANK_NAME" value="<?=$arResult["arUser"]["~UF_BANK_NAME"]?>" class="input">
								</div>
							</div>
						</div>
					</div>
				</div>


				<div class="section">
					<div class="title">Контактное лицо</div>

					<div class="cols row">
						<div class="col">
							<div class="line">
								<div class="label">Фамилия <span class="required">*</span></div>

								<div class="field">
									<input type="text" name="LAST_NAME" value="<?=$arResult["arUser"]["~LAST_NAME"]?>" title="Фамилия" class="input required_custom">
								</div>
							</div>

							<div class="line">
								<div class="label">Имя <span class="required">*</span></div>

								<div class="field">
									<input type="text" name="NAME" value="<?=$arResult["arUser"]["~NAME"]?>" title="Имя" class="input required_custom">
								</div>
							</div>
						</div>

						<div class="col">
							<div class="line">
								<div class="label">Телефон <span class="required">*</span></div>

								<div class="field">
									<input data-tel-input type="tel" name="PERSONAL_PHONE_REC" value="<?=$arResult["arUser"]["~PERSONAL_PHONE"]?>" title="Телефон" class="input required_custom">
									
									<input type="hidden" name="PERSONAL_PHONE" value="<?=$arResult["arUser"]["~PERSONAL_PHONE"]?>">
								</div>
							</div>

							<div class="line">
								<div class="label">E-mail <span class="required">*</span></div>

								<div class="field">
									<input type="email" name="EMAIL" value="<?=$arResult["arUser"]["~EMAIL"]?>" title="E-mail" class="input required_custom">
								</div>
							</div>
						</div>
					</div>
				</div>
				<button type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD"))?>" class="submit_btn" onclick="return checkUserFields(this);"  style="display:none">Сохранить</button>
			</form>
		
		<? } else { */ ?>
			
