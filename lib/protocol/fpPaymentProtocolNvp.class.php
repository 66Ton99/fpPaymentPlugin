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
      if (is_array($value)) {
        foreach ($value as $subKey => $subValue) {
          if (is_array($subValue)) {
            foreach ($subValue as $subsKey => $subsValue) {
              $parts[] = urlencode($field . '[' . $subKey . '].' .$subsKey) . '=' . urlencode($subsValue);
            }
          } else {
            $parts[] = urlencode($field . '[' . $subKey . ']') . '=' . urlencode($subValue);
          }
        }
      } else {
        $parts[] = $field . '=' . urlencode($value);
      }
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
    $retrun = array();
    foreach (explode('&', $responseString) as $line) {
      list($key, $value) = array_map('urldecode', explode('=', $line));
      $retrun[$key] = $value;
    }
    return $retrun;
  }
}