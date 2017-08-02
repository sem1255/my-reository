<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <nav id="nav">
        <ul><?/*
            <li class="current"><a href="index.html">Home</a></li>
            <li>
                <a href="#">Dropdown</a>
                <ul>
                    <li><a href="#">Lorem dolor</a></li>
                    <li><a href="#">Magna phasellus</a></li>
                    <li><a href="#">Etiam sed tempus</a></li>
                    <li>
                        <a href="#">Submenu</a>
                        <ul>
                            <li><a href="#">Lorem dolor</a></li>
                            <li><a href="#">Phasellus magna</a></li>
                            <li><a href="#">Magna phasellus</a></li>
                            <li><a href="#">Etiam nisl</a></li>
                            <li><a href="#">Veroeros feugiat</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Veroeros feugiat</a></li>
                </ul>
            </li>
            <li><a href="left-sidebar.html">Left Sidebar</a></li>
            <li><a href="right-sidebar.html">Right Sidebar</a></li>
            <li><a href="two-sidebar.html">Two Sidebar</a></li>
            <li><a href="no-sidebar.html">No Sidebar</a></li>
            */?>

            <?
            foreach ($arResult as $arItem):
                if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                    continue;
                ?>
                <? if ($arItem["SELECTED"]):?>
                <li class="current"><a  ><?= $arItem["TEXT"] ?></a></li>
            <? else:?>
                <li><a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a></li>
            <? endif ?>

            <? endforeach ?>

        </ul>
    </nav>
<? endif ?>