<?php

/**
 * Payment Manager Item. Process Base item price
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
abstract class fpPaymentPriceManagerBaseItem
{
  
  protected $priceManager;
  
  protected $item;
  
  protected $quntity = 1;

  /**
   * Constructor
   *
   * @param object $item
   * @param int $quntity
   */
  abstract public function __construct($item, $quntity = 1);
  
  /**
   * Get item price
   *
   * @return double
   */
  abstract public function getPrice();
  
  /**
   * Get item
   *
   * @return object
   */
  abstract public function getItem();
  
	/**
   * Get price of item
   *
   * @return double
   */
  abstract public function getItemPrice();
  
	/**
   * Get items quantity
   *
   * @return int
   */
  abstract public function getQuntity();
  
  /**
   * Get product (item) tax
   *
   * @return double
   */
  abstract public function getTaxValue();
}