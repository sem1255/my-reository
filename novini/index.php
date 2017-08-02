<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("������");
?>
    <section class="wrapper style1">
        <div class="container">
            <div class="row 200%">
                <div class="8u 12u(narrower)">
                    <div id="content">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:news",
                            "news",
                            array(
                                "ADD_ELEMENT_CHAIN" => "Y",
                                "ADD_SECTIONS_CHAIN" => "Y",
                                "AJAX_MODE" => "N",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "AJAX_OPTION_HISTORY" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "BROWSER_TITLE" => "NAME",
                                "CACHE_FILTER" => "N",
                                "CACHE_GROUPS" => "Y",
                                "CACHE_TIME" => "36000000",
                                "CACHE_TYPE" => "A",
                                "CHECK_DATES" => "Y",
                                "DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
                                "DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
                                "DETAIL_DISPLAY_TOP_PAGER" => "N",
                                "DETAIL_FIELD_CODE" => array(
                                    0 => "",
                                    1 => "",
                                ),
                                "DETAIL_PAGER_SHOW_ALL" => "Y",
                                "DETAIL_PAGER_TEMPLATE" => "",
                                "DETAIL_PAGER_TITLE" => "��������",
                                "DETAIL_PROPERTY_CODE" => array(
                                    0 => "",
                                    1 => "",
                                ),
                                "DETAIL_SET_CANONICAL_URL" => "N",
                                "DISPLAY_BOTTOM_PAGER" => "Y",
                                "DISPLAY_DATE" => "Y",
                                "DISPLAY_NAME" => "Y",
                                "DISPLAY_PICTURE" => "Y",
                                "DISPLAY_PREVIEW_TEXT" => "Y",
                                "DISPLAY_TOP_PAGER" => "N",
                                "HIDE_LINK_WHEN_NO_DETAIL" => "Y",
                                "IBLOCK_ID" => "1",
                                "IBLOCK_TYPE" => "information",
                                "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                                "LIST_ACTIVE_DATE_FORMAT" => "d.M.Y g:i A",
                                "LIST_FIELD_CODE" => array(
                                    0 => "",
                                    1 => "",
                                ),
                                "LIST_PROPERTY_CODE" => array(
                                    0 => "",
                                    1 => "",
                                ),
                                "MESSAGE_404" => "",
                                "META_DESCRIPTION" => "-",
                                "META_KEYWORDS" => "-",
                                "NEWS_COUNT" => "3",
                                "PAGER_BASE_LINK_ENABLE" => "N",
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                "PAGER_SHOW_ALL" => "N",
                                "PAGER_SHOW_ALWAYS" => "N",
                                "PAGER_TEMPLATE" => "modern",
                                "PAGER_TITLE" => "�������",
                                "PREVIEW_TRUNCATE_LEN" => "",
                                "SEF_FOLDER" => "/novini/",
                                "SEF_MODE" => "N",
                                "SET_LAST_MODIFIED" => "N",
                                "SET_STATUS_404" => "N",
                                "SET_TITLE" => "Y",
                                "SHOW_404" => "N",
                                "SORT_BY1" => "ACTIVE_FROM",
                                "SORT_BY2" => "SORT",
                                "SORT_ORDER1" => "DESC",
                                "SORT_ORDER2" => "ASC",
                                "STRICT_SECTION_CHECK" => "N",
                                "USE_CATEGORIES" => "N",
                                "USE_FILTER" => "N",
                                "USE_PERMISSIONS" => "N",
                                "USE_RATING" => "N",
                                "USE_RSS" => "N",
                                "USE_SEARCH" => "N",
                                "USE_SHARE" => "N",
                                "COMPONENT_TEMPLATE" => "news",
                                "VARIABLE_ALIASES" => array(
                                    "SECTION_ID" => "SECTION_ID",
                                    "ELEMENT_ID" => "ELEMENT_ID",
                                )
                            ),
                            false,
                            array(
                                "ACTIVE_COMPONENT" => "Y"
                            )
                        ); ?>
                    </div>
                </div>
                <div class="4u 12u(narrower)">
                    <div id="sidebar">
                        <section>
                            <? $APPLICATION->IncludeComponent("bitrix:main.include", "news", Array(
                                "COMPONENT_TEMPLATE" => ".default",
                                "AREA_FILE_SHOW" => "sect",    // ���������� ���������� �������
                                "AREA_FILE_SUFFIX" => "inc",    // ������� ����� ����� ���������� �������
                                "AREA_FILE_RECURSIVE" => "Y",    // ����������� ����������� ���������� �������� ��������
                                "EDIT_TEMPLATE" => "",    // ������ ������� �� ���������
                            ),
                                false
                            ); ?>
                            <footer><a href="#" class="button">Continue Reading</a></footer>
                        </section>
                        <section>
                            <h3>Розділи</h3>
                            <? $APPLICATION->IncludeComponent("bitrix:catalog.section.list", "news", Array(
                                "ADD_SECTIONS_CHAIN" => "Y",    // �������� ������ � ������� ���������
                                "CACHE_GROUPS" => "Y",    // ��������� ����� �������
                                "CACHE_TIME" => "36000000",    // ����� ����������� (���.)
                                "CACHE_TYPE" => "A",    // ��� �����������
                                "COUNT_ELEMENTS" => "Y",    // ���������� ���������� ��������� � �������
                                "IBLOCK_ID" => "1",    // ��������
                                "IBLOCK_TYPE" => "information",    // ��� ���������
                                "SECTION_CODE" => "",    // ��� �������
                                "SECTION_FIELDS" => array(    // ���� ��������
                                    0 => "",
                                    1 => "",
                                ),
                                "SECTION_ID" => $_REQUEST["SECTION_ID"],    // ID �������
                                "SECTION_URL" => "/novini/#CODE#",    // URL, ������� �� �������� � ���������� �������
                                "SECTION_USER_FIELDS" => array(    // �������� ��������
                                    0 => "",
                                    1 => "",
                                ),
                                "SHOW_PARENT_NAME" => "Y",    // ���������� �������� �������
                                "TOP_DEPTH" => "2",    // ������������ ������������ ������� ��������
                                "VIEW_MODE" => "LIST",    // ��� ������ �����������
                            ),
                                false
                            ); ?>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section> <br><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>