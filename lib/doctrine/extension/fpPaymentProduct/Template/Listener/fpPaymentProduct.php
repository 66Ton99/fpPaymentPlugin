<?php

/**
 * Doctrine extension fpPaymentProduct listener
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class Doctrine_Template_Listener_fpPaymentProduct extends Doctrine_Record_Listener
{

  /**
   * Constructor
   *
   * @param array $options
   */
  public function __construct($options = array())
  {
    $this->_options = $options;
  }
  
/**
	 * 
   * 
   * @return bool
   */
  public function isFpPaymentProduct()
  {
    return true;
  }
}