<?php 
    if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
        die();
    }

    if ($arResult['ITEMS']) {
    ?>
    <div class="advantages-badges">
        <?php foreach ($arResult['ITEMS'] as $item) {?>
            <div class="advantages-badges-item">
                <a href="<?= $item['PROPERTIES']['LINK']["VALUE"]?>">
                    <div class="advantages-badges-item-img">
                        <img class="badge-img lazy"
                                data-src="<?= $item['PREVIEW_PICTURE']['SRC']?>"
                                alt="<?= $item['NAME']?>">
                    </div>
                    <span class="advantages-badges-item-name"><?= $item['NAME']?></span>
                </a>
            </div>
        <?php }?>
        <!--div class="advantages-badges-item">
            <a href="/mezhdunarodnyy-sertifikat-kachestva/">
                <div class="teasers-main-desc_img">
                    <img class="lazy" data-src="/images/main-teaser-photo.jpg" alt="main-teaser-photo">
                </div>
                <div class="teasers-main-desc_txt">
                    <div>Наш секрет прост,<br/>это натуральные материалы</div>
                    <div class="teasers-main-gr">100% хлопок</div>
                </div>
            </a>
        </div-->
    </div>
<?php }
