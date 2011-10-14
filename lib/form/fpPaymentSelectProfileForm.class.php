<?php

/**
 * fpPaymentSelectProfileForm
 * 
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentSelectProfileForm extends BaseForm
{
  
  /**
   * (non-PHPdoc)
   * @see sfForm::setup()
   */
  public function setup()
  {
    $profiles = fpPaymentContext::getInstance()->getCustomer()->getProfilesList($this->getOption('isBilling', true));
    $profiles['new'] = 'New address';
    $this->setWidgets(array(
      'profile' => new sfWidgetFormSelect(array('choices' => $profiles,
                                                'label' => $this->getOption('isBilling', true)?
                                                							'Billing address':
                                                							'Shipping address'))
    ));
    
    $this->setValidators(array(
      'profile' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($profiles))),
    ));
    $widgetSchema = $this->getWidgetSchema();
    $widgetSchema->setNameFormat(get_class($this) . '[' . $widgetSchema->getNameFormat() . ']');
  }
}