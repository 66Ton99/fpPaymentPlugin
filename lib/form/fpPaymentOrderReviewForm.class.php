<?php

/**
 * fpPaymentOrderReviewForm
 * 
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentOrderReviewForm extends BaseForm
{
  
  /**
   * (non-PHPdoc)
   * @see sfForm::setup()
   */
  public function setup()
  {
    
    $widgetSchema = $this->getWidgetSchema();
    $widgetSchema->setNameFormat(get_class($this) . '[' . $widgetSchema->getNameFormat() . ']');
  }
}