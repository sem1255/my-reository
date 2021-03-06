<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("crm"))
	return;

global $USER;
$CCrmPerms = new CCrmPerms($USER->GetID());
$userPermissions = CCrmPerms::GetCurrentUserPermissions();
$arSupportedTypes = array(); // all entity types are defined in settings
$arParams['ENTITY_TYPE'] = array(); // only entity types are allowed for current user
$arSettings = $arParams['arUserField']['SETTINGS'];
if (isset($arSettings['LEAD']) && $arSettings['LEAD'] === 'Y')
{
	$arSupportedTypes[] = CCrmOwnerType::LeadName;
	if(CCrmLead::CheckReadPermission(0, $userPermissions))
	{
		$arParams['ENTITY_TYPE'][] = CCrmOwnerType::LeadName;
	}
}
if (isset($arSettings['CONTACT']) && $arSettings['CONTACT'] === 'Y')
{
	$arSupportedTypes[] = 'CONTACT';
	if(CCrmContact::CheckReadPermission(0, $userPermissions))
	{
		$arParams['ENTITY_TYPE'][] = CCrmOwnerType::ContactName;
	}
}
if (isset($arSettings['COMPANY']) && $arSettings['COMPANY'] === 'Y')
{
	$arSupportedTypes[] = 'COMPANY';
	if(CCrmCompany::CheckReadPermission(0, $userPermissions))
	{
		$arParams['ENTITY_TYPE'][] = CCrmOwnerType::CompanyName;
	}
}
if (isset($arSettings['DEAL']) && $arSettings['DEAL'] === 'Y')
{
	$arSupportedTypes[] = 'DEAL';
	if(CCrmDeal::CheckReadPermission(0, $userPermissions))
	{
		$arParams['ENTITY_TYPE'][] = CCrmOwnerType::DealName;
	}
}
if (isset($arSettings['QUOTE']) && $arSettings['QUOTE'] === 'Y')
{
	$arSupportedTypes[] = CCrmOwnerType::QuoteName;
	if(CCrmQuote::CheckReadPermission(0, $userPermissions))
	{
		$arParams['ENTITY_TYPE'][] = CCrmOwnerType::DealName;
	}
}
if (isset($arSettings['PRODUCT']) && $arSettings['PRODUCT'] === 'Y')
{
	$arSupportedTypes[] = 'PRODUCT';
	if(CCrmProduct::CheckReadPermission())
	{
		$arParams['ENTITY_TYPE'][] = 'PRODUCT';
	}
}

$arResult['PREFIX'] = count($arSupportedTypes) > 1 ? 'Y' : 'N';
if(!empty($arParams['usePrefix']))
	$arResult['PREFIX'] = 'Y';

$arResult['MULTIPLE'] = $arParams['arUserField']['MULTIPLE'];
if (!is_array($arResult['VALUE']))
	$arResult['VALUE'] = explode(';', $arResult['VALUE']);
else
{
	$ar = array();
	foreach ($arResult['VALUE'] as $value)
		foreach(explode(';', $value) as $val)
			if (!empty($val))
				$ar[$val] = $val;
	$arResult['VALUE'] = $ar;
}

$arResult['SELECTED'] = array();
foreach ($arResult['VALUE'] as $key => $value)
{
	if (empty($value))
	{
		continue;
	}

	if($arResult['PREFIX'] === 'Y')
	{
		$arResult['SELECTED'][$value] = $value;
	}
	else
	{
		// Try to get raw entity ID
		$ary = explode('_', $value);
		if(count($ary) > 1)
		{
			$value = $ary[1];
		}

		$arResult['SELECTED'][$value] = $value;
	}
}

