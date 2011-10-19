<?php

/**
 * Payment Manager Item. Process item price
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentPriceManagerItem
{
  
  protected $priceManager;
  
  protected $item;
  
  protected $quntity = 1;

  /**
   * Constructor
   *
   * @param fpPaymentPriceManager $priceManager
   * @param Product $item
   * @param int $quntity
   */
  public function __construct(fpPaymentPriceManager $priceManager, sfDoctrineRecord $item, $quntity = 1)
  {
    $this->priceManager = $priceManager;
    
    if (!$item->getTable()->hasTemplate('fpPaymentProduct')) {
      throw new sfException('The "' . get_class($item) . '" model model item must implement fpPaymentProduct behavior');
    }
    
    $this->item = $item;
    
    if (1 > $quntity) {
      throw new sfException('The quntity must be more then 0');
    }
    $this->quntity = $quntity;
    $this->getPriceManager()->addItem($this);
    return $this;
  }
  
  /**
   * Get Price Manager
   *
   * @return fpPaymentPriceManager
   */
  protected function getPriceManager()
  {
    return $this->priceManager;
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
   * @return Product
   */
  public function getItem()
  {
    return $this->item;
  }
  
	/**
   * Get price of item
   *
   * @return double
   */
  public function getItemPrice()
  {
    return $this->getItem()->getPrice();
  }
  
	/**
   * Get items quantity
   *
   * @return int
   */
  public function getQuntity()
  {
    return $this->quntity;
  }
  
  /**
   * Get product (item) tax
   *
   * @return double
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