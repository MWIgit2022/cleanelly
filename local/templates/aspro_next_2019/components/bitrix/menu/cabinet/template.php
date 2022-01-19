<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?global $USER, $arTheme;?>
<?$bParent = $arResult && $USER->IsAuthorized();?>
<?$this->setFrameMode(true);?>
<!-- noindex -->
<div class="mega-menu table-menu">
		<table>
			<tr>
				<td class="menu-item dropdown ">
					<div class="wrap">
						<?$link = str_replace('/', SITE_DIR, \Bitrix\Main\Config\Option::get('aspro.next', 'PERSONAL_PAGE_URL', SITE_DIR.'personal/'));?>
						<?=CNext::ShowCabinetLink(true, true);?>
						<ul class="dropdown-menu ">
							<?foreach($arResult as $arItem):?>
								<li >
									<a href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>">
										<span class = "name"><?=$arItem['TEXT']?></span>
									</a>
								</li>
							<?endforeach;?>
						</ul>
					</div>
				</td>
			</tr>
		</table>
</div>
<!-- /noindex -->