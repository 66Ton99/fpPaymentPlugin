<?php

/**
 * fpPayment context
 * 
 * @method fpPaymentAuthorizeContext getAuthorize()
 * @method fpPaymentPayPalContext getPayPal()
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentContext
{
  
  protected $user;
  
  protected $cart;
  
  protected $paymentMethodsInstances = array();
  
  protected $paymentMethods = array();
  
  /**
   * Object instance
   *
   * @var fpPaymentContext
   */
  protected static $instance;
  
  /**
   * Order model
   *
   * @var fpPaymentOrder
   */
  protected $orderModel;
  
  /**
   * Return singleton
   *
   * @return fpPaymentContext
   */
  public static function getInstance()
  {
    if (empty(static::$instance)) {
      static::$instance = new self();
    }
    return static::$instance;
  }
  
  /**
   * Get dispatcher
   *
   * @return sfEventDispatcher
   */
  public function getDispatcher()
  {
    return sfContext::getInstance()->getEventDispatcher();
  }
  
  /**
   * Constructor
   *
   * @return void
   */
  protected function __construct()
  {
    // Initialize cart
    $this->getCart();
  }
  
  /**
   * getPaymentMethods
   *
   * @return array
   */
  public function getPaymentMethods()
  {
    return $this->paymentMethods;
  }
  
  /**
   * getPaymentMethod
   *
   * @param array $arg - key: class, val: name
   *
   * @return fpPaymentContext
   */
  public function addPaymentMethod($arg)
  {
    $this->paymentMethods = array_merge($this->paymentMethods, $arg);
    return $this;
  }
  
  /**
   * Check is exist provided method
   *
   * @param string $method
   *
   * @return bool
   */
  public function hasPaymentMethod($method)
  {
    return isset($this->paymentMethods[$method]);
  }
  
  /**
   * 
   *
   * @param string $method
   * @param arraty $params
   * @throws sfException
   *
   * @return fpPaymentMethodContext
   */
  public function __call($method, $params)
  {
    if ('get' != substr($method, 0, 3)) {
       throw new sfException("Wrong getter '{$method}'");
    }
    // Strip get
    $method = substr($method, 3);
    if (!$this->hasPaymentMethod($method)) {
      throw new sfException(sprintf('Payment method \'%s\' does not found in: %s',
                                    $method,
                                    print_r($this->paymentMethods, true)));
    }
    if (!isset($this->paymentMethodsInstances[$method])) {
      $class = sfConfig::get('fp_payment_payments_context_prefix', 'fpPayment') . $method . 'Context';
      $this->paymentMethodsInstances[$method] = new $class();
    }
    return $this->paymentMethodsInstances[$method];
  }
  
  /**
   * setOrderModel
   *
   * @param fpPaymentOrder $model
   *
   * @return fpPaymentMethodContext
   */
  public function setOrderModel(fpPaymentOrder $model)
  {
    $this->orderModel = $model;
    return $this;
  }
  
  /**
   * getOrderModel
   *
   * @return fpPaymentOrder
   */
  public function getOrderModel()
  {
    return $this->orderModel;
  }
  
  /**
   * Get cart item holder
   *
   * @return fpPaymentCartContext
   */
  public function getCart()
  {
    if (empty($this->cart)) {
      $this->cart = fpPaymentFunctions::getObjFromConfig('fp_payment_callback_cart_item_holder',
                                                         'fpPaymentCartContext::getInstance');
    }
    return $this->cart;
  }
  
  /**
   * Get user
   *
   * @return sfGuardUser
   */
  public function getUser()
  {
    if (empty($this->user)) {
      $this->user = fpPaymentFunctions::getObjFromConfig('fp_payment_callback_user',
                                                         array('function' => 'sfContext::getInstance',
                                                               'parameters' => array(),
                                                               'subFunctions' => array('getUser', 'getGuardUser')));
    } 
    return $this->user;
  }
}
