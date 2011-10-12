<?php

/**
 * PluginfpPaymentOrderItemTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
abstract class PluginfpPaymentOrderItemTable extends Doctrine_Table
{
  
  const MODEL_NAME = 'fpPaymentOrderItem';

  /**
   * Returns an instance of this class.
   *
   * @return object PluginfpPaymentOrderItemTable
   */
  public static function getInstance()
  {
    return Doctrine_Core::getTable(self::MODEL_NAME);
  }
  
  /**
   * Eavent hendler. Save cart items to order items
   *
   * @param sfEvent $event - Key: context
   *
   * @return void
   */
  public function saveCartItemsToOrderItems(sfEvent $event)
  {
    /* @var $context fpPaymentContext */
    $context = $event['context'];
    $orderId = $context->getOrderModel()->getId();
    $items = $context->getCart()->getHolder()->getAll();
    $class = self::MODEL_NAME;
    $colums = array_keys($this->getColumns());
    /* @var $item fpPaymentCart */
    foreach ($items as $item) {
      /* @var $model fpPaymentOrderItem */
      $model = new $class();
      $params = array_merge($item->toArray(), $item->getProduct()->toArray());
      $params['oreder_id'] = $orderId;
      unset($params['id']);
      foreach ($params as $key => $val) {
        if (!in_array($key, $colums)) {
          unset($params[$key]);
        }
      }
      $model->setArray($params);
      if ($item->getProduct()->getTable()->hasTemplate('fpPaymentTaxable')) {
        $model->setTax($item->getProduct()->getTaxValue($item->getQuantity()));
      }
      $model->save();
    }
  }
  
}