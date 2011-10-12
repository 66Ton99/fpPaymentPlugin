<?php

/**
 * PluginfpPaymentOrderTable
 *  
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
abstract class PluginfpPaymentOrderTable extends Doctrine_Table
{
  
  /**
   * Model name
   *
   * @var string
   */
  const MODEL_NAME = 'fpPaymentOrder';

  /**
   * Return singleton
   *
   * @return fpPaymentOrderTable
   */
  public static function getInstance() {
    return Doctrine_Core::getTable(static::MODEL_NAME);
  }
  
  /**
   * Event handler. Create order 
   *
   * @param sfEvent $event
   *
   * @return void
   */
  public function createOrder(sfEvent $event)
  {
    /* @var $context fpPaymentContext */
    $context = $event['context'];
    
    $context->getDispatcher()->notify(new sfEvent($this, 'fp_payment_order.befor_create', array(
      'context' => $context,
      'values' => $event['values']
    )));
    
    $user = $context->getCustomer();
    if (($order = $context->getOrderModel()) &&
        is_object($order) &&
        fpPaymentOrderStatusEnum::NEWONE != $order->getStatus())
    {
      $order = null;
    }
    if (empty($order)) {
      $class = static::MODEL_NAME;
      $order = new $class();
      $order->setCustomerId($user->getId())
        ->setStatus(fpPaymentOrderStatusEnum::NEWONE)
        ->setCurrency($context->getCart()->getCurrency())
        ->save();
    }
    $context->setOrderModel($order);
    
    $context->getDispatcher()->notify(new sfEvent($this, 'fp_payment_order.after_create', array(
      'context' => $context,
      'values' => $event['values']
    )));
  }
  
  /**
   * Find one order by ID and Status
   *
   * @param int $id
   * @param fpPaymentOrder $status
   *
   * @return fpPaymentOrder
   */
  public function findOneByIdAndStatus($id, $status)
  {
    return $this->createQuery('o')
      ->andWhere('o.id = ?', $id)
      ->andWhere('o.status = ?', $status)
      ->fetchOne();
  }
}