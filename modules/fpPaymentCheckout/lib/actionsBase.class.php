<?php

/**
 * Checkout actions.
 *
 * @package    fpPayment
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentCheckoutActionsBase extends sfActions
{
  
  /**
   * Check is cart empty or not
   *
   * @return void
   */
  protected function checkCart()
  {
    if (fpPaymentContext::getInstance()->getCart()->isEmpty()) $this->redirect('@fpPaymentPlugin_cart');
  } 
  
  /**
   * Must do somthing but by default redirect to firststep
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeIndex(sfWebRequest $request)
  {
    if (class_exists('fpPaymentTaxContext')) {
      $this->redirect(sfConfig::get('fp_payment_checkout_billing_step', '@fpPaymentPlugin_billing'));
    } else {
      $this->redirect(sfConfig::get('fp_payment_checkout_first_step', '@fpPaymentPlugin_method'));
    }
  }
  
  /**
   * Billing address
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeBilling(sfWebRequest $request)
  {
    $this->checkCart();
    $formClass = sfConfig::get('fp_payment_select_profile_class_form', 'fpPaymentSelectProfileForm');
    $this->isBilling = true;
    $form = new $formClass(array(), array('isBilling' => $this->isBilling));
    if (sfRequest::POST == $request->getMethod()) {
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid()) {
        if ('new' == $form->getValue('profile')) {
          return $this->redirect('@fpPaymentPlugin_profile?is_billing=1');
        }
        $customer = fpPaymentContext::getInstance()->getCustomer();
        $customer->setCurrentBillingProfile(fpPaymentCustomerProfileTable::getInstance()->findOneById($form->getValue('profile')));
        if (sfConfig::get('fp_payment_shipping_context_class_name')) {
          $this->redirect('@fpPaymentPlugin_shipping');
        } else {
          $this->redirect('@fpPaymentPlugin_method');
        }
      }
    }
  }
  
	/**
   * Profile
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeProfile(sfWebRequest $request)
  {
    $this->checkCart();
    $form = new fpPaymentCustomerProfileForm();
    $form->addSaveChckbox();
    $form->setDefault('customer_id', fpPaymentContext::getInstance()->getCustomer()->getId());
    if ($request->isMethod(sfRequest::POST)) {
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid()) {
        if ($form->getValue('save')) {
          $form->save();
        } else {
          $form->updateObject($form->getValues());
        }
        if ($request->getParameter('is_billing')) {
          fpPaymentContext::getInstance()->getCustomer()->setCurrentBillingProfile($form->getObject());
          if (sfConfig::get('fp_payment_shipping_context_class_name')) {
            $this->redirect('@fpPaymentPlugin_shipping');
          } else {
            $this->redirect('@fpPaymentPlugin_method');
          }
        } else {
          fpPaymentContext::getInstance()->getCustomer()->setCurrentShippingProfile($form->getObject());
          $this->redirect('@fpPaymentPlugin_shippingMethod');
        }
      }
    }
  }
  
  /**
   * Shipping address
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeShipping(sfWebRequest $request)
  {
    $this->checkCart();
    $formClass = sfConfig::get('fp_payment_select_profile_class_form', 'fpPaymentSelectProfileForm');
    $this->isBilling = false;
    $form = new $formClass(array(), array('isBilling' => $this->isBilling));
    if (sfRequest::POST == $request->getMethod()) {
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid()) {
        if ('new' == $form->getValue('profile')) {
          return $this->redirect('@fpPaymentPlugin_profile?is_billing=0');
        }
        $customer = fpPaymentContext::getInstance()->getCustomer();
        $customer->setCurrentShippingProfile(fpPaymentCustomerProfileTable::getInstance()->findOneById($form->getValue('profile')));
        
        $this->redirect('@fpPaymentPlugin_shippingMethod');
      }
    }
  }
  
  /**
   * Select shipping method
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeShippingMethod(sfWebRequest $request)
  {
    $this->checkCart();
    $this->forward('fpPaymentShipping', 'index');
  }
  
  /**
   * Chose payment method
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeMethod(sfWebRequest $request)
  {
    $this->checkCart();
    $paymentMethods = fpPaymentContext::getInstance()->getPaymentMethods();
    if (1 == count($paymentMethods)) {
      $paymentMethod = array_pop($paymentMethods);
      $this->getContext()->getUser()->setAttribute('paymentMethod',
                                                   $paymentMethod,
                                                   sfConfig::get('fp_payment_main_ns', 'fpPaymentNS'));
      $this->redirect('@fpPaymentPlugin_info');
    }
    $formClass = sfConfig::get('fp_payment_payment_method_class_form', 'fpPaymentMethodPluginForm');
    $form = new $formClass();
    if (sfRequest::POST == $request->getMethod()) {
      $form->bind($request->getParameter($form->getName()));
      $this->getContext()->getUser()->setAttribute('paymentMethod',
                                                   $form->getValue('method'),
                                                   sfConfig::get('fp_payment_main_ns', 'fpPaymentNS'));
      $this->redirectIf($form->isValid(), '@fpPaymentPlugin_info');
    }
  }
  
  /**
   * Payment info
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeInfo(sfWebRequest $request)
  {
    $this->checkCart();
    $method = 'get' . $this->getContext()->getUser()->getAttribute('paymentMethod',
                                                                   null,
                                                                   sfConfig::get('fp_payment_main_ns', 'fpPaymentNS'));
    fpPaymentContext::getInstance()->$method()->renderInfoPage($this, $request);
  }
  
  /**
   * Order review
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeOrderReview(sfWebRequest $request)
  {
    $this->checkCart();
    $formClass = sfConfig::get('fp_payment_order_class_form', 'fpPaymentOrderReviewForm');
    $form = new $formClass();
    if (sfRequest::POST == $request->getMethod()) {
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid()) {
        $paymentNs = sfConfig::get('fp_payment_main_ns', 'fpPaymentNS');
        /* @var $attrHolder sfNamespacedParameterHolder */
        $attrHolder = $this->getContext()->getUser()->getAttributeHolder();
        $method = $attrHolder->get('paymentMethod', null,  $paymentNs);
        $request->setParameter('method', $method);
        $getMethod = 'get' . $method;
        $values = $attrHolder->get('paymentValues', array(), $paymentNs);
        fpPaymentContext::getInstance()->$getMethod()->doProcess($values);
        $attrHolder->removeNamespace($paymentNs);
        fpPaymentContext::getInstance()->$getMethod()->renderSuccessPage($this, $request);
      }
    }
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
    $method = 'get' . $request->getParameter('method');
    fpPaymentContext::getInstance()->$method()->renderErrorPage($this, $request);
  }

}