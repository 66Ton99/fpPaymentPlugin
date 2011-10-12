<?php

/**
 * Checkout actions.
 *
 * @package    fpPayment
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentCheckoutActions extends sfActions
{
  
  /**
   * Fist step choose payment
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeIndex(sfWebRequest $request)
  {
    if (fpPaymentContext::getInstance()->getCart()->isEmpty()) {
      return $this->redirect('@fpPaymentPlugin_cart');
    }
    
    $paymentMethods = fpPaymentContext::getInstance()->getPaymentMethods();
    if (!count($paymentMethods)) {
      throw new sfException('No payment methods.');
    }
    if (1 == count($paymentMethods)) {
      $paymentMethod = array_pop($paymentMethods);
      $this->redirect('@fpPaymentPlugin_info?method=' . $paymentMethod);
    }
    
    $formClass = sfConfig::get('fp_payment_payment_method_class_form', 'fpPaymentMethodPluginForm');
    $this->form = new $formClass();
    $this->form
      ->getWidget('method')
      ->addOption('choices', $paymentMethods);
    $this->form
      ->getValidator('method')
      ->addOption('choices', array_keys($paymentMethods));
      
    if (sfRequest::POST == $request->getMethod()) {
      $this->form->bind($request->getParameter($this->form->getName()));
      $this->redirectIf($this->form->isValid(), '@fpPaymentPlugin_info?method=' . $this->form->getValue('method'));
    }
  }
  
  /**
   * Second step payment info
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeInfo(sfWebRequest $request)
  {
    if (fpPaymentContext::getInstance()->getCart()->isEmpty()) {
      return $this->redirect('@fpPaymentPlugin_cart');
    }
    $method = 'get' . $request->getParameter('method');
    fpPaymentContext::getInstance()->$method()->renderInfoPage($this, $request);
  }
  
  /**
   * Success step
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeSuccess(sfWebRequest $request)
  {
    
  }
  
	/**
   * Error step
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeError(sfWebRequest $request)
  {
    $method = 'get' . $request->getParameter('type');
    fpPaymentContext::getInstance()->$method()->renderErrorPage($this, $request);
  }

}