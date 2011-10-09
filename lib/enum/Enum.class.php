<?php

/**
 * Base Enum class
 *
 * @package    Payment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
abstract class fpPaymentEnum
{
  /**
   * Titles cache
   *
   * @var array
   */
  private static $_titles = array();

  /**
   * Specific titles
   *
   * @var array
   */
  protected static $titles = array();

  /**
   * Pointer to the translator function
   *
   * @var callbeck
   */
  protected static $translator;

  public static function all()
  {
    return static::getRC()->getConstants();
  }

  /**
   * Values
   *
   * @return array
   */
  public static function values()
  {
    return array_values(static::all());
  }

  /**
   * Keys
   *
   * @return array
   */
  public static function keys()
  {
    return array_keys(static::all());
  }

  /**
   * Translated titles
   *
   * @return array
   */
  public static function titles($sort = false)
  {
    $class = get_called_class();
    if (!empty(self::$_titles[$class]) && null !== self::$_titles[$class]) return self::$_titles[$class];
    $titles = array();
    foreach (static::values() as $val)
    {
      if (!empty(static::$titles[$val]))
      {
        $titles[$val] = static::$titles[$val];
      }
      else
      {
        $titles[$val] = ucfirst(str_replace('_', ' ', $val));
      }
    }
    $titles = array_map(self::getTranslator(), $titles);
    if ($sort) asort($titles);
    return self::$_titles[$class] = $titles;
  }
  
  /**
   * Translated title
   *
   * @var string|int $value - on '' & null will return deafult value
   *
   * @return array
   */
  public static function title($value)
  {
    $titles = static::titles();
    if ('' === $value || null === $value) $value = static::defautVal();
    static::throwsInvalid($value);
    return $titles[$value];
  }

  /**
   * Check value
   *
   * @param string|int $value
   *
   * @return bool
   */
  public static function isValid($value)
  {
    return in_array($value, static::all(), false);
  }

  /**
   * Check value with exception on fail
   *
   * @param mixed $value
   *
   * @throws Exception if the value does not valid for the enum type
   *
   * @return void
   */
  public static function throwsInvalid($value)
  {
    if (!static::isValid($value))
    {
      throw new Exception('The enum type `' . get_called_class() . '` does not contains value `' .
                           var_export($value, true) . '`. Possible values are `' . static::implode('`, `') . '`');
    }
  }

  /**
   * Implode all values to the string sepatetag by $separator
   *
   * @param string $separator
   *
   * @return string
   */
  public static function implode($separator = ', ')
  {
    return implode($separator, static::values());
  }

  /**
   * Set translator callbec function
   *
   * @param callback $tranlator
   *
   * @return void
   */
  public static function setTranslator($tranlator)
  {
    self::$translator = $translator;
  }

  /**
   * Get translator function
   *
   * @return callbeck
   */
  public static function getTranslator()
  {
    if (false == self::$translator)
    {
      self::$translator = function ($title)
      {
        return $title;
      };
    }
    
    return self::$translator;
  }

  /**
   * Get reflaction class
   *
   * @return ReflectionClass
   */
  protected static function getRC()
  {
    return new ReflectionClass(get_called_class());
  }
  
  /**
   * Search in titles
   * 
   * @param string $needle
   *
   * @return array - of keys
   */
  public static function search($needle)
  {
    $return = array();
    foreach (static::titles() as $key => $val) {
      if (false !== stristr($val, $needle)) {
        $return[] = $key;
      }
    }
    return $return;
  }
  
  /**
   * Get default alues by default it is first element
   *
   * @return string
   */
  public static function defautVal()
  {
    $vals = (static::values());
    return array_shift($vals);
  }
}