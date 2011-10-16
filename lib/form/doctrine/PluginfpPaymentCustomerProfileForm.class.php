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
    unset($this['created_at'], $this['updated_at']);
    $this->setWidget('customer_id', new sfWidgetFormInputHidden());
    $this->setWidget('country', new sfWidgetFormI18nChoiceCountry());
    $this->setValidator('country', new sfValidatorI18nChoiceCountry());
  }
  
  public function addSaveChckbox()
  {
    $this->setWidget('save', new sfWidgetFormInputCheckbox());
    $this->setValidator('save', new sfValidatorBoolean());
  }
}
