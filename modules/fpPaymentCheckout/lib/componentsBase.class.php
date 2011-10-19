<?php

/**
 * Checkout components
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentCheckoutComponentsBase extends sfComponents
{
  
  /**
   * Validate form
   *
   * @return bool
   */
  protected function validateForm($form)
  {
    $request = $this->getRequest();
    if (sfRequest::POST == $request->getMethod()) {
      $form->bind($request->getParameter($form->getName()));
      return $form->isValid();
    }
  }

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
    $this->validateForm($this->form);
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
    $this->validateForm($this->form);
  }
  
  /**
   * Enter or create profile
   *
   * @return void
   */
  public function executeProfile()
  {
    $profile = fpPaymentContext::getInstance()->getCustomer()->getCurrentBillingProfile();
    if (!empty($profile) && $profile->getId()) {
      $profile = $profile->copy();
    }
    $this->form = new fpPaymentCustomerProfileForm($profile);
    $this->form->addSaveChckbox();
    $this->form->setDefault('customer_id', fpPaymentContext::getInstance()->getCustomer()->getId());
    $this->validateForm($this->form);
  }
}