<?php

/**
 * Class of functions
 *
 * @package    fpPayment
 * @subpackage Base
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
  
  /**
   * Register congig to system. After 3-th level must be array
   *
   * @param strig $sectionName
   * @param array $configs
   * @param int $levels
   *
   * @return void
   */
  public static function registerConfigsToSystem($sectionName, $configs, $levels = 1)
  {
    $levels--;
    foreach ($configs as $name => $value) {
      if ($levels && is_array($value)) {
        static::registerConfigsToSystem($sectionName . '_' . $name, $value, $levels);
      } else {
        sfConfig::set($sectionName . '_' . $name, $value);
      }  
    }
  }
  
  /**
   * Recursive merge 2 or more arrays 
   *
   * @return array
   */
  public static function arrayMergeRecursive()
  {

    if (func_num_args() < 2) {
      trigger_error(__FUNCTION__ . ' needs two or more array arguments', E_USER_WARNING);
      return;
    }
    $arrays = func_get_args();
    $merged = array();
    while ($arrays) {
      $array = array_shift($arrays);
      if (!is_array($array)) {
        trigger_error(__FUNCTION__ . ' encountered a non array argument', E_USER_WARNING);
        return;
      }
      if (!$array) continue;
      foreach ($array as $key => $value) {
        if (is_string($key)) {
          if (is_array($value) && array_key_exists($key, $merged) && is_array($merged[$key])) {
            $merged[$key] = static::arrayMergeRecursive($merged[$key], $value);
          } else {
            $merged[$key] = $value;
          }
        } else {
          $merged[] = $value;
        }
      }
    }
    return $merged;
  }
}
