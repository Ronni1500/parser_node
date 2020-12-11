<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
$iblock_catalog = 39;

CModule::IncludeModule("catalog");

$list = json_decode(file_get_contents('data.json'));
$id = $_POST['id'];
// die();
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




 $rsSect = new CIBlockSection;
 $arFields = array(
     "ACTIVE"    => "Y",
     "IBLOCK_ID" => $iblock_catalog,
     "NAME"      => $cart->title,   
     "CODE" => CUtil::translit($cart->title, "ru",$params),       
     "PICTURE" => CFile::MakeFileArray($cart->img),
     "IBLOCK_SECTION_ID" => 501,
 );
 if(!$idS = $rsSect->Add($arFields)) {
     $arResult['message'] = "Section Error:" . $rsSect->LAST_ERROR;
 } else{
 	foreach ($cart->items as $element) {
		$el = new CIBlockElement;
		$PROP = array();
		foreach ($element->articul as $key => $value) {
			$PROP[130][] = [
				'VALUE' => $value->name,
				'DESCRIPTION' => $value->value
			];
		}
		foreach ($element->charact as $key => $value) {
			$PROP[129][] = [
				'VALUE' => $value->name,
				'DESCRIPTION' => $value->value
			];
		}
		$fields = [
			"IBLOCK_SECTION_ID" => $idS,
			"IBLOCK_ID"      => $iblock_catalog,
			"NAME"           => $element->name,
			"ACTIVE"         => "Y",            // активен		
			"CODE" => CUtil::translit($element->name, "ru",$params),
			'PREVIEW_PICTURE' => CFile::MakeFileArray($element->img),
			'DETAIL_PICTURE' => CFile::MakeFileArray($element->img),
			'DETAIL_TEXT' => $element->desc,
			"DATE_ACTIVE_FROM" => ConvertTimeStamp(time(), "SHORT"),
			'PROPERTY_VALUES' => $PROP,
		];


		if($PRODUCT_ID = $el->Add($fields)){
			$arResult['message'] = 'Section: ' . $idS ; 
		}
		else
			$arResult['message'] += "Element Error: ".$cart->name. " - ".$el->LAST_ERROR; 		
 	}

 }



// Загрузить один элемент
// echo json_encode(array('id' => false, 'message' => $arResult['message']));
// die;

if(($id + 1) == count($list)){
	echo json_encode(array('id' => false, 'message' => 'The end'));
	die;
}

$arResult['id'] = ($id+1);
$arResult['count'] = count($list);
echo json_encode($arResult);
die;

?>