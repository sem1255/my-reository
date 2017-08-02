<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();
?>
<!DOCTYPE html>
<html>
<head>
    <? $APPLICATION->ShowHead(); ?>
    <title><? $APPLICATION->ShowTitle(); ?></title>
    <!--[if lte IE 8]>
    <script src="<?=SITE_TEMPLATE_PATH?>assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/css/main.css"/>
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/css/ie8.css"/><![endif]-->
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/assets/css/ie9.css"/><![endif]-->
</head>
<body>
<div id="panel">
    <? $APPLICATION->ShowPanel(); ?>
</div>
<div id="page-wrapper">

    <!-- Header -->
    <div id="header">

        <!-- Logo -->
        <h1><a href="<?=SITE_TEMPLATE_PATH?>/index.html" id="logo">Arcana <em>by HTML5 UP</em></a></h1>

        <!-- Nav -->
        <?$APPLICATION->IncludeComponent("bitrix:menu", "top", Array(
	"ALLOW_MULTI_SELECT" => "N",	// ��������� ��������� �������� ������� ������������
		"CHILD_MENU_TYPE" => "top",	// ��� ���� ��� ��������� �������
		"DELAY" => "N",	// ����������� ���������� ������� ����
		"MAX_LEVEL" => "1",	// ������� ����������� ����
		"MENU_CACHE_GET_VARS" => array(	// �������� ���������� �������
			0 => "",
		),
		"MENU_CACHE_TIME" => "3600",	// ����� ����������� (���.)
		"MENU_CACHE_TYPE" => "N",	// ��� �����������
		"MENU_CACHE_USE_GROUPS" => "Y",	// ��������� ����� �������
		"ROOT_MENU_TYPE" => "top",	// ��� ���� ��� ������� ������
		"USE_EXT" => "N",	// ���������� ����� � ������� ���� .���_����.menu_ext.php
	),
	false
);?>


    </div>
						