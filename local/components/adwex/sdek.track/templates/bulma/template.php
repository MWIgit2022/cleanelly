<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true); ?>
<section class="adw-cdek">
<? if (!empty($arResult['ERROR'])): ?>
    <? foreach ($arResult['ERROR'] as $errorCode => $errorText): ?>
        <div class="alert alert-danger alert--<?= $errorCode ?> message is-danger" role="alert">
        <div class="message-body"><?= $errorText ?></div>
        </div>
    <? endforeach ?>
<? endif ?>
    <div class="adw-cdek__form" id="adwCdek">
        <form method="POST" action="#adwCdek" name="adwCdek" class="webFormTools">
            <div class="form-group field adw-cdek__form-group webFormItemField">
                <label for="adwCdekTrackCode" class="adw-cdek__form-label label"><?= GetMessage('ADW_CDEK_TRACK_CODE') ?></label>
                <input type="text" value="<?= $arResult['TRACK_CODE'] ?>" class="form-control input" name="actc" id="adwCdekTrackCode">
            </div>
            <input type="submit" class="btn btn-default button is-primary button-default" value="<?= GetMessage('ADW_CDEK_SEND') ?>">
        </form>
    </div>
<? if (!empty($arResult['WARNING'])): ?>
    <? foreach ($arResult['WARNING'] as $warningCode => $warningText): ?>
        <div class="alert alert-warning alert--<?= $warningCode ?> message is-warning" role="alert">
        <div class="message-body"><?= $warningText ?></div>
        </div>
    <? endforeach ?>
<? endif ?>
<? if (isset($arResult['TRACK_INFO']['STATUS'])): ?>
    <div class="adw-cdek__info<? if ($arResult['SHOW_HISTORY']): ?> adw-cdek__info--history<? endif ?>">
        <table class="adw-cdek__info-table">
        <thead><tr><th><?= GetMessage('ADW_CDEK_FROM') ?></th><th><?= GetMessage('ADW_CDEK_TO') ?></th></tr></thead>
        <tbody><tr><td><?= $arResult['TRACK_INFO']['ORDER']['FROM']['Name'] ?></td><td><?= $arResult['TRACK_INFO']['ORDER']['TO']['Name'] ?></td></tr></tbody>
        </table>
        <section>
            <h2 class="adw-cdek__info-status"><?= GetMessage('ADW_CDEK_STATUS') ?>:</h2>
            <?/* if (count($arResult['CALCULATE']) && !empty($arResult['CALCULATE']['deliveryDateMax'])): ?>                
                <b><?= GetMessage('ADW_CDEK_WHEN_WAIT') ?></b> <small class="text-muted"><time><?= $arResult['CALCULATE']['deliveryDateMax']['BEAUTY'] ?></time></small><br>
            <? endif */?>
            <b><?= $arResult['TRACK_INFO']['STATUS']['Code']['NAME'] ?></b> <small class="text-muted"><time><?= $arResult['TRACK_INFO']['STATUS']['Date']['BEAUTY'] ?></time></small>
            <div><? if (is_array($arResult['TRACK_INFO']['ORDER']['DeliveryDate'])): ?><b><?= GetMessage('ADW_CDEK_DELIVERY_DATE') ?>:</b> <?= $arResult['TRACK_INFO']['ORDER']['DeliveryDate']['BEAUTY'] ?><br><? endif ?>
           <? if (strlen($arResult['TRACK_INFO']['ORDER']['RecipientName'])): ?> <b><?= GetMessage('ADW_CDEK_WHO') ?>:</b> <?= $arResult['TRACK_INFO']['ORDER']['RecipientName'] ?><br><? endif ?>
            <b><?= GetMessage('ADW_CDEK_WHERE_IS') ?>:</b> <?= $arResult['TRACK_INFO']['STATUS']['CityName'] ?></div>
            <? if ($arResult['SHOW_HISTORY'] && count($arResult['TRACK_INFO']['HISTORY'])): ?>
            <div class="adw-cdek__history">
                <h3 class="adw-cdek__history-title"><?= GetMessage('ADW_CDEK_TRACKING') ?>:</h3>
                <? foreach ($arResult['TRACK_INFO']['HISTORY'] as $state): ?>
                <div class="adw-cdek__history-item">
                    <b><?= $state['Code']['NAME'] ?></b> <small class="text-muted"><time><?= $state['Date']['BEAUTY'] ?></time></small><br>
                    <?= GetMessage('ADW_CDEK_WHERE_IS') ?>: <?= $state['CityName'] ?>
                </div>
                <? endforeach ?>
            </div>
            <? endif ?>
        </section>
    </div>
<? endif ?>
</section>