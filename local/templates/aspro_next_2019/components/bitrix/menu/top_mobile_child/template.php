<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?$this->setFrameMode(true);

if($arResult):?>
	<?foreach($arResult as $arItem):?>
		<?$bShowChilds = $arParams['MAX_LEVEL'] > 1;?>
		<?$bParent = $arItem['CHILD'] && $bShowChilds;?>
		<li<?=($arItem['SELECTED'] ? ' class="selected"' : '')?>>
			<a class="dark-color<?=($bParent ? ' parent' : '')?>" href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>">
				<span><?=$arItem['TEXT']?></span>
				<?if($bParent):?>
					<span class="arrow"><i class="svg svg_triangle_right"></i></span>
				<?endif;?>
			</a>
			<?if($bParent):?>
				<ul class="dropdown">
					<li class="menu_back"><a href="" class="dark-color" rel="nofollow"><i class="svg svg-arrow-right"></i><?=GetMessage('NEXT_T_MENU_BACK')?></a></li>
					<li class="menu_title"><a href="<?=$arItem['LINK'];?>"><?=$arItem['TEXT']?></a></li>
					<?foreach($arItem['CHILD'] as $arSubItem):?>
						<?$bShowChilds = $arParams['MAX_LEVEL'] > 2;?>
						<?$bParent = $arSubItem['CHILD'] && $bShowChilds;?>
						<li<?=($arSubItem['SELECTED'] ? ' class="selected"' : '')?>>
							<a class="dark-color <?= $arSubItem['TEXT'] === 'Cleanelly GREEN PLANET' ? 'green_planet' : '' ?><?=($bParent ? ' parent' : '')?>" href="<?=$arSubItem["LINK"]?>" title="<?=$arSubItem["TEXT"]?>">
								<span><?=$arSubItem['TEXT']?></span>
								<?if($bParent):?>
									<span class="arrow"><i class="svg svg_triangle_right"></i></span>
								<?endif;?>
							</a>
							<?if($bParent):?>
								<ul class="dropdown">
									<li class="menu_back"><a href="" class="dark-color" rel="nofollow"><i class="svg svg-arrow-right"></i><?=GetMessage('NEXT_T_MENU_BACK')?></a></li>
									<li class="menu_title"><a href="<?=$arSubItem['LINK'];?>"><?=$arSubItem['TEXT']?></a></li>
									<?foreach($arSubItem["CHILD"] as $arSubSubItem):?>
										<?$bShowChilds = $arParams['MAX_LEVEL'] > 3;?>
										<?$bParent = $arSubSubItem['CHILD'] && $bShowChilds;?>
										<li<?=($arSubSubItem['SELECTED'] ? ' class="selected"' : '')?>>
											<a class="dark-color<?=($bParent ? ' parent' : '')?>" href="<?=$arSubSubItem["LINK"]?>" title="<?=$arSubSubItem["TEXT"]?>">
												<span><?=$arSubSubItem['TEXT']?></span>
												<?if($bParent):?>
													<span class="arrow"><i class="svg svg_triangle_right"></i></span>
												<?endif;?>
											</a>
											<?if($bParent):?>
												<ul class="dropdown">
													<li class="menu_back"><a href="" class="dark-color" rel="nofollow"><i class="svg svg-arrow-right"></i><?=GetMessage('NEXT_T_MENU_BACK')?></a></li>
													<li class="menu_title"><a href="<?=$arSubSubItem['LINK'];?>"><?=$arSubSubItem['TEXT']?></a></li>
													<?foreach($arSubSubItem["CHILD"] as $arSubSubSubItem):?>
														<?$bShowChilds = $arParams['MAX_LEVEL'] > 4;?>
														<?$bParent = $arSubSubSubItem['CHILD'] && $bShowChilds;?>
														<li<?=($arSubSubSubItem['SELECTED'] ? ' class="selected"' : '')?>>
															<a class="dark-color" href="<?=$arSubSubSubItem["LINK"]?>" title="<?=$arSubSubSubItem["TEXT"]?>">
																<span><?=$arSubSubSubItem['TEXT']?></span>
																<?if($bParent):?>
																	<span class="arrow"><i class="svg svg_triangle_right"></i></span>
																<?endif;?>
															</a>
															<?if($bParent):?>
																<ul class="dropdown">
																	<li class="menu_back"><a href="" class="dark-color" rel="nofollow"><i class="svg svg-arrow-right"></i><?=GetMessage('NEXT_T_MENU_BACK')?></a></li>
																	<li class="menu_title"><a href="<?=$arSubSubSubItem['LINK'];?>"><?=$arSubSubSubItem['TEXT']?></a></li>
																	<?foreach($arSubSubSubItem["CHILD"] as $arSubSubSubSubItem):?>
																		<li<?=($arSubSubSubSubItem['SELECTED'] ? ' class="selected"' : '')?>>
																			<a class="dark-color" href="<?=$arSubSubSubSubItem["LINK"]?>" title="<?=$arSubSubSubSubItem["TEXT"]?>">
																				<span><?=$arSubSubSubSubItem['TEXT']?></span>
																			</a>
																	<?endforeach;?>
																</ul>
															<?endif;?>
														</li>
													<?endforeach;?>
												</ul>
											<?endif;?>
										</li>
									<?endforeach;?>
								</ul>
							<?endif;?>
						</li>
					<?endforeach;?>
				</ul>
			<?endif;?>
		</li>
	<?endforeach;?>
<?endif;?>