$arResult['ELEMENT'] = array();
$arResult['ENTITY_TYPE'] = array();
// last 50 entity
if (in_array('LEAD', $arParams['ENTITY_TYPE'], true))
{
	$hasNameFormatter = method_exists("CCrmLead", "PrepareFormattedName");
	$arResult['ENTITY_TYPE'][] = 'lead';
	$obRes = CCrmLead::GetListEx(
		array('ID' => 'DESC'),
		array(),
		false,
		array('nTopCount' => 50),
		$hasNameFormatter
			? array('ID', 'TITLE', 'HONORIFIC', 'NAME', 'SECOND_NAME', 'LAST_NAME')
			: array('ID', 'TITLE', 'FULL_NAME')
	);
	while ($arRes = $obRes->Fetch())
	{
		$arRes['SID'] = $arResult['PREFIX'] == 'Y'? 'L_'.$arRes['ID']: $arRes['ID'];
		if (isset($arResult['SELECTED'][$arRes['SID']]))
		{
			unset($arResult['SELECTED'][$arRes['SID']]);
			$sSelected = 'Y';
		}
		else
		{
			if(!empty($arParams['usePrefix']) && isset($arResult['SELECTED'][$arRes['ID']]))
			{
				unset($arResult['SELECTED'][$arRes['ID']]);
				$sSelected = 'Y';
			}
			else
			{
				$sSelected = 'N';
			}
		}

		if($hasNameFormatter)
		{
			$description = CCrmLead::PrepareFormattedName(
				array(
					'HONORIFIC' => isset($arRes['HONORIFIC']) ? $arRes['HONORIFIC'] : '',
					'NAME' => isset($arRes['NAME']) ? $arRes['NAME'] : '',
					'SECOND_NAME' => isset($arRes['SECOND_NAME']) ? $arRes['SECOND_NAME'] : '',
					'LAST_NAME' => isset($arRes['LAST_NAME']) ? $arRes['LAST_NAME'] : ''
				)
			);
		}
		else
		{
			$description = isset($arRes['FULL_NAME']) ? $arRes['FULL_NAME'] : '';
		}

		$arResult['ELEMENT'][] = Array(
			'title' => (str_replace(array(';', ','), ' ', $arRes['TITLE'])),
			'desc' => $description,
			'id' => $arRes['SID'],
			'url' => CComponentEngine::MakePathFromTemplate(COption::GetOptionString('crm', 'path_to_lead_show'),
				array(
					'lead_id' => $arRes['ID']
				)
			),
			'type'  => 'lead',
			'selected' => $sSelected
		);
	}
}
if (in_array('CONTACT', $arParams['ENTITY_TYPE'], true))
{
	$hasNameFormatter = method_exists("CCrmContact", "PrepareFormattedName");
	$arResult['ENTITY_TYPE'][] = 'contact';
	$obRes = CCrmContact::GetListEx(
		array('ID' => 'DESC'),
		array(),
		false,
		array('nTopCount' => 50),
		$hasNameFormatter
			? array('ID', 'HONORIFIC', 'NAME', 'SECOND_NAME', 'LAST_NAME', 'COMPANY_TITLE', 'PHOTO')
			: array('ID', 'FULL_NAME', 'COMPANY_TITLE', 'PHOTO')
	);
	while ($arRes = $obRes->Fetch())
	{
		$imageUrl = '';
		if (isset($arRes['PHOTO']) && $arRes['PHOTO'] > 0)
		{
			$arImg = CFile::ResizeImageGet($arRes['PHOTO'], array('width' => 25, 'height' => 25), BX_RESIZE_IMAGE_EXACT);
			if(is_array($arImg) && isset($arImg['src']))
			{
				$imageUrl = $arImg['src'];
			}
		}

		$arRes['SID'] = $arResult['PREFIX'] == 'Y'? 'C_'.$arRes['ID']: $arRes['ID'];
		if (isset($arResult['SELECTED'][$arRes['SID']]))
		{
			unset($arResult['SELECTED'][$arRes['SID']]);
			$sSelected = 'Y';
		}
		else
		{
			if(!empty($arParams['usePrefix']) && isset($arResult['SELECTED'][$arRes['ID']]))
			{
				unset($arResult['SELECTED'][$arRes['ID']]);
				$sSelected = 'Y';
			}
			else
			{
				$sSelected = 'N';
			}
		}

		if($hasNameFormatter)
		{
			$title = CCrmContact::PrepareFormattedName(
				array(
					'HONORIFIC' => isset($arRes['HONORIFIC']) ? $arRes['HONORIFIC'] : '',
					'NAME' => isset($arRes['NAME']) ? $arRes['NAME'] : '',
					'SECOND_NAME' => isset($arRes['SECOND_NAME']) ? $arRes['SECOND_NAME'] : '',
					'LAST_NAME' => isset($arRes['LAST_NAME']) ? $arRes['LAST_NAME'] : ''
				)
			);
		}
		else
		{
			$title = isset($arRes['FULL_NAME']) ? $arRes['FULL_NAME'] : '';
		}

		$arResult['ELEMENT'][] = Array(
			'title' => $title,
			'desc'  => empty($arRes['COMPANY_TITLE']) ? '' : $arRes['COMPANY_TITLE'],
			'id' => $arRes['SID'],
			'url' => CComponentEngine::MakePathFromTemplate(COption::GetOptionString('crm', 'path_to_contact_show'),
				array(
					'contact_id' => $arRes['ID']
				)
			),
			'image' => $imageUrl,
			'type'  => 'contact',
			'selected' => $sSelected
		);
	}
}
if (in_array('COMPANY', $arParams['ENTITY_TYPE'], true))
{
	$arResult['ENTITY_TYPE'][] = 'company';

	$arCompanyTypeList = CCrmStatus::GetStatusListEx('COMPANY_TYPE');
	$arCompanyIndustryList = CCrmStatus::GetStatusListEx('INDUSTRY');
	$arSelect = array('ID', 'TITLE', 'COMPANY_TYPE', 'INDUSTRY',  'LOGO');
	$obRes = CCrmCompany::GetList(array('ID' => 'DESC'), Array(), $arSelect, 50);
	while ($arRes = $obRes->Fetch())
	{
		$imageUrl = '';
		if (isset($arRes['LOGO']) && $arRes['LOGO'] > 0)
		{
			$arImg = CFile::ResizeImageGet($arRes['LOGO'], array('width' => 25, 'height' => 25), BX_RESIZE_IMAGE_EXACT);
			if(is_array($arImg) && isset($arImg['src']))
			{
				$imageUrl = $arImg['src'];
			}
		}

		$arRes['SID'] = $arResult['PREFIX'] == 'Y'? 'CO_'.$arRes['ID']: $arRes['ID'];
		if (isset($arResult['SELECTED'][$arRes['SID']]))
		{
			unset($arResult['SELECTED'][$arRes['SID']]);
			$sSelected = 'Y';
		}
		else
		{
			if(!empty($arParams['usePrefix']) && isset($arResult['SELECTED'][$arRes['ID']]))
			{
				unset($arResult['SELECTED'][$arRes['ID']]);
				$sSelected = 'Y';
			}
			else
			{
				$sSelected = 'N';
			}
		}

		$arDesc = Array();
		if (isset($arCompanyTypeList[$arRes['COMPANY_TYPE']]))
			$arDesc[] = $arCompanyTypeList[$arRes['COMPANY_TYPE']];
		if (isset($arCompanyIndustryList[$arRes['INDUSTRY']]))
			$arDesc[] = $arCompanyIndustryList[$arRes['INDUSTRY']];


		$arResult['ELEMENT'][] = Array(
			'title' => (str_replace(array(';', ','), ' ', $arRes['TITLE'])),
			'desc' => implode(', ', $arDesc),
			'id' => $arRes['SID'],
			'url' => CComponentEngine::MakePathFromTemplate(COption::GetOptionString('crm', 'path_to_company_show'),
				array(
					'company_id' => $arRes['ID']
				)
			),
			'image' => $imageUrl,
			'type'  => 'company',
			'selected' => $sSelected
		);
	}
}
if (in_array('DEAL', $arParams['ENTITY_TYPE'], true))
{
	$arResult['ENTITY_TYPE'][] = 'deal';

	$arSelect = array('ID', 'TITLE', 'STAGE_ID', 'COMPANY_TITLE', 'CONTACT_FULL_NAME');
	$obRes = CCrmDeal::GetList(array('ID' => 'DESC'), Array(), $arSelect, 50);
	while ($arRes = $obRes->Fetch())
	{
		$arRes['SID'] = $arResult['PREFIX'] == 'Y'? 'D_'.$arRes['ID']: $arRes['ID'];
		if (isset($arResult['SELECTED'][$arRes['SID']]))
		{
			unset($arResult['SELECTED'][$arRes['SID']]);
			$sSelected = 'Y';
		}
		else
		{
			if(!empty($arParams['usePrefix']) && isset($arResult['SELECTED'][$arRes['ID']]))
			{
				unset($arResult['SELECTED'][$arRes['ID']]);
				$sSelected = 'Y';
			}
			else
			{
				$sSelected = 'N';
			}
		}

		$clientTitle = (!empty($arRes['COMPANY_TITLE'])) ? $arRes['COMPANY_TITLE'] : '';
		$clientTitle .= (($clientTitle !== '' && !empty($arRes['CONTACT_FULL_NAME'])) ? ', ' : '').$arRes['CONTACT_FULL_NAME'];

		$arResult['ELEMENT'][] = Array(
			'title' => (str_replace(array(';', ','), ' ', $arRes['TITLE'])),
			'desc' => $clientTitle,
			'id' => $arRes['SID'],
			'url' => CComponentEngine::MakePathFromTemplate(COption::GetOptionString('crm', 'path_to_deal_show'),
				array(
					'deal_id' => $arRes['ID']
				)
			),
			'type'  => 'deal',
			'selected' => $sSelected
		);
	}
}
if (in_array('QUOTE', $arParams['ENTITY_TYPE'], true))
{
	$arResult['ENTITY_TYPE'][] = 'quote';

	$arSelect = array('ID', 'TITLE', 'STAGE_ID', 'COMPANY_TITLE', 'CONTACT_FULL_NAME');
	$obRes = CCrmQuote::GetList(array('ID' => 'DESC'), Array(), false, array('nTopCount' => 50), $arSelect);
	while ($arRes = $obRes->Fetch())
	{
		$arRes['SID'] = $arResult['PREFIX'] == 'Y'? 'Q_'.$arRes['ID']: $arRes['ID'];
		if (isset($arResult['SELECTED'][$arRes['SID']]))
		{
			unset($arResult['SELECTED'][$arRes['SID']]);
			$sSelected = 'Y';
		}
		else
		{
			if(!empty($arParams['usePrefix']) && isset($arResult['SELECTED'][$arRes['ID']]))
			{
				unset($arResult['SELECTED'][$arRes['ID']]);
				$sSelected = 'Y';
			}
			else
			{
				$sSelected = 'N';
			}
		}

		$clientTitle = (!empty($arRes['COMPANY_TITLE'])) ? $arRes['COMPANY_TITLE'] : '';
		$clientTitle .= (($clientTitle !== '' && !empty($arRes['CONTACT_FULL_NAME'])) ? ', ' : '').$arRes['CONTACT_FULL_NAME'];

		$arResult['ELEMENT'][] = Array(
			'title' => (str_replace(array(';', ','), ' ', $arRes['TITLE'])),
			'desc' => $clientTitle,
			'id' => $arRes['SID'],
			'url' => CComponentEngine::MakePathFromTemplate(COption::GetOptionString('crm', 'path_to_quote_show'),
				array(
					'quote_id' => $arRes['ID']
				)
			),
			'type'  => 'quote',
			'selected' => $sSelected
		);
	}
}
if (in_array('PRODUCT', $arParams['ENTITY_TYPE'], true))
{
	$arResult['ENTITY_TYPE'][] = 'product';

	$arSelect = array('ID', 'NAME', 'PRICE', 'CURRENCY_ID');
	$arPricesSelect = $arVatsSelect = array();
	$arSelect = CCrmProduct::DistributeProductSelect($arSelect, $arPricesSelect, $arVatsSelect);
	$obRes = CCrmProduct::GetList(array('ID' => 'DESC'), array(), $arSelect, 50);

	$arProducts = $arProductId = array();
	while ($arRes = $obRes->Fetch())
	{
		foreach ($arPricesSelect as $fieldName)
			$arRes[$fieldName] = null;
		foreach ($arVatsSelect as $fieldName)
			$arRes[$fieldName] = null;
		$arProductId[] = $arRes['ID'];
		$arProducts[$arRes['ID']] = $arRes;
	}
	CCrmProduct::ObtainPricesVats($arProducts, $arProductId, $arPricesSelect, $arVatsSelect);
	unset($arProductId, $arPricesSelect, $arVatsSelect);

	foreach ($arProducts as $arRes)
	{
		$arRes['SID'] = $arResult['PREFIX'] == 'Y'? 'PROD_'.$arRes['ID']: $arRes['ID'];
		if (isset($arResult['SELECTED'][$arRes['SID']]))
		{
			unset($arResult['SELECTED'][$arRes['SID']]);
			$sSelected = 'Y';
		}
		else
		{
			if(!empty($arParams['usePrefix']) && isset($arResult['SELECTED'][$arRes['ID']]))
			{
				unset($arResult['SELECTED'][$arRes['ID']]);
				$sSelected = 'Y';
			}
			else
			{
				$sSelected = 'N';
			}
		}

		$arResult['ELEMENT'][] = array(
			'title' => $arRes['NAME'],
			'desc' => CCrmProduct::FormatPrice($arRes),
			'id' => $arRes['SID'],
			'url' => CComponentEngine::MakePathFromTemplate(COption::GetOptionString('crm', 'path_to_product_show'),
				array(
					'product_id' => $arRes['ID']
				)
			),
			'type'  => 'product',
			'selected' => $sSelected
		);
	}
	unset($arProducts);
}

