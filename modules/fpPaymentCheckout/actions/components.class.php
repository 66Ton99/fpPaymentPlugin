<?php

/**
 * Checkout components
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentCheckoutComponents extends sfComponents
{

  /**
   * Method
   * 
   * @throws sfException
   * 
   * @return void
   */
  public function executeMethod()
  {
    $paymentMethods = fpPaymentContext::getInstance()->getPaymentMethods();
    if (!count($paymentMethods)) {
      throw new sfException('No payment methods.');
    }
    if (1 == count($paymentMethods)) {
      return sfView::NONE;
    }
    
    $formClass = sfConfig::get('fp_payment_payment_method_class_form', 'fpPaymentMethodPluginForm');
    $this->form = new $formClass();
  }
  
  /**
   * Builing
   *
   * @return void
   */
  public function executeBilling()
  {
    $formClass = sfConfig::get('fp_payment_select_profile_class_form', 'fpPaymentSelectProfileForm');
    $this->form = new $formClass();
  }
}
