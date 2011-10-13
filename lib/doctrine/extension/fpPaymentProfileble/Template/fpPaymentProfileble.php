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
      $profileItem = array($profile->getId() => $profile->getAddresString());
      if (($isBilling && 1 == $profile->getIsDefaultBilling()) ||
          (!$isBilling && 1 == $profile->getIsDefaultShipping()))
      {
        $profilesList = array_merge($profileItem, $profilesList);
      } else {
        $profilesList = array_merge($profilesList, $profileItem);
      }
    }
    return $profilesList;
  }

  /**
   * Get selected or entered profile of user
   *
   * @return fpPaymentCustomerProfile
   */
  public function getCurrentProfile()
  {
    return $this->getBillingProfile();
  }
}

