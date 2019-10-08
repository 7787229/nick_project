<?





$name='Коля';
$phone='+7-495-789-45-78';
$comment='Хочу побыстрее';

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;
use Bitrix\Main,
    Bitrix\Main\Loader,
    Bitrix\Main\Config\Option,
    Bitrix\Sale,
    Bitrix\Sale\Order,
    Bitrix\Main\Application,
    Bitrix\Sale\DiscountCouponsManager;

if (!Loader::IncludeModule('sale'))
    die();

$request = Application::getInstance()->getContext()->getRequest();
    global $USER, $APPLICATION;

    $siteId = \Bitrix\Main\Context::getCurrent()->getSite();

    $currencyCode = Option::get('sale', 'default_currency', 'RUB');

    DiscountCouponsManager::init();

    $registeredUserID = $USER->GetID();



    $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());


    if ($item = $basket->getExistsItem('catalog', 73)) {
        $item->setField('QUANTITY', $item->getQuantity() + $quantity);
    } else {
        $item = $basket->createItem('catalog', 73);
        $item->setFields(array(
            'QUANTITY' => 1,
            'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
            'LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
            'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
        ));
    }
    $basket->save();


    if($registeredUserID){
      $order = Order::create($siteId, $registeredUserID);
    } else {


      // создадим массив описывающий изображение
// находящееся в файле на сервере


$user = new CUser;
$arFields = Array(
  "NAME"              => "Саша",
  "LAST_NAME"         => "Басин",
  "EMAIL"             => "ivanov1@microsoft.com",
  "LOGIN"             => "ivan1",
  "LID"               => "ru",
  "ACTIVE"            => "Y",
  "GROUP_ID"          => array(5),
  "PASSWORD"          => "123456",
  "CONFIRM_PASSWORD"  => "123456",

);

$ID = $user->Add($arFields);


      $order = Order::create($siteId, $ID);
    }

    //

    $order->setPersonTypeId(1);
    $basket = Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), Bitrix\Main\Context::getCurrent()->getSite())->getOrderableItems();

    $order->setBasket($basket);

    /*Shipment*/
    $shipmentCollection = $order->getShipmentCollection();
    $shipment = $shipmentCollection->createItem();
    $shipment->setFields(array(
        'DELIVERY_ID' => 22,
        'DELIVERY_NAME' => 'Доставка по России',
        'CURRENCY' => $order->getCurrency()
    ));


    $shipmentItemCollection = $shipment->getShipmentItemCollection();

    foreach ($order->getBasket() as $item)
    {
        $shipmentItem = $shipmentItemCollection->createItem($item);
        $shipmentItem->setQuantity($item->getQuantity());
    }


    /*Payment*/
    $paymentCollection = $order->getPaymentCollection();
    $extPayment = $paymentCollection->createItem();
    $extPayment->setFields(array(
        'PAY_SYSTEM_ID' => 9,
        'PAY_SYSTEM_NAME' => 'Наличные',
        'SUM' => $order->getPrice()
    ));

    /**/
    $order->doFinalAction(true);

	$propertyCollection = $order->getPropertyCollection();

    $propertyCollection = $order->getPropertyCollection();


		foreach ($propertyCollection->getGroups() as $group)
		{

			foreach ($propertyCollection->getGroupProperties($group['ID']) as $property)
			{

                $p = $property->getProperty();
                if( $p["CODE"] == "CONTACT_PERSON")
                    $property->setValue("VASYA");

			}
		}



    $order->setField('CURRENCY', $currencyCode);
    $order->setField('COMMENTS', 'Заказ оформлен через АПИ. ' . $comment);
    $order->save();
    $orderId = $order->GetId();

    if($orderId > 0){
        echo "Ваш заказ оформлен";
    }
    else{
        echo "Ошибка оформления";
    }
