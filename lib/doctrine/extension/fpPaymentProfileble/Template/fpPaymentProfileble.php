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

  /**
   * Return array of addresses
   * 
   * @param bool $isBilling
   *
   * @return array
   */
  public function getProfilesList($isBilling = true)
  {
    $profiles = $this->getInvoker()->getFpPaymentCustomerProfile();
    if (!count($profiles)) return array();
    $keys = array();
    $values = array();
    /* @var $profile fpPaymentCustomerProfile */
    foreach ($profiles as $profile) {
      if (($isBilling && $profile->getIsDefaultBilling()) ||
          (!$isBilling && $profile->getIsDefaultShipping()))
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
   * Check urrent profile
   *
   * @return bool
   */
  public function hasCurrentBillingProfile()
  {
    return sfContext::getInstance()
      ->getUser()
      ->hasAttribute('billing_rofile', sfConfig::get('fp_payment_profiles_ns', 'fpPaymentCurrentProfiles'));
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
      ->setAttribute('billing_rofile', $profile, sfConfig::get('fp_payment_profiles_ns', 'fpPaymentCurrentProfiles'));
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
    $this->currentBillingProfile = sfContext::getInstance()
                                       ->getUser()
                                       ->getAttribute('billing_rofile',
                                                      $this->getBillingProfile(),
                                                      sfConfig::get('fp_payment_profiles_ns', 'fpPaymentCurrentProfiles'));
    
    return $this->currentBillingProfile;
  }
  
	/**
   * Check urrent profile
   *
   * @return bool
   */
  public function hasCurrentShippingProfile()
  {
    return sfContext::getInstance()
      ->getUser()
      ->hasAttribute('shipping_rofile', sfConfig::get('fp_payment_profiles_ns', 'fpPaymentCurrentProfiles'));
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
      ->setAttribute('shipping_rofile', $profile, sfConfig::get('fp_payment_profiles_ns', 'fpPaymentCurrentProfiles'));
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
    $this->currentShippingProfile = sfContext::getInstance()
                                       ->getUser()
                                       ->getAttribute('shipping_rofile',
                                                      $this->getShippingProfile(),
                                                      sfConfig::get('fp_payment_profiles_ns', 'fpPaymentCurrentProfiles'));
    return $this->currentShippingProfile;
  }
}

