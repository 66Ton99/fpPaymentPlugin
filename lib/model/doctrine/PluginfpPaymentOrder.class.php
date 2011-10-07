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
  const STATUTS_NEW        = 'new';
  const STATUTS_IN_PROCESS = 'in_process';
  const STATUTS_SUCCESS    = 'success';
  const STATUTS_CANCELLED  = 'cancelled';
  const STATUTS_FAIL       = 'fail';
  
  /**
   * Get all statuses
   *
   * @return array
   */
  public static function getStatusEnums()
  {
    return fpPaymentEnum::getInstance(get_called_class())->values('STATUTS_');
  }
}