if (!empty($arResult['SELECTED']))
{
	foreach ($arResult['SELECTED'] as $value)
	{
		if (is_numeric($value))
			$arSelected[$arParams['ENTITY_TYPE'][0]][] = $value;
		else
		{
			$ar = explode('_', $value);
			$arSelected[CUserTypeCrm::GetLongEntityType($ar[0])][] = intval($ar[1]);
		}
	}

	if ($arSettings['LEAD'] == 'Y'
		&& isset($arSelected['LEAD']) && !empty($arSelected['LEAD']))
	{
		$hasNameFormatter = method_exists("CCrmLead", "PrepareFormattedName");
		$obRes = CCrmLead::GetListEx(
			array('ID' => 'DESC'),
			array('=ID' => $arSelected['LEAD']),
			false,
			false,
			$hasNameFormatter
				? array('ID', 'TITLE', 'HONORIFIC', 'NAME', 'SECOND_NAME', 'LAST_NAME')
				: array('ID', 'TITLE', 'FULL_NAME')
		);
		$ar = Array();
		while ($arRes = $obRes->Fetch())
		{
			$arRes['SID'] = $arResult['PREFIX'] == 'Y'? 'L_'.$arRes['ID']: $arRes['ID'];
			if (isset($arResult['SELECTED'][$arRes['SID']]))
			{
				unset($arResult['SELECTED'][$arRes['SID']]);
				$sSelected = 'Y';
			}
			else
			{
				if(!empty($arParams['usePrefix']) && isset($arResult['SELECTED'][$arRes['ID']]))
				{
					unset($arResult['SELECTED'][$arRes['ID']]);
					$sSelected = 'Y';
				}
				else
				{
					$sSelected = 'N';
				}
			}

			if($hasNameFormatter)
			{
				$description = CCrmLead::PrepareFormattedName(
					array(
						'HONORIFIC' => isset($arRes['HONORIFIC']) ? $arRes['HONORIFIC'] : '',
						'NAME' => isset($arRes['NAME']) ? $arRes['NAME'] : '',
						'SECOND_NAME' => isset($arRes['SECOND_NAME']) ? $arRes['SECOND_NAME'] : '',
						'LAST_NAME' => isset($arRes['LAST_NAME']) ? $arRes['LAST_NAME'] : ''
					)
				);
			}
			else
			{
				$description = isset($arRes['FULL_NAME']) ? $arRes['FULL_NAME'] : '';
			}

			$ar[] = Array(
				'title' => (str_replace(array(';', ','), ' ', $arRes['TITLE'])),
				'desc' => $description,
				'id' => $arRes['SID'],
				'url' => CComponentEngine::MakePathFromTemplate(COption::GetOptionString('crm', 'path_to_lead_show'),
					array(
						'lead_id' => $arRes['ID']
					)
				),
				'type'  => 'lead',
				'selected' => $sSelected
			);
		}
		$arResult['ELEMENT'] = array_merge($ar, $arResult['ELEMENT']);
	}
	if ($arSettings['CONTACT'] == 'Y'
		&& isset($arSelected['CONTACT']) && !empty($arSelected['CONTACT']))
	{
		$hasNameFormatter = method_exists("CCrmContact", "PrepareFormattedName");
		$obRes = CCrmContact::GetListEx(
			array('ID' => 'DESC'),
			array('=ID' => $arSelected['CONTACT']),
			false,
			false,
			$hasNameFormatter
				? array('ID', 'HONORIFIC', 'NAME', 'SECOND_NAME', 'LAST_NAME', 'COMPANY_TITLE', 'PHOTO')
				: array('ID', 'FULL_NAME', 'COMPANY_TITLE', 'PHOTO')
		);
		$ar = Array();
		while ($arRes = $obRes->Fetch())
		{
			$imageUrl = '';
			if (isset($arRes['PHOTO']) && $arRes['PHOTO'] > 0)
			{
				$arImg = CFile::ResizeImageGet($arRes['PHOTO'], array('width' => 25, 'height' => 25), BX_RESIZE_IMAGE_EXACT);
				if(is_array($arImg) && isset($arImg['src']))
				{
					$imageUrl = $arImg['src'];
				}
			}

			$arRes['SID'] = $arResult['PREFIX'] == 'Y'? 'C_'.$arRes['ID']: $arRes['ID'];
			if (isset($arResult['SELECTED'][$arRes['SID']]))
			{
				unset($arResult['SELECTED'][$arRes['SID']]);
				$sSelected = 'Y';
			}
			else
			{
				if(!empty($arParams['usePrefix']) && isset($arResult['SELECTED'][$arRes['ID']]))
				{
					unset($arResult['SELECTED'][$arRes['ID']]);
					$sSelected = 'Y';
				}
				else
				{
					$sSelected = 'N';
				}
			}

			if($hasNameFormatter)
			{
				$title = CCrmContact::PrepareFormattedName(
					array(
						'HONORIFIC' => isset($arRes['HONORIFIC']) ? $arRes['HONORIFIC'] : '',
						'NAME' => isset($arRes['NAME']) ? $arRes['NAME'] : '',
						'SECOND_NAME' => isset($arRes['SECOND_NAME']) ? $arRes['SECOND_NAME'] : '',
						'LAST_NAME' => isset($arRes['LAST_NAME']) ? $arRes['LAST_NAME'] : ''
					)
				);
			}
			else
			{
				$title = isset($arRes['FULL_NAME']) ? $arRes['FULL_NAME'] : '';
			}

			$ar[] = Array(
				'title' => $title,
				'desc'  => empty($arRes['COMPANY_TITLE']) ? '': $arRes['COMPANY_TITLE'],
				'id' => $arRes['SID'],
				'url' => CComponentEngine::MakePathFromTemplate(
					COption::GetOptionString('crm', 'path_to_contact_show'),
					array('contact_id' => $arRes['ID'])
				),
				'image' => $imageUrl,
				'type'  => 'contact',
				'selected' => $sSelected
			);
		}
		$arResult['ELEMENT'] = array_merge($ar, $arResult['ELEMENT']);
	}
	if ($arSettings['COMPANY'] == 'Y'
		&& isset($arSelected['COMPANY']) && !empty($arSelected['COMPANY']))
	{
		$arCompanyTypeList = CCrmStatus::GetStatusListEx('COMPANY_TYPE');
		$arCompanyIndustryList = CCrmStatus::GetStatusListEx('INDUSTRY');
		$arSelect = array('ID', 'TITLE', 'COMPANY_TYPE', 'INDUSTRY',  'LOGO');
		$obRes = CCrmCompany::GetList(array('ID' => 'DESC'), Array('ID' => $arSelected['COMPANY']), $arSelect);
		$ar = Array();
		while ($arRes = $obRes->Fetch())
		{
			$imageUrl = '';
			if (isset($arRes['LOGO']) && $arRes['LOGO'] > 0)
			{
				$arImg = CFile::ResizeImageGet($arRes['LOGO'], array('width' => 25, 'height' => 25), BX_RESIZE_IMAGE_EXACT);
				if(is_array($arImg) && isset($arImg['src']))
				{
					$imageUrl = $arImg['src'];
				}
			}

			$arRes['SID'] = $arResult['PREFIX'] == 'Y'? 'CO_'.$arRes['ID']: $arRes['ID'];
			if (isset($arResult['SELECTED'][$arRes['SID']]))
			{
				unset($arResult['SELECTED'][$arRes['SID']]);
				$sSelected = 'Y';
			}
			else
			{
				if(!empty($arParams['usePrefix']) && isset($arResult['SELECTED'][$arRes['ID']]))
				{
					unset($arResult['SELECTED'][$arRes['ID']]);
					$sSelected = 'Y';
				}
				else
				{
					$sSelected = 'N';
				}
			}


			$arDesc = Array();
			if (isset($arCompanyTypeList[$arRes['COMPANY_TYPE']]))
				$arDesc[] = $arCompanyTypeList[$arRes['COMPANY_TYPE']];
			if (isset($arCompanyIndustryList[$arRes['INDUSTRY']]))
				$arDesc[] = $arCompanyIndustryList[$arRes['INDUSTRY']];

			$ar[] = Array(
				'title' => (str_replace(array(';', ','), ' ', $arRes['TITLE'])),
				'desc' => implode(', ', $arDesc),
				'id' => $arRes['SID'],
				'url' => CComponentEngine::MakePathFromTemplate(COption::GetOptionString('crm', 'path_to_company_show'),
					array(
						'company_id' => $arRes['ID']
					)
				),
				'image' => $imageUrl,
				'type'  => 'company',
				'selected' => $sSelected
			);
		}
		$arResult['ELEMENT'] = array_merge($ar, $arResult['ELEMENT']);
	}
	if ($arSettings['DEAL'] == 'Y'
	&& isset($arSelected['DEAL']) && !empty($arSelected['DEAL']))
	{
		$arSelect = array('ID', 'TITLE', 'STAGE_ID', 'COMPANY_TITLE', 'CONTACT_FULL_NAME');
		$ar = Array();
		$obRes = CCrmDeal::GetList(array('ID' => 'DESC'), Array('ID' => $arSelected['DEAL']), $arSelect);
		while ($arRes = $obRes->Fetch())
		{
			$arRes['SID'] = $arResult['PREFIX'] == 'Y'? 'D_'.$arRes['ID']: $arRes['ID'];
			if (isset($arResult['SELECTED'][$arRes['SID']]))
			{
				unset($arResult['SELECTED'][$arRes['SID']]);
				$sSelected = 'Y';
			}
			else
			{
				if(!empty($arParams['usePrefix']) && isset($arResult['SELECTED'][$arRes['ID']]))
				{
					unset($arResult['SELECTED'][$arRes['ID']]);
					$sSelected = 'Y';
				}
				else
				{
					$sSelected = 'N';
				}
			}

			$clientTitle = (!empty($arRes['COMPANY_TITLE'])) ? $arRes['COMPANY_TITLE'] : '';
			$clientTitle .= (($clientTitle !== '' && !empty($arRes['CONTACT_FULL_NAME'])) ? ', ' : '').$arRes['CONTACT_FULL_NAME'];

			$ar[] = Array(
				'title' => (str_replace(array(';', ','), ' ', $arRes['TITLE'])),
				'desc' => $clientTitle,
				'id' => $arRes['SID'],
				'url' => CComponentEngine::MakePathFromTemplate(COption::GetOptionString('crm', 'path_to_deal_show'),
					array(
						'deal_id' => $arRes['ID']
					)
				),
				'type'  => 'deal',
				'selected' => $sSelected
			);
		}
		$arResult['ELEMENT'] = array_merge($ar, $arResult['ELEMENT']);
	}
	if ($arSettings['QUOTE'] == 'Y'
		&& isset($arSelected['QUOTE']) && !empty($arSelected['QUOTE']))
	{
		$arSelect = array('ID', 'TITLE', 'STAGE_ID', 'COMPANY_TITLE', 'CONTACT_FULL_NAME');
		$ar = Array();
		$obRes = CCrmQuote::GetList(array('ID' => 'DESC'), Array('ID' => $arSelected['QUOTE']), false, false, $arSelect);
		while ($arRes = $obRes->Fetch())
		{
			$arRes['SID'] = $arResult['PREFIX'] == 'Y'? 'Q_'.$arRes['ID']: $arRes['ID'];
			if (isset($arResult['SELECTED'][$arRes['SID']]))
			{
				unset($arResult['SELECTED'][$arRes['SID']]);
				$sSelected = 'Y';
			}
			else
			{
				if(!empty($arParams['usePrefix']) && isset($arResult['SELECTED'][$arRes['ID']]))
				{
					unset($arResult['SELECTED'][$arRes['ID']]);
					$sSelected = 'Y';
				}
				else
				{
					$sSelected = 'N';
				}
			}

			$clientTitle = (!empty($arRes['COMPANY_TITLE'])) ? $arRes['COMPANY_TITLE'] : '';
			$clientTitle .= (($clientTitle !== '' && !empty($arRes['CONTACT_FULL_NAME'])) ? ', ' : '').$arRes['CONTACT_FULL_NAME'];

			$ar[] = Array(
				'title' => (str_replace(array(';', ','), ' ', $arRes['TITLE'])),
				'desc' => $clientTitle,
				'id' => $arRes['SID'],
				'url' => CComponentEngine::MakePathFromTemplate(COption::GetOptionString('crm', 'path_to_quote_show'),
					array(
						'quote_id' => $arRes['ID']
					)
				),
				'type'  => 'quote',
				'selected' => $sSelected
			);
		}
		$arResult['ELEMENT'] = array_merge($ar, $arResult['ELEMENT']);
	}
	if (isset($arSettings['PRODUCT'])
		&& $arSettings['PRODUCT'] == 'Y'
		&& isset($arSelected['PRODUCT'])
		&& !empty($arSelected['PRODUCT']))
	{
		$ar = array();
		$arSelect = array('ID', 'NAME', 'PRICE', 'CURRENCY_ID');
		$arPricesSelect = $arVatsSelect = array();
		$arSelect = CCrmProduct::DistributeProductSelect($arSelect, $arPricesSelect, $arVatsSelect);
		$obRes = CCrmProduct::GetList(
			array('ID' => 'DESC'),
			array('ID' => $arSelected['PRODUCT']),
			$arSelect
		);

		$arProducts = $arProductId = array();
		while ($arRes = $obRes->Fetch())
		{
			foreach ($arPricesSelect as $fieldName)
				$arRes[$fieldName] = null;
			foreach ($arVatsSelect as $fieldName)
				$arRes[$fieldName] = null;
			$arProductId[] = $arRes['ID'];
			$arProducts[$arRes['ID']] = $arRes;
		}
		CCrmProduct::ObtainPricesVats($arProducts, $arProductId, $arPricesSelect, $arVatsSelect);
		unset($arProductId, $arPricesSelect, $arVatsSelect);

		foreach ($arProducts as $arRes)
		{
			$arRes['SID'] = $arResult['PREFIX'] == 'Y'? 'D_'.$arRes['ID']: $arRes['ID'];
			if (isset($arResult['SELECTED'][$arRes['SID']]))
			{
				unset($arResult['SELECTED'][$arRes['SID']]);
				$sSelected = 'Y';
			}
			else
			{
				if(!empty($arParams['usePrefix']) && isset($arResult['SELECTED'][$arRes['ID']]))
				{
					unset($arResult['SELECTED'][$arRes['ID']]);
					$sSelected = 'Y';
				}
				else
				{
					$sSelected = 'N';
				}
			}

			$ar[] = array(
				'title' => $arRes['NAME'],
				'desc' => CCrmProduct::FormatPrice($arRes),
				'id' => $arRes['SID'],
				'url' => CComponentEngine::MakePathFromTemplate(COption::GetOptionString('crm', 'path_to_product_show'),
					array(
						'product_id' => $arRes['ID']
					)
				),
				'type'  => 'product',
				'selected' => $sSelected
			);
		}
		unset($arProducts);
		$arResult['ELEMENT'] = array_merge($ar, $arResult['ELEMENT']);
	}
}

if(!empty($arParams['createNewEntity']))
{
	if(!empty($arResult['ENTITY_TYPE']))
	{
		if(count($arResult['ENTITY_TYPE']) > 1)
		{
			$arResult['PLURAL_CREATION'] = true;
		}
		else
		{
			$arResult['PLURAL_CREATION'] = false;
			$arResult['CURRENT_ENTITY_TYPE'] = current($arResult['ENTITY_TYPE']);
		}
	}
	
	$arResult['LIST_ENTITY_CREATE_URL'] = array();
	foreach($arResult['ENTITY_TYPE'] as $entityType)
	{
		$arResult['LIST_ENTITY_CREATE_URL'][$entityType] =
			CCrmOwnerType::GetEditUrl(CCrmOwnerType::ResolveID($entityType), 0, false);
	}
	
}
?>