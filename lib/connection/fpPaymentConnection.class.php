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
 * @method string sendPostRequest()
 * @method string sendGetRequest()
 * @method fpPaymentConnectionBase addTextError()
 * @method fpPaymentConnectionBase setHeader()
 * @method bool hasErrors()
 * @method array getErrors()
 * @method mixed getResponse()
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
      $this->object = new fpPaymentConnectionStream($url);
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

