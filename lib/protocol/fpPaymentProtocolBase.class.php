<?php

/**
 * Base protocol 
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
abstract class fpPaymentProtocolBase
{
  
  /**
   * Apply function to keys and values of given array
   *
   * @param array $arr
   * @param string $fn
   *
   * @return array
   */
  protected function applyFnToKeysAndValuesOfArray($arr, $fn)
  {
    $return = array();
    foreach ($arr as $key => $val) {
      if (is_array($val)) {
        $return[$fn($key)] = $this->applyFnToKeysAndValuesOfArray($val, $fn);
      } else {
        $return[$fn($key)] = $fn($val);
      }
    }
    return $return;
  }
}