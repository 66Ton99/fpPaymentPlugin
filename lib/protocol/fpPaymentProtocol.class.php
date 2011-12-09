<?php

/**
 * Protocol decorator
 * 
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 * 
 * @property $object fpPaymentProtocolBase
 * 
 * @method ? ?
 */
class fpPaymentProtocol extends fpPaymentDecoratorBase
{

  /**
   * Constructor
   * 
   * @param fpPaymentProtocolBase $object
   */
  function __construct(fpPaymentProtocolBase $object)
  {
    $this->object = $object;
  }
  
  /**
   * (non-PHPdoc)
   * @see fpPaymentDecoratorBase::__call()
   */
  public function __call($method, $params)
  {
    $return = parent::__call($method, $params);
    if ($return instanceof fpPaymentProtocolBase) {
      return $this;
    }
    return $return;
  }
}