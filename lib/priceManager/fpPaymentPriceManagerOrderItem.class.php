<?php

/**
 * Payment Manager Item. Process order item price
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 * 
 * @property $item fpPaymentOrderItem
 */
class fpPaymentPriceManagerOrderItem extends fpPaymentPriceManagerBaseItem
{

  /**
   * Constructor
   *
   * @param Product $item
   * @param int $quntity - No need
   */
  public function __construct($item, $quntity = 1)
  {
    if (!($item instanceof PluginfpPaymentOrderItemTable)) {
      throw new sfException('The "' . get_class($item) . '" must be instance of PluginfpPaymentOrderItemTable');
    }
    if (!$item->getTable()->hasTemplate('fpPaymentProduct')) {
      throw new sfException('The "' . get_class($item) . '" model model item must implement fpPaymentProduct behavior');
    }
    $this->item = $item;
    return $this;
  }
  
  /**
   * Get item price
   *
   * @return double
   */
  public function getPrice()
  {
    return $this->getQuntity() * $this->getItemPrice();
  }
  
  /**
   * Get item
   *
   * @return fpPaymentOrderItem
   */
  public function getItem()
  {
    return $this->item;
  }
  
	/**
	 * (non-PHPdoc)
	 * @see fpPaymentPriceManagerBaseItem::getItemPrice()
	 */
  public function getItemPrice()
  {
    return $this->getItem()->getPrice();
  }
  
	/**
	 * (non-PHPdoc)
	 * @see fpPaymentPriceManagerBaseItem::getQuntity()
	 */
  public function getQuntity()
  {
    return $this->getItem()->getQuantity();
  }
  
  /**
   * (non-PHPdoc)
   * @see fpPaymentPriceManagerBaseItem::getTaxValue()
   */
  public function getTaxValue()
  {
    return $this->getItem()->getTax();
  }
  
  /**
   * (non-PHPdoc)
   * @see fpPaymentPriceManagerBaseItem::getShippingValue()
   */
  public function getShippingValue()
  {
    return $this->getItem()->getShipping();
  }
}