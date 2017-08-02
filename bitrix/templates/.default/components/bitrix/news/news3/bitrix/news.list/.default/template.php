<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="news-list">


<? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
    <?= $arResult["NAV_STRING"] ?><br/>
<? endif; ?>

<? foreach ($arResult["ITEMS"] as $arItem): ?>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
    <article id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
        <header>
            <? if ($arParams["DISPLAY_NAME"] != "N" && $arItem["NAME"]): ?>
                <? if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
                    <a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>"><h2><? echo $arItem["NAME"] ?></h2></a>
                <? else: ?>
                    <h2><? echo $arItem["NAME"] ?></h2>
                <? endif; ?>
            <? endif; ?>
            <? if ($arParams["DISPLAY_DATE"] != "N" && $arItem["DISPLAY_ACTIVE_FROM"]): ?>
                <p class="news-date-time"><? echo $arItem["DISPLAY_ACTIVE_FROM"] ?></p>
            <? endif ?>
        </header>
        <? if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arItem["PREVIEW_PICTURE"])): ?>
            <? if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
                <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"> <span class="image featured"><img
                                class="preview_picture"
                                src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                                height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                                alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                                title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                        /></span></a>
            <? else: ?>
                <span class="image featured"><img
                            class="preview_picture"
                            src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                            width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                            height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                            alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                            title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                    /></span>
            <? endif; ?>
        <? endif ?>


        <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
            <p><? echo $arItem["PREVIEW_TEXT"]; ?></p>
        <? endif; ?>
        <p>Phasellus quam turpis, feugiat sit amet ornare in, hendrerit in lectus.
           Praesent semper mod quis eget mi. Etiam eu ante risus. Aliquam erat volutpat.
           Aliquam luctus et mattis lectus sit amet pulvinar. Nam turpis nisi
           consequat etiam lorem ipsum dolor sit amet nullam.</p>

        <h3>And Yet Another Subheading</h3>
    </article>
<?/*
    <p class="news-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
    <? if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arItem["PREVIEW_PICTURE"])): ?>
        <? if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><img
                        class="preview_picture"
                        border="0"
                        src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                        width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                        height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                        alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                        title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                        style="float:left"
                /></a>
        <? else: ?>
            <img
                    class="preview_picture"
                    border="0"
                    src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                    width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                    height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                    alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                    title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                    style="float:left"
            />
        <? endif; ?>
    <? endif ?>
    <? if ($arParams["DISPLAY_DATE"] != "N" && $arItem["DISPLAY_ACTIVE_FROM"]): ?>
        <span class="news-date-time"><? echo $arItem["DISPLAY_ACTIVE_FROM"] ?></span>
    <? endif ?>
    <? if ($arParams["DISPLAY_NAME"] != "N" && $arItem["NAME"]): ?>
        <? if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
            <a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>"><b><? echo $arItem["NAME"] ?></b></a><br/>
        <? else: ?>
            <b><? echo $arItem["NAME"] ?></b><br/>
        <? endif; ?>
    <? endif; ?>
    <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
        <? echo $arItem["PREVIEW_TEXT"]; ?>
    <? endif; ?>
    <? if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arItem["PREVIEW_PICTURE"])): ?>
        <div style="clear:both"></div>
    <? endif ?>
    <? foreach ($arItem["FIELDS"] as $code => $value): ?>
        <small>
            <?= GetMessage("IBLOCK_FIELD_" . $code) ?>:&nbsp;<?= $value; ?>
        </small><br/>
    <? endforeach; ?>
    <? foreach ($arItem["DISPLAY_PROPERTIES"] as $pid => $arProperty): ?>
        <small>
            <?= $arProperty["NAME"] ?>:&nbsp;
            <? if (is_array($arProperty["DISPLAY_VALUE"])): ?>
                <?= implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]); ?>
            <? else: ?>
                <?= $arProperty["DISPLAY_VALUE"]; ?>
            <? endif ?>
        </small><br/>
    <? endforeach; ?>
    </p>
*/?>
<? endforeach; ?>

<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
    <br/><?= $arResult["NAV_STRING"] ?>
<? endif; ?>
</div>
