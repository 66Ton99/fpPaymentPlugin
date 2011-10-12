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

	/**
   * Get billing profile
   *
   * @return fpPaymentCustomerProfile
   */
  public function getBillingProfile()
  {
    $profiles = $this->getInvoker()->getFpPaymentCustomerProfile();
    /* @var $profile UserProfile */
    foreach ($profiles as $profile)
    {
      if (fpPaymentCustomerProfileTypeEnum::DEF == $profile->getType())
      {
        return $profile;
      }
    }
    foreach ($profiles as $profile)
    {
      if (fpPaymentCustomerProfileTypeEnum::BILLING == $profile->getType())
      {
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
    /* @var $profile UserProfile */
    foreach ($profiles as $profile)
    {
      if (fpPaymentCustomerProfileTypeEnum::DEF == $profile->getType())
      {
        return $profile;
      }
    }
    foreach ($profiles as $profile)
    {
      if (fpPaymentCustomerProfileTypeEnum::SHIPPING == $profile->getType())
      {
        return $profile;
      }
    }
    return null;
  }
  
  /**
   * Get selected or entered profile of user
   *
   * @return fpPaymentCustomerProfile
   */
  public function getCusrrentProfile()
  {
    return $this->getBillingProfile();
  }
}

