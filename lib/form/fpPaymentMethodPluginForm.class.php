<?php

/**
 * fpPaymentMethodPluginForm
 * 
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentMethodPluginForm extends BaseForm
{
  
  /**
   * (non-PHPdoc)
   * @see sfForm::setup()
   */
  public function setup()
  {
    $this->setWidgets(array(
      'method' => new sfWidgetFormSelect(array('choices' => array(),
                                               'label' => 'Payment method'))
    ));
    
    $this->setValidators(array(
      'method' => new sfValidatorChoice(array('required' => true, 'choices' => array())),
    ));
    $widgetSchema = $this->getWidgetSchema();
    $widgetSchema->setNameFormat(get_class($this) . '[' . $widgetSchema->getNameFormat() . ']');
  }
}