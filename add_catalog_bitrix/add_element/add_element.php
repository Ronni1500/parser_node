<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
$iblock_catalog = 39;

CModule::IncludeModule("catalog");

$list = json_decode(file_get_contents('data.json'));
$id = $_POST['id'];

// $id = 0;

$result = array(
	'id' => 0,
	'message' => '',
	'price' => '',
);

$params = Array(
	"max_len" => "100", // обрезает символьный код до 100 символов
	"change_case" => "L", // буквы преобразуются к нижнему регистру
	"replace_space" => "-", // меняем пробелы на нижнее подчеркивание
	"replace_other" => "-", // меняем левые символы на нижнее подчеркивание
	"delete_repeat_replace" => "true", // удаляем повторяющиеся нижние подчеркивания
	"use_google" => "false", // отключаем использование google
 ); 
$cart = $list[$id];





$el = new CIBlockElement;
$PROP = array();


foreach ($cart->articul as $key => $value) {
	$PROP[130][] = [
		'VALUE' => $value->name,
		'DESCRIPTION' => $value->value
	];
}

foreach ($cart->charact as $key => $value) {
	$PROP[129][] = [
		'VALUE' => $value->name,
		'DESCRIPTION' => $value->value
	];
}
$fields = [
	"IBLOCK_SECTION_ID" => 532,          // элемент лежит в корне раздела
	"IBLOCK_ID"      => $iblock_catalog,
	"NAME"           => $cart->name,
	"ACTIVE"         => "Y",            // активен		
	"CODE" => CUtil::translit($cart->name, "ru",$params),
	'PREVIEW_PICTURE' => CFile::MakeFileArray('https://doorhan.ru/' . $cart->img),
	'DETAIL_PICTURE' => CFile::MakeFileArray('https://doorhan.ru/' . $cart->img),
	'DETAIL_TEXT' => $cart->desc,
	"DATE_ACTIVE_FROM" => ConvertTimeStamp(time(), "SHORT"),
	'PROPERTY_VALUES' => $PROP,
];


if($PRODUCT_ID = $el->Add($fields)){
	$arResult['message'] = $PRODUCT_ID; 
}
else
	$arResult['message'] = "Error: ".$cart->name. " - ".$el->LAST_ERROR;

// Загрузить один элемент
// echo json_encode(array('id' => false, 'message' => $arResult['message']));
// die;

if(($id + 1) == count($list)){
	echo json_encode(array('id' => false));
	die;
}

$arResult['id'] = ($id+1);
$arResult['count'] = count($list);
echo json_encode($arResult);
die;

?>