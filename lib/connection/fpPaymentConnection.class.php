<?php

/**
 * Connection protocol decorator
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 * 
 * @property $object fpPaymentConnectionBase
 * 
 * @method ? ?
 */
class fpPaymentConnection extends fpPaymentDecoratorBase
{
	
	/**
   * Constructor
   * 
   * @param fpPaymentConnectionBase $object
   */
  function __construct($url)
  {
    if (extension_loaded('curl')) {
      $this->object = new fpPaymentConnectionCurl($url);
    } else {
      throw new sfException('cURL extension does not loaded. Need to implement socket connection');
    }
  }
  
  /**
   * (non-PHPdoc)
   * @see fpPaymentDecoratorBase::__call()
   */
  public function __call($method, $params)
  {
    $return = parent::__call($method, $params);
    if ($return instanceof fpPaymentConnectionBase) {
      return $this;
    }
    return $return;
  }
}
