<?php

/**
 * Base decorator
 * 
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
abstract class fpPaymentDecoratorBase
{
  
  /**
   * Decorated object
   *
   * @var object
   */
  protected $object = null;
  
  /**
   * Magic method
   *
   * @param string $method
   * @param array $params
   * 
   * @throws sfException
   *
   * @return mixed
   */
  public function __call($method, $params)
  {
    if (!method_exists($this->object, $method)) {
      throw new sfException("Called '{$method}' method does not exist in " . get_class($this));
    }
    $return = call_user_func_array(array($this->object, $method), $params);
    return $return;
  }
}