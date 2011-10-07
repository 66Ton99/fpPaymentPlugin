<?php

/**
 * Enum class
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentEnum
{
  
  private static $instances = array();
  
  protected $objectName = null;

  /**
   * Pointer to the translator function
   *
   * @var callbeck
   */
  protected static $translator;
  
  /**
   * Get instance
   *
   * @param string $objectName
   *
   * @return fpPaymentEnum
   */
  public static function getInstance($objectName)
  {
    if (empty(self::$instances[$objectName])) {
      self::$instances[$objectName] = new static($objectName);
    }
    return self::$instances[$objectName];
  }
  
  /**
   * Constructor
   *
   * @param object $objectName
   *
   * @return void
   */
  protected function __construct($objectName)
  {
    $this->objectName = $objectName;
  }

  protected function excludeByPrefix($arr, $prefix)
  {
    if ($this->getRC()->isSubclassOf('sfDoctrineRecord')) {
      foreach ($arr as $key => $val) {
        if (substr($key, 0, strlen($prefix)) == $prefix) {
          unset($arr[$key]);
        }
      }
    }
    return $arr;
  }
  
  protected function leftWithPrefix($arr, $prefix)
  {
    if (empty($prefix)) return $arr;
    if ($this->getRC()->isSubclassOf('sfDoctrineRecord')) {
      foreach ($arr as $key => $val) {
        if (substr($key, 0, strlen($prefix)) != $prefix) {
          unset($arr[$key]);
        }
      }
    }
    return $arr;
  }
  
  /**
   * Get all constants
   *
   * @param string $prefix
   *
   * @return array
   */
  public function all($prefix = null)
  {
    return $this->leftWithPrefix($this->excludeByPrefix($this->getRC()->getConstants(), 'STATE_'), $prefix);
  }

  /**
   * Values
   *
   * @return array
   */
  public function values($prefix = null)
  {
    return array_values($this->all($prefix));
  }

  /**
   * Keys
   *
   * @return array
   */
  public function keys()
  {
    return array_keys($this->all());
  }

  /**
   * Translated titles
   *
   * @todo finish
   *
   * @return array
   */
//  public function titles($sort = false)
//  {
//    $class = get_called_class();
//    if (!empty(self::$_titles[$class]) && null !== self::$_titles[$class]) return self::$_titles[$class];
//    $titles = array();
//    foreach ($this->values() as $val)
//    {
//      if (!empty($this->$titles[$val]))
//      {
//        $titles[$val] = $this->$titles[$val];
//      }
//      else
//      {
//        $titles[$val] = ucfirst(str_replace('_', ' ', $val));
//      }
//    }
//    $titles = array_map(self::getTranslator(), $titles);
//    if ($sort) asort($titles);
//    return self::$_titles[$class] = $titles;
//  }
  
  /**
   * Translated title
   *
   * @var string|int $value - on '' & null will return deafult value
   * 
   * @todo finish
   *
   * @return array
   */
//  public function title($value)
//  {
//    $titles = $this->titles();
//    if ('' === $value || null === $value) $value = $this->defautVal();
//    $this->throwsInvalid($value);
//    return $titles[$value];
//  }

  /**
   * Check value
   *
   * @param string|int $value
   *
   * @return bool
   */
  public function isValid($value)
  {
    return in_array($value, $this->all(), false);
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
  public function throwsInvalid($value)
  {
    if (!$this->isValid($value))
    {
      throw new Exception('The enum type `' . get_called_class() . '` does not contains value `' .
                           var_export($value, true) . '`. Possible values are `' . $this->implode('`, `') . '`');
    }
  }

  /**
   * Implode all values to the string sepatetag by $separator
   *
   * @param string $separator
   *
   * @return string
   */
  public function implode($separator = ', ')
  {
    return implode($separator, $this->values());
  }

  /**
   * Set translator callbec function
   *
   * @param callback $tranlator
   *
   * @return void
   */
  public function setTranslator($tranlator)
  {
    self::$translator = $translator;
  }

  /**
   * Get translator function
   *
   * @return callbeck
   */
  public function getTranslator()
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
  protected function getRC()
  {
    return new ReflectionClass($this->objectName);
  }
  
  /**
   * Search in titles
   * 
   * @param string $needle
   *
   * @return array - of keys
   */
  public function search($needle)
  {
    $return = array();
    foreach ($this->titles() as $key => $val) {
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
  public function defautVal()
  {
    $vals = ($this->values());
    return array_shift($vals);
  }
}