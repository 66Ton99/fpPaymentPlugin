<?php

/**
 * PluginfpPaymentCustomerProfile form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginfpPaymentCustomerProfileForm extends BasefpPaymentCustomerProfileForm
{
  
  /**
   * (non-PHPdoc)
   * @see sfForm::configure()
   */
  public function configure()
  {
    unset($this['customer_id'], $this['created_at'], $this['updated_at']);
    $this->setWidget('country', new sfWidgetFormI18nChoiceCountry());
    $this->setValidator('country', new sfValidatorI18nChoiceCountry());
  }
}
