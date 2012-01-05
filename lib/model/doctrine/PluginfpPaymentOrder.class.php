<?php

/**
 * PluginfpPaymentOrder
 * 
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
abstract class PluginfpPaymentOrder extends BasefpPaymentOrder
{
  
  /**
   * Return title of status
   *
   * @return string
   */
  public function getStatusTitle()
  {
    return fpPaymentOrderStatusEnum::title($this->getStatus());
  }
}