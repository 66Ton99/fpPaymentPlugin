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
  
  protected $shipping = null;

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
   * Clear items price
   *
   * @return double
   */
  public function getSubTotal()
  {
    $subTotal = 0.0;
    /* @var $item fpPaymentPriceManagerItem */
    foreach ($this->getItems() as $item) {
      $subTotal += $item->getItemPrice() * $item->getQuntity();
    }
    return $subTotal;
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
    return $this->getRoundedValue($sum + $this->getTaxValue() + $this->getShippingPrice());
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
   * Shipping
   *
   * @return fpPaymentShippingContext
   */
  public function getShipping()
  {
    $class = sfConfig::get('fp_payment_shipping_context_class_name', 'fpPaymentShippingContext');
    if ($class && class_exists($class) && empty($this->shipping)) {
      $this->shipping = new $class($this->getItems());
    }
    return $this->shipping;
  }
  
  /**
   * Get shipping price
   *
   * @return double
   */
  public function getShippingPrice()
  {
    return $this->getRoundedValue($this->getShipping()->getPrice()) ;
  }
}