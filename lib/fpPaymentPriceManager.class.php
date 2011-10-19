<?php

/**
 * Payment Manager. Process price
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentPriceManager
{
  
  protected $items = array();
  
  protected $promotion = null;
  
  protected $customer = null;

  /**
   * Constructor
   *
   * @param sfGuardUser $customer
   */
  public function __construct($customer)
  {
    $this->customer = $customer;
  }
  
  /**
   * Add one item
   *
   * @param fpPaymentPriceManagerItem $item
   *
   * @return fpPaymentPriceManager
   */
  public function addItem($item)
  {
    $this->items[] = $item;
    return $this;
  }
  
  /**
   * Get items list
   *
   * @return array
   */
  public function getItems()
  {
    return $this->items;
  }
  
  /**
   * 
   *
   * @param double $value
   *
   * @return double
   */
  protected function getRoundedValue($value)
  {
    return round($value, 2);
  }
  
  /**
   * Get sum
   *
   * @return double
   */
  public function getSum()
  {
    $sum = 0.0;
    /* @var $item fpPaymentPriceManagerItem */
    foreach ($this->getItems() as $item) {
      $sum += $item->getPrice();
    }
    return $this->getRoundedValue($sum + $this->getTaxValue());
  }
  
  /** 
   * Get tax value
   *
   * @param bool $rounded
   *
   * @return double
   */
  public function getTaxValue()
  {
    $taxes = 0.00;
    /* @var $item fpPaymentPriceManagerItem */
    foreach ($this->getItems() as $item) {
      $taxes += $item->getTaxValue();
    }
    return $this->getRoundedValue($taxes);
  }
  
  /**
   * Get shipping price
   *
   * @return double
   */
  public function getShippingPrice()
  {
    $class = sfConfig::get('fp_payment_shipping_context_class_name', 'fpPaymentShippingContext');
    if ($class && class_exists($class)) {
      $shipping = new $class($this->getItems());
      return $this->getRoundedValue($shipping->getPrice());
    }
    return 0.00;
  }
}