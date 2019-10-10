<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $APPLICATION;

if(
    !CModule::IncludeModule("sale")
    || !CModule::IncludeModule("catalog")
    || !CModule::IncludeModule("iblock")
) return;

if($_POST['AJAX'] == 'Y'){
    $OFFER_ID = intval($_POST['OFFER_ID']);
    //$PROPS = $_POST['TREE_PROPS'];
    $PROPS['SALON'] = $_POST['SALON'];

    $item = Add2BasketByProductID(
        $OFFER_ID,
        1,
        array(),
        $PROPS
    );
    if($item){
        $result = array(
            'STATUS' => 'OK',
            'MESSAGE' => ''
        );
    }else{
        $message = 'При добавлении товара в корзину произошла ошибка';
        if ($ex = $APPLICATION->GetException())
            $message = $ex->GetString();

        $result = array(
            'STATUS' => 'ERROR',
            'MESSAGE' => $message
        );
    }
}else{
    $result = array(
        'STATUS' => 'ERROR',
        'MESSAGE' => 'При добавлении товара в корзину произошла ошибка'
    );
}
echo json_encode($result, JSON_UNESCAPED_UNICODE);
