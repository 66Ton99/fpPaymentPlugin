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
    $class = self::MODEL_NAME;
    /* @var $context fpPaymentContext */
    $context = $event['context'];
    $user = $context->getUser();
    if (($order = $context->getOrderModel()) &&
        is_object($order) &&
        $class::STATUTS_NEW != $order->getStatus())
    {
      $order = null;
    }
    if (empty($order)) {
      $order = new $class();
      $order->setCustomerId($user->getId())
        ->setStatus($class::STATUTS_NEW)
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