<?php

/**
 * NVP (Name-Value Pair) protocol 
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentProtocolNvp extends fpPaymentProtocolBase
{
  
  /**
   * Convert array data to string
   *
   * @param array $data
   *
   * @return string
   */
  public function fromArray($arr)
  {
    if (empty($arr) || !is_array($arr)) return '';
    $parts = array();
    foreach ($arr as $field => $value) {
      $parts[] = $field . '=' . urlencode($value);
    }
    
    return implode('&', $parts);
  }
  
  /**
   * Convert string to array
   *
   * @return array
   */
  public function toArray($responseString)
  {
    $return = $output = array();
    parse_str($responseString, $output);
    foreach ($output as $key => $val) {
      $return[urldecode($key)] = urldecode($val);
    }
    return $return;
  }
}