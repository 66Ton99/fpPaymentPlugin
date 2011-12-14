<?php

/**
 * Test null object
 * 
 * @package    fpPayment
 * @subpackage Base
 * @author		 Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentTestNullObject
{
  
  /**
   * Call self
   *
   * @param string $method
   * @param mixed $params
   *
   * @return testNullObject
   */
  public function __call($method, $params)
  {
    return $this;
  }
}