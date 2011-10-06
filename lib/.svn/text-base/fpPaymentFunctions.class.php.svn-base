<?php

/**
 * Class of functions
 *
 * @package    fpPayment
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentFunctions
{

	/**
   * Read config and returm object
   *
   * @param mixed $key
   * @param mixed $default
   *
   * @return class
   */
  public static function getObjFromConfig($key, $default = null)
  {
    $callback = sfConfig::get($key, $default);
    
    if (is_array($callback)) {
      if (!empty($callback['function'])) {
        $object = call_user_func_array($callback['function'], $callback['parameters']);
        if (!empty($callback['subFunctions'])) {
          foreach ((array)$callback['subFunctions'] as $fn) {
            $object = $object->$fn();
          }
        }
        return $object;
      } else {
        return call_user_func($callback);
      }
    }
    
    if (class_exists($callback)) {
      return new $callback();
    } else {
      return call_user_func($callback);
    }
  }
}
