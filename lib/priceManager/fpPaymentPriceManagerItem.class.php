<?php

/**
 * Payment Manager Item. Process item price
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentPriceManagerItem extends fpPaymentPriceManagerBaseItem
{
  
  /**
   * Constructor
   *
   * @param Product $item
   * @param int $quntity
   */
  public function __construct($item, $quntity = 1)
  {
    if (!($item instanceof sfDoctrineRecord)) {
      throw new sfException('The "' . get_class($item) . '" must be model');
    }
    if (!$item->getTable()->hasTemplate('fpPaymentProduct')) {
      throw new sfException('The "' . get_class($item) . '" model model item must implement fpPaymentProduct behavior');
    }
    
    $this->item = $item;
    
    if (1 > $quntity) {
      throw new sfException('The quntity must be more then 0');
    }
    $this->quntity = $quntity;
  }
  
  /**
   * (non-PHPdoc)
   * @see fpPaymentPriceManagerBaseItem::getPrice()
   */
  public function getPrice()
  {
    return $this->getQuntity() * $this->getItemPrice();
  }
  
  /**
   * (non-PHPdoc)
   * @see fpPaymentPriceManagerBaseItem::getItem()
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
    return $this->quntity;
  }
  
  /**
   * (non-PHPdoc)
   * @see fpPaymentPriceManagerBaseItem::getTaxValue()
   */
  public function getTaxValue()
  {
    if ($this->getItem()->getTable()->hasTemplate('fpPaymentTaxable')) {
      return $this->getItem()->getTaxValue($this->getQuntity());
    } else {
      return 0.00;
    }
  }
}