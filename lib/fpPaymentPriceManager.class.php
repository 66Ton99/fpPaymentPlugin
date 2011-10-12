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
    return round($sum + $this->getTaxValue(), 2);
  }
  
  /**
   * Get tax object
   *
   * @return
   */
  public function getTax()
  {
    return $this->tax;
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
    return round($taxes, 2);
  }
}