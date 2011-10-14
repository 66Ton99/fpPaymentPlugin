<?php

/**
 * Doctrine extension fpPaymentProfileble
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class Doctrine_Template_fpPaymentProfileble extends Doctrine_Template
{
  
  protected $currentBillingProfile;
  
  protected $currentShippingProfile;

  /**
   * (non-PHPdoc)
   * @see Doctrine_Template::setUp()
   */
  public function setUp()
  {
    $this->currentBillingProfile = sfContext::getInstance()
                                     ->getUser()
                                     ->getAttribute('billing_rofile',null, sfConfig::get('fp_payment_profiles_ns'));
    $this->currentShippingProfile = sfContext::getInstance()
                                     ->getUser()
                                     ->getAttribute('shipping_rofile',null, sfConfig::get('fp_payment_profiles_ns'));
  }
  
  
  /**
   * Get billing profile
   *
   * @return fpPaymentCustomerProfile
   */
  public function getBillingProfile()
  {
    $profiles = $this->getInvoker()->getFpPaymentCustomerProfile();
    /* @var $profile fpPaymentCustomerProfile */
    foreach ($profiles as $profile) {
      if ($profile->getIsDefaultBilling()) {
        return $profile;
      }
    }
    return null;
  }

  /**
   * Get shipping profile
   *
   * @return fpPaymentCustomerProfile
   */
  public function getShippingProfile()
  {
    $profiles = $this->getInvoker()->getFpPaymentCustomerProfile();
    /* @var $profile fpPaymentCustomerProfile */
    foreach ($profiles as $profile) {
      if ($profile->getIsDefaultShipping()) {
        return $profile;
      }
    }
    return null;
  }

  public function getProfilesList($isBilling = true)
  {
    $profiles = $this->getInvoker()->getFpPaymentCustomerProfile();
    $profilesList = array();
    /* @var $profile fpPaymentCustomerProfile */
    foreach ($profiles as $profile) {
      $keys = array();
      $values = array();
      if (($isBilling && 1 == $profile->getIsDefaultBilling()) ||
          (!$isBilling && 1 == $profile->getIsDefaultShipping()))
      {
        array_unshift($keys, $profile->getId());
        array_unshift($values, $profile->getAddresString());
      } else {
        array_push($keys, $profile->getId());
        array_push($values, $profile->getAddresString());
      }
    }
    return array_combine($keys, $values);;
  }

  /**
   * Set current selected billing profile
   *
   * @param fpPaymentCustomerProfile $profile
   *
   * @return sfGuardUser
   */
  public function setCurrentBillingProfile(fpPaymentCustomerProfile $profile)
  {
    sfContext::getInstance()
      ->getUser()
      ->setAttribute('billing_rofile', $profile, sfConfig::get('fp_payment_profiles_ns'));
    $this->currentBillingProfile = $profile;
    return $this->getInvoker();
  }
  
  /**
   * Get selected or entered billing profile of user
   *
   * @return fpPaymentCustomerProfile
   */
  public function getCurrentBillingProfile()
  {
    if (!empty($this->currentBillingProfile)) return $this->getBillingProfile();
    return $this->currentBillingProfile;
  }
  
	/**
   * Set current selected billing profile
   *
   * @param fpPaymentCustomerProfile $profile
   *
   * @return sfGuardUser
   */
  public function setCurrentShippingProfile(fpPaymentCustomerProfile $profile)
  {
    sfContext::getInstance()
      ->getUser()
      ->setAttribute('shipping_rofile', $profile, sfConfig::get('fp_payment_profiles_ns'));
    $this->currentShippingProfile = $profile;
    return $this->getInvoker();
  }
  
  /**
   * Get selected or entered billing profile of user
   *
   * @return fpPaymentCustomerProfile
   */
  public function getCurrentShippingProfile()
  {
    if (!empty($this->currentShippingProfile)) return $this->getShippingProfile();
    return $this->currentShippingProfile;
  }
}

